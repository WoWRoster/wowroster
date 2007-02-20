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

$fileversion='2.0.1';

/*
DATABASE: the altmonitor table should have an entry for every member.

roster_addon_altmonitor {
	member_id,
	main_id,
	alt_type,
}

member_id is the ID of the member this record is about. main_id is the ID of the corresponding
main. alt_type one of the following
*/

define('ROSTER_ALT_TABLE',$GLOBALS['db_prefix'].'addon_altmonitor');
define('ROSTER_ALT_CONFIG_TABLE',$GLOBALS['db_prefix'].'addon_altmonitor_config');

define('ALTMONITOR_MAIN_ALTS',0);
define('ALTMONITOR_MAIN_NO_ALTS',1);
define('ALTMONITOR_ALT_WITH_MAIN',2);
define('ALTMONITOR_ALT_NO_MAIN',3);
define('ALTMONITOR_MAIN_MANUAL_WITH_ALTS',4);
define('ALTMONITOR_MAIN_MANUAL_NO_ALTS',5);
define('ALTMONITOR_ALT_MANUAL_WITH_MAIN',6);
define('ALTMONITOR_ALT_MANUAL_NO_MAIN',7);

/*
$manual = $alt_type & 0x4
$alt    = $alt_type & 0x2
$single = $alt_type & 0x1
*/

// -[ Test if our config table exists ]-
$query = "SHOW TABLES LIKE '".ROSTER_ALT_CONFIG_TABLE."'";

$result = $wowdb->query( $query ) or die_quietly($wowdb->error(),'AltMonitor',__FILE__,__LINE__, $query );

if ( $row = $wowdb->fetch_assoc($result) )
{
	$wowdb->free_result($result);

	// -[ Get config values and insert them into the array ]-
	$query = "SELECT `config_name`, `config_value` FROM `".ROSTER_ALT_CONFIG_TABLE."` ORDER BY `id` ASC;";

	$result = $wowdb->query( $query ) or die_quietly($wowdb->error(),'AltMonitor',__FILE__,__LINE__, $query );

	while( $row = $wowdb->fetch_assoc($result) )
	{
		$addon_conf['AltMonitor'][$row['config_name']] = stripslashes($row['config_value']);
	}

	$wowdb->free_result($result);

	$dbversion = $addon_conf['AltMonitor']['version'];
}
else
{
	$dbversion = '0.0.0'; // we need to install
	$addon_conf['AltMonitor']['update_type'] = 0; // for the trigger file
}

?>
