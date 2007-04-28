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
	'<span class="title_text">' . $act_words['roster_config'] . '</span><br />'.
	$roster_login->getMessage().
	$roster_login->getLoginForm();

	include_once (ROSTER_BASE . 'roster_footer.tpl');
	exit();
}
// ----[ End Check log-in ]---------------------------------

include_once(ROSTER_ADMIN . 'pages.php');

$header = $menu = $body = $pagebar = $footer = '';

// ----[ Check for latest UniAdmin Version ]------------------
if( $roster_conf['check_updates'] )
{
	$roster_ver_latest = $roster_ver_info = '';

	$content = urlgrabber('http://wowroster.net/roster_updater/version.txt');

	if( preg_match('#<version>(.+)</version>#i',$content,$version) )
	{
		$roster_ver_latest = $version[1];
	}

	if( preg_match('#<info>(.+)</info>#i',$content,$info) )
	{
		$roster_ver_info = $info[1];
	}

	if( version_compare($roster_ver_latest,ROSTER_VERSION,'>') )
	{
		$header = messagebox(sprintf($act_words['new_version_available'],'WoWRoster',$roster_ver_latest) . '<br />' . $roster_ver_info,$act_words['update']);
	}
}

// Find out what subpage to include, and do so
$page = (isset($roster_pages[1]) && ($roster_pages[1]!='')) ? $roster_pages[1] : 'roster';

if( isset($config_pages[$page]['file']) )
{
	if (file_exists(ROSTER_ADMIN . $config_pages[$page]['file']))
	{
		require_once(ROSTER_ADMIN . $config_pages[$page]['file']);
	}
	else
	{
		$body .= $roster_login->getMessage() . '<br />' . messagebox(sprintf($act_words['roster_cp_not_exist'],$page),$act_words['roster_cp'],'sred');
	}
}
else
{
	$body .= $roster_login->getMessage() . '<br />' . messagebox($act_words['roster_cp_invalid'],$act_words['roster_cp'],'sred');
}

// Build the pagebar from admin/pages.php
foreach ($config_pages as $pindex => $data)
{
	if (!isset($data['special']))
	{
		$pagebar .= '<li' . ($roster_pages[0] . '-' . $page == $data['href'] ? ' class="selected"' : '') . '><a href="' . makelink($data['href']) . '">' . $act_words[$data['title']] . "</a></li>\n";
	}
	elseif ($data['special'] == 'divider')
	{
		$pagebar .= '<li><hr /></li>';
	}
}

if ($pagebar != '')
{
	$pagebar = "<ul class=\"tab_menu\">\n$pagebar</ul>";
	$pagebar = messagebox($pagebar,$act_words['pagebar_function']) . "<br />\n";
}

// Add addon buttons
$query = 'SELECT `basename` FROM `' . $wowdb->table('addon') . '` ORDER BY `basename`;';
$result = $wowdb->query($query);
if( !$result )
{
	die_quietly('Could not fetch addon records for pagebar','Roster Admin Panel',__LINE__,basename(__FILE__),$query);
}

if( $wowdb->num_rows($result) > 0 )
{
	$addon_pagebar = '';
	while($row = $wowdb->fetch_assoc($result))
	{
		$addon = getaddon($row['basename']);

		if( file_exists($addon['admin_dir'] . 'index.php') || $addon['config'] != '' )
		{
			$addon_pagebar .= '<li' . (isset($roster_pages[2]) && $roster_pages[2] == $row['basename'] ? ' class="selected"' : '') . '><a href="' . makelink('rostercp-addon-' . $row['basename']) . '">' . $row['basename'] . "</a></li>\n";
		}
	}
	if( $addon_pagebar != '' )
	{
		$pagebar .= border('sgray','start',$act_words['pagebar_addonconf']) . "\n";
		$pagebar .= '<ul class="tab_menu">' . "\n";
		$pagebar .= $addon_pagebar;
		$pagebar .= "</ul>\n";
		$pagebar .= border('sgray','end') . "\n";
	}
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
