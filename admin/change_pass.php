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

		$success = 0;
		if( $mode == 'Admin' && md5($oldpass) != $realhash )
		{
			$rcp_message .= messagebox($roster->locale->act['pass_old_error'],$roster->locale->act['roster_cp'],'sred');
		}
		elseif( !array_key_exists('newpass',$_POST) || !array_key_exists('confirmpass',$_POST) )
		{
			$rcp_message .= messagebox($roster->locale->act['pass_submit_error'],$roster->locale->act['roster_cp'],'sred');
		}
		elseif( $newpass != $confirmpass )
		{
			$rcp_message .= messagebox($roster->locale->act['pass_mismatch'],$roster->locale->act['roster_cp'],'sred');
		}
		elseif( $newpass === '' || $confirmpass === '' || md5($newpass) == md5('') )
		{
			$rcp_message .= messagebox($roster->locale->act['pass_blank'],$roster->locale->act['roster_cp'],'sred');
		}
		elseif( md5($newpass) == $realhash )
		{
			$rcp_message .= messagebox($roster->locale->act['pass_isold'],$roster->locale->act['roster_cp'],'sorange');
		}
		else // valid password
		{
			$query = 'UPDATE `' . $roster->db->table('account') . '` SET `hash` = "' . md5($newpass) . '"  WHERE `name` = "' . $mode . '";';

			$result = $roster->db->query($query);

			if (!$result)
			{
				die_quietly('There was a database error while trying to change the password. MySQL said: <br />' . $roster->db->error(),$roster->locale->act['roster_cp'],__FILE__,__LINE__,$query);
			}

			$success = 1;

			$rcp_message .= messagebox(sprintf($roster->locale->act['pass_changed'],$mode,'<span style="font-size:11px;color:red;">' . $newpass . '</span>'),$roster->locale->act['roster_cp'],'sgreen');
		}

		$rcp_message .= '<br />';
	}
}

$roster->tpl->set_filenames(array('body' => 'admin/change_pass.html'));
$body = $roster->tpl->fetch('body');
