<?php
/******************************
 * worldofwarcraftguilds.com
 * Copyright 2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 ******************************/

// Check if we already assigned something to $content, if not, Declare it
if (!isset($content))
{
	$content = '';
}

// Server (for public roster use)
$server_name = $roster_conf['server_name'];

//Faction Selection
$query = "SELECT `guild_id` FROM `" . ROSTER_GUILDTABLE . "` where `guild_name` = '" . $roster_conf['guild_name'] . "' and `server` ='" . addslashes($roster_conf['server_name']) . "'"; 

if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}


$result = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
if ($row = $wowdb->fetch_array($result)) 
{ 
	$guildId = $row['guild_id'];
} 
else 
{ 
	die_quietly($nodata[$roster_conf['roster_lang']], 'Database Error', basename(__FILE__), __LINE__);
} 

// Lets make the basis for most of the SQL statements right here.
$MemberSelect = "SELECT * FROM `" . ROSTER_MEMBERSTABLE . "`";
$whereclause = " WHERE `" . ROSTER_MEMBERSTABLE . "`.`guild_id` = " . $guildId;
if (!$include_bank) 
	$whereclause .= " AND `" . ROSTER_MEMBERSTABLE . "`.`" . $roster_conf['banker_fieldname'] . "` NOT LIKE '%" . $roster_conf['banker_rankname'] . "%'";
$where1 = $whereclause . " AND `" . ROSTER_MEMBERSTABLE . "`.`" . $roster_conf['alt_location'] . "` NOT LIKE '%" . $roster_conf['alt_type'] . "%'";
$where2 = $whereclause . " AND `" . ROSTER_MEMBERSTABLE . "`.`" . $roster_conf['alt_location'] . "` LIKE '%" . $roster_conf['alt_type'] . "%'";
$select1 = $MemberSelect . $where1;
$select2 = $MemberSelect . $where2;

$select3 = $MemberSelect . " INNER JOIN `" . ROSTER_PLAYERSTABLE . "` ON `" . ROSTER_MEMBERSTABLE . "`.`member_id` = `" . ROSTER_PLAYERSTABLE . "`.`member_id` " . $whereclause;

$classquery = "SELECT `class`, count(`member_id`) FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " GROUP BY `class` ORDER BY `class`";

$tableFooter = "	</table>\n</table>\n" . border('syellow','end') ;

$content .= "<br />\n" . tableheader("BEST", localize("guildbestworst"), 'syellow') . "\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
$content .= '   <td valign="top" class="row">';

$content .= "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"2\">
	" ;
// Lets do the class with the most and least number of members
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " GROUP BY `class` ORDER BY `classcount` DESC LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classmostmembers'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " GROUP BY `class` ORDER BY `classcount` LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classleastmembers'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;

// Classes with the highest and lowest average class level
$query = "SELECT `class`, avg(`level`) as `classaverage` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " GROUP BY `class` ORDER BY `classaverage` DESC LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classhiaverage'), $classRow['class'], $classRow['classaverage'], true, true) . "
	" ;
$query = "SELECT `class`, avg(`level`) as `classaverage` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " GROUP BY `class` ORDER BY `classaverage` LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classloaverage'), $classRow['class'], $classRow['classaverage'], true, true) . "
	" ;

// Classes with the most and least number of members at level 60
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` = 60 GROUP BY `class` ORDER BY `classcount` DESC LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classmost60'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` = 60 GROUP BY `class` ORDER BY `classcount` LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classleast60'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;

// Classes with the most and least number of members between level 50 and 59
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` > 49 AND `level` < 60 GROUP BY `class` ORDER BY `classcount` DESC LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classmost50'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` > 49 AND `level` < 60 GROUP BY `class` ORDER BY `classcount` LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classleast50'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;

// Classes with the most and least number of members between level 40 and 49
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` > 39 AND `level` < 50 GROUP BY `class` ORDER BY `classcount` DESC LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classmost40'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` > 39 AND `level` < 50 GROUP BY `class` ORDER BY `classcount` LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classleast40'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;

