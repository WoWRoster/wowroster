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

if( !isset($roster_pages[2]) )
{
	$body = messagebox($act_words['specify_addon'],$act_words['addon_error'],'sred');
	return;
}

// Initialize addon framework for this addon and return the addon's db profile.
$addon = getaddon($roster_pages[2]);

// Check if addon is active
if( $addon['active'] = '1' )
{
	// Check to see if the index file exists
	if( file_exists($addon['admin_file']) )
	{
		$script_filename = 'rostercp-addon-'.$roster_pages[2];

		// Include addon's locale files if they exist
		foreach( $roster_conf['multilanguages'] as $langvalue )
		{
			if( file_exists($addon['locale_dir'].$langvalue.'.php') )
			{
				add_locale_file($addon['locale_dir'].$langvalue.'.php',$langvalue,$wordings);
			}
		}

		// Include addon's conf.php file
		if( file_exists($addon['conf_file']) )
		{
			include_once( $addon['conf_file'] );
		}

		// The addon will now assign its output to $content
		ob_start();
			include_once( $addon['admin_file'] );
			$content = ob_get_contents();
		ob_end_clean();
	}
	else
	{
		$body =  messagebox(sprintf($act_words['addon_not_exist'],$addon['basename']),$act_words['addon_error'],'sred');
	}
}
else
{
	$body =  messagebox(sprintf($act_words['addon_disabled'],$addon['basename']),$act_words['addon_error'],'sred');
}

// Pass all the css to $more_css which is a placeholder in roster_header for more css style defines
if( $addon['css_url'] != '' )
	$more_css = '  <link rel="stylesheet" type="text/css" href="'.$roster_conf['roster_dir'].$addon['css_url'].'">'."\n";
