<?php
/**
 * WoWRoster.net WoWRoster
 *
 * RosterCP (Control Panel)
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
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

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}
$roster->tpl->assign_vars(array(
	'PAGE_INFO' => $roster->locale->act['roster_cp'],
	)
);
// ----[ Check log-in ]-------------------------------------
if( ! $roster->auth->getAuthorized( ROSTERLOGIN_ADMIN ) )
{
	echo $roster->auth->getLoginForm();
	return;
}
// ----[ End Check log-in ]---------------------------------

define('IN_ROSTER_ADMIN',true);

include_once(ROSTER_ADMIN . 'pages.php');

$header = $menu = $footer = $body = $rcp_message = '';

// ----[ Check for latest WoWRoster Version ]------------------

if( $roster->config['check_updates'] && isset($roster->config['versioncache']) )
{
	$cache = unserialize($roster->config['versioncache']);

	if( $roster->config['versioncache'] == '' )
	{
		$cache['timestamp'] = 0;
		$cache['ver_latest'] = '';
		$cache['ver_info'] = '';
		$cache['ver_date'] = '';
	}

	if( ($cache['timestamp'] + (60 * 60 * $roster->config['check_updates'])) <= time() )
	{
		$cache['timestamp'] = time();

		$content = urlgrabber(ROSTER_UPDATECHECK);

		if( preg_match('#<version>(.+)</version>#i',$content,$version) )
		{
			$cache['ver_latest'] = $version[1];
		}

		if( preg_match('#<info>(.+)</info>#i',$content,$info) )
		{
			$cache['ver_info'] = $info[1];
		}

		if( preg_match('#<updated>(.+)</updated>#i',$content,$info) )
		{
			$cache['ver_date'] = $info[1];
		}

		$roster->db->query ( "UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '" . serialize($cache) . "' WHERE `id` = '6' LIMIT 1;");

	}

	if( version_compare($cache['ver_latest'],ROSTER_VERSION,'>') )
	{
		$cache['ver_date'] = date($roster->locale->act['phptimeformat'], $cache['ver_date'] + (3600*$roster->config['localtimeoffset']));
		$rcp_message .= messagebox(sprintf($roster->locale->act['new_version_available'],'WoWRoster',$cache['ver_latest'],$cache['ver_date'],'http://www.wowroster.net') . '<br />' . $cache['ver_info'],$roster->locale->act['update']);
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
		$rcp_message .= messagebox(sprintf($roster->locale->act['roster_cp_not_exist'],$page),$roster->locale->act['roster_cp'],'sred');
	}
}
else
{
	$rcp_message .= messagebox($roster->locale->act['roster_cp_invalid'],$roster->locale->act['roster_cp'],'sred');
}

// Build the pagebar from admin/pages.php
foreach( $config_pages as $pindex => $data )
{
	$pagename = $roster->pages[0] . ( $page != 'roster' ? '-' . $page : '' );

	if( !isset($data['special']) || $data['special'] != 'hidden' )
	{
		$roster->tpl->assign_block_vars('pagebar',array(
			'SPECIAL' => ( isset($data['special']) ? $data['special'] : '' ),
			'SELECTED' => ( isset($data['href']) ? ($pagename == $data['href'] ? true : false) : ''),
			'LINK' => ( isset($data['href']) ? makelink($data['href']) : '' ),
			'NAME' => ( isset($data['title']) ? ( isset($roster->locale->act[$data['title']]) ? $roster->locale->act[$data['title']] : $data['title'] ) : '' ),
			)
		);
	}
}

// Refresh the addon list because we may have installed/uninstalled something
$roster->get_addon_data();

$roster->tpl->assign_var('ADDON_PAGEBAR',(bool)count($roster->addon_data));

foreach( $roster->addon_data as $row )
{
	$addon = getaddon($row['basename']);

	$rcp_message .= updateCheck($addon);

	if( file_exists($addon['admin_dir'] . 'index.php') || $addon['config'] != '' )
	{
		// Save current locale array
		// Since we add all locales for localization, we save the current locale array
		// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
		$localetemp = $roster->locale->wordings;

		foreach( $roster->multilanguages as $lang )
		{
			$roster->locale->add_locale_file(ROSTER_ADDONS . $row['basename'] . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php',$lang);
		}

		$roster->tpl->assign_block_vars('addon_pagebar',array(
			'SELECTED' => (isset($roster->pages[2]) && $roster->pages[2] == $row['basename'] ? true : false),
			'LINK' => makelink('rostercp-addon-' . $row['basename']),
			'NAME' => ( isset($roster->locale->act[$row['fullname']]) ? $roster->locale->act[$row['fullname']] : $row['fullname'] ),
			)
		);

		// Restore our locale array
		$roster->locale->wordings = $localetemp;
		unset($localetemp);
	}
}

// ----[ Render the page ]----------------------------------

// Generate a title, so the user knows where they are at in RosterCP
$rostercp_title = $roster->locale->act['roster_cp_ab'];
if( isset($roster->pages[1]) )
{
	if( $roster->pages[1] == 'addon' )
	{
		$fullname = $roster->addon_data[$roster->pages[2]]['fullname'];
		$rostercp_title = ( isset($roster->locale->act[$fullname]) ? $roster->locale->act[$fullname] : $fullname );
	}
	elseif( $roster->pages[1] != '' )
	{
		$rostercp_title = ( isset($config_pages[$roster->pages[1]]['title']) ?
		( isset($roster->locale->act[$config_pages[$roster->pages[1]]['title']]) ? $roster->locale->act[$config_pages[$roster->pages[1]]['title']] : $config_pages[$roster->pages[1]]['title'] ) : '' );
	}
}

$roster->tpl->assign_vars(array(
	'ROSTERCP_TITLE'  => $rostercp_title,
	'ROSTERCP_MESSAGE' => $rcp_message,
	'HEADER' => $header,
	'MENU' => $menu,
	'BODY' => $body,
	'PAGE_INFO' => $roster->locale->act['roster_cp'],
	'FOOTER' => $footer,
	)
);

$roster->tpl->set_filenames(array('rostercp' => 'rostercp.html'));
$roster->tpl->display('rostercp');
