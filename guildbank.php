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
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}

$header_title = $wordings[$roster_conf['roster_lang']]['guildbank'];
include_once (ROSTER_BASE.'roster_header.tpl');

include_once(ROSTER_LIB.'item.php');


$query1= "SELECT `m`.`member_id`, `c`.`name` as `member_name`, `m`.`note` as `member_note`, `m`.`officer_note` as `member_officer_note`, `i`.*, sum(`i`.`item_quantity`) as `total_quantity`
 FROM `".ROSTER_ITEMSTABLE."` as `i`
 INNER JOIN `".ROSTER_MEMBERSTABLE."` as `m` ON `m`.`member_id` = `i`.`member_id`
 INNER JOIN `".ROSTER_CHARACTERSTABLE."` as `c` ON `m`.`member_id` = `c`.`member_id`
 WHERE `m`.`".$roster_conf['banker_fieldname']."` LIKE '%".$roster_conf['banker_rankname']."%'
 AND `i`.`item_parent`!='bags'
 AND `i`.`item_parent`!='equip'
 AND (`i`.`item_tooltip`
 NOT LIKE '%".$wordings[$roster_conf['roster_lang']]['tooltip_soulbound']."%'
 OR `i`.`item_tooltip`
 LIKE '%".$wordings[$roster_conf['roster_lang']]['tooltip_boe']."%')
 GROUP BY `i`.`item_name`;";

$query2= "SELECT `m`.`member_id`, `c`.`name` as `member_name`, `m`.`note` as `member_note`, `m`.`officer_note` as `member_officer_note`, `i`.*
 FROM `".ROSTER_ITEMSTABLE."` as `i`
 INNER JOIN `".ROSTER_MEMBERSTABLE."` as `m` ON `m`.`member_id` = `i`.`member_id`
 INNER JOIN `".ROSTER_CHARACTERSTABLE."` as `c` ON `m`.`member_id` = `c`.`member_id`
 WHERE `m`.`".$roster_conf['banker_fieldname']."` LIKE '%".$roster_conf['banker_rankname']."%'
 AND `i`.`item_parent`!='bags'
 AND `i`.`item_parent`!='equip'
 AND (`i`.`item_tooltip`
 NOT LIKE '%".$wordings[$roster_conf['roster_lang']]['tooltip_soulbound']."%'
 OR `i`.`item_tooltip`
 LIKE '%".$wordings[$roster_conf['roster_lang']]['tooltip_boe']."%')
 ORDER BY `i`.`item_name`";

if ($wowdb->sqldebug)
  echo "<!-- $query1 --> \n";


$result = $wowdb->query( $query1 );

if( !$result )
{
	die_quietly($wowdb->error(), 'Database Error',basename(__FILE__),__LINE__,$query1);
}

$result2 = $wowdb->query( $query2 );

if( !$result2 )
{
	die_quietly($wowdb->error(), 'Database Error',basename(__FILE__),__LINE__,$query2);
}

while ($row2 = $wowdb->fetch_array($result2))
{
	list($base_id, $extras) = split(':',$row2['item_id'],2);
	$owners[$base_id][]=$row2['member_name'];
	$mains[$row2['member_name']]=$row2['member_note'];
}

echo $roster_menu->makeMenu('main');
echo "<br />\n";

if ( $roster_conf['bank_money'] )
{
	$moneyqry = "SELECT SUM( p.money_g ) AS gold, SUM( p.money_s ) AS silver, SUM( p.money_c ) as copper
 FROM `".ROSTER_PLAYERSTABLE."` AS p, `".ROSTER_MEMBERSTABLE."` AS m
 WHERE m.".$roster_conf['banker_fieldname']." LIKE '%".$roster_conf['banker_rankname']."%'
 AND p.member_id = m.member_id";
 	$moneyrslt = $wowdb->query($moneyqry);

 	if( !$moneyrslt )
 	{
 		die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$moneyqry);
 	}

	$mulemoney = $wowdb->fetch_array($moneyrslt);

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
	$stripe_class = ( ( ++$striping_counter % 2 ) + 1 );

	$item_texture=str_replace('\\','/',$row['item_texture']);
	echo '  <tr valign="top" class="membersRowColor'.$stripe_class.'">'."\n";

	// Item holder column
	echo '    <td align="center" class="membersRowCell" style="white-space:normal;">';
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
	echo '    <td class="membersRowCell">';

	$item = new item($row);
	echo $item->out();

	echo '</td>'."\n";

	// Item description column
	echo '    <td width="220" class="membersRowRightCell" style="white-space:normal;">';

	echo colorTooltip($row['item_tooltip'],$row['item_color']);

	echo '</td>
  </tr>'."\n";
}
echo "</table>\n".border('sgray','end');

include_once (ROSTER_BASE.'roster_footer.tpl');
?>
