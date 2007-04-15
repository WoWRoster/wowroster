<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Master Locale File
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

// Add locales via a function call
// This prevents one locale from overwritting the others
$roster_conf['multilanguages'] = array();
$roster_conf['multilanguages'][] = 'deDE';
$roster_conf['multilanguages'][] = 'enUS';
$roster_conf['multilanguages'][] = 'esES';
$roster_conf['multilanguages'][] = 'frFR';


$wordings = array();
foreach( $roster_conf['multilanguages'] as $langvalue )
{
	add_locale_file(ROSTER_LOCALE_DIR.$langvalue.'.php',$langvalue,$wordings);
}

$act_words = &$wordings[$roster_conf['roster_lang']];



// Credits page
// Only defined here because we don't need to or want to translate this for EVERY locale

$creditspage['top']='Props to <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, and <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> for the original code used for this site
<br />
Special thanks to calvin from <a href="http://www.rpgoutfitter.com" target="_blank">rpgoutfitter.com</a> for his wonderfull addons CharacterProfiler and GuildProfiler
<br /><br />
To the DEVs of Roster, for helping to build and maintain the package. You Rock!
<br /><br />
Thanks to all the coders who have contributed code, bug fixes, time, and testing of WoWRoster
<br /><br />';

// This is an array of the dev team
$creditspage['devs'] = array(
		'active'=>array(
			array(	"name"=>	"zanix",
					"info"=>	"Site Admin, WoWRoster Coordinator<br />Author of SigGen<br />Author of UniAdmin"),
			array(	"name"=>	"Anaxent",
					"info"=>	"WoWRoster Dev<br />WoWRosterDF Author (DragonflyCMS Port)"),
			array(	"name"=>	"mathos",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"PleegWat",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"seleleth",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Sphinx",
					"info"=>	"WoWRoster Dev<br />German Translator"),
			array(	"name"=>	"silencer-ch-au",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Zeryl",
					"info"=>	"WoWRoster Dev<br />Missing Recipes Roster-Addon Author"),
			array(	"name"=>	"Matt Miller",
					"info"=>	"Gimpy DEV<br />Author of UniAdmin and UniUploader"),
			array(	"name"=>	"calvin",
					"info"=>	"Gimpy DEV<br />Author of CharacterProfiler and GuildProfiler"),
			array(	"name"=>	"bsmorgan",
					"info"=>	"Gimpy DEV<br />Author of PvPLog"),
		),

		'library'=>array(
			array(	"name"=>	"DynamicDrive",
					"info"=>	"<a href='http://www.dynamicdrive.com/dynamicindex17/tabcontent.htm'>Tab content script</a><br /><a href='http://www.dynamicdrive.com/notice.htm'>DynamicDrive license</a>"),
			array(	"name"=>	"Erik Bosrup",
					"info"=>	"<a href='http://www.bosrup.com/web/overlib/'>OverLib tooltip library</a><br /><a href='http://www.bosrup.com/web/overlib/?License'>License</a>"),
			array(	"name"=>	"Walter Zorn",
					"info"=>	"<a href='http://www.walterzorn.com/dragdrop/dragdrop_e.htm'>DHTML Drag/Drop library</a><br /><a href='http://gnu.org/copyleft/lesser.html'>Lesser General Public License</a>"),
		),

		'3rdparty'=>array(
			array(	"name"=>	"<a href='http://53x11.com' target='_blank'>Nick Schaffner</a>",
					"info"=>	"Original WoW server status"),
			array(	"name"=>	"Averen",
					"info"=>	"Original MemberLog author"),
			array(	"name"=>	"Cybrey",
					"info"=>	"Advanced stats &amp; bonuses block on the character page"),
			array(	"name"=>	"vgjunkie",
					"info"=>	"Recoded professions page for v1.7.1<br />New show/hide javascript code for collapsable tables"),
			array(	"name"=>	"dehoskins",
					"info"=>	"Improvements to the stats &amp; bonuses block"),
			array(	"name"=>	"<a href='http://www.eqdkp.com' target='_blank'>EQdkp team</a>",
					"info"=>	"Original version of the installer/upgrader code<br />and their templating engine"),
		),

		'inactive'=>array(
			array(	"name"=>	"AnthonyB",
					"info"=>	"Retired DEV<br />Site Admin and Coordinator<br />v1.04 to v1.7.0"),
			array(	"name"=>	"Airor/Chris",
					"info"=>	"Inactive Dev<br />Coded new lua parser for v1.7.0"),
			array(	"name"=>	"dsrbo",
					"info"=>	"Retired DEV<br />Retired PvPLog Author"),
			array(	"name"=>	"Guppy",
					"info"=>	"Retired DEV"),
			array(	"name"=>	"Mordon",
					"info"=>	"Retired Dev<br />Head Dev v1.03 and lower"),
			array(	"name"=>	"mrmuskrat",
					"info"=>	"Retired DEV<br />Retired PvPLog Author"),
			array(	"name"=>	"Nemm",
					"info"=>	"Inactive Dev"),
			array(	"name"=>	"nerk01",
					"info"=>	"Inactive Dev"),
			array(	"name"=>	"Nostrademous",
					"info"=>	"Retired Dev<br />Retired PvPLog Author"),
			array(	"name"=>	"peperone",
					"info"=>	"Inactive Dev<br />German Translator"),
			array(	"name"=>	"RossiRat",
					"info"=>	"Inactive Dev<br />German Translator"),
			array(	"name"=>	"Swipe",
					"info"=>	"Inactive Dev"),
			array(	"name"=>	"vaccafoeda",
					"info"=>	"Inactive Dev"),
			array(	"name"=>	"Vich",
					"info"=>	"Inactive Dev"),
		),
	);

$creditspage['bottom']='WoW Roster is licensed under a Creative Commons "Attribution-NonCommercial-ShareAlike 2.5" license.
<br />
Serveral javascript files are libraries that are under their own licenses.
<br />
The installer was derived from the EQdkp installer and is licensed under the GNU General Public License
<br /><br />
See <a href="'.makelink('license').'">license.txt</a> for details';
