<?php
/**
 * Comments Admin Page Template
 * 
 * @package Zed CMS Admin
 * @since 3.2.0
 */

// Get current filter
$status = $_GET['status'] ?? null;
$page = max(1, (int)($_GET['page'] ?? 1));
$search = $_GET['search'] ?? '';

// Get counts for tabs
$counts = zed_comment_status_counts();

// Get comments
$result = zed_admin_get_comments([
    'status' => $status,
    'search' => $search,
    'page' => $page,
    'per_page' => 20,
]);

$comments = $result['comments'];
$total_pages = $result['pages'];
$base_url = \Core\Router::getBasePath();
?>

<div class="admin-header">
    <div class="admin-header-title">
        <h1>Comments</h1>
        <p class="subtitle">Manage and moderate comments on your content</p>
    </div>
</div>

<!-- Status Tabs -->
<div class="status-tabs">
    <a href="<?= $base_url ?>/admin/comments" class="tab <?= !$status ? 'active' : '' ?>">
        All <span class="count">(<?= $counts['all'] ?>)</span>
    </a>
    <a href="<?= $base_url ?>/admin/comments?status=pending" class="tab <?= $status === 'pending' ? 'active' : '' ?>">
        Pending <span class="count">(<?= $counts['pending'] ?>)</span>
    </a>
    <a href="<?= $base_url ?>/admin/comments?status=approved" class="tab <?= $status === 'approved' ? 'active' : '' ?>">
        Approved <span class="count">(<?= $counts['approved'] ?>)</span>
    </a>
    <a href="<?= $base_url ?>/admin/comments?status=spam" class="tab <?= $status === 'spam' ? 'active' : '' ?>">
        Spam <span class="count">(<?= $counts['spam'] ?>)</span>
    </a>
    <a href="<?= $base_url ?>/admin/comments?status=trash" class="tab <?= $status === 'trash' ? 'active' : '' ?>">
        Trash <span class="count">(<?= $counts['trash'] ?>)</span>
    </a>
</div>

<!-- Search -->
<div class="admin-toolbar">
    <form class="search-form" method="get" action="<?= $base_url ?>/admin/comments">
        <?php if ($status): ?>
            <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
        <?php endif; ?>
        <input type="search" name="search" placeholder="Search comments..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-secondary">Search</button>
    </form>
</div>

<!-- Comments Table -->
<div class="data-table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th class="col-author">Author</th>
                <th class="col-comment">Comment</th>
                <th class="col-post">In Response To</th>
                <th class="col-date">Submitted</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($comments)): ?>
                <tr>
                    <td colspan="4" class="empty-state">
                        <span class="material-symbols-outlined">comments_disabled</span>
                        <p>No comments found</p>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <?php
                    $avatar = zed_get_avatar($comment['author_email'], 40);
                    $date = date('M j, Y \a\t g:i a', strtotime($comment['created_at']));
                    $excerpt = mb_substr(strip_tags($comment['content']), 0, 100);
                    if (strlen($comment['content']) > 100) $excerpt .= '...';
                    ?>
                    <tr class="comment-row status-<?= $comment['status'] ?>" data-id="<?= $comment['id'] ?>">
                        <td class="col-author">
                            <div class="author-info">
                                <img src="<?= $avatar ?>" alt="" class="avatar">
                                <div class="author-details">
                                    <strong><?= htmlspecialchars($comment['author_name']) ?></strong>
                                    <small><?= htmlspecialchars($comment['author_email']) ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="col-comment">
                            <div class="comment-content">
                                <?php if ($comment['status'] === 'pending'): ?>
                                    <span class="badge badge-warning">Pending</span>
                                <?php elseif ($comment['status'] === 'spam'): ?>
                                    <span class="badge badge-danger">Spam</span>
                                <?php endif; ?>
                                <p><?= htmlspecialchars($excerpt) ?></p>
                            </div>
                            <div class="row-actions">
                                <?php if ($comment['status'] !== 'approved'): ?>
                                    <button class="action-btn action-approve" data-action="approve" data-id="<?= $comment['id'] ?>">Approve</button>
                                <?php endif; ?>
                                <?php if ($comment['status'] !== 'spam'): ?>
                                    <button class="action-btn action-spam" data-action="spam" data-id="<?= $comment['id'] ?>">Spam</button>
                                <?php endif; ?>
                                <?php if ($comment['status'] !== 'trash'): ?>
                                    <button class="action-btn action-trash" data-action="trash" data-id="<?= $comment['id'] ?>">Trash</button>
                                <?php else: ?>
                                    <button class="action-btn action-restore" data-action="pending" data-id="<?= $comment['id'] ?>">Restore</button>
                                    <button class="action-btn action-delete" data-action="delete" data-id="<?= $comment['id'] ?>">Delete Permanently</button>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="col-post">
                            <?php if ($comment['post_title']): ?>
                                <a href="<?= $base_url ?>/admin/content/edit?id=<?= $comment['post_id'] ?>">
                                    <?= htmlspecialchars($comment['post_title']) ?>
                                </a>
                            <?php else: ?>
                                <em>Post deleted</em>
                            <?php endif; ?>
                        </td>
                        <td class="col-date">
                            <time datetime="<?= $comment['created_at'] ?>"><?= $date ?></time>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <?php
        $url = $base_url . '/admin/comments?page=' . $i;
        if ($status) $url .= '&status=' . urlencode($status);
        if ($search) $url .= '&search=' . urlencode($search);
        ?>
        <a href="<?= $url ?>" class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>

