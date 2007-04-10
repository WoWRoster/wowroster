<?php
$versions['versionDate']['pvplist'] = '$Date: 2006/01/23 10:00:30 $'; 
$versions['versionRev']['pvplist'] = '$Revision: 1.7 $'; 
$versions['versionAuthor']['pvplist'] = '$Author: sphinx $';

require_once 'conf.php';
require_once 'lib/wowdb.php';

// Establish our connection and select our database
$link = mysql_connect($db_host, $db_user, $db_passwd) or die ("Could not connect to desired database.");
mysql_select_db($db_name) or die ("Could not select desired database");

$server_name_escape = $wowdb->escape($server_name);
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$roster_lang]."') FROM `".ROSTER_GUILDTABLE."` WHERE guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = mysql_query($query) or die(__LINE__.":".mysql_error());

if ($row = mysql_fetch_row($result)) {
	$guildId = $row[0];
	$updateTime = $row[1];
} else {
	die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}

$striping_counter = 0;
$tableHeader = "\n".'<!-- Begin PvPLIST -->
<table cellpadding="0" cellspacing="0" class="membersList">'."\n";
$borderTop = '  <tr>
    <th colspan="3" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th>
  </tr>'."\n";
$tableHeaderRow = '  <tr>
    <th class="rankbordercenterleft"><div class="membersHeader">'.$guild_name.$wordings[$roster_lang]['pvplist'].'</div></th>
    <th class="membersHeader">&nbsp;</th>
    <th class="rankbordercenterright"><div class="membersHeaderRight">&nbsp;</div></th>
  </tr>'."\n";
$borderBottom = '  <tr>
    <th colspan="3" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></th>
  </tr>'."\n";
$tableFooter = "</table>\n<!-- End PvPLIST -->\n";

function pvprankRight($sc) {
	print '    <td class="rankbordercenterleft"><div class="membersRow'.$sc.'">';
}
function pvprankMid($sc) {
	print '    <td class="membersRow'.$sc.'">';
}
function pvprankLeft($sc) {
	print '    <td class="rankbordercenterright"><div class="membersRowRight'.$sc.'">';
}

print($tableHeader);
print ($borderTop);
print ($tableHeaderRow);

$link = mysql_connect($db_host, $db_user, $db_passwd) or die("Could not connect");
mysql_select_db($db_name) or die("Could not select DB");

$query = "SELECT guild, COUNT(guild) as countg FROM `".ROSTER_PVP2TABLE."` WHERE win = '1' AND enemy = '1' GROUP BY guild ORDER BY countg DESC";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	pvprankRight((($striping_counter % 2) +1));
	print('<a href="indexPvp.php?type=guildwins">'.$wordings[$roster_lang]['pvplist1'].'</a></div></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	if ($row['guild'] == '')
		$guildname = '(unguilded)';
	else
		$guildname = $row['guild'];
	print($guildname);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));
	print($row['countg']);
	print("</div></td>\n  </tr>\n");
}
$query = "SELECT guild, COUNT(guild) as countg FROM `".ROSTER_PVP2TABLE."` WHERE win = '0' AND enemy = '1' GROUP BY guild ORDER BY countg DESC";
$result = mysql_query($query) or die(__LINE__.":".mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	pvprankRight((($striping_counter % 2) +1));
	print('<a href="indexPvp.php?type=guildlosses">'.$wordings[$roster_lang]['pvplist2'].'</a></div></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	if ($row['guild'] == '') {
		$guildname = '(unguilded)';
	} else {
		$guildname = $row['guild'];
	}
	print($guildname);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));
	print($row['countg']);
	print("</div></td>\n  </tr>\n");
}
$query = "SELECT name, COUNT(name) as countg FROM `".ROSTER_PVP2TABLE."` WHERE win = '1' AND enemy = '1' GROUP BY name ORDER BY countg DESC";
$result = mysql_query($query) or die(__LINE__.":".mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	pvprankRight((($striping_counter % 2) +1));
	print('<a href="indexPvp.php?type=enemywins">'.$wordings[$roster_lang]['pvplist3'].'</a></div></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));
	print($row['countg']);
	print("</div></td>\n  </tr>\n");
}
$query = "SELECT name, COUNT(name) as countg FROM `".ROSTER_PVP2TABLE."` WHERE win = '0' AND enemy = '1' GROUP BY name ORDER BY countg DESC";
$result = mysql_query($query) or die(__LINE__.":".mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	pvprankRight((($striping_counter % 2) +1));
	print('<a href="indexPvp.php?type=enemylosses">'.$wordings[$roster_lang]['pvplist4'].'</a></div></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));
	print($row['countg']);
	print("</div></td>\n  </tr>\n");
}
$query = "SELECT pvp2.member_id, members.name as gn, COUNT(pvp2.member_id) AS countg FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE win = '1' AND enemy = '1' GROUP BY pvp2.member_id ORDER BY countg DESC";
$result = mysql_query($query) or die(__LINE__.":".mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;

	pvprankRight((($striping_counter % 2) +1));
	print('<a href="indexPvp.php?type=purgewins">'.$wordings[$roster_lang]['pvplist5'].'</a></div></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	print($row['gn']);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));
	print($row['countg']);
	print("</div></td>\n  </tr>\n");
}
$query = "SELECT pvp2.member_id, members.name as gn, COUNT(pvp2.member_id) as countg FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE win = '0' AND enemy = '1' GROUP BY pvp2.member_id ORDER BY countg DESC";
$result = mysql_query($query) or die(__LINE__.":".mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;

	pvprankRight((($striping_counter % 2) +1));
	print('<a href="indexPvp.php?type=purgelosses">'.$wordings[$roster_lang]['pvplist6'].'</a></div></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	print($row['gn']);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));
	print($row['countg']);
	print("</div></td>\n  </tr>\n");
}
$query = "SELECT pvp2.member_id, members.name as gn, AVG(pvp2.`leveldiff`) as ave, COUNT(pvp2.member_id) as countg FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE win = '1' AND enemy = '1' GROUP BY pvp2.member_id ORDER BY ave DESC";
$result = mysql_query($query) or die(__LINE__.":".mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;

	pvprankRight((($striping_counter % 2) +1));
	print('<a href="indexPvp.php?type=purgeavewins">'.$wordings[$roster_lang]['pvplist7'].'</a></div></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	print($row['gn']);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));

	$ave = round($row['ave'], 2);

	if ($ave > 0) {
		$ave = '+'.$ave;
	}
	print($ave);
	print("</div></td>\n  </tr>\n");
}
$query = "SELECT pvp2.member_id, members.name as gn, AVG(pvp2.`leveldiff`) as ave, COUNT(pvp2.member_id) as countg FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE win = '0' AND enemy = '1' GROUP BY pvp2.member_id ORDER BY ave DESC";
$result = mysql_query($query) or die(__LINE__.":".mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	pvprankRight((($striping_counter % 2) +1));
	print('<a href="indexPvp.php?type=purgeavelosses">'.$wordings[$roster_lang]['pvplist8'].'</a></div></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	print($row['gn']);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));

	$ave = round($row['ave'], 2);

	if ($ave > 0) {
		$ave = '+'.$ave;
	}
	print($ave);
	print("</div></td>\n  </tr>\n");
}
print($borderBottom);
print($tableFooter);
mysql_free_result($result);
?>