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
error_reporting(E_ALL);



$header_title = $wordings[$roster_conf['roster_lang']]['guildbank'];

$menu_cell = '      <td class="menubarHeader" align="center" valign="middle"><a href="';

print '<div align="center">'."\n";

print border('sorange','start', 'Categorized Bank');

print '  <table cellpadding="3" cellspacing="0" class="menubar">'."\n<tr>\n";

echo $menu_cell.getlink('&amp;file=addon&amp;roster_addon_name=guildbank').'">Categorized Bank</a></td>'."\n";
echo $menu_cell.getlink('&amp;file=guildbank2').'">Bankers</a></td>'."\n";
echo $menu_cell.getlink('&amp;file=guildbank').'">'.$wordings[$roster_conf['roster_lang']]['guildbank'].'</a></td>'."\n";


print "  </tr>\n</table>\n";

print border('sorange','end');

echo '</div><br />';

if( $roster_conf['show_bank'] && is_user())
{
	require ($addonDir.'gbank.php');
}
else
{
	require_once(ROSTER_LIB.'login.php');
	$roster_login = new RosterUserLogin($script_filename);

	if( !$roster_login->getAuthorized() )
	{
		print
		//'<br />'.
		//'<span class="title_text">SigGen Config</span><br />'.
		$roster_login->getMessage().
		$roster_login->getLoginForm();

		return;
	}
}

echo $content;


?>