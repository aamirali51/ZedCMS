# Deployment Guide

Deploying Zed CMS to a live server (VPS or Shared Hosting).

## 1. Requirements

*   PHP 8.2+
*   MySQL/MariaDB
*   Apache with `mod_rewrite` enabled OR Nginx

## 2. Apache Setup (`.htaccess`)

Ensure your `.htaccess` redirects all traffic to `index.php`. The default file provided with Zed handles this:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

## 3. Configuration

1.  Upload files to `public_html`.
2.  Import your local database SQL dump.
3.  Edit `config.php`:

```php
return [
    'db_host' => 'localhost',
    'db_name' => 'your_db_name',
    'db_user' => 'your_db_user',
    'db_pass' => 'secure_password',
];
```

## 4. Permissions

Ensure the following directories are writable (`chmod 755` or `777` depending on user group):
*   `content/uploads/` (Crucial for media)
*   `content/backups/` (If used)

## 5. Security Checklist

*   [ ] Change the admin email and password immediately.
*   [ ] Ensure `debug_mode` is `0` in `zed_options` table.
*   [ ] Verify proper file permissions.
