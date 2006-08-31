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

$showlogin = false;

if (($roster_login->getUserName() == '') || $roster_login->getAuthorized())
{
	$userfield = true; // Show the user box if we're not logged in or on admin login
}

if ( array_key_exists('oldpass',$_POST))
{
	$success = $roster_login->changePass((($userfield)?$_POST['user']:$roster_login->user), $_POST['oldpass'], $_POST['newpass1'], $_POST['newpass2']);
	
	$body = messagebox($roster_login->getMessage(),'Roster User Panel','sgreen')."<br />\n";

	if ($success)
	{
		return;
	}
}

$body .='<form action="" method="post" enctype="multipart/form-data" id="conf_change_pass" onsubmit="submitonce(this)">
	'.border('sred','start','Change Password').'
	  <table class="bodyline" cellspacing="0" cellpadding="0">'.(($userfield)?'
	    <tr>
	      <td class="membersRow1">User Name:</td>
	      <td class="membersRowRight1"><input type="text" name="user" value="" /></td>
	    </tr>':'').'
	    <tr>
	      <td class="membersRow1">Old Password:</td>
	      <td class="membersRowRight1"><input type="password" name="oldpass" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow2">New Password:</td>
	      <td class="membersRowRight2"><input type="password" name="newpass1" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow1">New Password<br />[ confirm ]:</td>
	      <td class="membersRowRight1"><input type="password" name="newpass2" value="" /></td>
	    </tr>
	    <tr>
	      <td colspan="2" class="membersRowRight2" valign="bottom"><div align="center">
		<input type="submit" value="Change" /></div></td>
	    </tr>
	  </table>
	'.border('sred','end').'
	</form>';

?>