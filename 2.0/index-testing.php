<!--
GrimFiend v2.0
by Static_Fiend

This document complies with HTML5 standards, and uses
very heavy PHP.  See the git repo for the source code
to the entire page over at:
http://github.com/

You can also try this in the (preview version) of the
validator over at:
http://validator.w3.org/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>GrimFiend - VLP Index.</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="new-index_style.css" />
</head>
<body>
<?
//Testing a new index system for my VLP section of GrimFiend.
//Load necessary modules for the index.

include("../../misc.php"); //Takes care of database connections.

include("sort.php"); //Takes care of the queries
include("vars.php"); //Takes care of variables (id, title, etc)
include("image.php"); //Takes care of getting ratios for scaling images.
include("avatar.php"); //Takes care of all the gravatar stuff
include("views.php"); //Takes care of view numbers, read/write

dbConnect($dbh);
?>
<!--
Do top of page here, maybe a logo, some links and a divider.
-->
<div class="logo">
</div>
<span class="blank"><br /></span>
<div class="options">
<!--
This kind of looks bad with text, try images.
Sort by: &nbsp;&nbsp;&nbsp;Title &nbsp;&nbsp;&nbsp;Author &nbsp;&nbsp;&nbsp;Game
-->
</div>
<span class="blank"><br /></span>
<?
//Time to set up the sorting, and query the database for entries.
// *11/3/2010 - Sorting/getvar functions work perfectly.
// *11/3/2010 - Pages work.
// *11/3/2010 - Fixed <img> tags to use properly encoded urls.
// *11/3/2010 - Added image scaling with image.php 
$sort = $_GET["sort"];
$desc = intval($_GET["desc"]);
$page = intval($_GET["page"]);

if ($page == 0 || $page < 0)
	$page = 1;

$page = ($page - 1) * 5;
$result = doSort($sort, $desc, $page);

$authors = array("Static Fiend", "Garin", "Oyster", "Sartak", "Tyrone");
$authormd5 = array("6068e51acd8e531d91448132e392cc7b", "aaf5ab479b4b76922cddcd6f94aa8679", "97c0590bb4ff5ad1be7040c68ce94195", "609c52819f220e49667a53cc0a82bfa7", "8c07efe344805cdf04c47deb1470b993");

while ($vars = mysql_fetch_array($result)) {
	get_vid_var($vars);
	$views = readViews($path);

	$ratio = getRatio($check);
	findGravatar($author, $authors);

	echo "<table class=\"video\">\n";
	echo "<tr><td class=\"preview\"><img src=\"$image\" style=\"width: ".intval($width * $ratio)."px; height: ".intval($height * $ratio)."px;\" /></td>\n";
	echo "<td class=\"info\">\n
	<b>Title</b>: <a href=\"play.php?id=$id\">$title</a><br />\n
	<b>Author</b>: $author<br />\n
	<b>Game</b>: $game<br />\n
	<b>Views</b>: $views<br />\n
	</td>";
	echo "<td class=\"avatar\">";
	showAvatar($authormd5, $avatarPointer, $avatarFound);
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "<span class=\"blank\"><br /></span>\n";
}

?>
</body>
</html>
