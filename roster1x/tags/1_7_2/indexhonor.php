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

$header_title = $wordings[$roster_conf['roster_lang']]['menuhonor'];
include_once (ROSTER_BASE.'roster_header.tpl');


// Additional querries needed for this page
// Make sure the last item in this array DOES NOT have a (,) at the end
$additional_sql = array(
	'`players`.`RankIcon`, ',
	'`players`.`Rankexp`, ',
	'`players`.`sessionHK`, ',
	'`players`.`sessionCP`, ',
	'`players`.`yesterdayHK`, ',
	'`players`.`yesterdayContribution`, ',
	'`players`.`lifetimeHK`, ',
	'`players`.`lifetimeRankName`, ',
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
		'lang_field' => 'Today HK',
		'order' => array( '`players`.`sessionHK` DESC' ),
		'order_d' => array( '`players`.`sessionHK` ASC' ),
	),
);

$FIELD[] = array (
	'sessionCP' => array(
		'lang_field' => 'Today CP',
		'order' => array( '`players`.`sessionCP` DESC' ),
		'order_d' => array( '`players`.`sessionCP` ASC' ),
	),
);

$FIELD[] = array (
	'yesterdayHK' => array(
		'lang_field' => 'Yest HK',
		'order' => array( '`players`.`yesterdayHK` DESC' ),
		'order_d' => array( '`players`.`yesterdayHK` ASC' ),
	),
);

$FIELD[] = array (
	'yesterdayContribution' => array(
		'lang_field' => 'Yest CP',
		'order' => array( '`players`.`yesterdayContribution` DESC' ),
		'order_d' => array( '`players`.`yesterdayContribution` ASC' ),
	),
);

$FIELD[] = array (
	'lifetimeHK' => array(
		'lang_field' => 'Life HK',
		'order' => array( '`players`.`lifetimeHK` DESC' ),
		'order_d' => array( '`players`.`lifetimeHK` ASC' ),
	),
);

$FIELD[] = array (
	'lifetimeRankName' => array(
		'lang_field' => 'Highest Rank',
		'order' => array( '`players`.`lifetimeRankName` DESC' ),
		'order_d' => array( '`players`.`lifetimeRankName` ASC' ),
	),
);

$FIELD[] = array (
	'honorpoints' => array(
		'lang_field' => 'Honor Points',
		'order' => array( '`players`.`honorpoints` DESC' ),
		'order_d' => array( '`players`.`honorpoints` ASC' ),
	),
);

$FIELD[] = array (
	'arenapoints' => array(
		'lang_field' => 'Arena Points',
		'order' => array( '`players`.`arenapoints` DESC' ),
		'order_d' => array( '`players`.`arenapoints` ASC' ),
	),
);

include_once (ROSTER_BASE.'memberslist.php');

include_once (ROSTER_BASE.'roster_footer.tpl');
?>