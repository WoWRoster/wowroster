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

if( !isset($roster_pages[1]) )
{
	roster_die($act_words['specify_addon'],$act_words['addon_error']);
}

// Initialize addon framework for this addon and return the addon's db profile.
$addon = getaddon($roster_pages[1]);

// Make the header/menu/footer show by default
$roster_show_header = true;
$roster_show_menu = true;
$roster_show_footer = true;

// Check if addon is active
if( $addon['active'] = '1' )
{
	// Check to see if the index file exists
	if( file_exists($addon['index_file']) )
	{
		$script_filename = 'addon-'.$roster_pages[1];

		// Include addon's locale files if they exist
		foreach( $roster_conf['multilanguages'] as $lang )
		{
			if( file_exists($addon['locale_dir'].$lang.'.php') )
			{
				add_locale_file($addon['locale_dir'].$lang.'.php',$lang,$wordings);
			}
		}

		// Include addon's conf.php file
		if( file_exists($addon['conf_file']) )
		{
			include_once( $addon['conf_file'] );
		}

		// The addon will now assign its output to $content
		ob_start();
			include_once( $addon['index_file'] );
			$content = ob_get_contents();
		ob_end_clean();
	}
	else
	{
		roster_die(sprintf($act_words['addon_not_exist'],$addon['basename']),$act_words['addon_error']);
	}
}
else
{
	roster_die(sprintf($act_words['addon_disabled'],$addon['basename']),$act_words['addon_error']);
}

// Pass all the css to $more_css which is a placeholder in roster_header for more css style defines
if( $addon['css_url'] != '' )
	$more_css = '  <link rel="stylesheet" type="text/css" href="'.$roster_conf['roster_dir'].$addon['css_url'].'">'."\n";

if ($roster_show_header)
	include_once (ROSTER_BASE.'roster_header.tpl');

if ($roster_show_menu)
	include_once (ROSTER_LIB.'menu.php');

echo $content;

if ($roster_show_footer)
	include_once (ROSTER_BASE.'roster_footer.tpl');
