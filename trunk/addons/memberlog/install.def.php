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

class memberlog
{
	var $active = true;
	var $icon = 'inv_misc_book_06';

	var $upgrades = array(); // There are no previous versions to upgrade from

	var $version = '1.0.0';

	var $fullname = 'MemberLog';
	var $description = 'Shows a log of added and removed members';
	var $credits = array(
	array(	"name"=>	"vgjunkie",
			"info"=>	"Original Author")
	);


	function install()
	{
		global $installer;

		// Master and menu entries
		$installer->add_config("'1','startpage','memberlog_conf','display','master'");
		$installer->add_config("'110','memberlog_conf',NULL,'blockframe','menu'");
/*
		$installer->add_config("'1010','show_class','0','radio{No^0|Yes^1','memberlog_conf'");
		$installer->add_config("'1020','show_level','0','radio{No^0|Yes^1','memberlog_conf'");
		$installer->add_config("'1030','show_guild_title','0','radio{No^0|Yes^1','memberlog_conf'");
		$installer->add_config("'1040','show_type','0','radio{No^0|Yes^1','memberlog_conf'");
		$installer->add_config("'1050','show_date','0','radio{No^0|Yes^1','memberlog_conf'");
*/
		$installer->add_config("'1060','show_note','0','radio{No^0|Yes^1','memberlog_conf'");
		$installer->add_config("'1070','show_officer_note','0','radio{No^0|Yes^1','memberlog_conf'");

		$installer->add_menu_button('memberlog','guild','');
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

		$installer->remove_menu_button('memberlog');
		return true;
	}
}
