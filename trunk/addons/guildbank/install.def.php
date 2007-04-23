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

class guildbank
{
	var $active = true;
	var $icon = 'inv_misc_bag_15';

	var $upgrades = array(); // There are no previous versions to upgrade from

	var $version = '1.0.0';

	var $fullname = 'Guild Bank';
	var $description = 'Default Guild Bank display';
	var $credits = array(
	array(	"name"=>	"vaccafoeda.hellscream@gmail.com",
			"info"=>	"Original author")
	);


	function install()
	{
		global $installer;

		// First we backup the config table to prevent damage
		$installer->add_backup(ROSTER_ADDONCONFTABLE);

		// Master and menu entries
		$installer->add_config("'1','startpage','guildbank_conf','display','master'");
		$installer->add_config("'110','guildbank_conf',NULL,'blockframe','menu'");
		$installer->add_config("'1000', 'guildbank_ver', '', 'select{Table^|Inventory^2', 'guildbank_conf'");
		$installer->add_config("'1100', 'bank_money', '1', 'radio{yes^1|no^0', 'guildbank_conf'");
		$installer->add_config("'1200', 'banker_rankname', 'BankMule', 'text{50|30', 'guildbank_conf'");
		$installer->add_config("'1300', 'banker_fieldname', 'note', 'select{Player Note^note|Officer Note^officer_note|Guild Rank Number^guild_rank|Guild Title^guild_title|Player Name^name', 'guildbank_conf'");

		$installer->add_menu_button('guildbank');
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

		$installer->remove_menu_button('guildbank');
		return true;
	}
}
