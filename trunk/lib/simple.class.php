<?php
/**
 * WoWRoster.net WoWRoster
 *
 * WoWRoster Simple Class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.9.9
 * @package    WoWRoster
 * @subpackage SimpleClass
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * WoWRoster Simple Class
 *
 * Allows easy access to XML Data
 *
 * @package    WoWRoster
 * @subpackage SimpleClass
 */
class SimpleClass
{
	var $properties = array();

	/**
	 * Set property method
	 *
	 * @param string $propName
	 * @param string $propValue
	 */
	function setProp( $propName , $propValue )
	{
		if( isset($this->$propName) )
		{
			$this->$propName .= $propValue;
		}
		else
		{
			$this->$propName = $propValue;
		}
		if( !in_array($propName, $this->properties) )
		{
			$this->properties[] = $propName;
		}
	}

	/**
	 * Set array method
	 *
	 * @param array $array
	 */
	function setArray( $array )
	{
		if( is_array($array) )
		{
			foreach( $array as $key => $value )
			{
				$this->setProp($key, $value);
			}
		}
	}

	/**
	 * HasProp method
	 * checks the object for given prop name
	 *
	 * @param string $propName
	 * @return bool
	 */
	function hasProp( $propName )
	{
		return in_array($propName, $this->properties);
	}
}
