<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}

define('ROSTER_VERSION','1.8.0');
define('ROSTER_MAXCHARLEVEL','70');
define('ROSTER_MAXSKILLLEVEL','375');

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
 * Database Table Names
 * Only set if $db_prefix exists
 */
if( isset($db_prefix) )
{
	define('ROSTER_GUILDTABLE',$db_prefix.'guild');
	define('ROSTER_ACCOUNTTABLE',$db_prefix.'account');
	define('ROSTER_ADDONTABLE',$db_prefix.'addon');
	define('ROSTER_ADDONMENUTABLE',$db_prefix.'addon_menu');
	define('ROSTER_ADDONCONFTABLE',$db_prefix.'addon_config');
	define('ROSTER_BUFFSTABLE',$db_prefix.'buffs');
	define('ROSTER_CONFIGTABLE',$db_prefix.'config');
	define('ROSTER_ITEMSTABLE',$db_prefix.'items');
	define('ROSTER_MAILBOXTABLE',$db_prefix.'mailbox');
	define('ROSTER_MEMBERLOGTABLE',$db_prefix.'memberlog');
	define('ROSTER_MEMBERSTABLE',$db_prefix.'members');
	define('ROSTER_MENUTABLE',$db_prefix.'menu');
	define('ROSTER_MENUBUTTONTABLE',$db_prefix.'menu_button');
	define('ROSTER_PETSTABLE',$db_prefix.'pets');
	define('ROSTER_PLAYERSTABLE',$db_prefix.'players');
	define('ROSTER_PVP2TABLE',$db_prefix.'pvp2');
	define('ROSTER_QUESTSTABLE',$db_prefix.'quests');
	define('ROSTER_REALMSTATUSTABLE',$db_prefix.'realmstatus');
	define('ROSTER_RECIPESTABLE',$db_prefix.'recipes');
	define('ROSTER_REPUTATIONTABLE',$db_prefix.'reputation');
	define('ROSTER_SKILLSTABLE',$db_prefix.'skills');
	define('ROSTER_SPELLTABLE',$db_prefix.'spellbook');
	define('ROSTER_SPELLTREETABLE',$db_prefix.'spellbooktree');
	define('ROSTER_PETSPELLTABLE',$db_prefix.'spellbook_pet');
	define('ROSTER_TALENTSTABLE',$db_prefix.'talents');
	define('ROSTER_TALENTTREETABLE',$db_prefix.'talenttree');
}
