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
 * $Id: change_pass.php 738 2007-03-29 07:53:15Z Zanix $
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

if ( array_key_exists('oldpass',$_POST))
{
	if ($roster_login->getauthorized())
	{
		$success = 0;
		if ( md5($_POST['oldpass']) != $roster_conf['roster_upd_pw'] )
		{
			$body = messagebox('Wrong password. Please enter the correct old password.','Roster Control Panel','sred');
		}
		elseif (!array_key_exists('newpass1',$_POST) ||
			!array_key_exists('newpass2',$_POST))
		{
			$body = messagebox('Submit error. The old password, the new password, and the confirmed new password need to be submitted.','Roster Control Panel','sred');
		}
		elseif ( $_POST['newpass1'] != $_POST['newpass2'] )
		{
			$body = messagebox('Passwords do not match. Please type the exact same password in both new password fields.','Roster Control Panel','sred');
		}
		elseif ( $_POST['newpass1'] === '' || $_POST['newpass2'] === '')
		{
			$body = messagebox('No blank passwords. Please enter a password in both fields. Blank passwords are not allowed.','Roster Control Panel','sred');
		}
		elseif ( md5($_POST['newpass1']) == md5('') )
		{
			$body = messagebox('No blank passwords. You did not enter a blank password but it does have the same hash. Blank passwords are not allowed.','Roster Control Panel','sred');
		}
		elseif ( md5($_POST['newpass1']) == $roster_conf['roster_upd_pw'] )
		{
			$body = messagebox('Password not changed. The new password was the same as the old one.','Roster Control Panel','sred');
		}
		else // valid password
		{
			$query = 'UPDATE `'.$wowdb->table('config').'` SET `config_value` = "'.md5($_POST['newpass1']).'"  WHERE `config_name` = "roster_upd_pw"';

			$result = $wowdb->query($query);

			if (!$result)
			{
				die_quietly('There was a database error while trying to change the password. MySQL said: <br />'.$wowdb->error(),'Roster Control Panel',basename(__FILE__),__LINE__,$query);
			}

			$wowdb->free_result($result);

			$success = 1;

			$body = messagebox('Password changed. Your new password is <span style="font-size:11px;color:red;">'.$_POST['newpass1'].'</span>.<br /> Do not forget this password, it is stored encrypted only.','Roster Control Panel','sgreen');
		}
	}

	if ($success)
	{
		return;
	}

	$body .= '<br />';
}

$body .= $roster_login->getMessage().'<br />
<form action="" method="post" enctype="multipart/form-data" id="conf_change_pass" onsubmit="submitonce(this)">
	'.border('sred','start',$act_words['pagebar_changeupdatepass']).'
	  <table class="bodyline" cellspacing="0" cellpadding="0">
	    <tr>
	      <td class="membersRow1">Old Password:</td>
	      <td class="membersRowRight1"><input class="wowinput192" type="password" name="oldpass" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow2">New Password:</td>
	      <td class="membersRowRight2"><input class="wowinput192" type="password" name="newpass1" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow1">New Password<br />[ confirm ]:</td>
	      <td class="membersRowRight1"><input class="wowinput192" type="password" name="newpass2" value="" /></td>
	    </tr>
	    <tr>
	      <td colspan="2" class="membersRowRight2" valign="bottom"><div align="center">
		<input type="submit" value="Change" /></div></td>
	    </tr>
	  </table>
	'.border('sred','end').'
	</form>';
