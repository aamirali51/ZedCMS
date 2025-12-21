# Complete Theme Walkthrough

This guide provides a blueprint for a production-ready "Magazine" theme.

## 1. File Structure

```text
content/themes/magazine/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── app.js
├── partials/
│   ├── header.php
│   ├── footer.php
│   └── sidebar.php
├── index.php        (Homepage & Lists)
├── single.php       (Articles)
└── page.php         (Static Pages)
```

## 2. Shared Header (`partials/header.php`)

```php
<?php use Core\Router; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= zed_page_title($page_title ?? '') ?></title>
    <link rel="stylesheet" href="<?= Router::getBasePath() ?>/content/themes/magazine/assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <?php \Core\Event::trigger('zed_head'); ?>
</head>
<body class="bg-gray-50 text-gray-900 font-sans">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="container mx-auto px-4 h-16 flex items-center justify-between">
            <!-- Logo -->
            <a href="<?= Router::getBasePath() ?>/" class="text-2xl font-bold tracking-tighter">
                Zed<span class="text-indigo-600">Mag</span>
            </a>
            
            <!-- Navigation -->
            <nav class="hidden md:block">
                <?= zed_menu('Main Menu', ['class' => 'flex gap-6 font-medium text-sm']) ?>
            </nav>
            
            <!-- Mobile Toggle -->
            <button class="md:hidden" onclick="document.getElementById('mob-nav').classList.toggle('hidden')">
                Menu
            </button>
        </div>
        <!-- Mobile Nav -->
        <div id="mob-nav" class="hidden md:hidden bg-white border-t p-4">
             <?= zed_menu('Main Menu', ['class' => 'space-y-2']) ?>
        </div>
    </header>
```

## 3. Blog Listing (`index.php`)

```php
<?php
// Load header
require __DIR__ . '/partials/header.php';
?>

<div class="container mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <main class="lg:col-span-2 space-y-8">
        <h1 class="text-3xl font-bold mb-6 border-b pb-2">Latest Stories</h1>
        
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <?php 
                $data = json_decode($post['data'], true); 
                $img = $data['featured_image'] ?? null;
                ?>
                <article class="flex flex-col md:flex-row gap-6 bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <?php if ($img): ?>
                    <a href="<?= Router::getBasePath() . '/' . $post['slug'] ?>" class="w-full md:w-1/3 shrink-0">
                        <img src="<?= $img ?>" class="w-full h-48 object-cover rounded-lg" loading="lazy">
                    </a>
                    <?php endif; ?>
                    
                    <div class="flex-1">
                        <div class="text-xs text-indigo-600 font-bold uppercase mb-2">
                            <?= date('M d, Y', strtotime($post['created_at'])) ?>
                        </div>
                        <h2 class="text-2xl font-bold mb-3 leading-tight">
                            <a href="<?= Router::getBasePath() . '/' . $post['slug'] ?>" class="hover:text-indigo-600">
                                <?= $post['title'] ?>
                            </a>
                        </h2>
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            <?= $post['excerpt'] ?: substr($post['plain_text'], 0, 150) . '...' ?>
                        </p>
                        <a href="<?= Router::getBasePath() . '/' . $post['slug'] ?>" class="text-sm font-bold text-indigo-600 hover:underline">
                            Read Article →
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>

            <!-- Pagination (Basic) -->
            <div class="flex justify-between pt-8">
                <?php if ($page_num > 1): ?>
                    <a href="?page=<?= $page_num - 1 ?>" class="px-4 py-2 bg-white border rounded">← Previous</a>
                <?php else: ?>
                    <span></span>
                <?php endif; ?>
                
                <?php if ($page_num < $total_pages): ?>
                    <a href="?page=<?= $page_num + 1 ?>" class="px-4 py-2 bg-white border rounded">Next →</a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </main>
    
    <!-- Sidebar -->
    <aside class="lg:col-span-1">
        <?php require __DIR__ . '/partials/sidebar.php'; ?>
    </aside>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
```

## 4. Single Article (`single.php`)

```php
<?php
// Parse Data
$data = json_decode($post['data'], true);
$content = $data['content'] ?? [];

require __DIR__ . '/partials/header.php';
?>

<article class="max-w-4xl mx-auto px-4 py-12">
    <header class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-black mb-6 leading-tight"><?= $post['title'] ?></h1>
        <div class="text-gray-500">
            By <span class="text-gray-900 font-bold">Author Name</span> 
            on <time><?= date('F j, Y', strtotime($post['created_at'])) ?></time>
        </div>
    </header>
    
    <?php if (!empty($data['featured_image'])): ?>
    <figure class="mb-12">
        <img src="<?= $data['featured_image'] ?>" class="w-full rounded-2xl shadow-xl" alt="Featured">
    </figure>
    <?php endif; ?>
    
    <div class="prose prose-lg prose-indigo mx-auto">
        <?= render_blocks($content) ?>
    </div>
</article>

<?php require __DIR__ . '/partials/footer.php'; ?>
```
