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
 * @package    GuildBank
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * GuildBank Addon Installer
 * @package GuildBank
 * @subpackage Installer
 */
class guildbank
{
	var $active = true;
	var $icon = 'inv_misc_bag_15';

	var $version = '2.0.0.0';

	var $fullname = 'Guild Bank';
	var $description = 'Shows the inventory of characters marked as the Guild Bank';
	var $credits = array(
		array(	"name"=>	"vaccafoeda.hellscream@gmail.com",
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
		$installer->add_config("'1','startpage','guildbank_conf','display','master'");
		$installer->add_config("'110','guildbank_conf',NULL,'blockframe','menu'");
		$installer->add_config("'1000', 'guildbank_ver', '1', 'select{Table^1|Inventory^2', 'guildbank_conf'");
		$installer->add_config("'1100', 'bank_money', '1', 'radio{yes^1|no^0', 'guildbank_conf'");
		$installer->add_config("'1200', 'banker_rankname', 'BankMule', 'text{50|30', 'guildbank_conf'");
		$installer->add_config("'1300', 'banker_fieldname', 'note', 'select{Player Note^note|Officer Note^officer_note|Guild Rank Number^guild_rank|Guild Title^guild_title|Player Name^name', 'guildbank_conf'");

		$installer->add_menu_button('gbankbutton','guild');
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
