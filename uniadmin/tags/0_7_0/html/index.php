<?php
/******************************
 * WoWRoster.net  UniAdmin
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

// This picks up the view page so people with no login can look at the addons
if( isset($_GET['p']) && ($_GET['p'] == 'view' || $_GET['p'] == 'interface') )
{
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.$_GET['p'].'.php');
	die();
}

// Include the initialization file
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'set_env.php');


// This is a list of allowed actions that a person can make.
// For each action, there is a corresponding php file, i.e.
// help == modules/help.php
// Any action that is attempted that isn't listed in this array will
// refer them to the help page (or the login screen if they are not logged in)
$allowed_pages = array(
	'addons',
	'settings',
	'logo',
	'help',
	'stats',
	'users',
	'pref',
	);



// ----[ Decide what to do next ]-------------------------------
if( isset($_GET['p']) )
{
	$page = $_GET['p'];
}
else
{
	$page = '';
}

if( in_array($page, $allowed_pages) )
{
	include_once(UA_MODULEDIR.$page.'.php');
}
else
{
	include_once(UA_MODULEDIR.'help.php');
}

$db->close_db();

?>