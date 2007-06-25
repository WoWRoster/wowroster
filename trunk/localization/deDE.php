<?php
/**
 * WoWRoster.net WoWRoster
 *
 * deDE Locale File
 *
 * deDE translation by sphinx
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.5.0
 * @package    WoWRoster
 * @subpackage Locale
*/


//Instructions how to upload, as seen on the mainpage
$lang['update_link']='Hier klicken um zur Aktualisierungsanleitung zu gelangen';
$lang['update_instructions']='Anleitung zur Aktualisierung';

$lang['lualocation']='W&auml;hle die Datei "CharacterProfiler.lua" aus';

$lang['filelocation']='finden unter<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$lang['noGuild']='Gilde nicht in der Datenbank gefunden. Bitte lade zun&auml;chst die Mitgliederliste hoch.';
$lang['nodata']='Konnte Gilde <b>\'%1$s\'</b> auf dem Server <b>\'%2$s\'</b> nicht finden<br />Du musst erst einmal die <a href="%3$s">Gildendaten hochladen</a> oder die <a href="%4$s">Konfiguration beenden</a><br /><br /><a href="http://www.wowroster.net/wiki/Roster:Install" target="_blank">Klicke hier um zur Installationsanleitung zu gelangen</a>';
$lang['nodata_title']='No Guild Data';

$lang['update_page']='Gildenmitglied aktualisieren';

$lang['guild_nameNotFound']='&quot;%s&quot; nicht gefunden. Stimmt er mit dem konfigurierten Namen &uuml;berein?';
$lang['guild_addonNotFound']='Keine Gilde gefunden. Ist das Addon GuildProfiler korrekt installiert?';

$lang['ignored']='Ignoriert';
$lang['update_disabled']='Update.php Zugriff deaktiviert.';

$lang['nofileUploaded']='UniUploader hat keine oder die falschen Dateien hochgeladen.';
$lang['roster_upd_pwLabel']='Roster Update Passwort';
$lang['roster_upd_pw_help']='(Wird nur ben&ouml;tigt, wenn man die Gilde aktualisiert)';


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
$lang['upload_data'] = 'Updating %1$s Data for [<span class="orange">%2$s@4$s-%3$s</span>]';
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
$lang['index_text_uniloader'] = '(Du kannst dieses Programm von der WoW-Roster-Webseite herunterladen, schaue nach dem "UniUploader Installer" f&uuml;r die aktuellste Version)';

$lang['update_instruct']='
<strong>Empfehlung zur automatischen Aktualisierung:</strong>
<ul>
<li>Benutze den <a href="%1$s" target="_blank">UniUploader</a><br />
%2$s</li>
</ul>
<strong>Anleitung:</strong>
<ol>
<li>Lade den <a href="%3$s" target="_blank">Character Profiler</a> herunter</li>
<li>Extrahiere die Zip-Datei in ein eigenes Verzeichnis unter C:\Program Files\World of Warcraft\Interface\Addons\CharacterProfiler\</li>
<li>Starte WoW</li>
<li>&Ouml;ffne einmal dein Bankschliessfach, deine Rucks&auml;cke, deine Berufsseiten und deine Charakter-&Uuml;bersicht</li>
<li>Logge aus oder beende WoW (Siehe oben, falls das der UniUploader automatisch erledigen soll.)</li>
<li>Gehe zur <a href="%4$s"> Update-Seite</a></li>
<li>%5$s</li>
</ol>';

$lang['update_instructpvp']='
<strong>Optionale PvP Stats:</strong>
<ol>
<li>Lade <a href="%1$s" target="_blank">PvPLog</a> herunter</li>
<li>Auch in ein eigenes Addon-Verzeichnis entpacken</li>
<li>Mache ein paar Duelle oder PvP-Kills</li>
<li>Lade "PvPLog.lua" &uuml;ber die Update-Seite hoch</li>
</ol>';

$lang['roster_credits']='Dank an <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, und <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> f&uuml;r den originalen Code der Seite. <br />
WoWRoster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="%1$s">Additional Credits</a>';


//Charset
$lang['charset']="charset=utf-8";

