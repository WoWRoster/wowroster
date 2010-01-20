<?php
/**
 * WoWRoster.net WoWRoster
 *
 * enUS Locale File
 *
 *
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.5.0
 * @package    WoWRoster
 * @subpackage Locale
*/

$lang['langname'] = 'English';

//Instructions how to upload, as seen on the mainpage
$lang['update_link']='Click here for Updating Instructions';
$lang['update_instructions']='Updating Instructions';

$lang['lualocation']='Click browse and select your *.lua files to upload';

$lang['filelocation']='is located at<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$lang['nodata']='Could not find guild: <b>\'%1$s\'</b> for server <b>\'%2$s\'</b><br />You need to <a href="%3$s">load your guild</a> first and make sure you <a href="%4$s">finished configuration</a><br /><br /><a href="http://www.wowroster.net/MediaWiki/Roster:Install" target="_blank">Click here for installation instructions</a>';
$lang['no_default_guild']='No default guild has been set yet. Please set one here.';
$lang['nodefguild']='No default guild has been set yet. Please make sure you have <a href="%1$s">finished configuration</a><br /><br /><a href="http://www.wowroster.net/MediaWiki/Roster:Install" target="_blank">Click here for installation instructions</a>';
$lang['nodata_title']='No Guild Data';

$lang['update_page']='Update Profile';

$lang['guild_addonNotFound']='Could not find Guild. GuildProfiler Addon not installed correctly?';

$lang['ignored']='Ignored';
$lang['update_disabled']='Update access has been disabled';

$lang['nofileUploaded']='UniUploader did not upload any file(s), or uploaded the wrong file(s).';
$lang['roster_upd_pwLabel']='Roster Update Password';
$lang['roster_upd_pw_help']='(Some lua updates may require a password)';


$lang['roster_error'] = 'Roster Error';
$lang['sql_queries'] = 'SQL Queries';
$lang['invalid_char_module'] = 'Invalid characters in addon name';
$lang['module_not_exist'] = 'The module [%1$s] does not exist';

$lang['addon_error'] = 'Addon Error';
$lang['specify_addon'] = 'You must specify an addon name!';
$lang['addon_not_exist'] = '<b>The addon [%1$s] does not exist!</b>';
$lang['addon_disabled'] = '<b>The addon [%1$s] has been disabled</b>';
$lang['addon_no_access'] = '<b>Insufficient credentials to access [%1$s]</b>';
$lang['addon_upgrade_notice'] = '<b>The addon [%1$s] has been disabled because it needs to be upgraded</b>';
$lang['addon_not_installed'] = '<b>The addon [%1$s] has not been installed yet</b>';
$lang['addon_no_config'] = '<b>The addon [%1$s] does not have a configuration</b>';

$lang['char_error'] = 'Character Error';
$lang['specify_char'] = 'Character was not specified';
$lang['no_char_id'] = 'Sorry no character data for member_id [ %1$s ]';
$lang['no_char_name'] = 'Sorry no character data for <strong>%1$s</strong> of <strong>%2$s</strong>';

$lang['roster_cp'] = 'Roster Control Panel';
$lang['roster_cp_ab'] = 'RosterCP';
$lang['roster_cp_not_exist'] = 'Page [%1$s] does not exist';
$lang['roster_cp_invalid'] = 'Invalid page specified or insufficient credentials to access this page';
$lang['access_level'] = 'Access Level';

$lang['parsing_files'] = 'Parsing files';
$lang['parsed_time'] = 'Parsed %1$s in %2$s seconds';
$lang['error_parsed_time'] = 'Error while parsing %1$s after %2$s seconds';
$lang['upload_not_accept'] = '%1$s is not allowed for upload';

$lang['processing_files'] = 'Processing Files';
$lang['error_addon'] = 'There was an error in addon %1$s in method %2$s';
$lang['addon_messages'] = 'Addon Messages:';

$lang['not_accepted'] = '%1$s %2$s @ %3$s-%4$s not accepted';

$lang['not_updating'] = 'NOT Updating %1$s for [%2$s] - %3$s';
$lang['not_update_guild'] = 'NOT Updating Guild List for %1$s@%3$s-%2$s';
$lang['not_update_guild_time'] = 'NOT Updating Guild List for %1$s. Guild profile is too old';
$lang['not_update_char_time'] = 'NOT Updating Character %1$s. Profile is too old';
$lang['no_members'] = 'Data does not contain any guild members';
$lang['upload_data'] = 'Updating %1$s Data for [<span class="orange">%2$s@%4$s-%3$s</span>]';
$lang['realm_ignored'] = 'Realm: %1$s Not Scanned';
$lang['guild_realm_ignored'] = 'Guild: %1$s @ Realm: %2$s Not Scanned';
$lang['update_members'] = 'Updating Members';
$lang['update_errors'] = 'Update Errors';
$lang['update_log'] = 'Update Log';
$lang['save_error_log'] = 'Save Error Log';
$lang['save_update_log'] = 'Save Update Log';

$lang['new_version_available'] = 'There is a new version of %1$s available <span class="green">v%2$s</span><br />Released: %3$s<br />Get it <a href="%4$s" target="_blank">HERE</a>';

$lang['remove_install_files'] = 'Remove Install Files';
$lang['remove_install_files_text'] = 'Please remove <span class="green">install.php</span> in this directory';

$lang['upgrade_wowroster'] = 'Upgrade WoWRoster';
$lang['upgrade'] = 'Upgrade';
$lang['select_version'] = 'Select Version';
$lang['upgrade_wowroster_text'] = "Looks like you've loaded a new version of Roster<br /><br />\nYour Version: <span class=\"red\">%1\$s</span><br />\nNew Version: <span class=\"green\">%2\$s</span><br /><br />\n<a href=\"upgrade.php\" style=\"border:1px outset white;padding:2px 6px 2px 6px;\">UPGRADE</a>";
$lang['no_upgrade'] = 'You have already upgraded Roster<br />Or you have a newer version than this upgrader';
$lang['upgrade_complete'] = 'Your WoWRoster installation has been successfully upgraded';

// Menu buttons
$lang['menu_header_01'] = 'Guild Information';
$lang['menu_header_02'] = 'Realm Information';
$lang['menu_header_03'] = 'Update Profile';
$lang['menu_header_04'] = 'Utilities';
$lang['menu_header_scope_panel'] = '%s Panel';

$lang['menu_totals'] = 'Total: %1$s (+%2$s Alts)';
$lang['menu_totals_level'] = ' At least L%1$s';

// Updating Instructions
$lang['index_text_uniloader'] = '(You can download the program from the WoWRoster website, look for the UniUploader Installer for the latest version)';

