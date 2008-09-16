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
	echo "<tr class=\"header\"><td><a href=\"?sort=title&amp;order=desc\"><b>Title</b></a></td><td><a href=\"?sort=author\"><b>Author</b></a></td><td><a href=\"?sort=game\"><b>Game</b></a></td><td><b>Views</b></td></tr>\n";
else if ($sort == "author" && $desc != 1)
	echo "<tr class=\"header\"><td><a href=\"?sort=title\"><b>Title</b></a></td><td><a href=\"?sort=author&amp;order=desc\"><b>Author</b></a></td><td><a href=\"?sort=game\"><b>Game</b></a></td><td><b>Views</b></td></tr>\n";
else if ($sort == "game" && $desc != 1)
	echo "<tr class=\"header\"><td><a href=\"?sort=title\"><b>Title</b></a></td><td><a href=\"?sort=author\"><b>Author</b></a></td><td><a href=\"?sort=game&amp;order=desc\"><b>Game</b></a></td><td><b>Views</b></td></tr>\n";
else 
	echo "<tr class=\"header\"><td><a href=\"?sort=title\"><b>Title</b></a></td><td><a href=\"?sort=author\"><b>Author</b></a></td><td><a href=\"?sort=game\"><b>Game</b></a></td><td><b>Views</b></td></tr>\n";
	
$gravauthors = array("Static Fiend", "Garin", "Oyster", "Sartak");
$gravmd5 = array("6068e51acd8e531d91448132e392cc7b", "aaf5ab479b4b76922cddcd6f94aa8679", "97c0590bb4ff5ad1be7040c68ce94195", "609c52819f220e49667a53cc0a82bfa7");

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
	$videxists = 0;
	$x = 0;
	
	while ($x < count($gravauthors))
	{
		if (!strstr($author, $gravauthors[$x]))
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

	if ((file_exists($mov) || file_exists($mp4) || file_exists($avi)) && $dnd < 1)
	{
		$videxists = 1;
		$totalvids = $totalvids + intval(1);
		
		echo "<tr>\n";
		echo "<td><a href=\"play.php?id=".$id."\">".$title."</a></td>";
		if ($gravcheck)
			echo "<td><img src=\"http://www.gravatar.com/avatar/".$gravmd5[$gravid]."?s=15&amp;r=x\" alt=\"author avatar\" /> ".$author."</td>";
		else
			echo "<td>".$author."</td>";
		echo "<td>".$game."</td>";
	}
	if (!file_exists($path2."/views.txt") && $dnd != 1 && $videxists == 1)
		echo "<td>0</td>\n</tr>\n";
	else if ($dnd != 1 && $videxists == 1)
	{
		$viewfile = fopen($path2."/views.txt", "r");
		$viewnum = fgets($viewfile);
		fclose($viewfile);
		
		$totalviews = $totalviews + intval($viewnum);

		echo "<td>".$viewnum."</td>\n";
		echo "</tr>\n";
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
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<p><input type="hidden" name="cmd" value="_s-xclick" />
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" />
<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
" /></p>
</form>

</body>
</html>