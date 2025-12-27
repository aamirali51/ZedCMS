<?php
/**
 * Aurora Pro - Related Posts Component
 * 
 * Displays related posts after content.
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Database;
use Core\Router;

$base_url = Router::getBasePath();
$currentId = $post['id'] ?? 0;
$postType = $post['type'] ?? 'post';

// Get related posts (same type, different ID)
try {
    $db = Database::getInstance();
    $related = $db->query(
        "SELECT id, title, slug, data, created_at 
         FROM zed_content 
         WHERE type = :type AND id != :id AND status = 'published'
         ORDER BY created_at DESC 
         LIMIT 3",
        ['type' => $postType, 'id' => $currentId]
    );
} catch (Exception $e) {
    $related = [];
}

if (empty($related)) return;
?>

<div class="related-posts mt-16 pt-10 border-t border-gray-200 dark:border-gray-700">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">
        Related Articles
    </h3>
    
    <div class="grid md:grid-cols-3 gap-6">
        <?php foreach ($related as $relatedPost): 
            $data = is_string($relatedPost['data'] ?? '') 
                ? json_decode($relatedPost['data'], true) 
                : ($relatedPost['data'] ?? []);
            $featuredImage = $data['featured_image'] ?? '';
        ?>
        <article class="group">
            <!-- Image -->
            <div class="aspect-[16/10] rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 mb-4">
                <?php if ($featuredImage): ?>
                <img src="<?= htmlspecialchars($featuredImage) ?>" 
                     alt="<?= htmlspecialchars($relatedPost['title']) ?>"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <?php else: ?>
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-gray-700 dark:to-gray-600">
                    <span class="text-3xl font-bold text-indigo-200 dark:text-gray-500">
                        <?= strtoupper(substr($relatedPost['title'], 0, 1)) ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Content -->
            <time class="text-xs text-gray-500 dark:text-gray-400">
                <?= date('M j, Y', strtotime($relatedPost['created_at'])) ?>
            </time>
            <h4 class="font-bold text-gray-900 dark:text-white mt-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
                <a href="<?= $base_url . '/' . htmlspecialchars($relatedPost['slug']) ?>">
                    <?= htmlspecialchars($relatedPost['title']) ?>
                </a>
            </h4>
        </article>
        <?php endforeach; ?>
    </div>
</div>