$lang['update_instruct']='
<strong>Recommended automatic updaters:</strong>
<ul>
<li>Use <a href="%1$s" target="_blank">UniUploader</a><br />
%2$s</li>
</ul>
<strong>Updating instructions:</strong>
<ol>
<li>Download <a href="%3$s" target="_blank">Character Profiler</a></li>
<li>Extract zip into its own directory in C:\Program Files\World of Warcraft\Interface\Addons\</li>
<li>Start WoW</li>
<li>Open your bank, quests, and the profession windows which contain recipes</li>
<li>Log out/Exit WoW (See above if you want to use the UniUploader to upload the data automatically for you.)</li>
<li>Go to <a href="%4$s">the update page</a></li>
<li>%5$s</li>
</ol>';

$lang['update_instructpvp']='
<strong>Optional PvP Stats:</strong>
<ol>
<li>Download the <a href="%1$s" target="_blank">PvPLog</a></li>
<li>Extract the PvPLog dir into your Addon dir.</li>
<li>Duel or PvP</li>
<li>Upload PvPLog.lua</li>
</ol>';

$lang['roster_credits']='Props to <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, and <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> for the original code used for this site.<br />
WoWRoster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="%1$s">Additional Credits</a>';


$lang['timeformat'] = '%a %b %D, %l:%i %p'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$lang['phptimeformat'] = 'D M jS, g:ia';    // PHP date() Time format (example - 'D M jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


/**
 * Realmstatus Localizations
 */
$lang['rs'] = array(
	'ERROR' => 'Error',
	'NOSTATUS' => 'No Status',
	'UNKNOWN' => 'Unknown',
	'RPPVP' => 'RP-PvP',
	'PVE' => 'Normal',
	'PVP' => 'PvP',
	'RP' => 'RP',
	'OFFLINE' => 'Offline',
	'LOW' => 'Low',
	'MEDIUM' => 'Medium',
	'HIGH' => 'High',
	'MAX' => 'Max',
	'RECOMMENDED' => 'Recommended',
	'FULL' => 'Full'
);


//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$lang['guildless']='Guildless';
$lang['util']='Utilities';
$lang['char']='Character';
$lang['equipment']='Equipment';
$lang['upload']='Upload';
$lang['required']='Required';
$lang['optional']='Optional';
$lang['attack']='Attack';
$lang['defense']='Defense';
$lang['class']='Class';
$lang['race']='Race';
$lang['level']='Level';
$lang['lastzone']='Last Zone';
$lang['note']='Note';
$lang['officer_note']='Officer Note';
$lang['title']='Title';
$lang['name']='Name';
$lang['health']='Health';
$lang['mana']='Mana';
$lang['gold']='Gold';
$lang['armor']='Armor';
$lang['lastonline']='Last Online';
$lang['online']='Online';
$lang['lastupdate']='Last Updated';
$lang['currenthonor']='Current Honor Rank';
$lang['rank']='Rank';
$lang['sortby']='Sort by %';
$lang['total']='Total';
$lang['hearthed']='Hearthed';
$lang['recipes']='Recipes';
$lang['bags']='Bags';
$lang['character']='Character';
$lang['money']='Money';
$lang['bank']='Bank';
$lang['raid']='CT_Raid';
$lang['quests']='Quests';
$lang['roster']='Roster';
$lang['alternate']='Alternate';
$lang['byclass']='By Class';
$lang['menustats']='Stats';
$lang['menuhonor']='Honor';
$lang['basename']='Basename';
$lang['scope']='Scope';
$lang['tag']='Tag';
$lang['daily']='Daily';

//start search engine
$lang['search']='Search';
$lang['search_roster']='Search Roster';
$lang['search_onlyin']='Search Only These Addons';
$lang['search_advancedoptionsfor']='Advanced Options For';
$lang['search_results']='Search Results For';
$lang['search_results_from']='Here are your Search Results for';
$lang['search_nomatches']='Sorry there were No Matches for this Search';
$lang['search_didnotfind']='Didn\'t find what you were looking for? Try here!';
$lang['search_for']='Search Roster';
$lang['search_next_matches'] = 'Next matches for';
$lang['search_previous_matches'] = 'Previous matches for';
$lang['search_results_count'] = 'Results';
$lang['submited_author'] = 'Posted by:';
$lang['submited_date'] = 'On';
//end search engine
$lang['update']='Update';
$lang['credit']='Credits';
$lang['members']='Members';
$lang['items']='Items';
$lang['find']='Find item containing';
$lang['upprofile']='Update Profile';
$lang['backlink']='Back to the Roster';
$lang['gender']='Gender';
$lang['unusedtrainingpoints']='Unused Training Points';
$lang['unusedtalentpoints']='Unused Talent Points';
$lang['talentexport']='Export Talent Build';
$lang['questlog']='Quest Log';
$lang['recipelist']='Recipe List';
$lang['reagents']='Reagents';
$lang['item']='Item';
$lang['type']='Type';
$lang['date']='Date';
$lang['complete'] = 'Complete';
$lang['failed'] = 'Failed';
$lang['completedsteps'] = 'Completed Steps';
$lang['currentstep'] = 'Current Step';
$lang['uncompletedsteps'] = 'Uncompleted Steps';
$lang['key'] = 'Key';
$lang['timeplayed'] = 'Time Played';
$lang['timelevelplayed'] = 'Time Level Played';
$lang['Addon'] = 'Addons';
$lang['advancedstats'] = 'Advanced Stats';
$lang['crit'] = 'Crit';
$lang['dodge'] = 'Dodge';
$lang['parry'] = 'Parry';
$lang['block'] = 'Block';
$lang['realm'] = 'Realm';
$lang['region'] = 'Region';
$lang['server'] = 'Server';
$lang['faction'] = 'Faction';
$lang['page'] = 'Page';
$lang['general'] = 'General';
$lang['prev'] = 'Prev';
$lang['next'] = 'Next';
$lang['memberlog'] = 'Member Log';
$lang['removed'] = 'Removed';
$lang['added'] = 'Added';
$lang['add'] = 'Add';
$lang['delete'] = 'Delete';
$lang['updated'] = 'Updated';
$lang['no_info'] = 'No Information';
$lang['info'] = 'Info';
$lang['url'] = 'URL';
$lang['none']='None';
$lang['kills']='Kills';
$lang['allow'] = 'Allow';
$lang['disallow'] = 'Disallow';
$lang['locale'] = 'Locale';
$lang['language'] = 'Language';
$lang['default'] = 'Default';
$lang['proceed'] = 'Proceed';
$lang['submit'] = 'Submit';
$lang['strength']='Strength';
$lang['agility']='Agility';
$lang['stamina']='Stamina';
$lang['intellect']='Intellect';
$lang['spirit']='Spirit';

$lang['rosterdiag'] = 'RosterDiag';
$lang['difficulty'] = 'Difficulty';
$lang['recipe_4'] = 'optimal';
$lang['recipe_3'] = 'medium';
$lang['recipe_2'] = 'easy';
$lang['recipe_1'] = 'trivial';
$lang['roster_config'] = 'Roster Config';

