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
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.5.0
*/


//Instructions how to upload, as seen on the mainpage
$lang['update_link']='Click here for Updating Instructions';
$lang['update_instructions']='Updating Instructions';

$lang['lualocation']='Click browse and select your *.lua files to upload';

$lang['filelocation']='is located at<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$lang['noGuild']='Could not find guild in database. Please update members first.';
$lang['nodata']='Could not find guild: <b>\'%1$s\'</b> for server <b>\'%2$s\'</b><br />You need to <a href="%3$s">load your guild</a> first and make sure you <a href="%4$s">finished configuration</a><br /><br /><a href="http://www.wowroster.net/wiki/index.php/Roster:Install" target="_blank">Click here for installation instructions</a>';
$lang['nodata_title']='No Guild Data';

$lang['update_page']='Update Profile';

$lang['guild_nameNotFound']='Could not update &quot;%s&quot;. Maybe its not set in configuration?';
$lang['guild_addonNotFound']='Could not find Guild. GuildProfiler Addon not installed correctly?';

$lang['ignored']='Ignored';
$lang['update_disabled']='Update.php access has been disabled';

$lang['nofileUploaded']='UniUploader did not upload any file(s), or uploaded the wrong file(s).';
$lang['roster_upd_pwLabel']='Roster Update Password';
$lang['roster_upd_pw_help']='(This is required when doing a guild update)';


$lang['roster_error'] = 'Roster Error';
$lang['sql_queries'] = 'SQL Queries';
$lang['invalid_char_module'] = 'Invalid characters in module name';
$lang['module_not_exist'] = 'The module [%1$s] does not exist';

$lang['addon_error'] = 'Addon Error';
$lang['specify_addon'] = 'You must specify an addon name!';
$lang['addon_not_exist'] = '<b>The addon [%1$s] does not exist!</b>';
$lang['addon_disabled'] = '<b>The addon [%1$s] has been disabled</b>';
$lang['addon_not_installed'] = '<b>The addon [%1$s] has not been installed yet</b>';
$lang['addon_no_config'] = '<b>The addon [%1$s] does not have a config</b>';

$lang['char_error'] = 'Character Error';
$lang['specify_char'] = 'Character was not specified';
$lang['no_char_id'] = 'Sorry no character data for member_id [ %1$s ]';
$lang['no_char_name'] = 'Sorry no character data for <strong>%1$s</strong> of <strong>%2$s</strong>';

$lang['roster_cp'] = 'Roster Control Panel';
$lang['roster_cp_ab'] = 'Roster CP';
$lang['roster_cp_not_exist'] = 'Page [%1$s] does not exist';
$lang['roster_cp_invalid'] = 'Invalid page specified or insufficient credentials to access this page';

$lang['parsing_files'] = 'Parsing files';
$lang['parsed_time'] = 'Parsed %1$s in %2$s seconds';
$lang['error_parsed_time'] = 'Error while parsing %1$s after %2$s seconds';
$lang['upload_not_accept'] = 'Did not accept %1$s';
$lang['not_updating'] = 'NOT Updating %1$s for [%2$s] - %3$s';
$lang['not_update_guild'] = 'NOT Updating Guild List for %1$s';
$lang['not_update_guild_time'] = 'NOT Updating Guild List for %1$s. Guild profile is too old';
$lang['no_members'] = 'Data does not contain any guild members';
$lang['upload_data'] = 'Updating %1$s Data for [<span class="orange">%2$s</span>]';
$lang['realm_ignored'] = 'Realm: %1$s Not Scanned';
$lang['guild_realm_ignored'] = 'Guild: %1$s @ Realm: %2$s  Not Scanned';
$lang['update_members'] = 'Updating Members';
$lang['gp_user_only'] = 'GuildProfiler User Only';
$lang['update_errors'] = 'Update Errors';
$lang['update_log'] = 'Update Log';
$lang['save_error_log'] = 'Save Error Log';
$lang['save_update_log'] = 'Save Update Log';

$lang['new_version_available'] = 'There is a new version of %1$s available <span class="green">v%2$s</span><br />Get it <a href="%3$s" target="_blank">HERE</a>';


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


//Charset
$lang['charset']="charset=utf-8";

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
);


