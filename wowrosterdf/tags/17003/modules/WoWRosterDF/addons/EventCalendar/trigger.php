<?php
$versions['versionDate']['eventcalendar'] = '$Date: 2006/08/28 $'; 
$versions['versionRev']['eventcalendar'] = '$Revision: 1.0 $'; 
$versions['versionAuthor']['eventcalendar'] = '$Author: PoloDude $';

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
$updateChars = $addon_conf['EventCalendar']['EventUpdaters'];

//----------[ INSERT UPDATE TRIGGER BELOW ]-----------------------

// Run this on a character update
if( $mode == 'char' ){
	if( $addon_conf['EventCalendar']['UpdateTrigger'] ){
		// Check if character is allowed to updated raids
		if(in_array($member_name,$updateChars)){
			// If CT_RaidTracker data is there, assign it to $uploadData['RaidTracker']
			if( isset($uploadData['GroupCalendarData']) || isset($uploadData['GuildEventManagerData']) ){
				require_once('/var/www/vhosts/localdefence.net/httpdocs/modules/'.$module_name.'/addons/EventCalendar/functions.php');
				if($addon_conf['EventCalendar']['UpdateMode'] == 'GEM'){
					$output = processGEM($member_name,$uploadData['GuildEventManagerData']);
				}
				elseif($addon_conf['EventCalendar']['UpdateMode'] == 'GroupCalendar'){
					$output = processGRP($member_name,$uploadData['GroupCalendarData']);
				}
			}
		}
	}
	print("<!-- Update EventCalendar Log Output -->");
	echo $output;
}

?>
