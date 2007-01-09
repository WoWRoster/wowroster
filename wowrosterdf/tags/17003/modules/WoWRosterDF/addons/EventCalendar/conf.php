<?php
$versions['versionDate']['eventcalendar'] = '$Date: 2006/08/28 $'; 
$versions['versionRev']['eventcalendar'] = '$Revision: 1.0 $';
$versions['versionAuthor']['eventcalendar'] = '$Author: PoloDude $';

// What calendar will you be updating -- GEM or GroupCalendar
	$addon_conf['EventCalendar']['UpdateMode'] = 'GroupCalendar';

//Characters that can update the events
	$addon_conf['EventCalendar']['EventUpdaters'] = array(
		'Anaxent',
		'Updater2',
	);
//Channels that can be updated (GEM only)
	$wordings['EventCalendar']['Channels'] = array(
		'channel1',
		'channel2',
	);

// Show eventicon or not -- true or false
	$addon_conf['EventCalendar']['ShowIcon'] = true;

// Resetoffset -1 for EU, 6 for US (i think :/ )
	$wordings['EventCalendar']['ResetOffset'] = -1;

//Date display "D m/d G:i" => Wed 08/23 20:15
	$addon_conf['RaidTracker']['EventDate'] = "D d/m G:i";
	$addon_conf['RaidTracker']['ResetDate'] = "D d/m";

/****** DON'T CHANGE ANYTHING BELOW! ******/

//Update Trigger
	$addon_conf['EventCalendar']['UpdateTrigger'] = 1;

// General variables
	$wordings['EventCalendar']['Classes'] = array(
		"P" => "Priest",
		"S" => "Shaman",
		"M" => "Mage",
		"R" => "Rogue",
		"D" => "Druid",
		"W" => "Warrior",
		"H" => "Hunter",
		"K" => "Warlock",
		"L" => "Paladin",
	);

?>