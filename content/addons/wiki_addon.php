<?php
/**
 * Wiki Addon - Internal Knowledge Base
 * 
 * Provides a native documentation system within the admin panel.
 * Reads Markdown files from content/docs/ and renders them.
 */

use Core\Event;
use Core\Router;
use Core\Auth;

// =============================================================================
// HELPER FUNCTIONS
// =============================================================================

/**
 * Scan content/docs recursively and return index
 * Format: ['Category Name' => ['slug' => ['title' => 'Title', 'path' => 'Path']]]
 * 
 * @return array
 */
function zed_get_wiki_index(): array
{
    // __DIR__ is content/addons
    // We want content/docs
    $docsDir = dirname(__DIR__) . '/docs';
    if (!is_dir($docsDir)) {
        return [];
    }

    $index = [];
    
    // Get all categories (subdirectories)
    $categories = glob($docsDir . '/*', GLOB_ONLYDIR);
    
    foreach ($categories as $categoryPath) {
        $categoryDirName = basename($categoryPath);
        
        // Convert "01-category-name" to "Category Name"
        $categoryName = preg_replace('/^\d+-/', '', $categoryDirName);
        $categoryName = ucwords(str_replace('-', ' ', $categoryName));
        
        $files = glob($categoryPath . '/*.md');
        $pages = [];
        
        foreach ($files as $filePath) {
            $filename = basename($filePath, '.md');
            
            // Slug is "category/filename"
            $slug = $categoryDirName . '/' . $filename;
            
            // Extract title from filename (fallback)
            $rawTitle = preg_replace('/^\d+-/', '', $filename);
            $title = ucwords(str_replace('-', ' ', $rawTitle));
            
            // Attempt to read first H1 from file for better title
            $content = file_get_contents($filePath);
            if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
                $title = trim($matches[1]);
            }
            
            $pages[$slug] = [
                'title' => $title,
                'path' => $filePath,
                'slug' => $slug
            ];
        }
        
        if (!empty($pages)) {
            $index[$categoryName] = $pages;
        }
    }
    
    return $index;
}

/**
 * Lightweight Markdown Parser
 * Converts a subset of Markdown to HTML without external libraries.
 * 
 * @param string $text Markdown text
 * @return string HTML
 */
function zed_parse_markdown(string $text): string
{
    // 1. Escape HTML first to prevent XSS (basic)
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    
    // 2. Headings
    // H1 - H6
    $text = preg_replace('/^# (.*?)$/m', '<h1 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-4">$1</h1>', $text);
    $text = preg_replace('/^## (.*?)$/m', '<h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 group flex items-center gap-2" id="$1">$1</h2>', $text);
    $text = preg_replace('/^### (.*?)$/m', '<h3 class="text-xl font-bold text-gray-800 mt-6 mb-3">$1</h3>', $text);
    
    // 3. Bold & Italic
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
    
    // 4. Code Blocks (```php ... ```)
    $text = preg_replace_callback('/```(\w+)?\s*\n(.*?)\n```/s', function($matches) {
        $lang = $matches[1] ?? 'text';
        $code = $matches[2]; // Already escaped at step 1
        return '<pre class="bg-slate-900 text-slate-50 p-4 rounded-lg overflow-x-auto mb-6 text-sm font-mono leading-relaxed"><code class="language-' . $lang . '">' . $code . '</code></pre>';
    }, $text);
    
    // 5. Inline Code (`code`)
    $text = preg_replace('/`(.*?)`/', '<code class="bg-gray-100 text-pink-600 px-1.5 py-0.5 rounded text-sm font-mono">$1</code>', $text);
    
    // 6. Blockquotes
    $text = preg_replace('/^> (.*?)$/m', '<blockquote class="border-l-4 border-indigo-500 pl-4 py-2 my-6 bg-indigo-50 text-indigo-900 italic rounded-r">$1</blockquote>', $text);
    
    // 7. Unordered Lists
    // This is a simplified regex approach. For full nested list support, a parser is better,
    // but this works for basic documentation lists.
    $text = preg_replace('/^\* (.*?)$/m', '<li class="ml-4 list-disc">$1</li>', $text);
    $text = preg_replace('/(<\/li>\n?<li)/', '</li><li', $text); // compacted
    // Wrap consecutive lis in ul (tricky with regex, simplified approach: convert all lists to paragraphs for now or just style them)
    // Better Regex for lists:
    $text = preg_replace_callback('/((?:^\* .*?\n)+)/m', function($matches) {
        $items = $matches[1];
        $items = preg_replace('/^\* (.*?)$/m', '<li>$1</li>', $items);
        return '<ul class="list-disc pl-6 space-y-1 mb-6 text-gray-700">' . $items . '</ul>';
    }, $text);
    
    // 8. Ordered Lists
    $text = preg_replace_callback('/((?:^\d+\. .*?\n)+)/m', function($matches) {
        $items = $matches[1];
        $items = preg_replace('/^\d+\. (.*?)$/m', '<li>$1</li>', $items);
        return '<ol class="list-decimal pl-6 space-y-1 mb-6 text-gray-700">' . $items . '</ol>';
    }, $text);

    // 9. Paragraphs
    // Split by double newlines and wrap in <p> if not already a tag
    $lines = explode("\n\n", $text);
    $html = '';
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        // If line starts with a block tag, don't wrap
        if (preg_match('/^<(h\d|ul|ol|pre|blockquote)/', $line)) {
            $html .= $line . "\n";
        } else {
            $html .= '<p class="mb-4 text-gray-700 leading-relaxed">' . nl2br($line) . '</p>' . "\n";
        }
    }
    
    return $html;
}

