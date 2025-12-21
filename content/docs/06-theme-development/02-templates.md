# Single Posts and Pages

When a user visits a URL like `/hello-world` (for a post) or `/about` (for a page), Zed CMS loads specific templates.

## `single.php` (Posts)

Used for content type `post`.

**Variables Available:**
*   `$post`: The entire post array from the database.
    *   `$post['title']`: String
    *   `$post['data']`: The JSON content (needs decoding)
    *   `$post['created_at']`: Timestamp
    *   `$post['author_id']`: Author ID
*   `$author`: The specific author user array (if joined).

### Handling Block Content

The content is stored as robust JSON blocks. To render them as HTML, use the helper `render_blocks()`:

```php
<?php
// Decode the JSON data first
$data = json_decode($post['data'], true);
$contentBlocks = $data['content'] ?? [];
$featuredImage = $data['featured_image'] ?? '';
?>

<article>
    <?php if ($featuredImage): ?>
        <img src="<?= $featuredImage ?>" alt="Hero" />
    <?php endif; ?>

    <h1><?= $post['title'] ?></h1>

    <div class="entry-content">
        <!-- The Magic Renderer -->
        <?= render_blocks($contentBlocks) ?>
    </div>
</article>
```

## `page.php` (Pages)

Technically identical to `single.php` but used for content type `page`.

If `page.php` does not exist in your theme folder, Zed will try to use `single.php`. If `single.php` implies "blog post" styling (dates, authors), you definitely want a separate `page.php` for static content like "Contact Us" or "Privacy Policy".

## `404.php` (Not Found)

Loaded when no content matches the slug.

```php
<h1>404 - Not Found</h1>
<p>Sorry, possibly the page was deleted.</p>
<a href="/">Go Home</a>
```