$lang['search_names'] = 'Search Names';
$lang['search_items'] = 'Search Items';
$lang['search_tooltips'] = 'Search Tooltips';

// Talent Builds
$lang['talent_build_0'] = 'Active';
$lang['talent_build_1'] = 'Inactive';

// Char Scope
$lang['char_level_race_class'] = 'Level %1$s %2$s %3$s';
$lang['char_guildline'] = '%1$s of %2$s';

//this needs to be exact as it is the wording in the db
$lang['professions']='Professions';
$lang['secondary']='Secondary Skills';
$lang['Blacksmithing']='Blacksmithing';
$lang['Mining']='Mining';
$lang['Herbalism']='Herbalism';
$lang['Alchemy']='Alchemy';
$lang['Leatherworking']='Leatherworking';
$lang['Jewelcrafting']='Jewelcrafting';
$lang['Skinning']='Skinning';
$lang['Tailoring']='Tailoring';
$lang['Enchanting']='Enchanting';
$lang['Engineering']='Engineering';
$lang['Inscription']='Inscription';
$lang['Runeforging']='Runeforging';
$lang['Cooking']='Cooking';
$lang['Fishing']='Fishing';
$lang['First Aid']='First Aid';
$lang['Poisons']='Poisons';
$lang['backpack']='Backpack';
$lang['PvPRankNone']='none';

// Uses preg_match() to find required level in recipe tooltip
$lang['requires_level'] = '/Requires Level ([\d]+)/';

// Skills to EN id array
$lang['skill_to_id'] = array(
	'Class Skills' => 'classskills',
	'Professions' => 'professions',
	'Secondary Skills' => 'secondaryskills',
	'Weapon Skills' => 'weaponskills',
	'Armor Proficiencies' => 'armorproficiencies',
	'Languages' => 'languages',
);

//Tradeskill-Array
$lang['tsArray'] = array (
	$lang['Alchemy'],
	$lang['Herbalism'],
	$lang['Blacksmithing'],
	$lang['Mining'],
	$lang['Leatherworking'],
	$lang['Jewelcrafting'],
	$lang['Skinning'],
	$lang['Tailoring'],
	$lang['Enchanting'],
	$lang['Engineering'],
	$lang['Inscription'],
	$lang['Runeforging'],
	$lang['Cooking'],
	$lang['Fishing'],
	$lang['First Aid'],
	$lang['Poisons'],
);

//Tradeskill Icons-Array
$lang['ts_iconArray'] = array (
	$lang['Alchemy']=>'trade_alchemy',
	$lang['Herbalism']=>'trade_herbalism',
	$lang['Blacksmithing']=>'trade_blacksmithing',
	$lang['Mining']=>'trade_mining',
	$lang['Leatherworking']=>'trade_leatherworking',
	$lang['Jewelcrafting']=>'inv_misc_gem_02',
	$lang['Skinning']=>'inv_misc_pelt_wolf_01',
	$lang['Tailoring']=>'trade_tailoring',
	$lang['Enchanting']=>'trade_engraving',
	$lang['Engineering']=>'trade_engineering',
	$lang['Inscription']=>'inv_inscription_tradeskill01',
	$lang['Runeforging']=>'spell_deathknight_frozenruneweapon',
	$lang['Cooking']=>'inv_misc_food_15',
	$lang['Fishing']=>'trade_fishing',
	$lang['First Aid']=>'spell_holy_sealofsacrifice',
	$lang['Poisons']=>'ability_poisons'
);

// Riding Skill Icons-Array
$lang['riding'] = 'Riding';
$lang['ts_ridingIcon'] = array(
	'Night Elf'=>'ability_mount_whitetiger',
	'Human'=>'ability_mount_ridinghorse',
	'Dwarf'=>'ability_mount_mountainram',
	'Gnome'=>'ability_mount_mechastrider',
	'Undead'=>'ability_mount_undeadhorse',
	'Troll'=>'ability_mount_raptor',
	'Tauren'=>'ability_mount_kodo_03',
	'Orc'=>'ability_mount_blackdirewolf',
	'Blood Elf' => 'ability_mount_cockatricemount',
	'Draenei' => 'ability_mount_ridingelekk',
	'Paladin'=>'ability_mount_dreadsteed',
	'Warlock'=>'ability_mount_nightmarehorse',
	'Death Knight'=>'spell_deathknight_summondeathcharger'
// Space so locale files are line synced













);
$lang['ts_flyingIcon'] = array(
	'Horde'=>'ability_mount_wyvern_01',
	'Alliance'=>'ability_mount_gryphon_01',
	'Druid'=>'ability_druid_flightform',
	'Death Knight'=>'ability_mount_dreadsteed'
// Space so locale files are line synced


);

// Class Icons-Array
$lang['class_iconArray'] = array (
	'Death Knight'=>'deathknight_icon',
	'Druid'=>'druid_icon',
	'Hunter'=>'hunter_icon',
	'Mage'=>'mage_icon',
	'Paladin'=>'paladin_icon',
	'Priest'=>'priest_icon',
	'Rogue'=>'rogue_icon',
	'Shaman'=>'shaman_icon',
	'Warlock'=>'warlock_icon',
	'Warrior'=>'warrior_icon'
// Space so locale files are line synced










);

// Class Color-Array
$lang['class_colorArray'] = array(
	'Death Knight'=>'C41F3B',
	'Druid' => 'FF7D0A',
	'Hunter' => 'ABD473',
	'Mage' => '69CCF0',
	'Paladin' => 'F58CBA',
	'Priest' => 'FFFFFF',
	'Rogue' => 'FFF569',
	'Shaman' => '2459FF',
	'Warlock' => '9482C9',
	'Warrior' => 'C79C6E'
// Space so locale files are line synced










);

// Class To English Translation
$lang['class_to_en'] = array(
	'Death Knight'=>'Death Knight',
	'Druid' => 'Druid',
	'Hunter' => 'Hunter',
	'Mage' => 'Mage',
	'Paladin' => 'Paladin',
	'Priest' => 'Priest',
	'Rogue' => 'Rogue',
	'Shaman' => 'Shaman',
	'Warlock' => 'Warlock',
	'Warrior' => 'Warrior'
// Space so locale files are line synced










);

// Class to game-internal ID
$lang['class_to_id'] = array(
	'Warrior' => 1,
	'Paladin' => 2,
	'Hunter' => 3,
	'Rogue' => 4,
	'Priest' => 5,
	'Death Knight'=>6,
	'Shaman' => 7,
	'Mage' => 8,
	'Warlock' => 9,
	'Druid' => 11
// Space so locale files are line synced










);

// Game-internal ID to class
$lang['id_to_class'] = array(
	1 => 'Warrior',
	2 => 'Paladin',
	3 => 'Hunter',
	4 => 'Rogue',
	5 => 'Priest',
	6 => 'Death Knight',
	7 => 'Shaman',
	8 => 'Mage',
	9 => 'Warlock',
	11 => 'Druid'
);

