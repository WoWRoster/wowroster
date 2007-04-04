<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
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

class RosterLogin
{
	var $allow_login;
	var $admin;
	var $message;
	var $loginform;
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
	function RosterLogin( $script_filename='' , $admin=true )
	{
		global $act_words;

		$this->script_filename = makelink($script_filename);
		$this->allow_login = false;
		$this->admin = $admin;
		$this->log_word = ($this->admin ? 'Admin' : 'Access');

		$this->loginform = '
			<!-- Begin Password Input Box -->
			<form action="'.$this->script_filename.'" method="post" enctype="multipart/form-data" onsubmit="submitonce(this)">
			'.border('sred','start',$this->log_word .' '. $act_words['auth_req']).'
			  <table class="bodyline" cellspacing="0" cellpadding="0" width="100%">
			    <tr>
			      <td class="membersRowRight1">'.$act_words['password'].':<br />
			        <input name="password" class="wowinput192" type="password" size="30" maxlength="30" /></td>
			    </tr>
			    <tr>
			      <td class="membersRowRight2" valign="bottom">
			        <div align="right"><input type="submit" value="Go" /></div></td>
			    </tr>
			  </table>
			'.border('sred','end').'
			</form>
			<!-- End Password Input Box -->';

		$this->checkLogin();
		$this->checkLogout();
	}

	function checkLogin()
	{
		global $roster_conf;

		$pass_type = ($this->admin ? 'roster_admin_pw' : 'roster_upd_pw');

		if( !isset($_COOKIE['roster_pass']) )
		{
			if( isset($_POST['password']) )
			{
				if( md5($_POST['password']) == $roster_conf[$pass_type] || md5($_POST['password']) == $roster_conf['roster_admin_pw'] )
				{
					setcookie( 'roster_pass',$roster_conf[$pass_type],0,'/' );
					$this->message = '<span style="font-size:10px;color:red;">Logged in '.$this->log_word.':</span><form style="display:inline;" name="roster_logout" action="'.$this->script_filename.'" method="post"><span style="font-size:10px;color:#FFFFFF"><input type="hidden" name="logout" value="1" />[<a href="javascript:document.roster_logout.submit();">Logout</a>]</span></form><br />';
					$this->allow_login = true;
				}
				else
				{
					$this->message = '<span style="font-size:11px;color:red;">Wrong password for '.$this->log_word.'</span><br />';
					$this->allow_login = false;
				}
			}
		}
		else
		{
			$BigCookie = $_COOKIE['roster_pass'];

			if( $BigCookie == $roster_conf[$pass_type] || $BigCookie == $roster_conf['roster_admin_pw'] )
			{
 				$this->message = '<span style="font-size:10px;color:red;">Logged in '.$this->log_word.':</span><form style="display:inline;" name="roster_logout" action="'.$this->script_filename.'" method="post"><span style="font-size:10px;color:#FFFFFF"><input type="hidden" name="logout" value="1" />[<a href="javascript:document.roster_logout.submit();">Logout</a>]</span></form><br />';
				$this->allow_login = true;
			}
			else
			{
				setcookie( 'roster_pass','',time()-86400,'/' );
				$this->allow_login = false;
			}
		}
	}

	function checkLogout()
	{
		if( isset( $_POST['logout'] ) && $_POST['logout'] == '1' )
		{
			if( isset($_COOKIE['roster_pass']) )
				setcookie( 'roster_pass','',time()-86400,'/' );
			$this->allow_login = false;
			$this->message = '<span style="font-size:10px;color:red;">Logged out</span><br />';
		}
	}

	function getAuthorized()
	{
		return $this->allow_login;
	}

	function getMessage()
	{
		return $this->message;
	}

	function getLoginForm()
	{
		return $this->loginform;
	}
}
