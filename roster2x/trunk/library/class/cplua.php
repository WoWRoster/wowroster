<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/class/cpsqlfactory.php
 *
 * LUA parser and writer class
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
 * @subpackage luawriter
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
 * LUA parser/writer
 * @package cpFramework
 */
class cplua
{
	/**
	 * Wrapper function so that you can parse a file instead of an array.
	 * @author six
	 *
	 * @param string $file_name Location of the uploaded lua file
	 * @param string $file_type 'gz' if the file is gzipped
	 *
	 * @return array the parsed data, or false on errror
	 */
	function parse( $file_name , $file_type=null )
	{
		if( file_exists($file_name) && is_readable($file_name) )
		{
			if( $file_type == 'gz' )
			{
				$file_as_array = gzfile($file_name);
			}
			else
			{
				$file_as_array = file($file_name);
			}
	
			return($this->parseArray($file_as_array));
		}
		return(false);
	}
	
	/**
	 * Main LUA parsing function
	 * @author six, originally mordon
	 *
	 * @param array $file_as_array The lua file, one line per array element
	 *
	 * @return array The parsed array
	 */
	function parseArray( &$file_as_array )
	{
		if( !is_array($file_as_array) )
		{
			// return false if not presented with an array
			return(false);
		}
		else
		{
			// Parse the contents of the array
			$stack = array( array( '',  array() ) );
			$stack_pos = 0;
			$last_line = '';
			foreach( $file_as_array as $line )
			{
				// join lines ending in \\ together
				if( substr( $line, -2, 1 ) == '\\' )
				{
					$last_line .= substr($line, 0, -2) . "\n";
					continue;
				}
				if($last_line!='')
				{
					$line = trim($last_line . $line);
					$last_line = '';
				}
				else
				{
					$line = trim($line);
				}
				$line = rtrim($line, ',');
				// Look for a key value pair
				if( strpos( $line, '=' ) )
				{
					list($name, $value) = explode( '=', $line, 2 );
					$name = trim($name);
					$value = trim($value);
					if($name[0]=='[')
					{
						$name = trim($name, '[]"');
					}
					if( $value == '{' )
					{
						$stack_pos++;
						$stack[$stack_pos] = array($name, array());
					}
					else
					{
						if($value[0]=='"')
						{
							$value = substr($value,1,-1);
						}
						else if($value == 'true')
						{
							$value = true;
						}
						else if($value == 'false')
						{
							$value = false;
						}
						else if($value == 'nil')
						{
							$value = NULL;
						}
						$stack[$stack_pos][1][$name] = $value;
					}
				}
				else if( $line == '}' )
				{
					$hash = $stack[$stack_pos];
					$stack_pos--;
					$stack[$stack_pos][1][$hash[0]] = $hash[1];
				}
			}
			return($stack[0][1]);
		}
	}
	
	/**
	 * LUA writer. Converts a PHP array into a lua file. When calling
	 * from outside only the first parameter should be passed.
	 *
	 * I'm sure this can be sped up if anyone knows how to do it nicely
	 * without recursion.
	 *
	 * @param array $source The PHP array to write
	 * @param string $indent Tells how far to indent
	 * @param bool $top True for the initial call, bool for others
	 *
	 * @return string The lua result
	 */
	function write($source, $indent = '', $top = false)
	{
		if( $indent == '' )
		{
			$top = true;
		}
		foreach( $source as $name => $value )
		{
			$out .= $indent;
			if( $top )
			{
				if( is_numeric($name) ) die("no numeric top-level keys allowed");
				$out .= $name;
			}
			elseif( is_numeric($name) )
			{
				$out .= '['.$name.']';
			}
			else
			{
				$out .= '["'.$name.'"]';
			}
	
			$out .= ' = ';
	
			if( is_array($value) )
			{
				$out .= '{'."\n";
				$out .= luawriter($value, $indent."\t");
				$out .= $indent.'}';
			}
			elseif( is_string($value) )
			{
				$out .= '"'.addcslashes($value,"\n\"\\").'"';
			}
			elseif( is_numeric($value) )
			{
				$out .= $value;
			}
			elseif( is_null($value) )
			{
				$out .= 'nil';
			}
			elseif( is_bool($value) && $value )
			{
				$out .= 'true';
			}
			elseif( is_bool($value) && !$value )
			{
				$out .= 'false';
			}
			
			if( $top )
			{
				$out .= "\n";
			}
			else
			{
				$out .= ",\n";
			}
		}
		return $out;
	}
}
