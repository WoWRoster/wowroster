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

include( $addon['dir'] . 'inc/function.lib.php' );
$functions = new forum;
include(ROSTER_LIB . 'install.lib.php');
$installer = new Install;

if( isset( $_POST['op'] ) && $_POST['op'] == 'process' )
{
	switch ( $_POST['type'] )
	{
		case 'activate':
			$query = "UPDATE `" . $roster->db->table('topics',$addon['basename']) . "` SET `active` = '1' WHERE `topic_id` = '".$_POST['id']."';";
			$roster->db->query($query);
			break;

		case 'deactivate':
			$query = "UPDATE `" . $roster->db->table('topics',$addon['basename']) . "` SET `active` = '0' WHERE `topic_id` = '".$_POST['id']."';";
			$roster->db->query($query);
			break;
			
		case 'delete':
			processTopic();
			break;
			
		case 'lock':
			$functions->processLock( $_POST['id'] , 1 );
			break;
			
		case 'unlock':
			$functions->processLock( $_POST['id'] , 0 );
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
		'L_ACTIVEU' => ( $rowb['locked'] == 1 ? 'locked' : 'unlocked'),
		'L_ACTIVET'	=> ( $rowb['locked'] == 1 ? $roster->locale->act['lock'] : $roster->locale->act['unlock']),
		'L_ACTIVEOP'=> ( $rowb['locked'] == 1 ? 'unlock' : 'lock'),
		'B_ACTIVEI' => ( $rowb['active'] == 1 ? 'green' : 'red'),
		'B_ACTIVET'	=> ( $rowb['active'] == 1 ? 'Active' : 'Inactive'),
		'B_ACTIVEOP'=> ( $rowb['active'] == 1 ? 'deactivate' : 'activate'),
		'FORUM_ID'	=> $rowb['topic_id'],
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

	function processTopic()
	{
		global $roster, $addon, $installer;
		$msg = '';
		$query2 = 'DELETE FROM `' . $roster->db->table('topics',$addon['basename']) . "` WHERE `topic_id` = '" . $_POST['id'] . "';";
		$query3 = 'DELETE FROM `' . $roster->db->table('posts',$addon['basename']) . "` WHERE `topic_id` = '" . $_POST['id'] . "';";

		$result2 = $roster->db->query($query2);
		$b = $roster->db->affected_rows( );
		$result3 = $roster->db->query($query3);
		$c = $roster->db->affected_rows( );

		if ($result2)
		{
			$msg .= ''.$b.' Topics deleted<br>';
		}
		if ($result3)
		{
			$msg .= ''.$c.' Posts Deleted';
		}
		$installer->setmessages($msg);				
	}

	
