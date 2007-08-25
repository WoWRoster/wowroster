<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

include_once ($addon['dir'] . 'inc/memberslist.php');

$memberlist = new memberslist;

$mainQuery =
	'SELECT '.
	'`members`.`member_id`, '.
	'`members`.`name`, '.
	'`members`.`class`, '.
	'`members`.`level`, '.
	'`members`.`zone`, '.
	"(UNIX_TIMESTAMP( `members`.`last_online`)*1000+".($roster->config['localtimeoffset']*3600000).") AS 'last_online_stamp', ".
	"DATE_FORMAT(  DATE_ADD(`members`.`last_online`, INTERVAL ".$roster->config['localtimeoffset']." HOUR ), '".$roster->locale->act['timeformat']."' ) AS 'last_online', ".
	'`members`.`note`, '.
	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`guild_title`, '.

	'`alts`.`main_id`, '.

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
	"IF( `players`.`crit` IS NULL OR `players`.`crit` = '', 1, 0 ) AS 'cisnull', ".

	'`talenttable`.`talents` '.

	'FROM `'.$roster->db->table('members').'` AS members '.
	'INNER JOIN `'.$roster->db->table('players').'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	'LEFT JOIN `'.$roster->db->table('alts',$addon['basename']).'` AS alts ON `members`.`member_id` = `alts`.`member_id` '.

	"LEFT JOIN (SELECT `member_id` , GROUP_CONCAT( CONCAT( `tree` , '|', `pointsspent` , '|', `background` ) ORDER BY `order`) AS 'talents' ".
		'FROM `'.$roster->db->table('talenttree').'` '.
		'GROUP BY `member_id`) AS talenttable ON `members`.`member_id` = `talenttable`.`member_id` '.

	'WHERE `members`.`server` = "'.$roster->db->escape($roster->data['server']).'" '.
	'ORDER BY IF(`members`.`member_id` = `alts`.`member_id`,1,0), ';

$always_sort = ' `members`.`level` DESC, `members`.`name` ASC';

$FIELD['name'] = array(
	'lang_field' => 'name',
	'order'    => array( '`members`.`name` ASC' ),
	'order_d'    => array( '`members`.`name` DESC' ),
	'value' => array($memberlist,'name_value'),
	'js_type' => 'ts_string',
	'display' => 3,
);

$FIELD['class'] = array(
	'lang_field' => 'class',
	'order'    => array( '`members`.`class` ASC' ),
	'order_d'    => array( '`members`.`class` DESC' ),
	'value' => array($memberlist,'class_value'),
	'js_type' => 'ts_string',
	'display' => $addon['config']['stats_class'],
);

$FIELD['level'] = array(
	'lang_field' => 'level',
	'order_d'    => array( '`members`.`level` ASC' ),
	'value' => array($memberlist,'level_value'),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_level'],
);

