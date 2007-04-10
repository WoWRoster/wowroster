<?php require_once 'conf.php';
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
$striping_counter = 0;

$link = mysql_connect($db_host, $db_user, $db_passwd) or die("Could not connect");
mysql_select_db($db_name) or die("Could not select DB");

if (isset($_GET['type'])) {
	$type = $_GET['type'];
} else {
	$type = "";
}

$tableHeader = '<table cellpadding="0" cellspacing="0" class="membersList">'."\n";

function borderTop($col) {
	if ($col < 2) { $col = 2; }
	print '<tr><th colspan="'.$col.'" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th></tr>'."\n";
}

function tableHeaderRow($th) {
	$acount = 0;
	foreach ($th as $header) {
		++$acount;
		if ($acount == 1) {
			print '<th class="rankbordercenterleft"><div class="membersHeader">'.$header.'</div></th>'."\n";
		} elseif ($acount == count($th)) {
			print '<th class="rankbordercenterright"><div class="membersHeaderRight">'.$header.'</div></th>'."\n";
		} else {
			print '<th class="membersHeader">'.$header."</th>\n";
		}
	}
}

function borderBottom($col) {
	if ($col < 2) { $col = 2; }
	print '<tr><th colspan="'.$col.'" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></th></tr>'."\n";
}

$tableFooter = '</table>'."\n";

function rankRight($sc) {
        print '<td class="rankbordercenterleft"><div class="membersRow'.$sc.'">';
}
function rankMid($sc) {
        print '<td class="membersRow'.$sc.'">';
}
function rankLeft($sc) {
	print '<td class="rankbordercenterright"><div class="membersRowRight'.$sc.'">';
}

