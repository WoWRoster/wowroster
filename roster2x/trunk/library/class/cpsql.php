<?php

/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/class/cpsql.php
 *
 * Class to handle all database abstraction and insertion, any specific
 * objects from the class should not be placed within our global scope, the
 * ideal of this class is to seperate all MySQL database interaction to
 * better control the security and procedural events of our database.
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
 * @author Chris Stockton
 * @version 1.5.0
 * @copyright 2000-2006 Chris Stockton
 * @package cpFramework
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
 * Our Database Class, database abstraction is handled via this class
 * @package cpFramework
 */
class cpsql
{

    /**
     * Connect status, stores connection result
     *
     * @var special
     *
     * @access public
     */
    private $connect  = Array();

    /**
     * Holds the link identifier of querries
     *
     * @var array
     *
     * @access public
     */
    private $result   = Array();

    /**
     * Stores the fetched arrays from sql results
     *
     * @var array
     *
     * @access public
     */
    public $record   = Array();

    /**
     * Our construct allows the ability to auto config the
     * mysql connection to allow better module flow and
     * easier utilization of mysql.
     *
     * @param $_auto_conf bool Configure mysql automaticaly?
     *
     * @return void
     *
     * @access public
     */
    public function __construct($_auto_conf = TRUE)
    {
        if($_auto_conf === TRUE)
        {
            self::configuration(DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_TYPE);
            self::connect("default", TRUE);
            self::select_db(DB_NAME);
        }
    }

    /**
     * Configuration of the sql class, it sets the mysql connection
     * variables for the appropiate scope.
     *
     * @param string $host       Name of the mysql host
     *
     * @param string $database   Name of the database
     *
     * @param string $user       Database user
     *
     * @param string $password   Database password
     *
     * @return void
     *
     * @access public
     */
    public function configuration($host, $database, $user, $password, $type)
    {
        $this->host = $host;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
        $this->type = $type;
    }

    /**
     * Initiates the MySQL Connection and sets the link identifier
     * appropiately, errors out upon a false return
     *
     * @return link    mysql link identifier
     *
     * @access public
     */
    public function connect($link_name = "default", $activate = FALSE)
    {
        if(!isset($this->connect[$link_name]))
        {
            $this->connect[$link_name] = FALSE;
        }
        if($this->connect[$link_name] == 0)
        {
            $this->connect[$link_name] = mysql_connect($this->host, $this->user, $this->password);
            if($activate == TRUE)
            {
                $this->connect['cpsql_active'] = $link_name;
            }
            if($this->connect[$link_name])
            {
                return $this->connect[$link_name];
            }
             else
            {
                cpMain::cpErrorFatal
                (
                    "MySQL Error, Unable to connect to the server, MySQL Said: " .
                    "Errorno " . mysql_errno() . ": " . mysql_error(),
                    __FILE__,
                    __LINE__
                );
            }
        }
    }

    /**
     * Sets the appropiate active connection, we do not check if
     * the link is indeed a true link.. this may change
     *
     * @return link    mysql link identifier
     *
     * @access public
     */
    public function set_active($link_name)
    {
        $this->connect['cpsql_active'] = $link_name;
        return true;
    }

    /**
     * Connects to the database
     *
     * @return link     database link
     *
     * @access public
     */
    public function select_db($database, $link_name = "default")
    {
        if(!$this->connect[$link_name])
        {
            cpMain::cpErrorFatal
            (
                "MySQL Error, unable to select the database \"".$database."\" because the system could not find a active connection to the server, MySQL Said: " .
                "Errorno " . mysql_errno($this->connect[$link_name]) . ": " . mysql_error($this->connect[$link_name]),
                __FILE__,
                __LINE__
            );
        }
         else
        {
            if(!@mysql_select_db($database, $this->connect[$link_name]))
            {
                cpMain::cpErrorFatal
                (
                    "MySQL Error, Unable to select database \"".$database."\", MySQL Said: " .
                    "Errorno " . mysql_errno($this->connect[$link_name]) . ": " . mysql_error($this->connect[$link_name]),
                    __FILE__,
                    __LINE__
                );
            }
        }
    }

    /**
     * This function unprepares querys and strips slashes from previous safely
     * inserted rows
     *
     * @param string $query      String containing the unprepared MySQL Query
     *
     *
     * @return string            A return of the parsed string.
     *
     * @access public
     */
    public function query_unprepare($string)
    {
        return stripslashes($string);
    }

    /**
     * This function prepares the query and applies proper slashes
     * to prevent malicious SQL injections.
     *
     * @param string $query      String containing the unprepared MySQL Query
     *
     * @param string get_args    Excess args are caught and assigned to our
     *                           $args array, these values are applied to our
     *                           sprintf and processed safely.
     *
     * @return string            A return of the parsed string.
     *
     * @access public
     */
    public function query_prepare($query)
    {
        $args = func_get_args();
		$query = str_replace("?", "%s", (is_array((isset($args[1])) ? $args[1] : $args) ? $query : array_shift($args)));
        $args = (get_magic_quotes_gpc()) ? array_map('stripslashes', $args) : $args;
		$args = array_map('mysql_real_escape_string', (is_array((isset($args[1])) ? $args[1] : $args) ? ((count($args) > 1) ? $args[1] : $args) : $args));
		array_unshift($args, $query);
        return call_user_func_array('sprintf', $args);
    }

