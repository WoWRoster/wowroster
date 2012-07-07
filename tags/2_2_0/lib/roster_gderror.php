<?php
/**
 * WoWRoster.net WoWRoster
 *
 * GD error handler
 *
 * @copyright  2002-2011 WoWRoster.net
 * @author     zanix
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    WoWRoster
 * @subpackage GDError
 * @filesource
 */

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if( !defined('E_STRICT') )
{
	define('E_STRICT', 2048);
}

/**
 * Image based error handler
 * Why? Because we expect images so error messages need to be returned as images
 *
 * @package    WoWRoster
 * @subpackage GDError
 */
class GDError
{
	// Define variables that store the old error reporting and logging states
	var $old_handler;
	var $old_display_level;
	var $old_error_logging;

	var $report;
	var $active = false;
	var $error_level;

	function GDError( $error_level=E_ALL )
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

	/**
	 * User defined error handling function
	 *
	 * @param int $errno
	 * @param string $errmsg
	 * @param string $filename
	 * @param int $linenum
	 * @param unknown_type $vars
	 */
	function handler( $errno , $errmsg , $filename , $linenum , $vars='' )
	{
		$errortype = array (
			E_WARNING         => 'Warning',
			E_NOTICE          => 'Notice',
			E_CORE_ERROR      => 'Core Error',
			E_CORE_WARNING    => 'Core Warning',
			E_COMPILE_ERROR   => 'Compile Error',
			E_COMPILE_WARNING => 'Compile Warning',
			E_USER_ERROR      => 'RosterGD Error',
			E_USER_WARNING    => 'RosterGD Warning',
			E_USER_NOTICE     => 'RosterGD Notice',
			E_STRICT          => 'Runtime Notice'
		);

		if( $errno & $this->error_level )
		{
			$this->debugMode($errortype[$errno],$linenum,$errmsg,$filename);
		}
	}

	function debugMode( $errno , $linenum , $errmsg , $filename )
	{
		$linenum  = "Line: $linenum";
		$filename  = "File: $filename";

		//echo backtrace();die();
		$lines = array();
		$lines[] = array( 's' => $errno,    'f' => 5, 'c' => 'red' );
		$lines[] = array( 's' => $linenum,  'f' => 3, 'c' => 'blue' );
		$lines[] = array( 's' => $filename, 'f' => 2, 'c' => 'green' );
		$lines[] = array( 's' => $errmsg,   'f' => 2, 'c' => 'black' );

		$height = $width = 0;
		foreach( $lines as $line )
		{
			if( strlen($line['s']) > 0 )
			{
				$line_width = imagefontwidth($line['f']) * strlen($line['s']);
				$width = ( ($width < $line_width) ? $line_width : $width );
				$height += imagefontheight($line['f']);
			}
		}

		$im = imagecreate($width+1,$height);
		if( $im )
		{
			$white = imagecolorallocate($im,255,255,255);
			$red = imagecolorallocate($im,255,0,0);
			$green = imagecolorallocate($im,0,255,0);
			$blue = imagecolorallocate($im,0,0,255);
			$black = imagecolorallocate($im,0,0,0);

			$linestep = 0;
			foreach( $lines as $line )
			{
				if( strlen($line['s']) > 0 )
				{
					imagestring( $im, $line['f'], 1, $linestep, $line['s'], $$line['c'] );
					$linestep += imagefontheight($line['f']);
				}
			}

			header('Content-type: image/gif');
			imagegif($im);
			imagedestroy($im);
		}
		else  // In case our image crate fails, we use text error output
		{
			$string  = "<strong><span style=\"color:red\">$errno</span></strong>\n";
			$string .= "<span style=\"color:blue\">$linenum</span><br />\n";
			$string .= "<span style=\"color:green\">$filename</span><br />\n";
			$string .= "$errmsg";

			echo $string;
		}

		exit;
	}
}
