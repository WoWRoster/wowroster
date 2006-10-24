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


class RosterLogin
{
	var $allow_login;
	var $message;
	var $script_filename;

	/**
	 * Constructor for Roster Login class
	 * Accepts an action for the form
	 * And an array of additional fields
	 *
	 * @param string $script_filename
	 * @param array $fields
	 * @return RosterLogin
	 */
	function RosterLogin($script_filename)
	{
		$this->script_filename = $script_filename;
		$this->allow_login = false;

		$this->checkLogin();
	}

	function checkLogin()
	{
		$this->allow_login = is_admin();
	}

	function getAuthorized()
	{
		return $this->allow_login;
	}

	function getMessage()
	{
		return '';
	}

	function getLoginForm()
	{
		return '';
	}
}

// if is admin

class RosterAdminLogin
{
	var $allow_login;
	var $message;
	var $script_filename;
    
	/**
	 * Constructor for Roster Login class
	 * Accepts an action for the form
	 * And an array of additional fields
	 *
	 * @param string $script_filename
	 * @param array $fields
	 * @return RosterLogin
	 */
	function RosterAdminLogin($script_filename)
	{
		$this->script_filename = $script_filename;
		$this->allow_login = false;

		$this->checkLogin();
	}

	function checkLogin()
	{
		$this->allow_login = is_admin();
	}

	function getAuthorized()
	{
		return $this->allow_login;
	}

	function getMessage()
	{
		if (is_admin()){
		return 'you are logged in as admin';
		}
		else
		return '';
	}

	function getLoginForm()
	{
	global $sec_code, $pagetitle, $adminindex;
		if (is_admin()){
		return '';
		}
		else
		print border('sred', 'start', _ADMINLOGIN);
		echo open_form($adminindex, 'login').'
	<label for="alogin" class="ulog">'._ADMINID.'</label><input class="set" type="text" name="alogin" id="alogin" size="20" maxlength="25" /><br />
	<label for="pwd" class="ulog">'._PASSWORD.'</label><input class="set" type="password" name="pwd" id="pwd" size="20" maxlength="40" /><br />';
	if ($sec_code & 1) {
		echo '<label for="gfx_check" class="ulog">'._SECURITYCODE.':</label>'.generate_secimg(7).'<br />
		<label for="gfx_check" class="ulog">'._TYPESECCODE.':</label><input class="set" type="text" name="gfx_check" id="gfx_check" size="10" maxlength="8" /><br />';
	}
	echo '<label for="persistent" class="ulog">'._LOGIN_REMEMBERME.'</label><input type="checkbox" name="persistent" id="persistent" value="1" /><br />
	<div align="center"><input type="submit" class="sub" value="'._LOGIN.'" /></div>'.
	close_form();
	
	echo '<script type="text/javascript">document.login.alogin.focus();</script>';
	print border('sred','end');
	}
}

//If is member

class RosterUserLogin
{
	var $allow_login;
	var $message;
	var $script_filename;

	/**
	 * Constructor for Roster Login class
	 * Accepts an action for the form
	 * And an array of additional fields
	 *
	 * @param string $script_filename
	 * @param array $fields
	 * @return RosterLogin
	 */
	function RosterUserLogin($script_filename)
	{
		$this->script_filename = $script_filename;
		$this->allow_login = false;

		$this->checkLogin();
	}

	function checkLogin()
	{
		$this->allow_login = is_user();
	}

	function getAuthorized()
	{
		return $this->allow_login;
	}

	function getMessage()
	{
		return '';
	}

	function getLoginForm()
	{
	
	global $prefix, $user_prefix, $db, $sec_code, $userinfo, $MAIN_CFG, $CPG_SESS;
	if (is_user()){
	return '';
	}
	elseif (isset($_GET['redirect']) && !isset($CPG_SESS['user']['redirect'])) { $CPG_SESS['user']['redirect'] = $CPG_SESS['user']['uri']; }
	$redirect = (isset($CPG_SESS['user']['redirect']) ? $CPG_SESS['user']['redirect'] : getlink());
	echo '<br>';
	print border('sred', 'start','Error');
	echo '<div><br>Must be logged in to use this service<br><form action="'.$redirect.'" method="post"  enctype="multipart/form-data" accept-charset="utf-8"><table border="0" cellpadding="3" cellspacing="1" width="100%" >';
	if ($error) {
		echo '<tr><td align="center" class="catleft" colspan="2"><b><span class="gem" >'._ERROR.'</span></b></td></tr>
	<tr><td class="membersRow1" colspan="2" align="center">'.$error.'</td></tr>';
	}
	echo '<tr>
		<td class="membersRow1"><span class="gen"><label for="ulogin2">'._NICKNAME.'</label></span><br />'
		.(($MAIN_CFG['member']['allowuserreg']) ? '<a  href="'.getlink('&amp;file=register').'">Apply</a>' : '')
		.'</td><td class="membersRow2"><input type="text" name="ulogin" id="ulogin2" class="set" tabindex="1" size="20" maxlength="25" /></td></tr>
	<tr>
		<td class="membersRow1"><span class="gen"><label for="user_password2">'._PASSWORD.'</label></span><br /><a href="'.getlink('&amp;op=pass_lost').'">Lost your Password?</a></td>
		<td class="membersRow2"><input type="password" name="user_password" id="user_password2" class="set" tabindex="2" size="20" maxlength="20" /></td>
	</tr>';
	if ($MAIN_CFG['global']['sec_code'] & 2) {
		echo '<tr>
		<td class="membersRow1"><span class="gen"><label for="gfx_check">'._SECURITYCODE.'</label></span></td>
		<td class="membersRow2">'.generate_secimg().'</td>
	</tr><tr>
		<td class="membersRow1"><span class="gen"><label for="gfx_check">'._TYPESECCODE.'</label></span></td>
		<td class="membersRow2"><input type="text" name="gfx_check" id="gfx_check" class="set" tabindex="3" size="7" maxlength="6" /></td>
	</tr>';
	}
	echo '<tr><td class="membersRowRight1" colspan="2" align="center" height="28">
	<input type="submit" class="mainoption" value="'._LOGIN.'" />
	</td></tr></table></form></div>';
print border('sred', 'end');
	}
}
?>