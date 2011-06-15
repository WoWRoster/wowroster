<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    Achievements
 * @subpackage Installer
*/

if ( !defined('ROSTER_INSTALLED') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Installer for Achievements Addon
 *
 * @package    Achievements
 * @subpackage Installer
 *
 */
class achievementsInstall
{
	var $active = true;
	var $icon = 'achievement_general';

	var $version = '2.1.0';

	var $fullname = 'Player Achievements';
	var $description = 'Displays Player Achievements';
	var $wrnet_id = '0';
	var $credits = array(
		array(	'name'=>	'Ulminia',
				'info'=>	'Roster/Addon DEV'),
	);



	/**
	 * Install Function
	 *
	 * @return bool
	 */
	function install()
	{
		global $installer;

		# Master data for the config file
		$installer->add_menu_button('achive','char');

		$installer->create_table($installer->table('data'),"
			  `id` int(11) NOT NULL auto_increment,
			  `member_id` int(11) NOT NULL,
			  `guild_id` int(11) NOT NULL,
			  `achv_cat` int(11) NOT NULL,
			  `achv_cat_title` varchar(255) NOT NULL default '',
			  `achv_cat_sub` varchar(255) NOT NULL default '',
			  `achv_cat_sub2` int(10) default NULL,
			  `achv_id` varchar(25) NOT NULL,
			  `achv_points` int(11) NOT NULL,
			  `achv_icon` varchar(255) NOT NULL default '',
			  `achv_title` varchar(255) NOT NULL default '',
			  `achv_reward_title` varchar(255) default NULL,
			  `achv_disc` text NOT NULL,
			  `achv_date` date default NULL,
			  `achv_criteria` text NOT NULL,
			  `achv_progress` varchar(25) NOT NULL,
			  `achv_progress_width` varchar(50) NOT NULL,
			  `achv_complete` varchar(255) NOT NULL default '',
			  PRIMARY KEY  (`id`)");

		return true;
	}

	/**
	 * Upgrade Function
	 *
	 * @param string $oldversion
	 * @return bool
	 */
	function upgrade($oldversion, $version)
	{
		global $installer, $addon;

	// Change the icon for quests
		if( version_compare('1.0.2141', $oldversion,'>') == true )
		{

			//ALTER TABLE `roster_addons_achievements_summary` CHANGE `date_1` `date_1` DATE NOT NULL

			$installer->add_query("ALTER TABLE `" . $installer->table('data') . "` CHANGE `achv_date` `achv_date` DATE NOT NULL,");

			$installer->update_menu_button('cb_quests','char','quests','achievement_quests_completed_06');
		}
	}

	/**
	 * Un-Install Function
	 *
	 * @return bool
	 */
	function uninstall()
	{
		global $installer, $addon, $roster;

		$installer->remove_all_config();
		$installer->remove_all_menu_button();
		$installer->drop_table( $installer->table('data') );

		return true;
	}
}
