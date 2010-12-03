<?php
/**
 * WoWRoster.net WoWRoster
 *
 * AddOn config
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
*/

if( !defined('IN_ROSTER') || !defined('IN_ROSTER_ADMIN') )
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
	// Check if this addon is in the process of an upgrade and deny access if it hasn't yet been upgraded
	$installfile = $addon['inc_dir'] . 'install.def.php';
	$install_class = $addon['basename'] . 'Install';

	if( file_exists($installfile) )
	{
		include_once($installfile);

		if( class_exists($install_class) )
		{
			$addonstuff = new $install_class;

			// -1 = overwrote newer version
			//  0 = same version
			//  1 = upgrade available

			if( version_compare($addonstuff->version,$addon['version']) )
			{
				$body = messagebox(sprintf($roster->locale->act['addon_upgrade_notice'],$addon['basename']) . '<br /><a href="' . makelink('rostercp-install') . '">'
					  . sprintf($roster->locale->act['installer_click_upgrade'],$addon['version'],$addonstuff->version) . '</a>',$roster->locale->act['addon_error'],'sred');
				return;
			}
			unset($addonstuff);
		}
	}

	if( isset($roster->pages[3]) && file_exists($addon['admin_dir'] . $roster->pages[3] . '.php') )
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
		$roster->locale->add_locale_file($addon['locale_dir'] . $langvalue . '.php',$langvalue);
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
		if( file_exists($addon['admin_dir'] . 'config.func.php') )
		{
			include($addon['admin_dir'] . 'config.func.php');
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
		include(ROSTER_LIB . 'config.lib.php');
		$config = new roster_config( $roster->db->table('addon_config'), '`addon_id` = "' . $addon['addon_id'] . '"' );

		// ----[ Get configuration data ]---------------------------
		$config->getConfigData();

		// ----[ Process data if available ]------------------------
		$save_message = $config->processData($addon['config']);

		// ----[ Build the page items using lib functions ]---------
		$menu = $config->buildConfigMenu();

		$config->buildConfigPage();

		$body .= $config->form_start
			   . $save_message
			   . $config->formpages
			   . $config->submit_button
			   . $config->form_end
			   . $config->nonformpages;
		$footer .= $config->jscript;
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
	$roster->output['html_head'] .= '<link rel="stylesheet" type="text/css" href="' . $addon['css_url'] . '" />' . "\n";
}

if( $addon['tpl_css_url'] != '' )
{
	$roster->output['html_head'] .= '<link rel="stylesheet" type="text/css" href="' . $addon['tpl_css_url'] . '" />' . "\n";
}
