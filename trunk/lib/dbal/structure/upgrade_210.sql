#
# MySQL WoWRoster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### New Tables

DROP TABLE IF EXISTS `renprefix_addons_accounts_messaging`;
CREATE TABLE IF NOT EXISTS `renprefix_addons_accounts_messaging` (
  `msgid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `sender` int(11) NOT NULL,
  `senderLevel` int(11) NOT NULL,
  `read` int(11) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`msgid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `renprefix_api_gems`;
CREATE TABLE IF NOT EXISTS `renprefix_api_gems` (
  `gem_id` int(11) NOT NULL,
  `gem_name` varchar(96) NOT NULL DEFAULT '',
  `gem_color` varchar(16) NOT NULL DEFAULT '',
  `gem_tooltip` mediumtext NOT NULL,
  `gem_texture` varchar(64) NOT NULL DEFAULT '',
  `gem_bonus` varchar(255) NOT NULL DEFAULT '',
  `locale` varchar(16) NOT NULL DEFAULT '',
  `timestamp` int(10) NOT NULL,
  `json` longtext,
  PRIMARY KEY (`gem_id`,`locale`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `renprefix_api_items`;
CREATE TABLE IF NOT EXISTS `renprefix_api_items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(96) NOT NULL DEFAULT '',
  `item_color` varchar(16) NOT NULL DEFAULT '',
  `item_texture` varchar(64) NOT NULL DEFAULT '',
  `item_tooltip` mediumtext NOT NULL,
  `level` int(11) DEFAULT NULL,
  `item_level` int(11) DEFAULT NULL,
  `item_type` varchar(64) DEFAULT NULL,
  `item_subtype` varchar(64) DEFAULT NULL,
  `item_rarity` int(4) NOT NULL DEFAULT '-1',
  `locale` varchar(16) DEFAULT NULL,
  `timestamp` int(10) NOT NULL,
  `json` longtext,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `renprefix_api_usage`;
CREATE TABLE IF NOT EXISTS `renprefix_api_usage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `total` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

DROP TABLE IF EXISTS `renprefix_plugin`;
CREATE TABLE IF NOT EXISTS `renprefix_plugin` (
  `addon_id` int(11) NOT NULL AUTO_INCREMENT,
  `basename` varchar(16) NOT NULL DEFAULT '',
  `parent` varchar(100) DEFAULT NULL,
  `scope` varchar(20) DEFAULT NULL,
  `version` varchar(16) NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '1',
  `access` int(1) NOT NULL DEFAULT '0',
  `fullname` tinytext NOT NULL,
  `description` mediumtext NOT NULL,
  `credits` mediumtext NOT NULL,
  `icon` varchar(64) NOT NULL DEFAULT '',
  `wrnet_id` int(4) NOT NULL DEFAULT '0',
  `versioncache` tinytext,
  PRIMARY KEY (`addon_id`)
) ENGINE=MyISAM ;

DROP TABLE IF EXISTS `renprefix_plugin_config`;
CREATE TABLE IF NOT EXISTS `renprefix_plugin_config` (
  `addon_id` int(11) NOT NULL DEFAULT '0',
  `id` int(11) unsigned NOT NULL,
  `config_name` varchar(255) DEFAULT NULL,
  `config_value` tinytext,
  `form_type` mediumtext,
  `config_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`addon_id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `renprefix_sessions`;
CREATE TABLE IF NOT EXISTS `renprefix_sessions` (
  `sess_id` varchar(35) CHARACTER SET latin1 DEFAULT NULL,
  `session_id` char(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `session_user_id` varchar(5) CHARACTER SET latin1 DEFAULT NULL,
  `guestid` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `session_last_visit` int(11) NOT NULL DEFAULT '0',
  `session_start` int(11) NOT NULL DEFAULT '0',
  `session_time` int(11) NOT NULL DEFAULT '0',
  `session_ip` varchar(40) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `session_browser` varchar(150) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `session_forwarded_for` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `session_page` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `session_viewonline` tinyint(1) NOT NULL DEFAULT '1',
  `session_autologin` tinyint(1) NOT NULL DEFAULT '0',
  `session_admin` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `sess_id` (`sess_id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `renprefix_sessions_keys`;
CREATE TABLE IF NOT EXISTS `renprefix_sessions_keys` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `last_login` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`key_id`,`user_id`),
  KEY `last_login` (`last_login`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `renprefix_talent_mastery`;
CREATE TABLE IF NOT EXISTS `renprefix_talent_mastery` (
  `class_id` int(11) NOT NULL DEFAULT '0',
  `tree` varchar(64) NOT NULL DEFAULT '',
  `tree_num` varchar(64) NOT NULL DEFAULT '',
  `icon` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `spell_id` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`class_id`,`spell_id`,`tree`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `renprefix_user_members`;
CREATE TABLE IF NOT EXISTS `renprefix_user_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usr` varchar(32) NOT NULL DEFAULT '',
  `pass` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(255) DEFAULT NULL,
  `regIP` varchar(15) DEFAULT NULL,
  `dt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` varchar(25) DEFAULT NULL,
  `fname` varchar(30) DEFAULT NULL,
  `lname` varchar(30) DEFAULT NULL,
  `age` varchar(32) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `state` varchar(32) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  `zone` varchar(32) DEFAULT NULL,
  `homepage` varchar(64) DEFAULT NULL,
  `other_guilds` varchar(64) DEFAULT NULL,
  `why` varchar(64) DEFAULT NULL,
  `about` varchar(64) DEFAULT NULL,
  `notes` varchar(64) DEFAULT NULL,
  `last_login` varchar(64) DEFAULT NULL,
  `date_joined` varchar(64) DEFAULT NULL,
  `tmp_mail` varchar(32) DEFAULT NULL,
  `group_id` smallint(6) NOT NULL DEFAULT '1',
  `is_member` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `online` int(11) NOT NULL DEFAULT '0',
  `user_lastvisit` int(15) DEFAULT NULL,
  `last_sid` varchar(80) DEFAULT NULL,
  `hash` varchar(32) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usr` (`usr`)
) ENGINE=MyISAM ;

# --------------------------------------------------------
### Altered Tables
ALTER TABLE `renprefix_talenttree_data` ADD `roles` VARCHAR( 10 ) NULL DEFAULT NULL ,ADD `desc` VARCHAR( 255 ) NULL DEFAULT NULL;
ALTER TABLE `renprefix_talents_data` ADD `isspell` INT( 1 ) NULL DEFAULT NULL;
ALTER TABLE `renprefix_members` CHANGE `account_id` `account_id` SMALLINT( 6 ) NULL DEFAULT NULL;
ALTER TABLE `renprefix_currency` CHANGE `count` `count` INT( 5 ) NULL DEFAULT NULL;

# --------------------------------------------------------
### Add to Tables

# --------------------------------------------------------
### Update Tables
UPDATE `renprefix_members` set `account_id` = NULL WHERE `account_id` = '0';
UPDATE `renprefix_members` SET `access` = '11:0',`active`='1' WHERE `usr` = 'Admin';
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
INSERT INTO `renprefix_config` VALUES (10006, 'use_api_onupdate', '0', 'select{Yes^1|No^0', 'update_access');
# session settings
INSERT INTO `renprefix_config` VALUES (190,'acc_session','NULL','blockframe','menu'),
(1900, 'sess_time', '15', 'text{30|4', 'acc_session'),
(1910, 'save_login', '1', 'radio{on^1|off^0', 'acc_session');
INSERT INTO `renprefix_user_members` (`id`, `usr`, `pass`, `email`, `regIP`, `dt`, `access`,`active`) VALUES (1, 'Admin', '5f4dcc3b5aa765d61d8327deb882cf99', '', '', '0000-00-00 00:00:00', '11:0','1');

# --------------------------------------------------------
### Menu Updates
INSERT INTO `renprefix_menu` VALUES ('', 'user', '');