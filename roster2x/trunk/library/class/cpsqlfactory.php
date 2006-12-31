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
	public static function factory($type = DB_TYPE, $host = DB_HOST, $user = DB_USER, $pass = DB_PASS, $name = DB_NAME)
	{
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
