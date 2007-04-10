<?php
$versions['versionDate']['hsList'] = '$Date: 2006/01/26 02:25:20 $'; 
$versions['versionRev']['hsList'] = '$Revision: 1.8 $'; 
$versions['versionAuthor']['hsList'] = '$Author: nostrademous $';

require_once 'conf.php';
require_once 'lib/wowdb.php';

// Establish our connection and select our database
$link = mysql_connect($db_host, $db_user, $db_passwd) or die ("Could not connect to desired database.");
mysql_select_db($db_name) or die ("Could not select desired database");

$server_name_escape = $wowdb->escape($server_name);
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '%b %d %l%p') from `".ROSTER_GUILDTABLE."` guild where guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_row($result)) {
	$guildId = $row[0];
	$updateTime = $row[1];
} else {
	die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}

$striping_counter = 0;
$tableHeader = "\n".'<!-- Begin HSLIST -->
<table cellpadding="0" cellspacing="0" class="membersList">'."\n";
$borderTop = '  <tr>
    <th colspan="3" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th>
  </tr>'."\n";
$tableHeaderRow = '  <tr>
    <th class="rankbordercenterleft"><div class="membersHeader">'.$guild_name.$wordings[$roster_lang]['hslist'].'</div></th>
    <th class="membersHeader">&nbsp;</th>
    <th class="rankbordercenterright"><div class="membersHeaderRight">&nbsp;</div></th>
  </tr>'."\n";
$borderBottom = '  <tr>
    <th colspan="3" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></th>
  </tr>'."\n";
$tableFooter = "</table>\n<!-- End HSLIST -->\n";

function rankLeft($sc) {
	print '    <td class="rankbordercenterleft"><div class="membersRow'.$sc.'">';
}
function rankMid($sc) {
	print '    <td class="membersRow'.$sc.'">';
}
function rankRight($sc) {
	print '    <td class="rankbordercenterright"><div class="membersRowRight'.$sc.'">';
}

print($tableHeader);
print ($borderTop);
print ($tableHeaderRow);

$link = mysql_connect($db_host, $db_user, $db_passwd) or die("Could not connect");
mysql_select_db($db_name) or die("Could not select DB");

//Highest Ranking Player:
$query = "SELECT name, RankName FROM `".ROSTER_PLAYERSTABLE."` ORDER BY RankInfo DESC LIMIT 0,1";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexHonor.php">'.$wordings[$roster_lang]['hslist1'].'</a></div></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	$playername = $row['name'];
	print($playername);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['RankName']);
	print("</div></td>\n  </tr>\n");
}

//Highest Weekly Standing: 
$query = "SELECT name,lastweekRank FROM `".ROSTER_PLAYERSTABLE."` WHERE `lastweekRank` > 0 ORDER BY `lastweekRank` LIMIT 0 , 1"; 
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexHonor.php?s=lastweekRank">'.$wordings[$roster_lang]['hslist2'].'</a></div></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lastweekRank']);
	print("</div></td>\n  </tr>\n");
}

//Highest Weekly HKs
$query = "SELECT name,lastweekHK FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `lastweekHK` DESC, `lastweekRank` DESC LIMIT 0 , 1";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexHonor.php?s=lastweekHK">'.$wordings[$roster_lang]['hslist3'].'</a></div></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lastweekHK']);
	print("</div></td>\n  </tr>\n");
}

//Highest Weekly DKs 
$query = "SELECT name,lastweekDK FROM `".ROSTER_PLAYERSTABLE."` WHERE `lastweekDK` > 0 ORDER BY `lastweekDK` DESC, `lastweekHK` ASC LIMIT 0 , 1"; 
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexHonor.php?s=lastweekDK">'.$wordings[$roster_lang]['hslist4'].'</a></div></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lastweekDK']);
	print("</div></td>\n  </tr>\n");
}

//Highest Weekly CPs
$query = "SELECT name,lastweekContribution FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `lastweekContribution` DESC, `lastweekRank` DESC LIMIT 0 , 1";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexHonor.php?s=lastweekContribution">'.$wordings[$roster_lang]['hslist5'].'</a></div></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lastweekContribution']);
	print("</div></td>\n  </tr>\n");
}

//Highest Lifetime Rank
$query = "SELECT name,lifetimeRankName FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `lifetimeHighestRank`DESC, `lifetimeHK` DESC LIMIT 0 , 1";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexHonor.php?s=lifetimeRankName">'.$wordings[$roster_lang]['hslist6'].'</a></div></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lifetimeRankName']);
	print("</div></td>\n  </tr>\n");
}

//Highest LifeTime HKs
$query = "SELECT name,lifetimeHK FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `lifetimeHK` DESC, `lifetimeHighestRank` DESC LIMIT 0 , 1";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexHonor.php?s=lifetimeHK">'.$wordings[$roster_lang]['hslist7'].'</a></div></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lifetimeHK']);
	print("</div></td>\n  </tr>\n");
}

//Highest LifeTime DKs 
$query = "SELECT name,lifetimeDK FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `lifetimeDK` DESC, `lifetimeHK` ASC LIMIT 0 , 1"; 
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexHonor.php?s=lifetimeDK">'.$wordings[$roster_lang]['hslist8'].'</a></div></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lifetimeDK']);
	print("</div></td>\n  </tr>\n");
}

//Best Weekly HK-CP Average
$query = "SELECT `name`, (`lastweekContribution`/`lastweekHK`) as average FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `average` DESC LIMIT 0 , 1";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc( $result );
if($row['average']=='') { $ave='&nbsp;'; } 
else { $ave= $row['average']; }
if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexHonor.php">'.$wordings[$roster_lang]['hslist9'].'</a></div></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($ave);
	print("</div></td>\n  </tr>\n");
}

print($borderBottom);
print($tableFooter);
mysql_free_result($result);
?>
