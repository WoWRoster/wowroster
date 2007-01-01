<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: plugins/config.php
 *
 * Config class demo
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
 * @package Plugin-Config
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

cpMain::$instance['cpconfig']->loadConfig('test');
echo "Config data: <br /><pre>";
print_r(cpMain::$instance['cpconfig']->test);

$meta = cpMain::$instance['cpconfig']->loadConfigMeta('test');
echo "</pre><br />Metadata: <br /><pre>";
print_r($meta);
echo "</pre><br />";

$config = array('test'=>'hi','fred'=>'hello');

cpMain::$instance['cpconfig']->writeConfig('test',$config);


