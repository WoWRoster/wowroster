<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

if( array_key_exists('mode',$_POST) )
{
	$mode = $_POST['mode'];

	if( $mode == 'admin' )
	{
		$dbfield = 'roster_admin_pw';
	}
	elseif( $mode == 'update' )
	{
		$dbfield = 'roster_upd_pw';
	}
	else
	{
		continue;
	}

	if( $roster_login->getauthorized() )
	{
		$oldpass  = ( isset($_POST['oldpass']) ? $_POST['oldpass'] : '' );
		$newpass = ( isset($_POST['newpass']) ? $_POST['newpass'] : '' );
		$confirmpass = ( isset($_POST['confirmpass']) ? $_POST['confirmpass'] : '' );

		$success = 0;
		if( md5($oldpass) != $roster_conf[$dbfield] )
		{
			$body = messagebox($act_words['pass_old_error'],$act_words['roster_cp'],'sred');
		}
		elseif( !array_key_exists('newpass',$_POST) || !array_key_exists('confirmpass',$_POST) )
		{
			$body = messagebox($act_words['pass_submit_error'],$act_words['roster_cp'],'sred');
		}
		elseif( $newpass != $confirmpass )
		{
			$body = messagebox($act_words['pass_mismatch'],$act_words['roster_cp'],'sred');
		}
		elseif( $newpass === '' || $confirmpass === '' || md5($newpass) == md5('') )
		{
			$body = messagebox($act_words['pass_blank'],$act_words['roster_cp'],'sred');
		}
		elseif( md5($newpass) == $roster_conf[$dbfield] )
		{
			$body = messagebox($act_words['pass_isold'],$act_words['roster_cp'],'sred');
		}
		else // valid password
		{
			$query = 'UPDATE `'.$wowdb->table('config').'` SET `config_value` = "'.md5($newpass).'"  WHERE `config_name` = "'.$dbfield.'";';

			$result = $wowdb->query($query);

			if (!$result)
			{
				die_quietly('There was a database error while trying to change the password. MySQL said: <br />'.$wowdb->error(),$act_words['roster_cp'],basename(__FILE__),__LINE__,$query);
			}

			$success = 1;

			$body = messagebox(sprintf($act_words['pass_changed'],'<span style="font-size:11px;color:red;">'.$newpass.'</span>'),$act_words['roster_cp'],'sgreen');
		}
	}

	if ($success)
	{
		return;
	}

	$body .= '<br />';
}

$body .= $roster_login->getMessage().'<br />
<form action="'.makelink().'" method="post" enctype="multipart/form-data" id="conf_change_pass" onsubmit="submitonce(this)">
<input type="hidden" name="mode" value="admin" />
	'.border('sred','start',$act_words['changeadminpass']).'
	  <table class="bodyline" cellspacing="0" cellpadding="0">
	    <tr>
	      <td class="membersRow1">'.$act_words['old_pass'].':</td>
	      <td class="membersRowRight1"><input class="wowinput192" type="password" name="oldpass" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow2">'.$act_words['new_pass'].':</td>
	      <td class="membersRowRight2"><input class="wowinput192" type="password" name="newpass" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow1">'.$act_words['new_pass_confirm'].':</td>
	      <td class="membersRowRight1"><input class="wowinput192" type="password" name="confirmpass" value="" /></td>
	    </tr>
	    <tr>
	      <td colspan="2" class="membersRowRight2" valign="bottom"><div align="center">
		    <input type="submit" value="'.$act_words['pagebar_changepass'].'" /></div></td>
	    </tr>
	  </table>
	'.border('sred','end').'
	</form>
<br />';


$body .= '<br />
<form action="'.makelink().'" method="post" enctype="multipart/form-data" id="conf_change_pass" onsubmit="submitonce(this)">
<input type="hidden" name="mode" value="update" />
	'.border('syellow','start',$act_words['changeupdatepass']).'
	  <table class="bodyline" cellspacing="0" cellpadding="0">
	    <tr>
	      <td class="membersRow1">'.$act_words['old_pass'].':</td>
	      <td class="membersRowRight1"><input class="wowinput192" type="password" name="oldpass" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow2">'.$act_words['new_pass'].':</td>
	      <td class="membersRowRight2"><input class="wowinput192" type="password" name="newpass" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow1">'.$act_words['new_pass_confirm'].':</td>
	      <td class="membersRowRight1"><input class="wowinput192" type="password" name="confirmpass" value="" /></td>
	    </tr>
	    <tr>
	      <td colspan="2" class="membersRowRight2" valign="bottom"><div align="center">
		    <input type="submit" value="'.$act_words['pagebar_changepass'].'" /></div></td>
	    </tr>
	  </table>
	'.border('syellow','end').'
	</form>';
