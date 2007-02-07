<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /inc/sql.inc
 *
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Legal Information:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 *
 * Full License:
 *  license.txt (Included within this library)
 *
 * You should have recieved a FULL copy of this license in license.txt
 * along with this library, if you did not and you are unable to find
 * and agree to the license you may not use this library.
 *
 * For questions, comments, information and documentation please visit
 * the official website at cpframework.org
 *
 * @link http://www.wowroster.net
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author Joshua Clark
 * @version $Id:$
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$create_sql['install'][] = "DROP TABLE IF EXISTS `".ROSTER_SIGCONFIGTABLE."`;";
$create_sql['install'][] = "
CREATE TABLE `".ROSTER_SIGCONFIGTABLE."` (
  `config_id` varchar(20) NOT NULL default '',
  `db_ver` varchar(6) NOT NULL default '',
  `trigger` tinyint(1) NOT NULL default '0',
  `guild_trigger` tinyint(1) NOT NULL default '0',
  `main_image_size_w` smallint(6) NOT NULL default '0',
  `main_image_size_h` smallint(6) NOT NULL default '0',
  `image_dir` varchar(64) NOT NULL default '',
  `char_dir` varchar(64) NOT NULL default '',
  `class_dir` varchar(64) NOT NULL default '',
  `backg_dir` varchar(64) NOT NULL default '',
  `user_dir` varchar(64) NOT NULL default '',
  `pvplogo_dir` varchar(64) NOT NULL default '',
  `frame_dir` varchar(64) NOT NULL,
  `level_dir` varchar(64) NOT NULL,
  `border_dir` varchar(64) NOT NULL,
  `font_dir` varchar(64) NOT NULL default '',
  `image_order` varchar(128) NOT NULL default '',
  `default_message` varchar(64) NOT NULL,
  `save_images` tinyint(1) NOT NULL default '0',
  `save_only_mode` tinyint(1) NOT NULL default '0',
  `uniup_compat` tinyint(1) NOT NULL default '0',
  `save_prefix` varchar(32) NOT NULL default '',
  `save_suffix` varchar(32) NOT NULL default '',
  `save_images_dir` varchar(64) NOT NULL default '',
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
  `text_custom_disp` tinyint(1) NOT NULL default '0',
  `text_custom_loc_x` smallint(6) NOT NULL default '0',
  `text_custom_loc_y` smallint(6) NOT NULL default '0',
  `text_custom_text` varchar(128) NOT NULL default '',
  `text_custom_align` varchar(6) NOT NULL default '',
  `text_custom_shadow` varchar(15) NOT NULL default '',
  `text_custom_font_name` varchar(64) NOT NULL default '',
  `text_custom_font_color` varchar(15) NOT NULL default '',
  `text_custom_font_size` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`config_id`)
) TYPE=MyISAM;";


