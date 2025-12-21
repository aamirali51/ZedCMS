# Admin Features Overview

The Admin Panel (`/admin`) is the command center of Zed CMS. It is fully responsive and built with Tailwind CSS.

## The Dashboard (`/admin`)

The first screen you see.

### 1. Health Status
Top-left widget. It runs real-time checks every time you load the page:
*   **PHP Version:** Warns if < 8.2.
*   **Uploads Writable:** Critical for media.
*   **Production Mode:** Checks if `debug_mode` is off.
*   **SEO Status:** Checks if search engines are indexed.

### 2. Activity Feed (Jump Back In)
Shows the last 5 content items updated across the system. Useful for quickly resuming work.

### 3. Quick Draft
A lightning-fast way to capture ideas. Type a title, hit Enter, and it creates a draft Post and redirects you to the editor.

## The Content Screen (`/admin/content`)

Lists all Posts, Pages, and Custom content types.

*   **Tabs:** "All", "Published", "Drafts".
*   **Search:** Real-time filtering by title.
*   **Columns:** Title, Author, Status, Date.
*   **Actions:** Hover over a row to see "Edit", "View", "Delete".

## The Editor (`/admin/editor?id=X`)

Uses a Notion-style block interface.
*   **Slash Command:** Type `/` to open the block menu.
*   **Drag & Drop:** Drag the handle (::) to reorder blocks.
*   **Media:** Drag images directly onto the canvas.

## Settings (`/admin/settings`)

The unified configuration panel.
*   **General:** Site Title, Tagline.
*   **SEO:** Meta Description, Social Images.
*   **System:** Maintenance Mode, Debug Mode.
