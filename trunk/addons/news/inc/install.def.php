<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: pvp3.php 897 2007-05-06 00:35:11Z Zanix $
 * @link       http://www.wowroster.net
 * @package    News
 * @subpackage Installer
*/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

/**
 * News Addon Installer
 * @package News
 * @subpackage Installer
 */
class news
{
	var $active = true;
	var $icon = 'ability_warrior_rallyingcry';

	var $upgrades = array(); // There are no previous versions to upgrade from

	var $version = '1.8.0-0';

	var $fullname = 'News';
	var $description = 'News system, with comments';
	var $credits = array(
	array(	"name"=>	"PleegWat",
			"info"=>	"Original author")
	);


	/**
	 * Install function
	 *
	 * @return bool
	 */
	function install()
	{
		global $installer;

		// Master and menu entries
		$installer->add_config("'1','startpage','news_conf','display','master'");
		$installer->add_config("'110','news_conf',NULL,'blockframe','menu'");

		$installer->add_query("
			CREATE TABLE `" . $installer->table('news') . "` (
				`news_id` int(11) unsigned AUTO_INCREMENT,
				`author` varchar(16) NOT NULL DEFAULT '',
				`date` datetime,
				`title` mediumtext,
				`content` longtext,
				PRIMARY KEY (`news_id`)
			) TYPE=MyISAM;
		");
		
		$installer->add_query("
			CREATE TABLE `" . $installer->table('comments') . "` (
				`comment_id` int(11) unsigned AUTO_INCREMENT,
				`news_id` int(11) unsigned NOT NULL,
				`author` varchar(16) NOT NULL DEFAULT '',
				`date` datetime,
				`content` longtext,
				PRIMARY KEY (`comment_id`)
			) TYPE=MyISAM;
		");
				

		$installer->add_menu_button('news_button','util');
		return true;
	}

	/**
	 * Upgrade functoin
	 *
	 * @param string $oldversion
	 * @return bool
	 */
	function upgrade($oldversion)
	{
		// Nothing to upgrade from yet
		return false;
	}

	/**
	 * Un-Install function
	 *
	 * @return bool
	 */
	function uninstall()
	{
		global $installer;

		$installer->add_query("
			DROP TABLE IF EXISTS `" . $installer->table('news') . "`;
		");
		$installer->add_query("
			DROP TABLE IF EXISTS `" . $installer->table('comments') . "`;
		");
		
		
		$installer->remove_all_config();
		$installer->remove_all_menu_button();

		return true;
	}
}
