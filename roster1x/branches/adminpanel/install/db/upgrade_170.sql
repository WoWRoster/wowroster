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

# --------------------------------------------------------
### Addon table

DROP TABLE IF EXISTS `renprefix_addon`;
CREATE TABLE `renprefix_addon` (
	`addon_id` int(11) NOT NULL AUTO_INCREMENT,
	`basename` varchar(16) NOT NULL DEFAULT '',
	`dbname` varchar(16) NOT NULL DEFAULT '',
	`version` varchar(16) NOT NULL DEFAULT '0',
	`hasconfig` int(1) NOT NULL DEFAULT 0,
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
	PRIMARY KEY (`addon_id`,`title`)
) TYPE=MyISAM;