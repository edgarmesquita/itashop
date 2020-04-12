<?php

function createpngthumb($sourceImage, $new_width, $new_height, $tp_index=2, $dest_image) {
    list($old_Width, $old_Height, $type, $attr) = getimagesize($sourceImage);

    $im_arr = getimagesize($sourceImage);
    $old_Width = $im_arr[0];
    $old_Height = $im_arr[1];

    $thumbHeight = $new_height;
    $thumbWidth = $new_width;
#$image = imagecreatefrompng($sourceImage);
// Create the image based on image type


    $image = imagecreatefrompng($sourceImage);




    if (($old_Width >= $thumbWidth) or ($old_Height >= $thumbHeight)) {

        if ($old_Width > $old_Height) {
// calculate thumbnail size
            $new_width = $thumbWidth;
            $new_height = floor($old_Height * ( $thumbWidth / $old_Width ));

            $left_pad = 0;
            $top_pad = ($thumbHeight - $new_height) / 2;
        } else {
// calculate thumbnail size
            $new_height = $thumbHeight;
            $new_width = floor($old_Width * ( $thumbHeight / $old_Height ));

            $left_pad = ($thumbWidth - $new_width) / 2;
            $top_pad = 0;
        }

//echo 'new-width'.$new_width.'height'.$new_height;

        $image_resized = imagecreatetruecolor($thumbWidth, $thumbHeight);
// create a new temporary image
        $tmp_img = imagecreatetruecolor($new_width, $new_height);

        $trnprt_indx = imagecolortransparent($tmp_img);
// Get the original image's transparent color's RGB values
        $trnprt_color = imagecolorsforindex($tmp_img, $tp_index);
        imagealphablending($tmp_img, false);
// Allocate the same color in the new image resource
        $trnprt_indx = imagecolorallocate($tmp_img, 255, 255, 255);

// Completely fill the background of the new image with allocated color.
        imagefill($tmp_img, 0, 0, $trnprt_indx);
        imagesavealpha($tmp_img, true);
// Set the background color for new image to transparent
        imagecolortransparent($tmp_img, $trnprt_indx);

// copy and resize old image into new image
        imagecopyresampled($tmp_img, $image, 0, 0, 0, 0, $new_width, $new_height, $old_Width, $old_Height);

        $trnprt_indx = imagecolortransparent($image_resized);
// Get the original image's transparent color's RGB values
        $trnprt_color = imagecolorsforindex($image_resized, $tp_index);
        imagealphablending($tmp_img, false);
// Allocate the same color in the new image resource
        $trnprt_indx = imagecolorallocate($image_resized, 255, 255, 255);

// Completely fill the background of the new image with allocated color.
        imagefill($image_resized, 0, 0, $trnprt_indx);
        imagesavealpha($tmp_img, true);
// Set the background color for new image to transparent
        imagecolortransparent($image_resized, $trnprt_indx);

// imagecopyresampled($image_resized, $tmp_img, 0, 0, 0, 0, $new_width, $new_height, $new_width, $new_height);
        imagecopyresampled($image_resized, $tmp_img, $left_pad, $top_pad, 0, 0, $new_width, $new_height, $new_width, $new_height);
    } else {

// calculate thumbnail size
        $new_height = $old_Height;
        $new_width = $old_Width;

// create a new temporary image
        $tmp_img = imagecreatetruecolor($new_width, $new_height);

        $trnprt_indx = imagecolortransparent($tmp_img);

// Get the original image's transparent color's RGB values
        $trnprt_color = imagecolorsforindex($tmp_img, $tp_index);
        imagealphablending($tmp_img, false);
// Allocate the same color in the new image resource
        $trnprt_indx = imagecolorallocate($tmp_img, 255, 255, 255);

// Completely fill the background of the new image with allocated color.
        imagefill($tmp_img, 0, 0, $trnprt_indx);
        imagesavealpha($tmp_img, true);
// Set the background color for new image to transparent
        imagecolortransparent($tmp_img, $trnprt_indx);
// copy and resize old image into new image
        imagecopyresampled($tmp_img, $image, 0, 0, 0, 0, $new_width, $new_height, $old_Width, $old_Height);


        $left_pad = ($thumbWidth - $new_width) / 2;
        $top_pad = ($thumbHeight - $new_height) / 2;

// create empty thumbnail img
        $image_resized = imagecreatetruecolor($thumbWidth, $thumbHeight);

        $trnprt_indx = imagecolortransparent($image_resized);
// Get the original image's transparent color's RGB values
        $trnprt_color = imagecolorsforindex($image_resized, $tp_index);
        imagealphablending($tmp_img, false);
        $text_color = imagecolorallocate($image_resized, 255, 255, 255);
// Completely fill the background of the new image with allocated color.
        imagefill($image_resized, 0, 0, $trnprt_indx);
        imagesavealpha($tmp_img, true);
// Set the background color for new image to transparent
        imagecolortransparent($image_resized, $trnprt_indx);



        imagecopyresampled($image_resized, $tmp_img, $left_pad, $top_pad, 0, 0, $new_width, $new_height, $new_width, $new_height);
    }
//create


    $rotate = imagerotate($image_resized, 180, 0);

    $im_new = imagepng($rotate, $dest_image, 9);


#SET THE PERMISSIONS TO THE FOLDER
//chmod($dest_image,0777);
}

