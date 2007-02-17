<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/class/cpsqlinterface.php
 *
 * Our SQL interface.
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
 * @author WoWRoster
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
 * cp SQL interface
 * @package cpFramework
 */
interface cpsqli
{
	/**
	 * The constructor accepts 4 arguments that represent a DB configuration
	 *
	 * @param string $host		Hostname the DB runs on
	 * @param string $user		Database user
	 * @param string $pass		Database password
	 * @param string $db		Name of the database
	 * @return void
	 * @access public
	 */
	public function __construct($host, $user, $pass, $db);

	/**
	 * Set the active DB for a connection
	 *
	 * @param string $db_name		The DB name to switch to
	 */
	public function select_db($db_name);

	/**
	 * Create a query object with the specified query
	 *
	 * @param string $query		The query to prepare
	 * @return object				A cpSQL query object
	 */
	public function query_prepare($query);
}

/**
 * cp SQL Statement
 * @package cpFramework
 */
interface cpsqli_stmt
{
	/**
	 * Prepare. Prepares a new query.
	 */
	public function prepare($query);

	/**
	 * Bind parameters to the query. See also php.net/bind_params
	 *
	 * @param string $types		Parameter types
	 * @param array &$params	Parameter values, these need to be passed as an array
	 *							rather than seperately because of php restrictions
	 */
	public function bind_param($types, $params);

	/**
	 * Execute
	 */
	public function execute();

	/**
	 * Buffer results
	 */
	public function store_result();

	/**
	 * Bind the result variables for the query. See also php.net/bind_result
	 *
	 * @param array &$result	Return variables. Note to self: Examine behaviour and
	 *							document appropriately
	 */
	public function bind_result($result);

	/**
	 * Fetch into bound variables
	 */
	public function fetch();

	/**
	 * Fetch into associative array
	 */
	public function fetch_assoc();

	/**
	 * Fetch into enumerated array
	 */
	public function fetch_row();

	/**
	 * Fetch into both key types
	 */
	public function fetch_both();

	/**
	 * Reset query
	 */
	public function reset();

	/**
	 * Close query
	 */
	public function close();

	/**
	 * Statement properties
	 */

	/**
	 * Return number of affected rows by an INSERT, DELETE, or UPDATE statement
	 */
	public function affected_rows();

	/**
	 * Return last error number for THIS QUERY
	 */
	public function errno();

	/**
	 * Return textual error for THIS QUERY
	 */
	public function error();

	/**
	 * Return number of fields
	 */
	public function field_count();

	/**
	 * Return autoincrement ID for THIS QUERY
	 */
	public function insert_id();

	/**
	 * Return number of rows
	 */
	public function num_rows();
}
