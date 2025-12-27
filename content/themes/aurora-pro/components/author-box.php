<?php
/**
 * Aurora Pro - Author Box Component
 * 
 * Displays author bio after post content.
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Database;

$authorId = $post['author_id'] ?? null;
if (!$authorId) return;

try {
    $db = Database::getInstance();
    $author = $db->queryOne(
        "SELECT id, email, display_name, bio FROM zed_users WHERE id = :id",
        ['id' => $authorId]
    );
    
    if (!$author) return;
    
    $email = $author['email'];
    $name = $author['display_name'] ?? ucfirst(explode('@', $email)[0]);
    $bio = $author['bio'] ?? 'Author at this website.';
    $avatar = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?s=120&d=mp';
} catch (Exception $e) {
    return;
}
?>

<div class="author-box mt-12 p-6 bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700">
    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
        <!-- Avatar -->
        <img src="<?= htmlspecialchars($avatar) ?>" 
             alt="<?= htmlspecialchars($name) ?>"
             class="w-20 h-20 rounded-full ring-4 ring-white dark:ring-gray-700 shadow-lg">
        
        <!-- Info -->
        <div class="flex-1 text-center sm:text-left">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Written by</p>
            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                <?= htmlspecialchars($name) ?>
            </h4>
            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-4">
                <?= htmlspecialchars($bio) ?>
            </p>
            <a href="#" class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 text-sm font-medium hover:underline">
                View all posts
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</div>
