-- phpMyAdmin SQL Dump
-- version 2.8.0.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jun 23, 2006 at 02:09 PM
-- Server version: 4.1.19
-- PHP Version: 4.4.2
-- 
-- Database: `demontes_test`
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
)AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `uniadmin_addons`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `uniadmin_files`
-- 

CREATE TABLE `uniadmin_files` (
  `id` int(11) NOT NULL auto_increment,
  `addon_name` varchar(250) NOT NULL default '',
  `filename` varchar(250) NOT NULL default '',
  `md5sum` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`)
)AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `uniadmin_files`
-- 


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
)AUTO_INCREMENT=126 ;

-- 
-- Dumping data for table `uniadmin_logos`
-- 


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
  PRIMARY KEY  (`id`)
)AUTO_INCREMENT=68 ;

-- 
-- Dumping data for table `uniadmin_settings`
-- 

INSERT INTO `uniadmin_settings` VALUES (4, 'PRIMARYURL', 'http://yourdomain.com/yourinterface.php', '0', 'Primary URL');
INSERT INTO `uniadmin_settings` VALUES (2, 'LANGUAGE', 'English', '0', 'Language');
INSERT INTO `uniadmin_settings` VALUES (3, 'PROGRAMMODE', 'Basic', '0', 'Program Mode');
INSERT INTO `uniadmin_settings` VALUES (5, 'AUTOPATH', '1', '1', 'Auto-Path');
INSERT INTO `uniadmin_settings` VALUES (6, 'ADDONAUTOUPDATE', '1', '0', 'Addon Auto-Update');
INSERT INTO `uniadmin_settings` VALUES (7, 'UUSETTINGSUPDATER', '1', '0', 'UniUploader Settings Updater');
INSERT INTO `uniadmin_settings` VALUES (10, 'SYNCHROAUTOURL', '0', '0', 'Synchronization Auto-URL');
INSERT INTO `uniadmin_settings` VALUES (8, 'UUUPDATERCHECK', '1', '1', 'UniUploader Updater');
INSERT INTO `uniadmin_settings` VALUES (9, 'SYNCHROURL', 'http://www.demontest.us/UniAdmin/interface.php', '0', 'Synchronization URL');
INSERT INTO `uniadmin_settings` VALUES (15, 'SYSTRAY', '0', '0', 'System Tray');
INSERT INTO `uniadmin_settings` VALUES (17, 'AUTOUPLOADONFILECHANGES', '1', '1', 'Auto Upload on file changes');
INSERT INTO `uniadmin_settings` VALUES (18, 'ADDVAR1CH', '0', '0', 'Additional variable 1');
INSERT INTO `uniadmin_settings` VALUES (19, 'ADDVAR2CH', '0', '0', 'Additional variable 2');
INSERT INTO `uniadmin_settings` VALUES (20, 'ADDVAR3CH', '0', '0', 'Additional variable 3');
INSERT INTO `uniadmin_settings` VALUES (21, 'ADDVAR4CH', '0', '0', 'Additional variable 4');
INSERT INTO `uniadmin_settings` VALUES (22, 'ADDURL1CH', '0', '0', 'Additional URL1');
INSERT INTO `uniadmin_settings` VALUES (23, 'ADDURL2CH', '0', '0', 'Additional URL2');
INSERT INTO `uniadmin_settings` VALUES (24, 'ADDURL3CH', '0', '0', 'Additional URL3');
INSERT INTO `uniadmin_settings` VALUES (25, 'ADDURL4CH', '0', '0', 'Additional URL4');
INSERT INTO `uniadmin_settings` VALUES (26, 'ADDVARNAME1', '', '0', 'Additional Variable 1 Name');
INSERT INTO `uniadmin_settings` VALUES (27, 'ADDVARNAME2', '', '0', 'Additional Variable 2 Name');
INSERT INTO `uniadmin_settings` VALUES (28, 'ADDVARNAME3', '', '0', 'Additional Variable 3 Name');
INSERT INTO `uniadmin_settings` VALUES (29, 'ADDVARNAME4', '', '0', 'Additional Variable 4 Name');
INSERT INTO `uniadmin_settings` VALUES (30, 'ADDVARVAL1', '', '0', 'Additional Variable 1 Value');
INSERT INTO `uniadmin_settings` VALUES (31, 'ADDVARVAL2', '', '0', 'Additional Variable 2 Value');
INSERT INTO `uniadmin_settings` VALUES (32, 'ADDVARVAL3', '', '0', 'Additional Variable 3 Value');
INSERT INTO `uniadmin_settings` VALUES (33, 'ADDVARVAL4', '', '0', 'Additional Variable 4 Value');
INSERT INTO `uniadmin_settings` VALUES (34, 'ADDURL1', '', '0', 'Additional URL 1 location');
INSERT INTO `uniadmin_settings` VALUES (35, 'ADDURL2', '', '0', 'Additional URL 2 location');
INSERT INTO `uniadmin_settings` VALUES (36, 'ADDURL3', '', '0', 'Additional URL 3 Location');
INSERT INTO `uniadmin_settings` VALUES (37, 'ADDURL4', '', '0', 'Additional URL 4 Location');
INSERT INTO `uniadmin_settings` VALUES (38, 'ADDURLFFNAME1', '', '0', 'Additional URL 1 FileField Name');
INSERT INTO `uniadmin_settings` VALUES (39, 'ADDURLFFNAME2', '', '0', 'Additional URL 2 FileField Name');
INSERT INTO `uniadmin_settings` VALUES (40, 'ADDURLFFNAME3', '', '0', 'Additional URL 3 FileField Name');
INSERT INTO `uniadmin_settings` VALUES (41, 'ADDURLFFNAME4', '', '0', 'Additional URL 4 FileField Name');
INSERT INTO `uniadmin_settings` VALUES (42, 'GZIP', '1', '0', 'Gzip Compression');
INSERT INTO `uniadmin_settings` VALUES (43, 'PREPARSE', '1', '0', 'Pre-Parse');
INSERT INTO `uniadmin_settings` VALUES (44, 'PARSEVAR2CH', '0', '0', 'Pre-Parse Variable 2');
INSERT INTO `uniadmin_settings` VALUES (45, 'PARSEVAR4CH', '0', '0', 'Pre-Parse Variable 4');
INSERT INTO `uniadmin_settings` VALUES (46, 'PARSEVAR3CH', '0', '0', 'Pre-Parse Variable 3');
INSERT INTO `uniadmin_settings` VALUES (47, 'PARSEVAR5CH', '0', '0', 'Pre-Parse Variable 5');
INSERT INTO `uniadmin_settings` VALUES (48, 'PARSEVAR1', 'myProfile', '0', 'Pre-Parse Variable 1 name');
INSERT INTO `uniadmin_settings` VALUES (49, 'PARSEVAR2', '', '0', 'Pre-Parse Variable 2 name');
INSERT INTO `uniadmin_settings` VALUES (50, 'PARSEVAR3', '', '0', 'Pre-Parse Variable 3 name');
INSERT INTO `uniadmin_settings` VALUES (51, 'PARSEVAR4', '', '0', 'Pre-Parse Variable 4 name');
INSERT INTO `uniadmin_settings` VALUES (52, 'PARSEVAR5', '', '0', 'Pre-Parse Variable 5 name');
INSERT INTO `uniadmin_settings` VALUES (53, 'RETRDATA', '0', '0', 'Retrieve Data');
INSERT INTO `uniadmin_settings` VALUES (55, 'WINDOWMODE', '0', '0', 'WoW Window Mode');
INSERT INTO `uniadmin_settings` VALUES (56, 'STARTWITHWINDOWS', '0', '0', 'Start With Windows');
INSERT INTO `uniadmin_settings` VALUES (57, 'AUTOLAUNCHWOW', '0', '0', 'Auto-Launch WoW');
INSERT INTO `uniadmin_settings` VALUES (58, 'STARTMINI', '1', '0', 'Start Minimized');
INSERT INTO `uniadmin_settings` VALUES (59, 'DELAYUPLOAD', '0', '0', 'Upload Delay');
INSERT INTO `uniadmin_settings` VALUES (60, 'DELAYSECONDS', '5', '0', 'Upload Delay Seconds');
INSERT INTO `uniadmin_settings` VALUES (61, 'RETRDATAURL', 'http://somewhere.com/something.php', '0', 'Data Retrieval URL');
INSERT INTO `uniadmin_settings` VALUES (62, 'SENDPWSECURE', '1', '0', 'encrypt password with md5 before sending');
INSERT INTO `uniadmin_settings` VALUES (63, 'WEBWOWSVFILE', 'SavedVariables.lua', '0', 'The Saved Variables file to write to for Web=>WoW');
INSERT INTO `uniadmin_settings` VALUES (64, 'USERAGENT', 'UniUploader', '0', 'The User Agent UU uses');
INSERT INTO `uniadmin_settings` VALUES (65, 'DOWNLOADBEFOREWOWL', '0', '0', 'Initiate Web=>WoW Before UU Launches WoW');
INSERT INTO `uniadmin_settings` VALUES (66, 'DOWNLOADBEFOREUPLOAD', '0', '0', 'Initiate Web=>WoW Before UU Uploads');
INSERT INTO `uniadmin_settings` VALUES (67, 'DOWNLOADAFTERUPLOAD', '1', '0', 'Initiate Web=>WoW After UU Uploads');

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
)AUTO_INCREMENT=118 ;

-- 
-- Dumping data for table `uniadmin_stats`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `uniadmin_svlist`
-- 

CREATE TABLE `uniadmin_svlist` (
  `id` int(11) NOT NULL auto_increment,
  `sv_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
)AUTO_INCREMENT=7 ;

-- 
-- Dumping data for table `uniadmin_svlist`
-- 

INSERT INTO `uniadmin_svlist` VALUES (1, 'CharacterProfiler');
INSERT INTO `uniadmin_svlist` VALUES (6, 'PvPLog');

-- --------------------------------------------------------

-- 
-- Table structure for table `uniadmin_users`
-- 

CREATE TABLE `uniadmin_users` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `level` char(3) NOT NULL default '',
  PRIMARY KEY  (`id`)
)AUTO_INCREMENT=14 ;

-- 
-- Dumping data for table `uniadmin_users`
-- 

INSERT INTO `uniadmin_users` VALUES (1, 'Default', '4cb9c8a8048fd02294477fcb1a41191a', '3');
