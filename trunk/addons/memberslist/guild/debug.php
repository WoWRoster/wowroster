<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$query = "SELECT `config_name`, `config_value` "
	. "FROM `" . $roster->db->table('config_guild',$addon['basename']) . "` "
	. "WHERE `guild_id` = " . $roster->data['guild_id'] . ";";
$result = $roster->db->query($query);

while( $row = $roster->db->fetch($result,SQL_ASSOC) )
{
	$addon['rules'][$row['config_name']] = $row['config_value'];
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

$always_sort = ' `members`.`level` DESC, `members`.`name` ASC';

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
	'value'   => 'debugNote',
);

$FIELD['officer_note'] = array (
	'lang_field' => 'officer_note',
	'order' => array( 'onisnull','`members`.`note` ASC' ),
	'order_d' => array( 'onisnull','`members`.`note` DESC' ),
	'js_type' => 'ts_string',
	'display' => ( $addon['config']['member_onote'] ? 3 : 0 ),
	'value'   => 'debugNote',
);

include_once ($addon['inc_dir'] . 'memberslist.php');

$memberlist = new memberslist;

$memberlist->prepareData($mainQuery, $always_sort, $FIELD, 'memberslist');

echo $memberlist->makeMembersList('syellow');

echo "<br />\n".scrollbox(aprint($addon,'$addon',true),'Config data','sgray');

function debugNote( $row, $field, $data )
{
	global $addon;

	$rules = $addon['rules']['use_global'] ? $addon['config'] : $addon['rules'];
	if(preg_match($rules['getmain_regex'], $row[$field], $regs))
	{
		$tooltip_h = $regs[$rules['getmain_match']];
		$tooltip = aprint($regs, '', true);
	}
	else
	{
		$tooltip_h = '';
		$tooltip = 'No main match';
	}
	return '<div ' . makeOverlib($tooltip, $tooltip_h) . '>' . $row[$field] . '</div>' . "\n";
}
