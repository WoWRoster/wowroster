<?php
$versions['versionDate']['eventcalendar'] = '$Date: 2006/08/28 $'; 
$versions['versionRev']['eventcalendar'] = '$Revision: 1.0 $';
$versions['versionAuthor']['eventcalendar'] = '$Author: PoloDude $';

if (!defined("CPG_NUKE")) { exit; }

createTables();

function createTables(){
	global $wowdb, $roster_conf, $db_prefix;
	
	// Declare tables needed for EventCalendar
	$create_events = "CREATE TABLE `".$db_prefix."events` (
		`eventid` int(6) NOT NULL AUTO_INCREMENT,
		`date` datetime NOT NULL default '0000-00-00 00:00:00',
		`title` varchar(100) NOT NULL default '',
		`type` varchar(100) NOT NULL default '',
		`note` varchar(255) NOT NULL default '',
		`leader` varchar(100) NOT NULL default '',		
		`minLevel` int(11) NOT NULL default '0',
		`maxLevel` int(11) NOT NULL default '0',
		`maxCount` int(11) NOT NULL default '0',
		KEY `eventid` (`eventid`)
		) TYPE=MyISAM;";
	$create_eventmembers = "CREATE TABLE `".$db_prefix."event_members` (
		`name` varchar(100) NOT NULL default '',
		`guild` varchar(255) NOT NULL default '',
		`class` varchar(100) NOT NULL default '',
		`level` int(11) NOT NULL default '0',
		UNIQUE KEY `name` (`name`)
		) TYPE=MyISAM;";
	$create_eventsubscribers = "CREATE TABLE `".$db_prefix."event_subscribers` (
		`eventid` int(11) NOT NULL default '0',
		`name` varchar(100) NOT NULL default '',
		`place` varchar(10) NOT NULL default '',
		`status` varchar(100) NOT NULL default '',
		`note` varchar(255) NOT NULL default '',
		KEY `eventid` (`eventid`),
		KEY `name` (`name`)
		) TYPE=MyISAM;";
	$create_eventlimits = "CREATE TABLE `".$db_prefix."event_limits` (
		`eventid` int(11) NOT NULL default '0',
		`class` varchar(100) NOT NULL default '',
		`min` int(11) NOT NULL default '0',
		`max` int(11) NOT NULL default '0',
		KEY `eventid` (`eventid`)
		) TYPE=MyISAM;";
	
	// Create tables declared above
	$tables = 0;
	if($wowdb->query($create_events) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$create_events)){
		$tables += 1;
	}
	if($wowdb->query($create_eventmembers) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$create_eventmembers)){
		$tables += 1;
	}
	if($wowdb->query($create_eventsubscribers) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$create_raidmembers)){
		$tables += 1;
	}
	if($wowdb->query($create_eventlimits) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$create_eventlimits)){
		$tables += 1;
	}
	
	if($tables == 4){
		echo border('syellow','start');
		echo '<table width="300px">';
		echo '<tr><td align="center">All tables successfully added</td></tr>';
		echo '<tr><td align="center"><a href="addon.php?roster_addon_name=EventCalendar">Finish installation</a></td></tr>';
		echo '</table>';
		echo border('syellow','end');
	}
}
?>
