<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

require_once( 'settings.php' );

//---[ Check for Guild Info ]------------
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}
// Get guild_id from guild info check above
$guildId = $guild_info['guild_id'];


$striping_counter = 0;
$tableHeader = "\n".'<!-- Begin HSLIST -->
<div id="HSLIST_col"'.$pvp_hs_colapse.'>
'.border('sgray','start',"<div style=\"cursor:pointer;width:350px;\" onclick=\"swapShow('HSLIST_col','HSLIST_full')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" alt=\"\" />".$wordings[$roster_conf['roster_lang']]['hslist'].'</div>').'
'.border('sgray','end').'
</div>
<div id="HSLIST_full"'.$pvp_hs_full.'>
'.border('sgray','start',"<div style=\"cursor:pointer;width:350px;\" onclick=\"swapShow('HSLIST_col','HSLIST_full')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" alt=\"\" />".$wordings[$roster_conf['roster_lang']]['hslist'].'</div>').'
<table width="100%" cellpadding="0" cellspacing="0" class="bodyline">'."\n";

$tableFooter = "</table>\n".border('sgray','end')."\n</div>\n<!-- End HSLIST -->\n";

function rankLeft($sc)
{
	print '    <td class="membersRow'.$sc.'">';
}
function rankMid($sc)
{
	print '    <td class="membersRow'.$sc.'">';
}
function rankRight($sc)
{
	print '    <td class="membersRowRight'.$sc.'">';
}

print($tableHeader);



//Highest Ranking Player:
$query = "SELECT `name`, `RankName` FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `RankInfo` DESC LIMIT 0,1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexhonor.php">'.$wordings[$roster_conf['roster_lang']]['hslist1'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	$playername = $row['name'];
	print($playername);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	if ($row['RankName'] == '')
	{
		print('&nbsp;');
	}
	else
	{
		print($row['RankName']);
	}
	print("</td>\n  </tr>\n");
}

//Highest Weekly Standing:
$query = "SELECT `name`, `lastweekRank` FROM `".ROSTER_PLAYERSTABLE."` WHERE `lastweekRank` > 0 ORDER BY `lastweekRank` LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexhonor.php?s=lastweekRank">'.$wordings[$roster_conf['roster_lang']]['hslist2'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lastweekRank']);
	print("</td>\n  </tr>\n");
}

//Highest Weekly HKs
$query = "SELECT `name`, `lastweekHK` FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `lastweekHK` DESC, `lastweekRank` DESC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexhonor.php?s=lastweekHK">'.$wordings[$roster_conf['roster_lang']]['hslist3'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lastweekHK']);
	print("</td>\n  </tr>\n");
}

//Highest Weekly DKs
$query = "SELECT `name`, `lastweekDK` FROM `".ROSTER_PLAYERSTABLE."` WHERE `lastweekDK` > 0 ORDER BY `lastweekDK` DESC, `lastweekHK` ASC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexhonor.php?s=lastweekDK">'.$wordings[$roster_conf['roster_lang']]['hslist4'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lastweekDK']);
	print("</td>\n  </tr>\n");
}

//Highest Weekly CPs
$query = "SELECT `name`, `lastweekContribution` FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `lastweekContribution` DESC, `lastweekRank` DESC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexhonor.php?s=lastweekContribution">'.$wordings[$roster_conf['roster_lang']]['hslist5'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lastweekContribution']);
	print("</td>\n  </tr>\n");
}

//Highest Lifetime Rank
$query = "SELECT `name`, `lifetimeRankName` FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `lifetimeHighestRank`DESC, `lifetimeHK` DESC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexhonor.php?s=lifetimeRankName">'.$wordings[$roster_conf['roster_lang']]['hslist6'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	if ($row['lifetimeRankName'] == '')
	{
		print('&nbsp;');
	}
	else
	{
		print($row['lifetimeRankName']);
	}
	print("</td>\n  </tr>\n");
}

//Highest LifeTime HKs
$query = "SELECT `name`, `lifetimeHK` FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `lifetimeHK` DESC, `lifetimeHighestRank` DESC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexhonor.php?s=lifetimeHK">'.$wordings[$roster_conf['roster_lang']]['hslist7'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lifetimeHK']);
	print("</td>\n  </tr>\n");
}

//Highest LifeTime DKs
$query = "SELECT `name`, `lifetimeDK` FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `lifetimeDK` DESC, `lifetimeHK` ASC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexhonor.php?s=lifetimeDK">'.$wordings[$roster_conf['roster_lang']]['hslist8'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lifetimeDK']);
	print("</td>\n  </tr>\n");
}

//Best Weekly HK-CP Average
$query = "SELECT `name`, (`lastweekContribution`/`lastweekHK`) AS average FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `average` DESC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if($row['average']=='')
{
	$ave='&nbsp;';
}
else
{
	$ave= $row['average'];
}

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexhonor.php">'.$wordings[$roster_conf['roster_lang']]['hslist9'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($ave);
	print("</td>\n  </tr>\n");
}

print($tableFooter);
$wowdb->free_result($result);
?>