#
# MySQL Roster Data File
#
# * $Id$
#
# --------------------------------------------------------
### Data

# --------------------------------------------------------
### Master Values

INSERT INTO `renprefix_config` VALUES (3, 'roster_dbver', '6', 'display', 'master');
INSERT INTO `renprefix_config` VALUES (4, 'version', '1.8.0dev', 'display', 'master');
INSERT INTO `renprefix_config` VALUES (5, 'startpage', 'main_conf', 'display', 'master');

# --------------------------------------------------------
### Menu Entries
INSERT INTO `renprefix_config` VALUES (110, 'main_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (120, 'guild_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (130, 'menu_conf', NULL, 'page{1', 'menu');
INSERT INTO `renprefix_config` VALUES (140, 'display_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (150, 'char_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (160, 'realmstatus_conf', NULL, 'page{1', 'menu');
INSERT INTO `renprefix_config` VALUES (170, 'data_links', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (180, 'update_access', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (190, 'documentation', 'http://www.wowroster.net/wiki', 'newlink', 'menu');

# --------------------------------------------------------
### Main Roster Config

INSERT INTO `renprefix_config` VALUES (1000, 'sqldebug', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1001, 'debug_mode', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1002, 'sql_window', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1010, 'minCPver', '2.0.0', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1020, 'minGPver', '2.0.0', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1030, 'minPvPLogver', '0.6.1', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1040, 'roster_lang', 'enUS', 'function{rosterLangValue', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1050, 'default_page', 'members', 'function{pageNames', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1060, 'website_address', '', 'text{128|30', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1085, 'interface_url', 'img/', 'text{128|30', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1090, 'img_suffix', 'jpg', 'select{jpg^jpg|png^png|gif^gif', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1095, 'alt_img_suffix', 'gif', 'select{jpg^jpg|png^png|gif^gif', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1100, 'img_url', 'img/', 'text{128|30', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1110, 'timezone', 'PST', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1120, 'localtimeoffset', '0', 'select{-12^-12|-11^-11|-10^-10|-9^-9|-8^-8|-7^-7|-6^-6|-5^-5|-4^-4|-3.5^-3.5|-3^-3|-2^-2|-1^-1|0^0|+1^1|+2^2|+3^3|+3.5^3.5|+4^4|+4.5^4.5|+5^5|+5.5^5.5|+6^6|+6.5^6.5|+7^7|+8^8|+9^9|+9.5^9.5|+10^10|+11^11|+12^12|+13^13', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1130, 'pvp_log_allow', '1', 'radio{yes^1|no^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1140, 'use_update_triggers', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1150, 'check_updates', '1', 'radio{yes^1|no^0', 'main_conf');

# --------------------------------------------------------
### Guild Settings

INSERT INTO `renprefix_config` VALUES (2000, 'guild_name', 'guildName', 'text{50|30', 'guild_conf');
INSERT INTO `renprefix_config` VALUES (2010, 'server_name', 'realmName', 'text{50|30', 'guild_conf');
INSERT INTO `renprefix_config` VALUES (2020, 'guild_desc', 'A Great WoW Guild', 'text{255|30', 'guild_conf');
INSERT INTO `renprefix_config` VALUES (2030, 'server_type', 'PvE', 'select{PvE^PvE|PvP^PvP|RP^RP|RPPvP^RPPvP', 'guild_conf');
INSERT INTO `renprefix_config` VALUES (2040, 'alt_type', 'alt', 'text{30|30', 'guild_conf');
INSERT INTO `renprefix_config` VALUES (2050, 'alt_location', 'note', 'select{Player Note^note|Officer Note^officer_note|Guild Rank Number^guild_rank|Guild Title^guild_title', 'guild_conf');

# --------------------------------------------------------
### Roster Menu Settings

INSERT INTO `renprefix_config` VALUES (4000, 'menu_conf_top', NULL, 'blockframe', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4001, 'menu_conf_wide', NULL, 'page{2', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4002, 'menu_conf_left', NULL, 'blockframe', 'menu_conf_wide');
INSERT INTO `renprefix_config` VALUES (4003, 'menu_conf_right', NULL, 'blockframe', 'menu_conf_wide');

INSERT INTO `renprefix_config` VALUES (4020, 'menu_top_pane', '1', 'radio{on^1|off^0', 'menu_conf_top');

INSERT INTO `renprefix_config` VALUES (4200, 'menu_left_type', 'level', 'select{Hide^|Levels^level|Class^class|Realmstatus^realm', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4210, 'menu_left_level', '30', 'text{2|10', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4220, 'menu_left_style', 'list', 'select{List^list|Bar graph^bar|Logarithmic bargraph^barlog', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4230, 'menu_left_barcolor', '#3e0000', 'color', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4240, 'menu_left_bar2color', '#003e00', 'color', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4250, 'menu_left_textcolor', '#ffffff', 'color', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4260, 'menu_left_outlinecolor', '#000000', 'color', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4270, 'menu_left_text', 'VERANDA.TTF', 'function{fontFiles', 'menu_conf_left');

INSERT INTO `renprefix_config` VALUES (4400, 'menu_right_type', 'realm', 'select{Hide^|Levels^level|Class^class|Realmstatus^realm', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4410, 'menu_right_level', '60', 'text{2|10', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4420, 'menu_right_style', 'list', 'select{List^list|Bar graph^bar|Logarithmic bargraph^barlog', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4430, 'menu_right_barcolor', '#3e0000', 'color', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4440, 'menu_right_bar2color', '#003e00', 'color', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4450, 'menu_right_textcolor', '#ffffff', 'color', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4460, 'menu_right_outlinecolor', '#000000', 'color', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4470, 'menu_right_text', 'VERANDA.TTF', 'function{fontFiles', 'menu_conf_right');

# --------------------------------------------------------
### Display Settings

INSERT INTO `renprefix_config` VALUES (5020, 'logo', 'img/wowroster_logo.jpg', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5025, 'roster_bg', 'img/wowroster_bg.jpg', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5030, 'motd_display_mode', '1', 'radio{Image^1|Text^0', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5040, 'signaturebackground', 'img/default.png', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5050, 'processtime', '1', 'radio{on^1|off^0', 'display_conf');

# --------------------------------------------------------
### Links Settings

INSERT INTO `renprefix_config` VALUES (6000, 'questlink_1', '1', 'radio{on^1|off^0', 'data_links');
INSERT INTO `renprefix_config` VALUES (6010, 'questlink_2', '1', 'radio{on^1|off^0', 'data_links');
INSERT INTO `renprefix_config` VALUES (6020, 'questlink_3', '1', 'radio{on^1|off^0', 'data_links');
INSERT INTO `renprefix_config` VALUES (6100, 'profiler', 'http://www.rpgoutfitter.com/Addons/CharacterProfiler.cfm', 'text{128|30', 'data_links');
INSERT INTO `renprefix_config` VALUES (6110, 'pvplogger', 'http://www.wowroster.net/Downloads/details/id=51.html', 'text{128|30', 'data_links');
INSERT INTO `renprefix_config` VALUES (6120, 'uploadapp', 'http://www.wowroster.net/Downloads/c=2.html', 'text{128|30', 'data_links');

# --------------------------------------------------------
### Character Page Settings

INSERT INTO `renprefix_config` VALUES (7000, 'char_bodyalign', 'center', 'select{left^left|center^center|right^right', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7005, 'recipe_disp', '0', 'radio{show^1|collapse^0', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7015, 'show_talents', '2', 'radio{on^1|off^0|user^2', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7020, 'show_spellbook', '2', 'radio{on^1|off^0|user^2', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7030, 'show_mail', '2', 'radio{on^1|off^0|user^2', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7040, 'show_inventory', '2', 'radio{on^1|off^0|user^2', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7050, 'show_money', '2', 'radio{on^1|off^0|user^2', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7060, 'show_bank', '2', 'radio{on^1|off^0|user^2', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7070, 'show_recipes', '2', 'radio{on^1|off^0|user^2', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7080, 'show_quests', '2', 'radio{on^1|off^0|user^2', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7090, 'show_bg', '2', 'radio{on^1|off^0|user^2', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7100, 'show_pvp', '2', 'radio{on^1|off^0|user^2', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7110, 'show_duels', '2', 'radio{on^1|off^0|user^2', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7120, 'show_item_bonuses', '2', 'radio{on^1|off^0|user^2', 'char_conf');

# --------------------------------------------------------
### Realmstatus Settings

INSERT INTO `renprefix_config` VALUES (8010, 'rs_top', NULL, 'blockframe', 'realmstatus_conf');
INSERT INTO `renprefix_config` VALUES (8020, 'rs_wide', NULL, 'page{3', 'realmstatus_conf');
INSERT INTO `renprefix_config` VALUES (8030, 'rs_left', NULL, 'blockframe', 'rs_wide');
INSERT INTO `renprefix_config` VALUES (8040, 'rs_middle', NULL, 'blockframe', 'rs_wide');
INSERT INTO `renprefix_config` VALUES (8050, 'rs_right', NULL, 'blockframe', 'rs_wide');

INSERT INTO `renprefix_config` VALUES (8100, 'realmstatus_url', 'http://www.worldofwarcraft.com/realmstatus/status.xml', 'select{US Servers^http://www.worldofwarcraft.com/realmstatus/status.xml|EU Servers^http://www.wow-europe.com/en/serverstatus/index.xml', 'rs_top');
INSERT INTO `renprefix_config` VALUES (8110, 'rs_display', 'full', 'select{full^full|half^half', 'rs_top');
INSERT INTO `renprefix_config` VALUES (8120, 'rs_mode', '1', 'radio{Image^1|DIV Container^0', 'rs_top');
INSERT INTO `renprefix_config` VALUES (8130, 'realmstatus', '', 'text{50|30', 'rs_top');
INSERT INTO `renprefix_config` VALUES (8140, 'rs_timer', '10', 'text{5|5', 'rs_top');

INSERT INTO `renprefix_config` VALUES (8200, 'rs_font_server', 'VERANDA.TTF', 'function{fontFiles', 'rs_left');
INSERT INTO `renprefix_config` VALUES (8210, 'rs_size_server', '7', 'text{5|5', 'rs_left');
INSERT INTO `renprefix_config` VALUES (8220, 'rs_color_server', '#000000', 'color', 'rs_left');
INSERT INTO `renprefix_config` VALUES (8230, 'rs_color_shadow', '#95824e', 'color', 'rs_left');

INSERT INTO `renprefix_config` VALUES (8300, 'rs_font_type', 'silkscreenb.ttf', 'function{fontFiles', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8310, 'rs_size_type', '6', 'text{5|5', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8320, 'rs_color_rppvp', '#535600', 'color', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8330, 'rs_color_pve', '#234303', 'color', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8340, 'rs_color_pvp', '#660D02', 'color', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8350, 'rs_color_rp', '#535600', 'color', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8360, 'rs_color_unknown', '#860D02', 'color', 'rs_middle');

INSERT INTO `renprefix_config` VALUES (8400, 'rs_font_pop', 'GREY.TTF', 'function{fontFiles', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8410, 'rs_size_pop', '11', 'text{5|5', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8420, 'rs_color_low', '#234303', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8430, 'rs_color_medium', '#535600', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8440, 'rs_color_high', '#660D02', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8450, 'rs_color_max', '#860D02', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8460, 'rs_color_error', '#860D02', 'color', 'rs_right');


# --------------------------------------------------------
### Update Access

INSERT INTO `renprefix_config` VALUES (10000, 'authenticated_user', '1', 'radio{enable^1|disable^0', 'update_access');

# --------------------------------------------------------
### Menu table entries
INSERT INTO `renprefix_menu` VALUES (1, 'main', 'b1:b2|b3:b4');

# --------------------------------------------------------
### Menu Button entries
INSERT INTO `renprefix_menu_button` VALUES (1, 0, 'pvplist', 'guildpvp');
INSERT INTO `renprefix_menu_button` VALUES (2, 0, 'upprofile', 'update');
INSERT INTO `renprefix_menu_button` VALUES (3, 0, 'roster_cp_ab', 'rostercp');
INSERT INTO `renprefix_menu_button` VALUES (4, 0, 'credit', 'credits');
