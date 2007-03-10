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


define('ROSTER_PAGE_NAME', $page);

$pages = explode('-', $page);
$page = $pages[0];

if( preg_match('/[^a-zA-Z0-9_-]/', ROSTER_PAGE_NAME) )
{
	roster_die("Invalid characters in module name");
}

//---[ Check for Guild Info ]------------
if( empty($guild_info) && !in_array($page,array('rostercp','update','credits','license')) )
{
	roster_die( $wordings[$roster_conf['roster_lang']]['nodata'] , 'No Guild Data' );
}

// Include the module
if( is_file( $var = ROSTER_PAGES . $page . '.php' ) )
{
	require($var);
}
else
{
	roster_die("The page ($page) does not exist");
}

unset($page,$var);

$wowdb->closeDb();