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

/**
 * Value function for the language picker
 */
function rosterLangValue( $values )
{
	global $roster_conf;

	$input_field = '<select name="config_'.$values['name'].'">'."\n";
	$select_one = 1;
	foreach( $roster_conf['multilanguages'] as $value )
	{
		if( $value == $values['value'] && $select_one )
		{
			$input_field .= '  <option value="'.$value.'" selected="selected">-'.$value.'-</option>'."\n";
			$select_one = 0;
		}
		else
		{
			$input_field .= '  <option value="'.$value.'">'.$value.'</option>'."\n";
		}
	}
	$input_field .= '</select>';

	return $input_field;
}

/**
 * Value function to select starting page
 */
function pageNames( )
{
	global $roster_conf;

	/**
	 * Scan the pages directory to generate a list of available pages
	 */
	if( $handle = opendir(ROSTER_PAGES) )
	{
		$roster_conf['roster_pages'] = array();
		while( false !== ($file = readdir($handle)) )
		{
			if( !is_dir(ROSTER_PAGES.$file) && $file != '.' && $file != '..' && $file != 'addon.php' && !preg_match('/[^a-zA-Z0-9_.]/', $file) && get_file_ext($file) == 'php' )
			{
				$pages[] = array(substr($file,0,strpos($file,'.')),substr($file,0,strpos($file,'.')));
			}
		}
	}

	$addonlist = makeAddonList(2);

	if( !empty($addonlist) )
	{
		$pages = array_merge($pages, $addonlist);
	}

	$input_field = '<select name="config_default_page">'."\n";
	$select_one = 1;

	foreach( $pages as $value )
	{
		if( $value[0] == $roster_conf['default_page'] && $select_one )
		{
			$input_field .= '  <option value="'.$value[0].'" selected="selected">-'.$value[1].'-</option>'."\n";
			$select_one = 0;
		}
		else
		{
			$input_field .= '  <option value="'.$value[0].'">'.$value[1].'</option>'."\n";
		}
	}
	$input_field .= '</select>';

	return $input_field;
}
