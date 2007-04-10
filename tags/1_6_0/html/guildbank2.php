<?php
$versions['versionDate']['guildbank2'] = '$Date: 2006/02/03 06:09:46 $'; 
$versions['versionRev']['guildbank2'] = '$Revision: 1.14 $'; 
$versions['versionAuthor']['guildbank2'] = '$Author: anthonyb $';

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

// Multiple edits done for http://wowprofilers.com Roster

require_once 'conf.php';
require_once 'lib/wowdb.php';
require_once 'lib/char.php';

$header_title = $wordings[$roster_lang]['guildbank'];
include ('roster_header.tpl');


$muleNameQuery = "SELECT m.member_id, m.name AS member_name, m.note AS member_note, m.officer_note AS member_officer_note, p.money_g AS gold, p.money_s  AS silver, p.money_c AS copper
FROM `".ROSTER_PLAYERSTABLE."` AS p, `".ROSTER_MEMBERSTABLE."`  AS m
WHERE m.".$banker_fieldname." LIKE '%".$banker_rankname."%' AND p.member_id = m.member_id
ORDER BY m.name";

if ($wowdb->sqldebug)
	echo "<!-- $muleNameQuery --> \n";

$link = mysql_connect($db_host, $db_user, $db_passwd) or die ('Could not connect to desired database.');
mysql_select_db($db_name) or die ('Could not select desired database');

$muleNames = $wowdb->query($muleNameQuery);

include 'lib/menu.php';
echo "\n<br />\n";

if ( $bank_money )
{
	$mulemoney = mysql_fetch_array($wowdb->query(
"SELECT SUM( p.money_g ) AS gold, SUM( p.money_s ) AS silver, SUM( p.money_c ) as copper
FROM `".ROSTER_PLAYERSTABLE."` AS p, `".ROSTER_MEMBERSTABLE."` AS m
WHERE m.".$banker_fieldname." LIKE '%".$banker_rankname."%' AND p.member_id = m.member_id
ORDER  BY m.name"
));
if ($mulemoney['copper']>=100)
{
	$mulemoney['copper'] = $mulemoney['copper']/100;
	$addsilver= (int)$mulemoney['copper'];
	$mulemoney['copper'] = explode (".", $mulemoney['copper']);
	$mulemoney['copper'] = $mulemoney['copper'][1];
}
$mulemoney['silver'] = $mulemoney['silver'] + $addsilver;
if ($mulemoney['silver']>=100)
{
	$mulemoney['silver'] = $mulemoney['silver']/100;
	$addgold = (int)$mulemoney['silver'];
	$mulemoney['silver'] = explode (".", $mulemoney['silver']);
	$mulemoney['silver'] = $mulemoney['silver'][1];
}
$mulemoney['gold'] = $mulemoney['gold']+$addgold;

	echo '<br /> '.$wordings[$roster_lang]['guildbank_totalmoney'].' <div class="money">'.$mulemoney['gold'].' <img src="img/bagCoinGold.gif" alt="g"/> '.
	$mulemoney['silver'].' <img src="img/bagCoinSilver.gif" alt="s"/> '.
	$mulemoney['copper'].' <img src="img/bagCoinBronze.gif" alt="c"/></div>
<br />';
}

