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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

define('IN_SORTMEMBER',true);

//---[ Check for Guild Info ]------------
if( empty($guild_info) )
{
	die_quietly( $act_words['nodata'] );
}

$mainQuery =
	'SELECT '.
	'`members`.`member_id`, '.
	'`members`.`name`, '.
	'`members`.`class`, '.
	'`members`.`level`, '.
	'`members`.`zone`, '.
	"(UNIX_TIMESTAMP( `members`.`last_online`)*1000+".($roster_conf['localtimeoffset']*3600000).") AS 'last_online_stamp', ".
	"DATE_FORMAT(  DATE_ADD(`members`.`last_online`, INTERVAL ".$roster_conf['localtimeoffset']." HOUR ), '".$act_words['timeformat']."' ) AS 'last_online', ".
	'`members`.`note`, '.
	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`guild_title`, '.
	
	'`alts`.`main_id`, '.

	'`players`.`server`, '.
	'`players`.`race`, '.
	'`players`.`sex`, '.
	'`players`.`exp`, '.
	'`players`.`clientLocale`, '.

	'`players`.`sessionHK`, '.
	'`players`.`sessionCP`, '.
	'`players`.`yesterdayHK`, '.
	'`players`.`yesterdayContribution`, '.
	'`players`.`lifetimeHK`, '.
	'`players`.`lifetimeRankName`, '.
	'`players`.`lifetimeHighestRank`, '.
	"IF( `players`.`lifetimeHighestRank` IS NULL OR `players`.`lifetimeHighestRank` = '0', 1, 0 ) AS 'risnull', ".
	'`players`.`honorpoints`, '.
	'`players`.`arenapoints` '.

	'FROM `'.ROSTER_MEMBERSTABLE.'` AS members '.
	'INNER JOIN `'.ROSTER_PLAYERSTABLE.'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	'LEFT JOIN `'.ROSTER_ALT_TABLE.'` AS alts ON `members`.`member_id` = `alts`.`member_id` '.
	'WHERE `members`.`guild_id` = "'.$guild_info['guild_id'].'" '.
	'ORDER BY IF(`members`.`member_id` = `alts`.`member_id`,1,0), ';


$FIELD['name'] = array(
	'lang_field' => 'name',
	'order'    => array( '`members`.`name` ASC' ),
	'order_d'    => array( '`members`.`name` DESC' ),
	'value' => 'name_value',
	'display' => 3,
);

$FIELD['class'] = array(
	'lang_field' => 'class',
	'order'    => array( '`members`.`class` ASC' ),
	'order_d'    => array( '`members`.`class` DESC' ),
	'value' => 'class_value',
	'display' => $addon['config']['honor_class'],
);

$FIELD['level'] = array(
	'lang_field' => 'level',
	'order_d'    => array( '`members`.`level` ASC' ),
	'value' => 'level_value',
	'display' => $addon['config']['honor_level'],
);

$FIELD['sessionHK'] = array(
	'lang_field' => 'todayhk',
	'order' => array( '`players`.`sessionHK` DESC' ),
	'order_d' => array( '`players`.`sessionHK` ASC' ),
	'display' => $addon['config']['honor_thk'],
);

$FIELD['sessionCP'] = array(
	'lang_field' => 'todaycp',
	'order' => array( '`players`.`sessionCP` DESC' ),
	'order_d' => array( '`players`.`sessionCP` ASC' ),
	'display' => $addon['config']['honor_tcp'],
);

$FIELD['yesterdayHK'] = array(
	'lang_field' => 'yesthk',
	'order' => array( '`players`.`yesterdayHK` DESC' ),
	'order_d' => array( '`players`.`yesterdayHK` ASC' ),
	'display' => $addon['config']['honor_yhk'],
);

$FIELD['yesterdayContribution'] = array(
	'lang_field' => 'yestcp',
	'order' => array( '`players`.`yesterdayContribution` DESC' ),
	'order_d' => array( '`players`.`yesterdayContribution` ASC' ),
	'display' => $addon['config']['honor_ycp'],
);

$FIELD['lifetimeHK'] = array(
	'lang_field' => 'lifehk',
	'order' => array( '`players`.`lifetimeHK` DESC' ),
	'order_d' => array( '`players`.`lifetimeHK` ASC' ),
	'display' => $addon['config']['honor_lifehk'],
);

$FIELD['lifetimeRankName'] = array(
	'lang_field' => 'highestrank',
	'order' => array( 'risnull', '`players`.`lifetimeHighestRank` DESC' ),
	'order_d' => array( 'risnull', '`players`.`lifetimeHighestRank` ASC' ),
	'value' => 'honor_value',
	'display' => $addon['config']['honor_hrank'],
);

$FIELD['honorpoints'] = array(
	'lang_field' => 'honorpoints',
	'order' => array( '`players`.`honorpoints` DESC' ),
	'order_d' => array( '`players`.`honorpoints` ASC' ),
	'display' => $addon['config']['honor_hp'],
);

$FIELD['arenapoints'] = array(
	'lang_field' => 'arenapoints',
	'order' => array( '`players`.`arenapoints` DESC' ),
	'order_d' => array( '`players`.`arenapoints` ASC' ),
	'display' => $addon['config']['honor_ap'],
);


include_once ($addon['dir'].'inc/memberslist.php');

$memberlist = new memberslist;

$memberlist->prepareData($mainQuery, $FIELD, 'memberslist');

$html_head  = '<script type="text/javascript" src="addons/'.$addon['basename'].'/js/sorttable.js"></script>';
$html_head .= '<link rel="stylesheet" type="text/css" href="addons/'.$addon['basename'].'/default.css" />';

// Start output
$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');
$roster_show_menu = false;

echo "<table>\n  <tr>\n";

if ( $roster_conf['index_hslist'] == 1 )
{
	echo '    <td valign="top">';
	include_once( ROSTER_LIB.'hslist.php');
	echo "    </td>\n";
}

if ( $roster_conf['index_pvplist'] == 1 )
{
	echo '    <td valign="top">';
	include_once( ROSTER_LIB.'pvplist.php');
	echo "    </td>\n";
}

echo "  </tr>\n</table>\n";

echo $memberlist->makeFilterBox();

echo "<br />\n".border('syellow','start')."\n";
echo $memberlist->makeMembersList();
echo border('syellow','end');
