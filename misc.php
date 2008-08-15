<?php
function dbConnect(&$con)
{
	$con = mysql_connect("localhost", "EDITME", "EDITME");

	if (!$con)
		die('Cannot connect to database: ' . mysql_error());

	mysql_select_db("EDITME");
}
?>
