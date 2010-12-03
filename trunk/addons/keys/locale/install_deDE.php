<?php
/**
 * WoWRoster.net WoWRoster
 *
 * deDE Locale
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
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
		'Q|Das Horn der Bestie|1||',
		'Q|Besitznachweis|1||',
		'Q|Endlich!|1||',
		'In|Schlüssel zur Sengenden Schlucht|1||1',
		'Key|inv_misc_key_14|225'
	),
	'Gnome' => array(
		'In|Werkstattschlüssel|1||1',
		'Key|inv_misc_key_06|150'
	),
	'SM' => array(
		'In|Der scharlachrote Schlüssel|1||1',
		'Key|inv_misc_key_01|175'
	),
	'ZF' => array(
		'In|Hochheiliger Schlaghammer|1||1',
		'In|Schlaghammer von Zul\\\'Farrak|1||1',
		'Key|inv_hammer_19|'
	),
	'Mauro' => array(
		'In|Celebriangriff|1|()|1',
		'In|Celebriandiamant|1|()|1',
		'In|Szepter von Celebras|1||1',
		'Key|inv_staff_16|'
	),
	'BRDp' => array(
		'In|Gefängniszellenschlüssel|1||1',
		'Key|inv_misc_key_10|250'
	),
	'BRDs' => array(
		'In|Eisenhölle|1||1',
		'In|Schlüssel zur Schattenschmiede|1||1',
		'Key|inv_misc_key_08|250'
	),
	'DM' => array(
		'In|Mondsichelschlüssel|1||1',
		'Key|inv_misc_key_10|295'
	),
	'Scholo' => array(
		'Q|Scholomance|1||',
		'Q|Skelettfragmente|1||',
		'Q|Sold reimt sich auf...|1||',
		'Q|Feuerfeder geschmiedet|1||',
		'Q|Arajs Skarabäus|1||',
		'Q|Der Schlüssel zur Scholomance|1||',
		'In|Skelettschlüssel|1||1',
		'Key|inv_misc_key_11|280'
	),
	'Strath' => array(
		'In|Schlüssel zur Stadt|1||1',
		'Key|inv_misc_key_13|295'
	),
	'UBRS' => array(
		'In|Unverziertes Siegel des Aufstiegs|1|()|1',
		'In|Edelstein der Felsspitzoger|1|()|1',
		'In|Edelstein der Gluthauer|1|()|1',
		'In|Edelstein der Blutäxte|1|()|1',
		'In|Ungeschmiedetes Siegel des Aufstiegs|1||1',
		'In|Geschmiedetes Siegel des Aufstiegs|1||1',
		'In|Siegel des Aufstiegs|1||1',
		'Key|inv_jewelry_ring_01|'
	),
	'Onyxia' => array(
		'Q|Drachkin-Bedrohung|1||1',
		'Q|Die wahren Meister|1||1',
		'Q|Marshal Windsor|1||1',
		'Q|Verlorene Hoffnung|1||1',
		'Q|Eine zusammengeknüllte Notiz|1||1',
		'Q|Ein Funken Hoffnung|1||1',
		'Q|Gefängnisausbruch!|1||1',
		'Q|Treffen in Stormwind|1||1',
		'Q|Die große Maskerade|1||1',
		'Q|Das Großdrachenauge|1||1',
		'Q|Drachenfeueramulett|1||1',
		'In|Drachenfeueramulett|1||1',
		'Key|inv_jewelry_talisman_11|'
	),
	'MC' => array(
		'In|Ewige Quintessenz|1||1',
		'Key|inv_potion_83|'
	),
	'SL' => array(
		'In|Schlüssel des Schattenlabyrinths|1||1',
		'Key|inv_misc_key_02|350'
	),
	'SH' => array(
		'In|Präparierte Schlüsselform|1||1',
		'Q|Zugang zur Zitadelle|1||1',
		'Q|Großmeister Dumphry|1||1',
		'Q|Dumphrys Bitte|1||1',
		'Q|Heißer als die Hölle|1||1',
		'In|Schlüssel der zerschmetterten Hallen|1||1',
		'Key|inv_misc_key_02|350'
	),
	'HCH' => array(
		'R|Ehrenfeste|9000||1',
		'In|Flammengeschmiedeter Schlüssel|1||1',
		'Key|inv_misc_key_13|',
	),
	'AuchH' => array(
		'R|Unteres Viertel|9000||1',
		'In|Schlüssel der Auchenai|1||1',
		'Key|inv_misc_key_11|',
	),
	'CoilH' => array(
		'R|Expedition des Cenarius|9000||1',
		'In|Schlüssel des Kessels|1||1',
		'Key|inv_misc_key_13|',
	),
	'KoTH' => array(
		'R|Hüter der Zeit|9000||1',
		'In|Schlüssel der Zeit|1||1',
		'Key|inv_misc_key_04|',
	),
	'TempestH' => array(
		'R|Die Sha\\\'tar|9000||1',
		'In|Warpgeschmiedeter Schlüssel|1||1',
		'Key|inv_misc_key_09|',
	),
	'Karazhan' => array(
		'Q|Arkane Störungen|1|()|1',
		'Q|Rastlose Aktivität|1|()|1',
		'Q|Dalarankontaktpersonen|1||1',
		'Q|Khadgar|1||1',
		'Q|Nach Karazhan|1||1',
		'In|Zweites Schlüsselfragment|1|()|1',
		'In|Drittes Schlüsselfragment|1|()|1',
		'Q|Die Berührung des Meisters|1||1',
		'Q|Rückkehr zu Khadgar|1||1',
		'In|Der Schlüssel des Meisters|1||1',
		'Key|inv_misc_key_07|'
	),
	'Arcatraz' => array(
		'Q|Sphärenräuber Nesaad|1||1',
		'Q|Bitte um Unterstützung|1||1',
		'Q|Rechtmäßiger Besitz|1||1',
		'Q|Eine Audienz beim Prinzen|1||1',
		'Q|Triangulationspunkt Eins|1||1',
		'Q|Triangulationspunkt Zwei|1||1',
		'Q|Das volle Dreieck|1||1',
		'Q|Sonderlieferung nach Shattrath|1||1',
		'Q|Wie man in Arkatraz einbricht|1||1',
		'In|Schlüssel zur Arkatraz|1||1',
		'Key|inv_datacrystal03|350'
	),
	'Temple' => array(
		'Q|Schrifttafeln von Baa\\\'ri|1||1',
		'Q|Oronu der Älteste|1||1',
		'Q|Die Verderber der Aschenzungen|1||1',
		'Q|Der Kerker des Wächters|1||1',
		'Q|Ein Beweis der Treue|1||1',
		'Q|Akama|1||1',
		'Q|Seher Udalo|1||1',
		'Q|Ein mysteriöses Omen|1||1',
		'Q|Die Terrasse von Ata\\\'mal|1||1',
		'Q|Akamas Versprechen|1||1',
		'Q|Das gefährdete Geheimnis|1||1',
		'Q|Die List der Aschenzungen|1||1',
		'Q|Ein Artefakt aus der Vergangenheit|1||1',
		'Q|Die Seelengeisel|1||1',
		'Q|Zutritt zum Schwarzen Tempel|1||1',
		'Q|Ein Ablenkungsmanöver für Akama|1||1',
		'In|Medaillon von Karabor|1||1',
		'Key|inv_jewelry_amulet_04|'
	),
	'MH' => array(
		'Q|Die Phiolen der Ewigkeit|1||1',
		'In|Überreste von Vashjs Phiole|1|()|1',
		'In|Überreste von Kaels Phiole|1|()|1',
		'R|Die Wächter der Sande|1||1',
		'Key|inv_potion_101|'
	)
);


// HORDE KEYS
$inst_keys['H'] = array(
	'SG' => array(
		'Key|inv_misc_key_14|225'
	),
	'Gnome' => array(
		'In|Werkstattschlüssel|1||1',
		'Key|inv_misc_key_06|150'
	),
	'SM' => array(
		'In|Der scharlachrote Schlüssel|1||1',
		'Key|inv_misc_key_01|175'
	),
	'ZF' => array(
		'In|Hochheiliger Schlaghammer|1||1',
		'In|Schlaghammer von Zul\\\'Farrak|1||1',
		'Key|inv_hammer_19|'
	),
	'Mauro' => array(
		'In|Celebriangriff|1|()|1',
		'In|Celebriandiamant|1|()|1',
		'In|Szepter von Celebras|1||1',
		'Key|inv_staff_16|'
	),
	'BRDp' => array(
		'In|Gefängniszellenschlüssel|1||1',
		'Key|inv_misc_key_10|250'
	),
	'BRDs' => array(
		'In|Eisenhölle|1||1',
		'In|Schlüssel zur Schattenschmiede|1||1',
		'Key|inv_misc_key_08|250'
	),
	'DM' => array(
		'In|Mondsichelschlüssel|1||1',
		'Key|inv_misc_key_10|295'
	),
	'Scholo' => array(
		'Q|Scholomance|1||',
		'Q|Skelettfragmente|1||',
		'Q|Sold reimt sich auf...|1||',
		'Q|Feuerfeder geschmiedet|1||',
		'Q|Arajs Skarabäus|1||',
		'Q|Der Schlüssel zur Scholomance|1||',
		'In|Skelettschlüssel|1||1',
		'Key|inv_misc_key_11|280'
	),
	'Strath' => array(
		'In|Schlüssel zur Stadt|1||1',
		'Key|inv_misc_key_13|295'
	),
	'UBRS' => array(
		'In|Unverziertes Siegel des Aufstiegs|1|()|1',
		'In|Edelstein der Felsspitzoger|1|()|1',
		'In|Edelstein der Gluthauer|1|()|1',
		'In|Edelstein der Blutäxte|1|()|1',
		'In|Ungeschmiedetes Siegel des Aufstiegs|1||1',
		'In|Geschmiedetes Siegel des Aufstiegs|1||1',
		'In|Siegel des Aufstiegs|1||1',
		'Key|inv_jewelry_ring_01|'
	),
	'Onyxia' => array(
		'Q|Befehl des Kriegsherrn|1||',
		'Q|Eitriggs Weisheit|1||',
		'Q|Für die Horde!|1||',
		'Q|Was der Wind erzählt|1||',
		'Q|Der Champion der Horde|1||',
		'Q|Nachricht von Rexxar|1||',
		'Q|Oculus-Illusionen|1||',
		'Q|Emberstrife|1||',
		'Q|Die Prüfung der Schädel, Scryer|1|()|',
		'Q|Die Prüfung der Schädel, Somnus|1|()|',
		'Q|Die Prüfung der Schädel, Chronalis|1|()|',
		'Q|Die Prüfung der Schädel, Axtroz|1||',
		'Q|Aufstieg...|1||',
		'Q|Blut des schwarzen Großdrachen-Helden|1||',
		'In|Drachenfeueramulett|1||1',
		'Key|inv_jewelry_talisman_11|'
	),
	'MC' => array(
		'In|Ewige Quintessenz|1||1',
		'Key|inv_potion_83|'
	),
	'SL' => array(
		'In|Schlüssel des Schattenlabyrinths|1||1',
		'Key|inv_misc_key_02|350'
	),
	'SH' => array(
		'In|Präparierte Schlüsselform|1||1',
		'Q|Zugang zur Zitadelle|1||1',
		'Q|Großmeister Rohok|1||1',
		'Q|Rohoks Bitte|1||1',
		'Q|Heißer als die Hölle|1||1',
		'In|Schlüssel der zerschmetterten Hallen|1||1',
		'Key|inv_misc_key_02|350'
	),
	'HCH' => array(
		'R|Thrallmar|9000||1',
		'In|Flammengeschmiedeter Schlüssel|1||1',
		'Key|inv_misc_key_13|',
	),
	'AuchH' => array(
		'R|Unteres Viertel|9000||1',
		'In|Schlüssel der Auchenai|1||1',
		'Key|inv_misc_key_11|',
	),
	'CoilH' => array(
		'R|Expedition des Cenarius|9000||1',
		'In|Schlüssel des Kessels|1||1',
		'Key|inv_misc_key_13|',
	),
	'KoTH' => array(
		'R|Hüter der Zeit|9000||1',
		'In|Schlüssel der Zeit|1||1',
		'Key|inv_misc_key_04|',
	),
	'TempestH' => array(
		'R|Die Sha\\\'tar|9000||1',
		'In|Warpgeschmiedeter Schlüssel|1||1',
		'Key|inv_misc_key_09|',
	),
	'Karazhan' => array(
		'Q|Arkane Störungen|1|()|1',
		'Q|Rastlose Aktivität|1|()|1',
		'Q|Dalarankontaktpersonen|1||1',
		'Q|Khadgar|1||1',
		'Q|Nach Karazhan|1||1',
		'In|Zweites Schlüsselfragment|1|()|1',
		'In|Drittes Schlüsselfragment|1|()|1',
		'Q|Die Berührung des Meisters|1||1',
		'Q|Rückkehr zu Khadgar|1||1',
		'In|Der Schlüssel des Meisters|1||1',
		'Key|inv_misc_key_07|'
	),
	'Arcatraz' => array(
		'Q|Sphärenräuber Nesaad|1||1',
		'Q|Bitte um Unterstützung|1||1',
		'Q|Rechtmäßiger Besitz|1||1',
		'Q|Eine Audienz beim Prinzen|1||1',
		'Q|Triangulationspunkt Eins|1||1',
		'Q|Triangulationspunkt Zwei|1||1',
		'Q|Das volle Dreieck|1||1',
		'Q|Sonderlieferung nach Shattrath|1||1',
		'Q|Wie man in Arkatraz einbricht|1||1',
		'In|Schlüssel zur Arkatraz|1||1',
		'Key|inv_datacrystal03|350'
	),
	'Temple' => array(
		'Q|Schrifttafeln von Baa\\\'ri|1||1',
		'Q|Oronu der Älteste|1||1',
		'Q|Die Verderber der Aschenzungen|1||1',
		'Q|Der Kerker des Wächters|1||1',
		'Q|Ein Beweis der Treue|1||1',
		'Q|Akama|1||1',
		'Q|Seher Udalo|1||1',
		'Q|Ein mysteriöses Omen|1||1',
		'Q|Die Terrasse von Ata\\\'mal|1||1',
		'Q|Akamas Versprechen|1||1',
		'Q|Das gefährdete Geheimnis|1||1',
		'Q|Die List der Aschenzungen|1||1',
		'Q|Ein Artefakt aus der Vergangenheit|1||1',
		'Q|Die Seelengeisel|1||1',
		'Q|Zutritt zum Schwarzen Tempel|1||1',
		'Q|Ein Ablenkungsmanöver für Akama|1||1',
		'In|Medaillon von Karabor|1||1',
		'Key|inv_jewelry_amulet_04|'
	),
	'MH' => array(
		'Q|Die Phiolen der Ewigkeit|1||1',
		'In|Überreste von Vashjs Phiole|1|()|1',
		'In|Überreste von Kaels Phiole|1|()|1',
		'R|Die Wächter der Sande|1||1',
		'Key|inv_potion_101|'
	)
);
