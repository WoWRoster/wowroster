<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
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

// ----[ Prevent Direct Access to this file ]-------------------
if( !defined('ROSTER_INCLUDED') )
{
	exit("You can't access this file directly!");
}


// ----[ Template SubDir for character page ]-------------------
$chartdir = 'char_tpl'.DIR_SEP;


// ----[ Get Character name or member ID ]----------------------
if( isset($_GET['member']) && !empty($_GET['member']) )
{
	// if "member" is numeric, use it as a member_id
	if( is_numeric($_GET['member']) )
	{
		$memberid = $_GET['member'];
	}
	else
	{
		$name = $_GET['member'];
	}
}


// ----[ Build SQL Query ]--------------------------------------
if( isset($name) )
{
	$sqlquery = "SELECT t1.*, t2.guild_title, t3.* ".
		"FROM `".ROSTER_PLAYERSTABLE."` t1 ".
		"LEFT JOIN `".ROSTER_MEMBERSTABLE."` t2 ON t1.name = t2.name ".
		"LEFT JOIN `".ROSTER_GUILDTABLE."` t3 ON t2.guild_id = t3.guild_id ".
		"WHERE t1.name = '$name'";
}
elseif( isset($memberid) )
{
	$sqlquery = "SELECT t1.*, t2.guild_title, t3.guild_name ".
		"FROM `".ROSTER_PLAYERSTABLE."` t1 ".
		"LEFT JOIN `".ROSTER_MEMBERSTABLE."` t2 ON t1.name = t2.name ".
		"LEFT JOIN `".ROSTER_GUILDTABLE."` t3 ON t2.guild_id = t3.guild_id ".
		"WHERE t1.member_id = '$memberid'";
}
else
{
	die_quietly('No Character Selected','',(__FILE__),(__LINE__));
}


// Make sure we have a set _GET[action]
if( !isset($_GET['action']) )
{
	$action = 'invalidaction';
}
else
{
	$action = $_GET['action'];
}

// Assign the selected action to the template for icon highlighting
$tpl->assign( 'action', $action );


// ----[ Query Database for character info ]--------------------
setSqlQuery($sqlquery);

$result = db_query($sqlquery);
if( PEAR::isError($result) )
{
    die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
}

$result->fetchInto($data);


if( $result->numRows() == 0 )
{
	if( isset($name) )
	{
		die_quietly( "Sorry, no data in database for: $name",'',(__FILE__),(__LINE__),$sqlquery );
	}
	elseif( isset($memberid) )
	{
		die_quietly( "Sorry, no data in database for Member ID: $memberid",'',(__FILE__),(__LINE__),$sqlquery );
	}
}


// ----[ Assign SQL result to template variable ]---------------
$tpl->assign( 'data', $data );



// ----[ English'ize the character image ]----------------------
$char_image = getEnglishValue($data['sex'],$data['locale']).'-'.getEnglishValue($data['race'],$data['locale']);
$tpl->assign( 'char_image',$char_image );



// ----[ Assign Page Title ]------------------------------------
$page_title = getLocaleValue('charpage_pagetitle',GetConfigValue('lang')).' - '.$data['name'];


// ----[ Character functions file ]-----------------------------
require_once( ROSTER_BASE.'functions'.DIR_SEP.'char_lib.php' );


// ----[ Figure out what to do next ]---------------------------

// Store menu output into "$display"
$display = $tpl->fetch( $chartdir.'menu.tpl' );


// Store body output into "$display"
switch( $action )
{
	case 'bags':
		bagInit($data);
		$display .= $tpl->fetch($chartdir.'bags.tpl');
		$page_title .= ' ['.getLocaleValue('charpage_menu_bags',GetConfigValue('lang')).']';
		break;

	case 'bank':
		bankInit($data);
		$display .= $tpl->fetch($chartdir.'bags.tpl');
		$page_title .= ' ['.getLocaleValue('charpage_menu_bank',GetConfigValue('lang')).']';
		break;

	case 'inventory':
		bankInit($data);
		$display .= $tpl->fetch($chartdir.'bags.tpl');
		bagInit($data);
		$display .= $tpl->fetch($chartdir.'bags.tpl');
		$page_title .= ' ['.getLocaleValue('charpage_menu_inventory',GetConfigValue('lang')).']';
		break;

	case 'talents':
		writeTalents($data['member_id']);
		$display .= $tpl->fetch($chartdir.'talents.tpl');
		$page_title .= ' ['.getLocaleValue('charpage_menu_talents',GetConfigValue('lang')).']';
		break;

	case 'quests':
		$display .= $tpl->fetch($chartdir.'quests.tpl');
		$page_title .= ' ['.getLocaleValue('charpage_menu_quests',GetConfigValue('lang')).']';
		break;

	case 'professions':
		$display .= $tpl->fetch($chartdir.'professions.tpl');
		$page_title .= ' ['.getLocaleValue('charpage_menu_professions',GetConfigValue('lang')).']';
		break;

	case 'spellbook':
		writeSpellbook($data['member_id']);
		$display .= $tpl->fetch($chartdir.'spellbook.tpl');
		$page_title .= ' ['.getLocaleValue('charpage_menu_spellbook',GetConfigValue('lang')).']';
		break;

	case 'character':
		writeCharacter($data);
		$display .= $tpl->fetch($chartdir.'character.tpl');
		break;

	case 'pvp':
		$display .= $tpl->fetch($chartdir.'pvp.tpl');
		$page_title .= ' ['.getLocaleValue('charpage_menu_pvplog',GetConfigValue('lang')).']';
		break;


	default:
		writeCharacter($data);
		$display .= $tpl->fetch($chartdir.'character.tpl');
		break;
}


// ----[ Assign Page Title ]------------------------------------
$tpl->assign('page_title', $page_title);


?>