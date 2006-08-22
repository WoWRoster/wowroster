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
	'`players`.`stat_int_c`, '.
	'`players`.`stat_agl_c`, '.
	'`players`.`stat_sta_c`, '.
	'`players`.`stat_str_c`, '.
	'`players`.`stat_spr_c` '.

	'FROM `'.ROSTER_MEMBERSTABLE.'` AS members '.
	'INNER JOIN `'.ROSTER_PLAYERSTABLE.'` AS players ON `members`.`member_id` = `players`.`member_id` AND `members`.`guild_id` = 1 '.
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
	'stat_int_c' => array (
		'lang_field' => 'intellect',
	),
);

$FIELD[] = array (
	'stat_agl_c' => array (
		'lang_field' => 'agility',
	),
);

$FIELD[] = array (
	'stat_sta_c' => array (
		'lang_field' => 'stamina',
	),
);

$FIELD[] = array (
	'stat_str_c' => array (
		'lang_field' => 'strength',
	),
);

$FIELD[] = array (
	'stat_spr_c' => array (
		'lang_field' => 'spirit',
	),
);

$FIELD[] = array (
	'total' => array (
		'lang_field' => 'total',
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