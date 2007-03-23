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

$script_filename = 'index.php';
require_once( 'settings.php' );

//---[ Check for Guild Info ]------------
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}

$mainQuery =
	'SELECT '.
	'`characters`.`member_id`, '.
	'`characters`.`name`, '.
	'`characters`.`class`, '.
	'`characters`.`level`, '.
	'`characters`.`server`, '.
	'`characters`.`zone`, '.
	"UNIX_TIMESTAMP( `characters`.`last_online`) AS 'last_online_stamp', ".
	"DATE_FORMAT(  DATE_ADD(`characters`.`last_online`, INTERVAL ".$roster_conf['localtimeoffset']." HOUR ), '".$timeformat[$roster_conf['roster_lang']]."' ) AS 'last_online', ".

	'`members`.`note`, '.
	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`guild_rank`, '.
	'`members`.`guild_title`, '.
	'`members`.`status`, '.

	'`players`.`race`, '.
	'`players`.`RankName`, '.
	'`players`.`RankInfo`, '.
	"IF( `players`.`RankInfo` IS NULL OR `players`.`RankInfo` = '0', 1, 0 ) AS 'risnull', ".
	'`players`.`exp`, '.
	'`players`.`clientLocale`, '.
	'`players`.`RankIcon`, '.
	'`players`.`Rankexp`, '.
	'`players`.`hearth`, '.
	"IF( `players`.`hearth` IS NULL OR `players`.`hearth` = '', 1, 0 ) AS 'hisnull', ".
	"`players`.`dateupdatedutc` AS 'last_update', ".
	"DATE_FORMAT(  DATE_ADD(`players`.`dateupdatedutc`, INTERVAL ".$roster_conf['localtimeoffset']." HOUR ), '".$timeformat[$roster_conf['roster_lang']]."' ) AS 'last_update_format', ".
	"IF( `players`.`dateupdatedutc` IS NULL OR `players`.`dateupdatedutc` = '', 1, 0 ) AS 'luisnull', ".

	'`proftable`.`professions` '.

	'FROM `'.ROSTER_CHARACTERSTABLE.'` AS characters '.
	'LEFT JOIN `'.ROSTER_MEMBERSTABLE.'` AS members ON `characters`.`member_id` = `members`.`member_id` '.
	'LEFT JOIN `'.ROSTER_PLAYERSTABLE.'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	"LEFT JOIN (SELECT `member_id` , GROUP_CONCAT( CONCAT( `skill_name` , '|', `skill_level` ) ) AS 'professions' ".
		'FROM `roster_skills` '.
		'GROUP BY `member_id`) AS proftable ON `members`.`member_id` = `proftable`.`member_id` '.
	'WHERE `members`.`guild_id` = '.$guild_info['guild_id'].' '.
	'ORDER BY `characters`.`level` DESC, `characters`.`name` ASC';

$perms = array(
	'index_title' => $roster_conf['index_title'],
	'index_currenthonor' => $roster_conf['index_currenthonor'],
	'index_note' => $roster_conf['index_note'],
	'index_prof' => $roster_conf['index_prof'],
	'index_hearthed' => $roster_conf['index_hearthed'],
	'index_zone' => $roster_conf['index_zone'],
	'index_lastonline' => $roster_conf['index_lastonline'],
	'index_lastupdate' => $roster_conf['index_lastupdate']
);

$perms = $roster_login->getAuthorized($perms);

$FIELD['name'] = array (
	'lang_field' => 'name',
	'required' => true,
	'default'  => true,
	'value' => 'name_value',
);

$FIELD['class'] = array (
	'lang_field' => 'class',
	'default'  => true,
	'value' => 'class_value',
);

$FIELD['level'] = array (
	'lang_field' => 'level',
	'default'  => true,
	'value' => 'level_value',
);

if ( $perms['index_title'] )
{
	$FIELD['guild_title'] = array (
		'lang_field' => 'title',
		'jsort' => 'guild_rank',
	);
}

if ( $perms['index_currenthonor'] )
{
	$FIELD['RankName'] = array (
		'lang_field' => 'currenthonor',
		'value' => 'honor_value',
	);
}

if ( $perms['index_note'] )
{
	$FIELD['note'] = array (
		'lang_field' => 'note',
		'value' => 'note_value',
	);
}

if ( $perms['index_prof'] )
{
	$FIELD['professions'] = array (
		'lang_field' => 'professions',
		'value' => 'tradeskill_icons',
	);
}

if ( $perms['index_hearthed'] )
{
	$FIELD['hearth'] = array (
		'lang_field' => 'hearthed',
	);
}

if ( $perms['index_zone'] )
{
	$FIELD['zone'] = array (
		'lang_field' => 'zone',
	);
}

