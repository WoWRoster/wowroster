<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Master Locale File
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage Locale
*/

// Add locales via a function call
// This prevents one locale from overwritting the others
$roster->multilanguages = array();
$roster->multilanguages[] = 'deDE'; // German
$roster->multilanguages[] = 'enUS'; // US English
$roster->multilanguages[] = 'esES'; // Spanish
$roster->multilanguages[] = 'frFR'; // French
$roster->multilanguages[] = 'koKR'; // Korean
$roster->multilanguages[] = 'ruRU'; // Russian
$roster->multilanguages[] = 'zhCN'; // Chineese
$roster->multilanguages[] = 'zhTW'; // Taiwaneese



// Credits page
// Only defined here because we don't need to or want to translate this for EVERY locale

$creditspage['top']='<p>Props to <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, and <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> for the original code used for this site</p>
<p>Special thanks to calvin from <a href="http://www.rpgoutfitter.com" target="_blank">rpgoutfitter.com</a> for his wonderfull addons CharacterProfiler and GuildProfiler</p>
<p>To the Devs of Roster, for helping to build and maintain the package. You Rock!</p>
<p>Thanks to all the coders who have contributed code, bug fixes, time, and testing of WoWRoster</p>
<p>And thanks to the WoWRoster.net community, you are the reason we exist!</p>';

// This is an array of the Dev team
$creditspage['devs'] = array(
		'active'=>array(
			array(	'name'=>	'Zanix',
					'info'=>	'Coordinator, Site Admin, Author of SigGen, UniAdmin'),
			array(	'name'=>	'PleegWat',
					'info'=>	'Lead Developer'),
			array(	'name'=>	'Adric',
					'info'=>	'Interface Specialist'),
			array(	'name'=>	'Anaxent',
					'info'=>	'Public Relations, WoWRosterDF Author (DragonflyCMS Port)'),
			array(	'name'=>	'Ulminia',
					'info'=>	'Backend Code Update, WoWRoster-Profiler'),
			array(	'name'=>	'Zeryl',
					'info'=>	'Security Specialist, Author of Missing Recipes'),
			array(	'name'=>	'Matt Miller',
					'info'=>	'Author of UniUploader, UniAdmin'),
			array(	'name'=>	'bsmorgan',
					'info'=>	'Author of PvPLog'),
		),

		'library'=>array(
			array(	'name'=>	'jQuery',
					'info'=>	'<a href="http://jquery.com" target="_blank">Javascript library</a>'),
			array(	'name'=>	'DynamicDrive',
					'info'=>	'<a href="http://www.dynamicdrive.com/dynamicindex17/tabcontent.htm" target="_blank">Tab content script</a>'),
			array(	'name'=>	'Erik Bosrup',
					'info'=>	'<a href="http://www.bosrup.com/web/overlib/" target="_blank">OverLib tooltip library</a>'),
			array(	'name'=>	'Walter Zorn',
					'info'=>	'<a href="http://www.walterzorn.com/dragdrop/dragdrop_e.htm" target="_blank">DHTML Drag/Drop library</a>'),
			array(	'name'=>	'MiniXML',
					'info'=>	'<a href="http://minixml.psychogenic.com" target="_blank">MiniXML Library</a>'),
			array(  'name'=>	'nicEdit',
					'info'=>	'<a href="http://www.nicEdit.com" target="_blank">Micro Inline WYSIWYG Editor</a>'),
		),

		'3rdparty'=>array(
			array(	'name'=>	'<a href="http://53x11.com" target="_blank">Nick Schaffner</a>',
					'info'=>	'Original WoW server status'),
			array(	'name'=>	'Averen',
					'info'=>	'Original MemberLog author'),
			array(	'name'=>	'Cybrey',
					'info'=>	'Advanced stats &amp; bonuses block on the character page'),
			array(	'name'=>	'dehoskins',
					'info'=>	'Improvements to character item stats &amp; bonuses'),
			array(	'name'=>	'<a href="http://www.eqdkp.com" target="_blank">EQdkp team</a>',
					'info'=>	'Original version of the installer/upgrader code and their templating engine'),
		),

		'inactive'=>array(
			array(	'name'=>	'AnthonyB',
					'info'=>	'Retired, Site Admin and Coordinator v1.04 to v1.7.0'),
			array(	'name'=>	'Airor/Chris',
					'info'=>	'Inactive, Coded new lua parser for v1.7.0'),
			array(	'name'=>	'calvin',
					'info'=>	'Inactive Gimpy Dev, Author of CharacterProfiler, GuildProfiler'),
			array(	'name'=>	'DS',
					'info'=>	'Inactive'),
			array(	'name'=>	'dsrbo',
					'info'=>	'Retired, Retired PvPLog Author'),
			array(	'name'=>	'Guppy',
					'info'=>	'Retired'),
			array(	'name'=>	'mathos',
					'info'=>	'Retired, phpuniUploader'),
			array(	'name'=>	'Mordon',
					'info'=>	'Retired, Head Development up to v1.03'),
			array(	'name'=>	'mrmuskrat',
					'info'=>	'Retired, Retired PvPLog Author'),
			array(	'name'=>	'Nemm',
					'info'=>	'Inactive'),
			array(	'name'=>	'nerk01',
					'info'=>	'Inactive'),
			array(	'name'=>	'Nostrademous',
					'info'=>	'Retired, Retired PvPLog Author'),
			array(	'name'=>	'peperone',
					'info'=>	'Inactive, German Translator'),
			array(	'name'=>	'poetter',
					'info'=>	'Inactive'),
			array(	'name'=>	'RossiRat',
					'info'=>	'Inactive, German Translator'),
			array(	'name'=>	'seleleth',
					'info'=>	'Inactive'),
			array(	'name'=>	'Sphinx',
					'info'=>	'Inactive, German Translator'),
			array(	'name'=>	'silencer-ch-au',
					'info'=>	'Inactive'),
			array(	'name'=>	'Swipe',
					'info'=>	'Inactive'),
			array(	'name'=>	'vaccafoeda',
					'info'=>	'Inactive'),
			array(	'name'=>	'Vich',
					'info'=>	'Inactive'),
		),
	);

$creditspage['bottom']='<p>WoWRoster is licensed under the <a href="http://www.gnu.org/licenses/gpl.html" target="_blank">GNU General Public License v3</a>.</p>
<p>Serveral javascript files are libraries that are under their own licenses.</p>
<p>The installer was derived from the EQdkp installer and is licensed under the GNU General Public License</p>
<p>See <a href="' . makelink('license') . '">license</a> for more details</p>';
