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

require_once( 'settings.php' );

//---[ Check for Guild Info ]------------
$guild_info = $wowdb->get_guild_info($roster_conf['server_name'],$roster_conf['guild_name']);
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}

$header_title = $wordings[$roster_conf['roster_lang']]['guildbank'];
include_once (ROSTER_BASE.'roster_header.tpl');


$query1= "SELECT m.member_id, m.name as member_name, m.note as member_note, m.officer_note as member_officer_note, i.*, sum(i.item_quantity) as total_quantity
 FROM `".ROSTER_ITEMSTABLE."` as i, `".ROSTER_MEMBERSTABLE."` as m
 WHERE i.member_id=m.member_id
 AND m.".$roster_conf['banker_fieldname']." LIKE '%".$roster_conf['banker_rankname']."%'
 AND i.item_parent!='bags'
 AND i.item_parent!='equip'
 AND i.item_tooltip
 NOT LIKE '%Soulbound%'
 GROUP BY i.item_name";

$query2= "SELECT m.member_id, m.name as member_name, m.note as member_note, m.officer_note as member_officer_note, i.*
 FROM `".ROSTER_ITEMSTABLE."` as i, `".ROSTER_MEMBERSTABLE."` as m
 WHERE i.member_id=m.member_id
 AND m.".$roster_conf['banker_fieldname']." LIKE '%".$roster_conf['banker_rankname']."%'
 AND i.item_parent!='bags'
 AND i.item_parent!='equip'
 AND i.item_tooltip
 NOT LIKE '%Soulbound%'
 ORDER BY i.item_name";

if ($wowdb->sqldebug)
  echo "<!-- $query1 --> \n";


$result = $wowdb->query( $query1 );
$result2 = $wowdb->query( $query2 );
while ($row2 = $wowdb->fetch_array($result2))
{
	list($base_id, $extras) = split(':',$row2['item_id'],2);
	$owners[$base_id][]=$row2['member_name'];
	$mains[$row2['member_name']]=$row2['member_note'];
}

include_once (ROSTER_LIB.'menu.php');
echo "<br />\n";

if ( $roster_conf['bank_money'] )
{
	$mulemoney = $wowdb->fetch_array($wowdb->query(
"SELECT SUM( p.money_g ) AS gold, SUM( p.money_s ) AS silver, SUM( p.money_c ) as copper
 FROM `".ROSTER_PLAYERSTABLE."` AS p, `".ROSTER_MEMBERSTABLE."` AS m
 WHERE m.".$roster_conf['banker_fieldname']." LIKE '%".$roster_conf['banker_rankname']."%'
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

	echo '<br /> '.$wordings[$roster_conf['roster_lang']]['guildbank_totalmoney'].
		' <div class="money">'.$mulemoney['gold'].' <img src="'.$roster_conf['img_url'].'bagcoingold.gif" alt="g" /> '.
	$mulemoney['silver'].' <img src="'.$roster_conf['img_url'].'bagcoinsilver.gif" alt="s" /> '.
	$mulemoney['copper'].' <img src="'.$roster_conf['img_url'].'bagcoinbronze.gif" alt="c" /></div>
<br />';
}

echo border('sgray','start').'<table class="bodyline" cellspacing="0" cellpadding="0">
  <tr>
    <th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['guildbankcontact'].'</th>
    <th colspan="2" class="membersHeaderRight">'.$wordings[$roster_conf['roster_lang']]['guildbankitem'].'</th>
  </tr>
';

$striping_counter = 1;

while($row = $wowdb->fetch_array($result))
{
	$stripe_class = 'membersRow'.( ( ++$striping_counter % 2 ) + 1 );
	$stripe_class_right = 'membersRowRight'.( ( $striping_counter % 2 ) + 1 );
	$item_texture=str_replace('\\','/',$row['item_texture']);
	echo '  <tr valign="top">'."\n";

	// Item holder column
	echo '    <td align="center" class="'.$stripe_class.'" style="white-space:normal;">';
	list($base_id, $extras) = split(':',$row['item_id'],2);
	//echo "<!-- base_id = $base_id -->\n";
	foreach (array_unique($owners[$base_id]) as $owner)
	{
		// Parse the contact field for possible html characters
		$prg_find = array('/"/','/&/','|\\>|','|\\<|',"/\\n/");
		$prg_rep  = array('&quot;','&amp;','&gt;','&lt;','<br />');

		$note = preg_replace($prg_find, $prg_rep, $mains[$owner]);
		echo $owner.'&nbsp;('.$note.")<br />";
	}
	echo "</td>\n";

	// Item texture and quantity column
	echo '    <td class="'.$stripe_class.'"><div class="item">'."\n";

	echo '<a href="'.$itemlink[$roster_conf['roster_lang']].urlencode(utf8_decode($row['item_name'])).'" target="_blank">'."\n".
		'      <img src="'.$roster_conf['interface_url'].$item_texture.'.'.$roster_conf['img_suffix'].'" class="icon"'.' alt="'.utf8_decode($row['item_name']).'" /></a>';
	if( ($row['total_quantity'] > 1) && ($itemRow['item_parent'] != 'bags') )
			echo '<span class="quant">'.$row['total_quantity'].'</span>';


	echo '</div></td>'."\n";

	// Item description column
	echo '    <td width="220" class="'.$stripe_class_right.'" style="white-space:normal;">';
	$first_line = True;
	$tooltip_out = '';
	foreach (explode("\n", $row['item_tooltip']) as $line )
	{
		if( $first_line )
		{
			$color = substr( $row['item_color'], 2, 6 ) . '; font-weight: bold';
			$first_line = False;
		}
		else
		{
			if( substr( $line, 0, 2 ) == '|c' )
			{
				$color = substr( $line, 4, 6 );
				$line = substr( $line, 10, -2 );
			}
			else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_use'] ) === 0 )
				$color = '00ff00';
			else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_requires'] ) === 0 )
				$color = 'ff0000';
			else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_reinforced'] ) === 0 )
				$color = '00ff00';
			else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_equip'] ) === 0 )
				$color = '00ff00';
			else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_chance'] ) === 0 )
				$color = '00ff00';
			else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_enchant'] ) === 0 )
				$color = '00ff00';
			else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_soulbound'] ) === 0 )
				$color = '00bbff';
			elseif ( strpos( $line, '"' ) )
				$color = 'ffd517';
			else
				$color='ffffff';
		}
		$line = preg_replace('|\\>|','&gt;', $line );
		$line = preg_replace('|\\<|','&lt;', $line );

		if( strpos($line,"\t") )
		{
			$line = str_replace("\t",'</td><td align="right" style="font-size:10px;color:white;">', $line);
			$line = '<table width="220" cellspacing="0" cellpadding="0"><tr><td style="font-size:10px;color:white;">'.$line.'</td></tr></table>';
			$tooltip_out .= $line;
		}
		elseif( $line == '' || $line == ' ' )
			$tooltip_out .= "<br />\n";
		elseif( $line != '' )
			$tooltip_out .= '<span style="color:#'.$color.';">'.$line."</span><br />\n";
	}

	echo $tooltip_out;
	echo '</td>
  </tr>'."\n";
}
echo "</table>\n".border('sgray','end');

include_once (ROSTER_BASE.'roster_footer.tpl');
?>