<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster global class
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
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

/**
 * Roster global class
 *
 * @package    WoWRoster
 * @subpackage RosterClass
 */
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
	 * Roster API Object
	 *
	 * @var WowAPI
	 */
	var $api;

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
	var $data = false;		// scope data
	var $addon_data;

	/**
	 * Roster Error Handler Object
	 *
	 * @var roster_error
	 */
	var $error;				// Error handler class


	/**
	 * Roster Cache Class Object
	 *
	 * @var RosterCache
	 */
	var $cache;

	/**
	 * Roster Auth Object
	 *
	 * @var RosterLogin
	 */
	var $auth;

	var $output = array(
		'http_header' => true,
		'show_header' => true,
		'show_menu'   => array(
			'util'  => 0,
			'realm' => 0,
			'guild' => 0
		),
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
	var $tpl;				// Template object
	var $row_class = 2;		// For row striping in templates
	var $alt_row_class = 2;	// For row striping in templates


	/**
	 * Load the DBAL
	 */
	function load_dbal( )
	{
		global $db_config;

		switch( $db_config['dbtype'] )
		{
			case 'mysql':
				include_once (ROSTER_LIB . 'dbal' . DIR_SEP . 'mysql.php');
				break;

			case 'external':
				include_once (ROSTER_LIB . 'dbal' . DIR_SEP . 'external.php');
				break;

			default:
				include_once (ROSTER_LIB . 'dbal' . DIR_SEP . 'mysql.php');
				break;
		}

		$this->db = new roster_db($db_config['host'], $db_config['database'], $db_config['username'], $db_config['password'], $db_config['table_prefix']);
		$this->db->log_level();

		if( !$this->db->link_id )
		{
			die(__FILE__ . ': line[' . __LINE__ . ']<br />Could not connect to database "' . $db_config['database'] . '"<br />MySQL said:<br />' . $this->db->connect_error());
		}
	}

	/**
	 * Load the config
	 */
	function load_config( )
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

		/**
		 * Inject some different settings if the debug url switch is set
		 */
		if( isset($_GET['rdebug']) && is_numeric($_GET['rdebug']) )
		{
			switch( $_GET['rdebug'] )
			{
				case 2:
					$this->config['debug_mode'] = 2;
					$this->config['sql_window'] = 2;
					break;

				case 1:
				default:
					$this->config['debug_mode'] = 1;
					$this->config['sql_window'] = 1;
					break;
			}
		}

/*/ BETA ONLY, COMMENT THIS IN RC OR LATER!
// if these equal 0, force these on
		if( $this->config['debug_mode'] == 0 )
		{
			$this->config['debug_mode'] = 1;
		}
		if( $this->config['sql_window'] == 0 )
		{
			$this->config['sql_window'] = 1;
		}
// END BETA ONLY
*/
		$this->db->log_level($this->config['sql_window']);
	}

	/**
	 * Figure out the page to load, and put it in $this->pages and ROSTER_PAGE_NAME
	 */
	function get_page_name( )
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
			list($page, $gets) = explode('&amp;', $this->conf['default_page'], 2);
			foreach( explode('&amp;', $gets) as $get )
			{
				list($key, $value) = explode('=', $get, 2);
				$_GET[$key] = $value;
			}
		}

		// --[ We only accept certain characters in our page ]--
		if( preg_match('/[^a-zA-Z0-9_-]/', $page) )
		{
			roster_die($this->locale->act['invalid_char_module'], $this->locale->act['roster_error']);
		}

		define('ROSTER_PAGE_NAME', $page);

		$this->pages = explode('-', $page);

		if( in_array($this->pages[0], array('util', 'realm', 'guild', 'char')) )
		{
			$this->scope = $this->pages[0];
		}
		else
		{
			$this->scope = 'page';
		}
	}

	/**
	 * Get the data for the current scope and assign it to $this->data
	 */
	function get_scope_data( )
	{
		// --[ Resolve the anchor ]--
		$this->anchor = isset($_GET['a']) ? $_GET['a'] : '';

		if( empty($this->anchor) )
		{
			$this->atype = 'none';
		}
		elseif( strpos($this->anchor, ':') !== FALSE )
		{
			list($this->atype, $this->anchor) = explode(':', $this->anchor);
			switch( $this->atype )
			{
				case 'r':
				case 'realm':
					$this->atype = 'realm';
					break;

				case 'g':
				case 'guild':
					$this->atype = 'guild';
					break;

				case 'c':
				case 'char':
					$this->atype = 'char';
					break;

				default:
					$this->atype = 'none';
					break;
			}
		}
		elseif( strpos($this->anchor, '@') === FALSE )
		{
			$this->atype = 'realm';
		}
		else
		{
			// There is no way to see from the anchor if this is a guild anchor or a char anchor.
			// To keep it simple, we'll just assume it's an anchor for the current scope.
			$this->atype = $this->scope;
		}

		if( $this->atype == 'none' && in_array($this->scope, array('guild', 'realm')) )
		{
			// No anchor at all, but for realm/guild we have a default
			$defquery = "SELECT `name`, `server`, `region`"
				. " FROM `" . $this->db->table('upload') . "`"
				. " WHERE `default` = '1'"
				. " LIMIT 1;";

			$this->db->query($defquery);

			$data = $this->db->fetch();

			if( $data )
			{
				$name = $this->db->escape($data['name']);
				$realm = $this->db->escape($data['server']);
				$region = $this->db->escape($data['region']);

				$this->atype = 'default';
				$this->anchor = $name . '@' . $region . '-' . $realm;
			}
			else
			{
				$this->atype = 'none';
				$this->anchor = '';

//				roster_die(sprintf($this->locale->act['nodefguild'], makelink('rostercp-upload')), $this->locale->act['nodata_title']);
				$this->set_message(sprintf($this->locale->act['nodefguild'], makelink('rostercp-upload')), $this->locale->act['nodata_title'], 'error');
				$this->_break_scope();
				return;
			}
		}

		// --[ Build the query ]--
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
					list($name, $realm) = explode('@', $this->anchor);
					if( strpos($realm, '-') !== false )
					{
						list($region, $realm) = explode('-', $realm, 2);
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
				$query = 'SELECT guild.*, members.*, players.*, '
					. 'DATE_FORMAT( DATE_ADD(`players`.`dateupdatedutc`, INTERVAL ' . $this->config['localtimeoffset'] . ' HOUR ), "' . $this->locale->act['timeformat'] . '" ) AS "update_format" '
					. 'FROM `' . $this->db->table('players') . '` players '
					. 'LEFT JOIN `' . $this->db->table('members') . '` members ON `players`.`member_id` = `members`.`member_id` '
					. 'LEFT JOIN `' . $this->db->table('guild') . '` guild ON `players`.`guild_id` = `guild`.`guild_id` '
					. 'WHERE ' . $where . ";";

				$result = $this->db->query($query);

				if( !$result )
				{
					die_quietly($this->db->error(), 'Database error', __FILE__, __LINE__, $query);
				}

				if( !($this->data = $this->db->fetch($result)) )
				{
//					roster_die('The member ' . $this->anchor . ' is not in the database', $this->locale->act['roster_error']);
					$this->set_message(sprintf($roster->locale->act['no_char_in_db'], $this->anchor), $this->locale->act['roster_error'], 'error');
					$this->_break_scope();
					return;
				}

				$this->db->free_result($result);

				$this->anchor = $this->data['member_id'];

				// Scope specific functions
				$scope_class = new CharScope;
				$scope_class->set_tpl($this->data);
				$scope_class->alt_name_hover();
				$scope_class->mini_members_list();

				break;

			// We have a separate atype for default, but it loads a guild anchor from the uploads table.
			case 'guild':
			case 'default':
				if( in_array($this->scope, array('char')) )
				{
					$this->set_message($roster->locale->act['not_valid_anchor'], '', 'error');
					$this->anchor = 'none';
					$this->atype = 'none';
				}
				// Parse the attribute
				if( is_numeric($this->anchor) )
				{
					$where = ' `guild_id` = "' . $this->anchor . '"';
				}
				elseif( strpos($this->anchor, '@') !== false )
				{
					list($name, $realm) = explode('@', $this->anchor);
					if( strpos($realm, '-') !== false )
					{
						list($region, $realm) = explode('-', $realm, 2);
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
				$query = 'SELECT guild.* '
					. "FROM `" . $this->db->table('guild') . "` guild "
					. "WHERE " . $where . ";";

				$result = $this->db->query($query);

				if( !$result )
				{
					die_quietly($this->db->error(), 'Database Error', __FILE__ . '<br />Function: ' . __FUNCTION__, __LINE__, $query);
				}

				if( !($this->data = $this->db->fetch($result)) )
				{
//					roster_die(sprintf($this->locale->act['nodata'], $name, $realm, makelink('update'), makelink('rostercp-upload')), $this->locale->act['nodata_title']);
					$this->set_message(sprintf($this->locale->act['nodata'], $name, $realm, makelink('update'), makelink('rostercp-upload')), $this->locale->act['nodata_title'], 'error');
					$this->_break_scope();
					return;
				}

				$this->db->free_result($result);

				$this->anchor = $this->data['guild_id'];

				// Scope specific functions
				$scope_class = new GuildScope;
				$scope_class->set_tpl($this->data);

				break;

			case 'realm':
				if( in_array($this->scope, array('char', 'guild')) )
				{
					$this->set_message($roster->locale->act['not_valid_anchor'], '', 'error');
					$this->anchor = 'none';
					$this->atype = 'none';
				}
				if( strpos($this->anchor, '-') !== false )
				{
					list($region, $realm) = explode('-', $this->anchor, 2);
					$where = ' `server` = "' . $realm . '" '
						. 'AND `region` = "' . strtoupper($region) . '"';
				}
				else
				{
					$realm = $this->anchor;
					$where = ' `server` = "' . $realm . '" ';
				}

				// Check if there's data for this realm
				$query = "SELECT DISTINCT `server`, `region`"
					. " FROM `" . $this->db->table('guild') . "`"
					. " WHERE $where"
						. " UNION SELECT DISTINCT `server`, `region`"
						. " FROM `" . $this->db->table('players') . "`"
						. " WHERE $where"
						. " LIMIT 1;";

				$result = $this->db->query($query);

				if( !$result )
				{
					die_quietly($this->db->error(), 'Database Error', __FILE__ . '<br />Function: ' . __FUNCTION__, __LINE__, $query);
				}

				if( !($this->data = $this->db->fetch($result, SQL_ASSOC)) )
				{
//					roster_die(sprintf($this->locale->act['nodata'], '', $realm, makelink('update'), makelink('rostercp-upload')), $this->locale->act['nodata_title']);
					$this->set_message(sprintf($this->locale->act['nodata'], '', $realm, makelink('update'), makelink('rostercp-upload')), $this->locale->act['nodata_title'], 'error');
					$this->_break_scope();
					return;
				}

				$this->anchor = $this->data['region'] . '-' . $this->data['server'];

				// Scope specific functions
				$scope_class = new RealmScope;

				break;

			default:
				if( in_array($this->scope, array('char', 'guild', 'realm')) )
				{
					$this->set_message($this->locale->act['not_valid_anchor'], '', 'error');
					$this->anchor = 'none';
					$this->atype = 'none';
				}
				// no anchor passed, and we didn't load defaults so we're in util or page scope. No data needed.
				$this->data = array();

				// Scope specific functions
				$scope_class = new UtilScope;

				break;
		}

		// Figure out the armory url based on region
		if( isset($this->data['region']) )
		{
			switch( $this->data['region'] )
			{
				case 'US':
					$this->data['armoryurl'] = 'http://www.wowarmory.com';
					break;

				case 'EU':
					$this->data['armoryurl'] = 'http://eu.wowarmory.com';
					break;

				default:
					$this->data['armoryurl'] = '';
			}
		}

		// Set menu array
		if( isset($this->data['member_id']) )
		{
			$this->output['show_menu']['char'] = 1;
		}

		$this->output['show_menu'][$this->scope == 'page' ? 'util' : $this->scope] = 1;
	}

	function _break_scope()
	{
		// Scope specific functions
		$this->anchor = 'none';
		$this->atype = 'none';
		$this->scope = 'util';

		$this->data = array();
		$this->output['show_menu']['util'] = 1;
	}

	/**
	 * Fetch all addon data. We need to cache the active status for addon_active()
	 * and fetching everything isn't much slower and saves extra fetches later on.
	 */
	function get_addon_data( )
	{
		$query = "SELECT * FROM `" . $this->db->table('addon') . "` ORDER BY `basename`;";
		$result = $this->db->query($query);
		$this->addon_data = array();
		while( $row = $this->db->fetch($result, SQL_ASSOC) )
		{
			$this->addon_data[$row['basename']] = $row;
		}
	}

	/**
	 * Set a message which reflects the status of the performed operation.
	 * If the function is called with no arguments, this function returns all set messages without clearing them.
	 *
	 * @param string $message The message should begin with a capital letter and always ends with a period '.'.
	 * @param string $title (optional) The title of the message
	 * @param string $type The type of the message. One of the following values are possible: 'status' 'warning' 'error'
	 * @return Ambigous <NULL, unknown>
	 */
	function set_message( $message = NULL, $title = NULL, $type = 'status' )
	{
		if( $message )
		{
			if( !isset($_SESSION['messages'][$type]) )
			{
				$_SESSION['messages'][$type] = array();
			}
			$_SESSION['messages'][$type][] = array($title, $message);
		}
		// Messages not set when DB connection fails.
		return isset($_SESSION['messages']) ? $_SESSION['messages'] : NULL;
	}

	/**
	 * Return all messages that have been set.
	 *
	 * @param string $type (optional) Only return messages of this type.
	 * @param bool $clear_queue (optional) Set to FALSE if you do not want to clear the messages queue
	 * @return An associative array, the key is the message type, the value an array of messages.
	 * 		If the $type parameter is passed, you get only that type, or an empty array if there are no such messages.
	 * 		If $type is not passed, all message types are returned, or an empty array if none exist.
	 */
	function get_messages( $type = NULL, $clear_queue = TRUE )
	{
		if( $messages = $this->set_message() )
		{
			if( $type )
			{
				if( $clear_queue )
				{
					unset($_SESSION['messages'][$type]);
				}
				if( isset($messages[$type]) )
				{
					return array($type => $messages[$type]);
				}
			}
			else
			{
				if( $clear_queue )
				{
					unset($_SESSION['messages']);
				}
				return $messages;
			}
		}
		return array();
	}

	/**
	 * Switches the class for row coloring
	 *
	 * @param bool $set_new
	 * @return int
	 */
	function switch_row_class( $set_new = true )
	{
		$row_class = ($this->row_class == 1) ? 2 : 1;

		if( $set_new )
		{
			$this->row_class = $row_class;
		}

		return $row_class;
	}

	/**
	 * Switches the class for alternate row coloring
	 *
	 * @param bool $set_new
	 * @return int
	 */
	function switch_alt_row_class( $set_new = true )
	{
		$row_class = ($this->alt_row_class == 1) ? 2 : 1;

		if( $set_new )
		{
			$this->alt_row_class = $row_class;
		}

		return $row_class;
	}
}
