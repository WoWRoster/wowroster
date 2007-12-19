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
*/

$lang['keys'] = 'Clefs';
$lang['keys_desc'] = 'Liste les clefs des donjons d\'Azeroth que possèdent les membres de la guilde';
$lang['keybutton'] = 'Clefs|Liste les clefs des donjons d\'Azeroth que possèdent les membres de la guilde';

$lang['admin']['keys_conf'] = 'Paramètres principaux|Paramètres principaux pour les clefs d\'instance';
$lang['admin']['colorcmp'] = 'Couleur étape achevée|Couleur pour les étapes achevées apparaissant dans l\'infobulle de suivi de quête/partie';
$lang['admin']['colorcur'] = 'Couleur étape en cours|Couleur pour l\'étape en cours apparaissant dans l\'infobulle de suivi de quête/partie';
$lang['admin']['colorno'] = 'Couleur étape à venir|Couleur pour les étapes à venir apparaissant dans l\'infobulle de suivi des étapes pour l\'acquisition de la clef (quêtes ou parties)';

// language definitions for the rogue instance keys 'fix'
$lang['thievestools']='Outils de Voleur';
$lang['lockpicking']='Crochetage';

$lang['Quests'] = 'Quêtes';
$lang['Parts'] = 'Parties';
$lang['key_status'] = 'Progression pour la %2$s %1$s';


/*
Instance Keys
=============
A part that is marked with 'MS' (milestone) will be designated as an overall status. So if
you have this one part it will mark all other parts lower than this one as complete.
*/

// ALLIANCE KEYS
$lang['inst_keys']['A'] = array(
	'SG' => array( 'Quests',
		'SG' => 'Clé de la gorge des Vents brûlants|5396',
			'La Corne de la B�te|',
			'Titre de propriété|',
			'Enfin !|'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Clé d\\\'atelier|6893'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'La Clé écarlate|7146'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Marteau de Zul\\\'Farrak|9240',
			'Maillet sacré|8250'
		),
	'Marau' => array( 'Parts',
		'Marau' => 'Sceptre de Celebras|17191',
			'Bâtonnet de Celebras|19549',
			'Diamant de Celebras|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Clé de la prison|11140'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Clé ombreforge|11000',
			'Souillefer|9673'
		),
	'HT' => array( 'Key-Only',
		'HT' => 'Clé en croissant|18249'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Clé squelette|13704',
			'Scholomance|',
			'Fragments de squelette|',
			'Moisissure rime avec...|',
			'Plume de feu forgée|',
			'Le Scarabée d\\\'Araj|',
			'La clé de la Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Clé de la ville|12382'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Sceau d\\\'ascension|12344',
			'Sceau d\\\'ascension non décoré|5370',
			'Gemme de Pierre-du-pic|5379',
			'Gemme de Brûleronce|16095',
			'Gemme de Hache-sanglante|21777',
			'Sceau d\\\'ascension brut |24554||MS',
			'Sceau d\\\'ascension forgé|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Amulette Drakefeu|16309',
			'La menace dragonkin|',
			'Les véritables maîtres|',
			'Maréchal Windsor|',
			'Espoir abandonné|',
			'Une Note chiffonnée|',
			'Un espoir en lambeaux|',
			'Evasion !|',
			'Le rendez-vous à Stormwind|',
			'La grande mascarade|',
			'L\\\'Oeil de Dragon|',
			'Amulette drakefeu|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Quintessence éternelle|22754'
		),
);


// HORDE KEYS
$inst_keys['H'] = array(
	'SG' => array( 'Key-Only',
		'SG' => 'Clé de la gorge des Vents brûlants|5396'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Clé d\\\'atelier|6893'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'La Clé écarlate|7146'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Marteau de Zul\\\'Farrak|9240',
			'Maillet sacré|8250'
		),
	'Marau' => array( 'Parts',
		'Marau' => 'Sceptre de Celebras|17191',
			'Bâtonnet de Celebras|19549',
			'Diamant de Celebras|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Clé de la prison|11140'
		),
	'BRDs' => array( 'Parts', 'BRDs' =>
			'Clé ombreforge|11000',
			'Souillefer|9673'
		),
	'HT' => array( 'Key-Only',
		'HT' => 'Clé en croissant|18249'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Clé squelette|13704',
			'Scholomance|',
			'Fragments de squelette|',
			'Moisissure rime avec...|',
			'Plume de feu forgée|',
			'Le Scarabée d\\\'Araj|',
			'La clé de la Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Clé de la ville|12382'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Sceau d\\\'ascension|12344',
			'Sceau d\\\'ascension non décoré|5370',
			'Gemme de Pierre-du-pic|5379',
			'Gemme de Brûleronce|16095',
			'Gemme de Hache-sanglante|21777',
			'Sceau d\\\'ascension brut |24554||MS',
			'Sceau d\\\'ascension forgé|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Amulette Drakefeu|16309',
			'Ordres du seigneur de guerre Goretooth|',
			'Ordre du chef de guerre|',
			'Pour la Horde !|',
			'Ce que le vent apporte|',
			'Le Champion de la Horde|',
			'Le testament de Rexxar|',
			'Illusions d\\\'Occulus|',
			'Querelleur ardent|',
			'L\\\'épreuve des crânes, Cristallomancier|',
			'L\\\'épreuve des crânes, Somnus|',
			'L\\\'épreuve des crânes, Chronalis|',
			'L\\\'épreuve des crânes, Axtroz|',
			'Ascension...|',
			'Sang du Champion des Dragons noirs|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Quintessence éternelle|22754'
		),
);
