<?php
/**
 * WoWRoster.net WoWRoster
 *
 * deDE Locale File
 *
 * deDE translation by sphinx, SethDeBlade, wowroster.de
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

$lang['langname'] = 'German';

//Instructions how to upload, as seen on the mainpage
$lang['update_link']='Hier klicken um zur Aktualisierungsanleitung zu gelangen';
$lang['update_instructions']='Anleitung zur Aktualisierung';

$lang['lualocation']='Wähle die Datei "CharacterProfiler.lua" aus';

$lang['filelocation']='finden unter<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$lang['noGuild']='Gilde nicht in der Datenbank gefunden. Bitte lade zunächst die Mitgliederliste hoch.';
$lang['nodata']='Konnte Gilde <b>\'%1$s\'</b> auf dem Server <b>\'%2$s\'</b> nicht finden<br />Du musst erst einmal die <a href="%3$s">Gildendaten hochladen</a> oder die <a href="%4$s">Konfiguration beenden</a><br /><br /><a href="http://www.wowroster.net/MediaWiki/Roster:Install" target="_blank">Klicke hier um zur Installationsanleitung zu gelangen</a>';
$lang['nodata_title']='keine Gildendaten vorhanden';

$lang['update_page']='Gildenmitglied aktualisieren';

$lang['guild_addonNotFound']='Keine Gilde gefunden. Ist das Addon GuildProfiler korrekt installiert?';

$lang['ignored']='Ignoriert';
$lang['update_disabled']='Update.php Zugriff deaktiviert.';

$lang['nofileUploaded']='UniUploader hat keine oder die falschen Dateien hochgeladen.';
$lang['roster_upd_pwLabel']='Roster Update Passwort';
$lang['roster_upd_pw_help']='(Some lua updates may require a password)';


$lang['roster_error'] = 'Roster Fehler';
$lang['sql_queries'] = 'SQL Abfragen';
$lang['invalid_char_module'] = 'Ungültige Zeichen im Modulnamen';
$lang['module_not_exist'] = 'Das Modul [%1$s] existiert nicht';

$lang['addon_error'] = 'Addon Fehler';
$lang['specify_addon'] = 'Du musste einen Addonnamen angeben!';
$lang['addon_not_exist'] = '<b>Das Addon [%1$s] existiert nicht!</b>';
$lang['addon_disabled'] = '<b>Das Addon [%1$s] wurde deaktiviert</b>';
$lang['addon_not_installed'] = '<b>Das Addon [%1$s] wurde bis jetzt noch nicht installiert</b>';
$lang['addon_no_config'] = '<b>Das Addon [%1$s] wurde noch nicht konfiguriert</b>';

$lang['char_error'] = 'Charakterfehler';
$lang['specify_char'] = 'Charakter wurde nicht spezifiziert';
$lang['no_char_id'] = 'Sorry keine Charakter for member_id [ %1$s ]';
$lang['no_char_name'] = 'Sorry keine Charakterdaten für <strong>%1$s</strong> von <strong>%2$s</strong>';

$lang['roster_cp'] = 'Roster Kontrollbereich';
$lang['roster_cp_ab'] = 'Roster CP';
$lang['roster_cp_not_exist'] = 'Seite [%1$s] existiert nicht';
$lang['roster_cp_invalid'] = 'Ungültige Seite angegeben oder ungültige Berechtigung um diese Seite aufzurufen';

$lang['parsing_files'] = 'Analysiere Dateien';
$lang['parsed_time'] = '%1$s analysiert in %2$s Sekunden';
$lang['error_parsed_time'] = 'Fehler während Analyse von %1$s nach %2$s seconds';
$lang['upload_not_accept'] = 'Es ist nicht erlaubt %1$s hochzuladen';

$lang['processing_files'] = 'Verarbeite Dateien';
$lang['error_addon'] = 'Es gab einen Fehler im Addon %1$s in der Methode %2$s';
$lang['addon_messages'] = 'Addon Nachrichten:';

$lang['not_accepted'] = '%1$s %2$s @ %3$s-%4$s nicht akzeptiert';

$lang['not_updating'] = 'kein Update %1$s für [%2$s] - %3$s';
$lang['not_update_guild'] = 'kein Update der Gildenseite für %1$s@%3$s-%2$s';
$lang['not_update_guild_time'] = 'kein Update der Gildenseite für %1$s. Gildenprofil ist zu Alt';
$lang['not_update_char_time'] = 'kein Update der Charakter für %1$s. Profil ist zu Alt';
$lang['no_members'] = 'Daten von manchen Gildemmitglieder konnten nicht aufgenommer werden';
$lang['upload_data'] = 'Update %1$s Daten für [<span class="orange">%2$s@%4$s-%3$s</span>]';
$lang['realm_ignored'] = 'Realm: %1$s Not Scanned';
$lang['guild_realm_ignored'] = 'Guild: %1$s @ Realm: %2$s Not Scanned';
$lang['update_members'] = 'Mitglieder aktualisieren ';
$lang['update_errors'] = 'Fehler beim Aktualisieren';
$lang['update_log'] = 'Update Log';
$lang['save_error_log'] = 'speichere Fehler Log';
$lang['save_update_log'] = 'speichere Update Log';

$lang['new_version_available'] = 'eine neue Version von %1$s ist verfügbar <span class="green">v%2$s</span><br />Download es  <a href="%3$s" target="_blank">HIER</a>';

$lang['remove_install_files'] = 'entferne Installationsdatein';
$lang['remove_install_files_text'] = 'Please remove <span class="green">install.php</span> in this directory';

$lang['upgrade_wowroster'] = 'Upgrade WoWRoster';
$lang['upgrade'] = 'Upgrade';
$lang['select_version'] = 'Select Version';
$lang['upgrade_wowroster_text'] = "Du hast die neue Version von WOWRoster geladen<br /><br />\nYour Version: <span class=\"red\">%1\$s</span><br />\nNew Version: <span class=\"green\">%2\$s</span><br /><br />\n<a href=\"upgrade.php\" style=\"border:1px outset white;padding:2px 6px 2px 6px;\">UPGRADE</a>";
$lang['no_upgrade'] = 'You have already upgraded Roster<br />Or you have a newer version than this upgrader';
$lang['upgrade_complete'] = 'Your WoWRoster installation has been successfully upgraded';

// Menu buttons
$lang['menu_header_01'] = 'Gilden Information';
$lang['menu_header_02'] = 'Realm Information';
$lang['menu_header_03'] = 'Update Profile';
$lang['menu_header_04'] = 'Einstellungen';
$lang['menu_header_scope_panel'] = '%s Panel';

// Updating Instructions
$lang['index_text_uniloader'] = '(Du kannst dieses Programm von der WoW-Roster-Webseite herunterladen, schaue nach dem "UniUploader Installer" für die aktuellste Version)';

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
<li>Öffne einmal dein Bankschliessfach, deine Rucksäcke, deine Berufsseiten und deine Charakter-Übersicht</li>
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
<li>Lade "PvPLog.lua" über die Update-Seite hoch</li>
</ol>';

$lang['roster_credits']='Dank an <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, und <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> für den originalen Code der Seite. <br />
WoWRoster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="%1$s">Additional Credits</a>';


$lang['timeformat'] = '%d.%m. %k:%i'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$lang['phptimeformat'] = 'd.m. G:i';  // PHP date() Time format (example - 'M D jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


/**
 * Realmstatus Localizations
 */
