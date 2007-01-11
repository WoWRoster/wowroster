<?php

$bank_menu = '<table cellpadding="3" cellspacing="0" class="menubar">'."\n<tr>\n";

$menu_cell = '<td class="menubarHeader" align="center" valign="middle">';

$bank_menu .= $menu_cell.'<a href="'.getlink('&amp;file=addon&roster_addon_name=guildbank').'">Catagorized Bank</a></td>'."\n";
$bank_menu .= $menu_cell.'<a href="'.getlink('&amp;file=guildbank2').'">Bankers</a></td>'."\n";
$bank_menu .= $menu_cell.'<a href="'.getlink('&amp;file=guildbank').'">'.$wordings[$roster_conf['roster_lang']]['guildbank'].'</a></td>'."\n";

$bank_menu .= "</tr>\n</table>\n";

print messagebox($bank_menu,$wordings[$roster_conf['roster_lang']]['guildbank'],'sorange');

echo '<br />';