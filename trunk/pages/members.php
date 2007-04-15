<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Main member list
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

include_once (ROSTER_BASE.'roster_header.tpl');


// Additional querries needed for this page
// Make sure the last item in this array DOES NOT have a (,) at the end
$additional_sql = array(
	'`players`.`hearth`, ',
	"IF( `players`.`hearth` IS NULL OR `players`.`hearth` = '', 1, 0 ) AS 'hisnull', ",
	"DATE_FORMAT(`players`.`dateupdatedutc`, '".$act_words['timeformat']."') as last_update, ",
	"IF( `players`.`dateupdatedutc` IS NULL OR `players`.`dateupdatedutc` = '', 1, 0 ) AS 'luisnull' ",
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

if ( $roster_conf['index_title'] == 1 )
{
	$FIELD[] = array (
		'guild_title' => array (
			'lang_field' => 'title',
			'divider' => true,
			'order' => array( '`members`.`guild_rank` ASC' ),
			'order_d' => array( '`members`.`guild_rank` DESC' ),
		),
	);
}

if ( $roster_conf['index_currenthonor'] == 1 )
{
	$FIELD[] = array (
		'lifetimeHighestRank' => array(
			'lang_field' => 'highestrank',
			'order' => array( 'risnull', '`players`.`lifetimeHighestRank` DESC' ),
			'order_d' => array( 'risnull', '`players`.`lifetimeHighestRank` ASC' ),
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
			'order' => array( 'hisnull', 'hearth ASC' ),
			'order_d' => array( 'hisnull', 'hearth DESC' ),
		),
	);
}

if ( $roster_conf['index_zone'] == 1 )
{
	$FIELD[] = array (
		'zone' => array(
			'lang_field' => 'zone',
			'divider' => true,
			'order' => array( '`members`.`zone` ASC' ),
			'order_d' => array( '`members`.`zone` DESC' ),
		),
	);
}

if ( $roster_conf['index_lastonline'] == 1 )
{
	$FIELD[] = array (
		'last_online_f' => array (
			'lang_field' => 'lastonline',
			'order' => array( '`members`.`last_online` DESC' ),
			'order_d' => array( '`members`.`last_online` ASC' ),
			'value' => 'last_online_value',
		),
	);
}

if ( $roster_conf['index_lastupdate'] == 1 )
{
	$FIELD[] = array (
		'last_update' => array (
			'lang_field' => 'lastupdate',
			'order' => array( 'luisnull','`players`.`dateupdatedutc` DESC' ),
			'order_d' => array( 'luisnull','`players`.`dateupdatedutc` ASC' ),
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

include_once (ROSTER_LIB.'memberslist.php');

include_once (ROSTER_BASE.'roster_footer.tpl');
