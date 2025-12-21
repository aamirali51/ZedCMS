# Performance Optimization

Zed CMS is fast by default, but content heaviness can slow it down.

## 1. Object Caching

Currently, Zed uses static PHP variables for caching within a single request (e.g., `zed_get_option`).

**Future Roadmap:** We plan to implement a Redis or File-based object cache driver in `Core\Cache`.

## 2. Image Optimization

The Media Library (`admin_addon.php`) automatically:
1.  **Resizes** images larger than 1920px width.
2.  **Converts** uploads to WebP format (approx 80% quality).
3.  **Generates** 300px wide thumbnails (`thumb_ filename.webp`).

**Developer Tip:** Always use the thumbnail version in grids or lists to save bandwidth.

```php
<img src="/content/uploads/thumb_my-image.webp" loading="lazy">
```

## 3. Database Indexing

The `zed_content` table relies heavily on JSON data. MySQL 5.7+ supports JSON indexing, but we currently use `Virtual Generated Columns` for high-performance filtering.

Example schema usage:
*   `type` (indexed) - Filter by 'post', 'page', 'attachment'.
*   `slug` (indexed) - Fast lookup for routing.
*   `created_at` (indexed) - Sort by date.
*   `data` (JSON) - Stores flexible content.
