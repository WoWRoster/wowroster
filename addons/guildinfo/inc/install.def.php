<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    GuildInfo
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Installer for GuildInfo Addon
 * @package    GuildInfo
 * @subpackage Installer
 */
class guildinfoInstall
{
	var $active = true;
	var $icon = 'inv_misc_note_06';

	var $version = '1.9.9.1758';
	var $wrnet_id = '0';

	var $fullname = 'guildinfo';
	var $description = 'guildinfo_desc';
	var $credits = array(
		array(	"name"=>	"WoWRoster Dev Team",
				"info"=>	"Original Author")
	);


	/**
	 * Install Function
	 *
	 * @return bool
	 */
	function install()
	{
		global $installer;

		
		$installer->create_table($installer->table('log'),"
			`log_id` int(11) unsigned NOT NULL default '0',
			`guild_id` int(11) unsigned NOT NULL default '0',
			`member` varchar(96) NOT NULL default '',
			`parent` varchar(64) NOT NULL default '',
			`type` varchar(32) NOT NULL default '0',
			`time` datetime default NULL,
			`amount` varchar(32) NOT NULL default '',
			`count` int(11) default NULL,
			`item_id` varchar(64) default NULL,
			KEY  (`log_id`),
			KEY `type` (`type`),
			KEY `name` (`member`)");
			
		$installer->create_table($installer->table('log'),"
			`log_id` int(11) unsigned NOT NULL default '0',
			`guild_id` int(11) unsigned NOT NULL default '0',
			`member` varchar(96) NOT NULL default '',
			`parent` varchar(64) NOT NULL default '',
			`type` varchar(32) NOT NULL default '0',
			`time` datetime default NULL,
			`amount` varchar(32) NOT NULL default '',
			`count` int(11) default NULL,
			`item_id` varchar(64) default NULL,
			KEY  (`log_id`),
			KEY `type` (`type`),
			KEY `name` (`member`)");
		
		$installer->add_menu_button('ginfobutton','guild');
		return true;
	}

	/**
	 * Upgrade Function
	 *
	 * @param string $oldversion
	 * @return bool
	 */
	function upgrade($oldversion)
	{
		global $installer;

		if( version_compare( $oldversion, '1.9.9.1562', '<' ) )
		{
			$installer->add_config("'1','startpage','guildinfo_conf','display','master'");
			$installer->add_config("'100','guildinfo_conf',NULL,'blockframe','menu'");
			$installer->add_config("'1000', 'guildinfo_access', '0', 'access', 'guildinfo_conf'");
		}

		if( version_compare( $oldversion, '1.9.9.1758', '<' ) )
		{
			$installer->remove_all_config();
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
		global $installer;

		$installer->remove_all_menu_button();
		return true;
	}
}
