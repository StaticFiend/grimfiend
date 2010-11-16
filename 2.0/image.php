<?
//Image calculations for ratio and resizing.
//by Static Fiend

function getRatio($image) {
	global $width, $height;

	$maxWidth = 160;
	$maxHeight = 160;

	list($width, $height) = getimagesize($image);

	$height_ratio = $maxHeight / $height;
	$width_ratio = $maxWidth / $width;
	$ratio = min($height_ratio, $width_ratio);

	return $ratio;
}
