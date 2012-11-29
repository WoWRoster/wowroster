<?php
/**
 * WoWRoster.net WoWRoster
 *
 * deDE Locale File
 *
 * deDE translation by sphinx, SethDeBlade, wowroster.de
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
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

$lang['lualocation']='Wähle deine *.lua Datei für den Upload aus';

$lang['filelocation']='zu finden unter<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$lang['nodata']='Konnte Gilde <b>\'%1$s\'</b> auf dem Server <b>\'%2$s\'</b> nicht finden.<br />Du musst erst einmal die <a href="%3$s">Gildendaten hochladen</a> oder die <a href="%4$s">Konfiguration beenden</a>.<br /><br /><a href="http://www.wowroster.net/MediaWiki/Roster:Install" target="_blank">Klicke hier um zur Installationsanleitung zu gelangen</a>';
$lang['no_char_in_db']='Das Mitglied <b>\'%1$s\'</b> ist nicht in der Datenbank';
$lang['no_default_guild']='Es wurde bisher keine Standardgilde gesetzt. Bitte hier eine angeben.';
$lang['not_valid_anchor']='Der Anker(a=) Parameter ist ungültig oder falsch.';
$lang['nodefguild']='Es wurde bisher keine Standardgilde gesetzt. Bitte versichere dich, dass die <a href="%1$s">Konfiguration abgeschlossen wurde</a><br /><br /><a href="http://www.wowroster.net/MediaWiki/Roster:Install" target="_blank">Für Installationsanleitung hier klicken</a>';
$lang['nodata_title']='Keine Gildendaten vorhanden';

$lang['update_page']='Gildenmitglied aktualisieren';

$lang['guild_addonNotFound']='Keine Gilde gefunden. Ist das Addon WoWRoster-GuildProfiler korrekt installiert?';

$lang['ignored']='Ignoriert';
$lang['update_disabled']='Update.php Zugriff deaktiviert.';

$lang['nofileUploaded']='UniUploader hat keine oder die falschen Dateien hochgeladen.';
$lang['roster_upd_pwLabel']='Roster Update Passwort';
$lang['roster_upd_pw_help']='Manche .lua-Dateien benötigen vielleicht ein Passwort';


$lang['roster_error'] = 'Roster Fehler';
$lang['sql_queries'] = 'SQL Abfragen';
$lang['invalid_char_module'] = 'Ungültige Zeichen im Modulnamen';
$lang['module_not_exist'] = 'Das Modul [%1$s] existiert nicht';

$lang['addon_error'] = 'Addon Fehler';
$lang['specify_addon'] = 'Du musste einen Addonnamen angeben!';
$lang['addon_not_exist'] = '<b>Das Addon [%1$s] existiert nicht!</b>';
$lang['addon_disabled'] = '<b>Das Addon [%1$s] wurde deaktiviert</b>';
$lang['addon_no_access'] = '<b>Ungenügende Rechte um auf [%1$s] zuzugreifen.</b>';
$lang['addon_upgrade_notice'] = '<b>Das Addon [%1$s] wurde deaktiviert, da es aktualisiert werden muss</b>';
$lang['addon_not_installed'] = '<b>Das Addon [%1$s] wurde bis jetzt noch nicht installiert</b>';
$lang['addon_no_config'] = '<b>Das Addon [%1$s] wurde noch nicht konfiguriert</b>';

$lang['char_error'] = 'Charakterfehler';
$lang['specify_char'] = 'Charakter wurde nicht spezifiziert';
$lang['no_char_id'] = 'Entschuldige, keine Charakterdaten für member_id [ %1$s ]';
$lang['no_char_name'] = 'Sorry keine Charakterdaten für <strong>%1$s</strong> von <strong>%2$s</strong>';

$lang['roster_cp'] = 'Roster Konfigurationsbereich';
$lang['roster_cp_ab'] = 'RosterCP';
$lang['roster_cp_not_exist'] = 'Seite [%1$s] existiert nicht';
$lang['roster_cp_invalid'] = 'Ungültige Seite angegeben oder ungültige Berechtigung um diese Seite aufzurufen';
$lang['access_level'] = 'Zugriffslevel';

$lang['parsing_files'] = 'Analysiere Dateien';
$lang['parsed_time'] = '%1$s analysiert in %2$s Sekunden';
$lang['error_parsed_time'] = 'Fehler während Analyse von %1$s nach %2$s Sekunden';
$lang['upload_not_accept'] = 'Es ist nicht erlaubt %1$s hochzuladen';

$lang['processing_files'] = 'Verarbeite Dateien';
$lang['error_addon'] = 'Es gab einen Fehler im Addon %1$s in der Methode %2$s';
$lang['addon_messages'] = 'Addon Nachrichten:';

$lang['not_accepted'] = '%1$s %2$s @ %3$s-%4$s nicht akzeptiert. Die Daten stimmen nicht mit den Upload Regeln überein.';

$lang['not_updating'] = 'Keine Aktualisierung %1$s für [%2$s] - %3$s';
$lang['not_update_guild'] = 'Keine Aktualisierung der Gildenliste für %1$s@%3$s-%2$s';
$lang['not_update_guild_time'] = 'Keine Aktualisierung der Gildenliste für %1$s. Die hochgeladenen Gildendaten wurden am %2$s gescannt, die gespeichert Gildendaten sind vom %3$s';
$lang['not_update_char_time'] = 'Keine Aktualisierung des Charakters %1$s. Profildaten wurde am %2$s gescannt, die gespeicherten Profildaten sind vom %3$s';
$lang['no_members'] = 'Daten enthalten keine Gildemmitglieder';
$lang['upload_data'] = 'Aktualisiere %1$s Daten für [%2$s@%4$s-%3$s]';
$lang['realm_ignored'] = 'Realm: %1$s nicht überprüft';
$lang['guild_realm_ignored'] = 'Gilde: %1$s @ Realm: %2$s nicht überprüft';
$lang['update_members'] = 'Aktualisiere Mitglieder';
$lang['update_errors'] = 'Aktualisierungsfehler';
$lang['update_log'] = 'Update Log';
$lang['select_files'] = 'Bitte Dateien wählen';
$lang['save_error_log'] = 'Speichere Fehler Log';
$lang['save_update_log'] = 'Speichere Update Log';

$lang['new_version_available'] = 'Eine neue Version von %1$s ist verfügbar v%2$s<br />Released: %3$s<br />Download <a href="%4$s" target="_blank">HIER</a>';

$lang['remove_install_files'] = 'Entferne Installationsdateien';
$lang['remove_install_files_text'] = 'Bitte entferne <span class="redB">install.php</span> im Roster Verzeichnis';

$lang['upgrade_wowroster'] = 'Upgrade WoWRoster';
$lang['upgrade'] = 'Upgrade';
$lang['select_version'] = 'Wähle Version';
$lang['no_upgrade'] = 'Du hast den Roster bereits aktualisiert<br />oder du hast eine aktuellere Version als dieses Upgrade<br /><a class="input" href="%1$s">Zurück zum WoWRoster</a>';
$lang['upgrade_complete'] = 'Deine WoWRoster-Installation wurde erfolgreich aktualisiert<br /><a class="input" href="%1$s">Zurück zum WoWRoster</a>';

// Menu buttons
$lang['menu_header_scope_panel'] = '%s Menü';

$lang['menu_totals'] = 'Gesamt: %1$s (+%2$s Alts)';
$lang['menu_totals_level'] = ' min. L%1$s';

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
<li>Lade den <a href="%3$s" target="_blank">WoWRoster-Profiler</a> herunter</li>
<li>Extrahiere die Zip-Datei in ein eigenes Verzeichnis unter C:\Programme\World of Warcraft\Interface\Addons\wowroster\ (oder an dem Ort wo dein World of Warcraft installiert ist)</li>
<li>Starte WoW</li>
<li>Öffne einmal dein Bankschliessfach, deine Rucksäcke, deine Berufsseiten und deine Charakter-Übersicht</li>
<li>Logge aus oder beende WoW (Siehe oben, falls das der UniUploader automatisch erledigen soll.)</li>
<li>Gehe zur <a href="%4$s">Update-Seite</a></li>
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

$lang['roster_credits']='WoWRoster Home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a>';
$lang['bliz_notice']='World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.';


$lang['timeformat'] = '%d.%m. %k:%i'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$lang['phptimeformat'] = 'd.m. G:i';  // PHP date() Time format (example - 'M D jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


/**
 * Realmstatus Localizations
 */
