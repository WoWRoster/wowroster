<?php
$versions['versionDate']['raidtracker'] = '$Date: 2006/08/13 $'; 
$versions['versionRev']['raidtracker'] = '$Revision: 1.1 $';
$versions['versionAuthor']['raidtracker'] = '$Author: PoloDude $';

//Characters that can update the raids
	$addon_conf['RaidTracker']['RaidUpdaters'] = array(
		'Anaxent', 'Cain', 'Linola'
	);

// Date display like "Y-m-d G:i:s", "d-m-Y G:i:s"
	$addon_conf['RaidTracker']['DateView'] = "Y-m-d G:i:s";

// Show loot in one box or seperate by users who won
	$addon_conf['RaidTracker']['SortByUser'] = 0;

/****** DON'T CHANGE ANYTHING BELOW! ******/

//Update Trigger
	$addon_conf['RaidTracker']['UpdateTrigger'] = 1;

// General variables
	$rt_wordings['RaidTracker']['ZoneIcons'] = array(
		"Zul'Gurub" => "zg",
		"Onyxia's Lair" => "onx",
		"Molten Core" => "mc",
		"Blackwing Lair" => "bwl",
		"Ahn'Qiraj Ruins" => "aq20",
		"Ahn'Qiraj Temple" => "aq40",
		"Naxxramas" => "nax",
		"World Bosses" => "outdoor",
		"RandomRaid" => "outdoor",
	);

?>