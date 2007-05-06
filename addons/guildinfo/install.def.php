<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: pvp3.php 897 2007-05-06 00:35:11Z Zanix $
 * @link       http://www.wowroster.net
*/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

class guildinfo
{
	var $active = true;
	var $icon = 'inv_misc_note_06';

	var $upgrades = array(); // There are no previous versions to upgrade from

	var $version = '1.8.0.0';

	var $fullname = 'Guild Info';
	var $description = 'Shows /guildinfo';
	var $credits = array(
	array(	"name"=>	"WoWRoster Dev Team",
			"info"=>	"Original Author")
	);


	function install()
	{
		global $installer;

		$installer->add_menu_button('guildinfo','guild','');
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

		$installer->remove_menu_button('guildinfo');
		return true;
	}
}
