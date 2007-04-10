<?php
$versions['versionDate']['guildbank'] = '$Date: 2006/01/01 13:44:33 $'; 
$versions['versionRev']['guildbank'] = '$Revision: 1.11 $'; 
$versions['versionAuthor']['guildbank'] = '$Author: zanix $';

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

$header_title = $wordings[$roster_lang]['guildbank'];
include 'roster_header.tpl';


$query1= "SELECT m.member_id, m.name as member_name, m.note as member_note, m.officer_note as member_officer_note, i.*, sum(i.item_quantity) as total_quantity
 FROM `".ROSTER_ITEMSTABLE."` as i, `".ROSTER_MEMBERSTABLE."` as m
 WHERE i.member_id=m.member_id
 AND m.".$banker_fieldname." LIKE '%".$banker_rankname."%'
 AND i.item_parent!='bags'
 AND i.item_parent!='equip'
 AND i.item_tooltip
 NOT LIKE '%Soulbound%'
 GROUP BY i.item_name";

$query2= "SELECT m.member_id, m.name as member_name, m.note as member_note, m.officer_note as member_officer_note, i.*
 FROM `".ROSTER_ITEMSTABLE."` as i, `".ROSTER_MEMBERSTABLE."` as m
 WHERE i.member_id=m.member_id
 AND m.".$banker_fieldname." LIKE '%".$banker_rankname."%'
 AND i.item_parent!='bags'
 AND i.item_parent!='equip'
 AND i.item_tooltip
 NOT LIKE '%Soulbound%'
 ORDER BY i.item_name";

if ($wowdb->sqldebug)
  echo "<!-- $query1 --> \n";

$link = mysql_connect($db_host, $db_user, $db_passwd) or die ('Could not connect to desired database.');
mysql_select_db($db_name) or die ('Could not select desired database');
$result = $wowdb->query( $query1 );
$result2 = $wowdb->query( $query2 );
while ($row2 = mysql_fetch_array($result2)) {
	list($base_id, $extras) = split(':',$row2['item_id'],2);
	$owners[$base_id][]=$row2['member_name'];
	$mains[$row2['member_name']]=$row2['member_note'];
}

include 'lib/menu.php';
echo "<br />\n";

if ( $bank_money )
{
	$mulemoney = mysql_fetch_array($wowdb->query(
"SELECT SUM( p.money_g ) AS gold, SUM( p.money_s ) AS silver, SUM( p.money_c ) as copper
 FROM `".ROSTER_PLAYERSTABLE."` AS p, `".ROSTER_MEMBERSTABLE."` AS m
 WHERE m.".$banker_fieldname." LIKE '%".$banker_rankname."%'
 AND p.member_id = m.member_id
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

	echo '<br /> '.$wordings[$roster_lang]['guildbank_totalmoney'].
		' <div class="money">'.$mulemoney['gold'].' <img src="img/bagCoinGold.gif" alt="g"/> '.
	$mulemoney['silver'].' <img src="img/bagCoinSilver.gif" alt="s"/> '.
	$mulemoney['copper'].' <img src="img/bagCoinBronze.gif" alt="c"/></div>
<br />';
}

echo '<table class="bodyline" cellspacing="1" cellpadding="2">
  <tr>
    <th class="membersHeader">'.$wordings[$roster_lang]['guildbankcontact'].'</th>
    <th colspan="2" class="membersHeaderRight">'.$wordings[$roster_lang]['guildbankitem'].'</th>
  </tr>
';

$striping_counter = 0;

while($row = mysql_fetch_array($result))
{
	$stripe_class = 'membersRow'.( ( ++$striping_counter % 2 ) + 1 );
	$item_texture=str_replace('\\','/',$row['item_texture']);
	echo '  <tr valign="top" class="'.$stripe_class.'">'."\n";

	// Item holder column
	echo '    <td align=center>';
	list($base_id, $extras) = split(':',$row['item_id'],2);
	//echo "<!-- base_id = $base_id -->\n";
	foreach (array_unique($owners[$base_id]) as $owner)
	{
		echo $owner.'&nbsp;('.$mains[$owner].")<br />";
	}
	echo "</td>\n";

	// Item texture and quantity column
	echo '    <td><div class="item">'."\n";

	echo '<a href="'.$itemlink[$roster_lang].urlencode(utf8_decode($row['item_name'])).'" target="_itemlink">'."\n".
		'      <img src="'.$img_url.$item_texture.'.'.$img_suffix.'" class="icon"'.' alt="'.utf8_decode($row['item_name']).'" /></a>';
	if( ($row['total_quantity'] > 1) && ($itemRow['item_parent'] != 'bags') )
			echo '<span class="quant">'.$row['total_quantity'].'</span>';


	echo '</div></td>'."\n";

	// Item description column
	echo '    <td>';
	$first_line = True;
	foreach (explode("\n", $row['item_tooltip']) as $line )
	{
		$line = str_replace("\t",'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$line);

		if( $first_line )
		{
			$color = substr( $row['item_color'], 2, 6 ) . '; font-weight: bold';
			$first_line = False;
		}
		else
		{
			if( substr( $line, 0, 2 ) == '|c' )
			{
				$color = substr( $line, 4, 6 ).';';
				$line = substr( $line, 10, -2 );
			}
			else if ( strpos( $line, $wordings[$roster_lang]['tooltip_use'] ) === 0 )
				$color = '00ff00; font-size: 10px';
			else if ( strpos( $line, $wordings[$roster_lang]['tooltip_requires'] ) === 0 )
				$color = 'ff0000; font-size: 10px';
			else if ( strpos( $line, $wordings[$roster_lang]['tooltip_reinforced'] ) === 0 )
				$color = '00ff00; font-size: 10px';
			else if ( strpos( $line, $wordings[$roster_lang]['tooltip_equip'] ) === 0 )
				$color = '00ff00; font-size: 10px';
			else if ( strpos( $line, $wordings[$roster_lang]['tooltip_chance'] ) === 0 )
				$color = '00ff00; font-size: 10px';
			else if ( strpos( $line, $wordings[$roster_lang]['tooltip_enchant'] ) === 0 )
				$color = '00ff00; font-size: 10px';
			else if ( strpos( $line, $wordings[$roster_lang]['tooltip_soulbound'] ) === 0 )
				$color = '00ffff; font-size: 10px';
			else
				$color='ffffff; font-size: 10px';
		}
		$line = preg_replace('|\\>|','&gt;', $line );
		$line = preg_replace('|\\<|','&lt;', $line );

		if( $line != '' )
			echo '<span style="color:#'.$color.';">'.$line."</span><br />\n";
	}
	echo '</td>
  </tr>'."\n";
}
echo "</table>\n";

include 'roster_footer.tpl';
?>