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


$striping_counter = 0;
$tableHeader = '
<!-- Begin HSLIST -->
'.border('sgray','start','<div style="cursor:pointer;width:370px;" onclick="showHide(\'hs_table\',\'hs_img\',\''.$roster_conf['img_url'].'minus.gif\',\''.$roster_conf['img_url'].'plus.gif\');">
	<div style="display:inline;float:right;"><img id="hs_img" src="'.$roster_conf['img_url'].$pvp_hs_image.'.gif" alt="" /></div>
'.$wordings[$roster_conf['roster_lang']]['hslist'].'</div>').'
<table width="100%" cellpadding="0" cellspacing="0" class="bodyline" id="hs_table"'.$pvp_hs_colapse.'>'."\n";


$tableFooter = "</table>\n".border('sgray','end')."\n<!-- End HSLIST -->\n";

function rankCell()
{
	print '    <td class="membersRowCell">';
}
function rankRight()
{
	print '    <td class="membersRowRightCell">';
}

print($tableHeader);



//Highest Ranking Player:
$query = "SELECT `name`, `RankName` FROM `".ROSTER_PLAYERSTABLE."` AS `players` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `players`.`member_id` = `characters`.`member_id` LEFT JOIN `".ROSTER_MEMBERSTABLE."` AS `members` ON `players`.`member_id` = `members`.`member_id` WHERE `guild_id` = ".$guild_info['guild_id']." ORDER BY `RankInfo` DESC LIMIT 0,1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	rankCell();
	print('<a href="honor.php">'.$wordings[$roster_conf['roster_lang']]['hslist1'].'</a></td>'."\n");
	rankCell();
	$playername = $row['name'];
	print($playername);
	print("</td>\n");
	rankRight();
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
$query = "SELECT `name`, `lastweekRank` FROM `".ROSTER_PLAYERSTABLE."` AS `players` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `players`.`member_id` = `characters`.`member_id` LEFT JOIN `".ROSTER_MEMBERSTABLE."` AS `members` ON `players`.`member_id` = `members`.`member_id` WHERE `guild_id` = ".$guild_info['guild_id']." AND `lastweekRank` > 0 ORDER BY `lastweekRank` LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	rankCell();
	print('<a href="honor.php?s=lastweekRank">'.$wordings[$roster_conf['roster_lang']]['hslist2'].'</a></td>'."\n");
	rankCell();
	print($row['name']);
	print("</td>\n");
	rankRight();
	print($row['lastweekRank']);
	print("</td>\n  </tr>\n");
}

//Highest Weekly HKs
$query = "SELECT `name`, `lastweekHK` FROM `".ROSTER_PLAYERSTABLE."` AS `players` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `players`.`member_id` = `characters`.`member_id` LEFT JOIN `".ROSTER_MEMBERSTABLE."` AS `members` ON `players`.`member_id` = `members`.`member_id` WHERE `guild_id` = ".$guild_info['guild_id']." ORDER BY `lastweekHK` DESC, `lastweekRank` DESC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	rankCell();
	print('<a href="honor.php?s=lastweekHK">'.$wordings[$roster_conf['roster_lang']]['hslist3'].'</a></td>'."\n");
	rankCell();
	print($row['name']);
	print("</td>\n");
	rankRight();
	print($row['lastweekHK']);
	print("</td>\n  </tr>\n");
}

//Highest Weekly DKs
$query = "SELECT `name`, `lastweekDK` FROM `".ROSTER_PLAYERSTABLE."` AS `players` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `players`.`member_id` = `characters`.`member_id` LEFT JOIN `".ROSTER_MEMBERSTABLE."` AS `members` ON `players`.`member_id` = `members`.`member_id` WHERE `guild_id` = ".$guild_info['guild_id']." AND `lastweekDK` > 0 ORDER BY `lastweekDK` DESC, `lastweekHK` ASC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	rankCell();
	print('<a href="honor.php?s=lastweekDK">'.$wordings[$roster_conf['roster_lang']]['hslist4'].'</a></td>'."\n");
	rankCell();
	print($row['name']);
	print("</td>\n");
	rankRight();
	print($row['lastweekDK']);
	print("</td>\n  </tr>\n");
}

