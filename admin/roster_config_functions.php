<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Special configuration functions for RosterCP
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Value function for the language picker
 */
function rosterLangValue( $values )
{
	global $roster;

	$input_field = '<select name="config_'.$values['name'].'">'."\n";
	$select_one = 1;
	foreach( $roster->multilanguages as $value )
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
	global $roster;

	$input_field = '<select name="config_default_page">'."\n";
	$select_one = 1;

	// --[ Fetch button list from DB ]--
	$query = "SELECT `mb`.*, `a`.`basename`
		FROM `".$roster->db->table('menu_button')."` AS mb
		LEFT JOIN `".$roster->db->table('addon')."` AS a
		ON `mb`.`addon_id` = `a`.`addon_id`
		ORDER BY `mb`.`title`;";

	$result = $roster->db->query($query);
	if( !$result )
	{
		die_quietly('Could not fetch menu buttons from table');
	}
	while( $row = $roster->db->fetch($result) )
	{
		if( $row['addon_id'] != '0' && !isset($roster->locale->act[$row['title']]) )
		{
			// Include addon's locale files if they exist
			foreach( $roster->multilanguages as $lang )
			{
				if( file_exists(ROSTER_ADDONS.$row['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php') )
				{
					$roster->locale->add_locale_file(ROSTER_ADDONS.$row['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php',$lang);
				}
			}
		}

		if( $row['url'] == $roster->config['default_page'] && $select_one )
		{
			$input_field .= '  <option value="'.$row['url'].'" selected="selected">-[ '.( isset($roster->locale->act[$row['title']]) ? $roster->locale->act[$row['title']] : $row['title'] ).' ]-</option>'."\n";
			$select_one = 0;
		}
		else
		{
			$input_field .= '  <option value="'.$row['url'].'">'.( isset($roster->locale->act[$row['title']]) ? $roster->locale->act[$row['title']] : $row['title'] ).'</option>'."\n";
		}
	}
	
	$input_field .= '</select>';

	$roster->db->free_result($result);
	
	return $input_field;
}

/**
 * Get a list of font from the font directory
 *
 * @return array | $file => $name
 */
function fontFiles( $values )
{
	$arrFiles = array();

	// Open the directory
	$tmp_dir = @opendir( ROSTER_BASE.'fonts' );

	if( !empty($tmp_dir) )
	{
		// Read the files
		while( $file = readdir($tmp_dir) )
		{
			$pfad_info = pathinfo($file);

			if( strtolower($pfad_info['extension']) == strtolower('ttf') )
			{
				$name = str_replace('.'.$pfad_info['extension'],'',$file);
				$arrFiles += array($file => $name);
			}
		}
		// close the directory
		closedir($tmp_dir);

		//sort the list
		asort($arrFiles);
	}

	$input_field = '<select name="config_'.$values['name'].'">'."\n";
	$select_one = 1;
	foreach( $arrFiles as $file=>$name )
	{
		if( $file == $values['value'] && $select_one )
		{
			$input_field .= '  <option value="'.$file.'" selected="selected">-[ '.$name.' ]-</option>'."\n";
			$select_one = 0;
		}
		else
		{
			$input_field .= '  <option value="'.$file.'">'.$name.'</option>'."\n";
		}
	}
	$input_field .= '</select>';
	return $input_field;
}

