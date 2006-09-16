--
-- MySQL UniAdmin Structure File
--

-- --------------------------------------------------------

--
-- Table structure for table `uniadmin_addons`
--

CREATE TABLE `uniadmin_addons` (
  `id` int(11) NOT NULL auto_increment,
  `time_uploaded` int(11) NOT NULL default '0',
  `version` varchar(250) NOT NULL default '',
  `enabled` varchar(5) NOT NULL default '',
  `name` varchar(250) NOT NULL default '',
  `dl_url` varchar(250) NOT NULL default '',
  `homepage` varchar(250) NOT NULL default '',
  `toc` mediumint(9) NOT NULL default '0',
  `required` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `uniadmin_config`
--

CREATE TABLE `uniadmin_config` (
  `config_name` varchar(255) NOT NULL,
  `config_value` varchar(255) default NULL,
  PRIMARY KEY  (`config_name`)
);


-- --------------------------------------------------------

--
-- Table structure for table `uniadmin_files`
--

CREATE TABLE `uniadmin_files` (
  `id` int(11) NOT NULL auto_increment,
  `addon_id` int(11) NOT NULL,
  `addon_name` varchar(250) NOT NULL default '',
  `filename` varchar(250) NOT NULL default '',
  `md5sum` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `addon_id` (`addon_id`)
);


-- --------------------------------------------------------

--
-- Table structure for table `uniadmin_logos`
--

CREATE TABLE `uniadmin_logos` (
  `id` int(11) NOT NULL auto_increment,
  `filename` varchar(250) NOT NULL default '',
  `updated` int(11) NOT NULL default '0',
  `logo_num` varchar(11) NOT NULL default '',
  `active` int(1) NOT NULL default '0',
  `download_url` varchar(250) NOT NULL default '',
  `md5` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`)
);


-- --------------------------------------------------------

--
-- Table structure for table `uniadmin_settings`
--

CREATE TABLE `uniadmin_settings` (
  `id` int(11) NOT NULL auto_increment,
  `set_name` varchar(250) NOT NULL default '',
  `set_value` varchar(250) NOT NULL default '',
  `enabled` varchar(11) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `section` varchar(64) NOT NULL,
  PRIMARY KEY  (`id`)
);

--
-- Dumping data for table `uniadmin_settings`
--

INSERT INTO `uniadmin_settings` (`set_name`, `set_value`, `enabled`, `description`, `section`) VALUES
('LANGUAGE', 'English', '0', 'Language', 'settings'),
('PRIMARYURL', 'http://yourdomain.com/yourinterface.php', '0', 'Upload SavedVariable files to this URL', 'settings'),
('PROGRAMMODE', 'Basic', '0', 'Program Mode', 'settings'),
('AUTODETECTWOW', '1', '1', 'Auto Detect WoW', 'settings'),
('OPENGL', '0', '0', 'WoW OpenGL Mode', 'settings'),
('WINDOWMODE', '0', '0', 'WoW Window Mode', 'settings'),
('UUUPDATERCHECK', '1', '1', 'Check for UniUploader Updates', 'updater'),
('SYNCHROURL', 'http://www.demontest.us/UniAdmin/interface.php', '0', 'Synchronization with UniAdmin URL', 'updater'),
('ADDONAUTOUPDATE', '1', '0', 'Addon Auto-Update', 'updater'),
('UUSETTINGSUPDATER', '1', '0', 'UniUploader Settings Updater', 'updater'),
('AUTOUPLOADONFILECHANGES', '1', '1', 'Auto Upload on file changes', 'options'),
('ALWAYSONTOP', '1', '0', 'Set UniUploader Always On Top', 'options'),
('SYSTRAY', '0', '0', 'Display UniUploader in the System Tray', 'options'),
('USERAGENT', 'UniUploader 2.0 (UU 2.5.0; English)', '0', 'The User Agent UU uses', 'options'),
('ADDVAR1CH', '0', '0', 'Additional variable 1', 'options'),
('ADDVARNAME1', 'username', '0', 'Additional Variable 1 (UserName) Name', 'options'),
('ADDVARVAL1', '', '0', 'Additional Variable 1 Value', 'options'),
('ADDVAR2CH', '0', '0', 'Additional variable 2', 'options'),
('ADDVARNAME2', 'password', '0', 'Additional Variable 2 (Password) Name', 'options'),
('ADDVARVAL2', '', '0', 'Additional Variable 2 Value', 'options'),
('ADDVAR3CH', '0', '0', 'Additional variable 3', 'options'),
('ADDVARNAME3', '', '0', 'Additional Variable 3 Name', 'options'),
('ADDVARVAL3', '', '0', 'Additional Variable 3 Value', 'options'),
('ADDVAR4CH', '0', '0', 'Additional variable 4', 'options'),
('ADDVARNAME4', '', '0', 'Additional Variable 4 Name', 'options'),
('ADDVARVAL4', '', '0', 'Additional Variable 4 Value', 'options'),
('ADDURL1CH', '0', '0', 'Additional URL1', 'options'),
('ADDURL1', '', '0', 'Additional URL 1 location', 'options'),
('ADDURL2CH', '0', '0', 'Additional URL2', 'options'),
('ADDURL2', '', '0', 'Additional URL 2 location', 'options'),
('ADDURL3CH', '0', '0', 'Additional URL3', 'options'),
('ADDURL3', '', '0', 'Additional URL 3 Location', 'options'),
('ADDURL4CH', '0', '0', 'Additional URL4', 'options'),
('ADDURL4', '', '0', 'Additional URL 4 Location', 'options'),
('AUTOLAUNCHWOW', '0', '0', 'Auto-Launch WoW', 'advanced'),
('WOWARGS', '0', '0', 'Launch Program Arguments', 'advanced'),
('STARTWITHWINDOWS', '0', '0', 'Start With Windows', 'advanced'),
('USELAUNCHER', '0', '0', 'Launch with WoW Launcher', 'advanced'),
('STARTMINI', '1', '0', 'Start Minimized', 'advanced'),
('SENDPWSECURE', '1', '0', 'md5 Encrypt Variable 2 Value (password) before sending', 'advanced'),
('GZIP', '1', '0', 'Gzip Compression', 'advanced'),
('DELAYUPLOAD', '0', '0', 'Upload Delay', 'advanced'),
('DELAYSECONDS', '5', '0', 'Upload Delay Seconds', 'advanced'),
('RETRDATAFROMSITE', '1', '0', 'Retrieve Data for Web=>WoW', 'advanced'),
('RETRDATAURL', 'http://somewhere.com/something.php', '0', 'Web=>WoW Data Retrieval URL', 'advanced'),
('WEBWOWSVFILE', 'SavedVariables.lua', '0', 'The Saved Variables file to write to for Web=>WoW', 'advanced'),
('DOWNLOADBEFOREWOWL', '0', '0', 'Initiate Web=>WoW Before UU Launches WoW', 'advanced'),
('DOWNLOADBEFOREUPLOAD', '0', '0', 'Initiate Web=>WoW Before UU Uploads', 'advanced'),
('DOWNLOADAFTERUPLOAD', '1', '0', 'Initiate Web=>WoW After UU Uploads', 'advanced'),
('SYNCHROAUTOURL', '1', '0', '(UU 1.x) Synchronization Auto-URL', ''),
('AUTOPATH', '1', '1', '(UU 1.x) Auto-Path', ''),
('PREPARSE', '1', '0', '(UU 1.x) Pre-Parse', ''),
('PARSEVAR2CH', '0', '0', '(UU 1.x) Pre-Parse Variable 2', ''),
('PARSEVAR4CH', '0', '0', '(UU 1.x) Pre-Parse Variable 4', ''),
('PARSEVAR3CH', '0', '0', '(UU 1.x) Pre-Parse Variable 3', ''),
('PARSEVAR5CH', '0', '0', '(UU 1.x) Pre-Parse Variable 5', ''),
('PARSEVAR1', 'myProfile', '0', '(UU 1.x) Pre-Parse Variable 1 name', ''),
('PARSEVAR2', '', '0', '(UU 1.x) Pre-Parse Variable 2 name', ''),
('PARSEVAR3', '', '0', '(UU 1.x) Pre-Parse Variable 3 name', ''),
('PARSEVAR4', '', '0', '(UU 1.x) Pre-Parse Variable 4 name', ''),
('PARSEVAR5', '', '0', '(UU 1.x) Pre-Parse Variable 5 name', ''),
('RETRDATA', '0', '0', '(UU 1.x) Retrieve Data', ''),
('ADDURLFFNAME1', '', '0', '(UU 1.x) Additional URL 1 FileField Name', ''),
('ADDURLFFNAME2', '', '0', '(UU 1.x) Additional URL 2 FileField Name', ''),
('ADDURLFFNAME3', '', '0', '(UU 1.x) Additional URL 3 FileField Name', ''),
('ADDURLFFNAME4', '', '0', '(UU 1.x) Additional URL 4 FileField Name', '');


-- --------------------------------------------------------

--
-- Table structure for table `uniadmin_stats`
--

CREATE TABLE `uniadmin_stats` (
  `id` int(11) NOT NULL auto_increment,
  `ip_addr` varchar(30) NOT NULL default '',
  `host_name` varchar(250) NOT NULL default '',
  `action` varchar(250) NOT NULL default '',
  `time` varchar(15) NOT NULL default '',
  `user_agent` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`)
);


-- --------------------------------------------------------

--
-- Table structure for table `uniadmin_svlist`
--

CREATE TABLE `uniadmin_svlist` (
  `id` int(11) NOT NULL auto_increment,
  `sv_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
);

--
-- Dumping data for table `uniadmin_svlist`
--

INSERT INTO `uniadmin_svlist` (`sv_name`) VALUES ('CharacterProfiler');
INSERT INTO `uniadmin_svlist` (`sv_name`) VALUES ('PvPLog');


-- --------------------------------------------------------

--
-- Table structure for table `uniadmin_users`
--

CREATE TABLE `uniadmin_users` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `level` char(3) NOT NULL default '',
  `language` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`id`)
);

--
-- Dumping data for table `uniadmin_users`
--

INSERT INTO `uniadmin_users` (`name`,`password`,`level`,`language`) VALUES ('Default', '4cb9c8a8048fd02294477fcb1a41191a', '3', 'English');
