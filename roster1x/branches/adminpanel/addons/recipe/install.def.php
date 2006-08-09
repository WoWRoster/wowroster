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

class recipe
{
	var $active = true;
	var $hastriggers = false;
	var $hasconfig = 'default';
	
	var $upgrades = array(); // There are no previous versions to upgrade from
	
	var $version = 1.0.0;
	
	function install()
	{
		global installer;
		$installer->profile = 'default';
		
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
		$installer->add_query('INSERT','config',"1,'startpage','recipe_conf','display','master'");
		$installer->add_query('INSERT','config',"110,'recipe_conf',NULL,'blockframe','menu'");
		
		// Settings
		$installer->add_query('INSERT','config',"1000,'display_icon','1','radio{on^1|off^0','recipe_conf'");
		$installer->add_query('INSERT','config',"1010,'display_name','1','radio{on^1|off^0','recipe_conf'");
		$installer->add_query('INSERT','config',"1020,'display_level','1','radio{on^1|off^0','recipe_conf'");
		$installer->add_query('INSERT','config',"1030,'display_tooltip','0','radio{on^1|off^0','recipe_conf'");
		$installer->add_query('INSERT','config',"1040,'display_type','0','radio{on^1|off^0','recipe_conf'");
		$installer->add_query('INSERT','config',"1050,'display_reagents','1','radio{on^1|off^0','recipe_conf'");
		$installer->add_query('INSERT','config',"1060,'display_makers','1','radio{on^1|off^0','recipe_conf'");
		$installer->add_query('INSERT','config',"1070,'display_makers_count','3','text{2|10','recipe_conf'");
		$installer->add_menu_button('MadeBy','',1);
		return true;
	}
	
	function upgrade($oldbasename, $oldversion)
	{
		// Nothing to upgrade from yet
		return false;
	}
	
	function uninstall()
	{
		$installer->profile = 'default';
		$installer->add_query('BACKUP','config');
		$installer->add_query('DROP','config');
		$installer->remove_menu_button('MadeBy');
		return true;
	}
}
