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

	var $version = '2.1.2519';

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

		/*
		old achievement table ... i have a better one now ....
		
		$installer->create_table($installer->table('data'),"
			  `achv_id` int(11) NOT NULL,
			  `achv_cat` int(11) NOT NULL,
			  `achv_cat_title` varchar(255) NOT NULL default '',
			  `achv_cat_sub` varchar(255) NOT NULL default '',
			  `achv_cat_sub2` int(10) default NULL,
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
		*/	  
		$installer->create_table($installer->table('achie'),"
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `achie_name` text,
			  `achie_desc` text,
			  `achie_points` int(10) DEFAULT NULL,
			  `achie_id` int(10) NOT NULL,
			  `achie_icon` varchar(255) DEFAULT NULL,
			  `achie_tooltip` mediumtext ,
			  `c_id` int(10) NOT NULL,
			  `p_id` int(10) DEFAULT NULL,
			  `achi_cate` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`)");
			  
		$installer->create_table($installer->table('crit'),"
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `crit_achie_id` int(10) NOT NULL,
			  `crit_id` int(10) NOT NULL,
			  `crit_desc` text,
			  PRIMARY KEY (`id`)");
		/*
		*	these tables hold the players data
		*	this data is then ran agenst the prim data tabels
		*/
		$installer->create_table($installer->table('achievements'),"
			  `member_id` int(11) NOT NULL DEFAULT '0',
			  `achie_id` int(7) NOT NULL DEFAULT '0',
			  `achie_date` varchar(20) DEFAULT NULL");


		$installer->create_table($installer->table('criteria'),"
			  `member_id` int(11) NOT NULL DEFAULT '0',
			  `crit_id` int(7) NOT NULL DEFAULT '0',
			  `crit_date` varchar(20) DEFAULT NULL,
			  `crit_value` varchar(64) DEFAULT NULL");


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

		return true;
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
		//$installer->drop_table( $installer->table('data') );
		$installer->drop_table( $installer->table('achie') );
		$installer->drop_table( $installer->table('crit') );
		$installer->drop_table( $installer->table('achievements') );
		$installer->drop_table( $installer->table('criteria') );

		return true;
	}
}
