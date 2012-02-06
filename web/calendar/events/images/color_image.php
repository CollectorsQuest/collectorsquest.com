<?php

header("Content-type: image/png");

if(isset($_GET['width'])) {
	$width = $_GET['width'];
} else {
	$width = 10;
}
if(isset($_GET['height'])) {
	$height = $_GET['height'];
} else {
	$height = 10;
}

if(isset($_GET['color'])) {
	$color = $_GET['color'];
} else {
	$color = "#ffffff";
}

$im = @imagecreate($width, $height)
     or die("Cannot Initialize new GD image stream");

$color = str_replace("#", "", $color);
$int = hexdec($color);
$arr = array("red" => 0xFF & ($int >> 0x10),
             "green" => 0xFF & ($int >> 0x8),
             "blue" => 0xFF & $int);

#echo $arr["red"] . ":" .  $arr["green"] . ":" .  $arr["blue"];
$imgcolor = ImageColorAllocate($im, $arr["red"], $arr["green"], $arr["blue"]);

imagepng($im);
imagedestroy($im);

?>

