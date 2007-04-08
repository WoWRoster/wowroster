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
	function RosterLogin( $script_filename='' )
	{
		global $act_words, $roster_conf;

		$this->script_filename = makelink($script_filename);

		if( isset( $_POST['logout'] ) && $_POST['logout'] == '1' )
		{
			setcookie( 'roster_pass','',time()-86400,'/' );
			$this->allow_login = 0;
			$this->message = '<span style="font-size:10px;color:red;">Logged out</span><br />';
		}
		elseif( isset($_POST['password']) )
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
			$this->message = '<span style="font-size:10px;color:red;">Not logged in</span><br />';
		}
	}

	function checkPass( $pass )
	{
		global $wowdb;
		
		$query = "SELECT * FROM `".ROSTER_ACCOUNTTABLE."` ORDER BY `account_id` DESC;";
		$result = $wowdb->query($query);
		
		if( !$result )
		{
			setcookie( 'roster_pass','',time()-86400,'/' );
			$this->allow_login = 0;
			$this->message = '<span style="font-size:10px;color:red;">Failed to fetch password info</span><br />';
			return;
		}
		
		while( $row = $wowdb->fetch_assoc($result) )
		{
			if(( $row['hash'] == md5($pass) ) ||
				( $row['hash'] == $pass ))
			{
				setcookie( 'roster_pass',$row['hash'],0,'/' );
				$this->allow_login = $row['account_id'];
 				$this->message = '<span style="font-size:10px;color:red;">Logged in '.$row['name'].':</span><form style="display:inline;" name="roster_logout" action="'.$this->script_filename.'" method="post"><span style="font-size:10px;color:#FFFFFF"><input type="hidden" name="logout" value="1" />[<a href="javascript:document.roster_logout.submit();">Logout</a>]</span></form><br />';
				
				$wowdb->free_result($result);
				return;
			}
		}
		$wowdb->free_result($result);

		setcookie( 'roster_pass','',time()-86400,'/' );
		$this->allow_login = 0;
		$this->message = '<span style="font-size:10px;color:red;">Invalid password</span><br />';
		return;
	}

	function getAuthorized()
	{
		return $this->allow_login;
	}

	function getMessage()
	{
		return $this->message;
	}

	function getLoginForm( $level = 3 )
	{
		global $act_words, $wowdb;

		$query = "SELECT * FROM `".ROSTER_ACCOUNTTABLE."` WHERE `account_id` = '".$level."';";
		$result = $wowdb->query($query);
		
		if( !$result )
		{
			die_quietly($wowdb->error, 'Roster Auth', basename(__FILE__),__LINE__,$query);
		}
		
		if( $wowdb->num_rows($result) != 1 )
		{
			die_quietly('Invalid required login level specified', 'Roster Auth');
		}
		
		$row = $wowdb->fetch_assoc($result);
		$wowdb->free_result($result);
		
		$log_word = $row['name'];

		return '
			<!-- Begin Password Input Box -->
			<form action="'.$this->script_filename.'" method="post" enctype="multipart/form-data" onsubmit="submitonce(this)">
			'.border('sred','start',$log_word .' '. $act_words['auth_req']).'
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
	}
}
