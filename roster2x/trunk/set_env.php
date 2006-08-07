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

// ----[ Prevent Direct Access to this file ]-------------------
if( !defined('ROSTER_INCLUDED') )
{
	exit("You can't access this file directly!");
}


// ----[ Check if conf.php has been set ]-----------------------
if ( !defined('ROSTER_INSTALLED') )
{
	header('Location: install.php');
	exit();
}


// ----[ PHP Error Reporting ]----------------------------------
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL);


// ----[ Get OS specific seperator ]----------------------------
if( !defined('DIR_SEP') )
{
	define( 'DIR_SEP', DIRECTORY_SEPARATOR );
}
if( !defined('PATH_SEP') )
{
	define( 'PATH_SEP',PATH_SEPARATOR );
}


// ----[ Define the roster base path ]--------------------------
$app_root = getBasePath();

if ( $app_root == null )
{
	exit("\n<br /><b>FATAL ERROR:</b> Could not obtain base Path");
}
else
{
	define( 'ROSTER_BASE',$app_root.DIR_SEP );
}


/* COMMENTED OUT TO TEST IF ROSTER WILL WORK WITHOUT THIS*/
// ----[ Set the include path ]---------------------------------
ini_set( 'include_path', '.' . PATH_SEP . ROSTER_BASE . 'include' . PATH_SEP . ROSTER_BASE );



// ----[ Include common roster functions ]----------------------
require_once( ROSTER_BASE.'functions'.DIR_SEP.'common.php' );



// ----[ Include roster constants ]-----------------------------
require_once( ROSTER_BASE.'functions'.DIR_SEP.'constants.php' );



// ----[ Set up the Smarty Template Object ]--------------------
define( 'SMARTY_DIR',ROSTER_BASE.'include'.DIR_SEP.'smarty'.DIR_SEP );
require_once( SMARTY_DIR.'Smarty.class.php' );
$tpl = new smarty;
$tpl->config_dir = SMARTY_DIR.'config';


// ----[ Set up the Smarty Plug-ins Directory ]-----------------
$tpl->plugins_dir[] = SMARTY_DIR . 'plugins';
$tpl->plugins_dir[] = SMARTY_DIR . 'roster_smarty_plugins';


// ----[ Database link ]----------------------------------------
// Include the database abstraction files
require_once( ROSTER_BASE.'include'.DIR_SEP.'DB.php' );
require_once( ROSTER_BASE.'functions'.DIR_SEP.'db_layer.php');
require_once( ROSTER_BASE.'functions'.DIR_SEP.'schema_schemer.php');
SyncSchema();

// ----[ Delete database settings for security ]----------------
$db_connect_str = NULL;
$roster_conf['db_host'] = NULL;
$roster_conf['db_user'] = NULL;
$roster_conf['db_pass'] = NULL;


// ----[ Get roster settings from the database ]----------------
include_once( ROSTER_BASE.'settings.php' );


// ----[ Define Path to the template files ]--------------------
define( 'TEMPLATE_DIR',ROSTER_BASE.'themes'.DIR_SEP.$roster_conf['theme'] );
$tpl->template_dir = TEMPLATE_DIR;


// ----[ Set up the Smarty caching options ]--------------------
$tpl->caching = false;
$tpl->cache_handler_func = 'smarty_cache_handler';


// ----[ Set up the Smarty compile options ]--------------------
$tpl->compile_dir = ROSTER_BASE.'themes'.DIR_SEP.'compiled';
$tpl->compile_id = $roster_conf['theme'];
$tpl->compile_check = true;
$tpl->force_compile = true;


// ----[ Smarty Debug Settings ]--------------------------------
$tpl->debugging = $roster_conf['smarty_debug'];
$tpl->debug_tpl = TEMPLATE_DIR . DIR_SEP . 'debug.tpl';


// ----[ Define roster's webroot ]------------------------------
define( 'ROSTER_WEBROOT','http://'.$_SERVER['HTTP_HOST'] );



// ----[ Include the localization variables ]-------------------
foreach( getLocaleFiles() as $localefile )
{
	include_once( ROSTER_BASE.'localization'.DIR_SEP.$localefile );
}


// ----[ Declare tooltip and sql globals ]----------------------
$tooltips = array();
$sqlqueries = array();



// ----[ Compress Output with GZIP ]----------------------------
if ( $roster_conf['gzip_compress'] && extension_loaded('zlib') )
{
	ob_start('ob_gzhandler');
}



