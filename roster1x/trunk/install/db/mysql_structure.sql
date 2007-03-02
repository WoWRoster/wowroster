#
# MySQL Roster Structure File
#
# * $Id$
#
# --------------------------------------------------------
### Account

DROP TABLE IF EXISTS `renprefix_account`;
CREATE TABLE `renprefix_account` (
  `account_id` smallint(6) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `hash` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`account_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Buffs

DROP TABLE IF EXISTS `renprefix_buffs`;
CREATE TABLE `renprefix_buffs` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(96) NOT NULL default '',
  `rank` varchar(32) NOT NULL default '',
  `count` int(11) unsigned NOT NULL default '0',
  `icon` varchar(64) NOT NULL default '',
  `tooltip` mediumtext NOT NULL,
  PRIMARY KEY (`member_id`,`name`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Config

DROP TABLE IF EXISTS `renprefix_config`;
CREATE TABLE `renprefix_config` (
  `id` int(11) NOT NULL,
  `config_name` varchar(255) default NULL,
  `config_value` tinytext,
  `form_type` mediumtext,
  `config_type` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Guild

DROP TABLE IF EXISTS `renprefix_guild`;
CREATE TABLE `renprefix_guild` (
  `guild_id` int(11) unsigned NOT NULL auto_increment,
  `guild_name` varchar(64) NOT NULL default '',
  `server` varchar(32) NOT NULL default '',
  `faction` varchar(8) default NULL,
  `guild_motd` varchar(255) NOT NULL default '',
  `guild_num_members` int(11) NOT NULL default '0',
  `guild_num_accounts` int(11) NOT NULL default '0',
  `update_time` datetime default NULL,
  `guild_dateupdatedutc` varchar(18) default NULL,
  `GPversion` varchar(6) default NULL,
  `guild_info_text` mediumtext,
  PRIMARY KEY  (`guild_id`),
  KEY `guild` (`guild_name`,`server`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Items

DROP TABLE IF EXISTS `renprefix_items`;
CREATE TABLE `renprefix_items` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `item_name` varchar(96) NOT NULL default '',
  `item_parent` varchar(64) NOT NULL default '',
  `item_slot` varchar(32) NOT NULL default '',
  `item_color` varchar(16) NOT NULL default '',
  `item_id` varchar(64) default NULL,
  `item_texture` varchar(64) NOT NULL default '',
  `item_quantity` int(11) default NULL,
  `item_tooltip` mediumtext NOT NULL,
  `level` INT( 11 ) default NULL,
  `item_level` INT( 11 ) default NULL,
  PRIMARY KEY  (`member_id`,`item_parent`,`item_slot`),
  KEY `parent` (`item_parent`),
  KEY `slot` (`item_slot`),
  KEY `name` (`item_name`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Mailbox

DROP TABLE IF EXISTS `renprefix_mailbox`;
CREATE TABLE `renprefix_mailbox` (
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
  `item_color` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`member_id`,`mailbox_slot`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Members

DROP TABLE IF EXISTS `renprefix_members`;
CREATE TABLE `renprefix_members` (
  `member_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `guild_id` int(11) unsigned NOT NULL default '0',
  `class` varchar(32) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  `note` varchar(255) NOT NULL default '',
  `guild_rank` int(11) default '0',
  `guild_title` varchar(64) default NULL,
  `officer_note` varchar(255) NOT NULL default '',
  `zone` varchar(64) NOT NULL default '',
  `status` varchar(16) NOT NULL default '',
  `online` int(1) default '0',
  `last_online` datetime default NULL,
  `update_time` datetime default NULL,
  `account_id` smallint(6) NOT NULL default '0',
  `inv` tinyint(4) NOT NULL default '3',
  `talents` tinyint(4) NOT NULL default '3',
  `quests` tinyint(4) NOT NULL default '3',
  `bank` tinyint(4) NOT NULL default '3',
  `spellbook` tinyint(4) NOT NULL default '3',
  `mail` tinyint(4) NOT NULL default '3',
  `recipes` tinyint(4) NOT NULL default '3',
  `bg` tinyint(4) NOT NULL default '3',
  `pvp` tinyint(4) NOT NULL default '3',
  `duels` tinyint(4) NOT NULL default '3',
  `money` tinyint(4) NOT NULL default '3',
  `item_bonuses` tinyint(4) NOT NULL default '3',
  `active` tinyint( 1 ) NOT NULL default '1',
  PRIMARY KEY  (`member_id`),
  KEY `member` (`guild_id`,`name`),
  KEY `name` (`name`),
  KEY `class` (`class`),
  KEY `level` (`level`),
  KEY `guild_rank` (`guild_rank`),
  KEY `last_online` (`last_online`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Member Log

DROP TABLE IF EXISTS `renprefix_memberlog`;
CREATE TABLE `renprefix_memberlog` (
  `log_id` int(11) unsigned NOT NULL auto_increment,
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

# --------------------------------------------------------
### Pets

DROP TABLE IF EXISTS `renprefix_pets`;
CREATE TABLE `renprefix_pets` (
  `member_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `slot` int(11) NOT NULL default '0',
  `level` int(11) NOT NULL default '0',
  `health` int(11) default NULL,
  `mana` int(11) default NULL,
  `xp` varchar(32) default NULL,
  `usedtp` int(11) default NULL,
  `totaltp` int(11) default NULL,
  `type` varchar(32) NOT NULL default '',
  `loyalty` varchar(32) NOT NULL default '',
  `icon` varchar(64) NOT NULL default '',
  `stat_int` varchar(32) default NULL,
  `stat_agl` varchar(32) default NULL,
  `stat_sta` varchar(32) default NULL,
  `stat_str` varchar(32) default NULL,
  `stat_spr` varchar(32) default NULL,
  `res_frost` int(11) default NULL,
  `res_arcane` int(11) default NULL,
  `res_fire` int(11) default NULL,
  `res_shadow` int(11) default NULL,
  `res_nature` int(11) default NULL,
  `armor` varchar(32) default NULL,
  `defense` int(11) default NULL,
  `melee_power` int(11) default NULL,
  `melee_rating` int(11) default NULL,
  `melee_range` varchar(16) default NULL,
  `melee_rangetooltip` tinytext,
  `melee_powertooltip` tinytext,
  PRIMARY KEY  (`member_id`,`name`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Players

DROP TABLE IF EXISTS `renprefix_players`;
CREATE TABLE `renprefix_players` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `guild_id` int(11) unsigned NOT NULL default '0',
  `dateupdatedutc` varchar(18) default NULL,
  `CPversion` varchar(6) default NULL,
  `race` varchar(32) NOT NULL default '',
  `sex` varchar(10) NOT NULL default '',
  `hearth` varchar(32) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  `server` varchar(32) NOT NULL default '',
  `talent_points` int(11) NOT NULL default '0',
  `money_c` int(11) NOT NULL default '0',
  `money_s` int(11) NOT NULL default '0',
  `money_g` int(11) NOT NULL default '0',
  `exp` varchar(32) NOT NULL default '',
  `class` varchar(32) NOT NULL default '',
  `health` int(11) NOT NULL default '0',
  `maildateutc` varchar(18) default NULL,
  `melee_power` int(11) NOT NULL default '0',
  `melee_power_c` int(11) NOT NULL default '0',
  `melee_power_b` int(11) NOT NULL default '0',
  `melee_power_d` int(11) NOT NULL default '0',
  `melee_hit` int(11) NOT NULL default '0',
  `melee_hit_c` int(11) NOT NULL default '0',
  `melee_hit_b` int(11) NOT NULL default '0',
  `melee_hit_d` int(11) NOT NULL default '0',
  `melee_crit` int(11) NOT NULL default '0',
  `melee_crit_c` int(11) NOT NULL default '0',
  `melee_crit_b` int(11) NOT NULL default '0',
  `melee_crit_d` int(11) NOT NULL default '0',
  `melee_haste` int(11) NOT NULL default '0',
  `melee_haste_c` int(11) NOT NULL default '0',
  `melee_haste_b` int(11) NOT NULL default '0',
  `melee_haste_d` int(11) NOT NULL default '0',
  `melee_crit_chance` float NOT NULL default '0',
  `melee_power_dps` float NOT NULL default '0',
  `melee_mhand_speed` float NOT NULL default '0',
  `melee_mhand_dps` float NOT NULL default '0',
  `melee_mhand_skill` int(11) NOT NULL default '0',
  `melee_mhand_mindam` int(11) NOT NULL default '0',
  `melee_mhand_maxdam` int(11) NOT NULL default '0',
  `melee_mhand_rating` int(11) NOT NULL default '0',
  `melee_mhand_rating_c` int(11) NOT NULL default '0',
  `melee_mhand_rating_b` int(11) NOT NULL default '0',
  `melee_mhand_rating_d` int(11) NOT NULL default '0',
  `melee_ohand_speed` float NOT NULL default '0',
  `melee_ohand_dps` float NOT NULL default '0',
  `melee_ohand_skill` int(11) NOT NULL default '0',
  `melee_ohand_mindam` int(11) NOT NULL default '0',
  `melee_ohand_maxdam` int(11) NOT NULL default '0',
  `melee_ohand_rating` int(11) NOT NULL default '0',
  `melee_ohand_rating_c` int(11) NOT NULL default '0',
  `melee_ohand_rating_b` int(11) NOT NULL default '0',
  `melee_ohand_rating_d` int(11) NOT NULL default '0',
  `melee_range_tooltip` tinytext,
  `melee_power_tooltip` tinytext,
  `ranged_power` int(11) NOT NULL default '0',
  `ranged_power_c` int(11) NOT NULL default '0',
  `ranged_power_b` int(11) NOT NULL default '0',
  `ranged_power_d` int(11) NOT NULL default '0',
  `ranged_hit` int(11) NOT NULL default '0',
  `ranged_hit_c` int(11) NOT NULL default '0',
  `ranged_hit_b` int(11) NOT NULL default '0',
  `ranged_hit_d` int(11) NOT NULL default '0',
  `ranged_crit` int(11) NOT NULL default '0',
  `ranged_crit_c` int(11) NOT NULL default '0',
  `ranged_crit_b` int(11) NOT NULL default '0',
  `ranged_crit_d` int(11) NOT NULL default '0',
  `ranged_haste` int(11) NOT NULL default '0',
  `ranged_haste_c` int(11) NOT NULL default '0',
  `ranged_haste_b` int(11) NOT NULL default '0',
  `ranged_haste_d` int(11) NOT NULL default '0',
  `ranged_crit_chance` float NOT NULL default '0',
  `ranged_power_dps` float NOT NULL default '0',
  `ranged_speed` float NOT NULL default '0',
  `ranged_dps` float NOT NULL default '0',
  `ranged_skill` int(11) NOT NULL default '0',
  `ranged_mindam` int(11) NOT NULL default '0',
  `ranged_maxdam` int(11) NOT NULL default '0',
  `ranged_rating` int(11) NOT NULL default '0',
  `ranged_rating_c` int(11) NOT NULL default '0',
  `ranged_rating_b` int(11) NOT NULL default '0',
  `ranged_rating_d` int(11) NOT NULL default '0',
  `ranged_range_tooltip` tinytext,
  `ranged_power_tooltip` tinytext,
  `spell_hit` int(11) NOT NULL default '0',
  `spell_hit_c` int(11) NOT NULL default '0',
  `spell_hit_b` int(11) NOT NULL default '0',
  `spell_hit_d` int(11) NOT NULL default '0',
  `spell_crit` int(11) NOT NULL default '0',
  `spell_crit_c` int(11) NOT NULL default '0',
  `spell_crit_b` int(11) NOT NULL default '0',
  `spell_crit_d` int(11) NOT NULL default '0',
  `spell_haste` int(11) NOT NULL default '0',
  `spell_haste_c` int(11) NOT NULL default '0',
  `spell_haste_b` int(11) NOT NULL default '0',
  `spell_haste_d` int(11) NOT NULL default '0',
  `spell_crit_chance` float NOT NULL default '0',
  `mana_regen_value` int(11) NOT NULL default '0',
  `mana_regen_time` int(11) NOT NULL default '0',
  `spell_penetration` int(11) NOT NULL default '0',
  `spell_damage` int(11) NOT NULL default '0',
  `spell_healing` int(11) NOT NULL default '0',
  `spell_damage_frost` int(11) NOT NULL default '0',
  `spell_damage_arcane` int(11) NOT NULL default '0',
  `spell_damage_fire` int(11) NOT NULL default '0',
  `spell_damage_shadow` int(11) NOT NULL default '0',
  `spell_damage_nature` int(11) NOT NULL default '0',
  `mana` int(11) NOT NULL default '0',
  `stat_int` int(11) NOT NULL default '0',
  `stat_int_c` int(11) NOT NULL default '0',
  `stat_int_b` int(11) NOT NULL default '0',
  `stat_int_d` int(11) NOT NULL default '0',
  `stat_agl` int(11) NOT NULL default '0',
  `stat_agl_c` int(11) NOT NULL default '0',
  `stat_agl_b` int(11) NOT NULL default '0',
  `stat_agl_d` int(11) NOT NULL default '0',
  `stat_sta` int(11) NOT NULL default '0',
  `stat_sta_c` int(11) NOT NULL default '0',
  `stat_sta_b` int(11) NOT NULL default '0',
  `stat_sta_d` int(11) NOT NULL default '0',
  `stat_str` int(11) NOT NULL default '0',
  `stat_str_c` int(11) NOT NULL default '0',
  `stat_str_b` int(11) NOT NULL default '0',
  `stat_str_d` int(11) NOT NULL default '0',
  `stat_spr` int(11) NOT NULL default '0',
  `stat_spr_c` int(11) NOT NULL default '0',
  `stat_spr_b` int(11) NOT NULL default '0',
  `stat_spr_d` int(11) NOT NULL default '0',
  `stat_def` int(11) NOT NULL default '0',
  `stat_def_c` int(11) NOT NULL default '0',
  `stat_def_b` int(11) NOT NULL default '0',
  `stat_def_d` int(11) NOT NULL default '0',
  `stat_armor` int(11) NOT NULL default '0',
  `stat_armor_c` int(11) NOT NULL default '0',
  `stat_armor_b` int(11) NOT NULL default '0',
  `stat_armor_d` int(11) NOT NULL default '0',
  `stat_block` int(11) NOT NULL default '0',
  `stat_block_c` int(11) NOT NULL default '0',
  `stat_block_b` int(11) NOT NULL default '0',
  `stat_block_d` int(11) NOT NULL default '0',
  `stat_parry` int(11) NOT NULL default '0',
  `stat_parry_c` int(11) NOT NULL default '0',
  `stat_parry_b` int(11) NOT NULL default '0',
  `stat_parry_d` int(11) NOT NULL default '0',
  `stat_defr` int(11) NOT NULL default '0',
  `stat_defr_c` int(11) NOT NULL default '0',
  `stat_defr_b` int(11) NOT NULL default '0',
  `stat_defr_d` int(11) NOT NULL default '0',
  `stat_dodge` int(11) NOT NULL default '0',
  `stat_dodge_c` int(11) NOT NULL default '0',
  `stat_dodge_b` int(11) NOT NULL default '0',
  `stat_dodge_d` int(11) NOT NULL default '0',
  `stat_res_ranged` int(11) NOT NULL default '0',
  `stat_res_spell` int(11) NOT NULL default '0',
  `stat_res_melee` int(11) NOT NULL default '0',
  `res_holy` int(11) NOT NULL default '0',
  `res_holy_c` int(11) NOT NULL default '0',
  `res_holy_b` int(11) NOT NULL default '0',
  `res_holy_d` int(11) NOT NULL default '0',
  `res_frost` int(11) NOT NULL default '0',
  `res_frost_c` int(11) NOT NULL default '0',
  `res_frost_b` int(11) NOT NULL default '0',
  `res_frost_d` int(11) NOT NULL default '0',
  `res_arcane` int(11) NOT NULL default '0',
  `res_arcane_c` int(11) NOT NULL default '0',
  `res_arcane_b` int(11) NOT NULL default '0',
  `res_arcane_d` int(11) NOT NULL default '0',
  `res_fire` int(11) NOT NULL default '0',
  `res_fire_c` int(11) NOT NULL default '0',
  `res_fire_b` int(11) NOT NULL default '0',
  `res_fire_d` int(11) NOT NULL default '0',
  `res_shadow` int(11) NOT NULL default '0',
  `res_shadow_c` int(11) NOT NULL default '0',
  `res_shadow_b` int(11) NOT NULL default '0',
  `res_shadow_d` int(11) NOT NULL default '0',
  `res_nature` int(11) NOT NULL default '0',
  `res_nature_c` int(11) NOT NULL default '0',
  `res_nature_b` int(11) NOT NULL default '0',
  `res_nature_d` int(11) NOT NULL default '0',
  `pvp_ratio` float NOT NULL default '0',
  `sessionHK` int(11) NOT NULL default '0',
  `sessionCP` int(11) NOT NULL default '0',
  `yesterdayHK` int(11) NOT NULL default '0',
  `yesterdayContribution` int(11) NOT NULL default '0',
  `lifetimeHK` int(11) NOT NULL default '0',
  `lifetimeRankName` varchar(64) NOT NULL default '0',
  `honorpoints` int(11) NOT NULL default '0',
  `arenapoints` int(11) NOT NULL default '0',
  `dodge` float NOT NULL default '0',
  `parry` float NOT NULL default '0',
  `block` float NOT NULL default '0',
  `mitigation` float NOT NULL default '0',
  `crit` float NOT NULL default '0',
  `lifetimeHighestRank` int(11) NOT NULL default '0',
  `clientLocale` varchar(4) NOT NULL default '',
  `timeplayed` int(11) NOT NULL default '0',
  `timelevelplayed` int(11) NOT NULL default '0',
  PRIMARY KEY  (`member_id`),
  KEY `name` (`name`,`server`)
) TYPE=MyISAM;

# --------------------------------------------------------
### PvP2

DROP TABLE IF EXISTS `renprefix_pvp2`;
CREATE TABLE `renprefix_pvp2` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `index` int(11) unsigned NOT NULL default '0',
  `date` datetime default NULL,
  `name` varchar(32) NOT NULL default '',
  `guild` varchar(32) NOT NULL default '',
  `realm` varchar(96) NOT NULL default '',
  `race` varchar(32) NOT NULL default '',
  `class` varchar(32) NOT NULL default '',
  `zone` varchar(32) NOT NULL default '',
  `subzone` varchar(32) NOT NULL default '',
  `enemy` tinyint(4) NOT NULL default '0',
  `win` tinyint(4) NOT NULL default '0',
  `rank` varchar(32) NOT NULL default '',
  `bg` tinyint(3) unsigned NOT NULL default '0',
  `leveldiff` tinyint(4) NOT NULL default '0',
  `honor` smallint(6) NOT NULL default '0',
  `column_id` mediumint(9) NOT NULL auto_increment,
  PRIMARY KEY  (`column_id`),
  KEY `date` (`date`,`guild`,`class`),
  KEY `member_id` (`member_id`,`index`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Quests

DROP TABLE IF EXISTS `renprefix_quests`;
CREATE TABLE `renprefix_quests` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `quest_name` varchar(64) NOT NULL default '',
  `quest_index` int(11) NOT NULL default '0',
  `quest_level` int(11) unsigned NOT NULL default '0',
  `quest_tag` varchar(32) NOT NULL default '',
  `is_complete` int(1) NOT NULL default '0',
  `zone` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`member_id`,`quest_name`),
  KEY `quest_index` (`quest_index`,`quest_level`,`quest_tag`),
  FULLTEXT KEY `quest_name` (`quest_name`),
  FULLTEXT KEY `zone` (`zone`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Realmstatus

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

# --------------------------------------------------------
### Recipes

DROP TABLE IF EXISTS `renprefix_recipes`;
CREATE TABLE `renprefix_recipes` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `recipe_name` varchar(64) NOT NULL default '',
  `recipe_type` varchar(100) NOT NULL default '',
  `skill_name` varchar(64) NOT NULL default '',
  `difficulty` int(11) NOT NULL default '0',
  `item_color` varchar(16) NOT NULL,
  `reagents` mediumtext NOT NULL,
  `recipe_texture` varchar(64) NOT NULL default '',
  `recipe_tooltip` mediumtext NOT NULL,
  `categories` varchar(64) NOT NULL default '',
  `level` int(11) default NULL,
  `item_level` INT( 11 ) default NULL,
  PRIMARY KEY  (`member_id`,`skill_name`,`recipe_name`,`categories`),
  KEY `skill_nameI` (`skill_name`),
  KEY `recipe_nameI` (`recipe_name`),
  KEY `categoriesI` (`categories`),
  KEY `levelI` (`level`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Reputation

DROP TABLE IF EXISTS `renprefix_reputation`;
CREATE TABLE `renprefix_reputation` (
  `member_id` int(10) unsigned NOT NULL default '0',
  `faction` varchar(32) NOT NULL default '',
  `name` varchar(32) NOT NULL default '',
  `curr_rep` int(8) NULL,
  `max_rep` int(8) NULL,
  `AtWar` int(11) NOT NULL default '0',
  `Standing` varchar(32) default '',
  PRIMARY KEY  (`member_id`,`name`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Skills

DROP TABLE IF EXISTS `renprefix_skills`;
CREATE TABLE `renprefix_skills` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `skill_type` varchar(32) NOT NULL default '',
  `skill_name` varchar(32) NOT NULL default '',
  `skill_order` int(11) NOT NULL default '0',
  `skill_level` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`member_id`,`skill_name`),
  KEY `skill_type` (`skill_type`),
  KEY `skill_name` (`skill_name`),
  KEY `skill_order` (`skill_order`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Spellbook

DROP TABLE IF EXISTS `renprefix_spellbook`;
CREATE TABLE `renprefix_spellbook` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `spell_name` varchar(64) NOT NULL default '',
  `spell_type` varchar(100) NOT NULL default '',
  `spell_texture` varchar(64) NOT NULL default '',
  `spell_rank` varchar(64) NOT NULL default '',
  `spell_tooltip` mediumtext NOT NULL,
  PRIMARY KEY (`member_id`,`spell_name`,`spell_rank`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Spellbook Tree

DROP TABLE IF EXISTS `renprefix_spellbooktree`;
CREATE TABLE `renprefix_spellbooktree` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `spell_type` varchar(64) NOT NULL default '',
  `spell_texture` varchar(64) NOT NULL default '',
  PRIMARY KEY (`member_id`,`spell_type`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Talents

DROP TABLE IF EXISTS `renprefix_talents`;
CREATE TABLE `renprefix_talents` (
  `member_id` int(11) NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `tree` varchar(64) NOT NULL default '',
  `row` tinyint(4) NOT NULL default '0',
  `column` tinyint(4) NOT NULL default '0',
  `rank` tinyint(4) NOT NULL default '0',
  `maxrank` tinyint(4) NOT NULL default '0',
  `tooltip` mediumtext NOT NULL,
  `texture` varchar(64) NOT NULL default '',
  PRIMARY KEY (`member_id`,`tree`,`row`,`column`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Talent Tree

DROP TABLE IF EXISTS `renprefix_talenttree`;
CREATE TABLE `renprefix_talenttree` (
  `member_id` int(11) NOT NULL default '0',
  `tree` varchar(64) NOT NULL default '',
  `background` varchar(64) NOT NULL default '',
  `order` tinyint(4) NOT NULL default '0',
  `pointsspent` tinyint(4) NOT NULL default '0',
  PRIMARY KEY (`member_id`,`tree`)
) TYPE=MyISAM;
