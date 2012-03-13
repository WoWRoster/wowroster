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
setcookie('roster_hash',NULL,(time()-60*60*24*30*100) );
		$this->setAction($script_filename);

		if( isset( $_POST['logout'] ) && $_POST['logout'] == '1' )
		{
			setcookie('roster_user',NULL,time()-(60*60*24*30*100) );
			setcookie('roster_u',NULL,(time()-60*60*24*30*100) );
			setcookie('roster_k',NULL,(time()-60*60*24*30*100) );
			setcookie('roster_sid',NULL,(time()-60*60*24*30*100) );
			setcookie('roster_pass',NULL,time()-(60*60*24*30*100) );
			setcookie('roster_remember',NULL,time()-(60*60*24*30*100) );
			$this->allow_login = false;
			$this->valid = 0;
			$this->uid = 0;
			//$roster->sessions->endSession()
			$this->message = $roster->locale->act['logged_out'].$this->getLoginForm();
			$this->getLoginForm();
		}
		elseif( isset($_POST['password']) && $_POST['password'] != '' && isset($_POST['username']) && $_POST['username'] != '')
		{
			$this->checkPass(md5($_POST['password']),$_POST['username'],'1');
		}
		elseif( isset($_COOKIE['roster_pass']) && isset($_COOKIE['roster_user']) )
		{
			$this->checkPass($_COOKIE['roster_pass'],$_COOKIE['roster_user'],'0');
		}
		else
		{
			$this->allow_login = false;
			$this->message = '';
			setcookie('roster_user',NULL,time()-(60*60*24*30*100) );
			setcookie('roster_u',NULL,(time()-60*60*24*30*100) );
			setcookie('roster_k',NULL,(time()-60*60*24*30*100) );
			setcookie('roster_sid',NULL,(time()-60*60*24*30*100) );
			setcookie('roster_pass',NULL,time()-(60*60*24*30*100) );
			setcookie('roster_remember',NULL,time()-(60*60*24*30*100) );
		}
	}

	function checkPass( $pass, $user,$createsession )
	{
		global $roster;

		$query = "SELECT * FROM `" . $roster->db->table('user_members') . "` WHERE `usr`='".$user."' AND `pass`='".$pass."' limit 1;";
		$result = $roster->db->query($query);
		//echo (bool)$result;
		$count = $roster->db->num_rows($result);
		//echo $count;
		if( $count == 0 )
		{
			setcookie('roster_user',NULL,(time()-60*60*24*30*100) );
			setcookie('roster_u',NULL,(time()-60*60*24*30*100) );
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
			$remember = (isset($_POST['rememberMe']) ? (int)$_POST['rememberMe'] : (int)$_COOKIE['roster_remember'] );
			$row = $roster->db->fetch($result);
			
			setcookie('roster_user',$user,(time()+60*60*24*30) );
			setcookie('roster_u',$row['id'],(time()+60*60*24*30) );
			setcookie('roster_pass',$pass,(time()+60*60*24*30) );
			setcookie('roster_remember',$remember,(time()+60*60*24*30) );
			$this->valid = 1;
			$this->uid = $row['id'];
			$this->allow_login = true;
			$this->access = $row['access'];
			$this->logout = '<form class="inline slim" name="roster_logout" action="' . $this->action . '" method="post" enctype="multipart/form-data"><input type="hidden" name="logout" value="1" /> <button type="submit">' . $roster->locale->act['logout'] . '</button></form>';
			$this->message = '<span class="login-message">Welcome, '.$user.' '.$this->logout.'</span>';
			/*
			if ($createsession == '1')
			{
				$resul = $roster->session->session_create($row['id'], (in_array('11',$row['access']) ? true : false), true, true);
			}
			else
			{
				$roster->session->session_begin();
			}
			*/
				
			
			$roster->db->free_result($result);
			return true;

		}
		$roster->db->free_result($result);

		//setcookie('roster_user','',time()-(60*60*24*30*100) );
		//setcookie('roster_pass','',time()-(60*60*24*30*100) );
		//setcookie('roster_remember','',time()-(60*60*24*30*100) );
		$this->allow_login = false;
		$this->message = $roster->locale->act['login_invalid'];
		return;
	}

	function getAuthorized( $access )
	{
	
		$this->approved = false;
		$addon = array();
		$addon = explode(":",$access);
		$user = array();
		$user = explode(":",$this->access);

		foreach ($user as $x => $ac)
		{
			if (in_array($ac,$addon))
			{
				$this->approved = true;
			}
		}
		if ($this->access == ROSTERLOGIN_ADMIN)
		{
			$this->approved = true;
		}
		return $this->approved;
	
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

		/*
		if( $this->approved != 1 )
		{
			roster_die('Invalid access lvl to access admin section logout and login as admin or ask for admin access from admin');
		}
		*/
		if( !$this->allow_login && !isset($_POST['logout']) ) // && $this->approved==1 )
		{
			//echo 'check login';

			//$this->ValadateUser ();
			$login_message = $this->getMessage();

			$roster->tpl->assign_block_vars('login', array(
				'U_LOGIN_ACTION'  	=> (isset($this->action) ? $this->action : $action),
				'L_LOGIN_WORD'    	=> '',
				'S_LOGIN_MESSAGE' 	=> (bool)$login_message,
				'L_LOGIN_MESSAGE' 	=> $login_message,
				'L_REGISTER'		=> '<a href="'.makelink("user-user-register", true).'"><br>Register Here!</a>',
				'U_LOGIN' 			=>  0
			));

			$roster->tpl->set_handle('roster_login', 'login_new.html');
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
				'L_LOGIN_MESSAGE' => $login_message,
				'U_LOGIN' 			=>  $this->valid
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
				'L_LOGOUT_MESSAGE' => $logout_message,
				'U_LOGIN' 			=>  $this->valid
			));

			$roster->tpl->set_handle('roster_menu_logout', 'menu_logout.html');
			return $roster->tpl->fetch('roster_menu_logout');
		}
	}

	function setAction( $action )
	{
		$this->action = makelink($action);
	}

	function makeAccess( $values )
	{
		global $roster;

		if( count($this->levels) == 0 )
		{
			$query = "SELECT DISTINCT (`guild_rank`), `guild_title` FROM `" . $roster->db->table('members') . "` ORDER BY `guild_rank` ASC";
			$result = $roster->db->query($query);
			if( !$result )
			{
				die_quietly($roster->db->error, 'Roster Auth', __FILE__,__LINE__,$query);
			}
			$this->levels[11] = 'CP Admin';
			$this->levels[0] = 'Public';
			$x ='1';
			while( $row = $roster->db->fetch($result) )
			{
				$this->levels[($row['guild_rank']+1)] = $row['guild_title'];
				//$x++;
			}
		}
		$name = $values['name'];
		$x = '';
		$x .= '<div class="radioset">';
		$lvl = explode(":",$values['value']);
		foreach ($this->levels as $acc => $a)
		{
			$this->radid++;
			$x .= '<input type="checkbox" name="'.$name.'['.$acc.']" id="rad_config_'.$this->radid.'" value="'.$acc.'"  '.(in_array($acc, $lvl) ? 'checked="checked"' : '') .' />
			<label for="rad_config_'.$this->radid.'">'.substr($a,0,9).'</label>';
		}
		$x .= '</div>';
			
		return $x;
	}
	
	function GetAccess()
	{
		global $roster;

		if( count($this->levels) == 0 )
		{
			$query = "SELECT DISTINCT (`guild_rank` ), `guild_title` FROM `" . $roster->db->table('members') . "` ORDER BY `guild_rank` ASC";
			$result = $roster->db->query($query);

			if( !$result )
			{
				die_quietly($roster->db->error, 'Roster Auth', __FILE__,__LINE__,$query);
			}

			$this->levels[11] = 'Roster Admin';
			$this->levels[0] = 'Public';
			while( $row = $roster->db->fetch($result) )
			{
				$this->levels[($row['guild_rank']+1)] = $row['guild_title'];
			}
		}
		return $this->levels;
	}
	
	function rosterAccess( $values )
	{
		global $roster;

		if( count($this->levels) == 0 )
		{
			$query = "SELECT DISTINCT (`guild_rank`), `guild_title` FROM `" . $roster->db->table('members') . "` ORDER BY `guild_rank` ASC";
			$result = $roster->db->query($query);
			if( !$result )
			{
				die_quietly($roster->db->error, 'Roster Auth', __FILE__,__LINE__,$query);
			}
			$this->levels[11] = 'CP Admin';
			$this->levels[0] = 'Public';
			$x ='1';
			while( $row = $roster->db->fetch($result) )
			{
				$this->levels[($row['guild_rank']+1)] = $row['guild_title'];
				//$x++;
			}
			//$this->levels[11] = 'Public';
		}
			$name = $values['name'];
			$x = '';
			$x .= '<div class="radioset">';
			$lvl = explode(":",$values['value']);
			foreach ($this->levels as $acc => $a)
			{
				$this->radid++;
				$x .= '<input type="checkbox" name="config_'.$name.'['.$acc.']" id="rad_config_'.$this->radid.'" value="'.$acc.'"  '.(in_array($acc, $lvl) ? 'checked="checked"' : '') .' />
				<label for="rad_config_'.$this->radid.'">'.substr($a,0,9).'</label>';
			}
			$x .= '</div>';
			if ($roster->output['title'] == $roster->locale->act['pagebar_addoninst'])
			{
				//$x = '<div class="config-input">'.$x.'</div>';
			}
			//$x .= '</tr></table>';
		return $x;
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
		}else{
			return '0';
		}
	}
	function getUUID( $d )
	{
		global $roster;
		
		return hash('ripemd128',$d);
	}
	
	
}
