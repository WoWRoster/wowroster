#
# MySQL WoWRoster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### New Tables

DROP TABLE IF EXISTS `renprefix_api_usage`;
CREATE TABLE IF NOT EXISTS `renprefix_api_usage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `total` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


# --------------------------------------------------------
### Altered Tables


# --------------------------------------------------------
### Config Table Updates

# javascript/css aggregation
INSERT INTO `renprefix_config` VALUES (99, 'css_js_query_string', 'lod68q', 'hidden', 'master');
INSERT INTO `renprefix_config` VALUES (1181, 'preprocess_js', '1', 'radio{on^1|off^0', 'main_conf');
INSERT INTO `renprefix_config` VALUES (1182, 'preprocess_css', '1', 'radio{on^1|off^0', 'main_conf');

### api key settings
INSERT INTO `renprefix_config` VALUES (10001, 'api_key_private', '', 'text{64|30', 'update_access');
INSERT INTO `renprefix_config` VALUES (10002, 'api_key_public', '', 'text{64|30', 'update_access');
INSERT INTO `renprefix_config` VALUES (10003, 'api_url_region', 'us', 'select{us.battle.net^us|eu.battle.net^eu|kr.battle.net^kr|tw.battle.net^tw', 'update_access');
INSERT INTO `renprefix_config` VALUES (10004, 'api_url_locale', 'en_US', 'select{us.battle.net (en_US)^en_US|us.battle.net (es_MX)^es_MX|eu.battle.net (en_GB)^en_GB|eu.battle.net (es_ES)^es_ES|eu.battle.net (fr_FR)^fr_FR|eu.battle.net (ru_RU)^ru_RU|eu.battle.net (de_DE)^de_DE|kr.battle.net (ko_kr)^ko_kr|tw.battle.net (zh_TW)^zh_TW|battlenet.com.cn (zh_CN)^zh_CN', 'update_access');



# --------------------------------------------------------
### Menu Updates
