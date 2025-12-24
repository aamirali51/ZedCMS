# The BlockNote Editor (Technical)

> **Updated:** 2025-12-23 | **Version:** 2.6.0

Zed CMS uses **BlockNote** as its content editor - a modern, Notion-style block editor built on React and ProseMirror.

## Features

- **📦 Block-based:** Content is structured as JSON blocks, not HTML soup
- **🎨 Theming:** Dynamic light/dark mode with CSS variables
- **📱 Touch-friendly:** Works great on mobile devices
- **⬆️ Media Upload:** Integrated with Zed's media library
- **↕️ Drag & Drop:** Reorder blocks easily

---

## Data Structure

Every post's content is stored as a JSON array of **Blocks** in the `data.content` field:

```json
[
  {
    "id": "b1a2c3d4",
    "type": "heading",
    "props": {
      "level": 2,
      "textColor": "default",
      "backgroundColor": "default",
      "textAlignment": "left"
    },
    "content": [
      {
        "type": "text",
        "text": "The Architecture",
        "styles": { "bold": true }
      }
    ],
    "children": []
  },
  {
    "id": "x9y8z7",
    "type": "paragraph",
    "props": {},
    "content": [
      {
        "type": "text",
        "text": "This is a simple paragraph.",
        "styles": {}
      }
    ]
  }
]
```

---

## Supported Block Types

| Type | HTML Output | Description |
|------|-------------|-------------|
| `paragraph` | `<p>` | Basic text block |
| `heading` | `<h1>` - `<h6>` | Headings (level 1-6) |
| `bulletListItem` | `<ul><li>` | Bullet list |
| `numberedListItem` | `<ol><li>` | Numbered list |
| `image` | `<img>` | Image with optional caption |
| `codeBlock` | `<pre><code>` | Code snippet |
| `table` | `<table>` | Data table |
| `video` | `<video>` | Video embed |
| `audio` | `<audio>` | Audio player |
| `file` | `<a>` | File download link |

---

## PHP Rendering (`render_blocks`)

The `render_blocks()` function in `frontend_addon.php` converts BlockNote JSON to HTML:

```php
function render_blocks(array|string $blocks): string
{
    if (is_string($blocks)) {
        $blocks = json_decode($blocks, true);
    }

    $html = '';
    foreach ($blocks as $block) {
        $type = $block['type'];
        $content = $block['content'];
        $props = $block['props'];

        switch ($type) {
            case 'paragraph':
                $html .= "<p>" . render_inline_content($content) . "</p>\n";
                break;
            
            case 'heading':
                $level = $props['level'] ?? 2;
                $html .= "<h{$level}>" . render_inline_content($content) . "</h{$level}>\n";
                break;
            
            // ... other block types
        }
    }
    return $html;
}
```

### Inline Content Rendering

Text styles are converted to HTML:
- `bold` → `<strong>`
- `italic` → `<em>`
- `underline` → `<u>`
- `strikethrough` → `<s>`
- `code` → `<code>`
- `textColor` → `<span style="color: ...">`
- `backgroundColor` → `<span style="background-color: ...">`

---

## Theming

BlockNote uses CSS variables for theming. The editor automatically syncs with the admin panel's dark/light mode.

### CSS Variables

```css
/* Light Mode */
.bn-container[data-color-scheme="light"] {
    --bn-colors-editor-text: #1e293b;
    --bn-colors-editor-background: #ffffff;
    --bn-colors-selected-background: #6366f1;
    --bn-colors-border: #e2e8f0;
}

/* Dark Mode */
.bn-container[data-color-scheme="dark"] {
    --bn-colors-editor-text: #e2e8f0;
    --bn-colors-editor-background: #0f172a;
    --bn-colors-selected-background: #6366f1;
    --bn-colors-border: #334155;
}
```

---

## Building the Editor

```bash
# Navigate to frontend source
cd _frontend

# Install dependencies
npm install

# Build the bundle
npm run build
```

Output files:
- `content/themes/admin-default/assets/editor.bundle.js`
- `content/themes/admin-default/assets/editor.bundle.css`

---

## Extending the Editor

To add a custom block type (e.g., "YouTube Embed"):

### 1. Frontend (React)

Create a custom block schema in `blocknote-editor.jsx`:

```jsx
const YouTubeBlock = {
    type: "youtube",
    propSchema: {
        videoId: { default: "" }
    },
    content: "none"
};
```

### 2. Backend (PHP)

Add a case handler in `frontend_addon.php`:

```php
case 'youtube':
    $videoId = htmlspecialchars($props['videoId'] ?? '');
    if ($videoId) {
        $html .= "<iframe src=\"https://www.youtube.com/embed/{$videoId}\" 
                          allowfullscreen></iframe>\n";
    }
    break;
```

---

## Security

- **No `strip_tags`:** JSON structure is inherently safer than raw HTML
- **XSS Prevention:** All text content is escaped via `htmlspecialchars()`
- **URL Validation:** Image/video sources are sanitized
- **CSRF Protection:** Save operations require valid session

---

## Troubleshooting

### Editor Not Loading
1. Check browser console for JavaScript errors
2. Verify `editor.bundle.js` exists
3. Ensure `#blocknote-root` container is present

### Styles Broken
1. Verify CSS variables are defined
2. Check for Tailwind conflicts
3. Rebuild with `npm run build`

### Save Not Working
1. Check `window.ZedEditor.getContent()` in console
2. Verify AJAX response in Network tab
3. Check PHP error logs

---

*Part of Zed CMS Wiki | Version 2.6.0*
