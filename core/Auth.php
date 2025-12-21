<?php

declare(strict_types=1);

namespace Core;

/**
 * Zed CMS Enhanced Authentication Handler
 * 
 * Security Features:
 * - Brute-force throttling (5 attempts, 10 min lockout)
 * - Session fixation prevention (regenerate_id on login)
 * - Remember Me with secure hashed tokens
 * - last_login tracking
 * 
 * Superior to WordPress default authentication.
 */
final class Auth
{
    /**
     * Cached current user data.
     */
    private static ?array $currentUser = null;

    /**
     * Session key for storing user ID.
     */
    private const SESSION_KEY = 'zed_user_id';
    
    /**
     * Cookie name for remember me token.
     */
    private const REMEMBER_COOKIE = 'zed_remember';
    
    /**
     * Remember me duration (30 days in seconds).
     */
    private const REMEMBER_DURATION = 30 * 24 * 60 * 60;
    
    /**
     * Max failed attempts before lockout.
     */
    private const MAX_ATTEMPTS = 5;
    
    /**
     * Lockout duration in minutes.
     */
    private const LOCKOUT_MINUTES = 10;

    /**
     * Ensure session is started.
     */
    private static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Secure session settings
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            session_start();
        }
    }
    
    /**
     * Ensure the users table has the required security columns.
     * Self-healing: adds columns if missing.
     */
    private static function ensureSecurityColumns(): void
    {
        static $checked = false;
        if ($checked) return;
        
        try {
            $db = Database::getInstance();
            
            // Check and add columns if needed
            $columns = ['remember_token', 'last_login', 'failed_attempts', 'locked_until'];
            
            foreach ($columns as $col) {
                try {
                    $db->queryOne("SELECT {$col} FROM users LIMIT 1");
                } catch (\PDOException $e) {
                    if (str_contains($e->getMessage(), 'Unknown column')) {
                        match($col) {
                            'remember_token' => $db->query("ALTER TABLE users ADD COLUMN remember_token VARCHAR(64) NULL"),
                            'last_login' => $db->query("ALTER TABLE users ADD COLUMN last_login DATETIME NULL"),
                            'failed_attempts' => $db->query("ALTER TABLE users ADD COLUMN failed_attempts INT DEFAULT 0"),
                            'locked_until' => $db->query("ALTER TABLE users ADD COLUMN locked_until DATETIME NULL"),
                        };
                    }
                }
            }
            
            $checked = true;
        } catch (\Exception $e) {
            // Silently fail - columns may already exist
        }
    }

    /**
     * Attempt to log in a user with security features.
     *
     * @param string $email    User's email address.
     * @param string $password User's password (plain text).
     * @param bool   $remember Whether to set remember me cookie.
     * @return array{success: bool, error?: string} Login result.
     */
    public static function login(string $email, string $password, bool $remember = false): array
    {
        self::ensureSession();
        self::ensureSecurityColumns();

        try {
            $db = Database::getInstance();
            
            // Find user by email
            $user = $db->queryOne(
                "SELECT id, email, password_hash, role, failed_attempts, locked_until FROM users WHERE email = :email LIMIT 1",
                ['email' => $email]
            );

            // User not found
            if (!$user) {
                return ['success' => false, 'error' => 'Invalid email or password.'];
            }
            
            // =========================================
            // STEP 1: Throttling - Check if locked out
            // =========================================
            if (!empty($user['locked_until'])) {
                $lockedUntil = strtotime($user['locked_until']);
                if ($lockedUntil > time()) {
                    $remainingMins = ceil(($lockedUntil - time()) / 60);
                    return [
                        'success' => false, 
                        'error' => "Too many failed attempts. Please wait {$remainingMins} minute(s).",
                        'locked' => true,
                        'locked_until' => $user['locked_until']
                    ];
                } else {
                    // Lockout expired, reset
                    $db->query(
                        "UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = :id",
                        ['id' => $user['id']]
                    );
                    $user['failed_attempts'] = 0;
                }
            }
            
            // =========================================
            // STEP 2: Verify password
            // =========================================
            if (!password_verify($password, $user['password_hash'])) {
                // Increment failed attempts
                $attempts = (int)($user['failed_attempts'] ?? 0) + 1;
                
                $updateData = ['id' => $user['id'], 'attempts' => $attempts];
                $sql = "UPDATE users SET failed_attempts = :attempts";
                
                // Lock account if max attempts exceeded
                if ($attempts >= self::MAX_ATTEMPTS) {
                    $lockUntil = date('Y-m-d H:i:s', strtotime('+' . self::LOCKOUT_MINUTES . ' minutes'));
                    $sql .= ", locked_until = :lock_until";
                    $updateData['lock_until'] = $lockUntil;
                }
                
                $sql .= " WHERE id = :id";
                $db->query($sql, $updateData);
                
                $remaining = self::MAX_ATTEMPTS - $attempts;
                if ($remaining > 0) {
                    return [
                        'success' => false, 
                        'error' => "Invalid password. {$remaining} attempt(s) remaining."
                    ];
                } else {
                    return [
                        'success' => false, 
                        'error' => "Account locked for " . self::LOCKOUT_MINUTES . " minutes due to too many failed attempts."
                    ];
                }
            }
            
            // =========================================
            // STEP 3: Session - Regenerate ID (CRITICAL)
            // =========================================
            session_regenerate_id(true);
            
            // Reset failed attempts and update last_login
            $db->query(
                "UPDATE users SET failed_attempts = 0, locked_until = NULL, last_login = NOW() WHERE id = :id",
                ['id' => $user['id']]
            );
            
            // Set session
            $_SESSION[self::SESSION_KEY] = (int) $user['id'];
            self::$currentUser = $user;
            
            // =========================================
            // STEP 4: Remember Me (if requested)
            // =========================================
            if ($remember) {
                self::setRememberToken((int) $user['id']);
            }

            return ['success' => true];

        } catch (\Exception $e) {
            error_log('Auth::login error: ' . $e->getMessage());
            return ['success' => false, 'error' => 'An error occurred. Please try again.'];
        }
    }
    
    /**
     * Generate and store remember me token.
     */
    private static function setRememberToken(int $userId): void
    {
        try {
            // Generate secure random token
            $token = bin2hex(random_bytes(32)); // 64 chars
            
            // Hash token for database storage (like password)
            $hashedToken = hash('sha256', $token);
            
            // Store hashed token in DB
            $db = Database::getInstance();
            $db->query(
                "UPDATE users SET remember_token = :token WHERE id = :id",
                ['token' => $hashedToken, 'id' => $userId]
            );
            
            // Set cookie with raw token (HttpOnly, Secure if HTTPS)
            $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
            $expiry = time() + self::REMEMBER_DURATION;
            
            setcookie(
                self::REMEMBER_COOKIE,
                $userId . ':' . $token,
                [
                    'expires' => $expiry,
                    'path' => '/',
                    'secure' => $secure,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]
            );
            
        } catch (\Exception $e) {
            error_log('Auth::setRememberToken error: ' . $e->getMessage());
        }
    }
    
    /**
     * Check remember me cookie and auto-login if valid.
     * Should be called on app init (before any auth checks).
     */
    public static function checkRememberCookie(): bool
    {
        self::ensureSession();
        
        // Already logged in via session
        if (isset($_SESSION[self::SESSION_KEY]) && $_SESSION[self::SESSION_KEY] > 0) {
            return true;
        }
        
        // Check for remember cookie
        if (!isset($_COOKIE[self::REMEMBER_COOKIE])) {
            return false;
        }
        
        try {
            self::ensureSecurityColumns();
            
            $cookieValue = $_COOKIE[self::REMEMBER_COOKIE];
            $parts = explode(':', $cookieValue, 2);
            
            if (count($parts) !== 2) {
                self::clearRememberCookie();
                return false;
            }
            
            [$userId, $token] = $parts;
            $userId = (int) $userId;
            
            if ($userId <= 0 || empty($token)) {
                self::clearRememberCookie();
                return false;
            }
            
            $db = Database::getInstance();
            $user = $db->queryOne(
                "SELECT id, email, role, remember_token FROM users WHERE id = :id LIMIT 1",
                ['id' => $userId]
            );
            
            if (!$user || empty($user['remember_token'])) {
                self::clearRememberCookie();
                return false;
            }
            
            // Verify token (compare hashes)
            $hashedToken = hash('sha256', $token);
            if (!hash_equals($user['remember_token'], $hashedToken)) {
                self::clearRememberCookie();
                return false;
            }
            
            // Valid token - log user in
            session_regenerate_id(true);
            $_SESSION[self::SESSION_KEY] = $userId;
            self::$currentUser = $user;
            
            // Update last_login
            $db->query(
                "UPDATE users SET last_login = NOW() WHERE id = :id",
                ['id' => $userId]
            );
            
            // Rotate token for security (optional but recommended)
            self::setRememberToken($userId);
            
            return true;
            
        } catch (\Exception $e) {
            error_log('Auth::checkRememberCookie error: ' . $e->getMessage());
            self::clearRememberCookie();
            return false;
        }
    }
    
    /**
     * Clear the remember me cookie.
     */
    private static function clearRememberCookie(): void
    {
        if (isset($_COOKIE[self::REMEMBER_COOKIE])) {
            setcookie(
                self::REMEMBER_COOKIE,
                '',
                [
                    'expires' => time() - 3600,
                    'path' => '/',
                    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]
            );
            unset($_COOKIE[self::REMEMBER_COOKIE]);
        }
    }

    /**
     * Log out the current user.
     *
     * @return void
     */
    public static function logout(): void
    {
        self::ensureSession();
        
        // Clear remember token from DB
        if (isset($_SESSION[self::SESSION_KEY])) {
            try {
                $db = Database::getInstance();
                $db->query(
                    "UPDATE users SET remember_token = NULL WHERE id = :id",
                    ['id' => $_SESSION[self::SESSION_KEY]]
                );
            } catch (\Exception $e) {
                // Ignore DB errors during logout
            }
        }

        // Clear user from session
        unset($_SESSION[self::SESSION_KEY]);
        self::$currentUser = null;
        
        // Clear remember cookie
        self::clearRememberCookie();

        // Destroy session completely
        session_destroy();

        // Start a new session for flash messages, etc.
        session_start();
        session_regenerate_id(true);
    }

    /**
     * Check if a user is currently logged in.
     *
     * @return bool True if logged in, false otherwise.
     */
    public static function check(): bool
    {
        self::ensureSession();

        return isset($_SESSION[self::SESSION_KEY]) && $_SESSION[self::SESSION_KEY] > 0;
    }

    /**
     * Get the current logged-in user's data.
     *
     * @return array|null User data array or null if not logged in.
     */
    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        // Return cached user if available
        if (self::$currentUser !== null) {
            return self::$currentUser;
        }

        try {
            $db = Database::getInstance();
            
            $user = $db->queryOne(
                "SELECT id, email, role, last_login, created_at, updated_at FROM users WHERE id = :id LIMIT 1",
                ['id' => $_SESSION[self::SESSION_KEY]]
            );

            if ($user) {
                self::$currentUser = $user;
                return $user;
            }

            // User not found in DB, clear invalid session
            self::logout();
            return null;

        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the current user's ID.
     *
     * @return int|null User ID or null if not logged in.
     */
    public static function id(): ?int
    {
        self::ensureSession();

        return isset($_SESSION[self::SESSION_KEY]) 
            ? (int) $_SESSION[self::SESSION_KEY] 
            : null;
    }

    /**
     * Check if the current user has a specific role.
     *
     * @param string $role Role to check for.
     * @return bool True if user has the role.
     */
    public static function hasRole(string $role): bool
    {
        $user = self::user();
        return $user !== null && ($user['role'] ?? '') === $role;
    }

    /**
     * Check if the current user is an admin.
     *
     * @return bool True if user is admin.
     */
    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user !== null && in_array($user['role'] ?? '', ['admin', 'administrator'], true);
    }

    /**
     * Require authentication - redirects if not logged in.
     *
     * @param string $redirectTo URL to redirect to if not authenticated.
     * @return void
     */
    public static function require(string $redirectTo = '/admin/login'): void
    {
        if (!self::check()) {
            header("Location: {$redirectTo}");
            exit;
        }
    }

    /**
     * Require a specific role - redirects if not authorized.
     *
     * @param string $role       Required role.
     * @param string $redirectTo URL to redirect to if not authorized.
     * @return void
     */
    public static function requireRole(string $role, string $redirectTo = '/admin/login'): void
    {
        self::require($redirectTo);

        if (!self::hasRole($role)) {
            header("Location: {$redirectTo}");
            exit;
        }
    }
    
    /**
     * Get remaining lockout time in seconds (0 if not locked).
     */
    public static function getLockoutRemaining(string $email): int
    {
        try {
            $db = Database::getInstance();
            $user = $db->queryOne(
                "SELECT locked_until FROM users WHERE email = :email LIMIT 1",
                ['email' => $email]
            );
            
            if ($user && !empty($user['locked_until'])) {
                $lockedUntil = strtotime($user['locked_until']);
                if ($lockedUntil > time()) {
                    return $lockedUntil - time();
                }
            }
            
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
