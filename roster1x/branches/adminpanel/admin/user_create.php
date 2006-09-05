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

if ( array_key_exists('user',$_POST))
{
	$success = $roster_login->createAccount($_POST['user'], $_POST['pass1'], $_POST['pass2']);
	
	$body = messagebox($roster_login->getMessage(),'Roster User Panel','sgreen')."<br />\n";

	if ($success)
	{
		return;
	}
}

$body .='<form action="" method="post" enctype="multipart/form-data" id="conf_change_pass" onsubmit="submitonce(this)">
	'.border('sred','start','Create New Account').'
	  <table class="bodyline" cellspacing="0" cellpadding="0">
	    <tr>
	      <td class="membersRow1">User Name:</td>
	      <td class="membersRowRight1"><input type="text" name="user" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow2">Password:</td>
	      <td class="membersRowRight2"><input type="password" name="pass1" value="" /></td>
	    </tr>
	    <tr>
	      <td class="membersRow1">Password<br />[ confirm ]:</td>
	      <td class="membersRowRight1"><input type="password" name="pass2" value="" /></td>
	    </tr>
	    <tr>
	      <td colspan="2" class="membersRowRight2" valign="bottom"><div align="center">
		<input type="submit" value="Create" /></div></td>
	    </tr>
	  </table>
	'.border('sred','end').'
	</form>';

?>