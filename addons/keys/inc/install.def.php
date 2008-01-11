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
class keysInstall
{
	var $active = true;
	var $icon = 'inv_misc_key_06';

	var $version = '1.9.9.1580';
	var $wrnet_id = '0';

	var $fullname = 'keys';
	var $description = 'keys_desc';
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
		$installer->add_config("'1040','keys_access','0','access','keys_conf'");

		$installer->add_menu_button('keybutton','guild');

		$installer->create_table($installer->table('keycache'),"
			`member_id` int(11) NOT NULL DEFAULT 0,
			`key_name` varchar(16) NOT NULL DEFAULT '',
			`stage` int(11) NOT NULL DEFAULT 0,
			PRIMARY KEY (`member_id`, `key_name`, `stage`)");
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
			$installer->add_config("'1040','keys_access','0','access','keys_conf'");
		}

		if( version_compare( $oldversion, '1.9.9.1580', '<' ) )
		{
			$installer->create_table($installer->table('keycache'),"
				`member_id` int(11) NOT NULL DEFAULT 0,
				`key_name` varchar(16) NOT NULL DEFAULT '',
				`stage` int(11) NOT NULL DEFAULT 0,
				PRIMARY KEY (`member_id`, `key_name`, `stage`)");
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

		$installer->remove_all_menu_button();
		return true;
	}
}
