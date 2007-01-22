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
if( isset($_GET['p']) && $_GET['p'] == 'interface' )
{
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.$_GET['p'].'.php');
	die();
}

// Include the initialization file
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'set_env.php');

// Check to run upgrader
if( $uniadmin->config['UAVer'] < UA_VER )
{
	require(UA_MODULEDIR . 'upgrade.php');
	die();
}

// Determine the module request
$page = ( isset($_GET['p']) ) ? $_GET['p'] : 'help';

// Include the module
if( is_file( $var = UA_MODULEDIR . $page . '.php' ) )
{
	require($var);
}
else
{
	require(UA_MODULEDIR . 'help.php');
}

$db->close_db();

?>