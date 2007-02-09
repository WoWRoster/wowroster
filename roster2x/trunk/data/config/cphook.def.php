<?php
/**
 * Roster hooks file
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
#' hooks type=hooks
// Used by the cphook library to store filenames/callbacks to run
// under named hooks.
$config['hooks'] = array();