//Highest Weekly CPs
$query = "SELECT `name`, `lastweekContribution` FROM `".ROSTER_PLAYERSTABLE."` AS `players` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `players`.`member_id` = `characters`.`member_id` LEFT JOIN `".ROSTER_MEMBERSTABLE."` AS `members` ON `players`.`member_id` = `members`.`member_id` WHERE `guild_id` = ".$guild_info['guild_id']." ORDER BY `lastweekContribution` DESC, `lastweekRank` DESC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	rankCell();
	print('<a href="honor.php?s=lastweekContribution">'.$wordings[$roster_conf['roster_lang']]['hslist5'].'</a></td>'."\n");
	rankCell();
	print($row['name']);
	print("</td>\n");
	rankRight();
	print($row['lastweekContribution']);
	print("</td>\n  </tr>\n");
}

//Highest Lifetime Rank
$query = "SELECT `name`, `lifetimeRankName` FROM `".ROSTER_PLAYERSTABLE."` AS `players` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `players`.`member_id` = `characters`.`member_id` LEFT JOIN `".ROSTER_MEMBERSTABLE."` AS `members` ON `players`.`member_id` = `members`.`member_id` WHERE `guild_id` = ".$guild_info['guild_id']." ORDER BY `lifetimeHighestRank`DESC, `lifetimeHK` DESC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	rankCell();
	print('<a href="honor.php?s=lifetimeRankName">'.$wordings[$roster_conf['roster_lang']]['hslist6'].'</a></td>'."\n");
	rankCell();
	print($row['name']);
	print("</td>\n");
	rankRight();
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
$query = "SELECT `name`, `lifetimeHK` FROM `".ROSTER_PLAYERSTABLE."` AS `players` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `players`.`member_id` = `characters`.`member_id` LEFT JOIN `".ROSTER_MEMBERSTABLE."` AS `members` ON `players`.`member_id` = `members`.`member_id` WHERE `guild_id` = ".$guild_info['guild_id']." ORDER BY `lifetimeHK` DESC, `lifetimeHighestRank` DESC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	rankCell();
	print('<a href="honor.php?s=lifetimeHK">'.$wordings[$roster_conf['roster_lang']]['hslist7'].'</a></td>'."\n");
	rankCell();
	print($row['name']);
	print("</td>\n");
	rankRight();
	print($row['lifetimeHK']);
	print("</td>\n  </tr>\n");
}

//Highest LifeTime DKs
$query = "SELECT `name`, `lifetimeDK` FROM `".ROSTER_PLAYERSTABLE."` AS `players` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `players`.`member_id` = `characters`.`member_id` LEFT JOIN `".ROSTER_MEMBERSTABLE."` AS `members` ON `players`.`member_id` = `members`.`member_id` WHERE `guild_id` = ".$guild_info['guild_id']." ORDER BY `lifetimeDK` DESC, `lifetimeHK` ASC LIMIT 0 , 1";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	rankCell();
	print('<a href="honor.php?s=lifetimeDK">'.$wordings[$roster_conf['roster_lang']]['hslist8'].'</a></td>'."\n");
	rankCell();
	print($row['name']);
	print("</td>\n");
	rankRight();
	print($row['lifetimeDK']);
	print("</td>\n  </tr>\n");
}

//Best Weekly HK-CP Average
$query = "SELECT `name`, (`lastweekContribution`/`lastweekHK`) AS average FROM `".ROSTER_PLAYERSTABLE."` AS `players` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `players`.`member_id` = `characters`.`member_id` LEFT JOIN `".ROSTER_MEMBERSTABLE."` AS `members` ON `players`.`member_id` = `members`.`member_id` WHERE `guild_id` = ".$guild_info['guild_id']." ORDER BY `average` DESC LIMIT 0 , 1";
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

if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	rankCell();
	print('<a href="honor.php">'.$wordings[$roster_conf['roster_lang']]['hslist9'].'</a></td>'."\n");
	rankCell();
	print($row['name']);
	print("</td>\n");
	rankRight();
	print($ave);
	print("</td>\n  </tr>\n");
}

print($tableFooter);
$wowdb->free_result($result);
?>