$lang['timeformat'] = '%d.%m. %k:%i'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$lang['phptimeformat'] = 'd.m. G:i';  // PHP date() Time format (example - 'M D jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


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
$lang['required']='Benötigt';
$lang['optional']='Optional';
$lang['attack']='Attacke';
$lang['defense']='Verteidigung';
$lang['class']='Klasse';
$lang['race']='Rasse';
$lang['level']='Level';
$lang['lastzone']='Letztes Gebiet';
$lang['note']='Notiz';
$lang['officer_note']='Officer Note';
$lang['title']='Rang';
$lang['name']='Name';
$lang['health']='Gesundheit';
$lang['mana']='Mana';
$lang['gold']='Gold';
$lang['armor']='Rüstung';
$lang['lastonline']='Zuletzt Online';
$lang['online']='Online';
$lang['lastupdate']='Zuletzt aktualisiert';
$lang['currenthonor']='Aktueller Ehrenrang';
$lang['rank']='Rank';
$lang['sortby']='Sortieren nach %';
$lang['total']='Gesamt';
$lang['hearthed']='Ruhestein';
$lang['recipes']='Rezepte';
$lang['bags']='Taschen';
$lang['character']='Charakter';
$lang['money']='Money';
$lang['bank']='Bank';
$lang['raid']='CT_Raid';
$lang['quests']='Quests';
$lang['roster']='Mitglieder';
$lang['alternate']='Alternative Ansicht';
$lang['byclass']='Nach Klasse';
$lang['menustats']='Grundwerte';
$lang['menuhonor']='Ehre';
$lang['search']='Suche';
$lang['update']='Update';
$lang['credit']='Credits';
$lang['members']='Mitglieder';
$lang['items']='Gegenst&auml;nde';
$lang['find']='Suche nach';
$lang['upprofile']='Profil Updaten';
$lang['backlink']='Zur&uuml;ck zur &Uuml;bersicht';
$lang['gender']='Geschlecht';
$lang['unusedtrainingpoints']='Unbenutzte Trainingspunkte';
$lang['unusedtalentpoints']='Unbenutzte Talentpunkte';
$lang['talentexport']='Export Talent Build';
$lang['questlog']='Questlog';
$lang['recipelist']='Rezepte Liste';
$lang['reagents']='Reagenzien';
$lang['item']='Gegenstand';
$lang['type']='Typ';
$lang['date']='Datum';
$lang['complete'] = 'Complete';
$lang['failed'] = 'Failed';
$lang['completedsteps'] = 'Abgeschlossen';
$lang['currentstep'] = 'Aktuell';
$lang['uncompletedsteps'] = 'Nicht Abgeschlossen';
$lang['key'] = 'Schl&uuml;ssel';
$lang['timeplayed'] = 'Gespielte Zeit';
$lang['timelevelplayed'] = 'Auf diesem Level';
$lang['Addon'] = 'Addons';
$lang['advancedstats'] = 'Erweiterte Eigenschaften';
$lang['crit'] = 'Krit.';
$lang['dodge'] = 'Ausweichen';
$lang['parry'] = 'Parieren';
$lang['block'] = 'Blocken';
$lang['realm'] = 'Realm';
$lang['region'] = 'Region';
$lang['server'] = 'Server';
$lang['faction'] = 'Faction';
$lang['page'] = 'Seite';
$lang['general'] = 'General';
$lang['prev'] = 'Zurück';
$lang['next'] = 'Vor';
$lang['memberlog'] = 'Mitglieder Log';
$lang['removed'] = 'Entfernt';
$lang['added'] = 'Zugefügt';
$lang['add'] = 'Add';
$lang['delete'] = 'Delete';
$lang['updated'] = 'Updated';
$lang['no_info'] = 'Keine Information';
$lang['none']='Keine';
$lang['kills']='Kills';
$lang['allow'] = 'Allow';
$lang['disallow'] = 'Disallow';
$lang['locale'] = 'Locale';
$lang['default'] = 'Default';

$lang['rosterdiag'] = 'Roster Diagnose Seite';
$lang['difficulty'] = 'Schwierigkeit';
$lang['recipe_4'] = 'optimal';
$lang['recipe_3'] = 'mittel';
$lang['recipe_2'] = 'leicht';
$lang['recipe_1'] = 'trivial';
$lang['roster_config'] = 'Roster Config';

$lang['search_names'] = 'Search Names';
$lang['search_items'] = 'Search Items';
$lang['search_tooltips'] = 'Search Tooltips';

