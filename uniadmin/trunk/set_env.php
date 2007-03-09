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

if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}

clearstatcache();

// Be paranoid with passed vars
// Destroy GET/POST/Cookie variables from the global scope
if( intval(ini_get('register_globals')) != 0 )
{
	foreach( $_REQUEST AS $key => $val )
	{
		if( isset($$key) )
			unset($$key);
	}
}

// Disable magic quotes and add slashes to global arrays
set_magic_quotes_runtime(0);
if ( get_magic_quotes_gpc() == 0 )
{
    $_GET = slash_global_data($_GET);
    $_POST = slash_global_data($_POST);
    $_COOKIE = slash_global_data($_COOKIE);
}

define('CAN_INI_SET',!ereg('ini_set', ini_get('disable_functions')));

$phpver = explode('.', phpversion());
$phpver = "$phpver[0]$phpver[1]";
define('PHPVERSION', $phpver);
unset($phpver);

if( PHPVERSION < 43 )
{
	die('You must have at least PHP version 4.3 and higher to run UniAdmin');
}

if( !defined('DIR_SEP') )
{
	define('DIR_SEP',DIRECTORY_SEPARATOR);
}

define('UA_BASEDIR',dirname(__FILE__).DIR_SEP);


if( file_exists(UA_BASEDIR.'config.php') )
{
	include( UA_BASEDIR.'config.php' );
}


if( !defined('UA_INSTALLED') )
{
	define( 'IN_UNIADMIN',true );
	include(UA_BASEDIR.'include'.DIR_SEP.'constants.php');
    require(UA_MODULEDIR . 'install.php');
    die();
}

define( 'IN_UNIADMIN',true );


include(UA_BASEDIR.'include'.DIR_SEP.'constants.php');

include(UA_INCLUDEDIR.'uadebug.php');

include(UA_INCLUDEDIR.'dbal.php');
include(UA_INCLUDEDIR.'uniadmin.php');
include(UA_INCLUDEDIR.'user.php');
include(UA_INCLUDEDIR.'template.php');


$tpl = new Template;
$uniadmin = new UniAdmin();
$user = new User();


include(UA_INCLUDEDIR.'login.php');


// Check to run upgrader
if( $uniadmin->config['UAVer'] < UA_VER )
{
	if( $user->data['level'] == UA_ID_ADMIN )
	{
		require(UA_MODULEDIR . 'upgrade.php');
		die();
	}
	else
	{
		ua_die($user->lang['error_upgrade_needed']);
	}
}


// ----[ Check for latest UniAdmin Version ]------------------
if( $user->data['level'] == UA_ID_ADMIN && $uniadmin->config['check_updates'] )
{
	$ua_ver_latest = '';

	$content = $uniadmin->get_remote_contents('http://wowroster.net/ua_version.txt');

	if( preg_match('#<version>(.+)</version>#i',$content,$version) )
	{
		$ua_ver_latest = $version[1];
	}

	if( !empty($ua_ver_latest) && $ua_ver_latest > UA_VER )
	{
		$uniadmin->error(sprintf($user->lang['new_version_available'],$ua_ver_latest));
	}
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
