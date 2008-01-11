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

$lang['rep2level'] = array(
	'Hated' => '-3',
	'Hostile' => '-2',
	'Unfriendly' => '-1',
	'Neutral' => '0',
	'Friendly' => '1',
	'Honored' => '2',
	'Revered' => '3',
	'Exalted' => '4'
);

/*
Instance Keys
=============

The generic format for a key definition is:

$lang['inst_keys'][$fact][$key_name] = array( $stage_definition, ...);

The stage definition can be defined in two ways:
- As an array with named keys. These keys are 'type', 'name', 'count', 'marker', and 'active'.
- As a shorthand. This consists of 'type|name|count|marker|active'.

Note that in the shorthand format all fields have to be present in this order. Also nonsensical ones like 'count' for a quest, since generic code is easier to maintain than code with exceptions.

$fact is the faction.
$key_name is the key name. The key name should be alphanumeric, start with a letter, and can't change between versions because it's stored in the database.

'type', 'value', and 'count' indicate the requirement for this stage.
'type' = 'G' means you need to have at least 'count' money. 'value' needs to be 'money' and 'count' must be in copper.
'type' = 'Ii' means you need to have at least 'count' items with base item ID 'value'.
'type' = 'In' means you need to have at least 'count' items named 'value'.
'type' = 'Q' means you need to have at least 'count' quests named 'value'. 'count' will probably always be 1 here, but is still required.
'type' = 'R' means you need to have at least reputation level 'count' with the faction 'value'. Reputation level is the string for this locale, or the ID defined in the array above.
'type' = 'S' means you need to have a skill level of at least 'count' at 'value'.

'marker' is for rendering purposes. It has these values:
  'marker'='SOURCE'
  'marker'='DRAIN'
  'marker'='SINGLE'

$active indicates if this stage should be counted as complete if it is active. This means it will count towards key progress in the progress bar. It will still be colored like an active stage in the tooltip.

SOURCEs and DRAINs define subchains. the SOURCE marker needs to be put on the first stage of the subchain, the DRAIN marker should be put on the last. A SINGLE is a single-stage subchain.

stages should be integers, ascending, starting at 0, without gaps. In other words, put them in the right order and have PHP assign the keys.

The key is the last stage of the chain.
*/

// ALLIANCE KEYS
$lang['inst_keys']['A'] = array(
	'SG' => array( 
		'Q|The Horn of the Beast|1||',
		'Q|Proof of deed|1||',
		'Q|At Last!|1||',
		'In|Key to Searing Gorge|1||1'
	),
	'Gnome' => array(
		'In|Workshop Key|1||1'
	),
	'SM' => array(
		'In|The Scarlet Key|1||1'
	),
	'ZF' => array(
		'In|Sacred Mallet|1||1',
		'In|Mallet of Zul\'Farrak|1||1'
	),
	'Mauro' => array(
		'In|Celebrian Rod|1|SINGLE|1',
		'In|Celebrian Diamond|1|SINGLE|1',
		'In|Scepter of Celebras|1||1'
	),
	'BRDp' => array(
		'In|Prison Cell Key|1||1'
	),
	'BRDs' => array(
		'In|Ironfel|1||1',
		'In|Shadowforge Key|1||1'
	),
	'DM' => array(
		'In|Crescent Key|1||1'
	),
	'Scholo' => array(
		'Q|Scholomance|1||',
		'Q|Skeletal Fragments|1||',
		'Q|Mold Rhymes With...|1||',
		'Q|Fire Plume Forged|1||',
		'Q|Araj\'s Scarab|1||',
		'Q|The Key to Scholomance|1||',
		'In|Skeleton Key|1||1'
	),
	'Strath' => array(
		'In|Key to the City|1||1'
	),
	'UBRS' => array(
		'In|Unadorned Seal of Ascension|1|SINGLE|1',
		'In|Gemstone of Spirestone|1|SINGLE|1',
		'In|Gemstone of Smolderthorn|1|SINGLE|1',
		'In|Gemstone of Bloodaxe|1|SINGLE|1',
		'In|Unforged Seal of Ascension|1||1',
		'In|Forged Seal of Ascension|1||1',
		'In|Seal of Ascension|1||1'
	),
	'Onyxia' => array(
		'Q|Dragonkin Menace|1||1',
		'Q|The True Masters|1||1',
		'Q|Marshal Windsor|1||1',
		'Q|Abandoned Hope|1||1',
		'Q|A Crumpled Up Note|1||1',
		'Q|A Shred of Hope|1||1',
		'Q|Jail Break!|1||1',
		'Q|Stormwind Rendezvous|1||1',
		'Q|The Great Masquerade|1||1',
		'Q|The Dragon\'s Eye|1||1',
		'Q|Drakefire Amulet|1||1',
		'In|Drakefire Amulet|1||1'
	),
	'MC' => array(
		'In|Eternal Quintessence|1||1'
	)
);


