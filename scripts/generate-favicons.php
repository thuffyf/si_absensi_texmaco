<?php

$root = dirname(__DIR__);
$source = $root . '/public/images/Logo Texmaco-transparent.png';
$public = $root . '/public';

if (! is_file($source)) {
    fwrite(STDERR, "Source logo not found: {$source}\n");
    exit(1);
}

if (! function_exists('imagecreatefrompng')) {
    fwrite(STDERR, "PHP GD extension is required.\n");
    exit(1);
}

$image = imagecreatefrompng($source);
imagesavealpha($image, true);
imagealphablending($image, false);

$width = imagesx($image);
$height = imagesy($image);

$makeSize = static function (int $size) use ($image, $width, $height, $public): void {
    $canvas = imagecreatetruecolor($size, $size);
    imagealphablending($canvas, false);
    imagesavealpha($canvas, true);

    $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
    imagefilledrectangle($canvas, 0, 0, $size, $size, $transparent);
    imagecopyresampled($canvas, $image, 0, 0, 0, 0, $size, $size, $width, $height);
    imagepng($canvas, "{$public}/favicon-{$size}.png");
    imagedestroy($canvas);
};

foreach ([16, 32, 180] as $size) {
    $makeSize($size);
}

copy("{$public}/favicon-32.png", "{$public}/favicon.png");
copy("{$public}/favicon-32.png", "{$public}/favicon.ico");
copy("{$public}/favicon-180.png", "{$public}/apple-touch-icon.png");

imagedestroy($image);

echo "Favicons generated in public/\n";
