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

// -[ esES Localization ]-

// Installer
$lang['SortMember_install_page']= 'Instalador de SortMember';
$lang['SortMember_install']     = 'Las tablas de SortMember no estan instaladas todavia. Haz click en Install para empezar la instalacion.';
$lang['SortMember_upgrade']     = 'Las tablas de SortMember no estan al dia. Haz click en Update para actualizar la base de datos o haz click en Install para borra y volver a vrear las tablas de SortMember.';
$lang['SortMember_no_upgrade']  = 'Las tablas de SortMember estan al dia. Haz click en Reinstall debajo para reinstalar las tablas.';
$lang['SortMember_uninstall']   = 'Esto borrara la configuracion de SortMember y las relaciones Main/Alt. Haz click en "Uninstall" para proceder.';
$lang['SortMember_installed']   = 'Felicidades, SortMember se ha instalado correctamente. Haz click en el link de debajo para configurarlo.';
$lang['SortMember_uninstalled'] = 'SortMember ha sido desinstalado. Necesitas borrar el addon de tu servidor web.';

// Main/Alt display
$lang['SortMember_Members']		= 'SortMembers';
$lang['SortMember_Stats']		= 'SortStats';
$lang['SortMember_Honor']		= 'SortHonor';
$lang['SortMember_NoAction']    = 'Comprueba si has escrito bien la direccion URL, se ha encontrado una accion incorrecta. Si has llegado aqui mediante un link de este addon, informa del error en los foros de wowroster.net.';

$lang['memberssortfilter']		= 'Sorting order and filtering';
$lang['memberssort']			= 'Sort';
$lang['memberscolshow']			= 'Show/Hide Columns';
$lang['membersfilter']			= 'Filter rows';

// Configuration
$lang['SortMember_config']      = 'Ir a la configuracion de SortMember';
$lang['SortMember_config_page'] = 'Configuracion de SortMember';
$lang['documentation']          = 'Documentacion';
$lang['uninstall']              = 'Uninstall';

// Page names
$lang['admin']['display']       = 'Display|Configure display options specific to SortMember.';
$lang['admin']['members']       = 'Members List|Configure visibility of members list columns.';
$lang['admin']['stats']         = 'Stats List|Configure visibility of stats list columns.';
$lang['admin']['honor']         = 'Honor List|Configure visibility of honor list columns.';
$lang['admin']['documentation'] = 'Documentation|SortMember documentation on the WoWRoster wiki.';

// Settings names on display page
$lang['admin']['openfilter']	= 'Open filterbox|Specify if you want the filterbox open or closed by default.';
$lang['admin']['nojs']          = 'List type|Specify if you want to use serverside sorting or clientside sorting+filtering.';
$lang['admin']['def_sort']		= 'Default sort|Specify the default sort method.';
$lang['admin']['member_tooltip']= 'Member tooltip|Turn the info tooltips on the member names on or off.';
$lang['admin']['icon_size']     = 'Icon size|Set the size for the class/honor/profession icons.';
$lang['admin']['class_icon']    = 'Class icon|Turn the class icon display on or off.';
$lang['admin']['class_color']   = 'Class colors|Turn the coloring of class names on or off.';
$lang['admin']['level_bar']     = 'Level bars|Display level bars instead of just numbers.';
$lang['admin']['honor_icon']    = 'Honor icon|Display honor rank icon.';
$lang['admin']['compress_note'] = 'Compress note|Show guild note in a tooltip instead of in the column.';

// Settings on Members page
$lang['admin']['member_class']  = 'Class|Set visibility of the class column on the members page';
$lang['admin']['member_level']  = 'Level|Set visibility of the level column on the members page';
$lang['admin']['member_gtitle'] = 'Guild Title|Set visibility of the guild title column on the members page';
$lang['admin']['member_hrank']  = 'Honor Rank|Set visibility of the last honor rank column on the members page';
$lang['admin']['member_prof']   = 'Class|Set visibility of the profesion column on the members page';
$lang['admin']['member_hearth'] = 'Hearth|Set visibility of the hearthstone location column on the members page';
$lang['admin']['member_zone']   = 'Zone|Set visibility of the last zone column on the members page';
$lang['admin']['member_online'] = 'Last Online|Set visibility of the last online column on the members page';
$lang['admin']['member_update'] = 'Last Update|Set visibility of the last update column on the members page';
$lang['admin']['member_note']   = 'Note|Set visibility of the note column on the members page';
$lang['admin']['member_onote']  = 'Officer Note|Set visibility of the officer note column on the members page';

// Settings on Stats page
$lang['admin']['stats_class']   = 'Class|Set visibility of the class column on the stats page';
$lang['admin']['stats_level']   = 'Level|Set visibility of the level column on the stats page';
$lang['admin']['stats_str']     = 'Strength|Set visibility of the strength column on the stats page';
$lang['admin']['stats_agi']     = 'Agility|Set visibility of the agility column on the stats page';
$lang['admin']['stats_sta']     = 'Stamina|Set visibility of the stamina column on the stats page';
$lang['admin']['stats_int']     = 'Intelect|Set visibility of the intelect column on the stats page';
$lang['admin']['stats_spi']     = 'Spirit|Set visibility of the spirit column on the stats page';
$lang['admin']['stats_sum']     = 'Total|Set visibility of the stat sum column on the stats page';
$lang['admin']['stats_health']  = 'Health|Set visibility of the health column on the stats page';
$lang['admin']['stats_mana']    = 'Mana|Set visibility of the mana column on the stats page';
$lang['admin']['stats_armor']   = 'Armor|Set visibility of the armor column on the stats page';
$lang['admin']['stats_dodge']   = 'Dodge|Set visibility of the dodge column on the stats page';
$lang['admin']['stats_parry']   = 'Parry|Set visibility of the parry column on the stats page';
$lang['admin']['stats_block']   = 'Block|Set visibility of the block column on the stats page';
$lang['admin']['stats_crit']    = 'Crit|Set visibility of the crit column on the stats page';

// Settings on Honor page
$lang['admin']['honor_class']   = 'Class|Set visibility of the class column on the honor page';
$lang['admin']['honor_level']   = 'Level|Set visibility of the level column on the honor page';
$lang['admin']['honor_thk']     = 'Today\'s HK|Set visibility of the Today\'s HK column on the honor page';
$lang['admin']['honor_tcp']     = 'Today\'s CP|Set visibility of the Today\'s CP column on the honor page';
$lang['admin']['honor_yhk']     = 'Yesterday\'s HK|Set visibility of the Yesterday\'s HK column on the honor page';
$lang['admin']['honor_ycp']     = 'Yesterday\'s CP|Set visibility of the Yesterday\'s CP column on the honor page';
$lang['admin']['honor_lifehk']  = 'Lifetime HK|Set visibility of the Lifetime HK column on the honor page';
$lang['admin']['honor_hrank']   = 'Honor Rank|Set visibility of the Honor Rank column on the honor page';
$lang['admin']['honor_hp']      = 'Honor Points|Set visibility of the honor points column on the honor page';
$lang['admin']['honor_ap']      = 'Arena Points|Set visibility of the arena points column on the honor page';

// Translator:
//
// BarryZGZ