$create_sql['insert']['avatar']    = "INSERT INTO `".ROSTER_SIGCONFIGTABLE."` VALUES ('avatar', '1.3', 0, 0, 100, 85, 'img/', 'character/default/', 'class/square/', 'background/defaultava/', 'members/', 'pvp/default/', 'frame/', 'level/', 'border/', 'fonts/', 'char:frames:border:class:lvl:pvp', 'SigGen Works', 0, 0, 0, '', '', 'ava/', 'png', 1, 'png', 85, 1, 1, 0, '#000000', 'default.png', 0, 'players', 'race', 1, 'Night Elf', 'Human', 'Gnome', 'Dwarf', 'Orc', 'Troll', 'Tauren', 'Undead', 'Draenei', 'Blood Elf', '', '', 'darnassus.png', 'stormwind.png', 'ironforge.png', 'ironforge.png', 'orgrimmar.png', 'orgrimmar.png', 'thunderbluff.png', 'undercity.png', 'exodar.png', 'silvermoon.png', '', '', 1, 'av-black.png', '', 1, 'default-alliance.png', 0, 0, 1, 65, 51, 0, 3, 25, 1, 'GREY.TTF', '#FF9900', 11, '#000000', 77, 68, 11, 15, '', 1, 0, 1, 1, 70, 1, 0, '[', ']', 'Max XP', 2, 79, 62, 3, '#000000', '#0B9BFF', '#FFFFFF', '#0B9BFF', 0, 20, 80, 0, 'VERANDA.TTF', '#FFFFFF', 6, '#000000', 'center', 'center', 0, 0, 0, 1, 1, 0, 3, 48, 65, 48, 10, 20, 'left', 'right', 7, 7, 2, '#000000', 'VERANDA.TTF', '#FF9900', 6, 1, 3, 22, 'left', '#000000', 'GREY.TTF', '#FFFF66', 14, 0, 3, 53, 'left', '#000000', 'GREY.TTF', '#99FF33', 7, 0, 97, 35, 'right', '#000000', 'VERANDA.TTF', '#99FF33', 7, 1, 3, 71, 'left', '#000000', 'GREY.TTF', '#0066FF', 8, 1, 12, 8, 'left', '#000000', 'VERANDA.TTF', '#FFCC66', 6, 1, 3, 78, 'left', '#000000', 'VERANDA.TTF', '#99FF33', 6, 0, 1, 50, 82, 'center', '#000000', 'VERANDA.TTF', '#FFFFFF', 6, 0, 3, 84, 'Custom Text', 'left', '#000000', 'VERANDA.TTF', '#FFFFFF', 6);";
$create_sql['insert']['signature'] = "INSERT INTO `".ROSTER_SIGCONFIGTABLE."` VALUES ('signature', '1.3', 0, 0, 400, 85, 'img/', 'character/default/', 'class/rounded/', 'background/defaultsig/', 'members/', 'pvp/default/', 'frame/', 'level/', 'border/', 'fonts/', 'char:frames:border:lvl:pvp:class', 'SigGen Works', 0, 0, 0, '', '', 'sig/', 'png', 1, 'png', 85, 1, 1, 0, '#000000', 'default.png', 0, 'players', 'race', 1, 'Night Elf', 'Human', 'Gnome', 'Dwarf', 'Orc', 'Troll', 'Tauren', 'Undead', 'Draenei', 'Blood Elf', '', '', 'darnassus.png', 'stormwind.png', 'ironforge.png', 'ironforge.png', 'orgrimmar.png', 'orgrimmar.png', 'thunderbluff.png', 'undercity.png', 'exodar.png', 'silvermoon.png', '', '', 1, 'sig-black.png', 'default.png', 1, 'default-alliance.png', 0, 0, 1, 90, 3, 0, 98, 38, 1, 'GREY.TTF', '#FFCC66', 9, '', 2, 60, 11, 15, 'yellow.png', 1, 0, 1, 1, 70, 1, 1, '[', ']', 'Max XP', 25, 73, 100, 7, '#000000', '#0B9BFF', '#FFFFFF', '#0B9BFF', 0, 20, 80, 0, 'VERANDA.TTF', '#000000', 6, '', 'center', 'center', 1, 1, 1, 1, 1, 0, 285, 12, 393, 12, 14, 22, 'left', 'right', 8, 8, 2, '#000000', 'VERANDA.TTF', '#FF9900', 7, 1, 204, 42, 'center', '#000000', 'GREY.TTF', '#0066FF', 22, 0, 126, 68, 'right', '#000000', 'GREY.TTF', '#99FF33', 8, 1, 124, 38, 'right', '#000000', 'GREY.TTF', '#99FF33', 8, 1, 204, 67, 'center', '#000000', 'GREY.TTF', '#FFFF66', 16, 1, 128, 18, 'left', '#000000', 'GREY.TTF', '#FF9900', 9, 1, 204, 79, 'center', '#000000', 'GREY.TTF', '#99FF33', 9, 1, 1, 394, 79, 'right', '#000000', 'VERANDA.TTF', '#FFFF66', 6, 0, 5, 15, 'Custom Text Message', 'left', '#000000', 'VERANDA.TTF', '#FFFFFF', 10);";

