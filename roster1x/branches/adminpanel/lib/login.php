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

/**
 * Note: None of the variables in this class are required. Most functions are,
 * it is noted in the function comment if a function is not required.
 *
 * Also note the getAuthorized and login functions have to support the roster
 * admin account. The user for that is always Roster_Admin, the pass is defined
 * in $roster_conf['roster_upd_pw'].
 * Other functions should not support the admin account.
 */

class RosterLogin
{
	var $account;	// Account ID. -1 for roster admin. 0 for Guest.
	var $user;	// User name. Roster_Admin for roster admin.
	var $level;	// -1 roster admin, 0 GM, 0-9 guild ranks, 10 anonymous/not in guild
	var $message;	// Login result message
	var $loginform;	// Login form
	var $script_filename;

	/**
	 * Constructor for Roster Login class
	 * Parameter is the file any results should be sent to.
	 * THIS IS A REQUIRED FUNCTION SINCE YOU NEED THE $script_filename TO GENERATE PROPER FORMS
	 *
	 * @param string $script_filename
	 * @return RosterLogin
	 */
	function RosterLogin($script_filename)
	{
		$this->script_filename = $script_filename;
		$this->level = 11;

		$this->checkLogin();
		$this->checkLogout();
	}

	/**
	 * Try to verify supplied login creds. Not a required function.
	 */
	function checkLogin()
	{
		global $roster_conf, $wowdb;

		if( isset($_COOKIE['roster_pass']) )
		{
			if (get_magic_quotes_gpc())
			{
				$supplied = unserialize(stripslashes($_COOKIE['roster_pass']));
			}
			else
			{
				$supplied = unserialize($_COOKIE['roster_pass']);
			}
		}
		elseif( isset($_POST['user_name']) && isset($_POST['pass_word']) )
		{
			$supplied = array('user' => $_POST['user_name'], 'hash' => md5($_POST['pass_word']));
		}
		else
		{
			if( isset($_COOKIE['roster_pass']) )
				setcookie( 'roster_pass','',time()-86400,'/' );
			$this->message = '<span style="font-size:11px;color:red;">Not logged in</span><br />';
			$this->user = '';
			$this->level = 11;
			$this->account = 0;
			return;
		}

		if ($supplied['user'] == 'Roster_Admin')
		{
			$proper['account_id'] = -1;
			$proper['name'] = 'Roster_Admin';
			$proper['hash'] = $roster_conf['roster_upd_pw'];
			$proper['level'] = -1;
		}
		else
		{
			$query = 'SELECT `account_id`, `name`, `hash`, `level` FROM '.$wowdb->table('account').' WHERE `name` = "'.$supplied['user'].'"';

			$result = $wowdb->query($query);

			if (!$result)
			{
				if( isset($_COOKIE['roster_pass']) )
					setcookie( 'roster_pass','',time()-86400,'/' );
				$this->message = '<span style="font-size:11px;color:red;">Database problem: Unable to verify supplied credentials. MySQL said: '.$wowdb->error().'</span><br />';
				$this->user = '';
				$this->level = 11;
				$this->account = 0;
				return;
			}

			$proper = $wowdb->fetch_assoc($result);

			if (!$proper)
			{
				if( isset($_COOKIE['roster_pass']) )
					setcookie( 'roster_pass','',time()-86400,'/' );
				$this->message = '<span style="font-size:11px;color:red;">Incorrect user name or password</span><br />';
				$this->user = '';
				$this->level = 11;
				$this->account = 0;
				return;
			}

			$wowdb->free_result($result);
		}

		if( $supplied['hash'] == $proper['hash'] )
		{
			setcookie( 'roster_pass',serialize($supplied),0,'/' );
			$this->message = '<span style="font-size:10px;color:red;">Logged in as '.$proper['name'].' </span><form style="display:inline;" name="roster_logout" action="'.$this->script_filename.'" method="post"><span style="font-size:10px;color:#FFFFFF"><input type="hidden" name="logout" value="1" />[<a href="javascript:document.roster_logout.submit();">Logout</a>]</span></form><br />';
			$this->user = $proper['name'];
			$this->level = $proper['level'];
			$this->account = $proper['account_id'];
	}
		else
		{
			if( isset($_COOKIE['roster_pass']) )
				setcookie( 'roster_pass','',time()-86400,'/' );
			$this->message = '<span style="font-size:11px;color:red;">Incorrect user name or password</span><br />';
			$this->user = '';
			$this->level = 11;
			$this->account = 0;
		}
	}