function crop_image($nw, $nh, $source, $dest) {
    $size = getimagesize($source);

    $w = $size[0];

    $h = $size[1];

//extracting the extension of the given file
    $stype = $this->file_extension($source);

//getting pathinformation
    $path_info = $this->pathinfo_image($source);

//getting filename without extension
    $filename = $path_info['basename'];

    switch ($stype) {
        case 'gif':
            $simg = imagecreatefromgif($source);
            break;
        case 'jpg':
        case 'jpeg':
            $simg = imagecreatefromjpeg($source);
            break;
        case 'png':
            $simg = imagecreatefrompng($source);
            break;
    }

    $dimg = imagecreatetruecolor($nw, $nh);

    $wm = $w / $nw;

    $hm = $h / $nh;

    $h_height = $nh / 2;

    $w_height = $nw / 2;

    if ($w > $h) {
        $adjusted_width = $w / $hm;

        $half_width = $adjusted_width / 2;

        $int_width = $half_width - $w_height;

        imagecopyresampled($dimg, $simg, -$int_width, 0, 0, 0, $adjusted_width, $nh, $w, $h);
    } elseif (($w < $h) || ($w == $h)) {
        $adjusted_height = $h / $wm;

        $half_height = $adjusted_height / 2;

        $int_height = $half_height - $h_height;
        imagecopyresampled($dimg, $simg, 0, -$int_height, 0, 0, $nw, $adjusted_height, $w, $h);
    } else {
        imagecopyresampled($dimg, $simg, 0, 0, 0, 0, $nw, $nh, $w, $h);
    }

//imagejpeg($dimg, $dest.$filename,100);
//create
    switch ($stype) {
        case 1 : //Image is type gif.
            $im_new = imagegif($th_img, $dest);
            break;
        case 2 : //Image is type jpeg.
            $im_new = imagejpeg($dimg, $dest, 80);
            break;
        case 3 : //Image is png.
            $im_new = imagepng($dimg, $dest, 9);
            break;
        default :
            $im_new = imagejpeg($dimg, $dest, 80);
            break;
    }
}

header("Content-type: image/png"); //Picture Format
header("Expires: Mon, 01 Jul 2003 00:00:00 GMT"); // Past date
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Consitnuously modified
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // NO CACHE

$file = 'resources/images/ticket.png';
$image = imagecreatefrompng($filename);

/* image generation code */
//create Image of size 350px x 75px
$bg = imagecreatetruecolor(200, 60);

//This will make it transparent
imagesavealpha($bg, true);

$trans_colour = imagecolorallocatealpha($bg, 0, 0, 0, 127);
imagefill($bg, 0, 0, $trans_colour);

//Text to be written
$text = isset($_GET['text']) ? $_GET['text'] : "63%\nde desconto";

$color = imagecolorallocate($bg, 197, 74, 70);

$font = 'resources/fonts/ARLRDBD.TTF'; //path to font you want to use
$fontsize = 20; //size of font
//Writes text to the image using fonts using FreeType 2
imagettftext($bg, $fontsize, 0, 20, 20, $color, $font, $text);

createpngthumb($bg, 262, 176);
//$final = imagerotate($bg, 30, $trans_colour);
//$final = imagecolortransparent($rotated, 0, 0, 0);
//Create image
//imagepng($final);
//$image = imagecreatefrompng($sourceImage);
//destroy image
//ImageDestroy($bg);
?>