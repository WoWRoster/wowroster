<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

class tradeskills
{
	var $active = true;
	var $icon = 'trade_leatherworking';

	var $upgrades = array(); // There are no previous versions to upgrade from

	var $version = '1.0.0';

	var $fullname = 'Professions';
	var $description = 'Lists tradeskills for everyone in the guild';
	var $credits = array(
	array(	"name"=>	"vgjunkie",
			"info"=>	"Original Author")
	);


	function install()
	{
		global $installer;

		// First we backup the config table to prevent damage
		$installer->add_backup(ROSTER_ADDONCONFTABLE);

		// Master and menu entries
		$installer->add_config("'1','startpage','tradeskill_conf','display','master'");
		$installer->add_config("'110','tradeskill_conf',NULL,'blockframe','menu'");

		$installer->add_config("'1010','show_new_skills','0','radio{No^0|Yes^1','tradeskill_conf'");
		$installer->add_config("'1020','collapse_list','0','radio{Show^0|Hide^1','tradeskill_conf'");

		$installer->add_menu_button('professions');
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

		$installer->remove_menu_button('professions');
		return true;
	}
}