$create_sql['reset']['avatar']    = "REPLACE INTO `".ROSTER_SIGCONFIGTABLE."` VALUES ('avatar', '1.3', 0, 0, 100, 85, 'img/', 'character/default/', 'class/square/', 'background/defaultava/', 'members/', 'pvp/default/', 'frame/', 'level/', 'border/', 'fonts/', 'char:frames:border:class:lvl:pvp', 'SigGen Works', 0, 0, 0, '', '', 'ava/', 'png', 1, 'png', 85, 1, 1, 0, '#000000', 'default.png', 0, 'players', 'race', 1, 'Night Elf', 'Human', 'Gnome', 'Dwarf', 'Orc', 'Troll', 'Tauren', 'Undead', 'Draenei', 'Blood Elf', '', '', 'darnassus.png', 'stormwind.png', 'ironforge.png', 'ironforge.png', 'orgrimmar.png', 'orgrimmar.png', 'thunderbluff.png', 'undercity.png', 'exodar.png', 'silvermoon.png', '', '', 1, 'av-black.png', '', 1, 'default-alliance.png', 0, 0, 1, 65, 51, 0, 3, 25, 1, 'GREY.TTF', '#FF9900', 11, '#000000', 77, 68, 11, 15, '', 1, 0, 1, 1, 70, 1, 0, '[', ']', 'Max XP', 2, 79, 62, 3, '#000000', '#0B9BFF', '#FFFFFF', '#0B9BFF', 0, 20, 80, 0, 'VERANDA.TTF', '#FFFFFF', 6, '#000000', 'center', 'center', 0, 0, 0, 1, 1, 0, 3, 48, 65, 48, 10, 20, 'left', 'right', 7, 7, 2, '#000000', 'VERANDA.TTF', '#FF9900', 6, 1, 3, 22, 'left', '#000000', 'GREY.TTF', '#FFFF66', 14, 0, 3, 53, 'left', '#000000', 'GREY.TTF', '#99FF33', 7, 0, 97, 35, 'right', '#000000', 'VERANDA.TTF', '#99FF33', 7, 1, 3, 71, 'left', '#000000', 'GREY.TTF', '#0066FF', 8, 1, 12, 8, 'left', '#000000', 'VERANDA.TTF', '#FFCC66', 6, 1, 3, 78, 'left', '#000000', 'VERANDA.TTF', '#99FF33', 6, 0, 1, 50, 82, 'center', '#000000', 'VERANDA.TTF', '#FFFFFF', 6, 0, 3, 84, 'Custom Text', 'left', '#000000', 'VERANDA.TTF', '#FFFFFF', 6);";
$create_sql['reset']['signature'] = "REPLACE INTO `".ROSTER_SIGCONFIGTABLE."` VALUES ('signature', '1.3', 0, 0, 400, 85, 'img/', 'character/default/', 'class/rounded/', 'background/defaultsig/', 'members/', 'pvp/default/', 'frame/', 'level/', 'border/', 'fonts/', 'char:frames:border:lvl:pvp:class', 'SigGen Works', 0, 0, 0, '', '', 'sig/', 'png', 1, 'png', 85, 1, 1, 0, '#000000', 'default.png', 0, 'players', 'race', 1, 'Night Elf', 'Human', 'Gnome', 'Dwarf', 'Orc', 'Troll', 'Tauren', 'Undead', 'Draenei', 'Blood Elf', '', '', 'darnassus.png', 'stormwind.png', 'ironforge.png', 'ironforge.png', 'orgrimmar.png', 'orgrimmar.png', 'thunderbluff.png', 'undercity.png', 'exodar.png', 'silvermoon.png', '', '', 1, 'sig-black.png', 'default.png', 1, 'default-alliance.png', 0, 0, 1, 90, 3, 0, 98, 38, 1, 'GREY.TTF', '#FFCC66', 9, '', 2, 60, 11, 15, 'yellow.png', 1, 0, 1, 1, 70, 1, 1, '[', ']', 'Max XP', 25, 73, 100, 7, '#000000', '#0B9BFF', '#FFFFFF', '#0B9BFF', 0, 20, 80, 0, 'VERANDA.TTF', '#000000', 6, '', 'center', 'center', 1, 1, 1, 1, 1, 0, 285, 12, 393, 12, 14, 22, 'left', 'right', 8, 8, 2, '#000000', 'VERANDA.TTF', '#FF9900', 7, 1, 204, 42, 'center', '#000000', 'GREY.TTF', '#0066FF', 22, 0, 126, 68, 'right', '#000000', 'GREY.TTF', '#99FF33', 8, 1, 124, 38, 'right', '#000000', 'GREY.TTF', '#99FF33', 8, 1, 204, 67, 'center', '#000000', 'GREY.TTF', '#FFFF66', 16, 1, 128, 18, 'left', '#000000', 'GREY.TTF', '#FF9900', 9, 1, 204, 79, 'center', '#000000', 'GREY.TTF', '#99FF33', 9, 1, 1, 394, 79, 'right', '#000000', 'VERANDA.TTF', '#FFFF66', 6, 0, 5, 15, 'Custom Text Message', 'left', '#000000', 'VERANDA.TTF', '#FFFFFF', 10);";


/**
 * Upgrade from 1.1 to 1.2
 */
