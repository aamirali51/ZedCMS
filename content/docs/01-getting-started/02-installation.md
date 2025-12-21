# Installation Guide

Follow these steps to get Zed CMS running on your local machine or production server.

## Prerequisites

*   **PHP:** Version 8.2 or higher.
    *   Extensions required: `pdo`, `pdo_mysql`, `gd` (for images), `json`, `mbstring`.
*   **Database:** MySQL 5.7+ or MariaDB 10.2+.
*   **Web Server:** Apache (with `mod_rewrite`) or Nginx.
*   **Composer:** Optional (only if you want to update dependencies, though vendors are committed).

## Step-by-step Installation

### 1. Download & Extract
Clone the repository or unzip the archive into your web root (e.g., `public_html` or `www`).

```bash
git clone https://github.com/your-repo/zero-cms.git .
```

### 2. Configure Database
Create a new MySQL database (e.g., `zed_cms`).

Rename `config.sample.php` to `config.php` and edit the credentials:

```php
// config.php
return [
    'db_host' => 'localhost',
    'db_name' => 'zed_cms',
    'db_user' => 'root',
    'db_pass' => '',
    'db_charset' => 'utf8mb4',
    'debug' => true, // Set to false in production
];
```

### 3. Run the Installer
Navigate to `http://your-site.com/install.php` in your browser.

The script will:
1.  Check for PHP 8.2 compatibility.
2.  Check for write permissions in `content/uploads`.
3.  Create the database tables (`zed_content`, `users`, `zed_options`, etc.).
4.  Create the default Admin account (`admin@example.com` / `password`).
5.  Seed sample content.

### 4. Secure the Installation
**CRITICAL:** Once installed, delete `install.php` and `migrate_options.php` to prevent unauthorized database resets.

```bash
rm install.php migrate_options.php
```

## Troubleshooting

### "Internal Server Error" (500)
*   Check your `.htaccess` file. Ensure `RewriteBase` matches your installation path.
*   Verify `config.php` syntax errors.
*   Check PHP error logs.

### "404 Not Found" for everything except Homepage
*   `mod_rewrite` is likely disabled in Apache.
*   For Nginx, ensure your config handles the fallback:
    ```nginx
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    ```

### Images not loading
*   Check permissions on `content/uploads`. It needs to be writable (`755` or `777`).
*   Ensure the GD extension is enabled in `php.ini`.
