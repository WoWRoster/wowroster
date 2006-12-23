<?php

/**
 * Schema Schemer
 *
 * Seamlessly keep schemas up to date. Or at least, that's the plan.
 *
 * $Id$
 * @author six
 */

/**
 * This is the meat of the schemer. You simply call this function and when it
 * finishes you have a database that is up to date.
 *
 */
function syncSchema(){
	global $roster_conf;

	if(tableExists(ROSTER_METADATATABLE)){
		$r = null;
		// Great, we have at least a version 1 db.
		$res = db_query("SELECT * FROM `".ROSTER_METADATATABLE."` WHERE `key` = 'db_version'");
	    if($res->numRows()==0){
		DropTable(ROSTER_METADATATABLE);
		$db_version = 0;
	    } else {
		$res->fetchInto($r);
		$db_version = $r['value'];
	    }
	} else {
	    $db_version = 0;
	}
    if($db_version < 1){
		// Since we don't have a version 1 db, let's drop the whole mess and start from scratch.
		DropTable(ROSTER_ACCOUNTTABLE);
		DropTable(ROSTER_GUILDTABLE);
		DropTable(ROSTER_ITEMSTABLE);
		DropTable(ROSTER_RECIPESTABLE);
		DropTable(ROSTER_LUT_RECIPES);
		DropTable(ROSTER_LUT_REPUTATION);
		DropTable(ROSTER_LUT_SKILLGROUPS);
		DropTable(ROSTER_LUT_TALENTS);
		DropTable(ROSTER_MEMBERSTABLE);
		DropTable(ROSTER_PETSTABLE);
		DropTable(ROSTER_PLAYERSTABLE);
		DropTable(ROSTER_PVPTABLE);
		DropTable(ROSTER_QUESTSTABLE);
		DropTable(ROSTER_RECIPESTABLE);
		DropTable(ROSTER_REPUTATIONTABLE);
		DropTable(ROSTER_SKILLSTABLE);
		DropTable(ROSTER_SPELLTABLE);
		DropTable(ROSTER_SPELLTREETABLE);
		DropTable(ROSTER_TALENTSTABLE);
		DropTable(ROSTER_TALENTTREETABLE);
	DropTable(ROSTER_LUT_SPELLINFO);

		CreateTable("guilds");
		CreateTable("items");
		CreateTable("lut_recipes");
		CreateTable("lut_reputation");
		CreateTable("lut_skill_groups");
	CreateTable("lut_spellinfo");
		CreateTable("lut_talents");
		CreateTable("members");
		CreateTable("pets");
		CreateTable("players");
		CreateTable("pvp");
		CreateTable("quests");
		CreateTable("recipes");
		CreateTable("reputation");
		CreateTable("skills");
		CreateTable("spellbook");
		CreateTable("spellbooktree");
		CreateTable("talents");
		CreateTable("talenttree");
		CreateTable("metadata");

		// Add the version tag to the metadata
		db_query("INSERT INTO `".ROSTER_METADATATABLE."` (`key`, `value`) VALUES (\"db_version\", \"1\")");

		$db_version = 1;
	}

	if($db_version<2){
	    $db_version = 2;
	}

	if($db_version<3){
	    $db_version = 3;
	}

	if($db_version<4){
	    $db_version = 4;
	}

}

/**
 * Check to see if a table exists
 *
 * @param string $tablename
 * @return int
 */
function tableExists($tablename){
	$res = db_query("SHOW TABLES LIKE '$tablename'");
	if($res->numRows()==1){
		return(true);
	}
	return(false);
}

/**
 * Create a table by "name"
 *
 * @param string $tablename
 */
