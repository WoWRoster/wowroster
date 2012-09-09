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

	var $version = '2.2.0';

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

		$installer->create_table($installer->table('achie'),"
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `achie_name` text,
			  `achie_desc` text,
			  `achie_points` int(10) DEFAULT NULL,
			  `achie_id` int(10) NOT NULL,
			  `achie_icon` varchar(255) DEFAULT NULL,
			  `achie_tooltip` mediumtext ,
			  `achie_isAccount` varchar(10) ,
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

		//*
		$installer->create_table($installer->table('criteria'),"
			  `member_id` int(11) NOT NULL DEFAULT '0',
			  `crit_id` int(7) NOT NULL DEFAULT '0',
			  `crit_date` varchar(20) DEFAULT NULL,
			  `crit_value` varchar(64) DEFAULT NULL");
		//*/
		$installer->add_config("'11000','startpage','achi_conf','display','master'");
		$installer->add_config("'11001','achi_conf',NULL,'blockframe','menu'");
		$installer->add_config("'11002','achi_data','rostercp-addon-achievements-data','makelink','menu'");
		$installer->add_config("'11003','show_icons','1','radio{enabled^1|disabled^0','achi_conf'");
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
		if( version_compare('2.2.0', $oldversion, '>') == true )
		{
			$installer->add_query("ALTER TABLE `" . $installer->table('achie') . "`
				ADD `achie_isAccount` varchar(10) NOT NULL default '0' AFTER `achie_tooltip`");
		}
		
		if( version_compare('2.1.2555', $oldversion, '>') == true )
		{
			$installer->add_config("'11000','startpage','achi_conf','display','master'");
		$installer->add_config("'11001','achi_conf',NULL,'blockframe','menu'");
		$installer->add_config("'11002','achi_data','rostercp-addon-achievements-data','makelink','menu'");
		$installer->add_config("'11003','show_icons','1','radio{enabled^1|disabled^0','achi_conf'");
			
			$installer->create_table($installer->table('crit'),"
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `crit_achie_id` int(10) NOT NULL,
			  `crit_id` int(10) NOT NULL,
			  `crit_desc` text,
			  PRIMARY KEY (`id`)");
			
			$installer->create_table($installer->table('criteria'),"
			  `member_id` int(11) NOT NULL DEFAULT '0',
			  `crit_id` int(7) NOT NULL DEFAULT '0',
			  `crit_date` varchar(20) DEFAULT NULL,
			  `crit_value` varchar(64) DEFAULT NULL");
			  
		}
		
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
