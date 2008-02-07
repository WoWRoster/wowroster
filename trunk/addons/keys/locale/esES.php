<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * esES Locale Translation by maqjav
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    InstanceKeys
 * @subpackage Locale
*/

$lang['keys'] = 'Llaves';
$lang['keys_desc'] = 'Muestra un listado con las llaves de las mazmorras de Azeroth que tienen los jugadores';
$lang['keybutton'] = 'Llaves|Muestra un listado con las llaves de las mazmorras de Azeroth que tienen los jugadores';

$lang['admin']['keys_conf'] = 'Opciones|Muestra las opciones para el addon llaves';
$lang['admin']['colorcmp'] = 'Color completo|Para aquellas llaves que requieren de una serie de misiones o partes para conseguirla, indica el color para especificar estas partes';
$lang['admin']['colorcur'] = 'Color actual|Indica el color de la parte actual para conseguir la llave';
$lang['admin']['colorno'] = 'Color incompleto|Indica el color de las partes incompletas para conseguir la llave';
$lang['admin']['keys_access'] = 'Acceso|Elige quién tiene acceso para acceder al listado de llaves';

// language definitions for the rogue instance keys 'fix'
$lang['thievestools']='Herramientas de ladrón';
$lang['lockpicking']='Ganzúa';

$lang['Quests'] = 'Misiones';
$lang['Parts'] = 'Partes';
$lang['key_status'] = 'Estado %2$s de %1$s';

$lang['rep2level'] = array(
	'Odiado' => -42000,
	'Hostil' => -6000,
	'Enemigo' => -3000,
	'Neutral' => 0,
	'Amistoso' => 3000,
	'Honorable' => 9000,
	'Reverenciado' => 21000,
	'Exaltado' => 42000
);

/*
Instance Keys
=============
A part that is marked with 'MS' (milestone) will be designated as an overall status. So if
you have this one part it will mark all other parts lower than this one as complete.
*/

// ALLIANCE KEYS
$lang['inst_keys']['A'] = array(
	'SG' => array( 'Quests',
		'SG' => 'Llave de la Garganta de Fuego|5396',
			'El cuerno de la bestia|',
			'Certificado de autenticidad|',
			'Al fin!|'
		),
	'Gnome' => array( 'Key-only',
		'Gnome' => 'Llave de taller|6893'
		),
	'SM' => array( 'Key-only',
		'SM' => 'La llave Escarlata|7146'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Marra de Zul\\\'Farrak|9240',
			'Marra sacra|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Cetro de Celebras|17191',
			'Vara de Celebras|19549',
			'Diamante de Celebras|19545'
		),
	'BRDp' => array( 'Key-only',
		'BRDp' => 'Llave de celda de prisión|11140'
		),
	'BRDs' => array( 'Partes',
		'BRDs' => 'Llave Sombratiniebla|11000',
			'Ferrovil|9673'
		),
	'DM' => array( 'Key-only',
		'DM' => 'Llave creciente|18249'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Llave esqueleto|13704',
			'Scholomance|',
			'Trozos esqueléticos|',
			'Molde rima con... ¿oro?|',
			'La forja del Penacho en Llamas|',
			'El escarabajo de Araj|',
			'La llave de Scholomance|'
		),
	'Strath' => array( 'Key-only',
		'Strath' => 'Llave de la ciudad|12382'
		),
	'UBRS' => array( 'Partes',
		'UBRS' => 'Lacre de ascensión|12344',
			'Sello de ascensión sin adornar|5370',
			'Gema de Cumbrerroca|5379',
			'Gema de Espina Ahumada|16095',
			'Gema de Hacha de Sangre|21777',
			'Sello de Ascensión sin forjar|24554||MS',
			'Sello de Ascensión forjado|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Amuleto de Pirodraco|16309',
			'La amenaza de los dragonantes|',
			'Los verdaderos maestros|',
			'El alguacil Windsor|',
			'Esperanza perdida|',
			'Una nota arrugada|',
			'Una esperanza hecha trizas|',
			'La fuga de la prisión|',
			'Tienes un cita en Ventormenta|',
			'La gran farsa|',
			'El Ojo del dragón|',
			'Amuleto Pirodraco|'
		),
	'MC' => array( 'Key-only',
		'MC' => 'Quintaesencia eterna|22754'
		),
);


// HORDE KEYS
$lang['inst_keys']['H'] = array(
	'SG' => array( 'Solo llave',
		'SG' => 'Llave de la Garganta de Fuego|5396'
		),
	'Gnome' => array( 'Key-only',
		'Gnome' => 'Llave de taller|6893'
		),
	'SM' => array( 'Key-only',
		'SM' => 'La llave Escarlata|7146'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Marra de Zul\\\'Farrak|9240',
			'Marra sacra|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Cetro de Celebras|17191',
			'Vara de Celebras|19549',
			'Diamante de Celebras|19545'
		),
	'BRDp' => array( 'Key-only',
		'BRDp' => 'Llave de celda de prisión|11140'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Llave Sombratiniebla|11000',
			'Ferrovil|9673'
		),
	'DM' => array( 'Key-only',
		'DM' => 'Llave creciente|18249'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Llave esqueleto|13704',
			'Scholomance|',
			'Trozos esqueléticos|',
			'Molde rima con... ¿oro?|',
			'La forja del Penacho en Llamas|',
			'El escarabajo de Araj|',
			'La llave de Scholomance|'
		),
	'Strath' => array( 'Key-only',
		'Strath' => 'Llave de la ciudad|12382'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Lacre de ascensión|12344',
			'Sello de ascensión sin adornar|5370',
			'Gema de Cumbrerroca|5379',
			'Gema de Espina Ahumada|16095',
			'Gema de Hacha de Sangre|21777',
			'Sello de Ascensión sin forjar|24554||MS',
			'Sello de Ascensión forjado|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Amuleto de Pirodraco|16309',
			'La orden del Señor de la Guerra|',
			'La sabiduría de Eitrigg|',
			'¡Por la Horda!|',
			'Lo que trae el viento|',
			'El Campeón de la Horda|',
			'Profesora del engaño|',
			'Ilusiones oculares|',
			'Brasaliza|',
			'La prueba de las calaveras, Arúspice|',
			'La prueba de las calaveras, Somnus|',
			'La prueba de las calaveras, Chronalis|',
			'La prueba de las calaveras, Axtroz|',
			'El ascenso|',
			'La sangre de campeón dragón negro|'
		),
	'MC' => array( 'Key-only',
		'MC' => 'Quintaesencia eterna|22754'
		),
);
