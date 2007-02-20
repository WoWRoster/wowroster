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

/*
	Start the following scripts when "update.php" is called

	Available variables
		- $wowdb       = roster's db layer
		- $member_id   = character id from the database ( ex. 24 )
		- $member_name = character's name ( ex. 'Jonny Grey' )
		- $roster_conf = The entire roster config array
		- $mode        = when you want to run the trigger
			= 'char'  - during a character update
			= 'guild' - during a guild update

	You may need to do some fancy coding if you need more variables

	Just print any needed output

*/

include_once($addonDir.'update.php');

$AltMonitorUpdate = $GLOBALS['AltMonitorUpdate'];
$AltMonitorUpdate->messages = '';

// We're only updating on guild updates.
if( $mode == 'guild_pre' )
{
	if( $addon_conf['AltMonitor']['update_type'] & 1 == 0 ) { return; }
	$retval = $AltMonitorUpdate->guild_pre($data);

	if (!empty($retval)) $AltMonitorUpdate->messages .= " - <span style='color:red;'>".$retval."</span><br/>\n";

	// echo messages for roster's update.php
	if (!empty($AltMonitorUpdate->messages)) echo 'AltMonitor'.$AltMonitorUpdate->messages."<br/>\n";
}
if( $mode == 'guild' )
{
	if( $addon_conf['AltMonitor']['update_type'] & 1 == 0 ) { return; }
	$retval = $AltMonitorUpdate->guild($member_id, $member_name, $data);

	if (!empty($retval)) $AltMonitorUpdate->messages .= " - <span style='color:red;'>".$retval."</span><br/>\n";

	// echo messages for roster's update.php
	if (!empty($AltMonitorUpdate->messages)) echo 'AltMonitor'.$AltMonitorUpdate->messages;
}
elseif( $mode == 'guild_post' )
{
	if( $addon_conf['AltMonitor']['update_type'] & 1 == 0 ) { return; }
	$retval = $AltMonitorUpdate->guild_post($data);

	if (!empty($retval)) $AltMonitorUpdate->messages .= " - <span style='color:red;'>".$retval."</span><br/>\n";

	// echo messages for roster's update.php
	if (!empty($AltMonitorUpdate->messages)) echo 'AltMonitor'.$AltMonitorUpdate->messages."<br/>\n";
}
elseif( $mode == 'char_pre' )
{
	if( $addon_conf['AltMonitor']['update_type'] & 2 == 0 ) { return; }
	$retval = $AltMonitorUpdate->char_pre($data);

	if (!empty($retval)) $AltMonitorUpdate->messages .= " - <span style='color:red;'>".$retval."</span><br/>\n";

	// echo messages for roster's update.php
	if (!empty($AltMonitorUpdate->messages)) echo 'AltMonitor'.$AltMonitorUpdate->messages."<br/>\n";
}
elseif( $mode == 'char' )
{
	if( $addon_conf['AltMonitor']['update_type'] & 2 == 0 ) { return; }
	$retval = $AltMonitorUpdate->char($member_id, $member_name, $data);

	if (!empty($retval)) $AltMonitorUpdate->messages .= " - <span style='color:red;'>".$retval."</span><br/>\n";

	// echo messages for roster's update.php
	if (!empty($AltMonitorUpdate->messages)) echo 'AltMonitor'.$AltMonitorUpdate->messages;
}
elseif( $mode == 'char_post' )
{
	if( $addon_conf['AltMonitor']['update_type'] & 2 == 0 ) { return; }
	$retval = $AltMonitorUpdate->char_post($data);
	
	if (!empty($retval)) $AltMonitorUpdate->messages .= " - <span style='color:red;'>".$retval."</span><br/>\n";

	// echo messages for roster's update.php
	if (!empty($AltMonitorUpdate->messages)) echo 'AltMonitor'.$AltMonitorUpdate->messages."<br/>\n";
}

?>
