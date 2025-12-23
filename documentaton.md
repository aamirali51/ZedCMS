# Zed CMS — BlockNote Editor Documentation

> **Editor:** BlockNote (React-based)  
> **Version:** 2.6.0  
> **Last Updated:** 2025-12-23

---

## 🎯 Overview

Zed CMS uses **BlockNote** as its content editor. BlockNote is a modern, Notion-style block editor built on React and ProseMirror.

### Why BlockNote?

| Feature | BlockNote | Traditional WYSIWYG |
|---------|-----------|---------------------|
| Data Format | Structured JSON | HTML tag soup |
| Portability | Easy API rendering | Complex parsing |
| Extensibility | React components | Plugin hell |
| Dark Mode | Built-in theming | Manual CSS hacks |
| Mobile UX | Touch-friendly | Desktop-centric |

---

## 📁 File Structure

```
_frontend/
├── package.json              # Dependencies (React, BlockNote, Mantine)
├── vite.config.js            # Build configuration → editor.bundle.js
└── src/
    ├── main.jsx              # Entry point, mounts editor to #blocknote-root
    ├── index.css             # BlockNote CSS variables, theming
    └── components/
        └── blocknote-editor.jsx  # Main editor component
```

**Build Output:**
```
content/themes/admin-default/assets/
├── editor.bundle.js          # Compiled React bundle
└── editor.bundle.css         # Compiled styles
```

---

## 🔧 Building the Editor

```bash
cd _frontend
npm install
npm run build
```

This compiles the React editor and outputs to the admin theme's assets folder.

---

## 📝 BlockNote Data Format

Content is stored as JSON in the `data.content` field of `zed_content` table.

### Example Block Structure

```json
[
  {
    "id": "abc123",
    "type": "paragraph",
    "props": {
      "textColor": "default",
      "backgroundColor": "default",
      "textAlignment": "left"
    },
    "content": [
      {
        "type": "text",
        "text": "Hello World",
        "styles": {}
      }
    ],
    "children": []
  },
  {
    "id": "def456",
    "type": "heading",
    "props": {
      "level": 2,
      "textAlignment": "left"
    },
    "content": [
      {
        "type": "text",
        "text": "Section Title",
        "styles": { "bold": true }
      }
    ],
    "children": []
  }
]
```

### Supported Block Types

| Type | Description | Props |
|------|-------------|-------|
| `paragraph` | Basic text | textAlignment, textColor, backgroundColor |
| `heading` | H1-H6 | level (1-6), textAlignment |
| `bulletListItem` | Bullet list | textAlignment |
| `numberedListItem` | Numbered list | textAlignment |
| `image` | Image block | url, caption, width |
| `codeBlock` | Code snippet | language |
| `table` | Data table | rows, columns |
| `video` | Video embed | url |
| `audio` | Audio player | url |
| `file` | File download | url, name |

---

## 🎨 Theming

BlockNote uses CSS variables for theming. These are defined in `_frontend/src/index.css` and `editor.php`.

### Light Mode Variables

```css
.bn-container[data-color-scheme="light"] {
    --bn-colors-editor-text: #1e293b;
    --bn-colors-editor-background: #ffffff;
    --bn-colors-menu-text: #374151;
    --bn-colors-menu-background: #ffffff;
    --bn-colors-tooltip-text: #1e293b;
    --bn-colors-tooltip-background: #f8fafc;
    --bn-colors-hovered-text: #1e293b;
    --bn-colors-hovered-background: #f1f5f9;
    --bn-colors-selected-text: #ffffff;
    --bn-colors-selected-background: #6366f1;
    --bn-colors-disabled-text: #9ca3af;
    --bn-colors-disabled-background: #f3f4f6;
    --bn-colors-shadow: rgba(0, 0, 0, 0.1);
    --bn-colors-border: #e2e8f0;
    --bn-colors-side-menu: #64748b;
    --bn-colors-highlights-gray-text: #374151;
    --bn-colors-highlights-gray-background: #f3f4f6;
    --bn-font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    --bn-border-radius: 8px;
}
```

### Dark Mode Variables

```css
.bn-container[data-color-scheme="dark"] {
    --bn-colors-editor-text: #e2e8f0;
    --bn-colors-editor-background: #0f172a;
    --bn-colors-menu-text: #e2e8f0;
    --bn-colors-menu-background: #1e293b;
    --bn-colors-tooltip-text: #f8fafc;
    --bn-colors-tooltip-background: #334155;
    --bn-colors-hovered-text: #f8fafc;
    --bn-colors-hovered-background: #334155;
    --bn-colors-selected-text: #ffffff;
    --bn-colors-selected-background: #6366f1;
    --bn-colors-disabled-text: #64748b;
    --bn-colors-disabled-background: #1e293b;
    --bn-colors-shadow: rgba(0, 0, 0, 0.3);
    --bn-colors-border: #334155;
    --bn-colors-side-menu: #94a3b8;
}
```

### Dynamic Theme Detection

The editor automatically detects dark mode from the admin panel:

```jsx
useEffect(() => {
    const checkTheme = () => {
        const isDark = document.documentElement.classList.contains('dark');
        setTheme(isDark ? "dark" : "light");
    };
    
    checkTheme();
    
    const observer = new MutationObserver(checkTheme);
    observer.observe(document.documentElement, { 
        attributes: true, 
        attributeFilter: ['class'] 
    });
    
    return () => observer.disconnect();
}, []);
```

---

## 🖼️ Image Uploads

Images are uploaded via the media library API:

```jsx
uploadFile: async (file) => {
    const formData = new FormData();
    formData.append('file', file);
    
    const response = await fetch(`${window.ZED_BASE_URL || ''}/admin/api/media/upload`, {
        method: 'POST',
        body: formData
    });
    
    const data = await response.json();
    return data.file_url;
}
```

---

## 🔄 PHP Rendering

The `render_blocks()` function in `frontend_addon.php` converts BlockNote JSON to HTML:

```php
function render_blocks(array|string $blocks): string
{
    // Parse JSON if string
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

---

## 📚 Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| `@blocknote/core` | ^0.22.0 | Core editor functionality |
| `@blocknote/react` | ^0.22.0 | React integration |
| `@blocknote/mantine` | ^0.22.0 | Mantine UI components |
| `@mantine/core` | ^7.x | UI framework |
| `react` | ^18.x | React library |
| `react-dom` | ^18.x | React DOM |
| `vite` | ^5.x | Build tool |

---

## 🐛 Troubleshooting

### Editor Not Loading

1. Check browser console for errors
2. Verify `editor.bundle.js` exists in admin theme assets
3. Ensure `#blocknote-root` container exists
4. Check if `window.ZED_INITIAL_CONTENT` is valid JSON

### Styles Not Applying

1. Check if `editor.bundle.css` is loaded
2. Verify CSS variables are defined
3. Look for conflicting Tailwind utilities

### Save Not Working

1. Check `window.ZedEditor.getContent()` in console
2. Verify AJAX endpoint is correct
3. Check PHP error logs for save handler issues

---

*This documentation is part of Zed CMS v2.6.0*