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

// Initialization
header('Content-Type: text/xml');
include('settings.php');
include(ROSTER_AJAX.'functions.php');
if( isset($_GET['method']) )
{
	$method = $_GET['method'];
}
else
{
	$method = '';
}
if( isset($_GET['cont']) )
{
	$cont = $_GET['cont'];
}
if( isset($_GET['addon']) )
{
	$addon = getaddon($_GET['addon']);
	// Check if addon is active
	if( $addon['active'] == '1' )
	{
		// Include addon's conf.php file
		if( file_exists($addon['conf_file']) )
		{
			include_once( $addon['conf_file'] );
		}

		include_once( $addon['ajax_file'] );
	}
}

// Check if the function is valid, if so run it, else error
if( isset($ajaxfuncs[$method]) )
{
	$status = 2;
	include($ajaxfuncs[$method]['file']);
	if( $status == 2 )
	{
		$errmsg = 'No result status set';
	}
}
elseif( $method == '')
{
	$status = 3;
	$errmsg = 'No method passed';
}
else
{
	$status = 1;
	$errmsg = 'This method is not supported';
}

// Output XML
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'."\n".
	'<response>'."\n".
	'  <method>'.$method.'</method>'."\n".
	(isset($cont)?'  <cont>'.$cont.'</cont>'."\n":'').
	(isset($result)?'  <result>'.$result.'</result>'."\n":'').
	'  <status>'.(int)$status.'</status>'."\n".
	(isset($errmsg)?'  <errmsg>'.$errmsg.'</errmsg>'."\n":'').
	'</response>'."\n";
?>