// Classes with the most and least number of members between level 30 and 39
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` > 29 AND `level` < 40 GROUP BY `class` ORDER BY `classcount` DESC LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classmost30'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` > 29 AND `level` < 40 GROUP BY `class` ORDER BY `classcount` LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classleast30'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;

// Classes with the most and least number of members between level 20 and 29
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` > 19 AND `level` < 30 GROUP BY `class` ORDER BY `classcount` DESC LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classmost20'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` > 19 AND `level` < 30 GROUP BY `class` ORDER BY `classcount` LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classleast20'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;

// Classes with the most and least number of members between level 10 and 19
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` > 9 AND `level` < 20 GROUP BY `class` ORDER BY `classcount` DESC LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classmost10'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` > 9 AND `level` < 20 GROUP BY `class` ORDER BY `classcount` LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classleast10'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;

// Classes with the most and least number of members between level 1 and 9
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` > 0 AND `level` < 10 GROUP BY `class` ORDER BY `classcount` DESC LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classmost1'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;
$query = "SELECT `class`, count(`member_id`) as `classcount` FROM `" . ROSTER_MEMBERSTABLE . "` " . $whereclause . " AND `level` > 0 AND `level` < 10 GROUP BY `class` ORDER BY `classcount` LIMIT 1";
$result_menu = $wowdb->query($query) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $query);
$classRow = $wowdb->getrow($result_menu);
$content .= addTopTableLine(localize('classleast1'), $classRow['class'], $classRow['classcount'], false, false) . "
	" ;

$content .= $tableFooter;

$result_menu = $wowdb->query($MemberSelect . $whereclause) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $MemberSelect . $whereclause);
$num_guild = $wowdb->num_rows($result_menu);
$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` = 60";
$result_menu = $wowdb->query($MemberSelect . $whereclause . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $MemberSelect . $whereclause . $levels);
$num_guild_lvl_60 = $wowdb->num_rows($result_menu);
$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` > 49 and `" . ROSTER_MEMBERSTABLE . "`.`level` < 60";
$result_menu = $wowdb->query($MemberSelect . $whereclause . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $MemberSelect . $whereclause . $levels);
$num_guild_lvl_50_59 = $wowdb->num_rows($result_menu);
$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` > 39 and `" . ROSTER_MEMBERSTABLE . "`.`level` < 50";
$result_menu = $wowdb->query($MemberSelect . $whereclause . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $MemberSelect . $whereclause . $levels);
$num_guild_lvl_40_49 = $wowdb->num_rows($result_menu);
$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` > 29 and `" . ROSTER_MEMBERSTABLE . "`.`level` < 40";
$result_menu = $wowdb->query($MemberSelect . $whereclause . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $MemberSelect . $whereclause . $levels);
$num_guild_lvl_30_39 = $wowdb->num_rows($result_menu);
$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` > 19 and `" . ROSTER_MEMBERSTABLE . "`.`level` < 30";
$result_menu = $wowdb->query($MemberSelect . $whereclause . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $MemberSelect . $whereclause . $levels);
$num_guild_lvl_20_29 = $wowdb->num_rows($result_menu);
$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` > 9 and `" . ROSTER_MEMBERSTABLE . "`.`level` < 20";
$result_menu = $wowdb->query($MemberSelect . $whereclause . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $MemberSelect . $whereclause . $levels);
$num_guild_lvl_10_19 = $wowdb->num_rows($result_menu);
$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` > 0 and `" . ROSTER_MEMBERSTABLE . "`.`level` < 10";
$result_menu = $wowdb->query($MemberSelect . $whereclause . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $MemberSelect . $whereclause . $levels);
$num_guild_lvl_1_9 = $wowdb->num_rows($result_menu);

$classresult = $wowdb->query($classquery) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $classquery);