//this needs to be exact as it is the wording in the db
$lang['professions']='Berufe';
$lang['secondary']='Sekundäre Fertigkeiten';
$lang['Blacksmithing']='Schmiedekunst';
$lang['Mining']='Bergbau';
$lang['Herbalism']='Kräuterkunde';
$lang['Alchemy']='Alchimie';
$lang['Leatherworking']='Lederverarbeitung';
$lang['Jewelcrafting']='Juwelenschleifen';
$lang['Skinning']='Kürschnerei';
$lang['Tailoring']='Schneiderei';
$lang['Enchanting']='Verzauberkunst';
$lang['Engineering']='Ingenieurskunst';
$lang['Cooking']='Kochkunst';
$lang['Fishing']='Angeln';
$lang['First Aid']='Erste Hilfe';
$lang['Poisons']='Gifte';
$lang['backpack']='Rucksack';
$lang['PvPRankNone']='none';

// Uses preg_match() to find required level in recipe tooltip
$lang['requires_level'] = '/Benötigte Stufe ([\d]+)/';

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
$lang['riding'] = 'Reiten';
$lang['ts_ridingIcon'] = array(
	'Nachtelf'=>'ability_mount_whitetiger',
	'Mensch'=>'ability_mount_ridinghorse',
	'Zwerg'=>'ability_mount_mountainram',
	'Gnom'=>'ability_mount_mechastrider',
	'Untoter'=>'ability_mount_undeadhorse',
	'Troll'=>'ability_mount_raptor',
	'Tauren'=>'ability_mount_kodo_03',
	'Orc'=>'ability_mount_blackdirewolf',
	'Blutelf' => 'ability_mount_cockatricemount',
	'Draenei' => 'ability_mount_ridingelekk',
	'Paladin'=>'ability_mount_dreadsteed',
	'Hexenmeister'=>'ability_mount_nightmarehorse'
);

// Class Icons-Array
$lang['class_iconArray'] = array (
	'Druide'=>'ability_druid_maul',
	'J�ger'=>'inv_weapon_bow_08',
	'Magier'=>'inv_staff_13',
	'Paladin'=>'spell_fire_flametounge',
	'Priester'=>'spell_holy_layonhands',
	'Schurke'=>'inv_throwingknife_04',
	'Schamane'=>'spell_nature_bloodlust',
	'Hexenmeister'=>'spell_shadow_cripple',
	'Krieger'=>'inv_sword_25',
);

// Class Color-Array
$lang['class_colorArray'] = array(
	'Druide' => 'FF7C0A',
	'J�ger' => 'AAD372',
	'Magier' => '68CCEF',
	'Paladin' => 'F48CBA',
	'Priester' => 'ffffff',
	'Schurke' => 'FFF468',
	'Schamane' => '00DBBA',
	'Hexenmeister' => '9382C9',
	'Krieger' => 'C69B6D'
);

$lang['pvplist']=' PvP Statistiken';
$lang['pvplist1']='Gilde, die am meisten unter uns zu leiden hat';
$lang['pvplist2']='Gilde, die uns am meisten zu Schaffen macht';
$lang['pvplist3']='Spieler, der am meisten unter uns zu leiden hat';
$lang['pvplist4']='Spieler, der uns am meisten zu Schaffen macht';
$lang['pvplist5']='Mitglied mit den meisten Kills';
$lang['pvplist6']='Mitglied, der am h&auml;ufigsten gestorben ist';
$lang['pvplist7']='Besten Kills-Level-Durchschnitt';
$lang['pvplist8']='Besten Tod-Level-Durchschnitt';

$lang['hslist']=' Ehren Statistiken';
$lang['hslist1']='H&ouml;chsten Lebenszeit Rang';
$lang['hslist2']='H&ouml;chsten Lebenszeit ES';
$lang['hslist3']='Die meisten Ehrenpunkte';
$lang['hslist4']='Die meisten Arenapunkte';

$lang['Druid']='Druide';
$lang['Hunter']='Jäger';
$lang['Mage']='Magier';
$lang['Paladin']='Paladin';
$lang['Priest']='Priester';
$lang['Rogue']='Schurke';
$lang['Shaman']='Schamane';
$lang['Warlock']='Hexenmeister';
$lang['Warrior']='Krieger';

