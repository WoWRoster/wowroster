<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays Guild honor stats
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

$header_title = $act_words['menuhonor'];
include_once (ROSTER_BASE.'roster_header.tpl');


// Additional querries needed for this page
// Make sure the last item in this array DOES NOT have a (,) at the end
$additional_sql = array(
	'`players`.`sessionHK`, ',
	'`players`.`sessionCP`, ',
	'`players`.`yesterdayHK`, ',
	'`players`.`yesterdayContribution`, ',
	'`players`.`lifetimeHK`, ',
	'`players`.`honorpoints`, ',
	'`players`.`arenapoints` ',
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
	'sessionHK' => array(
		'lang_field' => 'todayhk',
		'order' => array( '`players`.`sessionHK` DESC' ),
		'order_d' => array( '`players`.`sessionHK` ASC' ),
	),
);

$FIELD[] = array (
	'sessionCP' => array(
		'lang_field' => 'todaycp',
		'order' => array( '`players`.`sessionCP` DESC' ),
		'order_d' => array( '`players`.`sessionCP` ASC' ),
	),
);

$FIELD[] = array (
	'yesterdayHK' => array(
		'lang_field' => 'yesthk',
		'order' => array( '`players`.`yesterdayHK` DESC' ),
		'order_d' => array( '`players`.`yesterdayHK` ASC' ),
	),
);

$FIELD[] = array (
	'yesterdayContribution' => array(
		'lang_field' => 'yestcp',
		'order' => array( '`players`.`yesterdayContribution` DESC' ),
		'order_d' => array( '`players`.`yesterdayContribution` ASC' ),
	),
);

$FIELD[] = array (
	'lifetimeHK' => array(
		'lang_field' => 'lifehk',
		'order' => array( '`players`.`lifetimeHK` DESC' ),
		'order_d' => array( '`players`.`lifetimeHK` ASC' ),
	),
);

$FIELD[] = array (
	'lifetimeRankName' => array(
		'lang_field' => 'highestrank',
		'order' => array( 'risnull', '`players`.`lifetimeRankName` DESC' ),
		'order_d' => array( 'risnull', '`players`.`lifetimeRankName` ASC' ),
		'value' => 'honor_value',
	),
);

$FIELD[] = array (
	'honorpoints' => array(
		'lang_field' => 'honorpoints',
		'order' => array( '`players`.`honorpoints` DESC' ),
		'order_d' => array( '`players`.`honorpoints` ASC' ),
	),
);

$FIELD[] = array (
	'arenapoints' => array(
		'lang_field' => 'arenapoints',
		'order' => array( '`players`.`arenapoints` DESC' ),
		'order_d' => array( '`players`.`arenapoints` ASC' ),
	),
);

include_once (ROSTER_LIB.'memberslist.php');

include_once (ROSTER_BASE.'roster_footer.tpl');