	/**
	 * Logout if requested. Not a required function.
	 */
	function checkLogout()
	{
		if( isset( $_POST['logout'] ) && $_POST['logout'] == '1' )
		{
			if( isset($_COOKIE['roster_pass']) )
				setcookie( 'roster_pass','',time()-86400,'/' );
			$this->message = '<span style="font-size:10px;color:red;">Logged out</span><br />';
			$this->user = '';
			$this->level = 11;
			$this->account = 0;
		}
	}

	/**
	 * Check if the user is authorized to do this. There are 3 ways to call
	 * this function.
	 *
	 * @return boolean $creds
	 *	If the parameter is left out: True for roster admin, false
	 *	otherwise.
	 *
	 * @param string $creds
	 *	The credentials needed to perform this action.
	 *	For RosterAuth, this is a number, and lower numbers are better
	 *	credentials.
	 * @return boolean
	 *	True for allowed, false for disallowed.
	 *
	 * @param array $creds
	 *	Simple array($key => $creds,...) where $creds are required
	 *	credentials
	 * @return array
	 *	Simple array($key => $allowed,...) with the same keys as $creds,
	 *	$allowed is true if $creds[$key] is met.
	 */
	function getAuthorized($creds = '')
	{
		if ($creds == '')
		{
			return $this->level == -1;
		}

		if (!is_array($creds))
		{
			return $this->level <= $creds;
		}

		foreach ($creds as $key => $level)
		{
			$perms[$key] = ($this->level <= $level);
		}
		
		return $perms;
	}

	/**
	 * Return user name
	 *
	 * @return string $user
	 *	The user name. Empty string if not logged in.
	 */
	function getUserName()
	{
		return $this->user;
	}

	/**
	 * Return the result message for other class functions.
	 *
	 * @return string $html
	 *	The message.
	 */
	function getMessage()
	{
		return $this->message;
	}

	/**
	 * Return the login box
	 *
	 * @return string $html
	 *	The login box.
	 */
	function getLoginForm()
	{
		return '
			<!-- Begin Password Input Box -->
			<form action="'.$this->script_filename.'" method="post" enctype="multipart/form-data" onsubmit="submitonce(this)">
			'.border('sred','start','Please log in').'
			  <table class="bodyline" cellspacing="0" cellpadding="0">
			    <tr>
			      <td class="membersRow2">User Name: </td>
			      <td class="membersRowRight2"><input name="user_name" type="text" size="30" maxlength="30" /></td>
			    </tr>
			    <tr>
			      <td class="membersRow1">Password: </td>
			      <td class="membersRowRight1"><input name="pass_word" type="password" size="30" maxlength="30" /></td>
			    </tr>
			    <tr>
			      <td class="membersRowRight2" valign="bottom" colspan="2">
			        <div align="right"><input type="submit" value="Go" /></div></td>
			    </tr>
			  </table>
			'.border('sred','end').'
			</form>
			<!-- End Password Input Box -->';
	}

	/**
	 * Try to create an account
	 *
	 * @param string $user
	 *	Username
	 * @param string $pass1
	 *	The password
	 * @param string $pass2
	 *	The confirmed password
	 * @return boolean $success
	 *	True for success, false for failure
	 *
	 * A descriptive result message can be got from the getMessage function.
	 */
	function createAccount($user, $pass1, $pass2)
	{
		global $wowdb;
		
		if ( $user == 'Roster_Admin')
		{
			$this->message = 'Roster_Admin is a protected username';
			return false;
		}
		
		$query = 'SELECT COUNT(`name`) FROM `'.$wowdb->table('account').'` WHERE `name` = "'.$user.'"';
		
		$result = $wowdb->query($query);
		
		if (!$result)
		{
			$this->message = 'There was a database error while trying to create the account. MySQL said: <br />'.$wowdb->error();
			return false;
		}
		
		$row = $wowdb->fetch_row($result);
		$wowdb->free_result($result);
		
		if ( $row[0] > 0 )
		{
			$this->message = 'An account with that username already exists';
			return false;
		}

	 	if ( $newpass1 != $newpass2 )
	 	{
	 		$this->message = 'Passwords do not match. Please type the exact same password in both password fields.';
	 		return false;
	 	}

	 	if ( $pass1 === '' || $pass2 === '')
	 	{
	 		$this->message = 'No blank passwords. Please enter a password in both fields. Blank passwords are not allowed.';
	 		return false;
	 	}

	 	if ( md5($pass1) == md5('') )
	 	{
	 		$this->message = 'No blank passwords. You did not enter a blank password but it does have the same hash. Blank passwords are not allowed.';
	 		return false;
	 	}

	 	// valid password
		$query = 'INSERT INTO `'.$wowdb->table('account').'` VALUES (0, "'.$user.'", "'.md5($pass1).'", 10)';

		$result = $wowdb->query($query);

		if (!$result)
		{
			$this->message = 'There was a database error while trying to create the account. MySQL said: <br />'.$wowdb->error();
			return false;
		}

		$this->message = $user.', your password is <span style="font-size:11px;color:red;">'.$pass1.'</span>.<br /> Do not forget this password, it is stored encrypted only.';
		
		if ($this->user == '')
		{
			$supplied = array('user' => $user, 'hash' => md5($pass1));
			setcookie( 'roster_pass',serialize($supplied),0,'/' );
			
			$this->account = $wowdb->insert_id();			
			$this->user = $user;
			$this->level = 10;
		}

		return true;
	}

