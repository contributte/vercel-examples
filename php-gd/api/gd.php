<?php

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

// Create a new image (800x600)
$width = 800;
$height = 600;
$image = imagecreatetruecolor($width, $height);

// Allocate colors
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);
$red = imagecolorallocate($image, 255, 0, 0);
$green = imagecolorallocate($image, 0, 255, 0);
$blue = imagecolorallocate($image, 0, 0, 255);
$yellow = imagecolorallocate($image, 255, 255, 0);
$purple = imagecolorallocate($image, 128, 0, 128);

// Fill background with white
imagefill($image, 0, 0, $white);

// Draw a rectangle
imagerectangle($image, 50, 50, 250, 150, $red);

// Draw a filled rectangle
imagefilledrectangle($image, 300, 50, 500, 150, $green);

// Draw an ellipse
imageellipse($image, 150, 300, 200, 150, $blue);

// Draw a filled ellipse
imagefilledellipse($image, 400, 300, 200, 150, $yellow);

// Draw lines
imageline($image, 550, 50, 750, 150, $purple);
imageline($image, 550, 150, 750, 50, $purple);

// Draw a polygon (without deprecated $num_points parameter)
$points = [
    100, 450,  // Point 1
    200, 500,  // Point 2
    150, 550,  // Point 3
    50, 550,   // Point 4
    0, 500     // Point 5
];
imagepolygon($image, $points, $red);

// Draw a filled polygon (without deprecated $num_points parameter)
$filledPoints = [
    300, 450,
    450, 450,
    500, 550,
    400, 550,
    250, 550
];
imagefilledpolygon($image, $filledPoints, $blue);

// Add text using built-in font
imagestring($image, 5, 50, 200, 'PHP GD Example', $black);
imagestring($image, 3, 50, 230, 'Vercel PHP Runtime', $blue);

// Try to add text with TTF font if available
if (function_exists('imagettftext')) {
    // Note: TTF fonts would need to be included in the project
    // For now, we'll just use the built-in fonts
    imagestring($image, 4, 50, 260, 'TTF support: Available', $green);
} else {
    imagestring($image, 4, 50, 260, 'TTF support: Not available', $red);
}

// Add PHP and GD info text
imagestring($image, 2, 50, 290, 'PHP Version: ' . PHP_VERSION, $black);
$gdInfo = gd_info();
imagestring($image, 2, 50, 310, 'GD Version: ' . $gdInfo['GD Version'], $black);
imagestring($image, 2, 50, 330, 'PNG Support: ' . ($gdInfo['PNG Support'] ? 'Yes' : 'No'), $black);
imagestring($image, 2, 50, 350, 'JPEG Support: ' . ($gdInfo['JPEG Support'] ? 'Yes' : 'No'), $black);

// Set content type header
header('Content-Type: image/png');

// Output the image as PNG
imagepng($image);

// Memory is automatically freed when the script ends
// Note: imagedestroy() has no effect since PHP 8.0 and is deprecated in 8.5
