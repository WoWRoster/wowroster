<?php
	
if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

include( $addon['dir'] . 'inc/function.lib.php' );
$functions = new forum;
include(ROSTER_LIB . 'install.lib.php');
$installer = new Install;

if( isset( $_POST['type'] ) && !empty($_POST['type']) )
{
$op = ( isset($_POST['op']) ? $_POST['op'] : '' );
$id = ( isset($_POST['id']) ? $_POST['id'] : '' );

	switch( $_POST['type'] )
	{
		case 'deactivate':
			processActive($id,0);
			break;

		case 'activate':
			processActive($id,1);
			break;

		case 'unlock':
			processLock($id,0);
			break;

		case 'lock':
			processLock($id,1);
			break;
			
		case 'delete':
			deleteForum();
			break;

		case 'add':
			echo 'add forum';//processPage();
			addForum();
			break;
		
		case 'update':
			echo 'add forum';//processPage();
			updateForum();
			break;

		case 'access':
			processAccess();
			break;

		default:
			break;
	}
}
$parent = getDbData( ($roster->db->table('forums',$addon['basename'])),'`forum_id`, `title` ', '`parent_id` = "0" ', '`forum_id`' );

$roster->tpl->assign_var('PARENT', createList($parent,'','parent',1,'' ));
$forums = $functions->getForumsa();

	foreach($forums as $id =>$forum)
	{
		$roster->tpl->assign_block_vars('forum', array(
					'FORUM_ID' 	=> $forum['forumid'],
					'FORUM_URL'	=> makelink('guild-'.$addon['basename'].'-forum&amp;id=' . $forum['forumid']),
					'TITLE'		=> $forum['title'],
					'POSTS'		=> $forum['posts'],
					'ORDER'		=> $forum['order'],
					'POSTER'	=> $forum['t_poster'],
					'U_EDIT'	=> makelink('rostercp-addon-forum-forumedit&amp;id=' .$forum['forumid']),
					'P_TITLE'	=> $forum['t_title'],
					'PARENT'	=> createList($parent,$forum['parent_id'],'parent',1,'' ),
					'L_ACTIVEU' => ( $forum['locked'] == 1 ? 'locked' : 'unlocked'),
					'L_ACTIVET'	=> ( $forum['locked'] == 1 ? $roster->locale->act['lock'] : $roster->locale->act['unlock']),
					'L_ACTIVEOP'=> ( $forum['locked'] == 1 ? 'unlock' : 'lock'),
					'B_ACTIVEI' => ( $forum['active'] == 1 ? 'green' : 'red'),
					'B_ACTIVET'	=> ( $forum['active'] == 1 ? 'Active' : 'Inactive'),
					'B_ACTIVEOP'=> ( $forum['active'] == 1 ? 'deactivate' : 'activate'),
					'ACCESS'	=> ( isset($forum['access']) ? $roster->auth->rosterAccess(array('name' => 'access', 'value' => $forum['access'])) : false ),
					'DESC'		=> $forum['desc']
				));
	}

$roster->tpl->set_handle('forum',$addon['basename'] . '/admin_forum.html');

$body .= $roster->tpl->fetch('forum');

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

function addForum()
{
	global $roster, $addon,$installer;
	$parent = 0;
	if (isset($_POST['parent']))
	{
		$parent = $_POST['parent'];
	}
//`forum_id`  `parent_id`  `title`  `access`  `order_id`  `desc`  `misc`  `active`  `locked`  `icon`
	$query = "INSERT INTO `" . $roster->db->table('forums',$addon['basename']) . "` VALUES (
	NULL,
	'" . $parent . "',
	'" . $_POST['title'] . "',
	'',
	'',
	'" . $_POST['order'] . "',
	'" . $_POST['desc'] . "',
	'',
	'',
	'0',
	''
	);";
	$result = $roster->db->query($query);
	if( !$result )
	{
		$installer->seterrors('DB error while creating new addon record. <br /> MySQL said:' . $roster->db->error(),$roster->locale->act['installer_error']);
	}
	else
	{
		$installer->setmessages('forum "'.$_POST['title'].'" created');
	}
}
function updateForum()
{
	global $roster, $addon,$installer;
	$parent = 0;
	if (isset($_POST['parent']))
	{
		$parent = $_POST['parent'];
	}
	$query = "UPDATE `" . $roster->db->table('forums',$addon['basename']) . "` SET
	`title` = '" . $_POST['title'] . "',
	`parent_id` = '" . $parent . "',
	`desc` = '" . $_POST['desc'] . "',
	`order_id` = '" . $_POST['order_id'] . "' WHERE `forum_id` = '".$_GET['id']."';";
	$result = $roster->db->query($query);
	if( !$result )
	{
		$installer->seterrors('DB error while creating new addon record. <br /> MySQL said:' . $roster->db->error(),$roster->locale->act['installer_error']);
	}
	else
	{
		$installer->setmessages('Forum "'.$_POST['title'].'" Updated');
	}
}
function processLock( $id , $mode )
	{
		global $roster, $addon, $installer;

		$query = "UPDATE `" . $roster->db->table('forums',$addon['basename']) . "` SET `locked` = '$mode' WHERE `forum_id` = '".$id."';";
		$result = $roster->db->query($query);
		if( !$result )
		{
			$installer->seterrors('Database Error: ' . $roster->db->error() . '<br />SQL: ' . $query);
		}
		else
		{
			$installer->setmessages(sprintf($roster->locale->act['installer_activate_' . $mode] ,$addon['basename']));
		}
	}
