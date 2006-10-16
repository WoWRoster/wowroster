<?php
/******************************
 * WoWRoster.net  UniAdmin
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

if( !defined('IN_UNIADMIN') )
{
    exit('Unzulässiger Zugriff auf die Datei entdeckt!');
}

// %1\$<type> prevents a possible error in strings caused
//      by another language re-ordering the variables
// $s is a string, $d is an integer, $f is a float

// <title> Titles
$lang['title_help'] = 'Hilfe';
$lang['title_addons'] = 'AddOns';
$lang['title_logo'] = 'Logos';
$lang['title_settings'] = 'Einstellungen';
$lang['title_stats'] = 'Statistiken';
$lang['title_users'] = 'Benutzer';
$lang['title_config'] = 'UniAdmin Konfiguration';
$lang['title_login'] = 'Anmeldung';


// Help page text
$lang['help'] = array(
	array(	'header' => 'Einleitung',
			'text'   => '
<p>Ich wette, Du wunderst Dich wie dieses System zu benutzen ist, darum folgendes:</p>
<p>Dieses System wird benutzt, um die Addons, Logos und Einstellungen der Nutzer (die den UniUploader nutzen) aktuell zu halten<br />
Wenn Du eine Erweiterung ins System integrierst und die [Update]-Schaltfläche im UU drückst, dann schaut sich der UU die &quot;Synchronisations URL&quot; (die in der linken Spalte) an <br />
und beginnt den Download jedes AddOn-Updates, das sich in geringster Weise vom Original auf der lokalen Festplatte unterscheidet.<br />
UU wird im Anschluss daran jedes AddOn mit dem neuen Update auf deiner Maschine ersetzen.</p>'),

	array(	'header' => 'AddOns',
			'text'   => '
<p>Das hochgeladene AddOn darf nur im ZIP-Format vorliegen.<br />
Die ZIP-Datei darf nur folgende Verzeichnisstruktur beinhalten: [Verzeichnis],{Datei}, und nicht buchstäblich &quot;addonName&quot; oder &quot;addon-datei&quot;<br />
Der AddOn-Name ist der gleiche wie der des AddOn-Verzeichnisses in dem Die AddOn-Dateien liegen</p>
<pre>[Interface]
     [AddOns]
          [addonName]
               {addon-datei}
               {addon-datei}
               {addon-datei}
               {addon-datei}
etc.</pre>'),

	array(	'header' => 'Logos',
			'text'   => '
<p>Diese Einstellung wechselt die Logos die im /jUniUploader angezeigt werden<br />
Logo 1 wird auf der [Einstellungen]-Seite angezeigt<br />
Logo 2 wird auf der [Über]-Seite angezeigt</p>'),

	array(	'header' => 'Einstellungen',
			'text'   => '
<p>Du kannst hier sicherstellen, ob benutzerkritischen Einstellungen im UU geupdated werden, aber sei mit einigen von ihnen SEHR vorsichtig, denn manche Einstellungen können deinen Benutzer äußerst verärgern und wenn Du falsche Parameter einträgst, könnte es sein das Du all deine User verlierst! *lol*<br />
Mit 1 oder 0 kannst Du die Einstellung im UU aktivieren (1), bzw. deaktivieren (0).</p>
<p>Die gespeicherte Werteliste, ist die aktuelle Liste mit Dateien die Du mit dem UU hochladen möchtest.</p>'),

	array(	'header' => 'Statistiken',
			'text'   => '
<p>Diese Einstellung zeigt dir an, wer auf den UniAdmin zugreift</p>
<p>Die Tabelle zeigt dir jeden Zugriff an</p>
<ul>
	<li> &quot;Tätigkeit&quot; - Wonach der Client fragt </li>
	<li> &quot;IP Adresse&quot; - Die IP-Adresse des Clients</li>
	<li> &quot;Datum/Uhrzeit&quot; - Datum / Uhrzeit des Zugriffs</li>
	<li> &quot;Benutzerclient&quot; - Welche Software zugegriffen hat</li>
	<li> &quot;Quellname&quot; - Die Ursprungs-ID des Benutzers</li>
</ul>
<p>Unter der Tabelle siehst Du informative Kreisdiagramme, wie der UniAdmin erreicht worden ist</p>'),

	array(	'header' => 'Benutzer',
			'text'   => '
<p>Es gibt 3 verschiedene &quot;Benutzerstufen&quot;</p>
<p>(Shows highest action available)</p>
<dl>
	<dt>Stufe 1 (Benutzer) hat Zugriff auf</dt>
	<dd>1, 2, 3, 4, 5.3</dd>

	<dt>Stufe 2 (Hauptbenutzer) hat Zugriff auf</dt>
	<dd>1.1, 2, 3.1, 4, 5.7</dd>

	<dt>Stufe 3 (Administrator) hat Zugriff auf alles</dt>
	<dd>1.2, 2, 3.2, 4, 5.10, 6</dd>
	<dd>&nbsp;</dd>
</dl>
<p>Es sollten nicht mehr als 1 oder 2 &quot;Stufe 3&quot; Benutzer im UniAdmin angelegt werden!</p>
<hr />
<p>Zugangskontrolle für einzelne Bereiche:</p>
<ul>
	<li> 1: AddOn-Verwaltung
		<ul>
			<li> 1.1: AddOns verwalten</li>
			<li> 1.2: Hinzufügen/Löschen von AddOns</li>
		</ul></li>
	<li> 2: Logo Verwaltung</li>
	<li> 3: Einstellungsverwaltung
		<ul>
			<li> 3.1: Hinzufügen/Entfernen der SavedVariable-Dateien</li>
			<li> 3.2: settings.ini hochladen/runterladen</li>
		</ul></li>
	<li> 4: Statistikverwaltung</li>
	<li> 5: Benutzerverwaltung
		<ul>
			<li> 5.1: Sprache ändern</li>
			<li> 5.2: Passwort ändern</li>
			<li> 5.3: User löschen</li>
			<li> 5.4: Usernamen ändern</li>
			<li> 5.5: Benutzer der Stufe 1 hinzufügen</li>
			<li> 5.6: Informationen des Benutzers der Stufe 1 ändern (Benutzername, Passwort, Sprache)</li>
			<li> 5.7: Stufe 1 Benutzer löschen</li>
			<li> 5.8: User beliebiger Stufe hinzufügen</li>
			<li> 5.9: jeglichen Benutzer löschen</li>
			<li> 5.10: jegliche Benutzerinformationen ändern (Benutzername, Passwort, Stufe, Sprache)</li>
		</ul></li>
	<li> 6: UniAdmin Einstellungen</li>
</ul>'),
);


// Column Headers
$lang['name'] = 'Name';
$lang['toc'] = 'TOC';
$lang['required'] = 'Erforderlich';
$lang['version'] = 'Version';
$lang['uploaded'] = 'Hochgeladen';
$lang['enabled'] = 'Aktiviert';
$lang['files'] = 'Dateien';
$lang['url'] = 'URL';
$lang['delete'] = 'Löschen';
$lang['disable_enable'] = 'Deaktivieren / Aktivieren';
$lang['update_file'] = 'Datei aktualisieren';
$lang['updated'] = 'Aktualisiert';
$lang['setting_name'] = 'Einstellungsname';
$lang['description'] = 'Beschreibung';
$lang['value'] = 'Wert';
$lang['filename'] = 'Dateiname';
$lang['row'] = 'Zeile';
$lang['action'] = 'Tätigkeit';
$lang['ip_address'] = 'IP-Adresse';
$lang['date_time'] = 'Datum/Uhrzeit';
$lang['user_agent'] = 'Benutzerclient';
$lang['host_name'] = 'Quellname';



// Submit Buttons
$lang['login'] = 'Anmeldung';
$lang['logout'] = 'Abmeldung';
$lang['on'] = 'An';
$lang['off'] = 'Aus';
$lang['no'] = 'Nein';
$lang['yes'] = 'Ja';
$lang['add'] = 'Hinzufügen';
$lang['remove'] = 'Entfernen';
$lang['enable'] = 'Aktivieren';
$lang['disable'] = 'Deaktivieren';
$lang['modify'] = 'Ändern';
$lang['check'] = 'Überprüfen';
$lang['proceed'] = 'Fortfahren';
$lang['reset'] = 'Rückgängig machen';
$lang['submit'] = 'Abschicken';
$lang['upgrade'] = 'Upgraden';
$lang['update_logo'] = 'Logo updaten %1$d';
$lang['update_settings'] = 'Einstellungen updaten';
$lang['show'] = 'Zeigen';
$lang['add_update_addon'] = 'Hinzufügen / AddOn updaten';
$lang['import'] = 'Importieren';
$lang['export'] = 'Exportieren';
$lang['go'] = 'Go';


// Form Element Descriptions
$lang['current_password'] = 'Aktuelles Passwort';
$lang['current_password_note'] = 'Du muss dein aktuelles Passwort bestätigen, wenn Du deinen Benutzernamen oder dein Passwort ändern möchtest';
$lang['confirm_password'] = 'Passwort bestätigen';
$lang['confirm_password_note'] = 'Du musst nur dein neues Passwort bestätigen wenn Du es oben geändert hast.';
$lang['language'] = 'Sprache';
$lang['new_password'] = 'Neues Passwort';
$lang['new_password_note'] = 'Du musst nur ein neues Passwort eingeben, wenn Du es ändern möchtest';
$lang['change_username'] = 'Benutzername ändern';
$lang['change_password'] = 'Passwort ändern';
$lang['change_userlevel'] = 'Benutzerstufe ändern';
$lang['change_language'] = 'Sprache ändern';
$lang['basic_user_level_1'] = 'Benutzer (Stufe 1)';
$lang['power_user_level_2'] = 'Hauptbenutzer (Stufe 2)';
$lang['admin_level_3'] = 'Administrator (Stufe 3)';
$lang['password'] = 'Passwort';
$lang['username'] = 'Benutzername';
$lang['users'] = 'Benutzer';
$lang['add_user'] = 'Benutzer hinzufügen';
$lang['modify_user'] = 'Benutzer ändern';
$lang['current_users'] = 'Aktuelle Benutzer';
$lang['select_file'] = 'Datei auswählen';
$lang['userlevel'] = 'Benutzerstufe';
$lang['addon_management'] = 'AddOn-Verwaltung';
$lang['addon_uploaded'] = '%1$s wurde erfolgreich hochgeladen';
$lang['view_addons'] = 'AddOns anzeigen';
$lang['required_addon'] = 'Benötigtes AddOn';
$lang['homepage'] = 'Homepage';
$lang['logged_in_as'] = 'Eingeloggt als [%1$s]';
$lang['logo_table'] = 'Logo %1$d';
$lang['logo_uploaded'] = 'Logo %1$d wurde erfolgreich hochgeladen';
$lang['uniuploader_sync_settings'] = 'UniUploader Synchronisationseinstellungen';
$lang['uniadmin_config_settings'] = 'UniAdmin Konfigurationeinstellungen';
$lang['manage_svfiles'] = 'SavedVariable Dateien verwalten';
$lang['add_svfiles'] = 'SavedVariable Dateien hinzufügen';
$lang['svfiles'] = 'SavedVariable Dateien';
$lang['image_missing'] = 'Bild fehlt';
$lang['stats_limit'] = 'Zeile(n) ab der Aufzeichnung #';
$lang['user_modified'] = 'Benutzer %1$s geändert';
$lang['user_added'] = 'Benutzer %1$s hinzugefügt';
$lang['user_deleted'] = 'Benutzer %1$s gelöscht';
$lang['access_denied'] = 'Zugriff verweigert';
$lang['settings_file'] = 'settings.ini Datei';
$lang['import_file'] = 'Importiere Datei';
$lang['export_file'] = 'Exportiere Datei';
$lang['settings_updated'] = 'Einstellungen geupdated';
$lang['download'] = 'Download';
$lang['user_style'] = 'User Style';
$lang['change_style'] = 'Change Style';


// Pagination
$lang['next_page'] = 'Nächste Seite';
$lang['page'] = 'Seite';
$lang['previous_page'] = 'Vorherige Seite';


// Miscellaneous
$lang['time_format'] = 'M jS, J g:ia';
$lang['syncro_url'] = 'Synchronisations URL';
$lang['verify_syncro_url'] = 'klicken, um zu überprüfen';
$lang['guest_access'] = 'Gastzugang';
$lang['interface_ready'] = 'UniUploader Update Schnittstelle fertig...';




// UU Sync Settings

// Each setting for the UniUploader Setting Sync is listed here
// The keyname has to be exactly the same as the name in the DB and the name thats is used in UniUploader
// Any text must be html entity encoded first!
// Each group is separated by section based on the settings.ini file

// settings
$lang['LANGUAGE'] = 'Sprache';
$lang['PRIMARYURL'] = 'lade SavedVariable Dateien zu dieser URL hoch';
$lang['PROGRAMMODE'] = 'Programmmodus';
$lang['AUTODETECTWOW'] = 'WoW automatisch erkennen';
$lang['OPENGL'] = 'Starte WoW im OpenGL Modus';
$lang['WINDOWMODE'] = 'Starte WoW im Fenster Modus';

// updater
$lang['UUUPDATERCHECK'] = 'Überprüfe auf UniUploader Updates';
$lang['SYNCHROURL'] = 'URL für die Synchronisation mit dem UniAdmin';
$lang['ADDONAUTOUPDATE'] = 'automatisches AddOn-Update';
$lang['UUSETTINGSUPDATER'] = 'Synchronisiere UniUploader Einstellungen mit UniAdmin';

// options
$lang['AUTOUPLOADONFILECHANGES'] = 'Lade SavedVariable Datei bei Änderungen automatisch hoch';
$lang['ALWAYSONTOP'] = 'Zeige UniUploader immer im Vordergrund an';
$lang['SYSTRAY'] = 'Zeige UniUploader als SystemTray-Icon an';
$lang['ADDVAR1CH'] = 'Zusätzliche Variablen 1';
$lang['ADDVARNAME1'] = 'Zusätzlicher Name für Variable 1 (Standard-&gt;Benutzername)';
$lang['ADDVARVAL1'] = 'Zusätzlicher Wert für Variable 1';
$lang['ADDVAR2CH'] = 'Zusätzliche Variablen 2';
$lang['ADDVARNAME2'] = 'Zusätzlicher Name für Variable 2 (Standard-&gt;Passwort)';
$lang['ADDVARVAL2'] = 'Zusätzlicher Wert für Variable 2';
$lang['ADDVAR3CH'] = 'Zusätzliche Variablen 3';
$lang['ADDVARNAME3'] = 'Zusätzlicher Name für Variable 3';
$lang['ADDVARVAL3'] = 'Zusätzlicher Wert für Variable3';
$lang['ADDVAR4CH'] = 'Zusätzliche Variablen 4';
$lang['ADDVARNAME4'] = 'Zusätzlicher Name für Variable 4';
$lang['ADDVARVAL4'] = 'Zusätzlicher Wert für Variable 4';
$lang['ADDURL1CH'] = 'Zusätzliche URL 1';
$lang['ADDURL1'] = 'Zusätzliche URL 1 Lokation';
$lang['ADDURL2CH'] = 'Zusätzliche URL 2';
$lang['ADDURL2'] = 'Zusätzliche URL 2 Lokation';
$lang['ADDURL3CH'] = 'Zusätzliche URL 3';
$lang['ADDURL3'] = 'Zusätzliche URL 3 Lokation';
$lang['ADDURL4CH'] = 'Zusätzliche URL 4';
$lang['ADDURL4'] = 'Zusätzliche URL 4 Lokation';

// advanced
$lang['AUTOLAUNCHWOW'] = 'WoW automatisch starten';
$lang['WOWARGS'] = 'Starte mit Parametern';
$lang['STARTWITHWINDOWS'] = 'Starte UniUploader mit Windows';
$lang['USELAUNCHER'] = 'Stare WoW mit dem UU-Launcher';
$lang['STARTMINI'] = 'Starte minimiert';
$lang['SENDPWSECURE'] = 'MD5 Wert vor dem Abschicken verschlüsseln';
$lang['GZIP'] = 'gZip Kompression';
$lang['DELAYUPLOAD'] = 'Verzögerung beim hochladen';
$lang['DELAYSECONDS'] = 'Zeitverzögerung in Sekunden';
$lang['RETRDATAFROMSITE'] = 'Web==&gt;WoW - Daten empfangen';
$lang['RETRDATAURL'] = 'Web==&gt;WoW - Datenempfang an URL';
$lang['WEBWOWSVFILE'] = 'Web==&gt;WoW - SavedVariable Dateinamen schreiben';
$lang['DOWNLOADBEFOREWOWL'] = 'Web==&gt;WoW - Vor dem UU Start initiieren WoW';
$lang['DOWNLOADBEFOREUPLOAD'] = 'Web==&gt;WoW - Bevor UU hochlädt, initiieren';
$lang['DOWNLOADAFTERUPLOAD'] = 'Web==&gt;WoW - Nachdem UU hochgeladen hat, initiieren ';

// END UU Sync Strings


// BEGIN UA CONFIG SETTINGS

$lang['admin']['addon_folder'] = 'Gib den Ordner an wo die Addons abgelegt werden';
$lang['admin']['default_lang'] = 'Wähle die Standardsprache des UniAdmin Interfaces aus<br /><br />Werte werden hier automatisch im WoW-Verzeichnis erkannt';
$lang['admin']['default_style'] = 'Standart Anzeigemodus';
$lang['admin']['enable_gzip'] = 'Aktiviere GZip Kompression bei der Anzeige von UniAdmin Seiten';
$lang['admin']['interface_url'] = 'Wähle den Ort der interface.php aus<br /><br />Benutze %url% um die BasisURL einzufügen<br />Standard ist &quot;%url%?p=interface&quot; oder &quot;%url%interface.php&quot;';
$lang['admin']['logo_folder'] = 'Wähle den Ort für das UniAdmin Logos aus';
$lang['admin']['temp_analyze_folder'] = 'Wähle den Ort an dem die ZIP-Archive extrahiert und analysiert werden aus';
$lang['admin']['UAVer'] = 'Aktuelle UniAdmin Version<br />Du kannst diese Einstellung nicht ändern';

// END UA CONFIG SETTINGS


// Debug
$lang['queries'] = 'Fragen';
$lang['debug'] = 'Prüfung';
$lang['messages'] = 'Nachrichten';


// Settings
$lang['default_locale'] = 'Standardansicht';


// Error messages
$lang['error'] = 'UniAdmin Fehler';
$lang['error_invalid_login'] = 'Du hast ein falsches Passwort oder einen unzulässigen Benutzernamen eingegeben';
$lang['error_delete_addon'] = 'Fehler bei Löschung des AddOns';
$lang['error_enable_addon'] = 'Fehler bei Aktivierung des AddOns';
$lang['error_disable_addon'] = 'Fehler bei Deaktivierung des AddOns';
$lang['error_require_addon'] = 'Fehler bei Benötigung des AddOns';
$lang['error_optional_addon'] = 'Optionaler AddOn Fehler';
$lang['error_no_addon_in_db'] = 'Keine AddOns in der Datenbank';
$lang['error_no_addon_uploaded'] = 'Keine AddOns hochgeladen';
$lang['error_no_files_addon'] = 'Es wurden keine Dateien im hochgeladenen AddOn gefunden';
$lang['error_no_toc_file'] = 'Keine \'.toc\' Datei wurde im hochgeladenen AddOn gefunden';
$lang['error_unzip'] = 'Zip Fehler';
$lang['error_pclzip'] = 'PCLZip Fehler nicht zu beheben: [%1$s]';
$lang['error_addon_process'] = 'Fehler beim verarbeiten des AddOns';
$lang['error_zip_file'] = 'Das hochgeladene AddOn <u>muss</u> eine ZIP-Datei sein';

$lang['error_no_ini_uploaded'] = 'settings.ini Datei wurde nicht hochgeladen';
$lang['error_ini_file'] = 'Die hochgeladene Datei <u>muss</u> eine settings.ini vom UniUploader sein';

$lang['error_chmod'] = 'Konnte nicht chmod [%1$s]<br />chmod manuell und/oder checke die Dateiberechtigungen';
$lang['error_mkdir'] = 'Konnte nicht mkdir [%1$s]<br />mkdir manuell und/oder checke die Dateiberechtigungen';
$lang['error_unlink'] = 'Konnte nicht löschen [%1$s]<br />lösche manuell und/oder checke die Dateiberechtigungen';
$lang['error_move_uploaded_file'] = 'Konnte nicht Datei [%1$s] nach [%2$s] bewegen<br />Checke php Upload-Einstellungen und Dateiberechtigungen';

$lang['error_no_uploaded_logo'] = 'Kein Logo hochgeladen';
$lang['error_logo_format'] = 'Die hochgeladene Datei <u>muss</u> ein GIF-Bild sein';


// SQL Error Messages
$lang['sql_error'] = 'SQL Error';
$lang['sql_error_addons_list'] = 'Addonliste nicht erhalten';
$lang['sql_error_addons_disable'] = 'AddOn mit ID:%1$d konnte nicht deaktiviert werden';
$lang['sql_error_addons_enable'] = 'AddOn mit ID:%1$d konnte nicht aktiviert';
$lang['sql_error_addons_require'] = 'AddOn mit ID:%1$d konnte nicht als erforderlich gesetzt werden';
$lang['sql_error_addons_optional'] = 'AddOn mit ID:%1$d konnte nicht als optional gesetzt werden';
$lang['sql_error_addons_delete'] = 'AddOn mit ID:%1$d konnte nicht aus der Datenbank gelöscht werden<br />Entferne manuell';
$lang['sql_error_addons_insert'] = 'Konnte nicht AddOn-Hauptinformationen setzen';
$lang['sql_error_addons_files_insert'] = 'Konnte nicht AddOn-Dateien hinzufügen\' data';

$lang['sql_error_logo_toggle'] = 'Konnte Logo %1$s nicht setzen';
$lang['sql_error_logo_remove'] = 'Konnte Logo id=%1$d nicht aus der Datenbank entfernen';
$lang['sql_error_logo_insert'] = 'Konnte Logo nicht in die Datenbank einfügen';

$lang['sql_error_settings_update'] = 'Konnte Einstellungen nicht updaten =&gt; %1$s, Wert =&gt; %2$s, Aktiviert =&gt; %3$d';
$lang['sql_error_settings_sv_insert'] = 'Konnte nicht savedvariable Dateiname &quot;%1$s&quot; hinzufügen';
$lang['sql_error_settings_sv_remove'] = 'Konnte nicht savedvariable Dateiname &quot;%1$s&quot; entfernen';

$lang['sql_error_user_modify'] = 'Konnte nicht Benutzerinfo für Benutzer &quot;%1$s&quot; updaten';
$lang['sql_error_user_add'] = 'Konnte nicht Benutzer &quot;%1$s&quot; hinzufügen';
$lang['sql_error_user_delete'] = 'Konnte nicht Benutzer &quot;%1$s&quot; löschen';


?>