// =============================================================================
// ROUTES
// =============================================================================

Event::on('route_request', function(array $request) {
    if ($request['uri'] === '/admin/wiki') {
        
        // 1. Check Auth (Admin Only)
        if (!Auth::check() || !zed_current_user_can('view_dashboard')) {
            Router::redirect('/admin/login');
        }
        
        // 2. Get Index
        $wikiIndex = zed_get_wiki_index();
        $currentSlug = $_GET['doc'] ?? null;
        $pageData = null;
        $activeCategory = null;
        
        // 3. Determine Page to Load
        if (!$currentSlug && !empty($wikiIndex)) {
            // Default: First page of first category
            $firstCat = array_key_first($wikiIndex);
            $firstPage = array_key_first($wikiIndex[$firstCat]);
            $currentSlug = $firstPage;
        }
        
        // 4. Find Page Data
        if ($currentSlug) {
            foreach ($wikiIndex as $catName => $pages) {
                if (isset($pages[$currentSlug])) {
                    $activeCategory = $catName;
                    $pageData = $pages[$currentSlug];
                    
                    // Parse Content
                    if (file_exists($pageData['path'])) {
                        $rawMd = file_get_contents($pageData['path']);
                        $pageData['html'] = zed_parse_markdown($rawMd);
                    }
                    break;
                }
            }
        }
        
        // 5. Render
        if (!$pageData) {
            // 404 State
            $pageData = [
                'title' => 'Page Not Found',
                'html' => '<div class="p-8 text-center text-gray-500"><span class="material-symbols-outlined text-4xl mb-2">sentiment_dissatisfied</span><p>The documentation page you requested does not exist.</p></div>',
                'slug' => ''
            ];
        }
        
        // Extract Admin Layout
        $page_title = 'Knowledge Base';
        
        // Fix: Use correct variable name expected by admin-layout.php
        $themePath = dirname(__DIR__) . '/themes/admin-default';
        $content_partial = $themePath . '/partials/wiki-content.php';
        
        $current_page = 'wiki'; // Highlighting for sidebar
        
        // Pass data to view scope
        // Variables: $wikiIndex, $pageData, $activeCategory
        
        require $themePath . '/admin-layout.php';
        Router::setHandled(); // Stop further routing
    }
});
