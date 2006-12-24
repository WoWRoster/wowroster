<?php

/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/class/cpmain/cpexception.php
 *
 * This is our exception handling class, it's a direct extend of the Exception
 * default class, we are putting this simple wrapper here as a placeholder for
 * future error logging and such, in case I need to log errors, or handle
 * exceptions in a better way.
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
 * Our exception handling class extends php's default exception
 * handling.
 * @package cpFramework
 */
class cpException extends Exception
{

    /**
     * Our construct wraps the main exception construct
     *
     * @param string $message message to parent
     * @param string $code exception code
     *
     * @return void
     */
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }

    /**
     * Basic exception handling, toString is called by our parent on
	 * Exception instantiation.
     *
     * @return string Exception string for php to display
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

?>
