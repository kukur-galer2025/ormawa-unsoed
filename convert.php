<?php
function convertToWebp($source, $destination) {
    $image = imagecreatefromjpeg($source);
    imagewebp($image, $destination, 80);
    imagedestroy($image);
}

if (!is_dir('public/images')) {
    mkdir('public/images', 0777, true);
}

convertToWebp('C:\Users\prima dzaky\.gemini\antigravity-ide\brain\deb0fd36-2cec-4e5d-a9e2-2ed26839d3a2\login_bg_1787113782726.jpg', 'public/images/login_bg.webp');
convertToWebp('C:\Users\prima dzaky\.gemini\antigravity-ide\brain\deb0fd36-2cec-4e5d-a9e2-2ed26839d3a2\register_bg_1787113904483.jpg', 'public/images/register_bg.webp');
echo "Converted successfully!";
