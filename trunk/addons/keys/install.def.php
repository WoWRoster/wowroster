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
 * $Id: install.def.php 769 2007-04-05 08:13:18Z Zanix $
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

class keys
{
	var $active = true;
	var $icon = 'inv_misc_key_06';

	var $upgrades = array(); // There are no previous versions to upgrade from

	var $version = '1.0.0';
	
	var $fullname = 'Keys';
	var $description = 'Lists Dungeon keys for members';
	var $credits = array(
	array(	"name"=>	"Unknown",
			"info"=>	"Original author")
	);


	function install()
	{
		global $installer;

		// First we backup the config table to prevent damage
		$installer->add_backup(ROSTER_ADDONCONFTABLE);

		// Master and menu entries
		$installer->add_config("'1','startpage','keys_conf','display','master'");
		$installer->add_config("'110','keys_conf',NULL,'blockframe','menu'");
		$installer->add_config("'1000','display_icon','1','radio{on^1|off^0','keys_conf'");

		$installer->add_menu_button('keys');
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

		$installer->remove_menu_button('keys');
		return true;
	}
}
