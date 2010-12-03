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

if( isset( $_POST['process'] ) && $_POST['process'] == 'process' )
{
	if( substr( $_POST['action'], 0, 4 ) == 'del_' )
	{
		$cat = substr( $_POST['action'], 4, ( $keypos = strpos( $_POST['action'], '_', 4 ) ) - 4 );
		$key = substr( $_POST['action'], $keypos + 1 );

		$query = "DELETE FROM `" . $roster->db->table('category_key', 'keys') . "` WHERE `category` = '" . $cat ."' AND `key` = '" . $key . "';";
		$roster->db->query($query);
	}
	elseif( $_POST['action'] == 'add' )
	{
		$query = "SELECT COUNT(*) FROM `" . $roster->db->table('category_key', 'keys') . "` WHERE `category` = '" . $_POST['keycategory'] . "' AND `key` = '" . $_POST['key'] . "';";
		if( 0 == $roster->db->query_first($query) )
		{
			$query = "INSERT INTO `" . $roster->db->table('category_key', 'keys') . "` (`category`,`key`) VALUES ('" . $_POST['keycategory'] . "','" . $_POST['key'] . "');";
			$roster->db->query($query);
		}
	}

	if( substr( $_POST['action'], 0, 7 ) == 'delcat_' )
	{
		$cat = substr( $_POST['action'], 7 );

		$query = "DELETE FROM `" . $roster->db->table('category_key', 'keys') . "` WHERE `category` = '" . $cat ."';";
		$roster->db->query($query);
		$query = "DELETE FROM `" . $roster->db->table('category', 'keys') . "` WHERE `category` = '" . $cat ."';";
		$roster->db->query($query);
		$query = "DELETE FROM `" . $roster->db->table('menu_button') . "` WHERE `addon_id` = " . $addon['addon_id'] . " AND `title` = '" . $cat . "';";
		$roster->db->query($query);
	}
	elseif( $_POST['action'] == 'addcat' )
	{
		$query = "SELECT COUNT(*) FROM `" . $roster->db->table('category', 'keys') . "` WHERE `category` = '" . $_POST['category'] . "';";
		if( 0 == $roster->db->query_first($query) )
		{
			$query = "INSERT INTO `" . $roster->db->table('category', 'keys') . "` (`category`) VALUES ('" . $_POST['category'] . "');";
			$roster->db->query($query);
			$query = "INSERT INTO `" . $roster->db->table('menu_button') . "` VALUES (NULL,'" . $addon['addon_id'] . "','" . $_POST['category'] . "','keypane','guild-" . $addon['basename'] . '-' . $_POST['category'] . "','" . $addon['icon'] . "');";
			$roster->db->query($query);

			// Update the menu config to display the new button automatically
			$query = "UPDATE `" . $roster->db->table('menu') . "` SET `config` = CONCAT(`config`,':','b',LAST_INSERT_ID()) WHERE `section` = 'keypane' LIMIT 1;";
			$roster->db->query($query);

		}
	}
}

$roster->tpl->assign_vars(array(
	'L_CATEGORY' => $roster->locale->act['category'],
	'L_KEY'      => $roster->locale->act['key'],
	'L_DELETE'   => $roster->locale->act['delete'],
	'L_ADD'      => $roster->locale->act['add'],
	'L_KEY_CATEGORIES' => $roster->locale->act['key_categories'],
	'L_KEY_CATEGORY_ASSIGN' => $roster->locale->act['key_category_assignment']
	)
);

$query = "SELECT DISTINCT `key_name` "
	. "FROM `" . $roster->db->table('keys', 'keys') . "` "
	. "ORDER BY `key_name`;";

$result = $roster->db->query($query);

while( $row = $roster->db->fetch($result) )
{
	$roster->tpl->assign_block_vars('key_select',array(
		'NAME' => $row['key_name'],
		'VALUE' => $row['key_name'],
		)
	);
}

$query = "SELECT `category` "
	. "FROM `" . $roster->db->table('category', 'keys') . "` "
	. "ORDER BY `category`;";

$result = $roster->db->query($query);

while( $row = $roster->db->fetch($result) )
{
	$roster->tpl->assign_block_vars('category_select',array(
		'ROW_CLASS' => $roster->switch_row_class(),
		'NAME' => $row['category'],
		'VALUE' => $row['category'],
		)
	);
}

$query = "SELECT `category`, `key` "
	. "FROM `" . $roster->db->table('category_key', 'keys') . "` "
	. "ORDER BY `category`, `key`;";

$result = $roster->db->query($query);

while( $row = $roster->db->fetch($result) )
{
	$roster->tpl->assign_block_vars('category_key',array(
		'ROW_CLASS' => $roster->switch_row_class(),
		'KEY' => $row['key'],
		'CATEGORY' => $row['category'],
		)
	);
}

$roster->db->free_result($result);

$roster->tpl->set_handle('categories',$addon['basename'] . '/admin/categories.html');

$body .= $roster->tpl->fetch('categories');



/**
 * Make our menu from the config api
 */
// ----[ Set the tablename and create the config class ]----
include(ROSTER_LIB . 'config.lib.php');
$config = new roster_config( $roster->db->table('addon_config'), '`addon_id` = "' . $addon['addon_id'] . '"' );

// ----[ Get configuration data ]---------------------------
$config->getConfigData();

// ----[ Build the page items using lib functions ]---------
$menu .= $config->buildConfigMenu('rostercp-addon-' . $addon['basename']);
