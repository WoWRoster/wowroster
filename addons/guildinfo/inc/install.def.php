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

	var $version = '1.9.9.1760';
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

		if( version_compare( $oldversion, '1.9.9.1562', '<' ) )
		{
			$installer->add_config("'1','startpage','guildinfo_conf','display','master'");
			$installer->add_config("'100','guildinfo_conf',NULL,'blockframe','menu'");
			$installer->add_config("'1000', 'guildinfo_access', '0', 'access', 'guildinfo_conf'");
		}

		if( version_compare( $oldversion, '1.9.9.1758', '<' ) )
		{
			$installer->remove_all_config();
		}
		
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

		$installer->remove_all_menu_button();
		return true;
	}
}
