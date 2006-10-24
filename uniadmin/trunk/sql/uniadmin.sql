#
# MySQL UniAdmin DB Structure
#
# $Id$
#
# --------------------------------------------------------
### Table structure for addons

DROP TABLE IF EXISTS `uniadmin_addons`;
CREATE TABLE `uniadmin_addons` (
  `id` int(11) NOT NULL auto_increment,
  `time_uploaded` int(11) NOT NULL default '0',
  `version` varchar(16) NOT NULL default '0',
  `enabled` tinyint(1) NOT NULL default '0',
  `name` varchar(250) NOT NULL default '',
  `dl_url` varchar(250) NOT NULL default '',
  `homepage` varchar(250) NOT NULL default '',
  `toc` mediumint(9) NOT NULL default '0',
  `required` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `addon_name` (`name`)
);


# --------------------------------------------------------
### Table structure for config

DROP TABLE IF EXISTS `uniadmin_config`;
CREATE TABLE `uniadmin_config` (
  `config_name` varchar(255) NOT NULL,
  `config_value` varchar(255) default NULL,
  `form_type` mediumtext,
  PRIMARY KEY  (`config_name`)
);

### Configuration values
INSERT INTO `uniadmin_config` (`config_name`, `config_value`, `form_type`) VALUES
	('addon_folder', 'addon_zips', 'text{250|50'),
	('default_lang', 'english', 'function{lang_select'),
	('default_style', 'default', 'function{style_select'),
	('enable_gzip', '0', 'radio{yes^1|no^0'),
	('interface_url', '%url%interface.php', 'text{250|50'),
	('logo_folder', 'logos', 'text{250|50'),
	('temp_analyze_folder', 'addon_temp', 'text{250|50'),
	('UAVer', '0.7.0', 'display');


# --------------------------------------------------------
### Table structure for files

DROP TABLE IF EXISTS `uniadmin_files`;
CREATE TABLE `uniadmin_files` (
  `id` int(11) NOT NULL auto_increment,
  `addon_id` int(11) NOT NULL,
  `filename` varchar(250) NOT NULL default '',
  `md5sum` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `addon_id` (`addon_id`)
);


# --------------------------------------------------------
### Table structure for logos


DROP TABLE IF EXISTS `uniadmin_logos`;
CREATE TABLE `uniadmin_logos` (
  `id` int(11) NOT NULL auto_increment,
  `filename` varchar(250) NOT NULL default '',
  `updated` int(11) NOT NULL default '0',
  `logo_num` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  `download_url` varchar(250) NOT NULL default '',
  `md5` varchar(32) NOT NULL,
  PRIMARY KEY  (`id`)
);


# --------------------------------------------------------
### Table structure for settings


DROP TABLE IF EXISTS `uniadmin_settings`;
CREATE TABLE `uniadmin_settings` (
  `id` int(11) NOT NULL auto_increment,
  `set_name` varchar(250) NOT NULL default '',
  `set_value` varchar(250) NOT NULL default '',
  `enabled` varchar(11) NOT NULL default '',
  `section` varchar(64) NOT NULL,
  `form_type` mediumtext,
  PRIMARY KEY  (`id`)
);

