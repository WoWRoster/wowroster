<?php
/******************************
 * WoWRoster.net  UniAdmin
 * Copyright 2002-2007
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

if( !defined('E_STRICT') )
{
	define('E_STRICT', 2048);
}

class ua_debugger
{
	// Define variables that store the old error reporting and logging states
	var $old_handler;
	var $old_display_level;
	var $old_error_logging;

	var $report;
	var $active = false;
	var $error_level;

	function ua_debugger( $error_level=E_ALL )
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

	function stop( )
	{
		if ($this->active)
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

	// user defined error handling function
	function handler( $errno , $errmsg , $filename , $linenum , $vars='' )
	{
		$errortype = array (
			E_WARNING         => 'Warning',
			E_NOTICE          => 'Notice',
			E_CORE_ERROR      => 'Core Error',
			E_CORE_WARNING    => 'Core Warning',
			E_COMPILE_ERROR   => 'Compile Error',
			E_COMPILE_WARNING => 'Compile Warning',
			E_USER_ERROR      => 'UniAdmin Error',
			E_USER_WARNING    => 'UniAdmin Warning',
			E_USER_NOTICE     => 'UniAdmin Notice',
			E_STRICT          => 'Runtime Notice'
		);
		// NOTE: E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR and E_COMPILE_WARNING
		// error levels will be handled as per the error_reporting settings.
		if( $errno == E_USER_ERROR )
		{
			if( is_admin() )
			{
				ua_die($errortype[$errno]." - [$filename]<br />Line: $linenum<br />".$errmsg);
			}
			else
			{
				ua_die("A error occured while processing this page.<br />Please report the following error to the owner of this website.<br /><br /><b>$errmsg</b>");
			}
		}

		// set of errors for which a trace will be saved
		if( $errno & $this->error_level )
		{
			$this->report[$filename][] = $errortype[$errno]." line $linenum: ".$errmsg;
		}
	}
}

$ua_debugger =& new ua_debugger(E_ALL);
