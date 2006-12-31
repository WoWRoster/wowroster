<?php

/**
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

/*****************************************************************************
 * cpMySQLi: MySQLi wrapper.
 *****************************************************************************/
class cpmysqli implements cpsql
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
	 * Save configuration data privately
	 */
	private $config;

	/**
	 * Our construct allows the ability to auto config the
	 * mysql connection to allow better module flow and
	 * easier utilization of mysql.
	 *
	 * @param $_auto_conf bool Configure mysql automaticaly?
	 *   OR
	 * @params string $host, $user, $pass, $db_name connect information
	 *
	 * @return void
	 *
	 * @access public
	 */
	public function __construct()
	{
		if( ( func_num_args() == 0 ) || ( func_get_arg(0) == TRUE ) )
		{
			$this->configuration(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			$this->connect('', TRUE);
		}
		elseif( func_get_arg(0) == FALSE )
		{
			// do nothing
		}
		else
		{
			list($host, $user, $pass, $db) = func_get_args();
			$this->configuration($host, $user, $pass, $db);
			$this->connect('', TRUE);
		}
	}

	/**
	 * Manually configure a DB connection.
	 *
     * @param string $host       Name of the mysql host
     *
     * @param string $user       Database user
     *
     * @param string $password   Database password
     *
     * @param string $database   Name of the database
	 */
	public function configuration($host, $user, $pass, $db)
	{
		$this->config = array('host'=>$host, 'user'=>$user, 'pass'=>$pass, 'db'=>$db);
	}

	/**
	 * Connect using the previously set DB info.
	 *
	 * @param string $link_name		The name this link is identified by
	 *
	 * @param bool $activate		True to activate the link, false or omit not to
	 *
	 * @return object				MySQLi object
	 */
	public function connect($link_name = '', $activate = FALSE)
	{
		if( $link_name != '' && isset($this->connect[$link_name]) )
		{
			if( $this->active === $this->connect[$link_name] )
			{
				$this->active = FALSE;
			}
			unset($this->connect[$link_name]);
		}

		$link = new mysqli($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['db']);

		if( mysqli_connect_errno() )
		{
			cpMain::cpErrorFatal
			(
				'cpMySQLi: MySQLi Error, Unable to connect to the server, MySQLi Said: '.
				'Errorno '.mysqli_connect_errno().': '.mysqli_connect_error(),
				__FILE__,
				__LINE__
			);
		}

		if( $activate )
		{
			$this->active = $link;
		}
		if( $link_name != '' )
		{
			$this->connect[$link_name] = $link;
		}

		return $link;
	}

	/**
	 * Set the active DB link
	 */
	public function set_active($link_name)
	{
		if( isset( $this->connect[$link_name] ) )
		{
			cpMain::cpError
			(
				'cpMySQLi: Unable to activate link "'.$link_name.'" because it is invalid',
				__FILE__,
				__LINE__
			);
		}
		else
		{
			$this->active = $this->connect[$link_name];
		}
	}

	/**
	 * Set the active DB for a connection. If a link name is specified that link will be activated first
	 *
	 * @param string $db_name		The DB name to switch to
	 *
	 * @param string $link_name		The link to set the DB name for
	 */
	public function select_db($db_name, $link_name = '')
	{
		if( $link_name != '' )
		{
			$this->set_active($link_name);
		}

		if( !$this->active )
		{
			cpMain::cpErrorFatal
			(
				'cpMySQLi: Unable to select the database "'.$db_name.'" because there is no active link.',
				__FILE__,
				__LINE__
			);
		}
		elseif( !$this->active->select_db($db_name) )
		{
			cpMain::cpErrorFatal
			(
				'cpMySQLi: MySQLi error, Unable to select the database "'.$db_name.'". MySQLi said:'.
				'Errno. '.$this->active->errno.': '.$this->active->error,
				__FILE__,
				__LINE__
			);
		}
	}

	/**
	 * Close a connection.
	 */
	public function close($link_name = '')
	{
		if( $link_name == '' )
		{
			$this->active = FALSE;
		}
		if( ($link_name != '') && !isset($this->connect[$link_name]) )
		{
			if( $this->active === $this->connect[$link_name] )
			{
				$this->active = FALSE;
			}
			unset($this->connect[$link_name]);
		}
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
			cpMain::cpErrorFatal
			(
				'cpMySQLi: Unable to prepare query because the database link is invalid. The query was: '.$query,
				__FILE__,
				__LINE__
			);
		}

		if( $stmt = $this->active->prepare($query) )
		{
			$stmt = new cpmysqli_stmt($stmt);
		}
		else
		{
			cpMain::cpErrorFatal
			(
				'cpMySQLi: MySQLi: Unable to prepare query. MySQLi said: '."<br/>\n".
				'Errno. '.$this->active->errno.': '.$this->active->error,
				__FILE__,
				__LINE__
			);
		}

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
			cpMain::cpErrorFatal
			(
				'cpMySQLi: No query object to return under query name '.$query_name,
				__FILE__,
				__LINE__
			);
		}

		return $this->queries[$query_name];
	}
}

