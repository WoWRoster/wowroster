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
 * @package    CharacterInfo
 * @subpackage Installer
*/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Installer for Character Info Addon
 * @package    CharacterInfo
 * @subpackage Installer
 */
class info
{
	var $active = true;
	var $icon = 'inv_misc_grouplooking';

	var $upgrades = array(); // There are no previous versions to upgrade from

	var $version = '1.8.0.0';

	var $fullname = 'Character Information';
	var $description = 'Displays info about characters uploaded to Roster';
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
		global $installer, $roster;

		// Master and menu entries
		$installer->add_config("'1','startpage','char_conf','display','master'");
		$installer->add_config("'110','char_conf',NULL,'blockframe','menu'");
		$installer->add_config("'120','char_pref','rostercp-addon-info-display','makelink','menu'");

		$installer->add_config("'1000', 'char_bodyalign', 'center', 'select{left^left|center^center|right^right', 'char_conf'");
		$installer->add_config("'1010', 'recipe_disp', '0', 'radio{show^1|collapse^0', 'char_conf'");
		$installer->add_config("'1020', 'show_money', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1030', 'show_tab2', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1040', 'show_tab3', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1050', 'show_tab4', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1060', 'show_tab5', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1070', 'show_talents', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1080', 'show_spellbook', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1190', 'show_mail', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1100', 'show_bags', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1110', 'show_bank', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1120', 'show_quests', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1130', 'show_recipes', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1140', 'show_item_bonuses', '2', 'radio{on^1|off^0|user^2', 'char_conf'");

		$installer->add_backup($roster->db->table('players'));

		$installer->add_query("ALTER TABLE `" . $roster->db->table('players') . "`
		  ADD `show_money` TINYINT(1) NOT NULL default '3',
		  ADD `show_tab2` TINYINT(1) NOT NULL default '3',
		  ADD `show_tab3` TINYINT(1) NOT NULL default '3',
		  ADD `show_tab4` TINYINT(1) NOT NULL default '3',
		  ADD `show_tab5` TINYINT(1) NOT NULL default '3',
		  ADD `show_talents` TINYINT(1) NOT NULL default '3',
		  ADD `show_spellbook` TINYINT(1) NOT NULL default '3',
		  ADD `show_mail` TINYINT(1) NOT NULL default '3',
		  ADD `show_bags` TINYINT(1) NOT NULL default '3',
		  ADD `show_bank` TINYINT(1) NOT NULL default '3',
		  ADD `show_quests` TINYINT(1) NOT NULL default '3',
		  ADD `show_recipes` TINYINT(1) NOT NULL default '3',
		  ADD `show_item_bonuses` TINYINT(1) NOT NULL default '3';");

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
		global $installer, $roster;

		$installer->remove_all_config();

		$installer->add_backup($roster->db->table('players'));

		$installer->add_query("ALTER TABLE `" . $roster->db->table('players') . "`
		  DROP `show_money`,
		  DROP `show_tab2`,
		  DROP `show_tab3`,
		  DROP `show_tab4`,
		  DROP `show_tab5`,
		  DROP `show_talents`,
		  DROP `show_spellbook`,
		  DROP `show_mail`,
		  DROP `show_bags`,
		  DROP `show_bank`,
		  DROP `show_quests`,
		  DROP `show_recipes`,
		  DROP `show_item_bonuses`;");

		return true;
	}
}
