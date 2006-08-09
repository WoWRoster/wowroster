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


require_once('settings.php');

//---[ Check for Guild Info ]------------
$guild_info = $wowdb->get_guild_info($roster_conf['server_name'],$roster_conf['guild_name']);
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}

// Initialize addon framework for this addon and return the addon's db profile.
$addon = getaddon($_GET['dbname']);

// Make the header/menu/footer show by default
$roster_show_header = true;
$roster_show_menu = true;
$roster_show_footer = true;


// Check to see if the index file exists
if( file_exists($addon['index']) )
{
	// The addon will now assign its output to $content
	ob_start();
		include_once( $addon['index'] );
		$content = ob_get_contents();
	ob_end_clean();
}
else
{
	$content = '<b>The addon "'.$addon['basename'].'" installed under dbname "'.$_GET['dbname'].'" does not have an index file!</b>';
}

// Pass all the css to $more_css which is a placeholder in roster_header for more css style defines
if( $addon['cssUrl'] != '' )
	$more_css = '  <link rel="stylesheet" type="text/css" href="'.$roster_conf['roster_dir'].$addon['cssUrl'].'">'."\n";

if ($roster_show_header)
	include_once (ROSTER_BASE.'roster_header.tpl');
if ($roster_show_menu)
	include_once (ROSTER_LIB.'menu.php');

echo $content;

if ($roster_show_footer)
	include_once (ROSTER_BASE.'roster_footer.tpl');

?>