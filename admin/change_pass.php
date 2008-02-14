<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster password changer
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] .= $roster->locale->act['pagebar_changepass'];

$roster->tpl->assign_vars(array(
	'L_CHANGE_ADMIN'   => $roster->locale->act['changeadminpass'],
	'L_CHANGE_OFFICER' => $roster->locale->act['changeofficerpass'],
	'L_CHANGE_GUILD'   => $roster->locale->act['changeguildpass'],
	'L_OLD_PASS'         => $roster->locale->act['old_pass'],
	'L_NEW_PASS'         => $roster->locale->act['new_pass'],
	'L_NEW_PASS_CONFIRM' => $roster->locale->act['new_pass_confirm'],
	'L_CHANGE_PASS'      => $roster->locale->act['pagebar_changepass'],

	'MESSAGE' => '',
	)
);


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
			$message = messagebox($roster->locale->act['pass_old_error'],$roster->locale->act['roster_cp'],'sred');
		}
		elseif( !array_key_exists('newpass',$_POST) || !array_key_exists('confirmpass',$_POST) )
		{
			$message = messagebox($roster->locale->act['pass_submit_error'],$roster->locale->act['roster_cp'],'sred');
		}
		elseif( $newpass != $confirmpass )
		{
			$message = messagebox($roster->locale->act['pass_mismatch'],$roster->locale->act['roster_cp'],'sred');
		}
		elseif( $newpass === '' || $confirmpass === '' || md5($newpass) == md5('') )
		{
			$message = messagebox($roster->locale->act['pass_blank'],$roster->locale->act['roster_cp'],'sred');
		}
		elseif( md5($newpass) == $realhash )
		{
			$message = messagebox($roster->locale->act['pass_isold'],$roster->locale->act['roster_cp'],'sred');
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

			$message = messagebox(sprintf($roster->locale->act['pass_changed'],$mode,'<span style="font-size:11px;color:red;">' . $newpass . '</span>'),$roster->locale->act['roster_cp'],'sgreen');
		}

		$message .= '<br />';
		$roster->tpl->assign_var('MESSAGE',$message);
	}
}

$roster->tpl->set_filenames(array('body' => 'admin/change_pass.html'));
$body = $roster->tpl->fetch('body');
