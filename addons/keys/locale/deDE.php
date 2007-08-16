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

$lang['keys'] = 'Schl&uuml;ssel';
$lang['keybutton'] = 'Schl&uuml;ssel|Lists Azeroth Dungeon keys for guild members';

$lang['admin']['keys_conf'] = 'Main Settings|Main Settings for Instance Keys';
$lang['admin']['colorcmp'] = 'Completed Color|Color of complete steps in the Quests/Parts tooltip';
$lang['admin']['colorcur'] = 'Current Color|Color of current step in the Quests/Parts tooltip';
$lang['admin']['colorno'] = 'Incomplete Color|Color of incomplete steps in the Quests/Parts tooltip';

// language definitions for the rogue instance keys 'fix'
$lang['thievestools']='Diebeswerkzeug';
$lang['lockpicking']='Schlossknacken';


/*
Instance Keys
=============
A part that is marked with 'MS' (milestone) will be designated as an overall status. So if
you have this one part it will mark all other parts lower than this one as complete.
*/

// ALLIANCE KEYS
$lang['inst_keys']['A'] = array(
	'SG' => array( 'Quests',
		'SG' => 'Schlüssel zur Sengenden Schlucht|4826',
			'Das Horn der Bestie|',
			'Besitznachweis|',
			'Endlich!|'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Werkstattschlüssel|2288'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'Der scharlachrote Schlüssel|4445'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Schlaghammer von Zul\\\'Farrak|5695',
			'Hochheiliger Schlaghammer|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Szepter von Celebras|19710',
			'Celebriangriff|19549',
			'Celebriandiamant|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Gefängniszellenschlüssel|15545'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Schlüssel zur Schattenschmiede|2966',
			'Ironfel|9673'
		),
	'DM' => array( 'Key-Only',
		'DM' => 'Mondsichelschlüssel|35607'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Skelettschlüssel|16854',
			'Scholomance|',
			'Skelettfragmente|',
			'Sold reimt sich auf...|',
			'Feuerfeder geschmiedet|',
			'Arajs Skarabäus',
			'Der Schlüssel zur Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Schlüssel zur Stadt|13146'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Siegel des Aufstiegs|17057',
			'Unverziertes Siegel des Aufstiegs|5370',
			'Edelstein der Felsspitzoger|5379',
			'Edelstein der Gluthauer|16095',
			'Edelstein der Blutäxte|21777',
			'Ungeschmiedetes Siegel des Aufstiegs|24554||MS',
			'Geschmiedetes Siegel des Aufstiegs|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Drachenfeueramulett|4829',
			'Drachkin-Bedrohung|',
			'Die wahren Meister|',
			'Marshal Windsor|',
			'Verlorene Hoffnung|',
			'Eine zusammengeknüllte Notiz|',
			'Ein Funken Hoffnung|',
			'Gefängnisausbruch!|',
			'Treffen in Stormwind|',
			'Die große Maskerade|',
			'Das Großdrachenauge|',
			'Drachenfeueramulett|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Ewige Quintessenz|22754'
		),
);


// HORDE KEYS
$lang['inst_keys']['H'] = array(
	'SG' => array( 'Key-Only',
		'SG' => 'Schlüssel zur Sengenden Schlucht|4826'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Werkstattschlüssel|2288'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'Der scharlachrote Schlüssel|4445'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Schlaghammer von Zul\\\'Farrak|5695',
			'Hochheiliger Schlaghammer|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Szepter von Celebras|19710',
			'Celebriangriff|19549',
			'Celebriandiamant|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Gefängniszellenschlüssel|15545'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Schlüssel zur Schattenschmiede|2966',
			'Ironfel|9673'
		),
	'DM' => array( 'Key-Only',
		'DM' => 'Mondsichelschlüssel|35607'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Skelettschlüssel|16854',
			'Scholomance|',
			'Skelettfragmente|',
			'Sold reimt sich auf...|',
			'Feuerfeder geschmiedet|',
			'Arajs Skarabäus',
			'Der Schlüssel zur Scholomance|'
		),
	'Strath' => array( 'Key-Only', 'Strath' =>
			'Schlüssel zur Stadt|13146'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Siegel des Aufstiegs|17057',
			'Unverziertes Siegel des Aufstiegs|5370',
			'Edelstein der Felsspitzoger|5379',
			'Edelstein der Gluthauer|16095',
			'Edelstein der Blutäxte|21777',
			'Ungeschmiedetes Siegel des Aufstiegs|24554||MS',
			'Geschmiedetes Siegel des Aufstiegs|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Drachenfeueramulett|4829',
			'Befehl des Kriegsherrn|',
			'Eitriggs Weisheit|',
			'Für die Horde!|',
			'Was der Wind erzählt|',
			'Der Champion der Horde|',
			'Nachricht von Rexxar|',
			'Oculus-Illusionen|',
			'Emberstrife|',
			'Die Prüfung der Schädel, Scryer|',
			'Die Prüfung der Schädel, Somnus|',
			'Die Prüfung der Schädel, Chronalis|',
			'Die Prüfung der Schädel, Axtroz|',
			'Aufstieg...|',
			'Blut des schwarzen Großdrachen-Helden|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Ewige Quintessenz|22754'
		),
);
