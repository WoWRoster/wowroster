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
$lang['title_help'] = 'Aide';
$lang['title_addons'] = 'AddOns';
$lang['title_wowace'] = 'WoWAce';
$lang['title_logo'] = 'Logos';
$lang['title_settings'] = 'Paramètres';
$lang['title_stats'] = 'Statistiques';
$lang['title_users'] = 'Utilisateurs';
$lang['title_config'] = 'Config UniAdmin';
$lang['title_login'] = 'Login';


// Help page text
$lang['help'] = array(
	array(	'header' => 'Intro',
			'text'   => '
<p>Je parie que vous vous demandez soit ce que c\'est ou comment vous en servir, donc:</p>
<p>C\'est un système qui sert à tenir à jour les addons, logos et réglages des utilisateurs (qui utilisent UniUploader).<br />
Quand vous chargez un addon dans le système et cliquez [Mise à jour] dans UU, UU va regarder &quot;l\'URL de Synchronisation&quot;<br />
et procède au téléchargement des mises à jour qui sont différentes d\'une façon ou d\'une autre de la copie stockée sur le disque dur de l\'utilisateur.<br />
UU remplacera alors l\'addon par la nouvelle copie de l\'addon depuis ce système-ci.</p>'),

	array(	'header' => 'AddOns',
			'text'   => '
<p>L\'addon chargé doit être au format zip seulement.<br />
Le fichier zip doit avoir l\'arborescence de répertoires suivante: [dossier],{fichier}, et non littéralement &quot;nomAddon&quot; ou &quot;fichierAddon&quot;<br />
Le Nom de l\'Addon est le même que le nom du dossier dans lequel les fichiers de l\'Addon se trouvent</p>
<pre>[nomAddon]
     {fichierAddon}
     {fichierAddon}
     {fichierAddon}
     {fichierAddon}
or
[Interface]
     [AddOns]
          [nomAddon]
               {fichierAddon}
               {fichierAddon}
               {fichierAddon}
               {fichierAddon}
[Fonts]
     font.ttf</pre>'),

array(	'header' => 'WoWAce',
'text'   => '
<p>Ce module permet de télécharger des addons depuis le repository SVN de WoWAce.com SVN</p>
<p>Seul l\'utilisateur Admin UniAdmin peut accéder à ce module</p>'),

	array(	'header' => 'Logos',
			'text'   => '
<p>Ceci change les logos affichés dans UniUploader/jUniUploader<br />
Logo 1 est affiché sur l\'onglet [Paramètres]<br />
Logo 2 est affiché sur l\'onglet [A propos]</p>'),

	array(	'header' => 'Réglages',
			'text'   => '
<p>Vous pouvez vous assurer que les paramètres critiques d\'UU des utilisateurs sont à jour avec ceci, soyez TRES prudent avec certains réglages, puisque certains peuvent rendrent vos utilisateurs furieux après vous, et si vous mettez un mauvais paramètre, vous pourriez perdre le contact avec tous vos utilisateurs MDR<br />
Si le réglage est un 1 ou zéro cela signifie que c\'est une case à cocher dans UU qui doit être: cochée (1) ou décochée (0).</p>
<p>La liste des variables sauvegardées est la liste de fichiers que vous voulez que UU charge vers les (la seule) URL(s).</p>'),

	array(	'header' => 'Statistiques',
			'text'   => '
<p>Ceci montre qui accède à UniAdmin</p>
<p>La table montre chaque accès</p>
<ul>
	<li> &quot;Action&quot; - Ce que le client demande</li>
	<li> &quot;Adresse IP&quot; - L\'adresse IP du client</li>
	<li> &quot;Date/Heure&quot; - La date/heure d\'accès</li>
	<li> &quot;User Agent&quot; - Le logiciel qui accède</li>
	<li> &quot;Nom d\'Hôte&quot; - Le Nom d\'Hôte de l\'utilisateur</li>
</ul>
<p>Sous la table, on trouve de jolis camenberts sur comment UniAdmin à été accédé</p>'),

	array(	'header' => 'Utilisateurs',
			'text'   => '
<p>Il y a 3 &quot;user levels&quot;</p>
<p>(Montre l\'action la plus élevée disponible)</p>
<dl>
	<dt>niveau 1 (utilisateur standard) a accès à</dt>
	<dd>1, 2, 3, 4, 5.3</dd>

	<dt>niveau 2 (utilisateur expérimenté) a accès à</dt>
	<dd>1.1, 2, 3.1, 4, 5.7</dd>

	<dt>niveau 3 (administrateur) a accès à tout</dt>
	<dd>1.2, 2, 3.2, 4, 5.10, 6</dd>
	<dd>&nbsp;</dd>
</dl>
<p>Il ne devrait pas y avoir plus de 1 ou 2 utilisateurs de &quot;niveau 3&quot; dans UniAdmin</p>
<div class="ua_hr"><hr /></div>
<p>Légende des codes d\'accès:</p>
<ul>
	<li> 1: Gestion des AddOns
		<ul>
			<li> 1.1: Gérer les AddOns</li>
			<li> 1.2: Ajouter/Supprimer des AddOns</li>
		</ul></li>
	<li> 2: Gestion des Logos</li>
	<li> 3: Gestion des Paramètres
		<ul>
			<li> 3.1: Ajouter/Supprimer des Fichiers SavedVariable</li>
			<li> 3.2: charger/télécharger settings.ini</li>
		</ul></li>
	<li> 4: Gestion des Statistiques</li>
	<li> 5: Gestion des Utilisateurs
		<ul>
			<li> 5.1: Changer sa propre langue</li>
			<li> 5.2: Changer son mot de passe</li>
			<li> 5.3: Supprimmer son propre utilisateur</li>
			<li> 5.4: Changer son nom d\'utilisateur</li>
			<li> 5.5: Ajouter des utilisateurs de niveau 1</li>
			<li> 5.6: Changer les informations des utilisateurs de niveau 1 (nom d\'utilisateur, mot de passe, langue)</li>
			<li> 5.7: Supprimer des utilisateurs de niveau 1</li>
			<li> 5.8: Ajouter des utilisateurs de tout niveau</li>
			<li> 5.9: Supprimer n\'importe quel utilisateur</li>
			<li> 5.10: Changer les informations de n\'importe quel utilisateur (nom d\'utilisateur, mot de passe, langue)</li>
		</ul></li>
	<li> 6: Configuration d\'UniAdmin</li>
</ul>'),
);








// Column Headers
$lang['name'] = 'Nom';
$lang['toc'] = 'TOC';
$lang['required'] = 'Requis';
$lang['version'] = 'Version';
$lang['uploaded'] = 'Chargé';
$lang['enabled'] = 'Activé';
$lang['disabled'] = 'Désactive';
$lang['files'] = 'Fichiers';
$lang['url'] = 'URL';
$lang['delete'] = 'Supprimer';
$lang['disable_enable'] = 'Désactiver / Activer';
$lang['update_file'] = 'Mettre à jour le Fichier';
$lang['updated'] = 'Mis à Jour';
$lang['setting_name'] = 'Nom du Paramètre';
$lang['description'] = 'Description';
$lang['value'] = 'Valeur';
$lang['filename'] = 'NomFichier';
$lang['row'] = 'Ligne';
$lang['action'] = 'Action';
$lang['ip_address'] = 'Adresse IP';
$lang['date_time'] = 'Date/Heure';
$lang['user_agent'] = 'User Agent';
$lang['host_name'] = 'Nom d\'Hôte';



// Submit Buttons
$lang['login'] = 'Login';
$lang['logout'] = 'Logout';
$lang['on'] = 'On';
$lang['off'] = 'Off';
$lang['no'] = 'Non';
$lang['yes'] = 'Oui';
$lang['add'] = 'Ajouter';
$lang['remove'] = 'Supprimer';
$lang['enable'] = 'Activer';
$lang['disable'] = 'Désactiver';
$lang['modify'] = 'Modifier';
$lang['check'] = 'Vérifier';
$lang['proceed'] = 'Continuer';
$lang['reset'] = 'Réinitialiser';
$lang['submit'] = 'Soumettre';
$lang['upgrade'] = 'Mettre à Jour';
$lang['update_logo'] = 'Mettre à jour le Logo %1$d';
$lang['update_settings'] = 'Mettre à jour les Paramètres';
$lang['show'] = 'Montrer';
$lang['add_update_addon'] = 'Ajouter / Mettre à jour AddOn';
$lang['update_addon'] = 'Mettre à jour AddOn';
$lang['import'] = 'Importer';
$lang['export'] = 'Exporter';
$lang['go'] = 'Go';


// Form Element Descriptions
$lang['current_password'] = 'Mot de Passe Actuel';
$lang['current_password_note'] = 'Vous devez confirmer votre mot de passe actuel si vous souhaitez changer de nom d\'utilisateur ou de mot de passe';
$lang['confirm_password'] = 'Confirmer Mot de Passe';
$lang['confirm_password_note'] = 'Vous n\'avez à confirmer votre nouveau mot de passe que si vous l\'avez changé ci-dessus';
$lang['language'] = 'Langue';
$lang['new_password'] = 'Nouveau Mot de Passe';
$lang['new_password_note'] = 'Vous n\'avez a fournir un nouveau mot de passe que si vous voulez en changer';
$lang['change_username'] = 'Changer Nom d\'Utilisateur';
$lang['change_password'] = 'Changer Mot de Passe';
$lang['change_userlevel'] = 'Changer le Niveau Utilisateur';
$lang['change_language'] = 'Changer de Langue';
$lang['basic_user_level_1'] = 'Utilisateur Standard (niveau 1)';
$lang['power_user_level_2'] = 'Utilisateur Expérimenté (niveau 2)';
$lang['admin_level_3'] = 'administrateur (niveau 3)';
$lang['password'] = 'Mot de Passe';
$lang['retype_password'] = 'Retaper Mot de Passe';
$lang['old_password'] = 'Ancien Mot de Passe';
$lang['username'] = 'Nom d\'Utilisateur';
$lang['users'] = 'Utilisateurs';
$lang['add_user'] = 'Ajouter Utilisateur';
$lang['modify_user'] = 'Modifier Utilisateur';
$lang['current_users'] = 'Utilisateurs Actuels';
$lang['select_file'] = 'Choisir fichiers';
$lang['userlevel'] = 'Niveau Utilisateur';
$lang['get_wowace_addons'] = 'Télécharger des Addons WoWAce';
$lang['addon_management'] = 'Gestion des AddOns';
$lang['addon_uploaded'] = '%1$s a été chargé avec succès';
$lang['addon_edited'] = '%1$s a été modifié';
$lang['view_addons'] = 'Voir AddOns';
$lang['required_addon'] = 'AddOns Obligatoires';
$lang['homepage'] = 'Site Web';
$lang['logged_in_as'] = 'Connecté en tant que [%1$s]';
$lang['logo_table'] = 'Logo %1$d';
$lang['logo_uploaded'] = 'Logo %1$d a été chargé avec succès';
$lang['uniuploader_sync_settings'] = 'Réglages de Synchro UniUploader';
$lang['uniadmin_config_settings'] = 'Réglages de Config UniAdmin';
$lang['manage_svfiles'] = 'Gérer les Fichiers SavedVariable';
$lang['add_svfiles'] = 'Ajouter des Fichiers SavedVariable';
$lang['svfiles'] = 'Fichiers SavedVariable';
$lang['image_missing'] = 'IMAGE MANQUANTE';
$lang['stats_limit'] = 'ligne(s) à partir de l\'entrée #';
$lang['user_modified'] = 'Utilisateur %1$s modifié';
$lang['user_added'] = 'Utilisateur %1$s ajouté';
$lang['user_deleted'] = 'Utilisateur %1$s supprimé';
$lang['access_denied'] = 'Accès Refusé';
$lang['settings_file'] = 'Fichier settings.ini';
$lang['import_file'] = 'Importer Fichier';
$lang['export_file'] = 'Exporter Fichier';
$lang['settings_updated'] = 'Paramètres Mis à Jour';
$lang['download'] = 'Télécharger';
$lang['user_style'] = 'Style Utilisateur';
$lang['change_style'] = 'Changer de Style';
$lang['fullpath_addon'] = 'Chemin Complet Addon';
$lang['addon_details'] = 'Détails AddOn';
$lang['manage'] = 'Gérer';
$lang['optional'] = 'Facultatif';
$lang['notes'] = 'Notes';
$lang['half'] = 'Demi';
$lang['full'] = 'Complet';
$lang['edit'] = 'Editer';
$lang['cancel'] = 'Annuler';
$lang['status'] = 'Statut';
$lang['automatic'] = 'Automatique';
$lang['delete_all_addons'] = 'Supprimer tous les Addons';


// Pagination
$lang['next_page'] = 'Page Suivante';
$lang['page'] = 'Page';
$lang['previous_page'] = 'Page Précédente';


// Miscellaneous
$lang['time_format'] = 'jS M, Y g:ia';
$lang['syncro_url'] = 'URL de Synchronisation';
$lang['verify_syncro_url'] = 'cliquer pour vérifier';
$lang['guest_access'] = 'Accès Invité';
$lang['interface_ready'] = 'Interface de Mise à Jour UniUploader...';


// Addon Management
$lang['addon_required_tip'] = 'Quand coché, UniUploader exigera le téléchargement de cet addon';
$lang['addon_fullpath_tip'] = 'Ce paramètre est pour les addons qui extraient directement dans le répertoire de World of Warcraft<br /><br />- [oui] Extraire addon dans WoW/<br />- [non] Extraire dans WoW/Interface/AddOns/<br />- [Automatique]Détection automatique de l\'emplacement';
$lang['addon_selectfile_tip'] = 'Selectionner un addon à charger';
$lang['confirm_addons_delete'] = 'Ceci supprimera TOUS les addons de la base de donnée et du serveur web. Etes-vous sûr ?';
$lang['all_addons_delete'] = 'Tous les addons ont été supprimés de la base de donnée et du serveur web';


// WoWAce
$lang['new_wowace_list'] = 'Nouvelle liste téléchargée de WoWAce.com';


// Upgrader
$lang['ua_upgrade'] = 'Mise à jour d\'UniAdmin';
$lang['no_upgrade'] = 'Vous avez déjà mis à jour UniAdmin<br />Ou vous avez une version plus à jour que cet upgrader';
$lang['select_version'] = 'Selectionner Version';
$lang['success'] = 'Réussite';
$lang['upgrade_complete'] = 'Votre installation UniAdmin a été mise à jour';
$lang['new_version_available'] = 'Une nouvelle version d\'UniAdmin est disponible <span class="green">v%1$s</span><br />Télécharger <a href="http://www.wowroster.net" target="_blank">ICI</a>';


// UU Sync Settings

// Each setting for the UniUploader Setting Sync is listed here
// The keyname has to be exactly the same as the name in the DB and the name thats is used in UniUploader
// Any text must be html entity encoded first!
// Each group is separated by section based on the settings.ini file

// settings
$lang['LANGUAGE'] = 'Langue';
$lang['PRIMARYURL'] = 'Charger les fichiers SavedVariable vers cette URL';
$lang['PROGRAMMODE'] = 'Mode du programme';
$lang['AUTODETECTWOW'] = 'Auto détecter WoW';
$lang['OPENGL'] = 'Executer WoW en mode OpenGL';
$lang['WINDOWMODE'] = 'Executer WoW en fenêtré';

// updater
$lang['UUUPDATERCHECK'] = 'Vérifier les mises à jour d\'UniUploader';
$lang['SYNCHROURL'] = 'URL de synchronisation avec UniAdmin';
$lang['ADDONAUTOUPDATE'] = 'Mise à jour automatique des AddOns';
$lang['UUSETTINGSUPDATER'] = 'Synchroniser les réglages UniUploader avec UniAdmin';

// options
$lang['AUTOUPLOADONFILECHANGES'] = 'Charger Automatiquement les fichiers SavedVariable lorsqu\'ils sont modifiés';
$lang['ALWAYSONTOP'] = 'Garder UniUploader toujours à l\'avant-plan';
$lang['SYSTRAY'] = 'Afficher UniUploader dans la zone de notifications';
$lang['ADDVAR1CH'] = 'Variable Additionelle 1';
$lang['ADDVARNAME1'] = 'Nom de la Variable Additionnelle 1 (defaut-&gt;username)';
$lang['ADDVARVAL1'] = 'Valeur de la Variable Additionnelle 1';
$lang['ADDVAR2CH'] = 'Variable Additionelle 2';
$lang['ADDVARNAME2'] = 'Nom de la Variable Additionnelle 2 (defaut-&gt;password)';
$lang['ADDVARVAL2'] = 'Valeur de la Variable Additionnelle 2<br />Cette valeur est en général un mot de passe et de ce fait masquée';
$lang['ADDVAR3CH'] = 'Variable Additionelle 3';
$lang['ADDVARNAME3'] = 'Nom de la Variable Additionnelle 3';
$lang['ADDVARVAL3'] = 'Valeur de la Variable Additionnelle 3';
$lang['ADDVAR4CH'] = 'Variable Additionelle 4';
$lang['ADDVARNAME4'] = 'Nom de la Variable Additionnelle 4';
$lang['ADDVARVAL4'] = 'Valeur de la Variable Additionnelle 4';
$lang['ADDURL1CH'] = 'URL Additionnelle 1';
$lang['ADDURL1'] = 'Adresse de l\'URL Additionnelle 1';
$lang['ADDURL2CH'] = 'URL Additionnelle 1';
$lang['ADDURL2'] = 'Adresse de l\'URL Additionnelle 2';
$lang['ADDURL3CH'] = 'URL Additionnelle 1';
$lang['ADDURL3'] = 'Adresse de l\'URL Additionnelle 3';
$lang['ADDURL4CH'] = 'URL Additionnelle 1';
$lang['ADDURL4'] = 'Adresse de l\'URL Additionnelle 4';

// advanced
$lang['AUTOLAUNCHWOW'] = 'Lancer WoW automatiquement';
$lang['WOWARGS'] = 'Paramètres ligne de commande pour lancer WoW';
$lang['STARTWITHWINDOWS'] = 'Démarrer UniUploader avec windows';
$lang['USELAUNCHER'] = 'Utiliser le lanceur WoW';
$lang['STARTMINI'] = 'Démarrer minimisé';
$lang['SENDPWSECURE'] = 'Chiffrer avec MD5 la valeur de variable 2 (champ mot de passe) avant envoi';
$lang['GZIP'] = 'compression gZip';
$lang['DELAYUPLOAD'] = 'Utiliser un délai avant chargement';
$lang['DELAYSECONDS'] = 'Valeur en secondes du délai avant chargement';
$lang['RETRDATAFROMSITE'] = 'Web==&gt;WoW - récupérer des données';
$lang['RETRDATAURL'] = 'Web==&gt;WoW - URL de récupération de données';
$lang['WEBWOWSVFILE'] = 'Web==&gt;WoW - Ecrire dans le fichier SavedVariable nommé';
$lang['DOWNLOADBEFOREWOWL'] = 'Web==&gt;WoW - Faire avant qu\'UU ne lance WoW';
$lang['DOWNLOADBEFOREUPLOAD'] = 'Web==&gt;WoW - Faire avant qu\'UU ne charge';
$lang['DOWNLOADAFTERUPLOAD'] = 'Web==&gt;WoW - Faire après qu\'UU n\'ait chargé';

// END UU Sync Strings


// BEGIN UA CONFIG SETTINGS

$lang['admin']['addon_folder'] = 'Dossier Zip AddOn|Specifier le dossier ou les archives zip d\'addon seront sauvés';
$lang['admin']['check_updates'] = 'Vérifier Mises à jour UA|Vérifier sur wowroster.net si une nouvelle version d\'UniAdmin est disponible';
$lang['admin']['default_lang'] = 'Langue par Défaut|Langue par Défaut de l\'interface d\'UniAdmin<br /><br />Les valeurs sont scannées automatiquement depuis les répertoire de langues';
$lang['admin']['default_style'] = 'Style par Défaut|Le style d\'affichage par défaut';
$lang['admin']['enable_gzip'] = 'Compression Gzip|Activer la compression gzip pour l\'affichage des pages UniAdmin';
$lang['admin']['interface_url'] = 'URL Interface|Specifier le lieu de interface.php ici<br /><br />Utiliser %url% pour insérer l\'url de base<br />Par défaut  "%url%?p=interface" ou "%url%interface.php"';
$lang['admin']['logo_folder'] = 'Dossier Logo|Specifier le dossier ou les logos UniUploader seront sauvés';
$lang['admin']['remote_timeout'] = 'Temps d\'attente du fichier distant dépassé|Délais d\'attente d\'UniAdmin du fichier distant<br />Intervalle de mise à jour en heures<br />24 par défaut';
$lang['admin']['temp_analyze_folder'] = 'Dossier Analyse Temporaire AddOn|Specifier le dossier ou les archives zip d\'addons seront décompressées et analysées';
$lang['admin']['UAVer'] = 'Version UniAdmin|Version actuelle d\'UniAdmin<br />Vous ne pouvez pas changer ce paramètre';
$lang['admin']['ua_debug'] = 'Mode Debug|Debuggage pour UniAdmin<br /><br />- [non] Pas de débuggage<br />- [demi] Affiche le nombre de requêtes et le temps de rendu dans le pied de page<br />- [complet] Montre le nombre de requête, temps de rendu, et la requête SQL dans le pied de page';

// END UA CONFIG SETTINGS


// Debug
$lang['queries'] = 'Requêtes';
$lang['debug'] = 'Debug';
$lang['messages'] = 'Messages';


// Error messages
$lang['error'] = 'Erreur';
$lang['error_invalid_login'] = 'Nom d\'Utilisateur ou Mot de passe invalide ou erroné';
$lang['error_delete_addon'] = 'Erreur Suppression d\'AddOn';
$lang['error_enable_addon'] = 'Erreur Activation d\'AddOn';
$lang['error_disable_addon'] = 'Erreur Désactivation d\'AddOn';
$lang['error_require_addon'] = 'Erreur Addon Obligatoire';
$lang['error_optional_addon'] = 'Erreur Addon Facultatif';
$lang['error_no_addon_in_db'] = 'Aucun AddOn dans la Base de Données';
$lang['error_no_addon_uploaded'] = 'Aucun Addon Chargé';
$lang['error_no_files_addon'] = 'Aucun fichier detecté dans l\'AddOn chargé';
$lang['error_no_toc_file'] = 'Aucun fichier \'.toc\' détecté dans l\'AddOn chargé';
$lang['error_unzip'] = 'Erreur de Manipulation Zip';
$lang['error_pclzip'] = 'PCLZip Erreur Irrécupérable: [%1$s]';
$lang['error_unsafe_file'] = 'Fichier Peu sûr Rejeté: [%1$s]';
$lang['error_addon_process'] = 'Erreur Traitement AddOn';
$lang['error_zip_file'] = 'L\'Addon chargé <u>doit</u> être un fichier zip';
$lang['error_addon_not_exist'] = 'AddOn avec ID:%1$s n\'exist pas';

$lang['error_no_ini_uploaded'] = 'Le fichier settings.ini n\'a pas été chargé';
$lang['error_ini_file'] = 'Le fichier chargé <u>doit</u> être settings.ini d\'UniUploader';

$lang['error_chmod'] = 'chmod [%1$s] Impossible<br />chmod Manuellement et/ou vérifier les permissions de fichiers';
$lang['error_mkdir'] = 'mkdir [%1$s] Impossible<br />mkdir Manuellement et/ou vérifier les permissions de fichiers';
$lang['error_unlink'] = 'unlink(delete) [%1$s] Impossible<br />Supprimer Manuellement et/ou vérifier les permissions de fichiers';
$lang['error_move_uploaded_file'] = 'Impossible de de déplacer [%1$s] vers [%2$s]<br />Vérifier les paramètres de chargement php et les permissions de fichiers';
$lang['error_write_file'] = 'Ne peut écrire [%1$s]<br />Vérifier les permissions';
$lang['error_download_file'] = 'Impossible de charger [%1$s]<br />Echec de $uniadmin->get_remote_contents()';

$lang['error_no_uploaded_logo'] = 'Aucun Logo Chargé';
$lang['error_logo_format'] = 'Le fichier chargé <u>doit</u> être une image';

$lang['error_name_required'] = 'Nom requis';
$lang['error_pass_required'] = 'Mot de Passe requis';
$lang['error_pass_mismatch'] = 'Les Mots de Passe ne concordaient pas';
$lang['error_pass_mismatch_edit'] = 'Les Mots de Passe ne concordaient pas<br />Ancien Mot de Passe inchangé';

$lang['error_no_wowace_addons'] = 'Pas d\'Addon WoWAce dans la liste téléchargée';

$lang['error_upgrade_needed'] = 'UniAdmin est en cours de mise à jour<br />Se connecter avec un compte admin pour continuer';

$lang['error_invalid_module_name'] = 'Nom du module éronné';
$lang['error_invalid_module'] = 'Module inéxistant';

// SQL Error Messages
$lang['sql_error'] = 'Erreur SQL';
$lang['sql_error_addons_list'] = 'Impossible d\'obtenir la Liste des AddOns';
$lang['sql_error_addons_disable'] = 'AddOn d\'ID:%1$d n\'a pas pu être desactivé';
$lang['sql_error_addons_enable'] = 'AddOn d\'ID:%1$d n\'a pas pu être activé';
$lang['sql_error_addons_require'] = 'AddOn d\'ID:%1$d n\'a pas pu être rendu Obligatoire';
$lang['sql_error_addons_optional'] = 'AddOn d\'ID:%1$d n\'a pas pu être rendu Facultatif';
$lang['sql_error_addons_delete'] = 'AddOn d\'ID:%1$d n\'a pas pu être supprimé de la base de données<br />Supprimer Manuellement';
$lang['sql_error_addons_insert'] = 'Impossible d\'insérer les données de l\'addon';
$lang['sql_error_addons_files_insert'] = 'Impossible d\'insérer les données sur les fichiers de l\'addon';

$lang['sql_error_logo_toggle'] = 'Impossible de mettre le logo %1$s';
$lang['sql_error_logo_remove'] = 'Impossible de supprimer le logo id=%1$d de la base de données';
$lang['sql_error_logo_insert'] = 'Impossible d\'insérer le logo dans la base de données';

$lang['sql_error_settings_update'] = 'Impossible de mettre à jour le paramètre =&gt; %1$s, valeur =&gt; %2$s, activé =&gt; %3$d';
$lang['sql_error_settings_sv_insert'] = 'Impossible d\'insérer le nom de fichier savedvariable &quot;%1$s&quot;';
$lang['sql_error_settings_sv_remove'] = 'Impossible de supprimer le nom de fichier savedvariable &quot;%1$s&quot;';

$lang['sql_error_user_add'] = 'Impossible d\'ajouter l\'utilisateur &quot;%1$s&quot;';
$lang['sql_error_user_delete'] = 'Impossible de supprimer l\'utilisateur &quot;%1$s&quot;';

