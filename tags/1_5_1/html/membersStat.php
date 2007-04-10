<?php
require_once 'conf.php';
require_once 'lib/wowdb.php';

// Establish our connection and select our database
$link = mysql_connect($db_host, $db_user, $db_passwd) or die ("Could not connect to desired database.");
mysql_select_db($db_name) or die ("Could not select desired database");

$server_name_escape = $wowdb->escape($server_name);
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$roster_lang]."') FROM `".ROSTER_GUILDTABLE."` WHERE guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_row($result)) {
	$guildId = $row[0];
	$updateTime = $row[1];
} else {
	die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}

include 'lib/menu.php';
print "<br>\n";

#join the tables. These are small tables thankfully
$query = "SELECT members.name, members.class, members.level, players.health, players.stat_int2, players.stat_agl2, players.stat_sta2, " .
	 "players.stat_str2, players.stat_spr2, " .
         "players.server".
         " FROM `".ROSTER_MEMBERSTABLE."` members LEFT JOIN `".ROSTER_PLAYERSTABLE."` players ON members.member_id = players.member_id AND members.guild_id = $guildId";

// Add custom primary and secondary ORDER BY definitions
if (isset($_GET['s'])) {
	$switchString = ($_GET['s'])?$_GET['s']:'';
} else {
	$switchString = '';
}
switch ($switchString) {
	case 'name':
		$query .= " ORDER BY members.name ASC";
		break;
	case 'class':
		$query .= " ORDER BY members.class ASC, members.name ASC";
		break;
	case 'level':
		$query .= " ORDER BY members.level DESC, members.name ASC";
		break;
	case 'stat_int':
		$query .= " ORDER BY players.stat_int2 DESC, members.name ASC";
		break;
	case 'stat_agl':
		$query .= " ORDER BY players.stat_agl2 DESC, members.name ASC";
		break;
	case 'stat_sta':
		$query .= " ORDER BY players.stat_sta2 DESC, members.name ASC";
		break;
	case 'stat_str':
		$query .= " ORDER BY players.stat_str2 DESC, members.name ASC";
		break;
	case 'stat_spr':
		$query .= " ORDER BY players.stat_spr2 DESC, members.name ASC";
		break;
	case 'total':
		$query .= " ORDER BY (players.stat_int2 + players.stat_agl2 + players.stat_sta2 + players.stat_str2 + players.stat_spr2) DESC, members.name ASC";
		break;
	default:
		$query .= " ORDER BY members.name ASC";
}

$result = mysql_query($query) or die(mysql_error());
if ($sqldebug) {
	print ("<!--$query-->\n");
}

$tableHeader = '<table cellpadding="0" cellspacing="0" class="membersList">';
$borderTop = '<tr><th colspan="9" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th></tr>';
$tableHeaderRow = '<tr>
	<th class="rankbordercenterleft"><div class="membersHeader"><a href="?s=name">'.$wordings[$roster_lang]['name'].'</a></div></th>
	<th class="membersHeader"><a href="?s=class">'.$wordings[$roster_lang]['class'].'</a></th>
	<th class="membersHeader"><a href="?s=level">'.$wordings[$roster_lang]['level'].'</a></th>
	<th class="membersHeader"><a href="?s=stat_int">'.$wordings[$roster_lang]['intellect'].'</a></th>
	<th class="membersHeader"><a href="?s=stat_agl">'.$wordings[$roster_lang]['agility'].'</a></th>
	<th class="membersHeader"><a href="?s=stat_sta">'.$wordings[$roster_lang]['stamina'].'</a></th>
	<th class="membersHeader"><a href="?s=stat_str">'.$wordings[$roster_lang]['strength'].'</a></th>
	<th class="membersHeader"><a href="?s=stat_spr">'.$wordings[$roster_lang]['spirit'].'</a></th>
	<th class="rankbordercenterright"><div class="membersHeaderRight"><a href="?s=total">'.$wordings[$roster_lang]['total'].'</a></div></th>
</tr>';
$borderBottom = '<tr><th colspan="9" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></th></tr>';
$tableFooter = '</table>';

print($tableHeader);

// Counter for row striping
$striping_counter = 0;
if (isset($_GET['s'])) {
	$current_sorting = $_GET['s'];
} else {
	$current_sorting = '';
}

$last_value = '';
while($row = mysql_fetch_array($result)){
// Adding grouping dividers
	if ($current_sorting == 'class') {
		if ($last_value != $row['class']) {
			if ($striping_counter != 0) {
				print($borderBottom);
			}
			print('<tr><th colspan="9" class="membersGroup" id="'.$current_sorting.'-'.$row['class'].'">'.$row['class']."</th></tr>\n");
			print ($borderTop);
			print ($tableHeaderRow);
			$striping_counter = 0;
		}
		$last_value = $row['class'];
	} elseif ($current_sorting == 'level') {
		if ( $last_value != $row['level']) {
			if ($striping_counter != 0) {
				print($borderBottom);
			}
			//print('<tr class="membersGroup"><td colspan="8">Level '.$row['level']."</td></tr>\n");
			print('<tr><th colspan="9" class="membersGroup" id="'.$current_sorting.'-'.$row['level'].'">'.$row['level']."</th></tr>\n");
			print ($borderTop);
			print ($tableHeaderRow);
			$striping_counter = 0;
		}
		$last_value = $row['level'];
	} else {
		if ($striping_counter == 0) {
			print($borderTop);
			print($tableHeaderRow);
		}
	}

// Striping rows
	print('<tr class="membersRow'. (($striping_counter % 2) +1) ."\">\n");

// Increment counter so rows are colored alternately
	++$striping_counter;

// Echoing cells w/ data
	print('<td class="rankbordercenterleft"><div class="membersRow'. (($striping_counter % 2) +1) .'">');
	if ($row['server']) {
		print('<a href="char.php?name='.$row['name'].'&server='.$row['server'].'">'.$row['name'].'</a>');
	} else {
		print($row['name']);
	}
	print('</div></td>');
	print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['class'].'</td>');
	print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['level'].'</td>');

	if ($row['stat_int2']) {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['stat_int2'].'</td>');
	} else {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
	}
	if ($row['stat_agl2']) {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['stat_agl2'].'</td>');
	} else {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
	}
	if ($row['stat_sta2']) {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['stat_sta2'].'</td>');
	} else {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
	}
	if ($row['stat_str2']) {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['stat_str2'].'</td>');
	} else {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
	}
	if ($row['stat_spr2']) {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['stat_spr2'].'</td>');
	} else {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
	}
	if ($row['stat_spr2']) {
		$tot = $row['stat_int2'] + $row['stat_agl2'] + $row['stat_sta2'] + $row['stat_str2'] + $row['stat_spr2'];
		print('<td class="rankbordercenterright"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">'.$tot.'</div></td>');
	} else {
		print('<td class="rankbordercenterright"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">&nbsp;</div></td>');
	}
	print('</tr>');
}
print($borderBottom);
print($tableFooter);
mysql_free_result($result);
?>

</table>