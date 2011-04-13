<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    GuildInfo
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Installer for GuildInfo Addon
 * @package    GuildInfo
 * @subpackage Installer
 */
class guildinfoInstall
{
	var $active = true;
	var $icon = 'inv_misc_note_06';

	var $version = '2.0.9.2300';
	var $wrnet_id = '0';

	var $fullname = 'guildinfo';
	var $description = 'guildinfo_desc';
	var $credits = array(
		array(	"name"=>	"WoWRoster Dev Team",
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
/*
		local NEWS_MOTD = -1;				-- pseudo category
		local NEWS_GUILD_ACHIEVEMENT = 0;
		local NEWS_PLAYER_ACHIEVEMENT = 1;
		local NEWS_DUNGEON_ENCOUNTER = 2;
		local NEWS_ITEM_LOOTED = 3;
		local NEWS_ITEM_CRAFTED = 4;
		local NEWS_ITEM_PURCHASED = 5;
		local NEWS_GUILD_LEVEL = 6;
		local NEWS_GUILD_CREATE = 7;
*/
		$installer->create_table($installer->table('news'),"
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`guild_id` int(11) unsigned NOT NULL default '0',
			`type` varchar(96) NOT NULL default '',
			`Member` varchar(64) NOT NULL default '',
			`Achievement` varchar(150) NOT NULL default '0',
			`Date` datetime default NULL,
			`Display_date` varchar(96) NOT NULL default '',
			`Typpe` varchar(32) NOT NULL default '',
			KEY  (`id`)");
		/*

		["TotalXP"] = 0,
		["WeeklyXP"] = 0,
		["TotalRank"] = 108,
		["WeeklyRank"] = 82,
		*/
		$installer->create_table($installer->table('ranks'),"
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`guild_id` int(11) unsigned NOT NULL default '0',
			`member_id` int(11) NULL default '0',
			`Member` varchar(64) NULL default '',
			`TotalXP` INT( 32 ) NOT NULL DEFAULT '0',
			`WeeklyXP` INT( 32 ) NOT NULL DEFAULT '0',
			`TotalRank` INT( 32 ) NOT NULL DEFAULT '0',
			`WeeklyRank` INT( 32 ) NOT NULL DEFAULT '0',
			KEY  (`id`)");

		$installer->add_config("'1','startpage','guildinfo_conf','display','master'");
		$installer->add_config("'100','guildinfo_conf',NULL,'blockframe','menu'");
		$installer->add_config("'200','guildinfo_graph',NULL,'page{2','menu'");

		$installer->add_config("'210', 'graph_level', NULL, 'blockframe', 'guildinfo_graph'");
		$installer->add_config("'220', 'graph_class', NULL, 'blockframe', 'guildinfo_graph'");

		$installer->add_config("'1000', 'guildinfo_access', '0', 'access', 'guildinfo_conf'");

		$installer->add_config("'2000', 'graph_level_display', '1', 'radio{off^0|on^1', 'graph_level'");
		$installer->add_config("'2010', 'graph_level_style', 'bar', 'select{List^list|Bar graph^bar|Logarithmic bargraph^barlog', 'graph_level'");
		$installer->add_config("'2020', 'graph_level_level', '1', 'text{2|10', 'graph_level'");
		$installer->add_config("'2030', 'graph_level_bar_color', '#3E0000', 'color', 'graph_level'");
		$installer->add_config("'2040', 'graph_level_bar2_color', '#FFCC33', 'color', 'graph_level'");
		$installer->add_config("'2050', 'graph_level_font', 'VERANDA.TTF', 'function{fontFiles', 'graph_level'");
		$installer->add_config("'2060', 'graph_level_font_size', '10', 'text{2|10', 'graph_level'");
		$installer->add_config("'2070', 'graph_level_font_color', '#FFFFFF', 'color', 'graph_level'");
		$installer->add_config("'2080', 'graph_level_outline', '#000000', 'color', 'graph_level'");
		$installer->add_config("'2090', 'graph_level_foot_font', 'visitor.ttf', 'function{fontFiles', 'graph_level'");
		$installer->add_config("'2100', 'graph_level_foot_size', '12', 'text{2|10', 'graph_level'");
		$installer->add_config("'2110', 'graph_level_foot_color', '#FFFFFF', 'color', 'graph_level'");
		$installer->add_config("'2120', 'graph_level_foot_outline', '#000000', 'color', 'graph_level'");

		$installer->add_config("'3000', 'graph_class_display', '1', 'radio{off^0|on^1', 'graph_class'");
		$installer->add_config("'3010', 'graph_class_style', 'bar', 'select{List^list|Bar graph^bar|Logarithmic bargraph^barlog', 'graph_class'");
		$installer->add_config("'3020', 'graph_class_level', '1', 'text{2|10', 'graph_class'");
		$installer->add_config("'3030', 'graph_class_bar_color', '', 'color', 'graph_class'");
		$installer->add_config("'3040', 'graph_class_bar2_color', '#000066', 'color', 'graph_class'");
		$installer->add_config("'3050', 'graph_class_font', 'VERANDA.TTF', 'function{fontFiles', 'graph_class'");
		$installer->add_config("'3060', 'graph_class_font_size', '10', 'text{2|10', 'graph_class'");
		$installer->add_config("'3070', 'graph_class_font_color', '', 'color', 'graph_class'");
		$installer->add_config("'3080', 'graph_class_outline', '#000000', 'color', 'graph_class'");
		$installer->add_config("'3090', 'graph_class_foot_font', 'visitor.ttf', 'function{fontFiles', 'graph_class'");
		$installer->add_config("'3100', 'graph_class_foot_size', '12', 'text{2|10', 'graph_class'");
		$installer->add_config("'3110', 'graph_class_foot_color', '#FFFFFF', 'color', 'graph_class'");
		$installer->add_config("'3120', 'graph_class_foot_outline', '#000000', 'color', 'graph_class'");
		
		$installer->add_config("'4000', 'graph_rank_display', '1', 'radio{off^0|on^1', 'graph_rank'");
		$installer->add_config("'4010', 'graph_rank_style', 'bar', 'select{List^list|Bar graph^bar|Logarithmic bargraph^barlog', 'graph_rank'");
		$installer->add_config("'4020', 'graph_rank_level', '1', 'text{2|10', 'graph_rank'");
		$installer->add_config("'4030', 'graph_rank_bar_color', '', 'color', 'graph_rank'");
		$installer->add_config("'4440', 'graph_rank_bar2_color', '#000066', 'color', 'graph_rank'");
		$installer->add_config("'4450', 'graph_rank_font', 'VERANDA.TTF', 'function{fontFiles', 'graph_rank'");
		$installer->add_config("'4060', 'graph_rank_font_size', '10', 'text{2|10', 'graph_rank'");
		$installer->add_config("'4070', 'graph_rank_font_color', '', 'color', 'graph_rank'");
		$installer->add_config("'4080', 'graph_rank_outline', '#000000', 'color', 'graph_rank'");
		$installer->add_config("'4090', 'graph_rank_foot_font', 'visitor.ttf', 'function{fontFiles', 'graph_rank'");
		$installer->add_config("'4100', 'graph_rank_foot_size', '12', 'text{2|10', 'graph_rank'");
		$installer->add_config("'4110', 'graph_rank_foot_color', '#FFFFFF', 'color', 'graph_rank'");
		$installer->add_config("'4120', 'graph_rank_foot_outline', '#000000', 'color', 'graph_rank'");

		$installer->add_menu_button('ginfobutton','guild');
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

		if( version_compare( $oldversion, '1.9.9.1759', '<' ) )
		{
			$installer->create_table($installer->table('news'),"
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`guild_id` int(11) unsigned NOT NULL default '0',
				`type` varchar(96) NOT NULL default '',
				`Member` varchar(64) NOT NULL default '',
				`Achievement` varchar(150) NOT NULL default '0',
				`Date` datetime default NULL,
				`Display_date` varchar(96) NOT NULL default '',
				`Typpe` varchar(32) NOT NULL default '',
				KEY  (`id`)
			");
		}

		if( version_compare( $oldversion, '1.9.9.1760', '<' ) )
		{
			$installer->create_table($installer->table('ranks'),"
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`guild_id` int(11) unsigned NOT NULL default '0',
				`member_id` int(11) NULL default '0',
				`Member` varchar(64) NULL default '',
				`TotalXP` INT( 32 ) NOT NULL DEFAULT '0',
				`WeeklyXP` INT( 32 ) NOT NULL DEFAULT '0',
				`TotalRank` INT( 32 ) NOT NULL DEFAULT '0',
				`WeeklyRank` INT( 32 ) NOT NULL DEFAULT '0',
				KEY  (`id`)
			");
		}

		if( version_compare( $oldversion, '2.0.9.2300', '<' ) )
		{
		
		$installer->add_config("'4000', 'graph_rank_display', '1', 'radio{off^0|on^1', 'graph_rank'");
		$installer->add_config("'4010', 'graph_rank_style', 'bar', 'select{List^list|Bar graph^bar|Logarithmic bargraph^barlog', 'graph_rank'");
		$installer->add_config("'4020', 'graph_rank_level', '1', 'text{2|10', 'graph_rank'");
		$installer->add_config("'4030', 'graph_rank_bar_color', '', 'color', 'graph_rank'");
		$installer->add_config("'4440', 'graph_rank_bar2_color', '#000066', 'color', 'graph_rank'");
		$installer->add_config("'4450', 'graph_rank_font', 'VERANDA.TTF', 'function{fontFiles', 'graph_rank'");
		$installer->add_config("'4060', 'graph_rank_font_size', '10', 'text{2|10', 'graph_rank'");
		$installer->add_config("'4070', 'graph_rank_font_color', '', 'color', 'graph_rank'");
		$installer->add_config("'4080', 'graph_rank_outline', '#000000', 'color', 'graph_rank'");
		$installer->add_config("'4090', 'graph_rank_foot_font', 'visitor.ttf', 'function{fontFiles', 'graph_rank'");
		$installer->add_config("'4100', 'graph_rank_foot_size', '12', 'text{2|10', 'graph_rank'");
		$installer->add_config("'4110', 'graph_rank_foot_color', '#FFFFFF', 'color', 'graph_rank'");
		$installer->add_config("'4120', 'graph_rank_foot_outline', '#000000', 'color', 'graph_rank'");
		}
		// Add config for bargraphs and such
		if( version_compare( $oldversion, '2.0.9.2292', '<' ) )
		{
			$installer->remove_all_config();

			$installer->add_config("'1','startpage','guildinfo_conf','display','master'");
			$installer->add_config("'100','guildinfo_conf',NULL,'blockframe','menu'");
			$installer->add_config("'200','guildinfo_graph',NULL,'page{2','menu'");

			$installer->add_config("'210', 'graph_level', NULL, 'blockframe', 'guildinfo_graph'");
			$installer->add_config("'220', 'graph_class', NULL, 'blockframe', 'guildinfo_graph'");

			$installer->add_config("'1000', 'guildinfo_access', '0', 'access', 'guildinfo_conf'");

			$installer->add_config("'2000', 'graph_level_display', '1', 'radio{off^0|on^1', 'graph_level'");
			$installer->add_config("'2010', 'graph_level_style', 'bar', 'select{List^list|Bar graph^bar|Logarithmic bargraph^barlog', 'graph_level'");
			$installer->add_config("'2020', 'graph_level_level', '1', 'text{2|10', 'graph_level'");
			$installer->add_config("'2030', 'graph_level_bar_color', '#3E0000', 'color', 'graph_level'");
			$installer->add_config("'2040', 'graph_level_bar2_color', '#FFCC33', 'color', 'graph_level'");
			$installer->add_config("'2050', 'graph_level_font', 'VERANDA.TTF', 'function{fontFiles', 'graph_level'");
			$installer->add_config("'2060', 'graph_level_font_size', '10', 'text{2|10', 'graph_level'");
			$installer->add_config("'2070', 'graph_level_font_color', '#FFFFFF', 'color', 'graph_level'");
			$installer->add_config("'2080', 'graph_level_outline', '#000000', 'color', 'graph_level'");
			$installer->add_config("'2090', 'graph_level_foot_font', 'visitor.ttf', 'function{fontFiles', 'graph_level'");
			$installer->add_config("'2100', 'graph_level_foot_size', '12', 'text{2|10', 'graph_level'");
			$installer->add_config("'2110', 'graph_level_foot_color', '#FFFFFF', 'color', 'graph_level'");
			$installer->add_config("'2120', 'graph_level_foot_outline', '#000000', 'color', 'graph_level'");

			$installer->add_config("'3000', 'graph_class_display', '1', 'radio{off^0|on^1', 'graph_class'");
			$installer->add_config("'3010', 'graph_class_style', 'bar', 'select{List^list|Bar graph^bar|Logarithmic bargraph^barlog', 'graph_class'");
			$installer->add_config("'3020', 'graph_class_level', '1', 'text{2|10', 'graph_class'");
			$installer->add_config("'3030', 'graph_class_bar_color', '', 'color', 'graph_class'");
			$installer->add_config("'3040', 'graph_class_bar2_color', '#000066', 'color', 'graph_class'");
			$installer->add_config("'3050', 'graph_class_font', 'VERANDA.TTF', 'function{fontFiles', 'graph_class'");
			$installer->add_config("'3060', 'graph_class_font_size', '10', 'text{2|10', 'graph_class'");
			$installer->add_config("'3070', 'graph_class_font_color', '', 'color', 'graph_class'");
			$installer->add_config("'3080', 'graph_class_outline', '#000000', 'color', 'graph_class'");
			$installer->add_config("'3090', 'graph_class_foot_font', 'visitor.ttf', 'function{fontFiles', 'graph_class'");
			$installer->add_config("'3100', 'graph_class_foot_size', '12', 'text{2|10', 'graph_class'");
			$installer->add_config("'3110', 'graph_class_foot_color', '#FFFFFF', 'color', 'graph_class'");
			$installer->add_config("'3120', 'graph_class_foot_outline', '#000000', 'color', 'graph_class'");
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

		$installer->drop_table($installer->table('news'));
		$installer->drop_table($installer->table('ranks'));
		$installer->remove_all_config();
		$installer->remove_all_menu_button();
		return true;
	}
}
