<?
/*
Variable set up for the videos

by Static Fiend
*/

function get_vid_var($vars) {
	global $id, $file, $path, $title, $author, $game, $dnd, $avatarFound, $vidExists, $image, $check;

	$id = $vars["id"];
	//$check is needed here as kind of a dumb hack
	//basically url encoding screws with PHP when using file_exists
	$path = $vars["dirname"];
	$check = $path."/".$path;
	$path = rawurlencode($path);
	$file = $path."/".$path;

	if (file_exists($check.".jpg")) {
		$image = $file.".jpg";
		$check = $check.".jpg";
	}
	else if (file_exists($check.".png")) {
		$image = $file.".png";
		$check = $check.".png";
	}
	else if (file_exists($check.".gif")) {
		$image = $file.".gif";
		$check = $check.".gif";
	}
	else {
		$image = "default.jpg";
		$check = "default.jpg";
	}

	$title = $vars["title"];
	$author = $vars["author"];
	$game = $vars["game"];
	$dnd = $vars["dnd"];

	$avatarFound = 0;
	$vidExists = 0;
}
