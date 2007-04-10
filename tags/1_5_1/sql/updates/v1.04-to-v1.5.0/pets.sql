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