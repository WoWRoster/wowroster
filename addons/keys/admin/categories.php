<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
 */

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if( isset( $_POST['process'] ) && $_POST['process'] == 'process' )
{
	if( substr( $_POST['action'], 0, 4 ) == 'del_' )
	{
		$cat = substr( $_POST['action'], 4, ( $keypos = strpos( $_POST['action'], '_', 4 ) ) - 4 );
		$key = substr( $_POST['action'], $keypos + 1 );

		$query = "DELETE FROM `" . $roster->db->table('categories', 'keys') . "` WHERE `category` = '" . $cat ."' AND `key` = '" . $key . "';";
		$roster->db->query($query);
	}
	elseif( $_POST['action'] == 'add' )
	{
		$query = "INSERT INTO `" . $roster->db->table('categories', 'keys') . "` (`category`,`key`) VALUES ('" . $_POST['category'] . "','" . $_POST['key'] . "');";
		$roster->db->query($query);
	}
}

$roster->tpl->assign_vars(array(
	'L_CATEGORY' => 'Category',
	'L_KEY'      => 'Key',
	'L_DELETE'   => 'Delete',
	'L_ADD'      => 'Add',
	'L_KEY_CATEGORIES' => 'Key Categories'
	)
);

$query = "SELECT DISTINCT key_name "
	. "FROM `" . $roster->db->table('keys', 'keys') . "` "
	. "ORDER BY `key_name` ";

$result = $roster->db->query($query);

while( $row = $roster->db->fetch($result) )
{
	$roster->tpl->assign_block_vars('key_select',array(
		'NAME' => $row['key_name'],
		'VALUE' => $row['key_name'],
		)
	);
}

$query = "SELECT `category`, `key` "
	. "FROM `" . $roster->db->table('categories', 'keys') . "` "
	. "ORDER BY `category`, `key` ";

$result = $roster->db->query($query);

while( $row = $roster->db->fetch($result) )
{
	$roster->tpl->assign_block_vars('categories',array(
		'ROW_CLASS' => $roster->switch_row_class(),
		'KEY' => $row['key'],
		'CATEGORY' => $row['category'],
		)
	);
}

$roster->db->free_result($result);

$roster->tpl->set_handle('categories',$addon['basename'] . '/admin/categories.html');

$body .= $roster->tpl->fetch('categories');


$tab1 = explode('|',$roster->locale->act['admin']['keys_conf']);
$tab2 = explode('|',$roster->locale->act['admin']['keys_cats']);

$menu .= messagebox('
<ul class="tab_menu">
	<li><a href="' . makelink('rostercp-addon-keys') . '" style="cursor:help;"' . makeOverlib($tab1[1],$tab1[0],'',1,'',',WRAP') . '>' . $tab1[0] . '</a></li>
	<li class="selected"><a href="' . makelink('rostercp-addon-keys-categories') . '" style="cursor:help;"' . makeOverlib($tab2[1],$tab2[0],'',1,'',',WRAP') . '>' . $tab2[0] . '</a></li>
</ul>
',$roster->locale->act['roster_config_menu'],'sgray','145px');
