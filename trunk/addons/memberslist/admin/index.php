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

// ----[ Add per-guild config where it doesn't exist yet ]--
$guilds = array();
$query = "SELECT `guild`.`guild_id`, `guild_name`, IF( `config_guild`.`guild_id` IS NULL, 1, 0 ) fetch_config "
	. "FROM `" . $roster->db->table('guild') . "` guild "
	. "LEFT JOIN `" . $roster->db->table('config_guild',$addon['basename']) . "` config_guild USING (`guild_id`);";

$result = $roster->db->query($query);

while( $row = $roster->db->fetch( $result ) )
{
	$guilds[$row['guild_id']] = $row['guild_name'];

	if( $row['fetch_config'] )
	{
		// Copy in from master table
		$query = "INSERT INTO `" . $roster->db->table('config_guild',$addon['basename']) . "` "
			. "(`guild_id`,`id`,`config_name`,`config_value`,`form_type`,`config_type`) "
			. "SELECT '" . $row['guild_id'] . "', "
			. "`id`, "
			. "IF(`config_name`='build','guild_" . $row['guild_id'] . "',`config_name`) AS config_name, "
			. "`config_value`, "
			. "`form_type`, "
			. "IF(`config_type`='build','guild_" . $row['guild_id'] . "',`config_type`) AS config_type "
			. "FROM `" . $roster->db->table('addon_config') . "` "
			. "WHERE `addon_id` = '" . $addon['addon_id'] . "' "
			// `id` < 100 for the metadata the config class needs. The rest is the actual settings for the tab we're
			// interested in. If we ever start using advanced formatting I need to think up a better way to specify this.
			. "AND (`id` < 100 OR `config_name` = 'build' OR `config_type` = 'build');";

		$roster->db->query($query);
	}
}
asort($guilds);

// ----[ Fetch all of the ordinary config for the guilds ]--
$query = "SELECT `guild_id`, `config_name`, `config_value` "
	. "FROM `" . $roster->db->table('config_guild',$addon['basename']) . "` "
	. "WHERE `guild_id` > 0;";

$result = $roster->db->query($query);

while( $row = $roster->db->fetch( $result ) )
{
	$addon['rules'][$row['guild_id']][$row['config_name']] = $row['config_value'];
}

// ----[ Fetch master config ]------------------------------
include(ROSTER_LIB.'config.lib.php');
$config['master'] = new roster_config( $roster->db->table('addon_config'), '`addon_id` = \'' . $addon['addon_id'] . '\'', 'config_master_' );
$config['master']->getConfigData();
$save_message = $config['master']->processData($addon['config']);

// ----[ Create horizontal menu separator ]-----------------
$config['master']->db_values['menu']['hr'] = array(
	'name' => 'hr',
	'config_type' => 'menu',
	'value' => null,
	'form_type' => 'hr',
	'description' => '',
	'tooltip' => ''
);
// ----[ Create per-guild config ]--------------------------
foreach( $guilds as $guild_id => $guild_name )
{
	$config[$guild_id] = new roster_config( $roster->db->table('config_guild', $addon['basename']), '`guild_id` = \'' . $guild_id . '\'', 'config_guild_' . $guild_id . '_' );

	$config[$guild_id]->getConfigData();
	$config[$guild_id]->processData($addon['rules'][$guild_id]);

	$config['master']->db_values['menu']['guild_' . $guild_id] = array(
		'name' => 'guild_' . $guild_id,
		'config_type' => 'menu',
		'value' => null,
		'form_type' => 'function{dummy',
		'description' => $guild_name,
		'tooltip' => 'Per-guild main/alt detection settings for this guild'
	);
}

// ----[ Build the page items using lib functions ]---------
$menu = $config['master']->buildConfigMenu();

foreach( $config as $conf_obj )
{
	$conf_obj->buildConfigPage();
}

$body .= $config['master']->form_start
	. $save_message;
foreach( $config as $conf_obj )
{
	$body .= $conf_obj->formpages;
}
$body .= $config['master']->submit_button
	. $config['master']->form_end
	. $config['master']->nonformpages
	. $config['master']->jscript;