// HORDE KEYS
$lang['inst_keys']['H'] = array(
/* Horde can't get the searing key
	'SG' => array(
		'In|Key to Searing Gorge||1'
	),*/
	'Gnome' => array(
		'In|Workshop Key|1||1'
	),
	'SM' => array(
		'In|The Scarlet Key|1||1'
	),
	'ZF' => array(
		'In|Sacred Mallet|1||1',
		'In|Mallet of Zul\'Farrak|1||1'
	),
	'Mauro' => array(
		'In|Celebrian Rod|1|SINGLE|1',
		'In|Celebrian Diamond|1|SINGLE|1',
		'In|Scepter of Celebras|1||1'
	),
	'BRDp' => array(
		'In|Prison Cell Key|1||1'
	),
	'BRDs' => array(
		'In|Ironfel|1||1',
		'In|Shadowforge Key|1||1'
	),
	'DM' => array(
		'In|Crescent Key|1||1'
	),
	'Scholo' => array(
		'Q|Scholomance|1||',
		'Q|Skeletal Fragments|1||',
		'Q|Mold Rhymes With...|1||',
		'Q|Fire Plume Forged|1||',
		'Q|Araj\'s Scarab|1||',
		'Q|The Key to Scholomance|1||',
		'In|Skeleton Key|1||1'
	),
	'Strath' => array(
		'In|Key to the City|1||1'
	),
	'UBRS' => array(
		'In|Unadorned Seal of Ascension|1|SINGLE|1',
		'In|Gemstone of Spirestone|1|SINGLE|1',
		'In|Gemstone of Smolderthorn|1|SINGLE|1',
		'In|Gemstone of Bloodaxe|1|SINGLE|1',
		'In|Unforged Seal of Ascension|1||1',
		'In|Forged Seal of Ascension|1||1',
		'In|Seal of Ascension|1||1'
	),
	'Onyxia' => array(
		'Q|Warlord\'s Command|1||',
		'Q|Eitrigg\'s Wisdom|1||',
		'Q|For The Horde!|1||',
		'Q|What the Wind Carries|1||',
		'Q|The Champion of the Horde|1||',
		'Q|The Testament of Rexxar|1||',
		'Q|Oculus Illusions|1||',
		'Q|Emberstrife|1||',
		'Q|The Test of Skulls, Scryer|1|SINGLE|',
		'Q|The Test of Skulls, Somnus|1|SINGLE|',
		'Q|The Test of Skulls, Chronalis|1|SINGLE|',
		'Q|The Test of Skulls, Axtroz|1||',
		'Q|Ascension...|1||',
		'Q|Blood of the Black Dragon Champion|1||',
		'In|Drakefire Amulet|1||1'
	),
	'MC' => array(
		'In|Eternal Quintessence|1||1'
	)
);

// Convert key format
foreach( $lang['inst_keys'] as $faction => $keys )
{
	foreach( $keys as $key => $stages )
	{
		foreach( $stages as $stage => $data )
		{
			if( is_array( $data ) )
			{
				continue;
			}

			list( $type, $value, $count, $marker, $active ) = explode('|', $data);

			if( $type == 'R' && !is_numeric($count) )
			{
				$count = $lang['rep2level'][$count];
			}

			$lang['inst_keys'][$faction][$key][$stage] = array(
				'type' => $type,
				'value' => $value,
				'count' => $count,
				'marker' => $marker,
				'active' => $active
			);
		}
	}
}
