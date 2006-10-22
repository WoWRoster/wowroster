#
# MySQL Roster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### Config

DROP TABLE IF EXISTS `renprefix_config`;
CREATE TABLE IF NOT EXISTS `renprefix_config` (
  `id` int(11) NOT NULL,
  `config_name` varchar(255) default NULL,
  `config_value` tinytext,
  `form_type` mediumtext,
  `config_type` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Mailbox

DROP TABLE IF EXISTS `renprefix_mailbox`;
CREATE TABLE IF NOT EXISTS `renprefix_mailbox` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `mailbox_slot` int(11) NOT NULL default '0',
  `mailbox_coin` int(11) NOT NULL default '0',
  `mailbox_coin_icon` varchar(64) NOT NULL default '',
  `mailbox_days` float NOT NULL default '0',
  `mailbox_sender` varchar(30) NOT NULL default '',
  `mailbox_subject` mediumtext NOT NULL,
  `item_icon` varchar(64) NOT NULL default '',
  `item_name` varchar(96) NOT NULL default '',
  `item_quantity` int(11) default NULL,
  `item_tooltip` mediumtext NOT NULL,
  PRIMARY KEY  (`member_id`,`mailbox_slot`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Guild

ALTER TABLE `renprefix_guild`
  ADD `guild_num_accounts` INT( 11 ) NOT NULL default '0' AFTER `guild_num_members`,
  ADD `guild_info_text` mediumtext NULL;

# --------------------------------------------------------
### Items

ALTER TABLE `renprefix_items`
  CHANGE `item_name` `item_name` VARCHAR( 96 ) NOT NULL default '';

# --------------------------------------------------------
### Members

ALTER TABLE `renprefix_members`
  CHANGE `group` `status` VARCHAR( 16 ) NOT NULL default '',
  ADD `spellbook` TINYINT( 4 ) NOT NULL default '3',
  ADD `mail` TINYINT( 4 ) NOT NULL default '3',
  ADD `recipes` TINYINT( 4 ) NOT NULL default '3',
  ADD `bg` TINYINT( 4 ) NOT NULL default '3',
  ADD `pvp` TINYINT( 4 ) NOT NULL default '3',
  ADD `duels` TINYINT( 4 ) NOT NULL default '3',
  ADD `money` TINYINT( 4 ) NOT NULL default '3',
  ADD `item_bonuses` TINYINT( 4 ) NOT NULL default '3';

# --------------------------------------------------------
### Players

ALTER TABLE `renprefix_players`
  DROP `version`,
  DROP `res_frost`,
  DROP `res_arcane`,
  DROP `res_fire`,
  DROP `res_shadow`,
  DROP `res_nature`,
  DROP `stat_int2`,
  DROP `stat_agl2`,
  DROP `stat_sta2`,
  DROP `stat_str2`,
  DROP `stat_spr2`,
  DROP `stat_total`,
  DROP `defense`,
  DROP `stat_int`,
  DROP `stat_agl`,
  DROP `stat_sta`,
  DROP `stat_str`,
  DROP `stat_spr`,
  DROP `armor`,
  ADD `maildateutc` VARCHAR( 18 ) NULL AFTER `health`,
  ADD `stat_int` int(11) NOT NULL default '0' AFTER `mana`,
  ADD `stat_int_c` int(11) NOT NULL default '0' AFTER `stat_int`,
  ADD `stat_int_b` int(11) NOT NULL default '0' AFTER `stat_int_c`,
  ADD `stat_int_d` int(11) NOT NULL default '0' AFTER `stat_int_b`,
  ADD `stat_agl` int(11) NOT NULL default '0' AFTER `stat_int_d`,
  ADD `stat_agl_c` int(11) NOT NULL default '0' AFTER `stat_agl`,
  ADD `stat_agl_b` int(11) NOT NULL default '0' AFTER `stat_agl_c`,
  ADD `stat_agl_d` int(11) NOT NULL default '0' AFTER `stat_agl_b`,
  ADD `stat_sta` int(11) NOT NULL default '0' AFTER `stat_agl_d`,
  ADD `stat_sta_c` int(11) NOT NULL default '0' AFTER `stat_sta`,
  ADD `stat_sta_b` int(11) NOT NULL default '0' AFTER `stat_sta_c`,
  ADD `stat_sta_d` int(11) NOT NULL default '0' AFTER `stat_sta_b`,
  ADD `stat_str` int(11) NOT NULL default '0' AFTER `stat_sta_d`,
  ADD `stat_str_c` int(11) NOT NULL default '0' AFTER `stat_str`,
  ADD `stat_str_b` int(11) NOT NULL default '0' AFTER `stat_str_c`,
  ADD `stat_str_d` int(11) NOT NULL default '0' AFTER `stat_str_b`,
  ADD `stat_spr` int(11) NOT NULL default '0' AFTER `stat_str_d`,
  ADD `stat_spr_c` int(11) NOT NULL default '0' AFTER `stat_spr`,
  ADD `stat_spr_b` int(11) NOT NULL default '0' AFTER `stat_spr_c`,
  ADD `stat_spr_d` int(11) NOT NULL default '0' AFTER `stat_spr_b`,
  ADD `stat_def` int(11) NOT NULL default '0' AFTER `stat_spr_d`,
  ADD `stat_def_c` int(11) NOT NULL default '0' AFTER `stat_def`,
  ADD `stat_def_b` int(11) NOT NULL default '0' AFTER `stat_def_c`,
  ADD `stat_def_d` int(11) NOT NULL default '0' AFTER `stat_def_b`,
  ADD `stat_armor` int(11) NOT NULL default '0' AFTER `stat_def_d`,
  ADD `stat_armor_c` int(11) NOT NULL default '0' AFTER `stat_armor`,
  ADD `stat_armor_b` int(11) NOT NULL default '0' AFTER `stat_armor_c`,
  ADD `stat_armor_d` int(11) NOT NULL default '0' AFTER `stat_armor_b`,
  ADD `res_frost` int(11) NOT NULL default '0' AFTER `stat_armor_d`,
  ADD `res_frost_c` int(11) NOT NULL default '0' AFTER `res_frost`,
  ADD `res_frost_b` int(11) NOT NULL default '0' AFTER `res_frost_c`,
  ADD `res_frost_d` int(11) NOT NULL default '0' AFTER `res_frost_b`,
  ADD `res_arcane` int(11) NOT NULL default '0' AFTER `res_frost_d`,
  ADD `res_arcane_c` int(11) NOT NULL default '0' AFTER `res_arcane`,
  ADD `res_arcane_b` int(11) NOT NULL default '0' AFTER `res_arcane_c`,
  ADD `res_arcane_d` int(11) NOT NULL default '0' AFTER `res_arcane_b`,
  ADD `res_fire` int(11) NOT NULL default '0' AFTER `res_arcane_d`,
  ADD `res_fire_c` int(11) NOT NULL default '0' AFTER `res_fire`,
  ADD `res_fire_b` int(11) NOT NULL default '0' AFTER `res_fire_c`,
  ADD `res_fire_d` int(11) NOT NULL default '0' AFTER `res_fire_b`,
  ADD `res_shadow` int(11) NOT NULL default '0' AFTER `res_fire_d`,
  ADD `res_shadow_c` int(11) NOT NULL default '0' AFTER `res_shadow`,
  ADD `res_shadow_b` int(11) NOT NULL default '0' AFTER `res_shadow_c`,
  ADD `res_shadow_d` int(11) NOT NULL default '0' AFTER `res_shadow_b`,
  ADD `res_nature` int(11) NOT NULL default '0' AFTER `res_shadow_d`,
  ADD `res_nature_c` int(11) NOT NULL default '0' AFTER `res_nature`,
  ADD `res_nature_b` int(11) NOT NULL default '0' AFTER `res_nature_c`,
  ADD `res_nature_d` int(11) NOT NULL default '0' AFTER `res_nature_b`;

# --------------------------------------------------------
### Recipies

ALTER TABLE `renprefix_recipes`
  ADD `item_color` VARCHAR( 16 ) NOT NULL AFTER `difficulty`;

# --------------------------------------------------------
### Spellbook

ALTER TABLE `renprefix_spellbook`
  ADD `spell_tooltip` mediumtext NOT NULL;

# --------------------------------------------------------
### Talents

ALTER TABLE `renprefix_talents`
  CHANGE `name` `name` VARCHAR( 64 ) NOT NULL default '',
  CHANGE `tree` `tree` VARCHAR( 64 ) NOT NULL default '';

# --------------------------------------------------------
### Talent Tree

ALTER TABLE `renprefix_talenttree`
  CHANGE `tree` `tree` VARCHAR( 64 ) NOT NULL default '';