$lang['rs'] = array(
	'ERROR' => 'Fehler',
	'NOSTATUS' => 'Kein Status',
	'UNKNOWN' => 'Unbekannt',
	'RPPVP' => 'RP-PvP',
	'PVE' => 'Normal',
	'PVP' => 'PvP',
	'RP' => 'RP',
	'OFFLINE' => 'Offline',
	'LOW' => 'Wenig',
	'MEDIUM' => 'Mittel',
	'HIGH' => 'Hoch',
	'MAX' => 'Max',
	'RECOMMENDED' => 'Empfohlen',
	'FULL' => 'Voll'
);


//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$lang['guildless']='Gildenlos';
$lang['util']='Einstellungen';
$lang['char']='Charakter';
$lang['equipment']='Equipment';
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
$lang['rank']='Rang';
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
$lang['basename']='Basisname';
$lang['scope']='Bereich';
$lang['tag']='Tag';
$lang['daily']='Täglich';
$lang['user'] = 'Benutzer';

// Item Quality
$lang['quality']='Qualität';
$lang['poor']='Schlecht';
$lang['common']='Gewöhnlich';
$lang['uncommon']='Ungewöhnlich';
$lang['rare']='Rar';
$lang['epic']='Episch';
$lang['legendary']='Legendär';
$lang['artifact']='Artefakt';
$lang['heirloom']='Erbstück';

//start search engine
$lang['search']='Suche';
$lang['search_roster']='Suche im Roster';
$lang['search_onlyin']='Suche nur in diesen AddOns';
$lang['search_advancedoptionsfor']='Erweiterte Options für';
$lang['search_results']='Suche Ergebnisse für';
$lang['search_results_from']='Hier sind deine Suchergebnisse';
$lang['search_nomatches']='Sorry, die Suche hat keine Treffer gefunden';
$lang['search_didnotfind']='Du hast nicht gefunden, was du gesucht hast?? Versuch\'s hier!';
$lang['search_for']='Suche Roster';
$lang['search_next_matches'] = 'Nächste Ergebnisse für';
$lang['search_previous_matches'] = 'Vorherige Ergebnisse für';
$lang['search_results_count'] = 'Ergebnisse';
$lang['submited_author'] = 'Verfasst von:';
$lang['submited_date'] = 'Am';
//end search engine
$lang['update']='Update';
$lang['credit']='Credits';
$lang['who_made']='Wer entwickelte und programmierte den WoWRoster';
$lang['members']='Mitglieder';
$lang['member_profiles']='Mitglieder Profile';
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
$lang['completedsteps'] = 'Abgeschlossene Stufe';
$lang['currentstep'] = 'Aktuelle Stufe';
$lang['uncompletedsteps'] = 'Nicht abgeschlosse Stufe';
$lang['key'] = 'Schlüssel';
$lang['keyring'] = 'Schlüsselring';
$lang['timeplayed'] = 'Zeit gespielt';
$lang['timelevelplayed'] = 'Zeit gespielt Level'; // muss so kurz sein wegen der Anzeige
$lang['Addon'] = 'Addons';
$lang['advancedstats'] = 'Erweiterte Eigenschaften';
$lang['crit'] = 'Krit.';
$lang['dodge'] = 'Ausw.';
$lang['parry'] = 'Parieren';
$lang['block'] = 'Blocken';
$lang['realm'] = 'Realm';
$lang['region'] = 'Region';
$lang['server'] = 'Server';
$lang['faction'] = 'Fraktion';
$lang['page'] = 'Seite';
$lang['general'] = 'Allgemein';
$lang['prev'] = 'Zurück';
$lang['next'] = 'Vor';
$lang['memberlog'] = 'Mitglieder Log';
$lang['removed'] = 'Entfernt';
$lang['added'] = 'Zugefügt';
$lang['add'] = 'Hinzufügen';
$lang['delete'] = 'Löschen';
$lang['updated'] = 'aktualisiert';
$lang['no_info'] = 'Keine Information';
$lang['info'] = 'Info';
$lang['url'] = 'URL';
$lang['none']='Keine';
$lang['kills']='Kills';
$lang['allow'] = 'Erlauben';
$lang['disallow'] = 'Verbieten';
$lang['locale'] = 'Ort';
$lang['language'] = 'Sprache';
$lang['default'] = 'Standart';
$lang['proceed'] = 'weiter';
$lang['submit'] = 'senden';
$lang['strength']='Stärke';
$lang['agility']='Beweglichkeit';
$lang['stamina']='Ausdauer';
$lang['intellect']='Intelligenz';
$lang['spirit']='Willenskraft';

$lang['rosterdiag'] = 'Roster Diagnose Seite';
$lang['updates_available'] = 'Update verfügbar!';
$lang['updates_available_message'] = 'Als Admin anmelden um die Updatedateien herunterzuladen';
$lang['download_update_pkg'] = 'Download Update Paket';
$lang['download_update'] = 'Download Update';
$lang['zip_archive'] = '.zip Archiv';
$lang['targz_archive'] = '.tar.gz Archiv';

$lang['difficulty'] = 'Schwierigkeit';
$lang['recipe_4'] = 'optimal';
$lang['recipe_3'] = 'mittel';
$lang['recipe_2'] = 'leicht';
$lang['recipe_1'] = 'belanglos';
$lang['roster_config'] = 'Roster Einstellung';

$lang['search_names'] = 'Nach Namen suchen';
$lang['search_items'] = 'Nach Gegenständen suchen';
$lang['search_tooltips'] = 'Suche im Tooltip';

// Talent Builds
$lang['talent_build_0'] = 'Aktiv';
$lang['talent_build_1'] = 'Inaktiv';

// Char Scope
$lang['char_level_race_class'] = 'Level %1$s %2$s %3$s';
$lang['char_guildline'] = '%1$s der %2$s';

// Login
$lang['welcome_user'] = 'Welcome, %1$s';
$lang['login'] = 'Anmelden';
$lang['logout'] = 'Abmelden';
$lang['logged_in'] = 'Angemeldet';
$lang['logged_out'] = 'Abgemeldet';
$lang['login_invalid'] = 'Ungültiges Passwort';
$lang['login_fail'] = 'Fehler bei dem Abrufen der Passwort Informationen';
$lang['login_inactive'] = 'Your account is not active or is not approved by the admin. Only "Public" areas can be accessed.';
$lang['active'] = 'Aktiv';
$lang['inactive'] = 'Inaktiv';

//this needs to be exact as it is the wording in the db
$lang['professions']='Berufe';
$lang['secondary']='Sekundäre Fertigkeiten';
$lang['Blacksmithing']='Schmiedekunst';
$lang['Mining']='Bergbau';
$lang['Herbalism']='Kräuterkunde';
$lang['Alchemy']='Alchemie';
$lang['Archaeology']='Archäologie';
$lang['Leatherworking']='Lederverarbeitung';
$lang['Jewelcrafting']='Juwelenschleifen';
$lang['Skinning']='Kürschnerei';
$lang['Tailoring']='Schneiderei';
$lang['Enchanting']='Verzauberkunst';
$lang['Engineering']='Ingenieurskunst';
$lang['Inscription']='Inschriftenkunde';
$lang['Runeforging']='Runen schmieden';
$lang['Cooking']='Kochkunst';
$lang['Fishing']='Angeln';
$lang['First Aid']='Erste Hilfe';
$lang['Poisons']='Gifte';
$lang['backpack']='Rucksack';
$lang['PvPRankNone']='none';

// Uses preg_match() to find required level in recipe tooltip
$lang['requires_level'] = '/Benötigt Stufe ([\d]+)/';

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
	$lang['Archaeology'],
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
	$lang['Archaeology']=>'trade_archaeology',
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
	'Hexenmeister'=>'ability_mount_nightmarehorse',
	'Todesritter'=>'spell_deathknight_summondeathcharger',
	'Pandaren'=>'ability_mount_ridinghorse',
// Female variation
	// 'Nachtelf'=>'ability_mount_whitetiger',
	// 'Mensch'=>'ability_mount_ridinghorse',
	// 'Zwerg'=>'ability_mount_mountainram',
	// 'Gnom'=>'ability_mount_mechastrider',
	// 'Untoter'=>'ability_mount_undeadhorse',
	// 'Troll'=>'ability_mount_raptor',
	// 'Tauren'=>'ability_mount_kodo_03',
	// 'Orc'=>'ability_mount_blackdirewolf',
	// 'Blutelf' => 'ability_mount_cockatricemount',
	// 'Draenei' => 'ability_mount_ridingelekk',
	// 'Paladin'=>'ability_mount_dreadsteed',
	'Hexenmeisterin'=>'ability_mount_nightmarehorse',
	// 'Todesritter'=>'spell_deathknight_summondeathcharger',
	// 'Pandaren'=>'ability_mount_ridinghorse',
);

