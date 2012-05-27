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

define('ROSTERLOGIN_ADMIN',11);

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
	var $logout;
	var $uid;
	var $levels = array();
	var $valid = 0;
	var $radid = 55;
	var $approved;
	var $access = 0;
	var $user = array();

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
			setcookie('roster_user',     NULL, time() - (60*60*24*30*100));
			setcookie('roster_u',        '0',  time() + (60*60*24*30));
			setcookie('roster_k',        NULL, time() - (60*60*24*30*100));
			setcookie('roster_sid',      NULL, time() - (60*60*24*30*100));
			setcookie('roster_pass',     NULL, time() - (60*60*24*30*100));
			setcookie('roster_remember', NULL, time() - (60*60*24*30*100));

			$this->allow_login = false;
			$this->valid = 0;
			$this->uid = 0;
			//$roster->sessions->endSession()
			$this->message = $roster->locale->act['logged_out'] . $this->getLoginForm();
			$this->getLoginForm();
		}
		elseif( isset($_POST['password']) && $_POST['password'] != '' && isset($_POST['username']) && $_POST['username'] != '')
		{
			$this->checkPass(md5($_POST['password']), $_POST['username'],'1');
			return;
		}
		elseif( isset($_COOKIE['roster_pass']) && isset($_COOKIE['roster_user']) )
		{
			$this->checkPass($_COOKIE['roster_pass'], $_COOKIE['roster_user'],'0');
			return;
		}
		else
		{
			$this->allow_login = false;
			$this->message = '';
			setcookie('roster_user',     NULL, time() - (60*60*24*30*100));
			setcookie('roster_u',        '0',  time() + (60*60*24*30));
			setcookie('roster_k',        NULL, time() - (60*60*24*30*100));
			setcookie('roster_sid',      NULL, time() - (60*60*24*30*100));
			setcookie('roster_pass',     NULL, time() - (60*60*24*30*100));
			setcookie('roster_remember', NULL, time() - (60*60*24*30*100));
		}
	}

	function checkPass( $pass, $user,$createsession )
	{
		global $roster;

		$query = "SELECT * FROM `" . $roster->db->table('user_members') . "` WHERE `usr`='" . $user . "' AND `pass`='" . $pass . "' LIMIT 1;";
		$result = $roster->db->query($query);
		//echo (bool)$result;
		$row = $roster->db->fetch($result);
		$count = $roster->db->num_rows($result);
		//echo $count;

		if( $count == 0 )
		{
			setcookie('roster_user',NULL,(time()-60*60*24*30*100) );
			setcookie('roster_u','0',(time()+60*60*24*30) );
			setcookie('roster_k',NULL,(time()-60*60*24*30*100) );
			setcookie('roster_hash',NULL,(time()-60*60*24*30*100) );
			setcookie('roster_sid',NULL,(time()-60*60*24*30*100) );
			setcookie('roster_pass',NULL,(time()-60*60*24*30*100) );
			setcookie('roster_remember',NULL,(time()-60*60*24*30*100) );
			$this->allow_login = false;
			$this->valid = 0;
			$this->message = $roster->locale->act['login_fail'];
			return false;
		}

		if( $count == 1 )
		{
			$remember = (isset($_POST['rememberme']) ? (int)$_POST['rememberme'] : (int)$_COOKIE['roster_remember'] );
			setcookie('roster_user',$user,(time()+60*60*24*30) );
			setcookie('roster_u',$row['id'],(time()+60*60*24*30) );
			setcookie('roster_pass',$pass,(time()+60*60*24*30) );
			setcookie('roster_remember',$remember,(time()+60*60*24*30) );
			$this->valid = 1;
			$this->uid = $row['id'];
			$this->allow_login = true;
			$this->user = $row;
			if ($row['active'] != 1)
			{
				//roster_die('Your account is nto active or has not been approved by the admin only "Public" areas can be accessed');
				$roster->set_message($roster->locale->act['login_inactive'], 'Roster Auth', 'error');
				$this->access = '0:';
			}
			else
			{
				$this->access = $row['access'];
			}

			$this->message = 'Welcome, ' . $user;
			$roster->db->free_result($result);

			return true;
		}

		$roster->db->free_result($result);

		setcookie('roster_u','0',(time()+60*60*24*30) );
		$this->allow_login = false;
		$this->message = $roster->locale->act['login_invalid'];
		return;
	}

	function getAuthorized( $access )
	{
		global $roster;

		$this->approved = false;
		$addon = array();
		$addon = explode(":",$access);
		$user = array();
		$user = explode(":",$this->access);
		foreach ($user as $x => $ac)
		{
			foreach ($addon as $a => $as)
			{
				if ( (int)$as ==  (int)$ac)
				{
					return true;
				}
			}
		}
		$roster->set_message( sprintf($roster->locale->act['addon_no_access'], $roster->pages[0]), 'Roster Auth', 'warning');
		return false;
	}

	function getMessage()
	{
		return $this->message;
	}

	function GetMemberLogin()
	{
		$this->getLoginForm();
	}
	function getLoginForm(  )
	{
		global $roster;

		if( !$this->allow_login && !isset($_POST['logout']) )
		{
			$roster->tpl->assign_vars(array(
				'U_LOGIN_ACTION'  => $this->action,
				'L_LOGIN_WORD'    => '',
				'S_LOGIN_MESSAGE' => (bool)$this->message,
				'L_LOGIN_MESSAGE' => $this->message,
				'L_REGISTER'      => '',
				'U_LOGIN'         => 0
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

		$roster->tpl->assign_vars(array(
			'U_LOGIN_ACTION'  => $this->action,
			'S_LOGIN_MESSAGE' => (bool)$this->message,
			'L_LOGIN_MESSAGE' => $this->message,
			'U_LOGIN'         => $this->valid
		));

		$roster->tpl->set_handle('roster_menu_login', 'menu_login.html');
		return $roster->tpl->fetch('roster_menu_login');
	}

	function setAction( $action )
	{
		$this->action = makelink($action);
	}

	function makeAccess( $values )
	{
		trigger_error('$roster->auth->makeAccess is depricated. Please update your code to use $roster->auth->rosterAccess.');
		$this->rosterAccess($values);
	}

	function rosterAccess( $values )
	{
		global $roster;

		// Only add levels if we have none stored
		if( count($this->levels) == 0 )
		{
			// Add built-in levels
			$this->levels[11] = 'CP Admin';
			$this->levels[0] = 'Public';

			if (isset($roster->data['guild_id']))
			{
				$query = "SELECT DISTINCT (`guild_rank`), `guild_title` FROM `". $roster->db->table('members') ."` WHERE `guild_id` = '". $roster->data['guild_id'] ."' ORDER BY `guild_rank` ASC;";
				$result = $roster->db->query($query);

				if( !$result )
				{
					die_quietly($roster->db->error, 'Roster Auth', __FILE__,__LINE__,$query);
				}

				while( $row = $roster->db->fetch($result) )
				{
					$this->levels[($row['guild_rank'] + 1)] = $row['guild_title'];
				}
			}
		}

		$name = $values['name'];
		$title = isset($values['title']) ? $values['title'] : $roster->locale->act['access_level'] .':';
		$this->radid++;

		$output = $title .' <select id="rad_config_'. $this->radid .'" name="config_'. $name .'[]" class="multiselect" multiple="multiple">';
		$lvl = explode(':', $values['value']);

		foreach ($this->levels as $acc => $a)
		{
			$output .= '<option value="'. $acc .'" '. (in_array($acc, $lvl) ? 'selected' : '') .'>'. $a ."</option>\n";
		}
		$output .= '</select>';

		return $output;
	}

	function getUID($user, $pass)
	{
		global $roster;

		$query = "SELECT `id` FROM `" . $roster->db->table('user_members') . "` WHERE `usr`='".$user."' AND `pass`='".$pass."';";
		$result = $roster->db->query($query);
		$rows = $roster->db->num_rows($result);
		$row = $roster->db->fetch($result);

		if ($rows == 1)
		{
			return $row['id'];
		}
		else
		{
			return '0';
		}
	}

	function getUUID( $d )
	{
		global $roster;

		return hash('ripemd128', $d);
	}

}
