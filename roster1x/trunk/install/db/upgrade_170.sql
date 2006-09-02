#
# MySQL Roster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### Config

DELETE FROM `renprefix_config` WHERE `id` = '5020' LIMIT 1;

UPDATE `renprefix_config` SET `config_value` = '1.5.4' WHERE `id` = '1010' LIMIT 1;
UPDATE `renprefix_config` SET `config_name` = 'menu_memberlog' WHERE `id` = '4020' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = 'http://www.wowroster.net/Downloads/details/id=7.html' WHERE `id` = '6110' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = 'http://www.wowroster.net/Downloads/c=2.html' WHERE `id` = '6120' LIMIT 1;
UPDATE `renprefix_config` SET `id` = '5020', `config_type` = 'display_conf' WHERE `id` = '1050' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '1', `form_type` = 'radio{enable^1|disable^0' WHERE `id` = '10000' LIMIT 1;
UPDATE `renprefix_config` SET `form_type` = 'select{US Servers^http://www.worldofwarcraft.com/realmstatus/status.xml|European English^http://www.wow-europe.com/en/serverstatus/index.html|European German^http://www.wow-europe.com/de/serverstatus/index.html|European French^http://www.wow-europe.com/fr/serverstatus/index.html' WHERE `id` = '8000' LIMIT 1;

DELETE FROM `renprefix_config` WHERE `id` = '10010' LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = '10020' LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = '10030' LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` =  '4030' LIMIT 1;

INSERT INTO `renprefix_config` VALUES (1095, 'alt_img_suffix', 'gif', 'select{jpg^jpg|png^png|gif^gif', 'main_conf');
INSERT INTO `renprefix_config` VALUES (5025, 'roster_bg', 'img/wowroster_bg.jpg', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5050, 'processtime', '1', 'radio{on^1|off^0', 'display_conf');
INSERT INTO `renprefix_config` VALUES (10030, 'phpbb_group_admin', '2, 5, 22', 'text{128|30', 'update_access');

# --------------------------------------------------------
### NEW TABLES

DROP TABLE IF EXISTS `renprefix_memberlog`;
CREATE TABLE `renprefix_memberlog` (
  `log_id` int(11) unsigned NOT NULL,
  `member_id` int(11) unsigned NOT NULL,
  `name` varchar(64) NOT NULL default '',
  `guild_id` int(11) unsigned NOT NULL default '0',
  `class` varchar(32) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  `note` varchar(255) NOT NULL default '',
  `guild_rank` int(11) default '0',
  `guild_title` varchar(64) default NULL,
  `officer_note` varchar(255) NOT NULL default '',
  `update_time` datetime default NULL,
  `type` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`log_id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `renprefix_realmstatus`;
CREATE TABLE `renprefix_realmstatus` (
  `server_name` varchar(20) NOT NULL default '',
  `servertype` varchar(20) NOT NULL default '',
  `servertypecolor` varchar(8) NOT NULL default '',
  `serverstatus` varchar(20) NOT NULL default '',
  `serverpop` varchar(20) NOT NULL default '',
  `serverpopcolor` varchar(8) NOT NULL default '',
  `timestamp` tinyint(2) NOT NULL default '0',
  UNIQUE KEY `server_name` (`server_name`)
) TYPE=MyISAM;


ALTER TABLE `renprefix_mailbox`
  ADD `item_color` varchar(16) NOT NULL default '';

# --------------------------------------------------------
### The roster version and db version MUST be last

UPDATE `renprefix_config` SET `config_value` = '1.7.1' WHERE `id` = '4' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '3' WHERE `id` = '3' LIMIT 1;

ALTER TABLE `renprefix_config` ORDER BY `id`;