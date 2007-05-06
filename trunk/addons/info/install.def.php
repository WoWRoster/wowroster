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
*/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

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


	function install()
	{
		global $installer;

		// Master and menu entries
		$installer->add_config("'1','startpage','char_conf','display','master'");
		$installer->add_config("'110','char_conf',NULL,'blockframe','menu'");
		$installer->add_config("'120','char_pref','rostercp-addon-info-display','makelink','menu'");

		$installer->add_config("'1000', 'char_bodyalign', 'center', 'select{left^left|center^center|right^right', 'char_conf'");
		$installer->add_config("'1010', 'recipe_disp', '0', 'radio{show^1|collapse^0', 'char_conf'");
		$installer->add_config("'1020', 'show_talents', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1030', 'show_spellbook', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1040', 'show_mail', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1050', 'show_inventory', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1060', 'show_money', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1070', 'show_bank', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1080', 'show_recipes', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1090', 'show_quests', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1100', 'show_bg', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1110', 'show_pvp', '2', 'radio{on^1|off^0|user^2', 'char_conf'");
		$installer->add_config("'1120', 'show_duels', '2', 'radio{on^1|off^0|user^2', 'char_conf'");

		return true;
	}

	function upgrade($oldbasename, $oldversion)
	{
		// Nothing to upgrade from yet
		return false;
	}

	function uninstall()
	{
		global $installer;

		$installer->remove_all_config();

		return true;
	}
}
