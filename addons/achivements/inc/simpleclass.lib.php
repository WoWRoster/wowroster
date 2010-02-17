<?php
/**
 * WoWRoster.net WoWRoster
 *
 * SimpleClass Library
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: simpleclass.lib.php 477 2009-11-13 07:03:44Z Ulminia $
 * @link       http://www.wowroster.net
 * @package    ArmorySync
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

class SimpleClass {
	var $properties = array();
	
	function setProp($propName, $propValue) {
		$this->$propName = $propValue;
		if (!in_array($propName, $this->properties)) {
			$this->properties[] = $propName;
		}
  } 
  
	function setArray($array) {
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				$this->setProp($key, $value);
			}
		}
	}
	
	function hasProp($propName) {
		return in_array($propName, $this->properties);
	}
}
?>