<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster global class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: functions.lib.php 876 2007-05-05 05:19:20Z Zanix $
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}

class roster
{
	var $config = array();
	var $multilanguages = array();
	var $locale;
	var $db;
	var $pages;
	var $data = false; // scope data
	var $addon_data;

	var $output = array(
			'http_header' => true,
	        'show_header' => true,
	        'show_menu' => 'main',
	        'show_footer' => true,

	        // used on rostercp pages
	        'header' => '',
	        'menu' => '',
	        'body' => '',
	        'pagebar' => '',
	        'footer' => '',

	        // used on other pages
	        'content' => '',

	        // header stuff
	        'title' => '',
	        'html_head' => '',
	        'body_attr' => ''
	);

	/**
	 * Load the DBAL
	 */
	function load_dbal()
	{
		define('ROSTER_CONF_FILE',ROSTER_BASE . 'conf.php');

		// --[ Get the config file for the DB info. ]--
		if( !file_exists(ROSTER_CONF_FILE) )
		{
			header('Location: install.php');
		}
		else
		{
			require_once (ROSTER_CONF_FILE);
		}

		if( !defined('ROSTER_INSTALLED') )
		{
			header('Location: install.php');
		}

/*		switch ( $this->config['dbtype'] )
		{
			case 'mysql':
				include_once(UA_INCLUDEDIR . 'dbal' . DIR_SEP . 'mysql.php');
				break;

			default:
				include_once(UA_INCLUDEDIR . 'dbal' . DIR_SEP . 'mysql.php');
				break;
		}*/

		include_once(ROSTER_LIB.'dbal'.DIR_SEP.'mysql.php');

		$this->db = new roster_db($db_host, $db_name, $db_user, $db_passwd, $db_prefix);

		if ( !$this->db->link_id )
		{
			die(basename(__FILE__).': line['.(__LINE__).']<br />'.'Could not connect to database "'.$db_name.'"<br />MySQL said:<br />'.$this->db->connect_error());
		}
	}

	/**
	 * Load the config
	 */
	function load_config()
	{
		$query = "SELECT `config_name`, `config_value` FROM `" . $this->db->table('config') . "` ORDER BY `id` ASC;";
		$results = $this->db->query($query);

		if( !$results || $this->db->num_rows($results) == 0 )
		{
			die("Cannot get roster configuration from database<br />\nMySQL Said: " . $this->db->error() . "<br /><br />\nYou might not have roster installed<br />\n<a href=\"install.php\">INSTALL</a>");
		}

		while( $row = $this->db->fetch($results) )
		{
			$this->config[$row['config_name']] = $row['config_value'];
		}
		$this->db->free_result($results);
	}

	/**
	 * Figure out the page to load, and put it in $this->pages and ROSTER_PAGE_NAME
	 */
	function get_page_name()
	{
		// --[ mod_rewrite code ]--
		if( !isset($_GET[ROSTER_PAGE]) )
		{
			$uri = $_SERVER['REQUEST_URI'];
			$page = substr($uri,strlen(ROSTER_PATH));
			list($page) = explode('.',$page);
			$_GET[ROSTER_PAGE] = str_replace('/','-',$page);
		}

		// --[ Determine the module request ]--
		if( isset($_GET[ROSTER_PAGE]) && !empty($_GET[ROSTER_PAGE]) )
		{
			$page = $_GET[ROSTER_PAGE];
		}
		elseif( !strpos($this->config['default_page'], '&amp;') )
		{
			$page = $this->config['default_page'];
		}
		else
		{
			// --[ Insert directly into GET request ]--
			list($page, $gets) = explode('&amp;',$this->conf['default_page'],2);
			foreach( explode('&amp;',$gets) as $get )
			{
				list($key, $value) = explode('=',$get,2);
				$_GET[$key] = $value;
			}
		}

		define('ROSTER_PAGE_NAME', $page);

		$this->pages = explode('-', $page);

		// --[ We only accept certain characters in our page ]--
		if( preg_match('/[^a-zA-Z0-9_-]/', ROSTER_PAGE_NAME) )
		{
			roster_die($this->locale->act['invalid_char_module'],$this->locale->act['roster_error']);
		}
	}

	/**
	 * Get the data for the current scope and assign it to $this->data
	 */
	function get_scope_data()
	{
		// --[ Fetch the right data for the scope ]--
		switch( $this->pages[0] )
		{
			case 'char':
				// Check if the member attribute is set
				if( !isset($_GET['member']) )
				{
					roster_die('You need to provide a member id or name@server in character scope','WoWRoster');
				}

				// Parse the attribute
				if( is_numeric($_GET['member']) )
				{
					$where = ' `players`.`member_id` = "'.$_GET['member'].'"';
				}
				elseif( strpos('@',$_GET['member']) !== false )
				{
					list($name, $realm) = explode('@',$_GET['member']);
					$where = ' `players`.`name` = "'.$name.'" AND `players`.`server` = "'.$realm.'"';
				}
				else
				{
					$where = ' `players`.`name` = "'.$_GET['member'].'" AND `players`.`server` = "'.$this->config['server_name'].'"';
				}

				// Get the data
				$query = 'SELECT *, DATE_FORMAT(  DATE_ADD(`players`.`dateupdatedutc`, INTERVAL '.$this->config['localtimeoffset'].' HOUR ), "'.$this->locale->act['timeformat'].'" ) AS "update_format"'.
					'FROM `'.$this->db->table('players').'` players '.
					'LEFT JOIN `'.$this->db->table('members').'` members ON `players`.`member_id` = `members`.`member_id` '.
					'LEFT JOIN `'.$this->db->table('guild').'` guild ON `players`.`guild_id` = `guild`.`guild_id` '.
					'WHERE'.$where.';';

				$result = $this->db->query($query);

				if( !$result )
				{
					die_quietly($this->db->error(),'Database error',basename(__FILE__),__LINE__,$query);
				}

				if(!( $this->data = $this->db->fetch($result)) )
				{
					message_die('This member is not in the database',$this->locale->act['roster_error']);
				}

				$this->db->free_result($result);

				break;
			case 'guild':
				// If we ever go multiguild in 1x, we need to check the guild=
				// attribute here.
				$guild_escape = $this->db->escape( $this->config['guild_name'] );
				$server_escape = $this->db->escape( $this->config['server_name'] );

				$query = "SELECT * ".
					"FROM `".$this->db->table('guild')."` ".
					"WHERE `guild_name` = '".$guild_escape."' ".
						"AND `server` = '".$server_escape."';";

				$result = $this->db->query($query);

				if( !$result )
				{
					die_quietly($this->db->error(),'Database Error',basename(__FILE__).'<br />Function: '.(__FUNCTION__),__LINE__,$query);
				}

				if(!( $this->data = $this->db->fetch($result)) )
				{
					roster_die( sprintf($this->locale->act['nodata'], $this->config['guild_name'], $this->config['server_name'], makelink('update'), makelink('rostercp') ), $this->locale->act['nodata_title'] );
				}

				$this->db->free_result($result);

				break;
			default:
				$this->data = array();
				break;
		}
	}

	/**
	 * Fetch all addon data. We need to cache the active status for addon_active()
	 * and fetching everything isn't much slower and saves extra fetches later on.
	 */
	function get_addon_data()
	{
		$query = "SELECT * FROM `" . $this->db->table('addon') . "`;";
		$result = $this->db->query($query);
		$this->addon_data = array();
		while( $row = $this->db->fetch($result) )
		{
			$this->addon_data[$row['basename']] = $row;
		}
	}
}
