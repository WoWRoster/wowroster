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
$lang['admin']['keys_access'] = 'Access|Set who can view the keys list';

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

$lang['inst_keys'][$fact][$key_name] = array( array( 'type' => $type, 'value' => $value, 'marker' => $marker, 'active' => $active), ... );

Or, alternatively, with the conversion function in the bottom of this file:
$lang['inst_keys'][$fact][$key_name] = array( '$type|$value|$marker|$active', ... );

$fact is the faction.
$key_name is the key name. The key name should be alphanumeric, start with a letter, and can't change between versions because it's stored in the database.
$type and $value indicate the requirement for this stage to be active:
  $type='Q' means a quest named $value must be in the character's questlog
  $type='Ii' means an item with base ItemID $value must be in the character's inventory.
  $type='In' means an item with name $value must be in the characters inventory.

$marker is for rendering purposes. It has these values:
  $marker='SOURCE'
  $marker='DRAIN'
  $marker='SINGLE'

$active indicates if this stage should be counted as complete if it is active. This means it will count towards key progress in the progress bar. It will still be colored like an active stage in the tooltip.

SOURCEs and DRAINs define subchains. the SOURCE marker needs to be put on the first stage of the subchain, the DRAIN marker should be put on the last. A SINGLE is a single-stage subchain.

stages should be integers, ascending, starting at 0, without gaps. In other words, put them in the right order and have PHP assign the keys.

The key is the last stage of the chain.
*/

// ALLIANCE KEYS
$lang['inst_keys']['A'] = array(
	'SG' => array( 
		'Q|The Horn of the Beast||',
		'Q|Proof of deed||',
		'Q|At Last!||',
		'In|Key to Searing Gorge||1'
	),
	'Gnome' => array(
		'In|Workshop Key||1'
	),
	'SM' => array(
		'In|The Scarlet Key||1'
	),
	'ZF' => array(
		'In|Sacred Mallet||1',
		'In|Mallet of Zul\'Farrak||1'
	),
	'Mauro' => array(
		'In|Celebrian Rod|SINGLE|1',
		'In|Celebrian Diamond|SINGLE|1',
		'In|Scepter of Celebras||1'
	),
	'BRDp' => array(
		'In|Prison Cell Key||1'
	),
	'BRDs' => array(
		'In|Ironfel||1',
		'In|Shadowforge Key||1'
	),
	'DM' => array(
		'In|Crescent Key||1'
	),
	'Scholo' => array(
		'Q|Scholomance||',
		'Q|Skeletal Fragments||',
		'Q|Mold Rhymes With...||',
		'Q|Fire Plume Forged||',
		'Q|Araj\'s Scarab||',
		'Q|The Key to Scholomance||',
		'In|Skeleton Key||1'
	),
	'Strath' => array(
		'In|Key to the City||1'
	),
	'UBRS' => array(
		'In|Unadorned Seal of Ascension|SINGLE|1',
		'In|Gemstone of Spirestone|SINGLE|1',
		'In|Gemstone of Smolderthorn|SINGLE|1',
		'In|Gemstone of Bloodaxe|SINGLE|1',
		'In|Unforged Seal of Ascension||1',
		'In|Forged Seal of Ascension||1',
		'In|Seal of Ascension||1'
	),
	'Onyxia' => array(
		'Q|Dragonkin Menace||1',
		'Q|The True Masters||1',
		'Q|Marshal Windsor||1',
		'Q|Abandoned Hope||1',
		'Q|A Crumpled Up Note||1',
		'Q|A Shred of Hope||1',
		'Q|Jail Break!||1',
		'Q|Stormwind Rendezvous||1',
		'Q|The Great Masquerade||1',
		'Q|The Dragon\'s Eye||1',
		'Q|Drakefire Amulet||1',
		'In|Drakefire Amulet||1'
	),
	'MC' => array(
		'In|Eternal Quintessence||1'
	)
);


// HORDE KEYS
$lang['inst_keys']['H'] = array(
/* Horde can't get the searing key
	'SG' => array(
		'In|Key to Searing Gorge||1'
	),*/
	'Gnome' => array(
		'In|Workshop Key||1'
	),
	'SM' => array(
		'In|The Scarlet Key||1'
	),
	'ZF' => array(
		'In|Sacred Mallet||1',
		'In|Mallet of Zul\'Farrak||1'
	),
	'Mauro' => array(
		'In|Celebrian Rod|SINGLE|1',
		'In|Celebrian Diamond|SINGLE|1',
		'In|Scepter of Celebras||1'
	),
	'BRDp' => array(
		'In|Prison Cell Key||1'
	),
	'BRDs' => array(
		'In|Ironfel||1',
		'In|Shadowforge Key||1'
	),
	'DM' => array(
		'In|Crescent Key||1'
	),
	'Scholo' => array(
		'Q|Scholomance||',
		'Q|Skeletal Fragments||',
		'Q|Mold Rhymes With...||',
		'Q|Fire Plume Forged||',
		'Q|Araj\'s Scarab||',
		'Q|The Key to Scholomance||',
		'In|Skeleton Key||1'
	),
	'Strath' => array(
		'In|Key to the City||1'
	),
	'UBRS' => array(
		'In|Unadorned Seal of Ascension|SINGLE|1',
		'In|Gemstone of Spirestone|SINGLE|1',
		'In|Gemstone of Smolderthorn|SINGLE|1',
		'In|Gemstone of Bloodaxe|SINGLE|1',
		'In|Unforged Seal of Ascension||1',
		'In|Forged Seal of Ascension||1',
		'In|Seal of Ascension||1'
	),
	'Onyxia' => array(
		'Q|Warlord\'s Command||',
		'Q|Eitrigg\'s Wisdom||',
		'Q|For The Horde!||',
		'Q|What the Wind Carries||',
		'Q|The Champion of the Horde||',
		'Q|The Testament of Rexxar||',
		'Q|Oculus Illusions||',
		'Q|Emberstrife||',
		'Q|The Test of Skulls, Scryer|SINGLE|',
		'Q|The Test of Skulls, Somnus|SINGLE|',
		'Q|The Test of Skulls, Chronalis|SINGLE|',
		'Q|The Test of Skulls, Axtroz||',
		'Q|Ascension...||',
		'Q|Blood of the Black Dragon Champion||',
		'In|Drakefire Amulet||1'
	),
	'MC' => array(
		'In|Eternal Quintessence||1'
	)
);

// Convert key format
foreach( $lang['inst_keys'] as $faction => $keys )
{
	foreach( $keys as $key => $stages )
	{
		foreach( $stages as $stage => $data )
		{
			list( $type, $value, $marker, $active ) = explode('|', $data);
			$lang['inst_keys'][$faction][$key][$stage] = array(
				'type' => $type,
				'value' => $value,
				'marker' => $marker,
				'active' => $active
			);
		}
	}
}
