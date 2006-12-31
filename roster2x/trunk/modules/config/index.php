<?php

/**
 * The configuration module. Contains the configuration interface.
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

// Get post vars
$file = isset($_POST['file'])?$_POST['file']:'cpconf';
$save = isset($_POST['save'])?$_POST['save']:FALSE;

// Load classes
cpMain::loadClass('smarty','smarty');

$config = cpMain::$instance['cpconfig']->loadConfigMeta($file);

if( $save )
{
	cpMain::$instance['cpconfig']->writeConfig($file, array());
	cpMain::$instance['smarty']->assign('status', 'Personal config updated');
}

// Assign output vars
cpMain::$instance['smarty']->assign('file',$file);
cpMain::$instance['smarty']->assign('config',$config);
