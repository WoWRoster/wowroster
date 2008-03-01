<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * frFR Locale
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
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
		'Q|La Corne de la Bête|1||',
		'Q|Titre de propriété|1||',
		'Q|Enfin !|1||',
		'In|Clé de la gorge des Vents brûlants|1||1',
		'Key|inv_misc_key_14|225'
	),
	'Gnome' => array(
		'In|Clé d\\\'atelier|1||1',
		'Key|inv_misc_key_06|150'
	),
	'SM' => array(
		'In|La Clé écarlate|1||1',
		'Key|inv_misc_key_01|175'
	),
	'ZF' => array(
		'In|Maillet sacré|1||1',
		'In|Marteau de Zul\\\'Farrak|1||1',
		'Key|inv_hammer_19|'
	),
	'Mauro' => array(
		'In|Bâtonnet de Celebras|1|()|1',
		'In|Diamant de Celebras|1|()|1',
		'In|Sceptre de Celebras|1||1',
		'Key|inv_staff_16|'
	),
	'BRDp' => array(
		'In|Clé de la prison|1||1',
		'Key|inv_misc_key_10|250'
	),
	'BRDs' => array(
		'In|Souillefer|1||1',
		'In|Clé ombreforge|1||1',
		'Key|inv_misc_key_08|250'
	),
	'DM' => array(
		'In|Clé en croissant|1||1',
		'Key|inv_misc_key_10|295'
	),
	'Scholo' => array(
		'Q|Scholomance|1||',
		'Q|Fragments de squelette|1||',
		'Q|Moisissure rime avec...|1||',
		'Q|Plume de feu forgée|1||',
		'Q|Le Scarabée d\\\'Araj|1||',
		'Q|La clé de la Scholomance|1||',
		'In|Clé squelette|1||1',
		'Key|inv_misc_key_11|280'
	),
	'Strath' => array(
		'In|Clé de la ville|1||1',
		'Key|inv_misc_key_13|295'
	),
	'UBRS' => array(
		'In|Sceau d\\\'ascension non décoré|1|()|1',
		'In|Gemme de Pierre-du-pic|1|()|1',
		'In|Gemme de Brûleronce|1|()|1',
		'In|Gemme de Hache-sanglante|1|()|1',
		'In|Sceau d\\\'ascension brut |1||1',
		'In|Sceau d\\\'ascension forgé|1||1',
		'In|Sceau d\\\'ascension|1||1',
		'Key|inv_jewelry_ring_01|'
	),
	'Onyxia' => array(
		'Q|La menace dragonkin|1||1',
		'Q|Les véritables maîtres|1||1',
		'Q|Maréchal Windsor|1||1',
		'Q|Espoir abandonné|1||1',
		'Q|Une Note chiffonnée|1||1',
		'Q|Un espoir en lambeaux|1||1',
		'Q|Evasion !|1||1',
		'Q|Le rendez-vous à Stormwind|1||1',
		'Q|La grande mascarade|1||1',
		'Q|L\\\'Oeil de Dragon|1||1',
		'Q|Amulette drakefeu|1||1',
		'In|Amulette Drakefeu|1||1',
		'Key|inv_jewelry_talisman_11|'
	),
	'MC' => array(
		'In|Quintessence éternelle|1||1',
		'Key|inv_potion_83|'
	),
	'SL' => array(
		'In|Clé du labyrinthe des ombres|1||1',
		'Key|inv_misc_key_02|350'
	),
	'SH' => array(
		'In|Moule à clé préparé|1||1',
		'Q|L\\\'entrée dans la citadelle|1||1',
		'Q|Le grand maître Dumphry|1||1',
		'Q|La demande de Dumphry|1||1',
		'Q|Plus chaud que l\\\'enfer|1||1',
		'In|Clé des Salles Brisées|1||1',
		'Key|inv_misc_key_02|350'
	),
	'HCH' => array(
		'R|Bastion de l\\\'Honneur|9000||1',
		'In|Clé en flammes forgées|1||1',
		'Key|inv_misc_key_13|',
	),
	'AuchH' => array(
		'R|Ville basse|9000||1',
		'In|Clé Auchenaï|1||1',
		'Key|inv_misc_key_11|',
	),
	'CoilH' => array(
		'R|Expédition cénarienne|9000||1',
		'In|Clé du réservoir|1||1',
		'Key|inv_misc_key_13|',
	),
	'KoTH' => array(
		'R|Gardiens du temps|9000||1',
		'In|Clé du Temps|1||1',
		'Key|inv_misc_key_04|',
	),
	'TempestH' => array(
		'R|Les Sha\\\'tar|9000||1',
		'In|Clé dimensionnelle|1||1',
		'Key|inv_misc_key_09|',
	),
	'Karazhan' => array(
		'Q|Troubles arcaniques|1|()|1',
		'Q|L\\\'agitation des sans-repos|1|()|1',
		'Q|Un envoyé de Dalaran|1||1',
		'Q|Khadgar|1||1',
		'Q|L\\\'entrée de Karazhan|1||1',
		'In|Deuxième fragment de la clé|1|()|1',
		'In|Troisième fragment de la clé|1|()|1',
		'Q|Le toucher du maître|1||1',
		'Q|Retour vers Khadgar|1||1',
		'In|La clé du maître|1||1',
		'Key|inv_misc_key_07|'
	),
    'Arcatraz' => array(
        'Q|L\\\'écumeur-dimensionnel Nesaad|1||1',
        'Q|Demande d\\\'assistance|1||1',
        'Q|Récupérer ce qui nous revient de droit|1||1',
        'Q|Une audience avec le prince|1||1',
        'Q|Point de triangulation numéro un|1||1',
        'Q|Point de triangulation numéro deux|1||1',
        'Q|Le triangle est triangulé|1||1',
        'Q|Livraison spéciale à Shattrath|1||1',
        'Q|Comment pénétrer dans l\\\'Arcatraz|1||1',
        'In|Clé de l\\\'Arcatraz|1||1',
        'Key|inv_datacrystal03|350'
    ),
    'Temple' => array(
        'Q|Tablettes de Baa\\\'ri |1||1',
        'Q|Oronu l\\\'Ancien |1||1',
        'Q|Les corrupteurs cendrelangue|1||1',
        'Q|La cage du gardien|1||1',
        'Q|Preuve d\\\'allégeance|1||1',
        'Q|Akama|1||1',
        'Q|Le voyant Udalo|1||1',
        'Q|La terrasse Ata\\\'mal|1||1',
        'Q|promesse d\\\'Akama|1||1',
        'Q| Un secret compromis|1||1',
        'Q|Un artefact du passé|1||1',
        'Q|L\\\'Âme de l\\\'Otage |1||1',
        'Q|Entrée dans le Temple Noir|1||1',
        'Q|Une diversion pour Akama|1||1',
        'Q|À la recherche des Cendrelangue|1||1',
        'Q|La rédemption des Cendrelangue|1||1',
        'In|Le Médaillon de Karabor|1||1',
        'Key|inv_jewelry_amulet_04|'
    ),
	'MH' => array(
		'Q|The Vials of Eternity|1||1',
		'In|Vash\\\'s Vial Remnant|1|()|1',
		'In|Kael\\\'s Vial Remnant|1|()|1',
		'R|The Scale of the Sands|1||1',
		'Key|inv_potion_101|'
	)
);


