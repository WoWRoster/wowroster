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
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterClass
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

class roster
{
	var $config = array();
	var $multilanguages = array();

	/**
	 * Roster Locale Object
	 *
	 * @var roster_locale
	 */
	var $locale;

	/**
	 * Roster database Object
	 *
	 * @var roster_db
	 */
	var $db;
	var $pages;
	var $atype;
	var $anchor;
	var $scope;
	var $data = false; // scope data
	var $addon_data;

	/**
	 * Roster Error Handler Object
	 *
	 * @var roster_error
	 */
	var $error; // Error handler class

	/**
	 * Roster Cache Class Object
	 *
	 * @var RosterCache
	 */
	var $cache;

	var $output = array(
		'http_header' => true,
		'show_header' => true,
		'show_menu'   => array('util','realm','guild'),
		'show_footer' => true,

		// used on rostercp pages
		'header'  => '',
		'menu'    => '',
		'body'    => '',
		'pagebar' => '',
		'footer'  => '',

		// used on other pages
		'content' => '',

		// header stuff
		'title'       => '',
		'html_head'   => '',
		'body_attr'   => '',
		'body_onload' => '',
		'before_menu' => ''
	);

	/**
	 * Roster Template Object
	 *
	 * @var RosterTemplate
	 */
	var $tpl;								// Template object
	var $row_class         = 2;				// For row striping in templates

