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
	message_die( $wordings[$roster_conf['roster_lang']]['nodata'] );
}
// Get guild info from guild info check above
$guildId = $guild_info['guild_id'];


$striping_counter = 0;
$tableHeader = '
<!-- Begin PvPLIST -->
'.border('sgray','start','<div style="cursor:pointer;width:370px;" onclick="showHide(\'pvp_table\',\'pvp_img\',\''.$roster_conf['img_url'].'minus.gif\',\''.$roster_conf['img_url'].'plus.gif\');">
	<div style="display:inline;float:right;"><img id="pvp_img" src="'.$roster_conf['img_url'].$pvp_hs_image.'.gif" alt="" /></div>
'.$wordings[$roster_conf['roster_lang']]['pvplist'].'</div>').'
<table width="100%" cellpadding="0" cellspacing="0" class="bodyline" id="pvp_table"'.$pvp_hs_colapse.'>'."\n";


$tableFooter = "</table>\n".border('sgray','end')."\n<!-- End PvPLIST -->\n";


function pvprankRight($sc)
{
	print '    <td class="membersRow'.$sc.'">';
}
function pvprankMid($sc)
{
	print '    <td class="membersRow'.$sc.'">';
}
function pvprankLeft($sc)
{
	print '    <td class="membersRowRight'.$sc.'">';
}

print($tableHeader);


$query = "SELECT `guild`, COUNT(`guild`) AS countg FROM `".ROSTER_PVP2TABLE."` WHERE `win` = '1' AND `enemy` = '1' GROUP BY `guild` ORDER BY countg DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );
if ($row)
{
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	pvprankRight((($striping_counter % 2) +1));
	print('<a href="'.getlink($module_name.'&amp;&file=indexpvp&amp;type=guildwins').'">'.$wordings[$roster_conf['roster_lang']]['pvplist1'].'</a></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	if ($row['guild'] == '')
		$guildname = '(unguilded)';
	else
		$guildname = $row['guild'];
	print($guildname);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));
	print($row['countg']);
	print("</td>\n  </tr>\n");
}


$query = "SELECT `guild`, COUNT(`guild`) AS countg FROM `".ROSTER_PVP2TABLE."` WHERE `win` = '0' AND `enemy` = '1' GROUP BY `guild` ORDER BY countg DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );
if ($row)
{
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	pvprankRight((($striping_counter % 2) +1));
	print('<a href="'.getlink($module_name.'&amp;&file=indexpvp&amp;type=guildlosses').'">'.$wordings[$roster_conf['roster_lang']]['pvplist2'].'</a></td>'."\n");
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
	print("</td>\n  </tr>\n");
}


$query = "SELECT `name`, COUNT(`name`) AS countg FROM `".ROSTER_PVP2TABLE."` WHERE `win` = '1' AND `enemy` = '1' GROUP BY `name` ORDER BY countg DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );
if ($row)
{
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	pvprankRight((($striping_counter % 2) +1));
	print('<a href="'.getlink($module_name.'&amp;&file=indexpvp&amp;type=').'">'.$wordings[$roster_conf['roster_lang']]['pvplist3'].'</a></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));
	print($row['countg']);
	print("</td>\n  </tr>\n");
}


$query = "SELECT `name`, COUNT(`name`) AS countg FROM `".ROSTER_PVP2TABLE."` WHERE `win` = '0' AND `enemy` = '1' GROUP BY `name` ORDER BY countg DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );
if ($row)
{
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	pvprankRight((($striping_counter % 2) +1));
	print('<a href="'.getlink($module_name.'&amp;&file=indexpvp&amp;type=enemylosses').'">'.$wordings[$roster_conf['roster_lang']]['pvplist4'].'</a></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));
	print($row['countg']);
	print("</td>\n  </tr>\n");
}


$query = "SELECT pvp2.member_id, members.name AS gn, COUNT(pvp2.member_id) AS countg FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE win = '1' AND enemy = '1' GROUP BY pvp2.member_id ORDER BY countg DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );
if ($row)
{
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;

	pvprankRight((($striping_counter % 2) +1));
	print('<a href="'.getlink($module_name.'&amp;&file=indexpvp&amp;type=purgewins').'">'.$wordings[$roster_conf['roster_lang']]['pvplist5'].'</a></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	print($row['gn']);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));
	print($row['countg']);
	print("</td>\n  </tr>\n");
}


$query = "SELECT pvp2.member_id, members.name AS gn, COUNT(pvp2.member_id) as countg FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE win = '0' AND enemy = '1' GROUP BY pvp2.member_id ORDER BY countg DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;

	pvprankRight((($striping_counter % 2) +1));
	print('<a href="'.getlink($module_name.'&amp;&file=indexpvp&amp;type=purgelosses').'">'.$wordings[$roster_conf['roster_lang']]['pvplist6'].'</a></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	print($row['gn']);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));
	print($row['countg']);
	print("</td>\n  </tr>\n");
}


$query = "SELECT pvp2.member_id, members.name as gn, AVG(pvp2.`leveldiff`) as ave, COUNT(pvp2.member_id) as countg FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE win = '1' AND enemy = '1' GROUP BY pvp2.member_id ORDER BY ave DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;

	pvprankRight((($striping_counter % 2) +1));
	print('<a href="'.getlink($module_name.'&amp;&file=indexpvp&amp;type=purgeavewins').'">'.$wordings[$roster_conf['roster_lang']]['pvplist7'].'</a></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	print($row['gn']);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));

	$ave = round($row['ave'], 2);

	if ($ave > 0) {
		$ave = '+'.$ave;
	}
	print($ave);
	print("</td>\n  </tr>\n");
}


$query = "SELECT pvp2.member_id, members.name as gn, AVG(pvp2.`leveldiff`) as ave, COUNT(pvp2.member_id) as countg FROM `".ROSTER_PVP2TABLE."` pvp2 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp2.member_id WHERE win = '0' AND enemy = '1' GROUP BY pvp2.member_id ORDER BY ave DESC";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $wowdb->fetch_assoc( $result );

if ($row)
{
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	pvprankRight((($striping_counter % 2) +1));
	print('<a href="'.getlink($module_name.'&amp;&file=indexpvp&amp;type=purgeavelosses').'">'.$wordings[$roster_conf['roster_lang']]['pvplist8'].'</a></td>'."\n");
	pvprankMid((($striping_counter % 2) +1));
	print($row['gn']);
	print("</td>\n");
	pvprankLeft((($striping_counter % 2) +1));

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