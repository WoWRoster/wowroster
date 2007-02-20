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
// madeby sql 
if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}
$lang = $roster_conf['roster_lang'];

$install_sql['1.0.0'] = "
DROP TABLE IF EXISTS `".MADEBY_CONFIG_TABLE."`;
CREATE TABLE `".MADEBY_CONFIG_TABLE."` (
  `id` int(11) NOT NULL,
  `config_name` varchar(255) default NULL,
  `config_value` tinytext,
  `form_type` mediumtext,
  `config_type` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# Master data for the config file
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (1,'config_list','display|recipe','display','master');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (2,'version','1.0.0','display','master');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (3,'startpage','display','display','master');

# Display settings
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (1000,'display_recipe_icon','1','radio{Show^1|Hide^0','display');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (1010,'display_recipe_name','1','radio{Show^1|Hide^0','display');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (1020,'display_recipe_level','1','radio{Show^1|Hide^0','display');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (1030,'display_recipe_tooltip','1','radio{Show^1|Hide^0','display');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (1040,'display_recipe_type','1','radio{Show^1|Hide^0','display');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (1050,'display_recipe_reagents','1','radio{Show^1|Hide^0','display');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (1060,'display_recipe_makers','1','radio{Show^1|Hide^0','display');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (1070,'display_recipe_makers_count','3','select{1^1|2^2|3^3|4^4|5^5|6^6|7^7|8^8|9^9|10^10','display');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (1080,'display_prof_bar','1','radio{Show^1|Hide^0','display');

#recipe settings
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (2000,'".$wordings[$lang]['Blacksmithing']."','1','radio{Show^1|Hide^0','recipe');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (2010,'".$wordings[$lang]['Mining']."','1','radio{Show^1|Hide^0','recipe');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (2030,'".$wordings[$lang]['Alchemy']."','1','radio{Show^1|Hide^0','recipe');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (2040,'".$wordings[$lang]['Leatherworking']."','1','radio{Show^1|Hide^0','recipe');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (2060,'".$wordings[$lang]['Tailoring']."','1','radio{Show^1|Hide^0','recipe');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (2070,'".$wordings[$lang]['Enchanting']."','1','radio{Show^1|Hide^0','recipe');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (2080,'".$wordings[$lang]['Engineering']."','1','radio{Show^1|Hide^0','recipe');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (2090,'".$wordings[$lang]['Cooking']."','1','radio{Show^1|Hide^0','recipe');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (2110,'".$wordings[$lang]['First Aid']."','1','radio{Show^1|Hide^0','recipe');
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (2120,'".$wordings[$lang]['Poisons']."','1','radio{Show^1|Hide^0','recipe');";

$install_sql['1.1.0'] = "
INSERT INTO `".MADEBY_CONFIG_TABLE."` VALUES (2130,'".$wordings[$lang]['Jewelcrafting']."','1','radio{Show^1|Hide^0','recipe');";
?>
