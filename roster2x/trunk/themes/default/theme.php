<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: /themes/default/theme.php
 *
 * The default theme for R2
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
 * @link http://www.wowroster.net
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author WoWRoster.net
 * @version 1.5.0
 * @copyright 2000-2007 WoWRoster.net
 * @package cpThemes
 * @subpackage Default
 * @filesource
 *
 * Roster versioning tag
 * $Id:$
 */

/**
 * Our security measure, present in any file which does not contain
 * a direct access to our config itself. This is a security measure.
 */
if(!defined('SECURITY'))
{
	die("You may not access this file directly.");
}



cpMain::$instance['smarty']->assign(array(
	'CSS_FILE'     => ereg('MSIE', $_SERVER['HTTP_USER_AGENT']) ? 'ie' : 'common',
	'U_LINK1'    => getlink('config&mode=index'),
	'U_LINK2'    => getlink('test'),
	'U_LINK3'    => getlink(''),
	'S_FOOTER'      => 'Some Footer Text',
));
