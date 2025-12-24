<?php
/**
 * Zed CMS — Admin Helper Functions
 * 
 * Utility functions for content processing and media handling.
 * 
 * @package ZedCMS\Admin
 */

declare(strict_types=1);

use Core\Database;

// =============================================================================
// CONTENT HELPERS
// =============================================================================

/**
 * Recursively extract plain text from BlockNote blocks
 * Used for building a searchable 'plain_text' index
 */
function extract_text_from_blocks(array $blocks): string 
{
    $textSegments = [];
    
    foreach ($blocks as $block) {
        if (!is_array($block)) continue;
        
        // 1. Text from 'content' property
        if (isset($block['content'])) {
            if (is_array($block['content'])) {
                // Standard inline content array
                foreach ($block['content'] as $inline) {
                    // Plain text
                    if (isset($inline['type']) && $inline['type'] === 'text') {
                        $textSegments[] = $inline['text'] ?? ''; 
                    }
                    // Links
                    if (isset($inline['type']) && $inline['type'] === 'link') {
                         foreach ($inline['content'] ?? [] as $linkContent) {
                             $textSegments[] = $linkContent['text'] ?? '';
                         }
                    }
                }
            } elseif (is_string($block['content'])) {
                 // Fallback for simple blocks
                 $textSegments[] = $block['content'];
            }
        }
        
        // 2. Recursion for children (nested blocks)
        if (!empty($block['children']) && is_array($block['children'])) {
            $textSegments[] = extract_text_from_blocks($block['children']);
        }
    }
    
    // Join with spaces and trim
    return trim(implode(' ', array_filter($textSegments, fn($s) => trim($s) !== '')));
}

/**
 * Get content revisions for a specific content ID
 * 
 * Returns an array of revisions sorted by created_at DESC (newest first).
 * Each revision contains decoded JSON data compatible with BlockNote renderer.
 * 
 * @param int $content_id The content ID to get revisions for
 * @param int $limit Maximum number of revisions to return (default 10)
 * @return array<int, array{id: int, content_id: int, data: array, author_id: int, created_at: string}>
 */
function zed_get_revisions(int $content_id, int $limit = 10): array
{
    try {
        $db = Database::getInstance();
        
        $revisions = $db->query(
            "SELECT id, content_id, data_json, author_id, created_at 
             FROM zed_content_revisions 
             WHERE content_id = :content_id 
             ORDER BY created_at DESC 
             LIMIT :limit",
            ['content_id' => $content_id, 'limit' => $limit]
        );
        
        // Decode JSON data for each revision
        return array_map(function($rev) {
            return [
                'id' => (int)$rev['id'],
                'content_id' => (int)$rev['content_id'],
                'data' => json_decode($rev['data_json'], true) ?? [],
                'author_id' => (int)$rev['author_id'],
                'created_at' => $rev['created_at'],
            ];
        }, $revisions);
        
    } catch (Exception $e) {
        // Table might not exist or query failed
        error_log("zed_get_revisions error: " . $e->getMessage());
        return [];
    }
}

// =============================================================================
// IMAGE PROCESSING HELPERS
// =============================================================================

/**
 * Create a GD image resource from file
 */
function zed_image_from_file(string $source): ?GdImage {
    $info = getimagesize($source);
    if (!$info) return null;
    
    $type = $info[2];
    
    return match($type) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($source),
        IMAGETYPE_PNG => imagecreatefrompng($source),
        IMAGETYPE_GIF => imagecreatefromgif($source),
        IMAGETYPE_WEBP => imagecreatefromwebp($source),
        default => null
    };
}

/**
 * Resize an image maintaining aspect ratio
 */
function zed_resize_image(GdImage $source, int $maxWidth): GdImage {
    $srcWidth = imagesx($source);
    $srcHeight = imagesy($source);
    
    // Don't upscale
    if ($srcWidth <= $maxWidth) {
        return $source;
    }
    
    $ratio = $srcWidth / $srcHeight;
    $newWidth = $maxWidth;
    $newHeight = (int)($maxWidth / $ratio);
    
    $resized = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preserve transparency
    imagealphablending($resized, false);
    imagesavealpha($resized, true);
    $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
    imagefill($resized, 0, 0, $transparent);
    
    imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);
    
    return $resized;
}

/**
 * Save image as WebP
 */
function zed_save_webp(GdImage $image, string $dest, int $quality = 80): bool {
    return imagewebp($image, $dest, $quality);
}

