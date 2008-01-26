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
 * @version    SVN: $Id: enUS.php 1582 2008-01-11 21:13:42Z pleegwat $
 * @link       http://www.wowroster.net
 * @package    InstanceKeys
 * @subpackage Locale

Instance Keys
=============
Note: These files have to be imported from the installer/upgrader. A config GUI will be coming later.


The generic format for a key definition is:

$inst_keys[$fact][$key_name] = array( $stage_definition, ..., 'Key|icon|lockpicking');

The stage definition can be defined in two ways:
- As an array with named keys. These keys are 'type', 'name', 'count', 'flow', and 'active'.
- As a shorthand. This consists of 'type|name|count|flow|active'.

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

'flow' is for rendering purposes. It has these values:
  'flow'='('
  'flow'=')'
  'flow'='()'

$active indicates if this stage should be counted as complete if it is active. This means it will count towards key progress in the progress bar. It will still be colored like an active stage in the tooltip.

The opening and closing brackets in the flow define start and end of a subchain. If both are in a marker, that stage is a single-stage subchain.

stages should be integers, ascending, starting at 0, without gaps. In other words, put them in the right order and have PHP assign the keys.

The key is the last stage of the chain. If this stage is active (its condition is currently met) the icon will be shown instead of the progress bar. Define the icon by putting it's name in a string as the last element of your key definition.

All single quotes should be slashed for DB insert