while ($muleRow = mysql_fetch_array($muleNames))
{
	$date_char_data_updated = DateCharDataUpdated($muleRow['member_name']);
	echo '<table class="bodyline">
  <tr>
    <th colspan="15" class="membersHeaderRight">'.$muleRow['member_name'].' ('.$muleRow['member_note'].') - Updated: '.$date_char_data_updated.
		 ( $bank_money ?
			'<div class="money" align="right">'.
			$muleRow['gold'].  ' <img src="img/bagCoinGold.gif" alt="g"/> '.
			$muleRow['silver'].' <img src="img/bagCoinSilver.gif" alt="s"/> '.
			$muleRow['copper'].' <img src="img/bagCoinBronze.gif" alt="c"/></div>' : '' )
		.'</th>
  </tr>'."\n";

	$itemsOnMuleQuery = "SELECT i.*,LEFT(i.item_id, (LOCATE(':',i.item_id)-1)) as real_itemid,sum(i.item_quantity) as total_quantity
 FROM `".ROSTER_ITEMSTABLE."` as i
 WHERE ".$muleRow['member_id']."=i.member_id
 AND i.item_parent!='bags'
 AND i.item_parent!='equip'
 AND i.item_tooltip
 NOT LIKE '%".$wordings[$roster_lang]['tooltip_soulbound']."%'
 GROUP BY real_itemid
 ORDER BY i.item_name";

	$itemsOnMule = $wowdb->query($itemsOnMuleQuery);
	if ($wowdb->sqldebug)
		echo "<!-- $itemsOnMuleQuery --> \n";

	$itemRow=mysql_fetch_array($itemsOnMule);
	if ($itemRow==FALSE)
	{
		echo '  <tr>
    <td colspan="15">'.$muleRow['member_name']." has not uploaded an inventory yet.</td>
  </tr>"."\n";

	}
	else
	{
		$column_counter=1;
		while ($itemRow)
		{
			$item_texture=str_replace('\\','/',$itemRow['item_texture']);
			if ($column_counter==1)
				echo '  <tr valign="top">';

			// Item texture and quantity column
			echo "\n".'    <td>';

	// TOOLTIP CODE
		$first_line = True;
		foreach (explode("\n", $itemRow['item_tooltip']) as $line )
		{
			$line = str_replace("\t",'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$line);
			$class='tooltipline';
			if( $first_line )
			{
				$color = substr( $itemRow['item_color'], 2, 6 ) . '; font-weight: bold';
				$first_line = False;
				$class='tooltipheader';
			}
			else
			{
				if( substr( $line, 0, 2 ) == '|c' )
				{
					$color = substr( $line, 4, 6 ).'; font-size: 10px;';
					$line = substr( $line, 10, -2 );
				}
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_use'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_requires'] ) === 0 )
					$color = 'ff0000; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_reinforced'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_equip'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_chance'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_enchant'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_soulbound'] ) === 0 )
					$color = '00ffff; font-size: 10px;';
				else
					$color='ffffff; font-size: 10px;';
			}
			$line = preg_replace('|\\>|','&#8250;', $line );
			$line = preg_replace('|\\<|','&#8249;', $line );
			if( $line != '')
				$tooltip = $tooltip."<span class=\"$class\" style=\"color:#$color\">$line</span><br />";
		}
		$tooltip = str_replace("'", "\'", $tooltip);
		$tooltip = str_replace('"','&quot;', $tooltip);
		echo '<span style="z-index: 1000;" onMouseover="return overlib(\''.$tooltip.'\');" onMouseout="return nd();">'."\n";

		$tooltip = '';

		echo '      <div class="item">';
		extract($GLOBALS);
		echo '<a href="'.$itemlink[$roster_lang].urlencode(utf8_decode($itemRow['item_name'])).'" target="_itemlink">'."\n".
		'      <img src="'.$img_url.$item_texture.'.'.$img_suffix.'" class="icon"'.' alt="'.utf8_decode($row['item_name']).'" /></a>';
		if( ($itemRow['total_quantity'] > 1) && ($itemRow['item_parent'] != 'bags') )
			echo '<span class="quant">'.$itemRow['total_quantity'].'</span>';

		echo '</div></span>';

/*			echo '<td><div class="item"><a href="'.$itemlink.urlencode($itemRow['item_name']).'" target="_itemlink">'.
				'<img src="'.$img_url.$item_texture.'.'.$img_suffix.'"></a>';

			if ($itemRow['total_quantity']>1) {
				echo '<span class="quant">'.$itemRow['total_quantity'].'</span>';
			}*/

			echo '</td>';

			if ($column_counter==15)
			{
				echo "\n  </tr>\n";
				$column_counter=0;
			}
			$column_counter++;
			$itemRow = mysql_fetch_array($itemsOnMule);
		}
	}
	echo '</table><br />';
}

include 'roster_footer.tpl';
?>