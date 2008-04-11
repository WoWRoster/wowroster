<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    InstanceKeys
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Installer Instance Keys Addon
 *
 * @package    InstanceKeys
 * @subpackage Installer
 */
class keysInstall
{
	var $active = true;
	var $icon = 'inv_misc_key_06';

	var $version = '1.9.9.1751';	// ALWAYS NOTE BELOW IN upgrade() WHY THE VERSION NUMBER HAS CHANGED, EVEN WHEN ONLY UPDATING KEY DEFINES
	var $wrnet_id = '0';

	var $fullname = 'keys';
	var $description = 'keys_desc';
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

		// Master and menu entries
		$installer->add_config("'1','startpage','keys_conf','display','master'");
		$installer->add_config("'110','keys_conf',NULL,'blockframe','menu'");
		$installer->add_config("'200','keys_cats','rostercp-addon-keys-categories','makelink','menu'");
		$installer->add_config("'1010','colorcmp','#00ff00','color','keys_conf'");
		$installer->add_config("'1020','colorcur','#ffd700','color','keys_conf'");
		$installer->add_config("'1030','colorno','#ff0000','color','keys_conf'");
		$installer->add_config("'1040','keys_access','0','access','keys_conf'");

		$installer->add_menu_button('keybutton','guild');
		$installer->add_menu_pane('keypane');
		$installer->add_menu_button('keybutton','keypane','guild-keys');

