#
# MySQL Roster Data File
#
# * $Id$
#
# --------------------------------------------------------
### Data

# --------------------------------------------------------
### Master Values

INSERT INTO `renprefix_config` VALUES (1, 'config_list', 'main_conf|guild_conf|menu_conf|display_conf|index_conf|char_conf|realmstatus_conf|data_links|guildbank_conf|update_access', 'display', 'master');
INSERT INTO `renprefix_config` VALUES (2, 'roster_upd_pw', '', 'password:30|30', 'master');
INSERT INTO `renprefix_config` VALUES (3, 'roster_dbver', '5', 'display', 'master');
INSERT INTO `renprefix_config` VALUES (4, 'version', '1.7.3', 'display', 'master');

# --------------------------------------------------------
### Main Roster Config

INSERT INTO `renprefix_config` VALUES (1000, 'sqldebug', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1001, 'debug_mode', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1002, 'sql_window', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1010, 'minCPver', '2.0.0', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1020, 'minGPver', '2.0.0', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1030, 'minPvPLogver', '0.6.1', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1040, 'roster_lang', 'enUS', 'function{rosterLangValue', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1060, 'website_address', '', 'text{128|30', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1070, 'roster_dir', '/roster', 'text{128|30', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1080, 'server_name_comp', '0', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1085, 'interface_url', 'img/', 'text{128|30', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1090, 'img_suffix', 'jpg', 'select{jpg^jpg|png^png|gif^gif', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1095, 'alt_img_suffix', 'gif', 'select{jpg^jpg|png^png|gif^gif', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1100, 'img_url', 'img/', 'text{128|30', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1110, 'timezone', 'PST', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1120, 'localtimeoffset', '0', 'select{-12^-12|-11^-11|-10^-10|-9^-9|-8^-8|-7^-7|-6^-6|-5^-5|-4^-4|-3.5^-3.5|-3^-3|-2^-2|-1^-1|0^0|+1^1|+2^2|+3^3|+3.5^3.5|+4^4|+4.5^4.5|+5^5|+5.5^5.5|+6^6|+6.5^6.5|+7^7|+8^8|+9^9|+9.5^9.5|+10^10|+11^11|+12^12|+13^13', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1130, 'pvp_log_allow', '1', 'radio{yes^1|no^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1140, 'use_update_triggers', '1', 'radio{on^1|off^0', 'main_conf');

# --------------------------------------------------------
### Guild Settings

INSERT INTO `renprefix_config` VALUES (2000, 'guild_name', 'guildName', 'text{50|30', 'guild_conf');
INSERT INTO `renprefix_config` VALUES (2010, 'server_name', 'realmName', 'text{50|30', 'guild_conf');
INSERT INTO `renprefix_config` VALUES (2020, 'guild_desc', 'A Great WoW Guild', 'text{255|30', 'guild_conf');
INSERT INTO `renprefix_config` VALUES (2030, 'server_type', 'PvE', 'select{PvE^PvE|PvP^PvP|RP^RP|RPPvP^RPPvP', 'guild_conf');
INSERT INTO `renprefix_config` VALUES (2040, 'alt_type', 'alt', 'text{30|30', 'guild_conf');
INSERT INTO `renprefix_config` VALUES (2050, 'alt_location', 'note', 'select{Player Note^note|Officer Note^officer_note|Guild Rank Number^guild_rank|Guild Title^guild_title', 'guild_conf');

# --------------------------------------------------------
### Index Page

INSERT INTO `renprefix_config` VALUES (3000, 'index_pvplist', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3010, 'index_hslist', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3015, 'hspvp_list_disp', 'show', 'radio{show^show|hide^hide', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3020, 'index_member_tooltip', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3030, 'index_update_inst', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3040, 'index_sort', '', 'select{Default Sort^|Name^name|Class^class|Level^level|Guild Title^guild_title|PvP Rank^RankName|Note^note|Hearthstone Location^hearth|Zone Location^zone', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3050, 'index_motd', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3060, 'index_level_bar', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3070, 'index_iconsize', '16', 'select{8px^8|9px^9|10px^10|11px^11|12px^12|13px^13|14px^14|15px^15|16px^16|17px^17|18px^18|19px^19|20px^20', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3080, 'index_tradeskill_icon', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3090, 'index_tradeskill_loc', 'professions', 'select{Name^name|Class^class|Level^level|Guild Title^guild_title|PvP Rank^RankName|Note^note|Professions^professions|Hearthed^hearth|Last Zone^zone|Last On-line^lastonline|Last Updated^last_update', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3100, 'index_class_color', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3110, 'index_classicon', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3120, 'index_honoricon', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3130, 'index_prof', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3140, 'index_currenthonor', '0', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3150, 'index_note', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3160, 'index_title', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3170, 'index_hearthed', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3180, 'index_zone', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3190, 'index_lastonline', '1', 'radio{on^1|off^0', 'index_conf');
INSERT INTO `renprefix_config` VALUES (3200, 'index_lastupdate', '1', 'radio{on^1|off^0', 'index_conf');

# --------------------------------------------------------
### Roster Menu Settings

INSERT INTO `renprefix_config` VALUES (4000, 'menu_left_pane', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4010, 'menu_right_pane', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4020, 'menu_memberlog', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4040, 'menu_guild_info', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4050, 'menu_stats_page', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4055, 'menu_pvp_page', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4060, 'menu_honor_page', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4070, 'menu_guildbank', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4080, 'menu_keys_page', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4090, 'menu_tradeskills_page', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4100, 'menu_update_page', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4110, 'menu_quests_page', '1', 'radio{on^1|off^0', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4120, 'menu_search_page', '1', 'radio{on^1|off^0', 'menu_conf');

# --------------------------------------------------------
### Display Settings

INSERT INTO `renprefix_config` VALUES (5000, 'stylesheet', 'css/styles.css', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5005, 'roster_js', 'css/js/mainjs.js', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5008, 'tabcontent', 'css/js/tabcontent.js', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5010, 'overlib', 'css/js/overlib.js', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5015, 'overlib_hide', 'css/js/overlib_hideform.js', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5020, 'logo', 'img/wowroster_logo.jpg', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5025, 'roster_bg', 'img/wowroster_bg.jpg', 'text{128|30', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5030, 'motd_display_mode', '1', 'radio{Image^1|Text^0', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5035, 'compress_note', '1', 'radio{Icon^1|Text^0', 'display_conf');
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
INSERT INTO `renprefix_config` VALUES (7010, 'char_header_logo', '1', 'radio{on^1|off^0', 'char_conf');
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
INSERT INTO `renprefix_config` VALUES (7130, 'show_signature', '0', 'radio{yes^1|no^0', 'char_conf');
INSERT INTO `renprefix_config` VALUES (7140, 'show_avatar', '0', 'radio{yes^1|no^0', 'char_conf');

# --------------------------------------------------------
### Realmstatus Settings

INSERT INTO `renprefix_config` VALUES (8000, 'realmstatus_url', 'http://www.worldofwarcraft.com/realmstatus/status.xml', 'select{US Servers^http://www.worldofwarcraft.com/realmstatus/status.xml|EU Servers^http://www.wow-europe.com/en/serverstatus/index.xml', 'realmstatus_conf');
INSERT INTO `renprefix_config` VALUES (8010, 'rs_display', 'full', 'select{full^full|half^half', 'realmstatus_conf');
INSERT INTO `renprefix_config` VALUES (8020, 'rs_mode', '1', 'radio{Image^1|DIV Container^0', 'realmstatus_conf');
INSERT INTO `renprefix_config` VALUES (8030, 'realmstatus', '', 'text{50|30', 'realmstatus_conf');

# --------------------------------------------------------
### Guildbank Settings

INSERT INTO `renprefix_config` VALUES (9000, 'guildbank_ver', '', 'select{Table^|Inventory^2', 'guildbank_conf');
INSERT INTO `renprefix_config` VALUES (9010, 'bank_money', '1', 'radio{yes^1|no^0', 'guildbank_conf');
INSERT INTO `renprefix_config` VALUES (9020, 'banker_rankname', 'BankMule', 'text{50|30', 'guildbank_conf');
INSERT INTO `renprefix_config` VALUES (9030, 'banker_fieldname', 'note', 'select{Player Note^note|Officer Note^officer_note|Guild Rank Number^guild_rank|Guild Title^guild_title|Player Name^name', 'guildbank_conf');

# --------------------------------------------------------
### Update Access

INSERT INTO `renprefix_config` VALUES (10000, 'authenticated_user', '1', 'radio{enable^1|disable^0', 'update_access');
