<?php
/**
 * WoWRoster.net WoWRoster
 *
 * ArmorySync install definition
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: install.def.php 373 2008-02-24 13:55:12Z poetter $
 * @link       http://www.wowroster.net
 * @package    ArmorySync
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * ArmorySync Addon Installer
 * @package ArmorySync
 * @subpackage Installer
 */
class armorysyncInstall
{
	var $active = true;
	var $icon = 'inv_misc_missilesmall_blue';

	var $version = '2.6.0.373';
	var $wrnet_id = '122';

	var $fullname = 'Armory Sync';
	var $description = 'Syncronizes chars with Blizzard\'s Armory';
	var $credits = array(
		array(	"name"=>	"poetter@WoWRoster.net",
			"info"=>	"Author of 2.6 rewrite"),
		array(	"name"=>	"kristoff22@WoWRoster.net",
			"info"=>	"Original author"),
		array(	"name"=>	"tuigii@wowroster.net",
			"info"=>	"testing, bugfixing and translating"),
		array(	"name"=>	"zanix@wowroster.net",
			"info"=>	"testing, bugfixing and translating"),
		array(	"name"=>	"ds@wowroster.net",
			"info"=>	"supporting"),
		array(	"name"=>	"Subxero@wowroster.net",
			"info"=>	"testing, bugfixing and code support"),

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
		$installer->add_config("'1','startpage','armorysync_conf','display','master'");
		$installer->add_config("'10','armorysync_conf',NULL,'blockframe','menu'");
		$installer->add_config("'15','armorysync_ranks',NULL,'blockframe','menu'");
		$installer->add_config("'20','armorysync_images',NULL,'page{1','menu'");
		$installer->add_config("'30','armorysync_access',NULL,'blockframe','menu'");
		$installer->add_config("'90','armorysync_debug',NULL,'blockframe','menu'");

		$installer->add_config("'302','armorysync_frm1',NULL,'page{1','armorysync_images'");
		$installer->add_config("'303','armorysync_frm2',NULL,'page{1','armorysync_images'");
		$installer->add_config("'304','armorysync_frm3',NULL,'page{3','armorysync_images'");
		$installer->add_config("'310','armorysync_logo',NULL,'blockframe','armorysync_frm1'");
		$installer->add_config("'320','armorysync_effects',NULL,'blockframe','armorysync_frm2'");
		$installer->add_config("'330','armorysync_pic1',NULL,'blockframe','armorysync_frm3'");
		$installer->add_config("'340','armorysync_pic2',NULL,'blockframe','armorysync_frm3'");
		$installer->add_config("'350','armorysync_pic3',NULL,'blockframe','armorysync_frm3'");

		$installer->add_config("'1100', 'armorysync_minlevel', '10', 'text{2|2', 'armorysync_conf'");
		$installer->add_config("'1200', 'armorysync_synchcutofftime', '1', 'text{4|4', 'armorysync_conf'");
		$installer->add_config("'1250', 'armorysync_use_ajax', '1', 'radio{On^1|Off^0', 'armorysync_conf'");
		$installer->add_config("'1300', 'armorysync_reloadwaittime', '2', 'text{4|4', 'armorysync_conf'");
		$installer->add_config("'1325', 'armorysync_fetch_method', '2', 'select{be smart^2|per Character^1|per Page^0', 'armorysync_conf'");
		$installer->add_config("'1350', 'armorysync_fetch_timeout', '4', 'text{2|2', 'armorysync_conf'");
		$installer->add_config("'1352', 'armorysync_fetch_retrys', '2', 'text{2|2', 'armorysync_conf'");
		$installer->add_config("'1355', 'armorysync_update_incomplete', '1', 'radio{On^1|Off^0', 'armorysync_conf'");
		$installer->add_config("'1360', 'armorysync_skip_start', '0', 'radio{On^1|Off^0', 'armorysync_conf'");
		$installer->add_config("'1370', 'armorysync_status_hide', '0', 'radio{On^1|Off^0', 'armorysync_conf'");
		$installer->add_config("'1400', 'armorysync_protectedtitle', 'Banker', 'text{64|20', 'armorysync_conf'");

		$installer->add_config("'1540', 'armorysync_rank_set_order', '3', 'select{Roster/ArmorySync/Armory^3|ArmorySync/Roster/Armory^2|Roster/Armory^1|Armory^0', 'armorysync_ranks'");
		$installer->add_config("'1550', 'armorysync_rank_0', '', 'text{64|20', 'armorysync_ranks'");
		$installer->add_config("'1551', 'armorysync_rank_1', '', 'text{64|20', 'armorysync_ranks'");
		$installer->add_config("'1552', 'armorysync_rank_2', '', 'text{64|20', 'armorysync_ranks'");
		$installer->add_config("'1553', 'armorysync_rank_3', '', 'text{64|20', 'armorysync_ranks'");
		$installer->add_config("'1554', 'armorysync_rank_4', '', 'text{64|20', 'armorysync_ranks'");
		$installer->add_config("'1555', 'armorysync_rank_5', '', 'text{64|20', 'armorysync_ranks'");
		$installer->add_config("'1556', 'armorysync_rank_6', '', 'text{64|20', 'armorysync_ranks'");
		$installer->add_config("'1557', 'armorysync_rank_7', '', 'text{64|20', 'armorysync_ranks'");
		$installer->add_config("'1558', 'armorysync_rank_8', '', 'text{64|20', 'armorysync_ranks'");
		$installer->add_config("'1559', 'armorysync_rank_9', '', 'text{64|20', 'armorysync_ranks'");

		$installer->add_config("'1440', 'armorysync_char_update_access', '1', 'access', 'armorysync_access'");
		$installer->add_config("'1450', 'armorysync_guild_update_access', '2', 'access', 'armorysync_access'");
		$installer->add_config("'1460', 'armorysync_guild_memberlist_update_access', '2', 'access', 'armorysync_access'");
		$installer->add_config("'1470', 'armorysync_realm_update_access', '3', 'access', 'armorysync_access'");
		$installer->add_config("'1480', 'armorysync_guild_add_access', '3', 'access', 'armorysync_access'");

		$installer->add_config("'2110', 'armorysync_logo_show', '1', 'radio{On^1|Off^0', 'armorysync_logo'");
		$installer->add_config("'2120', 'armorysync_logo_pos_left', '410', 'text{5|5', 'armorysync_logo'");
		$installer->add_config("'2130', 'armorysync_logo_pos_top', '-170', 'text{5|5', 'armorysync_logo'");
		$installer->add_config("'2140', 'armorysync_logo_size', '250', 'text{5|5', 'armorysync_logo'");

		$installer->add_config("'2200', 'armorysync_pic_effects', '1', 'radio{On^1|Off^0', 'armorysync_effects'");

		$installer->add_config("'2210', 'armorysync_pic1_show', '1', 'radio{On^1|Off^0', 'armorysync_pic1'");
		$installer->add_config("'2220', 'armorysync_pic1_min_rows', '25', 'text{3|3', 'armorysync_pic1'");
		$installer->add_config("'2230', 'armorysync_pic1_pos_left', '-100', 'text{5|5', 'armorysync_pic1'");
		$installer->add_config("'2240', 'armorysync_pic1_pos_top', '350', 'text{5|5', 'armorysync_pic1'");
		$installer->add_config("'2250', 'armorysync_pic1_size', '250', 'text{5|5', 'armorysync_pic1'");

		$installer->add_config("'2310', 'armorysync_pic2_show', '1', 'radio{On^1|Off^0', 'armorysync_pic2'");
		$installer->add_config("'2320', 'armorysync_pic2_min_rows', '50', 'text{3|3', 'armorysync_pic2'");
		$installer->add_config("'2330', 'armorysync_pic2_pos_left', '760', 'text{5|5', 'armorysync_pic2'");
		$installer->add_config("'2340', 'armorysync_pic2_pos_top', '920', 'text{5|5', 'armorysync_pic2'");
		$installer->add_config("'2350', 'armorysync_pic2_size', '250', 'text{5|5', 'armorysync_pic2'");

		$installer->add_config("'2410', 'armorysync_pic3_show', '1', 'radio{On^1|Off^0', 'armorysync_pic3'");
		$installer->add_config("'2420', 'armorysync_pic3_min_rows', '75', 'text{3|3', 'armorysync_pic3'");
		$installer->add_config("'2430', 'armorysync_pic3_pos_left', '-125', 'text{5|5', 'armorysync_pic3'");
		$installer->add_config("'2440', 'armorysync_pic3_pos_top', '1500', 'text{5|5', 'armorysync_pic3'");
		$installer->add_config("'2450', 'armorysync_pic3_size', '250', 'text{5|5', 'armorysync_pic3'");

		$installer->add_config("'9100', 'armorysync_debuglevel', '1', 'select{All Methods Data Info^3|Armory & Job Data Info^2|Base Info^1|Quiet^0', 'armorysync_debug'");
		$installer->add_config("'9110', 'armorysync_debugdata', '0', 'radio{yes^1|no^0', 'armorysync_debug'");
		$installer->add_config("'9120', 'armorysync_javadebug', '0', 'radio{yes^1|no^0', 'armorysync_debug'");
		$installer->add_config("'9130', 'armorysync_xdebug_php', '0', 'radio{yes^1|no^0', 'armorysync_debug'");
		$installer->add_config("'9140', 'armorysync_xdebug_ajax', '0', 'radio{yes^1|no^0', 'armorysync_debug'");
		$installer->add_config("'9150', 'armorysync_xdebug_idekey', 'test', 'text{64|10', 'armorysync_debug'");
		$installer->add_config("'9200', 'armorysync_sqldebug', '0', 'radio{yes^1|no^0', 'armorysync_debug'");
		$installer->add_config("'9300', 'armorysync_updateroster', '1', 'radio{yes^1|no^0', 'armorysync_debug'");

		$installer->add_menu_button('async_button1','char', '', 'as_char.jpg');
		$installer->add_menu_button('async_button2','guild', '', 'as_char.jpg');
		$installer->add_menu_button('async_button3','realm', '', 'as_char.jpg');
		$installer->add_menu_button('async_button4','guild', 'memberlist', 'as_memberlist.jpg');
		$installer->add_menu_button('async_button5','util', 'add', 'as_guild_add.jpg');


		$installer->create_table(
				$installer->table('jobs'),
					"
						`job_id` int(11) unsigned NOT NULL auto_increment,
						`starttimeutc` datetime NOT NULL,
						PRIMARY KEY  (`job_id`)
					" );
		$installer->create_table(
				$installer->table('jobqueue'),
							"
							 `job_id` int(11) unsigned NOT NULL,
							 `member_id` int(11) unsigned NOT NULL,
							 `name` varchar(64) NOT NULL,
							 `guild_id` int(11) NOT NULL,
							 `guild_name` varchar(64) NOT NULL,
							 `server` varchar(32) NOT NULL,
							 `region` char(2) NOT NULL,
							 `guild_info` int(11) unsigned default NULL,
							 `character_info` tinyint(1) default NULL,
							 `skill_info` int(11) default NULL,
							 `reputation_info` int(11) default NULL,
							 `equipment_info` varchar(11) default NULL,
							 `talent_info` int(11) default NULL,
							 `starttimeutc` datetime default NULL,
							 `stoptimeutc` datetime default NULL,
							 `log` text,
							 PRIMARY KEY  (`job_id`,`member_id`)
							" );
		$installer->create_table(
				$installer->table('updates'),
							"
							`member_id` int(11) NOT NULL,
							`dateupdatedutc` datetime default NULL,
							PRIMARY KEY  (`member_id`)
							" );
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

		if ( version_compare('2.6.0.235', $oldversion,'>') == true ) {
			$installer->create_table(
				$installer->table('updates'),
							"
							`member_id` int(11) NOT NULL,
							`dateupdatedutc` datetime default NULL,
							PRIMARY KEY  (`member_id`)
							" );
	    }

		if ( version_compare('2.6.0.275', $oldversion,'>') == true ) {
			$installer->remove_all_menu_button();
			$installer->add_menu_button('async_button1','char', '', 'as_char.jpg');
			$installer->add_menu_button('async_button2','guild', '', 'as_char.jpg');
			$installer->add_menu_button('async_button3','realm', '', 'as_char.jpg');
			$installer->add_menu_button('async_button4','guild', 'memberlist', 'as_memberlist.jpg');
			$installer->add_menu_button('async_button5','util', 'add', 'as_guild_add.jpg');

		}

		if ( version_compare('2.6.0.264', $oldversion,'>') == true ) {

			$installer->remove_all_config();
			$installer->add_config("'1','startpage','armorysync_conf','display','master'");
			$installer->add_config("'10','armorysync_conf',NULL,'blockframe','menu'");
			$installer->add_config("'20','armorysync_images',NULL,'page{1','menu'");
			$installer->add_config("'30','armorysync_access',NULL,'blockframe','menu'");
			$installer->add_config("'90','armorysync_debug',NULL,'blockframe','menu'");

			$installer->add_config("'302','armorysync_frm1',NULL,'page{1','armorysync_images'");
			$installer->add_config("'303','armorysync_frm2',NULL,'page{1','armorysync_images'");
			$installer->add_config("'304','armorysync_frm3',NULL,'page{3','armorysync_images'");
			$installer->add_config("'310','armorysync_logo',NULL,'blockframe','armorysync_frm1'");
			$installer->add_config("'320','armorysync_effects',NULL,'blockframe','armorysync_frm2'");
			$installer->add_config("'330','armorysync_pic1',NULL,'blockframe','armorysync_frm3'");
			$installer->add_config("'340','armorysync_pic2',NULL,'blockframe','armorysync_frm3'");
			$installer->add_config("'350','armorysync_pic3',NULL,'blockframe','armorysync_frm3'");

			$installer->add_config("'1100', 'armorysync_minlevel', '10', 'text{2|2', 'armorysync_conf'");
			$installer->add_config("'1200', 'armorysync_synchcutofftime', '1', 'text{4|4', 'armorysync_conf'");
			$installer->add_config("'1250', 'armorysync_use_ajax', '1', 'radio{On^1|Off^0', 'armorysync_conf'");
			$installer->add_config("'1300', 'armorysync_reloadwaittime', '5', 'text{4|4', 'armorysync_conf'");
			$installer->add_config("'1350', 'armorysync_fetch_timeout', '8', 'text{2|2', 'armorysync_conf'");
			$installer->add_config("'1360', 'armorysync_skip_start', '0', 'radio{On^1|Off^0', 'armorysync_conf'");
			$installer->add_config("'1370', 'armorysync_status_hide', '0', 'radio{On^1|Off^0', 'armorysync_conf'");
			$installer->add_config("'1400', 'armorysync_protectedtitle', 'Banker', 'text{64|20', 'armorysync_conf'");


			$installer->add_config("'1440', 'armorysync_char_update_access', '1', 'access', 'armorysync_access'");
			$installer->add_config("'1450', 'armorysync_guild_update_access', '2', 'access', 'armorysync_access'");
			$installer->add_config("'1460', 'armorysync_guild_memberlist_update_access', '2', 'access', 'armorysync_access'");
			$installer->add_config("'1470', 'armorysync_realm_update_access', '3', 'access', 'armorysync_access'");
			$installer->add_config("'1480', 'armorysync_guild_add_access', '3', 'access', 'armorysync_access'");

			$installer->add_config("'2110', 'armorysync_logo_show', '1', 'radio{On^1|Off^0', 'armorysync_logo'");
			$installer->add_config("'2120', 'armorysync_logo_pos_left', '410', 'text{5|5', 'armorysync_logo'");
			$installer->add_config("'2130', 'armorysync_logo_pos_top', '-170', 'text{5|5', 'armorysync_logo'");
			$installer->add_config("'2140', 'armorysync_logo_size', '250', 'text{5|5', 'armorysync_logo'");

			$installer->add_config("'2200', 'armorysync_pic_effects', '1', 'radio{On^1|Off^0', 'armorysync_effects'");

			$installer->add_config("'2210', 'armorysync_pic1_show', '1', 'radio{On^1|Off^0', 'armorysync_pic1'");
			$installer->add_config("'2220', 'armorysync_pic1_min_rows', '25', 'text{3|3', 'armorysync_pic1'");
			$installer->add_config("'2230', 'armorysync_pic1_pos_left', '-100', 'text{5|5', 'armorysync_pic1'");
			$installer->add_config("'2240', 'armorysync_pic1_pos_top', '350', 'text{5|5', 'armorysync_pic1'");
			$installer->add_config("'2250', 'armorysync_pic1_size', '250', 'text{5|5', 'armorysync_pic1'");

			$installer->add_config("'2310', 'armorysync_pic2_show', '1', 'radio{On^1|Off^0', 'armorysync_pic2'");
			$installer->add_config("'2320', 'armorysync_pic2_min_rows', '50', 'text{3|3', 'armorysync_pic2'");
			$installer->add_config("'2330', 'armorysync_pic2_pos_left', '760', 'text{5|5', 'armorysync_pic2'");
			$installer->add_config("'2340', 'armorysync_pic2_pos_top', '920', 'text{5|5', 'armorysync_pic2'");
			$installer->add_config("'2350', 'armorysync_pic2_size', '250', 'text{5|5', 'armorysync_pic2'");

			$installer->add_config("'2410', 'armorysync_pic3_show', '1', 'radio{On^1|Off^0', 'armorysync_pic3'");
			$installer->add_config("'2420', 'armorysync_pic3_min_rows', '75', 'text{3|3', 'armorysync_pic3'");
			$installer->add_config("'2430', 'armorysync_pic3_pos_left', '-125', 'text{5|5', 'armorysync_pic3'");
			$installer->add_config("'2440', 'armorysync_pic3_pos_top', '1500', 'text{5|5', 'armorysync_pic3'");
			$installer->add_config("'2450', 'armorysync_pic3_size', '250', 'text{5|5', 'armorysync_pic3'");

			$installer->add_config("'9100', 'armorysync_debuglevel', '1', 'select{All Methods Data Info^3|Armory & Job Data Info^2|Base Info^1|Quiet^0', 'armorysync_debug'");
			$installer->add_config("'9110', 'armorysync_debugdata', '0', 'radio{yes^1|no^0', 'armorysync_debug'");
			$installer->add_config("'9120', 'armorysync_javadebug', '0', 'radio{yes^1|no^0', 'armorysync_debug'");
			$installer->add_config("'9130', 'armorysync_xdebug_php', '0', 'radio{yes^1|no^0', 'armorysync_debug'");
			$installer->add_config("'9140', 'armorysync_xdebug_ajax', '0', 'radio{yes^1|no^0', 'armorysync_debug'");
			$installer->add_config("'9150', 'armorysync_xdebug_idekey', 'test', 'text{64|10', 'armorysync_debug'");
			$installer->add_config("'9200', 'armorysync_sqldebug', '0', 'radio{yes^1|no^0', 'armorysync_debug'");
			$installer->add_config("'9300', 'armorysync_updateroster', '1', 'radio{yes^1|no^0', 'armorysync_debug'");
		}

		if ( version_compare('2.6.0.273', $oldversion,'>') == true ) {
			$installer->add_config("'15','armorysync_ranks',NULL,'blockframe','menu'");

			$installer->add_config("'1540', 'armorysync_rank_set_order', '3', 'select{Roster/ArmorySync/Armory^3|ArmorySync/Roster/Armory^2|Roster/Armory^1|Armory^0', 'armorysync_ranks'");
			$installer->add_config("'1550', 'armorysync_rank_0', '', 'text{64|20', 'armorysync_ranks'");
			$installer->add_config("'1551', 'armorysync_rank_1', '', 'text{64|20', 'armorysync_ranks'");
			$installer->add_config("'1552', 'armorysync_rank_2', '', 'text{64|20', 'armorysync_ranks'");
			$installer->add_config("'1553', 'armorysync_rank_3', '', 'text{64|20', 'armorysync_ranks'");
			$installer->add_config("'1554', 'armorysync_rank_4', '', 'text{64|20', 'armorysync_ranks'");
			$installer->add_config("'1555', 'armorysync_rank_5', '', 'text{64|20', 'armorysync_ranks'");
			$installer->add_config("'1556', 'armorysync_rank_6', '', 'text{64|20', 'armorysync_ranks'");
			$installer->add_config("'1557', 'armorysync_rank_7', '', 'text{64|20', 'armorysync_ranks'");
			$installer->add_config("'1558', 'armorysync_rank_8', '', 'text{64|20', 'armorysync_ranks'");
			$installer->add_config("'1559', 'armorysync_rank_9', '', 'text{64|20', 'armorysync_ranks'");
		}

		if ( version_compare('2.6.0.273', $oldversion,'>') == true ) {
			$installer->update_config('1250', 'config_value=1');
		}

		if ( version_compare('2.6.0.330', $oldversion,'>') == true ) {
				$installer->add_config("'1325', 'armorysync_fetch_method', '1', 'select{per Character^1|per Page^0', 'armorysync_conf'");
				$installer->drop_table( $installer->table('jobqueue') );
				$installer->create_table(
						$installer->table('jobqueue'),
									"
									 `job_id` int(11) unsigned NOT NULL,
									 `member_id` int(11) unsigned NOT NULL,
									 `name` varchar(64) NOT NULL,
									 `guild_id` int(11) NOT NULL,
									 `guild_name` varchar(64) NOT NULL,
									 `server` varchar(32) NOT NULL,
									 `region` char(2) NOT NULL,
									 `guild_info` int(11) unsigned default NULL,
									 `character_info` tinyint(1) default NULL,
									 `skill_info` int(11) default NULL,
									 `reputation_info` int(11) default NULL,
									 `equipment_info` varchar(11) default NULL,
									 `talent_info` int(11) default NULL,
									 `starttimeutc` datetime default NULL,
									 `stoptimeutc` datetime default NULL,
									 `log` text,
									 PRIMARY KEY  (`job_id`,`member_id`)
									" );
		}

		if ( version_compare('2.6.0.367', $oldversion,'>') == true ) {
			$installer->update_config('1325', 'form_type="select{be smart^2|per Character^1|per Page^0"');
		}

		if ( version_compare('2.6.0.368', $oldversion,'>') == true ) {
			$installer->remove_config('1325');
			$installer->remove_config('1300');
			$installer->remove_config('1350');
			$installer->add_config("'1300', 'armorysync_reloadwaittime', '2', 'text{4|4', 'armorysync_conf'");
			$installer->add_config("'1325', 'armorysync_fetch_method', '2', 'select{be smart^2|per Character^1|per Page^0', 'armorysync_conf'");
			$installer->add_config("'1350', 'armorysync_fetch_timeout', '4', 'text{2|2', 'armorysync_conf'");
		}

		if ( version_compare('2.6.0.369', $oldversion,'>') == true ) {
			$installer->add_config("'1352', 'armorysync_fetch_retrys', '2', 'text{2|2', 'armorysync_conf'");
			$installer->add_config("'1355', 'armorysync_update_incomplete', '1', 'radio{On^1|Off^0', 'armorysync_conf'");
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

		$installer->remove_all_config();
		$installer->remove_all_menu_button();
		$installer->drop_table( $installer->table('jobs') );
		$installer->drop_table( $installer->table('jobqueue') );
		$installer->drop_table( $installer->table('updates') );
		return true;
	}
}
