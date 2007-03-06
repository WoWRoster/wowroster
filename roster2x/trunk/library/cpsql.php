<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/cpsql.php
 *
 * This is a extend of our cpsql class, when multi database support is required
 * a implimentation will need to be put in place, for now this is a abstract
 * class with a private construct to prevent instantiation within modules.
 *
 * -http://en.wikipedia.org/wiki/Modular
 *
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Legal Information:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 *
 * Full License:
 *  license.txt (Included within this library)
 *
 * You should have recieved a FULL copy of this license in license.txt
 * along with this library, if you did not and you are unable to find
 * and agree to the license you may not use this library.
 *
 * For questions, comments, information and documentation please visit
 * the official website at cpframework.org
 *
 * @link http://cpframework.org
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author WoWRoster.net
 * @version 1.5.0
 * @copyright 2000-2006 WoWRoster.net
 * @package cpFramework
 * @subpackage cpSQL
 * @filesource
 *
 * Roster versioning tag
 * $Id$
 */

/**
 * Our security measure, present in any file which does not contain
 * a direct access to our config itself. This is a security measure.
 */
if(!defined('SECURITY'))
{
	die("You may not access this file directly.");
}

/**
 * cpMySQLi: MySQLi wrapper.
 * @package cpFramework
 */
class cpsql
{
	/**
	 * Database connections
	 */
	private $connect = array();

	/**
	 * Active connection
	 */
	private $active = FALSE;

	/**
	 * Store queries
	 */
	private $queries = array();
	
	/**
	 * Query log
	 */
	private $qlog = array();

	/**
	 * Save configuration data privately
	 */
	private $config;

	/**
	 * The constructor optionally accepts 4 arguments that represent a DB
	 * configuration
	 *
	 * @param string $host   Name of the mysql host
	 * @param string $pass   Database password
	 * @param string $db     Name of the database
	 * @return void
	 * @access public
	 */
	public function __construct()
	{
		if( func_num_args() >= 5)
		{
			list($type, $host, $user, $pass, $name) = func_get_args();
		}
		else
		{
			$type =  cpMain::$instance['cpconfig']->cpconf['db_type'];
			$host =  cpMain::$instance['cpconfig']->cpconf['db_host'];
			$user =  cpMain::$instance['cpconfig']->cpconf['db_user'];
			$pass =  cpMain::$instance['cpconfig']->cpconf['db_pass'];
			$name =  cpMain::$instance['cpconfig']->cpconf['db_name'];
		}

		$this->configuration($type, $host, $user, $pass, $name)
				->connect('', TRUE);
	}

	/**
	 * Manually configure a DB connection.
	 *
	 * @param string $type	 Type of database
	 * @param string $host   Name of the mysql host
	 * @param string $user   Database user
	 * @param string $pass   Database password
	 * @param string $db	 Name of the database
	 *
	 * @param object $this   Self, for chaining
	 */
	public function configuration($type, $host, $user, $pass, $db)
	{
		$this->config = array('type'=>$type, 'host'=>$host, 'user'=>$user, 'pass'=>$pass, 'db'=>$db);
		
		return $this;
	}

	/**
	 * Connect using the previously set DB info.
	 *
	 * @param string $link_name		The name this link is identified by
	 * @param bool $activate		True to activate the link, false or omit not to
	 * @return object				Self, for chaining
	 */
	public function connect($link_name = '', $activate = FALSE)
	{
		// If there's already a database on the specified link, shut it down first
		if( $link_name != '' && isset($this->connect[$link_name]) )
		{
			if( $this->active === $this->connect[$link_name] )
			{
				$this->active = FALSE;
			}
			unset($this->connect[$link_name]);
		}
		elseif( $link_name == '')
		{
			$this->active = FALSE;
		}

		// Load the interface and the implementation for the specified type. If it's already loaded this still won't do harm
		cpMain::loadFile('cpsqlinterface');
		cpMain::loadFile('cpsql_'.$this->config['type']);

		// Create the DB
		$link = new $this->config['type']($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['db']);

		if( !( $link instanceof cpsqli ) )
		{
			throw new cpException(
				'cpSQL: Tried to create a DB object of type "'.$this->config['type'].'" but that class does not implement cpsqli'
			);
		}

		// Save the link
		if( $activate )
		{
			$this->active = $link;
		}
		if( $link_name != '' )
		{
			$this->connect[$link_name] = $link;
		}

		return $self;
	}

