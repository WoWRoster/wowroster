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
 * @package    MembersList
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
	'WHERE `members`.`server` = "'.$roster->data['server'].'" '.
	'ORDER BY IF(`members`.`member_id` = `alts`.`member_id`,1,0), ';

$always_sort = ' `members`.`level` DESC, `members`.`name` ASC';

$FIELD['name'] = array(
	'lang_field' => 'name',
	'order'    => array( '`members`.`name` ASC' ),
	'order_d'    => array( '`members`.`name` DESC' ),
	'value' => array($memberlist,'name_value'),
	'js_type' => 'ts_string',
	'display' => 3,
);

$FIELD['class'] = array(
	'lang_field' => 'class',
	'order'    => array( '`members`.`class` ASC' ),
	'order_d'    => array( '`members`.`class` DESC' ),
	'value' => array($memberlist,'class_value'),
	'js_type' => 'ts_string',
	'display' => $addon['config']['honor_class'],
);

$FIELD['level'] = array(
	'lang_field' => 'level',
	'order_d'    => array( '`members`.`level` ASC' ),
	'value' => array($memberlist,'level_value'),
	'js_type' => 'ts_number',
	'display' => $addon['config']['honor_level'],
);

$FIELD['sessionHK'] = array(
	'lang_field' => 'todayhk',
	'order' => array( '`players`.`sessionHK` DESC' ),
	'order_d' => array( '`players`.`sessionHK` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['honor_thk'],
);

$FIELD['sessionCP'] = array(
	'lang_field' => 'todaycp',
	'order' => array( '`players`.`sessionCP` DESC' ),
	'order_d' => array( '`players`.`sessionCP` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['honor_tcp'],
);

$FIELD['yesterdayHK'] = array(
	'lang_field' => 'yesthk',
	'order' => array( '`players`.`yesterdayHK` DESC' ),
	'order_d' => array( '`players`.`yesterdayHK` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['honor_yhk'],
);

$FIELD['yesterdayContribution'] = array(
	'lang_field' => 'yestcp',
	'order' => array( '`players`.`yesterdayContribution` DESC' ),
	'order_d' => array( '`players`.`yesterdayContribution` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['honor_ycp'],
);

$FIELD['lifetimeHK'] = array(
	'lang_field' => 'lifehk',
	'order' => array( '`players`.`lifetimeHK` DESC' ),
	'order_d' => array( '`players`.`lifetimeHK` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['honor_lifehk'],
);

$FIELD['lifetimeRankName'] = array(
	'lang_field' => 'highestrank',
	'order' => array( 'risnull', '`players`.`lifetimeHighestRank` DESC' ),
	'order_d' => array( 'risnull', '`players`.`lifetimeHighestRank` ASC' ),
	'value' => array($memberlist,'honor_value'),
	'js_type' => 'ts_number',
	'display' => $addon['config']['honor_hrank'],
);

$FIELD['honorpoints'] = array(
	'lang_field' => 'honorpoints',
	'order' => array( '`players`.`honorpoints` DESC' ),
	'order_d' => array( '`players`.`honorpoints` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['honor_hp'],
);

$FIELD['arenapoints'] = array(
	'lang_field' => 'arenapoints',
	'order' => array( '`players`.`arenapoints` DESC' ),
	'order_d' => array( '`players`.`arenapoints` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['honor_ap'],
);

$memberlist->prepareData($mainQuery, $always_sort, $FIELD, 'memberslist');

$roster->output['html_head'] .= '<script type="text/javascript" src="addons/'.$addon['basename'].'/js/sorttable.js"></script>';


// Start output
if( $addon['config']['honor_update_inst'] )
{
	print '            <a href="' . makelink('#update') . '"><span style="font-size:20px;">'.$roster->locale->act['update_link'].'</span></a><br /><br />';
}


$roster_menu = new RosterMenu;
print $roster_menu->makeMenu($roster->output['show_menu']);
$roster->output['show_menu'] = false;



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
