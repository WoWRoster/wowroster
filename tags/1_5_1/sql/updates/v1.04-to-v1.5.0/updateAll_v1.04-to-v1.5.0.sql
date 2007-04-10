ALTER TABLE `guild` ADD `guild_dateupdatedutc` VARCHAR(18) NOT NULL default '';

ALTER TABLE `players` ADD `clientLocale` VARCHAR(4) NOT NULL default '';
ALTER TABLE `players` ADD `dateupdatedutc` VARCHAR(18) NOT NULL default '';
ALTER TABLE `players` ADD `hearth` VARCHAR(32) NOT NULL default '';
ALTER TABLE `players` ADD `Rankexp` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `players` CHANGE `sex` `sex` VARCHAR( 10 );
ALTER TABLE `players` ADD `TWContribution` int(11) NOT NULL default '0';
ALTER TABLE `players` ADD `TWHK` int(11) NOT NULL default '0';


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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


-- 
-- Table structure for table `pets`
-- 

CREATE TABLE `pets` (
  `member_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(32) collate latin1_general_ci NOT NULL default '',
  `slot` int(11) NOT NULL default '0',
  `level` int(11) NOT NULL default '0',
  `health` int(11) default NULL,
  `mana` int(11) default NULL,
  `xp` varchar(32) collate latin1_general_ci default NULL,
  `usedtp` int(11) default NULL,
  `totaltp` int(11) default NULL,
  `type` varchar(32) collate latin1_general_ci NOT NULL default '',
  `loyalty` varchar(32) collate latin1_general_ci NOT NULL default '',
  `icon` varchar(64) collate latin1_general_ci NOT NULL default '',
  `stat_int` varchar(32) collate latin1_general_ci default NULL,
  `stat_agl` varchar(32) collate latin1_general_ci default NULL,
  `stat_sta` varchar(32) collate latin1_general_ci default NULL,
  `stat_str` varchar(32) collate latin1_general_ci default NULL,
  `stat_spr` varchar(32) collate latin1_general_ci default NULL,
  `res_frost` int(11) default NULL,
  `res_arcane` int(11) default NULL,
  `res_fire` int(11) default NULL,
  `res_shadow` int(11) default NULL,
  `res_nature` int(11) default NULL,
  `armor` varchar(32) collate latin1_general_ci default NULL,
  `defense` int(11) default NULL,
  `melee_power` int(11) default NULL,
  `melee_rating` int(11) default NULL,
  `melee_range` varchar(16) collate latin1_general_ci default NULL,
  `melee_rangetooltip` tinytext collate latin1_general_ci,
  `melee_powertooltip` tinytext collate latin1_general_ci,
  PRIMARY KEY  (`member_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;