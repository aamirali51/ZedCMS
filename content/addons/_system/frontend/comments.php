<?php
/**
 * Zed CMS Comments System
 * 
 * Provides threaded comments with moderation workflow.
 * 
 * Functions:
 *   - zed_get_comments($post_id, $args)    - Fetch approved comments
 *   - zed_comment_count($post_id)          - Get comment count
 *   - zed_comment_form($post_id, $args)    - Render comment form
 *   - zed_submit_comment($data)            - Process new comment
 *   - zed_comments_open($post)             - Check if comments enabled
 *   - zed_get_comment($id)                 - Get single comment
 *   - zed_update_comment($id, $data)       - Update comment
 *   - zed_delete_comment($id)              - Delete comment
 *   - zed_moderate_comment($id, $status)   - Change comment status
 * 
 * @package Zed CMS
 * @since 3.2.0
 */

declare(strict_types=1);

use Core\Database;
use Core\Auth;
use Core\Event;

// ============================================================================
// COMMENT RETRIEVAL FUNCTIONS
// ============================================================================

/**
 * Get comments for a post
 * 
 * @param int $post_id Post ID
 * @param array $args Options: status, parent_id, order, limit, offset
 * @return array Array of comment objects
 */
function zed_get_comments(int $post_id, array $args = []): array
{
    $db = Database::getInstance();
    
    $defaults = [
        'status' => 'approved',
        'parent_id' => null,
        'order' => 'ASC',
        'limit' => 100,
        'offset' => 0,
        'threaded' => true,
    ];
    
    $args = array_merge($defaults, $args);
    
    $sql = "SELECT c.*, u.display_name as user_display_name 
            FROM zed_comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.post_id = :post_id";
    
    $params = ['post_id' => $post_id];
    
    if ($args['status']) {
        $sql .= " AND c.status = :status";
        $params['status'] = $args['status'];
    }
    
    if ($args['parent_id'] !== null) {
        $sql .= " AND c.parent_id = :parent_id";
        $params['parent_id'] = $args['parent_id'];
    }
    
    $sql .= " ORDER BY c.created_at " . ($args['order'] === 'DESC' ? 'DESC' : 'ASC');
    $sql .= " LIMIT " . (int)$args['limit'] . " OFFSET " . (int)$args['offset'];
    
    $comments = $db->query($sql, $params);
    
    // Build threaded structure if requested
    if ($args['threaded'] && $args['parent_id'] === null) {
        return _zed_build_comment_tree($comments);
    }
    
    return $comments;
}

/**
 * Build threaded comment tree
 * 
 * @param array $comments Flat array of comments
 * @param int $parent_id Parent ID to start from
 * @return array Nested comment tree
 */
function _zed_build_comment_tree(array $comments, int $parent_id = 0): array
{
    $tree = [];
    
    foreach ($comments as $comment) {
        if ((int)$comment['parent_id'] === $parent_id) {
            $comment['replies'] = _zed_build_comment_tree($comments, (int)$comment['id']);
            $tree[] = $comment;
        }
    }
    
    return $tree;
}

/**
 * Get single comment by ID
 * 
 * @param int $id Comment ID
 * @return array|null Comment data or null
 */
function zed_get_comment(int $id): ?array
{
    $db = Database::getInstance();
    
    $comment = $db->queryOne(
        "SELECT c.*, u.display_name as user_display_name 
         FROM zed_comments c
         LEFT JOIN users u ON c.user_id = u.id
         WHERE c.id = :id",
        ['id' => $id]
    );
    
    return $comment ?: null;
}

/**
 * Get comment count for a post
 * 
 * @param int $post_id Post ID
 * @param string $status Status filter (default: approved)
 * @return int Comment count
 */
function zed_comment_count(int $post_id, string $status = 'approved'): int
{
    $db = Database::getInstance();
    
    $count = $db->queryValue(
        "SELECT COUNT(*) FROM zed_comments WHERE post_id = :post_id AND status = :status",
        ['post_id' => $post_id, 'status' => $status]
    );
    
    return (int)$count;
}

/**
 * Get total comments across all statuses (for admin)
 * 
 * @param string|null $status Filter by status
 * @return int Total count
 */