// Race to English Translation
$lang['race_to_en'] = array(
	'Blood Elf' => 'Blood Elf',
	'Draenei'   => 'Draenei',
	'Night Elf' => 'Night Elf',
	'Dwarf'     => 'Dwarf',
	'Gnome'     => 'Gnome',
	'Human'     => 'Human',
	'Orc'       => 'Orc',
	'Undead'    => 'Undead',
	'Troll'     => 'Troll',
	'Tauren'    => 'Tauren',
// Space so locale files are line synced










);

$lang['race_to_id'] = array(
	'Human'     => 1,
	'Orc'       => 2,
	'Dwarf'     => 3,
	'Night Elf' => 4,
	'Undead'    => 5,
	'Tauren'    => 6,
	'Gnome'     => 7,
	'Troll'     => 8,
	'Blood Elf' => 10,
	'Draenei'   => 11,
// Space so locale files are line synced










);

$lang['id_to_race'] = array(
	1 => 'Human',
	2 => 'Orc',
	3 => 'Dwarf',
	4 => 'Night Elf',
	5 => 'Undead',
	6 => 'Tauren',
	7 => 'Gnome',
	8 => 'Troll',
	10 => 'Blood Elf',
	11 => 'Draenei',
);

$lang['hslist']=' Honor System Stats';
$lang['hslist1']='Highest Lifetime Rank';
$lang['hslist2']='Highest Lifetime HKs';
$lang['hslist3']='Most Honor Points';
$lang['hslist4']='Most Arena Points';

$lang['Death Knight']='Death Knight';
$lang['Druid']='Druid';
$lang['Hunter']='Hunter';
$lang['Mage']='Mage';
$lang['Paladin']='Paladin';
$lang['Priest']='Priest';
$lang['Rogue']='Rogue';
$lang['Shaman']='Shaman';
$lang['Warlock']='Warlock';
$lang['Warrior']='Warrior';

$lang['today']='Today';
$lang['todayhk']='Today HK';
$lang['todaycp']='Today CP';
$lang['yesterday']='Yesterday';
$lang['yesthk']='Yest HK';
$lang['yestcp']='Yest CP';
$lang['thisweek']='This Week';
$lang['lastweek']='Last Week';
$lang['lifetime']='Lifetime';
$lang['lifehk']='Life HK';
$lang['honorkills']='Honorable Kills';
$lang['dishonorkills']='Dishonorable Kills';
$lang['honor']='Honor';
$lang['standing']='Standing';
$lang['highestrank']='Highest Rank';
$lang['arena']='Arena';

$lang['when']='When';
$lang['guild']='Guild';
$lang['result']='Result';
$lang['zone']='Zone';
$lang['subzone']='Subzone';
$lang['yes']='Yes';
$lang['no']='No';
$lang['win']='Win';
$lang['loss']='Loss';
$lang['unknown']='Unknown';

//strings for Rep-tab
$lang['exalted']='Exalted';
$lang['revered']='Revered';
$lang['honored']='Honored';
$lang['friendly']='Friendly';
$lang['neutral']='Neutral';
$lang['unfriendly']='Unfriendly';
$lang['hostile']='Hostile';
$lang['hated']='Hated';
$lang['atwar']='At War';
$lang['notatwar']='Not at War';

// Factions to EN id
$lang['faction_to_id'] = array(
	'Alliance' => 'alliance',
	'Alliance Forces' => 'allianceforces',
	'Alliance Vanguard' => 'alliancevanguard',
	'Classic' => 'classic',
	'Other' => 'other',
	'Outland' => 'outland',
	'Shattrath City' => 'shattrathcity',
	'Steamwheedle Cartel' => 'steamwheedlecartel',
	'The Burning Crusade' => 'theburningcrusade',
	'Wrath of the Lich King' => 'wrathofthelitchking',
	'Sholazar Basin' => 'sholazarbasin',
);


// Quests page external links (on character quests page)
// $lang['questlinks'][][] = array(
// 		'name'=> 'Name',  // This is the name displayed on the quests page
// 		'url' => 'url',   // This is the URL used for the quest lookup (must be sprintf() compatible)

$lang['questlinks'][] = array(
	'name'=>'WoWHead',
	'url'=>'http://www.wowhead.com/?quest=%1$s'
);

$lang['questlinks'][] = array(
	'name'=>'Thottbot',
	'url'=>'http://thottbot.com/q%1$s'
);

$lang['questlinks'][] = array(
	'name'=>'Allakhazam',
	'url'=>'http://wow.allakhazam.com/db/quest.html?source=live;wquest=%1$s'
);

$lang['questlinks'][] = array(
	'name'=>'WoW Digger',
	'url'=>'http://wowdigger.com/quest/view/%1$s'
);

// Items external link
// Add as many item links as you need
// Just make sure their names are unique
// uses the 'item_id' for data
$lang['itemlink'] = 'Item Links';
$lang['itemlinks']['WoWHead'] = 'http://www.wowhead.com/?item=';
$lang['itemlinks']['Thottbot'] = 'http://www.thottbot.com/i';
$lang['itemlinks']['Allakhazam'] = 'http://wow.allakhazam.com/db/item.html?witem=';
$lang['itemlinks']['WoW Digger'] = 'http://www.wowdigger.com/item/view/';

// WoW Data Site Search
// Add as many item links as you need
// Just make sure their names are unique
// use these locales for data searches
$lang['data_search'] = 'WoW Data Site Search';
$lang['data_links']['WoWHead'] = 'http://www.wowhead.com/?search=';
$lang['data_links']['Thottbot'] = 'http://www.thottbot.com/index.cgi?s=';
$lang['data_links']['Allakhazam'] = 'http://wow.allakhazam.com/search.html?q=';
$lang['data_links']['WoW Digger'] = 'http://wowdigger.com/?c=search&amp;keywords=';

// Google Search
// Add as many item links as you need
// Just make sure their names are unique
// use these locales for data searches
$lang['google_search'] = 'Google';
$lang['google_links']['Google'] = 'http://www.google.com/search?q=';
$lang['google_links']['Google Groups'] = 'http://groups.google.com/groups?q=';
$lang['google_links']['Google Images'] = 'http://images.google.com/images?q=';
$lang['google_links']['Google News'] = 'http://news.google.com/news?q=';