// ----[ Start session ]----------------------------------------
session_start();




// ----[ Assign Global Smarty Values ]--------------------------

// Assign the Entire config array to "roster_conf"
$tpl->assign( 'roster_conf' , $roster_conf );

// Assign the language array
$tpl->assign( 'roster_wordings' , $roster_wordings );



// ----[ Set_Env Functions ]------------------------------------






/**
 * Obtains the "base" absolute Path
 *
 * @return string	"base" absolute Path
 */
function getBasePath()
{
	$currentPath = explode(DIR_SEP,realpath('.'));
	$foundPath = false;
	while (!$foundPath)
	{
		$testPath = implode(DIR_SEP,$currentPath);
		if (file_exists($testPath.DIR_SEP.'set_env.php'))
		{
			$foundPath = true;
			$basePath = $testPath;
		}
		if( array_pop($currentPath) == null )
		{
			return null;
		}
	}
	return $basePath;
}

/**
 * Cache Handler...for writing, reading, and deleting cache files to/from the database
 *
 * @param string $action
 * @param object $smarty_obj
 * @param string $cache_content
 * @param string $tpl_file
 * @param string $cache_id
 * @param string $compile_id
 * @param string $exp_time
 * @return SQL result
 */
function smarty_cache_handler( $action, &$smarty_obj, &$cache_content, $tpl_file=null, $cache_id=null, $compile_id=null, $exp_time=null )
{
	// create unique cache id
	$CacheID = md5( $tpl_file.$cache_id.$compile_id );

	$use_gzip = true;

	switch( $action )
	{
		case 'read':
			// read cache from database
			$sqlquery = "SELECT `cache_contents` FROM ".ROSTER_TEMPLATECACHE." WHERE `cache_id` = '$CacheID'";
			setSqlQuery($sqlquery);

			$result = db_query($sqlquery);
			if( PEAR::isError($result) )
			{
			    die_quietly($result->getMessage(),'',basename(__FILE__),(__LINE__),$sqlquery);
			}

			$result->fetchInto($row);

			if( $use_gzip && function_exists('gzuncompress') )
			{
				$cache_content = gzuncompress($row['cache_contents']);
			}
			else
			{
				$cache_content = $row['cache_contents'];
			}
			$return = $result;
			break;

		case 'write':
			// save cache to database

			if($use_gzip && function_exists('gzcompress'))
			{
				// compress the contents for storage efficiency
				$contents = gzcompress($cache_content);
			}
			else
			{
				$contents = $cache_content;
			}

			$sqlquery = "REPLACE INTO ".ROSTER_TEMPLATECACHE." values('$CacheID','".addslashes($contents)."')";
			setSqlQuery($sqlquery);

			$result = db_query($sqlquery);
			if( PEAR::isError($result) )
			{
			    die_quietly($result->getMessage(),'',basename(__FILE__),(__LINE__),$sqlquery);
			}

			$return = $result;
			break;

		case 'clear':
			// clear cache info
			if(empty($cache_id) && empty($compile_id) && empty($tpl_file))
			{
				// clear them all
				$sqlquery = "DELETE FROM ".ROSTER_TEMPLATECACHE;
			}
			else
			{
				$sqlquery = "DELETE FROM ".ROSTER_TEMPLATECACHE." WHERE `cache_id` = '$CacheID'";
			}

			setSqlQuery($sqlquery);

			$result = db_query($sqlquery);
			if( PEAR::isError($result) )
			{
			    die_quietly($result->getMessage(),'',basename(__FILE__),(__LINE__),$sqlquery);
			}
			$return = $result;
			break;

		default:
			// error, unknown action
			die_quietly( (__FUNCTION__).': unknown action '.$action );
			$return = false;
			break;
	}
	return $return;
}

/**
 * Gets the filename of each file in the localization folder
 *
 * @return array	Contains filename of each file in localization
 */
function getLocaleFiles()
{
	$localePath = ROSTER_BASE.'localization';
    $files_to_ignore = array('.', '..', 'CVS', '.svn');

	if( $handle = opendir(realpath($localePath)) )
	{
		$i=0;
		while( false !== ($file = readdir($handle)) )
		{
			if( !in_array($file, $files_to_ignore) )
			{
				$files[$i] = $file;
				$i++;
			}
		}
	}
	return $files;
}


?>