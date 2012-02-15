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
			$query = "UPDATE `" . $roster->db->table('banners',$addon['basename']) . "` SET `b_active` = '1' WHERE `id` = '".$_POST['id']."';";
			$roster->db->query($query);
			break;

		case 'deactivate':
			$query = "UPDATE `" . $roster->db->table('banners',$addon['basename']) . "` SET `b_active` = '0' WHERE `id` = '".$_POST['id']."';";
			$roster->db->query($query);
			break;
		case 'delete':
		
			$dir = $addon['image_path'];
			$filename = $_POST['image'];
			$delete = $_POST['id'];
			if( file_exists($dir.$filename) )
			{
				if( unlink($dir.$filename))
				{
					$roster->set_message( '<span class="green">'.$filename.'</span>: <span class="red">Deleted</span>' );
					$roster->db->query("DELETE FROM `".$roster->db->table('banners',$addon['basename'])."` WHERE id='".$delete."' ");
				}
				else
				{
					$roster->set_message( '<span class="red">'.$filename.': Could not be deleted</span>' );
				}
			}
			else
			{
				$roster->set_message( '<span class="red">File not found!</span><br>--['.$dir.$filename.']--<br>Removing SQL entry' );
				$roster->db->query("DELETE FROM `".$roster->db->table('banners',$addon['basename'])."` WHERE id='".$delete."' ");
			}
			
			break;

		default:
		break;
	}
}

$queryb = "SELECT `topics`.*, `posts`.`poster_id`, `posts`.`post_subject` as t_title,"
		. "COUNT(`posts`.`post_id`) as topic_count "
		. "FROM `" . $roster->db->table('topics',$addon['basename']) . "` topics "
		. "LEFT JOIN `" . $roster->db->table('posts',$addon['basename']) . "` posts USING (`topic_id`) "
		//. "WHERE `topics`.`forum_id` = '".$forum."' "
		. "GROUP BY `topics`.`topic_id` "
		. "ORDER BY `topics`.`topic_id` DESC; ";
		
		$resultsb = $roster->db->query($queryb);
		$num=1;
		$total = $roster->db->num_rows($resultsb);
		
while( $rowb = $roster->db->fetch($resultsb) )
{
	$roster->tpl->assign_block_vars('topic',array(
		'ROW_CLASS' => $roster->switch_row_class(),
		'T_NAME'	=> $rowb['title'],
		'T_POSTER'	=> $rowb['author'],
		'B_ACTIVEI' => ( $rowb['active'] == 1 ? 'green' : 'red'),
		'B_ACTIVET'	=> ( $rowb['active'] == 1 ? 'Active' : 'Inactive'),
		'B_ACTIVEOP'=> ( $rowb['active'] == 1 ? 'deactivate' : 'activate'),
		'FORUM_ID'	=> $rowb['forum_id'],
		'T_POSTS'	=> $rowb['topic_count']
		)
	);
}

$roster->tpl->set_handle('topic',$addon['basename'] . '/admin_topic.html');

$body .= $roster->tpl->fetch('topic');


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