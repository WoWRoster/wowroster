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

$header_title = $wordings[$roster_conf['roster_lang']]['alternate'];

// Additional querries needed for this page
// Make sure the last item in this array DOES NOT have a (,) at the end
$additional_sql = array(
	'`players`.`health`, ',
	"IF( `players`.`health` IS NULL OR `players`.`health` = '', 1, 0 ) AS 'hisnull', ",
	'`players`.`mana`, ',
	"IF( `players`.`mana` IS NULL OR `players`.`mana` = '', 1, 0 ) AS 'misnull', ",
	'`players`.`money_g`, ',
	'`players`.`money_s`, ',
	'`players`.`money_c`, ',
	'`players`.`stat_armor`, ',
	'`players`.`stat_armor_c`, ',
	'`players`.`stat_armor_b`, ',
	'`players`.`stat_armor_d`, ',
	"IF( `players`.`stat_armor_c` IS NULL OR `players`.`stat_armor_c` = '', 1, 0 ) AS 'aisnull', ",
	'`players`.`mitigation` ',
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
	'health' => array (
		'lang_field' => 'health',
		'order' => array( 'hisnull','`players`.`health` DESC' ),
		'order_d' => array( 'hisnull','`players`.`health` ASC' ),
	),
);

$FIELD[] = array (
	'mana' => array(
		'lang_field' => 'mana',
		'order' => array( 'misnull','`players`.`mana` DESC' ),
		'order_d' => array( 'misnull','`players`.`mana` ASC' ),
	),
);

if( $roster_conf['show_money'] )
{
	$FIELD[] = array (
		'money_g' => array(
			'lang_field' => 'gold',
			'order' => array( '`players`.`money_g` DESC','`players`.`money_s` DESC','`players`.`money_c` DESC' ),
			'order_d' => array( '`players`.`money_g` ASC','`players`.`money_s` ASC','`players`.`money_c` ASC' ),
			'value' => 'money_value',
		),
	);
}

$FIELD[] = array (
	'stat_armor_c' => array(
		'lang_field' => 'armor',
		'order' => array( 'aisnull','`players`.`stat_armor_c` DESC' ),
		'order_d' => array( 'aisnull','`players`.`stat_armor_c` ASC' ),
		'value' => 'armor_value',
	),
);

$more_css = '<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/sorttable.js"></script>';

include_once (ROSTER_BASE.'roster_header.tpl');

include_once (ROSTER_BASE.'memberslist.php');

include_once (ROSTER_BASE.'roster_footer.tpl');
?>