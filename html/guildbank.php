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
  .bodyline       { background-color: #000000; border: 1px #212121 solid; }
</style>
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
$query1= "SELECT m.member_id, m.name as member_name, m.note as member_note, m.officer_note as member_officer_note, i.*, sum(i.item_quantity) as total_quantity FROM `".ROSTER_ITEMSTABLE."` as i, `".ROSTER_MEMBERSTABLE."` as m WHERE i.member_id=m.member_id and m.".$banker_fieldname."='".$banker_rankname."' and i.item_parent!='bags' and i.item_parent!='equip' and i.item_tooltip not like '%Soulbound%' group by i.item_name";
$query2= "SELECT m.member_id, m.name as member_name, m.note as member_note, m.officer_note as member_officer_note, i.* FROM `".ROSTER_ITEMSTABLE."` as i, `".ROSTER_MEMBERSTABLE."` as m WHERE i.member_id=m.member_id and m.".$banker_fieldname."='".$banker_rankname."' and i.item_parent!='bags' and i.item_parent!='equip' and i.item_tooltip not like '%Soulbound%' order by i.item_name";

if ($wowdb->sqldebug) {
  print "<!-- $query1 --> \n";
}
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
print "<br>\n";

print '<table align="center"><tr><th>'.$wordings[$roster_lang]['guildbankcontact'].'</th><th colspan=2>'.$wordings[$roster_lang]['guildbankitem'].'</th></tr>';

while($row = mysql_fetch_array($result)) {
	$item_texture=str_replace('\\','/',$row['item_texture']);
	print '<tr valign="top">';

	// Item holder column
	print '<td align=center>';
	list($base_id, $extras) = split(':',$row['item_id'],2);
	//print "<!-- base_id = $base_id -->\n";
	foreach (array_unique($owners[$base_id]) as $owner) {
		print $owner.'&nbsp;('.$mains[$owner].')<br>'."\n";
	}
	print '</td>';

	// Item texture and quantity column
	print '<td><div class="item"><a href="'.$itemlink.urlencode($row['item_name']).'" target="_itemlink">'.
		'<img src="'.$img_url.$item_texture.'.'.$img_suffix.'"></a>';

	if ($row['total_quantity']>1) {
		print '<span class="quant">'.$row['total_quantity'].'</span>';
	}
	print '</div></td>';

	// Item description column
	print '<td>';
	$first_line = True;
	foreach (explode("\n", $row['item_tooltip']) as $line ) {
		$class='tooltipline';
		if( $first_line ) {
			$color = substr( $row['item_color'], 2, 6 );
			$first_line = False;
			$class='tooltipheader';
		} else {
			if( substr( $line, 0, 2 ) == '|c' ) {
				$color = substr( $line, 4, 6 );
				$line = substr( $line, 10, -2 );
			} else if ( substr( $line, 0, 4 ) == 'Use:' ) {
				$color = '00ff00';
			} else if ( substr( $line, 0, 8 ) == 'Requires' ) {
				$color = 'ff0000';
			} else if ( substr( $line, 0, 10 ) == 'Reinforced' ) {
				$color = '00ff00';
			} else if ( substr( $line, 0, 6 ) == 'Equip:' ) {
				$color = '00ff00';
			} else if ( substr( $line, 0, 6 ) == 'Chance' ) {
				$color = '00ff00';
			} else if ( substr( $line, 0, 8 ) == 'Enchant:' ) {
				$color = '00ff00';
			} else if ( substr( $line, 0, 9 ) == 'Soulbound' ) {
				$color = '00ffff';
			} else {
				$color='ffffff';
			}
		}
		$line = preg_replace('|\\>|','&gt;', $line ); 
		$line = preg_replace('|\\<|','&lt;', $line ); 
		if( $line == '' ) { 
			$line = '&nbsp;'; 
		}
		echo '<span style="color:#'.$color.'">'.$line.'</span><br>';
	}
	print '</td></tr>'."\n";
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