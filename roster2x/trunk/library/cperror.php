<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/cperror.php
 *
 * This is our standard error handling class
 * A simple wrapper here as a placeholder for any future logging
 * and error reporting
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

if (!defined('E_STRICT')) define('E_STRICT', 2048);

/**
 * Our error handling class
 * @package cpFramework
 */
class cpError
{
	// Define variables that store the old error reporting and logging states
	static private $old_handler;
	static private $old_display_level;
	static private $old_error_logging;

	static private $report;
	static private $active = false;
	static private $error_level;

	/**
	 * Begin error handling
	 */
	function start()
	{
		if( !self::$active )
		{
			self::$report = false;
			if( CAN_INI_SET )
			{
				self::$old_display_level = ini_set('display_errors', 1);
				self::$old_error_logging = ini_set('log_errors', 0);
			}
			self::$old_handler = set_error_handler(array(get_class(), 'handler'));

			self::$error_level = E_ALL;
			self::$active = true;
		}
	}

	/**
	 * Stop our error handler
	 *
	 * @return mixed ( array = errors | false = no errors )
	 */
	function stop()
	{
		if( self::$active )
		{
			// restore the previous state
			if( !is_bool(self::$old_handler) && self::$old_handler )
			{
				set_error_handler(self::$old_handler);
			}
			if( CAN_INI_SET )
			{
				ini_set('display_errors', self::$old_display_level);
				ini_set('log_errors', self::$old_error_logging);
			}
			self::$active = false;
			return self::$report;
		}
	}

	/**
	 * Our error handling method
	 *
	 * @param unknown_type $errno
	 * @param unknown_type $errmsg
	 * @param unknown_type $filename
	 * @param unknown_type $linenum
	 * @param unknown_type $vars
	 */
	function handler( $errno , $errmsg , $filename , $linenum , $vars='' )
	{
		//$errmsg = utf8_encode($errmsg);
		$errortype = array (
			E_WARNING         => 'Warning',
			E_NOTICE          => 'Notice',
			E_CORE_ERROR      => 'Core Error',
			E_CORE_WARNING    => 'Core Warning',
			E_COMPILE_ERROR   => 'Compile Error',
			E_COMPILE_WARNING => 'Compile Warning',
			E_USER_ERROR      => 'CMS Error',
			E_USER_WARNING    => 'CMS Warning',
			E_USER_NOTICE     => 'CMS Notice',
			E_STRICT          => 'Runtime Notice'
		);
		// NOTE: E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR and E_COMPILE_WARNING
		// error levels will be handled as per the error_reporting settings.
		if( $errno == E_USER_ERROR )
		{
			print($errortype[$errno]." $filename line $linenum: ".$errmsg);
			//print("A error occured while processing this page.<br />Please report the following error to the owner of this website.<br /><br /><b>$errmsg</b>");
			die();
		}

		// set of errors for which a trace will be saved
		if( R2_DEBUG && $errno & self::$error_level )
		{
			if( ereg('sql_', $errmsg) )
			{
				global $db;
				$filename = $db->file;
				$linenum = $db->line;
			}
			self::$report[$filename][] = $errortype[$errno]." line $linenum: ".$errmsg;
		}
	}
}