$lang['rs'] = array(
	'ERROR' => 'Fehler',
	'NOSTATUS' => 'kein Status',
	'UNKNOWN' => 'Unbekannt',
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
$lang['guildless']='Gildenlos';
$lang['util']='Einstellungen';
$lang['char']='Charakter';
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
$lang['officer_note']='Offiziers Notiz';
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
$lang['money']='Geld';
$lang['bank']='Bank';
$lang['raid']='CT_Raid';
$lang['quests']='Quests';
$lang['roster']='Mitglieder';
$lang['alternate']='Alternative Ansicht';
$lang['byclass']='Nach Klasse';
$lang['menustats']='Grundwerte';
$lang['menuhonor']='Ehre';
//start search engine
$lang['search']='Suche';
$lang['search_roster']='Suche im Roster';
$lang['search_onlyin']='Suche nur in diesen AddOns';
$lang['search_advancedoptionsfor']='erweiterte Options für:';
$lang['search_results']='Suche Ergebnisse für';
$lang['search_results_from']='Hier sind deine Suchergebnisse';
$lang['search_momatches']='Sorry, die Suche hat nichts ergeben';
$lang['search_didnotfind']='Did you not find what you were looking for? Try here!';
$lang['search_for']='Suche Roster For';
$lang['search_next_matches'] = 'Nächstes Ergebnis für: ';
$lang['search_previous_matches'] = 'Vorheriges Ergebnis für: ';
$lang['search_results_count'] = 'Ergebnisse';
$lang['submited_author'] = 'eingereicht durch';
$lang['submited_date'] = 'Daten gesendet';
//end search engine
$lang['update']='Update';
$lang['credit']='Credits';
$lang['members']='Mitglieder';
$lang['items']='Gegenstände';
$lang['find']='Suche nach';
$lang['upprofile']='Profil Updaten';
$lang['backlink']='Zurück zur Übersicht';
$lang['gender']='Geschlecht';
$lang['unusedtrainingpoints']='Unbenutzte Trainingspunkte';
$lang['unusedtalentpoints']='Unbenutzte Talentpunkte';
$lang['talentexport']='Exportiere Talentbaum';
$lang['questlog']='Questlog';
$lang['recipelist']='Rezeptliste';
$lang['reagents']='Reagenzien';
$lang['item']='Gegenstand';
$lang['type']='Typ';
$lang['date']='Datum';
$lang['complete'] = 'Fertiggestellt';
$lang['failed'] = 'Fehler';
$lang['completedsteps'] = 'Abgeschlossen';
$lang['currentstep'] = 'Aktuell';
$lang['uncompletedsteps'] = 'Nicht Abgeschlossen';
$lang['key'] = 'Schlüssel';
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
$lang['faction'] = 'Ruf';
$lang['page'] = 'Seite';
$lang['general'] = 'General';
$lang['prev'] = 'Zurück';
$lang['next'] = 'Vor';
$lang['memberlog'] = 'Mitglieder Log';
$lang['removed'] = 'Entfernt';
$lang['added'] = 'Zugefügt';
$lang['add'] = 'Hinzufügen';
$lang['delete'] = 'Löschen';
$lang['updated'] = 'geändert';
$lang['no_info'] = 'Keine Information';
$lang['none']='Keine';
$lang['kills']='Kills';
$lang['allow'] = 'Erlauben';
$lang['disallow'] = 'Verbieten';
$lang['locale'] = 'Locale';
$lang['language'] = 'Language';
$lang['default'] = 'Standart';
$lang['proceed'] = 'weiter';
$lang['submit'] = 'senden';

$lang['rosterdiag'] = 'Roster Diagnose Seite';
$lang['difficulty'] = 'Schwierigkeit';
$lang['recipe_4'] = 'optimal';
$lang['recipe_3'] = 'mittel';
$lang['recipe_2'] = 'leicht';
$lang['recipe_1'] = 'belanglos';
$lang['roster_config'] = 'Roster instellung';

$lang['search_names'] = 'nach Namen suchen';
$lang['search_items'] = 'nach Items suchen';
$lang['search_tooltips'] = 'suche im Tooltip';

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
	'Druide'=>'druid_icon',
	'Jäger'=>'hunter_icon',
	'Magier'=>'mage_icon',
	'Paladin'=>'paladin_icon',
	'Priester'=>'priest_icon',
	'Schurke'=>'rogue_icon',
	'Schamane'=>'shaman_icon',
	'Hexenmeister'=>'warlock_icon',
	'Krieger'=>'warrior_icon',
);

