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

require_once ('settings.php');

//---[ Check for Guild Info ]------------
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}


$striping_counter = 0;
$tableHeader = '
<!-- Begin PvPLIST -->
'.border('sgray','start','<div style="cursor:pointer;width:370px;" onclick="showHide(\'pvp_table\',\'pvp_img\',\''.$roster_conf['img_url'].'minus.gif\',\''.$roster_conf['img_url'].'plus.gif\');">
	<div style="display:inline;float:right;"><img id="pvp_img" src="'.$roster_conf['img_url'].$pvp_hs_image.'.gif" alt="" /></div>
'.$wordings[$roster_conf['roster_lang']]['pvplist'].'</div>').'
<table width="100%" cellpadding="0" cellspacing="0" class="bodyline" id="pvp_table"'.$pvp_hs_colapse.'>'."\n";


$tableFooter = "</table>\n".border('sgray','end')."\n<!-- End PvPLIST -->\n";

function pvprankCell()
{
	print '    <td class="membersRowCell">';
}
function pvprankRight()
{
	print '    <td class="membersRowRightCell">';
}

print($tableHeader);


$query = "SELECT `guild`, COUNT(`guild`) AS countg FROM `".ROSTER_PVP2TABLE."` AS pvp INNER JOIN `".ROSTER_MEMBERSTABLE."` AS member ON `pvp`.`member_id` = `member`.`member_id` WHERE `win` = '1' AND `enemy` = '1' AND `member`.`guild_id` = ".$guild_info['guild_id']." GROUP BY `guild` ORDER BY `countg` DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );
if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	pvprankCell();
	print('<a href="guildpvp.php?type=guildwins">'.$wordings[$roster_conf['roster_lang']]['pvplist1'].'</a></td>'."\n");
	pvprankCell();
	if ($row['guild'] == '')
		$guildname = '(unguilded)';
	else
		$guildname = $row['guild'];
	print($guildname);
	print("</td>\n");
	pvprankRight();
	print($row['countg']);
	print("</td>\n  </tr>\n");
}


$query = "SELECT `guild`, COUNT(`guild`) AS countg FROM `".ROSTER_PVP2TABLE."` AS pvp INNER JOIN `".ROSTER_MEMBERSTABLE."` AS member ON `pvp`.`member_id` = `member`.`member_id` WHERE `win` = '0' AND `enemy` = '1' AND `guild_id` = ".$guild_info['guild_id']." GROUP BY `guild` ORDER BY countg DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );
if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	pvprankCell();
	print('<a href="guildpvp.php?type=guildlosses">'.$wordings[$roster_conf['roster_lang']]['pvplist2'].'</a></td>'."\n");
	pvprankCell();
	if ($row['guild'] == '') {
		$guildname = '(unguilded)';
	} else {
		$guildname = $row['guild'];
	}
	print($guildname);
	print("</td>\n");
	pvprankRight();
	print($row['countg']);
	print("</td>\n  </tr>\n");
}


$query = "SELECT `pvp`.`name`, COUNT(`characters`.`name`) AS countg FROM `".ROSTER_PVP2TABLE."` AS pvp INNER JOIN `".ROSTER_MEMBERSTABLE."` AS member ON `pvp`.`member_id` = `member`.`member_id` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `member`.`member_id` = `characters`.`member_id` WHERE `win` = '1' AND `enemy` = '1' AND `guild_id` = ".$guild_info['guild_id']." GROUP BY `name` ORDER BY countg DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );
if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	pvprankCell();
	print('<a href="guildpvp.php?type=enemywins">'.$wordings[$roster_conf['roster_lang']]['pvplist3'].'</a></td>'."\n");
	pvprankCell();
	print($row['name']);
	print("</td>\n");
	pvprankRight();
	print($row['countg']);
	print("</td>\n  </tr>\n");
}


$query = "SELECT `pvp`.`name`, COUNT(`characters`.`name`) AS countg FROM `".ROSTER_PVP2TABLE."` AS pvp INNER JOIN `".ROSTER_MEMBERSTABLE."` AS member ON `pvp`.`member_id` = `member`.`member_id` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `member`.`member_id` = `characters`.`member_id` WHERE `win` = '0' AND `enemy` = '1' AND `guild_id` = ".$guild_info['guild_id']." GROUP BY `name` ORDER BY countg DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );
if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	pvprankCell();
	print('<a href="guildpvp.php?type=enemylosses">'.$wordings[$roster_conf['roster_lang']]['pvplist4'].'</a></td>'."\n");
	pvprankCell();
	print($row['name']);
	print("</td>\n");
	pvprankRight();
	print($row['countg']);
	print("</td>\n  </tr>\n");
}


