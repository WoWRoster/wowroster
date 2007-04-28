<?php
/**
 * WoWRoster.net WoWRoster
 *
 * The only file anyone should directly access in Roster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

require_once( dirname(__FILE__).DIRECTORY_SEPARATOR.'settings.php' );


//---[ Text File Downloader ]-----------------------------
if( isset($_POST['send_file']) && !empty($_POST['send_file']) && !empty($_POST['data']) )
{
	$file = $_POST['data'];

	header('Content-Type: text/x-delimtext; name="' . $_POST['send_file'] . '.txt"');
	header('Content-disposition: attachment; filename="' . $_POST['send_file'] . '.txt"');

	// We need to stripslashes no matter what the setting of magic_quotes_gpc is
	echo stripslashes($file);

	exit;
}


//---[ Determine the module request ]---------------------
if( isset($_GET[ROSTER_PAGE]) && !empty($_GET[ROSTER_PAGE]) )
{
	$page = $_GET[ROSTER_PAGE];
}
elseif( !strpos($roster_conf['default_page'], '&amp;') )
{
	$page = $roster_conf['default_page'];
}
else
{
	//---[ Insert directly into GET request ]-----------------
	list($page, $gets) = explode('&amp;',$roster_conf['default_page'],2);
	foreach( explode('&amp;',$gets) as $get )
	{
		list($key, $value) = explode('=',$get,2);
		$_GET[$key] = $value;
	}
}

define('ROSTER_PAGE_NAME', $page);

$roster_pages = explode('-', $page);
unset($page);

//---[ We only accept certain characters in our page ]----
if( preg_match('/[^a-zA-Z0-9_-]/', ROSTER_PAGE_NAME) )
{
	roster_die($act_words['invalid_char_module'],$act_words['roster_error']);
}

//---[ Special handling for certain pages ]---------------
if( in_array($roster_pages[0],array('credits','license','rostercp','rosterdiag','update')) )
{
	require(ROSTER_PAGES . $roster_pages[0] . '.php');
	exit();
}

//---[ Check for empty Guild info ]-----------------------
if( empty($guild_info) )
{
	roster_die( sprintf($act_words['nodata'], $roster_conf['guild_name'], $roster_conf['server_name'], makelink('update'), makelink('rostercp') ), $act_words['nodata_title'] );
}

$addon = getaddon($roster_pages[0]);
$file = ( isset($roster_pages[1]) ? $roster_pages[1] : 'index' );
$path = ROSTER_ADDONS . $roster_pages[0] . DIR_SEP . $file . '.php';

//---[ Make the header/menu/footer show by default ]------
$roster_show_header = true;
$roster_show_menu = 'main';
$roster_show_footer = true;

//---[ Check if the module exists ]-----------------------
if( !file_exists($path) )
{
	roster_die(sprintf($act_words['module_not_exist'],$roster_pages[0]),$act_words['roster_error']);
}

if( $addon['active'] == '1' )
{
	// Include addon's locale files if they exist
	foreach( $roster_conf['multilanguages'] as $lang )
	{
		if( file_exists($addon['locale_dir'] . $lang . '.php') )
		{
			add_locale_file($addon['locale_dir'] . $lang . '.php',$lang,$wordings);
		}
	}

	// Include addon's conf.php file
	if( file_exists($addon['conf_file']) )
	{
		include_once($addon['conf_file']);
	}

	// The addon will now assign its output to $content
	ob_start();
		require($path);
	$content = ob_get_clean();


	// Pass all the css to $more_css which is a placeholder in roster_header for more css style defines
	if( $addon['css_url'] != '' )
	{
		$more_css = '	<link rel="stylesheet" type="text/css" href="' . ROSTER_PATH . $addon['css_url'] . '" />' . "\n";
	}

	if( $roster_show_header )
	{
		include_once(ROSTER_BASE . 'roster_header.tpl');
	}

	if( $roster_show_menu )
	{
		$roster_menu = new RosterMenu;
		print $roster_menu->makeMenu($roster_show_menu);
	}

	echo $content;

	if( $roster_show_footer )
	{
		include_once(ROSTER_BASE . 'roster_footer.tpl');
	}
}
else
{
	roster_die(sprintf($act_words['addon_disabled'],$addon['basename']),$act_words['addon_error']);
}


$wowdb->closeDb();