// Class Color-Array
$lang['class_colorArray'] = array(
	'Druide' => 'FF7C0A',
	'Jäger' => 'AAD372',
	'Magier' => '68CCEF',
	'Paladin' => 'F48CBA',
	'Priester' => 'ffffff',
	'Schurke' => 'FFF468',
	'Schamane' => '00DBBA',
	'Hexenmeister' => '9382C9',
	'Krieger' => 'C69B6D'
);

// Class To English Translation
$lang['class_to_en'] = array(
	'Druide' => 'Druid',
	'Jäger' => 'Hunter',
	'Magier' => 'Mage',
	'Paladin' => 'Paladin',
	'Priester' => 'Priest',
	'Schurke' => 'Rogue',
	'Schamane' => 'Shaman',
	'Hexenmeister' => 'Warlock',
	'Krieger' => 'Warrior'
);

$lang['pvplist']='PvP Statistiken';
$lang['pvplist1']='Gilde, die am meisten unter uns zu leiden hat';
$lang['pvplist2']='Gilde, die uns am meisten zu Schaffen macht';
$lang['pvplist3']='Spieler, der am meisten unter uns zu leiden hat';
$lang['pvplist4']='Spieler, der uns am meisten zu Schaffen macht';
$lang['pvplist5']='Mitglied mit den meisten Kills';
$lang['pvplist6']='Mitglied, der am häufigsten gestorben ist';
$lang['pvplist7']='Besten Kills-Level-Durchschnitt';
$lang['pvplist8']='Besten Tod-Level-Durchschnitt';

