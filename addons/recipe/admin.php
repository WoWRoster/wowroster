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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// ----[ Set the tablename and create the config class ]----
$tablename = $wowdb->table('addon_config');
include(ROSTER_LIB.'config.lib.php');

// ----[ Process data if available ]------------------------
$save_message = $config->processData();

// ----[ Get configuration data ]---------------------------
$config->getConfigData($addon['addon_id']);

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
