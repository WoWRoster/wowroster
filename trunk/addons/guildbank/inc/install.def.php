<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
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
class guildbankInstall
{
	var $active = true;
	var $icon = 'inv_misc_bag_15';

	var $version = '1.9.9.1758';
	var $wrnet_id = '0';

	var $fullname = 'guildbank';
	var $description = 'guildbank_desc';
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
	 * Upgrade function
	 *
	 * @param string $oldversion
	 * @return bool
	 */
	function upgrade($oldversion)
	{
		global $installer;

		if( version_compare( $oldversion, '1.9.9.1562', '<' ) )
		{
			$installer->add_config("'1400', 'bank_access', '0', 'access', 'guildbank_conf'");
		}

		if( version_compare( $oldversion, '1.9.9.1758', '<' ) )
		{
			$installer->remove_config('1400');
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

		return true;
	}
}
