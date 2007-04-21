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
	'`members`.`guild_title`, '.

	'`players`.`server`, '.
	'`players`.`race`, '.
	'`players`.`sex`, '.
	'`players`.`exp`, '.
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
	'WHERE `members`.`guild_id` = '.$guild_info['guild_id'].' '.
	'ORDER BY `members`.`level` DESC, `members`.`name` ASC';


$FIELD['name'] = array(
	'lang_field' => 'name',
	'order'    => array( '`members`.`name` ASC' ),
	'order_d'    => array( '`members`.`name` DESC' ),
	'value' => 'name_value',
);

$FIELD['class'] = array(
	'lang_field' => 'class',
	'order'    => array( '`members`.`class` ASC' ),
	'order_d'    => array( '`members`.`class` DESC' ),
	'value' => 'class_value',
);

$FIELD['level'] = array(
	'lang_field' => 'level',
	'order_d'    => array( '`members`.`level` ASC' ),
	'value' => 'level_value',
);

$FIELD['stat_int_c'] = array (
	'lang_field' => 'intellect',
	'order' => array( "`stat_int_c` DESC" ),
	'order_d' => array( "`stat_int_c` ASC" ),
);

$FIELD['stat_agl_c'] = array (
	'lang_field' => 'agility',
	'order' => array( "`stat_agl_c` DESC" ),
	'order_d' => array( "`stat_agl_c` ASC" ),
);

$FIELD['stat_sta_c'] = array (
	'lang_field' => 'stamina',
	'order' => array( "`stat_sta_c` DESC" ),
	'order_d' => array( "`stat_sta_c` ASC" ),
);

$FIELD['stat_str_c'] = array (
	'lang_field' => 'strength',
	'order' => array( "`stat_str_c` DESC" ),
	'order_d' => array( "`stat_str_c` ASC" ),
);

$FIELD['stat_spr_c'] = array (
	'lang_field' => 'spirit',
	'order' => array( "`stat_spr_c` DESC" ),
	'order_d' => array( "`stat_spr_c` ASC" ),
);

$FIELD['total'] = array (
	'lang_field' => 'total',
	'order' => array( "(`players`.`stat_int_c` + `players`.`stat_agl_c` + `players`.`stat_sta_c` + `players`.`stat_str_c` + `players`.`stat_spr_c`) DESC" ),
	'order_d' => array( "(`players`.`stat_int_c` + `players`.`stat_agl_c` + `players`.`stat_sta_c` + `players`.`stat_str_c` + `players`.`stat_spr_c`) ASC" ),
	'value' => 'total_value',
);

$FIELD['health'] = array (
	'lang_field' => 'health',
	'order' => array( 'hisnull','`players`.`health` DESC' ),
	'order_d' => array( 'hisnull','`players`.`health` ASC' ),
);

$FIELD['mana'] = array(
	'lang_field' => 'mana',
	'order' => array( 'misnull','`players`.`mana` DESC' ),
	'order_d' => array( 'misnull','`players`.`mana` ASC' ),
);

$FIELD['stat_armor_c'] = array(
	'lang_field' => 'armor',
	'order' => array( 'aisnull','`players`.`stat_armor_c` DESC' ),
	'order_d' => array( 'aisnull','`players`.`stat_armor_c` ASC' ),
	'value' => 'armor_value',
);


$FIELD['dodge'] = array(
	'lang_field' => 'dodge',
	'order' => array( 'disnull','`players`.`dodge` DESC' ),
	'order_d' => array( 'disnull','`players`.`dodge` ASC' ),
);

$FIELD['parry'] = array(
	'lang_field' => 'parry',
	'order' => array( 'pisnull','`players`.`parry` DESC' ),
	'order_d' => array( 'pisnull','`players`.`parry` ASC' ),
);

$FIELD['block'] = array(
	'lang_field' => 'block',
	'order' => array( 'bisnull','`players`.`block` DESC' ),
	'order_d' => array( 'bisnull','`players`.`block` ASC' ),
);

$FIELD['crit'] = array(
	'lang_field' => 'crit',
	'order' => array( 'cisnull','`players`.`crit` DESC' ),
	'order_d' => array( 'cisnull','`players`.`crit` ASC' ),
);


include_once ($addon['dir'].'inc/memberslist.php');

$memberlist = new memberslist;

$memberlist->prepareData($mainQuery, $FIELD, 'memberslist');

$html_head  = '<script type="text/javascript" src="addons/'.$addon['basename'].'/js/sorttable.js"></script>';
$html_head .= '<link rel="stylesheet" type="text/css" href="addons/'.$addon['basename'].'/default.css" />';

// Start output
$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');
$roster_show_menu = false;

echo $memberlist->makeFilterBox();

echo "<br />\n".border('syellow','start')."\n";
echo $memberlist->makeMembersList();
echo border('syellow','end');

/**
 * Controls Output of the Total Stats value Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function total_value ( $row )
{
	global $wowdb, $roster_conf;

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
