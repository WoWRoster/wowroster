<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Ajax interface file
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage Ajax
*/

if( !isset($_GET[ROSTER_PAGE]) )
{
	$_GET[ROSTER_PAGE] = 'ajax';
}
// Initialization
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'settings.php');

include(ROSTER_AJAX . 'functions.php');
include(ROSTER_LIB . 'minixml.lib.php');

$method = (isset($_GET['method']) ? $_GET['method'] : '');

$cont = (isset($_GET['cont']) ? $_GET['cont'] : '');

$errmsg = $result = '';


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

header('Content-Type: text/xml');

// Output XML
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'."\n".
	'<response>'."\n".
	'  <method>'.$method.'</method>'."\n".
	'  <cont>'.$cont.'</cont>'."\n".
	'  <result>'.$result.'</result>'."\n".
	'  <status>'.(int)$status.'</status>'."\n".
	'  <errmsg>'.$errmsg.'</errmsg>'."\n".
	'</response>'."\n";

