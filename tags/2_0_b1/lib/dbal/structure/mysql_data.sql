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
INSERT INTO `renprefix_config` VALUES (4, 'version', '1.9.9-b1', 'display', 'master');
INSERT INTO `renprefix_config` VALUES (5, 'startpage', 'main_conf', 'display', 'master');

# --------------------------------------------------------
### Menu Entries
INSERT INTO `renprefix_config` VALUES (110, 'main_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (120, 'defaults_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (130, 'menu_conf', NULL, 'page{1', 'menu');
INSERT INTO `renprefix_config` VALUES (140, 'display_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (150, 'realmstatus_conf', NULL, 'page{1', 'menu');
INSERT INTO `renprefix_config` VALUES (160, 'data_links', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (170, 'update_access', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (180, 'documentation', 'http://www.wowroster.net/MediaWiki.html', 'newlink', 'menu');

# --------------------------------------------------------
### Main Roster Config

INSERT INTO `renprefix_config` VALUES (1001, 'debug_mode', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1002, 'sql_window', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1010, 'minCPver', '2.1.1', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1020, 'minGPver', '2.1.0', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1040, 'locale', 'enUS', 'function{rosterLangValue', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1050, 'default_page', 'guild-memberslist', 'function{pageNames', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1060, 'website_address', '', 'text{128|30', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1085, 'interface_url', 'img/', 'text{128|30', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1090, 'img_suffix', 'jpg', 'select{jpg^jpg|png^png|gif^gif', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1095, 'alt_img_suffix', 'gif', 'select{jpg^jpg|png^png|gif^gif', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1100, 'img_url', 'img/', 'text{128|30', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1110, 'timezone', 'PST', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1120, 'localtimeoffset', '0', 'select{-12^-12|-11^-11|-10^-10|-9^-9|-8^-8|-7^-7|-6^-6|-5^-5|-4^-4|-3.5^-3.5|-3^-3|-2^-2|-1^-1|0^0|+1^1|+2^2|+3^3|+3.5^3.5|+4^4|+4.5^4.5|+5^5|+5.5^5.5|+6^6|+6.5^6.5|+7^7|+8^8|+9^9|+9.5^9.5|+10^10|+11^11|+12^12|+13^13', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1140, 'use_update_triggers', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1150, 'check_updates', '1', 'radio{yes^1|no^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1160, 'seo_url', '0', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1170, 'local_cache', '1', 'radio{on^1|off^0', 'main_conf');


# --------------------------------------------------------
### Guild Settings

INSERT INTO `renprefix_config` VALUES (2000, 'default_name', 'WoWRoster', 'text{50|30', 'defaults_conf');
INSERT INTO `renprefix_config` VALUES (2020, 'default_desc', 'THE original Roster for World of Warcraft', 'text{255|30', 'defaults_conf');
INSERT INTO `renprefix_config` VALUES (2040, 'alt_type', 'alt', 'text{30|30', 'defaults_conf');
INSERT INTO `renprefix_config` VALUES (2050, 'alt_location', 'note', 'select{Player Note^note|Officer Note^officer_note|Guild Rank Number^guild_rank|Guild Title^guild_title', 'defaults_conf');

# --------------------------------------------------------
### Roster Menu Settings

INSERT INTO `renprefix_config` VALUES (4000, 'menu_conf_top', NULL, 'blockframe', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4001, 'menu_conf_wide', NULL, 'page{2', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4002, 'menu_conf_left', NULL, 'blockframe', 'menu_conf_wide');
INSERT INTO `renprefix_config` VALUES (4003, 'menu_conf_right', NULL, 'blockframe', 'menu_conf_wide');
INSERT INTO `renprefix_config` VALUES (4004, 'menu_conf_bottom', NULL, 'blockframe', 'menu_conf');

INSERT INTO `renprefix_config` VALUES (4100, 'menu_top_pane', '1', 'radio{on^1|off^0', 'menu_conf_top');
INSERT INTO `renprefix_config` VALUES (4110, 'menu_top_faction', '1', 'radio{on^1|off^0', 'menu_conf_top');
INSERT INTO `renprefix_config` VALUES (4120, 'menu_top_locale', '1', 'radio{on^1|off^0', 'menu_conf_top');

INSERT INTO `renprefix_config` VALUES (4200, 'menu_left_type', 'level', 'select{Hide^|Levels^level|Class^class|Realmstatus^realm', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4210, 'menu_left_level', '30', 'text{2|10', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4220, 'menu_left_style', 'list', 'select{List^list|Bar graph^bar|Logarithmic bargraph^barlog', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4230, 'menu_left_barcolor', '#3e0000', 'color', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4240, 'menu_left_bar2color', '#003e00', 'color', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4250, 'menu_left_textcolor', '#ffffff', 'color', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4260, 'menu_left_outlinecolor', '#000000', 'color', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4270, 'menu_left_text', 'VERANDA.TTF', 'function{fontFiles', 'menu_conf_left');

INSERT INTO `renprefix_config` VALUES (4300, 'menu_right_type', 'realm', 'select{Hide^|Levels^level|Class^class|Realmstatus^realm', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4310, 'menu_right_level', '60', 'text{2|10', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4320, 'menu_right_style', 'list', 'select{List^list|Bar graph^bar|Logarithmic bargraph^barlog', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4330, 'menu_right_barcolor', '#3e0000', 'color', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4340, 'menu_right_bar2color', '#003e00', 'color', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4350, 'menu_right_textcolor', '#ffffff', 'color', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4360, 'menu_right_outlinecolor', '#000000', 'color', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4370, 'menu_right_text', 'VERANDA.TTF', 'function{fontFiles', 'menu_conf_right');

INSERT INTO `renprefix_config` VALUES (4400, 'menu_bottom_pane', '1', 'radio{on^1|off^0', 'menu_conf_bottom');

# --------------------------------------------------------
### Display Settings

INSERT INTO `renprefix_config` VALUES (5000, 'theme', 'default', 'function{templateList', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5020, 'logo', 'img/wowroster_logo.jpg', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5025, 'roster_bg', 'img/wowroster_bg.jpg', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5030, 'motd_display_mode', '1', 'radio{Image^1|Text^0', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5040, 'signaturebackground', 'img/default.png', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5050, 'processtime', '1', 'radio{on^1|off^0', 'display_conf');

# --------------------------------------------------------
### Links Settings

INSERT INTO `renprefix_config` VALUES (6100, 'profiler', 'http://www.rpgoutfitter.com/Addons/CharacterProfiler.cfm', 'text{128|30', 'data_links');
INSERT INTO `renprefix_config` VALUES (6120, 'uploadapp', 'http://www.wowroster.net/Downloads/c=2.html', 'text{128|30', 'data_links');

# --------------------------------------------------------
### Realmstatus Settings

INSERT INTO `renprefix_config` VALUES (8010, 'rs_top', NULL, 'blockframe', 'realmstatus_conf');
INSERT INTO `renprefix_config` VALUES (8020, 'rs_wide', NULL, 'page{3', 'realmstatus_conf');
INSERT INTO `renprefix_config` VALUES (8030, 'rs_left', NULL, 'blockframe', 'rs_wide');
INSERT INTO `renprefix_config` VALUES (8040, 'rs_middle', NULL, 'blockframe', 'rs_wide');
INSERT INTO `renprefix_config` VALUES (8050, 'rs_right', NULL, 'blockframe', 'rs_wide');

INSERT INTO `renprefix_config` VALUES (8100, 'rs_display', 'full', 'select{full^full|half^half', 'rs_top');
INSERT INTO `renprefix_config` VALUES (8110, 'rs_mode', '1', 'radio{Image^1|DIV Container^0', 'rs_top');
INSERT INTO `renprefix_config` VALUES (8120, 'rs_timer', '10', 'text{5|5', 'rs_top');

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
INSERT INTO `renprefix_config` VALUES (10010, 'gp_user_level', '2', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10020, 'cp_user_level', '0', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10030, 'lua_user_level', '0', 'access', 'update_access');

# --------------------------------------------------------
### Menu table entries
INSERT INTO `renprefix_menu` VALUES (1, 'util', 'b1:b2:b3');
INSERT INTO `renprefix_menu` VALUES (2, 'realm', '');
INSERT INTO `renprefix_menu` VALUES (3, 'guild', '');
INSERT INTO `renprefix_menu` VALUES (4, 'char', '');

# --------------------------------------------------------
### Menu Button entries
INSERT INTO `renprefix_menu_button` VALUES (1, 0, 'menu_roster_cp', 'util', 'rostercp', 'inv_misc_gear_02');
INSERT INTO `renprefix_menu_button` VALUES (2, 0, 'menu_credits', 'util', 'credits', 'inv_egg_05');
INSERT INTO `renprefix_menu_button` VALUES (3, 0, 'menu_search', 'util', 'search', 'inv_misc_spyglass_02');