$lang['today']='Heute';
$lang['todayhk']='Heute HK';
$lang['todaycp']='Heute CP';
$lang['yesterday']='Gestern';
$lang['yesthk']='Gestern HK';
$lang['yestcp']='Gestern CP';
$lang['thisweek']='Diese Woche';
$lang['lastweek']='Letzte Woche';
$lang['lifetime']='Gesamte Spielzeit';
$lang['lifehk']='Gesamte HK';
$lang['honorkills']='Ehrenhafte Siege';
$lang['dishonorkills']='Ruchlose Morde';
$lang['honor']='Ehre';
$lang['standing']='Platzierung';
$lang['highestrank']='Höchster Rank';
$lang['arena']='Arena';

$lang['when']='Wann';
$lang['guild']='Gilde';
$lang['result']='Ergebnis';
$lang['zone']='Zone';
$lang['subzone']='Subzone';
$lang['yes']='Ja';
$lang['no']='Nein';
$lang['win']='Sieg';
$lang['loss']='Niederlage';
$lang['unknown']='Unknown';

//strings for Rep-tab
$lang['exalted']='Ehrfürchtig';
$lang['revered']='Respektvoll';
$lang['honored']='Wohlwollend';
$lang['friendly']='Freundlich';
$lang['neutral']='Neutral';
$lang['unfriendly']='Unfreundlich';
$lang['hostile']='Feindselig';
$lang['hated']='Hasserfüllt';
$lang['atwar']='Im Krieg';
$lang['notatwar']='Nicht im Krieg';


// Quests page external links (on character quests page)
// $lang['questlinks'][#]['name']  This is the name displayed on the quests page
// $lang['questlinks'][#]['url#']  This is the URL used for the quest lookup

$lang['questlinks'][0]['name']='WoW-Handwerk';
$lang['questlinks'][0]['url1']='http://www.wow-handwerk.de/search.php?quicksearch=';
//$lang['questlinks'][0]['url2']='';
//$lang['questlinks'][0]['url3']='&amp;maxl='';

$lang['questlinks'][1]['name']='Buffed DE';
$lang['questlinks'][1]['url1']='http://www.buffed.de/?f=';
//$lang['questlinks'][1]['url2']='';
//$lang['questlinks'][1]['url3']='';

$lang['questlinks'][2]['name']='Thottbot';
$lang['questlinks'][2]['url1']='http://www.thottbot.com/?f=q&amp;title=';
$lang['questlinks'][2]['url2']='&amp;obj=&amp;desc=&amp;minl=';
$lang['questlinks'][2]['url3']='&amp;maxl=';

//$lang['questlinks'][3]['name']='WoWHead';
//$lang['questlinks'][3]['url1']='http://www.wowhead.com/?quests&amp;filter=ti=';
//$lang['questlinks'][3]['url2']=';minle=';
//$lang['questlinks'][3]['url3']=';maxle=';

// Items external link
// Add as many item links as you need
// Just make sure their names are unique
$lang['itemlink'] = 'Item Links';
$lang['itemlinks']['WoW-Handwerk'] = 'http://www.wow-handwerk.de/search.php?quicksearch=';
$lang['itemlinks']['buffed.de'] = 'http://www.buffed.de/?f=';
$lang['itemlinks']['Thottbot'] = 'http://www.thottbot.com/index.cgi?i=';
//$lang['itemlinks']['WoWHead'] = 'http://www.wowhead.com/?items&amp;filter=na=';

// WoW Data Site Search
// Add as many item links as you need
// Just make sure their names are unique
$lang['data_search'] = 'WoW Data Site Search';
$lang['data_links']['WoW-Handwerk'] = 'http://www.wow-handwerk.de/search.php?quicksearch=';
$lang['data_links']['buffed.de'] = 'http://www.buffed.de/?f=';
$lang['data_links']['Thottbot'] = 'http://www.thottbot.com/index.cgi?s=';
//$lang['data_links']['WoWHead'] = 'http://www.wowhead.com/?search=';


