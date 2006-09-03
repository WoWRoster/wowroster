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

$mainQuery =
	'SELECT '.
	'`members`.`member_id`, '.
	'`members`.`name`, '.
	'`members`.`class`, '.
	'`members`.`note`, '.
	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`level`, '.
	'`members`.`guild_rank`, '.
	'`members`.`guild_title`, '.
	'`members`.`zone`, '.
	"UNIX_TIMESTAMP( `members`.`last_online`) AS 'last_online_stamp', ".
	"DATE_FORMAT( `members`.`last_online`, '".$timeformat[$roster_conf['roster_lang']]."' ) AS 'last_online', ".

	'`players`.`RankName`, '.
	'`players`.`RankInfo`, '.
	"IF( `players`.`RankInfo` IS NULL OR `players`.`RankInfo` = '0', 1, 0 ) AS 'risnull', ".
	'`players`.`exp`, '.
	'`players`.`server`, '.
	'`players`.`clientLocale`, '.
	'`players`.`RankIcon`, '.
	'`players`.`Rankexp`, '.
	'`players`.`sessionHK`, '.
	'`players`.`sessionDK`, '.
	'`players`.`yesterdayHK`, '.
	'`players`.`yesterdayDK`, '.
	'`players`.`yesterdayContribution`, '.
	'`players`.`lastweekHK`, '.
	'`players`.`lastweekDK`, '.
	'`players`.`lastweekContribution`, '.
	'`players`.`lastweekRank`, '.
	'`players`.`lifetimeHK`, '.
	'`players`.`lifetimeDK`, '.
	'`players`.`lifetimeRankName` '.

	'FROM `'.ROSTER_MEMBERSTABLE.'` AS members '.
	'INNER JOIN `'.ROSTER_PLAYERSTABLE.'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	'WHERE`members`.`guild_id` = 1 '.
	'ORDER BY `members`.`level` DESC, `members`.`name` ASC';

$FIELD[] = array (
	'name' => array(
		'lang_field' => 'name',
		'required' => true,
		'default'  => true,
		'value' => 'name_value',
	),
);

$FIELD[] = array (
	'class' => array(
		'lang_field' => 'class',
		'default'  => true,
		'value' => 'class_value',
	),
);

$FIELD[] = array (
	'level' => array(
		'lang_field' => 'level',
		'default'  => true,
		'value' => 'level_value',
	),
);

$FIELD[] = array (
	'RankName' => array(
		'lang_field' => 'currenthonor',
		'value' => 'honor_value',
	),
);

$FIELD[] = array (
	'sessionHK' => array(
		'lang_field' => 'Sess HK',
	),
);

$FIELD[] = array (
	'sessionDK' => array(
		'lang_field' => 'Sess DK',
	),
);

$FIELD[] = array (
	'yesterdayHK' => array(
		'lang_field' => 'Yest HK',
	),
);

$FIELD[] = array (
	'yesterdayDK' => array(
		'lang_field' => 'Yest DK',
	),
);

$FIELD[] = array (
	'yesterdayContribution' => array(
		'lang_field' => 'Yest CP',
	),
);

$FIELD[] = array (
	'lastweekHK' => array(
		'lang_field' => 'LW HK',
	),
);

$FIELD[] = array (
	'lastweekDK' => array(
		'lang_field' => 'LW DK',
	),
);

$FIELD[] = array (
	'lastweekContribution' => array(
		'lang_field' => 'LW CP',
	),
);

$FIELD[] = array (
	'lastweekRank' => array(
		'lang_field' => 'LW Rank',
	),
);

$FIELD[] = array (
	'lifetimeHK' => array(
		'lang_field' => 'Life HK',
	),
);

$FIELD[] = array (
	'lifetimeDK' => array(
		'lang_field' => 'Life DK',
	),
);

$FIELD[] = array (
	'lifetimeRankName' => array(
		'lang_field' => 'Highest Rank',
	),
);

$more_css = '<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/sorttable.js"></script>';

include_once (ROSTER_BASE.'roster_header.tpl');

include_once (ROSTER_BASE.'memberslist.php');

include_once (ROSTER_BASE.'roster_footer.tpl');
?>