The 'Key' entry, recommended to be last (though place doesn't really matter) names the icon and the lockpicking skill. If a lock is unpickable, leave the field empty
*/

// ALLIANCE KEYS
$inst_keys['A'] = array(
	'SG' => array( 
		'Q|The Horn of the Beast|1||',
		'Q|Proof of deed|1||',
		'Q|At Last!|1||',
		'In|Key to Searing Gorge|1||1',
		'Key|inv_misc_key_14|225'
	),
	'Gnome' => array(
		'In|Workshop Key|1||1',
		'Key|inv_misc_key_06|150'
	),
	'SM' => array(
		'In|The Scarlet Key|1||1',
		'Key|inv_misc_key_01|175'
	),
	'ZF' => array(
		'In|Sacred Mallet|1||1',
		'In|Mallet of Zul\\\'Farrak|1||1',
		'Key|inv_hammer_19|'
	),
	'Mauro' => array(
		'In|Celebrian Rod|1|()|1',
		'In|Celebrian Diamond|1|()|1',
		'In|Scepter of Celebras|1||1',
		'Key|inv_staff_16|'
	),
	'BRDp' => array(
		'In|Prison Cell Key|1||1',
		'Key|inv_misc_key_10|250'
	),
	'BRDs' => array(
		'In|Ironfel|1||1',
		'In|Shadowforge Key|1||1',
		'Key|inv_misc_key_08|'
	),
	'DM' => array(
		'In|Crescent Key|1||1',
		'Key|inv_misc_key_10|295'
	),
	'Scholo' => array(
		'Q|Scholomance|1||',
		'Q|Skeletal Fragments|1||',
		'Q|Mold Rhymes With...|1||',
		'Q|Fire Plume Forged|1||',
		'Q|Araj\\\'s Scarab|1||',
		'Q|The Key to Scholomance|1||',
		'In|Skeleton Key|1||1',
		'Key|inv_misc_key_11|280'
	),
	'Strath' => array(
		'In|Key to the City|1||1',
		'Key|inv_misc_key_13|295'
	),
	'UBRS' => array(
		'In|Unadorned Seal of Ascension|1|()|1',
		'In|Gemstone of Spirestone|1|()|1',
		'In|Gemstone of Smolderthorn|1|()|1',
		'In|Gemstone of Bloodaxe|1|()|1',
		'In|Unforged Seal of Ascension|1||1',
		'In|Forged Seal of Ascension|1||1',
		'In|Seal of Ascension|1||1',
		'Key|inv_jewelry_ring_01|'
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
		'Q|The Dragon\\\'s Eye|1||1',
		'Q|Drakefire Amulet|1||1',
		'In|Drakefire Amulet|1||1',
		'Key|inv_jewelry_talisman_11|'
	),
	'MC' => array(
		'In|Eternal Quintessence|1||1',
		'Key|inv_potion_83|'
	),
	'SL' => array(
                'In|Shadow Labyrinth Key|1||1',
                'Key|inv_misc_key_02|'
        ),
	'AuchH' => array(
		'R|Lower City|6||1',
		'In|Auchenai Key|1||1',
		'Key|inv_misc_key_11|',
	),
	'Karazhan' => array(
		'Q|Arcane Disturbances|1|()|1',
		'Q|Restless Activity|1|()|1',
		'Q|Contact from Dalaran|1||1',
		'Q|Khadgar|1||1',
		'Q|Entry Into Karazhan|1||1',
		'In|Second Key Fragment|1|()|1',
		'In|Third Key Fragment|1|()|1',
		'Q|The Master\\\'s Touch|1||1',
		'Q|Return to Khadgar|1||1',
		'In|The Master\\\'s Key|1||1',
		'Key|inv_misc_key_07|'
	)
);


// HORDE KEYS
$inst_keys['H'] = array(
/* Horde can't get the searing key
	'SG' => array( 
		'In|Key to Searing Gorge|1||1',
		'Key|inv_misc_key_14|225'
	),*/
	'Gnome' => array(
		'In|Workshop Key|1||1',
		'Key|inv_misc_key_06|150'
	),
	'SM' => array(
		'In|The Scarlet Key|1||1',
		'Key|inv_misc_key_01|175'
	),
	'ZF' => array(
		'In|Sacred Mallet|1||1',
		'In|Mallet of Zul\\\'Farrak|1||1',
		'Key|inv_hammer_19|'
	),
	'Mauro' => array(
		'In|Celebrian Rod|1|()|1',
		'In|Celebrian Diamond|1|()|1',
		'In|Scepter of Celebras|1||1',
		'Key|inv_staff_16|'
	),
	'BRDp' => array(
		'In|Prison Cell Key|1||1',
		'Key|inv_misc_key_10|250'
	),
	'BRDs' => array(
		'In|Ironfel|1||1',
		'In|Shadowforge Key|1||1',
		'Key|inv_misc_key_08|'
	),
	'DM' => array(
		'In|Crescent Key|1||1',
		'Key|inv_misc_key_10|295'
	),
	'Scholo' => array(
		'Q|Scholomance|1||',
		'Q|Skeletal Fragments|1||',
		'Q|Mold Rhymes With...|1||',
		'Q|Fire Plume Forged|1||',
		'Q|Araj\\\'s Scarab|1||',
		'Q|The Key to Scholomance|1||',
		'In|Skeleton Key|1||1',
		'Key|inv_misc_key_11|280'
	),
	'Strath' => array(
		'In|Key to the City|1||1',
		'Key|inv_misc_key_13|295'
	),
	'UBRS' => array(
		'In|Unadorned Seal of Ascension|1|()|1',
		'In|Gemstone of Spirestone|1|()|1',
		'In|Gemstone of Smolderthorn|1|()|1',
		'In|Gemstone of Bloodaxe|1|()|1',
		'In|Unforged Seal of Ascension|1||1',
		'In|Forged Seal of Ascension|1||1',
		'In|Seal of Ascension|1||1',
		'Key|inv_jewelry_ring_01|'
	),
	'Onyxia' => array(
		'Q|Warlord\\\'s Command|1||',
		'Q|Eitrigg\\\'s Wisdom|1||',
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
		'In|Drakefire Amulet|1||1',
		'Key|inv_jewelry_talisman_11|'
	),
	'MC' => array(
		'In|Eternal Quintessence|1||1',
		'Key|inv_potion_83|'
	),
	'SL' => array(
                'In|Shadow Labyrinth Key|1||1',
                'Key|inv_misc_key_02|'
        ),
	'AuchH' => array(
		'R|Lower City|6||1',
		'In|Auchenai Key|1||1',
		'Key|inv_misc_key_11|',
	),
	'Karazhan' => array(
		'Q|Arcane Disturbances|1|()|1',
		'Q|Restless Activity|1|()|1',
		'Q|Contact from Dalaran|1||1',
		'Q|Khadgar|1||1',
		'Q|Entry Into Karazhan|1||1',
		'In|Second Key Fragment|1|()|1',
		'In|Third Key Fragment|1|()|1',
		'Q|The Master\\\'s Touch|1||1',
		'Q|Return to Khadgar|1||1',
		'In|The Master\\\'s Key|1||1',
		'Key|inv_misc_key_07|'
	)
);