$FIELD['stat_str_c'] = array (
	'lang_field' => 'strength',
	'order' => array( "`stat_str_c` DESC" ),
	'order_d' => array( "`stat_str_c` ASC" ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_str'],
);

$FIELD['stat_agl_c'] = array (
	'lang_field' => 'agility',
	'order' => array( "`stat_agl_c` DESC" ),
	'order_d' => array( "`stat_agl_c` ASC" ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_agi'],
);

$FIELD['stat_sta_c'] = array (
	'lang_field' => 'stamina',
	'order' => array( "`stat_sta_c` DESC" ),
	'order_d' => array( "`stat_sta_c` ASC" ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_sta'],
);

$FIELD['stat_int_c'] = array (
	'lang_field' => 'intellect',
	'order' => array( "`stat_int_c` DESC" ),
	'order_d' => array( "`stat_int_c` ASC" ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_int'],
);

$FIELD['stat_spr_c'] = array (
	'lang_field' => 'spirit',
	'order' => array( "`stat_spr_c` DESC" ),
	'order_d' => array( "`stat_spr_c` ASC" ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_spi'],
);

$FIELD['total'] = array (
	'lang_field' => 'total',
	'order' => array( "(`players`.`stat_int_c` + `players`.`stat_agl_c` + `players`.`stat_sta_c` + `players`.`stat_str_c` + `players`.`stat_spr_c`) DESC" ),
	'order_d' => array( "(`players`.`stat_int_c` + `players`.`stat_agl_c` + `players`.`stat_sta_c` + `players`.`stat_str_c` + `players`.`stat_spr_c`) ASC" ),
	'value' => 'total_value',
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_sum'],
);

$FIELD['health'] = array (
	'lang_field' => 'health',
	'order' => array( 'hisnull','`players`.`health` DESC' ),
	'order_d' => array( 'hisnull','`players`.`health` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_health'],
);

$FIELD['mana'] = array(
	'lang_field' => 'mana',
	'order' => array( 'misnull','`players`.`mana` DESC' ),
	'order_d' => array( 'misnull','`players`.`mana` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_mana'],
);

$FIELD['stat_armor_c'] = array(
	'lang_field' => 'armor',
	'order' => array( 'aisnull','`players`.`stat_armor_c` DESC' ),
	'order_d' => array( 'aisnull','`players`.`stat_armor_c` ASC' ),
	'value' => 'armor_value',
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_armor'],
);


$FIELD['dodge'] = array(
	'lang_field' => 'dodge',
	'order' => array( 'disnull','`players`.`dodge` DESC' ),
	'order_d' => array( 'disnull','`players`.`dodge` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_dodge'],
);

$FIELD['parry'] = array(
	'lang_field' => 'parry',
	'order' => array( 'pisnull','`players`.`parry` DESC' ),
	'order_d' => array( 'pisnull','`players`.`parry` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_parry'],
);

$FIELD['block'] = array(
	'lang_field' => 'block',
	'order' => array( 'bisnull','`players`.`block` DESC' ),
	'order_d' => array( 'bisnull','`players`.`block` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_block'],
);

$FIELD['crit'] = array(
	'lang_field' => 'crit',
	'order' => array( 'cisnull','`players`.`crit` DESC' ),
	'order_d' => array( 'cisnull','`players`.`crit` ASC' ),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_crit'],
);

$memberlist->prepareData($mainQuery, $always_sort, $FIELD, 'memberslist');

$menu = '';
// Start output
if( $addon['config']['stats_update_inst'] )
{
	$roster->output['before_menu'] .= '<a href="' . makelink('#update') . '"><span style="font-size:20px;">'.$roster->locale->act['update_link'].'</span></a><br /><br />';
}

echo $memberlist->makeFilterBox();

echo $memberlist->makeToolBar('horizontal');

echo "<br />\n".border('syellow','start')."\n";
echo $memberlist->makeMembersList();
echo border('syellow','end');

// Print the update instructions
if( $addon['config']['stats_update_inst'] )
{
	print "<br />\n\n<a name=\"update\"></a>\n";

	echo border('sgray','start',$roster->locale->act['update_instructions']);
	echo '<div align="left" style="font-size:10px;background-color:#1F1E1D;">'.sprintf($roster->locale->act['update_instruct'], $roster->config['uploadapp'], $roster->locale->act['index_text_uniloader'], $roster->config['profiler'], makelink('update'), $roster->locale->act['lualocation']);
	echo '</div>'.border('sgray','end');
}

/**
 * Controls Output of the Total Stats value Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function total_value( $row )
{
	if( $row['stat_int_c'] )
	{
		$cell_value = '<div>'.($row['stat_int_c'] + $row['stat_agl_c'] + $row['stat_sta_c'] + $row['stat_str_c'] + $row['stat_spr_c']).'</div>';
	}
	else
	{
		$cell_value =  '&nbsp;';
	}

	return $cell_value;
}

/**
 * Controls Output of the Armor Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function armor_value( $row )
{
	global $roster;

	$cell_value = '&nbsp;';

	if( !empty($row['clientLocale']) )
	{
		$lang = $row['clientLocale'];
	}
	else
	{
		$lang = $roster->config['locale'];
	}

	// Configurlate armor is player has it
	if( $row['stat_armor_c'] )
	{
		$base = $row['stat_armor'];
		$current = $row['stat_armor_c'];
		$buff = $row['stat_armor_b'];

		if( $buff == 0 )
		{
			$color = 'white';
			$mod_symbol = '';
		}
		elseif( $buff < 0 )
		{
			$color = 'purple';
			$mod_symbol = '-';
		}
		else
		{
			$color = 'green';
			$mod_symbol = '+';
		}


		$name = $roster->locale->wordings[$lang]['armor'];
		if( !empty($row['mitigation']) )
		{
			$tooltip = sprintf($roster->locale->wordings[$lang]['armor_tooltip'],$row['mitigation']);
		}
		else
		{
			$tooltip = '';
		}

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
		$clean_line = str_replace("'", "\\'", $line);
		$clean_line = str_replace('"','&quot;', $clean_line);
		$clean_line = str_replace('(',"\\(", $clean_line);
		$clean_line = str_replace(')',"\\)", $clean_line);
		$clean_line = str_replace('<','&lt;', $clean_line);
		$clean_line = str_replace('>','&gt;', $clean_line);

		$cell_value  = '<span style="cursor:help;" onmouseover="return overlib(\''.$clean_line.'\');" onmouseout="return nd();">';
		$cell_value .= '<strong class="'.$color.'">'.$current.'</strong>';
		$cell_value .= '</span>';
	}
	return "<div style='display:none;'>".$current."</div>".$cell_value;
}
