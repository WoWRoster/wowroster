<?php
/******************************
 * WoWRoster.net  UniAdmin
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

	/**
	 * Initialize user object
	 */
	function User( )
	{
		global $uniadmin, $tpl;

		$this->ip_address = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
		$this->user_agent = ( !empty($_SERVER['HTTP_USER_AGENT']) ) ? $_SERVER['HTTP_USER_AGENT'] : $_ENV['HTTP_USER_AGENT'];

		$this->lang_path = UA_LANGDIR;

		if( !isset($this->data['id']) )
		{
			$this->data['id'] = 0;
			$this->data['name'] = 'Guest';
			$this->data['password'] = '';
			$this->data['level'] = 0;
			$this->data['language'] = $uniadmin->config['default_lang'];
			$this->data['user_style'] = $uniadmin->config['default_style'];
		}

		// Set up language array
		$this->lang_name = ( file_exists($this->lang_path . $this->data['language'] . '.php') ) ? $this->data['language'] : $uniadmin->config['default_lang'];

		include($this->lang_path . $this->lang_name . '.php');

		$this->lang = &$lang;

		// Set up user style
		$this->style = ( file_exists(UA_THEMEDIR . $this->data['user_style'] . DIR_SEP . 'index.html') ) ? $this->data['user_style'] : $uniadmin->config['default_style'];

		$this->style = isset($this->data['user_style']) ? $this->data['user_style'] : $uniadmin->config['default_style'];

		$tpl->set_template($this->style);

		return;
	}

	/**
	 * Re-initializes user object with new user data
	 *
	 * @param array $data
	 * @return bool
	 */
	function create( $data )
	{
		if( is_array($data) )
		{
			$this->data = $data;

			$this->User();
		}
		else
		{
			return false;
		}

		return true;
	}
}

/**
 * Gets the user name from a cookie
 *
 * @return string
 */
function get_username( )
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

/**
 * Gets the current user's info
 *
 * @param string $name
 * @return array
 */
function get_user_info( $name='' )
{
	global $db;

	$username = ( $name == '' ? get_username() : $name );

	$sql = "SELECT * FROM `".UA_TABLE_USERS."` WHERE `name` = '$username';";
	$result = $db->query($sql);
	$row = mysql_fetch_assoc($result);

	return $row;
}
