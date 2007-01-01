<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: modules/config/index.php
 *
 * The configuration module. Contains the configuration interface.
 *
 * -http://en.wikipedia.org/wiki/Modular
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
 * @author WoWRoster.net
 * @version 1.5.0
 * @copyright 2000-2006 WoWRoster.net
 * @package Module-Config
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
