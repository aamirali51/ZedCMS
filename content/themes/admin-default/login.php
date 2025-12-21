<?php

declare(strict_types=1);

/**
 * Zed CMS Admin Login Page
 * 
 * Security Features:
 * - CSRF protection
 * - Brute-force throttling with countdown
 * - Remember me (30 days)
 * - Session fixation prevention
 */

// Load the autoloader and config
$config = require __DIR__ . '/../../../config.php';

// Autoload core classes
spl_autoload_register(function (string $class): void {
    $prefix = 'Core\\';
    $baseDir = __DIR__ . '/../../../core/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use Core\Auth;
use Core\Database;
use Core\Router;

// Start session for CSRF
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize database
Database::setConfig($config['database']);

// Check remember me cookie first (auto-login)
Auth::checkRememberCookie();

// If already logged in, redirect to admin
if (Auth::check()) {
    Router::redirect('/admin');
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

$error = '';
$isLocked = false;
$lockoutRemaining = 0;

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!hash_equals($csrfToken, $submittedToken)) {
        $error = 'Invalid request. Please refresh and try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember-me']);

        if (empty($email) || empty($password)) {
            $error = 'Please enter both email and password.';
        } else {
            // Use enhanced login with remember me support
            $result = Auth::login($email, $password, $remember);
            
            if ($result['success']) {
                // Regenerate CSRF token after successful login
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                // Success! Redirect to admin dashboard
                Router::redirect('/admin');
            } else {
                $error = $result['error'] ?? 'Invalid email or password.';
                $isLocked = $result['locked'] ?? false;
                
                // Get lockout remaining time if locked
                if ($isLocked && !empty($email)) {
                    $lockoutRemaining = Auth::getLockoutRemaining($email);
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Zed CMS — Secure Login</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800&amp;family=Noto+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              "primary": "#4f46e5",
              "primary-hover": "#4338ca",
              "text-main": "#111827",
              "text-secondary": "#6b7280",
            },
            fontFamily: {
              "display": ["Space Grotesk", "sans-serif"],
              "body": ["Noto Sans", "sans-serif"],
            },
            backgroundImage: {
                'brand-gradient': 'linear-gradient(135deg, #312e81 0%, #7c3aed 50%, #f97316 100%)',
                'btn-gradient': 'linear-gradient(to right, #4f46e5, #7c3aed)',
            },
            keyframes: {
                shake: {
                    '0%, 100%': { transform: 'translateX(0)' },
                    '10%, 30%, 50%, 70%, 90%': { transform: 'translateX(-4px)' },
                    '20%, 40%, 60%, 80%': { transform: 'translateX(4px)' },
                }
            },
            animation: {
                shake: 'shake 0.5s cubic-bezier(.36,.07,.19,.97) both',
            }
          },
        },
      }
</script>
<style>
    /* Loading spinner */
    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Password toggle */
    .password-toggle {
        cursor: pointer;
        user-select: none;
        transition: color 0.2s;
    }
    .password-toggle:hover {
        color: #6b7280;
    }
    
    /* Countdown badge */
    .countdown-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        background: rgba(245, 158, 11, 0.2);
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        color: #b45309;
    }
</style>
</head>
<body class="font-body text-slate-800 bg-white min-h-screen flex w-full">
<div class="hidden lg:flex lg:w-1/2 bg-brand-gradient flex-col items-center justify-center relative overflow-hidden text-white p-12">
<div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
<div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-purple-500 rounded-full blur-[128px] opacity-30"></div>
<div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-orange-400 rounded-full blur-[128px] opacity-30"></div>
<div class="relative z-10 text-center">
<div class="mb-6">
<div class="w-20 h-20 border-4 border-white/20 rounded-2xl mx-auto flex items-center justify-center rotate-45 mb-8 backdrop-blur-sm">
<div class="w-10 h-10 bg-white rounded-lg -rotate-45"></div>
</div>
</div>
<h1 class="font-display font-bold text-8xl tracking-tight leading-none mb-4 drop-shadow-sm">ZED</h1>
<p class="font-light text-2xl tracking-[0.2em] uppercase text-white/90">The speed you need.</p>
</div>
<div class="absolute bottom-8 left-0 w-full text-center text-white/40 text-xs font-display">
    CMS Dashboard v1.2.0
</div>
</div>
<div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-24 bg-white relative">
<div class="w-full max-w-md space-y-8">
<div class="lg:hidden text-center mb-8">
<span class="font-display font-bold text-4xl text-transparent bg-clip-text bg-gradient-to-r from-purple-700 to-orange-500">ZED</span>
</div>
<div class="text-left">
<h2 class="text-3xl font-bold text-text-main font-display">Sign in to Dashboard</h2>
<p class="mt-2 text-sm text-text-secondary">Welcome back. Please enter your credentials to access the admin panel.</p>
</div>