// Definitions for item tooltip coloring
$lang['tooltip_use']='Benutzen';
$lang['tooltip_requires']='Benötigt';
$lang['tooltip_reinforced']='Verstärkte';
$lang['tooltip_soulbound']='Seelengebunden';
$lang['tooltip_boe']='Wird beim Anlegen gebunden';
$lang['tooltip_equip']='Anlegen';
$lang['tooltip_equip_restores']='Anlegen: Stellt';
$lang['tooltip_equip_when']='Anlegen: Erhöht';
$lang['tooltip_chance']='Gewährt';
$lang['tooltip_enchant']='Enchant';
$lang['tooltip_set']='Set';
$lang['tooltip_rank']='Rang';
$lang['tooltip_next_rank']='Nächster Rang';
$lang['tooltip_spell_damage']='Schaden';
$lang['tooltip_school_damage']='\\+.*Schaden';
$lang['tooltip_healing_power']='Heilung';
$lang['tooltip_chance_hit']='Trefferchance';
$lang['tooltip_reinforced_armor']='Verstärkte Rüstung';
$lang['tooltip_damage_reduction']='Schadensreduzierung';

// Warlock pet names for icon displaying
$lang['Imp']='Wichtel';
$lang['Voidwalker']='Leerwandler';
$lang['Succubus']='Sukkubus';
$lang['Felhunter']='Teufelsjäger';
$lang['Infernal']='Infernal';
$lang['Felguard']='Teufelswache';

// Max experiance for exp bar on char page
$lang['max_exp']='Max XP';

// Error messages
$lang['CPver_err']='Die verwendete Version des CharacterProfiler, zur Speicherung der Daten für diesen Charakter ist &auml;lter als die für den Upload minimal zugelassene Version.<br />Bitte stellen Sie sicher, daß Sie mindestens v%1$s verwenden, und daß Sie diese Version verwendet haben, um die Daten für diesen Charakter zu speichern.';
$lang['GPver_err']='Die verwendete Version von GuildProfiler, zur Speicherung der Daten für diese Gilde ist &auml;lter als die für den Upload minimal zugelassene Version.<br />Bitte stellen Sie sicher, daß Sie mindestens v%1$s verwenden.';

// Menu titles
$lang['menu_upprofile']='Update Profile|Update your profile on this site';
$lang['menu_search']='Search|Search items and recipes in the database';
$lang['menu_roster_cp']='Roster CP|Roster Configuration Panel';
$lang['menu_credits']='Credits|Who made WoW Roster';

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

// Upload Rules
$lang['upload_rules_help'] = 'The rules are divided into two blocks.<br />For each uploaded guild/char, first the top block is checked.<br />If the name@server matches one of these \'deny\' rules, it is rejected.<br />After that the second block is checked.<br />If the name@server matches one of these \'accept\' rules, it is accepted.<br />If it does not match any rule, it is rejected.';

/******************************
 * Roster Admin Strings
 ******************************/

$lang['pagebar_function'] = 'Aufgaben';
$lang['pagebar_rosterconf'] = 'Konfiguriere Roster';
$lang['pagebar_uploadrules'] = 'Upload Rules';
$lang['pagebar_changepass'] = 'Passwort Änderung';
$lang['pagebar_addoninst'] = 'Verwalte Addons';
$lang['pagebar_update'] = 'Upload Profile';
$lang['pagebar_rosterdiag'] = 'Roster Diagnose Seite';
$lang['pagebar_menuconf'] = 'Menu Konfiguration';
$lang['pagebar_configreset'] = 'Config Reset';

$lang['pagebar_addonconf'] = 'Addon Konfiguration';

$lang['roster_config_menu'] = 'Config Menu';

// Submit/Reset confirm questions
$lang['config_submit_button'] = 'Speichere Einstellungen';
$lang['config_reset_button'] = 'Zurücksetzen';
$lang['confirm_config_submit'] = 'Dies wird die Änderungen in die Datenbank schreiben. Sind sie sicher?';
$lang['confirm_config_reset'] = 'Dies wird das Formular in den Zustand zurücksetzen in dem es am Anfang war. Sind sie sicher?';

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
$lang['admin']['main_conf'] = 'Haupteinstellungen|Roster\'s wichtigste Einstellungen<br>Enthält Roster URL, Bilder URL und andere grundlegende Einstellungen...';
$lang['admin']['guild_conf'] = 'Gildenkonfiguration|Gib deine Gildeninfos ein<ul><li>Gildenname</li><li>Realmname (Server)</li><li>Eine kurze Beschreibung</li><li>Servertyp</li><li>etc...</li></ul>';
$lang['admin']['index_conf'] = 'Indexseite|Einstellen was auf der Hauptseite angezeigt werden soll';
$lang['admin']['menu_conf'] = 'Menüeinstellungen|Einstellen welche Elemente im Menü gezeigt werden sollen';
$lang['admin']['display_conf'] = 'Anzeigeneinstellungen|Verschiedene Anzeigeeinstellungen<br>css, javascript, motd, etc...';
$lang['admin']['realmstatus_conf'] = 'Serverstatus|Optionen für die Serverstatus<br><br>Um es auszustellen, bitte bei Menüeinstellungen gucken';
$lang['admin']['data_links'] = 'Item/Quest Data Links|Externe Links für Gegenstände und Quests';
$lang['admin']['update_access'] = 'Update Zugriff|Optionale phpBB Authorisierung für update';

