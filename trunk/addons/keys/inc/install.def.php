<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    InstanceKeys
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Installer Instance Keys Addon
 *
 * @package    InstanceKeys
 * @subpackage Installer
 */
class keys
{
	var $active = true;
	var $icon = 'inv_misc_key_06';

	var $upgrades = array(); // There are no previous versions to upgrade from

	var $version = '1.8.0.0';

	var $fullname = 'Instance Keys';
	var $description = 'Lists Azeroth Dungeon keys for guild members';
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

		// Master and menu entries
		$installer->add_config("'1','startpage','keys_conf','display','master'");
		$installer->add_config("'110','keys_conf',NULL,'blockframe','menu'");

		$installer->add_config("'1010','colorcmp','#00ff00','color','keys_conf'");
		$installer->add_config("'1020','colorcur','#ffd700','color','keys_conf'");
		$installer->add_config("'1030','colorno','#ff0000','color','keys_conf'");

		$installer->add_menu_button('keybutton','guild');
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
		return false;
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
		return true;
	}
}