	/**
	 * Load the DBAL
	 */
	function load_dbal()
	{
		global $db_config;

		switch( $db_config['dbtype'] )
		{
			case 'mysql':
				include_once(ROSTER_LIB . 'dbal' . DIR_SEP . 'mysql.php');
				break;

			default:
				include_once(ROSTER_LIB . 'dbal' . DIR_SEP . 'mysql.php');
				break;
		}

		$this->db = new roster_db($db_config['host'], $db_config['database'], $db_config['username'], $db_config['password'], $db_config['table_prefix']);

		if ( !$this->db->link_id )
		{
			die(__FILE__ . ': line[' . __LINE__ . ']<br />Could not connect to database "' . $db_config['database'] . '"<br />MySQL said:<br />' . $this->db->connect_error());
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
		// cmslink function to resolve SEO linking etc.
		parse_params();

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
		if( in_array( $this->pages[0], array('util','realm','guild','char') ) )
		{
			$this->scope = $this->pages[0];
		}
		else
		{
			$this->scope = 'page';
		}

		// --[ Resolve the anchor ]--
		$this->anchor = isset($_GET['a'])?$_GET['a']:'';

		if( empty($this->anchor) )
		{
			$this->atype = 'none';
		}
		elseif( strpos($this->anchor, ':') !== FALSE )
		{
			list($this->atype, $this->anchor) = explode(':', $this->anchor);
			switch( $this->atype )
			{
			case 'r': case 'realm':
				$this->atype = 'realm';
				break;
			case 'g': case 'guild':
				$this->atype = 'guild';
				break;
			case 'c': case 'char':
				$this->atype = 'char';
				break;
			default:
				$this->atype = 'none';
				break;
			}
		}
		elseif( strpos( $this->anchor, '@' ) === FALSE )
		{
			$this->atype = 'realm';
		}
		else
		{
			// There is no way to see from the anchor if this is a guild anchor or a char anchor. To keep
			// it simple, we'll just assume it's an anchor for the current scope.
			$this->atype = $this->scope;
		}

		// --[ Build the select part of the query, and validate the anchor is accurate enough ]--
		switch( $this->scope )
		{
		case 'char':
			if( !in_array( $this->atype, array('char') ) )
			{
				roster_die('The a= parameter does not provide accurate enough data or is badly formatted.','WoWRoster');
			}
			$query = 'SELECT guild.*, members.*, players.*, '
				. 'DATE_FORMAT(  DATE_ADD(`players`.`dateupdatedutc`, INTERVAL '
				. $this->config['localtimeoffset'] . ' HOUR ), "' . $this->locale->act['timeformat'] . '" ) AS "update_format" ';
			break;
		case 'guild':
			if( !in_array( $this->atype, array('char','guild','none') ) )
			{
				roster_die('The a= parameter does not provide accurate enough data or is badly formatted.','WoWRoster');
			}
			$query = 'SELECT guild.* ';
			break;
		case 'realm':
			$query = 'SELECT `region`,`server` ';
			break;
		default:
			// Util doesn't load any data.
			$query = 'SELECT 1 ';
			break;
		}

		if( $this->atype == 'none' && in_array($this->scope, array('guild','realm')) )
		{
			// No anchor at all. 
			$defquery =  "SELECT `name`, `server`, `region`"
				. " FROM `" . $this->db->table('upload') . "`"
				. " WHERE `default` = '1' LIMIT 1;";

			$this->db->query($defquery);

			$data = $this->db->fetch();

			$name = $this->db->escape( $data['name'] );
			$realm = $this->db->escape( $data['server'] );
			$region = $this->db->escape( $data['region'] );

			$this->atype = 'default';
			$this->anchor = $name . '@' . $region . '-' . $realm;
		}

		// --[ Build the from and where parts of the query ]--
		switch( $this->atype )
		{
			case 'char':
				// Parse the attribute
				if( is_numeric($this->anchor) )
				{
					$where = ' `players`.`member_id` = "' . $this->anchor . '"';
				}
				elseif( strpos($this->anchor, '@') !== false )
				{
					list($name, $realm) = explode('@',$this->anchor);
					if( strpos($realm,'-') !== false )
					{
						list($region, $realm) = explode('-',$realm,2);
						$where = ' `players`.`name` = "' . $name . '" '
							. 'AND `players`.`server` = "' . $realm . '" '
						        . 'AND `players`.`region` = "' . strtoupper($region) . '" ';
					}
					else
					{
						$where = ' `players`.`name` = "' . $name . '" '
						       . 'AND `players`.`server` = "' . $realm . '" ';
					}
				}
				else
				{
					$name = $this->anchor;
					$where = ' `players`.`name` = "' . $name . '"';
				}

				// Get the data
				$query .= 'FROM `' . $this->db->table('players') . '` players '
					. 'LEFT JOIN `'.$this->db->table('members') . '` members ON `players`.`member_id` = `members`.`member_id` '
					. 'LEFT JOIN `'.$this->db->table('guild').'` guild ON `players`.`guild_id` = `guild`.`guild_id` '
					. 'WHERE ' . $where . ";";

				$result = $this->db->query($query);

				if( !$result )
				{
					die_quietly($this->db->error(),'Database error',__FILE__,__LINE__,$query);
				}

				if(!( $this->data = $this->db->fetch($result)) )
				{
					roster_die('The member ' . $this->anchor . ' is not in the database',$this->locale->act['roster_error']);
				}

				$this->db->free_result($result);

				break;

			// We have a separate atype for default, but it loads a guild anchor from the uploads table.
			case 'guild': case 'default':
				// Parse the attribute
				if( is_numeric($this->anchor) )
				{
					$where = ' `guild_id` = "' . $this->anchor . '"';
				}
				elseif( strpos($this->anchor, '@') !== false )
				{
					list($name, $realm) = explode('@', $this->anchor);
					if( strpos($realm,'-') !== false )
					{
						list($region, $realm) = explode('-',$realm,2);
						$where = ' `guild_name` = "' . $name . '" '
							. 'AND `server` = "' . $realm . '" '
							. 'AND `region` = "' . strtoupper($region) . '" ';
					}
					else
					{
						$where = ' `guild_name` = "' . $name . '" '
							. 'AND `server` = "' . $realm . '" ';
					}
				}
				else
				{
					$name = $this->anchor;
					$where = ' `guild_name` = "' . $name . '"';
				}

				// Get the data
				$query .= "FROM `" . $this->db->table('guild') . "` guild "
					. "WHERE " . $where . ";";

				$result = $this->db->query($query);

				if( !$result )
				{
					die_quietly($this->db->error(),'Database Error',__FILE__.'<br />Function: '.__FUNCTION__,__LINE__,$query);
				}

				if(!( $this->data = $this->db->fetch($result)) )
				{
					roster_die( sprintf($this->locale->act['nodata'], $name, $realm, makelink('update'), makelink('rostercp-upload') ), $this->locale->act['nodata_title'] );
				}

				$this->db->free_result($result);

				break;

			case 'realm':
				if( strpos($this->anchor,'-') !== false )
				{
					list($region, $realm) = explode('-',$_GET['realm'],2);
					$where = ' `server` = "' . $realm . '" '
						. 'AND `region` = "' . strtoupper($region) . '"';
				}
				else
				{
					$realm = $_GET['realm'];
					$where = ' `server` = "' . $realm . '" ';
				}

				// Check if there's data for this realm
				$query = "SELECT DISTINCT `server`, `region`"
					   . " FROM `" . $this->db->table('guild') . "`"
					   . " UNION SELECT DISTINCT `server`, `region` FROM `" . $this->db->table('players') . "`"
					   . " WHERE $where"
					   . " LIMIT 1;";

				$result = $this->db->query($query);

				if( !$result )
				{
					die_quietly($this->db->error(), 'Database Error', __FILE__ . '<br />Function: ' . __FUNCTION__, __LINE__, $query);
				}

				if(!( $this->data = $this->db->fetch($result,SQL_ASSOC)) )
				{
					roster_die( sprintf($this->locale->act['nodata'], '', $realm, makelink('update'), makelink('rostercp-upload') ), $this->locale->act['nodata_title'] );
				}

				break;
			default:
				// no anchor passed, and we didn't load defaults so we're in util or page scope. No data needed.
				$this->data = array();
		}

		// Set menu array
		if( isset($this->data['member_id']) )
		{
			$this->output['show_menu'][] = 'char';
		}
	}

	/**
	 * Fetch all addon data. We need to cache the active status for addon_active()
	 * and fetching everything isn't much slower and saves extra fetches later on.
	 */
	function get_addon_data()
	{
		$query = "SELECT * FROM `" . $this->db->table('addon') . "` ORDER BY `basename`;";
		$result = $this->db->query($query);
		$this->addon_data = array();
		while( $row = $this->db->fetch($result,SQL_ASSOC) )
		{
			$this->addon_data[$row['basename']] = $row;
		}
	}

	/**
	 * Switches the class for row coloring
	 *
	 * @param bool $set_new
	 * @return int
	 */
	function switch_row_class( $set_new = true )
	{
		$row_class = ( $this->row_class == 1 ) ? 2 : 1;

		if( $set_new )
		{
			$this->row_class = $row_class;
		}

		return $row_class;
	}
}