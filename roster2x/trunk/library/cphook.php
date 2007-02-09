<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/class/cpsqlfactory.php
 *
 * Code hook manager.
 * Add, manage, remove functions under named hooks.
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
 * @subpackage hookmanager
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
 * Code hook manager
 * @package cpFramework
 */
class cphook
{
	public __construct()
	{
		cpMain::$instance['cpconfig']->loadConfig('cphook');
	}
	
	/**
	 * addFunc adds a function hook
	 *
	 * @param $hook named hookpoint
	 * @param $callback function callback - see http://php.net/callback
	 * @param $file filename to load before running the callback
	 *
	 * @return unique ID to use later if this hook needs to be removed.
	 */
	public addFunc($hook, $callback, $file)
	{
		$hooks = cpMain::$instance['cpconfig']->cphook['hooks'];
		$id = max(array_keys($hooks[$hook])) + 1;
		
		$hooks[$hook][$id] = array(
			'file' => $file,
			'callback' => $callback
		);
		
		cpMain::$instance['cpconfig']->writeConfig('hooks',$hooks);
		
		return $id;
	}
	
	/**
	 * runHook runs all functions associated with a hook
	 *
	 * @param $hook named hookpoint
	 *
	 * @return true  if there were no functions to run or all functions ran
	 *               successfully
	 *         false if at least one hooked function could not be called
	 */
	public runHook($hook)
	{
		$success = true;
		
		$hooks = cpMain::$instance['cpconfig']->cphook['hooks'];
		if( !isset($hooks[$hook]) || empty($hooks[$hook]) )
		{
			return true;
		}
		
		foreach( $hooks[$hook] as $func )
		{
			if( !is_file(PATH_LOCAL.$func['file']) )
			{
				$success = false;
				continue;
			}
			
			include_once(PATH_LOCAL.$func['file']);
			
			call_user_func($callback);
		}
		
		return $success;
	}
	
	/**
	 * Remove a function from a hook
	 *
	 * @param $hook named hookpoint
	 * @param $id function ID as returned by addFunc
	 *
	 * @return true on success, false on failure
	 */
	public delFunc($hook, $id)
	{
		$hooks = cpMain::$instance['cpconfig']->cphook['hooks'];
		if( isset($hooks[$hook][$id]) )
		{
			unset($hooks[$hook][$id]);
			return true;
		}
		else
		{
			return false;
		}
	}
}