$lang['hslist']=' Ehren Statistiken';
$lang['hslist1']='Höchsten Lebenszeit Rang';
$lang['hslist2']='Höchsten Lebenszeit ES';
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
// $lang['questlinks'][][] = array(
// 		'name'=> 'Name',  //This is the name displayed on the quests page
// 		'url#'=> 'url',  //This is the URL used for the quest lookup

$lang['questlinks'][] = array(
	'name'=>'WoW-Handwerk',
	'url1'=>'http://www.wow-handwerk.de/search.php?quicksearch=',
	//'url2'=>'',
	//'url3'=>'&amp;maxl=''
);

$lang['questlinks'][] = array(
	'name'=>'Buffed DE',
	'url1'=>'http://www.buffed.de/?f=',
	//'url2'=>'',
	//'url3'=>''
);

$lang['questlinks'][] = array(
	'name'=>'Thottbot',
	'url1'=>'http://www.thottbot.com/?f=q&amp;title=',
	'url2'=>'&amp;obj=&amp;desc=&amp;minl=',
	'url3'=>'&amp;maxl='
);

/*$lang['questlinks'][] = array(
	'name'=>'WoWHead',
	'url1'=>'http://www.wowhead.com/?quests&amp;filter=na=',
	'url2'=>';minle=',
	'url3'=>';maxle='
);*/

// Items external link
// Add as many item links as you need
// Just make sure their names are unique
// uses the 'item_id' for data
$lang['itemlink'] = 'Item Links';
$lang['itemlinks']['WoW-Handwerk'] = 'http://wowhandwerk.onlinewelten.com/item.php?id=';
$lang['itemlinks']['Buffed DE'] = 'http://www.buffed.de/?i=';
$lang['itemlinks']['Thottbot'] = 'http://www.thottbot.com/i';
//$lang['itemlinks']['WoWHead'] = 'http://www.wowhead.com/?items&amp;filter=na=';

