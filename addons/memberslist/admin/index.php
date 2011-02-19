<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
 */

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

// ----[ Delete per-guild config for guilds that aren't in the DB anymore ]--
$query = "DELETE `".$roster->db->table('config_guild',$addon['basename'])."` ".
	"FROM `".$roster->db->table('config_guild',$addon['basename'])."` ".
	"LEFT JOIN `".$roster->db->table('guild')."` USING (`guild_id`) ".
	"WHERE `".$roster->db->table('guild')."`.`guild_id` IS NULL ".
	"AND `".$roster->db->table('config_guild',$addon['basename'])."`.`guild_id` != 0;";

$roster->db->query($query);

// ----[ Add per-guild config where it doesn't exist yet ]--
$guilds = array();
$query = "SELECT `guild`.`guild_id`, `guild_name`, `region`, `server`, IF( `config_guild`.`guild_id` IS NULL, 1, 0 ) fetch_config "
	. "FROM `" . $roster->db->table('guild') . "` guild "
	. "LEFT JOIN `" . $roster->db->table('config_guild',$addon['basename']) . "` config_guild USING (`guild_id`) "
	. "ORDER BY `region`, `server`, `guild_name`;";

$result = $roster->db->query($query);

while( $row = $roster->db->fetch( $result ) )
{
	$guilds[$row['guild_id']] = $row['guild_name'] . ' @ ' . $row['region'] . '-' . $row['server'];

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

		// And from ID 0
		$query = "INSERT INTO `" . $roster->db->table('config_guild',$addon['basename']) . "` "
			. "(`guild_id`,`id`,`config_name`,`config_value`,`form_type`,`config_type`) "
			. "SELECT '" . $row['guild_id'] . "', "
			. "`id`, "
			. "IF(`config_name`='build','guild_" . $row['guild_id'] . "',`config_name`) AS config_name, "
			. "`config_value`, "
			. "`form_type`, "
			. "IF(`config_type`='build','guild_" . $row['guild_id'] . "',`config_type`) AS config_type "
			. "FROM `" . $roster->db->table('config_guild',$addon['basename']) . "` "
			. "WHERE `guild_id` = '0';";

		$roster->db->query($query);
	}
}

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
$config['master']->processData($addon['config']);

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

	$config[$guild_id]->db_values['menu']['guild_' . $guild_id]['description'] = $guild_name;
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

foreach( $config as $id => $conf_obj )
{
	$config[$id]->buildConfigPage();
}

$body .= $config['master']->form_start;
foreach( $config as $id => $conf_obj )
{
	$body .= $config[$id]->formpages;
}
$body .= $config['master']->submit_button
	. $config['master']->form_end
	. $config['master']->nonformpages
	. $config['master']->jscript;
