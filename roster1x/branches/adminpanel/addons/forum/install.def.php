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

class guildbank
{
	var $active = true;
	var $hasconfig = 'default';

	var $upgrades = array(); // There are no previous versions to upgrade from

	var $version = '0.0.1';

	var $fullname = 'Forum';
	var $description = 'An online discussion group, where participants with common interests can exchange open messages.';
	var $credits = array(
		array(	"name"=>	"Matt Miller",
				"info"=>	"Author")
	);


	function install()
	{
		global $installer;
		$installer->profile = 'default';
/*
		// Config table
		$installer->add_query('CREATE','config','
		  `id` int(11) NOT NULL,
		  `config_name` varchar(255) default NULL,
		  `config_value` tinytext,
		  `form_type` mediumtext,
		  `config_type` varchar(255) default NULL,
		  PRIMARY KEY  (`id`)
		');

		// Master and menu entries
		$installer->add_query('INSERT','config',"1,'startpage','cgb_conf','display','master'");
		$installer->add_query('INSERT','config',"110,'cgb_conf',NULL,'blockframe','menu'");

		// Settings
		$installer->add_query('INSERT','config',"1000,'row_columns','18','text{5|5','cgb_conf'");
		$installer->add_query('INSERT','config',"1010,'color_border','1','radio{on^1|off^0','cgb_conf'");
		$installer->add_query('INSERT','config',"1020,'show_empty','1','radio{on^1|off^0','cgb_conf'");

		$installer->add_menu_button('guildbank','',1);
*/
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
		/*
		$installer->profile = 'default';
		$installer->add_query('BACKUP','config');
		$installer->add_query('DROP','config');

		$installer->remove_menu_button('guildbank');
		*/
		return true;
	}
}
