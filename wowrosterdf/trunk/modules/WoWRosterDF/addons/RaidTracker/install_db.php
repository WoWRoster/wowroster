<?php
$versions['versionDate']['raidtracker'] = '$Date: 2006/08/13 $'; 
$versions['versionRev']['raidtracker'] = '$Revision: 1.1 $';
$versions['versionAuthor']['raidtracker'] = '$Author: PoloDude $';

//if (!defined("CPG_NUKE")) { exit; }
	
	dropTables();
	createTables();

// DROP TABLE `roster_raidbosskills`, `roster_raiditems`, `roster_raidjoins`, `roster_raidleaves`, `roster_raidmembers`, `roster_raids`;
function dropTables(){
	global $db, $wowdb, $roster_conf, $rt_wordings, $db_prefix;
	
	// Drop current tables
	$drop_boss = "DROP TABLE IF EXISTS `".$db_prefix."raidbosskills`";
	$dropp_item = "DROP TABLE IF EXISTS `".$db_prefix."raiditems`";
	$drop_join = "DROP TABLE IF EXISTS `".$db_prefix."raidjoins`";
	$drop_leave = "DROP TABLE IF EXISTS `".$db_prefix."raidleaves`";
	$drop_member = "DROP TABLE IF EXISTS `".$db_prefix."raidmembers`";
	$drop_raids = "DROP TABLE IF EXISTS `".$db_prefix."raids`";
	
	$wowdb->query($drop_boss) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$drop_boss);
	$wowdb->query($dropp_item) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$dropp_item);
	$wowdb->query($drop_join) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$drop_join);
	$wowdb->query($drop_leave) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$drop_leave);
	$wowdb->query($drop_member) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$drop_member);
	$wowdb->query($drop_raids) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$drop_raids);
		
}

function createTables(){
	global $db, $wowdb, $roster_conf, $rt_wordings, $db_prefix;
	
	// Declare tables needed for RaidTracker
	$create_raids = "CREATE TABLE `".$db_prefix."raids` (
					`raidnum` int(6) NOT NULL AUTO_INCREMENT,
					`raidid` datetime NOT NULL default '0000-00-00 00:00:00',
					`end` int(12) NOT NULL default '0',
					`zone` varchar(100) NOT NULL default '',
					`note` varchar(255) NOT NULL default '',
					KEY `raidnum` (`raidnum`),
					KEY `raidid` (`raidid`)
					) TYPE=MyISAM;";
	$create_raidbosskills = "CREATE TABLE ".$db_prefix."raidbosskills (
							`raidnum` int(12) NOT NULL default '0',
							`raidid` datetime NOT NULL default '0000-00-00 00:00:00',
							`boss` varchar(100) NOT NULL default '',
							`time` datetime NOT NULL default '0000-00-00 00:00:00',
							KEY `raidnum` (`raidnum`)
							) TYPE=MyISAM;";
	$create_raiditems = "CREATE TABLE `".$db_prefix."raiditems` (
						  `raidnum` int(12) NOT NULL default '0',
						  `raidid` datetime NOT NULL default '0000-00-00 00:00:00',
						  `itemname` varchar(100) NOT NULL default '',
						  `zone` varchar(100) NOT NULL default '',
						  `boss` varchar(100) NOT NULL default '',
						  `number` int(12) NOT NULL default '0',
						  `color` varchar(10) NOT NULL default 'ffffffff',
						  `loottime` datetime NOT NULL default '0000-00-00 00:00:00',
						  `name` varchar(100) NOT NULL default '',
						  `note` varchar(255) NOT NULL default '',
						  KEY `itemname` (`itemname`),
						  KEY `raidnum` (`raidnum`),
						  KEY `name` (`name`)
						) TYPE=MyISAM;";
	$create_raidmembers = "CREATE TABLE `".$db_prefix."raidmembers` (
							  `name` varchar(100) NOT NULL default '',
							  `race` varchar(100) NOT NULL default '',
							  `class` varchar(100) NOT NULL default '',
							  `level` int(11) NOT NULL default '0',
							  UNIQUE KEY `name` (`name`)
							) TYPE=MyISAM;";
	$create_raidjoins = "CREATE TABLE `".$db_prefix."raidjoins` (
						  `raidnum` int(12) NOT NULL default '0',
						  `raidid` datetime NOT NULL default '0000-00-00 00:00:00',
						  `datejoin` datetime NOT NULL default '0000-00-00 00:00:00',
						  `name` varchar(100) NOT NULL default '',
						  KEY `raidnum` (`raidnum`),
						  KEY `name` (`name`),
						  KEY `datejoin` (`datejoin`)
						) TYPE=MyISAM;";
	$create_raidleaves = "CREATE TABLE `".$db_prefix."raidleaves` (
						  `raidnum` int(12) NOT NULL default '0',
						  `raidid` datetime NOT NULL default '0000-00-00 00:00:00',
						  `index` int(11) NOT NULL default '0',
						  `dateleft` datetime NOT NULL default '0000-00-00 00:00:00',
						  `name` varchar(100) NOT NULL default '',
						  KEY `raidnum` (`raidnum`),
						  KEY `name` (`name`),
						  KEY `dateleft` (`dateleft`)
						) TYPE=MyISAM;";
	
	// Create tables declared above
	$tables = 0;
	if($wowdb->query($create_raidleaves) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$create_raidleaves)){
		$tables += 1;
	}
	if($wowdb->query($create_raidjoins) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$create_raidjoins)){
		$tables += 1;
	}
	if($wowdb->query($create_raidmembers) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$create_raidmembers)){
		$tables += 1;
	}
	if($wowdb->query($create_raiditems) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$create_raiditems)){
		$tables += 1;
	}
	if($wowdb->query($create_raidbosskills) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$create_raidbosskills)){
		$tables += 1;
	}
	if($wowdb->query($create_raids) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$create_raids)){
		$tables += 1;
	}
	
	if($tables == 6){
		echo border('syellow','start');
		echo '<table width="300px">';
		echo '<tr><td align="center">All tables successfully added</td></tr>';
		echo '<tr><td align="center"><a href="index.php?name='.$module_name.'&amp;file=addon&amp;roster_addon_name=RaidTracker">Finish installation</a></td></tr>';
		echo '</table>';
		echo border('syellow','end');
	}
}
?>
