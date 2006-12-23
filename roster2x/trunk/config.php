<?php

/**
 * Project: cpFramework - scalable object based modular framework
 * File: /config.php
 *
 * Holds configuration settings for the system. This file should be used
 * in place of any hard coding.
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
 * @author Chris Stockton
 * @version 1.5.0
 * @copyright 2000-2006 Chris Stockton
 * @package cpFramework
 * @filesource
 */

/**
 * Our security constant to keep files from being accessed directly
 */
define("SECURITY", true);

/**
 * Site pathing and settings with trailing slash
 */
define("PATH_LOCAL", "C:/htdocs/myprojects/cpframework/current_core/");
define("PATH_REMOTE", "http://localhost/myprojects/cpframework/current_core/");
define("PATH_REMOTE_S", "http://localhost/myprojects/cpframework/current_core/");

/**
 * System definitions..
 */
define("SYSTEM_DEFAULT_THEME", "default");
define("SYSTEM_DEFAULT_LANG", "english");
define("SYSTEM_DEFAULT_METHOD", "plugins");
define("SYSTEM_DEFAULT_MODULE", "");
define("SYSTEM_DEFAULT_MODE", "");
define("SYSTEM_DEFAULT_PLUGIN", "index");
define("SYSTEM_DEFAULT_ACCESS_DENIED", "Your user does not have the available privelages to view this method.");

/**
 * Friendly URLS require the use of a .htaccess file, so it's
 * unix only, unless you setup rulls for a windows enviroment. 
 */
define("SYSTEM_FRIENDLY_URLS", TRUE);

/**
 * A common issue with developers is that the users like to type
 * www.domain.com when www is actualy a sub domain as most
 * developers are aware, this causes issues with cookies and tracking
 * so by setting this we control the request and redirect them.
 * 
 * Options:
 * 'www' Redirect to www.PATH_REMOTE (replaces http:// with http://www. (if needed)
 * 'http' Redirect to PATH_REMOTE
 * 'off' Don't ever redirect domain 
 */ 
define("SYSTEM_REDIRECT_REQUEST", "off");

/**
 * Directory seperator to make for easy cross system compatibility.
 */ 
define("DIRECTORY_SEPERATOR", "/");

/**
 * Turn on ALL errors during development, we keep our code crisp.. and clean
 * however turn them off after development for security reasons. Make sure to
 * actively controll this configuration setting. 
 */
error_reporting(E_ALL);

?>
