<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Error Handler
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage ErrorControl
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

if( !defined('E_STRICT') )
{
	define('E_STRICT', 2048);
}
if( !defined('E_RECOVERABLE_ERROR') )
{
	define('E_RECOVERABLE_ERROR', 4096);
}
if( !defined('E_DEPRECATED') )
{
	define('E_DEPRECATED', 8192);
}
if( !defined('E_USER_DEPRECATED') )
{
	define('E_USER_DEPRECATED', 16384);
}


/**
 * Roster Error Handler
 *
 * @package    WoWRoster
 * @subpackage ErrorControl
 */
class roster_error
{
	// Define variables that store the old error reporting and logging states
	var $old_handler;
	var $old_display_level;
	var $old_error_logging;

	var $report;
	var $active = false;
	var $error_level;

	function roster_error( $error_level=E_ALL )
	{
		if( !$this->active )
		{
			error_reporting($error_level);

			$this->report = false;
			if( CAN_INI_SET )
			{
				$this->old_display_level = ini_set('display_errors', 1);
				$this->old_error_logging = ini_set('log_errors', 0);
			}

			$this->old_handler = set_error_handler(array(&$this, 'handler'));

			$this->error_level = E_ALL;
			$this->active = true;
		}
	}

	/**
	 * End our handler and return error control to php
	 *
	 * @return array Error report
	 */
	function stop()
	{
		if( $this->active )
		{
			// restore the previous state
			if( !is_bool($this->old_handler) && $this->old_handler )
			{
				set_error_handler($this->old_handler );
			}
			if( CAN_INI_SET )
			{
				ini_set('display_errors', $this->old_display_level);
				ini_set('log_errors', $this->old_error_logging);
			}
			$this->active = false;
			return $this->report;
		}
	}

	/**
	 * User defined error handling function
	 *
	 * @param int $errno
	 * @param string $errmsg
	 * @param string $filename
	 * @param int $linenum
	 * @param mixed $vars
	 */
	function handler( $errno , $errmsg , $filename , $linenum , $vars='' )
	{
		global $roster;

		$errortype = array (
			E_ERROR             => 'Run-time error',
			E_WARNING           => 'Run-time warning',
			E_PARSE             => 'Compile-time parse error',
			E_NOTICE            => 'Run-time notice',
			E_CORE_ERROR        => 'Core error',
			E_CORE_WARNING      => 'Core warning',
			E_COMPILE_ERROR     => 'Compile-time error',
			E_COMPILE_WARNING   => 'Compile-time warning',
			E_USER_ERROR        => 'Roster error',
			E_USER_WARNING      => 'Roster warning',
			E_USER_NOTICE       => 'Roster notice',
			E_STRICT            => 'Strict notice',
			E_RECOVERABLE_ERROR => 'Catchable fatal error',
			E_DEPRECATED        => 'Depreciated notice',
			E_USER_DEPRECATED   => 'Roster depreciated notice',
		);
		// NOTE: E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR and E_COMPILE_WARNING
		// error levels will be handled as per the error_reporting settings.
		if( $errno == E_USER_ERROR )
		{
			if( $roster->config['debug_mode'] )
			{
				die_quietly($errmsg,$errortype[$errno],$filename,$linenum);
			}
			else
			{
				die_quietly("A error occured while processing this page.<br />Please report the following error to the owner of this website.<br /><br /><b>$errmsg</b>",$errortype[$errno]);
			}
		}

		// set of errors for which a trace will be saved
		if( $errno & $this->error_level )
		{
			$this->report[$filename][] = $errortype[$errno] . " line $linenum: " . $errmsg;
		}
	}
}
