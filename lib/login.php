<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Login and authorization
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.1
 * @package    WoWRoster
 * @subpackage User
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

define('ROSTERLOGIN_ADMIN', 3);

/**
 * Login and authorization
 *
 * @package    WoWRoster
 * @subpackage User
 */
class RosterLogin
{
	var $allow_login;
	var $message;
	var $action;
	var $levels = array();

	/**
	 * Constructor for Roster Login class
	 * Accepts an action for the form
	 * And an array of additional fields
	 *
	 * @param $script_filename
	 * @return RosterLogin
	 */
	function RosterLogin( $script_filename = '' )
	{
		$this->setAction($script_filename);

		if( isset($_POST['logout']) && $_POST['logout'] == '1' )
		{
			setcookie('roster_pass', '', time() - 86400, '/');
			$this->allow_login = 0;
			$this->message = '<span style="font-size:10px;color:red;">Logged out</span><br />';
		}
		elseif( isset($_POST['password']) && $_POST['password'] != '' )
		{
			$this->checkPass($_POST['password']);
		}
		elseif( isset($_COOKIE['roster_pass']) )
		{
			$this->checkPass($_COOKIE['roster_pass']);
		}
		else
		{
			$this->allow_login = 0;
			$this->message = '';
		}
	}

	function checkPass( $pass )
	{
		global $roster;

		$query = "SELECT * FROM `" . $roster->db->table('account') . "` ORDER BY `account_id` DESC;";
		$result = $roster->db->query($query);

		if( !$result )
		{
			setcookie('roster_pass', '', time() - 86400, '/');
			$this->allow_login = 0;
			$this->message = '<span style="font-size:10px;color:red;">Failed to fetch password info</span><br />';
			return;
		}

		while( $row = $roster->db->fetch($result) )
		{
			if( ($row['hash'] == md5($pass)) || ($row['hash'] == $pass) )
			{
				setcookie('roster_pass', $row['hash'], 0, '/');
				$this->allow_login = $row['account_id'];
				$this->message = '<span style="font-size:10px;">Logged in ' . $row['name'] . ':</span> <form style="display:inline;" name="roster_logout" action="' . $this->action . '" method="post"><input type="hidden" name="logout" value="1" /><input type="submit" value="Logout" /></form>';

				$roster->db->free_result($result);
				return;
			}
		}
		$roster->db->free_result($result);

		setcookie('roster_pass', '', time() - 86400, '/');
		$this->allow_login = 0;
		$this->message = '<span style="font-size:10px;color:red;">Invalid password</span><br />';
		return;
	}

	function getAuthorized( $access )
	{
		return $this->allow_login >= $access;
	}

	function getMessage( )
	{
		return $this->message;
	}

	function getLoginForm( $level = 3 )
	{
		global $roster;

		if( !$this->allow_login )
		{
			$query = "SELECT * FROM `" . $roster->db->table('account') . "` WHERE `account_id` = '" . $level . "';";
			$result = $roster->db->query($query);

			if( !$result )
			{
				die_quietly($roster->db->error, 'Roster Auth', __FILE__, __LINE__, $query);
			}

			if( $roster->db->num_rows($result) != 1 )
			{
				die_quietly('Invalid required login level specified', 'Roster Auth');
			}

			$row = $roster->db->fetch($result);
			$roster->db->free_result($result);

			$log_word = $row['name'];

			return '
			<!-- Begin Password Input Box -->
			<form action="' . $this->action . '" method="post" enctype="multipart/form-data" onsubmit="submitonce(this);">
			' . border('sred', 'start', $log_word . ' ' . $roster->locale->act['auth_req']) . '
				<table class="bodyline" cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td class="membersRowRight1">' . $roster->locale->act['password'] . ':<br />
							<input name="password" class="wowinput192" type="password" size="30" maxlength="30" /></td>
					</tr>
					<tr>
						<td class="membersRowRight2" valign="bottom">
							<div style="float:right;"><input type="submit" value="Go" /></div>
							' . $this->getMessage() . '</td>
					</tr>
				</table>
			' . border('sred', 'end') . '
			</form>
			<!-- End Password Input Box -->';
		}
		else
		{
			return $this->getMessage();
		}
	}

	function getMenuLoginForm( )
	{
		if( !$this->allow_login )
		{
			return '
			<form action="' . $this->action . '" method="post" enctype="multipart/form-data" onsubmit="submitonce(this);" style="margin:0;">
				Log in: <input name="password" class="wowinput128" type="password" size="30" maxlength="30" />
				<input type="submit" value="Go" /> ' . $this->getMessage() . '
			</form>';
		}
		else
		{
			return $this->getMessage();
		}
	}

	function setAction( $action )
	{
		$this->action = makelink($action);
	}

	function rosterAccess( $values )
	{
		global $roster;

		if( count($this->levels) == 0 )
		{
			$query = "SELECT `account_id`, `name` FROM `" . $roster->db->table('account') . "`;";
			$result = $roster->db->query($query);

			if( !$result )
			{
				die_quietly($roster->db->error, 'Roster Auth', __FILE__, __LINE__, $query);
			}

			$this->levels[0] = 'Public';
			while( $row = $roster->db->fetch($result) )
			{
				$this->levels[$row['account_id']] = $row['name'];
			}
		}

		$input_field = '<select name="config_' . $values['name'] . '">' . "\n";
		$select_one = 1;
		foreach( $this->levels as $level => $name )
		{
			if( $level == $values['value'] && $select_one )
			{
				$input_field .= '  <option value="' . $level . '" selected="selected">-[ ' . $name . ' ]-</option>' . "\n";
				$select_one = 0;
			}
			else
			{
				$input_field .= '  <option value="' . $level . '">' . $name . '</option>' . "\n";
			}
		}
		$input_field .= '</select>';

		return $input_field;
	}
}
