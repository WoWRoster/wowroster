<?php
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
?>

<html>
<link rel="stylesheet" type="text/css" href="<?php print $stylesheet2; ?>" />
  <link rel="stylesheet" type="text/css" href="<?php print $stylesheet1; ?>" />
  <style type="text/css">
  /* This is the border line & background colour round the entire page */
  .bodyline { background-color: #000000; border: 1px #212121 solid; }
	</style>
	<script type="text/javascript" src="<?php print $overlib ?>"></script>
<body>
		    
<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr><td align="center">
<table border=0 cellpadding=8 cellspacing=0 width="100%">
<tr><td width="100%" class="bodyline">
<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr><td align="center" width="100%" class="bodyline">
<a href="<?php print $website_address ?>"><img src="<?php print $logo ?>" alt="" border="0"></a><br>
</td>
<tr><td align="center">
<br><br>

<?php
$muleNameQuery = "SELECT m.member_id, m.name AS member_name, m.note AS member_note, m.officer_note AS member_officer_note FROM `".ROSTER_MEMBERSTABLE."` m WHERE m.".$banker_fieldname."='".$banker_rankname."' ORDER BY m.name";

if ($wowdb->sqldebug) {
	print "<!-- $muleNameQuery --> \n";
}
$link = mysql_connect($db_host, $db_user, $db_passwd) or die ('Could not connect to desired database.');
mysql_select_db($db_name) or die ('Could not select desired database');

$muleNames = $wowdb->query($muleNameQuery);

include 'lib/menu.php';
print "<br>\n";

while ($muleRow = mysql_fetch_array($muleNames)) {
	print '<table align="center"><tr><th colspan="15">'.$muleRow['member_name'].' (aka '.$muleRow['member_note'].')</th></tr>'."\n";
	$itemsOnMuleQuery = "SELECT i.*, sum(i.item_quantity) as total_quantity FROM `".ROSTER_ITEMSTABLE."` i WHERE ".$muleRow['member_id']."=i.member_id AND i.item_parent!='bags' AND i.item_parent!='equip' AND i.item_tooltip NOT LIKE '%Soulbound%' GROUP BY i.item_name";
	$itemsOnMule = $wowdb->query($itemsOnMuleQuery);
	if ($wowdb->sqldebug) {
		print "<!-- $itemsOnMuleQuery --> \n";
	}
	if (mysql_fetch_array($itemsOnMule)==FALSE) {
		print '<tr><td colspan="15">'.$muleRow['member_name']." has not uploaded an inventory yet.</td></tr>"."\n";
	} else {
		$column_counter=1;
		while ($itemRow = mysql_fetch_array($itemsOnMule)) {
			$item_texture=str_replace('\\','/',$itemRow['item_texture']);
			if ($column_counter==1) {
				print '<tr valign="top">';
			}
			// Item texture and quantity column

			print '<td>';
		// TOOLTIP CODE
		$first_line = True;
		foreach (explode("\n", $itemRow['item_tooltip']) as $line ) {
			$class='tooltipline';
			if( $first_line ) {
				$color = substr( $itemRow['item_color'], 2, 6 ) . '; font-weight: bold';
				$first_line = False;
				$class='tooltipheader';
			} else {
				if( substr( $line, 0, 2 ) == '|c' ) {
					$color = substr( $line, 4, 6 ).'; font-size: 10px;';
					$line = substr( $line, 10, -2 );
				} else if ( substr( $line, 0, 4 ) == 'Use:' ) {
					$color = '00ff00; font-size: 10px;';
				} else if ( substr( $line, 0, 8 ) == 'Requires' ) {
					$color = 'ff0000; font-size: 10px;';
				} else if ( substr( $line, 0, 10 ) == 'Reinforced' ) {
					$color = '00ff00; font-size: 10px;';
				} else if ( substr( $line, 0, 6 ) == 'Equip:' ) {
					$color = '00ff00; font-size: 10px;';
				} else if ( substr( $line, 0, 6 ) == 'Chance' ) {
					$color = '00ff00; font-size: 10px;';
				} else if ( substr( $line, 0, 8 ) == 'Enchant:' ) {
					$color = '00ff00; font-size: 10px;';
				} else if ( substr( $line, 0, 9 ) == 'Soulbound' ) {
					$color = '00ffff; font-size: 10px;';
				} else {
					$color='ffffff; font-size: 10px;';
				}
			}
			$line = preg_replace('|\\>|','&#8250;', $line );
			$line = preg_replace('|\\<|','&#8249;', $line );
			if( $line != '') {
				$tooltip = $tooltip."<span class=\"$class\" style=\"color:#$color\">$line</span><br>";
			}
		}
		$tooltip = str_replace("'", "\'", $tooltip);
		$tooltip = str_replace('"','&quot;', $tooltip);
		echo '<span style="z-index: 1000;" onMouseover="return overlib(\''.$tooltip.'\');" onMouseout="return nd();">'."\n";
		
		$tooltip = '';

		echo '<div class="item">';
		extract($GLOBALS);
		echo '<a href="'.$itemlink.urlencode($itemRow['item_name']).'" target="_thottbot">'."\n".
		'<img src="'.$img_url.$item_texture.'.'.$img_suffix.'" class="icon"'." /></a>\n";
		if( ($itemRow['item_quantity'] > 1) && ($itemRow['item_parent'] != 'bags') ) {
			echo '<span class="quant">'.$itemRow['item_quantity'].'</span>';
		}
		echo '</div></span>';

/*
			print '<td><div class="item"><a href="'.$itemlink.urlencode($itemRow['item_name']).'" target="_itemlink">'.
				'<img src="'.$img_url.$item_texture.'.'.$img_suffix.'"></a>';
      
			if ($itemRow['total_quantity']>1) {
				print '<span class="quant">'.$itemRow['total_quantity'].'</span>';
			}*/
			print '</td>';

			if ($column_counter==15) {
				print '</tr>';
				$column_counter=0;
			}
			$column_counter++;
		}
	}
	print '</table>';
}
?>
</table>

</td></tr>
</table>
</td></tr>
</table>
</td></tr>
</table>

</body>
</html>