	/**
	 * Set the active DB link
	 *
	 * @param string $link_name     DB link to set active
	 *
	 * @return object               Self, for chaining
	 */
	public function set_active($link_name)
	{
		if( isset( $this->connect[$link_name] ) )
		{
			throw new cpException(
				'cpSQL: Unable to activate link "'.$link_name.'" because it is invalid'
			);
		}
		else
		{
			$this->active = $this->connect[$link_name];
		}
		
		return $this;
	}

	/**
	 * Set the active DB for a connection. If a link name is specified that link will be activated first
	 *
	 * @param string $db_name		The DB name to switch to
	 * @param string $link_name		The link to set the DB name for
	 *
	 * @return object               Self, for chaining
	 */
	public function select_db($db_name, $link_name = '')
	{
		if( $link_name != '' )
		{
			$this->set_active($link_name);
		}

		if( !$this->active )
		{
			throw new cpException(
				'cpSQL: Unable to select the database "'.$db_name.'" because there is no active link.'
			);
		}
		else
		{
			$this->active->select_db($db_name);
		}
		
		return $this;
	}

	/**
	 * Close a connection.
	 *
	 * @param string $link_name     Link name to close
	 *
	 * @return object               Self, for chaining
	 */
	public function close($link_name = '')
	{
		if( $link_name == '' )
		{
			$this->active = FALSE;
		}
		if( ($link_name != '') && isset($this->connect[$link_name]) )
		{
			if( $this->active === $this->connect[$link_name] )
			{
				$this->active = FALSE;
			}
			unset($this->connect[$link_name]);
		}
		
		return $this;
	}

	/**
	 * Create a query object with the specified query
	 * We're getting incompabitlbe with the old cpsql layer here
	 *
	 * @param string $query			The query to prepare
	 * @param string $query_name	The name to store the query under
	 * @param string $link_name		The link to execute it on, omit for active
	 * @return object				A cpMySQLi query object
	 */
	public function query_prepare($query, $query_name = '', $link_name = '')
	{
		$link = ( $link_name == '' ) ? $this->active : ( ( isset($this->connect[$link_name]) ) ? $this->connect[$link_name] : FALSE );

		if( !$link )
		{
			throw new cpException(
				'cpSQL: Unable to prepare query because the database link is invalid. The query was: '.$query
			);
		}

		$stmt = $link->query_prepare($query);

		if( $query_name != '')
		{
			$this->queries[$query_name] = $stmt;
		}
		
		return $stmt;
	}

	/**
	 * Get a query
	 *
	 * @param string $query_name	The name the query is stored under
	 */
	public function get_query($query_name)
	{
		if( !isset($this->queries[$query_name]) )
		{
			throw new cpException(
				'cpMySQLi: No query object to return under query name '.$query_name
			);
		}
		return $this->queries[$query_name];
	}
	
	/**
	 * Add a SQL log entry
	 *
	 * @param string $file          Filename that ran this SQL statement
	 * @param string $entry         Formatted log entry
	 *
	 * @param object                Self, for chaining
	 */
	public function qlog_add($file, $entry)
	{
		$this->qlog[$file][] = $entry;
		
		return $this;
	}
	
	/**
	 * Get the SQL log
	 *
	 * @return array                query log array
	 */
	public function get_qlog()
	{
		return $this->qlog;
	}
}