	/**
	 * Try to change the account password. This function does not and should
	 * never change the admin pass.
	 *
	 * @param string $user
	 *	Username
	 * @param string $oldpass
	 *	The old password
	 * @param string $newpass1
	 *	The password
	 * @param string $newpass2
	 *	The confirmed password
	 * @return boolean $success
	 *	True for success, false for failure
	 *
	 * A descriptive result message can be got from the getMessage function.
	 */
	function changePass($user, $oldpass, $newpass1, $newpass2)
	{
		global $wowdb;
		$query = 'SELECT `hash` FROM '.$wowdb->table('account').' WHERE `name` = "'.$user.'"';

		$result = $wowdb->query($query);

		if (!$result)
		{
			$this->message = 'Database problem: Unable to verify old password. MySQL said: <br />'.$wowdb->error();
			return false;
		}

		$row = $wowdb->fetch_assoc($result);

		if (!$row)
		{
			$this->message = 'Invalid old user name';
			return false;
		}

		$wowdb->free_result($result);

	 	if ( md5($oldpass) != $row['hash'] )
	 	{
	 		$this->message = 'Wrong password. Please enter the correct old password.';
	 		return false;
	 	}

	 	if ( $newpass1 != $newpass2 )
	 	{
	 		$this->message = 'Passwords do not match. Please type the exact same password in both new password fields.';
	 		return false;
	 	}

	 	if ( $newpass1 === '' || $newpass2 === '')
	 	{
	 		$this->message = 'No blank passwords. Please enter a password in both fields. Blank passwords are not allowed.';
	 		return false;
	 	}

	 	if ( md5($newpass1) == md5('') )
	 	{
	 		$this->message = 'No blank passwords. You did not enter a blank password but it does have the same hash. Blank passwords are not allowed.';
	 		return false;
	 	}

	 	if ( md5($newpass1) == $row['hash'] )
	 	{
	 		$this->message = 'Password not changed. The new password was the same as the old one.';
	 		return false;
	 	}

	 	// valid password
		$query = 'UPDATE `'.$wowdb->table('account').'` SET `hash` = "'.md5($newpass1).'"  WHERE `name` = "'.$user.'"';

		$result = $wowdb->query($query);

		if (!$result)
		{
			$this->message = 'There was a database error while trying to change the password. MySQL said: <br />'.$wowdb->error();
			return false;
		}

		$this->message = 'Password changed. The new password for '.$user.' is <span style="font-size:11px;color:red;">'.$_POST['newpass1'].'</span>.<br /> Do not forget this password, it is stored encrypted only.';
		
		if ($this->user == $user)
		{
			$supplied = array('user' => $user, 'hash' => md5($newpass1));
			setcookie( 'roster_pass',serialize($supplied),0,'/' );
		}
		
		return true;
	}

	/**
	 * Create a options control for setting an access level in Roster config.
	 *
	 * @param array $values
	 *	The form field name for this option
	 *
	 * @return string $html
	 *	The HTML for the option field
	 */
	function accessConfig($values)
	{
		return '<input name="config_'.$values['name'].'" type="text" value="'.$values['value'].'" size="4" maxlength="2" />';
	}

	/**
	 * Get the account ID for an account name
	 *
	 * @param string $name
	 *	The user name
	 * @return int $account_ID
	 *	The acount ID or false on error.
	 */
	function getAccountID($name)
	{
		global $wowdb;
		$query = 'SELECT `account_id` FROM '.$wowdb->table('account').' WHERE `name` = "'.$name.'"';

		$result = $wowdb->query($query);

		if (!$result)
		{
			$this->message = 'There was a database error while trying to fetch the account ID. MySQL said: <br />'.$wowdb->error();
			return false;
		}

		$row = $wowdb->fetch_assoc($result);
		
		if (!$result)
		{
			$this->message = 'There is no account for username '.$name;
			return false;
		}

		$wowdb->free_result($result);

		return $row['account_id'];
	}