/**
 * Full image processing: Convert to WebP, resize if needed, create thumbnail
 * Returns array with paths or false on failure
 */
function zed_process_upload(string $tmpPath, string $originalName, string $uploadDir): array|false {
    @set_time_limit(60);
    
    // Clean filename
    $baseName = pathinfo($originalName, PATHINFO_FILENAME);
    $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '', $baseName);
    $baseName = substr($baseName, 0, 50) ?: 'image_' . time();
    
    // Unique identifier
    $uniqueId = substr(md5(uniqid() . microtime(true)), 0, 8);
    $finalBaseName = $baseName . '_' . $uniqueId;
    
    // Check PHP GD support
    if (!function_exists('imagecreatefromjpeg')) {
        return false;
    }
    
    // Create source image
    $sourceImg = zed_image_from_file($tmpPath);
    if (!$sourceImg) {
        // Fallback: just save original
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $destPath = $uploadDir . '/' . $finalBaseName . '.' . $ext;
        if (move_uploaded_file($tmpPath, $destPath)) {
            return [
                'original' => $destPath,
                'webp' => null,
                'thumb' => null,
                'filename' => $finalBaseName . '.' . $ext
            ];
        }
        return false;
    }
    
    $srcWidth = imagesx($sourceImg);
    $srcHeight = imagesy($sourceImg);
    
    // Keep original in uploads (for backup)
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        $ext = 'jpg';
    }
    $originalPath = $uploadDir . '/' . $finalBaseName . '_original.' . $ext;
    copy($tmpPath, $originalPath);
    
    // Main WebP version (max 1920px)
    $mainImg = zed_resize_image($sourceImg, 1920);
    $webpPath = $uploadDir . '/' . $finalBaseName . '.webp';
    zed_save_webp($mainImg, $webpPath, 80);
    
    // Thumbnail WebP (300px)
    $thumbImg = zed_resize_image($sourceImg, 300);
    $thumbPath = $uploadDir . '/thumb_' . $finalBaseName . '.webp';
    zed_save_webp($thumbImg, $thumbPath, 75);
    
    // Cleanup
    if ($mainImg !== $sourceImg) imagedestroy($mainImg);
    if ($thumbImg !== $sourceImg) imagedestroy($thumbImg);
    imagedestroy($sourceImg);
    
    return [
        'original' => $originalPath,
        'webp' => $webpPath,
        'thumb' => $thumbPath,
        'filename' => $finalBaseName . '.webp',
        'width' => $srcWidth,
        'height' => $srcHeight
    ];
}

/**
 * Generate a thumbnail from an image file (legacy support)
 */
function zed_generate_thumbnail($source, $dest, $targetWidth = 300) {
    if (!file_exists($source)) return false;
    
    $info = getimagesize($source);
    if (!$info) return false;
    
    list($width, $height, $type) = $info;
    $ratio = $width / $height;
    $targetHeight = (int)($targetWidth / $ratio);
    
    $newImg = imagecreatetruecolor($targetWidth, $targetHeight);
    
    // Handle transparency for PNG/GIF
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagecolortransparent($newImg, imagecolorallocatealpha($newImg, 0, 0, 0, 127));
        imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
    }
    
    switch ($type) {
        case IMAGETYPE_JPEG: $sourceImg = imagecreatefromjpeg($source); break;
        case IMAGETYPE_PNG: $sourceImg = imagecreatefrompng($source); break;
        case IMAGETYPE_GIF: $sourceImg = imagecreatefromgif($source); break;
        case IMAGETYPE_WEBP: $sourceImg = imagecreatefromwebp($source); break;
        default: return false;
    }
    
    if (!$sourceImg) return false;
    
    imagecopyresampled($newImg, $sourceImg, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
    
    // Save as WebP if destination is .webp
    if (str_ends_with(strtolower($dest), '.webp')) {
        imagewebp($newImg, $dest, 80);
    } else {
        switch ($type) {
            case IMAGETYPE_JPEG: imagejpeg($newImg, $dest, 80); break;
            case IMAGETYPE_PNG: imagepng($newImg, $dest); break;
            case IMAGETYPE_GIF: imagegif($newImg, $dest); break;
            case IMAGETYPE_WEBP: imagewebp($newImg, $dest, 80); break;
        }
    }
    
    imagedestroy($newImg);
    imagedestroy($sourceImg);
    return true;
}
