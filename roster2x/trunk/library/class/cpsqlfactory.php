<?php

/**
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
