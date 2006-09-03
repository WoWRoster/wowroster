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

require_once( 'settings.php' );

$header_title = $wordings[$roster_conf['roster_lang']]['menuhonor'];

//---[ Check for Guild Info ]------------
$guild_info = $wowdb->get_guild_info($roster_conf['server_name'],$roster_conf['guild_name']);
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}
// Get guild info from guild info check above
$guildId = $guild_info['guild_id'];
$guildMOTD = $guild_info['guild_motd'];
$guildFaction = $guild_info['faction'];

$mainQuery =
	'SELECT '.
	'`members`.`member_id`, '.
	'`members`.`name`, '.
	'`members`.`class`, '.
	'`members`.`note`, '.
	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`level`, '.
	'`members`.`guild_rank`, '.
	'`members`.`guild_title`, '.
	'`members`.`zone`, '.
	"UNIX_TIMESTAMP( `members`.`last_online`) AS 'last_online_stamp', ".
	"DATE_FORMAT( `members`.`last_online`, '".$timeformat[$roster_conf['roster_lang']]."' ) AS 'last_online', ".

	'`players`.`RankName`, '.
	'`players`.`RankInfo`, '.
	"IF( `players`.`RankInfo` IS NULL OR `players`.`RankInfo` = '0', 1, 0 ) AS 'risnull', ".
	'`players`.`exp`, '.
	'`players`.`server`, '.
	'`players`.`clientLocale`, '.
	'`players`.`RankIcon`, '.
	'`players`.`Rankexp`, '.
	'`players`.`sessionHK`, '.
	'`players`.`sessionDK`, '.
	'`players`.`yesterdayHK`, '.
	'`players`.`yesterdayDK`, '.
	'`players`.`yesterdayContribution`, '.
	'`players`.`lastweekHK`, '.
	'`players`.`lastweekDK`, '.
	'`players`.`lastweekContribution`, '.
	'`players`.`lastweekRank`, '.
	'`players`.`lifetimeHK`, '.
	'`players`.`lifetimeDK`, '.
	'`players`.`lifetimeRankName` '.

	'FROM `'.ROSTER_MEMBERSTABLE.'` AS members '.
	'INNER JOIN `'.ROSTER_PLAYERSTABLE.'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	'WHERE`members`.`guild_id` = 1 '.
	'ORDER BY `members`.`level` DESC, `members`.`name` ASC';

$FIELD['name'] = array(
	'lang_field' => 'name',
	'required' => true,
	'default'  => true,
	'value' => 'name_value',
);

$FIELD['class'] = array(
	'lang_field' => 'class',
	'default'  => true,
	'value' => 'class_value',
);

$FIELD['level'] = array(
	'lang_field' => 'level',
	'default'  => true,
	'value' => 'level_value',
);

$FIELD['RankName'] = array(
	'lang_field' => 'currenthonor',
	'value' => 'honor_value',
);

$FIELD['sessionHK'] = array(
	'lang_field' => 'Sess HK',
);

$FIELD['sessionDK'] = array(
	'lang_field' => 'Sess DK',
);

$FIELD['yesterdayHK'] = array(
	'lang_field' => 'Yest HK',
);

$FIELD['yesterdayDK'] = array(
	'lang_field' => 'Yest DK',
);

$FIELD['yesterdayContribution'] = array(
		'lang_field' => 'Yest CP',
);

$FIELD['lastweekHK'] = array(
	'lang_field' => 'LW HK',
);

$FIELD['lastweekDK'] = array(
	'lang_field' => 'LW DK',
);

$FIELD['lastweekContribution'] = array(
	'lang_field' => 'LW CP',
);

$FIELD['lastweekRank'] = array(
	'lang_field' => 'LW Rank',
);

$FIELD['lifetimeHK'] = array(
		'lang_field' => 'Life HK',
);

$FIELD['lifetimeDK'] = array(
	'lang_field' => 'Life DK',
);

$FIELD['lifetimeRankName'] = array(
	'lang_field' => 'Highest Rank',
);

include_once (ROSTER_LIB.'memberslist.php');

$memberlist = new memberslist;

$memberlist->prepareData($mainQuery, $FIELD, 'memberslist');

$more_css = '<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/sorttable.js"></script>';

// Start output
include_once (ROSTER_BASE.'roster_header.tpl');

include_once (ROSTER_LIB.'menu.php');

if( $roster_conf['hspvp_list_disp'] == 'hide' )
{
	$pvp_hs_colapse='';
	$pvp_hs_full   =' style="display:none;"';
}
else
{
	$pvp_hs_colapse=' style="display:none;"';
	$pvp_hs_full   ='';
}

echo "<table>\n  <tr>\n";

if ( $roster_conf['index_hslist'] == 1 )
{
	echo '    <td valign="top">';
	include_once( ROSTER_BASE.'hslist.php');
	echo "    </td>\n";
}

if ( $roster_conf['index_pvplist'] == 1 )
{
	echo '    <td valign="top">';
	include_once( ROSTER_BASE.'pvplist.php');
	echo "    </td>\n";
}

echo "  </tr>\n</table>\n";

echo $memberlist->makeFilterBox();
echo $memberlist->makeMembersList();

include_once (ROSTER_BASE.'roster_footer.tpl');
?>