// WoW Data Site Search
// Add as many item links as you need
// Just make sure their names are unique
// use these locales for data searches
$lang['data_search'] = 'WoW Data Site Search';
$lang['data_links']['WoW-Handwerk'] = 'http://www.wow-handwerk.de/search.php?quicksearch=';
$lang['data_links']['buffed.de'] = 'http://www.buffed.de/?f=';
$lang['data_links']['Thottbot'] = 'http://www.thottbot.com/index.cgi?s=';
//$lang['data_links']['WoWHead'] = 'http://www.wowhead.com/?search=';

// Google Search
// Add as many item links as you need
// Just make sure their names are unique
// use these locales for data searches
$lang['google_search'] = 'Google';
$lang['google_links']['Google'] = 'http://www.google.com/search?q=';
$lang['google_links']['Google Groups'] = 'http://groups.google.com/groups?q=';
$lang['google_links']['Google Images'] = 'http://images.google.com/images?q=';
$lang['google_links']['Google News'] = 'http://news.google.com/news?q=';

// Definitions for item tooltip coloring
$lang['tooltip_use']='Benutzen.';
$lang['tooltip_requires']='Benötigt';
$lang['tooltip_reinforced']='Verstärkte';
$lang['tooltip_soulbound']='Seelengebunden';
$lang['tooltip_boe']='Wird beim Anlegen gebunden';
$lang['tooltip_equip']='Anlegen.';
$lang['tooltip_equip_restores']='Anlegen: Stellt';
$lang['tooltip_equip_when']='Anlegen: Erhöht';
$lang['tooltip_chance']='Gewährt';
$lang['tooltip_enchant']='Enchant';
$lang['tooltip_set']='Set.';
$lang['tooltip_rank']='Rang';
$lang['tooltip_next_rank']='Nächster Rang';
$lang['tooltip_spell_damage']='Schaden';
$lang['tooltip_school_damage']='\\+.*Schaden';
$lang['tooltip_healing_power']='Heilung';
$lang['tooltip_reinforced_armor']='Verstärkte Rüstung';
$lang['tooltip_damage_reduction']='Schadensreduzierung';
//--Tooltip Parsing -- Translated by Jellow
$lang['tooltip_durability']='Haltbarkeit';
$lang['tooltip_unique']='Einzigartig';
$lang['tooltip_speed']='Tempo';
$lang['tooltip_poisoneffect']='^Bei jedem Schlag';

$lang['tooltip_preg_armor']='/^(\d+) Rüstung/';
$lang['tooltip_preg_durability']='/Haltbarkeit (\d+) \/ (\d+)/';
$lang['tooltip_preg_madeby']='/\<Hergestellt von (.+)\>/';
$lang['tooltip_preg_bags']='/(\d+) Platz/';
$lang['tooltip_preg_socketbonus']='/Sockelbonus: (.+)\n/';
$lang['tooltip_preg_classes']='/^(Klassen:) (.+)/';
$lang['tooltip_preg_races']='/^(Völker:) (.+)/';
$lang['tooltip_preg_charges']='/(\d+) Aufladungen/';
$lang['tooltip_preg_block']='/(\d+) (Blocken)/';
$lang['tooltip_preg_emptysocket']='/(Meta|Roter|Gelber|Blauer)(?:.?sockel)/i';
$lang['tooltip_preg_reinforcedarmor']='(Verstärkt \(\+\d+ Rüstung\))';
$lang['tooltip_preg_tempenchants']='/(.+\s\(\d+\s(min|sek)\.?\))\n/i';

$lang['tooltip_chance_hit']='Trefferchance'; // needs to find 'chance on|to hit:'
$lang['tooltip_reg_requires']='Benötigt';
$lang['tooltip_reg_onlyworksinside']='Wirkt nur in der Festung der Stürme';
$lang['tooltip_reg_conjureditems']='Conjured items disappear';
$lang['tooltip_reg_weaponorbulletdps']='^\(|^Verursacht ';

