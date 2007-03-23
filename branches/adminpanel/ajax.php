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
include('settings.php');
include(ROSTER_AJAX.'functions.php');
header('Content-Type: text/xml');
$method = $_GET['method'];
$cont = $_GET['cont'];

// Check if the function is valid, if so run it, else error
if( isset($ajaxfuncs[$method]) )
{
	include(ROSTER_AJAX.$ajaxfuncs[$method]['file']);
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
	'  <cont>'.$cont.'</cont>'."\n".
	'  <result>'.$result.'</result>'."\n".
	'  <status>'.(int)$status.'</status>'."\n".
	'  <errmsg>'.$errmsg.'</errmsg>'."\n".
	'</response>'."\n";
?>