// HORDE KEYS
$inst_keys['H'] = array(
	'SG' => array( 
		'Key|inv_misc_key_14|225'
	),
	'Gnome' => array(
		'In|Clé d\\\'atelier|1||1',
		'Key|inv_misc_key_06|150'
	),
	'SM' => array(
		'In|La Clé écarlate|1||1',
		'Key|inv_misc_key_01|175'
	),
	'ZF' => array(
		'In|Maillet sacré|1||1',
		'In|Marteau de Zul\\\'Farrak|1||1',
		'Key|inv_hammer_19|'
	),
	'Mauro' => array(
		'In|Bâtonnet de Celebras|1|()|1',
		'In|Diamant de Celebras|1|()|1',
		'In|Sceptre de Celebras|1||1',
		'Key|inv_staff_16|'
	),
	'BRDp' => array(
		'In|Clé de la prison|1||1',
		'Key|inv_misc_key_10|250'
	),
	'BRDs' => array(
		'In|Souillefer|1||1',
		'In|Clé ombreforge|1||1',
		'Key|inv_misc_key_08|250'
	),
	'DM' => array(
		'In|Clé en croissant|1||1',
		'Key|inv_misc_key_10|295'
	),
	'Scholo' => array(
		'Q|Scholomance|1||',
		'Q|Fragments de squelette|1||',
		'Q|Moisissure rime avec...|1||',
		'Q|Plume de feu forgée|1||',
		'Q|Le Scarabée d\\\'Araj|1||',
		'Q|La clé de la Scholomance|1||',
		'In|Clé squelette|1||1',
		'Key|inv_misc_key_11|280'
	),
	'Strath' => array(
		'In|Clé de la ville|1||1',
		'Key|inv_misc_key_13|295'
	),
	'UBRS' => array(
		'In|Sceau d\\\'ascension non décoré|1|()|1',
		'In|Gemme de Pierre-du-pic|1|()|1',
		'In|Gemme de Brûleronce|1|()|1',
		'In|Gemme de Hache-sanglante|1|()|1',
		'In|Sceau d\\\'ascension brut |1||1',
		'In|Sceau d\\\'ascension forgé|1||1',
		'In|Sceau d\\\'ascension|1||1',
		'Key|inv_jewelry_ring_01|'
	),
	'Onyxia' => array(
		'Q|Ordres du seigneur de guerre Goretooth|1||',
		'Q|Ordre du chef de guerre|1||',
		'Q|Pour la Horde !|1||',
		'Q|Ce que le vent apporte|1||',
		'Q|Le Champion de la Horde|1||',
		'Q|Le testament de Rexxar|1||',
		'Q|Illusions d\\\'Occulus|1||',
		'Q|Querelleur ardent|1||',
		'Q|L\\\'épreuve des crânes, Cristallomancier|1|()|',
		'Q|L\\\'épreuve des crânes, Somnus|1|()|',
		'Q|L\\\'épreuve des crânes, Chronalis|1|()|',
		'Q|L\\\'épreuve des crânes, Axtroz|1||',
		'Q|Ascension...|1||',
		'Q|Sang du Champion des Dragons noirs|1||',
		'In|Amulette Drakefeu|1||1',
		'Key|inv_jewelry_talisman_11|'
	),
	'MC' => array(
		'In|Quintessence éternelle|1||1',
		'Key|inv_potion_83|'
	),
	'SL' => array(
		'In|Clé du labyrinthe des ombres|1||1',
		'Key|inv_misc_key_02|350'
	),
	'SH' => array(
		'In|Moule à clé préparé|1||1',
		'Q|L\\\'entrée dans la citadelle|1||1',
		'Q|Le grand maître Rohok|1||1',
		'Q|La demande de Rohok|1||1',
		'Q|Plus chaud que l\\\'enfer|1||1',
		'In|Clé des Salles Brisées|1||1',
		'Key|inv_misc_key_02|350'
	),
	'HCH' => array(
		'R|Thrallmar|9000||1',
		'In|Clé en flammes forgées|1||1',
		'Key|inv_misc_key_13|',
	),
	'AuchH' => array(
		'R|Ville basse|9000||1',
		'In|Clé Auchenaï|1||1',
		'Key|inv_misc_key_11|',
	),
	'CoilH' => array(
		'R|Expédition cénarienne|9000||1',
		'In|Clé du réservoir|1||1',
		'Key|inv_misc_key_13|',
	),
	'KoTH' => array(
		'R|Gardiens du temps|9000||1',
		'In|Clé du Temps|1||1',
		'Key|inv_misc_key_04|',
	),
	'TempestH' => array(
		'R|Les Sha\\\'tar|9000||1',
		'In|Clé dimensionnelle|1||1',
		'Key|inv_misc_key_09|',
	),
	'Karazhan' => array(
		'Q|Troubles arcaniques|1|()|1',
		'Q|L\\\'agitation des sans-repos|1|()|1',
		'Q|Un envoyé de Dalaran|1||1',
		'Q|Khadgar|1||1',
		'Q|L\\\'entrée de Karazhan|1||1',
		'In|Deuxième fragment de la clé|1|()|1',
		'In|Troisième fragment de la clé|1|()|1',
		'Q|Le toucher du maître|1||1',
		'Q|Retour vers Khadgar|1||1',
		'In|La clé du maître|1||1',
		'Key|inv_misc_key_07|'
	),
    'Arcatraz' => array(
        'Q|L\\\'écumeur-dimensionnel Nesaad|1||1',
        'Q|Demande d\\\'assistance|1||1',
        'Q|Récupérer ce qui nous revient de droit|1||1',
        'Q|Une audience avec le prince|1||1',
        'Q|Point de triangulation numéro un|1||1',
        'Q|Point de triangulation numéro deux|1||1',
        'Q|Le triangle est triangulé|1||1',
        'Q|Livraison spéciale à Shattrath|1||1',
        'Q|Comment pénétrer dans l\\\'Arcatraz|1||1',
        'In|Clé de l\\\'Arcatraz|1||1',
        'Key|inv_datacrystal03|350'
    ),
    'Temple' => array(
        'Q|Tablettes de Baa\\\'ri |1||1',
        'Q|Oronu l\\\'Ancien |1||1',
        'Q|Les corrupteurs cendrelangue|1||1',
        'Q|La cage du gardien|1||1',
        'Q|Preuve d\\\'allégeance|1||1',
        'Q|Akama|1||1',
        'Q|Le voyant Udalo|1||1',
        'Q|La terrasse Ata\\\'mal|1||1',
        'Q|promesse d\\\'Akama|1||1',
        'Q| Un secret compromis|1||1',
        'Q|Un artefact du passé|1||1',
        'Q|L\\\'Âme de l\\\'Otage |1||1',
        'Q|Entrée dans le Temple Noir|1||1',
        'Q|Une diversion pour Akama|1||1',
        'Q|À la recherche des Cendrelangue|1||1',
        'Q|La rédemption des Cendrelangue|1||1',
        'In|Le Médaillon de Karabor|1||1',
        'Key|inv_jewelry_amulet_04|'
    ),
	'MH' => array(
		'Q|The Vials of Eternity|1||1',
		'In|Vash\\\'s Vial Remnant|1|()|1',
		'In|Kael\\\'s Vial Remnant|1|()|1',
		'R|The Scale of the Sands|1||1',
		'Key|inv_potion_101|'
	)
);
