#
# MySQL WoWRoster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### Companions

DROP TABLE IF EXISTS `renprefix_companions`;
CREATE TABLE `renprefix_companions` (
  `comp_id` int(11) NOT NULL auto_increment,
  `member_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(96) NOT NULL,
  `type` varchar(96) NOT NULL,
  `slot` int(11) NOT NULL,
  `spellid` int(11) NOT NULL default '0',
  `icon` varchar(64) NOT NULL default '',
  `creatureid` int(11) NOT NULL default '0',
  `tooltip` mediumtext NOT NULL,
  PRIMARY KEY  (`comp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
### Currency

DROP TABLE IF EXISTS `renprefix_currency`;
CREATE TABLE `renprefix_currency` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `order` tinyint(4) NOT NULL default '0',
  `category` varchar(96) NOT NULL,
  `name` varchar(96) NOT NULL default '',
  `type` tinyint(4) unsigned NOT NULL default '0',
  `count` tinyint(4) unsigned NOT NULL default '0',
  `icon` varchar(64) NOT NULL,
  `tooltip` mediumtext NOT NULL,
  `watched` varchar(10) NOT NULL,
  PRIMARY KEY  (`member_id`,`category`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
### Glyphs

DROP TABLE IF EXISTS `renprefix_glyphs`;
CREATE TABLE `renprefix_glyphs` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `glyph_build` tinyint(2) NOT NULL default '0',
  `glyph_order` tinyint(4) NOT NULL default '0',
  `glyph_type` tinyint(4) NOT NULL default '0',
  `glyph_name` varchar(96) NOT NULL default '',
  `glyph_icon` varchar(64) NOT NULL default '',
  `glyph_tooltip` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
### Pets

ALTER TABLE `renprefix_pets`
  DROP `usedtp`,
  DROP `loyalty`;

# --------------------------------------------------------
### Pet Spellbook

CREATE TABLE `renprefix_pet_spellbook` (
  `member_id` int( 11 ) unsigned NOT NULL default '0',
  `pet_id` int( 11 ) unsigned NOT NULL default '0',
  `spell_name` varchar( 64 ) NOT NULL default '',
  `spell_texture` varchar( 64 ) NOT NULL default '',
  `spell_rank` varchar( 64 ) NOT NULL default '',
  `spell_tooltip` mediumtext NOT NULL ,
  PRIMARY KEY ( `member_id` , `pet_id` , `spell_name` , `spell_rank` )
) ENGINE = MYISAM DEFAULT CHARSET = utf8;

INSERT INTO `renprefix_pet_spellbook`
  SELECT *
  FROM `renprefix_spellbook_pet` ;

DROP TABLE `renprefix_spellbook_pet` ;

# --------------------------------------------------------
### Pet Talents

DROP TABLE IF EXISTS `renprefix_pet_talents`;
CREATE TABLE `renprefix_pet_talents` (
  `member_id` int(11) NOT NULL default '0',
  `pet_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `tree` varchar(64) NOT NULL default '',
  `row` tinyint(4) NOT NULL default '0',
  `column` tinyint(4) NOT NULL default '0',
  `rank` tinyint(4) NOT NULL default '0',
  `maxrank` tinyint(4) NOT NULL default '0',
  `tooltip` mediumtext NOT NULL,
  `icon` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`member_id`,`pet_id`,`tree`,`row`,`column`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
### Pet Talent Tree

DROP TABLE IF EXISTS `renprefix_pet_talenttree`;
CREATE TABLE `renprefix_pet_talenttree` (
  `member_id` int(11) NOT NULL default '0',
  `pet_id` int(11) unsigned NOT NULL default '0',
  `tree` varchar(64) NOT NULL default '',
  `background` varchar(64) NOT NULL default '',
  `order` tinyint(4) NOT NULL default '0',
  `pointsspent` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`member_id`,`pet_id`,`tree`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
### Quest Data

DROP TABLE IF EXISTS `renprefix_quest_data`;
CREATE TABLE `renprefix_quest_data` (
  `quest_id` int(11) NOT NULL default '0',
  `quest_name` varchar(64) NOT NULL default '',
  `quest_level` int(11) unsigned NOT NULL default '0',
  `quest_tag` varchar(32) NOT NULL default '',
  `group` int(1) NOT NULL default '0',
  `daily` int(1) NOT NULL default '0',
  `reward_money` int(11) NOT NULL default '0',
  `zone` varchar(32) NOT NULL default '',
  `description` text NOT NULL,
  `objective` text NOT NULL,
  `locale` varchar(4) NOT NULL default '',
  PRIMARY KEY  (`quest_id`,`locale`),
  FULLTEXT KEY `quest_name` (`quest_name`),
  FULLTEXT KEY `zone` (`zone`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
### Quests

DROP TABLE IF EXISTS `renprefix_quests`;
CREATE TABLE `renprefix_quests` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `quest_id` int(11) NOT NULL default '0',
  `quest_index` int(11) NOT NULL default '0',
  `difficulty` int(1) NOT NULL default '0',
  `is_complete` int(1) NOT NULL default '0',
  PRIMARY KEY  (`member_id`,`quest_id`),
  KEY `quest_index` (`quest_id`,`quest_index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
### Reputation

ALTER TABLE `renprefix_reputation`
ADD `parent` varchar(32) NULL AFTER `faction`,
ADD `Description` mediumtext NULL AFTER `Standing`;

# --------------------------------------------------------
### Spellbook

ALTER TABLE `renprefix_spellbook`
  ADD `spell_build` tinyint(2) NOT NULL default '0' AFTER `member_id`,
  DROP PRIMARY KEY,
  ADD PRIMARY KEY (`member_id`,`spell_build`,`spell_name`,`spell_rank`);

ALTER TABLE `renprefix_spellbooktree`
  ADD `spell_build` tinyint(2) NOT NULL default '0' AFTER `member_id`,
  DROP PRIMARY KEY,
  ADD PRIMARY KEY (`member_id`,`spell_build`,`spell_type`);

# --------------------------------------------------------
### Talents Data

DROP TABLE IF EXISTS `renprefix_talents_data`;
CREATE TABLE IF NOT EXISTS `renprefix_talents_data` (
  `talent_id` int(11) NOT NULL default '0',
  `talent_num` int(11) NOT NULL default '0',
  `tree_order` int(11) NOT NULL default '0',
  `class_id` int(11) NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `tree` varchar(64) NOT NULL default '',
  `row` tinyint(4) NOT NULL default '0',
  `column` tinyint(4) NOT NULL default '0',
  `rank` tinyint(4) NOT NULL default '0',
  `tooltip` mediumtext NOT NULL,
  `texture` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`rank`,`tree`,`row`,`column`,`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
### Talent Tree Data

DROP TABLE IF EXISTS `renprefix_talenttree_data`;
CREATE TABLE IF NOT EXISTS `renprefix_talenttree_data` (
  `class_id` int(11) NOT NULL default '0',
  `build` tinyint(2) NOT NULL default '0',
  `tree` varchar(64) NOT NULL default '',
  `tree_num` varchar(64) NOT NULL default '',
  `background` varchar(64) NOT NULL default '',
  `order` tinyint(4) NOT NULL default '0',
  `icon` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`class_id`,`build`,`tree`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
### Talent Builds

DROP TABLE IF EXISTS `renprefix_talent_builds`;
CREATE TABLE IF NOT EXISTS `renprefix_talent_builds` (
  `member_id` int(11) NOT NULL default '0',
  `build` tinyint(2) NOT NULL default '0',
  `tree` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`member_id`,`build`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
### Update Talents

ALTER TABLE `renprefix_talents`
  ADD `build` tinyint(2) NOT NULL default '0' AFTER `member_id`,
  DROP PRIMARY KEY,
  ADD PRIMARY KEY (`member_id`,`build`,`tree`,`row`,`column`);

# --------------------------------------------------------
### Talent Tree

ALTER TABLE `renprefix_talenttree`
  ADD `build` tinyint(2) NOT NULL default '0' AFTER `member_id`,
  DROP PRIMARY KEY,
  ADD PRIMARY KEY (`member_id`,`build`,`tree`);

# --------------------------------------------------------
### Config Table Updates

UPDATE `renprefix_config` SET `config_value` = 'http://www.wowroster.net/MediaWiki' WHERE `id` = 180 LIMIT 1;
INSERT INTO `renprefix_config` VALUES (10005, 'update_inst', '1', 'radio{on^1|off^0', 'update_access');
