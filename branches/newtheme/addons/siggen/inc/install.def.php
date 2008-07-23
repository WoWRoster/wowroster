<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @link http://www.wowroster.net
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author Joshua Clark
 * @version $Id: install.def.php 410 2008-07-02 23:38:04Z Zanix $
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Installer for SigGen Addon
 *
 * @package    SigGen
 * @subpackage Installer
 *
 */
class siggenInstall
{
	var $active = true;
	var $icon = 'inv_misc_book_13';

	var $version = '0.3.2.410';
	var $wrnet_id = '20';

	var $fullname = 'SigGen';
	var $description = 'siggen_install_desc';
	var $credits = array(
		array(	"name"=>	"Zanix",
				"info"=>	"Signature and Avatar Generator"),
	);



	/**
	 * Install Function
	 *
	 * @return bool
	 */
	function install()
	{
		global $roster, $installer, $addon;

		// ----[ Check for GD Functions ]---------------------------
		if( !function_exists('imageTTFBBox') || !function_exists('imageTTFText') || !function_exists('imagecreatetruecolor') || !function_exists('imagecreate') )
		{
			$installer->seterrors($roster->locale->act['no_gd_error']);
			return;
		}

		$installer->create_table($installer->table('config'),"
`config_id` varchar(20) NOT NULL default '',
`db_ver` varchar(6) NOT NULL default '',
`trigger` tinyint(1) NOT NULL default '0',
`guild_trigger` tinyint(1) NOT NULL default '0',
`cleardir` tinyint(1) NOT NULL default '0',
`main_image_size_w` smallint(6) NOT NULL default '0',
`main_image_size_h` smallint(6) NOT NULL default '0',
`image_dir` varchar(64) NOT NULL default '',
`char_dir` varchar(64) NOT NULL default '',
`class_dir` varchar(64) NOT NULL default '',
`spec_dir` varchar(64) NOT NULL default '',
`backg_dir` varchar(64) NOT NULL default '',
`user_dir` varchar(64) NOT NULL default '',
`pvplogo_dir` varchar(64) NOT NULL default '',
`frame_dir` varchar(64) NOT NULL,
`level_dir` varchar(64) NOT NULL,
`border_dir` varchar(64) NOT NULL,
`font_dir` varchar(64) NOT NULL default '',
`link_type` varchar(64) NOT NULL default '',
`image_order` varchar(128) NOT NULL default '',
`default_message` varchar(64) NOT NULL,
`save_images` tinyint(1) NOT NULL default '0',
`save_only_mode` tinyint(1) NOT NULL default '0',
`save_char_convert` tinyint(1) NOT NULL default '0',
`uniup_compat` tinyint(1) NOT NULL default '0',
`save_prefix` varchar(32) NOT NULL default '',
`save_suffix` varchar(32) NOT NULL default '',
`save_images_dir` varchar(255) NOT NULL default '',
`save_images_format` char(3) NOT NULL default '',
`etag_cache` tinyint(1) NOT NULL default '0',
`image_type` char(3) NOT NULL default '',
`image_quality` tinyint(3) NOT NULL default '0',
`gif_dither` tinyint(1) NOT NULL default '0',
`backg_disp` tinyint(1) NOT NULL default '0',
`backg_fill` tinyint(1) NOT NULL default '0',
`backg_fill_color` varchar(15) NOT NULL default '',
`backg_default_image` varchar(64) NOT NULL default '',
`backg_force_default` tinyint(1) NOT NULL default '0',
`backg_data_table` varchar(64) NOT NULL default '',
`backg_data` varchar(64) NOT NULL default '',
`backg_translate` tinyint(1) NOT NULL default '0',
`backg_search_1` varchar(64) NOT NULL default '',
`backg_search_2` varchar(64) NOT NULL default '',
`backg_search_3` varchar(64) NOT NULL default '',
`backg_search_4` varchar(64) NOT NULL default '',
`backg_search_5` varchar(64) NOT NULL default '',
`backg_search_6` varchar(64) NOT NULL default '',
`backg_search_7` varchar(64) NOT NULL default '',
`backg_search_8` varchar(64) NOT NULL default '',
`backg_search_9` varchar(64) NOT NULL default '',
`backg_search_10` varchar(64) NOT NULL default '',
`backg_search_11` varchar(64) NOT NULL default '',
`backg_search_12` varchar(64) NOT NULL default '',
`backg_file_1` varchar(64) NOT NULL default '',
`backg_file_2` varchar(64) NOT NULL default '',
`backg_file_3` varchar(64) NOT NULL default '',
`backg_file_4` varchar(64) NOT NULL default '',
`backg_file_5` varchar(64) NOT NULL default '',
`backg_file_6` varchar(64) NOT NULL default '',
`backg_file_7` varchar(64) NOT NULL default '',
`backg_file_8` varchar(64) NOT NULL default '',
`backg_file_9` varchar(64) NOT NULL default '',
`backg_file_10` varchar(64) NOT NULL default '',
`backg_file_11` varchar(64) NOT NULL default '',
`backg_file_12` varchar(64) NOT NULL default '',
`font_fullpath` tinyint(1) NOT NULL default '0',
`outside_border_image` varchar(64) NOT NULL default '',
`frames_image` varchar(64) NOT NULL default '',
`charlogo_disp` tinyint(1) NOT NULL default '0',
`charlogo_default_image` varchar(64) NOT NULL default '',
`charlogo_loc_x` smallint(6) NOT NULL default '0',
`charlogo_loc_y` smallint(6) NOT NULL default '0',
`class_img_disp` tinyint(1) NOT NULL default '0',
`class_img_loc_x` smallint(6) NOT NULL default '0',
`class_img_loc_y` smallint(6) NOT NULL default '0',
`spec_img_disp` tinyint(1) NOT NULL default '0',
`spec_img_loc_x` smallint(6) NOT NULL default '0',
`spec_img_loc_y` smallint(6) NOT NULL default '0',
`pvplogo_disp` tinyint(1) NOT NULL default '0',
`pvplogo_loc_x` smallint(6) NOT NULL default '0',
`pvplogo_loc_y` smallint(6) NOT NULL default '0',
`lvl_disp` tinyint(1) NOT NULL default '0',
`lvl_font_name` varchar(64) NOT NULL default '',
`lvl_font_color` varchar(15) NOT NULL default '',
`lvl_font_size` smallint(6) NOT NULL default '0',
`lvl_text_shadow` varchar(15) NOT NULL default '',
`lvl_loc_x` smallint(6) NOT NULL default '0',
`lvl_loc_y` smallint(6) NOT NULL default '0',
`lvl_text_loc_x` smallint(6) NOT NULL default '0',
`lvl_text_loc_y` smallint(6) NOT NULL default '0',
`lvl_image` varchar(64) NOT NULL default '',
`expbar_disp` tinyint(1) NOT NULL default '0',
`expbar_disp_bdr` tinyint(1) NOT NULL default '0',
`expbar_disp_inside` tinyint(1) NOT NULL default '0',
`expbar_max_disp` tinyint(1) NOT NULL default '0',
`expbar_max_level` smallint(6) NOT NULL default '0',
`expbar_max_hidden` tinyint(1) NOT NULL default '0',
`expbar_disp_text` tinyint(1) NOT NULL default '0',
`expbar_string_before` varchar(64) NOT NULL default '',
`expbar_string_after` varchar(64) NOT NULL default '',
`expbar_max_string` varchar(64) NOT NULL default '',
`expbar_loc_x` smallint(6) NOT NULL default '0',
`expbar_loc_y` smallint(6) NOT NULL default '0',
`expbar_length` smallint(6) NOT NULL default '0',
`expbar_height` smallint(6) NOT NULL default '0',
`expbar_color_border` varchar(15) NOT NULL default '',
`expbar_color_bar` varchar(15) NOT NULL default '',
`expbar_color_inside` varchar(15) NOT NULL default '',
`expbar_color_maxbar` varchar(15) NOT NULL default '',
`expbar_trans_border` smallint(6) NOT NULL default '0',
`expbar_trans_bar` smallint(6) NOT NULL default '0',
`expbar_trans_inside` smallint(6) NOT NULL default '0',
`expbar_trans_maxbar` smallint(6) NOT NULL default '0',
`expbar_font_name` varchar(64) NOT NULL default '',
`expbar_font_color` varchar(15) NOT NULL default '',
`expbar_font_size` smallint(6) NOT NULL default '0',
`expbar_text_shadow` varchar(15) NOT NULL default '',
`expbar_align` varchar(6) NOT NULL default '',
`expbar_align_max` varchar(6) NOT NULL default '',
`skills_disp_primary` tinyint(1) NOT NULL default '0',
`skills_disp_secondary` tinyint(1) NOT NULL default '0',
`skills_disp_mount` tinyint(1) NOT NULL default '0',
`skills_disp_desc` tinyint(1) NOT NULL default '0',
`skills_disp_level` tinyint(1) NOT NULL default '0',
`skills_disp_levelmax` tinyint(1) NOT NULL default '0',
`skills_desc_loc_x` smallint(6) NOT NULL default '0',
`skills_desc_loc_y` smallint(6) NOT NULL default '0',
`skills_level_loc_x` smallint(6) NOT NULL default '0',
`skills_level_loc_y` smallint(6) NOT NULL default '0',
`skills_desc_length` smallint(6) NOT NULL default '0',
`skills_desc_length_mount` smallint(6) NOT NULL default '0',
`skills_align_desc` varchar(6) NOT NULL default '',
`skills_align_level` varchar(6) NOT NULL default '',
`skills_desc_linespace` smallint(6) NOT NULL default '0',
`skills_level_linespace` smallint(6) NOT NULL default '0',
`skills_gap` tinyint(3) NOT NULL default '0',
`skills_shadow` varchar(15) NOT NULL default '',
`skills_font_name` varchar(64) NOT NULL default '',
`skills_font_color` varchar(15) NOT NULL default '',
`skills_font_size` smallint(6) NOT NULL default '0',
`text_name_disp` tinyint(1) NOT NULL default '0',
`text_name_loc_x` smallint(6) NOT NULL default '0',
`text_name_loc_y` smallint(6) NOT NULL default '0',
`text_name_align` varchar(6) NOT NULL default '',
`text_name_shadow` varchar(15) NOT NULL default '',
`text_name_font_name` varchar(64) NOT NULL default '',
`text_name_font_color` varchar(15) NOT NULL default '',
`text_name_font_size` smallint(6) NOT NULL default '0',
`text_honor_disp` tinyint(1) NOT NULL default '0',
`text_honor_loc_x` smallint(6) NOT NULL default '0',
`text_honor_loc_y` smallint(6) NOT NULL default '0',
`text_honor_align` varchar(6) NOT NULL default '',
`text_honor_shadow` varchar(15) NOT NULL default '',
`text_honor_font_name` varchar(64) NOT NULL default '',
`text_honor_font_color` varchar(15) NOT NULL default '',
`text_honor_font_size` smallint(6) NOT NULL default '0',
`text_class_disp` tinyint(1) NOT NULL default '0',
`text_class_loc_x` smallint(6) NOT NULL default '0',
`text_class_loc_y` smallint(6) NOT NULL default '0',
`text_class_align` varchar(6) NOT NULL default '',
`text_class_shadow` varchar(15) NOT NULL default '',
`text_class_font_name` varchar(64) NOT NULL default '',
`text_class_font_color` varchar(15) NOT NULL default '',
`text_class_font_size` smallint(6) NOT NULL default '0',
`text_guildname_disp` tinyint(1) NOT NULL default '0',
`text_guildname_loc_x` smallint(6) NOT NULL default '0',
`text_guildname_loc_y` smallint(6) NOT NULL default '0',
`text_guildname_align` varchar(6) NOT NULL default '',
`text_guildname_shadow` varchar(15) NOT NULL default '',
`text_guildname_font_name` varchar(64) NOT NULL default '',
`text_guildname_font_color` varchar(15) NOT NULL default '',
`text_guildname_font_size` smallint(6) NOT NULL default '0',
`text_guildtitle_disp` tinyint(1) NOT NULL default '0',
`text_guildtitle_loc_x` smallint(6) NOT NULL default '0',
`text_guildtitle_loc_y` smallint(6) NOT NULL default '0',
`text_guildtitle_align` varchar(6) NOT NULL default '',
`text_guildtitle_shadow` varchar(15) NOT NULL default '',
`text_guildtitle_font_name` varchar(64) NOT NULL default '',
`text_guildtitle_font_color` varchar(15) NOT NULL default '',
`text_guildtitle_font_size` smallint(6) NOT NULL default '0',
`text_servername_disp` tinyint(1) NOT NULL default '0',
`text_servername_loc_x` smallint(6) NOT NULL default '0',
`text_servername_loc_y` smallint(6) NOT NULL default '0',
`text_servername_align` varchar(6) NOT NULL default '',
`text_servername_shadow` varchar(15) NOT NULL default '',
`text_servername_font_name` varchar(64) NOT NULL default '',
`text_servername_font_color` varchar(15) NOT NULL default '',
`text_servername_font_size` smallint(6) NOT NULL default '0',
`text_sitename_disp` tinyint(1) NOT NULL default '0',
`text_sitename_remove` tinyint(1) NOT NULL default '0',
`text_sitename_loc_x` smallint(6) NOT NULL default '0',
`text_sitename_loc_y` smallint(6) NOT NULL default '0',
`text_sitename_align` varchar(6) NOT NULL default '',
`text_sitename_shadow` varchar(15) NOT NULL default '',
`text_sitename_font_name` varchar(64) NOT NULL default '',
`text_sitename_font_color` varchar(15) NOT NULL default '',
`text_sitename_font_size` smallint(6) NOT NULL default '0',
`text_spec_disp` tinyint(1) NOT NULL default '0',
`text_spec_loc_x` smallint(6) NOT NULL default '0',
`text_spec_loc_y` smallint(6) NOT NULL default '0',
`text_spec_align` varchar(6) NOT NULL default '',
`text_spec_shadow` varchar(15) NOT NULL default '',
`text_spec_font_name` varchar(64) NOT NULL default '',
`text_spec_font_color` varchar(15) NOT NULL default '',
`text_spec_font_size` smallint(6) NOT NULL default '0',
`text_talpoints_disp` tinyint(1) NOT NULL default '0',
`text_talpoints_loc_x` smallint(6) NOT NULL default '0',
`text_talpoints_loc_y` smallint(6) NOT NULL default '0',
`text_talpoints_align` varchar(6) NOT NULL default '',
`text_talpoints_shadow` varchar(15) NOT NULL default '',
`text_talpoints_font_name` varchar(64) NOT NULL default '',
`text_talpoints_font_color` varchar(15) NOT NULL default '',
`text_talpoints_font_size` smallint(6) NOT NULL default '0',
`text_custom_disp` tinyint(1) NOT NULL default '0',
`text_custom_loc_x` smallint(6) NOT NULL default '0',
`text_custom_loc_y` smallint(6) NOT NULL default '0',
`text_custom_text` varchar(128) NOT NULL default '',
`text_custom_align` varchar(6) NOT NULL default '',
`text_custom_shadow` varchar(15) NOT NULL default '',
`text_custom_font_name` varchar(64) NOT NULL default '',
`text_custom_font_color` varchar(15) NOT NULL default '',
`text_custom_font_size` smallint(6) NOT NULL default '0',
PRIMARY KEY  (`config_id`)");


		$installer->add_query("INSERT INTO `" . $installer->table('config') . "` (`config_id`, `db_ver`, `trigger`, `guild_trigger`, `cleardir`, `main_image_size_w`, `main_image_size_h`, `image_dir`, `char_dir`, `class_dir`, `spec_dir`, `backg_dir`, `user_dir`, `pvplogo_dir`, `frame_dir`, `level_dir`, `border_dir`, `font_dir`, `link_type`, `image_order`, `default_message`, `save_images`, `save_only_mode`, `save_char_convert`, `uniup_compat`, `save_prefix`, `save_suffix`, `save_images_dir`, `save_images_format`, `etag_cache`, `image_type`, `image_quality`, `gif_dither`, `backg_disp`, `backg_fill`, `backg_fill_color`, `backg_default_image`, `backg_force_default`, `backg_data_table`, `backg_data`, `backg_translate`, `backg_search_1`, `backg_search_2`, `backg_search_3`, `backg_search_4`, `backg_search_5`, `backg_search_6`, `backg_search_7`, `backg_search_8`, `backg_search_9`, `backg_search_10`, `backg_search_11`, `backg_search_12`, `backg_file_1`, `backg_file_2`, `backg_file_3`, `backg_file_4`, `backg_file_5`, `backg_file_6`, `backg_file_7`, `backg_file_8`, `backg_file_9`, `backg_file_10`, `backg_file_11`, `backg_file_12`, `font_fullpath`, `outside_border_image`, `frames_image`, `charlogo_disp`, `charlogo_default_image`, `charlogo_loc_x`, `charlogo_loc_y`, `class_img_disp`, `class_img_loc_x`, `class_img_loc_y`, `spec_img_disp`, `spec_img_loc_x`, `spec_img_loc_y`, `pvplogo_disp`, `pvplogo_loc_x`, `pvplogo_loc_y`, `lvl_disp`, `lvl_font_name`, `lvl_font_color`, `lvl_font_size`, `lvl_text_shadow`, `lvl_loc_x`, `lvl_loc_y`, `lvl_text_loc_x`, `lvl_text_loc_y`, `lvl_image`, `expbar_disp`, `expbar_disp_bdr`, `expbar_disp_inside`, `expbar_max_disp`, `expbar_max_level`, `expbar_max_hidden`, `expbar_disp_text`, `expbar_string_before`, `expbar_string_after`, `expbar_max_string`, `expbar_loc_x`, `expbar_loc_y`, `expbar_length`, `expbar_height`, `expbar_color_border`, `expbar_color_bar`, `expbar_color_inside`, `expbar_color_maxbar`, `expbar_trans_border`, `expbar_trans_bar`, `expbar_trans_inside`, `expbar_trans_maxbar`, `expbar_font_name`, `expbar_font_color`, `expbar_font_size`, `expbar_text_shadow`, `expbar_align`, `expbar_align_max`, `skills_disp_primary`, `skills_disp_secondary`, `skills_disp_mount`, `skills_disp_desc`, `skills_disp_level`, `skills_disp_levelmax`, `skills_desc_loc_x`, `skills_desc_loc_y`, `skills_level_loc_x`, `skills_level_loc_y`, `skills_desc_length`, `skills_desc_length_mount`, `skills_align_desc`, `skills_align_level`, `skills_desc_linespace`, `skills_level_linespace`, `skills_gap`, `skills_shadow`, `skills_font_name`, `skills_font_color`, `skills_font_size`, `text_name_disp`, `text_name_loc_x`, `text_name_loc_y`, `text_name_align`, `text_name_shadow`, `text_name_font_name`, `text_name_font_color`, `text_name_font_size`, `text_honor_disp`, `text_honor_loc_x`, `text_honor_loc_y`, `text_honor_align`, `text_honor_shadow`, `text_honor_font_name`, `text_honor_font_color`, `text_honor_font_size`, `text_class_disp`, `text_class_loc_x`, `text_class_loc_y`, `text_class_align`, `text_class_shadow`, `text_class_font_name`, `text_class_font_color`, `text_class_font_size`, `text_guildname_disp`, `text_guildname_loc_x`, `text_guildname_loc_y`, `text_guildname_align`, `text_guildname_shadow`, `text_guildname_font_name`, `text_guildname_font_color`, `text_guildname_font_size`, `text_guildtitle_disp`, `text_guildtitle_loc_x`, `text_guildtitle_loc_y`, `text_guildtitle_align`, `text_guildtitle_shadow`, `text_guildtitle_font_name`, `text_guildtitle_font_color`, `text_guildtitle_font_size`, `text_servername_disp`, `text_servername_loc_x`, `text_servername_loc_y`, `text_servername_align`, `text_servername_shadow`, `text_servername_font_name`, `text_servername_font_color`, `text_servername_font_size`, `text_sitename_disp`, `text_sitename_remove`, `text_sitename_loc_x`, `text_sitename_loc_y`, `text_sitename_align`, `text_sitename_shadow`, `text_sitename_font_name`, `text_sitename_font_color`, `text_sitename_font_size`, `text_spec_disp`, `text_spec_loc_x`, `text_spec_loc_y`, `text_spec_align`, `text_spec_shadow`, `text_spec_font_name`, `text_spec_font_color`, `text_spec_font_size`, `text_talpoints_disp`, `text_talpoints_loc_x`, `text_talpoints_loc_y`, `text_talpoints_align`, `text_talpoints_shadow`, `text_talpoints_font_name`, `text_talpoints_font_color`, `text_talpoints_font_size`, `text_custom_disp`, `text_custom_loc_x`, `text_custom_loc_y`, `text_custom_text`, `text_custom_align`, `text_custom_shadow`, `text_custom_font_name`, `text_custom_font_color`, `text_custom_font_size`)"
			. " VALUES ('avatar', '1.9', 0, 0, 0, 100, 85, 'img/', 'character/default/', 'class/square/', 'spec/smallround/', 'background/defaultava/', 'members/', 'pvp/default/', 'frame/', 'level/', 'border/', 'fonts/', 'default', 'char:frames:border:class:lvl:pvp:spec', 'SigGen Works', 0, 0, 0, 0, '', '', '%sava/', 'png', 1, 'png', 85, 1, 1, 0, '#000000', 'default.png', 0, 'players', 'race', 1, 'Night Elf', 'Human', 'Gnome', 'Dwarf', 'Orc', 'Troll', 'Tauren', 'Undead', 'Draenei', 'Blood Elf', '', '', 'darnassus.png', 'stormwind.png', 'ironforge.png', 'ironforge.png', 'orgrimmar.png', 'orgrimmar.png', 'thunderbluff.png', 'undercity.png', 'exodar.png', 'silvermoon.png', '', '', 1, 'av-black.png', 'av-default.png', 1, 'default-alliance.png', 0, 0, 1, 65, 51, 1, 76, 35, 0, 3, 25, 1, 'GREY.TTF', '#FF9900', 11, '#000000', 77, 68, 11, 15, '', 1, 0, 1, 1, 70, 1, 0, '[', ']', 'Max XP', 2, 79, 62, 3, '#000000', '#0B9BFF', '#FFFFFF', '#0B9BFF', 0, 20, 80, 0, 'VERANDA.TTF', '#FFFFFF', 6, '#000000', 'center', 'center', 0, 0, 0, 1, 1, 0, 3, 48, 65, 48, 10, 20, 'left', 'right', 7, 7, 2, '#000000', 'VERANDA.TTF', '#FF9900', 6, 1, 3, 22, 'left', '#000000', 'GREY.TTF', '#FFFF66', 14, 0, 3, 53, 'left', '#000000', 'GREY.TTF', '#99FF33', 7, 0, 97, 35, 'right', '#000000', 'VERANDA.TTF', '#99FF33', 7, 1, 3, 71, 'left', '#000000', 'GREY.TTF', '#0066FF', 8, 1, 12, 8, 'left', '#000000', 'VERANDA.TTF', '#FFCC66', 6, 1, 3, 78, 'left', '#000000', 'VERANDA.TTF', '#99FF33', 6, 0, 1, 50, 82, 'center', '#000000', 'VERANDA.TTF', '#FFFFFF', 6, 1, 3, 32, 'left', '#000000', 'GREY.TTF', '#FF6600', 7, 1, 97, 32, 'right', '#000000', 'GREY.TTF', '#CCFF33', 6, 0, 3, 84, 'Custom Text', 'left', '#000000', 'VERANDA.TTF', '#FFFFFF', 6);");

		$installer->add_query("INSERT INTO `" . $installer->table('config') . "` (`config_id`, `db_ver`, `trigger`, `guild_trigger`, `cleardir`, `main_image_size_w`, `main_image_size_h`, `image_dir`, `char_dir`, `class_dir`, `spec_dir`, `backg_dir`, `user_dir`, `pvplogo_dir`, `frame_dir`, `level_dir`, `border_dir`, `font_dir`, `link_type`, `image_order`, `default_message`, `save_images`, `save_only_mode`, `save_char_convert`, `uniup_compat`, `save_prefix`, `save_suffix`, `save_images_dir`, `save_images_format`, `etag_cache`, `image_type`, `image_quality`, `gif_dither`, `backg_disp`, `backg_fill`, `backg_fill_color`, `backg_default_image`, `backg_force_default`, `backg_data_table`, `backg_data`, `backg_translate`, `backg_search_1`, `backg_search_2`, `backg_search_3`, `backg_search_4`, `backg_search_5`, `backg_search_6`, `backg_search_7`, `backg_search_8`, `backg_search_9`, `backg_search_10`, `backg_search_11`, `backg_search_12`, `backg_file_1`, `backg_file_2`, `backg_file_3`, `backg_file_4`, `backg_file_5`, `backg_file_6`, `backg_file_7`, `backg_file_8`, `backg_file_9`, `backg_file_10`, `backg_file_11`, `backg_file_12`, `font_fullpath`, `outside_border_image`, `frames_image`, `charlogo_disp`, `charlogo_default_image`, `charlogo_loc_x`, `charlogo_loc_y`, `class_img_disp`, `class_img_loc_x`, `class_img_loc_y`, `spec_img_disp`, `spec_img_loc_x`, `spec_img_loc_y`, `pvplogo_disp`, `pvplogo_loc_x`, `pvplogo_loc_y`, `lvl_disp`, `lvl_font_name`, `lvl_font_color`, `lvl_font_size`, `lvl_text_shadow`, `lvl_loc_x`, `lvl_loc_y`, `lvl_text_loc_x`, `lvl_text_loc_y`, `lvl_image`, `expbar_disp`, `expbar_disp_bdr`, `expbar_disp_inside`, `expbar_max_disp`, `expbar_max_level`, `expbar_max_hidden`, `expbar_disp_text`, `expbar_string_before`, `expbar_string_after`, `expbar_max_string`, `expbar_loc_x`, `expbar_loc_y`, `expbar_length`, `expbar_height`, `expbar_color_border`, `expbar_color_bar`, `expbar_color_inside`, `expbar_color_maxbar`, `expbar_trans_border`, `expbar_trans_bar`, `expbar_trans_inside`, `expbar_trans_maxbar`, `expbar_font_name`, `expbar_font_color`, `expbar_font_size`, `expbar_text_shadow`, `expbar_align`, `expbar_align_max`, `skills_disp_primary`, `skills_disp_secondary`, `skills_disp_mount`, `skills_disp_desc`, `skills_disp_level`, `skills_disp_levelmax`, `skills_desc_loc_x`, `skills_desc_loc_y`, `skills_level_loc_x`, `skills_level_loc_y`, `skills_desc_length`, `skills_desc_length_mount`, `skills_align_desc`, `skills_align_level`, `skills_desc_linespace`, `skills_level_linespace`, `skills_gap`, `skills_shadow`, `skills_font_name`, `skills_font_color`, `skills_font_size`, `text_name_disp`, `text_name_loc_x`, `text_name_loc_y`, `text_name_align`, `text_name_shadow`, `text_name_font_name`, `text_name_font_color`, `text_name_font_size`, `text_honor_disp`, `text_honor_loc_x`, `text_honor_loc_y`, `text_honor_align`, `text_honor_shadow`, `text_honor_font_name`, `text_honor_font_color`, `text_honor_font_size`, `text_class_disp`, `text_class_loc_x`, `text_class_loc_y`, `text_class_align`, `text_class_shadow`, `text_class_font_name`, `text_class_font_color`, `text_class_font_size`, `text_guildname_disp`, `text_guildname_loc_x`, `text_guildname_loc_y`, `text_guildname_align`, `text_guildname_shadow`, `text_guildname_font_name`, `text_guildname_font_color`, `text_guildname_font_size`, `text_guildtitle_disp`, `text_guildtitle_loc_x`, `text_guildtitle_loc_y`, `text_guildtitle_align`, `text_guildtitle_shadow`, `text_guildtitle_font_name`, `text_guildtitle_font_color`, `text_guildtitle_font_size`, `text_servername_disp`, `text_servername_loc_x`, `text_servername_loc_y`, `text_servername_align`, `text_servername_shadow`, `text_servername_font_name`, `text_servername_font_color`, `text_servername_font_size`, `text_sitename_disp`, `text_sitename_remove`, `text_sitename_loc_x`, `text_sitename_loc_y`, `text_sitename_align`, `text_sitename_shadow`, `text_sitename_font_name`, `text_sitename_font_color`, `text_sitename_font_size`, `text_spec_disp`, `text_spec_loc_x`, `text_spec_loc_y`, `text_spec_align`, `text_spec_shadow`, `text_spec_font_name`, `text_spec_font_color`, `text_spec_font_size`, `text_talpoints_disp`, `text_talpoints_loc_x`, `text_talpoints_loc_y`, `text_talpoints_align`, `text_talpoints_shadow`, `text_talpoints_font_name`, `text_talpoints_font_color`, `text_talpoints_font_size`, `text_custom_disp`, `text_custom_loc_x`, `text_custom_loc_y`, `text_custom_text`, `text_custom_align`, `text_custom_shadow`, `text_custom_font_name`, `text_custom_font_color`, `text_custom_font_size`)"
			. " VALUES ('signature', '1.9', 0, 0, 0, 400, 85, 'img/', 'character/default/', 'class/rounded/', 'spec/smallround/', 'background/defaultsig/', 'members/', 'pvp/default/', 'frame/', 'level/', 'border/', 'fonts/', 'default', 'char:frames:border:lvl:pvp:class:spec', 'SigGen Works', 0, 0, 0, 0, '', '', '%ssig/', 'png', 1, 'png', 85, 1, 1, 0, '#000000', 'default.png', 0, 'players', 'race', 1, 'Night Elf', 'Human', 'Gnome', 'Dwarf', 'Orc', 'Troll', 'Tauren', 'Undead', 'Draenei', 'Blood Elf', '', '', 'darnassus.png', 'stormwind.png', 'ironforge.png', 'ironforge.png', 'orgrimmar.png', 'orgrimmar.png', 'thunderbluff.png', 'undercity.png', 'exodar.png', 'silvermoon.png', '', '', 1, 'sig-black.png', 'default.png', 1, 'default-alliance.png', 0, 0, 1, 90, 3, 1, 80, 20, 0, 98, 38, 1, 'GREY.TTF', '#FFCC66', 9, '', 2, 60, 11, 15, 'yellow.png', 1, 0, 1, 1, 70, 1, 1, '[', ']', 'Max XP', 25, 73, 100, 7, '#000000', '#0B9BFF', '#FFFFFF', '#0B9BFF', 0, 20, 80, 0, 'VERANDA.TTF', '#000000', 6, '', 'center', 'center', 1, 1, 1, 1, 1, 0, 285, 12, 393, 12, 14, 22, 'left', 'right', 8, 8, 2, '#000000', 'VERANDA.TTF', '#FF9900', 7, 1, 204, 42, 'center', '#000000', 'GREY.TTF', '#0066FF', 22, 0, 126, 68, 'right', '#000000', 'GREY.TTF', '#99FF33', 8, 1, 124, 38, 'right', '#000000', 'GREY.TTF', '#99FF33', 8, 1, 204, 67, 'center', '#000000', 'GREY.TTF', '#FFFF66', 16, 1, 128, 18, 'left', '#000000', 'GREY.TTF', '#FF9900', 9, 1, 204, 79, 'center', '#000000', 'GREY.TTF', '#99FF33', 9, 1, 1, 394, 79, 'right', '#000000', 'VERANDA.TTF', '#FFFF66', 6, 1, 124, 48, 'right', '#000000', 'GREY.TTF', '#FF6600', 8, 1, 124, 58, 'right', '#000000', 'GREY.TTF', '#CCFF33', 7, 0, 394, 70, 'Custom Text', 'right', '#000000', 'VERANDA.TTF', '#FFFFFF', 10);");

		# Roster menu entry
		$installer->add_menu_button('menu_siggen_char','char');
		return true;
	}

	/**
	 * Upgrade Function
	 *
	 * @param string $oldversion
	 * @return bool
	 */
	function upgrade( $oldversion )
	{
		global $installer;

		/**
		 * Update adds configurable link to siggen image
		 */
		if( version_compare('0.3.0.320', $oldversion,'>') == true )
		{
			$installer->add_query("ALTER TABLE `" . $installer->table('config') . "`"
				. " ADD `link_type` varchar(64) NOT NULL default '' AFTER `font_dir`,"
				. " ADD `clear_dir` tinyint(1) NOT NULL default '0' AFTER `guild_trigger`,"
				. " DROP `save_char_convert`;");
			$installer->add_query("UPDATE `" . $installer->table('config') . "` SET `link_type` = 'default';");
			$installer->add_query("UPDATE `" . $installer->table('config') . "` SET `clear_dir` = '0';");
			$installer->add_query("UPDATE `" . $installer->table('config') . "` SET `db_ver` = '1.6';");
		}

		/**
		 * Update changes clear_dir to cleardir to fix sql error
		 */
		if( version_compare('0.3.0.352', $oldversion,'>') == true )
		{
			$installer->add_query("ALTER TABLE `" . $installer->table('config') . "`"
				. " CHANGE `clear_dir` `cleardir` tinyint(1) NOT NULL default '0';");
			$installer->add_query("UPDATE `" . $installer->table('config') . "` SET `db_ver` = '1.7';");
		}

		/**
		 * Update re-adds 'save_char_convert'
		  */
		if( version_compare('0.3.1.363', $oldversion,'>') == true )
		{
			$installer->add_query("ALTER TABLE `" . $installer->table('config') . "`"
				. " ADD `save_char_convert` tinyint(1) NOT NULL default '0' AFTER `save_only_mode`;");
			$installer->add_query("UPDATE `" . $installer->table('config') . "` SET `db_ver` = '1.8';");
			$installer->add_query("UPDATE `" . $installer->table('config') . "` SET `save_char_convert` = '0';");
		}

		/**
		 * Update adds Talent Spec Icon
		  */
		if( version_compare('0.3.2.408', $oldversion,'>') == true )
		{
			$installer->add_query("ALTER TABLE `" . $installer->table('config') . "`"
				. " ADD `spec_dir` varchar(64) NOT NULL default '' AFTER `class_dir`,"
				. " ADD `spec_img_disp` tinyint(1) NOT NULL default '0' AFTER `class_img_loc_y`,"
				. " ADD `spec_img_loc_x` smallint(6) NOT NULL default '0' AFTER `spec_img_disp`,"
				. " ADD `spec_img_loc_y` smallint(6) NOT NULL default '0' AFTER `spec_img_loc_x`;");

			$installer->add_query("UPDATE `" . $installer->table('config') . "` SET `db_ver` = '1.9';");

			$installer->add_query("UPDATE `" . $installer->table('config') . "` SET `spec_dir` = 'spec/smallround/';");
			$installer->add_query("UPDATE `" . $installer->table('config') . "` SET `spec_img_disp` = '0';");
			$installer->add_query("UPDATE `" . $installer->table('config') . "` SET `spec_img_loc_x` = '20';");
			$installer->add_query("UPDATE `" . $installer->table('config') . "` SET `spec_img_loc_y` = '20';");

			$installer->add_query("UPDATE `" . $installer->table('config') . "` SET `image_order` = CONCAT(`image_order`,':spec');");
		}

		// Nothing to upgrade from yet
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

		$installer->remove_menu_button('menu_siggen_char');

		$installer->drop_table($installer->table('config'));
		return true;
	}
}
