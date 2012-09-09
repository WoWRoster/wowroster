<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    Vault
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Vault Addon Installer
 * @package Vault
 * @subpackage Installer
 */
class vaultInstall
{
	var $active = true;
	var $icon = 'inv_misc_ornatebox';

	var $version = '2.1.2415';
	var $wrnet_id = '0';

	var $fullname = 'vault';
	var $description = 'vault_desc';
	var $credits = array(
		array(	"name"=>	"WoWRoster Dev Team",
				"info"=>	"Original Author")
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
		$installer->add_config("'1','startpage','vault_conf','display','master'");
		$installer->add_config("'100','vault_conf',NULL,'blockframe','menu'");
		$installer->add_config("'1000', 'tab1', '0:13', 'access', 'vault_conf'");
		$installer->add_config("'1010', 'tab2', '0:13', 'access', 'vault_conf'");
		$installer->add_config("'1020', 'tab3', '0:13', 'access', 'vault_conf'");
		$installer->add_config("'1030', 'tab4', '0:13', 'access', 'vault_conf'");
		$installer->add_config("'1040', 'tab5', '0:13', 'access', 'vault_conf'");
		$installer->add_config("'1045', 'tab6', '0:13', 'access', 'vault_conf'");
		$installer->add_config("'1046', 'tab7', '0:13', 'access', 'vault_conf'");
		$installer->add_config("'1047', 'tab8', '0:13', 'access', 'vault_conf'");
		$installer->add_config("'1050', 'money', '1', 'access', 'vault_conf'");

		$installer->create_table($installer->table('log'),"
			`log_id` int(11) unsigned NOT NULL default '0',
			`guild_id` int(11) unsigned NOT NULL default '0',
			`member` varchar(96) NOT NULL default '',
			`parent` varchar(64) NOT NULL default '',
			`type` varchar(32) NOT NULL default '0',
			`time` datetime default NULL,
			`amount` varchar(32) NOT NULL default '',
			`count` int(11) default NULL,
			`item_id` varchar(64) default NULL,
			KEY  (`log_id`),
			KEY `type` (`type`),
			KEY `name` (`member`)");

		$installer->create_table($installer->table('money'),"
			`guild_id` int(11) unsigned NOT NULL default '0',
			`money_c` int(11) NOT NULL default '0',
			`money_s` int(11) NOT NULL default '0',
			`money_g` int(11) NOT NULL default '0',
			KEY `id` (`guild_id`)");

		$installer->create_table($installer->table('items'),"
			`guild_id` int(11) unsigned NOT NULL default '0',
			`item_name` varchar(96) NOT NULL default '',
			`item_parent` varchar(64) NOT NULL default '',
			`item_slot` varchar(32) NOT NULL default '',
			`item_color` varchar(16) NOT NULL default '',
			`item_id` varchar(64) default NULL,
			`item_texture` varchar(64) NOT NULL default '',
			`item_quantity` int(11) default NULL,
			`item_tooltip` mediumtext NOT NULL,
			`level` int(11) default NULL,
			`item_level` int(11) default NULL,
			`locale` varchar(4) default NULL,
			PRIMARY KEY  (`guild_id`,`item_parent`,`item_slot`),
			KEY `parent` (`item_parent`),
			KEY `slot` (`item_slot`),
			KEY `name` (`item_name`)");

		$installer->add_menu_button('vault_menu','guild');
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
		global $installer;

		if( version_compare('1.9.9.1487', $oldversion,'>') == true )
		{
			$installer->add_config("'1','startpage','vault_conf','display','master'");
			$installer->add_config("'100','vault_conf',NULL,'blockframe','menu'");
			$installer->add_config("'1000', 'tab1', '1', 'access', 'vault_conf'");
			$installer->add_config("'1010', 'tab2', '1', 'access', 'vault_conf'");
			$installer->add_config("'1020', 'tab3', '1', 'access', 'vault_conf'");
			$installer->add_config("'1030', 'tab4', '1', 'access', 'vault_conf'");
			$installer->add_config("'1040', 'tab5', '1', 'access', 'vault_conf'");
			$installer->add_config("'1050', 'money', '1', 'access', 'vault_conf'");
		}

		if( version_compare('1.9.9.1492', $oldversion,'>') == true )
		{
			$installer->add_config("'1045', 'tab6', '1', 'access', 'vault_conf'");
		}
		if( version_compare('2.1.2415', $oldversion,'>') == true )
		{
			$installer->add_config("'1046', 'tab7', '0:13', 'access', 'vault_conf'");
			$installer->add_config("'1047', 'tab8', '0:13', 'access', 'vault_conf'");
		}
		return true;
	}

	/**
	 * Un-Install function
	 *
	 * @return bool
	 */
	function uninstall()
	{
		global $installer;

		$installer->remove_all_config();
		$installer->remove_all_menu_button();

		$installer->drop_table($installer->table('log'));
		$installer->drop_table($installer->table('money'));
		$installer->drop_table($installer->table('items'));

		return true;
	}
}
