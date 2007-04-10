-- --------------------------------------------------------

-- 
-- Table structure for table `roster_guild`
-- 

CREATE TABLE `roster_guild` (
  `guild_id` int(11) unsigned NOT NULL auto_increment,
  `guild_name` varchar(64) NOT NULL default '',
  `server` varchar(32) NOT NULL default '',
  `faction` varchar(8) NULL default NULL,
  `guild_motd` varchar(255) NOT NULL default '',
  `guild_num_members` int(11) NOT NULL default '0',
  `update_time` datetime default NULL,
  `guild_dateupdatedutc` varchar(18) default NULL,
  `GPversion` varchar(6) NULL default NULL,
  PRIMARY KEY  (`guild_id`),
  KEY `guild` (`guild_name`,`server`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_items`
-- 

CREATE TABLE `roster_items` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `item_name` varchar(64) NOT NULL default '',
  `item_parent` varchar(64) NOT NULL default '',
  `item_slot` varchar(32) NOT NULL default '',
  `item_color` varchar(16) NOT NULL default '',
  `item_id` varchar(32) default NULL,
  `item_texture` varchar(64) NOT NULL default '',
  `item_quantity` int(11) default NULL,
  `item_tooltip` mediumtext NOT NULL,
  PRIMARY KEY  (`member_id`,`item_parent`,`item_slot`),
  KEY `parent` (`item_parent`),
  KEY `slot` (`item_slot`),
  KEY `name` (`item_name`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_members`
-- 

CREATE TABLE `roster_members` (
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
  `account_id` smallint(6) NOT NULL default '0',
  `inv` tinyint(4) NOT NULL default '3',
  `talents` tinyint(4) NOT NULL default '3',
  `quests` tinyint(4) NOT NULL default '3',
  `bank` tinyint(4) NOT NULL default '3',
  PRIMARY KEY  (`member_id`),
  KEY `member` (`guild_id`,`name`),
  KEY `name` (`name`),
  KEY `class` (`class`),
  KEY `level` (`level`),
  KEY `guild_rank` (`guild_rank`),
  KEY `last_online` (`last_online`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_pets`
-- 

CREATE TABLE `roster_pets` (
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
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_players`
-- 

CREATE TABLE `roster_players` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `guild_id` int(11) unsigned NOT NULL default '0',
  `dateupdatedutc` varchar(18) default NULL,
  `CPversion` varchar(6) NULL default NULL,
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
  `RankInfo` int(11) NOT NULL default '0',
  `RankName` varchar(64) NOT NULL default '',
  `RankIcon` varchar(64) NOT NULL default '',
  `clientLocale` varchar(4) NOT NULL default '',
  `timeplayed` int(11) NOT NULL default '0',
  `timelevelplayed` int(11) NOT NULL default '0',
  PRIMARY KEY  (`member_id`),
  KEY `name` (`name`,`server`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_pvp2`
-- 


CREATE TABLE `roster_pvp2` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `index` int(11) unsigned NOT NULL default '0',
  `date` datetime default NULL,
  `name` varchar(32) NOT NULL default '',
  `guild` varchar(32) NOT NULL default '',
  `race` varchar(32) NOT NULL default '',
  `class` varchar(32) NOT NULL default '',
  `zone` varchar(32) NOT NULL default '',
  `subzone` varchar(32) NOT NULL default '',
  `enemy` tinyint(4) NOT NULL default '0',
  `win` tinyint(4) NOT NULL default '0',
  `rank` varchar(32) NOT NULL default '',
  `bg` tinyint(3) unsigned NOT NULL default '0',
  `leveldiff` tinyint(4) NOT NULL default '0',
  `honor` smallint(6) NOT NULL default '0',
  `column_id` mediumint(9) NOT NULL auto_increment,
  PRIMARY KEY  (`column_id`),
  KEY `date` (`date`,`guild`,`class`),
  KEY `member_id` (`member_id`,`index`)
) ENGINE=MyISAM;


-- --------------------------------------------------------

-- 
-- Table structure for table `roster_quests`
-- 

CREATE TABLE `roster_quests` (
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
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_realmstatus`
-- 

CREATE TABLE `roster_realmstatus` (
  `server_name` varchar(20) NOT NULL default '',
  `servertype` varchar(20) NOT NULL default '',
  `serverstatus` varchar(20) NOT NULL default '',
  `serverpop` varchar(20) NOT NULL default '',
  `timestamp` tinyint(2) NOT NULL default '0',
  UNIQUE KEY `server_name` (`server_name`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_recipes`
-- 

CREATE TABLE `roster_recipes` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `recipe_name` varchar(64) NOT NULL default '',
  `recipe_type` varchar(100) NOT NULL default '',
  `skill_name` varchar(64) NOT NULL default '',
  `difficulty` int(11) NOT NULL default '0',
  `reagents` mediumtext NOT NULL,
  `recipe_texture` varchar(64) NOT NULL default '',
  `recipe_tooltip` mediumtext NOT NULL,
  `categories` varchar(64) NOT NULL default '',
  `level` int(11) default NULL,
  PRIMARY KEY  (`member_id`,`skill_name`,`recipe_name`,`categories`),
  KEY `skill_nameI` (`skill_name`),
  KEY `recipe_nameI` (`recipe_name`),
  KEY `categoriesI` (`categories`),
  KEY `levelI` (`level`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_reputation`
-- 

CREATE TABLE `roster_reputation` (
  `member_id` int(10) unsigned NOT NULL default '0',
  `faction` varchar(32) NOT NULL default '',
  `name` varchar(32) NOT NULL default '',
  `Value` varchar(32) default '0/0',
  `AtWar` int(11) NOT NULL default '0',
  `Standing` varchar(32) default '0/0',
  PRIMARY KEY  (`member_id`,`name`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_skills`
-- 

CREATE TABLE `roster_skills` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `skill_type` varchar(32) NOT NULL default '',
  `skill_name` varchar(32) NOT NULL default '',
  `skill_order` int(11) NOT NULL default '0',
  `skill_level` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`member_id`,`skill_name`),
  KEY `skill_type` (`skill_type`),
  KEY `skill_name` (`skill_name`),
  KEY `skill_order` (`skill_order`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_spellbook`
-- 

CREATE TABLE `roster_spellbook` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `spell_name` varchar(64) NOT NULL default '',
  `spell_type` varchar(100) NOT NULL default '',
  `spell_texture` varchar(64) NOT NULL default '',
  `spell_rank` varchar(64) NOT NULL default ''
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_spellbooktree`
-- 

CREATE TABLE `roster_spellbooktree` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `spell_type` varchar(64) NOT NULL default '',
  `spell_texture` varchar(64) NOT NULL default ''
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_talents`
-- 

CREATE TABLE `roster_talents` (
  `member_id` int(11) NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `tree` varchar(32) NOT NULL default '',
  `row` tinyint(4) NOT NULL default '0',
  `column` tinyint(4) NOT NULL default '0',
  `rank` tinyint(4) NOT NULL default '0',
  `maxrank` tinyint(4) NOT NULL default '0',
  `tooltip` mediumtext NOT NULL,
  `texture` varchar(64) NOT NULL default ''
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roster_talenttree`
-- 

CREATE TABLE `roster_talenttree` (
  `member_id` int(11) NOT NULL default '0',
  `tree` varchar(32) NOT NULL default '',
  `background` varchar(64) NOT NULL default '',
  `order` tinyint(4) NOT NULL default '0',
  `pointsspent` tinyint(4) NOT NULL default '0'
) ENGINE=MyISAM;

-- ----------------------------------------------------------

-- 
-- Table structure for table `roster_account`
-- 

CREATE TABLE `roster_account` (
  `account_id` smallint(6) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `hash` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`account_id`)
) ENGINE=MyISAM;