//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
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
$lang['search']='Search';
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
$lang['online_at_up'] = 'Online at Update';
$lang['faction'] = 'Faction';
$lang['page'] = 'Page';
$lang['general'] = 'General';
$lang['prev'] = 'Prev';
$lang['next'] = 'Next';
$lang['memberlog'] = 'Member Log';
$lang['removed'] = 'Removed';
$lang['added'] = 'Added';
$lang['updated'] = 'Updated';
$lang['no_info'] = 'No Information';
$lang['none']='None';
$lang['kills']='Kills';

$lang['rosterdiag'] = 'Roster Diag.';
$lang['difficulty'] = 'Difficulty';
$lang['recipe_4'] = 'optimal';
$lang['recipe_3'] = 'medium';
$lang['recipe_2'] = 'easy';
$lang['recipe_1'] = 'trivial';
$lang['roster_config'] = 'Roster Config';


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
$lang['Cooking']='Cooking';
$lang['Fishing']='Fishing';
$lang['First Aid']='First Aid';
$lang['Poisons']='Poisons';
$lang['backpack']='Backpack';
$lang['PvPRankNone']='none';

// Uses preg_match() to find required level in recipe tooltip
$lang['requires_level'] = '/Requires Level ([\d]+)/';

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
	'Warlock'=>'ability_mount_nightmarehorse'
);

// Class Icons-Array
$lang['class_iconArray'] = array (
	'Druid'=>'ability_druid_maul',
	'Hunter'=>'inv_weapon_bow_08',
	'Mage'=>'inv_staff_13',
	'Paladin'=>'spell_fire_flametounge',
	'Priest'=>'spell_holy_layonhands',
	'Rogue'=>'inv_throwingknife_04',
	'Shaman'=>'spell_nature_bloodlust',
	'Warlock'=>'spell_shadow_cripple',
	'Warrior'=>'inv_sword_25',
);

// Class Color-Array
$lang['class_colorArray'] = array(
	'Druid' => 'FF7C0A',
	'Hunter' => 'AAD372',
	'Mage' => '68CCEF',
	'Paladin' => 'F48CBA',
	'Priest' => 'ffffff',
	'Rogue' => 'FFF468',
	'Shaman' => '00DBBA',
	'Warlock' => '9382C9',
	'Warrior' => 'C69B6D'
);

$lang['pvplist']=' PvP Stats';
$lang['pvplist1']='Guild that suffered most at our hands';
$lang['pvplist2']='Guild that killed us the most';
$lang['pvplist3']='Player who we killed the most';
$lang['pvplist4']='Player who killed us the most';
$lang['pvplist5']='Member with the most kills';
$lang['pvplist6']='Member who has died the most';
$lang['pvplist7']='Member with best kill average';
$lang['pvplist8']='Member with best loss average';

$lang['hslist']=' Honor System Stats';
$lang['hslist1']='Highest Lifetime Rank';
$lang['hslist2']='Highest Lifetime HKs';
$lang['hslist3']='Most Honor Points';
$lang['hslist4']='Most Arena Points';

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


// Quests page external links (on character quests page)
	// $lang['questlinks'][#]['name']  This is the name displayed on the quests page
	// $lang['questlinks'][#]['url#']  This is the URL used for the quest lookup

	$lang['questlinks'][0]['name']='Thottbot';
	$lang['questlinks'][0]['url1']='http://www.thottbot.com/?f=q&amp;title=';
	$lang['questlinks'][0]['url2']='&amp;obj=&amp;desc=&amp;minl=';
	$lang['questlinks'][0]['url3']='&amp;maxl=';

	$lang['questlinks'][1]['name']='Allakhazam';
	$lang['questlinks'][1]['url1']='http://wow.allakhazam.com/db/qlookup.html?name=';
	$lang['questlinks'][1]['url2']='&amp;obj=&amp;desc=&amp;minl=';
	$lang['questlinks'][1]['url3']='&amp;maxl=';

	$lang['questlinks'][2]['name']='WWN Data';
	$lang['questlinks'][2]['url1']='http://wwndata.worldofwar.net/search.php?q=on&amp;search=';
	//$lang['questlinks'][2]['url2']='&amp;levelmin=';
	//$lang['questlinks'][2]['url3']='&amp;levelmax=';

	$lang['questlinks'][3]['name']='WoWHead';
	$lang['questlinks'][3]['url1']='http://www.wowhead.com/?quests&amp;filter=ti=';
	$lang['questlinks'][3]['url2']=';minle=';
	$lang['questlinks'][3]['url3']=';maxle=';

