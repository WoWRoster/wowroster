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

if ( !defined( 'CPG_NUKE' ) ) {
	exit;
}
$roster_show_header = true;
$roster_show_menu = true;
$roster_show_footer = true;
$header_title = $wordings[$roster_conf['roster_lang']]['LatestLoot'];
$url = '<a href="'.getlink('&amp;file=');

$menu_cell = '      <td class="menubarHeader" align="center" valign="middle">';

print '<div align="center">'."\n";


print border('sorange','start','Latest Loots');

print '  <table cellpadding="3" cellspacing="0" class="menubar">'."\n<tr>\n";

echo $menu_cell.$url.'index.php?name='.$module_name.'addon&roster_addon_name=eqdkp&amp;display=eqdkp">DKP Earned</a></td>'."\n";	
echo $menu_cell.$url.'index.php?name='.$module_name.'addon&roster_addon_name=eqdkp&amp;display=eqdkp2">Raids Attended</a></td>'."\n";
echo $menu_cell.$url.'index.php?name='.$module_name.'addon&roster_addon_name=latestloots">Latest Loot</a></td>'."\n";

print "  </tr>\n</table>\n";

print border('sorange','end');
echo '<br>';
require_once ($addonDir.'latestloot.php');

echo $content;
?>
