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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

if( !isset($pages[2]) )
{
	roster_die('You must specify an addon name!','Addon Error');
}

// Get the addon's location
$addonDir = ROSTER_ADDONS.$pages[2].DIR_SEP;

// Get the addon's index file
$addonFile = $addonDir.'admin.php';

// Get the addon's css style
$cssFile = $addonDir.'default.css';

// Get the addon's locale file
$localizationFile = $addonDir.'localization.php';

// Get the addon's config file
$configFile = $addonDir.'conf.php';

// Initialize css holder
$css = '';


// Check to see if the index file exists
if( file_exists($addonFile) )
{
	$script_filename = 'addon-'.$addon_name;

	// Set the css for the template set in conf.php
	if( file_exists($cssFile) )
	{
		$css = '/addons/'.$pages[2].'/default.css';
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

	include_once( $addonFile );
}
else
{
	$content = '<b>The addon "'.$addon_name.'" does not exist!</b>';
}

// Everything after this line will have to be changed to integrate into smarty! ;)

// Pass all the css to $more_css which is a placeholder in roster_header for more css style defines
if( $css != '' )
	$more_css = '  <link rel="stylesheet" type="text/css" href="'.$roster_conf['roster_dir'].$css.'">'."\n";

echo $content;