// Items external link
// Add as many item links as you need
// Just make sure their names are unique
	$lang['itemlink'] = 'Item Links';
	$lang['itemlinks']['Thottbot'] = 'http://www.thottbot.com/index.cgi?i=';
	$lang['itemlinks']['Allakhazam'] = 'http://wow.allakhazam.com/search.html?q=';
	$lang['itemlinks']['WWN Data'] = 'http://wwndata.worldofwar.net/search.php?search=';
	$lang['itemlinks']['WoWHead'] = 'http://www.wowhead.com/?items&amp;filter=na=';


// Definition for item tooltip coloring
	$lang['tooltip_use']='Use:';
	$lang['tooltip_requires']='Requires';
	$lang['tooltip_reinforced']='Reinforced';
	$lang['tooltip_soulbound']='Soulbound';
	$lang['tooltip_boe']='Binds when equipped';
	$lang['tooltip_equip']='Equip:';
	$lang['tooltip_equip_restores']='Equip: Restores';
	$lang['tooltip_equip_when']='Equip: When';
	$lang['tooltip_chance']='Chance';
	$lang['tooltip_enchant']='Enchant';
	$lang['tooltip_set']='Set';
	$lang['tooltip_rank']='Rank';
	$lang['tooltip_next_rank']='Next rank';
	$lang['tooltip_spell_damage']='Spell Damage';
	$lang['tooltip_school_damage']='\\+.*Spell Damage';
	$lang['tooltip_healing_power']='Healing Power';
	$lang['tooltip_chance_hit']='Chance to hit:';
	$lang['tooltip_reinforced_armor']='Reinforced Armor';
	$lang['tooltip_damage_reduction']='Damage Reduction';

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
	$lang['CPver_err']='The version of CharacterProfiler used to capture data for this character is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v%1$s and have logged onto this character and saved data using this version.';
	$lang['PvPLogver_err']='The version of PvPLog used to capture data for this character is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v%1$s, and if you have just updated your PvPLog, ensure you deleted your old PvPLog.lua SavedVariable file prior to updating.';
	$lang['GPver_err']='The version of GuildProfiler used to capture data for this guild is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v%1$s';

$lang['menuconf_sectionselect']='Select Section';

$lang['installer_install_0']='Installation of %1$s successful';
$lang['installer_install_1']='Installation of %1$s failed, but rollback successful';
$lang['installer_install_2']='Installation of %1$s failed, and rollback also failed';
$lang['installer_uninstall_0']='Uninstallation of %1$s successful';
$lang['installer_uninstall_1']='Uninstallation of %1$s failed, but rollback successful';
$lang['installer_uninstall_2']='Uninstallation of %1$s failed, and rollback also failed';
$lang['installer_upgrade_0']='Upgrade of %1$s successful';
$lang['installer_upgrade_1']='Upgrade of %1$s failed, but rollback successful';
$lang['installer_upgrade_2']='Upgrade of %1$s failed, and rollback also failed';

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
$lang['installer_click_upgrade'] = 'Click to Upgrade';
$lang['installer_click_install'] = 'Click to Install';
$lang['installer_overwrite'] = 'Old Version Overwrite';
$lang['installer_replace_files'] = 'Replace files with latest version';

$lang['installer_error'] = 'Install Errors';
$lang['installer_invalid_type'] = 'Invalid install type';
$lang['installer_no_success_sql'] = 'Queries were not successfully added to the installer';
$lang['installer_no_class'] = 'The install definition file for %1$s did not contain a correct installation class';
$lang['installer_no_installdef'] = 'install.def.php for %1$s was not found';

$lang['installer_no_empty'] = 'Cannot install with an empty addon name';
$lang['installer_fetch_failed'] = 'Failed to fetch addon data for %1$s';
$lang['installer_addon_exist'] = '%1$s already contains %2$s. You can go back and uninstall that addon first, or upgrade it, or install this addon with a different name';
$lang['installer_no_upgrade'] = '%1$s doesn\`t contain data to upgrade from';
$lang['installer_not_upgradable'] = '%1$s cannot upgrade %2$s since its basename %3$s isn\'t in the list of upgradable addons';
$lang['installer_no_uninstall'] = '%1$s doesn\'t contain an addon to uninstall';
$lang['installer_not_uninstallable'] = '%1$s contains an addon %2$s which must be uninstalled with that addons\' uninstaller';

