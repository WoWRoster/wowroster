<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Contants and defines file for Roster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.0
*/

if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}

define('ROSTER_VERSION','1.8.0dev');

/**
 * Base, absolute roster admin directory
 */
define('ROSTER_ADMIN',ROSTER_BASE.'admin'.DIR_SEP);

/**
 * Base, absolute roster ajax directory
 */
define('ROSTER_AJAX',ROSTER_BASE.'ajax'.DIR_SEP);

/**
 * Base, absolute roster addons directory
 */
define('ROSTER_ADDONS',ROSTER_BASE.'addons'.DIR_SEP);

/**
 * Base, absolute roster pages directory
 */
define('ROSTER_PAGES',ROSTER_BASE.'pages'.DIR_SEP);

/**
 * Base, absolute roster localization directory
 */
define('ROSTER_LOCALE_DIR',ROSTER_BASE.'localization'.DIR_SEP);

/**
 * Roster Remote File Validation
 * Please make a page on the web where you place the most rescent version of the files, including this file.
 * The webpage must be entered below without a trailing slash
 */
define('ROSTER_SVNREMOTE', 'http://www.wowroster.net/roster_updater/version_match.php');

/**
 * Deny access to these addon files
 */
define('ROSTER_NON_ADDON','conf,install.def,update_hook');

/**
 * Database Table Names
 * Only set if $roster->db->prefix exists
 */
if( isset($roster->db->prefix) )
{
	define('ROSTER_GUILDTABLE',$roster->db->prefix.'guild');
	define('ROSTER_ACCOUNTTABLE',$roster->db->prefix.'account');
	define('ROSTER_ADDONTABLE',$roster->db->prefix.'addon');
	define('ROSTER_ADDONMENUTABLE',$roster->db->prefix.'addon_menu');
	define('ROSTER_ADDONCONFTABLE',$roster->db->prefix.'addon_config');
	define('ROSTER_BUFFSTABLE',$roster->db->prefix.'buffs');
	define('ROSTER_CONFIGTABLE',$roster->db->prefix.'config');
	define('ROSTER_ITEMSTABLE',$roster->db->prefix.'items');
	define('ROSTER_MAILBOXTABLE',$roster->db->prefix.'mailbox');
	define('ROSTER_MEMBERLOGTABLE',$roster->db->prefix.'memberlog');
	define('ROSTER_MEMBERSTABLE',$roster->db->prefix.'members');
	define('ROSTER_MENUTABLE',$roster->db->prefix.'menu');
	define('ROSTER_MENUBUTTONTABLE',$roster->db->prefix.'menu_button');
	define('ROSTER_PETSTABLE',$roster->db->prefix.'pets');
	define('ROSTER_PLAYERSTABLE',$roster->db->prefix.'players');
	define('ROSTER_PVP2TABLE',$roster->db->prefix.'pvp2');
	define('ROSTER_QUESTSTABLE',$roster->db->prefix.'quests');
	define('ROSTER_REALMSTATUSTABLE',$roster->db->prefix.'realmstatus');
	define('ROSTER_RECIPESTABLE',$roster->db->prefix.'recipes');
	define('ROSTER_REPUTATIONTABLE',$roster->db->prefix.'reputation');
	define('ROSTER_SKILLSTABLE',$roster->db->prefix.'skills');
	define('ROSTER_SPELLTABLE',$roster->db->prefix.'spellbook');
	define('ROSTER_SPELLTREETABLE',$roster->db->prefix.'spellbooktree');
	define('ROSTER_PETSPELLTABLE',$roster->db->prefix.'spellbook_pet');
	define('ROSTER_TALENTSTABLE',$roster->db->prefix.'talents');
	define('ROSTER_TALENTTREETABLE',$roster->db->prefix.'talenttree');
}

/**
 * Some static and semi-static game data.
 */
define('ROSTER_MAXCHARLEVEL','70');
define('ROSTER_MAXSKILLLEVEL','375');
