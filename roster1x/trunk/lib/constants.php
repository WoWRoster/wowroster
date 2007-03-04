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
 * Page linking
 */
define('ROSTER_PAGE', 'p');
define('ROSTER_LINK', 'index.php?'.ROSTER_PAGE.'=%1$s');

/**
 * Base, absolute roster admin directory
 */
define('ROSTER_ADMIN',ROSTER_BASE.'admin'.DIR_SEP);

/**
 * Base, absolute roster addons directory
 */
define('ROSTER_ADDONS',ROSTER_BASE.'addons'.DIR_SEP);

/**
 * Base, absolute roster pages directory
 */
define('ROSTER_PAGES',ROSTER_BASE.'pages'.DIR_SEP);

/**
 * Database Table Names
 */
if( isset($db_prefix) )
{
	define('ROSTER_GUILDTABLE',$db_prefix.'guild');
	define('ROSTER_ACCOUNTTABLE',$db_prefix.'account');
	define('ROSTER_ADDONTABLE',$db_prefix.'addon');
	define('ROSTER_ADDONMENUTABLE',$db_prefix.'addon_menu');
	define('ROSTER_BUFFSTABLE',$db_prefix.'buffs');
	define('ROSTER_CONFIGTABLE',$db_prefix.'config');
	define('ROSTER_ITEMSTABLE',$db_prefix.'items');
	define('ROSTER_MAILBOXTABLE',$db_prefix.'mailbox');
	define('ROSTER_MEMBERLOGTABLE',$db_prefix.'memberlog');
	define('ROSTER_MEMBERSTABLE',$db_prefix.'members');
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
	define('ROSTER_TALENTSTABLE',$db_prefix.'talents');
	define('ROSTER_TALENTTREETABLE',$db_prefix.'talenttree');
}