<script>
// Comment moderation actions
document.querySelectorAll('.action-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
        const action = this.dataset.action;
        const id = this.dataset.id;
        const row = this.closest('tr');
        
        if (action === 'delete' && !confirm('Permanently delete this comment?')) {
            return;
        }
        
        try {
            const response = await fetch('<?= $base_url ?>/admin/api?action=moderate_comment', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ id, status: action })
            });
            
            const result = await response.json();
            
            if (result.success) {
                if (action === 'delete') {
                    row.remove();
                } else {
                    // Reload page to update counts
                    location.reload();
                }
            } else {
                alert(result.message || 'Action failed');
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    });
});
</script>

<style>
.status-tabs {
    display: flex;
    gap: 0;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.status-tabs .tab {
    padding: 0.75rem 1rem;
    color: var(--text-muted);
    text-decoration: none;
    border-bottom: 2px solid transparent;
    margin-bottom: -1px;
    transition: all 0.2s;
}

.status-tabs .tab:hover {
    color: var(--text-color);
}

.status-tabs .tab.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
    font-weight: 500;
}

.status-tabs .count {
    opacity: 0.7;
}

.admin-toolbar {
    margin-bottom: 1rem;
}

.search-form {
    display: flex;
    gap: 0.5rem;
    max-width: 400px;
}

.search-form input {
    flex: 1;
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius);
    background: var(--input-bg);
}

.comment-row.status-pending {
    background: rgba(234, 179, 8, 0.1);
}

.comment-row.status-spam {
    background: rgba(239, 68, 68, 0.05);
}

.col-author {
    width: 200px;
}

.author-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.author-info .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.author-details {
    display: flex;
    flex-direction: column;
}

.author-details small {
    color: var(--text-muted);
    font-size: 0.75rem;
}

.col-comment {
    max-width: 400px;
}

.comment-content p {
    margin: 0.25rem 0 0.5rem;
    color: var(--text-color);
}

.row-actions {
    display: none;
}

.comment-row:hover .row-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    background: none;
    border: none;
    color: var(--primary);
    font-size: 0.75rem;
    cursor: pointer;
    padding: 0;
}

.action-btn:hover {
    text-decoration: underline;
}

.action-spam, .action-trash, .action-delete {
    color: var(--danger);
}

.action-approve, .action-restore {
    color: var(--success);
}

.badge {
    display: inline-block;
    padding: 0.15rem 0.5rem;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 500;
    text-transform: uppercase;
}

.badge-warning {
    background: rgba(234, 179, 8, 0.2);
    color: #b45309;
}

.badge-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #dc2626;
}

.col-date time {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--text-muted);
}

.empty-state .material-symbols-outlined {
    font-size: 48px;
    opacity: 0.3;
}

.pagination {
    display: flex;
    gap: 0.25rem;
    justify-content: center;
    margin-top: 1.5rem;
}

.page-link {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius);
    text-decoration: none;
    color: var(--text-color);
}

.page-link.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
</style>