$lang['tooltip_armor_types']='Stoff|Leder|Schwere Rüstung|Platte';
$lang['tooltip_weapon_types']='Axt|Bogen|Armbrust|Dolch|Angel|Faustwaffe|Schußwaffe|Streitkolben|Waffenhand|Stangenwaffe|Stab|Schwert|Wurfwaffe|Zauberstab|In Schildhand geführt|Einhändig|Kugel|Pfeil';
$lang['tooltip_bind_types']='Seelengebunden|Wird beim Anlegen gebunden|Questgegenstand|Wird bei Benutzung gebunden|Dieser Gegenstand startet eine Quest';
$lang['tooltip_misc_types']='Finger|Hals|Rücken|Hemd|Schmuck|Wappenrock|Kopf|Brust|Beine|Füße';
$lang['tooltip_garbage']='<Zum Sockeln Shift-Rechtsklick>|<Zum Lesen rechtsklicken>|Verbleibende Abklingzeit';

//CP v2.1.1+ Gems info
//uses preg_match() to find the type and color of the gem
$lang['gem_preg_singlecolor'] = '/Am besten für einen (.+) Sockel geeignet/';
$lang['gem_preg_multicolor'] = '/Am besten für einen (.+) oder (.+) Sockel geeignet\./';
$lang['gem_preg_meta'] = '/Passt nur in einen Sockel der Kategorie Meta/';
$lang['gem_preg_prismatic'] = '/Am besten für einen roten, gelben oder blauen Sockel geeignet/';

//Gems color Array
$lang['gem_colors'] = array(
	'red' => 'roten',
	'blue' => 'blauen',
	'yellow' => 'gelben',
	'green' => 'grün',
	'orange' => 'orange',
	'purple' => 'violett',
	'prismatic' => 'prismatisch',
	'meta' => 'Meta' //verify translation
	);
// -- end tooltip parsing

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
$lang['CPver_err']='Die verwendete Version des CharacterProfiler, zur Speicherung der Daten für diesen Charakter ist älter als die für den Upload minimal zugelassene Version.<br />Bitte stellen Sie sicher, daß Sie mindestens v%1$s verwenden, und daß Sie diese Version verwendet haben, um die Daten für diesen Charakter zu speichern.';
$lang['GPver_err']='Die verwendete Version von GuildProfiler, zur Speicherung der Daten für diese Gilde ist älter als die für den Upload minimal zugelassene Version.<br />Bitte stellen Sie sicher, daß Sie mindestens v%1$s verwenden.';

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
$lang['installer_activated'] = 'Aktiviert';
$lang['installer_deactivated'] = 'Deaktiviert';
$lang['installer_installed'] = 'Installiert';
$lang['installer_upgrade_avail'] = 'Upgrade verfügbar';
$lang['installer_not_installed'] = 'nicht Installietr';

$lang['installer_turn_off'] = 'zum Ausschlaten klicken';
$lang['installer_turn_on'] = 'zum Anschalten klicken';
$lang['installer_click_uninstall'] = 'Klicken zum Deinstallieren';
$lang['installer_click_upgrade'] = 'Klicken um %1$s to %2$s zu Aktualisieren';
$lang['installer_click_install'] = 'Zum Installieren klicken';
$lang['installer_overwrite'] = 'alte Versionen überschreiben';
$lang['installer_replace_files'] = 'Du hast dein AddOn durch eine ältere Version überschreiben<br />Ersetze die Version durch eine Aktuelle';

$lang['installer_error'] = 'Fehler bei der Installation';
$lang['installer_invalid_type'] = 'Invalid install type';
$lang['installer_no_success_sql'] = 'Queries were not successfully added to the installer';
$lang['installer_no_class'] = 'The install definition file for %1$s did not contain a correct installation class';
$lang['installer_no_installdef'] = 'inc/install.def.php für %1$s wurde nicht gefunden';

