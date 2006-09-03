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

//---[ Check for Guild Info ]------------
$guild_info = $wowdb->get_guild_info($roster_conf['server_name'],$roster_conf['guild_name']);
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}
// Get guild info from guild info check above
$guildId = $guild_info['guild_id'];
$guildMOTD = $guild_info['guild_motd'];
$guildFaction = $guild_info['faction'];

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
	'`players`.`stat_spr_c`, '.
	'`players`.`health`, '.
	"IF( `players`.`health` IS NULL OR `players`.`health` = '', 1, 0 ) AS 'hisnull', ".
	'`players`.`mana`, '.
	"IF( `players`.`mana` IS NULL OR `players`.`mana` = '', 1, 0 ) AS 'misnull', ".
	'`players`.`stat_armor`, '.
	'`players`.`stat_armor_c`, '.
	'`players`.`stat_armor_b`, '.
	'`players`.`stat_armor_d`, '.
	"IF( `players`.`stat_armor_c` IS NULL OR `players`.`stat_armor_c` = '', 1, 0 ) AS 'aisnull', ".
	'`players`.`mitigation`, '.
	'`players`.`dodge`, '.
	"IF( `players`.`dodge` IS NULL OR `players`.`dodge` = '', 1, 0 ) AS 'disnull', ".
	'`players`.`parry`, '.
	"IF( `players`.`parry` IS NULL OR `players`.`parry` = '', 1, 0 ) AS 'pisnull', ".
	'`players`.`block`, '.
	"IF( `players`.`block` IS NULL OR `players`.`block` = '', 1, 0 ) AS 'bisnull', ".
	'`players`.`crit`, '.
	"IF( `players`.`crit` IS NULL OR `players`.`crit` = '', 1, 0 ) AS 'cisnull' ".
	'FROM `'.ROSTER_MEMBERSTABLE.'` AS members '.
	'INNER JOIN `'.ROSTER_PLAYERSTABLE.'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	'WHERE `members`.`guild_id` = 1 '.
	'ORDER BY `members`.`level` DESC, `members`.`name` ASC';


$FIELD['name'] = array(
	'lang_field' => 'name',
	'required' => true,
	'default'  => true,
	'value' => 'name_value',
);

$FIELD['class'] = array(
	'lang_field' => 'class',
	'default'  => true,
	'value' => 'class_value',
);

$FIELD['level'] = array(
	'lang_field' => 'level',
	'default'  => true,
	'value' => 'level_value',
);

$FIELD['stat_int_c'] = array (
	'lang_field' => 'intellect',
);

$FIELD['stat_agl_c'] = array (
	'lang_field' => 'agility',
);

$FIELD['stat_sta_c'] = array (
	'lang_field' => 'stamina',
);

$FIELD['stat_str_c'] = array (
	'lang_field' => 'strength',
);

$FIELD['stat_spr_c'] = array (
	'lang_field' => 'spirit',
);

$FIELD['total'] = array (
	'lang_field' => 'total',
	'value' => 'total_value',
);

$FIELD['health'] = array (
	'lang_field' => 'health',
);

$FIELD['mana'] = array(
	'lang_field' => 'mana',
);

$FIELD['stat_armor_c'] = array(
	'lang_field' => 'armor',
	'value' => 'armor_value',
);


$FIELD['dodge'] = array(
	'lang_field' => 'dodge',
);

$FIELD['parry'] = array(
	'lang_field' => 'parry',
);

$FIELD['block'] = array(
	'lang_field' => 'block',
);

$FIELD['crit'] = array(
	'lang_field' => 'crit',
);


include_once (ROSTER_LIB.'memberslist.php');

$memberlist = new memberslist;

$memberlist->prepareData($mainQuery, $FIELD, 'memberslist');

$more_css = '<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/sorttable.js"></script>';

// Start output
include_once (ROSTER_BASE.'roster_header.tpl');

include_once (ROSTER_LIB.'menu.php');

echo $memberlist->makeFilterBox();
echo $memberlist->makeMembersList();

include_once (ROSTER_BASE.'roster_footer.tpl');

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
		$cell_value = '<div>'.($row['stat_int_c'] + $row['stat_agl_c'] + $row['stat_sta_c'] + $row['stat_str_c'] + $row['stat_spr_c']).'</div>';
	else
		$cell_value =  '&nbsp;';

	return $cell_value;
}

/**
 * Controls Output of the Armor Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function armor_value ( $row )
{
	global $wowdb, $roster_conf, $wordings;

	$cell_value = '&nbsp;';

	if( !empty($row['clientLocale']) )
		$lang = $row['clientLocale'];
	else
		$lang = $roster_conf['roster_lang'];

	// Configurlate armor is player has it
	if( $row['stat_armor_c'] )
	{
		$base = $row['stat_armor'];
		$current = $row['stat_armor_c'];
		$buff = $row['stat_armor_b'];
		$debuff = $row['stat_armor_d'];

		if( $buff == 0 )
		{
			$color = 'white';
			$mod_symbol = '';
		}
		else if( $buff < 0 )
		{
			$color = 'purple';
			$mod_symbol = '-';
		}
		else
		{
			$color = 'green';
			$mod_symbol = '+';
		}


		$name = $wordings[$lang]['armor'];
		if( !empty($row['mitigation']) )
			$tooltip = '<span class="red">'.$wordings[$lang]['tooltip_damage_reduction'].': '.$row['mitigation'].'%</span>';

		if( $mod_symbol == '' )
		{
			$tooltipheader = $name.' '.$current;
		}
		else
		{
			$tooltipheader = "$name $current ($base <span class=\"$color\">$mod_symbol $buff</span>)";
		}

		$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';
		$clean_line = str_replace("'", "\'", $line);
		$clean_line = str_replace('"','&quot;', $clean_line);
		$clean_line = str_replace('(',"\(", $clean_line);
		$clean_line = str_replace(')',"\)", $clean_line);
		$clean_line = str_replace('<','&lt;', $clean_line);
		$clean_line = str_replace('>','&gt;', $clean_line);

		$cell_value  = '<span style="cursor:help;" onmouseover="return overlib(\''.$clean_line.'\');" onmouseout="return nd();">';
		$cell_value .= '<strong class="'.$color.'">'.$current.'</strong>';
		$cell_value .= '</span>';
	}
	return "<div style='display:none;'>".$current."</div>".$cell_value;
}

?>