$lang['ts_flyingIcon'] = array(
	'Horde'=>'ability_mount_wyvern_01',
	'Allianz'=>'ability_mount_gryphon_01',
	'Druide'=>'ability_druid_flightform',
	'Todesritter'=>'ability_mount_dreadsteed',
// Female variation
	'Druidin'=>'ability_druid_flightform',
//	'Todesritter'=>'ability_mount_dreadsteed'
);

// Class Icons-Array
$lang['class_iconArray'] = array (
	'Todesritter'=>'deathknight_icon',
	'Druide'=>'druid_icon',
	'Jäger'=>'hunter_icon',
	'Magier'=>'mage_icon',
	'Paladin'=>'paladin_icon',
	'Priester'=>'priest_icon',
	'Schurke'=>'rogue_icon',
	'Schamane'=>'shaman_icon',
	'Hexenmeister'=>'warlock_icon',
	'Kriegerin'=>'warrior_icon',
	'Mönch'=>'monk_icon',
// Female variation
	// 'Todesritter'=>'deathknight_icon',
	'Druidin'=>'druid_icon',
	'Jägerin'=>'hunter_icon',
	'Magierin'=>'mage_icon',
	// 'Paladin'=>'paladin_icon',
	'Priesterin'=>'priest_icon',
	'Schurkin'=>'rogue_icon',
	'Schamanin'=>'shaman_icon',
	'Hexenmeisterin'=>'warlock_icon',
	'Krieger'=>'warrior_icon',
	// 'Mönch'=>'monk_icon',
);

// Class Color-Array
$lang['class_colorArray'] = array(
	'Todesritter'=>'C41F3B',
	'Druide' => 'FF7D0A',
	'Jäger' => 'ABD473',
	'Magier' => '69CCF0',
	'Paladin' => 'F58CBA',
	'Priester' => 'FFFFFF',
	'Schurke' => 'FFF569',
	'Schamane' => '2459FF',
	'Hexenmeister' => '9482C9',
	'Krieger' => 'C79C6E',
	'Mönch' => '00C77b',
// Female variation
	// 'Todesritter'=>'C41F3B',
	'Druidin' => 'FF7D0A',
	'Jägerin' => 'ABD473',
	'Magierin' => '69CCF0',
	// 'Paladin' => 'F58CBA',
	'Priesterin' => 'FFFFFF',
	'Schurkin' => 'FFF569',
	'Schamanin' => '2459FF',
	'Hexenmeisterin' => '9482C9',
	'Kriegerin' => 'C79C6E',
	// 'Mönch' => '00C77b'
);

// Class To English Translation
$lang['class_to_en'] = array(
	'Todesritter'=>'Death Knight',
	'Druide' => 'Druid',
	'Jäger' => 'Hunter',
	'Magier' => 'Mage',
	'Paladin' => 'Paladin',
	'Priester' => 'Priest',
	'Schurke' => 'Rogue',
	'Schamane' => 'Shaman',
	'Hexenmeister' => 'Warlock',
	'Krieger' => 'Warrior',
	'Monk' => 'Monk',
// Female variation
	// 'Todesritter'=>'Death Knight',
	'Druidin' => 'Druid',
	'Jägerin' => 'Hunter',
	'Magierin' => 'Mage',
	// 'Paladin' => 'Paladin',
	'Priesterin' => 'Priest',
	'Schurkin' => 'Rogue',
	'Schamanin' => 'Shaman',
	'Hexenmeisterin' => 'Warlock',
	'Kriegerin' => 'Warrior',
	'Monk' => 'Monk'
);

// Class to game-internal ID
$lang['class_to_id'] = array(
	'Krieger' => 1,
	'Paladin' => 2,
	'Jäger' => 3,
	'Schurke' => 4,
	'Priester' => 5,
	'Todesritter'=>6,
	'Schamane' => 7,
	'Magier' => 8,
	'Hexenmeister' => 9,
	'Mönch' => 10,
	'Druide' => 11,
// Female variation
	'Kriegerin' => 1,
	// 'Paladin' => 2,
	'Jägerin' => 3,
	'Schurkin' => 4,
	'Priesterin' => 5,
	// 'Todesritter'=>6,
	'Schamanin' => 7,
	'Magierin' => 8,
	'Hexenmeisterin' => 9,
	// 'Mönch' => 10,
	'Druidin' => 11,
);

// Game-internal ID to class
$lang['id_to_class'] = array(
	1 => 'Krieger',
	2 => 'Paladin',
	3 => 'Jäger',
	4 => 'Schurke',
	5 => 'Priester',
	6 => 'Todesritter',
	7 => 'Schamane',
	8 => 'Magier',
	9 => 'Hexenmeister',
	10 => 'Mönch',
	11 => 'Druide'
);

// Race to English Translation
$lang['race_to_en'] = array(
	'Blutelf'  => 'Blood Elf',
	'Draenei'  => 'Draenei',
	'Nachtelf' => 'Night Elf',
	'Zwerg'    => 'Dwarf',
	'Gnom'     => 'Gnome',
	'Mensch'   => 'Human',
	'Orc'      => 'Orc',
	'Untoter'  => 'Undead',
	'Troll'    => 'Troll',
	'Tauren'   => 'Tauren',
	'Worgen'   => 'Worgen',
	'Goblin'   => 'Goblin',
	'Pandaren'	=> 'Pandaren',
// Female variation
	// 'Blutelf'  => 'Blood Elf',
	// 'Draenei'  => 'Draenei',
	// 'Nachtelf' => 'Night Elf',
	// 'Zwerg'    => 'Dwarf',
	// 'Gnom'     => 'Gnome',
	// 'Mensch'   => 'Human',
	// 'Orc'      => 'Orc',
	// 'Untoter'  => 'Undead',
	// 'Troll'    => 'Troll',
	// 'Tauren'   => 'Tauren',
	// 'Worgen'   => 'Worgen',
	// 'Goblin'   => 'Goblin',
);

$lang['race_to_id'] = array(
	'Mensch'   => 1,
	'Orc'      => 2,
	'Zwerg'    => 3,
	'Nachtelf' => 4,
	'Untoter'  => 5,
	'Tauren'   => 6,
	'Gnom'     => 7,
	'Troll'    => 8,
	'Blutelf'  => 10,
	'Draenei'  => 11,
	'Worgen'   => 22,
	'Goblin'   => 9,
	'Pandaren'	=> 25,
	'Pandaren'	=> 26,
// Female variation
	// 'Mensch'   => 1,
	// 'Orc'      => 2,
	// 'Zwerg'    => 3,
	// 'Nachtelf' => 4,
	// 'Untoter'  => 5,
	// 'Tauren'   => 6,
	// 'Gnom'     => 7,
	// 'Troll'    => 8,
	// 'Blutelf'  => 10,
	// 'Draenei'  => 11,
	// 'Worgen'   => 22,
	// 'Goblin'   => 9,
	// 'Pandaren'	=> 25,
	// 'Pandaren'	=> 26,
);

$lang['id_to_race'] = array(
	1 => 'Mensch',
	2 => 'Orc',
	3 => 'Zwerg',
	4 => 'Nachtelf',
	5 => 'Untoter',
	6 => 'Tauren',
	7 => 'Gnom',
	8 => 'Troll',
	10 => 'Blutelf',
	11 => 'Draenei',
	22 => 'Worgen',
	9 => 'Goblin',
	25 => 'Pandaren',
	26 => 'Pandaren',
);

$lang['hslist']=' Ehren Statistiken';
$lang['hslist1']='Höchster Lebenszeit Rang';
$lang['hslist2']='Höchsten Lebenszeit ES';
$lang['hslist3']='Die meisten Ehrenpunkte';
$lang['hslist4']='Die meisten Arenapunkte';

$lang['Death Knight']='Todesritter';
$lang['Druid']='Druide';
$lang['Hunter']='Jäger';
$lang['Mage']='Magier';
$lang['Monk']='Monk';
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
$lang['highestrank']='Höchster Rang';
$lang['arena']='Arena';

