<?php
$versions['versionDate']['dconfig'] = '$Date: 2006/01/15 22:48:46 $'; 
$versions['versionRev']['dconfig'] = '$Revision: 1.6 $'; 
$versions['versionAuthor']['dconfig'] = '$Author: zanix $'; 

/*
	Whatever configuration for your addon you need
	Try to follow this format to reduce the chance
	that overwritting could occur
		$addon_conf['addon_name']['variable_name'] = 1;
*/


// The following is an "example conf.php" file



//------[ Show Debug? ]------------
	$addon_conf['talenttree']['debug'] = 1;


// Update Triggers config
	$addon_conf['talenttree']['trigger_1'] = 0;		// Save data
	$addon_conf['talenttree']['trigger_2'] = 0;		// Make image



	// Made By Recipe list configuration
	$generate_output = 1;			//dummy addon generates output


?>