function CreateTable($tablename){
	static $table_type;
	$table_type = GetConfigValue("table_type");
	$sql['guilds']="CREATE TABLE `".ROSTER_GUILDTABLE."` (
			       guild_id int(11) unsigned NOT NULL auto_increment,
			       guild_name varchar(64) NOT NULL default '',
			       realm varchar(32) NOT NULL default '',
			       faction varchar(8) default NULL,
			       guild_motd varchar(255) NOT NULL default '',
			       guild_num_members int(11) NOT NULL default '0',
			       update_time datetime default NULL,
			       guild_dateupdatedutc varchar(18) default NULL,
			       GPversion varchar(6) default NULL,
			       PRIMARY KEY  (guild_id),
			       KEY guild (guild_name,realm)
			       ) ENGINE=".$table_type;
	$sql['items']="CREATE TABLE `".ROSTER_ITEMSTABLE."` (
					 `member_id` int(11) unsigned NOT NULL default '0',
					 `item_name` varchar(64) NOT NULL default '',
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
					 KEY `name` (`item_name`)
					 ) ENGINE=".$table_type;
	$sql['lut_recipes']="CREATE TABLE `".ROSTER_LUT_RECIPES."` (
						     `recipe_id` bigint(20) NOT NULL auto_increment,
						     `recipe_profession` varchar(255) NOT NULL,
						     `recipe_group` varchar(255) NOT NULL,
						     `recipe_name` varchar(255) NOT NULL,
						     `recipe_difficulty` int(11) NOT NULL,
						     `recipe_reagents` mediumtext NOT NULL,
						     `recipe_texture` varchar(64) NOT NULL,
						     `recipe_tooltip` mediumtext NOT NULL,
						     `recipe_categories` varchar(64) NOT NULL,
						     `recipe_level` int(11) NOT NULL,
						     PRIMARY KEY  (`recipe_id`)
						     ) ENGINE=".$table_type." COMMENT='Lookup table for recipes'";
	$sql['lut_reputation']="CREATE TABLE ".ROSTER_LUT_REPUTATION." (
							 reputation_id bigint(20) NOT NULL auto_increment,
							 reputation_group varchar(255) NOT NULL,
							 reputation_subgroup varchar(255) NOT NULL,
							 PRIMARY KEY  (reputation_id)
							 ) ENGINE=".$table_type;
    $sql['lut_spellinfo']="CREATE TABLE ".ROSTER_LUT_SPELLINFO." (
			    spellinfo_id BIGINT NOT NULL AUTO_INCREMENT ,
			    spellinfo_name VARCHAR( 255 ) NOT NULL ,
			    spellinfo_type VARCHAR( 255 ) NOT NULL ,
			    spellinfo_icon VARCHAR( 255 ) NOT NULL ,
			    spellinfo_rank VARCHAR( 255 ) NOT NULL ,
			    spellinfo_tooltip MEDIUMTEXT,
			    PRIMARY KEY ( spellinfo_id ) ,
			    INDEX ( spellinfo_name , spellinfo_type , spellinfo_icon , spellinfo_rank )
			    ) ENGINE = ".$table_type;
    $sql['lut_talents']="CREATE TABLE `".ROSTER_LUT_TALENTS."` (
						     `talent_id` bigint(20) NOT NULL auto_increment,
						     `talent_name` varchar(32) NOT NULL,
						     `talent_tree` varchar(32) NOT NULL,
						     `talent_rank` int(11) NOT NULL,
						     `talent_maximum_rank` int(11) NOT NULL,
						     `talent_row` tinyint(4) NOT NULL,
						     `talent_column` tinyint(4) NOT NULL,
						     `talent_tooltip` mediumtext NOT NULL,
						     `talent_texture` varchar(64) NOT NULL,
						     PRIMARY KEY  (`talent_id`)
						     ) ENGINE=".$table_type." COMMENT='Lookup table for talents'";
	$sql['members']="CREATE TABLE `".ROSTER_MEMBERSTABLE."` (
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
					     `group` varchar(16) NOT NULL default '',
					     `online` int(1) default '0',
					     `last_online` datetime default NULL,
					     `update_time` datetime default NULL,
					     `account_id` smallint(6) NOT NULL default '0',
					     `inv` tinyint(4) NOT NULL default '3',
					     `talents` tinyint(4) NOT NULL default '3',
					     `quests` tinyint(4) NOT NULL default '3',
					     `bank` tinyint(4) NOT NULL default '3',
					     PRIMARY KEY  (`member_id`),
					     KEY `member` (`guild_id`,`name`),
					     KEY `name` (`name`),
					     KEY `class` (`class`),
					     KEY `level` (`level`),
					     KEY `guild_rank` (`guild_rank`),
					     KEY `last_online` (`last_online`),
					     KEY `guild_id` (`guild_id`)
					     ) ENGINE=".$table_type;
	$sql['metadata']="CREATE TABLE ".ROSTER_METADATATABLE." (
    					`key` VARCHAR(255) NOT NULL,
						`value` VARCHAR(255),
						PRIMARY KEY (`key`)
						) ENGINE = ".$table_type;
	$sql['pets']="CREATE TABLE `".ROSTER_PETSTABLE."` (
						`member_id` int(10) unsigned NOT NULL default '0',
						`name` varchar(64) NOT NULL default '',
						`slot` smallint(5) unsigned NOT NULL default '0',
						`level` smallint(5) unsigned NOT NULL default '0',
						`loyalty` varchar(32) NOT NULL default '',
						`type` varchar(32) NOT NULL default '',
						`icon` varchar(64) NOT NULL default '',
						`health` int(10) unsigned NOT NULL default '0',
						`mana` int(10) unsigned NOT NULL default '0',
						`xp_current` int(10) unsigned NOT NULL default '0',
						`xp_total` int(10) unsigned NOT NULL default '0',
						`used_training_points` int(10) unsigned NOT NULL default '0',
						`total_training_points` int(10) unsigned NOT NULL default '0',
						`stats_intellect_base` int(11) NOT NULL default '0',
						`stats_intellect_current` int(11) NOT NULL default '0',
						`stats_intellect_bonus` int(11) NOT NULL default '0',
						`stats_intellect_debuff` int(11) NOT NULL default '0',
						`stats_agility_base` int(11) NOT NULL default '0',
						`stats_agility_current` int(11) NOT NULL default '0',
						`stats_agility_bonus` int(11) NOT NULL default '0',
						`stats_agility_debuff` int(11) NOT NULL default '0',
						`stats_armor_base` int(11) NOT NULL default '0',
						`stats_armor_current` int(11) NOT NULL default '0',
						`stats_armor_bonus` int(11) NOT NULL default '0',
						`stats_armor_debuff` int(11) NOT NULL default '0',
						`stats_defense_base` int(11) NOT NULL default '0',
						`stats_defense_current` int(11) NOT NULL default '0',
						`stats_defense_bonus` int(11) NOT NULL default '0',
						`stats_defense_debuff` int(11) NOT NULL default '0',
						`stats_stamina_base` int(11) NOT NULL default '0',
						`stats_stamina_current` int(11) NOT NULL default '0',
						`stats_stamina_bonus` int(11) NOT NULL default '0',
						`stats_stamina_debuff` int(11) NOT NULL default '0',
						`stats_spirit_base` int(11) NOT NULL default '0',
						`stats_spirit_current` int(11) NOT NULL default '0',
						`stats_spirit_bonus` int(11) NOT NULL default '0',
						`stats_spirit_debuff` int(11) NOT NULL default '0',
						`stats_strength_base` int(11) NOT NULL default '0',
						`stats_strength_current` int(11) NOT NULL default '0',
						`stats_strength_bonus` int(11) NOT NULL default '0',
						`stats_strength_debuff` int(11) NOT NULL default '0',
						`resist_arcane_base` int(11) NOT NULL default '0',
						`resist_arcane_current` int(11) NOT NULL default '0',
						`resist_arcane_bonus` int(11) NOT NULL default '0',
						`resist_arcane_debuff` int(11) NOT NULL default '0',
						`resist_fire_base` int(11) NOT NULL default '0',
						`resist_fire_current` int(11) NOT NULL default '0',
						`resist_fire_bonus` int(11) NOT NULL default '0',
						`resist_fire_debuff` int(11) NOT NULL default '0',
						`resist_frost_base` int(11) NOT NULL default '0',
						`resist_frost_current` int(11) NOT NULL default '0',
						`resist_frost_bonus` int(11) NOT NULL default '0',
						`resist_frost_debuff` int(11) NOT NULL default '0',
						`resist_holy_base` int(11) NOT NULL default '0',
						`resist_holy_current` int(11) NOT NULL default '0',
						`resist_holy_bonus` int(11) NOT NULL default '0',
						`resist_holy_debuff` int(11) NOT NULL default '0',
						`resist_nature_base` int(11) NOT NULL default '0',
						`resist_nature_current` int(11) NOT NULL default '0',
						`resist_nature_bonus` int(11) NOT NULL default '0',
						`resist_nature_debuff` int(11) NOT NULL default '0',
						`resist_shadow_base` int(11) NOT NULL default '0',
						`resist_shadow_current` int(11) NOT NULL default '0',
						`resist_shadow_bonus` int(11) NOT NULL default '0',
						`resist_shadow_debuff` int(11) NOT NULL default '0',
						`melee_power` mediumint(8) unsigned NOT NULL default '0',
						`melee_speed` float NOT NULL default '0',
						`melee_dps` float NOT NULL default '0',
						`melee_rating` mediumint(8) unsigned NOT NULL default '0',
						`melee_damage_min` mediumint(8) unsigned NOT NULL default '0',
						`melee_damage_max` mediumint(8) unsigned NOT NULL default '0',
						`melee_damage_range_tooltip` tinytext NOT NULL,
						`melee_power_dps` float NOT NULL default '0',
						`melee_power_tooltip` tinytext NOT NULL,
						PRIMARY KEY  (`member_id`,`name`)
				       ) ENGINE=".$table_type;
	$sql['players']="CREATE TABLE `".ROSTER_PLAYERSTABLE."` (
						`member_id` int(10) unsigned NOT NULL default '0',
						`guild_id` int(10) unsigned NOT NULL default '0',
						`name` varchar(64) NOT NULL default '',
						`level` tinyint(3) unsigned NOT NULL default '0',
						`cp_provider` varchar(8) NOT NULL default '',
						`cp_version` varchar(6) NOT NULL default '0',
						`date` datetime NOT NULL default '0000-00-00 00:00:00',
						`date_utc` datetime NOT NULL default '0000-00-00 00:00:00',
						`locale` varchar(6) NOT NULL default '',
						`faction` varchar(32) NOT NULL default '',
						`realm` varchar(64) NOT NULL default '',
						`guild_rank` tinyint(3) unsigned NOT NULL default '0',
						`zone` varchar(64) NOT NULL default '',
						`sub_zone` varchar(64) NOT NULL default '',
						`hearth` varchar(64) NOT NULL default '',
						`health` mediumint(8) unsigned NOT NULL default '0',
						`mana` mediumint(8) unsigned NOT NULL default '0',
						`xp_current` int(10) unsigned NOT NULL default '0',
						`xp_total` int(10) unsigned NOT NULL default '0',
						`xp_rested` int(10) unsigned NOT NULL default '0',
						`unused_talent_points` tinyint(3) unsigned NOT NULL default '0',
						`race` varchar(32) NOT NULL default '',
						`class` varchar(32) NOT NULL default '',
						`sex` varchar(32) NOT NULL default '',
						`time_played` int(10) unsigned NOT NULL default '0',
						`time_level_played` int(10) unsigned NOT NULL default '0',
						`money_copper` tinyint(3) unsigned NOT NULL default '0',
						`money_silver` tinyint(3) unsigned NOT NULL default '0',
						`money_gold` smallint(5) unsigned NOT NULL default '0',
						`stats_intellect_base` int(11) NOT NULL default '0',
						`stats_intellect_current` int(11) NOT NULL default '0',
						`stats_intellect_bonus` int(11) NOT NULL default '0',
						`stats_intellect_debuff` int(11) NOT NULL default '0',
						`stats_agility_base` int(11) NOT NULL default '0',
						`stats_agility_current` int(11) NOT NULL default '0',
						`stats_agility_bonus` int(11) NOT NULL default '0',
						`stats_agility_debuff` int(11) NOT NULL default '0',
						`stats_armor_base` int(11) NOT NULL default '0',
						`stats_armor_current` int(11) NOT NULL default '0',
						`stats_armor_bonus` int(11) NOT NULL default '0',
						`stats_armor_debuff` int(11) NOT NULL default '0',
						`stats_defense_base` int(11) NOT NULL default '0',
						`stats_defense_current` int(11) NOT NULL default '0',
						`stats_defense_bonus` int(11) NOT NULL default '0',
						`stats_defense_debuff` int(11) NOT NULL default '0',
						`stats_stamina_base` int(11) NOT NULL default '0',
						`stats_stamina_current` int(11) NOT NULL default '0',
						`stats_stamina_bonus` int(11) NOT NULL default '0',
						`stats_stamina_debuff` int(11) NOT NULL default '0',
						`stats_spirit_base` int(11) NOT NULL default '0',
						`stats_spirit_current` int(11) NOT NULL default '0',
						`stats_spirit_bonus` int(11) NOT NULL default '0',
						`stats_spirit_debuff` int(11) NOT NULL default '0',
						`stats_strength_base` int(11) NOT NULL default '0',
						`stats_strength_current` int(11) NOT NULL default '0',
						`stats_strength_bonus` int(11) NOT NULL default '0',
						`stats_strength_debuff` int(11) NOT NULL default '0',
						`resist_arcane_base` int(11) NOT NULL default '0',
						`resist_arcane_current` int(11) NOT NULL default '0',
						`resist_arcane_bonus` int(11) NOT NULL default '0',
						`resist_arcane_debuff` int(11) NOT NULL default '0',
						`resist_fire_base` int(11) NOT NULL default '0',
						`resist_fire_current` int(11) NOT NULL default '0',
						`resist_fire_bonus` int(11) NOT NULL default '0',
						`resist_fire_debuff` int(11) NOT NULL default '0',
						`resist_frost_base` int(11) NOT NULL default '0',
						`resist_frost_current` int(11) NOT NULL default '0',
						`resist_frost_bonus` int(11) NOT NULL default '0',
						`resist_frost_debuff` int(11) NOT NULL default '0',
						`resist_holy_base` int(11) NOT NULL default '0',
						`resist_holy_current` int(11) NOT NULL default '0',
						`resist_holy_bonus` int(11) NOT NULL default '0',
						`resist_holy_debuff` int(11) NOT NULL default '0',
						`resist_nature_base` int(11) NOT NULL default '0',
						`resist_nature_current` int(11) NOT NULL default '0',
						`resist_nature_bonus` int(11) NOT NULL default '0',
						`resist_nature_debuff` int(11) NOT NULL default '0',
						`resist_shadow_base` int(11) NOT NULL default '0',
						`resist_shadow_current` int(11) NOT NULL default '0',
						`resist_shadow_bonus` int(11) NOT NULL default '0',
						`resist_shadow_debuff` int(11) NOT NULL default '0',
						`block` float NOT NULL default '0',
						`crit` float NOT NULL default '0',
						`dodge` float NOT NULL default '0',
						`mitigation` float NOT NULL default '0',
						`parry` float NOT NULL default '0',
						`ranged_power` mediumint(8) unsigned NOT NULL default '0',
						`ranged_speed` float NOT NULL default '0',
						`ranged_dps` float NOT NULL default '0',
						`ranged_rating` mediumint(8) unsigned NOT NULL default '0',
						`ranged_damage_min` mediumint(8) unsigned NOT NULL default '0',
						`ranged_damage_max` mediumint(8) unsigned NOT NULL default '0',
						`ranged_damage_range_tooltip` tinytext NOT NULL,
						`ranged_power_dps` float NOT NULL default '0',
						`ranged_power_tooltip` tinytext NOT NULL,
						`melee_power` mediumint(8) unsigned NOT NULL default '0',
						`melee_speed` float NOT NULL default '0',
						`melee_dps` float NOT NULL default '0',
						`melee_rating` mediumint(8) unsigned NOT NULL default '0',
						`melee_damage_min` mediumint(8) unsigned NOT NULL default '0',
						`melee_damage_max` mediumint(8) unsigned NOT NULL default '0',
						`melee_damage_range_tooltip` tinytext NOT NULL,
						`melee_power_dps` float NOT NULL default '0',
						`melee_power_tooltip` tinytext NOT NULL,
						`honor_current_icon` varchar(64) NOT NULL default '',
						`honor_current_name` varchar(32) NOT NULL default '',
						`honor_current_rank` tinyint(3) unsigned NOT NULL default '0',
						`honor_current_progress` float NOT NULL default '0',
						`honor_session_hk` int(10) unsigned NOT NULL default '0',
						`honor_session_dk` int(10) unsigned NOT NULL default '0',
						`honor_yesterday_contib` int(10) unsigned NOT NULL default '0',
						`honor_yesterday_hk` int(10) unsigned NOT NULL default '0',
						`honor_yesterday_dk` int(10) unsigned NOT NULL default '0',
						`honor_thisweek_contrib` int(10) unsigned NOT NULL default '0',
						`honor_thisweek_hk` int(10) unsigned NOT NULL default '0',
						`honor_lastweek_contrib` int(10) unsigned NOT NULL default '0',
						`honor_lastweek_rank` smallint(5) unsigned NOT NULL default '0',
						`honor_lastweek_hk` int(10) unsigned NOT NULL default '0',
						`honor_lastweek_dk` int(10) unsigned NOT NULL default '0',
						`honor_lifetime_name` varchar(32) NOT NULL default '',
						`honor_lifetime_rank` smallint(5) unsigned NOT NULL default '0',
						`honor_lifetime_hk` int(10) unsigned NOT NULL default '0',
						`honor_lifetime_dk` int(10) unsigned NOT NULL default '0',
						PRIMARY KEY  (`member_id`)
					) ENGINE=".$table_type;
	$sql['pvpdata']="CREATE TABLE `".ROSTER_PVPTABLE."` (
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
					KEY `member_id` (`member_id`,`index`)
					) ENGINE=".$table_type;
	$sql['quests']="CREATE TABLE `".ROSTER_QUESTSTABLE."` (
					`member_id` int(11) unsigned NOT NULL default '0',
					`quest_name` varchar(64) NOT NULL default '',
					`quest_index` int(11) NOT NULL default '0',
					`quest_level` int(11) unsigned NOT NULL default '0',
					`quest_tag` varchar(32) NOT NULL default '',
					`is_complete` int(1) NOT NULL default '0',
					`zone` varchar(32) NOT NULL default '',
					PRIMARY KEY  (`member_id`,`quest_name`),
					KEY `quest_index` (`quest_index`,`quest_level`,`quest_tag`),
					KEY `quest_name` (`quest_name`),
					KEY `zone` (`zone`)
					) ENGINE=".$table_type;
	$sql['realmstatus']="CREATE TABLE `".ROSTER_REALMSTATUSTABLE."` (
					`server_name` varchar(20) NOT NULL default '',
					`servertype` varchar(20) NOT NULL default '',
					`serverstatus` varchar(20) NOT NULL default '',
					`serverpop` varchar(20) NOT NULL default '',
					`timestamp` tinyint(2) NOT NULL default '0',
					UNIQUE KEY `server_name` (`server_name`)
					) ENGINE=".$table_type;
	$sql['recipes']="CREATE TABLE `".ROSTER_RECIPESTABLE."` (
					`member_id` int(11) unsigned NOT NULL default '0',
					`recipe_id` bigint(20) NOT NULL,
					PRIMARY KEY  (`member_id`,`recipe_id`)
					) ENGINE=".$table_type;
	$sql['reputation']="CREATE TABLE `".ROSTER_REPUTATIONTABLE."` (
						`member_id` bigint(20) unsigned NOT NULL default '0',
						`reputation_id` bigint(20) NOT NULL,
						`reputation_current` bigint(20) NOT NULL,
						`reputation_maximum` bigint(20) NOT NULL,
						`reputation_war_status` tinyint(4) NOT NULL default '0',
						`reputation_standing` varchar(32) default 'Unknown',
						PRIMARY KEY  (`member_id`,`reputation_id`)
						) ENGINE=".$table_type;
	$sql['skills']="CREATE TABLE `".ROSTER_SKILLSTABLE."` (
					`member_id` int(11) unsigned NOT NULL,
					`skill_group_name` varchar(255) NOT NULL,
					`skill_group_order` int(11) NOT NULL,
					`skill_name` varchar(32) NOT NULL,
					`skill_level_current` bigint(20) NOT NULL default '0',
					`skill_level_maximum` bigint(20) NOT NULL,
					PRIMARY KEY  (`member_id`,`skill_name`),
					KEY `skill_name` (`skill_name`)
					) ENGINE=".$table_type;
	$sql['spellbook']="CREATE TABLE `".ROSTER_SPELLTABLE."` (
					`member_id` int(11) unsigned NOT NULL default '0',
					`spellinfo_id` int(11) unsigned NOT NULL default '0'
					) ENGINE=".$table_type;
	$sql['spellbooktree']="CREATE TABLE `".ROSTER_SPELLTREETABLE."` (
					`member_id` int(11) unsigned NOT NULL default '0',
					`spellinfo_id` int(11) unsigned NOT NULL default '0'
					) ENGINE=".$table_type;
	$sql['talents']="CREATE TABLE `".ROSTER_TALENTSTABLE."` (
					`member_id` int(11) NOT NULL default '0',
					`talent_id` bigint(20) NOT NULL,
					KEY `member_id` (`member_id`),
					KEY `talent_id` (`talent_id`)
					) ENGINE=".$table_type;
	$sql['talenttree']="CREATE TABLE `".ROSTER_TALENTTREETABLE."` (
					`member_id` int(11) NOT NULL default '0',
					`tree` varchar(32) NOT NULL default '',
					`background` varchar(64) NOT NULL default '',
					`order` tinyint(4) NOT NULL default '0',
					`pointsspent` tinyint(4) NOT NULL default '0'
					) ENGINE=".$table_type;
	// Look at the SQL array to determine if we know what table is being asked of us.
	if(isset($sql[$tablename])){
		db_query($sql[$tablename]);
	} else {
		debug_print("Request to create '$tablename' but I don't know what that is.");
	}
}

/**
 * Drop a table from the database
 *
 * @param string $tablename
 */
function DropTable($tablename){
    $res = db_query("SHOW TABLES LIKE '$tablename'");
    if($res->numRows()==1){
	db_query("DROP TABLE `$tablename`");
    }
}

?>