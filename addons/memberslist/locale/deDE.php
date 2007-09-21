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

// -[ deDE Localization ]-

// Installer names
$lang['memberslist']            = 'Mitgliederliste';
$lang['memberslist_desc']       = 'Eine sortier- und filterbare Mitgliederliste';

// Button names
$lang['memberslist_Members']	= 'Mitglieder|Zeigt die Gildenmitgliederliste mit Spielernotizen, Zuletzt online, usw... an';
$lang['memberslist_Stats']		= 'Grundwerte|Zeigt die Grundwerte jedes Gildenmitglieds wie Intelligenz, Ausdauer, usw... an';
$lang['memberslist_Honor']		= 'Ehre|Zeigt die PvP-Informationen jedes Gildenmitglieds an';
$lang['memberslist_Log']		= 'Mitglieder Log|Zeigt das upload log für neue und entfernte Mitglieder an';
$lang['memberslist_Realm']		= 'Mitglieder|Zeigt die Mitgliederliste für alle Gilden auf allen Servern an';
$lang['memberslist_RealmGuild']	= 'Gilden|Zeigt eine Liste für alle Gilden auf allen Realms an';

// Interface wordings
$lang['memberssortfilter']		= 'Sortierreihenfolge und Filterung';
$lang['memberssort']			= 'Sortierung';
$lang['memberscolshow']			= 'Zeige/Verstecke Spalten';
$lang['membersfilter']			= 'Zeilenfilter';

$lang['openall']                = 'Öffne alle';
$lang['closeall']               = 'Schließe alle';
$lang['ungroupalts']            = 'Twinkgruppen auflösen';
$lang['groupalts']              = 'Gruppiere Twinks';
$lang['clientsort']             = 'Clientsortierung';
$lang['serversort']             = 'Serversortierung';

// Column headers
$lang['onote']                  = 'Offizier Notiz';

$lang['honorpoints']            = 'Ehrenpunkte';
$lang['arenapoints']            = 'Arenapunkte';

$lang['main_name']              = 'Hauptcharaktername';
$lang['alt_type']               = 'Alt type';

// Last Online words
$lang['online_at_update']       = 'Online bei Update';
$lang['second']                 = 'vor %s Sekunde';
$lang['seconds']                = 'vor %s Sekunden';
$lang['minute']                 = 'vor %s Minute';
$lang['minutes']                = 'vor %s Minuten';
$lang['hour']                   = 'vor %s Stunde';
$lang['hours']                  = 'vor %s Stunden';
$lang['day']                    = 'vor %s Tag';
$lang['days']                   = 'vor %s Tagen';
$lang['week']                   = 'vor %s Woche';
$lang['weeks']                  = 'vor %s Wochen';
$lang['month']                  = 'vor %s Monat';
$lang['months']                 = 'vor %s Monaten';
$lang['year']                   = 'vor %s Jahr';
$lang['years']                  = 'vor %s Jahren';

$lang['armor_tooltip']			= 'Verringert den erlittenen körperlichen Schaden um %1$s%%';

$lang['motd']                   = 'MOTD';
$lang['accounts']               = 'Accounts';

// Configuration
$lang['memberslist_config']		= 'Gehe zur Mitgliederliste-Konfiguration';
$lang['memberslist_config_page']= 'Mitgliederliste-Konfiguration';
$lang['documentation']			= 'Dokumentation';
$lang['uninstall']				= 'Uninstall';

// Page names
$lang['admin']['display']       = 'Anzeige|Konfiguriert die Anzeigeoptionen für die Mitgliederliste.';
$lang['admin']['members']       = 'Mitgliederliste|Konfiguriert die Sichtbarkeit der Mitgliederliste-Spalten.';
$lang['admin']['stats']         = 'Werteliste|Konfiguriert die Sichtbarkeit der Grundwerte-Spalten.';
$lang['admin']['honor']         = 'Ehrenliste|Konfiguriert die Sichtbarkeit der Ehrenliste-Spalten.';
$lang['admin']['log']           = 'Mitglieder Log|Konfiguriert die Sichtbarkeit der Mitglieder Log-Spalten.';
$lang['admin']['build']         = 'Hauptchar/Twink Beziehungen|Konfiguriert wie Hauptchars/Twinks erkannt werden.';
$lang['admin']['ml_wiki']       = 'Dokumentation|Mitgliederliste Dokumentation auf WoWRoster wiki.';
$lang['admin']['updMainAlt']    = 'Aktualisiere Beziehungen|Aktualisiert die Main/Alt Beziehungen der Daten, die bereits in der DB gespeichert sind.';
$lang['admin']['page_size']		= 'Seitengröße|Konfiguriert die Anzahl der Zeilen Pro Seite oder 0 für keinen Seitenumbruch';

