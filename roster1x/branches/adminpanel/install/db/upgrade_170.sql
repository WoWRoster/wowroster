#
# MySQL Roster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### Config

DELETE FROM `renprefix_config` WHERE `id` = '5020' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '1.5.4' WHERE `id` = '1010' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = 'http://www.wowroster.net/Downloads/details/id=7.html' WHERE `id` = '6110' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = 'http://www.wowroster.net/Downloads/c=2.html' WHERE `id` = '6120' LIMIT 1;
UPDATE `renprefix_config` SET `id` = '5020', `config_type` = 'display_conf' WHERE `id` = '1050' LIMIT 1;
INSERT INTO `renprefix_config` VALUES (5025, 'roster_bg', 'img/wowroster_bg.jpg', 'text{128|30', 'display_conf');
UPDATE `renprefix_config` SET `config_value` = '1.7.1' WHERE `id` = '4' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '3' WHERE `id` = '3' LIMIT 1;
INSERT INTO `renprefix_config` VALUES (1095, 'alt_img_suffix', 'gif', 'select{jpg^jpg|png^png|gif^gif', 'main_conf');
ALTER TABLE `renprefix_config` ORDER BY `id`;
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
INSERT INTO `renprefix_config` VALUES (210, 'rosterdiag', '%roster%/rosterdiag.php', 'newlink', 'menu');
INSERT INTO `renprefix_config` VALUES (220, 'documentation', 'http://wowroster.net/wiki', 'newlink', 'menu');


# --------------------------------------------------------
### Addon table

DROP TABLE IF EXISTS `renprefix_addon`;
CREATE TABLE `renprefix_addon` (
	`addon_id` int(11) NOT NULL AUTO_INCREMENT,
	`basename` varchar(16) NOT NULL DEFAULT '',
	`dbname` varchar(16) NOT NULL DEFAULT '',
	`version` varchar(16) NOT NULL DEFAULT '0',
	`hasconfig` varchar(16) NOT NULL DEFAULT '0',
	`hastrigger` int(1) NOT NULL DEFAULT 0,
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