$lang['when']='Wann';
$lang['guild']='Gilde';
$lang['guilds']='Gilden';
$lang['result']='Ergebnis';
$lang['zone']='Zone';
$lang['subzone']='Subzone';
$lang['yes']='Ja';
$lang['no']='Nein';
$lang['win']='Sieg';
$lang['loss']='Niederlage';
$lang['unknown']='Unbekannt';

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

// Factions to EN id
$lang['faction_to_id'] = array(
	'Allianz' => 'alliance',
	'Streitkräfte der Allianz' => 'allianceforces',
	'Vorposten der Allianz' => 'alliancevanguard',
	'Classic' => 'classic',
	'Other' => 'other',
	'Outland' => 'outland',
	'Shattrath' => 'shattrathcity',
	'Dampfdruckkartell' => 'steamwheedlecartel',
	'The Burning Crusade' => 'theburningcrusade',
	'Wrath of the Lich King' => 'wrathofthelitchking',
	'Sholazarbecken' => 'sholazarbasin',
	'Expedition der Horde' => 'horde',
	'Horde' => 'horde',
	'Streitkräfte der Horde' => 'horde',
	'Cataclysm' => 'cataclysm',
	'Guild' => 'guild',
	'Reputation' => 'reputation',
	'Mists of Pandaria' => 'mists of pandaria',
	'The Anglers' => 'the anglers',
	'The Tillers' => 'the tillers',
);


// Quests page external links (on character quests page)
// $lang['questlinks'][][] = array(
// 		'name'=> 'Name',  // This is the name displayed on the quests page
// 		'url' => 'url',   // This is the URL used for the quest lookup (must be sprintf() compatible)

$lang['questlinks'][] = array(
	'name'=>'WoWHead',
	'url'=>'http://de.wowhead.com/?quest=%1$s'
);

$lang['questlinks'][] = array(
	'name'=>'WoW-Handwerk',
	'url'=>'http://wowhandwerk.onlinewelten.com/quest.php?id=%1$s'
);

$lang['questlinks'][] = array(
	'name'=>'Buffed DE',
	'url'=>'http://wow.buffed.de/?q=%1$s'
);

$lang['questlinks'][] = array(
	'name'=>'Thottbot',
	'url'=>'http://thottbot.com/q%1$s'
);

// Items external link
// Add as many item links as you need
// Just make sure their names are unique
// uses the 'item_id' for data
$lang['itemlink'] = 'Item Links';
$lang['itemlinks']['WoW-Handwerk'] = 'http://wowhandwerk.onlinewelten.com/item.php?id=';
$lang['itemlinks']['Buffed DE'] = 'http://www.buffed.de/?i=';
$lang['itemlinks']['Thottbot'] = 'http://www.thottbot.com/i';
$lang['itemlinks']['WoWHead'] = 'http://de.wowhead.com/?item=';

// WoW Data Site Search
// Add as many item links as you need
// Just make sure their names are unique
// use these locales for data searches
$lang['data_search'] = 'WoW Daten Homepage Suche';
$lang['data_links']['WoW-Handwerk'] = 'http://www.wow-handwerk.de/search.php?quicksearch=';
$lang['data_links']['buffed.de'] = 'http://www.buffed.de/?f=';
$lang['data_links']['Thottbot'] = 'http://www.thottbot.com/index.cgi?s=';
$lang['data_links']['WoWHead'] = 'http://de.wowhead.com/?search=';

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
$lang['tooltip_accountbound']='Accountgebunden';
$lang['tooltip_boe']='Wird beim Anlegen gebunden';
$lang['tooltip_equip']='Anlegen:';
$lang['tooltip_equip_restores']='Anlegen: Stellt';
$lang['tooltip_equip_when']='Anlegen: Erhöht';
$lang['tooltip_chance']='Gewährt';
$lang['tooltip_enchant']='Verzauberung';
$lang['tooltip_random_enchant']='Zufällige Verzauberung';
$lang['tooltip_set']='Set:';
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

// MOP 5.1
$lang['tooltip_upgrade']='Upgrade Level: %1$s/%2$s';
$lang['tooltip_preg_upgrade']='/Upgrade Level: (\d+)\/(\d+)/';
$lang['tooltip_preg_enchant']='/Enchanted: (.+)/';
$lang['tooltip_ienchant']='Enchanted: %1$s';
// MOP 5.0
$lang['tooltip_preg_item_season']='/Season ([0-9, ]+)/';
// php 5.3 changes
$lang['tooltip_preg_soulbound']='/Seelengebunden/';
$lang['tooltip_preg_dps']='/(\d+) Schaden pro Sekunde/';
$lang['tooltip_preg_item_equip']='/Anlegen: (.+)/';
$lang['tooltip_preg_item_set']='/Set: (.+)/';
$lang['tooltip_preg_use']='/Benutzen: (.+)/';
$lang['tooltip_preg_chance']='/Gewährt (.+)/';
$lang['tooltip_preg_chance_hit']='/Chance ^(to|on) hit: (.+)/';
$lang['tooltip_preg_heroic']='/Heroisch/';
$lang['tooltip_preg_lfr']='/Raid Finder/';
$lang['tooltip_garbage1']='/\<Zum Sockeln Shift-Rechtsklick\>/';
$lang['tooltip_garbage2']='/\<Zum Lesen rechtsklicken\>/';
$lang['tooltip_garbage3']='/Dauer (.+)/';
$lang['tooltip_garbage4']='/Cooldown remaining (.+)/';
$lang['tooltip_garbage5']='/\<Zum Öffnen rechtsklicken\>/';
$lang['tooltip_garbage6']='/Equipment Sets: (.+)/';
$lang['tooltip_garbage7'] = '/You may sell this item to a vendor within (.+) for a full refund./';
//^(Red|Yellow|Blue|Meta)
$lang['tooltip_preg_weapon_types']='/^(Pfeil|Axt|Bogen|Kugel|Armbrust|Dolch|Angel|Faustwaffe|Schußwaffe|Götze|Streitkolben|Waffenhand|Nebenhand|Stangenwaffe|Stab|Schwert|Wurfwaffe|Zauberstab|Fernkampf|Einhand|Zweihand|Relikt)/';
$lang['tooltip_preg_speed']='/Geschwindigkeit/';

$lang['tooltip_preg_armor']='/^([0-9, ]+) Rüstung/';
$lang['tooltip_preg_durability']='/Haltbarkeit(|:) (\d+) \/ (\d+)/';
$lang['tooltip_preg_madeby']='/\<Hergestellt von (.+)\>/';
$lang['tooltip_preg_bags']='/(\d+) Platz/';
$lang['tooltip_preg_socketbonus']='/Sockelbonus: (.+)/';
$lang['tooltip_preg_classes']='/^(Klassen:) (.+)/';
$lang['tooltip_preg_races']='/^(Völker:) (.+)/';
$lang['tooltip_preg_charges']='/(\d+) Aufladungen/';
$lang['tooltip_preg_block']='/([0-9, ]+) (Blocken)/';
$lang['tooltip_preg_emptysocket']='/(Meta|Roter|Gelber|Blauer|Prismatischer)(?:.?sockel)/i';
$lang['tooltip_preg_reinforcedarmor']='/(Verstärkt \(\+\d+ Rüstung\))/';
$lang['tooltip_preg_tempenchants']='/(.+\s\(\d+\s(min|sek)\.?\))\n/i';
$lang['tooltip_preg_meta_requires']='/Benötigt.*?Edelstein?/';
$lang['tooltip_preg_meta_requires_min']='/Benötigt mindestens (\d) Edelstein.*?der Kategorie (\S+)/';
$lang['tooltip_preg_meta_requires_more']='/Benötigt mehr Edelsteine der Kategorie (\S+) als Edelsteine der Kategorie (\S+)/';
$lang['tooltip_preg_item_level']='/Gegenstandsstufe (\d+)/';
$lang['tooltip_feral_ap']='Erhöht die Angriffskraft um';
$lang['tooltip_source']='Quelle';
$lang['tooltip_boss']='Boss';
$lang['tooltip_droprate']='Droprate';
$lang['tooltip_reforged']='Umgeschmiedet';
$lang['tooltip_transmogc'] = '/Umgeschmiedet zu: (.+)/';
$lang['tooltip_transmogb'] = 'Umgeschmiedet zu: ';
$lang['tooltip_transmoga'] = "Umgeschmiedet zu:\n";

