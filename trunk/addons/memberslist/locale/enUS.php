<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
 * @subpackage Locale
*/

// -[ enUS Localization ]-

// Installer names
$lang['memberslist']            = 'Members List';
$lang['memberslist_desc']       = 'A sortable, filterable member list';

// Button names
$lang['memberslist_Members']	= 'Members|Displays the guild member list with player notes, last online time, etc...';
$lang['memberslist_Stats']		= 'Stats|Displays each guild member\'s stats such as intellect, stamina, etc...';
$lang['memberslist_Honor']		= 'Honor|Displays each guild member\'s pvp information';
$lang['memberslist_Log']		= 'Member Log|Displays the upload log for new members and removed members';
$lang['memberslist_Realm']		= 'Members|Displays the member list for all guilds on the realm';
$lang['memberslist_RealmGuild']	= 'Guilds|Displays a list of all guilds on the realm';

// Interface wordings
$lang['memberssortfilter']		= 'Sorting order and filtering';
$lang['memberssort']			= 'Sort';
$lang['memberscolshow']			= 'Show/Hide Columns';
$lang['membersfilter']			= 'Filter rows';

$lang['openall']                = 'Open all';
$lang['closeall']               = 'Close all';
$lang['ungroupalts']            = 'Ungroup alts';
$lang['groupalts']              = 'Group alts';
$lang['clientsort']             = 'Client sorting';
$lang['serversort']             = 'Server sorting';

// Column headers
$lang['onote']                  = 'Officer Note';

$lang['honorpoints']            = 'Honor Points';
$lang['arenapoints']            = 'Arena Points';

$lang['main_name']              = 'Main name';
$lang['alt_type']               = 'Alt type';

// Last Online words
$lang['online_at_update']       = 'Online at Update';
$lang['second']                 = '%s second ago';
$lang['seconds']                = '%s seconds ago';
$lang['minute']                 = '%s minute ago';
$lang['minutes']                = '%s minutes ago';
$lang['hour']                   = '%s hour ago';
$lang['hours']                  = '%s hours ago';
$lang['day']                    = '%s day ago';
$lang['days']                   = '%s days ago';
$lang['week']                   = '%s week ago';
$lang['weeks']                  = '%s weeks ago';
$lang['month']                  = '%s month ago';
$lang['months']                 = '%s months ago';
$lang['year']                   = '%s year ago';
$lang['years']                  = '%s years ago';

$lang['armor_tooltip']			= 'Reduces physical damage taken by %1$s%%';

$lang['motd']                   = 'MOTD';
$lang['accounts']               = 'Accounts';

// Configuration
$lang['memberslist_config']		= 'Go to memberslist configuration';
$lang['memberslist_config_page']= 'memberslist Configuration';
$lang['documentation']			= 'Documentation';
$lang['uninstall']				= 'Uninstall';

// Page names
$lang['admin']['display']       = 'Display|Configure display options specific to memberslist.';
$lang['admin']['members']       = 'Members List|Configure visibility of members list columns.';
$lang['admin']['stats']         = 'Stats List|Configure visibility of stats list columns.';
$lang['admin']['honor']         = 'Honor List|Configure visibility of honor list columns.';
$lang['admin']['log']           = 'Member Log|Configure visibility of member log columns.';
$lang['admin']['build']         = 'Main/Alt Relations|Configure how the Main/Alt relations are detected.';
$lang['admin']['ml_wiki']       = 'Documentation|Members List documentation on the WoWRoster wiki.';
$lang['admin']['updMainAlt']    = 'Update Relations|Update the Main/Alt relations using the data already in the DB.';
$lang['admin']['page_size']		= 'Page size|Configure the number of items per page, or 0 for no pagination';

// Settings names on display page
$lang['admin']['openfilter']	= 'Open Filterbox|Specify if you want the filterbox open or closed by default.';
$lang['admin']['nojs']          = 'List Type|Specify if you want to use serverside sorting or clientside sorting+filtering.';
$lang['admin']['def_sort']		= 'Default Sort|Specify the default sort method.';
$lang['admin']['member_tooltip']= 'Member Tooltip|Turn the info tooltips on the member names on or off.';
$lang['admin']['group_alts']    = 'Group Alts|Goup alts under their main, rather than sorting them separately.';
$lang['admin']['icon_size']     = 'Icon Size|Set the size for the class/honor/profession icons.';
$lang['admin']['spec_icon']		= 'Talent Spec icon|Turn the talent spec icon on or off.';
$lang['admin']['class_icon']    = 'Class Icon|Controls the class/talent spec icon display.<br />Full - Display Talent Spec and Class Icon<br />On - Display only class icon<br />Off- Hide icons';
$lang['admin']['class_text']    = 'Class Text|Controls the class text display.<br />Color - Class text with coloring<br />On - Display class text<br />Off - Hide class text';
$lang['admin']['talent_text']   = 'Talent Text|Shows talent spec instead of class name.';
$lang['admin']['level_bar']     = 'Level Bars|Display level bars instead of just numbers.';
$lang['admin']['honor_icon']    = 'Honor Icon|Display honor rank icon.';
$lang['admin']['compress_note'] = 'Compress Note|Show guild note in a tooltip instead of in the column.';