$lang['installer_no_empty'] = 'Kann leeren AddOn Namen nicht installieren';
$lang['installer_fetch_failed'] = 'Failed to fetch addon data for %1$s';
$lang['installer_addon_exist'] = '%1$s already contains %2$s. You can go back and uninstall that addon first, or upgrade it, or install this addon with a different name';
$lang['installer_no_upgrade'] = '%1$s doesn\`t contain data to upgrade from';
$lang['installer_not_upgradable'] = '%1$s cannot upgrade %2$s since its basename %3$s isn\'t in the list of upgradable addons';
$lang['installer_no_uninstall'] = '%1$s doesn\'t contain an addon to uninstall';
$lang['installer_not_uninstallable'] = '%1$s contains an addon %2$s which must be uninstalled with that addons\' uninstaller';

// Password Stuff
$lang['password'] = 'Passwort';
$lang['changeadminpass'] = 'ändere Admin Passwort';
$lang['changeofficerpass'] = 'ändere Officer Passwort';
$lang['changeguildpass'] = 'ändere Guild Password';
$lang['old_pass'] = 'Altes Passwort';
$lang['new_pass'] = 'Neues Passwort';
$lang['new_pass_confirm'] = 'Neues Password [ confirm ]';
$lang['pass_old_error'] = 'alsches Passwort. Bitte gebe das richtige alte Passwort ein';
$lang['pass_submit_error'] = 'Submit error. The old password, the new password, and the confirmed new password need to be submitted';
$lang['pass_mismatch'] = 'Passworter stimmen nicht überein. Bitte gebe das gleiche Passwort in beiden Passwortfeldern ein';
$lang['pass_blank'] = 'Leere Passwortfelder sind nicht erlaubt. Bitte gib in beiden Feldern ein Passwort ein';
$lang['pass_isold'] = 'Passwort nicht geändert. Das Neue ist das gleiche Passwort wie das Alte';
$lang['pass_changed'] = 'Passwort geändertn. Dein neues Passwort ist [ %1$s ].<br /> Vergiß das Passwort nicht, da es nur Verschlüsselt gespeichetr ist';
$lang['auth_req'] = 'anmelden';

// Upload Rules
$lang['upload_rules_error'] = 'You cannot leave one of the fields empty when adding a rule';
$lang['upload_rules_help'] = 'The rules are divided into two blocks.<br />For each uploaded guild/char, first the top block is checked.<br />If the name@server matches one of these \'deny\' rules, it is rejected.<br />After that the second block is checked.<br />If the name@server matches one of these \'accept\' rules, it is accepted.<br />If it does not match any rule, it is rejected.';

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

$lang['pagebar_function'] = 'Aufgaben';
$lang['pagebar_rosterconf'] = 'Konfiguriere Roster';
$lang['pagebar_uploadrules'] = 'Upload Rules';
$lang['pagebar_changepass'] = 'Passwort Änderung';
$lang['pagebar_addoninst'] = 'Verwalte Addons';
$lang['pagebar_update'] = 'Upload Profil';
$lang['pagebar_rosterdiag'] = 'Roster Diagnose Seite';
$lang['pagebar_menuconf'] = 'Menu Konfiguration';
$lang['pagebar_configreset'] = 'Einstellungen zurücksetzen';

$lang['pagebar_addonconf'] = 'Addon Konfiguration';

$lang['roster_config_menu'] = 'Einstellungsmmenü';

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
$lang['admin']['main_conf'] = 'Haupteinstellungen|Roster\'s wichtigste Einstellungen<br />Enthält Roster URL, Bilder URL und andere grundlegende Einstellungen...';
$lang['admin']['defaults_conf'] = 'Standarteinstellungen|Setz die Einstellung auf Standart';
$lang['admin']['index_conf'] = 'Indexseite|Einstellen was auf der Hauptseite angezeigt werden soll';
$lang['admin']['menu_conf'] = 'Menüeinstellungen|Einstellen welche Elemente im Menü gezeigt werden sollen';
$lang['admin']['display_conf'] = 'Anzeigeneinstellungen|Verschiedene Anzeigeeinstellungen<br />css, javascript, motd, etc...';
$lang['admin']['realmstatus_conf'] = 'Serverstatus|Optionen für die Serverstatus<br /><br />Um es auszustellen, bitte bei Menüeinstellungen gucken';
$lang['admin']['data_links'] = 'Data Links|External links';
$lang['admin']['update_access'] = 'Update Zugriff|Optionale phpBB Authorisierung für update';

