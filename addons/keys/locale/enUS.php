<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * enUS Locale
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    InstanceKeys
 * @subpackage Locale
*/

$lang['keys'] = 'Instance Keys';
$lang['keys_desc'] = 'Lists Azeroth Dungeon keys for guild members';
$lang['keybutton'] = 'Instance Keys|Lists Azeroth Dungeon keys for guild members';

$lang['admin']['keys_conf'] = 'Main Settings|Main Settings for Instance Keys';
$lang['admin']['colorcmp'] = 'Completed Color|Color of complete steps in the Quests/Parts tooltip';
$lang['admin']['colorcur'] = 'Current Color|Color of current step in the Quests/Parts tooltip';
$lang['admin']['colorno'] = 'Incomplete Color|Color of incomplete steps in the Quests/Parts tooltip';

// language definitions for the rogue instance keys 'fix'
$lang['thievestools']='Thieves\\\' Tools';
$lang['lockpicking']='Lockpicking';

$lang['Quests'] = 'Quests';
$lang['Parts'] = 'Parts';
$lang['key_status'] = '%1$s %2$s Status';


/*
Instance Keys
=============
A part that is marked with 'MS' (milestone) will be designated as an overall status. So if
you have this one part it will mark all other parts lower than this one as complete.
*/

// ALLIANCE KEYS
$lang['inst_keys']['A'] = array(
	'SG' => array( 'Quests',
		'SG' =>	'Key to Searing Gorge|5396',
			'The Horn of the Beast|',
			'Proof of Deed|',
			'At Last!|'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Workshop Key|6893'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'The Scarlet Key|7146'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Mallet of Zul\\\'Farrak|9240',
			'Sacred Mallet|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Scepter of Celebras|17191',
			'Celebrian Rod|19549',
			'Celebrian Diamond|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Prison Cell Key|11140'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Shadowforge Key|11000',
			'Ironfel|9673'
		),
	'DM' => array( 'Key-Only',
		'DM' => 'Crescent Key|18249'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Skeleton Key|13704',
			'Scholomance|',
			'Skeletal Fragments|',
			'Mold Rhymes With...|',
			'Fire Plume Forged|',
			'Araj\\\'s Scarab|',
			'The Key to Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Key to the City|12382'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Seal of Ascension|12344',
			'Unadorned Seal of Ascension|5370',
			'Gemstone of Spirestone|5379',
			'Gemstone of Smolderthorn|16095',
			'Gemstone of Bloodaxe|21777',
			'Unforged Seal of Ascension|24554||MS',
			'Forged Seal of Ascension|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Drakefire Amulet|16309',
			'Dragonkin Menace|',
			'The True Masters|',
			'Marshal Windsor|',
			'Abandoned Hope|',
			'A Crumpled Up Note|',
			'A Shred of Hope|',
			'Jail Break!|',
			'Stormwind Rendezvous|',
			'The Great Masquerade|',
			'The Dragon\\\'s Eye|',
			'Drakefire Amulet|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Eternal Quintessence|22754'
		),
);


// HORDE KEYS
$lang['inst_keys']['H'] = array(
	'SG' => array( 'Key-Only',
		'SG' => 'Key to Searing Gorge|5396'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Workshop Key|6893'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'The Scarlet Key|7146'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Mallet of Zul\\\'Farrak|9240',
			'Sacred Mallet|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Scepter of Celebras|17191',
			'Celebrian Rod|19549',
			'Celebrian Diamond|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Prison Cell Key|11140'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Shadowforge Key|11000',
			'Ironfel|9673'
		),
	'DM' => array( 'Key-Only',
		'DM' => 'Crescent Key|18249'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Skeleton Key|13704',
			'Scholomance|',
			'Skeletal Fragments|',
			'Mold Rhymes With...|',
			'Fire Plume Forged|',
			'Araj\\\'s Scarab|',
			'The Key to Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Key to the City|12382'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Seal of Ascension|12344',
			'Unadorned Seal of Ascension|5370',
			'Gemstone of Spirestone|5379',
			'Gemstone of Smolderthorn|16095',
			'Gemstone of Bloodaxe|21777',
			'Unforged Seal of Ascension|24554||MS',
			'Forged Seal of Ascension|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Drakefire Amulet|16309',
			'Warlord\\\'s Command|',
			'Eitrigg\\\'s Wisdom|',
			'For The Horde!|',
			'What the Wind Carries|',
			'The Champion of the Horde|',
			'The Testament of Rexxar|',
			'Oculus Illusions|',
			'Emberstrife|',
			'The Test of Skulls, Scryer|',
			'The Test of Skulls, Somnus|',
			'The Test of Skulls, Chronalis|',
			'The Test of Skulls, Axtroz|',
			'Ascension...|',
			'Blood of the Black Dragon Champion|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Eternal Quintessence|22754'
		),
);
