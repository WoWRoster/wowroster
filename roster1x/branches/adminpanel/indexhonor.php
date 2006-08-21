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


// Additional querries needed for this page
// Make sure the last item in this array DOES NOT have a (,) at the end
$additional_sql = array(
	'`players`.`RankIcon`, ',
	'`players`.`Rankexp`, ',
	'`players`.`sessionHK`, ',
	'`players`.`sessionDK`, ',
	'`players`.`yesterdayHK`, ',
	'`players`.`yesterdayDK`, ',
	'`players`.`yesterdayContribution`, ',
	'`players`.`lastweekHK`, ',
	'`players`.`lastweekDK`, ',
	'`players`.`lastweekContribution`, ',
	'`players`.`lastweekRank`, ',
	'`players`.`lifetimeHK`, ',
	'`players`.`lifetimeDK`, ',
	'`players`.`lifetimeRankName` ',
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
		'order'    => array( '`members`.`class` ASC' ),
		'order_d'    => array( '`members`.`class` DESC' ),
		'default'  => true,
		'value' => 'class_value',
	),
);

$FIELD[] = array (
	'level' => array(
		'lang_field' => 'level',
		'order_d'    => array( '`members`.`level` ASC' ),
		'default'  => true,
		'value' => 'level_value',
	),
);

$FIELD[] = array (
	'RankName' => array(
		'lang_field' => 'currenthonor',
		'order' => array( '`players`.`RankInfo` DESC' ),
		'order_d' => array( '`players`.`RankInfo` ASC' ),
		'value' => 'honor_value',
	),
);

$FIELD[] = array (
	'sessionHK' => array(
		'lang_field' => 'Sess HK',
		'order' => array( '`players`.`sessionHK` DESC' ),
		'order_d' => array( '`players`.`sessionHK` ASC' ),
	),
);

$FIELD[] = array (
	'sessionDK' => array(
		'lang_field' => 'Sess DK',
		'order' => array( '`players`.`sessionDK` DESC' ),
		'order_d' => array( '`players`.`sessionDK` ASC' ),
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
	'yesterdayDK' => array(
		'lang_field' => 'Yest DK',
		'order' => array( '`players`.`yesterdayDK` DESC' ),
		'order_d' => array( '`players`.`yesterdayDK` ASC' ),
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
	'lastweekHK' => array(
		'lang_field' => 'LW HK',
		'order' => array( '`players`.`lastweekHK` DESC' ),
		'order_d' => array( '`players`.`lastweekHK` ASC' ),
	),
);

$FIELD[] = array (
	'lastweekDK' => array(
		'lang_field' => 'LW DK',
		'order' => array( '`players`.`lastweekDK` DESC' ),
		'order_d' => array( '`players`.`lastweekDK` ASC' ),
	),
);

$FIELD[] = array (
	'lastweekContribution' => array(
		'lang_field' => 'LW CP',
		'order' => array( '`players`.`lastweekContribution` DESC' ),
		'order_d' => array( '`players`.`lastweekContribution` ASC' ),
	),
);

$FIELD[] = array (
	'lastweekRank' => array(
		'lang_field' => 'LW Rank',
		'order' => array( '`players`.`lastweekRank` DESC' ),
		'order_d' => array( '`players`.`lastweekRank` ASC' ),
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
	'lifetimeDK' => array(
		'lang_field' => 'Life DK',
		'order' => array( '`players`.`lifetimeDK` DESC' ),
		'order_d' => array( '`players`.`lifetimeDK` ASC' ),
	),
);

$FIELD[] = array (
	'lifetimeRankName' => array(
		'lang_field' => 'Highest Rank',
		'order' => array( '`players`.`lifetimeRankName` DESC' ),
		'order_d' => array( '`players`.`lifetimeRankName` ASC' ),
	),
);

$more_css = '<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/sorttable.js"></script>';

include_once (ROSTER_BASE.'roster_header.tpl');

include_once (ROSTER_BASE.'memberslist.php');

include_once (ROSTER_BASE.'roster_footer.tpl');
?>