while ($classRow = $wowdb->getrow($classresult))
{
	$whereClass = " AND `" . ROSTER_MEMBERSTABLE . "`.`class` = '" . $classRow['class'] . "'";
	$select11 = $select1 . $whereClass;
	$select21 = $select2 . $whereClass;
	$select31 = $select3 . $whereClass;
	if ($roster_conf['sqldebug'])
	{
		print "<!-- " . $select11 . " -->\n";
		print "<!-- " . $select21 . " -->\n";
		print "<!-- " . $select31 . " -->\n";
	}
	$result_menu = $wowdb->query($select11) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select11);
	$num_non_alts = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select21) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select21);
	$num_alts = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select31) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select31);
	$num_loaded = $wowdb->num_rows($result_menu);
	
	$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` = 60";
	$result_menu = $wowdb->query($select11 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select11 . $levels);
	$num_lvl_60 = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select21 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select21 . $levels);
	$num_lvl_60_alts = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select31 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select31 . $levels);
	$num_lvl_60_loaded = $wowdb->num_rows($result_menu);
	
	$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` > 49 and `" . ROSTER_MEMBERSTABLE . "`.`level` < 60";
	$result_menu = $wowdb->query($select11 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select11 . $levels);
	$num_lvl_50_59 = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select21 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select21 . $levels);
	$num_lvl_50_59_alts = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select31 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select31 . $levels);
	$num_lvl_50_59_loaded = $wowdb->num_rows($result_menu);
	
	$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` > 39 and `" . ROSTER_MEMBERSTABLE . "`.`level` < 50";
	$result_menu = $wowdb->query($select11 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select11 . $levels);
	$num_lvl_40_49 = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select21 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select21 . $levels);
	$num_lvl_40_49_alts = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select31 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select31 . $levels);
	$num_lvl_40_49_loaded = $wowdb->num_rows($result_menu);
	
	$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` > 29 and `" . ROSTER_MEMBERSTABLE . "`.`level` < 40";
	$result_menu = $wowdb->query($select11 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select11 . $levels);
	$num_lvl_30_39 = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select21 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select21 . $levels);
	$num_lvl_30_39_alts = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select31 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select31 . $levels);
	$num_lvl_30_39_loaded = $wowdb->num_rows($result_menu);
	
	$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` > 19 and `" . ROSTER_MEMBERSTABLE . "`.`level` < 30";
	$result_menu = $wowdb->query($select11 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select11 . $levels);
	$num_lvl_20_29 = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select21 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select21 . $levels);
	$num_lvl_20_29_alts = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select31 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select31 . $levels);
	$num_lvl_20_29_loaded = $wowdb->num_rows($result_menu);
	
	$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` > 9 and `" . ROSTER_MEMBERSTABLE . "`.`level` < 20";
	$result_menu = $wowdb->query($select11 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select11 . $levels);
	$num_lvl_10_19 = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select21 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select21 . $levels);
	$num_lvl_10_19_alts = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select31 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select31 . $levels);
	$num_lvl_10_19_loaded = $wowdb->num_rows($result_menu);
	
	$levels = " AND `" . ROSTER_MEMBERSTABLE . "`.`level` > 0 and `" . ROSTER_MEMBERSTABLE . "`.`level` < 10";
	$result_menu = $wowdb->query($select11 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select11 . $levels);
	$num_lvl_1_9 = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select21 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select21 . $levels);
	$num_lvl_1_9_alts = $wowdb->num_rows($result_menu);
	$result_menu = $wowdb->query($select31 . $levels) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, $select31 . $levels);
	$num_lvl_1_9_loaded = $wowdb->num_rows($result_menu);
	
	$result_avg = $wowdb->fetch_array($wowdb->query("SELECT AVG(`" . ROSTER_MEMBERSTABLE . "`.`level`) FROM `" . ROSTER_MEMBERSTABLE . "` " . $where1 . $whereClass)) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, "SELECT AVG(level) FROM `" . ROSTER_MEMBERSTABLE . "` " . $where1 . $whereClass);
	$result_avg_alts = $wowdb->fetch_array($wowdb->query("SELECT AVG(`" . ROSTER_MEMBERSTABLE . "`.`level`) FROM `" . ROSTER_MEMBERSTABLE . "` " . $where2 . $whereClass)) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, "SELECT AVG(level) FROM `" . ROSTER_MEMBERSTABLE . "` " . $where2 . $whereClass);
	$result_avg_loaded = $wowdb->fetch_array($wowdb->query("SELECT AVG(`" . ROSTER_MEMBERSTABLE . "`.`level`) FROM `" . ROSTER_PLAYERSTABLE . "` INNER JOIN `" . ROSTER_MEMBERSTABLE . "` ON `" . ROSTER_PLAYERSTABLE . "`.`member_id` = `" . ROSTER_MEMBERSTABLE . "`.`member_id` " . $whereclause . $whereClass)) or die_quietly($wowdb->error(), 'Database Error', basename(__FILE__), __LINE__, "SELECT AVG(level) FROM `" . ROSTER_PLAYERSTABLE . "` INNER JOIN `" . ROSTER_MEMBERSTABLE . "` ON `" . ROSTER_PLAYERSTABLE . "`.`member_id` = `" . ROSTER_MEMBERSTABLE . "`.`member_id` " . $whereclause . $whereClass);
	
	$content .= "<br />\n" . tableheader($classRow['class'], class_divider($classRow['class']), 'syellow') . "\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
	$content .= '   <td valign="top" class="row">';
	
	$content .= "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"2\">
		" . addTableLine("", localize('classmain'), localize('classalt'), localize('classuploaded'), false, 0, false) . "
		" . addTableLine(localize('members') , $num_non_alts, $num_alts , $num_loaded, false, $num_guild, true) . "
		" . addTableLine(localize('averagelevel'), round($result_avg[0],$leveldecimal), round($result_avg_alts[0], $leveldecimal), round($result_avg_loaded[0], $leveldecimal), true, 0, false) . "
		" . addTableLine(localize('level') . " 60", $num_lvl_60, $num_lvl_60_alts, $num_lvl_60_loaded, false, $num_guild_lvl_60, false) . "
		" . addTableLine(localize('level') . " 50-59", $num_lvl_50_59, $num_lvl_50_59_alts, $num_lvl_50_59_loaded, false, $num_guild_lvl_50_59, false) . "
		" . addTableLine(localize('level') . " 40-49", $num_lvl_40_49, $num_lvl_40_49_alts, $num_lvl_40_49_loaded, false, $num_guild_lvl_40_49, false) . "
		" . addTableLine(localize('level') . " 30-39", $num_lvl_30_39, $num_lvl_30_39_alts, $num_lvl_30_39_loaded, false, $num_guild_lvl_30_39, false) . "
		" . addTableLine(localize('level') . " 20-29", $num_lvl_20_29, $num_lvl_20_29_alts, $num_lvl_20_29_loaded, false, $num_guild_lvl_20_29, false) . "
		" . addTableLine(localize('level') . " 10-19", $num_lvl_10_19, $num_lvl_10_19_alts, $num_lvl_10_19_loaded, false, $num_guild_lvl_10_19, false) . "
		" . addTableLine(localize('level') . " 1-9", $num_lvl_1_9, $num_lvl_1_9_alts, $num_lvl_1_9_loaded, false, $num_guild_lvl_1_9, false);
	$content .= $tableFooter;
}

