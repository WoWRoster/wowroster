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
$guild_info = $wowdb->get_guild_info($roster_conf['server_name'],$roster_conf['guild_name']);
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}
// Get guild_id from guild info check above
$guildId = $guild_info['guild_id'];


$striping_counter = 0;
$tableHeader = '
<!-- Begin HSLIST -->
'.border('sgray','start','<div style="cursor:pointer;width:370px;" onclick="showHide(\'hs_table\',\'hs_img\',\''.$roster_conf['img_url'].'minus.gif\',\''.$roster_conf['img_url'].'plus.gif\');">
	<div style="display:inline;float:right;"><img id="hs_img" src="'.$roster_conf['img_url'].$pvp_hs_image.'.gif" alt="" /></div>
'.$wordings[$roster_conf['roster_lang']]['hslist'].'</div>').'
<table width="100%" cellpadding="0" cellspacing="0" class="bodyline" id="hs_table"'.$pvp_hs_colapse.'>'."\n";


$tableFooter = "</table>\n".border('sgray','end')."\n<!-- End HSLIST -->\n";

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
	print('<a href="indexhonor.php?s=lifetimeRankName">'.$wordings[$roster_conf['roster_lang']]['hslist2'].'</a></td>'."\n");
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
	print('<a href="indexhonor.php?s=lifetimeHK">'.$wordings[$roster_conf['roster_lang']]['hslist3'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lifetimeHK']);
	print("</td>\n  </tr>\n");
}

//Highest LifeTime CPs
$query = "SELECT `name`, `lifetimeCP` FROM `".ROSTER_PLAYERSTABLE."` ORDER BY `lifetimeCP` DESC, `lifetimeHighestRank` DESC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="indexhonor.php?s=lifetimeCP">'.$wordings[$roster_conf['roster_lang']]['hslist4'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lifetimeCP']);
	print("</td>\n  </tr>\n");
}

print($tableFooter);
$wowdb->free_result($result);
?>