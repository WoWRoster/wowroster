<?php
$versions['versionDate']['gdinfo'] = '$Date: 2005/12/30 20:40:52 $'; 
$versions['versionRev']['gdinfo'] = '$Revision: 1.4 $'; 
$versions['versionAuthor']['gdinfo'] = '$Author: mordon $';


function describeGDdyn()
{
	echo "\n<ul><li>GD support: ";
	if(function_exists("gd_info"))
	{
		echo "<font color=\"#00ff00\">yes</font>";
		$info = gd_info();
		$keys = array_keys($info);
		for($i=0; $i<count($keys); $i++)
		{
			if(is_bool($info[$keys[$i]]))
				echo "</li>\n<li>" . $keys[$i] .": " . yesNo($info[$keys[$i]]);
			else
				echo "</li>\n<li>" . $keys[$i] .": " . $info[$keys[$i]];
		}
	}
	else
		echo "<font color=\"#ff0000\">no</font>";

	echo "</li></ul>";
}

function yesNo($bool)
{
	if($bool) return "<font color=\"#00ff00\"> yes</font>";
	else return "<font color=\"#ff0000\"> no</font>";
}

echo describeGDdyn();

?>