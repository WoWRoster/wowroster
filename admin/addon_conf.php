<?php
/**
 * WoWRoster.net WoWRoster
 *
 * AddOn config
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if( !isset($roster->pages[2]) )
{
	$body .= messagebox($roster->locale->act['specify_addon'],$roster->locale->act['addon_error'],'sred');
	return;
}

// Initialize addon framework for this addon and return the addon's db profile.
$addon = getaddon($roster->pages[2]);

// Check if addon is active
if( $addon['active'] = '1' )
{
	if( isset($roster->pages[3]) && file_exists($addon['admin_dir'].$roster->pages[3] . '.php') )
	{
		$addon['active_file'] = $addon['admin_dir'] . $roster->pages[3] . '.php';
	}
	else
	{
		$addon['active_file'] = $addon['admin_dir'] . 'index.php';
	}

	// Include addon's locale files if they exist
	foreach( $roster->multilanguages as $langvalue )
	{
		$roster->locale->add_locale_file($addon['locale_dir'].$langvalue.'.php',$langvalue);
	}

	// Include addon's conf.php file
	if( file_exists($addon['conf_file']) )
	{
		include_once( $addon['conf_file'] );
	}

	// Check to see if the index file exists
	if( file_exists($addon['active_file']) )
	{
		// The addon will now assign its output to $body
		ob_start();
			include_once( $addon['active_file'] );
		$body .= ob_get_clean();
	}
	elseif( $addon['config'] != '' )
	{
		if( file_exists($addon['dir'].'admin/config.func.php') )
		{
			include($addon['dir'].'admin/config.func.php');
			if( function_exists('topBox') )
			{
				$body .= topBox();
			}
			else
			{
				$body .= '';
			}
		}

		// ----[ Set the tablename and create the config class ]----
		$tablename = $roster->db->table('addon_config');
		include(ROSTER_LIB.'config.lib.php');

		// ----[ Get configuration data ]---------------------------
		$config->getConfigData('`addon_id` = "' . $addon['addon_id'] . '"');

		// ----[ Process data if available ]------------------------
		$save_message = $config->processData($addon['config'], '`addon_id` = "' . $addon['addon_id'] . '"');

		// ----[ Build the page items using lib functions ]---------
		$menu = $config->buildConfigMenu();

		$config->buildConfigPage();

		$body .= $config->form_start.
			$save_message.
			$config->formpages.
			$config->submit_button.
			$config->form_end.
			$config->nonformpages.
			$config->jscript;
	}
	else
	{
		$body .=  messagebox(sprintf($roster->locale->act['addon_no_config'],$addon['basename']),$roster->locale->act['addon_error'],'sred');
	}
}
else
{
	$body .=  messagebox(sprintf($roster->locale->act['addon_disabled'],$addon['basename']),$roster->locale->act['addon_error'],'sred');
}

// Pass all the css to $roster->output['html_head'] which is a placeholder in roster_header for more css style defines
if( $addon['css_url'] != '' )
{
	$roster->output['html_head'] .= '  <link rel="stylesheet" type="text/css" href="'.$addon['css_url'].'" />'."\n";
}
