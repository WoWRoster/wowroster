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
INSERT INTO `renprefix_config` VALUES (4, 'version', '', 'display', 'master');
INSERT INTO `renprefix_config` VALUES (5, 'startpage', 'main_conf', 'display', 'master');
INSERT INTO `renprefix_config` VALUES (6, 'versioncache', '', 'hidden', 'master');
INSERT INTO `renprefix_config` VALUES (99, 'css_js_query_string', 'lod68q', 'hidden', 'master');

# --------------------------------------------------------
### Menu Entries
INSERT INTO `renprefix_config` VALUES (110, 'main_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (120, 'defaults_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (140, 'display_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (150, 'realmstatus_conf', NULL, 'page{1', 'menu');
INSERT INTO `renprefix_config` VALUES (160, 'data_links', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (170, 'update_access', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (180, 'documentation', 'http://www.wowroster.net/MediaWiki', 'newlink', 'menu');
INSERT INTO `renprefix_config` VALUES (190,'acc_session','NULL','blockframe','menu');

# --------------------------------------------------------
### Main Roster Config

INSERT INTO `renprefix_config` VALUES (1001, 'debug_mode', '0', 'radio{extended^2|on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1002, 'sql_window', '0', 'radio{extended^2|on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1010, 'minCPver', '1.0.0', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1020, 'minGPver', '1.0.0', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1040, 'locale', 'enUS', 'function{rosterLangValue', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1050, 'default_page', 'rostercp', 'function{pageNames', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1055, 'external_auth', 'roster', 'function{externalAuth', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1060, 'website_address', '', 'text{128|60', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1085, 'interface_url', 'http://www.wowroster.net/', 'text{128|60', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1090, 'img_suffix', 'png', 'select{jpg^jpg|png^png|gif^gif', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1095, 'alt_img_suffix', 'png', 'select{jpg^jpg|png^png|gif^gif', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1100, 'img_url', 'img/', 'text{128|60', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1110, 'timezone', 'PST', 'text{10|10', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1120, 'localtimeoffset', '0', 'select{-12^-12|-11^-11|-10^-10|-9^-9|-8^-8|-7^-7|-6^-6|-5^-5|-4^-4|-3.5^-3.5|-3^-3|-2^-2|-1^-1|0^0|+1^1|+2^2|+3^3|+3.5^3.5|+4^4|+4.5^4.5|+5^5|+5.5^5.5|+6^6|+6.5^6.5|+7^7|+8^8|+9^9|+9.5^9.5|+10^10|+11^11|+12^12|+13^13', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1140, 'use_update_triggers', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1150, 'check_updates', '24', 'select{Do Not check^0|Once a Day^24|Once a Week^168|Once a Month^720', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1160, 'seo_url', '0', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1170, 'local_cache', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1180, 'use_temp_tables', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1181, 'preprocess_js', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1182, 'preprocess_css', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1190, 'enforce_rules', '1', 'select{Never^0|All LUA Updates^1|CP Updates^2|Guild Updates^3', 'main_conf');

# --------------------------------------------------------
### Guild Settings

INSERT INTO `renprefix_config` VALUES (2000, 'default_name', 'WoWRoster', 'text{50|50', 'defaults_conf');
INSERT INTO `renprefix_config` VALUES (2020, 'default_desc', 'THE original Roster for World of Warcraft', 'text{255|60', 'defaults_conf');
INSERT INTO `renprefix_config` VALUES (2040, 'alt_type', 'alt', 'text{30|30', 'defaults_conf');
INSERT INTO `renprefix_config` VALUES (2050, 'alt_location', 'note', 'select{Player Note^note|Officer Note^officer_note|Guild Rank Number^guild_rank|Guild Title^guild_title', 'defaults_conf');

# --------------------------------------------------------
### Display Settings

INSERT INTO `renprefix_config` VALUES (5000, 'theme', 'default', 'function{templateList', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5020, 'logo', 'img/wowroster_logo.jpg', 'text{128|60', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5025, 'roster_bg', 'img/wowroster_bg.jpg', 'text{128|60', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5030, 'motd_display_mode', '0', 'radio{Image^1|Text^0', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5031, 'header_locale', '1', 'radio{on^1|off^0', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5032, 'header_login', '1', 'radio{on^1|off^0', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5033, 'header_search', '1', 'radio{on^1|off^0', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5040, 'signaturebackground', 'img/default.png', 'text{128|60', 'display_conf');
INSERT INTO `renprefix_config` VALUES (5050, 'processtime', '1', 'radio{on^1|off^0', 'display_conf');

# --------------------------------------------------------
### Links Settings

INSERT INTO `renprefix_config` VALUES (6100, 'profiler', 'http://www.wowroster.net/downloads/index.php?cat=3&id=142', 'text{128|60', 'data_links');
INSERT INTO `renprefix_config` VALUES (6120, 'uploadapp', 'http://www.wowroster.net/downloads/?mcat=2', 'text{128|60', 'data_links');

# --------------------------------------------------------
### Realmstatus Settings

INSERT INTO `renprefix_config` VALUES (8010, 'rs_top', NULL, 'blockframe', 'realmstatus_conf');
INSERT INTO `renprefix_config` VALUES (8020, 'rs_wide', NULL, 'page{3', 'realmstatus_conf');
INSERT INTO `renprefix_config` VALUES (8030, 'rs_left', NULL, 'blockframe', 'rs_wide');
INSERT INTO `renprefix_config` VALUES (8040, 'rs_middle', NULL, 'blockframe', 'rs_wide');
INSERT INTO `renprefix_config` VALUES (8050, 'rs_right', NULL, 'blockframe', 'rs_wide');

INSERT INTO `renprefix_config` VALUES (8100, 'rs_display', 'full', 'radio{off^0|image^image|text^text', 'rs_top');
INSERT INTO `renprefix_config` VALUES (8120, 'rs_timer', '10', 'text{5|5', 'rs_top');

INSERT INTO `renprefix_config` VALUES (8200, 'rs_font_server', 'GREY.TTF', 'function{fontFiles', 'rs_left');
INSERT INTO `renprefix_config` VALUES (8210, 'rs_size_server', '20', 'text{5|5', 'rs_left');
INSERT INTO `renprefix_config` VALUES (8220, 'rs_color_server', '#FFFFFF', 'color', 'rs_left');
INSERT INTO `renprefix_config` VALUES (8230, 'rs_color_shadow', '#000000', 'color', 'rs_left');

INSERT INTO `renprefix_config` VALUES (8300, 'rs_font_type', 'visitor.ttf', 'function{fontFiles', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8310, 'rs_size_type', '10', 'text{5|5', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8320, 'rs_color_rppvp', '#EBDBA2', 'color', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8330, 'rs_color_pve', '#EBDBA2', 'color', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8340, 'rs_color_pvp', '#CC3333', 'color', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8350, 'rs_color_rp', '#33CC33', 'color', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8360, 'rs_color_unknown', '#860D02', 'color', 'rs_middle');

INSERT INTO `renprefix_config` VALUES (8400, 'rs_font_pop', 'visitor.ttf', 'function{fontFiles', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8410, 'rs_size_pop', '10', 'text{5|5', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8420, 'rs_color_low', '#33CC33', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8430, 'rs_color_medium', '#EBDBA2', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8440, 'rs_color_high', '#CC3333', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8450, 'rs_color_max', '#CC3333', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8460, 'rs_color_error', '#646464', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8465, 'rs_color_offline', '#646464', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8470, 'rs_color_full', '#CC3333', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8480, 'rs_color_recommended', '#33CC33', 'color', 'rs_right');


# --------------------------------------------------------
### Update Access
INSERT INTO `renprefix_config` VALUES (10000, 'authenticated_user', '1', 'radio{enable^1|disable^0', 'update_access');
INSERT INTO `renprefix_config` VALUES (10001, 'api_key_private', '', 'text{64|30', 'update_access');
INSERT INTO `renprefix_config` VALUES (10002, 'api_key_public', '', 'text{64|30', 'update_access');
INSERT INTO `renprefix_config` VALUES (10003, 'api_url_region', 'us', 'select{us.battle.net^us|eu.battle.net^eu|kr.battle.net^kr|tw.battle.net^tw', 'update_access');
INSERT INTO `renprefix_config` VALUES (10004, 'api_url_locale', 'en_US', 'select{us.battle.net (en_US)^en_US|us.battle.net (es_MX)^es_MX|eu.battle.net (en_GB)^en_GB|eu.battle.net (es_ES)^es_ES|eu.battle.net (fr_FR)^fr_FR|eu.battle.net (ru_RU)^ru_RU|eu.battle.net (de_DE)^de_DE|kr.battle.net (ko_kr)^ko_kr|tw.battle.net (zh_TW)^zh_TW|battlenet.com.cn (zh_CN)^zh_CN', 'update_access');
INSERT INTO `renprefix_config` VALUES (10005, 'update_inst', '1', 'radio{on^1|off^0', 'update_access');
INSERT INTO `renprefix_config` VALUES (10010, 'gp_user_level', '11:0', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10020, 'cp_user_level', '11:0', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10030, 'lua_user_level', '11:0', 'access', 'update_access');

# --------------------------------------------------------
### Session config
INSERT INTO `renprefix_config` VALUES (1900, 'sess_time', '15', 'text{30|4', 'acc_session');
INSERT INTO `renprefix_config` VALUES (1910, 'save_login', '1', 'radio{on^1|off^0', 'acc_session');

# --------------------------------------------------------
### Menu table entries
INSERT INTO `renprefix_menu` VALUES (1, 'util', 'b1:b2');
INSERT INTO `renprefix_menu` VALUES (2, 'realm', '');
INSERT INTO `renprefix_menu` VALUES (3, 'guild', '');
INSERT INTO `renprefix_menu` VALUES (4, 'char', '');
INSERT INTO `renprefix_menu` VALUES (5, 'user', '');

# --------------------------------------------------------
### Menu Button entries
INSERT INTO `renprefix_menu_button` VALUES (1, 0, 'menu_search', 'util', 'search', 'inv_misc_spyglass_02');
INSERT INTO `renprefix_menu_button` VALUES (2, 0, 'menu_roster_cp', 'util', 'rostercp', 'inv_misc_gear_02');

# --------------------------------------------------------
### Users

# --------------------------------------------------------
### Talent Tree Arrows

INSERT INTO `renprefix_talenttree_arrows` (`tree`, `arrowid`, `opt1`, `opt2`, `opt3`, `opt4`) VALUES
('hunterbeastmastery', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('hunterbeastmastery', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('hunterbeastmastery', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('hunterbeastmastery', 4, 'vArrow', 'disabledArrow', NULL, NULL),
('huntermarksmanship', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('huntermarksmanship', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('huntermarksmanship', 3, 'hArrow', 'arrowRight', 'disabledArrow', 'plain'),
('huntermarksmanship', 4, 'vArrow', 'disabledArrow', NULL, NULL),
('huntersurvival', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('huntersurvival', 2, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('huntersurvival', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('magefrost', 4, 'vArrow', 'disabledArrow', NULL, NULL),
('magefrost', 3, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('magefrost', 2, 'hArrow', 'arrowLeft', 'disabledArrow', 'disabledArrowL'),
('magefrost', 1, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('magefire', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('magefire', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('magearcane', 5, 'vArrow', 'disabledArrow', NULL, NULL),
('magearcane', 4, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('magearcane', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('magearcane', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('magearcane', 1, 'hArrow', 'arrowLeft', 'disabledArrow', 'disabledArrowL'),
('druidrestoration', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('druidrestoration', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('druidrestoration', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('druidferalcombat', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('druidferalcombat', 2, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('druidferalcombat', 1, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('druidbalance', 5, 'vArrow', 'disabledArrow', NULL, NULL),
('druidbalance', 4, 'vArrow', 'disabledArrow', NULL, NULL),
('druidbalance', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('druidbalance', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('druidbalance', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('deathknightunholy', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('deathknightunholy', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('deathknightunholy', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('deathknightfrost', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('deathknightblood', 1, 'hArrow', 'arrowLeft', 'disabledArrow', 'disabledArrowL'),
('paladinholy', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('paladinholy', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('paladinprotection', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('paladinprotection', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('paladinprotection', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('paladinprotection', 4, 'vArrow', 'disabledArrow', NULL, NULL),
('paladincombat', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('priestdiscipline', 1, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('priestdiscipline', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('priestdiscipline', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('priestholy', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('priestholy', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('priestholy', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('priestholy', 4, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('priestholy', 5, 'vArrow', 'disabledArrow', NULL, NULL),
('priestshadow', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('priestshadow', 2, 'hArrow', 'arrowRigh', 'disabledArrow', NULL),
('priestshadow', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('priestshadow', 4, 'vArrow', 'disabledArrow', NULL, NULL),
('rogueassassination', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('rogueassassination', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('roguecombat', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('roguesubtlety', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('roguesubtlety', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('shamanelementalcombat', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('shamanelementalcombat', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('shamanelementalcombat', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('shamanenhancement', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('shamanrestoration', 1, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('shamanrestoration', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('warlockcurses', 1, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('warlockcurses', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('warlocksummoning', 1, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('warlocksummoning', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('warlocksummoning', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('warlockdestruction', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('warlockdestruction', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('warlockdestruction', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('warriorarms', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('warriorarms', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('warriorarms', 3, 'hArrow', 'arrowRight', 'plain', 'disabledArrow'),
('warriorarms', 4, 'vArrow', 'disabledArrow', NULL, NULL),
('warriorfury', 1, 'vArrow', 'disabledArrow', NULL, NULL),
('warriorfury', 2, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('warriorfury', 3, 'vArrow', 'disabledArrow', NULL, NULL),
('warriorprotection', 1, 'hArrow', 'arrowRight', 'disabledArrow', NULL),
('warriorprotection', 2, 'vArrow', 'disabledArrow', NULL, NULL),
('warriorprotection', 3, 'vArrow', 'disabledArrow', NULL, NULL);
