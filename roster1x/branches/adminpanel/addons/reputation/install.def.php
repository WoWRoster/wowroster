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

class reputation
{
	var $active = true;
	var $hastriggers = false;
	var $hasconfig = 'default';
	
	var $upgrades = array(); // There are no previous versions to upgrade from
	
	var $version = 1.0.0;
	
	function install()
	{
		global $installer;
		// Since Reputation does not have options it only installs the menu button
		$installer->add_menu_button('reputation_addon','',1);
		return true;
	}
	
	function upgrade($oldbasename, $oldversion)
	{
		// Nothing to upgrade from yet
		return false;
	}
	
	function uninstall()
	{
		// Since Reputation does not have options it doesn't need to uninstall anything
		$installer->remove_menu_button('reputation_addon');
		return true;
	}
}