$lang['admin']['documentation'] = 'Dokumentation|WoWRoster Dokumentation über das wowroster.net-Wiki';

// main_conf
$lang['admin']['roster_dbver'] = "Roster Datenbank Version|Die Version der Datenbank";
$lang['admin']['version'] = "Roster Version|Aktuelle Version des Rosters";
//$lang['admin']['sqldebug'] = "SQL Debug Output|Gib MySQL Debug Ausgaben in HTML Kommentaren";
$lang['admin']['debug_mode'] = "Debug Modus|Zeigt die komplette Debugprotokollierung im Fehlerfalle an";
$lang['admin']['sql_window'] = "SQL Fenster|Zeigt die SQL Fehler in einem Fenster in der Fußzeile an";
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
$lang['admin']['local_cache']= "File System Cache|Use server local file system to cache some files to increase performance.";

// defaults_conf
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
$lang['admin']['logo'] = "URL für das Kopf-Logo|Die volle URL für das Logo<br />Oder &quot;img/&quot; vor den Namen setzen, um im /img-Verzeichnis des Rosters nachzugucken";
$lang['admin']['roster_bg'] = "URL für das Hintergrundbild|Die volle URL für den Haupthintergrund<br />Oder &quot;img/&quot; vor den Namen setzen, um im /img-Verzeichnis des Rosters nachzugucken";
$lang['admin']['motd_display_mode'] = "MOTD Anzeige Modus|Wie die MOTD (Message of the day) angezeigt werden soll:<br /><br />&quot;Text&quot; - Zeigt MOTD in rotem Text<br />&quot;Image&quot; - Zeigt MOTD als Bild (Benötigt GD!)";
$lang['admin']['signaturebackground'] = "img.php Hintergrund|Support für die (alten) Standard Signaturen";
$lang['admin']['processtime'] = "Seiten Gen. Zeit/DB Abfragen|Zeigt &quot;<i>Diese Seite wurde erzeugt in XXX Sekunden mit XX Datenbankabfragen</i>&quot; im Footer des Rosters an";

// data_links
$lang['admin']['profiler'] = "CharacterProfiler Downloadlink|URL um das CharacterProfiler-Addon herunterzuladen";
$lang['admin']['uploadapp'] = "UniUploader Downloadlink|URL um den UniUploader herunterzuladen";

// realmstatus_conf
$lang['admin']['rs_display'] = "Info Mode|&quot;full&quot; zeigt Status, Name, Population, und Servertyp<br />&quot;half&quot; zeigt nur den Status an";
$lang['admin']['rs_mode'] = "Display Mode|Wie der Status angezeigt werden soll:<br /><br />&quot;DIV Container&quot; - Zeigt den Realmstatus in einem DIV Container mit Text und Standardbildern<br />&quot;Image&quot; - Zeigt Realmstatus als ein Bild (BENÖTIGT GD!)";
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
$lang['admin']['gp_user_level'] = "Guild Data Level|Level required to process GuildProfiler Data";
$lang['admin']['cp_user_level'] = "Character Data Level|Level required to process CharacterProfiler Data";
$lang['admin']['lua_user_level'] = "Other LUA Data Level|Level required to process other LUA files' Data<br />This is for EVERY other lua file that can be uploaded to Roster";

// Character Display Settings
$lang['admin']['per_character_display'] = 'Charakterspezifische Anzeige-Einstellungen';

//Overlib for Allow/Disallow rules
$lang['guildname'] = 'Gildenname';
$lang['realmname']  = 'Realm name';
$lang['regionname'] = 'Region (i.e. US)';
$lang['charname'] = 'Character name';
