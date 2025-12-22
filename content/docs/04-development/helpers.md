# Helper Function Reference

Zed CMS provides **70+ theme helper functions** organized into 11 logical files in `content/addons/frontend/`. All helpers follow **pure function principles**: they take explicit parameters and return values (no globals, no side effects).

---

## Content Retrieval

### `zed_get_post(int $id): ?array`
Get a single post by ID.

### `zed_get_post_by_slug(string $slug, ?string $type = null): ?array`
Get a single post by slug, optionally filtered by content type.

### `zed_get_posts(array $args = []): array`
Get multiple posts with flexible query options.

```php
$posts = zed_get_posts([
    'type' => 'post',           // Content type
    'status' => 'published',    // draft|published|all
    'limit' => 10,
    'offset' => 0,
    'orderby' => 'created_at',  // created_at|updated_at|title
    'order' => 'DESC',
    'category' => null,         // Category slug or ID
    'author' => null,           // Author ID
    'search' => null,           // Search term
]);
```

### `zed_get_pages(array $args = []): array`
Shortcut for `zed_get_posts(['type' => 'page', ...])`.

### `zed_count_posts(array $args = []): int`
Count posts matching criteria.

---

## Data Extraction

All these functions take a `$post` array (from `zed_get_post()`) as their first argument.

### `zed_get_title(array $post): string`
Get post title.

### `zed_get_excerpt(array $post, int $length = 160): string`
Get post excerpt (from data or auto-generated from content).

### `zed_get_content(array $post): string`
Get rendered HTML content from BlockNote blocks.

### `zed_get_slug(array $post): string`
Get post slug.

### `zed_get_permalink(array $post): string`
Get full URL to post.

### `zed_get_status(array $post): string`
Get status (`draft` or `published`).

### `zed_get_type(array $post): string`
Get content type.

### `zed_get_created_date(array $post, string $format = 'M j, Y'): string`
Get formatted creation date.

### `zed_get_updated_date(array $post, string $format = 'M j, Y'): string`
Get formatted update date.

---

## Featured Images

### `zed_get_featured_image(array $post): ?string`
Get featured image URL or null.

### `zed_get_thumbnail(array $post): ?string`
Get thumbnail URL (looks for `thumb_` prefixed version).

### `zed_has_featured_image(array $post): bool`
Check if post has featured image.

### `zed_featured_image(array $post, array $attrs = []): string`
Render featured image as `<img>` tag.

```php
echo zed_featured_image($post, [
    'class' => 'rounded-lg shadow',
    'alt' => 'Custom alt text',
    'loading' => 'lazy'
]);
```

---

## Authors

### `zed_get_author(int $userId): ?array`
Get author data by user ID.

### `zed_get_post_author(array $post): ?array`
Get author of a specific post.

### `zed_get_author_name(int $userId): string`
Get author display name.

### `zed_get_author_avatar(int $userId, int $size = 64): string`
Get Gravatar URL for user.

---

## Categories

### `zed_get_categories(array $args = []): array`
Get all categories.

### `zed_get_category(int|string $idOrSlug): ?array`
Get single category by ID or slug.

### `zed_get_post_categories(array $post): array`
Get categories for a specific post.

### `zed_category_link(array $category): string`
Get category archive URL.

---

## Pagination

### `zed_get_pagination(int $current, int $total, int $perPage = 10): array`
Calculate pagination data.

Returns array with: `current`, `total_pages`, `total_items`, `has_prev`, `has_next`, `prev_url`, `next_url`.

### `zed_pagination(int $current, int $total, int $perPage = 10, array $opts = []): string`
Render pagination HTML.

### `zed_get_adjacent_post(array $post, bool $previous = true): ?array`
Get previous or next post.

---

## Utilities

### `zed_reading_time(string|array $content): int`
Calculate reading time in minutes.

### `zed_word_count(string|array $content): int`
Get word count.

### `zed_time_ago(string $datetime): string`
Format datetime as relative time (e.g., "2 hours ago").

### `zed_truncate(string $text, int $length = 160, string $suffix = '...'): string`
Truncate text at word boundary.

### `zed_share_urls(array $post): array`
Get social share URLs.

```php
$urls = zed_share_urls($post);
// ['twitter' => '...', 'facebook' => '...', 'linkedin' => '...', ...]
```

---

## SEO & Meta

### `zed_meta_tags(array $post = []): string`
Generate all meta tags (description, canonical, OG, Twitter).

### `zed_og_tags(array $post = []): string`
Generate Open Graph meta tags.

### `zed_schema_markup(array $post = []): string`
Generate JSON-LD schema markup.

### `zed_canonical_url(): string`
Get canonical URL for current page.

---

## Conditionals

### `zed_is_home(): bool`
Check if current page is homepage.

### `zed_is_single(): bool`
Check if current page is single post/page.

### `zed_is_page(?string $slug = null): bool`
Check if current page is a page (optionally specific slug).

### `zed_is_archive(): bool`
Check if current page is archive/listing.

### `zed_is_category(?string $slug = null): bool`
Check if current page is category archive.

### `zed_is_logged_in(): bool`
Check if user is logged in.

### `zed_is_admin_user(): bool`
Check if current user is admin.

---

## URLs & Assets

### `zed_theme_url(string $path = ''): string`
Get theme asset URL.

### `zed_uploads_url(string $path = ''): string`
Get uploads URL.

### `zed_base_url(string $path = ''): string`
Get site base URL.

### `zed_admin_url(string $path = ''): string`
Get admin URL.

### `zed_edit_url(array|int $postOrId): string`
Get editor URL for a post.

---

## Related Content

### `zed_get_related_posts(array $post, int $limit = 4): array`
Get related posts based on shared categories.

### `zed_get_featured_posts(int $limit = 3, ?string $type = null): array`
Get posts marked as featured.

### `zed_get_popular_posts(int $limit = 5, ?string $type = null): array`
Get popular posts (requires analytics addon for real data).

---

## Complete Theme Example

```php
<?php $posts = zed_get_posts(['limit' => 10]); ?>

<?php foreach ($posts as $post): ?>
<article class="post-card">
    <?php if (zed_has_featured_image($post)): ?>
        <?= zed_featured_image($post, ['class' => 'w-full h-48 object-cover']) ?>
    <?php endif; ?>
    
    <div class="p-4">
        <span class="text-sm text-gray-500">
            <?= zed_get_created_date($post) ?> · <?= zed_reading_time(zed_get_content($post)) ?> min read
        </span>
        
        <h2 class="text-xl font-bold">
            <a href="<?= zed_get_permalink($post) ?>"><?= zed_get_title($post) ?></a>
        </h2>
        
        <p><?= zed_get_excerpt($post, 120) ?></p>
        
        <div class="categories">
            <?php foreach (zed_get_post_categories($post) as $cat): ?>
                <a href="<?= $cat['url'] ?>"><?= $cat['name'] ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</article>
<?php endforeach; ?>

<?= zed_pagination($_GET['page'] ?? 1, zed_count_posts(), 10) ?>
```