<?php if ($error): ?>
<!-- Error Message with Shake Animation -->
<div id="errorBox" class="p-4 rounded-xl <?= $isLocked ? 'bg-amber-50 border-amber-200' : 'bg-red-50 border-red-200' ?> border flex items-start gap-3 animate-shake">
    <span class="material-symbols-outlined <?= $isLocked ? 'text-amber-500' : 'text-red-500' ?> text-xl mt-0.5"><?= $isLocked ? 'lock_clock' : 'error' ?></span>
    <div class="flex-1">
        <p class="<?= $isLocked ? 'text-amber-700' : 'text-red-700' ?> font-semibold text-sm"><?= $isLocked ? 'Account Temporarily Locked' : 'Authentication Failed' ?></p>
        <p class="<?= $isLocked ? 'text-amber-600' : 'text-red-600' ?> text-sm mt-1"><?= htmlspecialchars($error) ?></p>
        <?php if ($isLocked && $lockoutRemaining > 0): ?>
        <div class="mt-2">
            <span class="countdown-badge">
                <span class="material-symbols-outlined text-[14px]">timer</span>
                <span id="countdown"><?= gmdate('i:s', $lockoutRemaining) ?></span> remaining
            </span>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<form action="" class="mt-8 space-y-6" method="POST" id="loginForm">
<!-- CSRF Token -->
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

<div class="space-y-5">
<div>
<label class="block text-sm font-semibold text-text-main mb-2" for="email">Email Address</label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-gray-400 text-[20px]">mail</span>
</div>
<input autofocus autocomplete="email" class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent sm:text-sm transition-all duration-200" id="email" name="email" placeholder="name@company.com" required="" type="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"/>
</div>
</div>
<div>
<label class="block text-sm font-semibold text-text-main mb-2" for="password">Password</label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-gray-400 text-[20px]">lock</span>
</div>
<input autocomplete="current-password" class="appearance-none block w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent sm:text-sm transition-all duration-200" id="password" name="password" placeholder="••••••••" required="" type="password"/>
<!-- Password Visibility Toggle -->
<div class="absolute inset-y-0 right-0 pr-3 flex items-center">
    <span id="togglePassword" class="material-symbols-outlined text-gray-400 text-[20px] password-toggle" title="Show password">visibility_off</span>
</div>
</div>
</div>
</div>
<div class="flex items-center justify-between">
<div class="flex items-center">
<input class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded cursor-pointer" id="remember-me" name="remember-me" type="checkbox"/>
<label class="ml-2 block text-sm text-text-secondary cursor-pointer select-none" for="remember-me">Remember me for 30 days</label>
</div>
</div>
<div>
<button id="submitBtn" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-btn-gradient hover:shadow-lg hover:shadow-purple-500/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300 transform hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:transform-none" type="submit">
<span id="btnIcon" class="absolute left-0 inset-y-0 flex items-center pl-3">
<span class="material-symbols-outlined text-purple-200 group-hover:text-white transition-colors text-[20px]">verified_user</span>
</span>
<span id="btnText">Authenticate</span>
<span id="btnSpinner" class="hidden">
    <div class="spinner"></div>
</span>
</button>
</div>
</form>
<div class="mt-6 text-center">
<a class="text-sm font-medium text-purple-600 hover:text-purple-500 transition-colors" href="#">
    Forgot Password?
</a>
</div>
</div>
<div class="absolute bottom-6 w-full text-center lg:text-left lg:pl-0">
<div class="text-xs text-gray-300 lg:hidden">
    © <?= date('Y') ?> Zed CMS
</div>
</div>
</div>

<script>
(function() {
    'use strict';
    
    // ========== PASSWORD VISIBILITY TOGGLE ==========
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? 'visibility_off' : 'visibility';
            this.title = type === 'password' ? 'Show password' : 'Hide password';
        });
    }
    
    // ========== LOADING SPINNER ON SUBMIT ==========
    const form = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnIcon = document.getElementById('btnIcon');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            // Disable button and show spinner
            submitBtn.disabled = true;
            btnIcon.classList.add('hidden');
            btnText.textContent = 'Authenticating...';
            btnSpinner.classList.remove('hidden');
        });
    }
    
    // ========== LOCKOUT COUNTDOWN TIMER ==========
    <?php if ($isLocked && $lockoutRemaining > 0): ?>
    const countdownEl = document.getElementById('countdown');
    let remaining = <?= $lockoutRemaining ?>;
    
    const timer = setInterval(function() {
        remaining--;
        if (remaining <= 0) {
            clearInterval(timer);
            // Auto-refresh when lockout expires
            location.reload();
        } else {
            const mins = Math.floor(remaining / 60);
            const secs = remaining % 60;
            countdownEl.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }
    }, 1000);
    <?php endif; ?>
    
    // ========== REMOVE SHAKE ANIMATION AFTER PLAY ==========
    const errorBox = document.getElementById('errorBox');
    if (errorBox) {
        errorBox.addEventListener('animationend', function() {
            this.classList.remove('animate-shake');
        });
    }
    
})();
</script>
</body>
</html>