// Definition for item tooltip coloring
$lang['tooltip_use']='Use:';
$lang['tooltip_requires']='Requires';
$lang['tooltip_reinforced']='Reinforced';
$lang['tooltip_soulbound']='Soulbound';
$lang['tooltip_accountbound']='Account Bound';
$lang['tooltip_boe']='Binds when equipped';
$lang['tooltip_equip']='Equip:';
$lang['tooltip_equip_restores']='Equip: Restores';
$lang['tooltip_equip_when']='Equip: When';
$lang['tooltip_chance']='Chance';
$lang['tooltip_enchant']='Enchant';
$lang['tooltip_random_enchant']='Random enchantment';
$lang['tooltip_set']='Set.';
$lang['tooltip_rank']='Rank';
$lang['tooltip_next_rank']='Next rank';
$lang['tooltip_spell_damage']='Spell Damage';
$lang['tooltip_school_damage']='\\+.*Spell Damage';
$lang['tooltip_healing_power']='Healing Power';
$lang['tooltip_reinforced_armor']='Reinforced Armor';
$lang['tooltip_damage_reduction']='Damage Reduction';
//--new
$lang['tooltip_durability']='Durability';
$lang['tooltip_unique']='Unique';
$lang['tooltip_speed']='Speed';
$lang['tooltip_poisoneffect']='^Each strike has';

$lang['tooltip_preg_armor']='/^(\d+) Armor/';
$lang['tooltip_preg_durability']='/Durability(|:) (\d+) \/ (\d+)/';
$lang['tooltip_preg_madeby']='/\<Made by (.+)\>/';
$lang['tooltip_preg_bags']='/^(\d+) Slot/';
$lang['tooltip_preg_socketbonus']='/Socket Bonus: (.+)/';
$lang['tooltip_preg_classes']='/^(Classes:) (.+)/';
$lang['tooltip_preg_races']='/^(Races:) (.+)/';
$lang['tooltip_preg_charges']='/(\d+) Charges?/';
$lang['tooltip_preg_block']='/(\d+) (Block)/';
$lang['tooltip_preg_emptysocket']='/^(Red|Yellow|Blue|Meta) Socket$/';
$lang['tooltip_preg_reinforcedarmor']='/(Reinforced\s\(\+\d+\sArmor\))/';
$lang['tooltip_preg_tempenchants']='/(.+\s\(\d+\s(min|sec)\))\n/i';
$lang['tooltip_preg_meta_requires']='/Requires.*?gem?/';
$lang['tooltip_preg_meta_requires_min']='/Requires at least (\d) (\S+) gem?/';
$lang['tooltip_preg_meta_requires_more']='/Requires more (\S+) gems than (\S+) gems/';
$lang['tooltip_preg_item_level']='/Item Level (\d+)/';
$lang['tooltip_feral_ap']='Increases attack power by';
$lang['tooltip_source']='Source';
$lang['tooltip_boss']='Boss';
$lang['tooltip_droprate']='Drop Rate';

$lang['tooltip_chance_hit']='Chance to|on hit:';
$lang['tooltip_reg_requires']='Requires';
$lang['tooltip_reg_onlyworksinside']='Only works inside';
$lang['tooltip_reg_conjureditems']='Conjured Item';
$lang['tooltip_reg_weaponorbulletdps']='^\(|^Adds ';

$lang['tooltip_armor_types']='Cloth|Leather|Mail|Plate';
$lang['tooltip_weapon_types']='Arrow|Axe|Bow|Bullet|Crossbow|Dagger|Fishing Pole|Fist Weapon|Gun|Idol|Mace|Main Hand|Off-hand|Polearm|Staff|Sword|Thrown|Wand|Ranged|One-Hand|Two-Hand|Relic';
$lang['tooltip_bind_types']='Soulbound|Binds when equipped|Quest Item|Binds when used|Binds when picked up|This Item Begins a Quest|Binds to Account|Account Bound';
$lang['tooltip_misc_types']='Finger|Neck|Back|Shirt|Trinket|Tabard|Head|Chest|Legs|Feet';
$lang['tooltip_garbage']='<Shift Right Click to Socket>|<Right Click to Read>|Duration|Cooldown remaining|<Right Click to Open>';

//CP v2.1.1+ Gems info
//uses preg_match() to find the type and color of the gem
$lang['gem_preg_singlecolor'] = '/Matches a (\w+) Socket/';
$lang['gem_preg_multicolor'] = '/Matches a (\w+) or (\w+) Socket/';
$lang['gem_preg_meta'] = '/Only fits in a meta gem slot/';
$lang['gem_preg_prismatic'] = '/Matches a Red, Yellow or Blue Socket/';

//Gems color Array
$lang['gem_colors'] = array(
	'red' => 'Red',
	'blue' => 'Blue',
	'yellow' => 'Yellow',
	'green' => 'Green',
	'orange' => 'Orange',
	'purple' => 'Purple',
	'prismatic' => 'Prismatic',
	'meta' => 'Meta'
	);

$lang['gem_colors_to_en'] = array(
	'red' => 'red',
	'blue' => 'blue',
	'yellow' => 'yellow',
	'green' => 'green',
	'orange' => 'orange',
	'purple' => 'purple',
	'prismatic' => 'prismatic',
	'meta' => 'meta' //verify translation
	);

$lang['socket_colors_to_en'] = array(
	'red' => 'red',
	'blue' => 'blue',
	'yellow' => 'yellow',
	'meta' => 'meta',
	);
// -- end tooltip parsing



// Warlock pet names for icon displaying
$lang['Imp']='Imp';
$lang['Voidwalker']='Voidwalker';
$lang['Succubus']='Succubus';
$lang['Felhunter']='Felhunter';
$lang['Infernal']='Infernal';
$lang['Felguard']='Felguard';

// Max experiance for exp bar on char page
$lang['max_exp']='Max XP';

// Error messages
$lang['CPver_err']='The version of CharacterProfiler used to capture data for this character is older than the minimum version allowed for upload.<br />Please ensure you are running at least v%1$s and have logged onto this character and saved data using this version.';
$lang['GPver_err']='The version of GuildProfiler used to capture data for this guild is older than the minimum version allowed for upload.<br />Please ensure you are running at least v%1$s';

// Menu titles
$lang['menu_upprofile']='Update Profile|Update your profile on this site';
$lang['menu_search']='Search|Search items and recipes in the database';
$lang['menu_roster_cp']='RosterCP|Roster Configuration Panel';
$lang['menu_credits']='Credits|Who made WoW Roster';
$lang['menupanel_util']  = 'Utilities';
$lang['menupanel_realm'] = 'Realm';
$lang['menupanel_guild'] = 'Guild';
$lang['menupanel_char']  = 'Character';

$lang['menuconf_sectionselect']='Select Panel';
$lang['menuconf_changes_saved']='Changes to %1$s saved';
$lang['menuconf_no_changes_saved']='No changes saved';
$lang['menuconf_add_button']='Add button';
$lang['menuconf_drag_delete']='Drag here to delete';
$lang['menuconf_addon_inactive']='Addon is inactive';
$lang['menuconf_unused_buttons']='Unused Buttons';