    /**
     * This function executes querys and assigns proper values
     * to our class result subjects. Function assumes a safe string
     * prepared previously by $->query_prepare
     *
     * @param string $query      String containing a prepared MySQL Query
     *
     * @param string $name       Unique identifier for the query to be executed
     *
     * @param bool   $fetch      Defines wether or not the query should fetch
     *                           the result and assign to our $->record
     *
     * @return special           A unique identifier of the sql subject
     *
     * @access public
     */
    public function query($query, $name = "")
    {
        if($name == "")
        {
        echo "QUERRIED";
            mysql_query($query) or
            cpMain::cpError
            (
                "MySQL Error, Could not run the query, mysql said: " .
                "Errorno " . mysql_errno($this->connect[$this->connect['cpsql_active']]) . ": " . mysql_error($this->connect[$this->connect['cpsql_active']]) .
                " when trying <b>".$query . "</b>",
                __LINE__,
                __FILE__
            );
        }
         else
        {

            if(isset($this->result[$name]))
            {
                mysql_free_result($this->result[$name]);
            }

            $this->result[$name] = mysql_query($query);

            if(!$this->result[$name])
            {
                cpMain::cpError
                (
                    "MySQL Error, Could not run the query, mysql said: " .
                    "Errorno " . mysql_errno($this->connect[$this->connect['cpsql_active']]) . ": " . mysql_error($this->connect[$this->connect['cpsql_active']]) .
                    " when trying <b>".$query . "</b>",
                    __LINE__,
                    __FILE__
                );
            }
             else
            {
                return $this->result[$name];
            }
        }
    }

    /**
     * This function will close our mysql connection, stored in our
     * $->connect identifier if method = 0, or if method !=0 it will
     * free the result identifier stored with $-> result
     *
     * @param string $method     A option to decide our method of ececution
     *                           0 - close sql connection
     *                           !0 - free our result identifier
     *
     * @return bool              The true|false result of our action
     *
     * @access public
     */
    public function close($link_name = "default", $method = "0")
    {
        return ($this->connect[$link_name]) ? (($method !== "0") ? mysql_free_result($this->result) : mysql_close($this->connect[$link_name])) : false;
    }

    /**
     * This function is used for loops and such, checks to see if a
     * identifier exist, if it does not then it will cycle through
     * fetching the results as called.
     *
     * @param string $name       The name of our resource link
     *
     * @return special           Our fetched array on success, false
     *                           on failure.
     *
     * @access public
     */
    public function next($name = "", $result_type = MYSQL_BOTH)
    {
        /**
         * something i've been meaning to do, returning a bool on my next is just silly, what if
         * you want to assign a row to the fetched array? now you can.
         */
        $this->record[$name] = (isset($this->result[$name]) ? mysql_fetch_array($this->result[$name], $result_type) : cpMain::cpError("MySQL Error, Could not <b>mysql_fetch_array()</b> from result name <b>" . $name . "</b> because it is not a member of the result array.", __LINE__, __FILE__));
        return $this->record[$name];
    }

    /**
     * This function will fetch a result for the specified resource
     *
     * @param string $name       The name of our resource link
     *
     * @return special           Our fetched array on success, false
     *                           on failure.
     *
     * @access public
     */
    public function fetch($name = "", $result_type = MYSQL_BOTH)
    {
        $this->record[$name] = (isset($this->result[$name]) ? mysql_fetch_array($this->result[$name], $result_type) : cpMain::cpError("MySQL Error, Could not <b>mysql_fetch_array()</b> from result name <b>" . $name . "</b> because it is not a member of the result array.", __LINE__, __FILE__));
        return $this->record[$name];
    }

    /**
     * Return a count of the number of rows a specific
     * resource has selected.
     *
     * @param string $name       The name of our resource link
     *
     * @return special           Int count on success, false on failure
     *
     * @access public
     */
    public function num_rows($name = "")
    {
	    return (isset($this->result[$name]) ? mysql_num_rows($this->result[$name]) : cpMain::cpError("MySQL Error, Could not <b>mysql_num_rows()</b> from result name <b>" . $name . "</b> because it is not a member of the result array.", __LINE__, __FILE__));
    }

    /**
     * Return the number of fields contained within a specific resource
     *
     * @param string $name       The name of our resource link
     *
     * @return special           Int count on success, false on failure
     *
     * @access public
     */
    public function fields_num($name = "")
    {
		return (isset($this->result[$name]) ? mysql_num_fields($this->result[$name]) : cpMain::cpError("MySQL Error, Could not <b>mysql_num_fields()</b> from result name <b>" . $name . "</b> because it is not a member of the result array.", __LINE__, __FILE__));
    }

    /**
     * This function will acquire a name of a field based on the
     * collumn and name of the resource
     *
     * @param string $name       The name of our resource link
     *
     * @param string $collumn    Collumn to return
     *
     * @return special           Named collumn on success, false
     *                           on failure.
     *
     * @access public
     */
    public function fields_name($name = "", $collumn)
    {
		return (isset($this->result[$name]) ? mysql_field_name($this->result[$name], $collumn) : cpMain::cpError("MySQL Error, Could not <b>mysql_field_name()</b> from result name <b>" . $name . "</b> because it is not a member of the result array.", __LINE__, __FILE__));
    }

    /**
     * This function will acquire a name of a field based on the
     * collumn and name of the resource
     *
     * @param string $name       The name of our resource link
     *
     * @param string $collumn    Collumn to return
     *
     * @return int               Int of affected rows on success, -1 on failure
     *
     * @access public
     */
    public function affected($name = "")
    {
		return (isset($this->result[$name]) ? mysql_affected_rows($this->result[$name], $collumn) : cpMain::cpError("MySQL Error, Could not <b>mysql_affected_rows()</b> from result name <b>" . $name . "</b> because it is not a member of the result array.", __LINE__, __FILE__));
    }
}

?>