/*****************************************************************************
 * SQL statement wrapper.
 *****************************************************************************/
class cpmysqli_stmt implements cpsql_stmt
{
	/**
	 * Query object.
	 */
	private $qry;

	/**
	 * Caching for fetch functions
	 */
	private $cache;

	/**
	 * Constructor. Only usually called by the class above.
	 */
	public function __construct(mysqli_stmt $statement)
	{
		return $this->qry = $statement;
	}

	/**
	 * Prepare. Prepares a new query.
	 */
	public function prepare($query)
	{
		return $this->qry->prepare($query);
	}


	/**
	 * Bind parameters to the query. See also php.net/bind_params
	 *
	 * @param string $types		Parameter types
	 *
	 * @param array &$params	Parameter values, these need to be passed as an array
	 *							rather than seperately because of php restrictions
	 */
	public function bind_param($types, $params)
	{
		$args[0] = $types;
		foreach( $params as &$param )
		{
			$args[] =& $param;
		}
		call_user_func_array(array($this->qry, 'bind_param'), $args);
	}

	/**
	 * Send data for large blob/text fields.
	 *
	 * @param int $param_nr
	 *
	 * @param string $data
	 */
	public function send_long_data($param_nr, $data)
	{
		return $this->qry->send_long_data($param_nr, $data);
	}

	/**
	 * Execute
	 */
	public function execute()
	{
		$this->cache = array();
		return $this->qry->execute();
	}

	/**
	 * Buffer results
	 */
	public function store_result()
	{
		return $this->qry->store_result();
	}

	/**
	 * Bind the result variables for the query. See also php.net/bind_result
	 *
	 * @param array &$result	Return variables. Note to self: Examine behaviour and
	 *							document appropriately
	 */
	public function bind_result($result)
	{
		return call_user_func_array(array($this->qry, 'bind_result'), $result);
	}

	/**
	 * Fetch into bound variables
	 */
	public function fetch()
	{
		return $this->qry->fetch();
	}

	/**
	 * Fetch into associative array
	 */
	public function fetch_assoc()
	{
		if( !isset( $this->cache['bindAssoc'] ) | !isset( $this->cache['resAssoc'] ) )
		{
			$meta = $this->qry->result_metadata();

			while( $column = $meta->fetch_field() )
			{
				$this->cache['bindAssoc'][] = &$this->cache['resAssoc'][$column->name];
			}
		}

		call_user_func_array(array($this->qry, 'bind_result'), $this->cache['bindAssoc']);

		if( $this->qry->fetch() )
		{
			return $this->cache['resAssoc'];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Fetch into enumerated array
	 */
	public function fetch_row()
	{
		if( !isset( $this->cache['bindRow'] ) | !isset( $this->cache['resRow'] ) )
		{
			for( $i=0; $i<$this->qry->field_count; $i++)
			{
				$this->cache['bindRow'][] = &$this->cache['resRow'][$i];
			}
		}

		call_user_func_array(array($this->qry, 'bind_result'), $this->cache['bindRow']);

		if( $this->qry->fetch() )
		{
			return $this->cache['resRow'];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Fetch into both key types
	 */
	public function fetch_both()
	{
		if( !isset( $this->cache['bindBoth'] ) | !isset( $this->cache['resBoth'] ) )
		{
			$meta = $this->qry->result_metadata();

			$i = 0;

			while( $column = $meta->fetch_field() )
			{
				$this->cache['bindBoth'][] =& $this->cache['resBoth'][$column->name];
				$this->cache['resBoth'][$i++] =& $this->cache['resBoth'][$column->name];
			}
		}

		call_user_func_array(array($this->qry, 'bind_result'), $this->cache['bindBoth']);

		if( $this->qry->fetch() )
		{
			return $this->cache['resBoth'];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Reset query
	 */
	public function reset()
	{
		return $this->qry->reset();
	}

	/**
	 * Close query
	 */
	public function close()
	{
		return $this->qry->close();
	}

	/*************************************************************************
	 * Statement properties
	 *************************************************************************/

	/**
	 * Return number of affected rows by an INSERT, DELETE, or UPDATE statement
	 */
	public function affected_rows()
	{
		return $this->qry->affected_rows;
	}

	/**
	 * Return last error number for THIS QUERY
	 */
	public function errno()
	{
		return $this->qry->errno;
	}

	/**
	 * Return textual error for THIS QUERY
	 */
	public function error()
	{
		return $this->qry->error;
	}

	/**
	 * Return number of fields
	 */
	public function field_count()
	{
		return $this->qry->field_count;
	}

	/**
	 * Return autoincrement ID for THIS QUERY
	 */
	public function insert_id()
	{
		return $this->qry->insert_id;
	}

	/**
	 * Return number of rows
	 */
	public function num_rows()
	{
		return $this->qry->num_rows;
	}
}