		$installer->create_table($installer->table('keycache'),"
			`member_id` int(11) NOT NULL DEFAULT 0,
			`key_name` varchar(16) NOT NULL DEFAULT '',
			`stage` int(11) NOT NULL DEFAULT 0,
			PRIMARY KEY (`member_id`, `key_name`, `stage`)");

		$installer->create_table($installer->table('keys'),"
			`locale` varchar(4) NOT NULL DEFAULT 'enUS',
			`faction` char(1) NOT NULL DEFAULT '',
			`key_name` varchar(16) NOT NULL DEFAULT '',
			`icon` varchar(64) NOT NULL DEFAULT 'inv_misc_questionmark',
			PRIMARY KEY (`locale`, `faction`, `key_name`)");

		$installer->create_table($installer->table('stages'),"
			`locale` varchar(4) NOT NULL DEFAULT 'enUS',
			`faction` char(1) NOT NULL DEFAULT '',
			`key_name` varchar(16) NOT NULL DEFAULT '',
			`stage` int(11) NOT NULL DEFAULT 0,
			`type` char(2) NOT NULL DEFAULT '',
			`value` varchar(128) NOT NULL DEFAULT '',
			`count` int(11) NOT NULL DEFAULT 0,
			`flow` char(2) NOT NULL DEFAULT '',
			`active` int(1) NOT NULL DEFAULT 0,
			PRIMARY KEY (`locale`, `faction`, `key_name`, `stage`)");

		$installer->create_table($installer->table('category_key'),"
			`category` varchar(16) NOT NULL DEFAULT '',
			`key` varchar(16) NOT NULL DEFAULT '',
			PRIMARY KEY (`category`, `key`)");

		$installer->create_table($installer->table('category'),"
			`category` varchar(16) NOT NULL DEFAULT '',
			PRIMARY KEY (`category`)");

		$this->loadkeys( 'install_' );

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
			$installer->add_config("'1040','keys_access','0','access','keys_conf'");
		}

		if( version_compare( $oldversion, '1.9.9.1580', '<' ) )
		{
			$installer->create_table($installer->table('keycache'),"
				`member_id` int(11) NOT NULL DEFAULT 0,
				`key_name` varchar(16) NOT NULL DEFAULT '',
				`stage` int(11) NOT NULL DEFAULT 0,
				PRIMARY KEY (`member_id`, `key_name`, `stage`)");
		}

		if( version_compare( $oldversion, '1.9.9.1596', '<' ) )
		{
			$installer->create_table($installer->table('keys'),"
				`faction` char(1) NOT NULL DEFAULT '',
				`key_name` varchar(16) NOT NULL DEFAULT '',
				`icon` varchar(64) NOT NULL DEFAULT 'inv_misc_questionmark',
				PRIMARY KEY (`faction`, `key_name`)");

			$installer->create_table($installer->table('stages'),"
				`faction` varchar(16) NOT NULL DEFAULT '',
				`key_name` varchar(16) NOT NULL DEFAULT '',
				`stage` int(11) NOT NULL DEFAULT 0,
				`type` char(2) NOT NULL DEFAULT '',
				`value` varchar(128) NOT NULL DEFAULT '',
				`count` int(11) NOT NULL DEFAULT 0,
				`flow` char(2) NOT NULL DEFAULT '',
				`active` int(1) NOT NULL DEFAULT 0,
				PRIMARY KEY (`faction`, `key_name`, `stage`)");
		}

		// 1600: Key defines only
		// 1604: Key defines only
		// 1608: Reputation format changed.
		// 1642: esES key defines
		// 1646: Added Arca and BT key defines
		// 1647: Key defines only
		// 1651: frFR key defines

		if( version_compare( $oldversion, '1.9.9.1653', '<' ) )
		{
			$installer->add_query("
				ALTER TABLE `" . $installer->table('keys') . "`
				ADD `locale` varchar(4) NOT NULL DEFAULT 'enUS' FIRST,
				DROP PRIMARY KEY,
				ADD PRIMARY KEY (`locale`, `faction`, `key_name`);");
			$installer->add_query("
				ALTER TABLE `" . $installer->table('stages') . "`
				ADD `locale` varchar(4) NOT NULL DEFAULT 'enUS' FIRST,
				DROP PRIMARY KEY,
				ADD PRIMARY KEY (`locale`, `faction`, `key_name`, `stage`);");
		}

		// 1667: frFR key defines

		if( version_compare( $oldversion, '1.9.9.1686', '<' ) )
		{
			$installer->create_table($installer->table('categories'),"
				`category` varchar(16) NOT NULL DEFAULT '',
				`key` varchar(16) NOT NULL DEFAULT '',
				PRIMARY KEY (`category`, `key`)");
		}

		if( version_compare( $oldversion, '1.9.9.1696', '<' ) )
		{
			$installer->add_config("'200','keys_cats','rostercp-addon-keys-categories','makelink','menu'");
		}

		// 1709: enUS mount hijal

		if( version_compare( $oldversion, '1.9.9.1750', '<' ) )
		{
			$installer->add_query('RENAME TABLE `' . $installer->table('categories') . '`  TO `' . $installer->table('category_key') . '` ;');

			$installer->create_table($installer->table('category'),"
				`category` varchar(16) NOT NULL DEFAULT '',
				PRIMARY KEY (`category`)");

			$installer->add_query('INSERT INTO `' . $installer->table('category') . '`
				SELECT DISTINCT `category`
				FROM `' . $installer->table('category_key') . '`;');
		}

		if( version_compare( $oldversion, '1.9.9.1751', '<' ) )
		{
			$installer->add_menu_pane('keypane');
			$installer->add_menu_button('keybutton','keypane','guild-keys');
		}

		// Always overwrite the key definitions with the defaults on upgrade. If people want to change those they'll have to change the name.
		$this->loadkeys( 'install_' );
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

		$installer->remove_all_config();

		$installer->drop_table($installer->table('keycache'));
		$installer->drop_table($installer->table('keys'));
		$installer->drop_table($installer->table('stages'));

		$installer->remove_all_menu_button();
		return true;
	}

	function loadkeys( $prefix )
	{
		global $installer, $roster;

		foreach( $roster->multilanguages as $lang )
		{
			$inst_keys = array( 'A' => array(), 'H' => array() );

			//echo ROSTER_ADDONS . $installer->addata['basename'] . "/locale/" . $prefix . $lang . ".php";
			if(file_exists( ROSTER_ADDONS . $installer->addata['basename'] . "/locale/" . $prefix . $lang . ".php"))
			{
				include_once( ROSTER_ADDONS . $installer->addata['basename'] . "/locale/" . $prefix . $lang . ".php");
			}
			else
			{
				continue;
			}

			// We need the rep2level array from the normal locale file
			if(file_exists( ROSTER_ADDONS . $installer->addata['basename'] . "/locale/" . $lang . ".php") )
			{
				include_once( ROSTER_ADDONS . $installer->addata['basename'] . "/locale/" . $lang . ".php");
			}

			foreach( $inst_keys as $faction => $keylist )
			{
				foreach( $keylist as $key_name => $stagelist )
				{
					$installer->add_query("DELETE FROM `" . $installer->table('keys') . "` WHERE `locale` = '" . $lang . "' AND `key_name` = '" . $key_name . "' AND `faction` = '" . $faction . "';");
					$installer->add_query("DELETE FROM `" . $installer->table('stages') . "` WHERE `locale` = '" . $lang . "' AND `key_name` = '" . $key_name . "' AND `faction` = '" . $faction . "';");
					$icon = 'inv_misc_questionmark';
					$lockpicking = 0;

					foreach( $stagelist as $stage => $data )
					{
						if( !is_array( $data ) )
						{
							$data = explode('|', $data);
						}

						if( $data[0] == 'Key' )
						{
							list(,$icon,$lockpicking) = $data;
							continue;
						}

						list( $type, $value, $count, $flow, $active ) = $data;

						if( $type == 'R' && !is_numeric($count) )
						{
							list($standing, $count) = explode('+',$count);
							$count += $lang['rep2level'][$standing];
						}

						$installer->add_query("INSERT INTO `" . $installer->table('stages') . "` VALUES ('" . $lang . "','" . $faction . "','" . $key_name . "'," . (int)$stage . ",'" . $type . "','" . $value . "'," . (int)$count . ",'" . $flow . "'," . (int)$active . ");");
					}

					if( $lockpicking )
					{
						$installer->add_query("INSERT INTO `" . $installer->table('stages') . "` VALUES ('" . $lang . "','" . $faction . "','" . $key_name . "',-1,'S','" . $lang['lockpicking'] . "'," . (int)$lockpicking . ",'LP',0);");
					}

					$installer->add_query("INSERT INTO `" . $installer->table('keys') . "` VALUES ('" . $lang . "','" . $faction . "','" . $key_name . "','" . $icon . "');");
				}
			}
		}
	}
}