### Settings
INSERT INTO `uniadmin_settings` (`set_name`, `set_value`, `enabled`, `section`, `form_type`) VALUES
	('LANGUAGE', 'English', '0', 'settings', 'select{English^English|Deutsch^Deutsch|French^French|Nederlands^Nederlands|Russian^Russian|Svenska^Svenska'),
	('PRIMARYURL', 'http://yourdomain.com/yourinterface.php', '0', 'settings', 'text{250|50'),
	('PROGRAMMODE', 'Basic', '0', 'settings', 'select{Basic^Basic|Advanced^Advanced'),
	('AUTODETECTWOW', '1', '0', 'settings', 'radio{yes^1|no^0'),
	('OPENGL', '0', '0', 'settings', 'radio{yes^1|no^0'),
	('WINDOWMODE', '0', '0', 'settings', 'radio{yes^1|no^0'),
	('UUUPDATERCHECK', '1', '0', 'updater', 'radio{yes^1|no^0'),
	('SYNCHROURL', 'http://yourdomain.com/UniAdmin/interface.php', '0', 'updater', 'text{250|50'),
	('ADDONAUTOUPDATE', '1', '0', 'updater', 'radio{yes^1|no^0'),
	('UUSETTINGSUPDATER', '1', '0', 'updater', 'radio{yes^1|no^0'),
	('AUTOUPLOADONFILECHANGES', '1', '0', 'options', 'radio{yes^1|no^0'),
	('ALWAYSONTOP', '1', '0', 'options', 'radio{yes^1|no^0'),
	('SYSTRAY', '0', '0', 'options', 'radio{yes^1|no^0'),
	('ADDVAR1CH', '0', '0', 'options', 'radio{on^1|off^0'),
	('ADDVARNAME1', 'username', '0', 'options', 'text{250|50'),
	('ADDVARVAL1', '', '0', 'options', 'text{250|50'),
	('ADDVAR2CH', '0', '0', 'options', 'radio{on^1|off^0'),
	('ADDVARNAME2', 'password', '0', 'options', 'text{250|50'),
	('ADDVARVAL2', '', '0', 'options', 'text{250|50'),
	('ADDVAR3CH', '0', '0', 'options', 'radio{on^1|off^0'),
	('ADDVARNAME3', '', '0', 'options', 'text{250|50'),
	('ADDVARVAL3', '', '0', 'options', 'text{250|50'),
	('ADDVAR4CH', '0', '0', 'options', 'radio{on^1|off^0'),
	('ADDVARNAME4', '', '0', 'options', 'text{250|50'),
	('ADDVARVAL4', '', '0', 'options', 'text{250|50'),
	('ADDURL1CH', '0', '0', 'options', 'radio{on^1|off^0'),
	('ADDURL1', '', '0', 'options', 'text{250|50'),
	('ADDURL2CH', '0', '0', 'options', 'radio{on^1|off^0'),
	('ADDURL2', '', '0', 'options', 'text{250|50'),
	('ADDURL3CH', '0', '0', 'options', 'radio{on^1|off^0'),
	('ADDURL3', '', '0', 'options', 'text{250|50'),
	('ADDURL4CH', '0', '0', 'options', 'radio{on^1|off^0'),
	('ADDURL4', '', '0', 'options', 'text{250|50'),
	('AUTOLAUNCHWOW', '0', '0', 'advanced', 'radio{yes^1|no^0'),
	('WOWARGS', '0', '0', 'advanced', 'text{250|50'),
	('STARTWITHWINDOWS', '0', '0', 'advanced', 'radio{yes^1|no^0'),
	('USELAUNCHER', '0', '0', 'advanced', 'radio{yes^1|no^0'),
	('STARTMINI', '1', '0', 'advanced', 'radio{yes^1|no^0'),
	('SENDPWSECURE', '1', '0', 'advanced', 'radio{yes^1|no^0'),
	('GZIP', '1', '0', 'advanced', 'radio{yes^1|no^0'),
	('DELAYUPLOAD', '0', '0', 'advanced', 'radio{yes^1|no^0'),
	('DELAYSECONDS', '5', '0', 'advanced', 'text{250|10'),
	('RETRDATAFROMSITE', '1', '0', 'advanced', 'radio{yes^1|no^0'),
	('RETRDATAURL', 'http://yourdomain.com/web_to_wow.php', '0', 'advanced', 'text{250|50'),
	('WEBWOWSVFILE', 'SavedVariables.lua', '0', 'advanced', 'text{250|50'),
	('DOWNLOADBEFOREWOWL', '0', '0', 'advanced', 'radio{on^1|off^0'),
	('DOWNLOADBEFOREUPLOAD', '0', '0', 'advanced', 'radio{on^1|off^0'),
	('DOWNLOADAFTERUPLOAD', '1', '0', 'advanced', 'radio{on^1|off^0');


# --------------------------------------------------------
### Table structure for stats


DROP TABLE IF EXISTS `uniadmin_stats`;
CREATE TABLE `uniadmin_stats` (
  `id` int(11) NOT NULL auto_increment,
  `ip_addr` varchar(30) NOT NULL default '',
  `host_name` varchar(250) NOT NULL default '',
  `action` varchar(250) NOT NULL default '',
  `time` varchar(15) NOT NULL default '',
  `user_agent` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`)
);


# --------------------------------------------------------
### Table structure for svlist


DROP TABLE IF EXISTS `uniadmin_svlist`;
CREATE TABLE `uniadmin_svlist` (
  `id` int(11) NOT NULL auto_increment,
  `sv_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `sv_name` (`sv_name`)
);

### SV List
INSERT INTO `uniadmin_svlist` (`sv_name`) VALUES
	('CharacterProfiler'),
	('PvPLog');


# --------------------------------------------------------
### Table structure for users


DROP TABLE IF EXISTS `uniadmin_users`;
CREATE TABLE `uniadmin_users` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `password` varchar(32) NOT NULL,
  `level` int(11) NOT NULL default '0',
  `language` varchar(32) NOT NULL,
  `user_style` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
);

### User List
INSERT INTO `uniadmin_users` (`name`, `password`, `level`, `language`, `user_style`) VALUES
	('Default', '4cb9c8a8048fd02294477fcb1a41191a', '3', 'english', 'default');