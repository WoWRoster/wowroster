<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: install.def.php 1794 2008-06-15 21:28:59Z Zanix $
 * @link       http://www.wowroster.net
 * @package    MembersList
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Installer for MembersList Addon
 * @package    MembersList
 * @subpackage Installer
 */
class memberslistInstall
{
	var $active = true;
	var $icon = 'inv_letter_06';

	var $version = '1.9.9.1607';
	var $wrnet_id = '0';

	var $fullname = 'memberslist';
	var $description = 'memberslist_desc';
	var $credits = array(
		array(	"name"=>	"WoWRoster Dev Team",
				"info"=>	"Now maintained by the whole team"),
		array(	"name"=>	"PleegWat",
				"info"=>	"Original Author")
	);


	/**
	 * Install Function
	 *
	 * @return bool
	 */
	function install()
	{
		global $installer;

		# Master data for the config file
		$installer->add_config("1,'startpage','display','display','master'");

		# Config menu entries
		$installer->add_config("110,'display',NULL,'blockframe','menu'");
		$installer->add_config("120,'members',NULL,'blockframe','menu'");
		$installer->add_config("130,'stats',NULL,'blockframe','menu'");
		$installer->add_config("140,'honor',NULL,'blockframe','menu'");
		$installer->add_config("150,'log',NULL,'blockframe','menu'");
		$installer->add_config("160,'build',NULL,'blockframe','menu'");
		$installer->add_config("170,'ml_wiki','http://www.wowroster.net/MediaWiki/MembersList','newlink','menu'");
		$installer->add_config("180,'updMainAlt','rostercp-addon-memberslist-update','makenewlink','menu'");

		# Generic display settings
		$installer->add_config("1000,'openfilter','0','radio{Show^1|Hide^0','display'");
		$installer->add_config("1010,'nojs','0','radio{Server^1|Client^0','display'");
		$installer->add_config("1020,'def_sort','','select{Default Sort^|Name^name|Class^class|Level^level|Guild Title^guild_title|Highest Rank^lifetimeHighestRank|Note^note|Hearthstone Location^hearth|Zone Location^zone|Last Online^last_online_f|Last Updated^last_update','display'");
		$installer->add_config("1030,'member_tooltip','1','radio{On^1|Off^0','display'");
		$installer->add_config("1040,'group_alts','1','radio{Open^2|Closed^1|Ungrouped^0','display'");
		$installer->add_config("1050,'icon_size','16','select{8px^8|9px^9|10px^10|11px^11|12px^12|13px^13|14px^14|15px^15|16px^16|17px^17|18px^18|19px^19|20px^20','display'");
		$installer->add_config("1060,'class_icon','2','radio{Full^2|On^1|Off^0','display'");
		$installer->add_config("1070,'class_text','0','radio{Color^2|On^1|Off^0','display'");
		$installer->add_config("1080,'talent_text','0','radio{On^1|Off^0','display'");
		$installer->add_config("1090,'level_bar','1','radio{On^1|Off^0','display'");
		$installer->add_config("1100,'honor_icon','1','radio{On^1|Off^0','display'");
		$installer->add_config("1110,'compress_note','1','radio{On^1|Off^0','display'");
		$installer->add_config("1120,'page_size','0','text{4|30','display'");

		# Per page settings: Memberlist
		$installer->add_config("2000,'member_update_inst','1','radio{Off^0|On^1','members'");
		$installer->add_config("2010,'member_motd','1','radio{Off^0|On^1','members'");
		$installer->add_config("2020,'member_hslist','1','radio{Off^0|On^1','members'");
		$installer->add_config("2030,'member_pvplist','1','radio{Off^0|On^1','members'");
		$installer->add_config("2040,'member_class','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','members'");
		$installer->add_config("2050,'member_level','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','members'");
		$installer->add_config("2060,'member_gtitle','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','members'");
		$installer->add_config("2070,'member_hrank','0','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','members'");
		$installer->add_config("2080,'member_prof','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','members'");
		$installer->add_config("2090,'member_hearth','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','members'");
		$installer->add_config("2100,'member_zone','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','members'");
		$installer->add_config("2110,'member_online','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','members'");
		$installer->add_config("2120,'member_update','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','members'");
		$installer->add_config("2130,'member_note','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','members'");
		$installer->add_config("2140,'member_onote','0','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','members'");

		# Per page settings: Statslist
		$installer->add_config("3000,'stats_update_inst','0','radio{Off^0|On^1','stats'");
		$installer->add_config("3010,'stats_motd','0','radio{Off^0|On^1','stats'");
		$installer->add_config("3020,'stats_hslist','0','radio{Off^0|On^1','stats'");
		$installer->add_config("3030,'stats_pvplist','0','radio{Off^0|On^1','stats'");
		$installer->add_config("3040,'stats_class','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3050,'stats_level','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3060,'stats_str','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3070,'stats_agi','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3080,'stats_sta','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3090,'stats_int','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3100,'stats_spi','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3110,'stats_sum','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3120,'stats_health','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3130,'stats_mana','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3140,'stats_armor','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3150,'stats_dodge','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3160,'stats_parry','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3170,'stats_block','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");
		$installer->add_config("3180,'stats_crit','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','stats'");

		# Per page settings: Honorlist
		$installer->add_config("4000,'honor_update_inst','0','radio{Off^0|On^1','honor'");
		$installer->add_config("4010,'honor_motd','0','radio{Off^0|On^1','honor'");
		$installer->add_config("4020,'honor_hslist','1','radio{Off^0|On^1','honor'");
		$installer->add_config("4030,'honor_pvplist','1','radio{Off^0|On^1','honor'");
		$installer->add_config("4040,'honor_class','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','honor'");
		$installer->add_config("4050,'honor_level','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','honor'");
		$installer->add_config("4060,'honor_thk','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','honor'");
		$installer->add_config("4070,'honor_tcp','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','honor'");
		$installer->add_config("4080,'honor_yhk','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','honor'");
		$installer->add_config("4090,'honor_ycp','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','honor'");
		$installer->add_config("4100,'honor_lifehk','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','honor'");
		$installer->add_config("4110,'honor_hrank','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','honor'");
		$installer->add_config("4120,'honor_hp','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','honor'");
		$installer->add_config("4130,'honor_ap','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','honor'");

		# Per page settings: Member Log
		$installer->add_config("5000,'log_update_inst','0','radio{Off^0|On^1','log'");
		$installer->add_config("5010,'log_motd','0','radio{Off^0|On^1','log'");
		$installer->add_config("5020,'log_hslist','0','radio{Off^0|On^1','log'");
		$installer->add_config("5030,'log_pvplist','0','radio{Off^0|On^1','log'");
		$installer->add_config("5040,'log_class','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','log'");
		$installer->add_config("5050,'log_level','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','log'");
		$installer->add_config("5060,'log_gtitle','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','log'");
		$installer->add_config("5070,'log_type','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','log'");
		$installer->add_config("5080,'log_date','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','log'");
		$installer->add_config("5090,'log_note','2','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','log'");
		$installer->add_config("5100,'log_onote','0','radio{Force Hidden^0|Default Hidden^1|Default Shown^2|Force Shown^3','log'");

		# Main/Alt Build settings
		$installer->add_config("6000,'getmain_regex','/ALT-([\\\\w]+)/i','text{50|30','build'");
		$installer->add_config("6010,'getmain_field','Note','select{Public Note^Note|Officer Note^OfficerNote','build'");
		$installer->add_config("6020,'getmain_match','1','text{2|30','build'");
		$installer->add_config("6030,'getmain_main','Main','text{20|30','build'");
		$installer->add_config("6040,'defmain','1','radio{Main^1|Mainless Alt^0','build'");
		$installer->add_config("6050,'invmain','0','radio{Main^1|Mainless Alt^0','build'");
		$installer->add_config("6060,'altofalt','alt','select{Try to resolve^resolve|Leave in table^leave|Set as main^main|Set as mainless alt^alt','build'");
		$installer->add_config("6070,'update_type','1','select{None^0|Guild^1|Character^2|Both^3','build'");

		$installer->create_table($installer->table('alts'),"
			`member_id` int(11)    unsigned NOT NULL default '0',
			`main_id`   int(11)    unsigned NOT NULL default '0',
			`alt_type`  tinyint(3) unsigned NOT NULL default '0',
			PRIMARY KEY (`member_id`)");

		// Filled from the normal config table by admin/guild.php
		$installer->create_table($installer->table('config_guild'),"
			`guild_id` int(11) NOT NULL default '0',
			`id` int(11) unsigned NOT NULL,
			`config_name` varchar(255) default NULL,
			`config_value` tinytext,
			`form_type` mediumtext,
			`config_type` varchar(255) default NULL,
			PRIMARY KEY  (`guild_id`,`id`)");

		$installer->add_query("INSERT INTO `" . $installer->table('config_guild') . "` VALUES
			(0, 5590, 'use_global', '1', 'radio{on^1|off^0', 'build');");

		# Roster menu entry
		$installer->add_menu_button('memberslist_Members','guild','','spell_holy_prayerofspirit');
		$installer->add_menu_button('memberslist_Stats','guild','statslist','inv_misc_book_09');
		$installer->add_menu_button('memberslist_Honor','guild','honorlist','inv_jewelry_necklace_37');
		$installer->add_menu_button('memberslist_Log','guild','log','inv_misc_symbolofkings_01');
		$installer->add_menu_button('memberslist_Realm','realm','','spell_holy_crusade');
		$installer->add_menu_button('memberslist_RealmGuild','realm','guild','spell_nature_natureguardian');
		return true;
	}

	/**
	 * Upgrade Function
	 *
	 * @param string $oldversion
	 * @return bool
	 */
	function upgrade($oldversion)
	{
		global $installer;

		/**
		 * In this update: Per-guild main/alt rule configuration
		 */
		if( version_compare('1.9.9.1479', $oldversion, '>') == true )
		{
			// Filled from the normal config table and ID=0 by admin/guild.php
			// Note: When updating config entries from the 'build' category, apply them both on the main config table and on this one.
			$installer->create_table($installer->table('config_guild'),"
				`guild_id` int(11) NOT NULL default '0',
				`id` int(11) unsigned NOT NULL,
				`config_name` varchar(255) default NULL,
				`config_value` tinytext,
				`form_type` mediumtext,
				`config_type` varchar(255) default NULL,
				PRIMARY KEY  (`guild_id`,`id`)");
		}

		if( version_compare('1.9.9.1522', $oldversion, '>') == true )
		{
			$installer->add_query("UPDATE `" . $installer->table('config_guild') . "` SET `config_name` = CONCAT('guild_', `guild_id`) WHERE `config_name` = 'build' AND `guild_id` > 0;");
			$installer->add_query("UPDATE `" . $installer->table('config_guild') . "` SET `config_type` = CONCAT('guild_', `guild_id`) WHERE `config_type` = 'build' AND `guild_id` > 0;");
			$installer->remove_config(165);
		}

		if( version_compare('1.9.9.1523', $oldversion, '>') == true )
		{
			$installer->add_query("DELETE FROM `" . $installer->table('config_guild') . "` WHERE `id` in (100,170,180);");
		}

		if( version_compare('1.9.9.1525', $oldversion, '>') == true )
		{
			$installer->add_query("UPDATE `" . $installer->table('config_guild') . "` SET `config_type` = CONCAT('guild_', `guild_id`) WHERE `config_type` = 'build' AND `guild_id` > 0;");
		}

		// Re-add the use_global field, since bad code in admin/index.php deleted it.
		if( version_compare('1.9.9.1607', $oldversion, '>') == true )
		{
			$installer->add_query("DELETE FROM `" . $installer->table('config_guild') . "` WHERE `id` = '5590';");

			$installer->add_query("INSERT INTO `" . $installer->table('config_guild') . "` VALUES
				(0, 5590, 'use_global', '1', 'radio{on^1|off^0', 'build');");

			$installer->add_query("INSERT INTO `" . $installer->table('config_guild') . "`
				SELECT DISTINCT `guild_id`, 5590, 'use_global', '0', 'radio{on^1|off^0', CONCAT('guild_', `guild_id`)
				FROM `" . $installer->table('config_guild') . "`
				WHERE `guild_id` > 0;");
		}
		return true;
	}

	/**
	 * Un-Install Function
	 *
	 * @return bool
	 */
	function uninstall()
	{
		global $installer;

		$installer->remove_all_config();

		$installer->drop_table($installer->table('alts'));
		$installer->drop_table($installer->table('config_guild'));
		$installer->remove_all_menu_button();

		return true;
	}
}