// Password Stuff
$lang['password'] = 'Password';
$lang['changeadminpass'] = 'Change Admin Password';
$lang['changeupdatepass'] = 'Change Update Password';
$lang['changeguildpass'] = 'Change Guild Password';
$lang['old_pass'] = 'Old Password';
$lang['new_pass'] = 'New Password';
$lang['new_pass_confirm'] = 'New Password [ confirm ]';
$lang['pass_old_error'] = 'Wrong password. Please enter the correct old password';
$lang['pass_submit_error'] = 'Submit error. The old password, the new password, and the confirmed new password need to be submitted';
$lang['pass_mismatch'] = 'Passwords do not match. Please type the exact same password in both new password fields';
$lang['pass_blank'] = 'No blank passwords. Please enter a password in both fields. Blank passwords are not allowed';
$lang['pass_isold'] = 'Password not changed. The new password was the same as the old one';
$lang['pass_changed'] = 'Password changed. Your new password is [ %1$s ].<br /> Do not forget this password, it is stored encrypted only';
$lang['auth_req'] = 'Authorization Required';


/******************************
 * Roster Admin Strings
 ******************************/

$lang['pagebar_function'] = 'Function';
$lang['pagebar_rosterconf'] = 'Configure Main Roster';
$lang['pagebar_changepass'] = 'Change Password';
$lang['pagebar_addoninst'] = 'Manage Addons';
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
$lang['admin']['main_conf'] = 'Main Settings|Roster\'s main settings<br>Including roster URL, Interface Images URL, and other core options';
$lang['admin']['guild_conf'] = 'Guild Config|Set up your guild info<ul><li>Guild name</li><li>Realm name (server)</li><li>Short guild description</li><li>Server type</li><li>etc...</li></ul>';
$lang['admin']['index_conf'] = 'Index Page|Options for what shows on the Main Page';
$lang['admin']['menu_conf'] = 'Menu|Control what is displayed in the Roster Main Menu';
$lang['admin']['display_conf'] = 'Display Config|Misc display settings<br>css, javascript, motd, etc...';
$lang['admin']['realmstatus_conf'] = 'Realmstatus|Options for Realmstatus<br><br>To turn this off, look in the Menu section';
$lang['admin']['data_links'] = 'Item/Quest Data Links|External links for item and quest data';
$lang['admin']['update_access'] = 'Update Access|Set access levels for rostercp components';

$lang['admin']['documentation'] = 'Documentation|WoWRoster Documentation via the wowroster.net wiki';

// main_conf
$lang['admin']['roster_dbver'] = "Roster Database Version|The version of the database";
$lang['admin']['version'] = "Roster Version|Current version of Roster";
$lang['admin']['sqldebug'] = "SQL Debug Output|Print MySQL Debug Statements in html comments";
$lang['admin']['debug_mode'] = "Debug Mode|Full debug trace in error messages";
$lang['admin']['sql_window'] = "SQL Window|Displays SQL Queries in a window in the footer";
$lang['admin']['minCPver'] = "Min CP Version|Minimum CharacterProfiler version allowed to upload";
$lang['admin']['minGPver'] = "Min GP version|Minimum GuildProfiler version allowed to upload";
$lang['admin']['locale'] = "Roster Main Language|The main language roster will be displayed in";
$lang['admin']['default_page'] = "Default Page|Page to display if no page is specified in the url";
$lang['admin']['website_address'] = "Website Address|Used for url link for logo, and guildname link in the main menu<br />Some roster addons may also use this";
$lang['admin']['interface_url'] = "Interface Directory URL|Directory that the Interface images are located<br />Default is &quot;img/&quot;<br /><br />You can use a relative path or a full URL";
$lang['admin']['img_suffix'] = "Interface Image Extension|The image type of the Interface images";
$lang['admin']['alt_img_suffix'] = "Alt Interface Image Extension|The alternate possible image type of the Interface images";
$lang['admin']['img_url'] = "Roster Images Directory URL|Directory that Roster's images are located<br />Default is &quot;img/&quot;<br /><br />You can use a relative path or a full URL";
$lang['admin']['timezone'] = "Timezone|Displayed after timestamps so people know what timezone the time references are in";
$lang['admin']['localtimeoffset'] = "Time Offest|The timezone offset from UTC/GMT<br />Times on roster will be displayed as a calculated value using this offset";
$lang['admin']['use_update_triggers'] = "Addon Update Triggers|Addon Update Triggers are for addons that need to run during a character or guild update<br />Some addons my require that this is turned on for them to function properly";
$lang['admin']['check_updates'] = "Check for Updates|This allows your copy of WoWRoster (and addons that use this feature) to check if you have the newest version of the software";
$lang['admin']['seo_url'] = "Alternative urls|Use /some/page/here.html?param=value instead of /?p=some-page-here&param=value";

