<?php
$versions['versionDate']['membersList2'] = '$Date: 2005/12/30 20:40:53 $'; 
$versions['versionRev']['membersList2'] = '$Revision: 1.6 $'; 
$versions['versionAuthor']['membersList2'] = '$Author: mordon $';

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
$query = "SELECT members.name, members.class, members.level, players.health, players.mana, players.money_g, players.armor, players.exp, " .
					"players.server".
					" FROM `".ROSTER_MEMBERSTABLE."` members LEFT JOIN `".ROSTER_PLAYERSTABLE."` players ON members.member_id = players.member_id AND members.guild_id = $guildId";

// Add custom primary and secondary ORDER BY definitions
if (isset($_GET['s']))
	$switchString = ($_GET['s'])?$_GET['s']:'';
else
	$switchString = '';

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
	case 'health':
		$query .= " ORDER BY players.health DESC, members.name ASC";
		break;
	case 'mana':
		$query .= " ORDER BY players.mana DESC, members.name ASC";
		break;
	case 'money_g':
		$query .= " ORDER BY players.money_g DESC, members.name ASC";
		break;
	case 'armor':
		$query .= " ORDER BY (substring_index(substring_index(players.armor, ':', -2), ':', 1)+0) DESC, members.name ASC";
		break;
	case 'exp':
		$query .= " ORDER BY (substring_index(players.exp, ':', 1)+0) DESC, members.name ASC";
		break;
	case 'expp':
		$query .= " ORDER BY round(((substring_index(players.exp, ':', 1) / substring_index(players.exp, ':', -1)) * 100), 1) DESC, members.name ASC";
		break;
	default:
		$query .= " ORDER BY members.name ASC";
}

$result = mysql_query($query) or die(mysql_error());
if ($sqldebug)
	print ("<!--$query-->\n");

$tableHeader = '<table cellpadding="0" cellspacing="0" class="membersList">'."\n";
$borderTop = '  <tr>
    <th colspan="8" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th>
  </tr>'."\n";
if($show_money) {
	$tableHeaderRow = '  <tr>
    <th class="rankbordercenterleft"><div class="membersHeader"><a href="?s=name">'.$wordings[$roster_lang]['name'].'</a></div></th>
    <th class="membersHeader"><a href="?s=class">'.$wordings[$roster_lang]['class'].'</a></th>
    <th class="membersHeader"><a href="?s=level">'.$wordings[$roster_lang]['level'].'</a></th>
    <th class="membersHeader"><a href="?s=health">'.$wordings[$roster_lang]['health'].'</a></th>
    <th class="membersHeader"><a href="?s=mana">'.$wordings[$roster_lang]['mana'].'</a></th>
    <th class="membersHeader"><a href="?s=money_g">'.$wordings[$roster_lang]['gold'].'</a></th>
    <th class="membersHeader"><a href="?s=armor">'.$wordings[$roster_lang]['armor'].'</a></th>
    <th class="rankbordercenterright"><div class="membersHeaderRight"><a href="?s=exp">XP</a> (<a href="?s=expp">'.$wordings[$roster_lang]['sortby'].'</a>)</div></th>
  </tr>'."\n";
} else {
		$tableHeaderRow = '  <tr>
    <th class="rankbordercenterleft"><div class="membersHeader"><a href="?s=name">'.$wordings[$roster_lang]['name'].'</a></div></th>
    <th class="membersHeader"><a href="?s=class">'.$wordings[$roster_lang]['class'].'</a></th>
    <th class="membersHeader"><a href="?s=level">'.$wordings[$roster_lang]['level'].'</a></th>
    <th class="membersHeader"><a href="?s=health">'.$wordings[$roster_lang]['health'].'</a></th>
    <th class="membersHeader"><a href="?s=mana">'.$wordings[$roster_lang]['mana'].'</a></th>
    <th class="membersHeader"><a href="?s=armor">'.$wordings[$roster_lang]['armor'].'</a></th>
    <th class="rankbordercenterright"><div class="membersHeaderRight"><a href="?s=exp">XP</a> (<a href="?s=expp">'.$wordings[$roster_lang]['sortby'].'</a>)</div></th>
  </tr>'."\n";
}
$borderBottom = '  <tr>
    <th colspan="8" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></th>
  </tr>'."\n";
$tableFooter = '</table>';

print($tableHeader);

// Counter for row striping
$striping_counter = 0;
if (isset($_GET['s']))
	$current_sorting = $_GET['s'];
else
	$current_sorting = '';

$last_value = '';
while($row = mysql_fetch_array($result)) {
	// Adding grouping dividers
	if ($current_sorting == 'class') {
		if ($last_value != $row['class']) {
			if ($striping_counter != 0) {
				print($borderBottom);
			}
			//echo '<tr class="membersGroup"><td colspan="8">'.$row['class']."</td></tr>\n";
			print('  <tr>
    <th colspan="8" class="membersGroup" id="'.$current_sorting.'-'.$row['class'].'">'.$row['class']."</th>
  </tr>\n");
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
			print('  <tr>
    <th colspan="8" class="membersGroup" id="'.$current_sorting.'-'.$row['level'].'">'.$row['level']."</th>
  </tr>\n");
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
	print('  <tr class="membersRow'. (($striping_counter % 2) +1) ."\">\n");
	// print('<tr>');

  // Increment counter so rows are colored alternately
  ++$striping_counter;

	// Echoing cells w/ data
	print('    <td class="rankbordercenterleft"><div class="membersRow'. (($striping_counter % 2) +1) .'">');
	if ($row['server'])
		print('<a href="char.php?name='.$row['name'].'&amp;server='.$row['server'].'">'.$row['name'].'</a>');
	else
		print($row['name']);

	print('</div></td>'."\n");
	print('    <td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['class'].'</td>'."\n");
	print('    <td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['level'].'</td>'."\n");
	if ($row['server']) {
		print('    <td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['health'].'</td>'."\n");
		print('    <td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['mana'].'</td>'."\n");
		if($show_money) {
			print('    <td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['money_g'].'</td>'."\n");
		}
		if ($row['armor']) {
			$split = explode(':', $row['armor']);
			print('    <td class="membersRow'. (($striping_counter % 2) +1) .'">'.$split[1].' (+'.$split[2].')'.'</td>'."\n");
		} else {
			print('    <td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['armor'].'</td>'."\n");
		}
		if ($row['exp']) {
			$split = explode(':', $row['exp']);
			$perc1 = intval($split[0]); 
			$perc2 = intval($split[1]); 
			//avoid division by zero
			if ($perc2 == 0) {
				$perc = 0;
			} else {
				$perc = round(($perc1 / $perc2)*100, 1);
			}
			//print('<td>'.$split[0].' of '.$split[1].' ('.$perc.'%)</td>');
			print('    <td class="rankbordercenterright"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">'.$split[0].' of '.$split[1].' ('.$perc.'%)'.'</div></td>'."\n");
		} else {
			//print('<td>'.$row['exp'].'</td>');
			print('    <td class="rankbordercenterright"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">'.$row['exp'].'</div></td>'."\n");
		} 
	} else {
		print('    <td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>'."\n");
		print('    <td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>'."\n");
		if($show_money) {
			print('    <td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>'."\n");
		}
		print('    <td class="membersRow'. (($striping_counter % 2) +1) .'">&nbsp;</td>'."\n");
		print('    <td class="rankbordercenterright"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">&nbsp;</div></td>'."\n");
	}   
	print('  </tr>'."\n");
}
print($borderBottom);
print($tableFooter);
mysql_free_result($result);
?>