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

require_once('settings.php');
include_once(ROSTER_ADMIN.'pages.php');

$script_filename = 'rostercp.php';

// ----[ Check log-in ]-------------------------------------

$showlogin = ( $roster_login->getUserName() == '' );

// ----[ End Check log-in ]---------------------------------

$menu = '';
$body = '';
$pagebar = '';


$page = (array_key_exists('page',$_GET) && $_GET['page'] != '') ? $_GET['page'] : ( ($showlogin) ? 'create' : 'update' );

if (isset($pages[$page]['file']) and (!isset($pages[$page]['access']) or $roster_login->getAuthorized($pages[$page]['access'])))
{ // There is something defined to include and we're allowed to access it
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

foreach ($pages as $page => $data)
{
	if (!isset($data['access']) or $roster_login->getAuthorized($data['access']))
	{
		if (!isset($data['special']))
		{
			$pagebar .= '<li><a href="'.$data['href'].'">'.$act_words[$data['title']].'</a></li>'."\n";
		}
		elseif ($data['special'] == 'divider')
		{
			$pagebar .= '<li><hr></li>';
		}
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

if ($roster_login->getAuthorized())
{
	$query = 'SELECT `basename`,`dbname`,`hasconfig` FROM `'.$wowdb->table('addon').'` WHERE `hasconfig` != ""';
	$result = $wowdb->query($query);
	if( !$result )
	{
		die_quietly('Could not fetch addon records for pagebar','Roster Admin Panel',__LINE__,basename(__FILE__),$query);
	}

	if ($wowdb->num_rows($result))
	{
		$pagebar .= border('sgray','start',$act_words['pagebar_addonconf'])."\n";
		$pagebar .= '<ul class="tab_menu">'."\n";
		while($row = $wowdb->fetch_assoc($result))
		{
			include(ROSTER_ADDONS.$row['basename'].DIR_SEP.'localization.php');
			$pagebar .= '<li><a href="?page=addon&amp;addon='.$row['dbname'].'&amp;profile='.$row['hasconfig'].'">'.$row['basename'].' - '.$row['dbname'].'</a></li>'."\n";
		}
		$pagebar .= '</ul>'."\n";
		$pagebar .= border('sgray','end')."\n";
	}

	$wowdb->free_result($result);
}

// ----[ Render the page ]----------------------------------
include_once( ROSTER_BASE.'roster_header.tpl' );
echo 	$roster_menu->makeMenu('main').
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
?>
