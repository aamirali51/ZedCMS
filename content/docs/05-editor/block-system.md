# The Block System (Technical)

The Zed Editor uses a modified version of **BlockNote** (built on Prosemirror). It saves content as a structured JSON array.

## Data Structure

Every post content is a JSON array of **Blocks**.

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

## How Rendering Works (`render_blocks`)

The PHP helper `render_blocks()` iterates over this array recursively.

1.  **Switch Case:** Checks `type` (`heading`, `paragraph`, `codeBlock`, `image`, etc.).
2.  **Element Creation:** Maps type to HTML tag (e.g., `heading` -> `h1-h6`).
3.  **Property Injection:** Injects `class`, `id`, `src`.
4.  **Content Expansion:** Loops through the `content` array (text nodes).
    *   Applies inline styles: `bold` -> `<strong>`, `italic` -> `<em>`.
5.  **Recursion:** If the block has `children` (nested blocks), the renderer calls itself.

## Extending the Editor (Future)

To add new blocks (e.g., a "YouTube Embed" block), you need to:
1.  **Frontend (React):** Create a Custom Block Schema in `editor.js`.
2.  **Backend (PHP):** Add a `case 'youtube':` handler in `frontend_addon.php`.

## Sanitation

We do **not** run `strip_tags` on the saved JSON because the JSON structure is inherently safer than raw HTML. However, during rendering:
*   We escape `text` content to prevent XSS.
*   We validate `src` attributes for images.
