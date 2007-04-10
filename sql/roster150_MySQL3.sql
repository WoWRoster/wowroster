-- phpMyAdmin SQL Dump
-- version 2.6.3-pl
-- http://www.phpmyadmin.net
-- 
-- Host: 192.168.0.5
-- Generation Time: July 18, 2005 at 11:42 PM
-- Server version: 4.1.12
-- PHP Version: 4.3.10
-- 
-- Database: `wow`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `guild`
-- 

CREATE TABLE `guild` (
  `guild_id` int(11) unsigned NOT NULL auto_increment,
  `guild_name` varchar(64) NOT NULL default '',
  `server` varchar(32) NOT NULL default '',
  `guild_motd` varchar(255) NOT NULL default '',
  `guild_num_members` int(11) NOT NULL default '0',
  `update_time` datetime default NULL,
  `guild_dateupdatedutc` VARCHAR(18) default NULL,
  PRIMARY KEY  (`guild_id`),
  KEY `guild` (`guild_name`,`server`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `items`
-- 

CREATE TABLE `items` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `item_name` varchar(64) NOT NULL default '',
  `item_parent` varchar(64) NOT NULL default '',
  `item_slot` varchar(32) NOT NULL default '',
  `item_color` varchar(16) NOT NULL default '',
  `item_id` varchar(16) NOT NULL default '',
  `item_texture` varchar(64) NOT NULL default '',
  `item_quantity` int(11) default NULL,
  `item_tooltip` mediumtext NOT NULL,
  PRIMARY KEY  (`member_id`,`item_parent`,`item_slot`),
  KEY `parent` (`item_parent`),
  KEY `slot` (`item_slot`),
  KEY `name` (`item_name`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `members`
-- 

CREATE TABLE `members` (
  `member_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `guild_id` int(11) unsigned NOT NULL default '0',
  `class` varchar(32) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  `note` varchar(255) NOT NULL default '',
  `guild_rank` int(11) default '0',
  `guild_title` varchar(64) default NULL,
  `officer_note` varchar(255) NOT NULL default '',
  `zone` varchar(64) NOT NULL default '',
  `group` varchar(16) NOT NULL default '',
  `online` int(1) default '0',
  `last_online` datetime default NULL,
  `update_time` datetime default NULL,
  PRIMARY KEY  (`member_id`),
  KEY `member` (`guild_id`,`name`),
  KEY `name` (`name`),
  KEY `class` (`class`),
  KEY `level` (`level`),
  KEY `guild_rank` (`guild_rank`),
  KEY `last_online` (`last_online`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `players`
-- 

CREATE TABLE `players` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `guild_id` int(11) unsigned NOT NULL default '0',
  `dateupdatedutc` VARCHAR(18) default NULL,
  `stat_int` varchar(32) NOT NULL default '',
  `stat_agl` varchar(32) NOT NULL default '',
  `stat_sta` varchar(32) NOT NULL default '',
  `stat_str` varchar(32) NOT NULL default '',
  `stat_spr` varchar(32) NOT NULL default '',
  `race` varchar(32) NOT NULL default '',
  `sex` varchar(10) NOT NULL default '',
  `hearth` varchar(32) NOT NULL default '',
  `res_frost` int(11) NOT NULL default '0',
  `res_arcane` int(11) NOT NULL default '0',
  `res_fire` int(11) NOT NULL default '0',
  `res_shadow` int(11) NOT NULL default '0',
  `res_nature` int(11) NOT NULL default '0',
  `armor` varchar(32) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  `server` varchar(32) NOT NULL default '',
  `defense` int(11) NOT NULL default '0',
  `talent_points` int(11) NOT NULL default '0',
  `money_c` int(11) NOT NULL default '0',
  `money_s` int(11) NOT NULL default '0',
  `money_g` int(11) NOT NULL default '0',
  `exp` varchar(32) NOT NULL default '',
  `class` varchar(32) NOT NULL default '',
  `health` int(11) NOT NULL default '0',
  `melee_power` int(11) default NULL,
  `melee_rating` int(11) default NULL,
  `melee_range` varchar(16) default NULL,
  `melee_range_tooltip` tinytext,
  `melee_power_tooltip` tinytext,
  `ranged_power` int(11) default NULL,
  `ranged_rating` int(11) default NULL,
  `ranged_range` varchar(16) default NULL,
  `ranged_range_tooltip` tinytext,
  `ranged_power_tooltip` tinytext,
  `version` varchar(16) default NULL,
  `mana` int(11) NOT NULL default '0',
  `stat_int2` int(11) unsigned NOT NULL default '0',
  `stat_agl2` int(11) unsigned NOT NULL default '0',
  `stat_sta2` int(11) unsigned NOT NULL default '0',
  `stat_str2` int(11) unsigned NOT NULL default '0',
  `stat_spr2` int(11) unsigned NOT NULL default '0',
  `stat_total` int(11) NOT NULL default '0',
  `pvp_ratio` float NOT NULL default '0',
  `sessionHK` int(11) NOT NULL default '0',
  `sessionDK` int(11) NOT NULL default '0',
  `yesterdayHK` int(11) NOT NULL default '0',
  `yesterdayDK` int(11) NOT NULL default '0',
  `yesterdayContribution` int(11) NOT NULL default '0',
  `lastweekHK` int(11) NOT NULL default '0',
  `lastweekDK` int(11) NOT NULL default '0',
  `lastweekContribution` int(11) NOT NULL default '0',
  `lastweekRank` int(11) NOT NULL default '0',
  `lifetimeHK` int(11) NOT NULL default '0',
  `lifetimeDK` int(11) NOT NULL default '0',
  `lifetimeRankName` varchar(64) NOT NULL default '0',
  `Rankexp` int(11) NOT NULL default '0',
  `TWContribution` int(11) NOT NULL default '0',
  `TWHK` int(11) NOT NULL default '0',
  `dodge` float NOT NULL default '0',
  `parry` float NOT NULL default '0',
  `block` float NOT NULL default '0',
  `mitigation` float NOT NULL default '0',
  `crit` float NOT NULL default '0',
  `lifetimeHighestRank` int(11) NOT NULL default '0',
  `RankInfo` varchar(64) NOT NULL default '',
  `RankName` varchar(64) NOT NULL default '',
  `RankIcon` varchar(64) NOT NULL default '',
  `clientLocale` VARCHAR(4) NOT NULL default '',
  PRIMARY KEY  (`member_id`),
  KEY `name` (`name`,`server`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `pvp2`
-- 

CREATE TABLE `pvp2` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `index` int(11) unsigned NOT NULL default '0',
  `date` datetime default NULL,
  `name` varchar(32) NOT NULL default '',
  `guild` varchar(32) NOT NULL default '',
  `race` varchar(32) NOT NULL default '',
  `class` varchar(32) NOT NULL default '',
  `zone` varchar(32) NOT NULL default '',
  `subzone` varchar(32) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  `mylevel` int(11) NOT NULL default '0',
  `diff` int(11) NOT NULL default '0',
  `enemy` varchar(4) NOT NULL default '',
  `win` varchar(4) NOT NULL default '',
  `group` int(3) NOT NULL default '0',
  `column_id` mediumint(9) NOT NULL auto_increment,
  PRIMARY KEY  (`column_id`),
  KEY `date` (`date`,`guild`,`class`),
  KEY `member_id` (`member_id`,`index`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `quests`
-- 

CREATE TABLE `quests` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `quest_name` varchar(64) NOT NULL default '',
  `quest_index` int(11) NOT NULL default '0',
  `quest_level` int(11) unsigned NOT NULL default '0',
  `quest_tag` varchar(32) NOT NULL default '',
  `is_complete` int(1) NOT NULL default '0',
  `zone` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`member_id`,`quest_name`),
  KEY `quest_index` (`quest_index`,`quest_level`,`quest_tag`),
  FULLTEXT KEY `quest_name` (`quest_name`),
  FULLTEXT KEY `zone` (`zone`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `recipes`
-- 

CREATE TABLE `recipes` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `recipe_name` varchar(64) NOT NULL default '',
  `recipe_type` VARCHAR( 100 ) NOT NULL ,
  `skill_name` varchar(64) NOT NULL default '',
  `difficulty` int(11) NOT NULL default '0',
  `reagents` mediumtext NOT NULL,
  `recipe_texture` varchar(64) NOT NULL default '',
  `recipe_tooltip` mediumtext NOT NULL,
  `categories` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`member_id`,`skill_name`,`recipe_name`,`categories`),
  KEY `skill_nameI` (`skill_name`),
  KEY `recipe_nameI` (`recipe_name`),
  KEY `categoriesI` (`categories`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `skills`
-- 

CREATE TABLE `skills` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `skill_type` varchar(32) NOT NULL default '',
  `skill_name` varchar(32) NOT NULL default '',
  `skill_order` int(11) NOT NULL default '0',
  `skill_level` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`member_id`,`skill_name`),
  KEY `skill_type` (`skill_type`),
  KEY `skill_name` (`skill_name`),
  KEY `skill_order` (`skill_order`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `talents`
-- 

CREATE TABLE `talents` (
  `member_id` int(11) NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `tree` varchar(32) NOT NULL default '',
  `row` tinyint(4) NOT NULL default '0',
  `column` tinyint(4) NOT NULL default '0',
  `rank` tinyint(4) NOT NULL default '0',
  `maxrank` tinyint(4) NOT NULL default '0',
  `tooltip` mediumtext NOT NULL,
  `texture` varchar(64) NOT NULL default ''
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `talenttree`
-- 

CREATE TABLE `talenttree` (
  `member_id` int(11) NOT NULL default '0',
  `tree` varchar(32) NOT NULL default '',
  `background` varchar(64) NOT NULL default '',
  `order` tinyint(4) NOT NULL default '0',
  `pointsspent` tinyint(4) NOT NULL default '0'
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `reputation`
-- 

CREATE TABLE `reputation` (
  `member_id` int(10) unsigned NOT NULL default '0',
  `faction` varchar(32) NOT NULL default '',
  `name` varchar(32) NOT NULL default '',
  `Value` varchar(32) default '0/0',
  `AtWar` int(11) NOT NULL default '0',
  `Standing` varchar(32) default '0/0',
  PRIMARY KEY  (`member_id`,`name`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `pets`
-- 

CREATE TABLE `pets` (
  `member_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `slot` int(11) NOT NULL default '0',
  `level` int(11) NOT NULL default '0',
  `health` int(11) default NULL,
  `mana` int(11) default NULL,
  `xp` varchar(32) default NULL,
  `usedtp` int(11) default NULL,
  `totaltp` int(11) default NULL,
  `type` varchar(32) NOT NULL default '',
  `loyalty` varchar(32) NOT NULL default '',
  `icon` varchar(64) NOT NULL default '',
  `stat_int` varchar(32) default NULL,
  `stat_agl` varchar(32) default NULL,
  `stat_sta` varchar(32) default NULL,
  `stat_str` varchar(32) default NULL,
  `stat_spr` varchar(32) default NULL,
  `res_frost` int(11) default NULL,
  `res_arcane` int(11) default NULL,
  `res_fire` int(11) default NULL,
  `res_shadow` int(11) default NULL,
  `res_nature` int(11) default NULL,
  `armor` varchar(32) default NULL,
  `defense` int(11) default NULL,
  `melee_power` int(11) default NULL,
  `melee_rating` int(11) default NULL,
  `melee_range` varchar(16) default NULL,
  `melee_rangetooltip` tinytext,
  `melee_powertooltip` tinytext,
  PRIMARY KEY  (`member_id`,`name`)
) TYPE=MyISAM;