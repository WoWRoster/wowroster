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

// -[ enUS Localization ]-

// Installer
$wordings['enUS']['AltMonitor_install_page']= 'AltMonitor Installer';
$wordings['enUS']['AltMonitor_install']     = 'The AltMonitor tables haven\'t been installed yet. Click Install to start the installation';
$wordings['enUS']['AltMonitor_upgrade']     = 'The AltMonitor tables are not up to date. Click Updade to update the database or click Install to drop and recreate the AltMonitor tables.';
$wordings['enUS']['AltMonitor_no_upgrade']  = 'The AltMonitor tables are already up to date. Click Reinstall below to reinstall the tables.';
$wordings['enUS']['AltMonitor_uninstall']   = 'This will remove the AltMonitor configuration and main/alt relations. Click \'Uninstall\' to proceed';
$wordings['enUS']['AltMonitor_installed']   = 'Congratulations, AltMonitor has been successfully installed. Click the link below to configure it.';
$wordings['enUS']['AltMonitor_uninstalled'] = 'AltMonitor has been uninstalled. You may now delete the addon from your webserver.';

// Main/Alt display
$wordings['enUS']['AltMonitor_Menu']        = 'Mains/Alts';
$wordings['enUS']['AltMonitor_NoAction']    = 'Please check if you mistyped the url, as an invalid action was defined. If you got here by a link from within this addon, report the bug on the WoWroster forums.';
$wordings['enUS']['main_name']              = 'Main\'s  name';
$wordings['enUS']['altlist_menu']           = 'Open/Close all tabs';
$wordings['enUS']['altlist_close']          = 'Close all';
$wordings['enUS']['altlist_open']           = 'Open all';

// Configuration
$wordings['enUS']['AltMonitor_config']      = 'Go to AltMonitor configuration';
$wordings['enUS']['AltMonitor_config_page'] = 'AltMonitor Configuration';
$wordings['enUS']['documentation']          = 'Documentation';
$wordings['enUS']['updMainAlt']             = 'Update Relations';
$wordings['enUS']['uninstall']              = 'Uninstall';

// Page names
$wordings['enUS']['admin']['build']         = 'Main/Alt Relations';
$wordings['enUS']['admin']['display']       = 'Display';

// Settings names on build page
$wordings['enUS']['admin']['getmain_regex'] = 'Regex|The top 3 variables define how the regex is extracted from the member info. <br /> See the wiki link for details. <br /> This field specifies the regex to use.';
$wordings['enUS']['admin']['getmain_field'] = 'Apply on field|The top 3 variables define how the regex is extracted from the member info. <br /> See the wiki link for details. <br /> This field specifies which member field the regex is applied on.';
$wordings['enUS']['admin']['getmain_match'] = 'Use match no|The top 3 variables define how the regex is extracted from the member info. <br /> See the wiki link for details. <br /> This field specifies which return value of the regex is used.';

$wordings['enUS']['admin']['getmain_main']  = 'Main identifier|If the regex resolves to this value the character is assumed to be a main.';
$wordings['enUS']['admin']['defmain']       = 'No result|Set what you want the character to be defined as if the regex doesn\'t return anything.';
$wordings['enUS']['admin']['invmain']       = 'Invalid result|Set what you want the character to be defined as if the regex returns a result that isn\'t a guild member or equal to the main identifier.';
$wordings['enUS']['admin']['altofalt']      = 'Alt of Alt|Specify what to do if the character is a mainless alt.';

$wordings['enUS']['admin']['update_type']   = 'Update type|Specify on which trigger types to update main/alt relations.';

// Settings names on display page
$wordings['enUS']['admin']['showmain']      = 'Show main name|Specify if you want to show or hide the character\'s main\'s name in the alt list.';
$wordings['enUS']['admin']['altopen']       = 'Alt foldouts|Specify if you want the alt foldouts open or closed by default.';
$wordings['enUS']['admin']['mainlessbottom']= 'Show mainless alts|Specify if you want mainless alts at the top or at the bottom.';
