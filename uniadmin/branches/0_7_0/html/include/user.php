<?php

if( !defined('IN_UNIADMIN') )
{
	exit('Detected invalid access to this file!');
}

/**
 * User Class
 *
 * Stores user/global preferences
 * and language data
 */

class User
{
	var $data         = array();            // Data array               @var data
	var $style        = array();            // Style data               @var style
	var $lang         = array();            // Loaded language data     @var lang
	var $lang_name    = '';                 // Pack name (ie 'english') @var lang_name
	var $lang_path    = '';                 // Language path            @var lang_path
	var $user_agent   = '';                 // User Agent               @var user_agent
	var $ip_address   = 0;                  // User IP                  @var ip_address

	function start()
	{
		global $db, $uniadmin;

		$this->ip_address   = ( !empty($_SERVER['REMOTE_ADDR']) )     ? $_SERVER['REMOTE_ADDR']     : $REMOTE_ADDR;
		$this->user_agent      = ( !empty($_SERVER['HTTP_USER_AGENT']) ) ? $_SERVER['HTTP_USER_AGENT'] : $_ENV['HTTP_USER_AGENT'];

		$this->lang_path = UA_LANGDIR;

		// Set up language array
		if ( (isset($this->data['id'])) && ($this->data['id'] != UA_ID_ANON) && (!empty($this->data['language'])) )
		{
			$this->lang_name = ( file_exists(UA_LANGDIR . $this->data['language'] . '.php') ) ? $this->data['language'] : $uniadmin->config['default_lang'];
		}
		else
		{
			$this->lang_name = $uniadmin->config['default_lang'];
		}


		include($this->lang_path . $this->lang_name . '.php');

		$this->lang = &$lang;

		return;
	}

	function create(&$data)
	{
		if( is_array($data) )
		{
			$this->data = $data;

			$this->start();
		}
		else
		{
			return false;
		}

		return true;
	}
}



function GetUsername()
{
	if( isset($_COOKIE['UA']) )
	{
		$BigCookie = explode('|',$_COOKIE['UA']);
		return $BigCookie[0];
	}
	else
	{
		return '';
	}
}

function GetUserinfo($name='')
{
	global $dblink, $db;

	$username = ( $name == '' ? GetUsername() : $name );

	$sql = "SELECT * FROM `".UA_TABLE_USERS."` WHERE `name` = '$username';";
	$result = $db->query($sql);
	$row = mysql_fetch_assoc($result);

	return $row;
}
?>