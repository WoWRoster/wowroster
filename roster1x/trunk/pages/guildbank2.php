<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
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

// Multiple edits done for http://wowroster.net Roster

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

require_once( ROSTER_LIB.'char.php' );

$header_title = $wordings[$roster_conf['roster_lang']]['guildbank'];
include_once (ROSTER_BASE.'roster_header.tpl');


$muleNameQuery = "SELECT m.member_id, m.name AS member_name, m.note AS member_note, m.officer_note AS member_officer_note, p.money_g AS gold, p.money_s  AS silver, p.money_c AS copper
FROM `".ROSTER_PLAYERSTABLE."` AS p, `".ROSTER_MEMBERSTABLE."`  AS m
WHERE m.".$roster_conf['banker_fieldname']." LIKE '%".$roster_conf['banker_rankname']."%' AND p.member_id = m.member_id
ORDER BY m.name";

if ($wowdb->sqldebug)
	echo "<!-- $muleNameQuery --> \n";


$muleNames = $wowdb->query($muleNameQuery);

include_once (ROSTER_LIB.'menu.php');
echo "\n<br />\n";

if ( $roster_conf['bank_money'] )
{
	$mulemoney = $wowdb->fetch_array($wowdb->query(
"SELECT SUM( p.money_g ) AS gold, SUM( p.money_s ) AS silver, SUM( p.money_c ) as copper
 FROM `".ROSTER_PLAYERSTABLE."` AS p, `".ROSTER_MEMBERSTABLE."` AS m
 WHERE m.".$roster_conf['banker_fieldname']." LIKE '%".$roster_conf['banker_rankname']."%'
 AND p.member_id = m.member_id
 ORDER  BY m.name"
));
$addsilver=0;
if ($mulemoney['copper']>=100)
{
	$mulemoney['copper'] = $mulemoney['copper']/100;
	$addsilver= (int)$mulemoney['copper'];
	$mulemoney['copper'] = explode (".", $mulemoney['copper']);
	$mulemoney['copper'] = $mulemoney['copper'][1];
}
$mulemoney['silver'] = $mulemoney['silver'] + $addsilver;
$addgold=0;
if ($mulemoney['silver']>=100)
{
	$mulemoney['silver'] = $mulemoney['silver']/100;
	$addgold = (int)$mulemoney['silver'];
	$mulemoney['silver'] = explode (".", $mulemoney['silver']);
	$mulemoney['silver'] = $mulemoney['silver'][1];
}
$mulemoney['gold'] = $mulemoney['gold']+$addgold;

	echo '<br /> '.$wordings[$roster_conf['roster_lang']]['guildbank_totalmoney'].' <div class="money">'.$mulemoney['gold'].' <img src="'.$roster_conf['img_url'].'bagcoingold.gif" alt="g"/> '.
	$mulemoney['silver'].' <img src="'.$roster_conf['img_url'].'bagcoinsilver.gif" alt="s"/> '.
	$mulemoney['copper'].' <img src="'.$roster_conf['img_url'].'bagcoinbronze.gif" alt="c"/></div>
<br />';
}

while ($muleRow = $wowdb->fetch_array($muleNames))
{
	// Parse the note field for possible html characters
	$prg_find = array('/"/','/&/','|\\>|','|\\<|',"/\\n/");
	$prg_rep  = array('&quot;','&amp;','&gt;','&lt;','<br />');

	$note = preg_replace($prg_find, $prg_rep, $muleRow['member_note']);

	$date_char_data_updated = DateCharDataUpdated($muleRow['member_id']);

	echo border('sgray','start',$muleRow['member_name'].' ('.$note.') - Updated: '.$date_char_data_updated).
	'<table class="bodyline" cellspacing="0" cellpadding="2">'.
		 ( $roster_conf['bank_money'] ?
		 	  '<tr>
    <td colspan="15" class="membersRowRight2">'.
			'<div class="money" align="center">'.
			$muleRow['gold'].  ' <img src="'.$roster_conf['img_url'].'bagcoingold.gif" alt="g"/> '.
			$muleRow['silver'].' <img src="'.$roster_conf['img_url'].'bagcoinsilver.gif" alt="s"/> '.
			$muleRow['copper'].' <img src="'.$roster_conf['img_url'].'bagcoinbronze.gif" alt="c"/></div>'.
		"</td>\n</tr>\n" : '' );

	$itemsOnMuleQuery = "SELECT i.*,LEFT(i.item_id, (LOCATE(':',i.item_id)-1)) as real_itemid,sum(i.item_quantity) as total_quantity
 FROM `".ROSTER_ITEMSTABLE."` as i
 WHERE ".$muleRow['member_id']."=i.member_id
 AND i.item_parent!='bags'
 AND i.item_parent!='equip'
 AND (i.item_tooltip
 NOT LIKE '%".$wordings[$roster_conf['roster_lang']]['tooltip_soulbound']."%'
 OR i.item_tooltip
 LIKE '%".$wordings[$roster_conf['roster_lang']]['tooltip_boe']."%')
 GROUP BY real_itemid
 ORDER BY i.item_name";

	$itemsOnMule = $wowdb->query($itemsOnMuleQuery);
	if ($wowdb->sqldebug)
		echo "<!-- $itemsOnMuleQuery --> \n";

	$itemRow=$wowdb->fetch_array($itemsOnMule);
	if ($itemRow==FALSE)
	{
		echo '  <tr>
    <td class="membersRowRight1">'.$muleRow['member_name']." has not uploaded an inventory yet.</td>
  </tr>"."\n";

	}
	else
	{
		echo '  <tr>
    <td class="membersRowRight1">';
		$column_counter=1;
		echo '<table width="100%" cellspacing="0" cellpadding="2">';
		while ($itemRow)
		{
			$item_texture=str_replace('\\','/',$itemRow['item_texture']);
			if ($column_counter==1)
				echo '  <tr valign="top">';

			// Item texture and quantity column
			echo "\n".'    <td align="center">';

			$itemRow['item_quantity'] = $itemRow['total_quantity'];

			$item = new item($itemRow);
			echo $item->out();

			echo '</td>';

			if ($column_counter==15)
			{
				echo "\n  </tr>\n";
				$column_counter=0;
			}
			$column_counter++;
			$itemRow = $wowdb->fetch_array($itemsOnMule);
		}
		echo "</table></td>\n</tr>\n";
	}
	echo '</table>'.border('sgray','end').'<br />';
}

include_once (ROSTER_BASE.'roster_footer.tpl');
