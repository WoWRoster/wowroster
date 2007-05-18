<?php
/**
 * WoWRoster.net WoWRoster
 *
 * RosterCP (Control Panel)
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

/******************************
 * Call parameters:
 *
 * page
 *		roster		Roster config
 *		character	Per-character preferences
 *		addon		Addon config
 *		install		Addon installation screen
 *
 * addon	If page is addon, this says which addon is being configured
 * profile	If page is addon, this says which addon profile is being configured.
 *
 ******************************/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// ----[ Check log-in ]-------------------------------------
$roster_login = new RosterLogin();

// Disallow viewing of the page
if( !$roster_login->getAuthorized() )
{
	include_once (ROSTER_BASE . 'roster_header.tpl');
	$roster_menu = new RosterMenu;
	print $roster_menu->makeMenu('main');

	print
	'<span class="title_text">' . $roster->locale->act['roster_config'] . '</span><br />'.
	$roster_login->getMessage().
	$roster_login->getLoginForm();

	include_once (ROSTER_BASE . 'roster_footer.tpl');
	exit();
}
// ----[ End Check log-in ]---------------------------------

include_once(ROSTER_ADMIN . 'pages.php');

$header = $menu = $body = $pagebar = $footer = '';

// ----[ Check for latest UniAdmin Version ]------------------
if( $roster->config['check_updates'] )
{
	$roster_ver_latest = $roster_ver_info = '';

	$content = urlgrabber('http://wowroster.net/roster_updater/version.txt');

	if( preg_match('#<version>(.+)</version>#i',$content,$version) )
	{
		$roster_ver_latest = $version[1];
	}

	if( preg_match('#<info>(.+)</info>#i',$content,$info) )
	{
		$roster_ver_info = '<br />' . $info[1];
	}

	if( version_compare($roster_ver_latest,ROSTER_VERSION,'>') )
	{
		$header = messagebox(sprintf($roster->locale->act['new_version_available'],'WoWRoster',$roster_ver_latest,'http://www.wowroster.net') . $roster_ver_info,$roster->locale->act['update']);
	}
}

// Find out what subpage to include, and do so
$page = (isset($roster->pages[1]) && ($roster->pages[1]!='')) ? $roster->pages[1] : 'roster';

if( isset($config_pages[$page]['file']) )
{
	if (file_exists(ROSTER_ADMIN . $config_pages[$page]['file']))
	{
		require_once(ROSTER_ADMIN . $config_pages[$page]['file']);
	}
	else
	{
		$body .= $roster_login->getMessage() . '<br />' . messagebox(sprintf($roster->locale->act['roster_cp_not_exist'],$page),$roster->locale->act['roster_cp'],'sred');
	}
}
else
{
	$body .= $roster_login->getMessage() . '<br />' . messagebox($roster->locale->act['roster_cp_invalid'],$roster->locale->act['roster_cp'],'sred');
}

// Build the pagebar from admin/pages.php
foreach ($config_pages as $pindex => $data)
{
	if (!isset($data['special']))
	{
		$pagebar .= '<li' . ($roster->pages[0] . '-' . $page == $data['href'] ? ' class="selected"' : '') . '><a href="' . makelink($data['href']) . '">' . $roster->locale->act[$data['title']] . "</a></li>\n";
	}
	elseif ($data['special'] == 'divider')
	{
		$pagebar .= '<li><hr /></li>';
	}
}

if ($pagebar != '')
{
	$pagebar = "<ul class=\"tab_menu\">\n$pagebar</ul>";
	$pagebar = messagebox($pagebar,$roster->locale->act['pagebar_function']) . "<br />\n";
}

// Add addon buttons
$addon_pagebar = '';
foreach( $roster->addon_data as $row )
{
	$addon = getaddon($row['basename']);

	if( file_exists($addon['admin_dir'] . 'index.php') || $addon['config'] != '' )
	{
		$addon_pagebar .= '<li' . (isset($roster->pages[2]) && $roster->pages[2] == $row['basename'] ? ' class="selected"' : '') . '><a href="' . makelink('rostercp-addon-' . $row['basename']) . '">' . $row['fullname'] . "</a></li>\n";
	}
}

if( $addon_pagebar != '' )
{
	$pagebar .= border('sgray','start',$roster->locale->act['pagebar_addonconf']) . "\n";
	$pagebar .= '<ul class="tab_menu">' . "\n";
	$pagebar .= $addon_pagebar;
	$pagebar .= "</ul>\n";
	$pagebar .= border('sgray','end') . "\n";
}

// ----[ Render the page ]----------------------------------
include_once( ROSTER_BASE . 'roster_header.tpl' );
$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');

echo
	$header . "\n".
	'<table width="100%"><tr>' . "\n".
	'<td valign="top" align="left" width="15%">' . "\n".
	$menu . "</td>\n".
	'<td valign="top" align="center" width="70%">' . "\n".
	$body . "</td>\n".
	'<td valign="top" align="right" width="15%">' . "\n".
	$pagebar . "</td>\n".
	"</tr></table>\n".
	$footer;

include_once( ROSTER_BASE . 'roster_footer.tpl' );