$lang['admin']['documentation'] = 'Dokumentation|WoWRoster Dokumentation über das wowroster.net-Wiki';

// main_conf
$lang['admin']['roster_dbver'] = "Roster Datenbank Version|Die Version der Datenbank";
$lang['admin']['version'] = "Roster Version|Aktuelle Version des Rosters";
$lang['admin']['sqldebug'] = "SQL Debug Output|Gib MySQL Debug Ausgaben in HTML Kommentaren";
$lang['admin']['debug_mode'] = "Debug Modus|Zeigt die komplette Debugprotokollierung im Fehlerfalle an";
$lang['admin']['sql_window'] = "SQL Fenster|Zeigt die SQL Fehler in einem Fenster in der Fu&szlig;zeile an";
$lang['admin']['minCPver'] = "Min CP Version|Minimum CharacterProfiler Version zum Upload";
$lang['admin']['minGPver'] = "Min GP version|Minimum GuildProfiler Version zum Upload";
$lang['admin']['locale'] = "Roster Hauptsprache|Sprache, in der das Roster anzeigen soll";
$lang['admin']['default_page'] = "Default Page|Page to display if no page is specified in the url";
$lang['admin']['website_address'] = "Webseitenadresse|Wird benötigt für das Logo, den Gildennamenlink und das Hauptmenü<br />Einige Roster Addons benötigen diese auch";
$lang['admin']['interface_url'] = "Interface Directory URL|Verzeichnis zu den Interface Images<br />Das Standartverzeichnis ist &quot;img/&quot;<br /><br />Du kannst auch eine andere URL verwenden.";
$lang['admin']['img_suffix'] = "Bilder Dateierweiterung|Der Dateityp deiner Interface Images";
$lang['admin']['alt_img_suffix'] = "Alternative Bilder Dateierweiterung|Der Dateityp deiner Interface Images";
$lang['admin']['img_url'] = "Roster Bilder Verzeichnis URL|Verzeichnis zu den Roster's Images<br />Das Standartverzeichnis ist &quot;img/&quot;<br /><br />Du kannst auch eine andere URL verwenden.";
$lang['admin']['timezone'] = "Zeitzone|Wird hinter der Zeit angezeigt, damit man weis in welcher Zeitzone sich der Zeithinweis befindet";
$lang['admin']['localtimeoffset'] = "Zeitzonenabstand|Der Zeitzonenabstand zur UTC/GMT<br />Die Zeiten im Roster werden durch diesen Abstand zur UTC/GMT berechnet.";
$lang['admin']['use_update_triggers'] = "Addon Update Triggers|Addon Update Triggers werden für einige AddOns während eines Character oder Gildenupdates benötigt.<br />Einige Addons benötigen wahrscheinlich, dass diese Funktion für sie angestellt ist.";
$lang['admin']['check_updates'] = "Check for Updates|This allows your copy of WoWRoster (and addons that use this feature) to check if you have the newest version of the software";
$lang['admin']['seo_url'] = "Alternative urls|Use /some/page/here.html?param=value instead of /?p=some-page-here&param=value";

// guild_conf
$lang['admin']['default_name'] = "WowRoster Name|Enter a name to be displayed when not in the guild or char scope";
$lang['admin']['default_desc'] = "Description|Enter a short description to be displayed when not in the guild or char scope";
$lang['admin']['alt_type'] = "2.-Char Suche (Twinks)|Text, der zur Anzeige der Anzahl der 2.-Charaktere auf der Hautpseite benutzt wird";
$lang['admin']['alt_location'] = "Twink Suchfeld|In welchem Feld soll der Twink-Text gesucht werden";

