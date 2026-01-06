<?php
use Illuminate\Support\Facades\Storage;
use App\Models\Frame;

// 1. Create a transparent frame with a border (using GD)
$width = 720;
$height = 240; // 1/3 of the strip
$img = imagecreatetruecolor($width, $height);

// Make transparent
imagealphablending($img, false);
imagesavealpha($img, true);
$transparent = imagecolorallocatealpha($img, 255, 255, 255, 127);
imagefilledrectangle($img, 0, 0, $width, $height, $transparent);

// Add Neon Pink Border
$pink = imagecolorallocate($img, 255, 20, 147);
imagesetthickness($img, 20);
imagerectangle($img, 0, 0, $width, $height, $pink);

// Add Text
$white = imagecolorallocate($img, 255, 255, 255);
imagestring($img, 5, 20, 20, "Poselab Default", $white);

// Save to storage
$path = 'frames/neon_default.png';
Storage::disk('public')->put($path, ''); // Create file first
$fullPath = Storage::disk('public')->path($path);
imagepng($img, $fullPath);
imagedestroy($img);

// 2. Insert into DB
Frame::updateOrCreate(
    ['name' => 'Neon Default'],
    ['image_path' => $path, 'active' => true]
);

echo "Frame created and seeded!";
