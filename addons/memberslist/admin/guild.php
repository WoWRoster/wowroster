<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: update.php 1287 2007-08-26 12:23:15Z pleegwat $
 * @link       http://www.wowroster.net
 * @package    MembersList
 */

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

// Change scope to guild, and rerun detection to load default
$roster->scope = 'guild';
$roster->get_scope_data();

// Double check there's config data. If there isn't, copy it in from the global table.
$query = "SELECT COUNT(*) "
	. "FROM `" . $roster->db->table('config_guild',$addon['basename']) . "` "
	. "WHERE `guild_id` = '" . $roster->data['guild_id'] . "';";

if( $roster->db->query_first($query) == 0 )
{
	// Copy in from master table
	$query = "INSERT INTO `" . $roster->db->table('config_guild',$addon['basename']) . "` "
		. "(`guild_id`,`id`,`config_name`,`config_value`,`form_type`,`config_type`) "
		. "SELECT '" . $roster->data['guild_id'] . "',`id`,`config_name`,`config_value`,`form_type`,`config_type` "
		. "FROM `" . $roster->db->table('addon_config') . "` "
		. "WHERE `addon_id` = '" . $addon['addon_id'] . "' "
		// `id` < 100 for the metadata the config class needs. The rest is the actual settings for the tab we're
		// interested in. If we ever start using advanced formatting I need to think up a better way to specify this.
		. "AND (`id` < 100 OR `config_name` = 'build' OR `config_type` = 'build');";

	$roster->db->query($query);

	// And from ID 0
	$query = "INSERT INTO `" . $roster->db->table('config_guild',$addon['basename']) . "` "
		. "(`guild_id`,`id`,`config_name`,`config_value`,`form_type`,`config_type`) "
		. "SELECT '" . $roster->data['guild_id'] . "',`id`,`config_name`,`config_value`,`form_type`,`config_type` "
		. "FROM `" . $roster->db->table('config_guild',$addon['basename']) . "` "
		. "WHERE `guild_id` = '0';";
	
	$roster->db->query($query);
}

// ----[ Set the tablename and create the config class ]----
$tablename = $roster->db->table('config_guild',$addon['basename']);
include(ROSTER_LIB.'config.lib.php');

// ----[ Get configuration data ]---------------------------
$config->getConfigData('`guild_id` = "' . $roster->data['guild_id'] . '"');

// ----[ Process data if available ]------------------------
$save_message = $config->processData($addon['config'], '`guild_id` = "' . $roster->data['guild_id'] . '"');

// ----[ Build the page items using lib functions ]---------
$menu = $config->buildConfigMenu();

$config->buildConfigPage();

$body .= messagebox('These are the guild-specific rules for "' . $roster->data['guild_name'] . '@' . $roster->data['region'] . '-' . $roster->data['server'] . '". These override any settings in the generic main/alt rule section.')
	. "<br />\n"
	. $config->form_start
	. $save_message
	. $config->formpages
	. $config->submit_button
	. $config->form_end
	. $config->nonformpages
	. $config->jscript;
