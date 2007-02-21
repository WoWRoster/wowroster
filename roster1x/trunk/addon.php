<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
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
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}

// NEW URI, made backward compatible with Roster < 1.7.4
if( isset($_GET['addon']) )
{
	$_REQUEST['roster_addon_name'] = $_GET['addon'];
	$_GET['roster_addon_name'] = $_GET['addon'];
}
elseif( isset($_GET['roster_addon_name']) )
{
	$_REQUEST['addon'] = $_GET['roster_addon_name'];
	$_GET['addon'] = $_GET['roster_addon_name'];
}

// Get the addon's location
$addonDir = ROSTER_ADDONS.$_GET['addon'].DIR_SEP;

// Get the addon's index file
$addonFile = $addonDir.'index.php';

// Get the addon's css style
$cssFile = $addonDir.'default.css';

// Get the addon's locale file
$localizationFile = $addonDir.'localization.php';

// Get the addon's config file
$configFile = $addonDir.'conf.php';

// Initialize css holder
$css = '';

// Make the header/menu/footer show by default
$roster_show_header = true;
$roster_show_menu = true;
$roster_show_footer = true;


// Check to see if the index file exists
if( file_exists($addonFile) )
{
	$script_filename = 'addon.php?addon='.$_GET['addon'];

	// Set the css for the template set in conf.php
	if( file_exists($cssFile) )
	{
		$css = '/addons/'.$_GET['addon'].'/default.css';
	}

	// Include localization variables
	if( file_exists($localizationFile) )
	{
		include_once( $localizationFile );
	}

	// Include addon's conf.php settings
	if( file_exists($configFile) )
	{
		include_once( $configFile );
	}

	// The addon will now assign its output to $content
	ob_start();
		include_once( $addonFile );
		$content = ob_get_contents();
	ob_end_clean();
}
else
{
	$content = '<b>The addon "'.$_GET['addon'].'" does not exist!</b>';
}

// Everything after this line will have to be changed to integrate into smarty! ;)

// Pass all the css to $more_css which is a placeholder in roster_header for more css style defines
if( $css != '' )
	$more_css = '  <link rel="stylesheet" type="text/css" href="'.$roster_conf['roster_dir'].$css.'">'."\n";

if ($roster_show_header)
	include_once (ROSTER_BASE.'roster_header.tpl');
if ($roster_show_menu)
	include_once (ROSTER_LIB.'menu.php');

echo $content;

if ($roster_show_footer)
	include_once (ROSTER_BASE.'roster_footer.tpl');

?>