$lang['tooltip_chance_hit']='Trefferchance'; // needs to find 'chance on|to hit:'
$lang['tooltip_reg_requires']='Benötigt';
$lang['tooltip_reg_onlyworksinside']='Wirkt nur in der Festung der Stürme';
$lang['tooltip_reg_conjureditems']='Herbeigezauberte Gegenstände verschwinden';
$lang['tooltip_reg_weaponorbulletdps']='^\(|^Verursacht ';

$lang['tooltip_armor_types']='Stoff|Leder|Schwere Rüstung|Platte';
$lang['tooltip_weapon_types']='Axt|Bogen|Armbrust|Dolch|Angel|Faustwaffe|Schußwaffe|Schusswaffe|Streitkolben|Waffenhand|Stangenwaffe|Stab|Schwert|Wurfwaffe|Zauberstab|In Schildhand geführt|Einhändig|Kugel|Pfeil';
$lang['tooltip_bind_types']='Seelengebunden|Wird beim Anlegen gebunden|Questgegenstand|Wird bei Benutzung gebunden|Dieser Gegenstand startet eine Quest|Wird beim Aufheben gebunden|Accountgebunden';
$lang['tooltip_misc_types']='Finger|Hals|Rücken|Hemd|Schmuck|Wappenrock|Kopf|Brust|Beine|Füße';
$lang['tooltip_garbage']='<Zum Sockeln Shift-Rechtsklick>|<Zum Lesen rechtsklicken>|Dauer|Verbleibende Abklingzeit|<Zum Öffnen rechtsklicken>';

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

$lang['gem_colors_to_en'] = array(
	'rot' => 'red',
	'blau' => 'blue',
	'gelb' => 'yellow',
	'grün' => 'green',
	'orange' => 'orange',
	'violett' => 'purple',
	'prismatisch' => 'prismatic',
	'meta' => 'meta' //verify translation, meta is correct, if errors occur try Meta with capital M
	);

$lang['socket_colors_to_en'] = array(
	'roter' => 'red',
	'blauer' => 'blue',
	'gelber' => 'yellow',
	'meta' => 'meta',
	'prismatischen' => 'prismatic',
	);
// -- end tooltip parsing



// Warlock pet names for icon displaying
$lang['Imp']='Wichtel';
$lang['Voidwalker']='Leerwandler';
$lang['Succubus']='Sukkubus';
$lang['Felhunter']='Teufelsjäger';
$lang['Infernal']='Höllenbestie';
$lang['Felguard']='Teufelswache';

// Max experiance for exp bar on char page
$lang['max_exp']='Max XP';

// Error messages
$lang['CPver_err']='Die zur Speicherung der Daten für diesen Charakter verwendete Version des WoWRoster-Profiler ist älter als die für den Upload minimal zugelassene Version.<br />Bitte stelle sicher, daß Du mindestens v%1$s verwendest, und daß Du diese Version verwendet hast, um die Daten für diesen Charakter zu speichern.';
$lang['GPver_err']='Die zur Speicherung der Daten für diese Gilde verwendete Version von WoWRoster-GuildProfiler ist älter als die für den Upload minimal zugelassene Version.<br />Bitte stelle sicher, daß Du mindestens v%1$s verwendest.';

// Menu titles
$lang['menu_upprofile']='Update Profile|Aktualisiere dein Profil auf dieser Seite';
$lang['menu_search']='Suche|Suche Gegenstände oder Rezepte in der Datenbank';
$lang['menu_roster_cp']='RosterCP|Roster Konfigurationsbereich';
$lang['menupanel_util']  = 'Einstellungen';
$lang['menupanel_realm'] = 'Realm';
$lang['menupanel_guild'] = 'Gilde';
$lang['menupanel_char']  = 'Charakter';
$lang['menupanel_user'] = 'UserCP|Benutzer Konfigurationsbereich';

$lang['menuconf_sectionselect']='Wähle Auswahl';
$lang['menuconf_section']='Sektion';
$lang['menuconf_changes_saved']='Veränderungen von %1$s gespeichert';
$lang['menuconf_no_changes_saved']='Keine Veränderungen gespeichert';
$lang['menuconf_add_button']='Button hinzufügen';
$lang['menuconf_drag_delete']='Ziehe hierher zum Löschen';
$lang['menuconf_addon_inactive']='Addon ist inaktiv';
$lang['menuconf_unused_buttons']='Ungenutze Buttons';

$lang['default_page_set']='Die Standardseite wurde auf [%1$s] gesetzt.';

$lang['installer_install_0']='Installation von %1$s erfolgreich';
$lang['installer_install_1']='Installation von %1$s fehlgeschlagen, aber Wiederherstellung erfolgreich';
$lang['installer_install_2']='Installation ovonf %1$s fehlgeschlagen und Wiederherstellung ebenfalls fehlgeschlagen';
$lang['installer_uninstall_0']='Deinstallation von %1$s erfolgreich';
$lang['installer_uninstall_1']='Deinstallation von %1$s fehlgeschlagen, aber Wiederherstellung erfolgreich';
$lang['installer_uninstall_2']='Deinstallation von %1$s fehlgeschlagen und Wiederherstellung ebenfalls fehlgeschlagen';
$lang['installer_upgrade_0']='Upgrade von %1$s erfolgreich';
$lang['installer_upgrade_1']='Upgrade von %1$s fehlgeschlagen, aber Wiederherstellung erfolgreich';
$lang['installer_upgrade_2']='Upgrade von %1$s fehlgeschlagen und Wiederherstellung ebenfalls fehlgeschlagen';
$lang['installer_purge_0']='Säuberung von %1$s erfolgreich';

$lang['installer_icon'] = 'Icon';
$lang['installer_addoninfo'] = 'Addon Info';
$lang['installer_status'] = 'Status';
$lang['installer_installation'] = 'Installation';
$lang['installer_author'] = 'Autor';
$lang['installer_log'] = 'Addon Log';
$lang['installer_activate_0'] = 'Addon %1$s deaktiviert';
$lang['installer_activate_1'] = 'Addon %1$s aktiviert';
$lang['installer_deactivated'] = 'Deaktiviert';
$lang['installer_activated'] = 'Aktiviert';
$lang['installer_installed'] = 'Installiert';
$lang['installer_upgrade_avail'] = 'Upgrade verfügbar';
$lang['installer_not_installed'] = 'Nicht installiert';
$lang['installer_install'] = 'Installieren';
$lang['installer_uninstall'] = 'Deinstallieren';
$lang['installer_activate'] = 'Aktivieren';
$lang['installer_deactivate'] = 'Deaktivieren';
$lang['installer_upgrade'] = 'Aktualisieren';
$lang['installer_purge'] = 'Säubern';

$lang['installer_turn_off'] = 'Klicken zum Deaktivieren';
$lang['installer_turn_on'] = 'Klicken zum Aktivieren';
$lang['installer_click_uninstall'] = 'Klicken zum Deinstallieren';
$lang['installer_click_upgrade'] = 'Klicken um %1$s auf %2$s zu aktualisieren';
$lang['installer_click_install'] = 'Klicken zum Installieren ';
$lang['installer_overwrite'] = 'Alte Versionen überschreiben';
$lang['installer_replace_files'] = 'Du hast ein AddOn durch eine ältere Version überschrieben<br />Ersetze die Dateien durch eine aktuelle Version<br /><br />oder klicke um das Addon zu bereinigen';

$lang['installer_error'] = 'Fehler bei der Installation';
$lang['installer_invalid_type'] = 'Ungültiger Installationstyp';
$lang['installer_no_success_sql'] = 'Abfragen wurden nicht erfolgreich zum Installer hinzugefügt';
$lang['installer_no_class'] = 'Die Installations-Definitionsdatei für %1$s enthielt keine korrekte Installations-Klasse';
$lang['installer_no_installdef'] = 'inc/install.def.php für %1$s wurde nicht gefunden';

$lang['installer_no_empty'] = 'Kann leeren AddOn Namen nicht installieren';
$lang['installer_fetch_failed'] = 'Abrufen der Addondaten für %1$s fehlgeschlagen';
$lang['installer_addon_exist'] = '%1$s beinhaltet bereits %2$s. Du kannst zurück gehen und dieses Addon zuerst deinstallieren oder upgraden oder du installierst das Addon unter einem anderen Namen.';
$lang['installer_no_upgrade'] = '%1$s enthält keine Daten zum upgraden';
$lang['installer_not_upgradable'] = '%1$s kann %2$s nicht upgraden, weil der Basisname %3$s nicht in der Liste der upgradebaren Addons ist';
$lang['installer_no_uninstall'] = '%1$s enthält kein Addon zum deinstallieren';
$lang['installer_not_uninstallable'] = '%1$s enthält ein Addon %2$s welches mit dessen Addon-Deinstaller deinstalliert werden muss';

