<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    PvPLog
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Installer for PvPLog Addon
 *
 * @package    PvPLog
 * @subpackage Installer
 */
class pvplogInstall
{
	var $active = true;
	var $icon = 'inv_banner_03';

	var $version = '2.0.9.1976';
	var $wrnet_id = '0';

	var $fullname = 'pvplog';
	var $description = 'pvplog_desc';
	var $credits = array(
		array(	"name"=>	"WoWRoster Dev Team",
				"info"=>	"Sortable/filterable member list"),
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
		$installer->add_config("1,'startpage','pvpconfig','display','master'");

		# Config menu entries
		$installer->add_config("100,'pvpconfig',NULL,'blockframe','menu'");

		# Generic display settings
		$installer->add_config("'1000','minPvPLogver','2.0.0','text{10|10','pvpconfig'");

		$installer->create_table($installer->table('pvp2'),"
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
		  KEY `member_id` (`member_id`,`index`)");

		# Roster menu entry
		$installer->add_menu_button('button_pvplog','guild');

		$installer->add_menu_button('button_pvp','char','pvp','inv_banner_03');
		$installer->add_menu_button('button_bg','char','bg','inv_bannerpvp_03');
		$installer->add_menu_button('button_duel','char','duel','inv_brd_banner');
		return true;
	}

	/**
	 * Upgrade Function
	 *
	 * @return bool
	 */
	function upgrade($oldversion)
	{
		// Nothing to upgrade from yet
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
		$installer->remove_all_menu_button();

		$installer->drop_table($installer->table('pvp2'));

		$installer->remove_menu_button('pvplist');

		return true;
	}
}
