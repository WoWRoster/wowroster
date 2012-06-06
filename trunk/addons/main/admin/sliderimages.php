<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /admin/index.php
 *
 * @link http://www.wowroster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @author Joshua Clark
 * @version $Id$
 * @copyright 2005-2011 Joshua Clark
 * @package SigGen
 * @filesource
 */

// Bad monkey! You can view this directly. And you are stupid for trying. HA HA, take that!
if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}


if( isset( $_POST['op'] ) && $_POST['op'] == 'process' )
{
	switch ( $_POST['type'] )
	{
		case 'activate':
			$query = "UPDATE `" . $roster->db->table('slider',$addon['basename']) . "` SET `b_active` = '1' WHERE `id` = '".$_POST['id']."';";
			$roster->db->query($query);
			break;

		case 'deactivate':
			$query = "UPDATE `" . $roster->db->table('slider',$addon['basename']) . "` SET `b_active` = '0' WHERE `id` = '".$_POST['id']."';";
			$roster->db->query($query);
			break;

		case 'delete':

			$dir = $addon['dir'];
			$filename = $_POST['image'];
			$delete = $_POST['id'];
			if( file_exists($dir.$filename) )
			{
				if( unlink($dir.$filename))
				{
					unlink($dir.'thumb-'.$filename);
					unlink($dir.'slider-'.$filename);
					
					$roster->set_message( '"'.$filename.' & thumb-'.$filename.' & slider-'.$filename.'": Deleted' );
					$roster->db->query("DELETE FROM `".$roster->db->table('slider',$addon['basename'])."` WHERE id='".$delete."' ");
				}
				else
				{
					$roster->set_message( $filename.': Could not be deleted' );
				}
			}
			else
			{
				$roster->set_message( 'File not found! ['. $dir . $filename .'] Removing SQL entry' );
				$roster->db->query("DELETE FROM `".$roster->db->table('slider',$addon['basename'])."` WHERE id='".$delete."' ");
			}

			break;

		default:
		break;
	}
}

$query = "SELECT * FROM `" . $roster->db->table('slider',$addon['basename']) . "` "
	. "ORDER BY `id` ASC;";

$result = $roster->db->query($query);

while( $row = $roster->db->fetch($result) )
{
	$roster->tpl->assign_block_vars('slider_row',array(
		'ROW_CLASS'  => $roster->switch_row_class(),
		'B_TITLE'    => $row['b_title'],
		'B_DESC'     => $row['b_desc'],
		'B_ACTIVE'   => $row['b_active'],
		'B_ID'       => $row['id'],
		'B_IMG'      => $row['b_image'],
		'B_ACTIVEI'  => ( $row['b_active'] == 1 ? 'green' : 'yellow'),
		'B_ACTIVET'  => ( $row['b_active'] == 1 ? $roster->locale->act['active'] : $roster->locale->act['inactive']),
		'B_ACTIVEOP' => ( $row['b_active'] == 1 ? 'deactivate' : 'activate'),
		'B_IMAGE'    => $addon['url_path'] .'images/thumb-'. $row['b_image'],
		)
	);
}

$roster->tpl->set_handle('slider',$addon['basename'] . '/admin/sliderimages.html');

$body .= $roster->tpl->fetch('slider');


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