// After Install guide
$lang['install'] = 'Installation';
$lang['setup_guide'] = 'Nach Installationsanleitung';
$lang['skip_setup'] = 'Setup überspringen';
$lang['default_data'] = 'Standard Daten';
$lang['default_data_help'] = 'Hier die standardmäßig zugelassene Gilde einstellen<br />Eine Standardgilde wird von vielen Addons benötigt um korrekt zu funktionieren<br />Weitere Gilden können in RosterCP-&gt;Upload Regeln zugelassen werden<br /><br />Falls dies eine Gildenlose Roster Installation ist:<br />Guildless-F als Gildenname angeben<br />F mit der eigenen Fraktion ersetzen (A=Allianz, H=Horde)<br />Realm und Region eingeben<br />Upload Regeln für Charaktere einstellen unter RosterCP-&gt;Upload Regeln';
$lang['guide_complete'] = 'Das Nach Installationssetup ist komplett';
$lang['guide_next'] = 'Denke daran,';
$lang['guide_next_text'] = '<ul><li><a href="%1$s" target="_blank">Installiere Roster AddOns</a></li><li><a href="%2$s" target="_blank">Upload Regeln einstellen</a></li><li><a href="%3$s" target="_blank">Aktualisiere Daten vom Arsenal</a></li></ul>';
$lang['guide_already_complete'] = 'Der Installationsassistent wurde bereits beendet<br />Er kann nicht nochmal ausgeführt werden';

// Armory Data
$lang['adata_update_talents'] = 'Talente';
$lang['adata_update_class'] = 'Klasse %1$s aktualisiert';
$lang['adata_update_row'] = '%1$s Zeilen zur Datenbank hinzugefügt.';

// Password Stuff
$lang['username'] = 'Username';
$lang['password'] = 'Passwort';
$lang['remember_me'] = 'Remember me';
$lang['pass'] = 'Pass';
$lang['changeadminpass'] = 'Ändere das Admin Passwort';
$lang['changeofficerpass'] = 'Ändere das Offizer Passwort';
$lang['changeguildpass'] = 'Ändere das Gilden Passwort';
$lang['old_pass'] = 'Altes Passwort';
$lang['new_pass'] = 'Neues Passwort';
$lang['new_pass_confirm'] = 'Neues Passwort [ bestätigen ]';
$lang['pass_old_error'] = 'Falsches Passwort. Bitte gebe das richtige alte Passwort ein';
$lang['pass_submit_error'] = 'Übertragungsfehler. Das alte, das neue und das bestätigte neue Passwort müssen angegeben werden';
$lang['pass_mismatch'] = 'Passwörter stimmen nicht überein. Bitte gib das gleiche Passwort in beiden Passwortfeldern ein';
$lang['pass_blank'] = 'Leere Passwortfelder sind nicht erlaubt. Bitte gib in beiden Feldern ein Passwort ein';
$lang['pass_isold'] = 'Passwort nicht geändert. Das Neue ist das gleiche Passwort wie das Alte';
$lang['pass_changed'] = '&quot;%1$s&quot; Passwort geändert. Dein neues Passwort ist [ %2$s ].<br /> Vergiß das Passwort nicht, da es verschlüsselt gespeichert ist';
$lang['auth_req'] = 'Anmeldung erforderlich';

// Upload Rules
$lang['enforce_rules'] = 'Upload Regeln erzwingen';
$lang['enforce_rules_never'] = 'Niemals';
$lang['enforce_rules_all'] = 'Bei allen LUA Dateien';
$lang['enforce_rules_cp'] = 'Nur bei wowroster.lua';
$lang['enforce_rules_gp'] = 'Nur bei Gilden aktualisierung';
$lang['upload_rules_error'] = 'Du kannst beim Hinzufügen einer Regel kein Feld leer lassen';
$lang['upload_rules_help'] = 'Die Regeln sind in zwei Blöcke unterteilt.<ul><li>Für jeden hochgeladene Gilde/Charakter wird zuerst der oberste Block überprüft.<br />Wenn der Name@Server übereinstimmt mit einer der \'Verbieten\' Regeln, wird er abgewiesen.</li><li>Anschließend wird der zweite Block überprüft.<br />Wenn der Name@Server übereinstimmt mit einer der \'Erlauben\' Regeln, wird er akzeptiert.</li><li>Wenn er mit keiner Regel übereinstimmt, wird er abgewiesen.</li></ul>You can use % for a wildcard.<br /><br />Remember to set a default guild here as well.';

// Data Manager
$lang['clean'] = 'Einträge auf den Regeln basierent säubern';
$lang['clean_help'] = 'Dies wird alle Einträge anhand der \'Upload Regeln\' säubern.';
$lang['select_guild'] = 'Gilde auswählen';
$lang['delete_checked'] = 'Markierte löschen';
$lang['delete_guild'] = 'Gilde löschen';
$lang['delete_guild_confirm'] = 'Dies entfernt die gesamte Gilde und setzt alle ihre Mitglieder auf Gildenlos.\\n Bist du sicher das Du das tun willst?\\n\\nHINWEIS: Dies kann nicht Rückgängig gemacht werden!';

// Config Reset
$lang['config_is_reset'] = 'Konfiguration wurde zurückgesetzt. Bitte vergiss nicht ALLE deine Einstellung erneut einzugeben, bevor du versuchst deine Daten hochzuladen';
$lang['config_reset_confirm'] = 'Dies ist unumkehrbar. Willst du wirklich fortfahren?';
$lang['config_reset_help'] = 'Dies wird deine Roster Konfiguration komplett zurücksetzen.<br />
Alle Daten in den Roster Konfigurationstabellen werden dauerhaft gelöscht und die Standardwerte werden gespeichert.<br />
Gildendaten, Charakterdaten, Addon Konfigurationen, Addondaten, Menü-Buttons und Upload-Regeln bleiben erhalten.<br />
Das Gilden-, Offizier und das Admin-Passwort werden ebenfalls erhalten bleiben.<br />
<br />
Um fortzufahren gibt dein Admin-Passwort unten ein und klicke auf \'weiter\'.';

/******************************
 * Roster Admin Strings
 ******************************/

$lang['pagebar_function'] = 'Aufgaben';
$lang['pagebar_rosterconf'] = 'Konfiguriere Roster';
$lang['pagebar_uploadrules'] = 'Upload Regeln';
$lang['pagebar_dataman'] = 'Daten Manager';
$lang['pagebar_userman'] = 'Benutzer Manager';
$lang['pagebar_armory_data'] = 'Arsenal Daten';
$lang['pagebar_changepass'] = 'Passwort ändern';
$lang['pagebar_addoninst'] = 'Verwalte Addons';
$lang['pagebar_plugin'] = 'Plugin Verwaltung';
$lang['pagebar_update'] = 'Upload Profil';
$lang['pagebar_rosterdiag'] = 'Roster Diagnose Seite';
$lang['pagebar_menuconf'] = 'Menü Konfiguration';
$lang['pagebar_configreset'] = 'Einstellungen zurücksetzen';

$lang['pagebar_addonconf'] = 'Addon Konfiguration';

$lang['roster_config_menu'] = 'Einstellungsmenü';
$lang['menu_config_help'] = 'Menü Button hinzufügen Hilfe';
$lang['menu_config_help_text'] = 'Benutzen um einen neuen Button hinzuzufügen. Der neue Button wird in der aktuellen Sektion hinzugefügt.<ul><li>Titel - Der Name des Buttons.</li><li>URL - Der Link für den Button. Das kann eine interne URL sein oder eine komplette URL (dann bitte http:// voranstellen).</li><li>Icon - Das Icon für den Button. Dieses muss ein Icon aus dem Interface Image Pack sein, ohne den Pfad und ohne Dateiendung (Bsp. inv_misc_gear_01)</ul>';