// Settings names on display page
$lang['admin']['openfilter']	= 'Öffne Filterbereich|Gib an, ob der Filterbereich standardmäßig geöffnet oder geschlossen sein sill.';
$lang['admin']['nojs']          = 'Listentyp|Gib an, ob du eine serverseitige Sortierung oder eine clientseitige Sortierung+Filterung haben möchtest.';
$lang['admin']['def_sort']		= 'Standard Sortierung|Gib die standard Sortiermethode an.';
$lang['admin']['member_tooltip']= 'Mitglieder Tooltip|Schalte den Inf-Tooltip über den Mitgliedernamen an oder aus.';
$lang['admin']['group_alts']    = 'Gruppiere Twinks|Gruppiere Twinks unterhalb ihrer Hauptcharaktere anstelle sie seperat zu sortieren.';
$lang['admin']['icon_size']     = 'Icon Größe|Setze die Größe der Klasse/Ehre/Beruf Icons.';
$lang['admin']['spec_icon']		= 'Talent Icon|Schalte das Talent Icon an oder aus.';
$lang['admin']['class_icon']    = 'Klassen Icon|Kontrolliert die Klass/Talent Icon Anzeige.<br />Full - Anzeige von Talent und Klassen Icon<br />On - Nur Anzeige des Klasse Icons<br />Off- Keine Icons';
$lang['admin']['class_text']    = 'Klassentext|Kontrolliert die Klassentectanzeige.<br />Color - Farbiger Klassentext<br />On - Anzeige des Klassentextes<br />Off - Kein Klassentext';
$lang['admin']['talent_text']   = 'Talenttext|Zeigt die Talentspezialisierung anstalle des Klassennamens.';
$lang['admin']['level_bar']     = 'Level Balken|Anzeige von Levelbalken anstelle von einfachen Zahlen.';
$lang['admin']['honor_icon']    = 'Ehre Icon|Anzeige des Ehrenrang Icons.';
$lang['admin']['compress_note'] = 'Kompakte Notiz|Zeige Gildennotiz als Tooltip anstelle innerhalb der Spalte.';

// Settings on Members page
$lang['admin']['member_update_inst'] = 'Aktualisierungsanleitung|Kontrolliert die Anzeige der Aktualisierungsanleitung auf der Mitgliederseite';
$lang['admin']['member_motd']	= 'Gilden MOTD|Zeige Gilden "Nachtricht des Tages" oben auf der Mitgliederseite';
$lang['admin']['member_hslist']	= 'Ehrensystem Werte|Kontrolliert die Anzeige der Ehrenpunkteliste auf der Mitgliederseite';
$lang['admin']['member_pvplist']= 'PvP-Logger Werte|Kontrolliert die Anzeige der PvP-Log Werte auf der Mitgliederseite<br />Wenn du das Hochladen des PvPLog deaktiviert hast, macht es keinen Sinn diese Anzeige zu aktivieren';
$lang['admin']['member_class']  = 'Klasse|Setze die Sichtbarkeit der Klassespalte auf der Mitgliederseite';
$lang['admin']['member_level']  = 'Level|Setze die Sichtbarkeit der Levelspalte auf der Mitgliederseite';
$lang['admin']['member_gtitle'] = 'Gildenrang|Setze die Sichtbarkeit der Gildenrangspalte auf der Mitgliederseite';
$lang['admin']['member_hrank']  = 'Ehrenrang|Setze die Sichtbarkeit der letzter Ehrenrangspalte auf der Mitgliederseite';
$lang['admin']['member_prof']   = 'Beruf|Setze die Sichtbarkeit der Berufspalte auf der Mitgliederseite';
$lang['admin']['member_hearth'] = 'Ruhestein|Setze die Sichtbarkeit der Ruhesteinspalte auf der Mitgliederseite';
$lang['admin']['member_zone']   = 'Zone|Setze die Sichtbarkeit der Letztes Gebiet-Spalte auf der Mitgliederseite';
$lang['admin']['member_online'] = 'Zuletzt Online|Setze die Sichtbarkeit der Zuletzt Online-Spalte auf der Mitgliederseite';
$lang['admin']['member_update'] = 'Zuletzt aktualisiert|Setze die Sichtbarkeit der Zuletzt aktualisiert-Spalte auf der Mitgliederseite';
$lang['admin']['member_note']   = 'Notiz|Setze die Sichtbarkeit der Notizspalte auf der Mitgliederseite';
$lang['admin']['member_onote']  = 'Offizernotiz|Setze die Sichtbarkeit der Offiziernotizspalte auf der Mitgliederseite';

