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

class SortMember
{
	var $active = true;
	var $icon = 'inv_letter_06';

	var $upgrades = array(); // There are no previous versions to upgrade from

	var $version = '0.3.0';

	var $fullname = 'SortMembers';
	var $description = 'A sortable, filterable memberslist.';
	var $credits = array(
		array(	"name"=>	"PleegWat",
				"info"=>	"Sortable/filterable memberslist"),
	);


	function install()
	{
		global $installer, $wowdb;

		// First we backup the config table to prevent damage
		$installer->add_backup(ROSTER_ADDONCONFTABLE);

		# Master data for the config file
		$installer->add_config("1,'config_list','display','display','master'");
		$installer->add_config("2,'startpage','display','display','master'");

		# Config menu entries
		$installer->add_config("110,'display',NULL,'blockframe','menu'");
		$installer->add_config("120,'documentation','http://www.wowroster.net/wiki/index.php/Roster:Addon:SortMember','link','menu'");

		# setting
		$installer->add_config("1000,'openfilter','0','radio{Show^1|Hide^0','display'");
		$installer->add_config("1010,'nojs','0','radio{Server^1|Client^0','display'");

		# Roster menu entry
		$installer->add_menu_button('SortMember_Members','-memberslist');
		$installer->add_menu_button('SortMember_Stats','-statslist');
		$installer->add_menu_button('SortMember_Honor','-honorlist');
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
		
		$installer->remove_menu_button('SortMember_Members');
		$installer->remove_menu_button('SortMember_Stats');
		$installer->remove_menu_button('SortMember_Honor');
		return true;
	}
}
