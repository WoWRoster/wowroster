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
	PRIMARY KEY (`addon_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Addon Menu table

DROP TABLE IF EXISTS `renprefix_addon_menu`;
CREATE TABLE `renprefix_addon_menu` (
	`addon_name` varchar(16),
	`title` varchar(32),
	`url` varchar(64),
	`active` int(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (`addon_name`,`title`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Addon Trigger table

DROP TABLE IF EXISTS `renprefix_addon_trigger`;
CREATE TABLE `renprefix_addon_trigger` (
	`addon_name` varchar(16),
	`file` varchar(32),
	`active` int(1) NOT NULL default '0',
	PRIMARY KEY  (`addon_name`,`file`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Account

DROP TABLE IF EXISTS `renprefix_account`;
CREATE TABLE `renprefix_account` (
	`account_id` smallint(6) NOT NULL auto_increment,
	`name` varchar(30) NOT NULL default '',
	`hash` varchar(32) NOT NULL default '',
	`level` int(8) NOT NULL default '10',
	PRIMARY KEY  (`account_id`)
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

DELETE FROM `renprefix_config` WHERE `id` = 3080;
DELETE FROM `renprefix_config` WHERE `id` = 3090;

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

# --------------------------------------------------------
### Update character page visibility values per character
ALTER TABLE `renprefix_members`
	CHANGE `talents` `talents` tinytext,
	CHANGE `spellbook` `spellbook` tinytext,
	CHANGE `mail` `mail` tinytext,
	CHANGE `inv` `inv` tinytext,
	CHANGE `money` `money` tinytext,
	CHANGE `bank` `bank` tinytext,
	CHANGE `recipes` `recipes` tinytext,
	CHANGE `quests` `quests` tinytext,
	CHANGE `bg` `bg` tinytext,
	CHANGE `pvp` `pvp` tinytext,
	CHANGE `duels` `duels` tinytext,
	CHANGE `item_bonuses` `item_bonuses` tinytext;

UPDATE `renprefix_members` SET
	`talents`	= (`talents`-1)*5,
	`spellbook`	= (`spellbook`-1)*5,
	`mail`		= (`mail`-1)*5,
	`inv`		= (`inv`-1)*5,
	`money`		= (`money`-1)*5,
	`bank`		= (`bank`-1)*5,
	`recipes`	= (`recipes`-1)*5,
	`quests`	= (`quests`-1)*5,
	`bg`		= (`bg`-1)*5,
	`pvp`		= (`pvp`-1)*5,
	`duels`		= (`duels`-1)*5,
	`item_bonuses`	= (`item_bonuses`-1)*5;


# --------------------------------------------------------
### The roster version and db version MUST be last

UPDATE `renprefix_config` SET `config_value` = '1.8.0' WHERE `id` = '4' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '3' WHERE `id` = '3' LIMIT 1;

ALTER TABLE `renprefix_config` ORDER BY `id`;