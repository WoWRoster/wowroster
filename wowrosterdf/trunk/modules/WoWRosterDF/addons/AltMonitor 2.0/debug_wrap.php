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

// I'll include the menu myself to remain as much as possible in the same style as main members list
$roster_show_menu = false;

// Based on index.php
$additional_sql = array(
	"`members`.`officer_note`, ",
	"IF( `members`.`officer_note` IS NULL OR `members`.`officer_note` = '', 1, 0 ) AS 'onisnull' ",
);

$FIELD[] = array (
	'name' => array(
		'lang_field' => 'name',
		'order'    => array( '`mains`.`name` ASC, isalt, `members`.`name` ASC' ),
		'order_d'    => array( '`mains`.`name` DESC, isalt, `members`.`name` DESC' ),
		'required' => true,
		'default'  => true,
		'value' => 'name_value',
	),
);

$FIELD[] = array (
	'main_name' => array (
		'lang_field' => 'main_name',
		'order' => array( '`mains`.`name` ASC, isalt ' ),
		'order_d' => array( '`mains`.`name` DESC, isalt' ),
	),
);

$FIELD[] = array (
	'alt_type' => array (
		'lang_field' => 'alt_type',
	),
);

if($addon_conf['AltMonitor']['getmain_field'] == 'guild_title')
{
	$FIELD[] = array (
		'guild_title' => array (
			'lang_field' => 'title',
			'divider' => true,
			'order' => array( '`mains`.`guild_rank` ASC, `mains`.`name` ASC, isalt, `members`.`guild_rank` ASC' ),
			'order_d' => array( '`mains`.`guild_rank` DESC, `mains`.`name` DESC, isalt, `members`.`guild_rank` DESC' ),
		),
	);
}

if($addon_conf['AltMonitor']['getmain_field'] == 'note')
{
	$FIELD[] = array (
		'note' => array(
			'lang_field' => 'note',
			'order' => array( 'nisnull','`members`.`note` ASC' ),
			'order_d' => array( 'nisnull','`members`.`note` DESC' ),
		),
	);
}

if($addon_conf['AltMonitor']['getmain_field'] == 'officer_note')
{
	$FIELD[] = array (
		'officer_note' => array(
			'lang_field' => 'officer_note',
			'order' => array( 'onisnull','`members`.`officer_note` ASC' ),
			'order_d' => array( 'onisnull','`members`.`officer_note` DESC' ),
		),
	);
}

include_once ($addonDir.'altlist.php');

print border('syellow','start','Config').'<pre>';
print_r($addon_conf);
print '</pre>'.border('syellow','end');
?>
