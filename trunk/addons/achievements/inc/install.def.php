<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: install.def.php 493 2009-12-27 17:29:50Z Ulminia $
 * @link       http://www.wowroster.net
 * @package    Achievements
 * @subpackage Installer
*/

if ( !defined('ROSTER_INSTALLED') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Installer for Achievements Addon
 *
 * @package    Achievements
 * @subpackage Installer
 *
 */
class achievementsInstall
{
	var $active = true;
	var $icon = 'achievement_general';

	var $version = '1.0.493';
	
	var $fullname = 'Player Achievements';
	var $description = 'Displays Player Achievements';
	var $wrnet_id = '0';
	var $credits = array(
		array(	'name'=>	'Ulminia',
				'info'=>	'Roster/Addon DEV'),
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
		//$installer->add_config("1,'startpage','display','display','master'");
		$installer->add_menu_button('achive','char');
		$installer->add_config("1,'startpage','achive','display','master'");

		$installer->create_table($installer->table('data'),"
			  `id` int(11) NOT NULL auto_increment,
			  `member_id` int(11) NOT NULL,
			  `guild_id` int(11) NOT NULL,
			  `achv_cat` int(11) NOT NULL,
			  `achv_cat_title` varchar(255) NOT NULL default '',
			  `achv_cat_sub` varchar(255) NOT NULL default '',
			  `achv_cat_sub2` int(10) default NULL,
			  `achv_id` varchar(25) NOT NULL,
			  `achv_points` int(11) NOT NULL,
			  `achv_icon` varchar(255) NOT NULL default '',
			  `achv_title` varchar(255) NOT NULL default '',
			  `achv_reward_title` varchar(255) default NULL,
			  `achv_disc` text NOT NULL,
			  `achv_date` varchar(50) default NULL,
			  `achv_criteria` text NOT NULL,
			  `achv_progress` varchar(25) NOT NULL,
			  `achv_progress_width` varchar(50) NOT NULL,
			  `achv_complete` varchar(255) NOT NULL default '',
			  PRIMARY KEY  (`id`)"); 

		$installer->create_table($installer->table('summary'),"
			  `id` int(11) NOT NULL auto_increment,
			  `member_id` int(11) NOT NULL,
			  `guild_id` int(11) NOT NULL,
			  `total` varchar(255) NOT NULL default '',
			  `general` varchar(10) NOT NULL default '',
			  `quests` varchar(10) NOT NULL default '',
			  `exploration` varchar(10) NOT NULL default '',
			  `pvp` varchar(10) NOT NULL default '',
			  `dn_raids` varchar(10) NOT NULL default '',
			  `prof` varchar(10) NOT NULL default '',
			  `rep` varchar(10) NOT NULL default '',
			  `world_events` varchar(10) NOT NULL default '',
			  `feats` varchar(10) NOT NULL default '',
			  `title_1` varchar(255) NOT NULL default '',
			  `disc_1` varchar(255) NOT NULL default '',
			  `date_1` varchar(12) NOT NULL default '',
			  `points_1` varchar(10) NOT NULL default '',
			  `title_2` varchar(255) NOT NULL default '',
			  `disc_2` varchar(255) NOT NULL default '',
			  `date_2` varchar(12) NOT NULL default '',
			  `points_2` varchar(10) NOT NULL default '',
			  `title_3` varchar(255) NOT NULL default '',
			  `disc_3` varchar(255) NOT NULL default '',
			  `date_3` varchar(12) NOT NULL default '',
			  `points_3` varchar(10) NOT NULL default '',
			  `title_4` varchar(255) NOT NULL default '',
			  `disc_4` varchar(255) NOT NULL default '',
			  `date_4` varchar(12) NOT NULL default '',
			  `points_4` varchar(10) NOT NULL default '',
			  `title_5` varchar(255) NOT NULL default '',
			  `disc_5` varchar(255) NOT NULL default '',
			  `date_5` varchar(12) NOT NULL default '',
			  `points_5` varchar(10) NOT NULL default '',
			  PRIMARY KEY  (`id`)"); 

		return true;
	}

	/**
	 * Upgrade Function
	 *
	 * @param string $oldversion
	 * @return bool
	 */
	function upgrade($oldversion, $version)
	{
		global $installer, $addon;
	}

	/**
	 * Un-Install Function
	 *
	 * @return bool
	 */
	function uninstall()
	{
		global $installer, $addon, $roster;

		$installer->remove_all_config();
		$installer->remove_all_menu_button();
		$installer->drop_table( $installer->table('data') );
		$installer->drop_table( $installer->table('summary') );

		return true;
	}
}
