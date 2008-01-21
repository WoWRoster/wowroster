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
 * @package    IntanceKeys
 */

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if( !active_addon('memberslist') )
{
	// Memberslist not installed. Just die for now.
	roster_die( "You will need to install memberslist to use the keys addon display component" );
}

$memberslist_addon = getaddon('memberslist');

// Include addon's locale files if they exist
foreach( $roster->multilanguages as $lang )
{
	$roster->locale->add_locale_file($memberslist_addon['locale_dir'] . $lang . '.php',$lang);
}

include_once ($memberslist_addon['dir'] . 'inc/memberslist.php');
include_once (ROSTER_LIB . 'item.php');
$memberlist = new memberslist(array(), $memberslist_addon);

// First define static data
$mainSelect =
	'SELECT '.
	'`members`.`member_id`, '.
	'`members`.`name`, '.
	'`members`.`class`, '.
	'`members`.`level`, '.
	'`members`.`zone`, '.
	'`members`.`online`, '.
	'`members`.`last_online`, '.
	"UNIX_TIMESTAMP(`members`.`last_online`) AS 'last_online_stamp', ".
	"DATE_FORMAT(  DATE_ADD(`members`.`last_online`, INTERVAL ".$roster->config['localtimeoffset']." HOUR ), '".$roster->locale->act['timeformat']."' ) AS 'last_online_format', ".
	'`members`.`note`, '.
	'`members`.`guild_title`, '.

	'`alts`.`main_id`, '.

	'`guild`.`update_time`, '.

	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`officer_note`, '.
	"IF( `members`.`officer_note` IS NULL OR `members`.`officer_note` = '', 1, 0 ) AS 'onisnull', ".
	'`members`.`guild_rank`, '.

	'`players`.`server`, '.
	'`players`.`race`, '.
	'`players`.`sex`, '.
	'`players`.`exp`, '.
	'`players`.`clientLocale`, '.

	'`players`.`lifetimeRankName`, '.
	'`players`.`lifetimeHighestRank`, '.
	"IF( `players`.`lifetimeHighestRank` IS NULL OR `players`.`lifetimeHighestRank` = '0', 1, 0 ) AS 'risnull', ".
	'`players`.`hearth`, '.
	"IF( `players`.`hearth` IS NULL OR `players`.`hearth` = '', 1, 0 ) AS 'hisnull', ".
	"UNIX_TIMESTAMP( `players`.`dateupdatedutc`) AS 'last_update_stamp', ".
	"DATE_FORMAT(  DATE_ADD(`players`.`dateupdatedutc`, INTERVAL ".$roster->config['localtimeoffset']." HOUR ), '".$roster->locale->act['timeformat']."' ) AS 'last_update_format', ".
	"IF( `players`.`dateupdatedutc` IS NULL OR `players`.`dateupdatedutc` = '', 1, 0 ) AS 'luisnull', ";

$mainTables =
	'FROM `'.$roster->db->table('members').'` AS members '.
	'LEFT JOIN `'.$roster->db->table('alts',$memberslist_addon['basename']).'` AS alts ON `members`.`member_id` = `alts`.`member_id` '.
	'INNER JOIN `'.$roster->db->table('players').'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	'INNER JOIN `'.$roster->db->table('guild').'` AS guild ON `members`.`guild_id` = `guild`.`guild_id` '.
	'INNER JOIN `'.$roster->db->table('keycache',$addon['basename']).'` AS keycache ON `members`.`member_id` = `keycache`.`member_id` '.
	'WHERE `members`.`guild_id` = "'.$roster->data['guild_id'].'" '.
	'GROUP BY `members`.`member_id` '.
	'ORDER BY ';

$always_sort = ' `members`.`level` DESC, `members`.`name` ASC';

$FIELD['name'] = array (
	'lang_field' => 'name',
	'order'    => array( '`members`.`name` ASC' ),
	'order_d'    => array( '`members`.`name` DESC' ),
	'value' => array($memberlist,'name_value'),
	'js_type' => 'ts_string',
	'display' => 3,
);

$FIELD['class'] = array (
	'lang_field' => 'class',
	'order'    => array( '`members`.`class` ASC' ),
	'order_d'    => array( '`members`.`class` DESC' ),
	'value' => array($memberlist,'class_value'),
	'js_type' => 'ts_string',
	'display' => 2
);

$FIELD['level'] = array (
	'lang_field' => 'level',
	'order_d'    => array( '`members`.`level` ASC' ),
	'value' => array($memberlist,'level_value'),
	'js_type' => 'ts_number',
	'display' => 2
);

// For each key, we get two extra database columns and an extra FIELD
foreach( $roster->locale->act['inst_keys'][substr($roster->data['faction'],0,1)] as $key_name => $key_data )
{
	$mainSelect .=
		'GROUP_CONCAT( IF( `keycache`.`key_name` = \'' . $key_name . '\', `stage`, NULL) ) AS `' . $key_name . '_stages`, ' .
		'MAX( IF( `keycache`.`key_name` = \'' . $key_name . '\', `stage`, NULL) ) AS `' . $key_name . '_latest`, ';
	$FIELD[$key_name] = array(
		'lang_field' => $key_name,
		'order' => array( '`keycache`.`' . $key_name . '_latest` ASC' ),
		'order_d' => array( '`keycache`.`' . $key_name . '_latest` ASC' ),
		'value' => 'key_value',
		'js_type' => 'ts_number',
		'display' => 3
	);
}

