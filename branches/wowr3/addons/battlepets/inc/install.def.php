<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    GuildInfo
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Installer for GuildInfo Addon
 * @package    GuildInfo
 * @subpackage Installer
 */
class battlepetsInstall
{
	var $active = true;
	var $icon = 'inv_pet_diseasedsquirrel';

	var $version = '1.0';
	var $wrnet_id = '0';

	var $fullname = 'bpets';
	var $description = 'bpets_desc';
	var $credits = array(
		array(	"name"=>	"Ulminia",
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
		
		//$installer->add_config("7550,'startpage','display','display','master'");

		# Config menu entries
		//$installer->add_config("7551,'display',NULL,'blockframe','menu'");
		//$installer->add_config("7552,'page_size','0','text{4|30','display'");
		
		$installer->create_table($installer->table('pets'),"
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`member_id` int(11) unsigned NOT NULL default '0',
			`CName` varchar(96) NOT NULL default '',
			`Level` int(11) NOT NULL default '1',
			`XP` int(11) NULL default '0',
			`MaxXp` int(11) NULL default '0',
			`Name` varchar(96) NULL default '',
			`Texture` varchar(96) NULL default '',
			`Tooltip` varchar(255) NULL default '',
			`Health` varchar(96) NULL default '',
			`MaxHealth` varchar(96) NULL default '',
			`Power` varchar(96) NULL default '',
			`Speed` varchar(96) NULL default '',
			`Rarity` varchar(96) NULL default '',
			`Type` int(11) NULL default '0',
			`CreatureID` int(11) NULL default '0',
			`SText` mediumtext NULL,
			`Description` mediumtext,
			`Species` int(11) NULL default '0',
			`IsWild` int(11) NULL default '0',
			KEY `id` (`id`)");

			$installer->create_table($installer->table('pets_spells'),"
			`bpet_id` int(11) unsigned NOT NULL default '0',
			`member_id` int(11) unsigned NOT NULL default '0',
			`spell_level` tinyint(2) NOT NULL default '0',
			`spell_id` int(11) unsigned NOT NULL default '0',
			`spell_cd` int(11) unsigned NOT NULL default '0',
			`spell_turns` int(11) unsigned NOT NULL default '0',
			`spell_name` varchar(64) NULL default '',
			`spell_strong` varchar(50) NULL default '',
			`spell_weak` varchar(50) NULL default '',
			`spell_texture` varchar(64) NULL default '',
			`spell_tooltip` mediumtext NULL,
			PRIMARY KEY  (`bpet_id`,`spell_level`)");


		$installer->add_menu_button('cbattlepets','char');

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

		$installer->drop_table($installer->table('pbattle_pets'));
		$installer->drop_table($installer->table('pbattle_pets_spells'));
		$installer->remove_all_config();
		$installer->remove_all_menu_button();
		return true;
	}
}
