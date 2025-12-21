# Styling Your Theme

You have complete freedom. Zed CMS does not enforce any CSS framework.

## Option 1: Plain CSS

Create a `style.css` in your theme folder (or `assets/css/style.css`).

Then link it in your `<head>`:

```php
<link rel="stylesheet" href="<?= $base_url ?>/content/themes/my-theme/style.css">
```

> **Note:** Always use `$base_url` (from `Router::getBasePath()`) to ensure your theme works in subdirectories.

## Option 2: Tailwind CSS (CDN)

The fastest way to prototype. Just include the script:

```html
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#FF5733',
                }
            }
        }
    }
</script>
```

## Option 3: Tailwind Build Process (Advanced)

For production, you should run a Node.js build process.

1.  Run `npm init -y` in your theme folder.
2.  Install Tailwind: `npm install -D tailwindcss`
3.  Configure `tailwind.config.js` to scan your PHP files:
    ```js
    module.exports = {
      content: ["./**/*.php"],
      theme: { extend: {} },
      plugins: [],
    }
    ```
4.  Build: `npx tailwindcss -i ./src/input.css -o ./dist/output.css --watch`

## Typography Plugin

Since the editor outputs raw HTML elements (h1, p, ul), you should use the `@tailwindcss/typography` plugin.

```html
<article class="prose lg:prose-xl">
    <?= render_blocks($blocks) ?>
</article>
```

This single class (`prose`) automatically styles all the children elements beautifully.
