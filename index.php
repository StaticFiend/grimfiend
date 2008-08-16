<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
<?php
include("../../misc.php");

dbConnect($dbh);
?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="stylesheet" type="text/css" href="indexstyle.css" />
<title>Video LP - Index</title>
</head>
<body>
<?php

echo "<div class=\"title\"><b>Video LP Index</b></div>\n";

$sort = $_GET["sort"];

if ($_GET["order"] == "desc")
	$desc = 1;
	
if ($sort == "title")
{
	$result = mysql_query("SELECT * FROM vidinfo ORDER BY title");
	
	if ($desc == 1)
		$result = mysql_query("SELECT * FROM vidinfo ORDER BY title DESC");

}
else if ($sort == "author")
{
	$result = mysql_query("SELECT * FROM vidinfo ORDER BY author");

	if ($desc == 1)
		$result = mysql_query("SELECT * FROM vidinfo ORDER BY author DESC");
}
else if ($sort == "game")
{
	$result = mysql_query("SELECT * FROM vidinfo ORDER BY game");

	if ($desc == 1)
		$result = mysql_query("SELECT * FROM vidinfo ORDER BY game DESC");
}
else
	$result = mysql_query("SELECT * FROM vidinfo ORDER BY dirname");

echo "<table>\n";

if ($sort == "title" && $desc != 1)
	echo "<tr class=\"header\"><td><a href=\"index.php?sort=title&amp;order=desc\"><b>Title</b></a></td><td><a href=\"index.php?sort=author\"><b>Author</b></a></td><td><a href=\"index.php?sort=game\"><b>Game</b></a></td><td><b>Views</b></td></tr>\n";
else if ($sort == "author" && $desc != 1)
	echo "<tr class=\"header\"><td><a href=\"index.php?sort=title\"><b>Title</b></a></td><td><a href=\"index.php?sort=author&amp;order=desc\"><b>Author</b></a></td><td><a href=\"index.php?sort=game\"><b>Game</b></a></td><td><b>Views</b></td></tr>\n";
else if ($sort == "game" && $desc != 1)
	echo "<tr class=\"header\"><td><a href=\"index.php?sort=title\"><b>Title</b></a></td><td><a href=\"index.php?sort=author\"><b>Author</b></a></td><td><a href=\"index.php?sort=game&amp;order=desc\"><b>Game</b></a></td><td><b>Views</b></td></tr>\n";
else 
	echo "<tr class=\"header\"><td><a href=\"index.php?sort=title\"><b>Title</b></a></td><td><a href=\"index.php?sort=author\"><b>Author</b></a></td><td><a href=\"index.php?sort=game\"><b>Game</b></a></td><td><b>Views</b></td></tr>\n";
	
$gravauthors = array("Static Fiend");
$gravmd5 = array("6068e51acd8e531d91448132e392cc7b");

while ($vars = mysql_fetch_array($result))
{
	$id = $vars["id"];
	$path = $vars["dirname"]."/".$vars["dirname"];
	$path2 = $vars["dirname"];
	$title = htmlspecialchars($vars["title"]);
	$author = htmlspecialchars($vars["author"]);
	$game = htmlspecialchars($vars["game"]);
	$dnd = $vars["private"];
	
	$gravcheck = 0;
	$x = 0;
	
	while ($x < count($gravauthors))
	{
		if (!strstr($gravauthors[$x], $author))
		{
			$x++;
			continue;
		}
		else
		{
			$gravcheck = 1;
			$gravid = $x;
			break;
		}
		
		$x++;
	}
	
	$mov = $path.".mov";
	$mp4 = $path.".mp4";
	$avi = $path.".avi";

	if (file_exists($mov) || file_exists($mp4) || file_exists($avi) && $dnd != 1)
		{
			$videxists = 1;
			$totalvids = $totalvids + intval(1);
			
			if ($dnd != 1)
			{
				echo "<tr>\n";
				echo "<td><a href=\"http://www.grimfiend.com/lp/vlp/play.php?id=".$id."\">".$title."</a></td>";
				if ($gravcheck)
					echo "<td><img src=\"http://www.gravatar.com/avatar/".$gravmd5[$gravid]."?s=15\" alt=\"author avatar\" /> ".$author."</td>";
				else
					echo "<td>".$author."</td>";
				echo "<td>".$game."</td>";
			}
		}
	if (!file_exists($path2."/views.txt") && $dnd != 1 && $videxists == 1)
	{
		echo "<td>0</td>\n</tr>\n";
		$videxists = 0;
	}
	else if ($dnd != 1 && $videxists == 1)
	{
		$viewfile = fopen($path2."/views.txt", "r");
		$viewnum = fgets($viewfile);
		fclose($viewfile);
		
		$totalviews = $totalviews + intval($viewnum);

		echo "<td>".$viewnum."</td>\n";
		echo "</tr>\n";
		
		$videxists = 0;
	}
}
echo "<tr class=\"header\"><td><b>Total Number of Videos</b></td><td></td><td></td><td><b>Total</b></td></tr>\n";
echo "<tr><td>".$totalvids."</td><td></td><td></td><td>".$totalviews."</td></tr>\n";
echo "</table>\n";
echo "<div class=\"bottom\"><br />PHP by Static Fiend, <a href=\"http://github.com/StaticFiend/grimfiend/tree/master\">click here</a> for the source code.<br /><br /></div>\n";

mysql_close($dbh);
?>
<p>
<a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
<a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fwww.grimfiend.com%2Flp%2Fvlp%2Findexstyle.css&amp;profile=css3&amp;usermedium=all&amp;warning=1&amp;lang=en"><img src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!" height="31" width="88" /></a>
</p>
</body>
</html>