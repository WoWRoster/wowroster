#
# MySQL Roster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### Addon table

DROP TABLE IF EXISTS `renprefix_addon`;
CREATE TABLE `renprefix_addon` (
	`addon_id` int(11) NOT NULL AUTO_INCREMENT,
	`basename` varchar(16) NOT NULL DEFAULT '',
	`dbname` varchar(16) NOT NULL DEFAULT '',
	`version` varchar(16) NOT NULL DEFAULT '0',
	`hasconfig` varchar(16) NOT NULL DEFAULT '0',
	`active` int(1) NOT NULL DEFAULT 1,
	`fullname` tinytext NOT NULL,
	`description` mediumtext NOT NULL,
	`credits` mediumtext NOT NULL,
	PRIMARY KEY (`addon_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Guild Ranks table

DROP TABLE IF EXISTS `renprefix_guildranks`;
CREATE TABLE `renprefix_guildranks` (
  `index` int(11) NOT NULL,
  `title` varchar(96) NOT NULL,
  `control` varchar(64) NOT NULL,
  `guild_id` int(10) unsigned NOT NULL,
  KEY `index` (`index`,`guild_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Addon Trigger table

DROP TABLE IF EXISTS `renprefix_addon_trigger`;
CREATE TABLE `renprefix_addon_trigger` (
	`trigger_id` int(11) AUTO_INCREMENT,
	`addon_id` int(11),
	`file` varchar(32),
	`active` int(1) NOT NULL default 0,
	PRIMARY KEY (`trigger_id`),
	KEY idfile (`addon_id`,`file`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Menu config table

DROP TABLE IF EXISTS `renprefix_menu`;
CREATE TABLE `renprefix_menu` (
	`config_id` int(11) AUTO_INCREMENT,
	`account_id` smallint(6) NOT NULL COMMENT '0 for default value',
	`section` varchar(16),
	`config` mediumtext,
	PRIMARY KEY (`config_id`),
	UNIQUE KEY `idsect` (`account_id`,`section`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Menu button table

DROP TABLE IF EXISTS `renprefix_menu_button`;
CREATE TABLE `renprefix_menu_button` (
	`button_id` int(11) AUTO_INCREMENT,
	`addon_id` int(11) NOT NULL COMMENT '0 for main roster',
	`title` varchar(32),
	`url` varchar(128),
	`need_creds` tinytext,
	PRIMARY KEY (`button_id`),
	KEY `idtitle` (`addon_id`,`title`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Account

DROP TABLE IF EXISTS `renprefix_account`;
CREATE TABLE `renprefix_account` (
	`account_id` smallint(6) AUTO_INCREMENT,
	`name` varchar(30) NOT NULL default '',
	`hash` varchar(32) NOT NULL default '',
	`level` int(8) NOT NULL default '10',
	PRIMARY KEY  (`account_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Groups

DROP TABLE IF EXISTS `renprefix_groups`;
CREATE TABLE `renprefix_groups` (
	`group_id` int(11) AUTO_INCREMENT,
	`group_type` smallint(6),
	`group_name` varchar(32) NOT NULL,
	`group_description` mediumtext,
	`group_master` int(11) NOT NULL COMMENT 'account_id',
	PRIMARY KEY (`group_id`),
	UNIQUE KEY `name` (`group_name`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Group members

DROP TABLE IF EXISTS `renprefix_group_members`;
CREATE TABLE `renprefix_group_members` (
	`group_id` int(11) NOT NULL,
	`account_id` int(11) NOT NULL,
	`status` smallint(6),
	PRIMARY KEY (`group_id`, `account_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Permissions

DROP TABLE IF EXISTS `renprefix_permissions`;
CREATE TABLE `renprefix_permissions` (
	`permission_id` int(11) AUTO_INCREMENT,
	`addon_id` int(11),
	`permission_name` varchar(32) NOT NULL,
	`permission_description` mediumtext,
	PRIMARY KEY (`permission_id`),
	UNIQUE KEY `name` (`addon_id`, `permission_name`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Group-Permissions

DROP TABLE IF EXISTS `renprefix_group_permissions`;
CREATE TABLE `renprefix_group_permissions` (
	`group_id` int(11) NOT NULL,
	`permission_id` int(11) NOT NULL,
	PRIMARY KEY (`group_id`,`permission_id`)
) TYPE=MyISAM;


INSERT INTO `renprefix_config` VALUES (5, 'startpage', 'main_conf', 'display', 'master');

# --------------------------------------------------------
### Config Menu Entries
INSERT INTO `renprefix_config` VALUES (110, 'main_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (120, 'guild_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (130, 'menu_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (140, 'display_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (150, 'index_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (160, 'char_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (170, 'realmstatus_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (180, 'data_links', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (190, 'guildbank_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (200, 'update_access', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (210, 'documentation', 'http://wowroster.net/wiki', 'newlink', 'menu');

INSERT INTO `renprefix_config` VALUES (3210, 'members_openfilter', '1', 'radio{open^1|closed^0', 'index_conf');

INSERT INTO `renprefix_config` VALUES (4013, 'menu_top_pane', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4016, 'menu_button_pane', '1', 'radio{on^1|off^0', 'menu_conf');

DELETE FROM `renprefix_config` WHERE `id` = 3080;
DELETE FROM `renprefix_config` WHERE `id` = 3090;
DELETE FROM `renprefix_config` WHERE `id` = 4100;

# --------------------------------------------------------
### Update entries for index column visibility
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3130;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3140;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3150;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3160;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3170;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3180;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3190;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3200;

# --------------------------------------------------------
### Update entries for character page visibility (global)
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7015;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7020;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7030;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7040;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7050;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7060;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7070;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7080;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7090;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7100;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7110;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7120;

DELETE FROM `renprefix_config` WHERE `id` = 10000;

INSERT INTO `renprefix_config` VALUES (10000, 'auth_update', '10', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10010, 'auth_updateGP', '1', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10020, 'auth_install_addon', '-1', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10030, 'auth_roster_config', '-1', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10040, 'auth_character_config', '1', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10050, 'auth_change_pass', '10', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10060, 'auth_diag_button', '0', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10070, 'auth_addon_config', '-1', 'access', 'update_access');

# --------------------------------------------------------
### Menu table entries
INSERT INTO `renprefix_menu` VALUES (1, 0, 'main', 'b1:b2:b3:b4:b5|b6:b7:b8:b9|b10:b11:b12:b13');

# --------------------------------------------------------
### Menu Button entries
INSERT INTO `renprefix_menu_button` VALUES (1, 0, 'Roster', 'index.php',11);
INSERT INTO `renprefix_menu_button` VALUES (2, 0, 'Guild Info', 'guildinfo.php',11);
INSERT INTO `renprefix_menu_button` VALUES (3, 0, 'Stats', 'stats.php',11);
INSERT INTO `renprefix_menu_button` VALUES (4, 0, 'Professions', 'tradeskills.php',11);
INSERT INTO `renprefix_menu_button` VALUES (5, 0, 'GuildBank', 'guildbank.php',11);
INSERT INTO `renprefix_menu_button` VALUES (6, 0, 'PvP Stats', 'guildpvp.php',11);
INSERT INTO `renprefix_menu_button` VALUES (7, 0, 'Honor', 'honor.php',11);
INSERT INTO `renprefix_menu_button` VALUES (8, 0, 'Member Log', 'memberlog.php',11);
INSERT INTO `renprefix_menu_button` VALUES (9, 0, 'Keys', 'keys.php',11);
INSERT INTO `renprefix_menu_button` VALUES (10, 0, 'User Control', 'rostercp.php',11);
INSERT INTO `renprefix_menu_button` VALUES (11, 0, 'Find Team', 'questlist.php',11);
INSERT INTO `renprefix_menu_button` VALUES (12, 0, 'Search', 'search.php',11);
INSERT INTO `renprefix_menu_button` VALUES (13, 0, 'Credits', 'credits.php',11);

# --------------------------------------------------------
### Add character table and populate
CREATE TABLE `renprefix_characters` (
  `member_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `server` varchar(32) NOT NULL,
  `faction` varchar(8) NOT NULL,
  `class` varchar(32) NOT NULL,
  `level` int(11) NOT NULL,
  `zone` varchar(64) NOT NULL,
  `last_online` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `character` (`server`,`name`),
  KEY `class` (`class`),
  KEY `level` (`level`),
  KEY `last_online` (`last_online`)
) TYPE=MyISAM
SELECT `member`.`member_id`,
  `member`.`name`,
  `guild`.`server`,
  `guild`.`faction`,
  `member`.`class`,
  `member`.`level`,
  `member`.`zone`,
  `member`.`last_online`,
  `member`.`update_time`
FROM `renprefix_members` `members`
LEFT JOIN `renprefix_guild` `guild` ON `members`.`guild_id` = `guild`.`guild_id`;

# --------------------------------------------------------
### Reconfigure players table
ALTER TABLE `renprefix_players`
 	ADD `account_id` int(11) NOT NULL default '0' AFTER `member_id`,
	ADD `talents` tinytext,
	ADD `spellbook` tinytext,
	ADD `mail` tinytext,
	ADD `inv` tinytext,
	ADD `money` tinytext,
	ADD `bank` tinytext,
	ADD `recipes` tinytext,
	ADD `quests` tinytext,
	ADD `bg` tinytext,
	ADD `pvp` tinytext,
	ADD `duels` tinytext,
	ADD `item_bonuses` tinytext,
	CHANGE `dateupdatedutc` `dateupdatedutc` datetime default NULL,
	DROP `name`,
	DROP `guild_id`,
	DROP `server`,
	DROP `class`,
	DROP `level`;

# --------------------------------------------------------
### Calculate new permission values form the old ones
UPDATE `renprefix_players` `players`
INNER JOIN `renprefix_members` `members`
	ON `players`.`member_id` = `members`.`member_id`
SET
	`players`.`talents`	= (`members`.`talents`-1)*5,
	`players`.`spellbook`	= (`members`.`spellbook`-1)*5,
	`players`.`mail`	= (`members`.`mail`-1)*5,
	`players`.`inv`		= (`members`.`inv`-1)*5,
	`players`.`money`	= (`members`.`money`-1)*5,
	`players`.`bank`	= (`members`.`bank`-1)*5,
	`players`.`recipes`	= (`members`.`recipes`-1)*5,
	`players`.`quests`	= (`members`.`quests`-1)*5,
	`players`.`bg`		= (`members`.`bg`-1)*5,
	`players`.`pvp`		= (`members`.`pvp`-1)*5,
	`players`.`duels`	= (`members`.`duels`-1)*5,
	`players`.`item_bonuses`= (`members`.`item_bonuses`-1)*5;

# --------------------------------------------------------
### Reconfigure members table
ALTER TABLE `renprefix_members`
	DROP KEY `member`,
	DROP `talents`,
	DROP `spellbook`,
	DROP `mail`,
	DROP `inv`,
	DROP `money`,
	DROP `bank`,
	DROP `recipes`,
	DROP `quests`,
	DROP `bg`,
	DROP `pvp`,
	DROP `duels`,
	DROP `item_bonuses`,
	ADD `active` tinyint(1) NOT NULL DEFAULT '0' AFTER `update_time`,
	DROP `name`,
	DROP `class`,
	DROP `level`,
	DROP `zone`,
	DROP `account_id`,
	CHANGE `member_id` `member_id` int(11) NOT NULL;
	

# --------------------------------------------------------
### Update guild table

ALTER TABLE `renprefix_guild`
  CHANGE `guild_dateupdatedutc` `guild_dateupdatedutc` datetime default NULL,
  DROP `update_time`;

# --------------------------------------------------------
### The roster version and db version MUST be last

UPDATE `renprefix_config` SET `config_value` = '1.8.0' WHERE `id` = '4' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '3' WHERE `id` = '3' LIMIT 1;

ALTER TABLE `renprefix_config` ORDER BY `id`;
