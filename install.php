<?php

declare(strict_types=1);

/**
 * Zed CMS System Installer
 * 
 * Handles database setup, table creation, and initial admin user.
 */

// If config.php already exists, redirect to home
if (file_exists(__DIR__ . '/config.php')) {
    // Get base path for subdirectory support
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/install.php';
    $basePath = dirname($scriptName);
    $basePath = ($basePath === '/' || $basePath === '\\') ? '' : $basePath;
    header('Location: ' . $basePath . '/');
    exit;
}

$error = '';
$success = false;

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = trim($_POST['db_host'] ?? 'localhost');
    $name = trim($_POST['db_name'] ?? '');
    $user = trim($_POST['db_user'] ?? '');
    $pass = $_POST['db_pass'] ?? '';
    
    // Admin credentials
    $admin_email = trim($_POST['admin_email'] ?? '');
    $admin_pass = $_POST['admin_pass'] ?? '';

    // Validate required fields
    if (empty($name)) {
        $error = 'Database name is required.';
    } elseif (empty($user)) {
        $error = 'Database user is required.';
    } elseif (empty($admin_email)) {
        $error = 'Admin email is required.';
    } elseif (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid admin email address.';
    } elseif (empty($admin_pass) || strlen($admin_pass) < 6) {
        $error = 'Admin password must be at least 6 characters.';
    } else {
        try {
            // 1. Connect to MySQL (without database first)
            $dsn = "mysql:host={$host};charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            // 2. Create database if not exists
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$name}`");

            // =====================================================================
            // 3. Create zed_content table (with author_id and plain_text for RBAC)
            // =====================================================================
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `zed_content` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `slug` VARCHAR(255) NOT NULL,
                    `type` VARCHAR(50) NOT NULL DEFAULT 'page',
                    `title` VARCHAR(255) NOT NULL,
                    `data` JSON NULL COMMENT 'BlockNote content + metadata (status, excerpt, featured_image)',
                    `plain_text` LONGTEXT NULL COMMENT 'Extracted text for full-text search',
                    `author_id` BIGINT UNSIGNED NULL COMMENT 'User who created the content',
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `idx_slug` (`slug`),
                    INDEX `idx_type` (`type`),
                    INDEX `idx_author` (`author_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // =====================================================================
            // 4. Create users table with security columns
            // =====================================================================
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `users` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `email` VARCHAR(255) NOT NULL,
                    `password_hash` VARCHAR(255) NOT NULL,
                    `display_name` VARCHAR(100) NULL,
                    `bio` TEXT NULL,
                    `avatar` VARCHAR(500) NULL,
                    `social_links` JSON NULL,
                    `role` VARCHAR(50) NOT NULL DEFAULT 'subscriber' COMMENT 'admin, editor, author, subscriber',
                    `remember_token` VARCHAR(64) NULL COMMENT 'Hashed token for persistent login',
                    `last_login` DATETIME NULL COMMENT 'Last successful login timestamp',
                    `failed_attempts` INT NOT NULL DEFAULT 0 COMMENT 'Failed login attempts counter',
                    `locked_until` DATETIME NULL COMMENT 'Account lockout expiry time',
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `idx_email` (`email`),
                    INDEX `idx_remember_token` (`remember_token`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // =====================================================================
            // 5. Create zed_options table for settings (Settings Panel)
            // =====================================================================
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `zed_options` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `option_name` VARCHAR(191) NOT NULL,
                    `option_value` LONGTEXT NULL,
                    `autoload` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Load on every request',
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `idx_option_name` (`option_name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // =====================================================================
            // 6. Create zed_categories table
            // =====================================================================
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `zed_categories` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(255) NOT NULL,
                    `slug` VARCHAR(255) NOT NULL,
                    `description` TEXT NULL,
                    `parent_id` BIGINT UNSIGNED NULL COMMENT 'For hierarchical categories',
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `idx_slug` (`slug`),
                    INDEX `idx_parent` (`parent_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // =====================================================================
            // 7. Create zed_menus table (Navigation Manager)
            // =====================================================================
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `zed_menus` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(255) NOT NULL,
                    `items` JSON NULL COMMENT 'Menu items tree structure',
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // =====================================================================
            // 8. Create zed_content_revisions table (Version History)
            // =====================================================================
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `zed_content_revisions` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `content_id` BIGINT UNSIGNED NOT NULL,
                    `data` JSON NULL COMMENT 'Snapshot of content data',
                    `author_id` BIGINT UNSIGNED NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    INDEX `idx_content` (`content_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // =====================================================================
            // 9. Create zed_tags table
            // =====================================================================
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `zed_tags` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(255) NOT NULL,
                    `slug` VARCHAR(255) NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `idx_slug` (`slug`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // =====================================================================
            // 10. Create zed_media table (Media Library)
            // =====================================================================
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `zed_media` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `filename` VARCHAR(255) NOT NULL,
                    `original_filename` VARCHAR(255) NOT NULL,
                    `file_path` VARCHAR(500) NOT NULL,
                    `url` VARCHAR(500) NOT NULL,
                    `thumbnail_url` VARCHAR(500) NULL,
                    `medium_url` VARCHAR(500) NULL,
                    `large_url` VARCHAR(500) NULL,
                    `mime_type` VARCHAR(100) NOT NULL,
                    `file_size` BIGINT UNSIGNED NOT NULL DEFAULT 0,
                    `width` INT UNSIGNED NULL,
                    `height` INT UNSIGNED NULL,
                    `alt_text` VARCHAR(255) NULL,
                    `caption` TEXT NULL,
                    `folder_id` BIGINT UNSIGNED NULL,
                    `uploaded_by` BIGINT UNSIGNED NULL,
                    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    INDEX `idx_folder` (`folder_id`),
                    INDEX `idx_mime` (`mime_type`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // =====================================================================
            // 11. Create zed_comments table (v3.2.0 Comments System)
            // =====================================================================
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `zed_comments` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `post_id` BIGINT UNSIGNED NOT NULL,
                    `parent_id` BIGINT UNSIGNED DEFAULT 0,
                    `user_id` BIGINT UNSIGNED DEFAULT NULL,
                    `author_name` VARCHAR(100) NOT NULL,
                    `author_email` VARCHAR(255) NOT NULL,
                    `author_url` VARCHAR(255) DEFAULT NULL,
                    `content` TEXT NOT NULL,
                    `status` ENUM('pending', 'approved', 'spam', 'trash') DEFAULT 'pending',
                    `ip_address` VARCHAR(45) DEFAULT NULL,
                    `user_agent` VARCHAR(255) DEFAULT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    INDEX `idx_post` (`post_id`),
                    INDEX `idx_parent` (`parent_id`),
                    INDEX `idx_status` (`status`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // =====================================================================
            // 12. Insert admin user with provided credentials
            // =====================================================================
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $admin_email]);
            $adminId = 1;
            if (!$stmt->fetch()) {
                $hashedPassword = password_hash($admin_pass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, display_name, role) VALUES (:email, :pass, :name, :role)");
                $stmt->execute([
                    'email' => $admin_email,
                    'pass'  => $hashedPassword,
                    'name'  => 'Administrator',
                    'role'  => 'admin',
                ]);
                $adminId = (int)$pdo->lastInsertId();
            }

            // =====================================================================
            // 9. Insert default settings
            // =====================================================================
            $defaultOptions = [
                ['site_title', 'Zed CMS', 1],
                ['site_tagline', 'A modern content management system', 1],
                ['homepage_mode', 'latest_posts', 1],
                ['posts_per_page', '10', 1],
                ['blog_slug', 'blog', 1],
                ['meta_description', '', 1],
                ['discourage_search_engines', '0', 1],
                ['maintenance_mode', '0', 1],
                ['debug_mode', '0', 1], // Off by default for production
                // Comments settings (v3.2.0)
                ['comments_enabled', '1', 1],
                ['comments_moderation', '1', 1],
                ['comments_require_email', '1', 1],
                ['comments_notify_admin', '1', 1],
                // Active theme
                ['active_theme', 'zenith', 1],
                // Active addons - empty by default (only system modules load)
                ['active_addons', '[]', 1],
            ];
            
            $optStmt = $pdo->prepare("INSERT IGNORE INTO zed_options (option_name, option_value, autoload) VALUES (?, ?, ?)");
            foreach ($defaultOptions as $opt) {
                $optStmt->execute($opt);
            }

            // =====================================================================
            // 10. Insert default categories
            // =====================================================================
            $defaultCategories = [
                ['Uncategorized', 'uncategorized', 'Default category for posts'],
                ['News', 'news', 'Latest news and updates'],
                ['Tutorials', 'tutorials', 'How-to guides and tutorials'],
            ];
            
            $catStmt = $pdo->prepare("INSERT IGNORE INTO zed_categories (name, slug, description) VALUES (?, ?, ?)");
            foreach ($defaultCategories as $cat) {
                $catStmt->execute($cat);
            }

            // =====================================================================
            // 11. Insert sample homepage content
            // =====================================================================
            $sampleContent = [
                'content' => [
                    [
                        'id' => uniqid(),
                        'type' => 'heading',
                        'props' => ['level' => 1],
                        'content' => [['type' => 'text', 'text' => 'Welcome to Zed CMS']],
                        'children' => []
                    ],
                    [
                        'id' => uniqid(),
                        'type' => 'paragraph',
                        'props' => [],
                        'content' => [['type' => 'text', 'text' => 'Congratulations! Your Zed CMS installation is complete. This is a sample page to help you get started.']],
                        'children' => []
                    ],
                    [
                        'id' => uniqid(),
                        'type' => 'heading',
                        'props' => ['level' => 2],
                        'content' => [['type' => 'text', 'text' => 'Getting Started']],
                        'children' => []
                    ],
                    [
                        'id' => uniqid(),
                        'type' => 'bulletListItem',
                        'props' => [],
                        'content' => [['type' => 'text', 'text' => 'Edit this page or create new content from the admin panel']],
                        'children' => []
                    ],
                    [
                        'id' => uniqid(),
                        'type' => 'bulletListItem',
                        'props' => [],
                        'content' => [['type' => 'text', 'text' => 'Configure your site settings in Settings → General']],
                        'children' => []
                    ],
                    [
                        'id' => uniqid(),
                        'type' => 'bulletListItem',
                        'props' => [],
                        'content' => [['type' => 'text', 'text' => 'Upload images through the Media Library']],
                        'children' => []
                    ]
                ],
                'status' => 'published',
                'excerpt' => 'Welcome to your new Zed CMS installation.'
            ];
            
            $stmt = $pdo->prepare("SELECT id FROM zed_content WHERE slug = 'welcome'");
            $stmt->execute();
            if (!$stmt->fetch()) {
                $stmt = $pdo->prepare("
                    INSERT INTO zed_content (title, slug, type, data, plain_text, author_id) 
                    VALUES (:title, :slug, :type, :data, :plain_text, :author)
                ");
                $stmt->execute([
                    'title' => 'Welcome to Zed CMS',
                    'slug' => 'welcome',
                    'type' => 'page',
                    'data' => json_encode($sampleContent),
                    'plain_text' => 'Welcome to Zed CMS. Congratulations! Your installation is complete.',
                    'author' => $adminId
                ]);
            }

            // =====================================================================
            // 12. Write config.php
            // =====================================================================
            $configContent = <<<PHP
<?php

declare(strict_types=1);

/**
 * Zed CMS Configuration
 * Database credentials and core settings.
 */

return [
    'database' => [
        'host'     => '{$host}',
        'port'     => 3306,
        'name'     => '{$name}',
        'user'     => '{$user}',
        'password' => '{$pass}',
        'charset'  => 'utf8mb4',
    ],

    'app' => [
        'name'    => 'Zed CMS',
        'version' => '3.2.0',
        'debug'   => true,
    ],
];
PHP;

            file_put_contents(__DIR__ . '/config.php', $configContent);

            // =====================================================================
            // 13. Set success flag for display
            // =====================================================================
            $success = true;
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/install.php';
            $basePath = dirname($scriptName);
            $basePath = ($basePath === '/' || $basePath === '\\') ? '' : $basePath;
            $adminLoginUrl = $basePath . '/admin/login';

        } catch (PDOException $e) {
            $error = 'Database Error: ' . $e->getMessage();
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Zed CMS System Installer</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800&amp;family=Noto+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Theme Configuration -->
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#256af4",
              "primary-dark": "#1a52c9",
              "background-light": "#f5f6f8",
              "background-dark": "#101622",
              "surface-light": "#ffffff",
              "surface-dark": "#1e293b",
            },
            fontFamily: {
              "display": ["Space Grotesk", "sans-serif"],
              "body": ["Noto Sans", "sans-serif"],
            },
            borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1rem", "full": "9999px" },
          },
        },
      }
    </script>
<style>
        /* Custom smooth focus transition */
        .form-input-transition {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="font-display antialiased text-slate-800 bg-background-light dark:bg-background-dark min-h-screen flex items-center justify-center relative overflow-hidden selection:bg-primary selection:text-white">
<!-- Vibrant Mesh Background -->
<div class="absolute inset-0 w-full h-full bg-[#101622] z-0">
<!-- Base Gradient -->
<div class="absolute inset-0 bg-gradient-to-br from-indigo-950 via-[#0f172a] to-[#0a0f1a] opacity-90"></div>
<!-- Mesh Blobs -->
<div class="absolute top-[-10%] left-[-10%] w-[60%] h-[60%] bg-[#256af4] rounded-full blur-[140px] opacity-20 animate-pulse"></div>
<div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] bg-purple-600 rounded-full blur-[140px] opacity-20"></div>
<div class="absolute top-[40%] left-[50%] -translate-x-1/2 -translate-y-1/2 w-[40%] h-[40%] bg-cyan-500 rounded-full blur-[120px] opacity-15"></div>
<!-- Grid Pattern Overlay -->
<div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-soft-light"></div>
</div>
<!-- Main Installer Layout -->
<div class="layout-container flex w-full max-w-6xl p-4 md:p-6 lg:p-8 z-10 relative">
<div class="flex-1 flex justify-center">
<!-- Card Container -->
<div class="w-full max-w-[1000px] bg-surface-light dark:bg-surface-dark rounded-2xl shadow-2xl overflow-hidden border border-white/20 dark:border-white/5 flex flex-col md:flex-row min-h-[640px]">
<!-- Left Visual Column (Hidden on mobile) -->
<div class="hidden md:flex md:w-5/12 lg:w-4/12 relative bg-slate-900 overflow-hidden flex-col justify-between">
<!-- Background Image -->
<div class="absolute inset-0 z-0">
<img alt="Abstract vibrant geometric structures floating in dark space" class="w-full h-full object-cover opacity-80 mix-blend-overlay" data-alt="Abstract vibrant geometric structures floating in dark space" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB5uR8A2ezko2fJqiIoUmLL5YxDOkKtDSI2iPl8OUzS1nISBO4D501dhGX7TrID9NdsKwTNp8UC2Znnj-gTjt1ndDn7hu7rPRJ0hIxt-569NkRsnQoVEd3qS2oQ3rcwGpMa6pJ1LpghNxtlT1nQO5l30OZo7FoNs3XzVQnHQrhqBntfoAlW1NdhFrXLghkRloavN3B_AiYMEI4JWnaBpUge3Gwj4cRFg27RkqoJxXwtYB9G5f37cd4eshN_MLuUlky4ZSQYAD2q8jMT"/>
<div class="absolute inset-0 bg-gradient-to-b from-primary/30 to-purple-900/90 mix-blend-multiply"></div>
</div>
<!-- Decorative Content -->
<div class="relative z-10 p-8 h-full flex flex-col justify-end text-white">
<div class="w-12 h-12 rounded-lg bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-white text-2xl">deployed_code</span>
</div>
<h2 class="text-3xl font-bold leading-tight mb-3">Build Faster.<br/>Scale Infinite.</h2>
<p class="text-white/70 text-sm font-normal leading-relaxed mb-8 max-w-[260px]">
                            Zero uses an advanced query engine to deliver content at the speed of light.
                        </p>
<!-- Progress Indicators -->
<div class="flex items-center gap-2">
<div class="h-1 flex-1 bg-white rounded-full"></div>
<div class="h-1 flex-1 bg-white/20 rounded-full"></div>
<div class="h-1 flex-1 bg-white/20 rounded-full"></div>
</div>
<p class="text-[10px] uppercase tracking-widest text-white/50 mt-2 font-bold">Step 1 of 3: Database</p>
</div>
</div>
<!-- Right Form Column -->
<div class="flex-1 flex flex-col p-6 lg:p-10">
<!-- Header -->
<div class="flex flex-col gap-2 mb-10">
<div class="flex items-center gap-2 text-primary mb-2">
<span class="material-symbols-outlined text-3xl">bolt</span>
<span class="text-xl font-black tracking-tighter uppercase text-slate-900 dark:text-white">Zero</span>
</div>
<h1 class="text-[#0d121c] dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Welcome to the Future of Content</h1>
<p class="text-[#49659c] dark:text-slate-400 text-base font-normal leading-normal">
                            Configure your database to initialize the high-performance core.
                        </p>
</div>

<?php if ($error): ?>
<!-- Error Message -->
<div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 flex items-start gap-3">
    <span class="material-symbols-outlined text-red-500 text-xl mt-0.5">error</span>
    <div>
        <p class="text-red-600 dark:text-red-400 font-semibold text-sm">Installation Failed</p>
        <p class="text-red-500/80 dark:text-red-400/80 text-sm mt-1"><?= htmlspecialchars($error) ?></p>
    </div>
</div>
<?php endif; ?>

<?php if ($success): ?>
<!-- Success Message -->
<div class="flex flex-col items-center justify-center text-center py-8">
    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center mb-6 shadow-lg shadow-green-500/30">
        <span class="material-symbols-outlined text-white text-4xl">check</span>
    </div>
    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Installation Complete!</h2>
    <p class="text-slate-500 dark:text-slate-400 mb-8 max-w-sm">
        Zed CMS has been successfully installed. Your database is ready and your admin account has been created.
    </p>
    <a href="<?= htmlspecialchars($adminLoginUrl) ?>" 
       class="relative overflow-hidden rounded-xl px-8 py-4 bg-gradient-to-r from-primary to-purple-600 text-white text-lg font-bold tracking-wide shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 inline-flex items-center gap-3">
        <span class="material-symbols-outlined">login</span>
        Go to Admin Login
    </a>
    <p class="text-sm text-slate-400 mt-6">
        <span class="material-symbols-outlined text-amber-500 text-sm align-middle">warning</span>
        Remember to delete <code class="bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">install.php</code> for security
    </p>
</div>
<?php else: ?>
<!-- Form Fields -->
<form class="flex flex-col gap-6" method="POST" action="">
<!-- Row 1: Host -->
<label class="flex flex-col gap-2 group">
<p class="text-[#0d121c] dark:text-slate-200 text-sm font-bold uppercase tracking-wide">Database Host</p>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
<span class="material-symbols-outlined text-[20px]">dns</span>
</div>
<input name="db_host" class="form-input-transition flex w-full rounded-lg border border-[#ced7e8] dark:border-slate-600 bg-background-light dark:bg-slate-800/50 h-14 pl-12 pr-4 text-base text-[#0d121c] dark:text-white placeholder:text-[#49659c] dark:placeholder:text-slate-500 focus:outline-0 focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="localhost" type="text" value="<?= htmlspecialchars($_POST['db_host'] ?? 'localhost') ?>"/>
</div>
</label>
<!-- Row 2: DB Name -->
<label class="flex flex-col gap-2 group">
<p class="text-[#0d121c] dark:text-slate-200 text-sm font-bold uppercase tracking-wide">Database Name</p>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
<span class="material-symbols-outlined text-[20px]">database</span>
</div>
<input name="db_name" class="form-input-transition flex w-full rounded-lg border border-[#ced7e8] dark:border-slate-600 bg-background-light dark:bg-slate-800/50 h-14 pl-12 pr-4 text-base text-[#0d121c] dark:text-white placeholder:text-[#49659c] dark:placeholder:text-slate-500 focus:outline-0 focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="zero_db" type="text" value="<?= htmlspecialchars($_POST['db_name'] ?? '') ?>"/>
</div>
</label>
<!-- Row 3: User & Pass (2 Columns) -->
<div class="flex flex-col sm:flex-row gap-6">
<label class="flex flex-col gap-2 flex-1 min-w-[200px]">
<p class="text-[#0d121c] dark:text-slate-200 text-sm font-bold uppercase tracking-wide">Database User</p>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
<span class="material-symbols-outlined text-[20px]">person</span>
</div>
<input name="db_user" class="form-input-transition flex w-full rounded-lg border border-[#ced7e8] dark:border-slate-600 bg-background-light dark:bg-slate-800/50 h-14 pl-12 pr-4 text-base text-[#0d121c] dark:text-white placeholder:text-[#49659c] dark:placeholder:text-slate-500 focus:outline-0 focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="username" type="text" value="<?= htmlspecialchars($_POST['db_user'] ?? 'root') ?>"/>
</div>
</label>
<label class="flex flex-col gap-2 flex-1 min-w-[200px]">
<p class="text-[#0d121c] dark:text-slate-200 text-sm font-bold uppercase tracking-wide">Database Password</p>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
<span class="material-symbols-outlined text-[20px]">key</span>
</div>
<input name="db_pass" class="form-input-transition flex w-full rounded-lg border border-[#ced7e8] dark:border-slate-600 bg-background-light dark:bg-slate-800/50 h-14 pl-12 pr-4 text-base text-[#0d121c] dark:text-white placeholder:text-[#49659c] dark:placeholder:text-slate-500 focus:outline-0 focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="••••••••" type="password" value=""/>
</div>
</label>
</div>

<!-- Separator -->
<div class="flex items-center gap-4 my-2">
<div class="flex-1 h-px bg-slate-200 dark:bg-slate-700"></div>
<span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Admin Account</span>
<div class="flex-1 h-px bg-slate-200 dark:bg-slate-700"></div>
</div>

<!-- Row 4: Admin Email -->
<label class="flex flex-col gap-2 group">
<p class="text-[#0d121c] dark:text-slate-200 text-sm font-bold uppercase tracking-wide">Admin Email</p>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
<span class="material-symbols-outlined text-[20px]">mail</span>
</div>
<input name="admin_email" class="form-input-transition flex w-full rounded-lg border border-[#ced7e8] dark:border-slate-600 bg-background-light dark:bg-slate-800/50 h-14 pl-12 pr-4 text-base text-[#0d121c] dark:text-white placeholder:text-[#49659c] dark:placeholder:text-slate-500 focus:outline-0 focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="admin@example.com" type="email" value="<?= htmlspecialchars($_POST['admin_email'] ?? '') ?>"/>
</div>
</label>

<!-- Row 5: Admin Password -->
<label class="flex flex-col gap-2 group">
<p class="text-[#0d121c] dark:text-slate-200 text-sm font-bold uppercase tracking-wide">Admin Password</p>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
<span class="material-symbols-outlined text-[20px]">lock</span>
</div>
<input name="admin_pass" class="form-input-transition flex w-full rounded-lg border border-[#ced7e8] dark:border-slate-600 bg-background-light dark:bg-slate-800/50 h-14 pl-12 pr-4 text-base text-[#0d121c] dark:text-white placeholder:text-[#49659c] dark:placeholder:text-slate-500 focus:outline-0 focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Min 6 characters" type="password" value=""/>
</div>
</label>

<!-- Action Button -->
<div class="pt-4">
<button type="submit" class="relative w-full overflow-hidden rounded-xl h-14 bg-gradient-to-r from-primary to-purple-600 text-[#f8f9fc] text-lg font-bold tracking-wide shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 group">
<div class="absolute inset-0 bg-white/0 group-hover:bg-white/10 transition-colors duration-200"></div>
<div class="flex items-center justify-center gap-3 relative z-10">
<span class="material-symbols-outlined text-[24px]">rocket_launch</span>
<span>Ignite Engine</span>
</div>
</button>
</div>
</form>
<?php endif; ?>
<!-- Footer Help -->
<div class="mt-auto pt-8 flex justify-center">
<a class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-primary transition-colors font-medium" href="#">
<span class="material-symbols-outlined text-[18px]">help</span>
<span>Trouble connecting?</span>
</a>
</div>
</div>
</div>
</div>
</div>
</body></html>