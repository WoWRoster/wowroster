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
	var $allow_admin_login;
	var $allow_update_login;
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
	function RosterLogin($script_filename='', $admin=TRUE)
	{
		$this->script_filename = makelink($script_filename);
		$this->allow_admin_login = false;
		$this->allow_update_login = false;

		$this->loginform = '
			<!-- Begin Password Input Box -->
			<form action="'.$this->script_filename.'" method="post" enctype="multipart/form-data" onsubmit="submitonce(this)">
			'.border('sred','start','Authorization Required').'
			  <table class="bodyline" cellspacing="0" cellpadding="0">
			    <tr>
			      <td class="membersRowRight1">Password:<br />
			        <input name="pass_word" class="wowinput192" type="password" size="30" maxlength="30" /></td>
			    </tr>
			    <tr>
			      <td class="membersRowRight2" valign="bottom">
			        <div align="right"><input type="submit" value="Go" /></div></td>
			    </tr>
			  </table>
			'.border('sred','end').'
			</form>
			<!-- End Password Input Box -->';

		if($admin === TRUE)
		{
			$this->checkAdminLogin();
			$this->checkLogout();	
		} else {
			$this->checkUpdateLogin();
			$this->checkLogout();
		}
	}

	function checkAdminLogin()
	{
		global $roster_conf;

		if( !isset($_COOKIE['roster_pass']) )
		{
			if( isset($_POST['pass_word']) )
			{
				if( md5($_POST['pass_word']) == $roster_conf['roster_admin_pw'] )
				{
					setcookie( 'roster_pass',$roster_conf['roster_admin_pw'],0,'/' );
					$this->message = '<span style="font-size:10px;color:red;">Logged in:</span><form style="display:inline;" name="roster_logout" action="'.$this->script_filename.'" method="post"><span style="font-size:10px;color:#FFFFFF"><input type="hidden" name="logout" value="1" />[<a href="javascript:document.roster_logout.submit();">Logout</a>]</span></form><br />';
					$this->allow_admin_login = true;
					$this->allow_update_login = true;
				}
				else
				{
					$this->message = '<span style="font-size:11px;color:red;">Wrong password</span><br />';
					$this->allow_admin_login = false;
					$this->allow_update_login = false;
				}
			}
		}
		else
		{
			$BigCookie = $_COOKIE['roster_pass'];

			if( $BigCookie == $roster_conf['roster_admin_pw'] )
			{
 				$this->message = '<span style="font-size:10px;color:red;">Logged in:</span><form style="display:inline;" name="roster_logout" action="'.$this->script_filename.'" method="post"><span style="font-size:10px;color:#FFFFFF"><input type="hidden" name="logout" value="1" />[<a href="javascript:document.roster_logout.submit();">Logout</a>]</span></form><br />';
				$this->allow_admin_login = true;
				$this->allow_update_login = true;
			}
			else
			{
				setcookie( 'roster_pass','',time()-86400,'/' );
				$this->allow_login = false;
			}
		}
	}
	
	function checkUpdateLogin()
	{
		global $roster_conf;

		if( !isset($_COOKIE['roster_update_pass']) )
		{
			if( isset($_POST['pass_word']) )
			{
				if( (md5($_POST['pass_word']) == $roster_conf['roster_update_pw']) || md5($_POST['pass_word']) == $roster_conf['roster_admin_pw'])
				{
					setcookie( 'roster_pass',$roster_conf['roster_roster_pw'],0,'/' );
					$this->message = '<span style="font-size:10px;color:red;">Logged in:</span><form style="display:inline;" name="roster_logout" action="'.$this->script_filename.'" method="post"><span style="font-size:10px;color:#FFFFFF"><input type="hidden" name="logout" value="1" />[<a href="javascript:document.roster_logout.submit();">Logout</a>]</span></form><br />';
					$this->allow_update_login = true;
				}
				else
				{
					$this->message = '<span style="font-size:11px;color:red;">Wrong password</span><br />';
					$this->allow_admin_login = false;
					$this->allow_update_login = false;
				}
			}
		}
		else
		{
			$BigCookie = $_COOKIE['roster_update_pass'];

			if( ($BigCookie == $roster_conf['roster_update_pw']) || ($BigCookie == $roster_conf['roster_admin_pw']))
			{
 				$this->message = '<span style="font-size:10px;color:red;">Logged in:</span><form style="display:inline;" name="roster_logout" action="'.$this->script_filename.'" method="post"><span style="font-size:10px;color:#FFFFFF"><input type="hidden" name="logout" value="1" />[<a href="javascript:document.roster_logout.submit();">Logout</a>]</span></form><br />';
				$this->allow_update_login = true;
			}
			else
			{
				setcookie( 'roster_pass','',time()-86400,'/' );
				$this->allow_update_login = false;
			}
		}
	}

	function checkLogout()
	{
		if( isset( $_POST['logout'] ) && $_POST['logout'] == '1' )
		{
			if( isset($_COOKIE['roster_admin_pass']) )
				setcookie( 'roster_admin_pass','',time()-86400,'/' );
			if( isset($_COOKIE['roster_update_pass']) )
				setcookie( 'roster_update_pass','',time()-86400,'/' );
			$this->allow_admin_login = false;
			$this->allow_update_login = false;
			$this->message = '<span style="font-size:10px;color:red;">Logged out</span><br />';
		}
	}

	function getAuthorized()
	{
		global $admin;
		if($admin == TRUE)
		{
			return $this->allow_admin_login;
		} 
		elseif($admin == FALSE)
		{
			return $this->allow_update_login;
		}
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
