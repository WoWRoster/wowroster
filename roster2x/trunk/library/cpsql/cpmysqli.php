<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/class/cpsql/cpmysqli.php
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
class cpmysqli implements cpsqli
{
	/**
	 * mysqli
	 */
	private $db = FALSE;
	
	/**
	 * The constructor accepts 4 arguments that represent a DB configuration
	 *
	 * @param string $host		Name of the mysql host
	 * @param string $user		Database user
	 * @param string $pass		Database password
	 * @param string $db		Name of the database
	 * @return void
	 * @access public
	 */
	public function __construct($host, $user, $pass, $db)
	{
		$this->db = new mysqli($host, $user, $pass, $db);

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
	}

	/**
	 * Set the active DB
	 *
	 * @param string $db_name		The DB name to switch to
	 */
	public function select_db($db_name)
	{
		if( !$this->db->select_db($db_name) )
		{
			cpMain::cpErrorFatal
			(
				'cpMySQLi: MySQLi error, Unable to select the database "'.$db_name.'". MySQLi said:'.
				'Errno. '.$this->db->errno.': '.$this->db->error,
				__FILE__,
				__LINE__
			);
		}
	}

	/**
	 * Create a query object with the specified query
	 *
	 * @param string $query		The query to prepare
	 * @return object				A cpMySQLi query object
	 */
	public function query_prepare($query)
	{
		if( $stmt = $this->db->prepare($query) )
		{
			$stmt = new cpmysqli_stmt($stmt);
		}
		else
		{
			cpMain::cpErrorFatal
			(
				'cpMySQLi: MySQLi: Unable to prepare query. MySQLi said: '."<br/>\n".
				'Errno. '.$this->db->errno.': '.$this->db->error,
				__FILE__,
				__LINE__
			);
		}

		return $stmt;
	}
}

/**
 * SQL statement wrapper.
 * @package cpFramework
 */
class cpmysqli_stmt implements cpsqli_stmt
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

	/**
	 * Statement properties
	 */

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
