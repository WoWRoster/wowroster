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

define('IN_SORTMEMBER',true);

//---[ Check for Guild Info ]------------
if( empty($guild_info) )
{
	die_quietly( $act_words['nodata'] );
}

$mainQuery =
	'SELECT '.
	'`members`.`member_id`, '.
	'`members`.`name`, '.
	'`members`.`class`, '.
	'`members`.`level`, '.
	'`members`.`zone`, '.
	"(UNIX_TIMESTAMP( `members`.`last_online`)*1000+".($roster_conf['localtimeoffset']*3600000).") AS 'last_online_stamp', ".
	"DATE_FORMAT(  DATE_ADD(`members`.`last_online`, INTERVAL ".$roster_conf['localtimeoffset']." HOUR ), '".$act_words['timeformat']."' ) AS 'last_online', ".
	'`members`.`note`, '.

	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`guild_rank`, '.
	'`members`.`guild_title`, '.

	'`players`.`server`, '.
	'`players`.`race`, '.
	'`players`.`sex`, '.
	'`players`.`lifetimeRankName`, '.
	'`players`.`lifetimeHighestRank`, '.
	"IF( `players`.`lifetimeHighestRank` IS NULL OR `players`.`lifetimeHighestRank` = '0', 1, 0 ) AS 'risnull', ".
	'`players`.`exp`, '.
	'`players`.`clientLocale`, '.
	'`players`.`hearth`, '.
	"IF( `players`.`hearth` IS NULL OR `players`.`hearth` = '', 1, 0 ) AS 'hisnull', ".
	"UNIX_TIMESTAMP( `players`.`dateupdatedutc`) AS 'last_update_stamp', ".
	"DATE_FORMAT(  DATE_ADD(`players`.`dateupdatedutc`, INTERVAL ".$roster_conf['localtimeoffset']." HOUR ), '".$act_words['timeformat']."' ) AS 'last_update_format', ".
	"IF( `players`.`dateupdatedutc` IS NULL OR `players`.`dateupdatedutc` = '', 1, 0 ) AS 'luisnull', ".

	'`proftable`.`professions` '.

	'FROM `'.ROSTER_MEMBERSTABLE.'` AS members '.
	'LEFT JOIN `'.ROSTER_PLAYERSTABLE.'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	"LEFT JOIN (SELECT `member_id` , GROUP_CONCAT( CONCAT( `skill_name` , '|', `skill_level` ) ) AS 'professions' ".
		'FROM `'.ROSTER_SKILLSTABLE.'` '.
		'GROUP BY `member_id`) AS proftable ON `members`.`member_id` = `proftable`.`member_id` '.
	'WHERE `members`.`guild_id` = '.$guild_info['guild_id'].' '.
	'ORDER BY `members`.`level` DESC, `members`.`name` ASC';

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

if ( $roster_conf['index_title'] == 1 )
{
	$FIELD['guild_title'] = array (
		'lang_field' => 'title',
		'jsort' => 'guild_rank',
	);
}

if ( $roster_conf['index_currenthonor'] == 1 )
{
	$FIELD['lifetimeRankName'] = array (
		'lang_field' => 'currenthonor',
		'value' => 'honor_value',
	);
}

if ( $roster_conf['index_note'] == 1 && $roster_conf['compress_note'] == 0 )
{
	$FIELD['note'] = array (
		'lang_field' => 'note',
		'value' => 'note_value',
	);
}

if ( $roster_conf['index_prof'] == 1 )
{
	$FIELD['professions'] = array (
		'lang_field' => 'professions',
		'value' => 'tradeskill_icons',
	);
}

if ( $roster_conf['index_hearthed'] == 1 )
{
	$FIELD['hearth'] = array (
		'lang_field' => 'hearthed',
	);
}

if ( $roster_conf['index_zone'] == 1 )
{
	$FIELD['zone'] = array (
		'lang_field' => 'zone',
	);
}

if ( $roster_conf['index_lastonline'] == 1 )
{
	$FIELD['last_online'] = array (
		'lang_field' => 'lastonline',
		'jsort' => 'last_online_stamp',
	);
}

if ( $roster_conf['index_lastupdate'] == 1 )
{
	$FIELD['last_update_format'] = array (
		'lang_field' => 'lastupdate',
		'jsort' => 'last_update_stamp',
	);
}

if ( $roster_conf['index_note'] == 1 && $roster_conf['compress_note'] == 1 )
{
	$FIELD['note'] = array (
		'lang_field' => 'note',
		'value' => 'note_value',
	);
}
	
include_once ($addon['dir'].'inc/memberslist.php');

$memberlist = new memberslist;

$memberlist->prepareData($mainQuery, $FIELD, 'memberslist');

$html_head  = '<script type="text/javascript" src="addons/'.$addon['basename'].'/js/sorttable.js"></script>';
$html_head .= '<link rel="stylesheet" type="text/css" href="addons/'.$addon['basename'].'/default.css" />';

// Start output
if( $roster_conf['index_update_inst'] )
{
	print '            <a href="#update"><font size="4">'.$act_words['update_link'].'</font></a><br /><br />';
}


if ( $roster_conf['index_motd'] == 1 )
{
	print $memberlist->makeMotd();
}

$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');
$roster_show_menu = false;

echo "<table>\n  <tr>\n";

if ( $roster_conf['index_hslist'] == 1 )
{
	echo '    <td valign="top">';
	include_once( ROSTER_LIB.'hslist.php');
	echo "    </td>\n";
}

if ( $roster_conf['index_pvplist'] == 1 )
{
	echo '    <td valign="top">';
	include_once( ROSTER_LIB.'pvplist.php');
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

	echo border('sgray','start',$act_words['update_instructions']);
	echo '<div align="left" style="font-size:10px;">'.$act_words['update_instruct'];

	if ($roster_conf['pvp_log_allow'] == 1)
	{
		echo $act_words['update_instructpvp'];
	}
	echo '</div>'.border('sgray','end');
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
			$icon = isset($wordings[$lang]['ts_iconArray'][$r_prof[0]])?$wordings[$lang]['ts_iconArray'][$r_prof[0]]:'';
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
	global $roster_conf, $wordings, $act_words;

	$tooltip='';
	if( !empty($row['note']) )
	{
		$note = htmlspecialchars(nl2br($row['note']));

		if( $roster_conf['compress_note'] )
		{
			$note = '<img src="'.$roster_conf['img_url'].'note.gif" style="cursor:help;" '.makeOverlib($note,$act_words['note'],'',1,'',',WRAP').' alt="[]" />';
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

	return '<div style="display:none; ">'.$row['note'].'</div>'.$note;
}
