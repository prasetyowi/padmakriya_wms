<?php
function compressImage($source, $destination, $quality)
{
    // Get image info 
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];

    // Create a new image from file 
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            $image = imagecreatefromjpeg($source);
    }

    // Save image 
    // $img = imagerotate($image, -180, 0);
    imagejpeg($image, $destination, $quality);

    // Return compressed image 
    return $destination;
}

// function convert_filesize($bytes, $decimals = 2)
// {
//     $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
//     $factor = floor((strlen($bytes) - 1) / 3);
//     return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
// }
