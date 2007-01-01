<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/class/cpsqlfactory.php
 *
 * Our SQL facorty, now with more smog!
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
 * SQL Factory
 * @package cpFramework
 */
class cpsqlfactory
{
	/**
	 * Factory function wrapper, no need to instantiate
	 */
	private function __construct(){}

	/**
	 * Factory function
	 */
	public static function factory()
	{
		if( func_num_args() >= 5)
		{
			list($type, $host, $user, $pass, $name) = func_get_args();
		}
		else
		{
			$type =  cpMain::$instance['cpconfig']->cpconf['db_type'];
			$host =  cpMain::$instance['cpconfig']->cpconf['db_host'];
			$user =  cpMain::$instance['cpconfig']->cpconf['db_user'];
			$pass =  cpMain::$instance['cpconfig']->cpconf['db_pass'];
			$name =  cpMain::$instance['cpconfig']->cpconf['db_name'];
		}
		cpMain::loadFile('cpsqlinterface');
		cpMain::loadFile('cpsql_'.$type);

		$DB = new $type($host, $user, $pass, $name);

		if( $DB instanceof cpsql )
		{
			return $DB;
		}
		else
		{
			cpMain::cpErrorFatal
			(
				'cpSQLfactory: Tried to create a DB object of type "'.$type.'" but that class does not implement cpsqlinterface',
				__FILE__,
				__LINE__
			);
		}
	}
}
