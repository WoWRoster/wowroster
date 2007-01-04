<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: /index.php
 *
 * This file is available publicly, it runs and controls all
 * methods.
 *
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Legal Information:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 *
 * Full License:
 *  license.txt (Included within this library)
 *
 * You should have recieved a FULL copy of this license in license.txt
 * along with this library, if you did not and you are unable to find
 * and agree to the license you may not use this library.
 *
 * For questions, comments, information and documentation please visit
 * the official website at cpframework.org
 *
 * @link http://cpframework.org
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author Chris Stockton
 * @version 1.5.0
 * @copyright 2000-2006 Chris Stockton
 * @package cpFramework
 * @filesource
 *
 * Roster versioning tag
 * $Id$
 */

/**
 * DIR_SEP, since I'm lazy
 */
define('DIR_SEP', DIRECTORY_SEPARATOR);

/**
 * Security define. Used elsewhere to check if we're in the framework
 */
define('SECURITY', true);

/**
 * Site pathing and settings with trailing slash
 */
define('PATH_LOCAL', dirname(__FILE__).DIR_SEP );

if (!empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']))
{
	define('PATH_REMOTE', 'http://'.((!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST']).'/' );
	define('PATH_REMOTE_S', 'https://'.((!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST']).'/' );
}

/**
 * Create some constants
 */
define('START_TIME',    microtime(true));
define('R2_VER',        '1.9.0.0');
define('ZLIBSUPPORT',   extension_loaded('zlib'));
define('WINDOWS',       (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN'));
// Are we allowed to modify php.ini on the fly ?
define('CAN_INI_SET',   !ereg('ini_set', ini_get('disable_functions')));
define('MAGICQUOTES',   get_magic_quotes_gpc() || ini_get('magic_quotes_sybase'));
define('R2_LIB_PATH',   PATH_LOCAL . 'library'.DIR_SEP);
define('R2_CLASS_PATH', R2_LIB_PATH . 'class'.DIR_SEP);
define('SMARTY_DIR',    R2_CLASS_PATH . 'smarty'.DIR_SEP);

$phpver = explode('.', phpversion());
$phpver = "$phpver[0]$phpver[1]";
define('PHPVERS', $phpver);

unset($phpver);

# http://bugs.php.net/bug.php?id=15693
if ($_SERVER['REQUEST_METHOD'] == 'HEAD') { exit; }

// Disable magic_quotes_runtime
set_magic_quotes_runtime(0);
umask(0);

if( CAN_INI_SET )
{
	ini_set('magic_quotes_sybase', 0);
}

/**
 * Prepare input variables
 *
 * Destroy GET/POST/Cookie variables from the global scope since IIS can't
 * turn off register_globals as Apache can thru .ht
 */
if( intval(ini_get('register_globals')) != 0 )
{
	foreach ($_REQUEST AS $key => $val)
	{
		if( isset($$key) ) unset($$key);
	}
}
if( is_array($_POST) )
{
	array_walk($_POST, 'prepareInput');
}
if( is_array($_GET) )
{
	array_walk($_GET, 'prepareInput');
}


/**
 * Turn on ALL errors during development, we keep our code crisp.. and clean
 * however turn them off after development for security reasons. Make sure to
 * actively controll this configuration setting.
 */
error_reporting(E_ALL);

/**
 * Our exception class
 */
require(R2_CLASS_PATH . 'cpmain'.DIR_SEP.'cpexception.php');

/**
 * Our main class, cpEngine, our instance handler
 */
require(R2_CLASS_PATH . 'cpmain.php');

/**
 * The config class
 */
cpMain::loadClass('cpconfig','cpconfig');

/**
 * Load default config
 */
cpMain::$instance['cpconfig']->loadConfig('cpconf');

/**
 * If the config file hasn't been created yet we'll only run the config module
 */
if( !file_exists(PATH_LOCAL . 'data'.DIR_SEP.'config'.DIR_SEP.'cpconf.php') )
{
	if( !defined('INSTALL') )
	{
		cpMain::cpErrorFatal("You must install R2CMS first before you can use it<br />Go to ?modules=config to set up config",'','',true);
	}
}

/**
 * We set our publicly available variables before doing anything these are
 * contained within our main class in a static variable.
 */
cpMain::$system['method_name'] = NULL;
cpMain::$system['method_mode'] = NULL;
cpMain::$system['method_path'] = NULL;
cpMain::$system['template_path'] = NULL;

/**
 * Redirect handling based off the SYSTEM_REDIRECT_REQUEST.
 */
if(cpMain::$instance['cpconfig']->cpconf['redirect_www'] !== 'off')
{
	if(preg_match('/(^www\.+)/', $_SERVER['HTTP_HOST']) && cpMain::$instance['cpconfig']->cpconf['redirect_www'] === 'http')
	{
		header('Location: ' . PATH_REMOTE);
		exit;
	}
	elseif(!preg_match('/(www\.+)/', $_SERVER['HTTP_HOST']) && cpMain::$instance['cpconfig']->cpconf['redirect_www'] === 'www')
	{
		header('Location: ' . preg_replace('/(http:\/\/|www\.)+/', 'http://www.', PATH_REMOTE));
		exit;
	}
}

header('Content-Type: text/html; charset=iso-8859-1');
header('Date: '.date('D, d M Y H:i:s', gmtime()).' GMT');
header('Last-Modified: '.date('D, d M Y H:i:s', gmtime()).' GMT');
header('Expires: 0');

/**
 * Shall we use a search friendly urls?
 */
if( cpMain::$instance['cpconfig']->cpconf['hide_param'] )
{
	/**
	 * Get our get vars from the seo friendly URL, simple regex is very powerfull. Assuming
	 * you are using htaccess and have mod_rewrite running on your server. This feature can
	 * be disabled all together.
	 *
	 * Matches:
	 * foo1-bar1/2foo-bar2/something-else.html
	 * foo1-bar1/2foo-bar2/something-else/a.html
	 * foo1-bar1/2foo-bar2/something-else/
	 *  -- And more, you get the picture... --
	 *
	 * All result in:
	 *   ($_GET = Array ( [foo1] => bar1 [2foo] => bar2 [something] => else))
	 *
	 *
	 */
	preg_match_all("/([^\/\-\.?]+)\-([^\/\-\.?]+)/i", $_SERVER['REQUEST_URI'], $matches);

	/**
	 * Inject our variables directly into the _GET super global, we do this to prevent bad practice
	 * as placing them into the global scope with variable variables, or utilizing our system
	 * array. The _GET scope it is. Please don't argue me this practice as no logic will defeat
	 * me in my own mind : )
	 */
	foreach($matches[1] as $key => $value)
	{
		$_GET[$value] = $matches[2][$key];
	}
}

/**
 * Determine the users module request within switch function.
 */
if( isset($_GET['module']) )
{
	/**
	 * The users request is for module usage, we must set variables defining
	 * the library path and the mode in which the module shall be ran. We
	 * then define the method type - being module.
	 */
	cpMain::$system['method_name'] = (isset($_GET['module'])) ? $_GET['module'] : NULL;
	cpMain::$system['method_mode'] = (isset($_GET['mode'])) ? $_GET['mode'] : 'index';
	cpMain::$system['method_path'] = cpMain::$system['method_name'] . DIR_SEP . cpMain::$system['method_mode'];
	cpMain::$system['method_dir']  = 'modules'.DIR_SEP.cpMain::$system['method_name'];
}
else
{
	/**
	 * The users request is invalid or perhaps simply undefined. Therefore we
	 * direct them to the default method. Setting variables accordingly.
	 */
	cpMain::$system['method_name'] = cpMain::$instance['cpconfig']->cpconf['def_module'];
	cpMain::$system['method_mode'] = 'index';
	cpMain::$system['method_path'] = cpMain::$system['method_name'] . DIR_SEP . cpMain::$system['method_mode'];
	cpMain::$system['method_dir']  = 'modules'.DIR_SEP.cpMain::$system['method_name'];
}

/**
 * Include the module core based on the method type and set path.
 */
if( is_file( $var = PATH_LOCAL . 'modules' . DIR_SEP . cpMain::$system['method_path'] . '.php' ) )
{
	require($var);
}
else
{
	cpMain::cpErrorFatal("Error Loading Requested Method, the path the system was looking for (or at least 1 of the paths we checked) is: " . $var, __LINE__, __FILE__);
}

/**
 * We only initialize our template system only if the method chosen requires its
 * usage as I want the capability of non-template driven implimentations
 * of this system to remain possible. As its general purpose is to be a
 * invaluable tool across ALL development enviroments.
 */
if(cpMain::isClass('smarty'))
{
	/**
	 * Make sure the specified module has a available theme (template file)
	 */
	if( cpMain::isClass('cpusers') && (is_file(PATH_LOCAL . 'themes'.DIR_SEP . cpMain::$instance['cpusers']->data['user_theme']. DIR_SEP . 'theme.php')) )
	{
		cpMain::$system['current_theme'] = PATH_LOCAL . 'themes'.DIR_SEP . cpMain::$instance['cpusers']->data['user_theme'].DIR_SEP;
	}
	elseif( is_file(PATH_LOCAL . 'themes'.DIR_SEP . cpMain::$instance['cpconfig']->cpconf['def_theme']. DIR_SEP . 'theme.php') )
	{
		cpMain::$system['current_theme'] = PATH_LOCAL . 'themes'.DIR_SEP . cpMain::$instance['cpconfig']->cpconf['def_theme'].DIR_SEP;
	}
	else
	{
		cpMain::cpErrorFatal("Error Loading Requested Template, theme.php does not exist", __LINE__, __FILE__);
	}

	/**
	 * Include the theme's php file
	 */
	require(cpMain::$system['current_theme'] . 'theme.php');

	/**
	 * Configure smarty
	 */
	cpMain::$instance['smarty']->template_dir = cpMain::$system['current_theme'];
	cpMain::$instance['smarty']->compile_dir = PATH_LOCAL . 'cache'.DIR_SEP;


	/**
	 * Debug console for smarty templates, also forces recompile for templates
	 */
	if( cpMain::$instance['cpconfig']->cpconf['smary_debug'] == 1 && !defined('INSTALL') )
	{
		cpMain::$instance['smarty']->debugging = true;
		cpMain::$instance['smarty']->force_compile = true;
		cpMain::$instance['smarty']->debug_tpl = 'smarty_debug.tpl';
	}


	/**
	* Smarty security
	* Turning on security enforces the following rules to the template language
	*
	*  If $php_handling is set to SMARTY_PHP_ALLOW, this is implicitly changed to SMARTY_PHP_PASSTHRU
	*  PHP functions are not allowed in {if} statements, except those specified in the $security_settings
	*  Templates can only be included from directories listed in the $secure_dir array
	*  Local files can only be fetched from directories listed in the $secure_dir array using {fetch}
	*  {php}{/php} tags are not allowed
	*  PHP functions are not allowed as modifiers, except those specified in the $security_settings
	*/
	cpMain::$instance['smarty']->security = true;
	cpMain::$instance['smarty']->secure_dir = array(PATH_LOCAL . 'themes' . DIR_SEP);


	// Use GZIP output on smarty templates?
	if( cpMain::$instance['cpconfig']->cpconf['output_gzip'] == 1 && !defined('INSTALL') && !ZLIBSUPPORT )
	{
		cpMain::$instance['smarty']->load_filter('output','gzip');
	}

	/**
	 * Set our CONSTANTS provided by our system
	 */
	cpMain::$instance['smarty']->assign('THEME_PATH', PATH_REMOTE);

	/**
	 * We only inject our language into the template if the users specifies.
	 * Notice the location of this as it will only work if the users requires
	 * the template class to be called upon, module authors make sure you realize
	 * that this option only injects the language into the template, it's not
	 * required for multi lingual functionality, as the language is injected
	 * automaticaly if the lang_(method).php file exists for the users. So, one
	 * important practice is to include the default language (english) with every
	 * module in case your module relies on the multi lingual template variables
	 * to be present.
	 */
	if(cpMain::isClass('cplang'))
	{
		/**
		 * Load the language to our template class
		 */
		foreach(cpMain::$instance['cplang']->lang as $key => $value)
		{
			cpMain::$instance['smarty']->assign($key, $value);
		}
	}

	/**
	 * Build the template for the specified block
	 */
	if( cpMain::$system['template_path'] != '' )
	{
		$var = cpMain::$system['template_path'];
	}
	else
	{
		$var = cpMain::$system['current_theme'] . 'modules' . DIR_SEP . cpMain::$system['method_path'] . '.tpl';
	}

	if( is_file($var) )
	{
		cpMain::$instance['smarty']->compile_id = cpMain::$system['method_path'];
		cpMain::$instance['smarty']->display($var);
	}
	else
	{
		cpMain::cpErrorFatal("Error Loading Requested Template, the path the system was looking for (or at least 1 of the paths we checked) is: " . $var, __LINE__, __FILE__);
	}
}



/**
 * Strip slashes from GET/POST/Cookie variables because we add them back later
 */
function prepareInput(&$value, $key, $set=true)
{
	if (is_array($value))
	{
		array_walk($value, 'prepareInput', false);
	}
	else
	{
		if (MAGICQUOTES)
		{
			$value = stripslashes($value);
		}
		# replace NO-BREAK and other types of utf8 spacings
		$value = preg_replace(array(
			// UTF8                         UNICODE
			'#[\xC2][\xA0]#',            // 00A0
			'#[\xE2][\x80][\x80-\x8D]#', // 2000-200D
			'#[\xE2][\x80][\xAF]#',      // 202F
			'#[\xE2][\x81][\x9F]#',      // 205F
			'#[\xE3][\x80][\x80]#'       // 3000
		), ' ', $value);
	}
	if ($set) $_REQUEST[$key] =& $value;
}

/**
 * Time Formatting
 */
function gmtime()
{
	static $time;
	if( !$time )
	{
		$time = (time() - date('Z'));
	}
	return $time;
}


/**
 * Converts any link to a frienly link if the config uses it
 * Ninja looted form DragonFly and converted for our use =P
 *
 * @param string  $url      What to link to
 * @param bool    $use_seo  Convert link to friendly URL
 * @param bool    $full     Append the full URL to the link
 * @return string
 */
function getlink( $url='', $use_seo=true, $full=false )
{
	if( empty($url) || $url[0] == '&' )
	{
		$url = 'module='.cpMain::$system['method_name'].$url;
	}
	else
	{
		$url = 'module='.$url;
	}

	if( cpMain::$instance['cpconfig']->cpconf['hide_param'] && $use_seo )
	{
		$url = ereg_replace('&amp;', '/', $url);
		$url = ereg_replace('&', '/', $url);
		$url = str_replace('?', '/', $url);
		$url = str_replace('=', '-', $url);

		if (ereg('#', $url))
		{
			$url = ereg_replace('#', '.html#', $url);
		}
		else
		{
			$url .= '.html';
		}
	}
	else
	{
		$url = "index.php?module=".$url;
	}

	if( $full )
	{
		$url = PATH_REMOTE.$url;
	}
	return $url;
}