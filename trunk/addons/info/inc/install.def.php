<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Installer for Character Info Addon
 * @package    CharacterInfo
 * @subpackage Installer
 */
class infoInstall
{
	var $active = true;
	var $icon = 'spell_holy_divinespirit';

	var $version = '2.0.9.2196';
	var $wrnet_id = '0';

	var $fullname = 'char_info';
	var $description = 'char_info_desc';
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
		global $installer, $roster;

		// Master and menu entries
		$installer->add_config("'1','startpage','char_conf','display','master'");
		$installer->add_config("'110','char_conf',NULL,'blockframe','menu'");
		$installer->add_config("'120','char_pref','rostercp-addon-info-display','makelink','menu'");

		$installer->add_config("'1000', 'recipe_disp', '0', 'radio{show^1|collapse^0', 'char_conf'");
		$installer->add_config("'1010', 'mail_disp', '1', 'radio{Table^0|Bag^1|Both^2', 'char_conf'");
		$installer->add_config("'1020', 'show_money', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1030', 'show_played', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1035', 'show_model', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1040', 'show_pets', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1050', 'show_reputation', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1060', 'show_skills', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1070', 'show_honor', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1075', 'show_currency', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1080', 'show_talents', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1085', 'show_glyphs', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1090', 'show_spellbook', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1100', 'show_mail', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1110', 'show_bags', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1120', 'show_bank', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1130', 'show_quests', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1140', 'show_recipes', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1150', 'show_item_bonuses', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1160', 'show_pet_talents', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1170', 'show_pet_spells', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1180', 'show_companions', '0', 'function{infoAccess', 'char_conf'");
		$installer->add_config("'1190', 'show_mounts', '0', 'function{infoAccess', 'char_conf'");