$create_sql['upgrade_1.1'][] = "
ALTER TABLE `".ROSTER_SIGCONFIGTABLE."`
  DROP `font1`,
  DROP `font2`,
  DROP `font3`,
  DROP `font4`,
  DROP `font5`,
  DROP `font6`,
  DROP `font7`,
  DROP `font8`,
  DROP `color1`,
  DROP `color2`,
  DROP `color3`,
  DROP `color4`,
  DROP `color5`,
  DROP `color6`,
  DROP `color7`,
  DROP `color8`,
  DROP `color9`,
  DROP `color10`,
  CHANGE `image_dir` `image_dir` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `char_dir` `char_dir` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `class_dir` `class_dir` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_dir` `backg_dir` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `user_dir` `user_dir` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `pvplogo_dir` `pvplogo_dir` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `font_dir` `font_dir` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `save_prefix` `save_prefix` VARCHAR(32) NOT NULL DEFAULT '',
  CHANGE `save_suffix` `save_suffix` VARCHAR(32) NOT NULL DEFAULT '',
  CHANGE `save_images_dir` `save_images_dir` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_default_image` `backg_default_image` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_data_table` `backg_data_table` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_data` `backg_data` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_search_1` `backg_search_1` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_search_2` `backg_search_2` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_search_3` `backg_search_3` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_search_4` `backg_search_4` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_search_5` `backg_search_5` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_search_6` `backg_search_6` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_search_7` `backg_search_7` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_search_8` `backg_search_8` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_search_9` `backg_search_9` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_search_10` `backg_search_10` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_search_11` `backg_search_11` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_search_12` `backg_search_12` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_file_1` `backg_file_1` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_file_2` `backg_file_2` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_file_3` `backg_file_3` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_file_4` `backg_file_4` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_file_5` `backg_file_5` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_file_6` `backg_file_6` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_file_7` `backg_file_7` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_file_8` `backg_file_8` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_file_9` `backg_file_9` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_file_10` `backg_file_10` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_file_11` `backg_file_11` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `backg_file_12` `backg_file_12` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `outside_border_image` `outside_border_image` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `frames_image` `frames_image` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `charlogo_default_image` `charlogo_default_image` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `lvl_font_name` `lvl_font_name` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `lvl_image` `lvl_image` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `expbar_string_before` `expbar_string_before` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `expbar_string_after` `expbar_string_after` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `expbar_max_string` `expbar_max_string` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `expbar_font_name` `expbar_font_name` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `skills_font_name` `skills_font_name` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `text_name_font_name` `text_name_font_name` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `text_honor_font_name` `text_honor_font_name` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `text_class_font_name` `text_class_font_name` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `text_guildname_font_name` `text_guildname_font_name` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `text_guildtitle_font_name` `text_guildtitle_font_name` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `text_servername_font_name` `text_servername_font_name` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `text_sitename_font_name` `text_sitename_font_name` VARCHAR(64) NOT NULL DEFAULT '',
  CHANGE `text_custom_text` `text_custom_text` VARCHAR(128) NOT NULL DEFAULT '',
  CHANGE `text_custom_font_name` `text_custom_font_name` VARCHAR(64) NOT NULL,
  ADD `default_message` VARCHAR( 64 ) NOT NULL DEFAULT '' AFTER `image_order`,
  ADD `frame_dir` VARCHAR( 64 ) NOT NULL DEFAULT '' AFTER `pvplogo_dir`,
  ADD `level_dir` VARCHAR( 64 ) NOT NULL DEFAULT '' AFTER `frame_dir`,
  ADD `border_dir` VARCHAR( 64 ) NOT NULL DEFAULT '' AFTER `level_dir` ;";

$create_sql['upgrade_1.1'][] = "UPDATE `".ROSTER_SIGCONFIGTABLE."` SET `db_ver` = '1.2';";


/**
 * Upgrade from 1.2 to 1.3
 */
$create_sql['upgrade_1.2'][] = "
ALTER TABLE `".ROSTER_SIGCONFIGTABLE."`
  ADD `uniup_compat` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `save_only_mode`;";

$create_sql['upgrade_1.2'][] = "UPDATE `".ROSTER_SIGCONFIGTABLE."` SET
  `expbar_max_level` = '70',
  `backg_search_9` = 'Draenei',
  `backg_file_9` = 'exodar.png',
  `backg_search_10` = 'Blood Elf',
  `backg_file_10` = 'silvermoon.png',
  `uniup_compat` = '0',
  `db_ver` = '1.3';";
