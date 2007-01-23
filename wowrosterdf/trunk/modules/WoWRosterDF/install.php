<?php
if (!defined('ADMIN_MOD_INSTALL')) { exit; }

if( !defined('ROSTER_DF_INSTALLER') )
{
	define('ROSTER_DF_INSTALLER',true);
}

# module installer
function RosterDF_install($this_prefix, $this_base)
{
	global $installer, $db, $prefix;

	if( version_compare(CPG_NUKE, '9.1.1', '<') )
	{
		cpg_error('WoWRosterDF requires DragonFly version 9.1.1.0');
	}


	# --------------------------------------------------------
	### Account

	//$installer->add_query('DROP', $this_prefix.'account');
	$installer->add_query('CREATE', $this_prefix.'account', "
	`account_id` smallint(6) NOT NULL auto_increment,
	`name` varchar(30) NOT NULL default '',
	`hash` varchar(32) NOT NULL default '',
	PRIMARY KEY  (`account_id`)", $this_prefix.'account');


	# --------------------------------------------------------
	### Buffs

	//$installer->add_query('DROP', $this_prefix.'buffs');
	$installer->add_query('CREATE', $this_prefix.'buffs', "
	`member_id` int(11) unsigned NOT NULL default '0',
	`name` varchar(96) NOT NULL default '',
	`rank` varchar(32) NOT NULL default '',
	`count` int(11) unsigned NOT NULL default '0',
	`icon` varchar(64) NOT NULL default '',
	`tooltip` mediumtext NOT NULL,
	PRIMARY KEY (`member_id`,`name`)", $this_prefix.'buffs');


	# --------------------------------------------------------
	### Config

	//$installer->add_query('DROP', $this_prefix.'config');
	$installer->add_query('CREATE', $this_prefix.'config', "
	`id` int(11) NOT NULL,
	`config_name` varchar(255) default NULL,
	`config_value` tinytext,
	`form_type` mediumtext,
	`config_type` varchar(255) default NULL,
	PRIMARY KEY  (`id`)", $this_prefix.'config');


	# --------------------------------------------------------
	### Guild

	//$installer->add_query('DROP', $this_prefix.'guild');
	$installer->add_query('CREATE', $this_prefix.'guild', "
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
	KEY `guild` (`guild_name`,`server`)", $this_prefix.'guild');


	# --------------------------------------------------------
	### Items

	//$installer->add_query('DROP', $this_prefix.'items');
	$installer->add_query('CREATE', $this_prefix.'items', "
	`member_id` int(11) unsigned NOT NULL default '0',
	`item_name` varchar(96) NOT NULL default '',
	`item_parent` varchar(64) NOT NULL default '',
	`item_slot` varchar(32) NOT NULL default '',
	`item_color` varchar(16) NOT NULL default '',
	`item_id` varchar(64) default NULL,
	`item_texture` varchar(64) NOT NULL default '',
	`item_quantity` int(11) default NULL,
	`item_tooltip` mediumtext NOT NULL,
	`level` INT (11) default NULL,
	`item_level` INT (11) default NULL,
	PRIMARY KEY  (`member_id`,`item_parent`,`item_slot`),
	KEY `parent` (`item_parent`),
	KEY `slot` (`item_slot`),
	KEY `name` (`item_name`)", $this_prefix.'items');


	# --------------------------------------------------------
	### Mailbox

	//$installer->add_query('DROP', $this_prefix.'mailbox');
	$installer->add_query('CREATE', $this_prefix.'mailbox', "
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
	PRIMARY KEY  (`member_id`,`mailbox_slot`)", $this_prefix.'mailbox');


	# --------------------------------------------------------
	### Members

	//$installer->add_query('DROP', $this_prefix.'members');
	$installer->add_query('CREATE', $this_prefix.'members', "
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
	PRIMARY KEY  (`member_id`),
	KEY `member` (`guild_id`,`name`),
	KEY `name` (`name`),
	KEY `class` (`class`),
	KEY `level` (`level`),
	KEY `guild_rank` (`guild_rank`),
	KEY `last_online` (`last_online`)", $this_prefix.'members');


	# --------------------------------------------------------
	### Member Log

	//$installer->add_query('DROP', $this_prefix.'memberlog');
	$installer->add_query('CREATE', $this_prefix.'memberlog', "
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
	PRIMARY KEY  (`log_id`)", $this_prefix.'memberlog');


	# --------------------------------------------------------
	### Pets

	//$installer->add_query('DROP', $this_prefix.'pets');
	$installer->add_query('CREATE', $this_prefix.'pets', "
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
	PRIMARY KEY  (`member_id`,`name`)", $this_prefix.'pets');


	# --------------------------------------------------------
	### Players

	//$installer->add_query('DROP', $this_prefix.'players');
	$installer->add_query('CREATE', $this_prefix.'players', "
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
	`melee_power` int(11) default NULL,
	`melee_rating` int(11) default NULL,
	`melee_range` varchar(32) default NULL,
	`melee_range_tooltip` tinytext,
	`melee_power_tooltip` tinytext,
	`ranged_power` int(11) default NULL,
	`ranged_rating` int(11) default NULL,
	`ranged_range` varchar(32) default NULL,
	`ranged_range_tooltip` tinytext,
	`ranged_power_tooltip` tinytext,
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
	KEY `name` (`name`,`server`)", $this_prefix.'players');


	# --------------------------------------------------------
	### PvP2

	//$installer->add_query('DROP', $this_prefix.'pvp2');
	$installer->add_query('CREATE', $this_prefix.'pvp2', "
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
	KEY `member_id` (`member_id`,`index`)", $this_prefix.'pvp2');


	# --------------------------------------------------------
	### Quests

	//$installer->add_query('DROP', $this_prefix.'quests');
	$installer->add_query('CREATE', $this_prefix.'quests', "
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
	FULLTEXT KEY `zone` (`zone`)", $this_prefix.'quests');


	# --------------------------------------------------------
	### Realmstatus

	//$installer->add_query('DROP', $this_prefix.'realmstatus');
	$installer->add_query('CREATE', $this_prefix.'realmstatus', "
	`server_name` varchar(20) NOT NULL default '',
	`servertype` varchar(20) NOT NULL default '',
	`servertypecolor` varchar(8) NOT NULL default '',
	`serverstatus` varchar(20) NOT NULL default '',
	`serverpop` varchar(20) NOT NULL default '',
	`serverpopcolor` varchar(8) NOT NULL default '',
	`timestamp` tinyint(2) NOT NULL default '0',
	UNIQUE KEY `server_name` (`server_name`)", $this_prefix.'realmstatus');


	# --------------------------------------------------------
	### Recipes

	//$installer->add_query('DROP', $this_prefix.'recipes');
	$installer->add_query('CREATE', $this_prefix.'recipes', "
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
	`item_level` INT (11) default NULL,
	PRIMARY KEY  (`member_id`,`skill_name`,`recipe_name`,`categories`),
	KEY `skill_nameI` (`skill_name`),
	KEY `recipe_nameI` (`recipe_name`),
	KEY `categoriesI` (`categories`),
	KEY `levelI` (`level`)", $this_prefix.'recipes');


	# --------------------------------------------------------
	### Reputation

	//$installer->add_query('DROP', $this_prefix.'reputation');
	$installer->add_query('CREATE', $this_prefix.'reputation', "
	`member_id` int(10) unsigned NOT NULL default '0',
	`faction` varchar(32) NOT NULL default '',
	`name` varchar(32) NOT NULL default '',
	`curr_rep` int(8) NULL,
	`max_rep` int(8) NULL,
	`AtWar` int(11) NOT NULL default '0',
	`Standing` varchar(32) default '',
	PRIMARY KEY  (`member_id`,`name`)", $this_prefix.'reputation');


	# --------------------------------------------------------
	### Skills

	//$installer->add_query('DROP', $this_prefix.'skills');
	$installer->add_query('CREATE', $this_prefix.'skills', "
	`member_id` int(11) unsigned NOT NULL default '0',
	`skill_type` varchar(32) NOT NULL default '',
	`skill_name` varchar(32) NOT NULL default '',
	`skill_order` int(11) NOT NULL default '0',
	`skill_level` varchar(16) NOT NULL default '',
	PRIMARY KEY  (`member_id`,`skill_name`),
	KEY `skill_type` (`skill_type`),
	KEY `skill_name` (`skill_name`),
	KEY `skill_order` (`skill_order`)", $this_prefix.'skills');


	# --------------------------------------------------------
	### Spellbook

	//$installer->add_query('DROP', $this_prefix.'spellbook');
	$installer->add_query('CREATE', $this_prefix.'spellbook', "
	`member_id` int(11) unsigned NOT NULL default '0',
	`spell_name` varchar(64) NOT NULL default '',
	`spell_type` varchar(100) NOT NULL default '',
	`spell_texture` varchar(64) NOT NULL default '',
	`spell_rank` varchar(64) NOT NULL default '',
	`spell_tooltip` mediumtext NOT NULL,
	PRIMARY KEY (`member_id`,`spell_name`,`spell_rank`)", $this_prefix.'spellbook');


	# --------------------------------------------------------
	### Spellbook Tree

	//$installer->add_query('DROP', $this_prefix.'spellbooktree');
	$installer->add_query('CREATE', $this_prefix.'spellbooktree', "
	`member_id` int(11) unsigned NOT NULL default '0',
	`spell_type` varchar(64) NOT NULL default '',
	`spell_texture` varchar(64) NOT NULL default '',
	PRIMARY KEY (`member_id`,`spell_type`)", $this_prefix.'spellbooktree');


	# --------------------------------------------------------
	### Talents

	//$installer->add_query('DROP', $this_prefix.'talents');
	$installer->add_query('CREATE', $this_prefix.'talents', "
	`member_id` int(11) NOT NULL default '0',
	`name` varchar(64) NOT NULL default '',
	`tree` varchar(64) NOT NULL default '',
	`row` tinyint(4) NOT NULL default '0',
	`column` tinyint(4) NOT NULL default '0',
	`rank` tinyint(4) NOT NULL default '0',
	`maxrank` tinyint(4) NOT NULL default '0',
	`tooltip` mediumtext NOT NULL,
	`texture` varchar(64) NOT NULL default '',
	PRIMARY KEY (`member_id`,`tree`,`row`,`column`)", $this_prefix.'talents');


	# --------------------------------------------------------
	### Talent Tree

	//$installer->add_query('DROP', $this_prefix.'talenttree');
	$installer->add_query('CREATE', $this_prefix.'talenttree', "
	`member_id` int(11) NOT NULL default '0',
	`tree` varchar(64) NOT NULL default '',
	`background` varchar(64) NOT NULL default '',
	`order` tinyint(4) NOT NULL default '0',
	`pointsspent` tinyint(4) NOT NULL default '0',
	PRIMARY KEY (`member_id`,`tree`)", $this_prefix.'talenttree');




	# --------------------------------------------------------
	### Master Values

	$installer->add_query('INSERT', $this_prefix.'config', "1, 'config_list', 'main_conf|guild_conf|menu_conf|display_conf|index_conf|char_conf|realmstatus_conf|data_links|guildbank_conf|update_access', 'display', 'master'");
	$installer->add_query('INSERT', $this_prefix.'config', "2, 'roster_upd_pw', '', 'password:30|30', 'master'");
	$installer->add_query('INSERT', $this_prefix.'config', "3, 'roster_dbver', '5', 'display', 'master'");
	$installer->add_query('INSERT', $this_prefix.'config', "4, 'version', '1.7.3.0', 'display', 'master'");

	# --------------------------------------------------------
	### Main Roster Config

	$installer->add_query('INSERT', $this_prefix.'config', "1000, 'sqldebug', '1', 'radio{on^1|off^0', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1001, 'debug_mode', '1', 'radio{on^1|off^0', 'main_conf'");
	//NOT USED!! $installer->add_query('INSERT', $this_prefix.'config', "1002, 'sql_window', '1', 'radio{on^1|off^0', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1010, 'minCPver', '2.0.0', 'text{10|10', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1020, 'minGPver', '2.0.0', 'text{10|10', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1030, 'minPvPLogver', '2.3.1', 'text{10|10', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1040, 'roster_lang', 'enUS', 'function{rosterLangValue', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1060, 'website_address', '$BASEHREF', 'text{128|30', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1070, 'roster_dir', 'modules/$this_base', 'text{128|30', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1080, 'server_name_comp', '0', 'radio{on^1|off^0', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1085, 'interface_url', 'images/wowrosterdf/', 'text{128|30', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1090, 'img_suffix', 'jpg', 'select{jpg^jpg|png^png|gif^gif', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1095, 'alt_img_suffix', 'png', 'select{jpg^jpg|png^png|gif^gif', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1100, 'img_url', 'modules/$this_base/img/', 'text{128|30', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1110, 'timezone', 'PST', 'text{10|10', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1120, 'localtimeoffset', '0', 'select{-12^-12|-11^-11|-10^-10|-9^-9|-8^-8|-7^-7|-6^-6|-5^-5|-4^-4|-3.5^-3.5|-3^-3|-2^-2|-1^-1|0^0|+1^1|+2^2|+3^3|+3.5^3.5|+4^4|+4.5^4.5|+5^5|+5.5^5.5|+6^6|+6.5^6.5|+7^7|+8^8|+9^9|+9.5^9.5|+10^10|+11^11|+12^12|+13^13', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1130, 'pvp_log_allow', '1', 'radio{yes^1|no^0', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1140, 'use_update_triggers', '1', 'radio{on^1|off^0', 'main_conf'");

	# --------------------------------------------------------
	### Guild Settings

	$installer->add_query('INSERT', $this_prefix.'config', "2000, 'guild_name', 'guildName', 'text{50|30', 'guild_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "2010, 'server_name', 'realmName', 'text{50|30', 'guild_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "2020, 'guild_desc', 'A Great WoW Guild', 'text{255|30', 'guild_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "2030, 'server_type', 'PvE', 'select{PvE^PvE|PvP^PvP|RP^RP|RPPvP^RPPvP', 'guild_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "2040, 'alt_type', 'alt', 'text{30|30', 'guild_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "2050, 'alt_location', 'note', 'select{Player Note^note|Officer Note^officer_note|Guild Rank Number^guild_rank|Guild Title^guild_title', 'guild_conf'");

	# --------------------------------------------------------
	### Index Page

	$installer->add_query('INSERT', $this_prefix.'config', "3000, 'index_pvplist', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3010, 'index_hslist', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3015, 'hspvp_list_disp', 'show', 'radio{show^show|hide^hide', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3020, 'index_member_tooltip', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3030, 'index_update_inst', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3040, 'index_sort', '', 'select{Default Sort^|Name^name|Class^class|Level^level|Guild Title^guild_title|PvP Rank^RankName|Note^note|Hearthstone Location^hearth|Zone Location^zone', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3050, 'index_motd', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3060, 'index_level_bar', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3070, 'index_iconsize', '16', 'select{8px^8|9px^9|10px^10|11px^11|12px^12|13px^13|14px^14|15px^15|16px^16|17px^17|18px^18|19px^19|20px^20', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3080, 'index_tradeskill_icon', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3090, 'index_tradeskill_loc', 'professions', 'select{Name^name|Class^class|Level^level|Guild Title^guild_title|PvP Rank^RankName|Note^note|Professions^professions|Hearthed^hearth|Last Zone^zone|Last On-line^lastonline|Last Updated^last_update', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3100, 'index_class_color', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3110, 'index_classicon', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3120, 'index_honoricon', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3130, 'index_prof', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3140, 'index_currenthonor', '0', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3150, 'index_note', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3160, 'index_title', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3170, 'index_hearthed', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3180, 'index_zone', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3190, 'index_lastonline', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3200, 'index_lastupdate', '1', 'radio{on^1|off^0', 'index_conf'");

	# --------------------------------------------------------
	### Roster Menu Settings

	$installer->add_query('INSERT', $this_prefix.'config', "4000, 'menu_left_pane', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4010, 'menu_right_pane', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4020, 'menu_memberlog', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4040, 'menu_guild_info', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4050, 'menu_stats_page', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4055, 'menu_pvp_page', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4060, 'menu_honor_page', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4070, 'menu_guildbank', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4080, 'menu_keys_page', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4090, 'menu_tradeskills_page', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4100, 'menu_update_page', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4110, 'menu_quests_page', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4120, 'menu_search_page', '1', 'radio{on^1|off^0', 'menu_conf'");

	# --------------------------------------------------------
	### Display Settings

	$installer->add_query('INSERT', $this_prefix.'config', "5000, 'stylesheet', 'css/styles.css', 'text{128|30', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5005, 'roster_js', 'css/js/mainjs.js', 'text{128|30', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5008, 'tabcontent', 'css/js/tabcontent.js', 'text{128|30', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5010, 'overlib', 'css/js/overlib.js', 'text{128|30', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5015, 'overlib_hide', 'css/js/overlib_hideform.js', 'text{128|30', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5020, 'logo', 'modules/$this_base/img/wowroster_logo.jpg', 'text{128|30', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5025, 'roster_bg', 'modules/$this_base/img/wowroster_bg.jpg', 'text{128|30', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5030, 'motd_display_mode', '1', 'radio{Image^1|Text^0', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5035, 'compress_note', '1', 'radio{Icon^1|Text^0', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5040, 'signaturebackground', 'modules/$this_base/img/default.png', 'text{128|30', 'display_conf'");
	//NOT USED!! $installer->add_query('INSERT', $this_prefix.'config', "5050, 'processtime', '1', 'radio{on^1|off^0', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "10030, 'item_stats', '1', 'radio{off^1|on^0', 'display_conf'");

	# --------------------------------------------------------
	### Links Settings

	$installer->add_query('INSERT', $this_prefix.'config', "6000, 'questlink_1', '1', 'radio{on^1|off^0', 'data_links'");
	$installer->add_query('INSERT', $this_prefix.'config', "6010, 'questlink_2', '1', 'radio{on^1|off^0', 'data_links'");
	$installer->add_query('INSERT', $this_prefix.'config', "6020, 'questlink_3', '1', 'radio{on^1|off^0', 'data_links'");
	$installer->add_query('INSERT', $this_prefix.'config', "6100, 'profiler', 'http://www.rpgoutfitter.com/Addons/CharacterProfiler.cfm', 'text{128|30', 'data_links'");
	$installer->add_query('INSERT', $this_prefix.'config', "6110, 'pvplogger', 'http://www.wowroster.net/Downloads/details/id=51.html', 'text{128|30', 'data_links'");
	$installer->add_query('INSERT', $this_prefix.'config', "6120, 'uploadapp', 'http://www.wowroster.net/Downloads/c=2.html', 'text{128|30', 'data_links'");

	# --------------------------------------------------------
	### Character Page Settings

	$installer->add_query('INSERT', $this_prefix.'config', "7000, 'char_bodyalign', 'center', 'select{left^left|center^center|right^right', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7015, 'show_talents', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7020, 'show_spellbook', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7030, 'show_mail', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7040, 'show_inventory', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7050, 'show_money', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7060, 'show_bank', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7070, 'show_recipes', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7080, 'show_quests', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7090, 'show_bg', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7100, 'show_pvp', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7110, 'show_duels', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7120, 'show_item_bonuses', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7130, 'show_signature', '0', 'radio{yes^1|no^0', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7140, 'show_avatar', '0', 'radio{yes^1|no^0', 'char_conf'");

	# --------------------------------------------------------
	### Realmstatus Settings

	$installer->add_query('INSERT', $this_prefix.'config', "8000, 'realmstatus_url', 'http://www.worldofwarcraft.com/realmstatus/status.xml', 'select{US Servers^http://www.worldofwarcraft.com/realmstatus/status.xml|European English^http://www.wow-europe.com/en/serverstatus/index.html|European German^http://www.wow-europe.com/de/serverstatus/index.html|European French^http://www.wow-europe.com/fr/serverstatus/index.html|European Spanish^http://www.wow-europe.com/es/serverstatus/index.html', 'realmstatus_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "8010, 'rs_display', 'full', 'select{full^full|half^half', 'realmstatus_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "8020, 'rs_mode', '1', 'radio{Image^1|DIV Container^0', 'realmstatus_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "8030, 'realmstatus', '', 'text{50|30', 'realmstatus_conf'");

	# --------------------------------------------------------
	### Guildbank Settings

	$installer->add_query('INSERT', $this_prefix.'config', "9000, 'guildbank_ver', '', 'select{Table^|Inventory^2', 'guildbank_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "9010, 'bank_money', '1', 'radio{yes^1|no^0', 'guildbank_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "9020, 'banker_rankname', 'BankMule', 'text{50|30', 'guildbank_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "9030, 'banker_fieldname', 'note', 'select{Player Note^note|Officer Note^officer_note|Guild Rank Number^guild_rank|Guild Title^guild_title|Player Name^name', 'guildbank_conf'");

	# --------------------------------------------------------
	### Update Access

	$installer->add_query('INSERT', $this_prefix.'config', "10000, 'authenticated_user', '1', 'radio{enable^1|disable^0', 'update_access'");


	return true;
}

# module uninstaller
function RosterDF_uninstall($this_tables)
{
	global $installer;
	foreach ($this_tables as $table)
	{
		$installer->add_query('DROP', $table);
	}
	return true;
}

# module upgrader
function RosterDF_upgrade($prev_version, $this_prefix, $this_base)
{
	global $installer, $db, $prefix;

	if( version_compare(CPG_NUKE, '9.1.1', '<') )
	{
		cpg_error('WoWRosterDF requires DragonFly version 9.1.1.0');
	}

	if( version_compare( $prev_version, '1.7.2.0', '<' ) )
	{
		# --------------------------------------------------------
		### Fix interface icons in database

		$installer->add_query('UPDATE', $this_prefix.'items', "`item_texture` = REPLACE(`item_texture`,'\\\\','/')");
		$installer->add_query('UPDATE', $this_prefix.'mailbox', "`mailbox_coin_icon` = REPLACE(`mailbox_coin_icon`,'\\\\','/')");
		$installer->add_query('UPDATE', $this_prefix.'mailbox', "`item_icon` = REPLACE(`item_icon`,'\\\\','/')");
		$installer->add_query('UPDATE', $this_prefix.'pets', "`icon` = REPLACE(`icon`,'\\\\','/')");
		$installer->add_query('UPDATE', $this_prefix.'recipes', "`recipe_texture` = REPLACE(`recipe_texture`,'\\\\','/')");
		$installer->add_query('UPDATE', $this_prefix.'spellbook', "`spell_texture` = REPLACE(`spell_texture`,'\\\\','/')");
		$installer->add_query('UPDATE', $this_prefix.'spellbooktree', "`spell_texture` = REPLACE(`spell_texture`,'\\\\','/')");
		$installer->add_query('UPDATE', $this_prefix.'talents', "`texture` = REPLACE(`texture`,'\\\\','/')");
		$installer->add_query('UPDATE', $this_prefix.'talenttree', "`background` = REPLACE(`background`,'\\\\','/')");


		# --------------------------------------------------------
		### Config

		$installer->add_query('UPDATE', $this_prefix.'config', "config_value = '2.0.0' WHERE id = '1010' LIMIT 1");
		$installer->add_query('UPDATE', $this_prefix.'config', "config_value = '2.0.0' WHERE id = '1020' LIMIT 1");
		$installer->add_query('UPDATE', $this_prefix.'config', "config_value = '2.3.1' WHERE id = '1030' LIMIT 1");
		$installer->add_query('UPDATE', $this_prefix.'config', "config_value = '0' WHERE id = '3140' LIMIT 1");

		$installer->add_query('UPDATE', $this_prefix.'config', "config_value = 'select{US Servers^http://www.worldofwarcraft.com/realmstatus/status.xml|European English^http://www.wow-europe.com/en/serverstatus/index.html|European German^http://www.wow-europe.com/de/serverstatus/index.html|European French^http://www.wow-europe.com/fr/serverstatus/index.html|European Spanish^http://www.wow-europe.com/es/serverstatus/index.html' WHERE id = '8000' LIMIT 1");

		$installer->add_query('INSERT', $this_prefix.'config', "'1001', 'debug_mode', '1', 'radio{on^1|off^0', 'main_conf'");

		$installer->add_query('UPDATE', $this_prefix.'config', "config_name = 'menu_memberlog' WHERE id = '4020' LIMIT 1");
		$installer->add_query('UPDATE', $this_prefix.'config', "config_value = 'http://www.wowroster.net/Downloads/details/id=51.html' WHERE id = '6110' LIMIT 1");
		$installer->add_query('UPDATE', $this_prefix.'config', "config_value = 'http://www.wowroster.net/Downloads/c=2.html' WHERE id = '6120' LIMIT 1");
		$installer->add_query('UPDATE', $this_prefix.'config', "id = '5020', config_type = 'display_conf' WHERE id = '1050' LIMIT 1");
		$installer->add_query('UPDATE', $this_prefix.'config', "config_value = '1', form_type = 'radio{enable^1|disable^0' WHERE id = '10000' LIMIT 1");
		$installer->add_query('UPDATE', $this_prefix.'config', "form_type = 'select{US Servers^http://www.worldofwarcraft.com/realmstatus/status.xml|European English^http://www.wow-europe.com/en/serverstatus/index.html|European German^http://www.wow-europe.com/de/serverstatus/index.html|European French^http://www.wow-europe.com/fr/serverstatus/index.html' WHERE id = '8000' LIMIT 1");

		$installer->add_query('DELETE', $this_prefix.'config', "id = '10010' LIMIT 1");
		$installer->add_query('DELETE', $this_prefix.'config', "id = '10020' LIMIT 1");
		$installer->add_query('DELETE', $this_prefix.'config', "id = '10030' LIMIT 1");
		$installer->add_query('DELETE', $this_prefix.'config', "id =  '4030' LIMIT 1");
		$installer->add_query('DELETE', $this_prefix.'config', "id =  '7010' LIMIT 1");

		$installer->add_query('INSERT', $this_prefix.'config', "1095, 'alt_img_suffix', 'gif', 'select{jpg^jpg|png^png|gif^gif', 'main_conf'");
		$installer->add_query('INSERT', $this_prefix.'config', "5008, 'tabcontent', 'css/js/tabcontent.js', 'text{128|30', 'display_conf'");
		$installer->add_query('INSERT', $this_prefix.'config', "5025, 'roster_bg', 'modules/$this_base/img/wowroster_bg.jpg', 'text{128|30', 'display_conf'");
		$installer->add_query('INSERT', $this_prefix.'config', "5035, 'compress_note', '1', 'radio{Icon^1|Text^0', 'display_conf'");

		$installer->add_query('INSERT', $this_prefix.'config', "10030, 'item_stats', '1', 'radio{off^1|on^0', 'display_conf'");

		$installer->add_query('UPDATE', $this_prefix.'config', "config_value = 'modules/$this_base/img/default.png' WHERE id = '5040' LIMIT 1");


		# --------------------------------------------------------
		### Memberlog

		$installer->add_query('CREATE', $this_prefix.'memberlog', "
			log_id int(11) unsigned NOT NULL auto_increment,
			member_id int(11) unsigned NOT NULL,
			name varchar(64) NOT NULL default '',
			guild_id int(11) unsigned NOT NULL default '0',
			class varchar(32) NOT NULL default '',
			level int(11) NOT NULL default '0',
			note varchar(255) NOT NULL default '',
			guild_rank int(11) default '0',
			guild_title varchar(64) default NULL,
			officer_note varchar(255) NOT NULL default '',
			update_time datetime default NULL,
			type tinyint(1) NOT NULL default '0',
			PRIMARY KEY  (log_id)", $this_prefix.'memberlog');


		# --------------------------------------------------------
		### Realmstatus

		$installer->add_query('DROP', $this_prefix.'realmstatus');
		$installer->add_query('CREATE', $this_prefix.'realmstatus', "
			server_name varchar(20) NOT NULL default '',
			servertype varchar(20) NOT NULL default '',
			servertypecolor varchar(8) NOT NULL default '',
			serverstatus varchar(20) NOT NULL default '',
			serverpop varchar(20) NOT NULL default '',
			serverpopcolor varchar(8) NOT NULL default '',
			timestamp tinyint(2) NOT NULL default '0',
			UNIQUE KEY server_name (server_name)", $this_prefix.'realmstatus');


		# --------------------------------------------------------
		### Buffs

		$installer->add_query('CREATE', $this_prefix.'buffs', "
			member_id int(11) unsigned NOT NULL default '0',
			name varchar(96) NOT NULL default '',
			rank varchar(32) NOT NULL default '',
			count int(11) unsigned NOT NULL default '0',
			icon varchar(64) NOT NULL default '',
			tooltip mediumtext NOT NULL,
			PRIMARY KEY (member_id,name)", $this_prefix.'buffs');


		# --------------------------------------------------------
		### Spell trees

		$db->sql_query('ALTER TABLE `'.$prefix.'_'.$this_prefix."spellbooktree` ADD PRIMARY KEY (`member_id`,`spell_type`)");


		# --------------------------------------------------------
		### Spellbook

		$db->sql_query('ALTER TABLE `'.$prefix.'_'.$this_prefix."spellbook` ADD PRIMARY KEY (`member_id`,`spell_name`,`spell_rank`)");


		# --------------------------------------------------------
		### Talent trees

		$db->sql_query('ALTER TABLE `'.$prefix.'_'.$this_prefix."talenttree` ADD PRIMARY KEY (`member_id`,`tree`)");


		# --------------------------------------------------------
		### Talents

		$db->sql_query('ALTER TABLE `'.$prefix.'_'.$this_prefix."talents` ADD PRIMARY KEY (`member_id`,`tree`,`row`,`column`)");


		# --------------------------------------------------------
		### Items Table

		$installer->add_query('ADD', $this_prefix.'items', "level INT(11) NOT NULL default 0");
		$installer->add_query('ADD', $this_prefix.'items', "item_level INT(11) NOT NULL default 0");

		$installer->add_query('CHANGE', $this_prefix.'items', "item_id item_id VARCHAR(64) NOT NULL default ''");

		# --------------------------------------------------------
		### Mailbox

		$installer->add_query('ADD', $this_prefix.'mailbox', "item_color VARCHAR(16) NOT NULL default ''");

		# --------------------------------------------------------
		### Players Table

		$installer->add_query('DEL', $this_prefix.'players', "sessionDK");
		$installer->add_query('DEL', $this_prefix.'players', "yesterdayDK");
		$installer->add_query('DEL', $this_prefix.'players', "lastweekDK");
		$installer->add_query('DEL', $this_prefix.'players', "lifetimeDK");
		$installer->add_query('DEL', $this_prefix.'players', "lastweekHK");
		$installer->add_query('DEL', $this_prefix.'players', "lastweekContribution");
		$installer->add_query('DEL', $this_prefix.'players', "lastweekRank");
		$installer->add_query('DEL', $this_prefix.'players', "TWContribution");
		$installer->add_query('DEL', $this_prefix.'players', "TWHK");
		$installer->add_query('DEL', $this_prefix.'players', "Rankexp");
		$installer->add_query('DEL', $this_prefix.'players', "RankInfo");
		$installer->add_query('DEL', $this_prefix.'players', "RankName");
		$installer->add_query('DEL', $this_prefix.'players', "RankIcon");

		$installer->add_query('ADD', $this_prefix.'players', "sessionCP INT(11) NOT NULL DEFAULT '0' AFTER sessionHK");
		$installer->add_query('ADD', $this_prefix.'players', "honorpoints INT(11) NOT NULL DEFAULT '0' AFTER lifetimeRankName");
		$installer->add_query('ADD', $this_prefix.'players', "arenapoints INT(11) NOT NULL DEFAULT '0' AFTER honorpoints");

		$installer->add_query('CHANGE', $this_prefix.'players', "melee_range melee_range VARCHAR(32) NOT NULL DEFAULT ''");
		$installer->add_query('CHANGE', $this_prefix.'players', "ranged_range ranged_range VARCHAR(32) NOT NULL DEFAULT ''");
		$installer->add_query('CHANGE', $this_prefix.'players', "melee_range melee_range VARCHAR(32) NOT NULL DEFAULT ''");
		$installer->add_query('CHANGE', $this_prefix.'players', "ranged_range ranged_range VARCHAR(32) NOT NULL DEFAULT ''");

		# --------------------------------------------------------
		### Recipe Table

		$installer->add_query('ADD', $this_prefix.'recipes', "item_level INT(11) NOT NULL DEFAULT '0'");

		# --------------------------------------------------------
		### Reputation Table

		$installer->add_query('CHANGE', $this_prefix.'reputation', "Standing Standing VARCHAR(32) NOT NULL DEFAULT ''");

		//$installer->add_query('ADD', $this_prefix.'reputation', "curr_rep int(8) NOT NULL DEFAULT '0' AFTER Value");
		//$installer->add_query('ADD', $this_prefix.'reputation', "max_rep int(8) NOT NULL DEFAULT '0' AFTER curr_rep");

		// THESE HAVE TO BE DONE THIS WAY TO BYPASS DF'S SCREWY SQL EDITING CRAP
		$db->sql_query('ALTER TABLE '.$prefix.'_'.$this_prefix."reputation ADD curr_rep int(8) NOT NULL DEFAULT '0' AFTER Value");
		$db->sql_query('ALTER TABLE '.$prefix.'_'.$this_prefix."reputation ADD max_rep int(8) NOT NULL DEFAULT '0' AFTER curr_rep");
		$db->sql_query('UPDATE '.$prefix.'_'.$this_prefix."reputation SET curr_rep = substring(Value, 1, locate('/', Value)-1) + 0, max_rep = substring(Value, locate('/', Value)+1, length(Value)-locate('/', Value)) + 0");
		$db->sql_query('ALTER TABLE '.$prefix.'_'.$this_prefix."reputation DROP Value");

		//$installer->add_query('UPDATE', $this_prefix.'reputation', "curr_rep = substring(Value, 1, locate('/', Value)-1) + 0, max_rep =  substring(Value, locate('/', Value)+1, length(Value)-locate('/', Value)) + 0");
		//$installer->add_query('DEL', $this_prefix.'reputation', "Value");

		# --------------------------------------------------------
		### PvP2 Table

		$installer->add_query('ADD', $this_prefix.'pvp2', "realm VARCHAR(96) NOT NULL DEFAULT '' AFTER guild");

		# --------------------------------------------------------
		### The roster version and db version MUST be last

		$installer->add_query('UPDATE', $this_prefix.'config', "config_value = '1.7.3.0' WHERE id = '4' LIMIT 1");
		$installer->add_query('UPDATE', $this_prefix.'config', "config_value = '5' WHERE id = '3' LIMIT 1");
	}

	return true;
}
