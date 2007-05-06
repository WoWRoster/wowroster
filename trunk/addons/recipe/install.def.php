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

class recipe
{
	var $active = true;
	var $icon = 'inv_misc_food_65';

	var $upgrades = array(); // There are no previous versions to upgrade from

	var $version = '1.0.0.0';

	var $fullname = 'Made By';
	var $description = 'Lists who can make what in roster';
	var $credits = array(
	array(	"name"=>	"Cybrey",
			"info"=>	"Original author"),
	array(	"name"=>	"Thorus",
			"info"=>	"Thanks for the mod of this script"),
);


	function install()
	{
		global $installer;

		// Master and menu entries
		$installer->add_config("'1','startpage','recipe_conf','display','master'");
		$installer->add_config("'110','recipe_conf',NULL,'blockframe','menu'");
		$installer->add_config("'1000','display_icon','1','radio{on^1|off^0','recipe_conf'");
		$installer->add_config("'1010','display_name','1','radio{on^1|off^0','recipe_conf'");
		$installer->add_config("'1020','display_level','1','radio{on^1|off^0','recipe_conf'");
		$installer->add_config("'1030','display_tooltip','0','radio{on^1|off^0','recipe_conf'");
		$installer->add_config("'1040','display_type','0','radio{on^1|off^0','recipe_conf'");
		$installer->add_config("'1050','display_reagents','1','radio{on^1|off^0','recipe_conf'");
		$installer->add_config("'1060','display_makers','1','radio{on^1|off^0','recipe_conf'");
		$installer->add_config("'1070','display_makers_count','3','text{2|10','recipe_conf'");

		$installer->add_menu_button('madeby','util','');
		return true;
	}

	function upgrade($oldversion)
	{
		// Nothing to upgrade from yet
		return false;
	}

	function uninstall()
	{
		global $installer;

		$installer->remove_all_config();

		$installer->remove_menu_button('madeby');
		return true;
	}
}
