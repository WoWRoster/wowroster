<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Here is where all the magic happens
 * All of the includes, defines, locales, this file loads everything
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: settings.php 1791 2008-06-15 16:59:24Z Zanix $
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.0
 * @package    WoWRoster
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Set PHP error reporting
 */
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL);

/**
 * Attempt to detect a current session and not set it
 * Sometimes it doesn't work...
 */
if( session_id() == '' )
{
	session_start();
}


// Be paranoid with passed vars
// Destroy GET/POST/Cookie variables from the global scope
if( intval(ini_get('register_globals')) != 0 )
{
	foreach( $_REQUEST AS $key => $val )
	{
		if( isset($$key) )
		{
			unset($$key);
		}
	}
}
// Unset old style global vars, we use only php 4.3+ style globals
unset($HTTP_GET_VARS,$HTTP_POST_VARS,$HTTP_COOKIE_VARS);


/**
 * Set magic quotes runtime off
 * Checking for function existance for php6
 */
if( function_exists('set_magic_quotes_runtime') )
{
	set_magic_quotes_runtime(0);
}


/**
 * Begin Roster Timing
 */
$sec = explode(' ', microtime());
define('ROSTER_STARTTIME',$sec[0] + $sec[1]);
unset($sec);


/**
 * Can we use ini_set
 */
define('CAN_INI_SET', !ereg('ini_set', ini_get('disable_functions')));