// Submit/Reset confirm questions
$lang['config_submit_button'] = 'Speichere Einstellungen';
$lang['config_reset_button'] = 'Zurücksetzen';
$lang['confirm_config_submit'] = 'Dies wird die Änderungen in die Datenbank schreiben. Bist Du sicher?';
$lang['confirm_config_reset'] = 'Dies wird das Formular in den Zustand zurücksetzen in dem es am Anfang war. Bist Du sicher?';

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
$lang['admin']['main_conf'] = 'Haupt Konfig.|Roster Haupteinstellungen<br />Enthält Roster URL, Bilder URL und andere grundlegende Einstellungen...';
$lang['admin']['defaults_conf'] = 'Standart Konfig.|Setz die Einstellung auf Standart';
$lang['admin']['index_conf'] = 'Indexseite|Einstellen, was auf der Startseite angezeigt werden soll';
$lang['admin']['menu_conf'] = 'Menü Konfig.|Einstellen, welche Elemente im Menü angezeigt werden sollen';
$lang['admin']['display_conf'] = 'Anzeigen Konfig.|Verschiedene Anzeigeeinstellungen: css, javascript, motd, etc...';
$lang['admin']['realmstatus_conf'] = 'Serverstatus|Optionen für den Serverstatus';
$lang['admin']['data_links'] = 'Daten Links|Externe Links';
$lang['admin']['update_access'] = 'Update Zugriff|Optionale phpBB Authorisierung für Update';

$lang['admin']['documentation'] = 'Dokumentation|WoWRoster Dokumentation über das wowroster.net-Wiki';

// main_conf
$lang['admin']['roster_dbver'] = "Roster Datenbank Version|Die Version der Datenbank";
$lang['admin']['version'] = "Roster Version|Aktuelle Version des Rosters";
$lang['admin']['debug_mode'] = "Debug Mode|aus - Keine Debug- oder Fehlermeldungen<br />ein - Anzeige von Fehler- und einfachen Debugmeldungen<br />erweitert - Vollständiger Debugmodus und Ablaufverfolgung bei Fehlermeldungen";
$lang['admin']['sql_window'] = "SQL Fenster|aus - Query Fenster nicht anzeigen<br />ein - Query Fenster in Fußzeile anzeigen<br />erweitert - DESCRIBE statements einbeziehen";
$lang['admin']['minCPver'] = "Min CP Version|Minimum WoWRoster-Profiler Version zum Upload";
$lang['admin']['minGPver'] = "Min GP version|Minimum WoWRoster-GuildProfiler Version zum Upload";
$lang['admin']['locale'] = "Roster Hauptsprache|Sprache, in welcher der Roster angezeigt wird";
$lang['admin']['default_page'] = "Standard Seite|Seite, die angezeigt wird, wenn keine Seite in der URL angegeben ist";
$lang['admin']['external_auth'] = "Roster Auth|Hier kannst du die Anmeldedatei auswählen die der Roster benutzen soll<br />&quot;Roster&quot; ist das Standard, Anmeldesystem";
$lang['admin']['website_address'] = "Webseitenadresse|Wird benötigt für das Logo, den Gildennamenlink und das Hauptmenü<br />Einige Roster Addons benötigen diese auch";
$lang['admin']['interface_url'] = "Interface Verzeichnis URL|Verzeichnis zu den Interface Images<br />Das Standartverzeichnis ist &quot;img/&quot;<br /><br />Du kannst auch eine andere URL verwenden.";
$lang['admin']['img_suffix'] = "Bilder Dateierweiterung|Die Dateierweiterung deiner Interface Images";
$lang['admin']['alt_img_suffix'] = "Alternative Bilder Dateierweiterung|Die Dateierweiterung deiner Interface Images";
$lang['admin']['img_url'] = "Roster Bilder Verzeichnis URL|Verzeichnis zu den Roster's Images<br />Das Standartverzeichnis ist &quot;img/&quot;<br /><br />Du kannst auch eine andere URL verwenden.";
$lang['admin']['timezone'] = "Zeitzone|Wird hinter der Zeit angezeigt, damit man weiss in welcher Zeitzone sich der Zeithinweis befindet";
$lang['admin']['localtimeoffset'] = "Zeitzonenabstand|Der Zeitzonenabstand zur UTC/GMT<br />Die Zeiten im Roster werden durch diesen Abstand zur UTC/GMT berechnet.";
$lang['admin']['use_update_triggers'] = "Addon Update Triggers|Addon Update Triggers werden für einige AddOns während eines Charakter- oder Gildenupdates benötigt.<br />Einige Addons benötigen diese Funktion voraussichtlich.";
$lang['admin']['check_updates'] = "Überprüfe auf Updates|Dies erlaubt deiner Rosterkopie (und Addons, die dieses Feature benutzen)<br />zu überprüfen, ob Du die neueste Version der Software hast";
$lang['admin']['seo_url'] = "Suchmaschinen freundliche URLs|Aktiviert SEO URL Links im Roster<br /><br />ein - /some/page/here/param=value.html<br />aus - index.php?p=some-page-here&amp;param=value";
$lang['admin']['local_cache']= "Dateisystem Cache|Benutze lokales Server Dateisystem um einige Dateien zu cachen und damit die Leistung zu erhöhen.";
$lang['admin']['use_temp_tables'] = "Benutze Temporary Tables|Diese Einstellung deaktivieren wenn dein Webhoster es nicht erlaubt temporary database tables zu generieren (CREATE TEMPORARY TABLE privilege).<br/>Diese Einstellung eingeschaltet zu lassen wird aus Performancegründen empfohlen.";
$lang['admin']['preprocess_js'] = "Javascript Dateien zusammenfassen|Bestimmte Javascript Dateien werden automatisch optimiert, was zu einer Reduzierung der Dateigröße und Anzahl der Zugriffe auf die Webseite führen kann.<br />Es wird empfohlen diese Option aktiviert zu lassen, für besser Perfomance.";
$lang['admin']['preprocess_css'] = "CSS Dateien zusammenfassen und komprimieren|Bestimmte CSS Dateien werden automatisch optimiert, was zu einer Reduzierung der Dateigröße und Anzahl der Zugriffe auf die Webseite führen kann.<br />Es wird empfohlen diese Option aktiviert zu lassen, für besser Perfomance.";
$lang['admin']['enforce_rules'] = "Upload Regeln erzwingen|Diese Einstellung erzwingt die Anwendung der Upload Regeln bei jedem lua Upload<ul class='ul-no-m'><li>Niemals: Regeln niemals erzwingen</li><li>Alle LUA Updates: Regeln bei jedem lua Update erzwingen</li><li>CP Updates: Regeln nur bei CP.lua Updates erzwingen</li><li>Gilden Updates: Regeln nur bei Gildenupdates erzwingen</li></ul>Diese Einstellungen können auch auf der &quot;Upload Regeln&quot; Seite eingestellt werden.";

// defaults_conf
$lang['admin']['default_name'] = "WowRoster Name|Einen Namen eingeben, dieser wird angezeigt wenn man nicht in der Gilden- oder Charakteransicht ist";
$lang['admin']['default_desc'] = "Beschreibung|Eine kurze Beschreibung eingeben, diese wird angezeigt wenn man nicht in der Gilden- oder Charakteransicht ist";
$lang['admin']['alt_type'] = "2.-Char Suche (Twinks)|Text, der zur Anzeige der Anzahl der 2.-Charaktere auf der Hautpseite benutzt wird<br /><br /><span class=\"red\">Das Addon Mitgliederliste benutzt das nicht für die Twink Gruppierung</span>";
$lang['admin']['alt_location'] = "Twink Suchfeld|In welchem Feld soll der Twink-Text gesucht werden<br /><br /><span class=\"red\">Das Addon Mitgliederliste benutzt das nicht für die Twink Gruppierung</span>";