// guild_conf
$lang['admin']['guild_name'] = "Guild Name|This must be spelled exactly as it is in the game<br />or you <u>WILL</u> <u>NOT</u> be able to upload profiles";
$lang['admin']['server_name'] = "Server Name|This must be spelled exactly as it is in the game<br />or you <u>WILL</u> <u>NOT</u> be able to upload profiles";
$lang['admin']['guild_desc'] = "Guild Description|Enter a short Guild Description";
$lang['admin']['server_type'] = "Server Type|This for your type of server in WoW";
$lang['admin']['alt_type'] = "Alt-Text Search|Text used to designate alts for display count in the main menu";
$lang['admin']['alt_location'] = "Alt-Search Field|Search location, what field to search for Alt-Text";

// menu_conf
$lang['admin']['menu_conf_left'] = "Left pane|";
$lang['admin']['menu_conf_right'] = "Right pane|";

$lang['admin']['menu_top_pane'] = "Top Pane|Controls display of the top pane of the main roster menu<br />This area holds the guild name, server, and last update";

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

// display_conf
$lang['admin']['logo'] = "URL for header logo|The full URL to the image<br />Or by apending &quot;img/&quot; to the name, it will look in the roster's img/ directory";
$lang['admin']['roster_bg'] = "URL for background image|The full URL to the image used for the main background<br />Or by apending &quot;img/&quot; to the name, it will look in the roster's img/ directory";
$lang['admin']['motd_display_mode'] = "MOTD Display Mode|How the MOTD will be displayed<br /><br />&quot;Text&quot; - Shows MOTD in red text<br />&quot;Image&quot; - Shows MOTD as an image (REQUIRES GD!)";
$lang['admin']['signaturebackground'] = "img.php Background|Support for legacy signature-creator";
$lang['admin']['processtime'] = "Process time|Displays &quot;<i>This page was created in XXX seconds with XX queries executed</i>&quot; in the footer of roster";

// data_links
$lang['admin']['questlink_1'] = "Quest Link #1|Item external links<br />Look in your localization-file(s) for link configuration";
$lang['admin']['questlink_2'] = "Quest Link #2|Item external links<br />Look in your localization-file(s) for link configuration";
$lang['admin']['questlink_3'] = "Quest Link #3|Item external links<br />Look in your localization-file(s) for link configuration";
$lang['admin']['profiler'] = "CharacterProfiler download link|URL to download CharacterProfiler";
$lang['admin']['uploadapp'] = "UniUploader download link|URL to download UniUploader";

// realmstatus_conf
$lang['admin']['realmstatus_url'] = "Realmstatus URL|URL to Blizzard's Realmstatus page";
$lang['admin']['rs_display'] = "Info Mode|&quot;full&quot; will show status and server name, population, and type<br />&quot;half&quot; will display just the status";
$lang['admin']['rs_mode'] = "Display Mode|How Realmstatus will be displayed<br /><br />&quot;DIV Container&quot; - Shows Realmstatus in a DIV container with text and standard images<br />&quot;Image&quot; - Shows Realmstatus as an image (REQUIRES GD!)";
$lang['admin']['realmstatus'] = "Alternate Servername|Some server names may cause realmstatus to not work correctly, even if uploading profiles works<br />The actual server name from the game may not match what is used on the server status data page<br />You can set this so serverstatus can use another servername<br /><br />Leave blank to use the name set in Guild Config";
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
$lang['admin']['rs_color_error'] = "Offline Color|Color for realm offline";

// update_access
$lang['admin']['authenticated_user'] = "Access to Update.php|Controls access to update.php<br /><br />Turning this off disables access for EVERYONE";

// Character Display Settings
$lang['admin']['per_character_display'] = 'Per-Character Display';
