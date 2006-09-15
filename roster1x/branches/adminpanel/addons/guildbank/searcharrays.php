<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// Do Not Change These //
$search_order = array(3,4,5,6,7,8,18,9,27,1,2,31,28,25,10,12,11,13,14,
		      26,20,21,22,15,16,17,19,24,23,30,32);

$bankitem[1]['arg'] = 'armor';
$bankitem[2]['arg'] = 'weapon';
$bankitem[3]['arg'] = 'leatherwork';
$bankitem[4]['arg'] = 'tailor';
$bankitem[5]['arg'] = 'plan';
$bankitem[6]['arg'] = 'recipe';
$bankitem[7]['arg'] = 'formula';
$bankitem[8]['arg'] = 'schematic';
$bankitem[9]['arg'] = 'firstaid';
$bankitem[10]['arg']= 'cloth';
$bankitem[11]['arg']= 'herbs';
$bankitem[12]['arg']= 'potion';
$bankitem[13]['arg']= 'cards';
$bankitem[14]['arg']= 'leather';
$bankitem[15]['arg']= 'gems';
$bankitem[16]['arg']= 'enchant';
$bankitem[17]['arg']= 'metal';
$bankitem[18]['arg']= 'val';
$bankitem[19]['arg']= 'write';
$bankitem[20]['arg']= 'zg'; // Zul'Gurub
$bankitem[21]['arg']= 'aq'; // Ahn'Quiraj
$bankitem[22]['arg']= 'mc'; // Molten Core
$bankitem[23]['arg']= 'food';
$bankitem[24]['arg']= 'scale';
$bankitem[25]['arg']= 'container';
$bankitem[26]['arg']= 'elemental';
$bankitem[27]['arg']= 'fish';
$bankitem[28]['arg']= 'quest';
$bankitem[29]['arg']= 'misc';
$bankitem[30]['arg']= 'scroll';
$bankitem[31]['arg']= 'engineer';
$bankitem[32]["arg"]= 'darkmoon';

/********************************************************************/
// Build the $itemarray based on all $search_order category numbers, and multilanguages
$itemarray = array();
itemarray_merge('omit');
foreach ($search_order as $catnumber)
{
	itemarray_merge($bankitem[$catnumber]['arg']);
}

// Merge the search words for multilanguage purposes based on the category name
function itemarray_merge($category)
{
	global $itemarray, $roster_conf, $wordings;

	// Fill the itemarray with all language wordings
	$itemarray[$category] = array();
	foreach ($roster_conf['multilanguages'] as $language)
	{
		if (isset($wordings[$category][$language]))
		{
			$itemarray[$category] = array_merge($itemarray[$category], $wordings[$category][$language]);
		}
	}
}

?>