<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Main Roster configuration
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

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// ----[ Set the tablename and create the config class ]----
$tablename = $wowdb->table('config');
include(ROSTER_LIB.'config.lib.php');

// ----[ Include special functions file ]-------------------
include(ROSTER_ADMIN.'roster_config_functions.php');

// ----[ Process data if available ]------------------------
$save_message = $config->processData();

// ----[ Get configuration data ]---------------------------
$config->getConfigData();

// ----[ Build the page items using lib functions ]---------
$menu = $config->buildConfigMenu();

$config->buildConfigPage();

$body = $config->form_start.
	$save_message.
	$config->submit_button.
	$config->formpages.
	$config->form_end.
	$config->nonformpages.
	$config->jscript;
