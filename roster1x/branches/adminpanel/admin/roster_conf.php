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

$html = $config->buildConfigPage();

$body = $save_message.$config->form_start.$config->submit_button.$html.$config->form_end.$config->jscript;

?>