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

require_once( 'settings.php' );

$header_title = $wordings[$roster_conf['roster_lang']]['menustats'];

// Additional querries needed for this page
// Make sure the last item in this array DOES NOT have a (,) at the end
$additional_sql = array(
	"`stat_int_c`, ",
	"`stat_agl_c`, ",
	"`stat_sta_c`, ",
	"`stat_str_c`, ",
	"`stat_spr_c` ",
);

$FIELD[] = array (
	'name' => array(
		'lang_field' => 'name',
		'order'    => array( '`members`.`name` ASC' ),
		'order_d'    => array( '`members`.`name` DESC' ),
		'required' => true,
		'default'  => true,
		'value' => 'name_value',
	),
);

$FIELD[] = array (
	'class' => array(
		'lang_field' => 'class',
		'divider' => true,
		'divider_value' => 'class_divider',
		'order'    => array( '`members`.`class` ASC' ),
		'order_d'    => array( '`members`.`class` DESC' ),
		'default'  => true,
		'value' => 'class_value',
	),
);

$FIELD[] = array (
	'level' => array(
		'lang_field' => 'level',
		'divider' => true,
		'divider_prefix' => 'Level ',
		'order_d'    => array( '`members`.`level` ASC' ),
		'default'  => true,
		'value' => 'level_value',
	),
);

$FIELD[] = array (
	'stat_int_c' => array (
		'lang_field' => 'intellect',
		'order' => array( "`stat_int_c` DESC" ),
		'order_d' => array( "`stat_int_c` ASC" ),
	),
);

$FIELD[] = array (
	'stat_agl_c' => array (
		'lang_field' => 'agility',
		'order' => array( "`stat_agl_c` DESC" ),
		'order_d' => array( "`stat_agl_c` ASC" ),
	),
);

$FIELD[] = array (
	'stat_sta_c' => array (
		'lang_field' => 'stamina',
		'order' => array( "`stat_sta_c` DESC" ),
		'order_d' => array( "`stat_sta_c` ASC" ),
	),
);

$FIELD[] = array (
	'stat_str_c' => array (
		'lang_field' => 'strength',
		'order' => array( "`stat_str_c` DESC" ),
		'order_d' => array( "`stat_str_c` ASC" ),
	),
);

$FIELD[] = array (
	'stat_spr_c' => array (
		'lang_field' => 'spirit',
		'order' => array( "`stat_spr_c` DESC" ),
		'order_d' => array( "`stat_spr_c` ASC" ),
	),
);

$FIELD[] = array (
	'total' => array (
		'lang_field' => 'total',
		'order' => array( "(`players`.`stat_int_c` + `players`.`stat_agl_c` + `players`.`stat_sta_c` + `players`.`stat_str_c` + `players`.`stat_spr_c`) DESC" ),
		'order_d' => array( "(`players`.`stat_int_c` + `players`.`stat_agl_c` + `players`.`stat_sta_c` + `players`.`stat_str_c` + `players`.`stat_spr_c`) ASC" ),
		'value' => 'total_value',
	),
);


/**
 * Controls Output of the Total Stats value Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function total_value ( $row )
{
	global $wowdb, $roster_conf, $wordings;

	if( $row['stat_int_c'] )
		$cell_value = $row['stat_int_c'] + $row['stat_agl_c'] + $row['stat_sta_c'] + $row['stat_str_c'] + $row['stat_spr_c'];
	else
		$cell_value =  '&nbsp;';

	return $cell_value;
}

$more_css = '<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/sorttable.js"></script>';

include_once (ROSTER_BASE.'roster_header.tpl');

include_once (ROSTER_BASE.'memberslist.php');

include_once (ROSTER_BASE.'roster_footer.tpl');
?>