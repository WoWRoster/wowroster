<?php
/******************************
 * WoWRoster.net  UniAdmin
 * Copyright 2002-2007
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

// Include the initialization file
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'set_env.php');

// Determine the module request
$page = ( isset($_GET[UA_URI_PAGE]) ) ? $_GET[UA_URI_PAGE] : 'help';

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