function deleteForum()
{
	global $roster, $addon, $installer;
	$msg = '';
	$query1 = 'DELETE FROM `' . $roster->db->table('forums',$addon['basename']) . "` WHERE `forum_id` = '" . $_POST['id'] . "';";
	$query2 = 'DELETE FROM `' . $roster->db->table('topics',$addon['basename']) . "` WHERE `forum_id` = '" . $_POST['id'] . "';";
	$query3 = 'DELETE FROM `' . $roster->db->table('posts',$addon['basename']) . "` WHERE `forum_id` = '" . $_POST['id'] . "';";
	$result1 = $roster->db->query($query1);
	$a = $roster->db->affected_rows( );
	$result2 = $roster->db->query($query2);
	$b = $roster->db->affected_rows( );
	$result3 = $roster->db->query($query3);
	$c = $roster->db->affected_rows( );
	if ($result1)
	{
		$msg .= ''.$a.' Forum deleted<br>';
	}
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
function processAccess()
{
	global $addon, $roster;

	$access = implode(":",$_POST['config_access']);
	$id = (int)$_POST['id'];
	$query = "UPDATE `" . $roster->db->table('forums',$addon['basename']) . "` SET `access` = '$access' WHERE `forum_id` = '$id';";

	if( !$roster->db->query($query) )
	{
		die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	}
}

function processActive( $id , $mode )
{
	global $roster, $addon, $installer;

	$query = "UPDATE `" . $roster->db->table('forums',$addon['basename']) . "` SET `active` = '$mode' WHERE `forum_id` = '$id' LIMIT 1;";
	$result = $roster->db->query($query);
	if( !$result )
	{
		$installer->seterrors('Database Error: ' . $roster->db->error() . '<br />SQL: ' . $query);
	}
	else
	{
		$installer->setmessages(sprintf($roster->locale->act['installer_activate_' . $mode] ,$addon['basename']));
	}
}
$errorstringout = $installer->geterrors();
$messagestringout = $installer->getmessages();
$sqlstringout = $installer->getsql();

// print the error messages
if( !empty($errorstringout) )
{
	$roster->set_message($errorstringout, $roster->locale->act['installer_error'], 'error');
}

// Print the update messages
if( !empty($messagestringout) )
{
	$roster->set_message($messagestringout, $roster->locale->act['installer_log']);
}


function createList( $values , $selected , $id , $type=0 , $param='' )
	{
		if( $selected != '' )
		{
			$select_one = true;
		}

		$option_list = "\n\t<select id=\"{$id}\" name=\"{$id}\" $param>\n\t\t<option value=\"\" style=\"color:grey;\">--None--</option>\n";

		foreach( $values as $data )
		{
			if( $selected == $data['forum_id'] && $select_one )
			{
				$option_list .= "\t\t\t<option value=\"".$data['forum_id']."\" selected=\"selected\">".$data['title']."</option>\n";
				$select_one = false;
			}
			else
			{
				$option_list .= "\t\t\t<option value=\"".$data['forum_id']."\">".$data['title']."</option>\n";
			}
		}
		$option_list .= "\t</select>";

		return $option_list;
	}
	
	function getDbData( $table , $field , $where='', $order='' )
	{
		global $roster;

		if( !empty($table) )
		{
			if( !empty($where) )
			{
				$where = ' WHERE ' . $where;
			}

			if( !empty($order) )
			{
				$order = ' ORDER BY ' . $order;
			}

			if( empty($field) )
			{
				$field = '*';
			}

			// SQL String
			$sql_str = "SELECT $field FROM `$table`$where$order;";

			$result = $roster->db->query($sql_str);

			if ( $result )
			{
				
					$data = array();
					for( $i=0; $i<$roster->db->num_rows(); $i++)
					{
						$row = $roster->db->fetch($result, SQL_ASSOC);
						$data[] = $row;
					}
					return $data;
				
			}
		}
	}
	
	