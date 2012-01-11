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
	//echo '<br>';
	foreach ($_POST as $name => $value)
	{
		$query = $access = '';
		if ($name != 'action' && $name != 'process')
		{
			//echo $name.' - '.implode(":",$value).'<br>';
			$access = implode(":",$value);
			$up_query = "UPDATE `" . $roster->db->table('user_members') . "` SET `access` = '".$access."' WHERE `id` = '".$name."';";
			//echo $up_query.'<br>';
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
$x .= '<table>';
$x .= '<tr>
		<td>User</td>
		<td>email</td>';
		foreach ($roster->auth->GetAccess() as $acc => $a)
		{
			$x .= '<td>'.$a.'</td>';
		}
$x .= '</tr>';
while( $data = $roster->db->fetch($dm_result) )
{
	//$dm_select[$data[1]][$data[2]] = $data[0];
	$x .= '<tr>
			<td>'.$data['usr'].'</td>
			<td>'.$data['email'].'</td>';
	
		$lvl = explode(":",$data['access']);
		
		foreach ($roster->auth->levels as $acc => $a)
		{
			$x .= '<td>'.(in_array($acc, $lvl) ? 'on' : 'off') .'
			<input type="checkbox" name="'.$data['id'].'['.$acc.']" id="'.$acc.'" value="'.$acc.'"  '.(in_array($acc, $lvl) ? 'checked="checked"' : '') .' />
			</td>';
		}
	$x .= '</tr>';
}
$x .= '<table>';
$roster->db->free_result($dm_result);


	$roster->tpl->assign_vars(array(
		'USER' => $x,
		)
	);


$roster->tpl->set_filenames(array('body' => 'admin/user_manager.html'));
$body = $roster->tpl->fetch('body');
