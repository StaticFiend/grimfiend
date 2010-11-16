<?

/*
Avatar finding and echoing code for GrimFiend.com (using Gravatar as a service).

by Static Fiend
*/
function findGravatar($name, $authors) //Find's the authors name amongst the $authors array and gives us a pointer.
{
	global $avatarFound, $avatarPointer;
	$x = 0;

//	echo "<div>function called, $name $x ".count($authors)."</div>\n";
	
	while($x < count($authors))
	{
//		echo "<div>DEBUG: Current x value is: $x\n";
		if (!strstr($name, $authors[$x]))
		{
			$x++;
			continue;
		}
		else
		{
			$avatarFound = 1;
			$avatarPointer = $x;
//			echo " - Avatar found at $x value of avatarFound $avatarFound $avatarPointer</div>\n";
			break;
		}
		$x++;
	}
}

function showAvatar($authormd5, $avatarPointer, $avatarFound) //Simply echo's the author's avatar onto the current page.
{
	if ($avatarFound == 1)
		echo "<img src=\"http://www.gravatar.com/avatar/".$authormd5[$avatarPointer]."?s=160&amp;r=x\" alt=\"authors avatar\" />";
	else
		echo "<img src=\"avatar_default.png\" />";
}

?>
