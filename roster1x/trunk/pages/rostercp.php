<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// ----[ Check log-in ]-------------------------------------
$roster_login = new RosterLogin('');

// Disallow viewing of the page
if( !$roster_login->getAuthorized() )
{
	include_once (ROSTER_BASE.'roster_header.tpl');
	include_once (ROSTER_LIB.'menu.php');

	print
	'<span class="title_text">'.$act_words['roster_config'].'</span><br />'.
	$roster_login->getMessage().
	$roster_login->getLoginForm();

	include_once (ROSTER_BASE.'roster_footer.tpl');

	exit();
}
// ----[ End Check log-in ]---------------------------------

include_once(ROSTER_ADMIN.'pages.php');

$menu = '';
$body = '';
$pagebar = '';

// Find out what subpage to include, and do so
$page = (isset($pages[1]) && ($pages[1]!=''))?$pages[1]:'roster';

if( isset($pages[$page]['file']) )
{
	if (file_exists(ROSTER_ADMIN.$pages[$page]['file']))
	{
		require_once(ROSTER_ADMIN.$pages[$page]['file']);
	}
	else
	{
		$body .= messagebox('File does not exist for page '.$page.'.','Roster Control Panel','sred');
	}
}
else
{
	$body .= messagebox('Invalid page specified or insufficient credentials to access this page.','Roster Admin Panel','sred');
}

// Build the pagebar from admin/pages.php
foreach ($pages as $page => $data)
{
	if (!isset($data['special']))
	{
		$pagebar .= '<li><a href="'.makelink($data['href']).'">'.$act_words[$data['title']].'</a></li>'."\n";
	}
	elseif ($data['special'] == 'divider')
	{
		$pagebar .= '<li><hr /></li>';
	}
}

if ($pagebar != '')
{
	$pagebar = border('sgray','start',$act_words['pagebar_function'])."\n".
		'<ul class="tab_menu">'."\n".
		$pagebar.
		'</ul>'."\n".
		border('sgray','end')."\n".
		"<br />\n";
}

// Add addon buttons
if ($handle = opendir(ROSTER_ADDONS))
{
	$addons = array();

	while (false !== ($file = readdir($handle)))
	{
		if( is_dir(ROSTER_ADDONS.$file) && $file != '.' && $file != '..' && !preg_match('/[^a-zA-Z0-9_.]/', $file) && is_file($adminfile = (ROSTER_ADDONS.$file.DIR_SEP.'admin.php')))
		{
			$addons[$file] = $adminfile;
		}
	}
}

if( count($addons)>0 )
{
	$pagebar .= border('sgray','start',$act_words['pagebar_addonconf'])."\n";
	$pagebar .= '<ul class="tab_menu">'."\n";
	foreach( $addons as $addon => $adminfile )
	{
		$pagebar .= '<li><a href="'.makelink('rostercp-addon-'.$addon).'">'.$addon.'</a></li>'."\n";
	}
	$pagebar .= '</ul>'."\n";
	$pagebar .= border('sgray','end')."\n";
}

// ----[ Render the page ]----------------------------------
include_once( ROSTER_BASE.'roster_header.tpl' );
include_once( ROSTER_LIB.'menu.php' );
echo
	$header."\n".
	'<table width="100%"><tr><td valign="top" align="left">'."\n".
	$menu."\n".
	'</td><td valign="top" align="center">'."\n".
	( ($showlogin) ? $roster_login->getLoginForm() : '' )."<br /><br />\n".
	$body."\n".
	'</td><td valign="top" align="right">'."\n".
	$pagebar."\n".
	'</td></tr></table>'."\n".
	$footer;

include_once( ROSTER_BASE.'roster_footer.tpl' );
