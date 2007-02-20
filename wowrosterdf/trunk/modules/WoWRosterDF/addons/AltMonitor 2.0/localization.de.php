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

// -[ deDE localization ]-

// Installer
$wordings['deDE']['AltMonitor_install_page']= 'AltMonitor Installationssystem';
$wordings['deDE']['AltMonitor_install']     = 'Die AltMonitor Tabellen sind noch nicht installiert. Dr�cke Install um die benötigten Tabellen zu erzeugen.';
$wordings['deDE']['AltMonitor_upgrade']     = 'Die AltMonitor Tabellen sind nicht auf dem neuesten Stand. Betätige Update um die Datenbank zu aktualisieren oder Install um die Tabellen zu löschen und neu zu erzeugen.';
$wordings['deDE']['AltMonitor_no_upgrade']  = 'Die AltMonitor Tabellen sind schon auf dem neuesten Stand. Betätige Reinstall um die Tabellen nochmal zu installieren.';
$wordings['enUS']['AltMonitor_uninstall']   = 'Dies wird die AltMonitor Konfiguration und Main/ALT-Beziehungen löschen. Klicken Sie \'Deinstallation\' um fortzufahren.';
$wordings['deDE']['AltMonitor_installed']   = 'Glückwunsch, AltMonitor wurde erfolgreich installiert. Klicke auf den unten stehenden Link um es zu Konfigurieren.';
$wordings['enUS']['AltMonitor_uninstalled'] = 'AltMonitor ist deinstalliert worden. Sie können jetzt das Addon von Ihrem Webserver löschen.';

// Main/Alt display
$wordings['deDE']['AltMonitor_Menu']        = 'Mains/Twinks';
$wordings['deDE']['AltMonitor_NoAction']    = 'Schau bitte nach, ob die URL korrekt ist, da eine inkorrekte Aktion getätigt wurde. Bist du einem Link innerhalb dieses Addons gefolgt, mache bitte einen Bugreport im WoWroster Forum.';
$wordings['deDE']['main_name']              = 'Name des Mains';
$wordings['deDE']['altlist_menu']           = 'Öffne/Schließe alle Tabs';
$wordings['deDE']['altlist_close']          = 'Alle öffnen';
$wordings['deDE']['altlist_open']           = 'Alle schließen';

// Configuration
$wordings['deDE']['AltMonitor_config']      = '&Ouml;ffne die AltMonitor Konfiguration';
$wordings['deDE']['AltMonitor_config_page'] = 'AltMonitor Konfiguration';
$wordings['deDE']['documentation']          = 'Documentation';
$wordings['deDE']['updMainAlt']             = 'Beziehungen Aktualisieren';
$wordings['deDE']['uninstall']              = 'Deinstallation';

// Page names
$wordings['deDE']['admin']['build']         = 'Main/Twink Beziehungen';
$wordings['deDE']['admin']['display']       = 'Anzeige';

// Settings names on build page
$wordings['deDE']['admin']['getmain_regex'] = 'Regex|Die oberen 3 Variablen definieren wie Regex aus der Mitgliederinformation herausgefiltert werden soll. <br /> Für mehr Details, benutzte den Link auf die Wiki Seite. <br /> Dieses Feld gibt an, wie Regex arbeiten soll.';
$wordings['deDE']['admin']['getmain_field'] = 'Auf dieses Feld beziehen| Die oberen 3 Variablen definieren wie Regex aus der Mitgliederinformation herausgefiltert werden soll. <br /> Für mehr Details, benutzte den Link auf die Wiki Seite. <br /> Dieses Feld gibt an, aus welchen Mitgliederfeld Regex seine Informationen beziehen soll.';
$wordings['deDE']['admin']['getmain_match'] = 'Benutze Treffer Nr. | Die oberen 3 Variablen definieren wie Regex aus der Mitgliederinformation herausgefiltert werden soll. <br /> Für mehr Details, benutzte den Link auf die Wiki Seite. <br /> Dieses Feld gibt an, welcher Rückgabewert von Regex benutzt werden soll.';

$wordings['deDE']['admin']['getmain_main']  = 'Main Identifikator| Wenn Regex diesen Wert findet, wird der Charakter als Main erkannt.';
$wordings['deDE']['admin']['defmain']       = 'Kein Ergebnis|Gebe an, als was ein Charakter eingestuft werden soll, wenn Regex nicht\'s findet.';
$wordings['deDE']['admin']['invmain']       = 'Ungültiges Ergebnis|Gebe an, als was ein Charakter eingestuft werden soll, wenn Regex ein Ergebnis findet, dass keinem Gildenmitglied zugeordnet werden kann oder mit dem Main Identifikator identisch ist.';
$wordings['deDE']['admin']['altofalt']      = 'Twink eines Twinks|Gebe an, als was ein Charakter eingestuft werden soll, wenn er als Twink ohne Main erkannt wurde.';

$wordings['deDE']['admin']['update_type']   = 'Update typ|Geben Sie an welchen Trigger typ Sie zum aktualisieren der Main/Alt-Beziehungen benutzen wollen.';

// Settings names on display page
$wordings['deDE']['admin']['showmain']      = 'Zeige den Namen des Mains|Gebe an, ob du möchtest, dass der Name des Mains in der Twink-Liste angezeigt werden soll oder nicht.';
$wordings['deDE']['admin']['altopen']       = 'Aufgeklappte Twinks|Gebe an, ob du möchtest, dass standardmäßig alle Twinks ausgeklappt sein sollen oder nicht.';
$wordings['deDE']['admin']['mainlessbottom']= 'Position der Mainlosen Twinks|Gebe an ob die Mainlosen Twinks über oder unterhalb der Liste angezeigt werden sollen.';

// Translators:
//
// Geschan
// Sphinx
// Lunzet


// Notes from German translator:

// UTF-8
// ä = �
// Ä = �
// ö = �
// &Ouml;  = �  //Da fehlt mir irgendwie die Kurzform ;)
// ü = �
// Ü = �
// ß = �
// ´ = �
//  = "
