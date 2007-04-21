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

class guildinfo
{
	var $active = true;
	var $icon = 'inv_misc_key_06';

	var $upgrades = array(); // There are no previous versions to upgrade from

	var $version = '1.0.0';
	
	var $fullname = 'Guild Info';
	var $description = 'Shows /guildinfo';
	var $credits = array(
	array(	"name"=>	"Zeryl/Zanix",
			"info"=>	"Original author")
	);


	function install()
	{
		global $installer;

		// First we backup the config table to prevent damage
		$installer->add_backup(ROSTER_ADDONCONFTABLE);

		// Master and menu entries
		$installer->add_menu_button('guildinfo');
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

		$installer->remove_menu_button('guildinfo');
		return true;
	}
}
