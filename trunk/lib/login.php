<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Login and authorization
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
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

define('ROSTERLOGIN_ADMIN',3);

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
	function RosterLogin( $script_filename='' )
	{
		global $roster;

		$this->setAction($script_filename);

		if( isset( $_POST['logout'] ) && $_POST['logout'] == '1' )
		{
			setcookie( 'roster_pass','',time()-86400,'/' );
			$this->allow_login = 0;
			$this->message = $roster->locale->act['logged_out'];
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
			setcookie( 'roster_pass','',time()-86400,'/' );
			$this->allow_login = 0;
			$this->message = $roster->locale->act['login_fail'];
			return;
		}

		while( $row = $roster->db->fetch($result) )
		{
			if( ( $row['hash'] == md5($pass) ) ||
				( $row['hash'] == $pass )
			)
			{
				setcookie( 'roster_pass',$row['hash'],0,'/' );
				$this->allow_login = $row['account_id'];
 				$this->message = $roster->locale->act['logged_in'] . ' ' . $row['name'] . ': <form class="inline slim" name="roster_logout" action="' . $this->action . '" method="post"><input type="hidden" name="logout" value="1" /><input type="submit" value="' . $roster->locale->act['logout'] . '" /></form>';

				$roster->db->free_result($result);
				return;
			}
		}
		$roster->db->free_result($result);

		setcookie( 'roster_pass','',time()-86400,'/' );
		$this->allow_login = 0;
		$this->message = $roster->locale->act['login_invalid'];
		return;
	}

	function getAuthorized( $access )
	{
		return $this->allow_login >= $access;
	}

	function getMessage()
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
				die_quietly($roster->db->error, 'Roster Auth', __FILE__,__LINE__,$query);
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
			<form action="' . $this->action . '" method="post" enctype="multipart/form-data">
				<div class="tier-2-a login-box">
					<div class="tier-2-b">
						<div class="tier-2-title">
							<div style="float:right;"><input type="submit" value="Go" /></div>
							' . $roster->locale->act['auth_req'] . '
						</div>

						<div class="tier-3-a">
							<div class="tier-3-b">
								<div class="right">
									<input name="password" type="password" size="30" maxlength="30" />
								</div>
								<div class="text">
									' . $log_word . ' ' . $roster->locale->act['password'] . '
								</div>
							</div>
						</div>
						' . ( $this->getMessage() ? '<div class="message">' . $this->getMessage() . '</div>' : '') . '
					</div>
				</div>
			</form>
			<!-- End Password Input Box -->';
		}
		else
		{
			return $this->getMessage();
		}
	}

	function getMenuLoginForm()
	{
		global $roster;

		if( !$this->allow_login )
		{
			return '
			<form class="slim" action="' . $this->action . '" method="post" enctype="multipart/form-data">
				' . ( $this->getMessage() ? '<div class="message">' . $this->getMessage() . '</div>' : '') . '
				' . $roster->locale->act['login'] . '
				<input name="password" type="password" size="14" maxlength="30" />
				<input type="submit" value="Go" />
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
				die_quietly($roster->db->error, 'Roster Auth', __FILE__,__LINE__,$query);
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
				$input_field .= '  <option value="' . $level . '" selected="selected">' . $name . '</option>' . "\n";
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
