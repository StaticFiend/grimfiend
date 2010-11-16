<?
/*
Index sorting function

by Static Fiend
*/

//FIXME: Rewrite this entire function to behave like the current test-index.php
//FIXME: Shouldn't need a return for $result, I believe it becomes global, but double check that.
function doSort($sort, $desc, $page) {

	if ($sort == "title") {
	        //Figure out how to properly limit here and do pages.
	        //Also think about another way to do desc/asc.
		if (!$desc)
			$query = "SELECT * FROM vidinfo ORDER BY title LIMIT ".$page.", 5";
	        else
	                $query = "SELECT * FROM vidinfo ORDER BY title DESC LIMIT ".$page.", 5";
	}
	else if ($sort == "author") {
	        if (!$desc)
	                $query = "SELECT * FROM vidinfo ORDER BY author LIMIT ".$page.", 5";
	        else
	                $query = "SELECT * FROM vidinfo ORDER BY author DESC LIMIT ".$page.", 5";
	}
	else if ($sort == "game") {
	        if (!$desc)
	                $query = "SELECT * FROM vidinfo ORDER BY game LIMIT ".$page.", 5";
	        else
	                $query = "SELECT * FROM vidinfo ORDER BY game DESC LIMIT ".$page.", 5";
	}
	else {
	        if (!$desc)
	                $query = "SELECT * FROM vidinfo ORDER BY dirname LIMIT ".$page.", 5";
	        else
        	        $query = "SELECT * FROM vidinfo ORDER BY dirname DESC LIMIT ".$page.", 5";
	}

	$result = mysql_query($query);

	return $result;
}
?>
