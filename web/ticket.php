<?php
header("Content-type: image/png"); //Picture Format
header("Expires: Mon, 01 Jul 2003 00:00:00 GMT"); // Past date
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Consitnuously modified
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // NO CACHE

$filename = 'resources/images/ticket.png';
$bg = imagecreatefrompng($filename);

/* image generation code */
//create Image of size 350px x 75px
//$bg = imagecreatetruecolor(200, 60);

//This will make it transparent
imagesavealpha($bg, true);

//$trans_colour = imagecolorallocatealpha($bg, 0, 0, 0, 127);
//imagefill($bg, 0, 0, $trans_colour);

//Text to be written
$percent = isset($_GET['p']) ? ($_GET['p'] < 10 ? "0".$_GET['p']."%" : $_GET['p']."%") : "00%";
$color = imagecolorallocate($bg, 197, 74, 70);

$font = 'resources/fonts/ARLRDBD.TTF'; //path to font you want to use
$fontsize = 20; //size of font
//Writes text to the image using fonts using FreeType 2
imagettftext($bg, 40, 14, 75, 105, $color, $font, $percent);
imagettftext($bg, 16, 14, 75, 130, $color, $font, "de desconto");

//Create image
imagepng($bg);

//destroy image
imagedestroy($bg);
?>