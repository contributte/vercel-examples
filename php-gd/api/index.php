<?php

/**
 * OG Image Generator using Intervention/Image v3
 *
 * Official documentation: https://image.intervention.io/v3
 *
 * This example demonstrates:
 * - Creating images with Intervention/Image
 * - Using GD driver for image manipulation
 * - Combining Intervention/Image API with native GD functions for drawing
 * - Generating Open Graph images (1200x630)
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

// Check if GD extension is available
if (!extension_loaded('gd')) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => 'GD extension is not available',
        'message' => 'The GD library is required for this example'
    ]);
    exit;
}

try {
    // OG Image standard dimensions (Open Graph image size)
    $width = 1200;
    $height = 630;

    // Get parameters from query string
    $title = $_GET['title'] ?? 'PHP GD';
    $subtitle = $_GET['subtitle'] ?? 'Image Generation with Intervention/Image';

    // Create image manager with GD driver
    // See: https://image.intervention.io/v3/basics/configuration-drivers
    $manager = new ImageManager(new Driver());

    // Create new image using Intervention/Image API
    // See: https://image.intervention.io/v3/basics/read-create-images
    $image = $manager->create($width, $height);

    // Fill with initial background color using Intervention/Image
    $image->fill('f0f5ff'); // Light blue background

    // Get GD resource for advanced drawing operations
    // Intervention/Image v3 focuses on image manipulation, so we use
    // native GD functions for complex drawing (gradients, shapes, text)
    $gdResource = $image->core()->native();

    // Create gradient background (light blue to white)
    // This uses GD directly as Intervention/Image doesn't have built-in gradient support
    for ($y = 0; $y < $height; $y++) {
        $ratio = $y / $height;
        $r = (int)(240 + ($ratio * 15));      // 240-255
        $g = (int)(245 + ($ratio * 10));      // 245-255
        $b = (int)(255);                      // 255
        $color = imagecolorallocate($gdResource, $r, $g, $b);
        imageline($gdResource, 0, $y, $width, $y, $color);
    }

    // Draw subtle decorative shapes in background
    $lightBlue = imagecolorallocatealpha($gdResource, 100, 150, 255, 30);
    $lightPurple = imagecolorallocatealpha($gdResource, 200, 150, 255, 25);

    // Large circles for visual interest
    imagefilledellipse($gdResource, 1000, 150, 400, 400, $lightBlue);
    imagefilledellipse($gdResource, 200, 500, 350, 350, $lightPurple);
    imagefilledellipse($gdResource, 1100, 450, 200, 200, $lightBlue);

    // Draw main content card with solid white background
    $cardX = 100;
    $cardY = 80;
    $cardWidth = $width - 200;
    $cardHeight = 470;

    // White card background
    $cardBg = imagecolorallocate($gdResource, 255, 255, 255);
    imagefilledrectangle($gdResource, $cardX, $cardY, $cardX + $cardWidth, $cardY + $cardHeight, $cardBg);

    // Card shadow effect for depth
    $shadowColor = imagecolorallocatealpha($gdResource, 0, 0, 0, 50);
    for ($i = 0; $i < 10; $i++) {
        imagefilledrectangle($gdResource, $cardX + $i, $cardY + $i, $cardX + $cardWidth + $i, $cardY + $cardHeight + $i, $shadowColor);
    }
    // Redraw card on top of shadow
    imagefilledrectangle($gdResource, $cardX, $cardY, $cardX + $cardWidth, $cardY + $cardHeight, $cardBg);

    // Card border
    $borderColor = imagecolorallocate($gdResource, 220, 220, 230);
    imagerectangle($gdResource, $cardX, $cardY, $cardX + $cardWidth, $cardY + $cardHeight, $borderColor);

    // Allocate text colors (dark for visibility on white)
    $titleColor = imagecolorallocate($gdResource, 20, 30, 50);
    $subtitleColor = imagecolorallocate($gdResource, 80, 100, 140);
    $textColor = imagecolorallocate($gdResource, 100, 120, 150);
    $accentColor = imagecolorallocate($gdResource, 50, 120, 200);
    $white = imagecolorallocate($gdResource, 255, 255, 255);

    // Draw title with multiple passes for bold effect
    $titleY = $cardY + 120;
    $titleFontSize = 5; // Largest built-in font
    $titleText = strtoupper($title);
    $titleWidth = imagefontwidth($titleFontSize) * strlen($titleText);
    $titleX = $cardX + ($cardWidth - $titleWidth) / 2;

    // Draw title with shadow for depth
    $shadowOffset = 2;
    imagestring($gdResource, $titleFontSize, $titleX + $shadowOffset, $titleY + $shadowOffset, $titleText, imagecolorallocate($gdResource, 200, 200, 200));
    imagestring($gdResource, $titleFontSize, $titleX, $titleY, $titleText, $titleColor);
    // Draw again slightly offset for bold effect
    imagestring($gdResource, $titleFontSize, $titleX + 1, $titleY, $titleText, $titleColor);

    // Draw subtitle
    $subtitleY = $titleY + 80;
    $subtitleFontSize = 3;
    $subtitleText = $subtitle;
    $subtitleWidth = imagefontwidth($subtitleFontSize) * strlen($subtitleText);
    $subtitleX = $cardX + ($cardWidth - $subtitleWidth) / 2;
    imagestring($gdResource, $subtitleFontSize, $subtitleX, $subtitleY, $subtitleText, $subtitleColor);

    // Draw decorative line under subtitle
    $lineY = $subtitleY + 50;
    $lineX1 = $cardX + 150;
    $lineX2 = $cardX + $cardWidth - 150;
    imageline($gdResource, $lineX1, $lineY, $lineX2, $lineY, imagecolorallocate($gdResource, 230, 235, 240));
    imageline($gdResource, $lineX1, $lineY + 1, $lineX2, $lineY + 1, imagecolorallocate($gdResource, 240, 245, 250));

    // Draw info badges with better visibility
    $infoY = $subtitleY + 120;
    $infoFontSize = 2;
    $badgeSpacing = 15;
    $badgeY = $infoY;
    $badgeHeight = imagefontheight($infoFontSize) + 12;
    $badgeX = $cardX + 80;

    // PHP Version badge
    $phpVersion = 'PHP ' . PHP_VERSION;
    $phpWidth = imagefontwidth($infoFontSize) * strlen($phpVersion);
    $phpBadgeBg = imagecolorallocate($gdResource, 50, 150, 100);
    imagefilledrectangle($gdResource, $badgeX, $badgeY, $badgeX + $phpWidth + 20, $badgeY + $badgeHeight, $phpBadgeBg);
    imagestring($gdResource, $infoFontSize, $badgeX + 10, $badgeY + 6, $phpVersion, $white);

    // GD Version badge
    $gdInfo = gd_info();
    $gdVersion = 'GD ' . $gdInfo['GD Version'];
    $gdX = $badgeX + $phpWidth + $badgeSpacing + 20;
    $gdWidth = imagefontwidth($infoFontSize) * strlen($gdVersion);
    $gdBadgeBg = imagecolorallocate($gdResource, 150, 80, 200);
    imagefilledrectangle($gdResource, $gdX, $badgeY, $gdX + $gdWidth + 20, $badgeY + $badgeHeight, $gdBadgeBg);
    imagestring($gdResource, $infoFontSize, $gdX + 10, $badgeY + 6, $gdVersion, $white);

    // Vercel badge
    $vercelText = 'Vercel Runtime';
    $vercelX = $gdX + $gdWidth + $badgeSpacing + 20;
    $vercelWidth = imagefontwidth($infoFontSize) * strlen($vercelText);
    $vercelBadgeBg = imagecolorallocate($gdResource, 0, 0, 0);
    imagefilledrectangle($gdResource, $vercelX, $badgeY, $vercelX + $vercelWidth + 20, $badgeY + $badgeHeight, $vercelBadgeBg);
    imagestring($gdResource, $infoFontSize, $vercelX + 10, $badgeY + 6, $vercelText, $white);

    // Draw additional info text
    $infoTextY = $badgeY + $badgeHeight + 40;
    $infoText = 'Intervention/Image v3 • PHP 8.5 Compatible';
    $infoTextWidth = imagefontwidth($infoFontSize) * strlen($infoText);
    $infoTextX = $cardX + ($cardWidth - $infoTextWidth) / 2;
    imagestring($gdResource, $infoFontSize, $infoTextX, $infoTextY, $infoText, $textColor);

    // Draw footer text with better visibility
    $footerY = $height - 50;
    $footerText = 'Generated with PHP GD + Intervention/Image';
    $footerFontSize = 2;
    $footerWidth = imagefontwidth($footerFontSize) * strlen($footerText);
    $footerX = ($width - $footerWidth) / 2;

    // Footer with background for visibility
    // Alpha must be 0-127 (0=opaque, 127=transparent). Converting 200/255 opacity to ~28 alpha
    $footerBg = imagecolorallocatealpha($gdResource, 255, 255, 255, 28);
    imagefilledrectangle($gdResource, $footerX - 15, $footerY - 5, $footerX + $footerWidth + 15, $footerY + imagefontheight($footerFontSize) + 5, $footerBg);
    imagestring($gdResource, $footerFontSize, $footerX, $footerY, $footerText, $textColor);

    // Draw decorative corner elements
    $cornerColor = imagecolorallocate($gdResource, 100, 150, 255);
    $cornerSize = 40;
    $cornerThickness = 4;

    // Top-left corner
    for ($i = 0; $i < $cornerThickness; $i++) {
        imageline($gdResource, $cardX, $cardY + $i, $cardX + $cornerSize, $cardY + $i, $cornerColor);
        imageline($gdResource, $cardX + $i, $cardY, $cardX + $i, $cardY + $cornerSize, $cornerColor);
    }

    // Bottom-right corner
    for ($i = 0; $i < $cornerThickness; $i++) {
        imageline($gdResource, $cardX + $cardWidth - $cornerSize, $cardY + $cardHeight - $i, $cardX + $cardWidth, $cardY + $cardHeight - $i, $cornerColor);
        imageline($gdResource, $cardX + $cardWidth - $i, $cardY + $cardHeight - $cornerSize, $cardX + $cardWidth - $i, $cardY + $cardHeight, $cornerColor);
    }

    // Use Intervention/Image for final output
    // See: https://image.intervention.io/v3/basics/image-output
    $finalImage = $manager->read($gdResource);

    // Set headers for OG image
    header('Content-Type: image/png');
    header('Cache-Control: public, max-age=3600');

    // Output the image as PNG using Intervention/Image API
    // See: https://image.intervention.io/v3/basics/image-output
    echo $finalImage->toPng();

} catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => 'Image processing failed',
        'message' => $e->getMessage()
    ]);
}
