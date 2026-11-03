<?php
/**
 * og_image.php - Dynamic OG Image Generator for A2P Realtech
 * Crops & resizes any property photo to exactly 1200x630 (Facebook recommended)
 * This ensures Facebook mobile shows the LARGE banner image (not small thumbnail)
 */

// Security: only allow safe filenames
$photo = isset($_GET['photo']) ? $_GET['photo'] : '';
$photo = basename(urldecode($photo)); // strip any path traversal

$uploadDir  = __DIR__ . '/upload/';
$imagePath  = $uploadDir . $photo;
$fallback   = $uploadDir . '080325100432logo.png';

// Use fallback if file doesn't exist or photo param is empty
if (empty($photo) || !file_exists($imagePath)) {
    $imagePath = $fallback;
}

// Target OG dimensions
$outW = 1200;
$outH = 630;

// Cache headers - cache for 24 hours, re-generate daily
$etag = md5($imagePath . date('Ymd'));
header('Content-Type: image/jpeg');
header('Cache-Control: public, max-age=86400');
header('ETag: "' . $etag . '"');

// Return 304 if client already has this version
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) === '"' . $etag . '"') {
    header('HTTP/1.1 304 Not Modified');
    exit;
}

// Load the source image
$info = @getimagesize($imagePath);
if (!$info) {
    // fallback to logo if corrupt
    $imagePath = $fallback;
    $info = getimagesize($imagePath);
}

$type = $info[2];
switch ($type) {
    case IMAGETYPE_JPEG:  $src = @imagecreatefromjpeg($imagePath); break;
    case IMAGETYPE_PNG:   $src = @imagecreatefrompng($imagePath);  break;
    case IMAGETYPE_WEBP:  $src = @imagecreatefromwebp($imagePath); break;
    case IMAGETYPE_GIF:   $src = @imagecreatefromgif($imagePath);  break;
    default:              $src = @imagecreatefromjpeg($imagePath);  break;
}

if (!$src) {
    $src = imagecreatefrompng($fallback);
}

$srcW = imagesx($src);
$srcH = imagesy($src);

// Center-crop to 1200:630 (1.904:1) ratio
$targetRatio = $outW / $outH;
$srcRatio    = $srcW / $srcH;

if ($srcRatio > $targetRatio) {
    // Source too wide — crop left/right
    $cropH = $srcH;
    $cropW = (int)($srcH * $targetRatio);
    $cropX = (int)(($srcW - $cropW) / 2);
    $cropY = 0;
} else {
    // Source too tall — crop top/bottom
    $cropW = $srcW;
    $cropH = (int)($srcW / $targetRatio);
    $cropX = 0;
    $cropY = (int)(($srcH - $cropH) / 3); // Slightly above center for better framing
}

// Create output canvas
$dst = imagecreatetruecolor($outW, $outH);

// Fill with white background (for PNG images with transparency)
$white = imagecolorallocate($dst, 255, 255, 255);
imagefill($dst, 0, 0, $white);

// Resample crop onto 1200x630 canvas
imagecopyresampled($dst, $src, 0, 0, $cropX, $cropY, $outW, $outH, $cropW, $cropH);

// Output as JPEG with high quality
imagejpeg($dst, null, 92);

// Free memory
imagedestroy($src);
imagedestroy($dst);
exit;
