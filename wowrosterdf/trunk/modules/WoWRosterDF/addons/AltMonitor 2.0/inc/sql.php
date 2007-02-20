<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$install_sql['1.0.0'] = "
DROP TABLE IF EXISTS `".ROSTER_ALT_TABLE."`;
CREATE TABLE `".ROSTER_ALT_TABLE."` (
  `member_id` int(11)    unsigned NOT NULL default '0',
  `main_id`   int(11)    unsigned NOT NULL default '0',
  `alt_type`  tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY (`member_id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `".ROSTER_ALT_CONFIG_TABLE."`;
CREATE TABLE `".ROSTER_ALT_CONFIG_TABLE."` (
  `id` int(11) NOT NULL,
  `config_name` varchar(255) default NULL,
  `config_value` tinytext,
  `form_type` mediumtext,
  `config_type` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# Master data for the config file
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (1,'config_list','build|display','display','master');
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (2,'version','1.0.1','display','master');

# Build settings
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (1000,'getmain_regex','/ALT-([\\\\\\\\w]+)/i','text{50|30','build');
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (1010,'getmain_field','note','text{15|30','build');
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (1020,'getmain_match','1','text{2|30','build');
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (1030,'getmain_main','Main','text{20|30','build');
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (1040,'defmain','1','radio{Main^1|Mainless Alt^0','build');
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (1050,'invmain','0','radio{Main^1|Mainless Alt^0','build');
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (1060,'altofalt','alt','select{Try to resolve^resolve|Leave in table^leave|Set as main^main|Set as mainless alt^alt','build');

# Display options
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (2000,'showmain','0','radio{Show^1|Hide^0','display');
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (2010,'altopen','1','radio{Open^1|Closed^0','display');
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (2020,'mainlessbottom','1','radio{Bottom^1|Top^0','display');
";

$install_sql['1.1.0'] = "
UPDATE `".ROSTER_ALT_CONFIG_TABLE."` SET `form_type` = 'select{Member name^name|Guild Title^guild_title|Public note^note|Officer note^officer_note' WHERE `config_name` = 'getmain_field';
";

$install_sql['1.2.0'] = "
INSERT INTO `".ROSTER_ALT_CONFIG_TABLE."` VALUES (3,'startpage','1','display','master');
";

$install_sql['1.4.0'] = "
UPDATE `".ROSTER_ALT_CONFIG_TABLE."` SET `config_value` = 'build' WHERE `config_name` = 'startpage';
";

$install_sql['1.6.0'] = "
INSERT INTO `" .ROSTER_ALT_CONFIG_TABLE."` VALUES (1070,'update_type','3','select{None^0|Guild^1|Character^2|Both^3','build');
";

// ADD THE RIGHT GETMAIN_FIELD STATS HERE
$install_sql['2.0.1'] = "
UPDATE `".ROSTER_ALT_CONFIG_TABLE."` SET `config_value` = 'Note', `form_type` = 'select{Public Note^Note|Officer Note^OfficerNote' WHERE `config_name` = 'getmain_field';
";


$uninstall_sql['1.0.0'] = "
DROP TABLE IF EXISTS `".ROSTER_ALT_TABLE."`;
DROP TABLE IF EXISTS `".ROSTER_ALT_CONFIG_TABLE."`;
";
?>
