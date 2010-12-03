<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
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
	'LEFT JOIN `'.$roster->db->table('members').'` AS mains ON `alts`.`main_id` = `mains`.`member_id` ';
$where[] = '`members`.`guild_id` = "'.$roster->data['guild_id'].'"';
$order_first[] = 'IF(`members`.`member_id` = `alts`.`member_id`,1,0)';
$order_last[] = '`members`.`level` DESC';
$order_last[] = '`members`.`name` ASC';

$FIELD['name'] = array (
	'lang_field' => 'name',
	'filt_field' => '`members`.`name`',
	'order'      => array( '`members`.`name` ASC' ),
	'order_d'    => array( '`members`.`name` DESC' ),
	'display'    => 3,
);

$FIELD['main_name'] = array (
	'lang_field' => 'main_name',
	'filt_field' => '`mains`.`name`',
	'order'      => array( '`mains`.`name` ASC' ),
	'order_d'    => array( '`mains`.`name` DESC' ),
	'display'    => 3,
);

$FIELD['alt_type'] = array (
	'lang_field' => 'alt_type',
	'order'      => array('`alts`.`alt_type` ASC' ),
	'order_d'    => array('`alts`.`alt_type` DESC' ),
	'display'    => 3,
);

$FIELD['note'] = array (
	'lang_field' => 'note',
	'filt_field' => '`members`.`note`',
	'order'      => array( 'nisnull','`members`.`note` ASC' ),
	'order_d'    => array( 'nisnull','`members`.`note` DESC' ),
	'value'      => 'debugNote',
	'display'    => 3,
);

$FIELD['officer_note'] = array (
	'lang_field' => 'officer_note',
	'filt_field' => '`members`.`officer_note`',
	'order'      => array( 'onisnull','`members`.`note` ASC' ),
	'order_d'    => array( 'onisnull','`members`.`note` DESC' ),
	'value'      => 'debugNote',
	'display'    => ( $addon['config']['member_onote'] ? 3 : 0 ),
);

include_once ($addon['inc_dir'] . 'memberslist.php');

$memberlist = new memberslist;

$memberlist->prepareData($mainQuery, $where, null, $order_first, $order_last, $FIELD, 'memberslist');

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
