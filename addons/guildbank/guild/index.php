<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Shows items for every guild bank character
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    GuildBank
 */

# guildbank.php -- display items held by a guild's banker characters.
# Copyright 2005 vaccafoeda.hellscream@gmail.com
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the Affero General Public License as published by
# Affero, Incorporated; either version 1, or (at your option) any later
# version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# Affero General Public License for more details.
#
# You should have received a copy of the Affero General Public License
# along with this program; if not, download it from http://www.affero.org/


// Multiple edits done for WoWRoster


if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

include (ROSTER_LIB . 'item.php');

if( isset($_GET['mode']) )
{
	$gbank_mode = ($_GET['mode'] == 'inv' ? '2' : '1');
	$gbank_mode = ($_GET['mode'] == 'full' ? '1' : '2');
}
else
{
	$gbank_mode = $addon['config']['guildbank_ver'];
}

$columns = ($gbank_mode == '2' ? '15' : '2');

$roster->output['title'] = $roster->locale->act['guildbank'];

$roster->tpl->assign_vars(array(
	'U_FULL' => makelink('&amp;mode=full'),
	'U_INV' => makelink('&amp;mode=inv'),

	'S_MONEY' => (bool)$addon['config']['bank_money'],
	'S_INFO_ADDON' => active_addon('info'),

	'S_COLUMNS' => $columns,
	'S_MODE' => $gbank_mode,

	'L_GUILDBANK' => $roster->locale->act['guildbank'],
	'L_LIST' => $roster->locale->act['gbank_list'],
	'L_INV' => $roster->locale->act['gbank_inv'],
	'L_TOTAL_MONEY' => $roster->locale->act['guildbank_totalmoney'],
	'L_LAST_UPDATED' => $roster->locale->act['lastupdate']
));

$muleNameQuery = "SELECT m.member_id, m.name AS member_name, m.note AS member_note, m.officer_note AS member_officer_note, p.money_g AS gold, p.money_s AS silver, p.money_c AS copper, p.clientLocale, p.dateupdatedutc" . " FROM `" . $roster->db->table('players') . "` AS p, `" . $roster->db->table('members') . "`  AS m" . " WHERE m." . $addon['config']['banker_fieldname'] . " LIKE '%" . $addon['config']['banker_rankname'] . "%' AND p.member_id = m.member_id AND m.guild_id = " . $roster->data['guild_id'] . " ORDER BY m.name;";

$muleNames = $roster->db->query($muleNameQuery);

if( $addon['config']['bank_money'] )
{
	$mulemoney = $roster->db->fetch($roster->db->query("SELECT SUM( p.money_g ) AS gold, SUM( p.money_s ) AS silver, SUM( p.money_c ) AS copper" . " FROM `" . $roster->db->table('players') . "` AS p, `" . $roster->db->table('members') . "` AS m" . " WHERE m." . $addon['config']['banker_fieldname'] . " LIKE '%" . $addon['config']['banker_rankname'] . "%'" . " AND p.member_id = m.member_id AND m.guild_id = " . $roster->data['guild_id'] . " ORDER  BY m.name;"));
	$addsilver = 0;
	if( $mulemoney['copper'] >= 100 )
	{
		$mulemoney['copper'] = $mulemoney['copper'] / 100;
		$addsilver = (int)$mulemoney['copper'];
		$mulemoney['copper'] = explode('.', $mulemoney['copper']);
		$mulemoney['copper'] = $mulemoney['copper'][1];
	}
	$mulemoney['silver'] = $mulemoney['silver'] + $addsilver;
	$addgold = 0;
	if( $mulemoney['silver'] >= 100 )
	{
		$mulemoney['silver'] = $mulemoney['silver'] / 100;
		$addgold = (int)$mulemoney['silver'];
		$mulemoney['silver'] = explode('.', $mulemoney['silver']);
		$mulemoney['silver'] = $mulemoney['silver'][1];
	}
	$mulemoney['gold'] = $mulemoney['gold'] + $addgold;

	$roster->tpl->assign_vars(array(
		'MONEY_G' => $mulemoney['gold'],
		'MONEY_S' => $mulemoney['silver'],
		'MONEY_C' => $mulemoney['copper']
	));
}

