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
	 "INNER JOIN `".ROSTER_SKILLSTABLE."` skills ON members.member_id = skills.member_id AND members.guild_id = $guildId";

// Add custom primary and secondary ORDER BY definitions
$switchString = ($_GET['s'])?$_GET['s']:"";
switch ($switchString)
{
  case "name":
    $query .= " ORDER BY members.name ASC";
    break;
  case "class":
    $query .= " ORDER BY members.class ASC, members.name ASC";
    break;
  case "level":
    $query .= " ORDER BY members.level DESC, members.name ASC";
    break;
  default:
    $query .= " ORDER BY members.name ASC";
}


$result = mysql_query($query) or die(mysql_error());
if ($sqldebug) {
  print ("<!--$query-->");
}
?>

<table class="members">
  <tr class="membersHeader">
    <th><a href="?s=name">Name</a></th>
    <th><a href="?s=class">Class</a></th>
    <th><a href="?s=level">Level</a></th>
  </tr>

<?php

// Counter for row striping
$striping_counter = 0;
$current_sorting = $_GET['s'];

while($row = mysql_fetch_array($result))
{
  // Adding grouping dividers
  if ($current_sorting == "class")
  {
    if ($last_value != $row['class'])
    {
      echo '<tr class="membersGroup"><td colspan="6">'.$row['class']."</td></tr>\n";
      $striping_counter = 0;
    }
    $last_value = $row['class'];
  }
  elseif ($current_sorting == "level")
  {
    if ( $last_value != $row['level'])
    {
      print('<tr class="membersGroup"><td colspan="6">Level '.$row['level']."</td></tr>\n");
      $striping_counter = 0;
    }
    $last_value = $row['level'];
  }

  // Striping rows
  print('<tr class="membersRow'. (($striping_counter % 2) +1) ."\">\n");

  // Increment counter so rows are colored alternately
  ++$striping_counter;

  // Echoing cells w/ data
	if ($row['server']) {
		print('<td><a href="char.php?name='.$row['name'].'&server='.$row['server'].'">'.$row['name'].'</a></td>');
	} else {
		print('<td>'.$row['name'].'</td>');
	}
	print('<td>'.$row['class'].'</td>');
	print('<td>'.$row['level'].'</td>');
	print("</tr>");
}

mysql_free_result($result);

?>
</table>
<?php
  print "<span id=\"last_update\" class=\"last_update\">Last updated $updateTime</span>";
?>
