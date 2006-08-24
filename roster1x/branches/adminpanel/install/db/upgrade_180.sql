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
### Account table

ALTER TABLE `renprefix_account`
	ADD `level` int(8) NOT NULL DEFAULT 10;



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

INSERT INTO `renprefix_config` VALUES (3210, 'members_openfilter', '1', 'radio{open^1|closed^0', 'index_conf');