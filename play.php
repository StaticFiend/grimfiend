<? echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
include("../../misc.php");

dbConnect($dbh);

$idnum = $_GET["id"];
$idnum = stripslashes($idnum);
$idnum = mysql_real_escape_string($idnum);
$idnum = intval($idnum);

$fullscreen = $_GET["fullscreen"];

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
	exit(""); // Yes even my error pages can be validated!
}
else
{
	$vars = mysql_fetch_array($sql);
	mysql_close($dbh);
}
?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>GrimFiend Video LP Player - <? echo htmlspecialchars($vars["title"])." by ".htmlspecialchars($vars["author"]); ?></title>
<style type="text/css">
<!--
body {background-color: #<? echo strtoupper(str_pad(dechex($vars['background']), 6, "0", STR_PAD_LEFT)); ?>; padding: 0; margin: 0; height: 100%; text-align: center; font-family: arial, helvetica, sans-serif;}
div#container {height: 100%}
p {color: #<? echo strtoupper(str_pad(dechex($vars['foreground']), 6, "0", STR_PAD_LEFT)); ?>; line-height: 7px; font-size: 12px}
a {color: #AA3333; text-decoration: none;}
img {border: 0px}
html { height: 100%; <? if ($fullscreen == 1) echo "overflow: hidden;"; ?>}
-->
</style>
<?

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

if ($vars["game"] == "Super Adventure Rockman" && $vars["game"] == "Butcher Bay")
{
	$noover = 1;
	$height2 = $vars["height"] + 20;
}

if (!file_exists($video))
{
	echo "</head><body>\n";
	echo "<p>ERROR: Unable to load video</p>\n";
	echo "</body></html>";
	exit("");
}
if (!file_exists($preview))
	$preview = "default.jpg";

if ($format == "mov" || $format == "mp4")
{?>
<script type="text/javascript" src="swfobject.js"></script>
<script type="text/javascript">

var flashvars = {
skin: "stylish_mod.swf",
fullscreen: "true",
<?if ($fullscreen < 1)
{?>
width: "<?=intval($vars["width"])?>",
height: "<?=intval($vars["height"])?>",
<?}?>
<?if ($noover != 1)
{?>
controlbar: "over",
<?}?>
image: "<?=$preview?>",
file: "<?=$video?>"
};

var params = {
allowfullscreen: "true"
};

var attributes = {};

<?if ($fullscreen > 0)
{?>
swfobject.embedSWF("player.swf", "container", "100%", "100%", "9.0.98", false, flashvars, params, attributes);
<?}
else if ($noover == 1)
{?>
swfobject.embedSWF("player.swf", "container", "<?=intval($vars["width"])?>", <?=$height2?>, "9.0.98", false, flashvars, params, attributes);
<?}
else
{?>
swfobject.embedSWF("player.swf", "container", "<?=intval($vars["width"])?>", "<?=intval($vars["height"])?>", "9.0.98", false, flashvars, params, attributes);
<?}
}?>
</script>
</head>
<body>
<?//Banner code
$game2 = str_replace(":", "", $vars["game"]);

if (file_exists("logo/$game2.png") && $fullscreen < 1)
	echo "<p><img src=\"logo/$game2.png\" alt=\"$game logo\" /></p>\n";
else if (file_exists ("logo/$game2.jpg") && $fullscreen < 1)
	echo "<p><img src=\"logo/$game2.jpg\" alt=\"$game logo\" /></p>\n";

if ($fullscreen < 1)
	echo "<p><b>".htmlspecialchars($vars["title"])." by ".htmlspecialchars($vars["author"])."</b></p>\n";

if ($format == "mov" || $format == "mp4")
{ 
	if ($fullscreen > 0)
		echo "<div style=\"width:100%; height:100%;\" id=\"container\"><a href=\"http://www.macromedia.com/go/getflashplayer\">Get the Flash Player</a> to see this player.</div>\n";
	else
		echo "<p id=\"container\"><a href=\"http://www.macromedia.com/go/getflashplayer\">Get the Flash Player</a> to see this player.</p>\n";
}
else if ($format == "avi")
{
	$vars["height"] = intval($vars["height"]) + 20; ?>

<p><object type="video/divx" data="<?=$video?>" width="<?=$vars["width"]?>" height="<?=$vars["height"]?>">
<param name="type" value="video/divx" />
<param name="src" value="<?=$video?>" />
<param name="data" value="<?=$video?>" />
<param name="codebase" value="http://go.divx.com/plugin/DivXBrowserPlugin.cab" />
<param name="custommode" value="none" />
<param name="autoPlay" value="false" />
<param name="previewImage" value="<?=$preview?>" />
<param name="allowContextMenu" value="true" />
<param name="pluginspage" value="http://go.divx.com/plugin/download/" />
<param name="url" value="<?=$video?>" />
</object></p>
<p><br />No video? Get the DivX Web Player for <a style="text-decoration: underline;" href="http://download.divx.com/player/DivXWebPlayerInstaller.exe">Windows</a> or <a style="text-decoration: underline;" href="http://download.divx.com/player/DivXWebPlayer.dmg">Mac</a></p>
<?
}
else
{
	echo "<p>What the hell did you do?!?!</p></body></html>\n";
	exit("");
}

if ($fullscreen < 1)
{
	echo "<p><br /><a href=\"http://www.grimfiend.com/lp/vlp/$video\">Click here to download this video.</a></p>\n";
	if ($format == "mov" || $format == "mp4")
		echo "<p>Want to watch in your full browser window?  <a href=\"play.php?id=$idnum&amp;fullscreen=1\">Click here!</a></p>\n";
	echo "<p>Page viewed: ";

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
	echo "</p>\n";
}

if (($format == "mov" || $format == "mp4") && $fullscreen < 1)
{ ?>
<p>Uses <a href="http://www.jeroenwijering.com/?item=JW_FLV_Media_Player">JW FLV Media Player 4.2.90</a>, player skin <a href="http://www.longtailvideo.com/AddOns/productpage.html?addon=50&amp;q=&amp;category=skins">Stylish</a> modified by jawbroken, PHP by Static Fiend.  Source code <a href="http://github.com/StaticFiend/grimfiend/tree/master">here</a>.</p>
<p><a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
<a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!" /></a></p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<p><input type="hidden" name="cmd" value="_s-xclick" />
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" />
<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
" /></p>
<?
}
else if ($fullscreen < 1)
{ ?>
<p>PHP by Static Fiend.  Source code <a href="http://github.com/StaticFiend/grimfiend/tree/master">here</a>.</p>
<p><a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
<a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!" /></a></p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<p><input type="hidden" name="cmd" value="_s-xclick" />
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" />
<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
" /></p>
<?
}
?>
</form>
</body>
</html>