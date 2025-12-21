# Theme Development Basics

Themes in Zed CMS are purposely simple. Unlike WordPress, there is no "Loop" complexity or required `style.css` header metadata. A theme is just a directory of PHP files.

## Directory Structure

Themes live in `content/themes/`. A minimal theme needs just one file: `index.php`.

```text
content/themes/my-awesome-theme/
├── index.php         (Required) Homepage & Blog Listing
├── single.php        (Optional) Individual Posts
├── page.php          (Optional) Static Pages
├── 404.php           (Optional) Not Found Page
└── assets/           (Recommended) CSS, JS, Images
```

## How Routing Works

The `frontend_addon.php` handles theme selection.
1. It looks for `single.php` if a single post is requested.
2. It looks for `page.php` if a page is requested.
3. It falls back to `index.php` for everything else.

## The `index.php` Template

This is your main entry point. It receives the following variables automatically:

```php
// Available Variables
$posts        // Array of post objects
$total_posts  // Integer
$page_num     // Current page number (1-based)
$total_pages  // Total number of pages
$is_home      // Bool
$is_blog      // Bool
$site_title   // String
```

### Example `index.php`

```php
<!DOCTYPE html>
<html>
<head>
    <title><?= zed_get_site_name() ?></title>
    <?php Event::trigger('zed_head'); ?>
</head>
<body>
    <header>
        <h1><a href="/"><?= zed_get_site_name() ?></a></h1>
        <?= zed_menu('Main Menu') ?>
    </header>

    <main>
        <?php foreach ($posts as $post): ?>
            <article>
                <h2><a href="/<?= $post['slug'] ?>"><?= $post['title'] ?></a></h2>
                <div class="meta">
                    Published on <?= date('M d', strtotime($post['created_at'])) ?>
                </div>
                <!-- Display excerpt or content -->
                <p><?= $post['excerpt'] ?: substr($post['plain_text'], 0, 150) . '...' ?></p>
            </article>
        <?php endforeach; ?>
    </main>

    <footer>
        &copy; <?= date('Y') ?>
        <?php Event::trigger('zed_footer'); ?>
    </footer>
</body>
</html>
```
