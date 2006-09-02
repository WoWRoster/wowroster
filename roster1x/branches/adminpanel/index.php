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
	'`players`.`hearth`, '.
	"IF( `players`.`hearth` IS NULL OR `players`.`hearth` = '', 1, 0 ) AS 'hisnull', ".
	"`players`.`dateupdatedutc` AS 'last_update', ".
	"IF( `players`.`dateupdatedutc` IS NULL OR `players`.`dateupdatedutc` = '', 1, 0 ) AS 'luisnull', ".

	'`proftable`.`professions` '.

	'FROM `'.ROSTER_MEMBERSTABLE.'` AS members '.
	'LEFT JOIN `'.ROSTER_PLAYERSTABLE.'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	"LEFT JOIN (SELECT `member_id` , GROUP_CONCAT( CONCAT( `skill_name` , '|', `skill_level` ) ) AS 'professions' ".
		'FROM `roster_skills` '.
		'GROUP BY `member_id`) AS proftable ON `members`.`member_id` = `proftable`.`member_id` '.
	'WHERE `members`.`guild_id` = 1 '.
	'ORDER BY `members`.`level` DESC, `members`.`name` ASC';

	$always_sort;


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

if ( $roster_conf['index_title'] == 1 )
{
	$FIELD[] = array (
		'guild_title' => array (
			'lang_field' => 'title',
			'jsort' => 'guild_rank',
		),
	);
}

if ( $roster_conf['index_currenthonor'] == 1 )
{
	$FIELD[] = array (
		'RankName' => array(
			'lang_field' => 'currenthonor',
			'value' => 'honor_value',
		),
	);
}

if ( $roster_conf['index_note'] == 1 )
{
	$FIELD[] = array (
		'note' => array(
			'lang_field' => 'note',
			'value' => 'note_value',
		),
	);
}

if ( $roster_conf['index_prof'] == 1 )
{
	$FIELD[] = array (
		'professions' => array(
			'lang_field' => 'professions',
			'value' => 'tradeskill_icons',
		),
	);
}

if ( $roster_conf['index_hearthed'] == 1 )
{
	$FIELD[] = array (
		'hearth' => array(
			'lang_field' => 'hearthed',
		),
	);
}

if ( $roster_conf['index_zone'] == 1 )
{
	$FIELD[] = array (
		'zone' => array(
			'lang_field' => 'zone',
		),
	);
}

if ( $roster_conf['index_lastonline'] == 1 )
{
	$FIELD[] = array (
		'last_online' => array (
			'lang_field' => 'lastonline',
		),
	);
}

if ( $roster_conf['index_lastupdate'] == 1 )
{
	$FIELD[] = array (
		'last_update' => array (
			'lang_field' => 'lastupdate',
			'jsort' => 'last_online_stamp',
		),
	);
}

/**
 * Controls Output of the Last Updated Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function last_up_value ( $row )
{
	global $roster_conf, $phptimeformat;

	if ( $row['last_update'] != '')
	{
		$cell_value = $row['last_update'];

		list($month,$day,$year,$hour,$minute,$second) = sscanf($cell_value,"%d/%d/%d %d:%d:%d");

		$localtime = mktime($hour+$roster_conf['localtimeoffset'] ,$minute, $second, $month, $day, $year, -1);
		return '<div style="display:none;">'.$localtime.'</div>'.date($phptimeformat[$roster_conf['roster_lang']], $localtime);
	}
	else
	{
		return '&nbsp;';
	}
}

/**
 * Controls Output of the Tradeskill Icons Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function tradeskill_icons ( $row )
{
	global $wowdb, $roster_conf, $wordings;

	$cell_value ='';
	
	// Don't proceed for characters without data
	if ($row['clientLocale'] == '')
	{
		return '&nbsp;';
	}
	$profs = explode(',',$row['professions']);
	foreach ( $profs as $prof)
	{
		$r_prof = explode('|',$prof);
		$toolTip = str_replace(':','/',$r_prof[1]);
		$toolTiph = $r_prof[0];

		$skill_image = 'Interface/Icons/'.$wordings[$row['clientLocale']]['ts_iconArray'][$r_prof[0]];

		// Don't add professions we don't have an icon for. This keeps other skills out.
		if ($wordings[$row['clientLocale']]['ts_iconArray'][$r_prof[0]] != '') {
			$cell_value .= "<img class=\"membersRowimg\" width=\"".$roster_conf['index_iconsize']."\" height=\"".$roster_conf['index_iconsize']."\" src=\"".$roster_conf['interface_url'].$skill_image.'.'.$roster_conf['img_suffix']."\" alt=\"\" onmouseover=\"return overlib('$toolTip',CAPTION,'$toolTiph',RIGHT,WRAP);\" onmouseout=\"return nd();\" />\n";
		}
	}
	return $cell_value;
}

/**
 * Controls Output of a Note Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function note_value ( $row )
{
	if( !empty($row['note']) )
	{
		$prg_find = array('/"/','/&/','|\\>|','|\\<|',"/\\n/");
		$prg_rep  = array('&quot;','&amp;','&gt;','&lt;','<br />');

		$note = preg_replace($prg_find, $prg_rep, $row['note']);
	}
	else
		$note = '&nbsp;';

	return $note;
}


$more_css = '<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/sorttable.js"></script>';

include_once (ROSTER_BASE.'roster_header.tpl');

include_once (ROSTER_BASE.'memberslist.php');

include_once (ROSTER_BASE.'roster_footer.tpl');
?>