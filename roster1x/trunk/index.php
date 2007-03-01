<?php
/******************************
 * WoWRoster.net  Roster
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

require_once( dirname(__FILE__).DIRECTORY_SEPARATOR.'settings.php' );

// Determine the module request
$page = ( isset($_GET[ROSTER_PAGE]) && !empty($_GET[ROSTER_PAGE]) ) ? $_GET[ROSTER_PAGE] : $roster_conf['default_page'];

if( strpos($page,'addon@') !== false )
{
	list($page,$addon_name) = explode('@',$page,2);
}

if( preg_match('/[^a-zA-Z0-9_]/', $page) )
{
	roster_die("Invalid characters in module name");
}

define('ROSTER_PAGE_NAME', $page);

// Include the module
if( is_file( $var = ROSTER_PAGES . $page . '.php' ) )
{

	require($var);
}
else
{
	roster_die("The page ($page) does not exist");
}

$wowdb->closeDb();