function addTableLine($Level, $Main, $Alt, $Uploaded, $bBar, $GuildTotal, $bTotal)
{
global $num_guild, $num_non_alts, $num_alts, $leveldecimal, $roster_conf;
	if ($bBar)
	{
		$mainPercent = round(($Main / 60) * 100);
		$altPercent = round(($Alt / 60) * 100);
		$uploadedPercent = round(($Uploaded / 60) * 100);
		return "		<tr>
			<td>
				" . $Level . "
			</td>
			<td>
				<div style=\"cursor:default;\">
					<div class=\"levelbarParent\" style=\"width:70px;\">
						<div class=\"levelbarChild\">
							" . $Main . "
						</div>
					</div>
					<table class=\"expOutline\" border=0 cellpadding=0 cellspacing=0 width=70>
						<tr>
							<td style=\"background-image: url('".$roster_conf['img_url']."expbar-var2.gif');\" width=\"" . $mainPercent . "%\"><img src=\"".$roster_conf['img_url']."pixel.gif\" height=\"14\" width=\"1\" alt=\"\"></td>
							<td width=\"" . (100 - $mainPercent) . "%\"></td>
						</tr>
					</table>
				</div>
			</td>
			<td>
				<div style=\"cursor:default;\">
					<div class=\"levelbarParent\" style=\"width:70px;\">
						<div class=\"levelbarChild\">
							" . $Alt . "
						</div>
					</div>
					<table class=\"expOutline\" border=0 cellpadding=0 cellspacing=0 width=70>
						<tr>
							<td style=\"background-image: url('".$roster_conf['img_url']."expbar-var2.gif');\" width=\"" . $altPercent . "%\"><img src=\"".$roster_conf['img_url']."pixel.gif\" height=\"14\" width=\"1\" alt=\"\"></td>
							<td width=\"" . (100 - $altPercent) . "%\"></td>
						</tr>
					</table>
				</div>
			</td>
			<td>
				<div style=\"cursor:default;\">
					<div class=\"levelbarParent\" style=\"width:70px;\">
						<div class=\"levelbarChild\">
							" . $Uploaded . "
						</div>
					</div>
					<table class=\"expOutline\" border=0 cellpadding=0 cellspacing=0 width=70>
						<tr>
							<td style=\"background-image: url('".$roster_conf['img_url']."expbar-var2.gif');\" width=\"" . $uploadedPercent . "%\"><img src=\"".$roster_conf['img_url']."pixel.gif\" height=\"14\" width=\"1\" alt=\"\"></td>
							<td width=\"" . (100 - $uploadedPercent) . "%\"></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
";
	} else {
		if ($GuildTotal>0)
		{
			$MainTip = "";
			$AltTip = "";
			$UploadedTip = "";
			$tipString = "";
			if($bTotal)
			{
				$tipString = localize('statsclass');
			} else {
				$tipString = localize('statslevelclass');
			}
			if($Main + $Alt > 0)
			{
				$UploadedTip .= round((($Uploaded / ($Main + $Alt)) * 100), $leveldecimal) . $tipString . "<br \>";
			} else {
				$UploadedTip .= "0" . $tipString . "<br \>";
			}
			
			if ($num_guild == $GuildTotal)
			{
				$MainTip .= round((($Main / $GuildTotal) * 100), $leveldecimal) . localize('statstotalguild');
				$AltTip .= round((($Alt / $GuildTotal) * 100), $leveldecimal) . localize('statstotalguild');
				$UploadedTip .= round((($Uploaded / $GuildTotal) * 100), $leveldecimal) . localize('statstotalguild');
			} else {
				$MainTip .= round((($Main / ($num_non_alts + $num_alts)) * 100), $leveldecimal) . localize('statsclass') . "<br \>";
				$AltTip .= round((($Alt / ($num_non_alts + $num_alts)) * 100), $leveldecimal) . localize('statsclass') . "<br \>";
				$UploadedTip .= round((($Uploaded / ($num_non_alts + $num_alts)) * 100), $leveldecimal) . localize('statsclass') . "<br \>";
				$MainTip .= round((($Main / $GuildTotal) * 100), $leveldecimal) . localize('statslevel') . "<br \>";
				$AltTip .= round((($Alt / $GuildTotal) * 100), $leveldecimal) . localize('statslevel') . "<br \>";
				$UploadedTip .= round((($Uploaded / $GuildTotal) * 100), $leveldecimal) . localize('statslevel') . "<br \>";
				$MainTip .= round((($Main / $num_guild) * 100), $leveldecimal) . localize('statstotalguild');
				$AltTip .= round((($Alt / $num_guild) * 100), $leveldecimal) . localize('statstotalguild');
				$UploadedTip .= round((($Uploaded / $num_guild) * 100), $leveldecimal) . localize('statstotalguild');
			}
			return "		<tr>
			<td>
				" . $Level . "
			</td>
			<td align=right>
				<div style=\"cursor:help;\" onmouseover=\"overlib('" . $MainTip . "',CENTER);\" onmouseout=\"return nd();\">" . $Main . "</div>
			</td>
			<td align=right>
				<div style=\"cursor:help;\" onmouseover=\"overlib('" . $AltTip . "',CENTER);\" onmouseout=\"return nd();\">" . $Alt . "</div>
			</td>
			<td align=right>
				<div style=\"cursor:help;\" onmouseover=\"overlib('" . $UploadedTip . "',CENTER);\" onmouseout=\"return nd();\">" . $Uploaded . "</div>
			</td>
		</tr>
";
		} else {
			return "		<tr>
			<td>
				" . $Level . "
			</td>
			<td align=right>
				" . $Main . "
			</td>
			<td align=right>
				" . $Alt . "
			</td>
			<td align=right>
				" . $Uploaded . "
			</td>
		</tr>
";
		}
	}
}