// Settings on Stats page
$lang['admin']['stats_update_inst'] = 'Aktualisierungsanleitung|Kontrolliert die Anzeige der Aktualisierungsanleitung auf der Wertelisteseite';
$lang['admin']['stats_motd']	= 'Gilden MOTD|Zeige Gilden "Nachtricht des Tages" oben auf der Wertelisteseite';
$lang['admin']['stats_hslist']  = 'Ehrensystem Werte|Kontrolliert die Anzeige der Ehrenpunkteliste auf der Wertelisteseite';
$lang['admin']['stats_pvplist']	= 'PvP-Logger Werte|Kontrolliert die Anzeige der PvP-Log Werte auf der Wertelisteseite<br />Wenn du das Hochladen des PvPLog deaktiviert hast, macht es keinen Sinn diese Anzeige zu aktivieren';
$lang['admin']['stats_class']   = 'Klasse|Setze die Sichtbarkeit der Klassespalte auf der Wertelisteseite';
$lang['admin']['stats_level']   = 'Level|Setze die Sichtbarkeit der Levelspalte auf der Wertelisteseite';
$lang['admin']['stats_str']     = 'Stärke|Setze die Sichtbarkeit der Stärkepalte auf der Wertelisteseite';
$lang['admin']['stats_agi']     = 'Beweglichkeit|Setze die Sichtbarkeit der Beweglichkeitspalte auf der Wertelisteseite';
$lang['admin']['stats_sta']     = 'Willenskraft|Setze die Sichtbarkeit der Willenskraftspalte auf der Wertelisteseite';
$lang['admin']['stats_int']     = 'Intelligenz|Setze die Sichtbarkeit der Intelligenzspalte auf der Wertelisteseite';
$lang['admin']['stats_spi']     = 'Willenskraft|Setze die Sichtbarkeit der Willenskraftspalte auf der Wertelisteseite';
$lang['admin']['stats_sum']     = 'Gesamt|Setze die Sichtbarkeit der Gesamtspalte auf der Wertelisteseite';
$lang['admin']['stats_health']  = 'Gesundheit|Setze die Sichtbarkeit der Gesundheitspalte auf der Wertelisteseite';
$lang['admin']['stats_mana']    = 'Mana|Setze die Sichtbarkeit der Manaspalte auf der Wertelisteseite';
$lang['admin']['stats_armor']   = 'Rüstung|Setze die Sichtbarkeit der Rüstungspalte auf der Wertelisteseite';
$lang['admin']['stats_dodge']   = 'Ausweichen|Setze die Sichtbarkeit der Ausweichenspalte auf der Wertelisteseite';
$lang['admin']['stats_parry']   = 'Parrieren|Setze die Sichtbarkeit der Parierenspalte auf der Wertelisteseite';
$lang['admin']['stats_block']   = 'Blocken|Setze die Sichtbarkeit der Blockenspalte auf der Wertelisteseite';
$lang['admin']['stats_crit']    = 'Krit|Setze die Sichtbarkeit der Kritspalte auf der Wertelisteseite';

// Settings on Honor page
$lang['admin']['honor_update_inst'] = 'Aktualisierungsanleitung|Kontrolliert die Anzeige der Aktualisierungsanleitung auf der Ehrenstatistikseite';
$lang['admin']['honor_motd']	= 'Gilden MOTD|Zeige Gilden "Nachtricht des Tages" oben auf der Ehrenstatistikseite';
$lang['admin']['honor_hslist']  = 'Ehrensystem Werte|Kontrolliert die Anzeige der Ehrenpunkteliste auf der Ehrenstatistikseite';
$lang['admin']['honor_pvplist']	= 'PvP-Logger Werte|Kontrolliert die Anzeige der PvP-Log Werte auf der Ehrenstatistikseite<br />Wenn du das Hochladen des PvPLog deaktiviert hast, macht es keinen Sinn diese Anzeige zu aktivieren';
$lang['admin']['honor_class']   = 'Klasse|Setze die Sichtbarkeit der Klassespalte auf der Ehrenstatistikseite';
$lang['admin']['honor_level']   = 'Level|Setze die Sichtbarkeit der Levelspalte auf der Ehrenstatistikseite';
$lang['admin']['honor_thk']     = 'Heute HK|Setze die Sichtbarkeit der Heute HK-Spalte auf der Ehrenstatistikseite';
$lang['admin']['honor_tcp']     = 'Heute CP|Setze die Sichtbarkeit der Heute CP-Spalte auf der Ehrenstatistikseite';
$lang['admin']['honor_yhk']     = 'Gestern HK|Setze die Sichtbarkeit der Gestern HK-Spalte auf der Ehrenstatistikseite';
$lang['admin']['honor_ycp']     = 'Gestern CP|Setze die Sichtbarkeit der Gestern CP-Spalte auf der Ehrenstatistikseite';
$lang['admin']['honor_lifehk']  = 'Gesamte HK|Setze die Sichtbarkeit der Gesamt HK-Spalte auf der Ehrenstatistikseite';
$lang['admin']['honor_hrank']   = 'Höchster Rang|Setze die Sichtbarkeit der Höchster Rang-Spalte auf der Ehrenstatistikseite';
$lang['admin']['honor_hp']      = 'Ehrenpunkte|Setze die Sichtbarkeit der Ehrenpunktespalte auf der Ehrenstatistikseite';
$lang['admin']['honor_ap']      = 'Arenapunkte|Setze die Sichtbarkeit der Arenapunktespalte auf der Ehrenstatistikseite';

