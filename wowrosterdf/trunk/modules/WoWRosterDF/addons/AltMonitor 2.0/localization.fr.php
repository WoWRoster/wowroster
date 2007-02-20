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

// -[ frFR Localization ]-

// Installer
$wordings['frFR']['AltMonitor_install_page']= 'Installation d\'AltMonitor';
$wordings['frFR']['AltMonitor_install']     = 'Les tables de AltMonitor n\'ont pas encore été installée. Cliquez sur Install pour débuter l\'installation.';
$wordings['frFR']['AltMonitor_upgrade']     = 'Les tables de AltMonitor ne sont pas à jour. Cliquez sur MAJ pour mettre à jour la base de données ou cliquez sur Install pour effacer et recréer les tables nécessaires à AltMonitor.';
$wordings['frFR']['AltMonitor_no_upgrade']  = 'Les tables de AltMonitor sont déjà à jour. Cliquez ci-dessous sur Reinstall pour réinstaller les tables.';
$wordings['enUS']['AltMonitor_uninstall']   = 'This will remove the AltMonitor configuration and main/alt relations. Click \'Uninstall\' to proceed';
$wordings['frFR']['AltMonitor_installed']   = 'Félicitations, AltMonitor a été installé avec succès. Cliquez sur le lien ci dessous pour le configurer.';
$wordings['enUS']['AltMonitor_uninstalled'] = 'AltMonitor has been uninstalled. You may now delete the addon from your webserver.';

// Main/Alt display
$wordings['frFR']['AltMonitor_Menu']        = 'Mains/Rerolls';
$wordings['frFR']['AltMonitor_NoAction']    = 'Action invalide : Veuillez vérifier si vous avez correctement tapé l\'url. Si vous avez accédé à cette page via le lien présent sur cet addon, signalez ce bug sur les forums de WoWroster.';
$wordings['frFR']['main_name']              = 'Nom du perso principal';
$wordings['frFR']['altlist_menu']           = 'Ouvrir/Fermer l\'arborescence';
$wordings['frFR']['altlist_close']          = 'Fermer Tout';
$wordings['frFR']['altlist_open']           = 'Ouvrir Tout';

// Configuration
$wordings['frFR']['AltMonitor_config']      = 'Configurer AltMonitor';
$wordings['frFR']['AltMonitor_config_page'] = 'Configuration d\'AltMonitor';
$wordings['frFR']['documentation']          = 'Documentation';
$wordings['frFR']['updMainAlt']             = 'Mettre a jour les relations';
$wordings['frFR']['uninstall']              = 'Uninstall';

// Page names
$wordings['frFR']['admin']['build']         = 'Relations Main/Reroll';
$wordings['frFR']['admin']['display']       = 'Montrer';

// Settings names on build page
$wordings['frFR']['admin']['getmain_regex'] = 'Regex|Les 3 premières variables définissent la façon dont le regex est extrait depuis les infos du membre. <br /> Voir le wiki pour les détails. <br /> Ce champ défini quel regex utiliser.';
$wordings['frFR']['admin']['getmain_field'] = 'Appliquer sur le champ|Les 3 premières variables définissent la façon dont le regex est extrait depuis les infos du membre. <br /> Voir le wiki pour les détails. <br />Ce champ défini sur quel champ du membre le regex est appliqué.';
$wordings['frFR']['admin']['getmain_match'] = 'Use match no|Les 3 premières variables définissent la façon dont le regex est extrait depuis les infos du membre. <br /> Voir le wiki pour les détails. <br /> Ce champ défini quel valeur de retour est utilisée par le regex.';

$wordings['frFR']['admin']['getmain_main']  = 'Identification du perso principal|Si le regex retourne cette valeur le perso est considéré comme un perso principal.';
$wordings['frFR']['admin']['defmain']       = 'Pas de résultats|Définir ce que sera le perso si le regex ne retourne rien.';
$wordings['frFR']['admin']['invmain']       = 'Résultat invalide|Définir ce que sera le perso si le regex donne un perso n\'étant pas dans la guilde ou correspondant a un perso principal.';
$wordings['frFR']['admin']['altofalt']      = 'Reroll de Reroll|Définir ce qu\'il faut faire si le perso est sans perso principal.';

$wordings['frFR']['admin']['update_type']   = 'Type de mise à jour | Indiquez le type de mise à jour de la relation main/alt';

// Settings names on display page
$wordings['frFR']['admin']['showmain']      = 'Montrer le nom du perso principal|Définir si vous voulez montrer ou masquer le nom du perso principal dans la liste des rerolls.';
$wordings['frFR']['admin']['altopen']       = 'Arborescence des Rerolls|Définir si vous voulez l\'arborescence des rerolls ouverte ou fermée par défaut.';
$wordings['frFR']['admin']['mainlessbottom']= 'Montrer les persos sans perso principal|Définir si vous voulez montrer les persos sans perso principal au début ou à la fin de la liste.';

// Translators:
//
// Harut
// Antaros
// Malkom
