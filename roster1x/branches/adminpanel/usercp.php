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

$roster_auth_level = 1;

if (!array_key_exists('page',$_GET))
	$_GET['page'] = 'update';

switch ($_GET['page'])
{
	case 'update':
		include(ROSTER_ADMIN.'update.php');
		break;

	default:
		$body = messagebox('Invalid page specified.','Roster User Panel','sred');
		break;
}


// ----[ Render the page ]----------------------------------
include_once( ROSTER_BASE.'roster_header.tpl' );
include_once( ROSTER_LIB.'menu.php' );

echo '<table width="100%"><tr><td valign="top" align="left">'.
	$menu.
	'</td><td valign="top" align="center">'.
	$body.
	'</td><td valign="top" align="right">'.
	$pagebar.
	'</td></tr></table>';

include_once( ROSTER_BASE.'roster_footer.tpl' );
?>