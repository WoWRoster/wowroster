<?php

/**
 * Project: cpFramework - scalable object based modular framework
 * File: /autoload.php
 *
 * This file contains autoload files, they must be within the autoload
 * directory. The purpose is to easily include files that need to be
 * executed before the module/plugin HOWEVER to follow our general
 * methodology these files MUST be a common task. Having a common
 * task throughout all methods and calling it within each method is
 * a poor practice; which is why the autoload system is in place.  
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
 * Our security measure, present in any file which does not contain
 * a direct access to our config itself. This is a security measure.
 */
if(!defined('SECURITY'))
{
   die("You may not access this file directly.");
}

/**
 * All autload files need to go below this comment, only the name of the file is needed without the extension of .php
 * remember the files must go into the autoload directory.
 */
?>
autoloadedFileForSomeRoutinesBeforeModulesAreRan.php*
someautoload *Example, it's commented out with a *

