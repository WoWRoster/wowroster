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
	'`players`.`hearth`, ',
	"IF( `players`.`hearth` IS NULL OR `players`.`hearth` = '', 1, 0 ) AS 'hisnull', ",
	"IF( `main_players`.`hearth` IS NULL OR `main_players`.`hearth` = '', 1, 0 ) AS 'mhisnull', ",
	"`players`.`dateupdatedutc` AS 'last_update', ",
	"IF( `players`.`dateupdatedutc` IS NULL OR `players`.`dateupdatedutc` = '', 1, 0 ) AS 'luisnull', ",
	"IF( `main_players`.`dateupdatedutc` IS NULL OR `main_players`.`dateupdatedutc` = '', 1, 0 ) AS 'mluisnull' "
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
	'class' => array(
		'lang_field' => 'class',
		'divider' => true,
		'divider_value' => 'class_divider',
		'order'    => array( '`mains`.`class` ASC, `mains`.`name` ASC, isalt, `members`.`class` ASC' ),
		'order_d'    => array( '`mains`.`class` DESC, `mains`.`name` ASC, isalt, `members`.`class` DESC' ),
		'default'  => true,
		'value' => 'class_value',
	),
);

$FIELD[] = array (
	'level' => array(
		'lang_field' => 'level',
		'divider' => true,
		'divider_prefix' => 'Level ',
		'order'    => array( '`mains`.`level` DESC, `mains`.`name` ASC, isalt'),
		'order_d'    => array( '`mains`.`level` ASC, `mains`.`name` DESC, isalt, `members`.`level` ASC' ),
		'default'  => true,
		'value' => 'level_value',
	),
);

if ( $addon_conf['AltMonitor']['showmain'] )
{
	$FIELD[] = array (
		'main_name' => array (
			'lang_field' => 'main_name',
			'order' => array( '`mains`.`name` ASC, isalt ' ),
			'order_d' => array( '`mains`.`name` DESC, isalt' ),
		),
	);
}

if ( $roster_conf['index_title'] == 1 )
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

if ( $roster_conf['index_currenthonor'] == 1 )
{
	$FIELD[] = array (
		'RankName' => array(
			'lang_field' => 'currenthonor',
			'divider' => true,
			'order' => array( 'mrisnull, `main_players`.`RankInfo` DESC, `mains`.`name`, isalt, risnull, `players`.`RankInfo` DESC' ),
			'order_d' => array( 'mrisnull, `main_players`.`RankInfo` ASC, `mains`.`name`, isalt, risnull, `players`.`RankInfo` ASC' ),
			'value' => 'honor_value',
		),
	);
}

if ( $roster_conf['index_note'] == 1 && $roster_conf['compress_note'] == 0 )
{
	$FIELD[] = array (
		'note' => array(
			'lang_field' => 'note',
			'order' => array( 'nisnull','`members`.`note` ASC' ),
			'order_d' => array( 'nisnull','`members`.`note` DESC' ),
			'value' => 'note_value',
		),
	);
}

if ( $roster_conf['index_prof'] == 1 )
{
	$FIELD[] = array (
		'professions' => array(
			'lang_field' => 'professions',
		),
	);
}

if ( $roster_conf['index_hearthed'] == 1 )
{
	$FIELD[] = array (
		'hearth' => array(
			'lang_field' => 'hearthed',
			'divider' => true,
			'order' => array( 'mhisnull, `main_players`.`hearth` ASC, `mains`.`name`, isalt, hisnull, `players`.`hearth` ASC' ),
			'order_d' => array( 'mhisnull, `main_players`.`hearth` DESC, `mains`.`name`, isalt, hisnull, `players`.`hearth` DESC' ),
		),
	);
}

if ( $roster_conf['index_zone'] == 1 )
{
	$FIELD[] = array (
		'zone' => array(
			'lang_field' => 'zone',
			'divider' => true,
			'order' => array( '`mains`.`zone` ASC, `mains`.`name`, isalt, `members`.`zone` ASC' ),
			'order_d' => array( '`mains`.`zone` DESC, `mains`.`name`, isalt, `members`.`zone` DESC' ),
		),
	);
}

if ( $roster_conf['index_lastonline'] == 1 )
{
	$FIELD[] = array (
		'last_online' => array (
			'lang_field' => 'lastonline',
			'order' => array( '`mains`.`last_online` ASC, `mains`.`name`, isalt, `members`.`last_online` DESC' ),
			'order_d' => array( '`mains`.`last_online` DESC, `mains`.`name`, isalt, `members`.`last_online` ASC' ),
		),
	);
}

if ( $roster_conf['index_lastupdate'] == 1 )
{
	$FIELD[] = array (
		'last_update' => array (
			'lang_field' => 'lastupdate',
			'order' => array( 'mluisnull, `main_players`.`dateupdatedutc` DESC, `mains`.`name`, isalt, luisnull, last_update DESC' ),
			'order_d' => array( 'mluisnull, `main_players`.`dateupdatedutc` ASC, `mains`.`name`, isalt, luisnull, last_update ASC' ),
			'value' => 'last_up_value',
		),
	);
}

if ( $roster_conf['index_note'] == 1 && $roster_conf['compress_note'] == 1 )
{
	$FIELD[] = array (
		'note' => array(
			'lang_field' => 'note',
			'order' => array( 'nisnull','`members`.`note` ASC' ),
			'order_d' => array( 'nisnull','`members`.`note` DESC' ),
			'value' => 'note_value',
		),
	);
}

include_once ($addonDir.'altlist.php');
?>
