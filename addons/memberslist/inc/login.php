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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// ----[ Check log-in ]-------------------------------------
$roster_login = new RosterLogin();

// Disallow viewing of the page
if( !$roster_login->getAuthorized(2) )
{
	include_once (ROSTER_BASE.'roster_header.tpl');

	$roster_menu = new RosterMenu;
	print $roster_menu->makeMenu('main');

	print
	'<span class="title_text">'.$wordings[$roster_conf['roster_lang']]['roster_config'].'</span><br />'.
	$roster_login->getMessage().
	$roster_login->getLoginForm();

	include_once (ROSTER_BASE.'roster_footer.tpl');

	exit();
}
// ----[ End Check log-in ]---------------------------------
