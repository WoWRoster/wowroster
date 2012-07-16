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
			if( ( $row['hash'] == md5($pass) ) || ( $row['hash'] == $pass ) )
			{
				setcookie( 'roster_pass',$row['hash'],0,'/' );
				$this->allow_login = $row['account_id'];
				$this->message = $roster->locale->act['logged_in'] . ' ' . $row['name'];

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

			$login_message = $this->getMessage();

			$roster->tpl->assign_vars(array(
				'U_LOGIN_ACTION'  => $this->action,
				'L_LOGIN_WORD'    => htmlspecialchars($row['name']),
				'S_LOGIN_MESSAGE' => (bool)$login_message,
				'L_LOGIN_MESSAGE' => $login_message
			));

			$roster->tpl->set_handle('roster_login', 'login.html');
			return $roster->tpl->fetch('roster_login');
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
			$login_message = $this->getMessage();

			$roster->tpl->assign_vars(array(
				'U_LOGIN_ACTION'  => $this->action,
				'S_LOGIN_MESSAGE' => (bool)$login_message,
				'L_LOGIN_MESSAGE' => $login_message
			));

			$roster->tpl->set_handle('roster_menu_login', 'menu_login.html');
			return $roster->tpl->fetch('roster_menu_login');
		}
		else
		{
			$logout_message = $this->getMessage();

			$roster->tpl->assign_vars(array(
				'U_LOGOUT_ACTION'  => $this->action,
				'S_LOGOUT_MESSAGE' => (bool)$logout_message,
				'L_LOGOUT_MESSAGE' => $logout_message
			));

			$roster->tpl->set_handle('roster_menu_logout', 'menu_logout.html');
			return $roster->tpl->fetch('roster_menu_logout');
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
