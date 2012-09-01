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
	//echo '<pre>';print_r($_POST);echo '</pre>';
		if (isset($_POST['delete']))
		{
			foreach ($_POST['delete'] as $user => $id)
			{
				$dl_query = "DELETE FROM `" . $roster->db->table('user_members') . "` WHERE `id` = '".$id."';";
				$dl_result = $roster->db->query($dl_query);
				$dla_query = "DELETE FROM `" . $roster->db->table('user_link', 'user') . "` WHERE `uid` = '".$id."';";
				$dla_result = $roster->db->query($dla_query);
			}
		}
	foreach ($_POST as $name => $value)
	{
		$query = $access = '';
		$ad = array();

		if ($name != 'action' && $name != 'process')
		{

			$name = substr($name, 7);
			$a=$b='';
			if (isset($value['access']))
			{
				$access = implode(":",$value['access']);
				$a = "`access` = '".$access."' ";
			}
			if (isset($value['active']))
			{
				$b = ", `active` = '".$value['active']."' ";
			}
			if ($name != '')
			{
				$up_query = "UPDATE `" . $roster->db->table('user_members') . "` SET $a $b WHERE `id` = '".$name."';";
				$up_result = $roster->db->query($up_query);
			}
		}
	}
}

// Change scope to guild, and rerun detection to load default
//print_r($roster->auth->rosterAccess());
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
	$tooltip = array();
	$tooltip[] = "User ID\t". $row['id'];
	$tooltip[] = "Username\t". $row['usr'];
	$tooltip[] = "Email\t". $row['email'];
	$tooltip[] = "Reg IP\t". $row['regIP'];
	$tooltip[] = "Access\t". $row['access'];
	$tooltip[] = "Name\t". $row['fname'] .' '. $row['lname'];
	$tooltip[] = "Age\t". $row['age'];
	$tooltip[] = "City\t". $row['city'];
	$tooltip[] = "State\t". $row['state'];
	$tooltip[] = "Country\t". $row['country'];
	$tooltip[] = "Zone\t". $row['zone'];
	$tooltip[] = "Homepage\t". $row['homepage'];
	$tooltip[] = "Other Guilds\t". $row['other_guilds'];
	$tooltip[] = "Why\t". $row['why'];
	$tooltip[] = "About\t". $row['about'];
	$tooltip[] = "Notes\t". $row['notes'];
	$tooltip[] = "Last Login\t". $row['last_login'];
	$tooltip[] = "Joined\t". $row['date_joined'];
	$tooltip[] = "Is Member\t". $row['is_member'];
	$tooltip[] = "Active\t". $row['active'];
	$tooltip[] = "Online\t". $row['online'];
	$tooltip[] = "Last online\t". $row['user_lastvisit'];

	$roster->tpl->assign_block_vars('user', array(
		'ROW_CLASS' => $roster->switch_row_class(),
		'ID'        => $row['id'],
		'IDC'       => $c++,
		'IDX'       => $c++,
		'ACTIVE'    => (bool)$row['active'],
		'NAME'      => $row['usr'],
		'TOOLTIP'   => makeOverlib(implode("\n", $tooltip), $row['usr'], '', 1, '', ''),
		'EMAIL'     => $row['email'],
		'ACCESS'    => $roster->auth->rosterAccess(array('guild_id' => ''.$row['group_id'].'','name' => ''.$row['id'].'[access]', 'value' => $row['access']))
		)
	);
}

$roster->db->free_result($dm_result);
$roster->tpl->assign_vars(array(
  'L_USER_MANAGER' => $roster->locale->act['admin']['user_desc'],
	)
);

$roster->tpl->set_filenames(array('body' => 'admin/user_manager.html'));
$body = $roster->tpl->fetch('body');
