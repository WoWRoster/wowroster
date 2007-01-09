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
 *	Introduction:
 *	This addon fetches the 20 last drops from your eqdkp installations.
 *	
 *	In Details:
 *	The addon heavy relies on the wellknown itemstats mod for eqdkp
 *  found @ http://forums.eqdkp.com/index.php?showforum=69
 *	This is customized a bit though but not much.<br>
 *
 *	Installation Procedure:
 *	Pretty basic, extract the addon to your addons dir.
 *
 *	Edit the following files:
 *
 *	itemstats/config.php; setup database info for itemstats
 *	you can also change the location of the images used, though
 *	this mod will use the roster icons.
 *
 *	eqdkp_config.php:
 *	enter the database configuration for eqdkp along with your eqdkp table prefix
 *
 *	Localization support:
 *	This addon is availbale in Englisb and in German,
 *	but lol.. i babelfish'ed the translation to german so 
 *	don't be surprised if you find any erros.. report them!
 *
 *	That's it i think.. have fun kids and report bugs and wishes
 *	Regs.
 *	Thumann, First Choice - Terenas, EU 
 *	www: fc-guild.org
 ******************************/ 
if ( !defined( 'CPG_NUKE' ) ) {
	exit;
}

//include('eqdkp_config.php');
//include_once('./modules/Forums/itemstats/eqdkp_itemstats.php');

$server_name_escape = $wowdb->escape($roster_conf['server_name']);
$guild_name_escape = $wowdb->escape($roster_conf['guild_name']);
/*
* Connect to the Database
*/
//$link = mysql_connect($addon_conf['latestloot']['db_host2'], $addon_conf['latestloot']['db_user2'], $addon_conf['latestloot']['db_passwd2']) or die($_SERVER['PHP_SELF'].":".__LINE__." "."Could not connect to the server");
//mysql_select_db($addon_conf['latestloot']['db_name2']) or die($_SERVER['PHP_SELF'].":".__LINE__." "."Could not select Database");
?>
<?php
/*
* Get Main title from eqdkp
*/

$query = "SELECT config_value FROM ".$addon_conf['latestloot']['eqdkp_prefix']."config WHERE config_name = 'main_title'";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_array($result))
{
	$eqname = $row[0];
}
mysql_free_result($result);

/*
* Get total drops from eqdkp
*/
$query = "select count(*) from ".$addon_conf['latestloot']['eqdkp_prefix']."items";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_array($result))
{
$total_drops = $row[0];
}
mysql_free_result($result);

if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}
$query = "SELECT `item_name`, `item_buyer` FROM ".$addon_conf['latestloot']['eqdkp_prefix']."items ORDER BY item_date DESC limit 20";
if ($roster_conf['sqldebug'])
{
print "<!-- $query -->\n";
}
echo border('syellow','start');
echo '<table cellpadding="0" cellspacing="0"> ';
echo '<tr>';
echo '<tr><td class="membersHeader">'.$eqname.'</td></tr>';
$result = mysql_query($query) or die(mysql_error());
while ($row = mysql_fetch_array($result)) {
$last_mc_loot = $row[item_name];
$buyer = $row[item_buyer];
$myhtml = "[item]".$last_mc_loot."[/item] ".'<span class="by-text">'.$wordings[$roster_conf['roster_lang']]['by'].'</span>'." ".'<a href="char.php?name='.urlencode($buyer).'&server='.$roster_conf['server_name'].'">'.utf8_encode($buyer).'</a>'. "";

echo itemstats_parse('<tr><td class="membersHeader">'.$myhtml.'</td></tr>');
}
echo '<tr><td class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['TotalDrops'].': '.$total_drops.'</td></tr>';
echo '</tr>';
echo '</table>';
echo border('syellow','end');
echo '<br />';
?>