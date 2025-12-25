<?php
/**
 * Media Upload API
 * WordPress-style media management with year/month folders and thumbnails
 * 
 * @package ZedCMS\Admin\API
 */

declare(strict_types=1);

use Core\Database;
use Core\Auth;
use Core\Router;

/**
 * Handle media upload
 * Route: POST /admin/api/upload
 */
function zed_handle_media_upload(): void
{
    // Ensure clean JSON output - suppress any PHP errors/warnings from appearing as HTML
    header('Content-Type: application/json');
    
    // Start fresh output buffer to catch any stray output
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    ob_start();
    
    // Set error handler to convert errors to exceptions
    set_error_handler(function($severity, $message, $file, $line) {
        error_log("Media Upload Error: $message in $file:$line");
        return true; // Suppress output
    });
    
    try {
        // Check authentication
        if (!Auth::check()) {
            throw new \Exception('Unauthorized', 401);
        }
        
        // Check if file was uploaded (handle both 'file' and 'image' field names)
        $file = $_FILES['file'] ?? $_FILES['image'] ?? null;
        
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $errorMsg = 'No file uploaded';
            if ($file && $file['error'] !== UPLOAD_ERR_OK) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temp folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write to disk',
                ];
                $errorMsg = $errorMessages[$file['error']] ?? 'Upload error code: ' . $file['error'];
            }
            throw new \Exception($errorMsg, 400);
        }
        
        // Validate file type (images only)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            throw new \Exception('Invalid file type. Only images allowed.', 400);
        }
        
        // Get image dimensions
        $imageInfo = getimagesize($file['tmp_name']);
        $width = $imageInfo[0] ?? null;
        $height = $imageInfo[1] ?? null;
        
        // Create year/month folder structure (WordPress style)
        $uploadBaseDir = dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/uploads';
        $year = date('Y');
        $month = date('m');
        $uploadDir = $uploadBaseDir . '/' . $year . '/' . $month;
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $originalFilename = $file['name'];
        $extension = strtolower(pathinfo($originalFilename, PATHINFO_EXTENSION));
        $basename = pathinfo($originalFilename, PATHINFO_FILENAME);
        $basename = sanitize_filename($basename);
        
        $filename = $basename . '.' . $extension;
        $counter = 1;
        while (file_exists($uploadDir . '/' . $filename)) {
            $filename = $basename . '-' . $counter . '.' . $extension;
            $counter++;
        }
        
        $filePath = $uploadDir . '/' . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new \Exception('Failed to save file', 500);
        }
        
        // Generate thumbnails (WordPress style)
        $relativePath = $year . '/' . $month . '/' . $filename;
        $baseUrl = Router::url('/uploads/' . $relativePath);
        
        $thumbnails = generate_image_sizes($filePath, $uploadDir, $basename, $extension);
        
        // Save to database using insert() which returns the last insert ID
        $db = Database::getInstance();
        $userId = Auth::user()['id'] ?? 1;
        
        $mediaId = (int)$db->insert('zed_media', [
            'filename' => $filename,
            'original_filename' => $originalFilename,
            'file_path' => $relativePath,
            'url' => $baseUrl,
            'thumbnail_url' => $thumbnails['thumbnail'] ?? $baseUrl,
            'medium_url' => $thumbnails['medium'] ?? $baseUrl,
            'large_url' => $thumbnails['large'] ?? $baseUrl,
            'file_size' => filesize($filePath),
            'mime_type' => $mimeType,
            'width' => $width,
            'height' => $height,
            'uploaded_by' => $userId,
            'uploaded_at' => date('Y-m-d H:i:s')
        ]);
        
        // Clear buffer and send success response
        ob_end_clean();
        restore_error_handler();
        
        echo json_encode([
            'success' => 1,
            'status' => 'success',
            'id' => $mediaId,
            'url' => $baseUrl,
            'file' => [
                'url' => $baseUrl,
                'url_medium' => $thumbnails['medium'] ?? $baseUrl,
                'url_thumbnail' => $thumbnails['thumbnail'] ?? $baseUrl,
            ],
            'thumbnail' => $thumbnails['thumbnail'] ?? $baseUrl,
            'medium' => $thumbnails['medium'] ?? $baseUrl,
            'large' => $thumbnails['large'] ?? $baseUrl,
            'filename' => $filename,
            'size' => filesize($filePath),
            'dimensions' => ['width' => $width, 'height' => $height]
        ]);
        exit;
        
    } catch (\Throwable $e) {
        // Clean output and send error
        ob_end_clean();
        restore_error_handler();
        
        $code = $e->getCode() ?: 500;
        if ($code < 100 || $code > 599) $code = 500;
        http_response_code($code);
        
        echo json_encode([
            'success' => 0,
            'error' => $e->getMessage()
        ]);
        exit;
    }
}