while( $muleRow = $roster->db->fetch($muleNames) )
{
	$roster->tpl->assign_block_vars('bankers', array(
		'NAME' => $muleRow['member_name'],
		'LINK' => makelink('&amp;mode=' . ($gbank_mode == 1 ? 'full' : 'inv') . '#c_' . $muleRow['member_id'])
	));

	// Parse the note field for possible html characters
	$prg_find = array(
		'/"/',
		'/&/',
		'|\\>|',
		'|\\<|',
		"/\\n/"
	);
	$prg_rep = array(
		'&quot;',
		'&amp;',
		'&gt;',
		'&lt;',
		'<br />'
	);

	$note = preg_replace($prg_find, $prg_rep, $muleRow['member_note']);

	$date_char_data_updated = date_char_updated($muleRow['dateupdatedutc']);

	$itemsOnMuleQuery = "SELECT i.*,LEFT(i.item_id, (LOCATE(':',i.item_id)-1)) AS real_itemid,sum(i.item_quantity) AS total_quantity" . " FROM `" . $roster->db->table('items') . "` AS i" . " WHERE " . $muleRow['member_id'] . " = i.member_id" . " AND i.item_parent!='bags'" . " AND i.item_parent!='equip'" . " AND (i.item_tooltip" . " NOT LIKE '%" . $roster->locale->wordings[$muleRow['clientLocale']]['tooltip_soulbound'] . "%'" . " OR i.item_tooltip" . " LIKE '%" . $roster->locale->wordings[$muleRow['clientLocale']]['tooltip_boe'] . "%')" . " GROUP BY real_itemid" . " ORDER BY i.item_name";

	$itemsOnMule = $roster->db->query($itemsOnMuleQuery);

	$roster->tpl->assign_block_vars('bank', array(
		'ID' => $muleRow['member_id'],
		'NAME' => $muleRow['member_name'],
		'LINK' => makelink('char-info&amp;a=c:' . $muleRow['member_id']),
		'NOTE' => $note,
		'UPDATED' => $date_char_data_updated,
		'MONEY_G' => $muleRow['gold'],
		'MONEY_S' => $muleRow['silver'],
		'MONEY_C' => $muleRow['copper'],

		'S_ITEMS' => (bool)$roster->db->num_rows(),

		'L_NO_INFO' => sprintf($roster->locale->act['gbank_not_loaded'], $muleRow['member_name'])
	));

	if( $itemsOnMule )
	{
		$column_counter = 1;
		$striping_counter = 1;

		while( $itemRow = $roster->db->fetch($itemsOnMule) )
		{
			$itemRow['item_quantity'] = $itemRow['total_quantity'];

			$item = new item($itemRow);

			$roster->tpl->assign_block_vars('bank.items', array(
				'ITEM' => $item->out(),
				'ITEM_TOOLTIP' => $item->html_tooltip,
				'ROW_CLASS' => $roster->switch_row_class(),
				'COUNT' => $column_counter
			));

			if( $column_counter == $columns )
			{
				$column_counter = 0;
			}
			$column_counter++;
		}
	}
}

$roster->tpl->set_handle('body', $addon['basename'] . '/guildbank.html');
$roster->tpl->display('body');

/**
 * Gets the last upload date for a character
 *
 * @param int $id | Member ID
 * @return string
 */
function date_char_updated( $time )
{
	global $roster;

	list($year, $month, $day, $hour, $minute, $second) = sscanf($time, '%d-%d-%d %d:%d:%d');
	$localtime = mktime($hour + $roster->config['localtimeoffset'], $minute, $second, $month, $day, $year, -1);
	return date($roster->locale->act['phptimeformat'], $localtime);
}
