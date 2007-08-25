<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    InstanceKeys
 * @subpackage Locale
*/

$lang['keys'] = 'Llaves';
$lang['keys_desc'] = 'Lists Azeroth Dungeon keys for guild members';
$lang['keybutton'] = 'Llaves|Lists Azeroth Dungeon keys for guild members';

$lang['admin']['keys_conf'] = 'Main Settings|Main Settings for Instance Keys';
$lang['admin']['colorcmp'] = 'Completed Color|Color of complete steps in the Quests/Parts tooltip';
$lang['admin']['colorcur'] = 'Current Color|Color of current step in the Quests/Parts tooltip';
$lang['admin']['colorno'] = 'Incomplete Color|Color of incomplete steps in the Quests/Parts tooltip';

// language definitions for the rogue instance keys 'fix'
$lang['thievestools']='Thieves\\\' Tools';
$lang['lockpicking']='Lockpicking';


/*
Instance Keys
=============
A part that is marked with 'MS' (milestone) will be designated as an overall status. So if
you have this one part it will mark all other parts lower than this one as complete.
*/

// ALLIANCE KEYS
$lang['inst_keys']['A'] = array(
	'SG' => array( 'Quests',
		'SG' => 'Llave de la Garganta de Fuego|4826',
			'The Horn of the Beast|',
			'Proof of Deed|',
			'At Last!|'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Llave de taller|2288'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'La llave Escarlata|4445'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Marra de Zul\\\'Farrak|5695',
			'Marra sacra|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Cetro de Celebras|19710',
			'Vara de Celebras|19549',
			'Diamante de Celebras|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Llave de celda de prisión|15545'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Llave Sombratiniebla|2966',
			'Ferrovil|9673'
		),
	'DM' => array( 'Key-Only',
		'DM' => 'Llave creciente|35607'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Llave esqueleto|16854',
			'Scholomance|',
			'Skeletal Fragments|',
			'Mold Rhymes With...|',
			'Fire Plume Forged|',
			'Araj\\\'s Scarab|',
			'The Key to Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Llave de la ciudad|13146'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Lacre de ascensión|17057',
			'Sello de ascensión sin adornar|5370',
			'Gema de Cumbrerroca|5379',
			'Gema de Espina Ahumada|16095',
			'Gema de Hacha de Sangre|21777',
			'Sello de Ascensión sin forjar|24554||MS',
			'Sello de Ascensión forjado|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Amuleto de Pirodraco|4829',
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
			'Amuleto de Pirodraco|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Quintaesencia eterna|22754'
		),
);


// HORDE KEYS
$lang['inst_keys']['H'] = array(
	'SG' => array( 'Key-Only',
		'SG' => 'Llave de la Garganta de Fuego|4826'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Llave de taller|2288'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'La llave Escarlata|4445'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Marra de Zul\\\'Farrak|5695',
			'Marra sacra|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Cetro de Celebras|19710',
			'Vara de Celebras|19549',
			'Diamante de Celebras|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Llave de celda de prisión|15545'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Llave Sombratiniebla|2966',
			'Ferrovil|9673'
		),
	'DM' => array( 'Key-Only',
		'DM' => 'Llave creciente|35607'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Llave esqueleto|16854',
			'Scholomance|',
			'Skeletal Fragments|',
			'Mold Rhymes With...|',
			'Fire Plume Forged|',
			'Araj\\\'s Scarab|',
			'The Key to Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Llave de la ciudad|13146'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Lacre de ascensión|17057',
			'Sello de ascensión sin adornar|5370',
			'Gema de Cumbrerroca|5379',
			'Gema de Espina Ahumada|16095',
			'Gema de Hacha de Sangre|21777',
			'Sello de Ascensión sin forjar|24554||MS',
			'Sello de Ascensión forjado|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Amuleto de Pirodraco|4829',
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
		'MC' => 'Quintaesencia eterna|22754'
		),
);