$lang['installer_install_0']='Installation of %1$s successful';
$lang['installer_install_1']='Installation of %1$s failed, but rollback successful';
$lang['installer_install_2']='Installation of %1$s failed, and rollback also failed';
$lang['installer_uninstall_0']='Uninstallation of %1$s successful';
$lang['installer_uninstall_1']='Uninstallation of %1$s failed, but rollback successful';
$lang['installer_uninstall_2']='Uninstallation of %1$s failed, and rollback also failed';
$lang['installer_upgrade_0']='Upgrade of %1$s successful';
$lang['installer_upgrade_1']='Upgrade of %1$s failed, but rollback successful';
$lang['installer_upgrade_2']='Upgrade of %1$s failed, and rollback also failed';
$lang['installer_purge_0']='Purge of %1$s successful';

$lang['installer_icon'] = 'Icon';
$lang['installer_addoninfo'] = 'Addon Info';
$lang['installer_status'] = 'Status';
$lang['installer_installation'] = 'Installation';
$lang['installer_author'] = 'Author';
$lang['installer_log'] = 'Addon Manager Log';
$lang['installer_activated'] = 'Activated';
$lang['installer_deactivated'] = 'Deactivated';
$lang['installer_installed'] = 'Installed';
$lang['installer_upgrade_avail'] = 'Upgrade Available';
$lang['installer_not_installed'] = 'Not Installed';

$lang['installer_turn_off'] = 'Click to Deactivate';
$lang['installer_turn_on'] = 'Click to Activate';
$lang['installer_click_uninstall'] = 'Click to Uninstall';
$lang['installer_click_upgrade'] = 'Click to Upgrade %1$s to %2$s';
$lang['installer_click_install'] = 'Click to Install';
$lang['installer_overwrite'] = 'Old Version Overwrite';
$lang['installer_replace_files'] = 'You have overwrote your current addon installation with an older version<br />Replace files with latest version<br /><br />Or Click to Purge AddOn';

$lang['installer_error'] = 'Install Errors';
$lang['installer_invalid_type'] = 'Invalid install type';
$lang['installer_no_success_sql'] = 'Queries were not successfully added to the installer';
$lang['installer_no_class'] = 'The install definition file for %1$s did not contain a correct installation class';
$lang['installer_no_installdef'] = 'inc/install.def.php for %1$s was not found';

$lang['installer_no_empty'] = 'Cannot install with an empty addon name';
$lang['installer_fetch_failed'] = 'Failed to fetch addon data for %1$s';
$lang['installer_addon_exist'] = '%1$s already contains %2$s. You can go back and uninstall that addon first, or upgrade it, or install this addon with a different name';
$lang['installer_no_upgrade'] = '%1$s doesn\`t contain data to upgrade from';
$lang['installer_not_upgradable'] = '%1$s cannot upgrade %2$s since its basename %3$s isn\'t in the list of upgradable addons';
$lang['installer_no_uninstall'] = '%1$s doesn\'t contain an addon to uninstall';
$lang['installer_not_uninstallable'] = '%1$s contains an addon %2$s which must be uninstalled with that addons\' uninstaller';

// After Install guide
$lang['install'] = 'Install';
$lang['setup_guide'] = 'After Install Guide';
$lang['default_data'] = 'Default Data';
$lang['default_data_help'] = 'Set your default allowed guild here<br />A default guild is needed for many addons to function properly<br />You can add more allowed guilds in RosterCP-&gt;Upload Rules<br /><br />If this is a non-guilded Roster install:<br />Enter Guildless-F for guild name<br />Replace F with your Faction (A-Alliance, H-Horde)<br />Enter your realm and region<br />Set Upload Rules for characters in RosterCP-&gt;Upload Rules';
$lang['guide_complete'] = 'The after install setup is complete<br /><br />Remember to <a href="%1$s">install addons</a> for Roster';
$lang['guide_already_complete'] = 'The after install guide setup has already been completed<br />You cannot run it again';

// Password Stuff
$lang['password'] = 'Password';
$lang['changeadminpass'] = 'Change Admin Password';
$lang['changeofficerpass'] = 'Change Officer Password';
$lang['changeguildpass'] = 'Change Guild Password';
$lang['old_pass'] = 'Old Password';
$lang['new_pass'] = 'New Password';
$lang['new_pass_confirm'] = 'New Password [ confirm ]';
$lang['pass_old_error'] = 'Wrong password. Please enter the correct old password';
$lang['pass_submit_error'] = 'Submit error. The old password, the new password, and the confirmed new password need to be submitted';
$lang['pass_mismatch'] = 'Passwords do not match. Please type the exact same password in both new password fields';
$lang['pass_blank'] = 'No blank passwords. Please enter a password in both fields. Blank passwords are not allowed';
$lang['pass_isold'] = 'Password not changed. The new password was the same as the old one';
$lang['pass_changed'] = '&quot;%1$s&quot; password changed. Your new password is [ %2$s ].<br /> Do not forget this password, it is stored encrypted only';
$lang['auth_req'] = 'Authorization Required';

// Upload Rules
$lang['upload_rules_error'] = 'You cannot leave one of the fields empty when adding a rule';
$lang['upload_rules_help'] = 'The rules are divided into two blocks.<br />For each uploaded guild/char, first the top block is checked. If the name@server matches one of these \'deny\' rules, it is rejected.<br />After that the second block is checked. If the name@server matches one of these \'accept\' rules, it is accepted.<br />If it does not match any rule, it is rejected.<br /><br />Remember to set a default guild here as well.';

// Data Manager
$lang['clean'] = 'Clean up entries based on rules';
$lang['select_guild'] = 'Select Guild';
$lang['delete_checked'] = 'Delete Checked';
$lang['delete_guild'] = 'Delete Guild';
$lang['delete_guild_confirm'] = 'This will remove this entire guild and all set all members guildless.\\n Are you sure you want to do this?\\n\\nNOTE: This cannot be un-done!';

// Config Reset
$lang['config_is_reset'] = 'Configuration has been reset. Please remember to re-configure ALL your settings before attempting to upload data';
$lang['config_reset_confirm'] = 'This is irreversible. Do you really want to continue?';
$lang['config_reset_help'] = 'This will completely reset your Roster configuration.<br />
All data in the Roster configuration table will be permanently removed, and the default values will be restored.<br />
Guild data, Character data, Addon config, Addon data, menu buttons, and upload rules will be saved.<br />
The guild, officer, and admin passwords will also be saved.<br />
<br />
To continue, enter your admin password below and click on \'Proceed\'.';

/******************************
 * Roster Admin Strings
 ******************************/

$lang['pagebar_function'] = 'Function';
$lang['pagebar_rosterconf'] = 'Configure Main Roster';
$lang['pagebar_uploadrules'] = 'Upload Rules';
$lang['pagebar_dataman'] = 'Data Manager';
$lang['pagebar_talentman'] = 'Armory Data';
$lang['pagebar_changepass'] = 'Change Password';
$lang['pagebar_addoninst'] = 'Addon Management';
$lang['pagebar_update'] = 'Upload Profile';
$lang['pagebar_rosterdiag'] = 'Roster Diag';
$lang['pagebar_menuconf'] = 'Menu Configuration';
$lang['pagebar_configreset'] = 'Config Reset';

