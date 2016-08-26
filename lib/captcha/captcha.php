<?php

session_start();
$text = (isset($_SESSION['captcha'])) ? $_SESSION['captcha'] : '';

if (empty($text)) {
	$charList = '23456789ABCDEFGHJKLMNPRSTVWXYZ';
	$text = substr(str_shuffle($charList), 0, 5);
	$_SESSION['captcha'] = $text;
}

$image = imagecreatefromjpeg("captcha.jpg");
$font = imagecolorallocate($image, 0, 0, 0);

imagestring($image, 8, 8, 2, $text, $font);
header("Content-type:image/jpeg");
imagejpeg($image);
imagedestroy($image);
