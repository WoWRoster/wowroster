<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * enUS Locale
 *
 * @copyright  2002-2008 WoWRoster.net
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
		'Q|El cuerno de la bestia|1||',
		'Q|Certificado de autenticidad|1||',
		'Q|¡Al fin!|1||',
		'In|Llave de la Garganta de Fuego|1||1',
		'Key|inv_misc_key_14|225'
	),
	'Gnome' => array(
		'In|Llave de taller|1||1',
		'Key|inv_misc_key_06|150'
	),
	'SM' => array(
		'In|La llave Escarlata|1||1',
		'Key|inv_misc_key_01|175'
	),
	'ZF' => array(
		'In|Marra sacra|1||1',
		'In|Marra de Zul\\\'Farrak|1||1',
		'Key|inv_hammer_19|'
	),
	'Mauro' => array(
		'In|Vara de Celebras|1|()|1',
		'In|Diamante de Celebras|1|()|1',
		'In|Cetro de Celebras|1||1',
		'Key|inv_staff_16|'
	),
	'BRDp' => array(
		'In|Llave de celda de prisión|1||1',
		'Key|inv_misc_key_10|250'
	),
	'BRDs' => array(
		'In|Ferrovil|1||1',
		'In|Llave Sombratiniebla|1||1',
		'Key|inv_misc_key_08|250'
	),
	'DM' => array(
		'In|Llave creciente|1||1',
		'Key|inv_misc_key_10|295'
	),
	'Scholo' => array(
		'Q|Scholomance|1||',
		'Q|Trozos esqueléticos|1||',
		'Q|Molde rima con... ¿oro?|1||',
		'Q|La forja del Penacho en Llamas|1||',
		'Q|El escarabajo de Araj|1||',
		'Q|La llave de Scholomance|1||',
		'In|Llave esqueleto|1||1',
		'Key|inv_misc_key_11|280'
	),
	'Strath' => array(
		'In|Llave de la ciudad|1||1',
		'Key|inv_misc_key_13|295'
	),
	'UBRS' => array(
		'In|Sello de ascensión sin adornar|1|()|1',
		'In|Gema de Cumbrerroca|1|()|1',
		'In|Gema de Espina Ahumada|1|()|1',
		'In|Gema de Hacha de Sangre|1|()|1',
		'In|Sello de Ascensión sin forjar|1||1',
		'In|Sello de Ascensión forjado|1||1',
		'In|Lacre de ascensión|1||1',
		'Key|inv_jewelry_ring_01|'
	),
	'Onyxia' => array(
		'Q|La amenaza de los dragonantes|1||1',
		'Q|Los verdaderos maestros|1||1',
		'Q|El alguacil Windsor|1||1',
		'Q|Esperanza perdida|1||1',
		'Q|Una nota arrugada|1||1',
		'Q|Una esperanza hecha trizas|1||1',
		'Q|La fuga de la prisión|1||1',
		'Q|Tienes un cita en Ventormenta|1||1',
		'Q|La gran farsa|1||1',
		'Q|El Ojo del dragón|1||1',
		'Q|Amuleto Pirodraco|1||1',
		'In|Amuleto de Pirodraco|1||1',
		'Key|inv_jewelry_talisman_11|'
	),
	'MC' => array(
		'In|Quintaesencia eterna|1||1',
		'Key|inv_potion_83|'
	),
	'SL' => array(
		'In|Llave del Laberinto de las Sombras|1||1',
		'Key|inv_misc_key_02|350'
	),
	'SH' => array(
		'In|Molde de llave preparado|1||1',
		'Q|Entrada a la Ciudadela|1||1',
		'Q|Gran maestro Rohok|1||1',
		'Q|La petición de Rohok|1||1',
		'Q|Más caliente que el infierno|1||1',
		'In|Llave de las Salas Arrasadas|1||1',
		'Key|inv_misc_key_02|350'
	),
	'HCH' => array(
		'R|Bastión del Honor|9000||1',
		'In|Llave de Forjallamas|1||1',
		'Key|inv_misc_key_13|',
	),
	'AuchH' => array(
		'R|Bajo Arrabal|9000||1',
		'In|Llave Auchenai|1||1',
		'Key|inv_misc_key_11|',
	),
	'CoilH' => array(
		'R|Expedición Cenarion|9000||1',
		'In|Llave de depósito|1||1',
		'Key|inv_misc_key_13|',
	),
	'KoTH' => array(
		'R|Vigilantes del Tiempo|9000||1',
		'In|Llave del tiempo|1||1',
		'Key|inv_misc_key_04|',
	),
	'TempestH' => array(
		'R|Los Sha\\\'tar|9000||1',
		'In|Llave forjada de distorsión|1||1',
		'Key|inv_misc_key_09|',
	),
	'Karazhan' => array(
		'Q|Alteraciones arcanas|1|()|1',
		'Q|Actividad incansable|1|()|1',
		'Q|Contacto de Dalaran|1||1',
		'Q|Khadgar|1||1',
		'Q|La entrada a Karazhan|1||1',
		'In|Segundo trozo de llave|1|()|1',
		'In|Tercer trozo de llave|1|()|1',
		'Q|El toque del maestro|1||1',
		'Q|Khadgar de nuevo|1||1',
		'In|La llave del maestro|1||1',
		'Key|inv_misc_key_07|'
	),
	'Arcatraz' => array(
		'Q|Warp-Raider Nesaad|1||1',
		'Q|Request for Assistance|1||1',
		'Q|Rightful Repossession|1||1',
		'Q|An Audience with the Prince|1||1',
		'Q|Triangulation Point One|1||1',
		'Q|Triangulation Point Two|1||1',
		'Q|Full Triangle|1||1',
		'Q|Special Delivery to Shattrath City|1||1',
		'Q|How to Break Into the Arcatraz|1||1',
		'In|Key to the Arcatraz|1||1',
		'Key|inv_datacrystal03|350'
	),
	'Temple' => array(
		'Q|Tablets of Baa\\\'ri|1||1',
		'Q|Oronu the Elder|1||1',
		'Q|The Ashtongue Corruptors|1||1',
		'Q|The Warden\\\'s Cage|1||1',
		'Q|Proof of Allegiance|1||1',
		'Q|Akama|1||1',
		'Q|Seer Udalo|1||1',
		'Q|A Mysterious Portent|1||1',
		'Q|The Ata\\\'mal Terrace|1||1',
		'Q|Akama\\\'s Promise|1||1',
		'Q|The Secret Compromised|1||1',
		'Q|Ruse of the Ashtongue|1||1',
		'Q|An Artifact From the Past|1||1',
		'Q|The Hostage Soul|1||1',
		'Q|Entry Into the Black Temple|1||1',
		'Q|A Distraction for Akama|1||1',
		'In|Medallion of Karabor|1||1',
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
		'In|Llave de taller|1||1',
		'Key|inv_misc_key_06|150'
	),
	'SM' => array(
		'In|La llave Escarlata|1||1',
		'Key|inv_misc_key_01|175'
	),
	'ZF' => array(
		'In|Marra sacra|1||1',
		'In|Marra de Zul\\\'Farrak|1||1',
		'Key|inv_hammer_19|'
	),
	'Mauro' => array(
		'In|Vara de Celebras|1|()|1',
		'In|Diamante de Celebras|1|()|1',
		'In|Cetro de Celebras|1||1',
		'Key|inv_staff_16|'
	),
	'BRDp' => array(
		'In|Llave de celda de prisión|1||1',
		'Key|inv_misc_key_10|250'
	),
	'BRDs' => array(
		'In|Ferrovil|1||1',
		'In|Llave Sombratiniebla|1||1',
		'Key|inv_misc_key_08|250'
	),
	'DM' => array(
		'In|Llave creciente|1||1',
		'Key|inv_misc_key_10|295'
	),
	'Scholo' => array(
		'Q|Scholomance|1||',
		'Q|Trozos esqueléticos|1||',
		'Q|Molde rima con... ¿oro?|1||',
		'Q|La forja del Penacho en Llamas|1||',
		'Q|El escarabajo de Araj|1||',
		'Q|La llave de Scholomance|1||',
		'In|Llave esqueleto|1||1',
		'Key|inv_misc_key_11|280'
	),
	'Strath' => array(
		'In|Llave de la ciudad|1||1',
		'Key|inv_misc_key_13|295'
	),
	'UBRS' => array(
		'In|Sello de ascensión sin adornar|1|()|1',
		'In|Gema de Cumbrerroca|1|()|1',
		'In|Gema de Espina Ahumada|1|()|1',
		'In|Gema de Hacha de Sangre|1|()|1',
		'In|Sello de Ascensión sin forjar|1||1',
		'In|Sello de Ascensión forjado|1||1',
		'In|Lacre de ascensión|1||1',
		'Key|inv_jewelry_ring_01|'
	),
	'Onyxia' => array(
		'Q|La orden del Señor de la Guerra|1||',
		'Q|La sabiduría de Eitrigg|1||',
		'Q|¡Por la Horda!|1||',
		'Q|Lo que trae el viento|1||',
		'Q|El Campeón de la Horda|1||',
		'Q|Profesora del engaño|1||',
		'Q|Ilusiones oculares|1||',
		'Q|Brasaliza|1||',
		'Q|La prueba de las calaveras, Arúspice|1|()|',
		'Q|La prueba de las calaveras, Somnus|1|()|',
		'Q|La prueba de las calaveras, Chrona|1|()|',
		'Q|La prueba de las calaveras, Axtroz|1||',
		'Q|El ascenso|1||',
		'Q|La sangre de campeón dragón negro|1||',
		'In|Amuleto de Pirodraco|1||1',
		'Key|inv_jewelry_talisman_11|'
	),
	'MC' => array(
		'In|Quintaesencia eterna|1||1',
		'Key|inv_potion_83|'
	),
	'SL' => array(
		'In|Llave del Laberinto de las Sombras|1||1',
		'Key|inv_misc_key_02|350'
	),
	'SH' => array(
		'In|Molde de llave preparado|1||1',
		'Q|Entrada a la Ciudadela|1||1',
		'Q|Gran maestro Rohok|1||1',
		'Q|La petición de Rohok|1||1',
		'Q|Más caliente que el infierno|1||1',
		'In|Llave de las Salas Arrasadas|1||1',
		'Key|inv_misc_key_02|350'
	),
	'HCH' => array(
		'R|Thrallmar|9000||1',
		'In|Llave de Forjallamas|1||1',
		'Key|inv_misc_key_13|',
	),
	'AuchH' => array(
		'R|Bajo Arrabal|9000||1',
		'In|Llave Auchenai|1||1',
		'Key|inv_misc_key_11|',
	),
	'CoilH' => array(
		'R|Expedición Cenarion|9000||1',
		'In|Llave de depósito|1||1',
		'Key|inv_misc_key_13|',
	),
	'KoTH' => array(
		'R|Vigilantes del Tiempo|9000||1',
		'In|Llave del tiempo|1||1',
		'Key|inv_misc_key_04|',
	),
	'TempestH' => array(
		'R|Los Sha\\\'tar|9000||1',
		'In|Llave forjada de distorsión|1||1',
		'Key|inv_misc_key_09|',
	),
	'Karazhan' => array(
		'Q|Alteraciones arcanas|1|()|1',
		'Q|Actividad incansable|1|()|1',
		'Q|Contacto de Dalaran|1||1',
		'Q|Khadgar|1||1',
		'Q|La entrada a Karazhan|1||1',
		'In|Segundo trozo de llave|1|()|1',
		'In|Tercer trozo de llave|1|()|1',
		'Q|El toque del maestro|1||1',
		'Q|Khadgar de nuevo|1||1',
		'In|La llave del maestro|1||1',
		'Key|inv_misc_key_07|'
	),
	'Arcatraz' => array(
		'Q|Warp-Raider Nesaad|1||1',
		'Q|Request for Assistance|1||1',
		'Q|Rightful Repossession|1||1',
		'Q|An Audience with the Prince|1||1',
		'Q|Triangulation Point One|1||1',
		'Q|Triangulation Point Two|1||1',
		'Q|Full Triangle|1||1',
		'Q|Special Delivery to Shattrath City|1||1',
		'Q|How to Break Into the Arcatraz|1||1',
		'In|Key to the Arcatraz|1||1',
		'Key|inv_datacrystal03|350'
	),
	'Temple' => array(
		'Q|Tablets of Baa\\\'ri|1||1',
		'Q|Oronu the Elder|1||1',
		'Q|The Ashtongue Corruptors|1||1',
		'Q|The Warden\\\'s Cage|1||1',
		'Q|Proof of Allegiance|1||1',
		'Q|Akama|1||1',
		'Q|Seer Udalo|1||1',
		'Q|A Mysterious Portent|1||1',
		'Q|The Ata\\\'mal Terrace|1||1',
		'Q|Akama\\\'s Promise|1||1',
		'Q|The Secret Compromised|1||1',
		'Q|Ruse of the Ashtongue|1||1',
		'Q|An Artifact From the Past|1||1',
		'Q|The Hostage Soul|1||1',
		'Q|Entry Into the Black Temple|1||1',
		'Q|A Distraction for Akama|1||1',
		'In|Medallion of Karabor|1||1',
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
