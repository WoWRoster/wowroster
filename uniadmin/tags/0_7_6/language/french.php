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
$lang['title_settings'] = 'Param�tres';
$lang['title_stats'] = 'Statistiques';
$lang['title_users'] = 'Utilisateurs';
$lang['title_config'] = 'Config UniAdmin';
$lang['title_login'] = 'Login';


// Help page text
$lang['help'] = array(
	array(	'header' => 'Intro',
			'text'   => '
<p>Je parie que vous vous demandez soit ce que c\'est ou comment vous en servir, donc:</p>
<p>C\'est un syst�me qui sert � tenir � jour les addons, logos et r�glages des utilisateurs (qui utilisent UniUploader).<br />
Quand vous chargez un addon dans le syst�me et cliquez [Mise � jour] dans UU, UU va regarder &quot;l\'URL de Synchronisation&quot;<br />
et proc�de au t�l�chargement des mises � jour qui sont diff�rentes d\'une fa�on ou d\'une autre de la copie stock�e sur le disque dur de l\'utilisateur.<br />
UU remplacera alors l\'addon par la nouvelle copie de l\'addon depuis ce syst�me-ci.</p>'),

	array(	'header' => 'AddOns',
			'text'   => '
<p>L\'addon charg� doit �tre au format zip seulement.<br />
Le fichier zip doit avoir l\'arborescence de r�pertoires suivante: [dossier],{fichier}, et non litt�ralement &quot;nomAddon&quot; ou &quot;fichierAddon&quot;<br />
Le Nom de l\'Addon est le m�me que le nom du dossier dans lequel les fichiers de l\'Addon se trouvent</p>
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
<p>Ce module permet de t�l�charger des addons depuis le repository SVN de WoWAce.com SVN</p>
<p>Seul l\'utilisateur Admin UniAdmin peut acc�der � ce module</p>'),

	array(	'header' => 'Logos',
			'text'   => '
<p>Ceci change les logos affich�s dans UniUploader/jUniUploader<br />
Logo 1 est affich� sur l\'onglet [Param�tres]<br />
Logo 2 est affich� sur l\'onglet [A propos]</p>'),

	array(	'header' => 'R�glages',
			'text'   => '
<p>Vous pouvez vous assurer que les param�tres critiques d\'UU des utilisateurs sont � jour avec ceci, soyez TRES prudent avec certains r�glages, puisque certains peuvent rendrent vos utilisateurs furieux apr�s vous, et si vous mettez un mauvais param�tre, vous pourriez perdre le contact avec tous vos utilisateurs MDR<br />
Si le r�glage est un 1 ou z�ro cela signifie que c\'est une case � cocher dans UU qui doit �tre: coch�e (1) ou d�coch�e (0).</p>
<p>La liste des variables sauvegard�es est la liste de fichiers que vous voulez que UU charge vers les (la seule) URL(s).</p>'),

	array(	'header' => 'Statistiques',
			'text'   => '
<p>Ceci montre qui acc�de � UniAdmin</p>
<p>La table montre chaque acc�s</p>
<ul>
	<li> &quot;Action&quot; - Ce que le client demande</li>
	<li> &quot;Adresse IP&quot; - L\'adresse IP du client</li>
	<li> &quot;Date/Heure&quot; - La date/heure d\'acc�s</li>
	<li> &quot;User Agent&quot; - Le logiciel qui acc�de</li>
	<li> &quot;Nom d\'H�te&quot; - Le Nom d\'H�te de l\'utilisateur</li>
</ul>
<p>Sous la table, on trouve de jolis camenberts sur comment UniAdmin � �t� acc�d�</p>'),

	array(	'header' => 'Utilisateurs',
			'text'   => '
<p>Il y a 3 &quot;user levels&quot;</p>
<p>(Montre l\'action la plus �lev�e disponible)</p>
<dl>
	<dt>niveau 1 (utilisateur standard) a acc�s �</dt>
	<dd>1, 2, 3, 4, 5.3</dd>

	<dt>niveau 2 (utilisateur exp�riment�) a acc�s �</dt>
	<dd>1.1, 2, 3.1, 4, 5.7</dd>

	<dt>niveau 3 (administrateur) a acc�s � tout</dt>
	<dd>1.2, 2, 3.2, 4, 5.10, 6</dd>
	<dd>&nbsp;</dd>
</dl>
<p>Il ne devrait pas y avoir plus de 1 ou 2 utilisateurs de &quot;niveau 3&quot; dans UniAdmin</p>
<div class="ua_hr"><hr /></div>
<p>L�gende des codes d\'acc�s:</p>
<ul>
	<li> 1: Gestion des AddOns
		<ul>
			<li> 1.1: G�rer les AddOns</li>
			<li> 1.2: Ajouter/Supprimer des AddOns</li>
		</ul></li>
	<li> 2: Gestion des Logos</li>
	<li> 3: Gestion des Param�tres
		<ul>
			<li> 3.1: Ajouter/Supprimer des Fichiers SavedVariable</li>
			<li> 3.2: charger/t�l�charger settings.ini</li>
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
$lang['uploaded'] = 'Charg�';
$lang['enabled'] = 'Activ�';
$lang['disabled'] = 'D�sactive';
$lang['files'] = 'Fichiers';
$lang['url'] = 'URL';
$lang['delete'] = 'Supprimer';
$lang['disable_enable'] = 'D�sactiver / Activer';
$lang['update_file'] = 'Mettre � jour le Fichier';
$lang['updated'] = 'Mis � Jour';
$lang['setting_name'] = 'Nom du Param�tre';
$lang['description'] = 'Description';
$lang['value'] = 'Valeur';
$lang['filename'] = 'NomFichier';
$lang['row'] = 'Ligne';
$lang['action'] = 'Action';
$lang['ip_address'] = 'Adresse IP';
$lang['date_time'] = 'Date/Heure';
$lang['user_agent'] = 'User Agent';
$lang['host_name'] = 'Nom d\'H�te';



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
$lang['disable'] = 'D�sactiver';
$lang['modify'] = 'Modifier';
$lang['check'] = 'V�rifier';
$lang['proceed'] = 'Continuer';
$lang['reset'] = 'R�initialiser';
$lang['submit'] = 'Soumettre';
$lang['upgrade'] = 'Mettre � Jour';
$lang['update_logo'] = 'Mettre � jour le Logo %1$d';
$lang['update_settings'] = 'Mettre � jour les Param�tres';
$lang['show'] = 'Montrer';
$lang['add_update_addon'] = 'Ajouter / Mettre � jour AddOn';
$lang['update_addon'] = 'Mettre � jour AddOn';
$lang['import'] = 'Importer';
$lang['export'] = 'Exporter';
$lang['go'] = 'Go';


// Form Element Descriptions
$lang['current_password'] = 'Mot de Passe Actuel';
$lang['current_password_note'] = 'Vous devez confirmer votre mot de passe actuel si vous souhaitez changer de nom d\'utilisateur ou de mot de passe';
$lang['confirm_password'] = 'Confirmer Mot de Passe';
$lang['confirm_password_note'] = 'Vous n\'avez � confirmer votre nouveau mot de passe que si vous l\'avez chang� ci-dessus';
$lang['language'] = 'Langue';
$lang['new_password'] = 'Nouveau Mot de Passe';
$lang['new_password_note'] = 'Vous n\'avez a fournir un nouveau mot de passe que si vous voulez en changer';
$lang['change_username'] = 'Changer Nom d\'Utilisateur';
$lang['change_password'] = 'Changer Mot de Passe';
$lang['change_userlevel'] = 'Changer le Niveau Utilisateur';
$lang['change_language'] = 'Changer de Langue';
$lang['basic_user_level_1'] = 'Utilisateur Standard (niveau 1)';
$lang['power_user_level_2'] = 'Utilisateur Exp�riment� (niveau 2)';
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
$lang['get_wowace_addons'] = 'T�l�charger des Addons WoWAce';
$lang['addon_management'] = 'Gestion des AddOns';
$lang['addon_uploaded'] = '%1$s a �t� charg� avec succ�s';
$lang['addon_edited'] = '%1$s a �t� modifi�';
$lang['view_addons'] = 'Voir AddOns';
$lang['required_addon'] = 'AddOns Obligatoires';
$lang['homepage'] = 'Site Web';
$lang['logged_in_as'] = 'Connect� en tant que [%1$s]';
$lang['logo_table'] = 'Logo %1$d';
$lang['logo_uploaded'] = 'Logo %1$d a �t� charg� avec succ�s';
$lang['uniuploader_sync_settings'] = 'R�glages de Synchro UniUploader';
$lang['uniadmin_config_settings'] = 'R�glages de Config UniAdmin';
$lang['manage_svfiles'] = 'G�rer les Fichiers SavedVariable';
$lang['add_svfiles'] = 'Ajouter des Fichiers SavedVariable';
$lang['svfiles'] = 'Fichiers SavedVariable';
$lang['image_missing'] = 'IMAGE MANQUANTE';
$lang['stats_limit'] = 'ligne(s) � partir de l\'entr�e #';
$lang['user_modified'] = 'Utilisateur %1$s modifi�';
$lang['user_added'] = 'Utilisateur %1$s ajout�';
$lang['user_deleted'] = 'Utilisateur %1$s supprim�';
$lang['access_denied'] = 'Acc�s Refus�';
$lang['settings_file'] = 'Fichier settings.ini';
$lang['import_file'] = 'Importer Fichier';
$lang['export_file'] = 'Exporter Fichier';
$lang['settings_updated'] = 'Param�tres Mis � Jour';
$lang['download'] = 'T�l�charger';
$lang['user_style'] = 'Style Utilisateur';
$lang['change_style'] = 'Changer de Style';
$lang['fullpath_addon'] = 'Chemin Complet Addon';
$lang['addon_details'] = 'D�tails AddOn';
$lang['manage'] = 'G�rer';
$lang['optional'] = 'Facultatif';
$lang['notes'] = 'Notes';
$lang['half'] = 'Demi';
$lang['full'] = 'Complet';
$lang['edit'] = 'Editer';
$lang['cancel'] = 'Annuler';
$lang['status'] = 'Statut';
$lang['automatic'] = 'Automatique';
$lang['delete_all_addons'] = 'Supprimer tous les Addons';
$lang['unscanned_addons'] = 'Un-Scanned AddOns';


// Pagination
$lang['next_page'] = 'Page Suivante';
$lang['page'] = 'Page';
$lang['previous_page'] = 'Page Pr�c�dente';


// Miscellaneous
$lang['time_format'] = 'jS M, Y g:ia';
$lang['syncro_url'] = 'URL de Synchronisation';
$lang['verify_syncro_url'] = 'cliquer pour v�rifier';
$lang['guest_access'] = 'Acc�s Invit�';
$lang['interface_ready'] = 'Interface de Mise � Jour UniUploader...';


// Addon Management
$lang['addon_required_tip'] = 'Quand coch�, UniUploader exigera le t�l�chargement de cet addon';
$lang['addon_fullpath_tip'] = 'Ce param�tre est pour les addons qui extraient directement dans le r�pertoire de World of Warcraft<br /><br />- [oui] Extraire addon dans WoW/<br />- [non] Extraire dans WoW/Interface/AddOns/<br />- [Automatique]D�tection automatique de l\'emplacement';
$lang['addon_selectfile_tip'] = 'Selectionner un addon � charger';
$lang['confirm_addons_delete'] = 'Ceci supprimera TOUS les addons de la base de donn�e et du serveur web. Etes-vous s�r ?';
$lang['all_addons_delete'] = 'Tous les addons ont �t� supprim�s de la base de donn�e et du serveur web';


// WoWAce
$lang['new_wowace_list'] = 'Nouvelle liste t�l�charg�e de WoWAce.com';


// Upgrader
$lang['ua_upgrade'] = 'Mise � jour d\'UniAdmin';
$lang['no_upgrade'] = 'Vous avez d�j� mis � jour UniAdmin<br />Ou vous avez une version plus � jour que cet upgrader';
$lang['select_version'] = 'Selectionner Version';
$lang['success'] = 'R�ussite';
$lang['upgrade_complete'] = 'Votre installation UniAdmin a �t� mise � jour';
$lang['new_version_available'] = 'Une nouvelle version d\'UniAdmin est disponible <span class="green">v%1$s</span><br />T�l�charger <a href="http://www.wowroster.net" target="_blank">ICI</a>';


// UU Sync Settings

// Each setting for the UniUploader Setting Sync is listed here
// The keyname has to be exactly the same as the name in the DB and the name thats is used in UniUploader
// Any text must be html entity encoded first!
// Each group is separated by section based on the settings.ini file

// settings
$lang['LANGUAGE'] = 'Langue';
$lang['PRIMARYURL'] = 'Charger les fichiers SavedVariable vers cette URL';
$lang['PROGRAMMODE'] = 'Mode du programme';
$lang['AUTODETECTWOW'] = 'Auto d�tecter WoW';
$lang['OPENGL'] = 'Executer WoW en mode OpenGL';
$lang['WINDOWMODE'] = 'Executer WoW en fen�tr�';

// updater
$lang['UUUPDATERCHECK'] = 'V�rifier les mises � jour d\'UniUploader';
$lang['SYNCHROURL'] = 'URL de synchronisation avec UniAdmin';
$lang['ADDONAUTOUPDATE'] = 'Mise � jour automatique des AddOns';
$lang['UUSETTINGSUPDATER'] = 'Synchroniser les r�glages UniUploader avec UniAdmin';

// options
$lang['AUTOUPLOADONFILECHANGES'] = 'Charger Automatiquement les fichiers SavedVariable lorsqu\'ils sont modifi�s';
$lang['ALWAYSONTOP'] = 'Garder UniUploader toujours � l\'avant-plan';
$lang['SYSTRAY'] = 'Afficher UniUploader dans la zone de notifications';
$lang['ADDVAR1CH'] = 'Variable Additionelle 1';
$lang['ADDVARNAME1'] = 'Nom de la Variable Additionnelle 1 (defaut-&gt;username)';
$lang['ADDVARVAL1'] = 'Valeur de la Variable Additionnelle 1';
$lang['ADDVAR2CH'] = 'Variable Additionelle 2';
$lang['ADDVARNAME2'] = 'Nom de la Variable Additionnelle 2 (defaut-&gt;password)';
$lang['ADDVARVAL2'] = 'Valeur de la Variable Additionnelle 2<br />Cette valeur est en g�n�ral un mot de passe et de ce fait masqu�e';
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
$lang['WOWARGS'] = 'Param�tres ligne de commande pour lancer WoW';
$lang['STARTWITHWINDOWS'] = 'D�marrer UniUploader avec windows';
$lang['USELAUNCHER'] = 'Utiliser le lanceur WoW';
$lang['STARTMINI'] = 'D�marrer minimis�';
$lang['SENDPWSECURE'] = 'Chiffrer avec MD5 la valeur de variable 2 (champ mot de passe) avant envoi';
$lang['GZIP'] = 'compression gZip';
$lang['DELAYUPLOAD'] = 'Utiliser un d�lai avant chargement';
$lang['DELAYSECONDS'] = 'Valeur en secondes du d�lai avant chargement';
$lang['RETRDATAFROMSITE'] = 'Web==&gt;WoW - r�cup�rer des donn�es';
$lang['RETRDATAURL'] = 'Web==&gt;WoW - URL de r�cup�ration de donn�es';
$lang['WEBWOWSVFILE'] = 'Web==&gt;WoW - Ecrire dans le fichier SavedVariable nomm�';
$lang['DOWNLOADBEFOREWOWL'] = 'Web==&gt;WoW - Faire avant qu\'UU ne lance WoW';
$lang['DOWNLOADBEFOREUPLOAD'] = 'Web==&gt;WoW - Faire avant qu\'UU ne charge';
$lang['DOWNLOADAFTERUPLOAD'] = 'Web==&gt;WoW - Faire apr�s qu\'UU n\'ait charg�';

// END UU Sync Strings


// BEGIN UA CONFIG SETTINGS

$lang['admin']['addon_folder'] = 'Dossier Zip AddOn|Specifier le dossier ou les archives zip d\'addon seront sauv�s';
$lang['admin']['check_updates'] = 'V�rifier Mises � jour UA|V�rifier sur wowroster.net si une nouvelle version d\'UniAdmin est disponible';
$lang['admin']['default_lang'] = 'Langue par D�faut|Langue par D�faut de l\'interface d\'UniAdmin<br /><br />Les valeurs sont scann�es automatiquement depuis les r�pertoire de langues';
$lang['admin']['default_style'] = 'Style par D�faut|Le style d\'affichage par d�faut';
$lang['admin']['enable_gzip'] = 'Compression Gzip|Activer la compression gzip pour l\'affichage des pages UniAdmin';
$lang['admin']['interface_url'] = 'URL Interface|Specifier le lieu de interface.php ici<br /><br />Utiliser %url% pour ins�rer l\'url de base<br />Par d�faut  "%url%?p=interface" ou "%url%interface.php"';
$lang['admin']['logo_folder'] = 'Dossier Logo|Specifier le dossier ou les logos UniUploader seront sauv�s';
$lang['admin']['remote_timeout'] = 'Temps d\'attente du fichier distant d�pass�|D�lais d\'attente d\'UniAdmin du fichier distant<br />Intervalle de mise � jour en heures<br />24 par d�faut';
$lang['admin']['temp_analyze_folder'] = 'Dossier Analyse Temporaire AddOn|Specifier le dossier ou les archives zip d\'addons seront d�compress�es et analys�es';
$lang['admin']['UAVer'] = 'Version UniAdmin|Version actuelle d\'UniAdmin<br />Vous ne pouvez pas changer ce param�tre';
$lang['admin']['ua_debug'] = 'Mode Debug|Debuggage pour UniAdmin<br /><br />- [non] Pas de d�buggage<br />- [demi] Affiche le nombre de requ�tes et le temps de rendu dans le pied de page<br />- [complet] Montre le nombre de requ�te, temps de rendu, et la requ�te SQL dans le pied de page';

// END UA CONFIG SETTINGS


// Debug
$lang['queries'] = 'Requ�tes';
$lang['debug'] = 'Debug';
$lang['messages'] = 'Messages';


// Error messages
$lang['error'] = 'Erreur';
$lang['error_invalid_login'] = 'Nom d\'Utilisateur ou Mot de passe invalide ou erron�';
$lang['error_delete_addon'] = 'Erreur Suppression d\'AddOn';
$lang['error_enable_addon'] = 'Erreur Activation d\'AddOn';
$lang['error_disable_addon'] = 'Erreur D�sactivation d\'AddOn';
$lang['error_require_addon'] = 'Erreur Addon Obligatoire';
$lang['error_optional_addon'] = 'Erreur Addon Facultatif';
$lang['error_no_addon_in_db'] = 'Aucun AddOn dans la Base de Donn�es';
$lang['error_no_addon_uploaded'] = 'Aucun Addon Charg�';
$lang['error_no_files_addon'] = 'Aucun fichier detect� dans l\'AddOn charg�';
$lang['error_no_toc_file'] = 'Aucun fichier \'.toc\' d�tect� dans l\'AddOn charg�';
$lang['error_unzip'] = 'Erreur de Manipulation Zip';
$lang['error_pclzip'] = 'PCLZip Erreur Irr�cup�rable: [%1$s]';
$lang['error_unsafe_file'] = 'Fichier Peu s�r Rejet�: [%1$s]';
$lang['error_addon_process'] = 'Erreur Traitement AddOn';
$lang['error_zip_file'] = 'L\'Addon charg� <u>doit</u> �tre un fichier zip';
$lang['error_addon_not_exist'] = 'AddOn avec ID:%1$s n\'exist pas';

$lang['error_no_ini_uploaded'] = 'Le fichier settings.ini n\'a pas �t� charg�';
$lang['error_ini_file'] = 'Le fichier charg� <u>doit</u> �tre settings.ini d\'UniUploader';

$lang['error_chmod'] = 'chmod [%1$s] Impossible<br />chmod Manuellement et/ou v�rifier les permissions de fichiers';
$lang['error_mkdir'] = 'mkdir [%1$s] Impossible<br />mkdir Manuellement et/ou v�rifier les permissions de fichiers';
$lang['error_unlink'] = 'unlink(delete) [%1$s] Impossible<br />Supprimer Manuellement et/ou v�rifier les permissions de fichiers';
$lang['error_move_uploaded_file'] = 'Impossible de de d�placer [%1$s] vers [%2$s]<br />V�rifier les param�tres de chargement php et les permissions de fichiers';
$lang['error_write_file'] = 'Ne peut �crire [%1$s]<br />V�rifier les permissions';
$lang['error_download_file'] = 'Impossible de charger [%1$s]<br />Echec de $uniadmin->get_remote_contents()';

$lang['error_no_uploaded_logo'] = 'Aucun Logo Charg�';
$lang['error_logo_format'] = 'Le fichier charg� <u>doit</u> �tre une image';

$lang['error_name_required'] = 'Nom requis';
$lang['error_pass_required'] = 'Mot de Passe requis';
$lang['error_pass_mismatch'] = 'Les Mots de Passe ne concordaient pas';
$lang['error_pass_mismatch_edit'] = 'Les Mots de Passe ne concordaient pas<br />Ancien Mot de Passe inchang�';

$lang['error_no_wowace_addons'] = 'Pas d\'Addon WoWAce dans la liste t�l�charg�e';

$lang['error_upgrade_needed'] = 'UniAdmin est en cours de mise � jour<br />Se connecter avec un compte admin pour continuer';

$lang['error_invalid_module_name'] = 'Nom du module �ronn�';
$lang['error_invalid_module'] = 'Module in�xistant';

// SQL Error Messages
$lang['sql_error'] = 'Erreur SQL';
$lang['sql_error_addons_list'] = 'Impossible d\'obtenir la Liste des AddOns';
$lang['sql_error_addons_disable'] = 'AddOn d\'ID:%1$d n\'a pas pu �tre desactiv�';
$lang['sql_error_addons_enable'] = 'AddOn d\'ID:%1$d n\'a pas pu �tre activ�';
$lang['sql_error_addons_require'] = 'AddOn d\'ID:%1$d n\'a pas pu �tre rendu Obligatoire';
$lang['sql_error_addons_optional'] = 'AddOn d\'ID:%1$d n\'a pas pu �tre rendu Facultatif';
$lang['sql_error_addons_delete'] = 'AddOn d\'ID:%1$d n\'a pas pu �tre supprim� de la base de donn�es<br />Supprimer Manuellement';
$lang['sql_error_addons_insert'] = 'Impossible d\'ins�rer les donn�es de l\'addon';
$lang['sql_error_addons_files_insert'] = 'Impossible d\'ins�rer les donn�es sur les fichiers de l\'addon';

$lang['sql_error_logo_toggle'] = 'Impossible de mettre le logo %1$s';
$lang['sql_error_logo_remove'] = 'Impossible de supprimer le logo id=%1$d de la base de donn�es';
$lang['sql_error_logo_insert'] = 'Impossible d\'ins�rer le logo dans la base de donn�es';

$lang['sql_error_settings_update'] = 'Impossible de mettre � jour le param�tre =&gt; %1$s, valeur =&gt; %2$s, activ� =&gt; %3$d';
$lang['sql_error_settings_sv_insert'] = 'Impossible d\'ins�rer le nom de fichier savedvariable &quot;%1$s&quot;';
$lang['sql_error_settings_sv_remove'] = 'Impossible de supprimer le nom de fichier savedvariable &quot;%1$s&quot;';

$lang['sql_error_user_add'] = 'Impossible d\'ajouter l\'utilisateur &quot;%1$s&quot;';
$lang['sql_error_user_delete'] = 'Impossible de supprimer l\'utilisateur &quot;%1$s&quot;';

