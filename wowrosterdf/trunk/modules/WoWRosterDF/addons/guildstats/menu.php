<?php
/******************************
 * worldofwarcraftguilds.com
 * Copyright 2006
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

$versions['versionDate']['itemsetslang'] = '$Date: 2006/06/27 20:00:00 $'; 
$versions['versionRev']['itemsetslang'] = '$Revision: 1.7.0 $'; 
$versions['versionAuthor']['itemsetslang'] = '$Author: nightfighter $'; 

$config['menu_name'] = $wordings[$roster_conf['roster_lang']]['guildstats'];
$config['menu_min_user_level'] = 0;
$config['menu_index_file'] = array();

$config['menu_index_file'][0][0] = ''; //request query variables delimited by & 
$config['menu_index_file'][0][1] = $wordings[$roster_conf['roster_lang']]['guildstatsbutton']; //menu link text
?>