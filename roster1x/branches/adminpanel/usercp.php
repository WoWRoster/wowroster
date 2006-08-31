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
 *		update		Update page
 *
 ******************************/

require_once('settings.php');

$script_filename = 'usercp.php';

// ----[ Check log-in ]-------------------------------------
$roster_login = new RosterLogin($script_filename);
$loginmsg = $roster_login->getMessage();

$showlogin = ($roster_login->getUserName() == '');

// ----[ End Check log-in ]---------------------------------

if (!array_key_exists('page',$_GET))
	$_GET['page'] = 'update';

switch ($_GET['page'])
{
	case 'update':
		include(ROSTER_ADMIN.'update.php');
		break;

	case 'password':
		include(ROSTER_ADMIN.'user_pass.php');
		break;

	default:
		$body .= messagebox('Invalid page specified.','Roster User Panel','sred');
		break;
}

include(ROSTER_ADMIN.'user_pagebar.php');

// ----[ Render the page ]----------------------------------
include_once( ROSTER_BASE.'roster_header.tpl' );
include_once( ROSTER_LIB.'menu.php' );

echo '<table width="100%"><tr><td valign="top" align="left">'.
	$menu.
	'</td><td valign="top" align="center">'.
	$loginmsg.(($showlogin)?$roster_login->getLoginForm():'')."<br />\n".
	$body.
	'</td><td valign="top" align="right">'.
	$pagebar.
	'</td></tr></table>';

include_once( ROSTER_BASE.'roster_footer.tpl' );
?>