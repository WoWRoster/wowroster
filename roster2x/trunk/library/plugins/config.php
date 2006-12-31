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
echo "Config data: <br><pre>";
print_r(cpMain::$instance['cpconfig']->test);

$meta = cpMain::$instance['cpconfig']->loadConfigMeta('test');
echo "</pre><br>Metadata: <br><pre>";
print_r($meta);
echo "</pre><br>";

$config = array('test'=>'hi','fred'=>'hello');

cpMain::$instance['cpconfig']->writeConfig('test',$config);


