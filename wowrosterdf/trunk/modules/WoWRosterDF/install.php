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

	//Account
	$installer->add_query('CREATE', $this_prefix.'account', "
		`account_id` smallint(6) NOT NULL auto_increment,
		`name` varchar(30) NOT NULL default '',
		`hash` varchar(32) NOT NULL default '',
		PRIMARY KEY  (`account_id`)", $this_prefix.'account');

	//Config
	$installer->add_query('CREATE', $this_prefix.'config', "
		`id` int(11) NOT NULL,
		`config_name` varchar(255) default NULL,
		`config_value` tinytext,
		`form_type` mediumtext,
		`config_type` varchar(255) default NULL,
		PRIMARY KEY  (`id`)", $this_prefix.'config');

	//Guild
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

	//Items
	$installer->add_query('CREATE', $this_prefix.'items', "
		`member_id` int(11) unsigned NOT NULL default '0',
		`item_name` varchar(96) NOT NULL,
		`item_parent` varchar(64) NOT NULL default '',
		`item_slot` varchar(32) NOT NULL default '',
		`item_color` varchar(16) NOT NULL default '',
		`item_id` varchar(32) default NULL,
		`item_texture` varchar(64) NOT NULL default '',
		`item_quantity` int(11) default NULL,
		`item_tooltip` mediumtext NOT NULL,
		PRIMARY KEY  (`member_id`,`item_parent`,`item_slot`),
		KEY `parent` (`item_parent`),
		KEY `slot` (`item_slot`),
		KEY `name` (`item_name`)", $this_prefix.'items');

	//Mailbox
	$installer->add_query('CREATE', $this_prefix.'mailbox', "
		`member_id` int(11) unsigned NOT NULL default '0',
		`mailbox_slot` int(11) NOT NULL default '0',
		`mailbox_coin` int(11) NOT NULL default '0',
		`mailbox_coin_icon` varchar(64) NOT NULL default '',
		`mailbox_days` float NOT NULL default '0',
		`mailbox_sender` varchar(30) NOT NULL default '',
		`mailbox_subject` mediumtext NOT NULL,
		`item_icon` varchar(64) NOT NULL default '',
		`item_name` varchar(96) NOT NULL,
		`item_quantity` int(11) default NULL,
		`item_tooltip` mediumtext NOT NULL,
		PRIMARY KEY  (`member_id`,`mailbox_slot`)", $this_prefix.'mailbox');

	//Members
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

	//Pets
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

	//Players
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
		`melee_range` varchar(16) default NULL,
		`melee_range_tooltip` tinytext,
		`melee_power_tooltip` tinytext,
		`ranged_power` int(11) default NULL,
		`ranged_rating` int(11) default NULL,
		`ranged_range` varchar(16) default NULL,
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
		`sessionDK` int(11) NOT NULL default '0',
		`yesterdayHK` int(11) NOT NULL default '0',
		`yesterdayDK` int(11) NOT NULL default '0',
		`yesterdayContribution` int(11) NOT NULL default '0',
		`lastweekHK` int(11) NOT NULL default '0',
		`lastweekDK` int(11) NOT NULL default '0',
		`lastweekContribution` int(11) NOT NULL default '0',
		`lastweekRank` int(11) NOT NULL default '0',
		`lifetimeHK` int(11) NOT NULL default '0',
		`lifetimeDK` int(11) NOT NULL default '0',
		`lifetimeRankName` varchar(64) NOT NULL default '0',
		`Rankexp` int(11) NOT NULL default '0',
		`TWContribution` int(11) NOT NULL default '0',
		`TWHK` int(11) NOT NULL default '0',
		`dodge` float NOT NULL default '0',
		`parry` float NOT NULL default '0',
		`block` float NOT NULL default '0',
		`mitigation` float NOT NULL default '0',
		`crit` float NOT NULL default '0',
		`lifetimeHighestRank` int(11) NOT NULL default '0',
		`RankInfo` int(11) NOT NULL default '0',
		`RankName` varchar(64) NOT NULL default '',
		`RankIcon` varchar(64) NOT NULL default '',
		`clientLocale` varchar(4) NOT NULL default '',
		`timeplayed` int(11) NOT NULL default '0',
		`timelevelplayed` int(11) NOT NULL default '0',
		PRIMARY KEY  (`member_id`),
		KEY `name` (`name`,`server`)", $this_prefix.'players');

	//PvP2
	$installer->add_query('CREATE', $this_prefix.'pvp2', "
		`member_id` int(11) unsigned NOT NULL default '0',
		`index` int(11) unsigned NOT NULL default '0',
		`date` datetime default NULL,
		`name` varchar(32) NOT NULL default '',
		`guild` varchar(32) NOT NULL default '',
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

	//Quests
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

	//Realmstatus
	$installer->add_query('CREATE', $this_prefix.'realmstatus', "
		`server_name` varchar(20) NOT NULL default '',
		`servertype` varchar(20) NOT NULL default '',
		`serverstatus` varchar(20) NOT NULL default '',
		`serverpop` varchar(20) NOT NULL default '',
		`timestamp` tinyint(2) NOT NULL default '0',
		UNIQUE KEY `server_name` (`server_name`)", $this_prefix.'realmstatus');

	//Recipes
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
		PRIMARY KEY  (`member_id`,`skill_name`,`recipe_name`,`categories`),
		KEY `skill_nameI` (`skill_name`),
		KEY `recipe_nameI` (`recipe_name`),
		KEY `categoriesI` (`categories`),
		KEY `levelI` (`level`)", $this_prefix.'recipes');

	//Reputation
	$installer->add_query('CREATE', $this_prefix.'reputation', "
		`member_id` int(10) unsigned NOT NULL default '0',
		`faction` varchar(32) NOT NULL default '',
		`name` varchar(32) NOT NULL default '',
		`Value` varchar(32) default '0/0',
		`AtWar` int(11) NOT NULL default '0',
		`Standing` varchar(32) default '0/0',
		PRIMARY KEY  (`member_id`,`name`)", $this_prefix.'reputation');

	//Skills
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

	//Spellbook
	$installer->add_query('CREATE', $this_prefix.'spellbook', "
		`member_id` int(11) unsigned NOT NULL default '0',
		`spell_name` varchar(64) NOT NULL default '',
		`spell_type` varchar(100) NOT NULL default '',
		`spell_texture` varchar(64) NOT NULL default '',
		`spell_rank` varchar(64) NOT NULL default '',
		`spell_tooltip` mediumtext NOT NULL", $this_prefix.'spellbook');

	//Spellbook Tree
	$installer->add_query('CREATE', $this_prefix.'spellbooktree', "
		`member_id` int(11) unsigned NOT NULL default '0',
		`spell_type` varchar(64) NOT NULL default '',
		`spell_texture` varchar(64) NOT NULL default ''", $this_prefix.'spellbooktree');

	//Talents
	$installer->add_query('CREATE', $this_prefix.'talents', "
		`member_id` int(11) NOT NULL default '0',
		`name` varchar(64) NOT NULL,
		`tree` varchar(64) NOT NULL,
		`row` tinyint(4) NOT NULL default '0',
		`column` tinyint(4) NOT NULL default '0',
		`rank` tinyint(4) NOT NULL default '0',
		`maxrank` tinyint(4) NOT NULL default '0',
		`tooltip` mediumtext NOT NULL,
		`texture` varchar(64) NOT NULL default ''", $this_prefix.'talents');

	//Talent Tree
	$installer->add_query('CREATE', $this_prefix.'talenttree', "
		`member_id` int(11) NOT NULL default '0',
		`tree` varchar(64) NOT NULL,
		`background` varchar(64) NOT NULL default '',
		`order` tinyint(4) NOT NULL default '0',
		`pointsspent` tinyint(4) NOT NULL default '0'", $this_prefix.'talenttree');



	//Pre-Installed Roster Addons

	//SigGen
	/*$installer->add_query('CREATE', $this_prefix.'addon_siggen', "
		`config_id` varchar(20) NOT NULL default '',
		`db_ver` varchar(6) NOT NULL default '',
		`trigger` tinyint(1) NOT NULL default '0',
		`guild_trigger` tinyint(1) NOT NULL default '0',
		`main_image_size_w` smallint(6) NOT NULL default '0',
		`main_image_size_h` smallint(6) NOT NULL default '0',
		`image_dir` varchar(20) NOT NULL default '',
		`char_dir` varchar(20) NOT NULL default '',
		`class_dir` varchar(20) NOT NULL default '',
		`backg_dir` varchar(20) NOT NULL default '',
		`user_dir` varchar(20) NOT NULL default '',
		`pvplogo_dir` varchar(20) NOT NULL default '',
		`font_dir` varchar(20) NOT NULL default '',
		`image_order` varchar(128) NOT NULL default '',
		`save_images` tinyint(1) NOT NULL default '0',
		`save_only_mode` tinyint(1) NOT NULL default '0',
		`save_prefix` varchar(8) NOT NULL default '',
		`save_suffix` varchar(8) NOT NULL default '',
		`save_images_dir` varchar(20) NOT NULL default '',
		`save_images_format` char(3) NOT NULL default '',
		`etag_cache` tinyint(1) NOT NULL default '0',
		`image_type` char(3) NOT NULL default '',
		`image_quality` tinyint(3) NOT NULL default '0',
		`gif_dither` tinyint(1) NOT NULL default '0',
		`backg_disp` tinyint(1) NOT NULL default '0',
		`backg_fill` tinyint(1) NOT NULL default '0',
		`backg_fill_color` varchar(15) NOT NULL default '',
		`backg_default_image` varchar(20) NOT NULL default '',
		`backg_force_default` tinyint(1) NOT NULL default '0',
		`backg_data_table` varchar(20) NOT NULL default '',
		`backg_data` varchar(20) NOT NULL default '',
		`backg_translate` tinyint(1) NOT NULL default '0',
		`backg_search_1` varchar(25) NOT NULL default '',
		`backg_search_2` varchar(25) NOT NULL default '',
		`backg_search_3` varchar(25) NOT NULL default '',
		`backg_search_4` varchar(25) NOT NULL default '',
		`backg_search_5` varchar(25) NOT NULL default '',
		`backg_search_6` varchar(25) NOT NULL default '',
		`backg_search_7` varchar(25) NOT NULL default '',
		`backg_search_8` varchar(25) NOT NULL default '',
		`backg_search_9` varchar(25) NOT NULL default '',
		`backg_search_10` varchar(25) NOT NULL default '',
		`backg_search_11` varchar(25) NOT NULL default '',
		`backg_search_12` varchar(25) NOT NULL default '',
		`backg_file_1` varchar(25) NOT NULL default '',
		`backg_file_2` varchar(25) NOT NULL default '',
		`backg_file_3` varchar(25) NOT NULL default '',
		`backg_file_4` varchar(25) NOT NULL default '',
		`backg_file_5` varchar(25) NOT NULL default '',
		`backg_file_6` varchar(25) NOT NULL default '',
		`backg_file_7` varchar(25) NOT NULL default '',
		`backg_file_8` varchar(25) NOT NULL default '',
		`backg_file_9` varchar(25) NOT NULL default '',
		`backg_file_10` varchar(25) NOT NULL default '',
		`backg_file_11` varchar(25) NOT NULL default '',
		`backg_file_12` varchar(25) NOT NULL default '',
		`font_fullpath` tinyint(1) NOT NULL default '0',
		`font1` varchar(25) NOT NULL default '',
		`font2` varchar(25) NOT NULL default '',
		`font3` varchar(25) NOT NULL default '',
		`font4` varchar(25) NOT NULL default '',
		`font5` varchar(25) NOT NULL default '',
		`font6` varchar(25) NOT NULL default '',
		`font7` varchar(25) NOT NULL default '',
		`font8` varchar(25) NOT NULL default '',
		`color1` varchar(7) NOT NULL default '',
		`color2` varchar(7) NOT NULL default '',
		`color3` varchar(7) NOT NULL default '',
		`color4` varchar(7) NOT NULL default '',
		`color5` varchar(7) NOT NULL default '',
		`color6` varchar(7) NOT NULL default '',
		`color7` varchar(7) NOT NULL default '',
		`color8` varchar(7) NOT NULL default '',
		`color9` varchar(7) NOT NULL default '',
		`color10` varchar(7) NOT NULL default '',
		`outside_border_image` varchar(20) NOT NULL default '',
		`frames_image` varchar(20) NOT NULL default '',
		`charlogo_disp` tinyint(1) NOT NULL default '0',
		`charlogo_default_image` varchar(20) NOT NULL default '',
		`charlogo_loc_x` smallint(6) NOT NULL default '0',
		`charlogo_loc_y` smallint(6) NOT NULL default '0',
		`class_img_disp` tinyint(1) NOT NULL default '0',
		`class_img_loc_x` smallint(6) NOT NULL default '0',
		`class_img_loc_y` smallint(6) NOT NULL default '0',
		`pvplogo_disp` tinyint(1) NOT NULL default '0',
		`pvplogo_loc_x` smallint(6) NOT NULL default '0',
		`pvplogo_loc_y` smallint(6) NOT NULL default '0',
		`lvl_disp` tinyint(1) NOT NULL default '0',
		`lvl_font_name` varchar(6) NOT NULL default '',
		`lvl_font_color` varchar(15) NOT NULL default '',
		`lvl_font_size` smallint(6) NOT NULL default '0',
		`lvl_text_shadow` varchar(15) NOT NULL default '',
		`lvl_loc_x` smallint(6) NOT NULL default '0',
		`lvl_loc_y` smallint(6) NOT NULL default '0',
		`lvl_text_loc_x` smallint(6) NOT NULL default '0',
		`lvl_text_loc_y` smallint(6) NOT NULL default '0',
		`lvl_image` varchar(20) NOT NULL default '',
		`expbar_disp` tinyint(1) NOT NULL default '0',
		`expbar_disp_bdr` tinyint(1) NOT NULL default '0',
		`expbar_disp_inside` tinyint(1) NOT NULL default '0',
		`expbar_max_disp` tinyint(1) NOT NULL default '0',
		`expbar_max_level` smallint(6) NOT NULL default '0',
		`expbar_max_hidden` tinyint(1) NOT NULL default '0',
		`expbar_disp_text` tinyint(1) NOT NULL default '0',
		`expbar_string_before` varchar(30) NOT NULL default '',
		`expbar_string_after` varchar(30) NOT NULL default '',
		`expbar_max_string` varchar(30) NOT NULL default '',
		`expbar_loc_x` smallint(6) NOT NULL default '0',
		`expbar_loc_y` smallint(6) NOT NULL default '0',
		`expbar_length` smallint(6) NOT NULL default '0',
		`expbar_height` smallint(6) NOT NULL default '0',
		`expbar_color_border` varchar(15) NOT NULL default '',
		`expbar_color_bar` varchar(15) NOT NULL default '',
		`expbar_color_inside` varchar(15) NOT NULL default '',
		`expbar_color_maxbar` varchar(15) NOT NULL default '',
		`expbar_trans_border` smallint(6) NOT NULL default '0',
		`expbar_trans_bar` smallint(6) NOT NULL default '0',
		`expbar_trans_inside` smallint(6) NOT NULL default '0',
		`expbar_trans_maxbar` smallint(6) NOT NULL default '0',
		`expbar_font_name` varchar(6) NOT NULL default '',
		`expbar_font_color` varchar(15) NOT NULL default '',
		`expbar_font_size` smallint(6) NOT NULL default '0',
		`expbar_text_shadow` varchar(15) NOT NULL default '',
		`expbar_align` varchar(6) NOT NULL default '',
		`expbar_align_max` varchar(6) NOT NULL default '',
		`skills_disp_primary` tinyint(1) NOT NULL default '0',
		`skills_disp_secondary` tinyint(1) NOT NULL default '0',
		`skills_disp_mount` tinyint(1) NOT NULL default '0',
		`skills_disp_desc` tinyint(1) NOT NULL default '0',
		`skills_disp_level` tinyint(1) NOT NULL default '0',
		`skills_disp_levelmax` tinyint(1) NOT NULL default '0',
		`skills_desc_loc_x` smallint(6) NOT NULL default '0',
		`skills_desc_loc_y` smallint(6) NOT NULL default '0',
		`skills_level_loc_x` smallint(6) NOT NULL default '0',
		`skills_level_loc_y` smallint(6) NOT NULL default '0',
		`skills_desc_length` smallint(6) NOT NULL default '0',
		`skills_desc_length_mount` smallint(6) NOT NULL default '0',
		`skills_align_desc` varchar(6) NOT NULL default '',
		`skills_align_level` varchar(6) NOT NULL default '',
		`skills_desc_linespace` smallint(6) NOT NULL default '0',
		`skills_level_linespace` smallint(6) NOT NULL default '0',
		`skills_gap` tinyint(3) NOT NULL default '0',
		`skills_shadow` varchar(15) NOT NULL default '',
		`skills_font_name` varchar(6) NOT NULL default '',
		`skills_font_color` varchar(15) NOT NULL default '',
		`skills_font_size` smallint(6) NOT NULL default '0',
		`text_name_disp` tinyint(1) NOT NULL default '0',
		`text_name_loc_x` smallint(6) NOT NULL default '0',
		`text_name_loc_y` smallint(6) NOT NULL default '0',
		`text_name_align` varchar(6) NOT NULL default '',
		`text_name_shadow` varchar(15) NOT NULL default '',
		`text_name_font_name` varchar(6) NOT NULL default '',
		`text_name_font_color` varchar(15) NOT NULL default '',
		`text_name_font_size` smallint(6) NOT NULL default '0',
		`text_honor_disp` tinyint(1) NOT NULL default '0',
		`text_honor_loc_x` smallint(6) NOT NULL default '0',
		`text_honor_loc_y` smallint(6) NOT NULL default '0',
		`text_honor_align` varchar(6) NOT NULL default '',
		`text_honor_shadow` varchar(15) NOT NULL default '',
		`text_honor_font_name` varchar(6) NOT NULL default '',
		`text_honor_font_color` varchar(15) NOT NULL default '',
		`text_honor_font_size` smallint(6) NOT NULL default '0',
		`text_class_disp` tinyint(1) NOT NULL default '0',
		`text_class_loc_x` smallint(6) NOT NULL default '0',
		`text_class_loc_y` smallint(6) NOT NULL default '0',
		`text_class_align` varchar(6) NOT NULL default '',
		`text_class_shadow` varchar(15) NOT NULL default '',
		`text_class_font_name` varchar(6) NOT NULL default '',
		`text_class_font_color` varchar(15) NOT NULL default '',
		`text_class_font_size` smallint(6) NOT NULL default '0',
		`text_guildname_disp` tinyint(1) NOT NULL default '0',
		`text_guildname_loc_x` smallint(6) NOT NULL default '0',
		`text_guildname_loc_y` smallint(6) NOT NULL default '0',
		`text_guildname_align` varchar(6) NOT NULL default '',
		`text_guildname_shadow` varchar(15) NOT NULL default '',
		`text_guildname_font_name` varchar(6) NOT NULL default '',
		`text_guildname_font_color` varchar(15) NOT NULL default '',
		`text_guildname_font_size` smallint(6) NOT NULL default '0',
		`text_guildtitle_disp` tinyint(1) NOT NULL default '0',
		`text_guildtitle_loc_x` smallint(6) NOT NULL default '0',
		`text_guildtitle_loc_y` smallint(6) NOT NULL default '0',
		`text_guildtitle_align` varchar(6) NOT NULL default '',
		`text_guildtitle_shadow` varchar(15) NOT NULL default '',
		`text_guildtitle_font_name` varchar(6) NOT NULL default '',
		`text_guildtitle_font_color` varchar(15) NOT NULL default '',
		`text_guildtitle_font_size` smallint(6) NOT NULL default '0',
		`text_servername_disp` tinyint(1) NOT NULL default '0',
		`text_servername_loc_x` smallint(6) NOT NULL default '0',
		`text_servername_loc_y` smallint(6) NOT NULL default '0',
		`text_servername_align` varchar(6) NOT NULL default '',
		`text_servername_shadow` varchar(15) NOT NULL default '',
		`text_servername_font_name` varchar(6) NOT NULL default '',
		`text_servername_font_color` varchar(15) NOT NULL default '',
		`text_servername_font_size` smallint(6) NOT NULL default '0',
		`text_sitename_disp` tinyint(1) NOT NULL default '0',
		`text_sitename_remove` tinyint(1) NOT NULL default '0',
		`text_sitename_loc_x` smallint(6) NOT NULL default '0',
		`text_sitename_loc_y` smallint(6) NOT NULL default '0',
		`text_sitename_align` varchar(6) NOT NULL default '',
		`text_sitename_shadow` varchar(15) NOT NULL default '',
		`text_sitename_font_name` varchar(6) NOT NULL default '',
		`text_sitename_font_color` varchar(15) NOT NULL default '',
		`text_sitename_font_size` smallint(6) NOT NULL default '0',
		`text_custom_disp` tinyint(1) NOT NULL default '0',
		`text_custom_loc_x` smallint(6) NOT NULL default '0',
		`text_custom_loc_y` smallint(6) NOT NULL default '0',
		`text_custom_text` varchar(50) NOT NULL default '',
		`text_custom_align` varchar(6) NOT NULL default '',
		`text_custom_shadow` varchar(15) NOT NULL default '',
		`text_custom_font_name` varchar(6) NOT NULL default '',
		`text_custom_font_color` varchar(15) NOT NULL default '',
		`text_custom_font_size` smallint(6) NOT NULL default '0',
		PRIMARY KEY  (`config_id`)", $this_prefix.'addon_siggen');*/

	//AltMonitor
	/*$installer->add_query('CREATE', $this_prefix.'addon_altmonitor', "
		`member_id` int(11) unsigned NOT NULL default '0',
		`main_id` int(11) unsigned NOT NULL default '0',
		`alt_type` tinyint(3) unsigned NOT NULL default '0',
		PRIMARY KEY (`member_id`)", $this_prefix.'addon_altmonitor');

	$installer->add_query('CREATE', $this_prefix.'addon_altmonitor_config', "
		`id` int(11) NOT NULL,
		`config_name` varchar(255) default NULL,
		`config_value` tinytext,
		`form_type` mediumtext,
		`config_type` varchar(255) default NULL,
		PRIMARY KEY  (`id`)", $this_prefix.'addon_altmonitor_config');*/


	//Master Values
	$installer->add_query('INSERT', $this_prefix.'config', "1, 'config_list', 'main_conf|guild_conf|menu_conf|display_conf|index_conf|char_conf|realmstatus_conf|data_links|guildbank_conf|update_access', 'display', 'master'");
	$installer->add_query('INSERT', $this_prefix.'config', "2, 'roster_upd_pw', '4cb9c8a8048fd02294477fcb1a41191a', 'password:30|30', 'master'");
	$installer->add_query('INSERT', $this_prefix.'config', "3, 'roster_dbver', '2', 'display', 'master'");
	$installer->add_query('INSERT', $this_prefix.'config', "4, 'version', '1.7.0', 'display', 'master'");

	//Main Roster Config
	$installer->add_query('INSERT', $this_prefix.'config', "1000, 'sqldebug', '1', 'radio{on^1|off^0', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1010, 'minCPver', '1.5.2', 'text{10|10', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1020, 'minGPver', '1.5.1', 'text{10|10', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1030, 'minPvPLogver', '0.5.0', 'text{10|10', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1040, 'roster_lang', 'enUS', 'function{rosterLangValue', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1050, 'logo', 'modules/$this_base/img/wowroster_logo.jpg', 'text{128|30', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1060, 'website_address', '', 'text{128|30', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1070, 'roster_dir', 'modules/$this_base', 'text{128|30', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1080, 'server_name_comp', '0', 'radio{on^1|off^0', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1085, 'interface_url', 'images/wowrosterdf/', 'text{128|30', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1090, 'img_suffix', 'jpg', 'select{jpg^jpg|png^png|gif^gif', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1100, 'img_url', 'modules/$this_base/img/', 'text{128|30', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1110, 'timezone', 'PST', 'text{10|10', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1120, 'localtimeoffset', '0', 'select{-12^-12|-11^-11|-10^-10|-9^-9|-8^-8|-7^-7|-6^-6|-5^-5|-4^-4|-3.5^-3.5|-3^-3|-2^-2|-1^-1|0^0|+1^1|+2^2|+3^3|+3.5^3.5|+4^4|+4.5^4.5|+5^5|+5.5^5.5|+6^6|+6.5^6.5|+7^7|+8^8|+9^9|+9.5^9.5|+10^10|+11^11|+12^12|+13^13', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1130, 'pvp_log_allow', '1', 'radio{yes^1|no^0', 'main_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "1140, 'use_update_triggers', '1', 'radio{on^1|off^0', 'main_conf'");

	//Guild Settings
	$installer->add_query('INSERT', $this_prefix.'config', "2000, 'guild_name', 'guildName', 'text{50|30', 'guild_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "2010, 'server_name', 'realmName', 'text{50|30', 'guild_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "2020, 'guild_desc', 'A Great WoW Guild', 'text{255|30', 'guild_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "2030, 'server_type', 'PvE', 'select{PvE^PvE|PvP^PvP|RP^RP|RPPvP^RPPvP', 'guild_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "2040, 'alt_type', 'alt', 'text{30|30', 'guild_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "2050, 'alt_location', 'note', 'select{Player Note^note|Officer Note^officer_note|Guild Rank Number^guild_rank|Guild Title^guild_title', 'guild_conf'");

	//Index Page
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
	$installer->add_query('INSERT', $this_prefix.'config', "3140, 'index_currenthonor', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3150, 'index_note', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3160, 'index_title', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3170, 'index_hearthed', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3180, 'index_zone', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3190, 'index_lastonline', '1', 'radio{on^1|off^0', 'index_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "3200, 'index_lastupdate', '1', 'radio{on^1|off^0', 'index_conf'");

	//Roster Menu Settings
	$installer->add_query('INSERT', $this_prefix.'config', "4000, 'menu_left_pane', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4010, 'menu_right_pane', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4020, 'menu_byclass', '1', 'radio{on^1|off^0', 'menu_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "4030, 'menu_alt_page', '1', 'radio{on^1|off^0', 'menu_conf'");
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

	//Display Settings
	$installer->add_query('INSERT', $this_prefix.'config', "5000, 'stylesheet', 'css/styles.css', 'text{128|30', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5005, 'roster_js', 'css/js/mainjs.js', 'text{128|30', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5010, 'overlib', 'css/js/overlib.js', 'text{128|30', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5015, 'overlib_hide', 'css/js/overlib_hideform.js', 'text{128|30', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5030, 'motd_display_mode', '1', 'radio{Image^1|Text^0', 'display_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "5040, 'signaturebackground', 'img/default.png', 'text{128|30', 'display_conf'");

	//Links Settings
	$installer->add_query('INSERT', $this_prefix.'config', "6000, 'questlink_1', '1', 'radio{on^1|off^0', 'data_links'");
	$installer->add_query('INSERT', $this_prefix.'config', "6010, 'questlink_2', '1', 'radio{on^1|off^0', 'data_links'");
	$installer->add_query('INSERT', $this_prefix.'config', "6020, 'questlink_3', '1', 'radio{on^1|off^0', 'data_links'");
	$installer->add_query('INSERT', $this_prefix.'config', "6100, 'profiler', 'http://www.rpgoutfitter.com/Addons/CharacterProfiler.cfm', 'text{128|30', 'data_links'");
	$installer->add_query('INSERT', $this_prefix.'config', "6110, 'pvplogger', 'http://www.wowroster.net/viewtopic.php?p=9266', 'text{128|30', 'data_links'");
	$installer->add_query('INSERT', $this_prefix.'config', "6120, 'uploadapp', 'http://www.wowroster.net/viewtopic.php?p=9266', 'text{128|30', 'data_links'");

	//Character Page Settings
	$installer->add_query('INSERT', $this_prefix.'config', "7000, 'char_bodyalign', 'center', 'select{left^left|center^center|right^right', 'char_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "7010, 'char_header_logo', '1', 'radio{on^1|off^0', 'char_conf'");
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

	//Realmstatus Settings
	$installer->add_query('INSERT', $this_prefix.'config', "8000, 'realmstatus_url', 'http://www.worldofwarcraft.com/realmstatus/status.xml', 'select{US Servers^http://www.worldofwarcraft.com/realmstatus/status.xml|European English^http://www.wow-europe.com/en/serverstatus/index.html|European German^http://www.wow-europe.com/de/serverstatus/index.html', 'realmstatus_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "8010, 'rs_display', 'full', 'select{full^full|half^half', 'realmstatus_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "8020, 'rs_mode', '0', 'radio{Image^1|DIV Container^0', 'realmstatus_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "8030, 'realmstatus', '', 'text{50|30', 'realmstatus_conf'");

	//Guildbank Settings
	$installer->add_query('INSERT', $this_prefix.'config', "9000, 'guildbank_ver', '', 'select{Table^|Inventory^2', 'guildbank_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "9010, 'bank_money', '1', 'radio{yes^1|no^0', 'guildbank_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "9020, 'banker_rankname', 'BankMule', 'text{50|30', 'guildbank_conf'");
	$installer->add_query('INSERT', $this_prefix.'config', "9030, 'banker_fieldname', 'note', 'select{Player Note^note|Officer Note^officer_note|Guild Rank Number^guild_rank|Guild Title^guild_title|Player Name^name', 'guildbank_conf'");

	//Update Access
	$installer->add_query('INSERT', $this_prefix.'config', "10000, 'authenticated_user', '1', 'radio{on^1|off^0', 'update_access'");
	$installer->add_query('INSERT', $this_prefix.'config', "10010, 'phpbb_root_path', '../phpbb/', 'text{128|30', 'update_access'");
	$installer->add_query('INSERT', $this_prefix.'config', "10020, 'upload_group', '3, 4, 44', 'text{128|30', 'update_access'");



	//Addons inserts

	//siggen
	/*$installer->add_query('INSERT', $this_prefix.'addon_siggen', "'avatar', '1.1', 0, 0, 100, 85, 'img/', 'char/', 'class/', 'backg/', 'members/', 'pvp/', 'fonts/', 'back:char:frames:border:class:lvl:pvp', 0, 0, 'ava-', '', 'saved/', 'jpg', 1, 'png', 85, 1, 1, 0, 'color1', 'av-default', 0, 'players', 'race', 1, 'Night Elf', 'Human', 'Gnome', 'Dwarf', 'Orc', 'Troll', 'Tauren', 'Undead', '', '', '', '', 'av-darnassus', 'av-stormwind', 'av-ironforge', 'av-ironforge', 'av-orgrimmar', 'av-orgrimmar', 'av-thunderbluff', 'av-undercity', '', '', '', '', 1, 'BASTARD1.TTF', 'COURIER.TTF', 'GREY.TTF', 'OLDENGL.TTF', 'TEMPSITC.TTF', 'VERANDA.TTF', 'VINERITC.TTF', 'silkscreen.ttf', '#000000', '#99FF33', '#808080', '#0B9BFF', '#FFFFFF', '#FF9900', '#0066FF', '#FFFF66', '#FFCC66', '#000000', 'border-av-black', '', 1, 'char-alliance', 0, 0, 1, 63, 49, 1, 3, 25, 1, 'font3', 'color6', 11, 'color1', 78, 66, 11, 15, '', 1, 0, 1, 1, 60, 1, 0, '[', ']', 'Max XP', 2, 79, 65, 3, 'color1', 'color4', 'color5', 'color4', 0, 20, 80, 0, 'font6', 'color5', 6, 'color1', 'center', 'center', 0, 0, 0, 1, 1, 0, 3, 48, 65, 48, 10, 20, 'left', 'right', 7, 7, 2, 'color1', 'font6', 'color6', 6, 1, 3, 22, 'left', 'color1', 'font3', 'color8', 14, 1, 3, 53, 'left', 'color1', 'font3', 'color2', 7, 0, 97, 35, 'right', 'color1', 'font6', 'color2', 7, 1, 3, 71, 'left', 'color1', 'font3', 'color7', 8, 1, 12, 8, 'left', 'color1', 'font6', 'color9', 6, 1, 3, 78, 'left', 'color1', 'font6', 'color2', 6, 0, 1, 50, 82, 'center', 'color1', 'font6', 'color5', 6, 0, 3, 84, 'Custom Text', 'left', 'color1', 'font6', 'color5', 6");
	$installer->add_query('INSERT', $this_prefix.'addon_siggen', "'signature', '1.1', 0, 0, 400, 85, 'img/', 'char/', 'class/', 'backg/', 'members/', 'pvp/', 'fonts/', 'back:char:frames:border:lvl:pvp:class', 0, 0, 'sig-', '', 'saved/', 'jpg', 1, 'png', 85, 1, 1, 0, 'color1', 'default', 0, 'players', 'race', 1, 'Night Elf', 'Human', 'Gnome', 'Dwarf', 'Orc', 'Troll', 'Tauren', 'Undead', '', '', '', '', 'darnassus', 'stormwind', 'ironforge', 'ironforge', 'orgrimmar', 'orgrimmar', 'thunderbluff', 'undercity', '', '', '', '', 1, 'BASTARD1.TTF', 'COURIER.TTF', 'GREY.TTF', 'OLDENGL.TTF', 'TEMPSITC.TTF', 'VERANDA.TTF', 'VINERITC.TTF', 'silkscreen.ttf', '#000000', '#99FF33', '#808080', '#0B9BFF', '#FFFFFF', '#FF9900', '#0066FF', '#FFFF66', '#FFCC66', '#000000', 'border-sig-black', 'frames-default', 1, 'char-horde', 0, 0, 1, 90, 3, 1, 98, 38, 1, 'font3', 'color9', 9, '', 2, 60, 11, 15, 'lvlbubble-yellow', 1, 0, 1, 1, 60, 1, 1, '[', ']', 'Max XP', 25, 73, 100, 7, 'color1', 'color4', 'color5', 'color4', 0, 20, 80, 0, 'font6', 'color1', 6, '', 'center', 'center', 1, 1, 1, 1, 1, 0, 285, 12, 393, 12, 12, 22, 'left', 'right', 8, 8, 2, 'color1', 'font6', 'color6', 7, 1, 204, 42, 'center', 'color1', 'font3', 'color7', 22, 1, 126, 68, 'right', 'color1', 'font3', 'color2', 8, 1, 124, 38, 'right', 'color1', 'font3', 'color2', 8, 1, 204, 67, 'center', 'color1', 'font3', 'color8', 16, 1, 128, 18, 'left', 'color1', 'font3', 'color6', 9, 1, 204, 79, 'center', 'color1', 'font3', 'color2', 9, 1, 1, 394, 79, 'right', 'color1', 'font6', 'color8', 6, 0, 5, 15, 'Custom Text Message', 'left', 'color1', 'font6', 'color5', 10");*/

	//maxres
	//$installer->add_query('ADD', $this_prefix.'players', '`max_res_all` INT NULL' );
	//$installer->add_query('ADD', $this_prefix.'players', '`max_res_fire` INT NULL' );
	//$installer->add_query('ADD', $this_prefix.'players', '`max_res_nat` INT NULL' );
	//$installer->add_query('ADD', $this_prefix.'players', '`max_res_arc` INT NULL' );
	//$installer->add_query('ADD', $this_prefix.'players', '`max_res_fro` INT NULL' );
	//$installer->add_query('ADD', $this_prefix.'players', '`max_res_shad` INT NULL' );
	//$db->sql_query("ALTER TABLE `".$prefix.'_'.$this_prefix."players` ADD `max_res_all` INT NULL, ADD `max_res_fire` INT NULL , ADD `max_res_nat` INT NULL , ADD `max_res_arc` INT NULL , ADD `max_res_fro` INT NULL , ADD `max_res_shad` INT NULL;");

	//altmonitor
	# Master data for the config file
/*	$installer->add_query('INSERT', $this_prefix.'addon_altmonitor_config', "1,'config_list','build|display','display','master'");
	$installer->add_query('INSERT', $this_prefix.'addon_altmonitor_config', "2,'version','1.1.0','display','master'");

	# Build settings$this_prefix.'addon_altmonitor_config',
	$installer->add_query('INSERT', $this_prefix.'addon_altmonitor_config', "1000,'getmain_regex','/ALT-([\\w]+)/i','text{50|30','build'");
	$installer->add_query('INSERT', $this_prefix.'addon_altmonitor_config', "1010,'getmain_field','note','select{Member name^name|Guild Title^guild_title|Public note^note|Officer note^officer_note','build'");
	$installer->add_query('INSERT', $this_prefix.'addon_altmonitor_config', "1020,'getmain_match','1','text{2|30','build'");
	$installer->add_query('INSERT', $this_prefix.'addon_altmonitor_config', "1030,'getmain_main','Main','text{20|30','build'");
	$installer->add_query('INSERT', $this_prefix.'addon_altmonitor_config', "1040,'defmain','1','radio{Main^1|Mainless Alt^0','build'");
	$installer->add_query('INSERT', $this_prefix.'addon_altmonitor_config', "1050,'invmain','0','radio{Main^1|Mainless Alt^0','build'");
	$installer->add_query('INSERT', $this_prefix.'addon_altmonitor_config', "1060,'altofalt','alt','select{Try to resolve^resolve|Leave in table^leave|Set as main^main|Set as mainless alt^alt','build'");

	# Display options
	$installer->add_query('INSERT', $this_prefix.'addon_altmonitor_config', "2000,'showmain','0','radio{Show^1|Hide^0','display'");
	$installer->add_query('INSERT', $this_prefix.'addon_altmonitor_config', "2010,'altopen','1','radio{Open^1|Closed^0','display'");
	$installer->add_query('INSERT', $this_prefix.'addon_altmonitor_config', "2020,'mainlessbottom','1','radio{Bottom^1|Top^0','display'");*/

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
	global $installer;

	if ( version_compare( $prev_version, '1.7.0', '<' ) )
	{
		// Do Nothing for now
	}

	return true;
}

?>