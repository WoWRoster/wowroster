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

/////////////////////////// CONFIGURATION OPTIONS /////////////////////////////
// Number of columns per category row
$row_columns = 18;
// Show color border
$color_border = 1; // 0 = No, 1 = Yes
// Do you want categories with no items to appear?
$show_empty = 1; // 0 = No, 1 = Yes
////// ItemLink Site
// 1=Thottbot,
// 2=Allakhazam for 'enUS' and blasc.de for 'deDE'.
$searchtype = 1;
// The order the tables will display in the guildbank page
// You can exclude items you don't want to appear
$display_order = array(20,21,22,18,3,4,5,6,7,8,31,19,1,2,11,12,15,17,16,14,13,32,
		       10,26,24,25,9,23,27,28,30,29);

// The header name for each category that will appear
$bankitem[1]['msg']  = $wordings[$roster_conf['roster_lang']]['bankitem_1'];
$bankitem[2]['msg']  = $wordings[$roster_conf['roster_lang']]['bankitem_2'];
$bankitem[3]['msg']  = $wordings[$roster_conf['roster_lang']]['bankitem_3'];
$bankitem[4]['msg']  = $wordings[$roster_conf['roster_lang']]['bankitem_4'];
$bankitem[5]['msg']  = $wordings[$roster_conf['roster_lang']]['bankitem_5'];
$bankitem[6]['msg']  = $wordings[$roster_conf['roster_lang']]['bankitem_6'];
$bankitem[7]['msg']  = $wordings[$roster_conf['roster_lang']]['bankitem_7'];
$bankitem[8]['msg']  = $wordings[$roster_conf['roster_lang']]['bankitem_8'];
$bankitem[9]['msg']  = $wordings[$roster_conf['roster_lang']]['bankitem_9'];
$bankitem[10]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_10'];
$bankitem[11]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_11'];
$bankitem[12]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_12'];
$bankitem[13]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_13'];
$bankitem[14]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_14'];
$bankitem[15]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_15'];
$bankitem[16]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_16'];
$bankitem[17]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_17'];
$bankitem[18]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_18'];
$bankitem[19]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_19'];
$bankitem[20]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_20'];
$bankitem[21]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_21'];
$bankitem[22]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_22'];
$bankitem[23]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_23'];
$bankitem[24]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_24'];
$bankitem[25]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_25'];
$bankitem[26]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_26'];
$bankitem[27]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_27'];
$bankitem[28]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_28'];
$bankitem[29]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_29'];
$bankitem[30]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_30'];
$bankitem[31]['msg'] = $wordings[$roster_conf['roster_lang']]['bankitem_31'];

include($addonDir.'searcharrays.php');

switch ($searchtype) {
	case 2:
		// Allakhazam / blasc.de
		$itemlink['enUS']='http://wow.allakhazam.com/search.html?q=';
		$itemlink['deDE']='http://blasc.planet-multiplayer.de/?i=';
		break;
	default:
		// Thottbot
		$itemlink['enUS']='http://www.thottbot.com/index.cgi?i=';
		$itemlink['deDE']='http://www.thottbot.com/de/index.cgi?i=';
}

?>