if ($type == "guildwins") {
	print($tableHeader);
	borderTop(2);
	tableHeaderRow(array(
		"Total wins by guild",
		'&nbsp;'
	));
	
	$query = "SELECT guild, COUNT(guild) AS countg FROM `".ROSTER_PVP2TABLE."` WHERE win = 'Yes' AND enemy = 'Yes' GROUP BY guild ORDER BY countg DESC";
	$result = mysql_query($query) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankRight((($striping_counter % 2) +1));
		print('<a href="?type=guildinfo&guild=');
  		print($row["guild"]);
  		print('">');
  		if ($row["guild"] == "") {
			$guildname = "(unguilded)";
      		} else {
			$guildname = $row["guild"];
		}
  		print($guildname);
		print("</a></div></td>");
		rankLeft((($striping_counter % 2) +1));
		print($row["countg"]);
		print("</div></td></tr>");
	}

	borderBottom(2);
	print($tableFooter);
} else if ($type == "guildlosses") {
	print($tableHeader);
	borderTop(2);
	tableHeaderRow(array(
		"Total losses by guild",
		'&nbsp;'
	));

	$query = "SELECT guild, COUNT(guild) AS countg FROM `".ROSTER_PVP2TABLE."` WHERE win = 'No' AND enemy = 'Yes' GROUP BY guild ORDER BY countg DESC";
	$result = mysql_query($query) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankRight((($striping_counter % 2) +1));
		print('<a href="?type=guildinfo&guild=');
  		print($row["guild"]);
  		print('">');
  		if ($row["guild"] == "") {
			$guildname = "(unguilded)";
		} else {
			$guildname = $row["guild"];
		}
  		print($guildname);
		print("</a></div></td>");
		rankLeft((($striping_counter % 2) +1));
		print($row["countg"]);
		print("</div></td></tr>");
	}

	borderBottom(2);
	print($tableFooter);
} else if ($type == "enemywins") {
	print($tableHeader);
	borderTop(6);
	tableHeaderRow(array(
		"Name",
		"Kills",
		"Guild",
		"Race",
		"Class",
		"Level",
	));

	$query = "SELECT name, guild, race, class, level, COUNT(name) AS countg FROM `".ROSTER_PVP2TABLE."` WHERE win = 'Yes' AND enemy = 'Yes' GROUP BY name ORDER BY countg DESC, level DESC";
	$result = mysql_query($query) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		// Striping rows
		print('<tr class="membersRow'. (($striping_counter % 2) +1) ."\">\n");

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankRight((($striping_counter % 2) +1));
		print('<a href="?type=playerinfo&player=');
  		print($row["name"]);
  		print('">');
  		print($row["name"]);
		print("</a></div></td>");
		rankMid((($striping_counter % 2) +1));
		print($row["countg"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
  		if ($row["guild"] == "") {
			$guildname = "(unguilded)";
		} else {
			$guildname = $row["guild"];
		}
		print($guildname);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["race"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["class"]);
		print("</td>");
		rankLeft((($striping_counter % 2) +1));
		print($row["level"]);
		print("</div></td></tr>");
	}

	borderBottom(6);
	print($tableFooter);
} else if ($type == "enemylosses") {
	print($tableHeader);
	borderTop(6);
	tableHeaderRow(array(
		"Name",
		"Kills",
		"Guild",
		"Race",
		"Class",
		"Level",
	));

	$query = "SELECT name, guild, race, class, level, COUNT(name) as countg FROM `".ROSTER_PVP2TABLE."` WHERE win = 'No' AND enemy = 'Yes' GROUP BY name ORDER BY countg DESC, level DESC";
	$result = mysql_query($query) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankRight((($striping_counter % 2) +1));
		print('<a href="?type=playerinfo&player=');
  		print($row["name"]);
  		print('">');
  		print($row["name"]);
		print("</a></td>");
		rankMid((($striping_counter % 2) +1));
		print($row["countg"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
  		if ($row["guild"] == "") {
			$guildname = "(unguilded)";
		} else {
			$guildname = $row["guild"];
		}
		print($guildname);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["race"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["class"]);
		print("</td>");
		rankLeft((($striping_counter % 2) +1));
		print($row["level"]);
		print("</div></td></tr>");
	}

	borderBottom(6);
	print($tableFooter);
} else if ($type == "purgewins") {
	print($tableHeader);
	borderTop(2);
	tableHeaderRow(array(
		"$guild_name members with the most kills",
		'&nbsp;'
	));

	$query = "SELECT pvp2.member_id, members.name AS gn, COUNT(pvp2.member_id) AS countg FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE win = 'Yes' AND enemy = 'Yes' GROUP BY pvp2.member_id ORDER BY countg DESC";
	$result = mysql_query($query) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankRight((($striping_counter % 2) +1));
		print('<a href="char.php?name='.$row['gn'].'&server='.$server_name.'&action=pvp&start=0&s=date">'.$row['gn'].'</a></td>');
		rankLeft((($striping_counter % 2) +1));
		print($row["countg"]);
		print("</td></tr>");
	}

	borderBottom(2);
	print($tableFooter);
} else if ($type == "purgelosses") {
	print($tableHeader);
	borderTop(2);
	tableHeaderRow(array(
		"$guild_name members who have died the most",
		'&nbsp;'
	));

	$query = "SELECT pvp2.member_id, members.name AS gn, COUNT(pvp2.member_id) AS countg FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE win = 'No' AND enemy = 'Yes' GROUP BY pvp2.member_id ORDER BY countg DESC";
	$result = mysql_query($query) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankRight((($striping_counter % 2) +1));
		print('<a href="char.php?name='.$row['gn'].'&server='.$server_name.'&action=pvp&start=0&s=date">'.$row['gn'].'</a></td>');
		rankLeft((($striping_counter % 2) +1));
		print($row["countg"]);
		print("</td></tr>");
	}

	borderBottom(2);
	print($tableFooter);
} else if ($type == "purgeavewins") {
	print($tableHeader);
	borderTop(3);
	tableHeaderRow(array(
		"$guild_name members with the best win level difference average",
		'&nbsp;',
		'&nbsp;'
	));

	$query = "SELECT pvp2.member_id, members.name as gn, AVG(pvp2.`diff`) as ave, COUNT(pvp2.member_id) as countg FROM `".ROSETER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE win = 'Yes' AND enemy = 'Yes' GROUP BY pvp2.member_id ORDER BY ave DESC";
	$result = mysql_query($query) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankRight((($striping_counter % 2) +1));
		print('<a href="char.php?name='.$row['gn'].'&server='.$server_name.'&action=pvp&start=0&s=date">'.$row['gn'].'</a></td>');
		rankMid((($striping_counter % 2) +1));
		$ave = round($row["ave"], 2);
		if ($ave > 0) {
		   $ave = "+".$ave;
		}
		print($ave);
		rankLeft((($striping_counter % 2) +1));
		print($row["countg"]);
		print("</td></tr>");
	}

	borderBottom(3);
	print($tableFooter);
} else if ($type == "purgeavelosses") {
	print($tableHeader);
	borderTop(3);
	tableHeaderRow(array(
		"$guild_name members with the best loss level difference average",
		'&nbsp;',
		'&nbsp;'
	));

	$query = "SELECT pvp2.member_id, members.name AS gn, AVG(pvp2.`diff`) AS ave, COUNT(pvp2.member_id) AS countg FROM `".ROSETER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE win = 'No' AND enemy = 'Yes' GROUP BY pvp2.member_id ORDER BY ave DESC";
	$result = mysql_query($query) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankRight((($striping_counter % 2) +1));
		print('<a href="char.php?name='.$row['gn'].'&server='.$server_name.'&action=pvp&start=0&s=date">'.$row['gn'].'</a></td>');
		rankMid((($striping_counter % 2) +1));
		$ave = round($row["ave"], 2);
		if ($ave > 0) {
		   $ave = "+".$ave;
		}
		print($ave);
		rankLeft((($striping_counter % 2) +1));
		print($row["countg"]);
		print("</td></tr>");
	}

	borderBottom(3);
	print($tableFooter);
} else if ($type == "pvpratio") {
	print('<br><small>Solo Win/Loss Ratios (only level differences -7 to +7 counted)</small><br><br>');
	print($tableHeader);
	borderTop(2);
	tableHeaderRow(array(
		"$guild_name Member",
		"Ratio"
	));

	//$query = "SELECT member_id, name as gn, pvp_ratio FROM `players` WHERE 1 ORDER BY pvp_ratio DESC";
	$query = "SELECT members.name, IF(pvp2.win = 'Yes', 1, 0) AS win, SUM(win) AS wtotal, COUNT(win) AS btotal FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE pvp2.group = 0 AND pvp2.diff < 8 AND pvp2.diff > -8 AND pvp2.enemy = 'Yes' GROUP BY members.name ORDER BY wtotal DESC";
	$result = mysql_query($query) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankRight((($striping_counter % 2) +1));
		print('<a href="char.php?name='.$row['name'].$server_name.'&action=pvp&s=date">'.$row['name'].'</a></td>');
		rankLeft((($striping_counter % 2) +1));
		$wins = $row['wtotal'];
		$battles = $row ['btotal'];
		if ($wins == $battles) {
			print "Winless";
		} elseif ($wins == 0) {
			print "Unbeaten";
		} else {
			$ratio = round(($wins / ($battles-$wins)), 2);
			print "$ratio to 1";
		}
		print("</td></tr>");
	}

	borderBottom(2);
	print($tableFooter);
} else if ($type == "playerinfo") {
	if (isset($_GET['player'])) {
		$player = $_GET['player'];
	} else {
		$player = "";
	}
	if (isset($_GET['s'])) {
		$sort = $_GET['s'];
	} else {
		$sort = "";
	}
    
	$first = true;
	$query = "SELECT pvp2.*, members.name AS gn FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE pvp2.name = '";
	$query = $query.$player;
	$query=$query."'";
	
	  if ($sort == "name") {
		  $query=$query." ORDER BY 'name', 'level' DESC, 'guild'";
	  } else if ($sort == "race") {
		  $query=$query." ORDER BY 'race', 'name', 'level' DESC";
	  } else if ($sort == "class") {
		  $query=$query." ORDER BY 'class', 'name', 'level' DESC";
	  } else if ($sort == "level") {
		  $query=$query." ORDER BY 'level' DESC, 'name' ";
	  } else if ($sort == "mylevel") {
		  $query=$query." ORDER BY 'mylevel' DESC, 'name' ";
	  } else if ($sort == "diff") {
		  $query=$query." ORDER BY 'diff' DESC, 'name' ";
	  } else if ($sort == "result") {
		  $query=$query." ORDER BY 'win' DESC, 'name' ";
	  } else if ($sort == "zone") {
		  $query=$query." ORDER BY 'zone', 'name' ";
	  } else if ($sort == "subzone") {
		  $query=$query." ORDER BY 'subzone', 'name' ";
	  } else if ($sort == "group") {
		  $query=$query." ORDER BY 'group', 'name' ";
	  } else if ($sort == "date") {
		  $query=$query." ORDER BY 'date' DESC, 'name' ";
	  } else {
		  $query=$query." ORDER BY 'date' DESC, 'name' ";
	  }

	$result = mysql_query($query) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		$url = '<a href="indexPvp.php?type=playerinfo&player='.$player;

		if ($first) {
			print('<br><small>Kill/Loss history for "');
                	print ($player.'" ('.$row["race"].' '.$row["class"].') of '.$row["guild"]);
                	print ('</small><br><br>');

			print($tableHeader);
			borderTop(9);
			tableHeaderRow(array(
				$url.'&s=date">When</a>',
				$url.'&s=name">Name</a>',
				$url.'&s=result">Result</a>',
				$url.'&s=zone">Zone</a>',
				$url.'&s=subzone">Subzone</a>',
				$url.'&s=level">Their Level</a>',
				$url.'&s=mylevel">Your Level</a>',
				$url.'&s=diff">Diff</a>',
				$url.'&s=group">Group</a>'
			));
			$first = false;
		}

		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankRight((($striping_counter % 2) +1));
		print($row["date"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["gn"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		if ($row["win"] == "Yes") {
			$res = "Win";
		} else {
			$res = "Lose";
		}
		print($res);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["zone"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
                if ($row["subzone"] == "") {
                        $szone = '&nbsp;';
                } else {
                        $szone = $row["subzone"];
                }
		print($szone);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["level"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["mylevel"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["diff"]);
		print("</td>");
		rankLeft((($striping_counter % 2) +1));
		print($row["group"]);
		print("</td>");
	}

	borderBottom(9);
	print($tableFooter);
} else if ($type == "guildinfo") {
	if (isset($_GET['guild'])) {
		$guild = $_GET['guild'];
	} else {
		$guild = "";
	}
	if (isset($_GET['s'])) {
		$sort = $_GET['s'];
	} else {
		$sort = "";
	}

	print('<br><small>Kill/Loss history for Guild "');
	print ($guild);
	print ('"</small><br><br>');
	print($tableHeader);
	borderTop(10);
	$url = '<a href="indexPvp.php?type=guildinfo&guild='.$guild;
	tableHeaderRow(array(
		$url.'&s=date">When</a>',
		$url.'&s=name">Them</a>',
		$url.'&s=name">Us</a>',
		$url.'&s=result">Result</a>',
		$url.'&s=zone">Zone</a>',
		$url.'&s=subzone">Subzone</a>',
		$url.'&s=level">Their Level</a>',
		$url.'&s=mylevel">Your Level</a>',
		$url.'&s=diff">Diff</a>',
		$url.'&s=group">Group</a>',
	));


	$query = "SELECT pvp2.*, members.name AS gn FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE pvp2.guild = '";
	$query = $query.$guild;
	$query=$query."'";

	  if ($sort == "name") {
		  $query=$query." ORDER BY 'name', 'level' DESC, 'guild'";
	  } else if ($sort == "race") {
		  $query=$query." ORDER BY 'race', 'name', 'level' DESC";
	  } else if ($sort == "class") {
		  $query=$query." ORDER BY 'class', 'name', 'level' DESC";
	  } else if ($sort == "level") {
		  $query=$query." ORDER BY 'level' DESC, 'name' ";
	  } else if ($sort == "mylevel") {
		  $query=$query." ORDER BY 'mylevel' DESC, 'name' ";
	  } else if ($sort == "diff") {
		  $query=$query." ORDER BY 'diff' DESC, 'name' ";
	  } else if ($sort == "result") {
		  $query=$query." ORDER BY 'win' DESC, 'name' ";
	  } else if ($sort == "zone") {
		  $query=$query." ORDER BY 'zone', 'name' ";
	  } else if ($sort == "subzone") {
		  $query=$query." ORDER BY 'subzone', 'name' ";
	  } else if ($sort == "group") {
		  $query=$query." ORDER BY 'group', 'name' ";
	  } else if ($sort == "date") {
		  $query=$query." ORDER BY 'date' DESC, 'name' ";
	  } else {
		  $query=$query." ORDER BY 'date' DESC, 'name' ";
	  }

	$result = mysql_query($query) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankRight((($striping_counter % 2) +1));
		print($row["date"]);
		print("</div></td>");
		rankMid((($striping_counter % 2) +1));
		print($row["name"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["gn"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		if ($row["win"] == "Yes") {
			$res = "Win";
		} else {
			$res = "Lose";
		}
		print($res);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["zone"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		if ($row["subzone"] == "") {
			$szone = '&nbsp;';
		} else {
			$szone = $row["subzone"];
		}
		print($szone);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["level"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["mylevel"]);
		print("</td>");
		rankMid((($striping_counter % 2) +1));
		print($row["diff"]);
		print("</td>");
		rankLeft((($striping_counter % 2) +1));
		print($row["group"]);
		print("</div></td></tr>");
	}

	borderBottom(10);
	print($tableFooter);
}

print "<br><span id=\"last_update\" class=\"last_update\">Last updated $updateTime</span>";
?>