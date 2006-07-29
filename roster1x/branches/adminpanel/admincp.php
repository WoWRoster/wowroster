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
 * page		roster		Roster config
 *		character	Per-character preferences
 *		addon		Addon config
 *		install		Addon installation screen
 *
 * addon	If page is addon, this says which addon is being configured
 * profile	If page is addon, this says which addon profile is being configured.
 * 
 ******************************/
 
require_once('settings.php');

// ----[ Check log-in ]-------------------------------------
$roster_login = new RosterLogin($script_filename);

// Disallow viewing of the page
if( !$roster_login->getAuthorized() )
{
	include_once (ROSTER_BASE.'roster_header.tpl');
	include_once (ROSTER_LIB.'menu.php');

	print
	'<span class="title_text">'.$wordings[$roster_conf['roster_lang']]['roster_config'].'</span><br />'.
	$roster_login->getMessage().
	$roster_login->getLoginForm();

	include_once (ROSTER_BASE.'roster_footer.tpl');

	exit();
}
// ----[ End Check log-in ]---------------------------------

if (!array_key_exists('page',$_GET)) $_GET['page'] = 'roster';

switch ($_GET['page']) {
case 'roster':
	// Code to call config library with roster config pages
	include(ROSTER_BASE.'admin/roster_conf.php');
	break;
case 'character':
	// Code to call character preferences screen
	include(ROSTER_BASE.'admin/character_conf.php');
	break;
case 'addon':
	// Code to call config library with addon config pages
	include(ROSTER_BASE.'admin/addon_conf.php');
	break;
case 'installer':
	// Code to list all installable/installed addons
	include(ROSTER_BASE.'admin/addon_install.php');
	break;
default:
	// Display an error message
	break;
}

include(ROSTER_BASE.'admin/pagebar.php');

// ----[ Render the page ]----------------------------------
include_once( ROSTER_BASE.'roster_header.tpl' );
include_once( ROSTER_LIB.'menu.php' );
echo '<table><tr><td valign="top" align="left">'.
	$menu.
	'</td><td valign="top" align="center">'.
	$body.
	'</td><td valign="top" align="right">'.
	$pagebar.
	'</td></tr></table>';
include_once( ROSTER_BASE.'roster_footer.tpl' );
?>