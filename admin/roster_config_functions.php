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
			$input_field .= '  <option value="'.$value.'" selected="selected">-[ '.$value.' ]-</option>'."\n";
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
	global $roster_conf, $wowdb, $act_words, $wordings;

	$input_field = '<select name="config_default_page">'."\n";
	$select_one = 1;

	// --[ Fetch button list from DB ]--
	$query = "SELECT `mb`.*, `a`.`basename`
		FROM `".$wowdb->table('menu_button')."` AS mb
		LEFT JOIN `".$wowdb->table('addon')."` AS a
		ON `mb`.`addon_id` = `a`.`addon_id`;";

	$result = $wowdb->query($query);
	if( !$result )
	{
		die_quietly('Could not fetch menu buttons from table');
	}
	while( $row = $wowdb->fetch_assoc($result) )
	{
		if( $row['addon_id'] != '0' && !isset($act_words[$row['title']]) )
		{
			// Include addon's locale files if they exist
			foreach( $roster_conf['multilanguages'] as $lang )
			{
				if( file_exists(ROSTER_ADDONS.$row['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php') )
				{
					add_locale_file(ROSTER_ADDONS.$row['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php',$lang,$wordings);
				}
			}
		}

		if( $row['url'] == $roster_conf['default_page'] && $select_one )
		{
			$input_field .= '  <option value="'.$row['url'].'" selected="selected">-[ '.( isset($act_words[$row['title']]) ? $act_words[$row['title']] : $row['title'] ).' ]-</option>'."\n";
			$select_one = 0;
		}
		else
		{
			$input_field .= '  <option value="'.$row['url'].'">'.( isset($act_words[$row['title']]) ? $act_words[$row['title']] : $row['title'] ).'</option>'."\n";
		}
	}
	$input_field .= '</select>';

	return $input_field;
}
