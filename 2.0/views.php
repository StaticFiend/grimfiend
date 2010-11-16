<?
/*
GrimFiend
Reading/writing views file
*/

function readViews($path) {
	if (file_exists($path."/views.txt")) {	
		$file = fopen($path."/views.txt", "r");
		$totalViews = fgets($file);
		fclose($file);
	}
	else
		$totalViews = 0;

	return $totalViews;
}

function writeViews() {
}
?>
