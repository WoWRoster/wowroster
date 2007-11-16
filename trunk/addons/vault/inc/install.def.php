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
class vault
{
	var $active = true;
	var $icon = 'inv_misc_ornatebox';

	var $version = '1.9.9.1431';
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
/*
		// Master and menu entries
		$installer->add_config("'1','startpage','guildbank_conf','display','master'");
		$installer->add_config("'110','guildbank_conf',NULL,'blockframe','menu'");
		$installer->add_config("'1000', 'guildbank_ver', '1', 'select{Table^1|Inventory^2', 'guildbank_conf'");
		$installer->add_config("'1100', 'bank_money', '1', 'radio{yes^1|no^0', 'guildbank_conf'");
		$installer->add_config("'1200', 'banker_rankname', 'BankMule', 'text{50|30', 'guildbank_conf'");
		$installer->add_config("'1300', 'banker_fieldname', 'note', 'select{Player Note^note|Officer Note^officer_note|Guild Rank Number^guild_rank|Guild Title^guild_title|Player Name^name', 'guildbank_conf'");
*/
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
		// Nothing to upgrade from yet
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

		return true;
	}
}
