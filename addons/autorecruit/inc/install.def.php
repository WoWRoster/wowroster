<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: install.def.php 1281 2007-08-25 09:15:11Z Zanix $
 * @link       http://www.wowroster.net
 * @package    AutoRecruit
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Installer for AutoRecruit Addon
 * @package    AutoRecruit
 * @subpackage Installer
 */
class autorecruit
{
	var $active = true;
	var $icon = 'spell_holy_blessedlife';

	var $version = '1.0.0.0';

	var $fullname = 'autorecruit';
	var $description = 'autorecruit_desc';
	var $credits = array(
		array(	"name"=>	"AdricTW",
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

		$installer->add_config("'1','startpage','autorecruit_conf','display','master'");


		$installer->add_menu_button('arecruitbutton','guild');
					
		$installer->create_table(
      $installer->table('recruitment'),
        "
					`guild_id` int(11) unsigned NOT NULL DEFAULT 0,
					`app_link` varchar(164) NOT NULL,
					`min_level` int(11) unsigned NOT NULL DEFAULT 70,
          `max_druid` int(11) unsigned NOT NULL DEFAULT 0,
					`max_hunter` int(11) unsigned NOT NULL DEFAULT 0,
					`max_mage` int(11) unsigned NOT NULL DEFAULT 0,
					`max_paladin` int(11) unsigned NOT NULL DEFAULT 0,
					`max_priest` int(11) unsigned NOT NULL DEFAULT 0,
					`max_rogue` int(11) unsigned NOT NULL DEFAULT 0,
					`max_shaman` int(11) unsigned NOT NULL DEFAULT 0,
					`max_warlock` int(11) unsigned NOT NULL DEFAULT 0,
					`max_warrior` int(11) unsigned NOT NULL DEFAULT 0,
					PRIMARY KEY  (`guild_id`)
        " );
																		
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
    $installer->drop_table( $installer->table('recruitment') );
		
		return true;
	}
}
