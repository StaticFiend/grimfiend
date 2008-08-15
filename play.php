<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
<?php
include("../../misc.php");

dbConnect($dbh);

$idnum = $_GET["id"];
$idnum = stripslashes($idnum);
$idnum = mysql_real_escape_string($idnum);
$idnum = intval($idnum);

$sql = mysql_query("SELECT * FROM vidinfo WHERE id='$idnum'");
$count = intval(mysql_num_rows($sql));

if ($count > 1 || $count < 1)
{
	mysql_close($dbh);
	echo "<!DOCTYPE html 
	PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
     \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
	<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
	echo "<head><title>ERROR</title></head>\n";
	echo "<body><p>ERROR: Unable to obtain data from this id</p></body></html>\n";
	exit("");
}
else
{
	$vars = mysql_fetch_array($sql);
	mysql_close($dbh);
}
?>
<!-- PHP Source that uses JW FLV Mediaplayer, anything in <script> is not my code and was written by its respective author.
     Everything else, by Static Fiend (ME).

     Use this for whatever. 
-->
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>GrimFiend Video LP Player - <?php echo htmlspecialchars($vars["title"])." by ".htmlspecialchars($vars["author"]); ?></title>
<style type="text/css">
<!--
body {background-color: #<?php echo strtoupper(str_pad(dechex($vars['background']), 6, "0", STR_PAD_LEFT)); ?>; padding: 0; margin: 0; height: 100%;}
div#container {height: 100%}
p {color: #<?php echo strtoupper(str_pad(dechex($vars['foreground']), 6, "0", STR_PAD_LEFT)); ?>; text-align: center; line-height: 7px}
a {color: #AA3333; text-decoration: none;}
img {border: 0px}
html { height: 100%; overflow: hidden;}
-->
</style>
</head>
<body>
<?php

$path = $vars["dirname"]."/".$vars["dirname"];
$path2 = $vars["dirname"];

if (file_exists($path.".mp4"))
	$format = "mp4";
else if (file_exists($path.".mov"))
	$format = "mov";
else
	$format = "avi";

if (file_exists($path.".jpg"))
	$imgformat = "jpg";
else if (file_exists($path.".png"))
	$imgformat = "png";
else if (file_exists($path.".gif"))
	$imgformat = "gif";
else
	$imgformat = "honk";

$video = $path.".".$format;
$preview = $path.".".$imgformat;
$fullscreen = $_GET["fullscreen"];
	
if (!file_exists($video))
{
	echo "<p>ERROR: Unable to load video</p>\n";
	echo "</body></html>";
	exit("");
}
if (!file_exists($preview))
	$preview = "default.jpg";

//Banner code
$game2 = str_replace(":", "", $vars["game"]);

if (file_exists("logo/$game2.png"))
	echo "<p><img src=\"logo/$game2.png\" alt=\"$game logo\" /></p>\n";
else if (file_exists ("logo/$game2.jpg"))
	echo "<p><img src=\"logo/$game2.jpg\" alt=\"$game logo\" /></p>\n";

if ($fullscreen < 1)
	echo "<p><big><b>".htmlspecialchars($vars["title"])." by ".htmlspecialchars($vars["author"])."</b></big></p>\n";

if ($format == "mov" || $format == "mp4")
{
	if ($fullscreen > 0)
		echo "<div style=\"width:100%; height:100%;\" id=\"container\"><a href=\"http://www.macromedia.com/go/getflashplayer\">Get the Flash Player</a> to see this player.</div>\n";
	else
		echo "<p id=\"container\"><a href=\"http://www.macromedia.com/go/getflashplayer\">Get the Flash Player</a> to see this player.</p>\n";
	echo "<script type=\"text/javascript\" src=\"swfobject.js\"></script>\n";
	echo "<script type=\"text/javascript\">\n";
	if ($fullscreen > 0)
		echo "var s1 = new SWFObject(\"player.swf\",\"player\",\"100%\",\"100%\",\"9.0.98\");\n";
	else
		echo "var s1 = new SWFObject(\"player.swf\",\"player\",\"".intval($vars["width"])."\",\"".intval($vars["height"])."\",\"9.0.98\");\n";
	echo "s1.addVariable(\"skin\", \"http://www.grimfiend.com/lp/vlp/Kleur-mod-new.swf\");\n";
	echo "s1.addParam(\"allowfullscreen\", \"true\");\n";
	echo "s1.addVariable(\"fullscreen\",\"true\");\n";
	if ($fullscreen < 1)
	{
		echo "s1.addVariable(\"width\",\"".intval($vars["width"])."\");\n";
		echo "s1.addVariable(\"height\",\"".intval($vars["height"])."\");\n";
	}
	else
	{
		//echo "s1.addVariable(\"width\",\"100%\");\n";
		//echo "s1.addVariable(\"height\",\"100%\");\n";
	}
	echo "s1.addVariable(\"file\",\"$video\");\n";
	if ($vars["game"] != "Super Adventure Rockman")
		echo "s1.addVariable(\"controlbar\", \"over\");\n";
	echo "s1.addVariable(\"image\", \"$preview\");\n";
	echo "s1.write(\"container\");\n";
	echo "</script>\n";
}
else if ($format == "avi")
{
	$vars["height"] = intval($vars["height"]) + 20;

	echo "<p><object type=\"video/divx\" data=\"$video\" width=\"".$vars["width"]."\" height=\"".$vars["height"]."\">\n";
	echo "<param name=\"type\" value=\"video/divx\" />\n";
	echo "<param name=\"src\" value=\"$video\" />\n";
	echo "<param name=\"data\" value=\"$video\" />\n";
	echo "<param name=\"codebase\" value=\"http://go.divx.com/plugin/DivXBrowserPlugin.cab\" />\n";
	echo "<param name=\"custommode\" value=\"none\" />\n";
	echo "<param name=\"autoPlay\" value=\"false\" />\n";
	echo "<param name=\"previewImage\" value=\"$preview\" />\n";
	echo "<param name=\"allowContextMenu\" value=\"true\" />\n";
	echo "<param name=\"pluginspage\" value=\"http://go.divx.com/plugin/download/\" />\n";
	echo "<param name=\"url\" value=\"$video\" />\n";
	echo "</object></p>\n";
	echo "<p><br />No video? Get the DivX Web Player for <a style=\"text-decoration: underline;\" href=\"http://download.divx.com/player/DivXWebPlayerInstaller.exe\">Windows</a> or <a style=\"text-decoration: underline;\" href=\"http://download.divx.com/player/DivXWebPlayer.dmg\">Mac</a></p>\n";
}

else
{
	exit("What the fuck did you do here?");
	echo "</body></html>\n";
}
?>

<?php

if ($fullscreen < 1)
{
	echo "<p><br /><a href=\"http://www.grimfiend.com/lp/vlp/$video\">Click here to download this video.</a></p>\n";
	echo "<p><small>Page viewed:";

	if (!file_exists($path2."/views.txt"))
	{
		//Doesn't exist? let's make one then
		$viewfile = fopen($path2."/views.txt", "x");
		fwrite($viewfile, "1");
		fclose($viewfile);

		//Write IP to file
		$ipfile = fopen($path2."/ip.txt", "x");
		fwrite($ipfile, $_SERVER["REMOTE_ADDR"]);
		fclose($ipfile);

		echo "1 time.";
	}
	else //Exists, check the IP to make sure they haven't viewed it before, then increment the view count if they haven't.
	{
		$ipfile = fopen($path2."/ip.txt", "r");

		while (!feof($ipfile))
		{
			$tempip = fgets($ipfile);

			if (trim($tempip) == $_SERVER["REMOTE_ADDR"])
				$ipfound = true;
		}

		fclose($ipfile);

		$viewfile2 = fopen($path2."/views.txt", "r");
		$viewnum = intval(trim(fgets($viewfile2)));
		fclose($viewfile2);

		if (!$ipfound)
		{
			//Okay I seriously don't get how any of this is bugging out and causign the views.txt to reset
			//if it happens again, read the ip.txt into an array and use the final number as the number of views
			//or compare the current viewnum to that number and if they don't match, use the lines in ip.txt
			$viewnum = intval($viewnum) + 1;
			
			$viewfile = fopen($path2."/views.txt", "w");
			fwrite($viewfile, $viewnum);
			fclose($viewfile);

			$ipfile = fopen($path2."/ip.txt", "a");
			fwrite($ipfile, "\n");
			fwrite($ipfile, $_SERVER["REMOTE_ADDR"]);
			fclose($ipfile);
		}

		echo $viewnum." times.";
	}
	echo "</small></p>\n";
}

if (($format == "mov" || $format == "mp4") && $fullscreen < 1)
{
	echo "<p><small>Uses <a href=\"http://www.jeroenwijering.com/?item=JW_FLV_Media_Player\">JW FLV Media Player 4.0</a>, player skin <a href=\"http://www.longtailvideo.com/skins.asp\">Kleur</a> modified by jawbroken, PHP by Static Fiend.  Source code <a href=\"http://www.grimfiend.com/lp/vlp/phpsrc.zip\">here</a>.</small></p>\n";
	echo "<p><a href=\"http://validator.w3.org/check?uri=referer\"><img src=\"http://www.w3.org/Icons/valid-xhtml10-blue\" alt=\"Valid XHTML 1.0 Strict\" height=\"31\" width=\"88\" /></a>\n";
	echo "<a href=\"http://jigsaw.w3.org/css-validator/check/referer\"><img style=\"border:0;width:88px;height:31px\" src=\"http://jigsaw.w3.org/css-validator/images/vcss\" alt=\"Valid CSS!\" /></a></p>\n";
}
else if ($fullscreen < 1)
{
	echo "<p><small>PHP by Static Fiend.  Source code <a href=\"http://www.grimfiend.com/lp/vlp/phpsrc.zip\">here</a>.</small></p>";
	if ($compat != 1)
	{
		echo "<p><a href=\"http://validator.w3.org/check?uri=referer\"><img src=\"http://www.w3.org/Icons/valid-xhtml10-blue\" alt=\"Valid XHTML 1.0 Strict\" height=\"31\" width=\"88\" /></a>\n";
	}
	else
		echo "<p>";
	echo "<a href=\"http://jigsaw.w3.org/css-validator/check/referer\"><img style=\"border:0;width:88px;height:31px\" src=\"http://jigsaw.w3.org/css-validator/images/vcss\" alt=\"Valid CSS!\" /></a></p>\n";
}
?>

</body>
</html>