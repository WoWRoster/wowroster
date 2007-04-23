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

// -[ enUS Localization ]-

// Button names
$lang['SortMember_Members']		= 'Members';
$lang['SortMember_Stats']		= 'Stats';
$lang['SortMember_Honor']		= 'Honor';

// Index: Wrong $roster_pages[2]
$lang['SortMember_NoAction']	= 'Please check if you mistyped the url, as an invalid action was defined. If you got here by a link from within this addon, report the bug on the WoWroster forums.';

// Interface wordings
$lang['memberssortfilter']		= 'Sorting order and filtering';
$lang['memberssort']			= 'Sort';
$lang['memberscolshow']			= 'Show/Hide Columns';
$lang['membersfilter']			= 'Filter rows';

// Column headers
$lang['name']                   = 'Name';
$lang['class']                  = 'Class';
$lang['level']                  = 'Level';
$lang['title']                  = 'Title';
$lang['currenthonor']           = 'Current Honor Rank';
$lang['professions']            = 'Professions';
$lang['hearthed']               = 'Hearthed';
$lang['zone']                   = 'Last Zone';
$lang['lastonline']             = 'Last Online';
$lang['lastupdate']             = 'Last Updated';
$lang['note']                   = 'Note';
$lang['onote']                  = 'Officer Note';

$lang['strength']               = 'Strength';
$lang['agility']                = 'Agility';
$lang['stamina']                = 'Stamina';
$lang['intellect']              = 'Intellect';
$lang['spirit']                 = 'Spirit';
$lang['total']                  = 'Total';
$lang['health']                 = 'Health';
$lang['mana']                   = 'Mana';
$lang['armor']                  = 'Armor';
$lang['dodge']                  = 'Dodge';
$lang['parry']                  = 'Parry';
$lang['block']                  = 'Block';
$lang['crit']                   = 'Crit';

$lang['todayhk']                = 'Today HK';
$lang['todaycp']                = 'Today CP';
$lang['yesthk']                 = 'Yest HK';
$lang['yestcp']                 = 'Yest CP';
$lang['lifehk']                 = 'Life HK';
$lang['highestrank']            = 'Highest Rank';
$lang['honorpoints']            = 'Honor Points';
$lang['arenapoints']            = 'Arena Points';

$lang['main_name']              = 'Main name';
$lang['alt_type']               = 'Alt type';

// Configuration
$lang['SortMember_config']		= 'Go to SortMember configuration';
$lang['SortMember_config_page']	= 'SortMember Configuration';
$lang['documentation']			= 'Documentation';
$lang['uninstall']				= 'Uninstall';

// Page names
$lang['admin']['display']       = 'Display|Configure display options specific to SortMember.';
$lang['admin']['members']       = 'Members List|Configure visibility of members list columns.';
$lang['admin']['stats']         = 'Stats List|Configure visibility of stats list columns.';
$lang['admin']['honor']         = 'Honor List|Configure visibility of honor list columns.';
$lang['admin']['build']         = 'Main/Alt Relations|Configure how the Main/Alt relations are detected.';
$lang['admin']['documentation'] = 'Documentation|SortMember documentation on the WoWRoster wiki.';
$lang['admin']['updMainAlt']    = 'Update Relations|Update the Main/Alt relations using the data already in the DB.';

// Settings names on display page
$lang['admin']['openfilter']	= 'Open filterbox|Specify if you want the filterbox open or closed by default.';
$lang['admin']['nojs']          = 'List type|Specify if you want to use serverside sorting or clientside sorting+filtering.';
$lang['admin']['def_sort']		= 'Default sort|Specify the default sort method.';
$lang['admin']['member_tooltip']= 'Member tooltip|Turn the info tooltips on the member names on or off.';
$lang['admin']['group_alts']    = 'Group alts|Goup alts under their main, rather than sorting them separately.';
$lang['admin']['icon_size']     = 'Icon size|Set the size for the class/honor/profession icons.';
$lang['admin']['class_icon']    = 'Class icon|Turn the class icon display on or off.';
$lang['admin']['class_color']   = 'Class colors|Turn the coloring of class names on or off.';
$lang['admin']['level_bar']     = 'Level bars|Display level bars instead of just numbers.';
$lang['admin']['honor_icon']    = 'Honor icon|Display honor rank icon.';
$lang['admin']['compress_note'] = 'Compress note|Show guild note in a tooltip instead of in the column.';

// Settings on Members page
$lang['admin']['member_update_inst'] = 'Update Instructions|Controls the display of the Update Instructions on the members page';
$lang['admin']['member_motd'] = 'Guild MOTD|Show Guild Message of the Day on the top of the members page';
$lang['admin']['member_hslist']  = 'Honor System Stats|Controls the display of the Honor stats list on the members page';
$lang['admin']['member_pvplist']  = 'PvP-Logger Stats|Controls the display of the PvP-Logger stats on the members page<br />If you have disabled PvPlog uploading, there is no need to have this on';
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
$lang['admin']['stats_update_inst'] = 'Update Instructions|Controls the display of the Update Instructions on the stats page';
$lang['admin']['stats_motd'] = 'Guild MOTD|Show Guild Message of the Day on the top of the stats page';
$lang['admin']['stats_hslist']  = 'Honor System Stats|Controls the display of the Honor stats list on the stats page';
$lang['admin']['stats_pvplist']  = 'PvP-Logger Stats|Controls the display of the PvP-Logger stats on the stats page<br />If you have disabled PvPlog uploading, there is no need to have this on';
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
$lang['admin']['honor_update_inst'] = 'Update Instructions|Controls the display of the Update Instructions on the honor page';
$lang['admin']['honor_motd'] = 'Guild MOTD|Show Guild Message of the Day on the top of the honor page';
$lang['admin']['honor_hslist']  = 'Honor System Stats|Controls the display of the Honor stats list on the honor page';
$lang['admin']['honor_pvplist']  = 'PvP-Logger Stats|Controls the display of the PvP-Logger stats on the honor page<br />If you have disabled PvPlog uploading, there is no need to have this on';
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

// Settings names on build page
$lang['admin']['getmain_regex'] = 'Regex|The top 3 variables define how the regex is extracted from the member info. <br /> See the wiki link for details. <br /> This field specifies the regex to use.';
$lang['admin']['getmain_field'] = 'Apply on field|The top 3 variables define how the regex is extracted from the member info. <br /> See the wiki link for details. <br /> This field specifies which member field the regex is applied on.';
$lang['admin']['getmain_match'] = 'Use match no|The top 3 variables define how the regex is extracted from the member info. <br /> See the wiki link for details. <br /> This field specifies which return value of the regex is used.';
$lang['admin']['getmain_main']  = 'Main identifier|If the regex resolves to this value the character is assumed to be a main.';
$lang['admin']['defmain']       = 'No result|Set what you want the character to be defined as if the regex doesn\'t return anything.';
$lang['admin']['invmain']       = 'Invalid result|Set what you want the character to be defined as if the regex returns a result that isn\'t a guild member or equal to the main identifier.';
$lang['admin']['altofalt']      = 'Alt of Alt|Specify what to do if the character is a mainless alt.';
$lang['admin']['update_type']   = 'Update type|Specify on which trigger types to update main/alt relations.';