// menu_conf
$lang['admin']['menu_conf_left'] = "Left pane|";
$lang['admin']['menu_conf_right'] = "Right pane|";

$lang['admin']['menu_top_pane'] = "Top Pane|Controls display of the top pane of the main roster menu<br />This area holds the guild name, server, last update, etc...";
$lang['admin']['menu_top_faction'] = "Faction Icon|Controls display of the faction icon in the top pane of the main roster menu";
$lang['admin']['menu_top_locale'] = "Locale Selection|Controls display of the locale selection in the top pane of the main roster menu";
$lang['admin']['menu_top_list'] = "List Selection|Controls display of the realm/guild/character list in the top pane of the main roster menu";

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
$lang['admin']['logo'] = "URL für das Kopf-Logo|Die volle URL für das Logo<br />Oder &quot;img/&quot; vor den Namen setzen, um im /img-Verzeichnis des Rosters nachzugucken";
$lang['admin']['roster_bg'] = "URL für das Hintergrundbild|Die volle URL für den Haupthintergrund<br />Oder &quot;img/&quot; vor den Namen setzen, um im /img-Verzeichnis des Rosters nachzugucken";
$lang['admin']['motd_display_mode'] = "MOTD Anzeige Modus|Wie die MOTD (Message of the day) angezeigt werden soll:<br /><br />&quot;Text&quot; - Zeigt MOTD in rotem Text<br />&quot;Image&quot; - Zeigt MOTD als Bild (Benötigt GD!)";
$lang['admin']['signaturebackground'] = "img.php Hintergrund|Support für die (alten) Standard Signaturen";
$lang['admin']['processtime'] = "Seiten Gen. Zeit/DB Abfragen|Zeigt &quot;<i>Diese Seite wurde erzeugt in XXX Sekunden mit XX Datenbankabfragen</i>&quot; im Footer des Rosters an";

// data_links
$lang['admin']['questlink_1'] = "Quest Link #1|Externe Verlinkung der Gegenstände<br />Schau in deine Lokalisations-Datei(en) für weitere Einstellungen";
$lang['admin']['questlink_2'] = "Quest Link #2|Externe Verlinkung der Gegenstände<br />Schau in deine Lokalisations-Datei(en) für weitere Einstellungen";
$lang['admin']['questlink_3'] = "Quest Link #3|Externe Verlinkung der Gegenstände<br />Schau in deine Lokalisations-Datei(en) für weitere Einstellungen";
$lang['admin']['profiler'] = "CharacterProfiler Downloadlink|URL um das CharacterProfiler-Addon herunterzuladen";
$lang['admin']['uploadapp'] = "UniUploader Downloadlink|URL um den UniUploader herunterzuladen";

// realmstatus_conf
$lang['admin']['realmstatus_url'] = "Realmstatus URL|URL zu Blizzard's Realmstatus Seite";
$lang['admin']['rs_display'] = "Info Mode|&quot;full&quot; zeigt Status, Name, Population, und Servertyp<br />&quot;half&quot; zeigt nur den Status an";
$lang['admin']['rs_mode'] = "Display Mode|Wie der Status angezeigt werden soll:<br /><br />&quot;DIV Container&quot; - Zeigt den Realmstatus in einem DIV Container mit Text und Standardbildern<br />&quot;Image&quot; - Zeigt Realmstatus als ein Bild (BENÖTIGT GD!)";
$lang['admin']['realmstatus'] = "Alternativer Servername|Manche Servernamen funktionieren hier nicht richtig, auch wenn der Upload von Profilen geht<br />Der tatsächliche Servername stimmt dann mit dem Namen auf der Statusseite von Blizzard nicht überein.<br />Dann kannst du hier einen anderen Servernamen setzen<br /><br />Leer lassen, um den Namen in der Gildenkonfiguration einzustellen";
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
$lang['admin']['authenticated_user'] = "Zugriff auf Update.php|Kontrolliert den Zugriff auf update.php<br /><br />OFF deaktiviert den Zugriff für JEDEN";

// Character Display Settings
$lang['admin']['per_character_display'] = 'Charakterspezifische Anzeige-Einstellungen';
