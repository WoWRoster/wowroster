<?php
$versions['versionDate']['raidtracker'] = '$Date: 2006/08/13 $'; 
$versions['versionRev']['raidtracker'] = '$Revision: 1.1 $'; 
$versions['versionAuthor']['raidtracker'] = '$Author: PoloDude $';

/******************************
 * $Id$
 ******************************/

if (!defined("CPG_NUKE")) { exit; }

/*
	Start the following scripts when "update.php" is called

	Available variables
		- $wowdb       = roster's db layer
		- $member_id   = character id from the database ( ex. 24 )
		- $member_name = character's name ( ex. 'Jonny Grey' )
		- $roster_conf = The entire roster config array
		- $mode        = when you want to run the trigger
			= 'char'  - during a character update
			= 'guild' - during a guild update

	You may need to do some fancy coding if you need more variables

	You can just print any needed output

*/

global $uploadData,$db_prefix;
$output = '';
$updateChars = $addon_conf['RaidTracker']['RaidUpdaters'];

//----------[ INSERT UPDATE TRIGGER BELOW ]-----------------------

// Run this on a character update
if( $mode == 'char' ){
	if( $addon_conf['RaidTracker']['UpdateTrigger'] ){
		// Check if character is allowed to updated raids
		if(in_array($member_name,$updateChars)){
			// If CT_RaidTracker data is there, assign it to $uploadData['RaidTracker']
			if( isset($uploadData['RaidTrackerData']) ){
				include_once(BASEDIR.'modules/'.$module_name.'/addons/RaidTracker/functions.php');
				$output = processRaids($member_name,$uploadData['RaidTrackerData']);
			}
		}
	}
	print("<!-- Update Log Output -->");
	return $output;
}

?>
