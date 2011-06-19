<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Ajax interface file
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage Ajax
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

$method = (isset($roster->pages[1]) ? $roster->pages[1] : '');

$cont = (isset($_GET['cont']) ? $_GET['cont'] : '');

$errmsg = $result = '';


if( $roster->pages[1] == 'addon' )
{
	$addon = getaddon($roster->pages[2]);
	$method = $roster->pages[3];
	// Check if addon is active
	if( $addon['active'] == '1' )
	{
		// Include addon's locale files if they exist
		foreach( $roster->multilanguages as $lang )
		{
			$roster->locale->add_locale_file($addon['locale_dir'] . $lang . '.php',$lang);
		}

		// Include addon's conf.php file
		if( file_exists($addon['conf_file']) )
		{
			include_once( $addon['conf_file'] );
		}

		include_once( $addon['ajax_file'] );
	}
	else
	{
		$status = 1;
	}
}
else
{
	include(ROSTER_AJAX . 'functions.php');
}

// Check if the function is valid, if so run it, else error
if( isset($ajaxfuncs[$method]) )
{
	include($ajaxfuncs[$method]['file']);
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

$roster->output['show_header'] = false;
$roster->output['show_menu'] = false;
$roster->output['show_footer'] = false;

header('Content-Type: text/xml');

// Output XML
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
	. "<response>\n"
	. '  <method>' . $method . "</method>\n"
	. '  <cont>' . $cont . "</cont>\n"
	. '  <result>' . $result . "</result>\n"
	. '  <status>' . (int)$status . "</status>\n"
	. '  <errmsg>' . $errmsg . "</errmsg>\n"
	. "</response>\n";
