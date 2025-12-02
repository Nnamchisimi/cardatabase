<?php
$sourceDir = __DIR__ . '/uploads/';
$targetDir = __DIR__ . '/resized/';
$targetWidth = 700;
$targetHeight = 600;

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$images = glob($sourceDir . '*.{jpg,jpeg,png}', GLOB_BRACE);

foreach ($images as $imagePath) {
    $imgInfo = getimagesize($imagePath);
    $mime = $imgInfo['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $srcImg = imagecreatefromjpeg($imagePath);
            break;
        case 'image/png':
            $srcImg = imagecreatefrompng($imagePath);
            break;
        default:
            echo "Unsupported type: $imagePath\n";
            continue 2;
    }

    $resizedImg = imagecreatetruecolor($targetWidth, $targetHeight);

 
    $white = imagecolorallocate($resizedImg, 255, 255, 255);
    imagefill($resizedImg, 0, 0, $white);

    imagecopyresampled(
        $resizedImg,
        $srcImg,
        0, 0, 0, 0,
        $targetWidth, $targetHeight,
        imagesx($srcImg), imagesy($srcImg)
    );

    $filename = basename($imagePath);
    $targetPath = $targetDir . $filename;

    imagejpeg($resizedImg, $targetPath, 85); // Save with 85% quality

    imagedestroy($srcImg);
    imagedestroy($resizedImg);

    echo "Resized to 585x539: $filename\n";
}
?>
