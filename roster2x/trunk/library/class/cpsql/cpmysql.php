<?php

/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/class/cpsql/cpmysql.php
 *
 * This is a extend of our cpsql class, when multi database support is required
 * a implimentation will need to be put in place, for now this is a abstract
 * class with a private construct to prevent instantiation within modules.
 *
 * @author Chris Stockton
 * @version 1.5.0
 * @copyright 2000-2006 Chris Stockton
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
 * This is a extend of our cpsql class, when multi database support is required
 * a implimentation will need to be put in place, for now this is a abstract
 * class with a private construct to prevent instantiation within modules.
 * @package cpFramework
 */
abstract class cpmysql extends cpsql
{
	/**
	 * Private construct disables instantiation
	 */
	private __construct()
	{
		/**
		 * NO WAY JOSE. IM PRIVATE.
		 */
	}
}
