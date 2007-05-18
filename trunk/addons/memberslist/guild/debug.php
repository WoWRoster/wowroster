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

//---[ Check for Guild Info ]------------
if( empty($roster->data) )
{
	die_quietly( $roster->locale->act['nodata'] );
}

$mainQuery =
	'SELECT '.
	'`members`.`member_id`, '.
	'`members`.`name`, '.
	'`members`.`note`, '.
	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`officer_note`, '.
	"IF( `members`.`officer_note` IS NULL OR `members`.`officer_note` = '', 1, 0 ) AS 'onisnull', ".

	'`alts`.`main_id`, '.
	'`alts`.`alt_type`, '.

	'`mains`.`name` AS main_name '.

	'FROM `'.$roster->db->table('members').'` AS members '.
	'LEFT JOIN `'.$roster->db->table('alts',$addon['basename']).'` AS alts ON `members`.`member_id` = `alts`.`member_id` '.
	'LEFT JOIN `'.$roster->db->table('members').'` AS mains ON `alts`.`main_id` = `mains`.`member_id` '.
	'WHERE `members`.`guild_id` = "'.$roster->data['guild_id'].'" '.
	'ORDER BY IF(`members`.`member_id` = `alts`.`member_id`,1,0), ';

$FIELD['name'] = array (
	'lang_field' => 'name',
	'order'    => array( '`members`.`name` ASC' ),
	'order_d'    => array( '`members`.`name` DESC' ),
	'js_type' => 'ts_string',
	'display' => 3,
);

$FIELD['main_name'] = array (
	'lang_field' => 'main_name',
	'order'    => array( '`mains`.`name` ASC' ),
	'order_d'    => array( '`mains`.`name` DESC' ),
	'js_type' => 'ts_string',
	'display' => 3,
);

$FIELD['alt_type'] = array (
	'lang_field' => 'alt_type',
	'order'    => array('`alts`.`alt_type` ASC' ),
	'order_d'    => array('`alts`.`alt_type` DESC' ),
	'js_type' => 'ts_number',
	'display' => 3,
);

$FIELD['note'] = array (
	'lang_field' => 'note',
	'order' => array( 'nisnull','`members`.`note` ASC' ),
	'order_d' => array( 'nisnull','`members`.`note` DESC' ),
	'js_type' => 'ts_string',
	'display' => 3,
);

$FIELD['officer_note'] = array (
	'lang_field' => 'officer_note',
	'order' => array( 'onisnull','`members`.`note` ASC' ),
	'order_d' => array( 'onisnull','`members`.`note` DESC' ),
	'js_type' => 'ts_string',
	'display' => 3,
);

include_once ($addon['dir'].'inc/memberslist.php');

$memberlist = new memberslist;

$memberlist->prepareData($mainQuery, $FIELD, 'memberslist');

$roster->output['html_head'] .= '<script type="text/javascript" src="addons/'.$addon['basename'].'/js/sorttable.js"></script>';

$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');
$roster->output['show_menu'] = false;

echo $memberlist->makeFilterBox();

echo "<br />\n".border('syellow','start')."\n";
echo $memberlist->makeMembersList();
echo border('syellow','end');

echo "<br />\n".scrollbox('<pre>'.print_r($addon,true).'</pre>','','sgray');

