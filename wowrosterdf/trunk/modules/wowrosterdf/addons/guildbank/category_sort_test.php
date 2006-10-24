<?
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
error_reporting(E_ALL);
define('DIR_SEP','/');
$addonDir = './';
include ('../../settings.php');
include ($addonDir.'conf.php');
include ($addonDir.'localization.php');
//include ($addonDir.'searcharrays.php');

foreach ($search_order as $CategoryID) {
	echo "<table border='1'>";
	$cat = $bankitem[$CategoryID]['arg'];
	$arrayitem = array();
	$arrayitem = $itemarray[$cat];
	echo '<tr><th>'.$CategoryID.'</th><th>'.$cat.'</th></tr>';
	foreach ($arrayitem as $key => $value) {
		echo '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
	}
	echo '</table><BR>';
}
?>