if ( $perms['index_lastonline'] )
{
	$FIELD['last_online'] = array (
		'lang_field' => 'lastonline',
	);
}

if ( $perms['index_lastupdate'] )
{
	$FIELD['last_update_format'] = array (
		'lang_field' => 'lastupdate',
		'jsort' => 'last_online_stamp',
	);
}

include_once (ROSTER_LIB.'memberslist.php');

$memberlist = new memberslist;

$memberlist->prepareData($mainQuery, $FIELD, 'memberslist');

$more_css = '<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/sorttable.js"></script>';


// Start output
include_once (ROSTER_BASE.'roster_header.tpl');

if( $roster_conf['index_update_inst'] )
{
	print '            <a href="#update"><font size="4">'.$wordings[$roster_conf['roster_lang']]['update_link'].'</font></a><br /><br />';
}


if ( $roster_conf['index_motd'] == 1 )
{
	print $memberlist->makeMotd();
}

echo $roster_menu->makeMenu('main');

if( $roster_conf['hspvp_list_disp'] == 'hide' )
{
	$pvp_hs_colapse=' style="display:none;"';
	$pvp_hs_image='plus';
}
else
{
	$pvp_hs_colapse='';
	$pvp_hs_image='minus';
}

echo "<table>\n  <tr>\n";

if ( $roster_conf['index_hslist'] == 1 )
{
	echo '    <td valign="top">';
	include_once( ROSTER_BASE.'hslist.php');
	echo "    </td>\n";
}

if ( $roster_conf['index_pvplist'] == 1 )
{
	echo '    <td valign="top">';
	include_once( ROSTER_BASE.'pvplist.php');
	echo "    </td>\n";
}

echo "  </tr>\n</table>\n";

echo $memberlist->makeFilterBox();

echo "<br />\n".border('syellow','start')."\n";
echo $memberlist->makeMembersList();
echo border('syellow','end');

// Print the update instructions
if( $roster_conf['index_update_inst'] )
{
	print "<br />\n\n<a name=\"update\"></a>\n";

	echo border('sgray','start',$wordings[$roster_conf['roster_lang']]['update_instructions']);
	echo '<div align="left" style="font-size:10px;">'.$wordings[$roster_conf['roster_lang']]['update_instruct'];

	if ($roster_conf['pvp_log_allow'] == 1)
	{
		echo $wordings[$roster_conf['roster_lang']]['update_instructpvp'];
	}
	echo '</div>'.border('sgray','end');
}

include_once (ROSTER_BASE.'roster_footer.tpl');

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

	$lang = $row['clientLocale'];

	$profs = explode(',',$row['professions']);
	foreach ( $profs as $prof)
	{
		$r_prof = explode('|',$prof);
		$toolTip = str_replace(':','/',$r_prof[1]);
		$toolTiph = $r_prof[0];

		if( $r_prof[0] == $wordings[$lang]['riding'] )
		{
			if( $row['class']==$wordings[$lang]['Paladin'] || $row['class']==$wordings[$lang]['Warlock'] )
			{
				$icon = $wordings[$lang]['ts_ridingIcon'][$row['class']];
			}
			else
			{
				$icon = $wordings[$lang]['ts_ridingIcon'][$row['race']];
			}
		}
		else
		{
			$icon = $wordings[$lang]['ts_iconArray'][$r_prof[0]];
		}

		// Don't add professions we don't have an icon for. This keeps other skills out.
		if ($icon != '') {
			$cell_value .= "<img class=\"membersRowimg\" width=\"".$roster_conf['index_iconsize']."\" height=\"".$roster_conf['index_iconsize']."\" src=\"".$roster_conf['interface_url'].'Interface/Icons/'.$icon.'.'.$roster_conf['img_suffix']."\" alt=\"\" ".makeOverlib($toolTip,$toolTiph,'',2,'',',RIGHT,WRAP')." />\n";
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
	global $roster_conf, $wordings;

	$tooltip='';
	if( !empty($row['note']) )
	{
		$prg_find = array('/"/','/&/','|\\>|','|\\<|',"/\\n/");
		$prg_rep  = array('&quot;','&amp;','&gt;','&lt;','<br />');

		$note = preg_replace($prg_find, $prg_rep, $row['note']);

		if( $roster_conf['compress_note'] )
		{
			$note = '<img src="'.$roster_conf['img_url'].'note.gif" style="cursor:help;" '.makeOverlib($note,$wordings[$roster_conf['roster_lang']]['note'],'',1,'',',WRAP').' alt="[]" />';
		}
	}
	else
	{
		$note = '&nbsp;';
		if( $roster_conf['compress_note'] )
		{
			$note = '<img src="'.$roster_conf['img_url'].'no_note.gif" alt="[]" />';
		}
	}

	return $note;
}

?>