define('WIN', (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN'));

if( !function_exists('posix_getpwuid') || WIN )
{
	define('ROSTER_PROCESS_UID', '0');
	define('ROSTER_PROCESS_OWNER', 'nobody');
}
else
{
	define('ROSTER_PROCESS_UID', posix_geteuid());
	$processUser = posix_getpwuid(ROSTER_PROCESS_UID);
	define('ROSTER_PROCESS_OWNER', $processUser['name']);
}


/**
 * OS specific Directory Seperator
 */
define('DIR_SEP',DIRECTORY_SEPARATOR);


/**
 * Base, absolute roster directory
 */
define('ROSTER_BASE',dirname(__FILE__) . DIR_SEP);


/**
 * Check PHP version
 */
if( version_compare(phpversion(), '4.3.0','<') )
{
	die('You must have PHP version 4.3 or later to run WoWRoster');
}

/**
 * Base, absolute roster library directory
 */
define('ROSTER_LIB',ROSTER_BASE . 'lib' . DIR_SEP);


/**
 * Include constants file
 */
require_once (ROSTER_LIB . 'constants.php');


/**
 * Include common functions
 **/
require_once (ROSTER_LIB . 'functions.lib.php');


/**
 * Slash global data if magic_quotes_gpc is off.
 * Checking for function existance for php6
 */
if( function_exists('get_magic_quotes_gpc') )
{
	if( !get_magic_quotes_gpc() )
	{
		$_GET = escape_array($_GET);
		$_POST = escape_array($_POST);
		$_COOKIE = escape_array($_COOKIE);
		$_REQUEST = escape_array($_REQUEST);
	}
}
else
{
	$_GET = escape_array($_GET);
	$_POST = escape_array($_POST);
	$_COOKIE = escape_array($_COOKIE);
	$_REQUEST = escape_array($_REQUEST);
}

// --[ Check to see if we need to install ]--
if( !file_exists(ROSTER_CONF_FILE) )
{
	require(ROSTER_BASE . 'install.php');
	die();
}
else
{
	require_once (ROSTER_CONF_FILE);
}

if( !defined('ROSTER_INSTALLED') )
{
	require(ROSTER_BASE . 'install.php');
	die();
}

include( ROSTER_LIB . 'roster.php' );
$roster = new roster;


/**
 * Roster Error Handler
 */
include( ROSTER_LIB . 'roster_error.php' );
$roster->error =& new roster_error();


/**
 * Load the dbal
 */
$roster->load_dbal();

unset($db_config);


/**
 * Include cache class
 */
require_once(ROSTER_LIB . 'cache.php');
$roster->cache = new RosterCache();

/**
 * Load the config
 */
$roster->load_config();


/**
 * Inject some different locale setting if the locale url switch is set
 */
$locale = (isset($_GET['locale']) ? $_GET['locale'] : isset($_POST['locale']) ? $_POST['locale'] : '');
if( $locale != '' )
{
	$_SESSION['locale'] = $locale;
	$roster->config['locale'] = $locale;
}
unset($locale);


/**
 * Include linking file
 */
require_once (ROSTER_LIB . 'cmslink.lib.php');


/**
 * Load the Template Parser
 */
include( ROSTER_LIB . 'template.php' );
$roster->tpl = new RosterTemplate;
if( file_exists($roster->tpl->root . DIR_SEP . 'theme.php') )
{
	include_once($roster->tpl->root . DIR_SEP . 'theme.php');
}


/**
 * Cache addon data
 */
$roster->get_addon_data();


/**
 * Load the locale class
 */
include(ROSTER_LIB . 'locale.php');
$roster->locale = new roster_locale;


/**
 * Include the Roster Menu class
 */
require_once(ROSTER_LIB . 'menu.php');


/**
 * Figure out the page
 */
$roster->get_page_name();


/**
 * Run the scope algorithm to load the data and figure out the file to load
 */
$roster->get_scope_data();


/**
 * Include Login class, external or Roster's
 */
if( file_exists(ROSTER_ADDONS . $roster->config['external_auth'] . DIR_SEP . 'inc' . DIR_SEP . 'login.php') )
{
	require_once(ROSTER_ADDONS . $roster->config['external_auth'] . DIR_SEP . 'inc' . DIR_SEP . 'login.php');
}
else
{
	$roster->config['external_auth'] = 'roster';
	require_once(ROSTER_LIB . 'login.php');
}

$roster->auth = new RosterLogin();


/**
 * Assign initial template vars
 */
$roster->tpl->assign_vars(array(
	'S_SEO_URL'       => $roster->config['seo_url'],
	'S_HEADER_LOGO'   => ( !empty($roster->config['logo']) ? true : false ),

	'U_MAKELINK'      => makelink(),
	'U_LINKFORM'      => linkform(),
	'ROSTER_URL'      => ROSTER_URL,
	'ROSTER_PATH'     => ROSTER_PATH,
	'WEBSITE_ADDRESS' => $roster->config['website_address'],
	'HEADER_LOGO'     => $roster->config['logo'],
	'IMG_URL'         => $roster->config['img_url'],
	'INTERFACE_URL'   => $roster->config['interface_url'],
	'IMG_SUFFIX'      => $roster->config['img_suffix'],
	'ROSTER_VERSION'  => $roster->config['version'],
	'ROSTER_CREDITS'  => sprintf($roster->locale->act['roster_credits'], makelink('credits')),
	'XML_LANG'        => substr($roster->config['locale'],0,2),

	'T_BORDER_WHITE'  => border('swhite','start'),
	'T_BORDER_GRAY'   => border('sgray','start'),
	'T_BORDER_GOLD'   => border('sgold','start'),
	'T_BORDER_RED'    => border('sred','start'),
	'T_BORDER_ORANGE' => border('sorange','start'),
	'T_BORDER_YELLOW' => border('syellow','start'),
	'T_BORDER_GREEN'  => border('sgreen','start'),
	'T_BORDER_PURPLE' => border('spurple','start'),
	'T_BORDER_BLUE'   => border('sblue','start'),
	'T_BORDER_END'    => border('sgray','end'),

	'PAGE_TITLE'      => '',
	'ROSTER_HEAD'     => '',
	'ROSTER_BODY'     => '',
	'ROSTER_ONLOAD'   => '',
	'ROSTER_MENU_BEFORE' => '',
	)
);



/**
 * If the version doesnt match the one in constants, redirect to upgrader
 */
if( empty($roster->config['version']) || version_compare($roster->config['version'],ROSTER_VERSION,'<') )
{
	require(ROSTER_PAGES . 'upgrade.php');
	die();
}


/**
 * If the install directory or files exist, die()
 */
if( file_exists(ROSTER_BASE . 'install.php') )
{
	if( !file_exists(ROSTER_BASE . 'version_match.php') )
	{
		roster_die($roster->locale->act['remove_install_files_text'],$roster->locale->act['remove_install_files'],'sred');
	}
}