function zed_total_comments(?string $status = null): int
{
    $db = Database::getInstance();
    
    if ($status) {
        $count = $db->queryValue(
            "SELECT COUNT(*) FROM zed_comments WHERE status = :status",
            ['status' => $status]
        );
    } else {
        $count = $db->queryValue("SELECT COUNT(*) FROM zed_comments WHERE status != 'trash'");
    }
    
    return (int)$count;
}

// ============================================================================
// COMMENT CRUD FUNCTIONS
// ============================================================================

/**
 * Submit a new comment
 * 
 * @param array $data Comment data
 * @return array Result with 'success', 'message', 'comment_id'
 */
function zed_submit_comment(array $data): array
{
    $db = Database::getInstance();
    
    // Validate required fields
    $required = ['post_id', 'content'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            return ['success' => false, 'message' => "Missing required field: {$field}"];
        }
    }
    
    // Get current user if logged in
    $user = Auth::user();
    $user_id = $user ? $user['id'] : null;
    
    // Author info
    if ($user) {
        $author_name = $data['author_name'] ?? $user['display_name'] ?? $user['username'];
        $author_email = $data['author_email'] ?? $user['email'];
    } else {
        // Guest comment - require name and email
        if (empty($data['author_name']) || empty($data['author_email'])) {
            return ['success' => false, 'message' => 'Name and email are required for guest comments'];
        }
        $author_name = $data['author_name'];
        $author_email = $data['author_email'];
    }
    
    // Validate email
    if (!filter_var($author_email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email address'];
    }
    
    // Sanitize content
    $content = strip_tags(trim($data['content']), '<p><br><strong><em><a><code>');
    if (strlen($content) < 2) {
        return ['success' => false, 'message' => 'Comment is too short'];
    }
    
    // Determine initial status
    $moderation_required = zed_get_option('comments_moderation', '1') === '1';
    $status = $moderation_required ? 'pending' : 'approved';
    
    // Auto-approve for logged-in users with approved comments
    if ($user && !$moderation_required) {
        $status = 'approved';
    }
    
    // Allow filtering status
    $status = Event::filter('zed_comment_status', $status, $data, $user);
    
    // Insert comment
    try {
        $pdo = $db->getPdo();
        $stmt = $pdo->prepare("
            INSERT INTO zed_comments 
            (post_id, parent_id, user_id, author_name, author_email, author_url, content, status, ip_address, user_agent)
            VALUES 
            (:post_id, :parent_id, :user_id, :author_name, :author_email, :author_url, :content, :status, :ip_address, :user_agent)
        ");
        
        $stmt->execute([
            'post_id' => (int)$data['post_id'],
            'parent_id' => (int)($data['parent_id'] ?? 0),
            'user_id' => $user_id,
            'author_name' => $author_name,
            'author_email' => $author_email,
            'author_url' => $data['author_url'] ?? null,
            'content' => $content,
            'status' => $status,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
        ]);
        
        $comment_id = (int)$pdo->lastInsertId();
        
        // Trigger event
        Event::trigger('zed_comment_submitted', $comment_id, $data);
        
        $message = $status === 'pending' 
            ? 'Your comment is awaiting moderation.' 
            : 'Comment submitted successfully.';
        
        return [
            'success' => true,
            'message' => $message,
            'comment_id' => $comment_id,
            'status' => $status,
        ];
        
    } catch (\Exception $e) {
        return ['success' => false, 'message' => 'Failed to submit comment: ' . $e->getMessage()];
    }
}

/**
 * Update a comment
 * 
 * @param int $id Comment ID
 * @param array $data Fields to update
 * @return bool Success
 */
function zed_update_comment(int $id, array $data): bool
{
    $db = Database::getInstance();
    
    $allowed = ['content', 'author_name', 'author_email', 'author_url', 'status'];
    $updates = [];
    $params = ['id' => $id];
    
    foreach ($data as $key => $value) {
        if (in_array($key, $allowed)) {
            $updates[] = "{$key} = :{$key}";
            $params[$key] = $value;
        }
    }
    
    if (empty($updates)) {
        return false;
    }
    
    $sql = "UPDATE zed_comments SET " . implode(', ', $updates) . " WHERE id = :id";
    
    try {
        $db->getPdo()->prepare($sql)->execute($params);
        Event::trigger('zed_comment_updated', $id, $data);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * Moderate a comment (change status)
 * 
 * @param int $id Comment ID
 * @param string $status New status: pending, approved, spam, trash
 * @return bool Success
 */
function zed_moderate_comment(int $id, string $status): bool
{
    $valid = ['pending', 'approved', 'spam', 'trash'];
    if (!in_array($status, $valid)) {
        return false;
    }
    
    $result = zed_update_comment($id, ['status' => $status]);
    
    if ($result) {
        Event::trigger('zed_comment_moderated', $id, $status);
    }
    
    return $result;
}

/**
 * Delete a comment permanently
 * 
 * @param int $id Comment ID
 * @return bool Success
 */
function zed_delete_comment(int $id): bool
{
    $db = Database::getInstance();
    
    try {
        // Delete replies first
        $db->getPdo()->prepare("DELETE FROM zed_comments WHERE parent_id = :id")->execute(['id' => $id]);
        
        // Delete the comment
        $db->getPdo()->prepare("DELETE FROM zed_comments WHERE id = :id")->execute(['id' => $id]);
        
        Event::trigger('zed_comment_deleted', $id);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

/**
 * Check if comments are open for a post
 * 
 * @param array $post Post data
 * @return bool
 */
function zed_comments_open(array $post): bool
{
    // Global setting
    if (zed_get_option('comments_enabled', '1') !== '1') {
        return false;
    }
    
    // Per-post setting (stored in data JSON)
    $data = $post['data'] ?? [];
    if (is_string($data)) {
        $data = json_decode($data, true) ?: [];
    }
    
    // Default to open unless explicitly closed
    $comments_open = $data['comments_open'] ?? true;
    
    return (bool)$comments_open;
}

/**
 * Render comments list HTML
 * 
 * @param int $post_id Post ID
 * @param array $args Options
 * @return void Echoes HTML
 */
function zed_comments_list(int $post_id, array $args = []): void
{
    $comments = zed_get_comments($post_id, $args);
    
    if (empty($comments)) {
        echo '<p class="no-comments">No comments yet. Be the first to comment!</p>';
        return;
    }
    
    echo '<div class="comments-list">';
    _zed_render_comment_tree($comments);
    echo '</div>';
}

/**
 * Render comment tree recursively
 * 
 * @param array $comments Comments array
 * @param int $depth Current depth
 */
function _zed_render_comment_tree(array $comments, int $depth = 0): void
{
    foreach ($comments as $comment) {
        $class = 'comment depth-' . $depth;
        $avatar = zed_get_avatar($comment['author_email'], 48);
        $date = date('M j, Y \a\t g:i a', strtotime($comment['created_at']));
        
        echo '<div class="' . $class . '" id="comment-' . $comment['id'] . '">';
        echo '  <div class="comment-header">';
        echo '    <img src="' . $avatar . '" alt="" class="comment-avatar">';
        echo '    <div class="comment-meta">';
        echo '      <span class="comment-author">' . esc_html($comment['author_name']) . '</span>';
        echo '      <span class="comment-date">' . $date . '</span>';
        echo '    </div>';
        echo '  </div>';
        echo '  <div class="comment-content">' . nl2br(esc_html($comment['content'])) . '</div>';
        echo '  <div class="comment-actions">';
        echo '    <a href="#respond" class="comment-reply-link" data-comment-id="' . $comment['id'] . '">Reply</a>';
        echo '  </div>';
        
        // Render replies
        if (!empty($comment['replies'])) {
            echo '<div class="comment-replies">';
            _zed_render_comment_tree($comment['replies'], $depth + 1);
            echo '</div>';
        }
        
        echo '</div>';
    }
}

/**
 * Get Gravatar URL for email
 * 
 * @param string $email Email address
 * @param int $size Size in pixels
 * @return string Gravatar URL
 */
function zed_get_avatar(string $email, int $size = 48): string
{
    $hash = md5(strtolower(trim($email)));
    return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=mp";
}

/**
 * Render comment form HTML
 * 
 * @param int $post_id Post ID
 * @param array $args Options: parent_id, class
 * @return void Echoes HTML
 */
function zed_comment_form(int $post_id, array $args = []): void
{
    $user = Auth::user();
    $parent_id = $args['parent_id'] ?? 0;
    $class = $args['class'] ?? 'comment-form';
    
    $base_url = defined('ZED_BASE_PATH') ? ZED_BASE_PATH : '';
    
    echo '<form id="respond" class="' . esc_html($class) . '" method="post" action="' . $base_url . '/api?action=submit_comment">';
    echo '  <input type="hidden" name="post_id" value="' . $post_id . '">';
    echo '  <input type="hidden" name="parent_id" id="comment_parent_id" value="' . $parent_id . '">';
    
    if (!$user) {
        echo '  <div class="comment-form-fields">';
        echo '    <div class="form-group">';
        echo '      <label for="author_name">Name <span class="required">*</span></label>';
        echo '      <input type="text" name="author_name" id="author_name" required>';
        echo '    </div>';
        echo '    <div class="form-group">';
        echo '      <label for="author_email">Email <span class="required">*</span></label>';
        echo '      <input type="email" name="author_email" id="author_email" required>';
        echo '    </div>';
        echo '    <div class="form-group">';
        echo '      <label for="author_url">Website</label>';
        echo '      <input type="url" name="author_url" id="author_url">';
        echo '    </div>';
        echo '  </div>';
    } else {
        echo '  <p class="logged-in-as">Logged in as <strong>' . esc_html($user['display_name'] ?? $user['username']) . '</strong></p>';
    }
    
    echo '  <div class="form-group">';
    echo '    <label for="comment_content">Comment <span class="required">*</span></label>';
    echo '    <textarea name="content" id="comment_content" rows="5" required></textarea>';
    echo '  </div>';
    
    echo '  <button type="submit" class="btn btn-primary">Post Comment</button>';
    echo '</form>';
    
    // Reply script
    echo '<script>
        document.querySelectorAll(".comment-reply-link").forEach(function(link) {
            link.addEventListener("click", function(e) {
                e.preventDefault();
                var commentId = this.getAttribute("data-comment-id");
                document.getElementById("comment_parent_id").value = commentId;
                document.getElementById("comment_content").focus();
                document.getElementById("respond").scrollIntoView({behavior: "smooth"});
            });
        });
    </script>';
}

/**
 * Escape HTML entities
 * 
 * @param string $string String to escape
 * @return string Escaped string
 */
function esc_html(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// ============================================================================
// ADMIN FUNCTIONS
// ============================================================================

/**
 * Get comments for admin listing
 * 
 * @param array $args Filters: status, search, post_id, page, per_page
 * @return array Comments with pagination info
 */
function zed_admin_get_comments(array $args = []): array
{
    $db = Database::getInstance();
    
    $defaults = [
        'status' => null,
        'search' => null,
        'post_id' => null,
        'page' => 1,
        'per_page' => 20,
    ];
    
    $args = array_merge($defaults, $args);
    
    $where = ["c.status != 'trash'"];
    $params = [];
    
    if ($args['status']) {
        $where[] = "c.status = :status";
        $params['status'] = $args['status'];
    }
    
    if ($args['post_id']) {
        $where[] = "c.post_id = :post_id";
        $params['post_id'] = $args['post_id'];
    }
    
    if ($args['search']) {
        $where[] = "(c.content LIKE :search OR c.author_name LIKE :search OR c.author_email LIKE :search)";
        $params['search'] = '%' . $args['search'] . '%';
    }
    
    $whereClause = implode(' AND ', $where);
    
    // Get total
    $total = (int)$db->queryValue(
        "SELECT COUNT(*) FROM zed_comments c WHERE {$whereClause}",
        $params
    );
    
    // Get comments
    $offset = ($args['page'] - 1) * $args['per_page'];
    $sql = "SELECT c.*, p.title as post_title 
            FROM zed_comments c
            LEFT JOIN zed_content p ON c.post_id = p.id
            WHERE {$whereClause}
            ORDER BY c.created_at DESC
            LIMIT {$args['per_page']} OFFSET {$offset}";
    
    $comments = $db->query($sql, $params);
    
    return [
        'comments' => $comments,
        'total' => $total,
        'pages' => ceil($total / $args['per_page']),
        'current_page' => $args['page'],
    ];
}

/**
 * Get comment counts by status (for admin tabs)
 * 
 * @return array Counts by status
 */
function zed_comment_status_counts(): array
{
    $db = Database::getInstance();
    
    $result = $db->query("SELECT status, COUNT(*) as count FROM zed_comments GROUP BY status");
    
    $counts = [
        'all' => 0,
        'pending' => 0,
        'approved' => 0,
        'spam' => 0,
        'trash' => 0,
    ];
    
    foreach ($result as $row) {
        $counts[$row['status']] = (int)$row['count'];
        if ($row['status'] !== 'trash') {
            $counts['all'] += (int)$row['count'];
        }
    }
    
    return $counts;
}