function addTopTableLine($text, $class, $num, $bround, $bBar)
{
	global $num_guild, $num_non_alts, $num_alts, $leveldecimal, $roster_conf;
	$count = $num;
	if ($bround)
	{
		$count = round($count, $leveldecimal);
	}
	if ($bBar)
	{
		$mainPercent = round(($count / 60) * 100);
		return "		<tr>
			<td>
				" . $text . "
			</td>
			<td>
				" . $class . "
			</td>
			<td>
				<div style=\"cursor:default;\">
					<div class=\"levelbarParent\" style=\"width:70px;\">
						<div class=\"levelbarChild\">
							" . $count . "
						</div>
					</div>
					<table class=\"expOutline\" border=0 cellpadding=0 cellspacing=0 width=70>
						<tr>
							<td style=\"background-image: url('".$roster_conf['img_url']."expbar-var2.gif');\" width=\"" . $mainPercent . "%\"><img src=\"".$roster_conf['img_url']."pixel.gif\" height=\"14\" width=\"1\" alt=\"\"></td>
							<td width=\"" . (100 - $mainPercent) . "%\"></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
";
	} else {
		return "		<tr>
			<td>
				" . $text . "
			</td>
			<td>
				" . $class . "
			</td>
			<td align=right>
				" . $count . "
			</td>
		</tr>
";
	}
}

