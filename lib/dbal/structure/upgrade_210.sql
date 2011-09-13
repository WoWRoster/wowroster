#
# MySQL WoWRoster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### New Tables


# --------------------------------------------------------
### Altered Tables


# --------------------------------------------------------
### Config Table Updates

INSERT INTO `renprefix_config` VALUES (99, 'css_js_query_string', 'lod68q', 'hidden', 'master');
INSERT INTO `renprefix_config` VALUES (1181, 'preprocess_js', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1182, 'preprocess_css', '1', 'radio{on^1|off^0', 'main_conf');
### api key settings
INSERT INTO `renprefix_config` VALUES (10001, 'api_key_privet', '', 'text{30|30', 'update_access');
INSERT INTO `renprefix_config` VALUES (10002, 'api_key_public', '', 'text{30|30', 'update_access');
### end api key settings

# --------------------------------------------------------
### Menu Updates
