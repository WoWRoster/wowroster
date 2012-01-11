<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    News
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * News Addon Installer
 * @package News
 * @subpackage Installer
 */
class newsInstall
{
	var $active = true;
	var $icon = 'ability_warrior_rallyingcry';

	var $version = '2.1.0';
	var $wrnet_id = '0';

	var $fullname = 'news';
	var $description = 'news_desc';
	var $credits = array(
		array(	"name"=>	"WoWRoster Dev Team",
				"info"=>	"Original author")
	);


	/**
	 * Install function
	 *
	 * @return bool
	 */
	function install()
	{
		global $installer;

		// Master and menu entries
		$installer->add_config("'1','startpage','news_conf','display','master'");
		$installer->add_config("'110','news_conf',NULL,'blockframe','menu'");
		$installer->add_config("'1000','news_add','2','access','news_conf'");
		$installer->add_config("'1010','news_edit','2','access','news_conf'");
		$installer->add_config("'1020','comm_add','0','access','news_conf'");
		$installer->add_config("'1030','comm_edit','2','access','news_conf'");
		$installer->add_config("'1040','news_html','1','radio{enabled^1|disabled^0|forbidden^-1','news_conf'");
		$installer->add_config("'1050','comm_html','-1','radio{enabled^1|disabled^0|forbidden^-1','news_conf'");
		$installer->add_config("'1060','news_nicedit','1','radio{enabled^1|disabled^0', 'news_conf'");

		$installer->create_table($installer->table('news'),"
				`news_id` int(11) unsigned AUTO_INCREMENT,
				`author` varchar(16) NOT NULL DEFAULT '',
				`date` datetime,
				`title` mediumtext,
				`content` longtext,
				`html` tinyint(1),
				PRIMARY KEY (`news_id`)");

		$installer->create_table($installer->table('comments'),"
				`comment_id` int(11) unsigned AUTO_INCREMENT,
				`news_id` int(11) unsigned NOT NULL,
				`author` varchar(16) NOT NULL DEFAULT '',
				`date` datetime,
				`content` longtext,
				`html` tinyint(1),
				PRIMARY KEY (`comment_id`)");

		$installer->add_menu_button('news_button','util');
		return true;
	}

	/**
	 * Upgrade functoin
	 *
	 * @param string $oldversion
	 * @return bool
	 */
	function upgrade($oldversion)
	{
		global $installer;

		if( version_compare('1.9.9.1668', $oldversion, '>') == true )
		{
			$installer->add_config("'1060','news_nicedit','1','radio{enabled^1|disabled^0', 'news_conf'");
		}
		return true;
	}

	/**
	 * Un-Install function
	 *
	 * @return bool
	 */
	function uninstall()
	{
		global $installer;

		$installer->drop_table($installer->table('news'));
		$installer->drop_table($installer->table('comments'));

		$installer->remove_all_config();
		$installer->remove_all_menu_button();

		return true;
	}
}