$lang['pagebar_addonconf'] = 'Addon Config';

$lang['roster_config_menu'] = 'Config Menu';

// Submit/Reset confirm questions
$lang['config_submit_button'] = 'Save Settings';
$lang['config_reset_button'] = 'Reset';
$lang['confirm_config_submit'] = 'This will save the changes to the database. Are you sure?';
$lang['confirm_config_reset'] = 'This will reset the form to how it was when you loaded it. Are you sure?';

// All strings here
// Each variable must be the same name as the config variable name
// Example:
//   Assign description text and tooltip for $roster->config['sqldebug']
//   $lang['admin']['sqldebug'] = "Desc|Tooltip";

// Each string is separated by a pipe ( | )
// The first part is the short description, the next part is the tooltip
// Use <br /> to make new lines!
// Example:
//   "Controls Flux-Capacitor|Turning this on may cause serious temporal distortions<br />Use with care"


// Main Menu words
$lang['admin']['main_conf'] = 'Main Settings|Roster\'s main settings<br />Including roster URL, Interface Images URL, and other core options';
$lang['admin']['defaults_conf'] = 'Defaults Config|Set up your Roster defaults';
$lang['admin']['index_conf'] = 'Index Page|Options for what shows on the Main Page';
$lang['admin']['menu_conf'] = 'Menu|Control what is displayed in the Roster Main Menu';
$lang['admin']['display_conf'] = 'Display Config|Misc display settings<br />css, javascript, motd, etc...';
$lang['admin']['realmstatus_conf'] = 'Realmstatus|Options for Realmstatus<br /><br />To turn this off, look in the Menu section';
$lang['admin']['data_links'] = 'Data Links|External links';
$lang['admin']['update_access'] = 'Update Access|Set access levels for rostercp components';

$lang['admin']['documentation'] = 'Documentation|WoWRoster Documentation via the wowroster.net wiki';

// main_conf
$lang['admin']['roster_dbver'] = "Roster Database Version|The version of the database";
$lang['admin']['version'] = "Roster Version|Current version of Roster";
$lang['admin']['debug_mode'] = "Debug Mode|off - No debug or error messages<br />on - Display error messages and simple debug<br />extended - Full debug mode and backtrace in error messages";
$lang['admin']['sql_window'] = "SQL Window|off - Do not show query window<br />on - Display query window in the footer<br />extended - Include DESCRIBE statements";
$lang['admin']['minCPver'] = "Min CP Version|Minimum CharacterProfiler version allowed to upload";
$lang['admin']['minGPver'] = "Min GP version|Minimum GuildProfiler version allowed to upload";
$lang['admin']['locale'] = "Roster Main Language|The main language roster will be displayed in";
$lang['admin']['default_page'] = "Default Page|Page to display if no page is specified in the url";
$lang['admin']['external_auth'] = "Roster Auth|Here you can choose the auth file Roster will use<br />&quot;Roster&quot; is the default, built-in auth system";
$lang['admin']['website_address'] = "Website Address|Used for url link for logo, and guildname link in the main menu<br />Some roster addons may also use this";
$lang['admin']['interface_url'] = "Interface Directory URL|Directory that the Interface images are located<br />Default is &quot;img/&quot;<br /><br />You can use a relative path or a full URL";
$lang['admin']['img_suffix'] = "Interface Image Extension|The image type of the Interface images";
$lang['admin']['alt_img_suffix'] = "Alt Interface Image Extension|The alternate possible image type of the Interface images";
$lang['admin']['img_url'] = "Roster Images Directory URL|Directory that Roster's images are located<br />Default is &quot;img/&quot;<br /><br />You can use a relative path or a full URL";
$lang['admin']['timezone'] = "Timezone|Displayed after timestamps so people know what timezone the time references are in";
$lang['admin']['localtimeoffset'] = "Time Offset|Your timezone offset from UTC/GMT<br />Times on roster will be displayed as a calculated value using this offset";
$lang['admin']['use_update_triggers'] = "Addon Update Triggers|Addon Update Triggers are for addons that need to run during a character or guild update<br />Some addons my require that this is turned on for them to function properly";
$lang['admin']['check_updates'] = "Check for Updates|This allows your copy of WoWRoster (and addons that use this feature) to<br />check if you have the newest version of the software";
$lang['admin']['seo_url'] = "Friendly URLs|Enable SEO like URL links in Roster<br /><br />on - /some/page/here/param=value.html<br />off - index.php?p=some-page-here&amp;param=value";
$lang['admin']['local_cache']= "File System Cache|Use server local file system to cache some files to increase performance.";
$lang['admin']['use_temp_tables'] = "Use Temporary Tables|Turn this setting off if your host does not allow you to create temporary database tables (CREATE TEMPORARY TABLE privilege).<br />Leaving this on is recommended for performance.";
$lang['admin']['enforce_rules'] = "Enforce Upload Rules|This setting will enforce the upload rules on every lua update<br />- Never: Never enforce rules<br />- All LUA Updates: Enforce rules on all lua updates<br />- CP Updates: Enforce rules on any CP.lua update<br />- Guild Updates: Enforce rules only on guild updates<br /><br />You can also toggle this setting on the &quot;Upload Rules&quot; page.";

// defaults_conf
$lang['admin']['default_name'] = "WoWRoster Name|Enter a name to be displayed when not in the guild or char scope";
$lang['admin']['default_desc'] = "Description|Enter a short description to be displayed when not in the guild or char scope";
$lang['admin']['alt_type'] = "Alt-Text Search|Text used to designate alts for display count in the main menu<br /><br /><span class=\"red\">The Memebers List AddOn DOES NOT use this for alt grouping</span>";
$lang['admin']['alt_location'] = "Alt-Search Field|Search location, what field to search for Alt-Text<br /><br /><span class=\"red\">The Memebers List AddOn DOES NOT use this for alt grouping</span>";

// menu_conf
$lang['admin']['menu_conf_left'] = "Left pane|";
$lang['admin']['menu_conf_right'] = "Right pane|";

$lang['admin']['menu_top_pane'] = "Top Pane|Controls display of the top pane of the main roster menu<br />This area holds the guild name, server, last update, etc...";
$lang['admin']['menu_top_faction'] = "Faction Icon|Controls display of the faction icon in the top pane of the main roster menu";
$lang['admin']['menu_top_locale'] = "Locale Selection|Controls display of the locale selection in the top pane of the main roster menu";

