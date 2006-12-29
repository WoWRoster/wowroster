<?php

/**
 * Config class demo
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

cpMain::loadClass('cpconfig','cpconfig');

cpMain::$instance['cpconfig']->loadConfig('test');

cpMain::$instance['cpconfig']->loadConfigMeta('test');

$config = array('test'=>'hi','fred'=>'hello');

cpMain::$instance['cpconfig']->writeConfig('test',$config);



?>
