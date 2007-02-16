<?php
/******************************
 * WoWRoster.net  UniAdmin
 * Copyright 2002-2007
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
    exit('Detected invalid access to this file!');
}

// %1\$<type> prevents a possible error in strings caused
//      by another language re-ordering the variables
// $s is a string, $d is an integer, $f is a float

// <title> Titles
$lang['title_help'] = 'Hilfe';
$lang['title_addons'] = 'AddOns';
$lang['title_wowace'] = 'WoWAce';
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
<p>Ich wette, du wunderst dich wie dieses System zu benutzen ist, darum folgendes:</p>
<p>Dieses System wird benutzt, um die Addons, Logos und Einstellungen der Benutzer (die den UniUploader nutzen) aktuell zu halten.<br />
Wenn du eine Erweiterung ins System integrierst und die [Synchronisiere]-Schaltfl�che im UU (UniUploader) dr�ckst, dann schaut sich der UU die &quot;Synchronisations URL&quot; (die in der linken Spalte) an <br />
und beginnt den Download jeder Addon-Aktualisierung, das sich in geringster Weise vom Original auf der lokalen Festplatte unterscheidet.<br />
Der UU wird im Anschluss daran jedes Addon mit der neuen Aktualisierung auf deiner Maschine ersetzen.</p>'),

	array(	'header' => 'Addons',
			'text'   => '
<p>Das hochgeladene Addon darf nur im ZIP-Format vorliegen.<br />
Die ZIP-Datei darf nur folgende Verzeichnisstruktur beinhalten: [Verzeichnis],{Datei}, und nicht buchst�blich &quot;addonName&quot; oder &quot;addon-datei&quot;<br />
Der Addon-Name ist der gleiche wie der des Addon-Verzeichnisses in dem Die Addon-Dateien liegen.</p>
<pre>[AddonName]
     {Addon-datei}
     {Addon-datei}
     {Addon-datei}
     {Addon-datei}
oder
[Interface]
     [Addons]
          [AddonName]
               {Addon-datei}
               {Addon-datei}
               {Addon-datei}
               {Addon-datei}
[Fonts]
     font.ttf</pre>'),

array(	'header' => 'WoWAce',
'text'   => '
<p>Diese Einstellung erlaubt dir, Addons aus dem WoWAce.com SVN Repository herunterzuladen.</p>
<p>Nur der UniAdmin Admin hat Zugriff auf dieses Modul.</p>'),

	array(	'header' => 'Logos',
			'text'   => '
<p>Diese Einstellung wechselt die Logos, die im /jUniUploader angezeigt werden.<br />
Logo 1 wird auf der [Einstellungen]-Seite angezeigt.<br />
Logo 2 wird auf der [�ber]-Seite angezeigt.</p>'),

	array(	'header' => 'Einstellungen',
			'text'   => '
<p>Du kannst hier sicherstellen, ob die benutzerkritischen Einstellungen im UU aktualisiert werden, aber sei mit einigen von ihnen SEHR vorsichtig, denn manche Einstellungen k�nnen deinen Benutzer �usserst ver�rgern und wenn Du falsche Parameter eintr�gst, k�nnte es sein, dass du all deine Benutzer verlierst! *lol*<br />
Mit 1 oder 0 kannst du die Einstellung im UU aktivieren (1), bzw. deaktivieren (0).</p>
<p>Die gespeicherte Werteliste ist die aktuelle Liste mit Dateien, die Du mit dem UU hochladen m�chtest.</p>'),

	array(	'header' => 'Statistiken',
			'text'   => '
<p>Diese Einstellung zeigt dir an, wer auf den UniAdmin zugreift</p>
<p>Die Tabelle zeigt dir jeden Zugriff an:</p>
<ul>
	<li> &quot;T�tigkeit&quot; - Wonach der Client fragt</li>
	<li> &quot;IP Adresse&quot; - Die IP-Adresse des Clients</li>
	<li> &quot;Datum/Uhrzeit&quot; - Datum / Uhrzeit des Zugriffs</li>
	<li> &quot;Benutzerclient&quot; - Welche Software zugegriffen hat</li>
	<li> &quot;Quellname&quot; - Die Ursprungs-ID des Benutzers</li>
</ul>
<p>Unter der Tabelle siehst du informative Kreisdiagramme, wie der UniAdmin erreicht worden ist.</p>'),

	array(	'header' => 'Benutzer',
			'text'   => '
<p>Es gibt 3 verschiedene &quot;Benutzerstufen&quot;</p>
<p>(Zeigt die h�chstmoglichen verf�gbaren Aktionen)</p>
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
<div class="ua_hr"><hr /></div>
<p>Zugangskontrolle f�r einzelne Bereiche:</p>
<ul>
	<li> 1: Addon-Verwaltung
		<ul>
			<li> 1.1: Addons verwalten</li>
			<li> 1.2: Hinzuf�gen/L�schen von Addons</li>
		</ul></li>
	<li> 2: Logo Verwaltung</li>
	<li> 3: Einstellungsverwaltung
		<ul>
			<li> 3.1: Hinzuf�gen/Entfernen der SavedVariable-Dateien</li>
			<li> 3.2: settings.ini hochladen/herunterladen</li>
		</ul></li>
	<li> 4: Statistikverwaltung</li>
	<li> 5: Benutzerverwaltung
		<ul>
			<li> 5.1: Sprache �ndern</li>
			<li> 5.2: Passwort �ndern</li>
			<li> 5.3: User l�schen</li>
			<li> 5.4: Usernamen �ndern</li>
			<li> 5.5: Benutzer der Stufe 1 hinzuf�gen</li>
			<li> 5.6: Informationen des Benutzers der Stufe 1 �ndern (Benutzername, Passwort, Sprache)</li>
			<li> 5.7: Stufe 1 Benutzer l�schen</li>
			<li> 5.8: User beliebiger Stufe hinzuf�gen</li>
			<li> 5.9: jeglichen Benutzer l�schen</li>
			<li> 5.10: jegliche Benutzerinformationen �ndern (Benutzername, Passwort, Stufe, Sprache)</li>
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
$lang['disabled'] = 'Deaktiviert';
$lang['files'] = 'Dateien';
$lang['url'] = 'URL';
$lang['delete'] = 'L�schen';
$lang['disable_enable'] = 'Deaktivieren / Aktivieren';
$lang['update_file'] = 'Datei aktualisieren';
$lang['updated'] = 'Aktualisiert';
$lang['setting_name'] = 'Einstellungsname';
$lang['description'] = 'Beschreibung';
$lang['value'] = 'Wert';
$lang['filename'] = 'Dateiname';
$lang['row'] = 'Zeile';
$lang['action'] = 'T�tigkeit';
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
$lang['add'] = 'Hinzuf�gen';
$lang['remove'] = 'Entfernen';
$lang['enable'] = 'Aktivieren';
$lang['disable'] = 'Deaktivieren';
$lang['modify'] = '�ndern';
$lang['check'] = '�berpr�fen';
$lang['proceed'] = 'Fortfahren';
$lang['reset'] = 'R�ckg�ngig machen';
$lang['submit'] = 'Abschicken';
$lang['upgrade'] = 'Erneuern';
$lang['update_logo'] = 'Logo aktualisieren %1$d';
$lang['update_settings'] = 'Einstellungen aktualisieren';
$lang['show'] = 'Zeigen';
$lang['add_update_addon'] = 'Hinzuf�gen / Addon aktualisieren';
$lang['update_addon'] = 'Addon aktualisieren';
$lang['import'] = 'Importieren';
$lang['export'] = 'Exportieren';
$lang['go'] = 'Go';


// Form Element Descriptions
$lang['current_password'] = 'Aktuelles Passwort';
$lang['current_password_note'] = 'Du muss dein aktuelles Passwort best�tigen, wenn du deinen Benutzernamen oder dein Passwort �ndern m�chtest';
$lang['confirm_password'] = 'Passwort best�tigen';
$lang['confirm_password_note'] = 'Du musst nur dein neues Passwort best�tigen wenn du es oben ge�ndert hast.';
$lang['language'] = 'Sprache';
$lang['new_password'] = 'Neues Passwort';
$lang['new_password_note'] = 'Du musst nur ein neues Passwort eingeben, wenn du es �ndern m�chtest';
$lang['change_username'] = 'Benutzername �ndern';
$lang['change_password'] = 'Passwort �ndern';
$lang['change_userlevel'] = 'Benutzerstufe �ndern';
$lang['change_language'] = 'Sprache �ndern';
$lang['basic_user_level_1'] = 'Benutzer (Stufe 1)';
$lang['power_user_level_2'] = 'Hauptbenutzer (Stufe 2)';
$lang['admin_level_3'] = 'Administrator (Stufe 3)';
$lang['password'] = 'Passwort';
$lang['retype_password'] = 'Passwort best�tigen';
$lang['old_password'] = 'Altes Passwort';
$lang['username'] = 'Benutzername';
$lang['users'] = 'Benutzer';
$lang['add_user'] = 'Benutzer hinzuf�gen';
$lang['modify_user'] = 'Benutzer �ndern';
$lang['current_users'] = 'Aktuelle Benutzer';
$lang['select_file'] = 'Datei ausw�hlen';
$lang['userlevel'] = 'Benutzerstufe';
$lang['get_wowace_addons'] = 'WoWAce Addons herunterladen';
$lang['addon_management'] = 'Addon-Verwaltung';
$lang['addon_uploaded'] = '%1$s wurde erfolgreich hochgeladen';
$lang['addon_edited'] = '%1$s was edited';
$lang['view_addons'] = 'Addons anzeigen';
$lang['required_addon'] = 'Ben�tigtes Addon';
$lang['homepage'] = 'Homepage';
$lang['logged_in_as'] = 'Eingeloggt als [%1$s]';
$lang['logo_table'] = 'Logo %1$d';
$lang['logo_uploaded'] = 'Logo %1$d wurde erfolgreich hochgeladen';
$lang['uniuploader_sync_settings'] = 'UniUploader Synchronisationseinstellungen';
$lang['uniadmin_config_settings'] = 'UniAdmin Konfigurationeinstellungen';
$lang['manage_svfiles'] = 'SavedVariable Dateien verwalten';
$lang['add_svfiles'] = 'SavedVariable Dateien hinzuf�gen';
$lang['svfiles'] = 'SavedVariable Dateien';
$lang['image_missing'] = 'Bild fehlt';
$lang['stats_limit'] = 'Zeile(n) ab der Aufzeichnung #';
$lang['user_modified'] = 'Benutzer %1$s ge�ndert';
$lang['user_added'] = 'Benutzer %1$s hinzugef�gt';
$lang['user_deleted'] = 'Benutzer %1$s gel�scht';
$lang['access_denied'] = 'Zugriff verweigert';
$lang['settings_file'] = 'settings.ini Datei';
$lang['import_file'] = 'Importiere Datei';
$lang['export_file'] = 'Exportiere Datei';
$lang['settings_updated'] = 'Einstellungen aktualisiert';
$lang['download'] = 'Download';
$lang['user_style'] = 'Benutzer Style';
$lang['change_style'] = '�ndere den Style';
$lang['fullpath_addon'] = 'Voll Pfad Addon';
$lang['addon_details'] = 'Addon Details';
$lang['manage'] = 'Verwalten';
$lang['optional'] = 'Optional';
$lang['notes'] = 'Kommentare';
$lang['half'] = 'Halb';
$lang['full'] = 'Voll';
$lang['edit'] = 'Editieren';
$lang['cancel'] = 'Abbrechen';
$lang['status'] = 'Status';


// Pagination
$lang['next_page'] = 'N�chste Seite';
$lang['page'] = 'Seite';
$lang['previous_page'] = 'Vorherige Seite';


// Miscellaneous
$lang['time_format'] = 'M jS, J g:ia';
$lang['syncro_url'] = 'Synchronisations URL';
$lang['verify_syncro_url'] = 'klicken, um zu �berpr�fen';
$lang['guest_access'] = 'Gastzugang';
$lang['interface_ready'] = 'UniUploader Aktualisierungsschnittstelle fertig...';


// Addon Management
$lang['addon_required_tip'] = 'Wenn aktiviert, UniUploader betrachted das Addon als obligatorischen Download';
$lang['addon_fullpath_tip'] = 'F�r Addons die direkt ins World of Warcraft Verzeichnis entpackt werden<br /><br />- [ja] Entpacke Addon in WoW/<br />- [nein] Entpacke Addon in WoW/Interface/Addons/';
$lang['addon_selectfile_tip'] = 'W�hle ein Addon zum Hochladen aus';


// WoWAce
$lang['new_wowace_list'] = 'Neue Liste von WoWAce.com heruntergeladen';


// Upgrader
$lang['ua_upgrade'] = 'UniAdmin Erneuerung';
$lang['no_upgrade'] = 'Du hast UniAdmin schon erneuert<br />Oder du hast schon eine neuere Version als diese Erneuerung';
$lang['select_version'] = 'W�hle Version';
$lang['success'] = 'Erfolg';
$lang['upgrade_complete'] = 'Deine UniAdmin Installation wurde erfolgreich upgegradet';
$lang['new_version_available'] = 'Es ist eine neue UniAdmin Version verf�gbar <span class="green">v%1$s</span><br /><a href="http://www.wowroster.net" target="_blank">Hier</a> herunterladen';


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
$lang['UUUPDATERCHECK'] = '�berpr�fe auf UniUploader Aktualisierungen';
$lang['SYNCHROURL'] = 'URL f�r die Synchronisation mit dem UniAdmin';
$lang['ADDONAUTOUPDATE'] = 'automatische Addon-Aktualisierung';
$lang['UUSETTINGSUPDATER'] = 'Synchronisiere UniUploader Einstellungen mit UniAdmin';

// options
$lang['AUTOUPLOADONFILECHANGES'] = 'Lade SavedVariable Datei bei �nderungen automatisch hoch';
$lang['ALWAYSONTOP'] = 'Zeige UniUploader immer im Vordergrund an';
$lang['SYSTRAY'] = 'Zeige UniUploader als SystemTray-Icon an';
$lang['ADDVAR1CH'] = 'Zus�tzliche Variablen 1';
$lang['ADDVARNAME1'] = 'Zus�tzlicher Name f�r Variable 1 (Standard-&gt;Benutzername)';
$lang['ADDVARVAL1'] = 'Zus�tzlicher Wert f�r Variable 1';
$lang['ADDVAR2CH'] = 'Zus�tzliche Variablen 2';
$lang['ADDVARNAME2'] = 'Zus�tzlicher Name f�r Variable 2 (Standard-&gt;Passwort)';
$lang['ADDVARVAL2'] = 'Zus�tzlicher Wert f�r Variable 2';
$lang['ADDVAR3CH'] = 'Zus�tzliche Variablen 3';
$lang['ADDVARNAME3'] = 'Zus�tzlicher Name f�r Variable 3';
$lang['ADDVARVAL3'] = 'Zus�tzlicher Wert f�r Variable3';
$lang['ADDVAR4CH'] = 'Zus�tzliche Variablen 4';
$lang['ADDVARNAME4'] = 'Zus�tzlicher Name f�r Variable 4';
$lang['ADDVARVAL4'] = 'Zus�tzlicher Wert f�r Variable 4';
$lang['ADDURL1CH'] = 'Zus�tzliche URL 1';
$lang['ADDURL1'] = 'Zus�tzliche URL 1 Lokation';
$lang['ADDURL2CH'] = 'Zus�tzliche URL 2';
$lang['ADDURL2'] = 'Zus�tzliche URL 2 Lokation';
$lang['ADDURL3CH'] = 'Zus�tzliche URL 3';
$lang['ADDURL3'] = 'Zus�tzliche URL 3 Lokation';
$lang['ADDURL4CH'] = 'Zus�tzliche URL 4';
$lang['ADDURL4'] = 'Zus�tzliche URL 4 Lokation';

// advanced
$lang['AUTOLAUNCHWOW'] = 'WoW automatisch starten';
$lang['WOWARGS'] = 'Starte mit Parametern';
$lang['STARTWITHWINDOWS'] = 'Starte UniUploader mit Windows';
$lang['USELAUNCHER'] = 'Starte WoW mit dem UU-Launcher';
$lang['STARTMINI'] = 'Starte minimiert';
$lang['SENDPWSECURE'] = 'MD5 Wert vor dem Abschicken verschl�sseln';
$lang['GZIP'] = 'gZip Kompression';
$lang['DELAYUPLOAD'] = 'Verz�gerung beim hochladen';
$lang['DELAYSECONDS'] = 'Zeitverz�gerung in Sekunden';
$lang['RETRDATAFROMSITE'] = 'Web==&gt;WoW - Daten empfangen';
$lang['RETRDATAURL'] = 'Web==&gt;WoW - Datenempfang an URL';
$lang['WEBWOWSVFILE'] = 'Web==&gt;WoW - SavedVariable Dateinamen schreiben';
$lang['DOWNLOADBEFOREWOWL'] = 'Web==&gt;WoW - Vor dem UU Start initiieren WoW';
$lang['DOWNLOADBEFOREUPLOAD'] = 'Web==&gt;WoW - Bevor UU hochl�dt, initiieren';
$lang['DOWNLOADAFTERUPLOAD'] = 'Web==&gt;WoW - Nachdem UU hochgeladen hat, initiieren ';

// END UU Sync Strings


// BEGIN UA CONFIG SETTINGS

$lang['admin']['addon_folder'] = 'Addon Zip Verzeichnis|Bestimme das Verzeichnis wo Addon Zip dateien gespeichert werden';
$lang['admin']['check_updates'] = 'Nach Aktualisierungen f�r UA pr�fen|Pr�ft wowroster.net ob eine neuere Version von UniAdmin verf�gbar ist.';
$lang['admin']['default_lang'] = 'Default Sprache|Default Sprache f�r die UniAdmin Benutzeroberfl�che<br /><br />Werte weden automatisch aus dem &quot;languages&quot; Verzeichnis ausgelesen';
$lang['admin']['default_style'] = 'Default Style|Der Default Display Style';
$lang['admin']['enable_gzip'] = 'Gzip Komprimierung|gzip Komprimierung f�r die Darstellung von UniAdmin seiten aktivieren';
$lang['admin']['interface_url'] = 'Interface URL|Bestimme das Verzeichnis, wo sich interface.php befindet<br /><br />Benutze %url% um die Basis-Url einzuf�gen.<br />Default-Wert ist &quot;%url%?p=interface&quot; oder &quot;%url%interface.php&quot;';
$lang['admin']['logo_folder'] = 'Logo Verzeichnis|Bestimme das Verzeichnis, wo UniUploader Logos speichern soll';
$lang['admin']['temp_analyze_folder'] = 'Tempor�res Addon Analyse Verzeichnis|Bestimme das Verzeichnis wo Addon zip-Dateien f�r die Analyse entpackt werden';
$lang['admin']['UAVer'] = 'UniAdmin Version|Aktuelle UniAdmin Version<br />Du kannst diesen Wert nicht �ndern';
$lang['admin']['ua_debug'] = 'Debug Modus|Debugging f�r UniAdmin<br /><br />- [Nein] Kein Debugging<br />- [Halb] Zeige Abfragenanzahl und Renderzeit im Footer<br />- [Voll] Zeige Abfragenanzahl, Renderzeit und SQL-Abfragen-Fenster im Footer';

// END UA CONFIG SETTINGS


// Debug
$lang['queries'] = 'Fragen';
$lang['debug'] = 'Pr�fen';
$lang['messages'] = 'Nachrichten';


// Error messages
$lang['error'] = 'Fehler';
$lang['error_invalid_login'] = 'Du hast ein falsches Passwort oder einen unzul�ssigen Benutzernamen eingegeben';
$lang['error_delete_addon'] = 'Fehler bei L�schung des Addons';
$lang['error_enable_addon'] = 'Fehler bei Aktivierung des Addons';
$lang['error_disable_addon'] = 'Fehler bei Deaktivierung des Addons';
$lang['error_require_addon'] = 'Fehler bei Ben�tigung des Addons';
$lang['error_optional_addon'] = 'Optionaler Addon Fehler';
$lang['error_no_addon_in_db'] = 'Keine Addons in der Datenbank';
$lang['error_no_addon_uploaded'] = 'Keine Addons hochgeladen';
$lang['error_no_files_addon'] = 'Es wurden keine Dateien im hochgeladenen Addon gefunden.';
$lang['error_no_toc_file'] = 'Keine \'.toc\' Datei wurde im hochgeladenen Addon gefunden.';
$lang['error_unzip'] = 'Zip Fehler';
$lang['error_pclzip'] = 'PCLZip Fehler nicht zu beheben: [%1$s]';
$lang['error_addon_process'] = 'Fehler beim verarbeiten des Addons';
$lang['error_zip_file'] = 'Das hochgeladene Addon <u>muss</u> eine ZIP-Datei sein';
$lang['error_addon_not_exist'] = 'Addon mit ID:%1$s exisitert nicht';

$lang['error_no_ini_uploaded'] = 'settings.ini Datei wurde nicht hochgeladen.';
$lang['error_ini_file'] = 'Die hochgeladene Datei <u>muss</u> eine settings.ini vom UniUploader sein';

$lang['error_chmod'] = 'Die Berechtigung auf [%1$s] konnte nicht ge�ndert werden.<br />�ndere die Berechtigung manuell und/oder checke die Dateiberechtigungen';
$lang['error_mkdir'] = 'Der Ordner [%1$s] konnte nicht erstellt werden.<br />Erstelle manuell und/oder checke die Dateiberechtigungen';
$lang['error_unlink'] = '[%1$s] konnte nicht gel�scht werden.<br />L�sche manuell und/oder checke die Dateiberechtigungen';
$lang['error_move_uploaded_file'] = 'Konnte nicht Datei [%1$s] nach [%2$s] bewegen<br />�berpr�fe die PHP-Upload-Einstellungen und Dateiberechtigungen';
$lang['error_write_file'] = 'Konnte nicht [%1$s] schreiben<br />�berpr�fe Zugriffsrechte';

$lang['error_no_uploaded_logo'] = 'Kein Logo hochgeladen';
$lang['error_logo_format'] = 'Die hochgeladene Datei <u>muss</u> ein GIF-Bild sein';

$lang['error_name_required'] = 'Name fehlt';
$lang['error_pass_required'] = 'Passwort fehlt';
$lang['error_pass_mismatch'] = 'Passw�rter stimmen nicht �berein';
$lang['error_pass_mismatch_edit'] = 'Passw�rter stimmen nicht �berein<br />Altes Passwort unge�ndert';

$lang['error_no_wowace_addons'] = 'Keine WoWAce Addons in der abgerufenen Liste';

$lang['error_upgrade_needed'] = 'UniAdmin wird erneuert.<br />Bitte mit Admin Account einloggen um weiterzumachen';

// SQL Error Messages
$lang['sql_error'] = 'SQL Fehler';
$lang['sql_error_addons_list'] = 'Addonliste nicht erhalten.';
$lang['sql_error_addons_disable'] = 'Addon mit ID:%1$d konnte nicht deaktiviert werden.';
$lang['sql_error_addons_enable'] = 'Addon mit ID:%1$d konnte nicht aktiviert.';
$lang['sql_error_addons_require'] = 'Addon mit ID:%1$d konnte nicht als erforderlich gesetzt werden.';
$lang['sql_error_addons_optional'] = 'Addon mit ID:%1$d konnte nicht als optional gesetzt werden.';
$lang['sql_error_addons_delete'] = 'Addon mit ID:%1$d konnte nicht aus der Datenbank gel�scht werden<br />Entferne manuell.';
$lang['sql_error_addons_insert'] = 'Konnte Addon-Hauptinformationen nicht setzen.';
$lang['sql_error_addons_files_insert'] = 'Konnte Daten einer Addon-Dateien nicht hinzuf�gen';

$lang['sql_error_logo_toggle'] = 'Konnte Logo %1$s nicht setzen';
$lang['sql_error_logo_remove'] = 'Konnte Logo mit id=%1$d nicht aus der Datenbank entfernen';
$lang['sql_error_logo_insert'] = 'Konnte Logo nicht in die Datenbank einf�gen';

$lang['sql_error_settings_update'] = 'Konnte Einstellungen nicht aktualisieren =&gt; %1$s, Wert =&gt; %2$s, Aktiviert =&gt; %3$d';
$lang['sql_error_settings_sv_insert'] = 'Konnte nicht savedvariable Dateiname &quot;%1$s&quot; hinzuf�gen';
$lang['sql_error_settings_sv_remove'] = 'Konnte nicht savedvariable Dateiname &quot;%1$s&quot; entfernen';

$lang['sql_error_user_add'] = 'Konnte Benutzer &quot;%1$s&quot; nicht hinzuf�gen';
$lang['sql_error_user_delete'] = 'Konnte Benutzer &quot;%1$s&quot; nicht l�schen';

