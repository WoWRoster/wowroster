#
# MySQL WoWRoster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### New Tables

DROP TABLE IF EXISTS `renprefix_api_usage`;
CREATE TABLE IF NOT EXISTS `renprefix_api_usage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `total` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `renprefix_plugin`;
CREATE TABLE `renprefix_plugin` (
  `addon_id` int(11) NOT NULL auto_increment,
  `basename` varchar(16) NOT NULL default '',
  `version` varchar(16) NOT NULL default '0',
  `active` int(1) NOT NULL default '1',
  `access` int(1) NOT NULL default '0',
  `fullname` tinytext NOT NULL,
  `description` mediumtext NOT NULL,
  `credits` mediumtext NOT NULL,
  `icon` varchar(64) NOT NULL default '',
  `wrnet_id` int(4) NOT NULL default '0',
  `versioncache` tinytext,
  PRIMARY KEY  (`addon_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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
ALTER TABLE `renprefix_talenttree_data` ADD `roles` VARCHAR( 10 ) NULL DEFAULT NULL ,ADD `desc` VARCHAR( 255 ) NULL DEFAULT NULL
ALTER TABLE `renprefix_talents_data` ADD `isspell` INT( 1 ) NULL DEFAULT NULL
# --------------------------------------------------------
### Config Table Updates

# javascript/css aggregation
INSERT INTO `renprefix_config` VALUES (99, 'css_js_query_string', 'lod68q', 'hidden', 'master');
INSERT INTO `renprefix_config` VALUES (1181, 'preprocess_js', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1182, 'preprocess_css', '1', 'radio{on^1|off^0', 'main_conf');

### api key settings
INSERT INTO `renprefix_config` VALUES (10001, 'api_key_private', '', 'text{64|30', 'update_access');
INSERT INTO `renprefix_config` VALUES (10002, 'api_key_public', '', 'text{64|30', 'update_access');
INSERT INTO `renprefix_config` VALUES (10003, 'api_url_region', 'us', 'select{us.battle.net^us|eu.battle.net^eu|kr.battle.net^kr|tw.battle.net^tw', 'update_access');
INSERT INTO `renprefix_config` VALUES (10004, 'api_url_locale', 'en_US', 'select{us.battle.net (en_US)^en_US|us.battle.net (es_MX)^es_MX|eu.battle.net (en_GB)^en_GB|eu.battle.net (es_ES)^es_ES|eu.battle.net (fr_FR)^fr_FR|eu.battle.net (ru_RU)^ru_RU|eu.battle.net (de_DE)^de_DE|kr.battle.net (ko_kr)^ko_kr|tw.battle.net (zh_TW)^zh_TW|battlenet.com.cn (zh_CN)^zh_CN', 'update_access');



# --------------------------------------------------------
### Menu Updates
