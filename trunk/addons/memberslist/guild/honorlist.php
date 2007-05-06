<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: pvp3.php 897 2007-05-06 00:35:11Z Zanix $
 * @link       http://www.wowroster.net
*/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

define('IN_SORTMEMBER',true);

include_once ($addon['dir'] . 'inc/memberslist.php');

$memberlist = new memberslist;

$mainQuery =
	'SELECT '.
	'`members`.`member_id`, '.
	'`members`.`name`, '.
	'`members`.`class`, '.
	'`members`.`level`, '.
	'`members`.`zone`, '.
	"(UNIX_TIMESTAMP( `members`.`last_online`)*1000+".($roster->config['localtimeoffset']*3600000).") AS 'last_online_stamp', ".
	"DATE_FORMAT(  DATE_ADD(`members`.`last_online`, INTERVAL ".$roster->config['localtimeoffset']." HOUR ), '".$roster->locale->act['timeformat']."' ) AS 'last_online', ".
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

	'FROM `'.$roster->db->table('members').'` AS members '.
	'INNER JOIN `'.$roster->db->table('players').'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	'LEFT JOIN `'.$roster->db->table('alts',$addon['basename']).'` AS alts ON `members`.`member_id` = `alts`.`member_id` '.
	'WHERE `members`.`guild_id` = "'.$roster->data['guild_id'].'" '.
	'ORDER BY IF(`members`.`member_id` = `alts`.`member_id`,1,0), ';


$FIELD['name'] = array(
	'lang_field' => 'name',
	'order'    => array( '`members`.`name` ASC' ),
	'order_d'    => array( '`members`.`name` DESC' ),
	'value' => array($memberlist,'name_value'),
	'display' => 3,
);

$FIELD['class'] = array(
	'lang_field' => 'class',
	'order'    => array( '`members`.`class` ASC' ),
	'order_d'    => array( '`members`.`class` DESC' ),
	'value' => array($memberlist,'class_value'),
	'display' => $addon['config']['honor_class'],
);

$FIELD['level'] = array(
	'lang_field' => 'level',
	'order_d'    => array( '`members`.`level` ASC' ),
	'value' => array($memberlist,'level_value'),
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
	'value' => array($memberlist,'honor_value'),
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

$memberlist->prepareData($mainQuery, $FIELD, 'memberslist');

$roster->output['html_head']  = '<script type="text/javascript" src="addons/'.$addon['basename'].'/js/sorttable.js"></script>';


// Start output
if( $addon['config']['honor_update_inst'] )
{
	print '            <a href="#update"><font size="4">'.$roster->locale->act['update_link'].'</font></a><br /><br />';
}


if ( $addon['config']['honor_motd'] == 1 )
{
	print $memberlist->makeMotd();
}

$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');
$roster->output['show_menu'] = false;

if( $addon['config']['honor_hslist'] == 1 || $addon['config']['honor_pvplist'] == 1 )
{
	echo "<table>\n  <tr>\n";

	if ( $addon['config']['honor_hslist'] == 1 )
	{
		echo '    <td valign="top">';
		include_once( ROSTER_LIB.'hslist.php');
		echo "    </td>\n";
	}

	if ( $addon['config']['honor_pvplist'] == 1 && active_addon('pvplog') )
	{
		echo '    <td valign="top">';
		include_once( ROSTER_ADDONS.'pvplog'.DIR_SEP.'inc'.DIR_SEP.'pvplist.php');
		echo "    </td>\n";
	}

	echo "  </tr>\n</table>\n";
}

echo $memberlist->makeFilterBox();

echo $memberlist->makeToolBar('horizontal');

echo "<br />\n".border('syellow','start')."\n";
echo $memberlist->makeMembersList();
echo border('syellow','end');

// Print the update instructions
if( $addon['config']['honor_update_inst'] )
{
	print "<br />\n\n<a name=\"update\"></a>\n";

	echo border('sgray','start',$roster->locale->act['update_instructions']);
	echo '<div align="left" style="font-size:10px;background-color:#1F1E1D;">'.sprintf($roster->locale->act['update_instruct'], $roster->config['uploadapp'], $roster->locale->act['index_text_uniloader'], $roster->config['profiler'], makelink('update'), $roster->locale->act['lualocation']);
	echo '</div>'.border('sgray','end');
}