/**
 * Sanitize filename
 */
function sanitize_filename(string $filename): string
{
    // Remove special characters
    $filename = preg_replace('/[^a-zA-Z0-9-_]/', '-', $filename);
    // Remove multiple dashes
    $filename = preg_replace('/-+/', '-', $filename);
    // Trim dashes
    $filename = trim($filename, '-');
    // Lowercase
    $filename = strtolower($filename);
    
    return $filename ?: 'file';
}

/**
 * Generate image thumbnails (WordPress style)
 * 
 * @return array URLs of generated sizes
 */
function generate_image_sizes(string $sourcePath, string $uploadDir, string $basename, string $ext): array
{
    $sizes = [
        'thumbnail' => ['width' => 150, 'height' => 150, 'crop' => true],
        'medium' => ['width' => 300, 'height' => 300, 'crop' => false],
        'large' => ['width' => 1024, 'height' => 1024, 'crop' => false],
    ];
    
    $generated = [];
    
    foreach ($sizes as $sizeName => $sizeConfig) {
        $newFilename = $basename . '-' . $sizeConfig['width'] . 'x' . $sizeConfig['height'] . '.' . $ext;
        $newPath = $uploadDir . '/' . $newFilename;
        
        if (resize_image($sourcePath, $newPath, $sizeConfig['width'], $sizeConfig['height'], $sizeConfig['crop'])) {
            // Get relative path
            $parts = explode('/uploads/', $newPath);
            $relativePath = end($parts);
            $generated[$sizeName] = Router::url('/uploads/' . $relativePath);
        }
    }
    
    return $generated;
}

/**
 * Resize image
 */
function resize_image(string $source, string $dest, int $maxWidth, int $maxHeight, bool $crop = false): bool
{
    $imageInfo = getimagesize($source);
    if (!$imageInfo) return false;
    
    list($origWidth, $origHeight, $imageType) = $imageInfo;
    
    // Don't upscale
    if ($origWidth <= $maxWidth && $origHeight <= $maxHeight && !$crop) {
        return copy($source, $dest);
    }
    
    // Create image resource
    $sourceImage = match($imageType) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($source),
        IMAGETYPE_PNG => imagecreatefrompng($source),
        IMAGETYPE_GIF => imagecreatefromgif($source),
        IMAGETYPE_WEBP => imagecreatefromwebp($source),
        default => false
    };
    
    if (!$sourceImage) return false;
    
    if ($crop) {
        // Crop to exact dimensions
        $ratio = max($maxWidth / $origWidth, $maxHeight / $origHeight);
        $newWidth = (int)($origWidth * $ratio);
        $newHeight = (int)($origHeight * $ratio);
        
        $cropX = (int)(($newWidth - $maxWidth) / 2);
        $cropY = (int)(($newHeight - $maxHeight) / 2);
        
        $tempImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tempImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        
        $destImage = imagecreatetruecolor($maxWidth, $maxHeight);
        imagecopy($destImage, $tempImage, 0, 0, $cropX, $cropY, $maxWidth, $maxHeight);
        
        imagedestroy($tempImage);
    } else {
        // Resize maintaining aspect ratio
        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
        $newWidth = (int)($origWidth * $ratio);
        $newHeight = (int)($origHeight * $ratio);
        
        $destImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG/GIF
        if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_GIF) {
            imagealphablending($destImage, false);
            imagesavealpha($destImage, true);
        }
        
        imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
    }
    
    // Save image
    $result = match($imageType) {
        IMAGETYPE_JPEG => imagejpeg($destImage, $dest, 90),
        IMAGETYPE_PNG => imagepng($destImage, $dest, 9),
        IMAGETYPE_GIF => imagegif($destImage, $dest),
        IMAGETYPE_WEBP => imagewebp($destImage, $dest, 90),
        default => false
    };
    
    imagedestroy($sourceImage);
    imagedestroy($destImage);
    
    return $result;
}
