<?php
require_once 'lib/wowdb.php';

// Establish our connection and select our database
$link = mysql_connect($db_host, $db_user, $db_passwd) or die ("Could not connect to desired database. <a href=\"docs/\" target=\"_new\">Click here for installation instructions.</a>");
mysql_select_db($db_name) or die ("Could not select desired database. <a href=\"docs/\" target=\"_new\">Click here for installation instructions.</a>");

$server_name_escape = $wowdb->escape($server_name);
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$roster_lang]."') FROM `".ROSTER_GUILDTABLE."` where guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_row($result)) {
	$guildId = $row[0];
	$updateTime = $row[1];
} else {
	die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration. <a href=\"docs/\" target=\"_new\">Click here for installation instructions.</a>");
}

include 'lib/menu.php';

print '<table><tr>';
if ($show_hslist == 1){
	print '<td>';
	include 'hsList.php';
	print '</td>';
}
if ($show_pvplist == 1){
	print '<td>';
	include 'pvpList.php';
	print '</td>';
}
print '</tr></table>';


#join the tables. These are small tables thankfully
// Original Query
/*$query = "SELECT members.name, members.class, members.level, members.note, members.guild_rank, members.guild_title, members.zone, " .
         "DATE_FORMAT(members.last_online, '%b %d %l%p') AS last_online, players.server".
         " FROM `members` LEFT JOIN `players` ON members.member_id = players.member_id AND members.guild_id = $guildId"; */

// For testing
//SELECT members.name, members.class, members.level, members.note, IF(members.note IS NULL or members.note = '', 1, 0) AS nisnull, members.guild_rank, members.guild_title, members.zone, DATE_FORMAT(members.last_online, '%b %d %l%p') AS last_online, players.server, mid(items.item_tooltip FROM 50 FOR (locate('.', items.item_tooltip)-50)) AS hearth, IF(items.item_tooltip IS NULL or items.item_tooltip = '', 1, 0) AS tisnull FROM members LEFT JOIN players ON members.member_id = players.member_id AND members.guild_id = '2' LEFT JOIN items ON members.member_id = items.member_id AND items.item_name = 'Hearthstone';

// Long ass query string
//$query = "SELECT members.name, members.class, players.RankName, members.level, members.note, IF(members.note IS NULL or members.note = '', 1, 0) AS nisnull, members.guild_rank, members.guild_title, members.zone, DATE_FORMAT(members.last_online, '".$timeformat[$roster_lang]."') AS last_online, players.server, mid(items.item_tooltip FROM 50 FOR (locate('.', items.item_tooltip)-50)) AS hearth, IF(items.item_tooltip IS NULL or items.item_tooltip = '', 1, 0) AS tisnull FROM members LEFT JOIN players ON members.member_id = players.member_id AND members.guild_id = $guildId LEFT JOIN items ON members.member_id = items.member_id AND items.item_name = 'Hearthstone'";
$query = "SELECT members.name, members.class, players.RankName, IF(members.level < players.level, players.level, members.level) AS level, members.note, IF(members.note IS NULL or members.note = '', 1, 0) AS nisnull, members.guild_rank, members.guild_title, members.zone, DATE_FORMAT(members.last_online, '".$timeformat[$roster_lang]."') AS last_online, players.server, players.hearth, IF(items.item_tooltip IS NULL or items.item_tooltip = '', 1, 0) AS tisnull FROM `".ROSTER_MEMBERSTABLE."` members LEFT JOIN `".ROSTER_PLAYERSTABLE."` players ON members.member_id = players.member_id AND members.guild_id = $guildId LEFT JOIN `".ROSTER_ITEMSTABLE."` items ON members.member_id = items.member_id AND items.item_name = 'Hearthstone'";

// Add custom primary and secondary ORDER BY definitions
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
		$query .= " ORDER BY members.class ASC, level DESC";
		break;
	case 'level':
		$query .= " ORDER BY level DESC, members.name ASC";
		break;
	case 'note':
		$query .= " ORDER BY nisnull ASC, members.note ASC, members.name ASC";
		break;
	case 'guild_rank':
		$query .= " ORDER BY members.guild_rank ASC, members.name ASC";
		break;
	case 'last_online':
		$query .= " ORDER BY members.last_online DESC, members.name ASC";
		break;
	case 'zone':
		$query .= " ORDER BY members.zone ASC, members.name ASC";
		break;
	case 'RankName':
		$query .= " ORDER BY players.RankInfo DESC, members.name ASC";
		break; 
	case 'hearth':
		$query .= " ORDER BY tisnull ASC, hearth ASC, members.name ASC";
		break;
	default:
		$query .= " ORDER BY level DESC, members.name ASC";
}