	/**
	 * Get the account name for an account ID
	 *
	 * @param int $account_ID
	 *	The account ID
	 * @return string $name
	 *	The user name or false on error
	 */
	function getAccountName($account_id)
	{
		global $wowdb;
		$query = 'SELECT `name` FROM '.$wowdb->table('account').' WHERE `account_id` = "'.$account_id.'"';

		$result = $wowdb->query($query);

		if (!$result)
		{
			$this->message = 'There was a database error while trying to fetch the name. MySQL said: <br />'.$wowdb->error();
			return false;
		}

		$row = $wowdb->fetch_assoc($result);
		
		if (!$result)
		{
			$this->message = 'There is no account with ID '.$account_id;
			return false;
		}

		$wowdb->free_result($result);

		return $row['name'];
	}
	
	/**
	 * Called on character update for identity verification.
	 *
	 * @param string $char_name
	 *	The character name
	 * @return bool $accept
	 *	True to allow update, false to disallow
	 */
	function charUpdate($char_name)
	{
		global $wowdb;
		if ($this->account == 0) //Guest
		{
			$this->message = 'Please log in before uploading';
			return false;
		}
		elseif ($this->account == -1) // Roster admin
		{
			return true;
		}
				
		$query = 'SELECT `account_id` FROM `'.$wowdb->table('members').'` WHERE `name` = "'.$char_name.'"';
		
		$result = $wowdb->query($query);
		
		if (!$result)
		{
			$this->message = 'There was a database error while checking the owner of '.$char_name.'. MySQL said: <br />'.$wowdb->error();
			return false;
		}
		
		$row = $wowdb->fetch_assoc($result);
		
		$wowdb->free_result($result);
		
		if ($row['account_id'] == 0)
		{
			$query = 'UPDATE `'.$wowdb->table('members').'` SET `account_id` = '.$this->account.' WHERE `name` = "'.$char_name.'"';
			
			$result = $wowdb->query($query);
			
			return true;
		}		
		elseif ($row['account_id'] == $this->account)
		{
			return true;
		}
		else
		{
			$this->message = 'You cannot update someone else\'s characters.';
			return false;
		}
	}
	
	/**
	 * Called on guild list update after the updater has done its work. Any
	 * automated access modifications based on guild data can be done here.
	 *
	 * If you need the uploaded data on PHP side don't fetch from DB but use $update->uploadData
	 */
	function updateAccounts()
	{
		global $wowdb;
		$this->message = '';
		
		// Set account level to 10 for all accounts who don't have any chars registered.
		$query = 'UPDATE `'.$wowdb->table('account').'` '.
			'SET `level` = 10 '.
			'WHERE `account_id` NOT IN ('.
				'SELECT DISTINCT `account_id` '.
				'FROM `'.$wowdb->table('members').
			'`)';
		
		$result = $wowdb->query($query);
		
		if (!$result)
		{
			$this->message = '<li>Failed setting access level for accounts without characters'."\n";
		}
		else
		{
			$this->message = '<li>Turned '.$wowdb->affected_rows().' accounts without characters into guest accounts'."\n";
		}
		
		// Update all other accounts by pulling the correct level from the members table.
		$query = 'UPDATE `'.$wowdb->table('account').'` AS account '.
			'INNER JOIN ('.
					'SELECT `account_id`, MIN(`guild_rank`) AS `newlevel` '.
					'FROM `roster_members` GROUP BY `account_id`'.
				') AS `members` '.
				'ON `account`.`account_id` = `members`.`account_id` '.
			'SET `account`.`level` = `members`.`newlevel`';
		
		$result = $wowdb->query($query);
		
		if (!$result)
		{
			$this->message .= '<li>Failed at updating access levels for all accounts with members'."\n";
		}
		else
		{
			$this->message .= '<li>Updated access levels for '.$wowdb->affected_rows().' accounts with characters.'."\n";
		}
	}
	
	/**
	 * Add default credentials to the wowdb query (on member add)
	 */
	function addInitialCredentials()
	{
		global $wowdb;
		
		$wowdb->add_value('talents',$roster_conf['show_talents']);
		$wowdb->add_value('spellbook',$roster_conf['show_spellbook']);
		$wowdb->add_value('mail',$roster_conf['show_mail']);
		$wowdb->add_value('inv',$roster_conf['show_inventory']);
		$wowdb->add_value('money',$roster_conf['show_money']);
		$wowdb->add_value('bank',$roster_conf['show_bank']);
		$wowdb->add_value('recipes',$roster_conf['show_recipes']);
		$wowdb->add_value('quests',$roster_conf['show_quests']);
		$wowdb->add_value('bg',$roster_conf['show_bg']);
		$wowdb->add_value('pvp',$roster_conf['show_php']);
		$wowdb->add_value('duels',$roster_conf['show_duels']);
		$wowdb->add_value('item_bonuses',$roster_conf['show_item_bonuses']);
	}
}

?>
