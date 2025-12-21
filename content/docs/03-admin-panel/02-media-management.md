# Media Management

Zed CMS includes a sophisticated media handling engine that rivals dedicated DAMs.

## Supported Formats

*   **Images:** JPG, PNG, GIF, WEBP.
*   **Documents:** PDF, TXT (coming soon).

## Automated Optimization Pipeline

When you upload an image (e.g., `huge-photo.jpg` - 5MB):

1.  **Compression:** It is converted to **WebP** format at 80% quality.
2.  **Resizing:** If wider than 1920px, it is downscaled to 1920px.
3.  **Thumbnails:** A `thumb_` version (300px width) is generated for admin grids.
4.  **Original Retention:** The original is renamed `_original.jpg` and kept as a backup.

## The Media Library UI

*   **Grid View:** Shows thumbnails.
*   **Lazy Loading:** Images load as you scroll.
*   **Search:** Filter by filename.
*   **Drag & Drop Area:** Drop files anywhere on the grid to upload.
*   **Click to Copy:** Clicking an image URL instantly copies it to the clipboard.

## Developer API

### `zed_process_upload($tmpPath, $filename, $destination)`
Manually processes a file through the optimization pipeline.

```php
$result = zed_process_upload($_FILES['img']['tmp_name'], 'my-pic.jpg', 'content/uploads');

// Returns:
// [
//    'filename' => 'my-pic.webp',
//    'original' => 'my-pic_original.jpg',
//    'thumb'    => 'thumb_my-pic.webp'
// ]
```
