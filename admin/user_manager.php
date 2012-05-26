<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster upload rule config
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
*/

if( !defined('IN_ROSTER') || !defined('IN_ROSTER_ADMIN') )
{
	exit('Detected invalid access to this file!');
}

$start = (isset($_GET['start']) ? $_GET['start'] : 0);

$roster->output['title'] .= $roster->locale->act['pagebar_userman'];


//user_desc
if( isset($_POST['process']) && $_POST['process'] == 'process' )
{
		if (isset($_POST['delete']))
		{
			foreach ($_POST['delete'] as $user => $id)
			{
				$dl_query = "DELETE FROM `" . $roster->db->table('user_members') . "` WHERE `id` = '".$id."';";
				$dl_result = $roster->db->query($dl_query);
			}
		}
	foreach ($_POST as $name => $value)
	{
		$query = $access = '';
		$ad = array();

		if ($name != 'action' && $name != 'process')
		{

			if (isset($value['access']))
			{
				$access = implode(":",$value['access']);
				$a = "`access` = '".$access."' ";
			}
			if (isset($value['active']))
			{
				$b = ", `active` = '".$value['active']."' ";
			}
			$up_query = "UPDATE `" . $roster->db->table('user_members') . "` SET $a $b WHERE `id` = '".$name."';";
			$up_result = $roster->db->query($up_query);
		}
	}
}

// Change scope to guild, and rerun detection to load default
//print_r($roster->auth->GetAccess());
// Get the scope select data
$dm_query = "SELECT * FROM `" . $roster->db->table('user_members') . "` ORDER BY `id` ASC";

$dm_result = $roster->db->query($dm_query);
$x = '';
if( !$dm_result )
{
	die_quietly($roster->db->error(), 'Database error', __FILE__, __LINE__, $dm_query);
}

$c = 1;
while( $row = $roster->db->fetch($dm_result) )
{
	$tooltip = '<table><tr><td>User ID</td><td>'.$row['id'].'</td></tr>';
	$tooltip .= '<tr><td>Username</td><td>'.$row['usr'].'</td></tr>';
	$tooltip .= '<tr><td>Email</td><td>'.$row['email'].'</td></tr>';
	$tooltip .= '<tr><td>Reg IP</td><td>'.$row['regIP'].'</td></tr>';
	$tooltip .= '<tr><td>Access</td><td>'.$row['access'].'</td></tr>';
	$tooltip .= '<tr><td>Name</td><td>'.$row['fname'].'</td><td>'.$row['lname'].'</td></tr>';
	$tooltip .= '<tr><td>Age</td><td>'.$row['age'].'</td></tr>';
	$tooltip .= '<tr><td>City</td><td>'.$row['city'].'</td></tr>';
	$tooltip .= '<tr><td>State</td><td>'.$row['state'].'</td></tr>';
	$tooltip .= '<tr><td>Country</td><td>'.$row['country'].'</td></tr>';
	$tooltip .= '<tr><td>Zone</td><td>'.$row['zone'].'</td></tr>';
	$tooltip .= '<tr><td>Homepage</td><td>'.$row['homepage'].'</td></tr>';
	$tooltip .= '<tr><td>Other Guilds</td><td>'.$row['other_guilds'].'</td></tr>';
	$tooltip .= '<tr><td>Why</td><td>'.$row['why'].'</td></tr>';
	$tooltip .= '<tr><td>About</td><td>'.$row['about'].'</td></tr>';
	$tooltip .= '<tr><td>Notes</td><td>'.$row['notes'].'</td></tr>';
	$tooltip .= '<tr><td>Last Login</td><td>'.$row['last_login'].'</td></tr>';
	$tooltip .= '<tr><td>Joined</td><td>'.$row['date_joined'].'</td></tr>';
	$tooltip .= '<tr><td>Is Member</td><td>'.$row['is_member'].'</td></tr>';
	$tooltip .= '<tr><td>Active</td><td>'.$row['active'].'</td></tr>';
	$tooltip .= '<tr><td>Online</td><td>'.$row['online'].'</td></tr>';
	$tooltip .= '<tr><td>Last online</td><td>'.$row['user_lastvisit'].'</td></tr></table>';

	$roster->tpl->assign_block_vars('user', array(
		'ROW_CLASS' => $roster->switch_row_class(),
		'ID'        => $row['id'],
		'IDC'       => $c++,
		'IDX'       => $c++,
		'ACTIVE'    => (bool)$row['active'],
		'NAME'      => $row['usr'],
		'TOOLTIP'   => $tooltip,
		'EMAIL'     => $row['email'],
		'ACCESS'    => $roster->auth->makeAccess(array('name' => ''.$row['id'].'[access][]', 'value' => $row['access']))
		)
	);
}

$roster->db->free_result($dm_result);
$roster->tpl->assign_vars(array(
  'L_USER_MANAGER' => $roster->locale->act['admin']['user_desc'],
/*
	'ROSTERCP_TITLE' => (!empty($rostercp_title) ? $rostercp_title : $roster->locale->act['roster_cp_ab']),
	'HEADER'         => $header,
	'MENU'           => $menu,
	'BODY'           => $body,
	'PAGE_INFO'      => $roster->locale->act['roster_cp'],
	'FOOTER' => $footer,
*/
	)
);

$roster->tpl->set_filenames(array('body' => 'admin/user_manager.html'));
$body = $roster->tpl->fetch('body');