// Settings on Members page
$lang['admin']['member_update_inst'] = 'Update Instructions|Controls the display of the Update Instructions on the members page';
$lang['admin']['member_motd']	= 'Guild MOTD|Show Guild Message of the Day on the top of the members page';
$lang['admin']['member_hslist']	= 'Honor System Stats|Controls the display of the Honor stats list on the members page';
$lang['admin']['member_pvplist']= 'PvP-Logger Stats|Controls the display of the PvP-Logger stats on the members page<br />If you have disabled PvPlog uploading, there is no need to have this on';
$lang['admin']['member_class']  = 'Class|Set visibility of the class column on the members page';
$lang['admin']['member_level']  = 'Level|Set visibility of the level column on the members page';
$lang['admin']['member_gtitle'] = 'Guild Title|Set visibility of the guild title column on the members page';
$lang['admin']['member_hrank']  = 'Honor Rank|Set visibility of the last honor rank column on the members page';
$lang['admin']['member_prof']   = 'Profession|Set visibility of the profesion column on the members page';
$lang['admin']['member_hearth'] = 'Hearth|Set visibility of the hearthstone location column on the members page';
$lang['admin']['member_zone']   = 'Zone|Set visibility of the last zone column on the members page';
$lang['admin']['member_online'] = 'Last Online|Set visibility of the last online column on the members page';
$lang['admin']['member_update'] = 'Last Update|Set visibility of the last update column on the members page';
$lang['admin']['member_note']   = 'Note|Set visibility of the note column on the members page';
$lang['admin']['member_onote']  = 'Officer Note|Set visibility of the officer note column on the members page';

// Settings on Stats page
$lang['admin']['stats_update_inst'] = 'Update Instructions|Controls the display of the Update Instructions on the stats page';
$lang['admin']['stats_motd']	= 'Guild MOTD|Show Guild Message of the Day on the top of the stats page';
$lang['admin']['stats_hslist']  = 'Honor System Stats|Controls the display of the Honor stats list on the stats page';
$lang['admin']['stats_pvplist']	= 'PvP-Logger Stats|Controls the display of the PvP-Logger stats on the stats page<br />If you have disabled PvPlog uploading, there is no need to have this on';
$lang['admin']['stats_class']   = 'Class|Set visibility of the class column on the stats page';
$lang['admin']['stats_level']   = 'Level|Set visibility of the level column on the stats page';
$lang['admin']['stats_str']     = 'Strength|Set visibility of the strength column on the stats page';
$lang['admin']['stats_agi']     = 'Agility|Set visibility of the agility column on the stats page';
$lang['admin']['stats_sta']     = 'Stamina|Set visibility of the stamina column on the stats page';
$lang['admin']['stats_int']     = 'Intellect|Set visibility of the intellect column on the stats page';
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
$lang['admin']['honor_motd']	= 'Guild MOTD|Show Guild Message of the Day on the top of the honor page';
$lang['admin']['honor_hslist']  = 'Honor System Stats|Controls the display of the Honor stats list on the honor page';
$lang['admin']['honor_pvplist']	= 'PvP-Logger Stats|Controls the display of the PvP-Logger stats on the honor page<br />If you have disabled PvPlog uploading, there is no need to have this on';
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

// Settings on Members page
$lang['admin']['log_update_inst'] = 'Update Instructions|Controls the display of the Update Instructions on the member log page';
$lang['admin']['log_motd']		= 'Guild MOTD|Show Guild Message of the Day on the top of the member log page';
$lang['admin']['log_hslist']	= 'Honor System Stats|Controls the display of the Honor stats list on the member log page';
$lang['admin']['log_pvplist']	= 'PvP-Logger Stats|Controls the display of the PvP-Logger stats on the member log page<br />If you have disabled PvPlog uploading, there is no need to have this on';
$lang['admin']['log_class']		= 'Class|Set visibility of the class column on the member log page';
$lang['admin']['log_level']		= 'Level|Set visibility of the level column on the member log page';
$lang['admin']['log_gtitle']	= 'Guild Title|Set visibility of the guild title column on the member log page';
$lang['admin']['log_type']		= 'Update Type|Set visibility of the update type column on the member log page';
$lang['admin']['log_date']		= 'Last Update|Set visibility of the date column on the member log page';
$lang['admin']['log_note']		= 'Note|Set visibility of the note column on the member log page';
$lang['admin']['log_onote']		= 'Officer Note|Set visibility of the officer note column on the member log page';

// Settings names on build page
$lang['admin']['getmain_regex'] = 'Regex|This field specifies the regex to use. <br /> See the wiki link for details.';
$lang['admin']['getmain_field'] = 'Apply on field|This field specifies which member field the regex is applied on. <br /> See the wiki link for details.';
$lang['admin']['getmain_match'] = 'Use match no|This field specifies which return value of the regex is used. <br /> See the wiki link for details.';
$lang['admin']['getmain_main']  = 'Main identifier|If the regex resolves to this value the character is assumed to be a main.';
$lang['admin']['defmain']       = 'No result|Set what you want the character to be defined as if the regex doesn\'t return anything.';
$lang['admin']['invmain']       = 'Invalid result|Set what you want the character to be defined as <br /> if the regex returns a result that isn\'t a guild member or equal to the main identifier.';
$lang['admin']['altofalt']      = 'Alt of Alt|Specify what to do if the character is a mainless alt.';
$lang['admin']['update_type']   = 'Update type|Specify on which trigger types to update main/alt relations.';
