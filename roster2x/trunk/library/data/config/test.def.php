<?php
/**
 * Roster config file
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

#' test text 30 30
// This is the test setting
$config['test'] = 'hello world';
#' fred text 30 53
$config['fred'] = 'bye';
