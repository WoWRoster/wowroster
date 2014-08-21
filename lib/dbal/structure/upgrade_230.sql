#
# MySQL WoWRoster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### New Tables


DROP TABLE IF EXISTS `renprefix_api_enchant`;
CREATE TABLE `renprefix_api_enchant` (
 `id` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `name` varchar(200) NOT NULL,
 `bonus` mediumtext DEFAULT NULL,
 `slot` varchar(30) NOT NULL,
 `icon` varchar(64) NOT NULL,
 `description` mediumtext NOT NULL,
 `castTime` varchar(100) DEFAULT NULL,
 KEY `name` ( `name` ),
 PRIMARY KEY  ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `renprefix_api_error`;
CREATE TABLE `renprefix_api_error` (
 `gem_id` int(11) NOT NULL,
 `gem_name` varchar(96) NOT NULL,
 `gem_color` varchar(16) NOT NULL,
 `gem_tooltip` mediumtext NOT NULL,
 `gem_texture` varchar(64) NOT NULL,
 `gem_bonus` varchar(255) NOT NULL,
 `gem_bonus_stat1` varchar(255) NOT NULL,
 `gem_bonus_stat2` varchar(255) NOT NULL,
 `locale` varchar(16) NOT NULL,
 `timestamp` int(10) NOT NULL,
 `json` longtext DEFAULT NULL,
 PRIMARY KEY  ( `gem_id`, `locale` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `renprefix_sessions_keys`;
CREATE TABLE `renprefix_sessions_keys` (
 `key_id` char(32) NOT NULL,
 `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
 `last_ip` varchar(40) NOT NULL,
 `last_login` int(11) UNSIGNED NOT NULL DEFAULT '0',
 KEY `last_login` ( `last_login` ),
 PRIMARY KEY  ( `key_id`, `user_id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `api_data_glyphs`;
CREATE TABLE `api_data_glyphs` (
 `name` varchar(96) NOT NULL,
 `id` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `class` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `type` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `description` mediumtext NOT NULL,
 `icon` varchar(96) NOT NULL,
 `itemId` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `spellKey` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `spellId` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `htmlDescription` mediumtext NOT NULL,
 `subtext` varchar(96) NOT NULL,
 `prettyName` varchar(96) NOT NULL,
 `typeOrder` varchar(96) NOT NULL,
 KEY `class` ( `class` ),
 KEY `name` ( `name` ),
 PRIMARY KEY  ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `api_class_spells`;
CREATE TABLE `api_class_spells` (
 `spellId` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `class_id` varchar(2) NOT NULL,
 `name` varchar(96) NOT NULL,
 `icon` varchar(96) NOT NULL,
 `castTime` varchar(96) NOT NULL,
 `description` mediumtext NOT NULL,
 `id` varchar(96) NOT NULL,
 `powerType` varchar(96) NOT NULL,
 `classMask` varchar(96) NOT NULL,
 `raceMask` varchar(96) NOT NULL,
 `htmlDescription` mediumtext NOT NULL,
 `classAbility` varchar(96) NOT NULL,
 `rawDescription` mediumtext NOT NULL,
 `serverOnly` varchar(96) NOT NULL,
 `keyAbility` varchar(96) NOT NULL,
 `spec` varchar(96) NOT NULL,
 `minLevel` varchar(96) NOT NULL,
 `mastery` varchar(96) NOT NULL,
 `passive` varchar(96) NOT NULL,
 KEY `class` ( `class_id` ),
 KEY `name` ( `name` ),
 PRIMARY KEY  ( `spellId` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
# --------------------------------------------------------
### Altered Tables
ALTER TABLE  `renprefix_items` ADD `json` longtext DEFAULT NULL;
ALTER TABLE  `renprefix_user_members` ADD `hash` varchar(32) NOT NULL DEFAULT '0';
ALTER TABLE  `renprefix_sessions ADD `guestid` varchar(10) DEFAULT NULL;
# --------------------------------------------------------
### Add to Tables

# --------------------------------------------------------
### Update Tables
# --------------------------------------------------------
### Config Table Updates

# javascript/css aggregation

### api key settings
# session settings
# --------------------------------------------------------
### Menu Updates