$query = "SELECT `pvp`.`member_id`, `characters`.`name` AS gn, COUNT(`pvp`.`member_id`) AS `countg` FROM `".ROSTER_PVP2TABLE."` AS pvp INNER JOIN `".ROSTER_MEMBERSTABLE."` AS member ON `pvp`.`member_id` = `member`.`member_id` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `member`.`member_id` = `characters`.`member_id` WHERE win = '1' AND enemy = '1' AND `guild_id` = ".$guild_info['guild_id']." GROUP BY pvp.member_id ORDER BY countg DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );
if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	pvprankCell();
	print('<a href="guildpvp.php?type=purgewins">'.$wordings[$roster_conf['roster_lang']]['pvplist5'].'</a></td>'."\n");
	pvprankCell();
	print($row['gn']);
	print("</td>\n");
	pvprankRight();
	print($row['countg']);
	print("</td>\n  </tr>\n");
}


$query = "SELECT `pvp`.`member_id`, `characters`.`name` AS `gn`, COUNT(`pvp`.`member_id`) AS countg FROM `".ROSTER_PVP2TABLE."` AS pvp INNER JOIN `".ROSTER_MEMBERSTABLE."` AS member ON `pvp`.`member_id` = `member`.`member_id` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `member`.`member_id` = `characters`.`member_id` WHERE win = '0' AND enemy = '1' AND `guild_id` = ".$guild_info['guild_id']." GROUP BY pvp.member_id ORDER BY countg DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	pvprankCell();
	print('<a href="guildpvp.php?type=purgelosses">'.$wordings[$roster_conf['roster_lang']]['pvplist6'].'</a></td>'."\n");
	pvprankCell();
	print($row['gn']);
	print("</td>\n");
	pvprankRight();
	print($row['countg']);
	print("</td>\n  </tr>\n");
}


$query = "SELECT `pvp`.`member_id`, `characters`.`name` AS gn, AVG(`pvp`.`leveldiff`) AS ave, COUNT(`pvp`.`member_id`) AS countg FROM `".ROSTER_PVP2TABLE."` AS pvp INNER JOIN `".ROSTER_MEMBERSTABLE."` AS member ON `pvp`.`member_id` = `member`.`member_id` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `member`.`member_id` = `characters`.`member_id` WHERE win = '1' AND enemy = '1' AND `guild_id` = ".$guild_info['guild_id']." GROUP BY pvp.member_id ORDER BY ave DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	pvprankCell();
	print('<a href="guildpvp.php?type=purgeavewins">'.$wordings[$roster_conf['roster_lang']]['pvplist7'].'</a></td>'."\n");
	pvprankCell();
	print($row['gn']);
	print("</td>\n");
	pvprankRight();

	$ave = round($row['ave'], 2);

	if ($ave > 0) {
		$ave = '+'.$ave;
	}
	print($ave);
	print("</td>\n  </tr>\n");
}


$query = "SELECT `pvp`.`member_id`, `characters`.`name` AS gn, AVG(`pvp`.`leveldiff`) AS ave, COUNT(`pvp`.`member_id`) AS countg FROM `".ROSTER_PVP2TABLE."` AS `pvp` INNER JOIN `".ROSTER_MEMBERSTABLE."` AS `member` ON `pvp`.`member_id` = `member`.`member_id` INNER JOIN `".ROSTER_CHARACTERSTABLE."` AS `characters` ON `member`.`member_id` = `characters`.`member_id` WHERE win = '0' AND enemy = '1' AND `guild_id` = ".$guild_info['guild_id']." GROUP BY pvp.member_id ORDER BY ave DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Increment counter so rows are colored alternately
	++$striping_counter;

	// Striping rows
	print("  <tr class=\"membersRowColor".(($striping_counter % 2) +1)."\">\n");

	pvprankCell();
	print('<a href="guildpvp.php?type=purgeavelosses">'.$wordings[$roster_conf['roster_lang']]['pvplist8'].'</a></td>'."\n");
	pvprankCell();
	print($row['gn']);
	print("</td>\n");
	pvprankRight();

	$ave = round($row['ave'], 2);

	if ($ave > 0) {
		$ave = '+'.$ave;
	}
	print($ave);
	print("</td>\n  </tr>\n");
}

print($tableFooter);
$wowdb->free_result($result);
?>
