<?php
require_once 'conf.php';
require_once 'lib/wowdb.php';

// Establish our connection and select our database 
$link = mysql_connect($db_host, $db_user, $db_passwd) or die ("Could not connect to desired database.");
mysql_select_db($db_name) or die ("Could not select desired database");

$server_name_escape = $wowdb->escape($server_name);
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '%b %d %l%p') FROM `".ROSTER_GUILDTABLE."` WHERE guild_name= '$guild_name_escape' AND server='$server_name_escape'";
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
$query = "SELECT members.name, members.class, members.level, players.sessionHK, players.sessionDK, players.yesterdayHK, players.yesterdayDK, players.yesterdayContribution, players.lastweekHK, players.lastweekDK, players.lastweekContribution, players.lastweekRank, players.lifetimeHK, players.lifetimeDK, players.lifetimeRankName, " . 
         "players.server". 
         " FROM `".ROSTER_MEMBERSTABLE."` members LEFT JOIN `".ROSTER_PLAYERSTABLE."` players ON members.member_id = players.member_id AND members.guild_id = $guildId"; 
// Add custom primary and secondary ORDER BY definitions 
if (isset($_GET['s'])) {
	$switchString = ($_GET['s'])?$_GET['s']:''; 
} else {
	$switchString = ''; 
}
switch ($switchString)
{
	case 'name': 
		$query .= ' ORDER BY members.name ASC'; 
	break; 
	case 'class': 
		$query .= ' ORDER BY members.class ASC, members.name ASC'; 
	break; 
	case 'level': 
		$query .= ' ORDER BY members.level DESC, members.name ASC'; 
	break; 
	case 'sessionHK': 
		$query .= ' ORDER BY players.sessionHK DESC, members.name ASC'; 
	break; 
	case 'sessionDK': 
		$query .= ' ORDER BY players.sessionDK DESC, members.name ASC'; 
	break; 
	case 'yesterdayHK': 
		$query .= ' ORDER BY players.yesterdayHK DESC, members.name ASC'; 
	break; 
	case 'yesterdayDK': 
		$query .= ' ORDER BY players.yesterdayDK DESC, members.name ASC'; 
	break; 
	case 'yesterdayContribution': 
		$query .= ' ORDER BY players.yesterdayContribution DESC, members.name ASC'; 
	break; 
	case 'lastweekHK': 
		$query .= ' ORDER BY players.lastweekHK DESC, members.name ASC'; 
	break;
	case 'lastweekDK':
		$query .= ' ORDER BY players.lastweekDK DESC, members.name ASC';
	break;
	case 'lastweekContribution':
		$query .= ' ORDER BY players.lastweekContribution DESC, members.name ASC';
	break;
	case 'lastweekRank':
		$query .= ' ORDER BY players.lastweekRank DESC, members.name ASC';
	break;
	case 'lifetimeHK':
		$query .= ' ORDER BY players.lifetimeHK DESC, members.name ASC';
	break;
	case 'lifetimeDK':
		$query .= ' ORDER BY players.lifetimeDK DESC, members.name ASC';
	break;
	case 'lifetimeRankName':
		$query .= ' ORDER BY players.RankInfo DESC, members.name ASC';
	break; 
	default: 
		$query .= ' ORDER BY members.level DESC, members.name ASC'; 
}

$result = mysql_query($query) or die(mysql_error());
if ($sqldebug) {
	print ("<!--$query-->\n");
}

$tableHeader = '<table cellpadding="0" cellspacing="0" class="membersList">';
$borderTop = '<tr><th colspan="15" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th></tr>';                 
$tableHeaderRow = '<tr> 
	<th class="rankbordercenterleft"><div class="membersHeader"><a href="?s=name">'.$wordings[$roster_lang]['name'].'</a></div></th> 
	<th class="membersHeader"><a href="?s=class">'.$wordings[$roster_lang]['class'].'</a></th> 
	<th class="membersHeader"><a href="?s=level">'.$wordings[$roster_lang]['level'].'</a></th>
	<th class="membersHeader"><a href="?s=sessionHK">Sess HK</a></th>
	<th class="membersHeader"><a href="?s=sessionDK">Sess DK</a></th>
	<th class="membersHeader"><a href="?s=yesterdayHK">Yest HK</a></th>
	<th class="membersHeader"><a href="?s=yesterdayDK">Yest DK</a></th>
	<th class="membersHeader"><a href="?s=yesterdayContribution">Yest CP</a></th>
	<th class="membersHeader"><a href="?s=lastweekHK">LW HK</a></th>
	<th class="membersHeader"><a href="?s=lastweekDK">LW DK</a></th>
	<th class="membersHeader"><a href="?s=lastweekContribution">LW CP</a></th>
	<th class="membersHeader"><a href="?s=lastweekRank">LW Rank</a></th>
	<th class="membersHeader"><a href="?s=lifetimeHK">Life HK</a></th> 
	<th class="membersHeader"><a href="?s=lifetimeDK">Life DK</a></th>
	<th class="rankbordercenterright"><div class="membersHeaderRight"><a href="?s=lifetimeRankName">Highest Rank<a></div></th>
</tr>'; 
$borderBottom = '<tr><th colspan="15" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></div></th>'; 
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
			//echo '<tr class="membersGroup"><td colspan="8">'.$row['class']."</td></tr>\n"; 
			print('<tr><th colspan="8" class="membersGroup" id="'.$current_sorting.'-'.$row['class'].'">'.$row['class']."</th></tr>"); 
			print ($borderTop); 
			print ($tableHeaderRow); 
			$striping_counter = 0; 
		}
		$last_value = $row['class']; 
	}
	/*elseif ($current_sorting == 'level') 
	{ 
		if ( $last_value != $row['level']) 
			{ 
				if ($striping_counter != 0) { 
					print($borderBottom); 
			}
			//print('<tr class="membersGroup"><td colspan="8">Level '.$row['level']."</td></tr>\n"); 
			print('<tr><th colspan="8" class="membersGroup" id="'.$current_sorting.'-'.$row['level'].'">'.$row['level']."</th></tr>\n"); 
			print ($borderTop); 
			print ($tableHeaderRow); 
			$striping_counter = 0; 
			}
		$last_value = $row['level']; 
	} */ else {
		if ($striping_counter == 0) {
			print($borderTop);
			print($tableHeaderRow);
		}
	}

	// Striping rows 
	print('<tr class="membersRow'. (($striping_counter % 2) +1) ."\">\n"); 
	//  print('<tr>'); 

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

	if ($row['server']) {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['sessionHK'].'</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['sessionDK'].'</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['yesterdayHK'].'</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['yesterdayDK'].'</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['yesterdayContribution'].'</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['lastweekHK'].'</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['lastweekDK'].'</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['lastweekContribution'].'</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['lastweekRank'].'</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['lifetimeHK'].'</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['lifetimeDK'].'</td>');
		if($row['lifetimeRankName']) {
			print('<td class="rankbordercenterright"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">'.$row['lifetimeRankName'].'</div></td>'); 
		} else {
			print('<td class="rankbordercenterright"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">&nbsp;</div></td>'); 
		}
	} else {
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>'); 
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>'); 
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>'); 
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>');
		print('<td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>'); 
		print('<td class="rankbordercenterright"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">&nbsp;</div></td>'); 
	}   
	print('</tr>'); 
} 
print($borderBottom); 
print($tableFooter); 
mysql_free_result($result); 

//print "<p id=\"last_update\" class=\"last_update\">Last updated $updateTime</p>"; 
?>

</table>