<?php
/******************************
 * Gear Browser
 * By Rihlsul
 * www.ironcladgathering.com
 * v 1.0  (9/2/2006 2:15 PM)
 * Compatible with Roster 1.70
 ******************************/


if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$wordings['enUS']['gearbrowser'] = 'Gear Browser';
$wordings['enUS']['gearbrowseroptions'] = 'Options';
$wordings['enUS']['gearbrowserapply'] = 'Apply';

$wordings['enUS']['gearbrowser-leftSideLabel'] = "Left Panel Member";
$wordings['enUS']['gearbrowser-leftSideExplain'] = "Which member in the roster to display on the left panel.";

$wordings['enUS']['gearbrowser-rightsidetype'] = "Right Panel Options";
$wordings['enUS']['gearbrowser-rightsidetypeplayer'] = "Display Players";
$wordings['enUS']['gearbrowser-rightsidetypeguildbank'] = "Display Guild Bank";
$wordings['enUS']['gearbrowser-rightsidetypemadeby'] = "Display Made By";

$wordings['enUS']['gearbrowser-rightSideLabel'] = "Right Panel Member";
$wordings['enUS']['gearbrowser-rightSideExplain'] = "Which member in the roster to display on the right panel.";

$wordings['enUS']['gearbrowser-minimumlevel'] = "Minimum Level";
$wordings['enUS']['gearbrowser-maximumlevel'] = "Maximum Level";

$wordings['enUS']['gearbrowser-AvailableDisclaimer'] = "Note: Only members who have uploaded are available to compare.";


//*********** Gleefully stolen from guildbank ****************
$wordings['enUS']['gearbrowser-filter'] = 'Filter';
$wordings['enUS']['gearbrowser-gbank_charsNotFound'] = 'Could not find any '.$wordings[$roster_conf['roster_lang']]['guildbank'].' '.$wordings[$roster_conf['roster_lang']]['character'].'s.';
$wordings['enUS']['gearbrowser-reqlevel'] = 'Requires Level';
$wordings['enUS']['gearbrowser-lvlrange'] = 'Level Range';

$wordings['deDE']['gearbrowser-filter'] = 'Filter';
$wordings['deDE']['gearbrowser-gbank_charsNotFound'] = $wordings[$roster_conf['roster_lang']]['guildbank'].' '.$wordings[$roster_conf['roster_lang']]['character'].'s night gefunden.';
$wordings['deDE']['gearbrowser-reqlevel'] = 'Benötigt Level';
$wordings['deDE']['gearbrowser-lvlrange'] = 'Level Bereich';

$wordings['enUS']['gearbrowser-armor'] =
								array("Head", "Neck", "Shoulder", "Back", "Chest", "Wrist", "Hands", "Waist",
									  "Legs", "Feet", "Finger", "Shield");

$wordings['enUS']['gearbrowser-weapon'] =  
								array("damage per second", "Off-hand", "Off Hand");

$wordings['deDE']['gearbrowser-armor'] =
								array("Kopf", "Hals", "Schulter", "Rücken", "Brust", "Handgelenke","Hände",
								"Taille", "Beine", "Füße", "Finger", "Schild");

$wordings['deDE']['gearbrowser-weapon'] =
								array("Einhändig", "Zweihändig", "Schusswaffe", "Armbrust", "Zauberstab",
								"Projektil", "Waffenhand", "Schildhand", "Nebenhand", "Crossbow");

$wordings['enUS']['gbitem_1'] =  "Armor";
$wordings['enUS']['gbitem_2'] =  "Weapons";
$wordings['deDE']['gbitem_1'] =  "Rüstungen";
$wordings['deDE']['gbitem_2'] =  "Waffen";

?>