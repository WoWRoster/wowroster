<?php
/******************************
 * WoWRoster.net  Roster
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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Main LUA parsing function
 * @author six, originally mordon
 */
function ParseLuaFile( $file_name )
{
	if( file_exists($file_name) && is_readable($file_name) )
	{
		$stack = array( array( '',  array() ) );
		$stack_pos = 0;
		$last_line = '';
		
		$file = gzopen($file_name,'r');
		
		while( !gzeof($file) )
		{
			$line = gzgets($file);
			$line = trim($line);

			// Look for end of an array
			if( isset($line[0]) && $line[0] == '}' )
			{
				$hash = $stack[$stack_pos];
				unset($stack[$stack_pos]);
				$stack_pos--;
				$stack[$stack_pos][1][$hash[0]] = $hash[1];
				unset($hash);
			}
			// Handle other cases
			else
			{
				// Check if the key is given
				if( strpos($line,'=') )
				{
					list($name, $value) = explode( '=', $line, 2 );
					$name = trim($name);
					$value = trim($value,', ');
					if($name[0]=='[')
					{
						$name = trim($name, '[]"');
					}
				}
				// Otherwise we'll have to make one up for ourselves
				else
				{
					$value = $line;
					if( empty($stack[$stack_pos][1]) )
					{
						$name = 1;
					}
					else
					{
						$name = max(array_keys($stack[$stack_pos][1]))+1;
					}
					if( strpos($line,'-- [') )
					{
						$value = explode('-- [',$value);
						array_pop($value);
						$value = implode('-- [',$value);
					}
					$value = trim($value,', ');
				}
				if( isset($value) && $value == '{' )
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
		}
		
		gzclose($file);
		return($stack[0][1]);
	}
}