// Settings on Members page
$lang['admin']['log_update_inst'] = 'Aktualisierungsanleitung|Kontrolliert die Anzeige der Aktualisierungsanleitung auf der Mitglieder Log Seite';
$lang['admin']['log_motd']		= 'Gilden MOTD|Zeige Gilden "Nachtricht des Tages" oben auf der Mitglieder Log Seite';
$lang['admin']['log_hslist']	= 'Ehrensystem Werte|Kontrolliert die Anzeige der Ehrenpunkteliste auf der Mitglieder Log Seite';
$lang['admin']['log_pvplist']	= 'PvP-Logger Werte|Kontrolliert die Anzeige der PvP-Log Werte auf der Mitglieder Log Seite<br />Wenn du das Hochladen des PvPLog deaktiviert hast, macht es keinen Sinn diese Anzeige zu aktivieren';
$lang['admin']['log_class']		= 'Klasse|Setze die Sichtbarkeit der Klassespalte auf der Mitglieder Log Seite';
$lang['admin']['log_level']		= 'Level|Setze die Sichtbarkeit der Levelspalte auf der Mitglieder Log Seite';
$lang['admin']['log_gtitle']	= 'Gildenrang|Setze die Sichtbarkeit der Gildenrangspalte auf der Mitglieder Log Seite';
$lang['admin']['log_type']		= 'Typ|Setze die Sichtbarkeit der Typspalte auf der Mitglieder Log Seite';
$lang['admin']['log_date']		= 'Zuletzt Online|Setze die Sichtbarkeit der Zuletzt Online-Spalte auf der Mitglieder Log Seite';
$lang['admin']['log_note']		= 'Notiz|Setze die Sichtbarkeit der Notizspalte auf der Mitglieder Log Seite';
$lang['admin']['log_onote']		= 'Offizernotiz|Setze die Sichtbarkeit der Offiziernotizspalte auf der Mitglieder Log Seite';

// Settings names on build page
$lang['admin']['getmain_regex'] = 'Regex|Dieses Feld spezifiziert die Benutzung des Regex. <br /> Folge dem wiki-Link für Details.';
$lang['admin']['getmain_field'] = 'Anwenden auf Feld|Dieses Feld spezifiziert auf welches Mitgliederfeld Regex angewendet werden soll. <br />Folge dem wiki-Link für Details.';
$lang['admin']['getmain_match'] = 'Benutze Treffernr.|Dieses Feld spezifiziert welcher Rückgabewert von Regex verwendet wird. <br /> Folge dem wiki-Link für Details.';
$lang['admin']['getmain_main']  = 'Hauptchar-Identifizierung|Wenn Regex diesen Wert findet, so wird dieser Chars als Hauptcharakter angenommen.';
$lang['admin']['defmain']       = 'Kein Ergebnis|Setze wie die Charaktere definiert weden sollen, bei denen Regex nichts zurückgibt.';
$lang['admin']['invmain']       = 'Ungültiges Ergebnis|Setze wie die Charaktere definiert weden sollen, <br />wenn Regex ein Ergebnis ausgibt, dass kein Gildenmitglied oder kein Hauptcharakter ist.';
$lang['admin']['altofalt']      = 'Twink des Twinks|Gib an, was zu tun ist, wenn der Charakter ein hauptcharakterloser Twink ist.';
$lang['admin']['update_type']   = 'Aktualisierungstyp|Gib an, an welchem Auslöser die Hauptchar/Twink Beziehungen aktualisiert werden sollen.';
