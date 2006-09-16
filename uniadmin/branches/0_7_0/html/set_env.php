<?php

ini_set('display_errors','1');
error_reporting(E_ALL);

// Initialize config array
$config = array();

define('UA_BASEDIR',dirname(__FILE__).DIRECTORY_SEPARATOR);



include(UA_BASEDIR.'config.php');


//////////////////////// FOLDER SETTINGS //////////////////////////
$config['addon_folder'] =			'addon_zips';
$config['temp_analyze_folder'] =	'addon_temp';
$config['logo_folder'] =			'logos';
$config['date_format'] =			'M jS, Y g:ia';


//////////////////// DATABASE TABLE NAMES //////////////////////////
$config['db_tables_addons'] = 		$config['db_prefix'].'addons';
$config['db_tables_files'] = 		$config['db_prefix'].'files';
$config['db_tables_logos'] = 		$config['db_prefix'].'logos';
$config['db_tables_settings'] = 	$config['db_prefix'].'settings';
$config['db_tables_stats'] = 		$config['db_prefix'].'stats';
$config['db_tables_users'] = 		$config['db_prefix'].'users';
$config['db_tables_svlist'] = 		$config['db_prefix'].'svlist';

////////////////////////// OTHER STUFF ////////////////////////////
$config['ziplibsupport'] =			false;
$config['UAVer'] = 					'0.7.0-beta';
$config['debugSetting'] =			true;

$url = explode('/','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
array_pop($url);
$url = implode('/',$url).'/';
$config['URL'] =					$url;
$config['IntLocation'] = 			$config['URL'].'index.php?p=interface';


define( 'UA_INDEXPAGE','index.php');
define( 'UA_FORMACTION',UA_INDEXPAGE.( isset($_GET['p']) ? '?p='.$_GET['p'] : '') );
define( 'IN_UNIADMIN',true );


include(UA_BASEDIR.'debug.php');
include(UA_BASEDIR.'MySqlCheck.php');
include(UA_BASEDIR.'EchoPage.php');

$dblink = @mysql_connect($config['host'],$config['username'],$config['password']);
if( $dblink )
{
	$uadb = @mysql_select_db($config['database'],$dblink);
	if( !$uadb )
	{
		debug('Cannot select the database ['.$config['database'].']');
		debug('MySQL Said: '.mysql_error() );
		die_ua();
	}
}
else
{
	debug('Cannot connect to the database');
	debug('MySQL Said: '.mysql_error() );
	die_ua();
}


$uamessages = array();
$uadebug = array();

include(UA_BASEDIR.'cookieFunctions.php');

if( !isset($interface) )
{
	include(UA_BASEDIR.'login.php');
	include(UA_BASEDIR.'menu.php');
}


function die_ua()
{
	EchoPage('','Uniadmin Error');
	die();
}

?>