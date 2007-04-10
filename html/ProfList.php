<?php
require_once 'conf.php';
require_once 'lib/wowdb.php';

// Establish our connection and select our database
$link = mysql_connect($db_host, $db_user, $db_passwd) or die ("Could not connect to desired database.");
mysql_select_db($db_name) or die ("Could not select desired database");

$server_name_escape = $wowdb->escape($server_name);
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '%b %d %l%p') FROM `".ROSTER_GUILDTABLE."` WHERE guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_row($result)) {
	$guildId = $row[0];
	$updateTime = $row[1];
} else {
	die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}

#join the tables. These are small tables thankfully
$query = "SELECT members.name, members.class, members.level, skills.skill_type, skills.skill_name, skills.skill_level, " .
         "players.server".
         " FROM `".ROSTER_MEMBERSTABLE."` members LEFT JOIN `".ROSTER_PLAYERSTABLE."` players ON members.member_id = players.member_id " .
	 "INNER JOIN `".ROSTER_SKILLSTABLE."` skills ON members.member_id = skills.member_id AND members.guild_id = $guildId ".
         "AND (skills.skill_type = 'Professions' OR skills.skill_type = 'Secondary Skills') ";

// Add custom primary and secondary ORDER BY definitions
$switchString = ($_GET['s'])?$_GET['s']:'';
switch ($switchString) {
	case 'name':
		$query .= " ORDER BY members.name ASC";
		break;
	case 'skill_name':
		$query .= " ORDER BY skills.skill_name ASC";
		break;  
	case 'skill_level':
		$query .= " ORDER BY skills.skill_level ASC";
	break;  
	default:
		$query .= " ORDER BY members.name ASC";
}

$result = mysql_query($query) or die(mysql_error());
if ($sqldebug) {
	print ("<!--$query-->");
}
?>

Views: <a href="index.php">Standard</a> <a href="indexAlt.php">Alternate</a> <a href="indexStat.php">Stats</a> Professions

<table class="members">
  <tr class="membersHeader">
    <th><a href="?s=name">Name</a></th>
    <th><a href="?s=skill_name">Skill</a></th>    
    <th><a href="?s=skill_level">Level</a></th>    
  </tr>

<?php
// Counter for row striping
$striping_counter = 0;
$current_sorting = $_GET['s'];

while($row = mysql_fetch_array($result)) {
	// Adding grouping dividers
	if ($current_sorting == 'skill_name') {
		if ($last_value != $row['skill_name']) {
			echo '<tr class="membersGroup"><td colspan="6">'.$row['skill_name']."</td></tr>";
			$striping_counter = 0;
		}
		$last_value = $row['skill_name'];
	} else if ($current_sorting == 'skill_level') {
		if ($last_value != $row['skill_level']) {
			$split = explode(':', $row['skill_level']);
			$perc1 = intval($split[0]); 
			$perc2 = intval($split[1]); 
			//$padded = sprintf("%03d", $perc1);
			echo '<tr class="membersGroup"><td colspan="6">'.$perc1.' of '.$perc2."</td></tr>";
			$striping_counter = 0;
		}
		$last_value = $row['skill_level'];
	} else {
		if ($last_value != $row['name']) {
			echo '<tr class="membersGroup"><td colspan="6">'.'<a href="char.php?name='.$row['name'].'&server='.$row['server'].'">'.$row['name'].'</a></td></tr>';
			$striping_counter = 0;
		}
		$last_value = $row['name'];
	}  

	// Striping rows
	print('<tr class="membersRow'. (($striping_counter % 2) +1) ."\">\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Echoing cells w/ data
	if ($current_sorting == 'skill_name' or $current_sorting == 'skill_level') {
		if ($row['server']) {
			print('<td><a href="char.php?name='.$row['name'].'&server='.$row['server'].'">'.$row['name'].'</a></td>');
		} else {
			print('<td>'.$row['name'].' ('.$row['class'].')</td>');
		}
	} else { print('<td></td>'); }
		if ($current_sorting != 'skill_name') {
			print('<td>'.$row['skill_name'].'</td>');  
		} else { print('<td></td>'); }
			if ($current_sorting != 'skill_level') {
				$split = explode(':', $row['skill_level']);
				$perc1 = intval($split[0]); 
				$perc2 = intval($split[1]); 
				//$padded = sprintf("%03d", $perc1);
				print('<td>'.$perc1.' of '.$perc2.'</td>'); 
			} else { print('<td></td>'); }
			print('</tr>');
}

mysql_free_result($result);
?>

</table>

<?php
	print "<span id=\"last_update\" class=\"last_update\">Last updated $updateTime</span>";
?>
