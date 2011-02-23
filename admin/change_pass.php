<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster password changer
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

$roster->output['title'] .= $roster->locale->act['pagebar_changepass'];

if( array_key_exists('mode',$_POST) && $roster->auth->getAuthorized(ROSTERLOGIN_ADMIN) )
{
	$mode = $_POST['mode'];

	$query = "SELECT * FROM `" . $roster->db->table('account') . "` WHERE `name` = '" . $mode . "';";
	$result = $roster->db->query($query);

	if( !$result )
	{
		die_quietly($roster->db->error(), $roster->locale->act['roster_cp'], __FILE__, __LINE__, $query);
	}

	if( $row = $roster->db->fetch($result) )
	{
		$realhash = $row['hash'];
	}


	if( $roster->auth->getAuthorized(ROSTERLOGIN_ADMIN) )
	{
		$oldpass  = ( isset($_POST['oldpass']) ? $_POST['oldpass'] : '' );
		$newpass = ( isset($_POST['newpass']) ? $_POST['newpass'] : '' );
		$confirmpass = ( isset($_POST['confirmpass']) ? $_POST['confirmpass'] : '' );

		if( $mode == 'Admin' && md5($oldpass) != $realhash )
		{
			$roster->set_message($roster->locale->act['pass_old_error'], '', 'error');
		}
		elseif( !array_key_exists('newpass',$_POST) || !array_key_exists('confirmpass',$_POST) )
		{
			$roster->set_message($roster->locale->act['pass_submit_error'], '', 'error');
		}
		elseif( $newpass != $confirmpass )
		{
			$roster->set_message($roster->locale->act['pass_mismatch'], '', 'error');
		}
		elseif( $newpass === '' || $confirmpass === '' || md5($newpass) == md5('') )
		{
			$roster->set_message($roster->locale->act['pass_blank'], '', 'error');
		}
		elseif( md5($newpass) == $realhash )
		{
			$roster->set_message($roster->locale->act['pass_isold'], '', 'warning');
		}
		else // valid password
		{
			$query = 'UPDATE `' . $roster->db->table('account') . '` SET `hash` = "' . md5($newpass) . '"  WHERE `name` = "' . $mode . '";';
			$result = $roster->db->query($query);

			if (!$result)
			{
				$roster->set_message('There was a database error while trying to change the password.', '', 'error');
				$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
			}
			else
			{
				$roster->set_message(sprintf($roster->locale->act['pass_changed'], $mode, '<span class="redB">' . $newpass . '</span>'));
			}
		}
	}
}

$roster->tpl->set_filenames(array('body' => 'admin/change_pass.html'));
$body = $roster->tpl->fetch('body');
