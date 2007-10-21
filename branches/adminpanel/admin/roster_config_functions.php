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

function rosterLangValue( $values )
{
	global $roster_conf;

	$input_field = '<select name="config_'.$values['name'].'">'."\n";
	$select_one = 1;
	foreach( $roster_conf['multilanguages'] as $value )
	{
		if( $value == $values['value'] && $select_one )
		{
			$input_field .= '  <option value="'.$value.'" selected="selected">&gt;'.$value.'&lt;</option>'."\n";
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

?>