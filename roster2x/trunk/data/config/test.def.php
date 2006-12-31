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

#' test type=text boxlen=30 fieldlen=30
// This is the test setting
$config['test'] = 'hello world';
#' fred type=text boxlen=30 fieldlen=53
$config['fred'] = 'bye';
