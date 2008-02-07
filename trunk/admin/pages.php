<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Available pages for RosterCP
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

// The key in the $config_pages array is the pagename for the admincp file.
// The value is an array whose keys have these meanings:
//	"href"		The link this should refer to.
//	"title"		The localization key for the button title.
//	"file"		The file to include if this page is called. Missing means
//			invalid page.
//	"special"	Ignored unless it's one of the following:
//			'divider'	Prints a horizontal line and no button.
//			'hidden'	Hides the link, but allows access to the page

$config_pages['roster'] = array(
	'href'=>	$roster->pages[0],
	'title'=>	'pagebar_rosterconf',
	'file'=>	'roster_conf.php',
	);
$config_pages['upload'] = array(
	'href'=>	$roster->pages[0].'-upload',
	'title'=>	'pagebar_uploadrules',
	'file'=>	'upload_rules.php',
	);
$config_pages['dataman'] = array(
	'href'=>	$roster->pages[0].'-dataman',
	'title'=>	'pagebar_dataman',
	'file'=>	'data_manager.php',
	);
$config_pages['install'] = array(
	'href'=>	$roster->pages[0].'-install',
	'title'=>	'pagebar_addoninst',
	'file'=>	'addon_install.php',
	);
$config_pages['menu'] = array(
	'href'=>	$roster->pages[0].'-menu',
	'title'=>	'pagebar_menuconf',
	'file'=>	'menu_conf.php',
	);
if( $roster->config['external_auth'] == 'roster' )
{
	$config_pages['change_pass'] = array(
		'href'=>	$roster->pages[0].'-change_pass',
		'title'=>	'pagebar_changepass',
		'file'=>	'change_pass.php',
		);
}
$config_pages['config_reset'] = array(
	'href'=>	$roster->pages[0].'-config_reset',
	'title'=>	'pagebar_configreset',
	'file'=>	'config_reset.php',
	);
$config_pages['hr'] = array(
	'special'=>	'divider',
	);
$config_pages['rosterdiag'] = array(
	'href'=>	'rosterdiag',
	'title'=>	'pagebar_rosterdiag',
	);

$config_pages['addon'] = array(
	'special'=>	'hidden',
	'file'=>	'addon_conf.php',
	);