/**
 * Controls Output of the Class Dividers
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function class_divider ( $text )
{
	global $wordings, $roster_conf;

	// Class Icon
	foreach ($roster_conf['multilanguages'] as $language)
	{
		if(isset($wordings[$language]['class_iconArray'][$text]))
			$icon_name = $wordings[$language]['class_iconArray'][$text];
		if( strlen($icon_name) > 0 ) break;
	}
	$icon_name = 'Interface/Icons/'.$icon_name;

	$icon_value = '<img class="membersRowimg" width="16" height="16" src="'.$roster_conf['interface_url'].$icon_name.'.'.$roster_conf['img_suffix'].'" alt="" />&nbsp;';

	return '<div class="membersGroup">'.$icon_value.$text.'</div>';

}

function tableheader ( $class, $title, $color )
{
global $roster_conf;
return "\n".'<!-- Begin HSLIST -->
<div id="' . $class . '_col" style="display:none;">
'.border($color,'start',"<div style=\"cursor:pointer;width:350px;\" onclick=\"swapShow('" . $class . "_col','" . $class . "_full')\"><img src=\"" . $roster_conf['img_url'] . "plus.gif\" style=\"float:right;\" />" . $title . "</div>").'
'.border($color,'end').'
</div>
<div id="' . $class . '_full">
'.border($color,'start',"<div style=\"cursor:pointer;width:350px;\" onclick=\"swapShow('" . $class . "_col','" . $class . "_full')\"><img src=\"" . $roster_conf['img_url'] . "minus.gif\" style=\"float:right;\" />" . $title . "</div>").'
<table width="100%" cellpadding="0" cellspacing="0" class="wowroster">'."\n";

}

function localize ( $item )
{
GLOBAL $wordings, $roster_conf;

	if (isset($wordings[$roster_conf['roster_lang']][$item]))
	{
		return $wordings[$roster_conf['roster_lang']][$item];
	} else {
		return $wordings['enUS'][$item];
	}
}
?>