// Combine the main query. The '1' is to fix the trailing comma for the fields list.
$mainQuery = $mainSelect . '1 ' . $mainTables;

$memberlist->prepareData($mainQuery, $always_sort, $FIELD, 'keyslist');

// Start output
echo $memberlist->makeFilterBox();

echo $memberlist->makeToolBar('horizontal');

echo "<br />\n".border('syellow','start')."\n";
echo $memberlist->makeMembersList();
echo border('syellow','end');

// Key display logic
function key_value( $row, $field )
{
	global $roster, $addon;
	static $keycache = array();

	$key_data = $roster->locale->act['inst_keys'][substr($roster->data['faction'],0,1)][$field];
	if( $row[$field . '_stages'] === null )
	{
		return '&nbsp;';
	}
	else
	{
		$active_stages = explode(',', $row[$field . '_stages']);
	}

	$num_stages = count($key_data);
	$num_completed_stages = 0;

	$depth = 0;
	$completed[0] = false;

	$tooltip_h = $field;

	$tooltip = '';

	for( $id = $num_stages - 1; $id >= 0; $id-- )
	{
		if( $key_data[$id]['marker'] == 'DRAIN' || $key_data[$id]['marker'] == 'SINGLE' )
		{
			$depth++;
			$completed[$depth] = $completed[$depth - 1];

			$tooltip = '</div>' . $tooltip;
		}

		if( in_array( $id, $active_stages ) )
		{
			$tipline = '<span style="color:' . $addon['config']['colorcur'] . ';">' . $key_data[$id]['value'] . '</span><br />';
			$completed[$depth] = true;
			if( $key_data[$id]['active'] )
			{
				$num_completed_stages++;
			}
		}
		elseif( $completed[$depth] )
		{
			$tipline = '<span style="color:' . $addon['config']['colorcmp'] . ';">' . $key_data[$id]['value'] . '</span><br />';
			$num_completed_stages++;
		}
		else
		{
			$tipline = '<span style="color:' . $addon['config']['colorno'] . ';">' . $key_data[$id]['value'] . '</span><br />';
		}

		if( $key_data[$id]['marker'] == 'SOURCE' || $key_data[$id]['marker'] == 'SINGLE' )
		{
			if( $depth == 0 )
			{
				$error = 'key_syntax';
			}

			if( $completed[$depth] )
			{
				$completed[$depth-1] = true;
			}
			$depth--;
			$tipline = '<div class="keyindent">' . $tipline;
		}

		$tooltip = $tipline . $tooltip;
	}

	// Set up progress mouseover and item link clickable tooltips
	$linktip = '';
	$linktipid = 'keys_' . $field . '_' . $row['member_id'];

	foreach( $roster->locale->act['itemlinks'] as $ikey => $ilink )
	{
		$linktip .= '<a href="' . $ilink . urlencode(utf8_decode(stripslashes($key_data[$num_stages - 1]['value']))) . '" target="_blank">' . $ikey . '</a><br />';
	}
	setTooltip($linktipid, $linktip);
	setTooltip('itemlink', $roster->locale->act['itemlink']);

	$linktip = ' onclick="return overlib(overlib_' . $linktipid . ',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

	$tooltip = '<div style="cursor:pointer;" '.makeOverlib($tooltip,$tooltip_h,'',2).$linktip.'>';

	if( in_array( $num_stages - 1, $active_stages ) )
	{
		if( !isset( $keycache[$field] ) )
		{
			$key = $key_data[$num_stages - 1];

			if( $key['type'] == 'Ii' )
			{
				$query = "SELECT * FROM `" . $roster->db->table('items') . "` WHERE `item_id` LIKE '" . $key['value'] . ":%' LIMIT 0,1;";
			}
			elseif( $key['type'] == 'In' )
			{
				$query = "SELECT * FROM `" . $roster->db->table('items') . "` WHERE `item_name` = '" . $key['value'] . "' LIMIT 0,1;";
			}
			$result = $roster->db->query($query);
			$item = $roster->db->fetch($result);
			$roster->db->free_result($result);
			
			$item = new item( $item, 'simple' );
			$keycache[$field] = $item->out();
		}

		$output = $keycache[$field];
	}
	else
	{
		$perc_done = round($num_completed_stages / $num_stages * 100);
		$output = 
			'<div class="levelbarParent" style="width:40px;"><div class="levelbarChild">' . $num_completed_stages . '/' . $num_stages . '</div></div>' . "\n" .
			'<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="40">' . "\n" .
			'<tr>' . "\n" .
			'<td style="background-image: url(\'' . $roster->config['img_url'] . 'expbar-var2.gif\');" width="' . $perc_done . '%">' . "\n" .
			'<img src="' . $roster->config['img_url'] . 'pixel.gif" height="14" width="1" alt="" />' . "\n" .
			'</td>' . "\n" .
			'<td width="' . (100 - $perc_done) . '%"></td>' . "\n" .
			'</tr>' . "\n" .
			'</table>' . "\n";
	}
	
	return '<div style="display:none; ">'.$num_completed_stages.'</div>'.$tooltip.$output.'</div>';
}
