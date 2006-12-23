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


// ----[ Assign Page Title ]------------------------------------
$tpl->assign('page_title', $roster_wordings[$roster_conf['lang']]['pagetitle_members']);



if ( !isset($_GET['guild']) )
{
	$sqlquery = "SELECT guild_id,guild_name,realm FROM ".ROSTER_GUILDTABLE." WHERE `guild_name` = '".escape($roster_conf['guild_name']).
	"' AND `realm` = '".escape($roster_conf['realm_name'])."'";
}
else
{
	$sqlquery = "SELECT guild_id,guild_name,realm FROM ".ROSTER_GUILDTABLE." WHERE `guild_id` = ".$_GET['guild'];
}
setSqlQuery($sqlquery);

$result = db_query($sqlquery);
if( PEAR::isError($result) )
{
	die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
}
$result->fetchInto($data);
$guild_id = $data['guild_id'];
$tpl->assign('guildname',$data['guild_name']);
$tpl->assign('realmname',$data['realm']);


// ----[ Build SQL Query ]--------------------------------------
$sqlquery =
	"SELECT ".
	// we could limit down what it actually pulls, but to save hassles in sorting by invisible fields, etc, we'll keep it constant. The query is a lot simpler than it looks anyway.
	"`members`.`member_id`, ".
	"`members`.`name`, ".
	"`members`.`class`, ".
	"`players`.`honor_current_name`, ".
	"IF( `members`.`level` < `players`.`level`, `players`.`level`, `members`.`level` ) AS 'level', ".
	"`members`.`note`, ".
	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	"`members`.`guild_rank`, ".
	"`members`.`guild_title`, ".
	"`members`.`zone`, ".
	"`players`.`realm` ".
	"FROM `".ROSTER_MEMBERSTABLE."` AS members ".
	"LEFT JOIN `".ROSTER_PLAYERSTABLE."` AS players ON `members`.`member_id` = `players`.`member_id` ".
	"WHERE members.guild_id = ".$guild_id." ".
	"ORDER BY `members`.`name` ASC";


// ----[ Query Database for members info ]--------------------
setSqlQuery($sqlquery);

$result = db_query($sqlquery);
if( PEAR::isError($result) )
{
    die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
}

if( $result->numRows() == 0 )
{
	die_quietly( "Sorry, no members found in database",'',(__FILE__),(__LINE__),$sqlquery );
}
if( $result->numRows() > 0 )
	{
		for ($j=0; $j < $result->numRows(); $j++)
		{
			$result->fetchInto($data);
			$data2[$j] = $data;
		}
	}
// ----[ Assign SQL result to template variable ]---------------
$tpl->assign( 'data', $data2 );






// ----[ Fetch the page ]---------------------------------------
$display = $tpl->fetch('members.tpl');

?>