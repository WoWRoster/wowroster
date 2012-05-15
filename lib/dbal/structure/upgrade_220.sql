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

DROP TABLE IF EXISTS `renprefix_user_members`;
CREATE TABLE IF NOT EXISTS `renprefix_user_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usr` varchar(32) NOT NULL DEFAULT '',
  `pass` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `regIP` varchar(15) NOT NULL DEFAULT '',
  `dt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` varchar(25) NOT NULL default '0',
  `fname` varchar(30) NOT NULL DEFAULT '',
  `lname` varchar(30) NOT NULL DEFAULT '',
  `age` varchar(32) NOT NULL DEFAULT '',
  `city` varchar(32) NOT NULL DEFAULT '',
  `state` varchar(32) NOT NULL DEFAULT '',
  `country` varchar(32) NOT NULL DEFAULT '',
  `zone` varchar(32) NOT NULL DEFAULT '',
  `homepage` varchar(64) NOT NULL DEFAULT '',
  `other_guilds` varchar(64) NOT NULL default '',
  `why` varchar(64) NOT NULL default '',
  `about` varchar(64) NOT NULL default '',
  `notes` varchar(64) NOT NULL DEFAULT '',
  `last_login` varchar(64) NOT NULL DEFAULT '',
  `date_joined` varchar(64) NOT NULL DEFAULT '',
  `tmp_mail` varchar(32) NOT NULL DEFAULT '',
  `group_id` smallint(6) NOT NULL DEFAULT '1',
  `is_member` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `online` int(11) NOT NULL DEFAULT '0',
  `user_lastvisit` int(15) DEFAULT NULL,
  `last_sid` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usr` (`usr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `renprefix_sessions`;
CREATE TABLE IF NOT EXISTS `renprefix_user_members` (
  `sess_id` varchar(35) DEFAULT NULL,
  `session_id` char(32) NOT NULL DEFAULT '',
  `session_user_id` varchar(5) DEFAULT NULL,
  `session_last_visit` int(11) NOT NULL DEFAULT '0',
  `session_start` int(11) NOT NULL DEFAULT '0',
  `session_time` int(11) NOT NULL DEFAULT '0',
  `session_ip` varchar(40) NOT NULL DEFAULT '',
  `session_browser` varchar(150) NOT NULL DEFAULT '',
  `session_forwarded_for` varchar(255) NOT NULL DEFAULT '',
  `session_page` varchar(255) NOT NULL DEFAULT '',
  `session_viewonline` tinyint(1) NOT NULL DEFAULT '1',
  `session_autologin` tinyint(1) NOT NULL DEFAULT '0',
  `session_admin` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `sess_id` (`sess_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

# --------------------------------------------------------
### Altered Tables

ALTER TABLE `renprefix_talenttree_data` ADD `roles` VARCHAR( 10 ) NULL DEFAULT NULL ,ADD `desc` VARCHAR( 255 ) NULL DEFAULT NULL;
ALTER TABLE `renprefix_talents_data` ADD `isspell` INT( 1 ) NULL DEFAULT NULL;
ALTER TABLE `renprefix_plugin` ADD `parent` VARCHAR( 100 ) NULL DEFAULT NULL AFTER `basename`;
ALTER TABLE `renprefix_members` CHANGE `account_id` `account_id` SMALLINT( 6 ) NULL DEFAULT NULL;
ALTER TABLE `renprefix_currency` CHANGE `count` `count` INT( 5 ) NULL DEFAULT NULL;

# --------------------------------------------------------
### Add to Tables

ADD `scope` VARCHAR( 20 ) NULL DEFAULT NULL AFTER `parent` ;
# --------------------------------------------------------
### Update Tables

UPDATE `renprefix_members` set `account_id` = NULL WHERE `account_id` = '0';

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

# session settings
INSERT INTO `renprefix_config` VALUES (190,'acc_session','NULL','blockframe','menu'),(1900, 'sess_time', '15', 'text{30|4', 'acc_session'),(1910, 'save_login', '1', 'radio{on^1|off^0', 'acc_session');

INSERT INTO `renprefix_user_members` (`id`, `usr`, `pass`, `email`, `regIP`, `dt`, `access`,`active`) VALUES (NULL, 'Admin', '5f4dcc3b5aa765d61d8327deb882cf99', '', '', '0000-00-00 00:00:00', '11:0','1');
INSERT INTO `renprefix_user_members` (`id`, `usr`, `pass`, `email`, `regIP`, `dt`, `access`,`active`) VALUES (NULL, 'Officer', '5f4dcc3b5aa765d61d8327deb882cf99', '', '', '0000-00-00 00:00:00', '11:0','1');
INSERT INTO `renprefix_user_members` (`id`, `usr`, `pass`, `email`, `regIP`, `dt`, `access`,`active`) VALUES (NULL, 'Guild', '5f4dcc3b5aa765d61d8327deb882cf99', '', '', '0000-00-00 00:00:00', '11:0','1');

# --------------------------------------------------------
### Menu Updates
INSERT INTO `renprefix_menu` VALUES ('', 'user', '');