$lang['admin']['menu_left_type'] = "Display type|Decide whether to show a level overview, a class overview, the realm status, or nothing at all";
$lang['admin']['menu_left_level'] = "Minimum level|Minimum level for characters to be included in the level/class overview";
$lang['admin']['menu_left_style'] = "Display style|Show as a list, a linear bargraph, or a logarithmic bargraph";
$lang['admin']['menu_left_barcolor'] = "Bar color|The color for the bar showing the number of characters of this level group/class";
$lang['admin']['menu_left_bar2color'] = "Bar 2 color|The color for the bar showing the number of alts of this level group/class";
$lang['admin']['menu_left_textcolor'] = "Text color|The color for the level/class group labels (class graph uses class colors for labels)";
$lang['admin']['menu_left_outlinecolor'] = "Text Outline color|The outline color for the level/class group labels<br />Clear this box to turn the outline off";
$lang['admin']['menu_left_text'] = "Text Font|The font for the level/class group labels";

$lang['admin']['menu_right_type'] = "Display type|Decide whether to show a level overview, a class overview, the realm status, or nothing at all";
$lang['admin']['menu_right_level'] = "Minimum level|Minimum level for characters to be included in the level/class overview";
$lang['admin']['menu_right_style'] = "Display style|Show as a list, a linear bargraph, or a logarithmic bargraph";
$lang['admin']['menu_right_barcolor'] = "Bar color|The color for the bar showing the number of characters of this level group/class";
$lang['admin']['menu_right_bar2color'] = "Bar 2 color|The color for the bar showing the number of alts of this level group/class";
$lang['admin']['menu_right_textcolor'] = "Text color|The color for the level/class group labels (class graph uses class colors for labels)";
$lang['admin']['menu_right_outlinecolor'] = "Text Outline color|The outline color for the level/class group labels<br />Clear this box to turn the outline off";
$lang['admin']['menu_right_text'] = "Text font|The font for the level/class group labels";

$lang['admin']['menu_bottom_pane'] = "Bottom Pane|Controls display of the bottom pane of the main roster menu<br />This area holds the search box";

// display_conf
$lang['admin']['theme'] = "Roster Theme|Choose the overall look of Roster<br /><span style=\"color:red;\">NOTE:</span> Not all of Roster is currently templated<br />and using themes other than the default may have unexpected results";
$lang['admin']['logo'] = "URL for header logo|The full URL to the image<br />Or by apending &quot;img/&quot; to the name, it will look in the roster's img/ directory";
$lang['admin']['roster_bg'] = "URL for background image|The full URL to the image used for the main background<br />Or by apending &quot;img/&quot; to the name, it will look in the roster's img/ directory";
$lang['admin']['motd_display_mode'] = "MOTD Display Mode|How the MOTD will be displayed<br /><br />&quot;Text&quot; - Shows MOTD in red text<br />&quot;Image&quot; - Shows MOTD as an image (REQUIRES GD!)";
$lang['admin']['signaturebackground'] = "img.php Background|Support for legacy signature creator";
$lang['admin']['processtime'] = "Process time|Displays render time and query count in the footer<br />&quot;<i>x.xx | xx</i>&quot;";

// data_links
$lang['admin']['profiler'] = "CharacterProfiler download link|URL to download CharacterProfiler";
$lang['admin']['uploadapp'] = "UniUploader download link|URL to download UniUploader";

// realmstatus_conf
$lang['admin']['rs_display'] = "Info Mode|&quot;full&quot; will show status and server name, population, and type<br />&quot;half&quot; will display just the status";
$lang['admin']['rs_mode'] = "Display Mode|How Realmstatus will be displayed<br /><br />&quot;DIV Container&quot; - Shows Realmstatus in a DIV container with text and standard images<br />&quot;Image&quot; - Shows Realmstatus as an image (REQUIRES GD!)";
$lang['admin']['rs_timer'] = "Refresh Timer|Set the timeout period for fetching new realmstatus data";
$lang['admin']['rs_left'] = "Display|";
$lang['admin']['rs_middle'] = "Type Display Settings|";
$lang['admin']['rs_right'] = "Population Display Settings|";
$lang['admin']['rs_font_server'] = "Realm Font|Font for the realm name<br />(Image mode only!)";
$lang['admin']['rs_size_server'] = "Realm Font Size|Font size for the realm name<br />(Image mode only!)";
$lang['admin']['rs_color_server'] = "Realm Color|Color of realm name";
$lang['admin']['rs_color_shadow'] = "Shadow Color|Color for text drop shadows<br />(Image mode only!)";
$lang['admin']['rs_font_type'] = "Type Font|Font for the realm type<br />(Image mode only!)";
$lang['admin']['rs_size_type'] = "Type Font Size|Font size for the realm type<br />(Image mode only!)";
$lang['admin']['rs_color_rppvp'] = "RP-PvP Color|Color for RP-PvP";
$lang['admin']['rs_color_pve'] = "Normal Color|Color for Normal";
$lang['admin']['rs_color_pvp'] = "PvP Color|Color for PvP";
$lang['admin']['rs_color_rp'] = "RP Color|Color for RP";
$lang['admin']['rs_color_unknown'] = "Unknown Color|Color for unknown";
$lang['admin']['rs_font_pop'] = "Pop Font|Font for the realm population<br />(Image mode only!)";
$lang['admin']['rs_size_pop'] = "Pop Font Size|Font size for the realm population<br />(Image mode only!)";
$lang['admin']['rs_color_low'] = "Low Color|Color for low population";
$lang['admin']['rs_color_medium'] = "Medium Color|Color for medium population";
$lang['admin']['rs_color_high'] = "High Color|Color for high population";
$lang['admin']['rs_color_max'] = "Max Color|Color for max population";
$lang['admin']['rs_color_error'] = "Error Color|Color for realm error";
$lang['admin']['rs_color_offline'] = "Offline Color|Color for realm offline";
$lang['admin']['rs_color_full'] = "Full Color|Color for full realms (EU realms only)";
$lang['admin']['rs_color_recommended'] = "Recommended Color|Color for recommended realms (EU realms only)";

// update_access
$lang['admin']['authenticated_user'] = "Access to Update.php|Controls access to update.php<br /><br />Turning this off disables access for EVERYONE";
$lang['admin']['update_inst'] = 'Update Instructions|Controls the display of the Update Instructions on the update page';
$lang['admin']['gp_user_level'] = "Guild Data Level|Level required to process GuildProfiler Data";
$lang['admin']['cp_user_level'] = "Character Data Level|Level required to process CharacterProfiler Data";
$lang['admin']['lua_user_level'] = "Other LUA Data Level|Level required to process other LUA files' Data<br />This is for EVERY other lua file that can be uploaded to Roster";

// Character Display Settings
$lang['admin']['per_character_display'] = 'Per-Character Display';

//Overlib for Allow/Disallow rules
$lang['guildname'] = 'Guild name';
$lang['realmname']  = 'Realm name';
$lang['regionname'] = 'Region (i.e. US)';
$lang['charname'] = 'Character name';
