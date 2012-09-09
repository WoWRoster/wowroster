#
# MySQL WoWRoster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### New Tables


DROP TABLE IF EXISTS `renprefix_talents`;
CREATE TABLE `renprefix_talents` (
  `member_id` int(11) NOT NULL DEFAULT '0',
  `build` tinyint(2) NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL DEFAULT '',
  `tree` varchar(64) NOT NULL DEFAULT '',
  `row` tinyint(4) NOT NULL DEFAULT '0',
  `column` tinyint(4) NOT NULL DEFAULT '0',
  `rank` tinyint(4) NOT NULL DEFAULT '0',
  `maxrank` tinyint(4) NOT NULL DEFAULT '0',
  `tooltip` mediumtext NOT NULL,
  `texture` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`member_id`,`build`,`tree`,`row`,`column`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `renprefix_talents_data`;
CREATE TABLE `renprefix_talents_data` (
  `talent_id` int(11) NOT NULL DEFAULT '0',
  `talent_num` int(11) NOT NULL DEFAULT '0',
  `tree_order` int(11) NOT NULL DEFAULT '0',
  `class_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL DEFAULT '',
  `tree` varchar(1) NOT NULL DEFAULT '',
  `row` tinyint(4) NOT NULL DEFAULT '0',
  `column` tinyint(4) NOT NULL DEFAULT '0',
  `rank` tinyint(4) NOT NULL DEFAULT '0',
  `tooltip` mediumtext NOT NULL,
  `texture` varchar(64) NOT NULL DEFAULT '',
  `isspell` int(1) DEFAULT '0',
  PRIMARY KEY (`rank`,`tree`,`row`,`column`,`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `renprefix_talenttree`;
CREATE TABLE `renprefix_talenttree` (
  `member_id` int(11) NOT NULL DEFAULT '0',
  `build` tinyint(2) NOT NULL DEFAULT '0',
  `tree` varchar(64) NOT NULL DEFAULT '',
  `background` varchar(64) NOT NULL DEFAULT '',
  `order` tinyint(4) NOT NULL DEFAULT '0',
  `pointsspent` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`,`build`,`tree`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `renprefix_talenttree_data`;
CREATE TABLE `renprefix_talenttree_data` (
  `class_id` int(11) NOT NULL DEFAULT '0',
  `build` tinyint(2) NOT NULL DEFAULT '0',
  `tree` varchar(64) NOT NULL DEFAULT '',
  `tree_num` varchar(64) NOT NULL DEFAULT '',
  `background` varchar(64) NOT NULL DEFAULT '',
  `order` tinyint(4) NOT NULL DEFAULT '0',
  `icon` varchar(64) NOT NULL DEFAULT '',
  `roles` varchar(10) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`class_id`,`build`,`tree`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `renprefix_talent_builds`;
CREATE TABLE `renprefix_talent_builds` (
  `member_id` int(11) NOT NULL DEFAULT '0',
  `build` tinyint(2) NOT NULL DEFAULT '0',
  `tree` varchar(200) NOT NULL DEFAULT '',
  `spec` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`member_id`,`build`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `renprefix_talent_mastery`;
CREATE TABLE `renprefix_talent_mastery` (
  `class_id` int(11) NOT NULL DEFAULT '0',
  `tree` varchar(64) NOT NULL DEFAULT '',
  `tree_num` varchar(64) NOT NULL DEFAULT '',
  `icon` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `spell_id` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`class_id`,`spell_id`,`tree`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
### Altered Tables
ALTER TABLE  `renprefix_players`
ADD  `mastery` VARCHAR( 10 ) NULL DEFAULT NULL AFTER  `crit` ,
ADD  `mastery_tooltip` MEDIUMTEXT NULL DEFAULT NULL AFTER  `mastery`,
ADD  `ilevel` VARCHAR( 20 ) NULL DEFAULT NULL AFTER `mastery_tooltip` ,
ADD  `pvppower` VARCHAR( 20 ) NULL DEFAULT NULL AFTER `ilevel` ,
ADD  `pvppower_bonus` VARCHAR( 20 ) NULL DEFAULT NULL AFTER `pvppower`;

ALTER TABLE  `renprefix_recipes` ADD  `recipe_sub_type` VARCHAR( 100 ) NULL DEFAULT NULL AFTER  `recipe_type`;
# --------------------------------------------------------
### Add to Tables

# --------------------------------------------------------
### Update Tables
# --------------------------------------------------------
### Config Table Updates

# javascript/css aggregation

### api key settings
# session settings
# --------------------------------------------------------
### Menu Updates