		$installer->create_table($installer->table('display'),"
		  `member_id` int(11) NOT NULL default '0',
		  `show_money` tinyint(1) NOT NULL default '0',
		  `show_played` tinyint(1) NOT NULL default '0',
		  `show_model` tinyint(1) NOT NULL default '0',
		  `show_pets` tinyint(1) NOT NULL default '0',
		  `show_reputation` tinyint(1) NOT NULL default '0',
		  `show_skills` tinyint(1) NOT NULL default '0',
		  `show_honor` tinyint(1) NOT NULL default '0',
		  `show_currency` tinyint(1) NOT NULL default '0',
		  `show_talents` tinyint(1) NOT NULL default '0',
		  `show_glyphs` tinyint(1) NOT NULL default '0',
		  `show_spellbook` tinyint(1) NOT NULL default '0',
		  `show_mail` tinyint(1) NOT NULL default '0',
		  `show_bags` tinyint(1) NOT NULL default '0',
		  `show_bank` tinyint(1) NOT NULL default '0',
		  `show_quests` tinyint(1) NOT NULL default '0',
		  `show_recipes` tinyint(1) NOT NULL default '0',
		  `show_item_bonuses` tinyint(1) NOT NULL default '0',
		  `show_pet_talents` tinyint(1) NOT NULL default '0',
		  `show_pet_spells` tinyint(1) NOT NULL default '0',
		  `show_companions` tinyint(1) NOT NULL default '0',
		  `show_mounts` tinyint(1) NOT NULL default '0',
		  PRIMARY KEY  (`member_id`)");


		$installer->create_table($installer->table('default'),"
		  `show_money` tinyint(1) NOT NULL default '0',
		  `show_played` tinyint(1) NOT NULL default '0',
		  `show_model` tinyint(1) NOT NULL default '0',
		  `show_pets` tinyint(1) NOT NULL default '0',
		  `show_reputation` tinyint(1) NOT NULL default '0',
		  `show_skills` tinyint(1) NOT NULL default '0',
		  `show_honor` tinyint(1) NOT NULL default '0',
		  `show_currency` tinyint(1) NOT NULL default '0',
		  `show_talents` tinyint(1) NOT NULL default '0',
		  `show_glyphs` tinyint(1) NOT NULL default '0',
		  `show_spellbook` tinyint(1) NOT NULL default '0',
		  `show_mail` tinyint(1) NOT NULL default '0',
		  `show_bags` tinyint(1) NOT NULL default '0',
		  `show_bank` tinyint(1) NOT NULL default '0',
		  `show_quests` tinyint(1) NOT NULL default '0',
		  `show_recipes` tinyint(1) NOT NULL default '0',
		  `show_item_bonuses` tinyint(1) NOT NULL default '0',
		  `show_pet_talents` tinyint(1) NOT NULL default '0',
		  `show_pet_spells` tinyint(1) NOT NULL default '0',
		  `show_companions` tinyint(1) NOT NULL default '0',
		  `show_mounts` tinyint(1) NOT NULL default '0'");

		$build_query = array(
			'show_money' => '0',
			'show_played' => '0',
			'show_model' => '0',
			'show_pets' => '0',
			'show_reputation' => '0',
			'show_skills' => '0',
			'show_honor' => '0',
			'show_currency' => '0',
			'show_talents' => '0',
			'show_glyphs' => '0',
			'show_spellbook' => '0',
			'show_mail' => '0',
			'show_bags' => '0',
			'show_bank' => '0',
			'show_quests' => '0',
			'show_recipes' => '0',
			'show_item_bonuses' => '0',
			'show_pet_talents' => '0',
			'show_pet_spells' => '0',
			'show_companions' => '0',
			'show_mounts' => '0'
		);

		$installer->add_query('INSERT INTO `' . $installer->table('default') . '` ' . $roster->db->build_query('INSERT', $build_query) . ';');

		$installer->add_query('INSERT INTO `' . $installer->table('display') . '` SELECT `p`.`member_id` , `d` . * FROM `' . $roster->db->table('players') . '` p, `' . $installer->table('default') . '` d ');

		$installer->add_menu_button('cb_character', 'char', '', 'spell_holy_divinespirit');
		$installer->add_menu_button('cb_mailbox', 'char', 'mailbox', 'inv_letter_02');
		$installer->add_menu_button('cb_inventory', 'char', 'inventory', 'inv_misc_bag_14');
		$installer->add_menu_button('cb_bags', 'char', 'bags', 'inv_misc_bag_08', false);
		$installer->add_menu_button('cb_bank', 'char', 'bank', 'inv_misc_bag_15', false);
		$installer->add_menu_button('cb_quests', 'char', 'quests', 'achievement_quests_completed_06');
		$installer->add_menu_button('cb_recipes', 'char', 'recipes', 'inv_scroll_02');

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
		global $installer, $roster;

		// Basicly we are re-installing this addon, since the config section has changed so much
		// This means any version upgrade routines are not needed
		if( version_compare('1.9.9.1745', $oldversion,'>') == true )
		{
			$installer->remove_all_config();
			$installer->remove_all_menu_button();

			$installer->add_query("ALTER TABLE `" . $roster->db->table('players') . "`
			  DROP `show_money`,
			  DROP `show_played`,
			  DROP `show_tab2`,
			  DROP `show_tab3`,
			  DROP `show_tab4`,
			  DROP `show_tab5`,
			  DROP `show_talents`,
			  DROP `show_spellbook`,
			  DROP `show_mail`,
			  DROP `show_bags`,
			  DROP `show_bank`,
			  DROP `show_quests`,
			  DROP `show_recipes`,
			  DROP `show_item_bonuses`;");

			// Important to return here, a re-install updates to the latest version no matter what, no upgrades needed
			return $this->install();
		}

		// I screwed up on the table naming here
		if( version_compare('1.9.9.1747', $oldversion,'>') == true )
		{
			$installer->add_query('RENAME TABLE `' . $installer->table('') . '`  TO `' . $installer->table('display') . '` ;');
		}

		// Change the icon for quests
		if( version_compare('2.0.9.1885', $oldversion,'>') == true )
		{
			$installer->update_menu_button('cb_quests','char','quests','achievement_quests_completed_06');
		}

		// Remove the talents and spellbook icons since they are on the same page as "profile" now
		if( version_compare('2.0.9.1940', $oldversion,'>') == true )
		{
			$installer->remove_menu_button('cb_talents');
			$installer->remove_menu_button('cb_spellbook');
		}

		// Add in glpyh, companion, mount, and pet talent/spell control
		if( version_compare('2.0.9.1998', $oldversion,'>') == true )
		{
			$installer->add_config("'1085', 'show_glyphs', '0', 'function{infoAccess', 'char_conf'");
			$installer->add_config("'1160', 'show_pet_talents', '0', 'function{infoAccess', 'char_conf'");
			$installer->add_config("'1170', 'show_pet_spells', '0', 'function{infoAccess', 'char_conf'");
			$installer->add_config("'1180', 'show_companions', '0', 'function{infoAccess', 'char_conf'");
			$installer->add_config("'1190', 'show_mounts', '0', 'function{infoAccess', 'char_conf'");

			$installer->add_query("ALTER TABLE `" . $installer->table('display') . "`
				ADD `show_glyphs` tinyint(1) NOT NULL default '0' AFTER `show_talents`,
				ADD `show_pet_talents` tinyint(1) NOT NULL default '0',
				ADD `show_pet_spells` tinyint(1) NOT NULL default '0',
				ADD `show_companions` tinyint(1) NOT NULL default '0',
				ADD `show_mounts` tinyint(1) NOT NULL default '0'");

			$installer->add_query("ALTER TABLE `" . $installer->table('default') . "`
				ADD `show_glyphs` tinyint(1) NOT NULL default '0' AFTER `show_talents`,
				ADD `show_pet_talents` tinyint(1) NOT NULL default '0',
				ADD `show_pet_spells` tinyint(1) NOT NULL default '0',
				ADD `show_companions` tinyint(1) NOT NULL default '0',
				ADD `show_mounts` tinyint(1) NOT NULL default '0'");
		}

		// Change the profile icon
		if( version_compare('2.0.9.2006', $oldversion,'>') == true )
		{
			$installer->update_menu_button('cb_character', 'char', '', 'spell_holy_divinespirit');
		}

		// Adding currency tab
		if( version_compare('2.0.9.2014', $oldversion,'>') == true )
		{
			$installer->add_config("'1075', 'show_currency', '0', 'function{infoAccess', 'char_conf'");
			$installer->add_query("ALTER TABLE `" . $installer->table('display') . "`
				ADD `show_currency` tinyint(1) NOT NULL default '0' AFTER `show_tab5`");
			$installer->add_query("ALTER TABLE `" . $installer->table('default') . "`
				ADD `show_currency` tinyint(1) NOT NULL default '0' AFTER `show_tab5`");
		}

		// Renaming config
		if( version_compare('2.0.9.2035', $oldversion,'>') == true )
		{
			$installer->update_config('1040', "`config_name` = 'show_pets'");
			$installer->update_config('1050', "`config_name` = 'show_reputation'");
			$installer->update_config('1060', "`config_name` = 'show_skills'");
			$installer->update_config('1070', "`config_name` = 'show_honor'");

			$installer->add_query("ALTER TABLE `" . $installer->table('display') . "`
				CHANGE `show_tab2` `show_pets` tinyint(1) NOT NULL default '0',
				CHANGE `show_tab3` `show_reputation` tinyint(1) NOT NULL default '0',
				CHANGE `show_tab4` `show_skills` tinyint(1) NOT NULL default '0',
				CHANGE `show_tab5` `show_honor` tinyint(1) NOT NULL default '0'");

			$installer->add_query("ALTER TABLE `" . $installer->table('default') . "`
				CHANGE `show_tab2` `show_pets` tinyint(1) NOT NULL default '0',
				CHANGE `show_tab3` `show_reputation` tinyint(1) NOT NULL default '0',
				CHANGE `show_tab4` `show_skills` tinyint(1) NOT NULL default '0',
				CHANGE `show_tab5` `show_honor` tinyint(1) NOT NULL default '0'");

			$installer->add_menu_button('cb_inventory', 'char', 'inventory', 'inv_misc_bag_14');
		}

		// Model Viewer tab
		if( version_compare('2.0.9.2196', $oldversion,'>') == true )
		{
			$installer->add_config("'1035', 'show_model', '0', 'function{infoAccess', 'char_conf'");
		
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
		global $installer, $roster;

		$installer->remove_all_config();
		$installer->remove_all_menu_button();
		$installer->drop_table($installer->table('display'));
		$installer->drop_table($installer->table('default'));

		return true;
	}
}
