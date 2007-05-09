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
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.0
*/

if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}

/**
 * Set PHP error reporting
 */
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL);



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
unset($HTTP_GET_VARS,$HTTP_POST_VARS,$HTTP_COOKIE_VARS);


/**
 * Begin Roster Timing
 */
$starttime = explode(' ', microtime() );
define('ROSTER_STARTTIME',$starttime[1] + $starttime[0]);


/**
 * Can we use ini_set
 */
define('CAN_INI_SET', !ereg('ini_set', ini_get('disable_functions')));


/**
 * OS specific Directory Seperator
 */
define('DIR_SEP',DIRECTORY_SEPARATOR);


/**
 * Base, absolute roster directory
 */
define('ROSTER_BASE',dirname(__FILE__) . DIR_SEP);


/**
 * Base, absolute roster library directory
 */
define('ROSTER_LIB',ROSTER_BASE . 'lib' . DIR_SEP);

include( ROSTER_LIB.'roster.php' );
$roster = new roster;

/**
 * Roster Error Handler
 */
include( ROSTER_LIB.'roster_error.php' );
$roster->error =& new roster_error(E_ALL);


/**
 * Load the dbal
 */
$roster->load_dbal();

/**
 * Include constants file
 */
require_once (ROSTER_LIB . 'constants.php');

/**
 * Load the config
 */
$roster->load_config();

/**
 * Cache addon data
 */
$roster->get_addon_data();

/**
 * Include common functions
 **/
require_once (ROSTER_LIB . 'functions.lib.php');

/**
 * Slash global data if magic_quotes_gpc is off.
 */
set_magic_quotes_runtime(0);
if( !get_magic_quotes_gpc() )
{
	$_GET = escape_array($_GET);
	$_POST = escape_array($_POST);
	$_COOKIE = escape_array($_COOKIE);
	$_REQUEST = escape_array($_REQUEST);
}

/**
 * Include linking file
 */
require_once (ROSTER_LIB . 'cmslink.lib.php');

/**
 * Load the locale class
 */
include(ROSTER_LIB.'locale.php');
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
 * Inject some different settings if the debug url switch is set
 */
if( isset($_GET['roster_debug']) && $_GET['roster_debug'] == 'roster_debug')
{
	$roster->config['sqldebug'] = 1;
	$roster->config['debug_mode'] = 1;
	$roster->config['sql_window'] = 1;
}

/**
 * If the version doesnt match the one in constants, redirect to upgrader
 */
if( empty($roster->config['version']) || version_compare($roster->config['version'],ROSTER_VERSION,'<') )
{
	roster_die('Looks like you\'ve loaded a new version of Roster<br />
<br />
Your Version: <span class="red">' . $roster->config['version'] . '</span><br />
New Version: <span class="green">' . ROSTER_VERSION . '</span><br />
<br />
<a href="upgrade.php" style="border:1px outset white;padding:2px 6px 2px 6px;">UPGRADE</a>','Upgrade Roster','sred');
}


/**
 * If the install directory or files exist, die()
 */
if( file_exists(ROSTER_BASE . 'install.php') || file_exists(ROSTER_BASE . 'upgrade.php')  || file_exists(ROSTER_BASE . 'install') )
{
	if( !file_exists(ROSTER_BASE . 'version_match.php') )
	{
		roster_die('Please remove the <span class="green">install</span> folder and the files <span class="green">install.php</span> and <span class="green">upgrade.php</span> in this directory','Remove Install Files','sred');
	}
}


/**
 * Include roster Login class
 */
require_once(ROSTER_LIB . 'login.php');
