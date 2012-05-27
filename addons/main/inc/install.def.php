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
class mainInstall
{
	var $active = true;
	var $icon = 'ability_warrior_rallyingcry';

	var $version = '0.2.2';
	var $wrnet_id = '0';

	var $fullname = 'CMS News Page';
	var $description = 'Cms overlay cms';
	var $credits = array(
		array(	"name"=>	"Ulminia",
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

		$installer->add_config("'8','startpage','cmsmain_conf','display','master'");
		$installer->add_config("'810','cmsmain_conf',NULL,'blockframe','menu'");
		$installer->add_config("'811','cmsmain_banner','rostercp-addon-main-banners','makelink','menu'");
		$installer->add_config("'812','cmsmain_banneradd','rostercp-addon-main-banneradd','makelink','menu'");
		$installer->add_config("'8000','news_add','2','access','cmsmain_conf'");
		$installer->add_config("'8010','news_edit','2','access','cmsmain_conf'");
		$installer->add_config("'8020','comm_add','0','access','cmsmain_conf'");
		$installer->add_config("'8030','comm_edit','2','access','cmsmain_conf'");
		$installer->add_config("'8040','news_html','1','radio{enabled^1|disabled^0|forbidden^-1','cmsmain_conf'");
		$installer->add_config("'8050','comm_html','-1','radio{enabled^1|disabled^0|forbidden^-1','cmsmain_conf'");
		$installer->add_config("'8060','news_nicedit','1','radio{enabled^1|disabled^0', 'cmsmain_conf'");

		$installer->create_table($installer->table('config'),"
                        `guild_id` int(11) unsigned NOT NULL DEFAULT '0',
                        `config_name` varchar(64) NOT NULL DEFAULT '',
                        `config_value` varchar(225) NOT NULL DEFAULT '',
                        PRIMARY KEY (`guild_id`,`config_name`)");

		$installer->create_table($installer->table('blocks'),"
						`guild_id` int(11) unsigned NOT NULL DEFAULT '0',
						`block_name` varchar(64) NOT NULL DEFAULT '',
						`block_location` varchar(10) NOT NULL DEFAULT '',
						PRIMARY KEY (`guild_id`,`block_name`)");
		$installer->create_table($installer->table('banners'),"
						`id` int(5) NOT NULL AUTO_INCREMENT,
						`b_id` varchar(10) DEFAULT NULL,
						`b_image` varchar(255) DEFAULT NULL,
						`b_desc` varchar(150) DEFAULT NULL,
						`b_url` varchar(255) NOT NULL DEFAULT '#',
						`b_title` varchar(255) DEFAULT NULL,
						`b_active` int(10) DEFAULT NULL,
						PRIMARY KEY (`id`)");
		$installer->create_table($installer->table('news'),"
						`news_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
						`name` varchar(255) DEFAULT NULL,
						`title` varchar(200) DEFAULT NULL,
						`text` longtext,
						`news_type` varchar(25) DEFAULT NULL,
						`comm_count` int(11) unsigned NOT NULL,
						`poster` varchar(100) DEFAULT NULL,
						`date` datetime DEFAULT NULL,
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

		$installer->add_menu_button('cms_button','guild');
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

		if( version_compare('0.2', $oldversion, '>') == true )
		{
			$installer->add_config("'8','startpage','cmsmain_conf','display','master'");
			$installer->add_config("'810','cmsmain_conf',NULL,'blockframe','menu'");
			$installer->add_config("'8000','news_add','2','access','cmsmain_conf'");
			$installer->add_config("'8010','news_edit','2','access','cmsmain_conf'");
			$installer->add_config("'8020','comm_add','0','access','cmsmain_conf'");
			$installer->add_config("'8030','comm_edit','2','access','cmsmain_conf'");
			$installer->add_config("'8040','news_html','1','radio{enabled^1|disabled^0|forbidden^-1','cmsmain_conf'");
			$installer->add_config("'8050','comm_html','-1','radio{enabled^1|disabled^0|forbidden^-1','cmsmain_conf'");
			$installer->add_config("'8060','news_nicedit','1','radio{enabled^1|disabled^0', 'cmsmain_conf'");
		}
		if( version_compare('0.2.1', $oldversion, '>') == true )
		{
			$installer->add_config("'811','cmsmain_banner','rostercp-addon-main-banners','makelink','menu'");
			$installer->add_query("
				ALTER TABLE `" . $installer->table('news') . "`
				ADD `html` tinyint(1),
				CHANGE `id` `news_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				DROP PRIMARY KEY,
				ADD PRIMARY KEY (`news_id`);");
		}
		if( version_compare('0.2.2', $oldversion, '>') == true )
		{
			$installer->add_config("'812','cmsmain_banneradd','rostercp-addon-main-banneradd','makelink','menu'");
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
		$installer->drop_table($installer->table('config'));
		$installer->drop_table($installer->table('blocks'));
		$installer->drop_table($installer->table('banners'));
		$installer->drop_table($installer->table('news'));
		$installer->remove_all_config();

		$installer->remove_all_menu_button();
		return true;
	}
}
