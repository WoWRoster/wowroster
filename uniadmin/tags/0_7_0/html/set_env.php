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

if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}

error_reporting(E_ALL);

// Disable magic quotes and add slashes to global arrays
set_magic_quotes_runtime(0);
if ( get_magic_quotes_gpc() == 0 )
{
    $_GET = slash_global_data($_GET);
    $_POST = slash_global_data($_POST);
    $_COOKIE = slash_global_data($_COOKIE);
}

if( !defined('DIR_SEP') )
{
	define('DIR_SEP',DIRECTORY_SEPARATOR);
}

define('UA_BASEDIR',dirname(__FILE__).DIR_SEP);


include(UA_BASEDIR.'config.php');


$url = explode('/','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
array_pop($url);
$url = implode('/',$url).'/';


define( 'IN_UNIADMIN',true );


include(UA_BASEDIR.'include'.DIR_SEP.'constants.php');
include(UA_INCLUDEDIR.'dbal.php');
include(UA_INCLUDEDIR.'uniadmin.php');
include(UA_INCLUDEDIR.'user.php');
include(UA_INCLUDEDIR.'template.php');


$tpl = new Template;
$uniadmin = new UniAdmin($url);
$user = new User();

if( !isset($interface) )
{
	include(UA_INCLUDEDIR.'login.php');
	include(UA_INCLUDEDIR.'menu.php');
}


function die_ua( )
{
	display_page('',$user->lang['error']);
	die();
}

/**
* Applies addslashes() to the provided data
*
* @param $data Array of data or a single string
* @return mixed Array or string of data
*/
function slash_global_data( $data )
{
	if( is_array($data) )
	{
		foreach( $data as $k => $v )
		{
			$data[$k] = ( is_array($v) ) ? slash_global_data($v) : addslashes($v);
		}
	}
	return $data;
}

?>