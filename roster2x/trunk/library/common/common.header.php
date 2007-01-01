<?php

/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/common/common.header.php
 *
 * This files primary duty is to perform the FEW tasks that are not
 * controlled by our methods, as the methods are dependant on them so
 * they must be called before autoloaded files or methods.
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
 * Our main class, cpEngine, our instance handler
 */
require(R2_CLASS_PATH . "cpmain".DIR_SEP."cpexception.php");

/**
 * Our main class, cpEngine, our instance handler
 */
require(R2_CLASS_PATH . "cpmain.php");

/**
 * The config class
 */
cpMain::loadClass('cpconfig','cpconfig');

/**
 * Load default config
 */
cpMain::$instance['cpconfig']->loadConfig('cpconf');
