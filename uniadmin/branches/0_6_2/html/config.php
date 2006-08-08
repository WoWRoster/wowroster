<?php
$config['host'] = 					'localhost';		// dbase host
$config['username'] = 				'root';				// dbase username
$config['password'] = 				'';					// dbase password
$config['database'] = 				'database_name';	// dbase name

//////////////// DO NOT TOUCH ANYTHING AFTER THIS LINE ////////////
ini_set('display_errors','1');
error_reporting(E_WARNING);
///////////////////////// DATABASE ////////////////////////////////
$config['db_prefix'] = 				'uniadmin_';
$config['db_tables_addons'] = 		$config['db_prefix'].'addons';
$config['db_tables_files'] = 		$config['db_prefix'].'files';
$config['db_tables_logos'] = 		$config['db_prefix'].'logos';
$config['db_tables_settings'] = 	$config['db_prefix'].'settings';
$config['db_tables_stats'] = 		$config['db_prefix'].'stats';
$config['db_tables_users'] = 		$config['db_prefix'].'users';
$config['db_tables_svlist'] = 		$config['db_prefix'].'svlist';
//////////////////////// FOLDER SETTINGS //////////////////////////
$config['addon_folder'] = 			'addon_zips';
$config['temp_analyze_folder'] = 	'addon_temp';
$config['logo_folder'] = 			'logos';
////////////////////////// OTHER STUFF ////////////////////////////
$config['ziplibsupport'] =			false;
$config['UAVer'] = 					'Beta 0.6.1';
$config['debugSetting'] =			true;
$url = explode('/','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
array_pop($url);
$url = implode('/',$url).'/';
$config['URL'] =					$url;
$config['IntLocation'] = 			$config['URL'].'interface.php';
$dblink=mysql_connect($config['host'],$config['username'],$config['password']);
mysql_select_db($config['database'],$dblink);
include('debug.php');
include('MySqlCheck.php');
include('css.php');
include('cookieFunctions.php');

if (!$interface){
	include('login.php');
	include('header.php');
	include('EchoPage.php');
}
?>
