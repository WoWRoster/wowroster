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

include( ROSTER_LIB . 'update.lib.php' );
$update = new update;

$start = (isset($_GET['start']) ? $_GET['start'] : 0);

$roster->output['title'] .= $roster->locale->act['pagebar_userman'];

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
$tooltip = 'User ID '.$row['id'].'</br>';
$tooltip .= 'Username '.$row['usr'].'</br>';
$tooltip .= 'Email '.$row['email'].'</br>';
$tooltip .= 'Reg ip '.$row['regIP'].'</br>';
$tooltip .= 'Access '.$row['access'].'</br>';
$tooltip .= 'Name '.$row['fname'].' '.$row['lname'].'</br>';
$tooltip .= 'Age '.$row['age'].'</br>';
$tooltip .= 'City '.$row['city'].'</br>';
$tooltip .= 'State '.$row['state'].'</br>';
$tooltip .= 'Country '.$row['country'].'</br>';
$tooltip .= 'Zone '.$row['zone'].'</br>';
$tooltip .= 'WWW '.$row['homepage'].'</br>';
$tooltip .= 'O/Guilds '.$row['other_guilds'].'</br>';
$tooltip .= 'Why '.$row['why'].'</br>';
$tooltip .= 'About '.$row['about'].'</br>';
$tooltip .= 'Notes '.$row['notes'].'</br>';
$tooltip .= 'Last Login '.$row['last_login'].'</br>';
$tooltip .= 'Joined '.$row['date_joined'].'</br>';
$tooltip .= 'Is Member '.$row['is_member'].'</br>';
$tooltip .= 'Active '.$row['active'].'</br>';
$tooltip .= 'Online '.$row['online'].'</br>';
$tooltip .= 'LAst online '.$row['user_lastvisit'].'</br>';
	$roster->tpl->assign_block_vars('user', array(
		'ROW_CLASS' => $roster->switch_row_class(),
		'ID'        => $row['id'],
		'IDC'        => $c++,
		'IDX'        => $c++,
		'ACTIVE'	=> (bool)$row['active'],
		'NAME'      => $row['usr'],
		'TOOLTIP'      => $tooltip,
		'EMAIL'    => $row['email'],
		'ACCESS'     => $roster->auth->makeAccess(array('name' => ''.$row['id'].'[access]', 'value' => $row['access']))
		)
	);
}
$roster->db->free_result($dm_result);


$roster->tpl->set_filenames(array('body' => 'admin/user_manager.html'));
$body = $roster->tpl->fetch('body');
