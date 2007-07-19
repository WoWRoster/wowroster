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

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] .= $roster->locale->act['pagebar_changepass'];

if( array_key_exists('mode',$_POST) )
{
	$mode = $_POST['mode'];

	$query = "SELECT * FROM `".$roster->db->table('account')."` WHERE `name` = '".$mode."';";
	$result = $roster->db->query($query);

	if( !$result )
	{
		die_quietly($roster->db->error(), $roster->locale->act['roster_cp'], __FILE__, __LINE__, $query);
	}

	if( $row = $roster->db->fetch($result) )
	{
		$realhash = $row['hash'];
	}


	if( $roster_login->getauthorized() )
	{
		$oldpass  = ( isset($_POST['oldpass']) ? $_POST['oldpass'] : '' );
		$newpass = ( isset($_POST['newpass']) ? $_POST['newpass'] : '' );
		$confirmpass = ( isset($_POST['confirmpass']) ? $_POST['confirmpass'] : '' );

		$success = 0;
		if( md5($oldpass) != $realhash )
		{
			$body = messagebox($roster->locale->act['pass_old_error'],$roster->locale->act['roster_cp'],'sred');
		}
		elseif( !array_key_exists('newpass',$_POST) || !array_key_exists('confirmpass',$_POST) )
		{
			$body = messagebox($roster->locale->act['pass_submit_error'],$roster->locale->act['roster_cp'],'sred');
		}
		elseif( $newpass != $confirmpass )
		{
			$body = messagebox($roster->locale->act['pass_mismatch'],$roster->locale->act['roster_cp'],'sred');
		}
		elseif( $newpass === '' || $confirmpass === '' || md5($newpass) == md5('') )
		{
			$body = messagebox($roster->locale->act['pass_blank'],$roster->locale->act['roster_cp'],'sred');
		}
		elseif( md5($newpass) == $realhash )
		{
			$body = messagebox($roster->locale->act['pass_isold'],$roster->locale->act['roster_cp'],'sred');
		}
		else // valid password
		{
			$query = 'UPDATE `'.$roster->db->table('account').'` SET `hash` = "'.md5($newpass).'"  WHERE `name` = "'.$mode.'";';

			$result = $roster->db->query($query);

			if (!$result)
			{
				die_quietly('There was a database error while trying to change the password. MySQL said: <br />'.$roster->db->error(),$roster->locale->act['roster_cp'],__FILE__,__LINE__,$query);
			}

			$success = 1;

			$body = messagebox(sprintf($roster->locale->act['pass_changed'],'<span style="font-size:11px;color:red;">'.$newpass.'</span>'),$roster->locale->act['roster_cp'],'sgreen');
		}
	}

	if ($success)
	{
		return;
	}

	$body .= '<br />';
}

$body .= '<form action="'.makelink().'" method="post" enctype="multipart/form-data" id="conf_admin_pass" onsubmit="submitonce(this)">
<input type="hidden" name="mode" value="Admin" />
	'.border('sred','start',$roster->locale->act['changeadminpass']).'
	  <table class="bodyline" cellspacing="0" cellpadding="0">
	    <tr>
	      <td class="membersRow1">'.$roster->locale->act['old_pass'].':</td>
	      <td class="membersRowRight1"><input class="wowinput192" type="password" name="oldpass" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow2">'.$roster->locale->act['new_pass'].':</td>
	      <td class="membersRowRight2"><input class="wowinput192" type="password" name="newpass" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow1">'.$roster->locale->act['new_pass_confirm'].':</td>
	      <td class="membersRowRight1"><input class="wowinput192" type="password" name="confirmpass" value="" /></td>
	    </tr>
	    <tr>
	      <td colspan="2" class="membersRowRight2" valign="bottom"><div align="center">
		    <input type="submit" value="'.$roster->locale->act['pagebar_changepass'].'" /></div></td>
	    </tr>
	  </table>
	'.border('sred','end').'
	</form>
<br />';


$body .= '<br />
<form action="'.makelink().'" method="post" enctype="multipart/form-data" id="conf_officer_pass" onsubmit="submitonce(this)">
<input type="hidden" name="mode" value="Officer" />
	'.border('syellow','start',$roster->locale->act['changeupdatepass']).'
	  <table class="bodyline" cellspacing="0" cellpadding="0">
	    <tr>
	      <td class="membersRow1">'.$roster->locale->act['old_pass'].':</td>
	      <td class="membersRowRight1"><input class="wowinput192" type="password" name="oldpass" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow2">'.$roster->locale->act['new_pass'].':</td>
	      <td class="membersRowRight2"><input class="wowinput192" type="password" name="newpass" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow1">'.$roster->locale->act['new_pass_confirm'].':</td>
	      <td class="membersRowRight1"><input class="wowinput192" type="password" name="confirmpass" value="" /></td>
	    </tr>
	    <tr>
	      <td colspan="2" class="membersRowRight2" valign="bottom"><div align="center">
		    <input type="submit" value="'.$roster->locale->act['pagebar_changepass'].'" /></div></td>
	    </tr>
	  </table>
	'.border('syellow','end').'
	</form>';


$body .= '<br />
<form action="'.makelink().'" method="post" enctype="multipart/form-data" id="conf_guild_pass" onsubmit="submitonce(this)">
<input type="hidden" name="mode" value="Guild" />
	'.border('sgreen','start',$roster->locale->act['changeguildpass']).'
	  <table class="bodyline" cellspacing="0" cellpadding="0">
	    <tr>
	      <td class="membersRow1">'.$roster->locale->act['old_pass'].':</td>
	      <td class="membersRowRight1"><input class="wowinput192" type="password" name="oldpass" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow2">'.$roster->locale->act['new_pass'].':</td>
	      <td class="membersRowRight2"><input class="wowinput192" type="password" name="newpass" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow1">'.$roster->locale->act['new_pass_confirm'].':</td>
	      <td class="membersRowRight1"><input class="wowinput192" type="password" name="confirmpass" value="" /></td>
	    </tr>
	    <tr>
	      <td colspan="2" class="membersRowRight2" valign="bottom"><div align="center">
		    <input type="submit" value="'.$roster->locale->act['pagebar_changepass'].'" /></div></td>
	    </tr>
	  </table>
	'.border('sgreen','end').'
	</form>';
