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
	'`members`.`note`, '.
	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`officer_note`, '.
	"IF( `members`.`officer_note` IS NULL OR `members`.`officer_note` = '', 1, 0 ) AS 'onisnull', ".
	
	'`alts`.`main_id`, '.
	'`alts`.`alt_type`, '.
	
	'`mains`.`name` AS main_name '.

	'FROM `'.ROSTER_MEMBERSTABLE.'` AS members '.
	'LEFT JOIN `'.ROSTER_ALT_TABLE.'` AS alts ON `members`.`member_id` = `alts`.`member_id` '.
	'LEFT JOIN `'.ROSTER_MEMBERSTABLE.'` AS mains ON `alts`.`main_id` = `mains`.`member_id` '.
	'WHERE `members`.`guild_id` = "'.$guild_info['guild_id'].'" '.
	'ORDER BY IF(`members`.`member_id` = `alts`.`member_id`,1,0), ';

$FIELD['name'] = array (
	'lang_field' => 'name',
	'order'    => array( '`members`.`name` ASC' ),
	'order_d'    => array( '`members`.`name` DESC' ),
	'display' => 3,
);

$FIELD['main_name'] = array (
	'lang_field' => 'main_name',
	'order'    => array( '`mains`.`name` ASC' ),
	'order_d'    => array( '`mains`.`name` DESC' ),
	'display' => 3,
);

$FIELD['alt_type'] = array (
	'lang_field' => 'alt_type',
	'order'    => array('`alts`.`alt_type` ASC' ),
	'order_d'    => array('`alts`.`alt_type` DESC' ),
	'display' => 3,
);

$FIELD['note'] = array (
	'lang_field' => 'note',
	'order' => array( 'nisnull','`members`.`note` ASC' ),
	'order_d' => array( 'nisnull','`members`.`note` DESC' ),
	'display' => 3,
);

$FIELD['officer_note'] = array (
	'lang_field' => 'officer_note',
	'order' => array( 'onisnull','`members`.`note` ASC' ),
	'order_d' => array( 'onisnull','`members`.`note` DESC' ),
	'display' => 3,
);

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
	echo '<div align="left" style="font-size:10px;background-color:#1F1E1D;">'.sprintf($act_words['update_instruct'], $roster_conf['uploadapp'], $act_words['index_text_uniloader'], $roster_conf['profiler'], makelink('update'), $act_words['lualocation']);

	if ($roster_conf['pvp_log_allow'] == 1)
	{
		echo sprintf($act_words['update_instructpvp'], $roster_conf['pvplogger']);
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
	global $wowdb, $roster_conf, $wordings, $addon;

	$cell_value ='';

	// Don't proceed for characters without data
	if ($row['clientLocale'] == '')
	{
		return '&nbsp;';
	}

	$lang = $row['clientLocale'];

	$profs = explode(',',$row['professions']);
	foreach ( $profs as $prof )
	{
		$r_prof = explode('|',$prof);
		$toolTip = (isset($r_prof[1]) ? str_replace(':','/',$r_prof[1]) : '');
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
			$cell_value .= "<img class=\"membersRowimg\" width=\"".$addon['config']['icon_size']."\" height=\"".$addon['config']['icon_size']."\" src=\"".$roster_conf['interface_url'].'Interface/Icons/'.$icon.'.'.$roster_conf['img_suffix']."\" alt=\"\" ".makeOverlib($toolTip,$toolTiph,'',2,'',',RIGHT,WRAP')." />\n";
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
function note_value ( $row, $field )
{
	global $roster_conf, $wordings, $act_words, $addon;

	$tooltip='';
	if( !empty($row[$field]) )
	{
		$note = htmlspecialchars(nl2br($row[$field]));

		if( $addon['config']['compress_note'] )
		{
			$note = '<img src="'.$roster_conf['img_url'].'note.gif" style="cursor:help;" '.makeOverlib($note,$act_words['note'],'',1,'',',WRAP').' alt="[]" />';
		}
	}
	else
	{
		$note = '&nbsp;';
		if( $addon['config']['compress_note'] )
		{
			$note = '<img src="'.$roster_conf['img_url'].'no_note.gif" alt="[]" />';
		}
	}

	return '<div style="display:none; ">'.$row['note'].'</div>'.$note;
}