// display_conf
$lang['admin']['theme'] = "Roster Theme|Wähle das generelle Aussehen des Rosters<br /><span class=\"red\">ACHTUNG:</span> Momentan ünterstützen nicht alle Teile des Rosters dieses Feature<br />Ein anderes als das Standardaussehen zu wählen, kann zu unerwartete Ereignissen führen";
$lang['admin']['logo'] = "URL für das Kopf-Logo|Die volle URL für das Logo<br />Oder &quot;img/&quot; vor den Namen setzen, um im /img-Verzeichnis des Rosters nachzugucken";
$lang['admin']['roster_bg'] = "URL für das Hintergrundbild|Die volle URL für den Haupthintergrund<br />Oder &quot;img/&quot; vor den Namen setzen, um im /img-Verzeichnis des Rosters nachzugucken";
$lang['admin']['motd_display_mode'] = "MOTD Anzeige Modus|Wie die MOTD (Nachricht des Tages) angezeigt werden soll:<br /><br />&quot;Text&quot; - Zeigt MOTD in rotem Text<br />&quot;Image&quot; - Zeigt MOTD als Bild (Benötigt GD!)";
$lang['admin']['header_locale'] = "Sprachauswahl|Kontrolliert die Anzeige der Sprachauswahl im oberen Ausschnitt des Hauptmenüs";
$lang['admin']['header_login'] = "Login im Kopfbereich|Anzeigen der Loginbox im Kopfbereich";
$lang['admin']['header_search'] = "Suchen im Kopfbereich|Anzeigen der Suchbox im Kopfbereich";
$lang['admin']['signaturebackground'] = "img.php Hintergrund|Support für die (alten) Standard Signaturen";
$lang['admin']['processtime'] = "Seiten Gen. Zeit/DB Abfragen|Zeit für die Berechnung der Seite und die Anzahl der Datenbankabfragen in der Fußzeile anzeigen<br />&quot;<i>x.xx | xx</i>&quot;";

// data_links
$lang['admin']['profiler'] = "WoWRoster-Profiler Downloadlink|URL um das WoWRoster-Profiler Addon herunterzuladen";
$lang['admin']['uploadapp'] = "UniUploader Downloadlink|URL um den UniUploader herunterzuladen";

// realmstatus_conf
$lang['admin']['rs_display'] = "Anzeige Modus|Wie der Status angezeigt werden soll<ul class='ul-no-m'><li>off: Do not show realm status</li><li>Text: Zeigt den Realmstatus in einem DIV Container mit Text und Standardbildern</li><li>Image: Zeigt Realmstatus als ein Bild (BENÖTIGT GD!)</li></ul>";
$lang['admin']['rs_timer'] = "Refresh Timer|Setzt das Intervall, in dem neue Realmstatusdaten abgefragt werden";
$lang['admin']['rs_left'] = "Realm Anzeige|";
$lang['admin']['rs_middle'] = "Typ Anzeige|";
$lang['admin']['rs_right'] = "Bevölkerungsanzeige|";
$lang['admin']['rs_font_server'] = "Schriftart|Schriftart für den Realmnamen<br />(Nur Image Modus!)";
$lang['admin']['rs_size_server'] = "Schriftgröße|Schriftgröße für den Realmnamen<br />(Nur Image Modus!)";
$lang['admin']['rs_color_server'] = "Farbe|Farbe des Realmnamens";
$lang['admin']['rs_color_shadow'] = "Schattenfarbe|Farbe des Textschattens<br />(Nur Image Modus!)";
$lang['admin']['rs_font_type'] = "Typschriftart|Schriftart für den Realmtyp<br />(Nur Image Modus!)";
$lang['admin']['rs_size_type'] = "Typschriftgröße|Schriftgröße für den Realmtyp<br />(Nur Image Modus!)";
$lang['admin']['rs_color_rppvp'] = "RP-PvP Farbe|Farbe für RP-PvP";
$lang['admin']['rs_color_pve'] = "Normal Farbe|Farbe für Normal";
$lang['admin']['rs_color_pvp'] = "PvP Farbe|Farbe für PvP";
$lang['admin']['rs_color_rp'] = "RP Farbe|Farbe für RP";
$lang['admin']['rs_color_unknown'] = "Unbekannt Farbe|Farbe für Unbekannt";
$lang['admin']['rs_font_pop'] = "Bev. Schriftart|Schriftart für die Realmbevölkerung<br />(Nur Image Modus!)";
$lang['admin']['rs_size_pop'] = "Bev. Schriftgröße|Schriftgröße für die Realmbevölkerung<br />(Nur Image Modus!)";
$lang['admin']['rs_color_low'] = "Farbe Niedrig|Farbe für niedrige Bevölkerung";
$lang['admin']['rs_color_medium'] = "Farbe Mittel|Farbe für mittlere Bevölkerung";
$lang['admin']['rs_color_high'] = "Farbe Hoch|Farbe für hohe Bevölkerung";
$lang['admin']['rs_color_max'] = "Farbe Max|Farbe für maximale Bevölkerung";
$lang['admin']['rs_color_error'] = "Farbe Fehler|Farbe für Realm Fehler";
$lang['admin']['rs_color_offline'] = "Farbe Offline|Farbe für offline Realms";
$lang['admin']['rs_color_full'] = "Farbe Voll|Farbe für volle Realms (Nur EU Realms)";
$lang['admin']['rs_color_recommended'] = "Farbe Empfohlen|Farbe für empfohlene Realms (Nur EU Realms)";

// update_access
$lang['admin']['authenticated_user'] = "Zugriff auf Update.php|Kontrolliert den Zugriff auf update.php<br /><br />OFF deaktiviert den Zugriff für JEDEN";
$lang['admin']['api_key_private'] = "Blizzard API Privatschlüssel|Dies ist der private Schlüssel, den man von Blizzard erhält.<br />Dieser aktiviert das der WoWRoster mehr als 3000 Anfrage pro Tag an das Arsenal senden darf.";
$lang['admin']['api_key_public'] = "Blizzard API Öffentlicherschlüssel|Dies ist der öffentliche Schlüssel, den man von Blizzard erhält<br />Dieser aktiviert das der WoWRoster mehr als 3000 Anfrage pro Tag an das Arsenal senden darf.";
$lang['admin']['api_url_region'] = "Blizzard API Region|Arsenal Region für Aktualisierungen";
$lang['admin']['api_url_locale'] = "Blizzard API Sprache|Diese Sprachen sind Regionsabhängig";
$lang['admin']['update_inst'] = 'Aktualisierungsanleitung|Kontrolliert die Anzeige der Aktualisierungsanleitung auf der Update Seite';
$lang['admin']['gp_user_level'] = "Gildendaten-Level|Level benötigt um WoWRoster-GuildProfiler Daten zu verarbeiten";
$lang['admin']['cp_user_level'] = "Charakterdata-Level|Level benötigt um WoWRoster-Profiler Daten zu verarbeiten";
$lang['admin']['lua_user_level'] = "Andere LUA Daten Level|Level benötigt um andere LUA-Dateien zu verarbeiten<br />Dies gilt für JEDE andere LUA-Datei, die in den Roster hochgeladen werden kann";
$lang['admin']['use_api_onupdate'] = "Api on Lua Update|process an api call on each lua char update pulling all api info for the character some addons may use this data";

//session
$lang['admin']['sess_time'] = 'Session Dauer|Die Zeit wie lange eine Session gültig ist.';
$lang['admin']['save_login'] = 'Login speichern|Cookie benutzen um Login zu speichern?';
$lang['admin']['acc_session'] = 'Session Konfig.|Konfiguration der Einstellung für Account Sessions.';

// Character Display Settings
$lang['admin']['per_character_display'] = 'Charakterspezifische Anzeige-Einstellungen';

// user admin
$lang['admin']['user_desc'] = 'CP Admin - This option gives the selected user admin access to WoWRoster<br/>Public - The default access given to all users. Allows the user to see any and all public pages in Roster. Removing this option only lets them see the content their other ranks allow<br/>** Other ranks are scaned from the currently selected guilds\' rank names and you can assign user access accordingly to users.';

//Overlib for Allow/Disallow rules
$lang['guildname'] = 'Gildenname';
$lang['realmname']  = 'Realmname';
$lang['regionname'] = 'Region (z.B. EU)';
$lang['charname'] = 'Charaktername';

//register locals
$lang['register'] 	= 'Registrieren';
$lang['menu_register'] 	= 'Registrieren|Registriere dich um Zugriff auf deine Gildenseite zu haben';
$lang['cname_tt'] 	='Dein Benutzername sollte der Name deines Hauptcharakter sein. Twinks können später hinzugefügt werden.';
$lang['cname'] 		='Charakter Name';
$lang['cclass_tt'] 	='Deine Charakter Klasse';
$lang['cclass'] 	='Charakter Klasse';
$lang['clevel_tt'] 	='Dein Charakter Level';
$lang['clevel']		='Charakter Level';
$lang['cgrank_tt'] 	='Dies ist ein Rang in der Gilde';
$lang['cgrank'] 	='Gildenrang';
$lang['cemail_tt'] 	='Deine E-Mail Adresse. NIEMALS die gleiche E-Mail Adresse wie für das battle.net verwenden.';
$lang['cemail'] 	='E-Mail Adresse';
