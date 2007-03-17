#
# MySQL Roster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### The roster version and db version MUST be last

DELETE FROM `renprefix_config` WHERE `id` = 1080 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 7130 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 7140 LIMIT 1;

# --------------------------------------------------------
### New master entry: Startpage
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

INSERT INTO `renprefix_config` VALUES (1050, 'default_page', 'members', 'function{pageNames', 'main_conf');
INSERT INTO `renprefix_config` VALUES (4030, 'menu_member_page', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (7005, 'recipe_disp', '0', 'radio{show^1|collapse^0', 'char_conf');

UPDATE `renprefix_config` SET `config_value` = '1.8.0' WHERE `id` = '4' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '6' WHERE `id` = '3' LIMIT 1;

DELETE FROM `renprefix_config` WHERE `id` = 1080 LIMIT 1;

ALTER TABLE `renprefix_config` ORDER BY `id`;

# --------------------------------------------------------
### Fix Item Icons
UPDATE `renprefix_items` SET `item_texture` = REPLACE(`item_texture`,'Interface/Icons/','');
UPDATE `renprefix_mailbox` SET `mailbox_coin_icon` = REPLACE(`mailbox_coin_icon`,'Interface/Icons/','');
UPDATE `renprefix_mailbox` SET `item_icon` = REPLACE(`item_icon`,'Interface/Icons/','');
UPDATE `renprefix_pets` SET `icon` = REPLACE(`icon`,'Interface/Icons/','');
UPDATE `renprefix_recipes` SET `recipe_texture` = REPLACE(`recipe_texture`,'Interface/Icons/','');
UPDATE `renprefix_spellbook` SET `spell_texture` = REPLACE(`spell_texture`,'Interface/Icons/','');
UPDATE `renprefix_spellbooktree` SET `spell_texture` = REPLACE(`spell_texture`,'Interface/Icons/','');
UPDATE `renprefix_talents` SET `texture` = REPLACE(`texture`,'Interface/Icons/','');
UPDATE `renprefix_talenttree` SET `background` = REPLACE(`background`,'Interface/TalentFrame/','');

UPDATE `renprefix_items` SET `item_texture` = LOWER(REPLACE(`item_texture`,' ','_'));
UPDATE `renprefix_mailbox` SET `mailbox_coin_icon` = LOWER(REPLACE(`mailbox_coin_icon`,' ','_'));
UPDATE `renprefix_mailbox` SET `item_icon` = LOWER(REPLACE(`item_icon`,' ','_'));
UPDATE `renprefix_pets` SET `icon` = LOWER(REPLACE(`icon`,' ','_'));
UPDATE `renprefix_recipes` SET `recipe_texture` = LOWER(REPLACE(`recipe_texture`,' ','_'));
UPDATE `renprefix_spellbook` SET `spell_texture` = LOWER(REPLACE(`spell_texture`,' ','_'));
UPDATE `renprefix_spellbooktree` SET `spell_texture` = LOWER(REPLACE(`spell_texture`,' ','_'));
UPDATE `renprefix_talents` SET `texture` = LOWER(REPLACE(`texture`,' ','_'));
UPDATE `renprefix_talenttree` SET `background` = LOWER(REPLACE(`background`,' ','_'));

# --------------------------------------------------------
### Alter Players Table
ALTER TABLE `renprefix_players`
  ADD `stat_block` int(11) NOT NULL default '0',
  ADD `stat_block_c` int(11) NOT NULL default '0',
  ADD `stat_block_b` int(11) NOT NULL default '0',
  ADD `stat_block_d` int(11) NOT NULL default '0',
  ADD `stat_parry` int(11) NOT NULL default '0',
  ADD `stat_parry_c` int(11) NOT NULL default '0',
  ADD `stat_parry_b` int(11) NOT NULL default '0',
  ADD `stat_parry_d` int(11) NOT NULL default '0',
  ADD `stat_defr` int(11) NOT NULL default '0',
  ADD `stat_defr_c` int(11) NOT NULL default '0',
  ADD `stat_defr_b` int(11) NOT NULL default '0',
  ADD `stat_defr_d` int(11) NOT NULL default '0',
  ADD `stat_dodge` int(11) NOT NULL default '0',
  ADD `stat_dodge_c` int(11) NOT NULL default '0',
  ADD `stat_dodge_b` int(11) NOT NULL default '0',
  ADD `stat_dodge_d` int(11) NOT NULL default '0',
  ADD `stat_res_ranged` int(11) NOT NULL default '0',
  ADD `stat_res_spell` int(11) NOT NULL default '0',
  ADD `stat_res_melee` int(11) NOT NULL default '0',
  ADD `res_holy` int(11) NOT NULL default '0',
  ADD `res_holy_c` int(11) NOT NULL default '0',
  ADD `res_holy_b` int(11) NOT NULL default '0',
  ADD `res_holy_d` int(11) NOT NULL default '0',
  ADD `melee_power_c` int(11) NOT NULL default '0',
  ADD `melee_power_b` int(11) NOT NULL default '0',
  ADD `melee_power_d` int(11) NOT NULL default '0',
  ADD `melee_hit` int(11) NOT NULL default '0',
  ADD `melee_hit_c` int(11) NOT NULL default '0',
  ADD `melee_hit_b` int(11) NOT NULL default '0',
  ADD `melee_hit_d` int(11) NOT NULL default '0',
  ADD `melee_crit` int(11) NOT NULL default '0',
  ADD `melee_crit_c` int(11) NOT NULL default '0',
  ADD `melee_crit_b` int(11) NOT NULL default '0',
  ADD `melee_crit_d` int(11) NOT NULL default '0',
  ADD `melee_haste` int(11) NOT NULL default '0',
  ADD `melee_haste_c` int(11) NOT NULL default '0',
  ADD `melee_haste_b` int(11) NOT NULL default '0',
  ADD `melee_haste_d` int(11) NOT NULL default '0',
  ADD `melee_crit_chance` float NOT NULL default '0',
  ADD `melee_power_dps` float NOT NULL default '0',
  ADD `melee_mhand_speed` float NOT NULL default '0',
  ADD `melee_mhand_dps` float NOT NULL default '0',
  ADD `melee_mhand_skill` int(11) NOT NULL default '0',
  ADD `melee_mhand_mindam` int(11) NOT NULL default '0',
  ADD `melee_mhand_maxdam` int(11) NOT NULL default '0',
  ADD `melee_mhand_rating` int(11) NOT NULL default '0',
  ADD `melee_mhand_rating_c` int(11) NOT NULL default '0',
  ADD `melee_mhand_rating_b` int(11) NOT NULL default '0',
  ADD `melee_mhand_rating_d` int(11) NOT NULL default '0',
  ADD `melee_ohand_speed` float NOT NULL default '0',
  ADD `melee_ohand_dps` float NOT NULL default '0',
  ADD `melee_ohand_skill` int(11) NOT NULL default '0',
  ADD `melee_ohand_mindam` int(11) NOT NULL default '0',
  ADD `melee_ohand_maxdam` int(11) NOT NULL default '0',
  ADD `melee_ohand_rating` int(11) NOT NULL default '0',
  ADD `melee_ohand_rating_c` int(11) NOT NULL default '0',
  ADD `melee_ohand_rating_b` int(11) NOT NULL default '0',
  ADD `melee_ohand_rating_d` int(11) NOT NULL default '0',
  DROP `melee_rating`,
  DROP `melee_range`,
  ADD `ranged_power_c` int(11) NOT NULL default '0',
  ADD `ranged_power_b` int(11) NOT NULL default '0',
  ADD `ranged_power_d` int(11) NOT NULL default '0',
  ADD `ranged_hit` int(11) NOT NULL default '0',
  ADD `ranged_hit_c` int(11) NOT NULL default '0',
  ADD `ranged_hit_b` int(11) NOT NULL default '0',
  ADD `ranged_hit_d` int(11) NOT NULL default '0',
  ADD `ranged_crit` int(11) NOT NULL default '0',
  ADD `ranged_crit_c` int(11) NOT NULL default '0',
  ADD `ranged_crit_b` int(11) NOT NULL default '0',
  ADD `ranged_crit_d` int(11) NOT NULL default '0',
  ADD `ranged_haste` int(11) NOT NULL default '0',
  ADD `ranged_haste_c` int(11) NOT NULL default '0',
  ADD `ranged_haste_b` int(11) NOT NULL default '0',
  ADD `ranged_haste_d` int(11) NOT NULL default '0',
  ADD `ranged_crit_chance` float NOT NULL default '0',
  ADD `ranged_power_dps` float NOT NULL default '0',
  ADD `ranged_speed` float NOT NULL default '0',
  ADD `ranged_dps` float NOT NULL default '0',
  ADD `ranged_skill` int(11) NOT NULL default '0',
  ADD `ranged_mindam` int(11) NOT NULL default '0',
  ADD `ranged_maxdam` int(11) NOT NULL default '0',
  ADD `ranged_rating_c` int(11) NOT NULL default '0',
  ADD `ranged_rating_b` int(11) NOT NULL default '0',
  ADD `ranged_rating_d` int(11) NOT NULL default '0',
  DROP `ranged_range`,
  ADD `spell_hit` int(11) NOT NULL default '0',
  ADD `spell_hit_c` int(11) NOT NULL default '0',
  ADD `spell_hit_b` int(11) NOT NULL default '0',
  ADD `spell_hit_d` int(11) NOT NULL default '0',
  ADD `spell_crit` int(11) NOT NULL default '0',
  ADD `spell_crit_c` int(11) NOT NULL default '0',
  ADD `spell_crit_b` int(11) NOT NULL default '0',
  ADD `spell_crit_d` int(11) NOT NULL default '0',
  ADD `spell_haste` int(11) NOT NULL default '0',
  ADD `spell_haste_c` int(11) NOT NULL default '0',
  ADD `spell_haste_b` int(11) NOT NULL default '0',
  ADD `spell_haste_d` int(11) NOT NULL default '0',
  ADD `spell_crit_chance` float NOT NULL default '0',
  ADD `mana_regen_value` int(11) NOT NULL default '0',
  ADD `mana_regen_time` int(11) NOT NULL default '0',
  ADD `spell_penetration` int(11) NOT NULL default '0',
  ADD `spell_damage` int(11) NOT NULL default '0',
  ADD `spell_healing` int(11) NOT NULL default '0',
  ADD `spell_damage_frost` int(11) NOT NULL default '0',
  ADD `spell_damage_arcane` int(11) NOT NULL default '0',
  ADD `spell_damage_fire` int(11) NOT NULL default '0',
  ADD `spell_damage_shadow` int(11) NOT NULL default '0',
  ADD `spell_damage_nature` int(11) NOT NULL default '0',
  ADD `raceEn` varchar(32) NOT NULL default '' AFTER `race`,
  ADD `classEn` varchar(32) NOT NULL default '' AFTER `class`,
  ADD `sexid` tinyint(1) NOT NULL default '0' AFTER `sex`;


# --------------------------------------------------------
### Alter Members Table
ALTER TABLE `renprefix_members`
  ADD `active` tinyint(1) NOT NULL default '0',
  DROP `update_time`;


# --------------------------------------------------------
### Alter Guild Table
ALTER TABLE `renprefix_guild`
  CHANGE `faction` `faction` varchar(32) NOT NULL default '0',
  ADD `factionEn` varchar(32) NOT NULL default '' AFTER `faction`;

# --------------------------------------------------------
### Addon table

DROP TABLE IF EXISTS `renprefix_addon`;
CREATE TABLE `renprefix_addon` (
	`addon_id` int(11) NOT NULL AUTO_INCREMENT,
	`basename` varchar(16) NOT NULL DEFAULT '',
	`version` varchar(16) NOT NULL DEFAULT '0',
	`hasconfig` varchar(16) NOT NULL DEFAULT '0',
	`active` int(1) NOT NULL DEFAULT 1,
	`fullname` tinytext NOT NULL,
	`description` mediumtext NOT NULL,
	`credits` mediumtext NOT NULL,
	PRIMARY KEY (`addon_id`)
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
	`section` varchar(16),
	`config` mediumtext,
	PRIMARY KEY (`config_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Menu button table

DROP TABLE IF EXISTS `renprefix_menu_button`;
CREATE TABLE `renprefix_menu_button` (
	`button_id` int(11) AUTO_INCREMENT,
	`addon_id` int(11) NOT NULL COMMENT '0 for main roster',
	`title` varchar(32),
	`url` varchar(128),
	PRIMARY KEY (`button_id`),
	KEY `idtitle` (`addon_id`,`title`)
) TYPE=MyISAM;