$result = mysql_query($query) or die(__LINE__.":".mysql_error());
if ($sqldebug) {
	print ("<!--$query-->");
}
$tableHeader = '<table cellpadding="0" cellspacing="0" class="membersList">';
$borderTop = '<tr><th colspan="9" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th></tr>';
$tableHeaderRow = '<tr>
	<th class="rankbordercenterleft"><div class="membersHeader"><a href="?s=name">'.$wordings[$roster_lang]['name'].'</a></div></th>
	<th class="membersHeader"><a href="?s=class">'.$wordings[$roster_lang]['class'].'</a></th>
	<th class="membersHeader"><a href="?s=level">'.$wordings[$roster_lang]['level'].'</a></th>
	<th class="membersHeader"><a href="?s=guild_rank">'.$wordings[$roster_lang]['title'].'</a></th>
	<th class="membersHeader"><a href="?s=note">'.$wordings[$roster_lang]['note'].'</a></th>
	<th class="membersHeader"><a href="?s=hearth">'.$wordings[$roster_lang]['hearthed'].'</a></th>
	<th class="membersHeader"><a href="?s=zone">'.$wordings[$roster_lang]['zone'].'</a></th>
	<th class="membersHeader"><a href="?s=RankName">'.$wordings[$roster_lang]['currenthonor'].'</a></th>
	<th class="rankbordercenterright"><div class="membersHeaderRight"><a href="?s=last_online">'.$wordings[$roster_lang]['lastonline'].'</a></div></th>
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
while($row = mysql_fetch_array($result)) {
	// Adding grouping dividers
	if ($current_sorting == 'class') {
		if ($last_value != $row['class']) {
			if ($striping_counter != 0) {
				print($borderBottom);
			}
			print('<tr><th colspan="8" class="membersGroup" id="'.$current_sorting.'-'.$row['class'].'">'.$row['class']."</th></tr>\n");
			print ($borderTop);
			print ($tableHeaderRow);
			$striping_counter = 0;
		}
		$last_value = $row['class'];
	}	elseif ($current_sorting == 'guild_rank') {
		if ( $last_value != $row['guild_rank']) {
			if ($striping_counter != 0) {
				print($borderBottom);
			}
			print('<tr><th colspan="8" class="membersGroup" id="'.$current_sorting.'-'.$row['guild_title'].'">'.$row['guild_title']."</th></tr>\n");
			print ($borderTop);
			print ($tableHeaderRow);
			$striping_counter = 0;
		}
	$last_value = $row['guild_rank'];
	} elseif ($current_sorting == 'level') {
		if ( $last_value != $row['level']) {
			if ($striping_counter != 0) {
				print($borderBottom);
			}
			print('<tr><th colspan="8" class="membersGroup" id="'.$current_sorting.'-'.$row['level'].'">Level '.$row['level']."</th></tr>\n");
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
	print('<tr>');

// Increment counter so rows are colored alternately
	++$striping_counter;

// Echoing cells w/ data
	print('<td class="rankbordercenterleft"><div class="membersRow'. (($striping_counter % 2) +1) .'">');
	if ($row['server']) {
		print('<a href="char.php?name='.$row['name'].'&amp;server='.$row['server'].'">'.$row['name'].'</a>');
	} else {
		print($row['name']);
	}
	print('</div></td>');

	print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['class'].'</td>');
	print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['level'].'</td>');
	print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['guild_title'].'</td>');
	if ($row['note']) {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['note'].'</td>');
	} else {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
	}
	if ($row['hearth']) {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['hearth'].'</td>');
	} else {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
	}
	if($row['zone']) { 
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['zone'].'</td>');
	} else { 
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
	}
	if ($row['RankName']) {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['RankName'].'</td>');
	} else {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
	}
	print('<td class="rankbordercenterright"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">'.$row['last_online'].'</div></td>');
	print("</tr>\n");
}
print($borderBottom);
print($tableFooter);
mysql_free_result($result);
print "<p id=\"last_update\" class=\"last_update\">".$wordings[$roster_lang]['update']." ".$updateTime."</p>";
?>