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
$tpl->assign('page_title', $roster_wordings[$roster_conf['lang']]['pagetitle_guilds']);



// ----[ Build SQL Query ]--------------------------------------
$sqlquery = "SELECT * FROM ".ROSTER_GUILDTABLE;


// ----[ Query Database for guild info ]--------------------
setSqlQuery($sqlquery);

$result = db_query($sqlquery);
if( PEAR::isError($result) )
{
    die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
}

if( $result->numRows() == 0 )
{
	die_quietly( "Sorry, no guild(s) found in database",'',(__FILE__),(__LINE__),$sqlquery );
}
if( $result->numRows() > 0 )
{
	for ($j=0; $j < $result->numRows(); $j++)
	{
		$result->fetchInto($data);
		$data['faction_en'] = strtolower(getEnglishValue($data['faction']));

		setTooltip('guild_'.$data['guild_id'],addslashes('<span style="font-size:12px; font-weight:bold;">'.$data['guild_name'].' of '.$data['realm'].'</span><br />Members: '.$data['guild_num_members'].'<br />Last Updated: '.date(getLocaleValue('phptimeformat'),strtotime($data['guild_dateupdatedutc'])).'<br /><br />MOTD:<div class="motd_tooltip">'.$data['guild_motd'].'</div>'));

		$guilds_data[$j] = $data;
	}
}
// ----[ Assign SQL result to template variable ]---------------
$tpl->assign( 'guilds_data', $guilds_data );

// ----[ Fetch the page ]---------------------------------------
$display = $tpl->fetch('guilds.tpl');

?>