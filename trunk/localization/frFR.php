<?php
/**
 * WoWRoster.net WoWRoster
 *
 * frFR Locale File
 *
 * frFR translation by wowodo, lesablier, Exerladan, Ansgar and Theophilius
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.1
 * @package    WoWRoster
 * @subpackage Locale
 */

$lang['langname'] = 'Français';

//Instructions how to upload, as seen on the mainpage
$lang['update_link']='Cliquer ici pour les instructions de mise à jour.';
$lang['update_instructions']='Instructions de mise à jour.';

$lang['lualocation']='Cliquer, naviguer et télécharger les fichiers *.lua<br />';

$lang['filelocation']='se trouve sous<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$lang['nodata']='Impossible de trouver la guilde : <b>\'%1$s\'</b> du serveur <b>\'%2$s\'</b><br />Vous devez préalablement<a href="%3$s">charger votre guilde</a> et <a href="%4$s">finaliser la configuration</a><br /><br /><a href="http://www.wowroster.net/MediaWiki/Roster:Install" target="_blank">Les instructions d\'installation sont disponibles ici</a>';
$lang['no_char_in_db']='The member <b>\'%1$s\'</b> is not in the database';
$lang['no_default_guild']='Aucune guilde par défaut n\'a encore été indiqué. Veuillez en indiquer une ici.';
$lang['not_valid_anchor']='The anchor(a=) parameter does not provide accurate enough data or is badly formatted.';
$lang['nodefguild']='Il n\'y a actuellement aucune guilde par défaut. Vous devez préalablement <a href="%1$s">>finaliser la configuration</a><br /><br /><a href="http://www.wowroster.net/MediaWiki/Roster:Install" target="_blank">Les instructions d\'installation sont disponibles ici</a>';
$lang['nodata_title']='Données de guilde introuvables';

$lang['update_page']='Mise à jour du profil';

$lang['guild_addonNotFound']='Impossible de trouver la guilde. Le greffon WoWRoster-GuildProfiler est-il installé correctement ?';

$lang['ignored']='Ignoré';
$lang['update_disabled']='L\'accès à update.php a été désactivé';

$lang['nofileUploaded']='Votre UniUploader n\'a pas téléchargé de fichier(s), ou des fichiers erronés.';
$lang['roster_upd_pwLabel']='Mot de passe du Roster';
$lang['roster_upd_pw_help']='Quelques mises à jour lua peuvent demander un mot de passe';


$lang['roster_error'] = 'Erreur lié au Roster';
$lang['sql_queries'] = 'Requêtes SQL';
$lang['invalid_char_module'] = 'Caractères interdits dans le nom du module';
$lang['module_not_exist'] = 'Le module [%1$s] n\'existe pas';

$lang['addon_error'] = 'Erreur lié au greffon';
$lang['specify_addon'] = 'Vous devez spécifier le nom du greffon !';
$lang['addon_not_exist'] = '<b>Le greffon [%1$s] n\'existe pas !</b>';
$lang['addon_disabled'] = '<b>Le greffon [%1$s] a été désactivé</b>';
$lang['addon_no_access'] = '<b>Qualifications insuffisantes pour accéder à [%1$s]</b>';
$lang['addon_upgrade_notice'] = '<b>Le greffon [%1$s] a été désactivé car il nécessite une mise à jour</b>';
$lang['addon_not_installed'] = '<b>Le greffon [%1$s] n\'est pas encore installé</b>';
$lang['addon_no_config'] = '<b>Le greffon [%1$s] n\'est pas configuré</b>';

$lang['char_error'] = 'Erreur lié au personnage';
$lang['specify_char'] = 'Le personnage n\'est pas indiqué';
$lang['no_char_id'] = 'Désolé, aucune données relatives au personnage pour [%1$s]';
$lang['no_char_name'] = 'Désolé, aucune données relatives au personnage pour <strong>%1$s</strong> de <strong>%2$s</strong>';

$lang['roster_cp'] = 'Panneau de contrôle du Roster';
$lang['roster_cp_ab'] = 'PC Roster';
$lang['roster_cp_not_exist'] = 'La page [%1$s] n\'existe pas';
$lang['roster_cp_invalid'] = 'La page spécifiée n\'est pas valide ou vous n\'avez pas les droits nécessaires pour y accéder';
$lang['access_level'] = 'Niveau d\'accès';

$lang['parsing_files'] = 'Traitement des fichiers';
$lang['parsed_time'] = 'Fichier %1$s traité en %2$s secondes';
$lang['error_parsed_time'] = 'Le traitement du fichier %1$s a échoué après %2$s secondes';
$lang['upload_not_accept'] = 'Le fichier %1$s ne peut être traité.';

$lang['processing_files'] = 'Traitement des fichiers';
$lang['error_addon'] = 'Le greffon [%1$s] a généré une erreur dans la méthode %2$s';
$lang['addon_messages'] = 'Messages du greffon :';

$lang['not_accepted'] = '%1$s %2$s @ %3$s-%4$s n\'est pas autorisé. Data does not match upload rules.';

$lang['not_updating'] = 'Pas de mise à jour de %1$s pour [%2$s] - %3$s';
$lang['not_update_guild'] = 'Pas de mise à jour de la liste des membres de la guilde pour %1$s@%3$s-%2$s';
$lang['not_update_guild_time'] = 'Not updating Guild List for %1$s. Guild data uploaded was scanned on %2$s. Guild data stored was scanned on %3$s';
$lang['not_update_char_time'] = 'Not updating Character %1$s. Profile data uploaded was scanned on %2$s Profile data stored was scanned on %3$s';
$lang['no_members'] = 'Les données n\'indiquent aucun membre de guilde';
$lang['upload_data'] = 'Mise à jour des données de %1$s pour [%2$s@%4$s-%3$s]';
$lang['realm_ignored'] = 'Royaume : %1$s non traité';
$lang['guild_realm_ignored'] = 'Guilde : %1$s @ Royaume : %2$s non traitée';
$lang['update_members'] = 'Mise à jour des membres de la guilde';
$lang['update_errors'] = 'Erreur de mise à jour';
$lang['update_log'] = 'Journal des mises à jour';
$lang['select_files'] = 'Select Files';
$lang['save_error_log'] = 'Sauver le journal des erreurs';
$lang['save_update_log'] = 'Sauver le journal des mises à jour';

$lang['new_version_available'] = 'Une nouvelle version de [%1$s] est disponible v%2$s<br />Released: %3$s<br />vous pouvez la récupérer <a href="%4$s" target="_blank">ici</a>';

$lang['remove_install_files'] = 'Supprimez les fichiers d\'installation';
$lang['remove_install_files_text'] = 'Merci de supprimer <span class="redB">install.php</span> de ce répertoire';

$lang['upgrade_wowroster'] = 'Mise à jour de WoWRoster';
$lang['upgrade'] = 'Mise à jour';
$lang['select_version'] = 'Choisissez votre version';
$lang['no_upgrade'] = 'Vous avez déjà mis à jour le Roster.<br />Ou vous avez déjà une version plus récente.<br /><a class="input" href="%1$s">Back to WoWRoster</a>';
$lang['upgrade_complete'] = 'Le Roster a été mis à jour<br /><a class="input" href="%1$s">Back to WoWRoster</a>';

// Menu buttons
$lang['menu_header_scope_panel'] = 'Panneau de contrôle : %s';

$lang['menu_totals'] = 'Total: %1$s (+%2$s Alts)';
$lang['menu_totals_level'] = ' Au moins L%1$s';

// Updating Instructions
$lang['index_text_uniloader'] = '<b><u>Prérequis à l\'utilisation d\'UniUploader:</b></u><a href="http://www.microsoft.com/downloads/details.aspx?FamilyID=0856EACB-4362-4B0D-8EDD-AAB15C5E04F5&displaylang=en">Microsoft .NET Framework</a> installé.<br />Pour les utilisateurs d\'OS autres que Windows, utiliser JUniUploader qui vous permettra d\'effectuer les mêmes opérations que UniUploader mais en utilisant Java.';

$lang['update_instruct']='
<strong>Actualisation automatique recommandée :<strong>
<ul>
<li>Utiliser <a href="%1$s" target="_blank">UniUploader</a><br />
%2$s</li>
</ul>
<strong>Instructions pour actualiser le profil :<strong>
<ol>
<li>télécharger <a href="%3$s" target="_blank">Character Profiler</a> ;</li>
<li>décompresser l\'archive zip dans son propre dossier dans *WoW Directory*\Interface\Addons\ ;</li>
<li>démarrer WoW ;</li>
<li>ouvrir votre compte en banque, la fenêtre des quêtes, et la fenêtre des professions qui contient les recettes ;</li>
<li>se déconnecter ou quitter WoW (voir ci-dessus si vous disposez d\'UniUploader pour automatiser l\'envoi des informations) ;</li>
<li>aller sur la page <a href="%4$s">d\'actualisation</a> ;</li>
<li>%5$s.</li>
</ol>';

$lang['update_instructpvp']='
<strong>Statistiques PvP optionnel :</strong>
<ol>
<li>télécharger <a href="%1$s" target="_blank">PvPLog</a> ;</li>
<li>décompresser l\'archive zip dans son propre dossier dans *WoW Directory*\Interface\Addons\ ;</li>
<li>duel ou PvP ;</li>
<li>envoyer les informations PvPLog.lua (voir étape 7 de l\'actualisation du profil).</li>
</ol>';

$lang['roster_credits']='Page officiel de WoWRoster - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a>';
$lang['bliz_notice']='World of Warcraft et Blizzard Entertainment sont des marques, déposées ou non, appartenant à Blizzard Entertainment Inc. aux États-Unis d\'Amérique et/ou dans les autres pays. Toutes les autres marques sont la propriété de leurs seuls ayant-droits respectifs.';


$lang['timeformat'] = '%d/%m/%Y %H:%i:%s'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$lang['phptimeformat'] = 'd/m/Y H:i:s';    // PHP date() Time format (example - 'M D jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


/**
 * Realmstatus Localizations
 */
$lang['rs'] = array(
	'ERROR' => 'Erreur',
	'NOSTATUS' => 'Pas de status',
	'UNKNOWN' => 'Inconnu',
	'RPPVP' => 'JdR-PvP',
	'PVE' => 'Normal',
	'PVP' => 'PvP',
	'RP' => 'JdR',
	'OFFLINE' => 'Déconnecté',
	'LOW' => 'Bas',
	'MEDIUM' => 'Moyen',
	'HIGH' => 'Haut',
	'MAX' => 'Max',
	'RECOMMENDED' => 'Recommendé',
	'FULL' => 'Plein'
);


//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$lang['guildless']='Sans guilde';
$lang['util']='Utilitaires';
$lang['user']='User CP';
$lang['char']='Personnage';
$lang['equipment']='Equipment';
$lang['upload']='Envoyer';
$lang['required']='Requis';
$lang['optional']='Optionnel';
$lang['attack']='Attaque';
$lang['defense']='Défense';
$lang['class']='Classe';
$lang['race']='Race';
$lang['level']='Niveau';
$lang['lastzone'] = 'Dernière zone';
$lang['note']='Note';
$lang['officer_note']='Note d\'officier';
$lang['title']='Titre';
$lang['name']='Nom';
$lang['health']='Vie';
$lang['mana']='Mana';
$lang['gold']='Or';
$lang['armor']='Armure';
$lang['lastonline']='Dernière connexion';
$lang['online']='Connexion';
$lang['lastupdate']='Dernière mise à jour';
$lang['currenthonor']='Rang d\'honneur actuel';
$lang['rank']='Rang';
$lang['sortby']='Trier par %';
$lang['total']='Total';
$lang['hearthed']='Pierre de foyer';
$lang['recipes']='Recettes';
$lang['bags']='Sacs';
$lang['character']='Personnage';
$lang['money']='Argent';
$lang['bank']='Banque';
$lang['raid']='CT Raid';
$lang['quests']='Quêtes';
$lang['roster']='Roster';
$lang['alternate']='Alternatif';
$lang['byclass']='Par classe';
$lang['menustats']='Caractéristiques';
$lang['menuhonor']='Honneur';
$lang['basename']='Nom de base';
$lang['scope']='Scope';
$lang['tag']='Tag';
$lang['daily']='Daily';

// Item Quality
$lang['quality']='Quality';
$lang['poor']='Poor';
$lang['common']='Common';
$lang['uncommon']='Uncommon';
$lang['rare']='Rare';
$lang['epic']='Epic';
$lang['legendary']='Legendary';
$lang['artifact']='Artifact';
$lang['heirloom']='Heirloom';

//start search engine
$lang['search']='Rechercher';
$lang['search_roster']='Rechercher sur le Roster';
$lang['search_onlyin']='Rechercher uniquement dans ce greffon';
$lang['search_advancedoptionsfor']='Options avancées pour';
$lang['search_results']='Rechercher des résultats pour';
$lang['search_results_from']='Voici les résultats de votre recherche';
$lang['search_nomatches']='Désolé, aucun résultat ne concorde avec votre recherche';
$lang['search_didnotfind']='Vous n\'avez pas trouvé ce que vous recherchiez ? Essayez ici !';
$lang['search_for']='Rechercher dans le Roster';
$lang['search_next_matches'] = 'Résultat suivant pour';
$lang['search_previous_matches'] = 'Résultat précédent pour';
$lang['search_results_count'] = 'Résultats';
$lang['submited_author'] = 'Envoyé par';
$lang['submited_date'] = 'Date de soumission';
//end search engine
$lang['update']='Mise à jour';
$lang['credit']='Crédits';
$lang['who_made']='Les artisans du projet WoWRoster';
$lang['members']='Membres';
$lang['member_profiles']='Member Profiles';
$lang['items']='Objets';
$lang['find']='Trouver les objets contenants';
$lang['upprofile']='Mise à jour du profil';
$lang['backlink']='Retour au Roster';
$lang['gender']='Genre';
$lang['unusedtrainingpoints']='Points de formation non utilisés';
$lang['unusedtalentpoints']='Points de talent non utilisés';
$lang['talentexport']='Construction de l\'arbre de talents';
$lang['questlog']='Journal des quêtes';
$lang['recipelist']='Liste des recettes';
$lang['reagents']='Composants';
$lang['item']='Objet';
$lang['type']='Type';
$lang['date']='Date';
$lang['complete'] = 'Terminée';
$lang['failed'] = 'Échec';
$lang['completedsteps'] = 'Étapes terminées';
$lang['currentstep'] = 'Étapes actuelles';
$lang['uncompletedsteps'] = 'Étapes incomplètes';
$lang['key'] = 'Clef';
$lang['keyring'] = 'Keyring';
$lang['timeplayed'] = 'Temps joué';
$lang['timelevelplayed'] = 'Temps joué à ce niveau';
$lang['Addon'] = 'Greffons :';
$lang['advancedstats'] = 'Statistiques avancées';
$lang['crit'] = 'Critiques';
$lang['dodge'] = 'Esquive';
$lang['parry'] = 'Parade';
$lang['block'] = 'Blocage';
$lang['realm'] = 'Royaume';
$lang['region'] = 'Région';
$lang['server'] = 'Serveur';
$lang['faction'] = 'Faction';
$lang['page'] = 'Page';
$lang['general'] = 'Général';
$lang['prev'] = 'Précédente';
$lang['next'] = 'Suivante';
$lang['memberlog'] = 'Journal';
$lang['removed'] = 'Enlevé';
$lang['added'] = 'Ajouté';
$lang['add'] = 'Ajout';
$lang['delete'] = 'Suppression';
$lang['updated'] = 'Mis à jour';
$lang['no_info'] = 'Aucune information';
$lang['info'] = 'Info';
$lang['url'] = 'URL';
$lang['none']='Rien';
$lang['kills']='Tués';
$lang['allow'] = 'Permis';
$lang['disallow'] = 'Interdit';
$lang['locale'] = 'Locale';
$lang['language'] = 'Langage';
$lang['default'] = 'Défaut';
$lang['proceed'] = 'Valider';
$lang['submit'] = 'Soumettre';
$lang['strength']='Force';
$lang['agility']='Agilité';
$lang['stamina']='Endurance';
$lang['intellect']='Intelligence';
$lang['spirit']='Esprit';

$lang['rosterdiag'] = 'Diagnostic du Roster';
$lang['updates_available'] = 'Updates Available!';
$lang['updates_available_message'] = 'Log in as Admin to download update files';
$lang['download_update_pkg'] = 'Download Update Package';
$lang['download_update'] = 'Download Update';
$lang['zip_archive'] = '.zip Archive';
$lang['targz_archive'] = '.tar.gz Archive';

$lang['difficulty'] = 'Difficulté';
$lang['recipe_4'] = 'optimal';
$lang['recipe_3'] = 'moyen';
$lang['recipe_2'] = 'facile';
$lang['recipe_1'] = 'insignifiant';
$lang['roster_config'] = 'Configuration du Roster';

$lang['search_names'] = 'Recherche de noms';
$lang['search_items'] = 'Recherche d\'objets';
$lang['search_tooltips'] = 'Recherche d\'aide';

// Talent Builds
$lang['talent_build_0'] = 'Active';
$lang['talent_build_1'] = 'Inactif';

// Char Scope
$lang['char_level_race_class'] = 'Niveau %1$s %2$s %3$s';
$lang['char_guildline'] = '%1$s de %2$s';

// Login
$lang['login'] = 'Login';
$lang['logout'] = 'Logout';
$lang['logged_in'] = 'Logged in';
$lang['logged_out'] = 'Logged out';
$lang['login_invalid'] = 'Invalid Password';
$lang['login_fail'] = 'Failed to fetch password info';

//this needs to be exact as it is the wording in the db
$lang['professions']='Métiers';
$lang['secondary']='Compétences secondaires';
$lang['Blacksmithing']='Forge';
$lang['Mining']='Minage';
$lang['Herbalism']='Herboristerie';
$lang['Alchemy']='Alchimie';
$lang['Archaeology']='Archéologie';
$lang['Leatherworking']='Travail du cuir';
$lang['Jewelcrafting']='Joaillerie';
$lang['Skinning']='Dépeçage';
$lang['Tailoring']='Couture';
$lang['Enchanting']='Enchantement';
$lang['Engineering']='Ingénierie';
$lang['Inscription']='Calligraphie';
$lang['Runeforging']='Runeforge';
$lang['Cooking']='Cuisine';
$lang['Fishing']='Pêche';
$lang['First Aid']='Secourisme';
$lang['Poisons']='Poisons';
$lang['backpack']='Sac à dos';
$lang['PvPRankNone']='Rien';

// Uses preg_match() to find required level in recipie tooltip
$lang['requires_level'] = '/Niveau ([\d]+) requis/';

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
$lang['riding'] = 'Monte';
$lang['ts_ridingIcon'] = array(
	'Elfe de la nuit'=>'ability_mount_whitetiger',
	'Humain'=>'ability_mount_ridinghorse',
	'Nain'=>'ability_mount_mountainram',
	'Gnome'=>'ability_mount_mechastrider',
	'Mort-vivant'=>'ability_mount_undeadhorse',
	'Troll'=>'ability_mount_raptor',
	'Tauren'=>'ability_mount_kodo_03',
	'Orc'=>'ability_mount_blackdirewolf',
	'Elfe de sang' => 'ability_mount_cockatricemount',
	'Draeneï' => 'ability_mount_ridingelekk',
	'Paladin'=>'ability_mount_dreadsteed',
	'Démoniste'=>'ability_mount_nightmarehorse',
	'Chevalier de la mort'=>'spell_deathknight_summondeathcharger',
// Female variation
//	'Elfe de la nuit'=>'ability_mount_whitetiger',
	'Humaine'=>'ability_mount_ridinghorse',
	'Naine'=>'ability_mount_mountainram',
//	'Gnome'=>'ability_mount_mechastrider',
	'Morte-vivante'=>'ability_mount_undeadhorse',
	'Trollesse'=>'ability_mount_raptor',
	'Taurène'=>'ability_mount_kodo_03',
	'Orque'=>'ability_mount_blackdirewolf',
//	'Elfe de sang' => 'ability_mount_cockatricemount',
//	'Draeneï' => 'ability_mount_ridingelekk',
//	'Paladin'=>'ability_mount_dreadsteed',
//	'Démoniste'=>'ability_mount_nightmarehorse',
//	'Chevalier de la mort'=>'spell_deathknight_summondeathcharger'
);
$lang['ts_flyingIcon'] = array(
	'Horde'=>'ability_mount_wyvern_01',
	'Alliance'=>'ability_mount_gryphon_01',
	'Druide'=>'ability_druid_flightform',
	'Chevalier de la mort'=>'ability_mount_dreadsteed',
// Female variation
	'Druidesse'=>'ability_druid_flightform',
//	'Chevalier de la mort'=>'ability_mount_dreadsteed'
);

// Class Icons-Array
$lang['class_iconArray'] = array (
	'Chevalier de la mort'=>'deathknight_icon',
	'Druide'=>'druid_icon',
	'Chasseur'=>'hunter_icon',
	'Mage'=>'mage_icon',
	'Paladin'=>'paladin_icon',
	'Prêtre'=>'priest_icon',
	'Voleur'=>'rogue_icon',
	'Chaman'=>'shaman_icon',
	'Démoniste'=>'warlock_icon',
	'Guerrier'=>'warrior_icon',
// Female variation
//	'Chevalier de la mort'=>'deathknight_icon',
	'Druidesse'=>'druid_icon',
	'Chasseresse'=>'hunter_icon',
//	'Mage'=>'mage_icon',
//	'Paladin'=>'paladin_icon',
	'Prêtresse'=>'priest_icon',
	'Voleuse'=>'rogue_icon',
	'Chamane'=>'shaman_icon',
//	'Démoniste'=>'warlock_icon',
	'Guerrière'=>'warrior_icon',
);

// Class Color-Array
$lang['class_colorArray'] = array(
	'Chevalier de la mort'=>'C41F3B',
	'Druide' => 'FF7D0A',
	'Chasseur' => 'ABD473',
	'Mage' => '69CCF0',
	'Paladin' => 'F58CBA',
	'Prêtre' => 'FFFFFF',
	'Voleur' => 'FFF569',
	'Chaman' => '2459FF',
	'Démoniste' => '9482C9',
	'Guerrier' => 'C79C6E',
// Female variation
//	'Chevalier de la mort'=>'C41F3B',
	'Druidesse' => 'FF7D0A',
	'Chasseresse' => 'ABD473',
//	'Mage' => '69CCF0',
//	'Paladin' => 'F58CBA',
	'Prêtresse' => 'FFFFFF',
	'Voleuse' => 'FFF569',
	'Chamane' => '2459FF',
//	'Démoniste' => '9482C9',
	'Guerrière' => 'C79C6E',
);

// Class To English Translation
$lang['class_to_en'] = array(
	'Chevalier de la mort'=>'Death Knight',
	'Druide' => 'Druid',
	'Chasseur' => 'Hunter',
	'Mage' => 'Mage',
	'Paladin' => 'Paladin',
	'Prêtre' => 'Priest',
	'Voleur' => 'Rogue',
	'Chaman' => 'Shaman',
	'Démoniste' => 'Warlock',
	'Guerrier' => 'Warrior',
// Female variation
//	'Chevalier de la mort'=>'Death Knight',
	'Druidesse' => 'Druid',
	'Chasseresse' => 'Hunter',
//	'Mage' => 'Mage',
//	'Paladin' => 'Paladin',
	'Prêtresse' => 'Priest',
	'Voleuse' => 'Rogue',
	'Chamane' => 'Shaman',
//	'Démoniste' => 'Warlock',
	'Guerrière' => 'Warrior',
);

// Class to game-internal ID
$lang['class_to_id'] = array(
	'Guerrier' => 1,
	'Paladin' => 2,
	'Chasseur' => 3,
	'Voleur' => 4,
	'Prêtre' => 5,
	'Chevalier de la mort'=>6,
	'Chaman' => 7,
	'Mage' => 8,
	'Démoniste' => 9,
	'Druide' => 11,
// Female variation
	'Guerrière' => 1,
//	'Paladin' => 2,
	'Chasseresse' => 3,
	'Voleuse' => 4,
	'Prêtresse' => 5,
//	'Chevalier de la mort'=>6,
	'Chamane' => 7,
//	'Mage' => 8,
//	'Démoniste' => 9,
	'Druidesse' => 11,
);

// Game-internal ID to class
$lang['id_to_class'] = array(
	1 => 'Guerrier',
	2 => 'Paladin',
	3 => 'Chasseur',
	4 => 'Voleur',
	5 => 'Prêtre',
	6 => 'Chevalier de la mort',
	7 => 'Chaman',
	8 => 'Mage',
	9 => 'Démoniste',
	11 => 'Druide'
);

// Race to English Translation
$lang['race_to_en'] = array(
	'Elfe de sang' => 'Blood Elf',
	'Draeneï'      => 'Draenei',
	'Elfe de la nuit' => 'Night Elf',
	'Nain'         => 'Dwarf',
	'Gnome'        => 'Gnome',
	'Humain'       => 'Human',
	'Orc'          => 'Orc',
	'Mort-vivant'  => 'Undead',
	'Troll'        => 'Troll',
	'Tauren'       => 'Tauren',
	'Worgen'       => 'Worgen',
	'Gobelin'      => 'Goblin',
// Female variation
//	'Elfe de sang'  => 'Blood Elf',
//	'Draeneï'       => 'Draenei',
//	'Elfe de la nuit' => 'Night Elf',
	'Naine'         => 'Dwarf',
//	'Gnome'         => 'Gnome',
	'Humaine'       => 'Human',
	'Orque'         => 'Orc',
	'Morte-vivante' => 'Undead',
	'Trollesse'     => 'Troll',
	'Taurène'       => 'Tauren',
//	'Worgen'        => 'Worgen',
//	'Gobelin'       => 'Goblin',
);

$lang['race_to_id'] = array(
	'Humain'       => 1,
	'Orc'          => 2,
	'Nain'         => 3,
	'Elfe de la nuit' => 4,
	'Mort-vivant'  => 5,
	'Tauren'       => 6,
	'Gnome'        => 7,
	'Troll'        => 8,
	'Elfe de sang' => 10,
	'Draeneï'      => 11,
	'Worgen'       => 22,
	'Gobelin'      => 9,
// Female variation
	'Humaine'       => 1,
	'Orque'         => 2,
	'Naine'         => 3,
//	'Elfe de la nuit' => 4,
	'Morte-vivante' => 5,
	'Taurène'      => 6,
//	'Gnome'         => 7,
	'Trollesse'     => 8,
//	'Elfe de sang'  => 10,
//	'Draeneï'      => 11,
//	'Worgen'       => 22,
//	'Gobelin'      => 9,
);

$lang['id_to_race'] = array(
	1 => 'Humain',
	2 => 'Orc',
	3 => 'Nain',
	4 => 'Elfe de la nuit',
	5 => 'Mort-vivant',
	6 => 'Tauren',
	7 => 'Gnome',
	8 => 'Troll',
	10 => 'Elfe de sang',
	11 => 'Draeneï',
	22 => 'Worgen',
	9 => 'Gobelin',
);

$lang['hslist']=' Statistiques du Système d\'Honneur';
$lang['hslist1']='Membre le mieux classé';
$lang['hslist2']='Membre ayant le plus de VH';
$lang['hslist3']='Le plus de Points d\'honneur';
$lang['hslist4']='Le plus de Points d\'arène';

$lang['Death Knight']='Chevalier de la mort';
$lang['Druid']='Druide';
$lang['Hunter']='Chasseur';
$lang['Mage']='Mage';
$lang['Paladin']='Paladin';
$lang['Priest']='Prêtre';
$lang['Rogue']='Voleur';
$lang['Shaman']='Chaman';
$lang['Warlock']='Démoniste';
$lang['Warrior']='Guerrier';

$lang['today']='Aujourd\'hui';
$lang['todayhk']='VH du jour';
$lang['todaycp']='CP du jour';
$lang['yesterday']='Hier';
$lang['yesthk']='VH d\'hier';
$lang['yestcp']='CP d\'hier';
$lang['thisweek']='Cette semaine';
$lang['lastweek']='Semaine dernière';
$lang['lifetime'] = 'À vie';
$lang['lifehk']='VH à vie';
$lang['honorkills']='Vict. Honorables';
$lang['dishonorkills']='Vict. Déshonorantes';
$lang['honor']='Honneur';
$lang['standing']='Position';
$lang['highestrank']='Plus haut niveau';
$lang['arena']='Arène';

$lang['when']='Quand';
$lang['guild']='Guilde';
$lang['guilds']='Guildes';
$lang['result']='Résultat';
$lang['zone']='Zone';
$lang['subzone']='Sous-zone';
$lang['yes']='Oui';
$lang['no']='Non';
$lang['win']='Victoire';
$lang['loss']='Défaite';
$lang['unknown']='Inconnu';

//strings for Rep-tab
$lang['exalted']='Exalté';
$lang['revered']='Révéré';
$lang['honored']='Honoré';
$lang['friendly']='Amical';
$lang['neutral']='Neutre';
$lang['unfriendly']='Inamical';
$lang['hostile']='Hostile';
$lang['hated']='Haï';
$lang['atwar']='En guerre';
$lang['notatwar']='Pas en guerre';

// Factions to EN id
$lang['faction_to_id'] = array(
	'Alliance' => 'alliance',
	'Alliance Forces' => 'allianceforces',
	'Alliance Vanguard' => 'alliancevanguard',
	'Classic' => 'classic',
	'Other' => 'other',
	'Outland' => 'outland',
	'Shattrath City' => 'shattrathcity',
	'Steamwheedle Cartel' => 'steamwheedlecartel',
	'The Burning Crusade' => 'theburningcrusade',
	'Wrath of the Lich King' => 'wrathofthelitchking',
	'Sholazar Basin' => 'sholazarbasin',
	'Horde Expedition' => 'horde',
	'Horde' => 'horde',
	'Horde Forces' => 'horde',
	'Cataclysm' => 'cataclysm',
	'Guild' => 'guild',
	'Reputation' => 'reputation',
	);


// Quests page external links (on character quests page)
// $lang['questlinks'][][] = array(
// 		'name'=> 'Name',  // This is the name displayed on the quests page
// 		'url' => 'url',   // This is the URL used for the quest lookup (must be sprintf() compatible)

$lang['questlinks'][] = array(
	'name'=>'WoWHead',
	'url'=>'http://fr.wowhead.com/?quest=%s'
);

/*$lang['questlinks'][] = array(
	'name'=>'Allakhazam',
	'url'=>'http://wow.allakhazam.com/db/quest.html?source=live;wquest=%s;locale=frFR'
);*/

/*$lang['questlinks'][] = array(  // Does not allow quest id linking
	'name'=>'Judgehype FR',
	'url'=>'http://worldofwarcraft.judgehype.com/index.php?page=bc-result&amp;Ckey='
);*/

/*$lang['questlinks'][] = array(  // In maintenance mode - not accessible yet
	'name'=>'WoWDBU FR',
	'url'=>'http://wowdbu.com/7.html?m=2&amp;mode=qsearch&amp;title='
);*/

// Items external link
// Add as manu item links as you need
// Just make sure their names are unique
// uses the 'item_id' for data
$lang['itemlink'] = 'Liens vers les objets';
$lang['itemlinks']['Judgehype FR'] = 'http://worldofwarcraft.judgehype.com/index.php?page=bc-obj&w=';
$lang['itemlinks']['WoWHead'] = 'http://fr.wowhead.com/?item=';
$lang['itemlinks']['Allakhazam'] = 'http://wow.allakhazam.com/db/item.html?locale=frFR&witem=';
//$lang['itemlinks']['WoWDBU FR'] ='http://wowdbu.com/2-1.html?way=asc&amp;order=name&amp;showstats=&amp;type_limit=0&amp;lvlmin=&amp;lvlmax=&amp;name='; // In maintenance mode - not accessible yet

// WoW Data Site Search
// Add as many item links as you need
// Just make sure their names are unique
// use these locales for data searches
$lang['data_search'] = 'Site de recherche de données sur WoW';
$lang['data_links']['Thottbot'] = 'http://www.thottbot.com/index.cgi?s=';
$lang['data_links']['Allakhazam'] = 'http://wow.allakhazam.com/search.html?locale=frFR&q=';
$lang['data_links']['WWN Data'] = 'http://wwndata.worldofwar.net/search.php?search=';
$lang['data_links']['WoWHead'] = 'http://fr.wowhead.com/?search=';

// Google Search
// Add as many item links as you need
// Just make sure their names are unique
// use these locales for data searches
$lang['google_search'] = 'Google';
$lang['google_links']['Google'] = 'http://www.google.com/search?q=';
$lang['google_links']['Google Groups'] = 'http://groups.google.com/groups?q=';
$lang['google_links']['Google Images'] = 'http://images.google.com/images?q=';
$lang['google_links']['Google News'] = 'http://news.google.com/news?q=';

// Definition for item tooltip coloring
$lang['tooltip_use']='Utiliser...';
$lang['tooltip_requires']='Niveau';
$lang['tooltip_reinforced']='renforcée';
$lang['tooltip_soulbound']='Lié';
$lang['tooltip_accountbound']='Account Bound';
$lang['tooltip_boe']='Lié quand équipé';
$lang['tooltip_equip']='Équipé...';
$lang['tooltip_equip_restores']='Équipé.:.Rend';
$lang['tooltip_equip_when']='Équipé : Lorsque';
$lang['tooltip_chance']='Chance';
$lang['tooltip_enchant']='Enchantement';
$lang['tooltip_random_enchant']='Enchantement aléatoire';
$lang['tooltip_set']='Ensemble...|Complet...|Set...';
$lang['tooltip_rank']='Rang';
$lang['tooltip_next_rank']='Prochain rang';
$lang['tooltip_spell_damage']='les dégâts et les soins produits par les sorts et effets magiques';
$lang['tooltip_school_damage']='les dégâts infligés par les sorts et effets';
$lang['tooltip_healing_power']='les soins prodigués par les sorts et effets';
$lang['tooltip_reinforced_armor']='Armure renforcée';
$lang['tooltip_damage_reduction']='Réduit les points de dégâts';
//--Tooltip Parsing -- Translated by Kalia
$lang['tooltip_durability']='Durabilité';
$lang['tooltip_unique']='Unique';
$lang['tooltip_speed']='Vitesse';
$lang['tooltip_poisoneffect']='^Chaque coup a';

// php 5.3 changes
$lang['tooltip_preg_soulbound']='/Soulbound/';
$lang['tooltip_preg_dps']='/(\d+) damage per second/';
$lang['tooltip_preg_item_equip']='/Equip: (.+)/';
$lang['tooltip_preg_item_set']='/Set: (.+)/';
$lang['tooltip_preg_use']='/Use: (.+)/';
$lang['tooltip_preg_chance']='/Chance (.+)/';
$lang['tooltip_preg_chance_hit']='/Chance ^(to|on) hit: (.+)/';
$lang['tooltip_preg_heroic']='/Heroic/';
$lang['tooltip_garbage1']='/\<Shift Right Click to Socket\>/';
$lang['tooltip_garbage2']='/\<Right Click to Read\>/';
$lang['tooltip_garbage3']='/Duration (.+)/';
$lang['tooltip_garbage4']='/Cooldown remaining (.+)/';
$lang['tooltip_garbage5']='/\<Right Click to Open\>/';
$lang['tooltip_garbage6']='/Equipment Sets: (.+)/';
//^(Red|Yellow|Blue|Meta)
$lang['tooltip_preg_weapon_types']='/^(Arrow|Axe|Bow|Bullet|Crossbow|Dagger|Fishing Pole|Fist Weapon|Gun|Idol|Mace|Main Hand|Off-hand|Polearm|Staff|Sword|Thrown|Wand|Ranged|One-Hand|Two-Hand|Relic)/';
$lang['tooltip_preg_speed']='/Speed/';

$lang['tooltip_preg_armor']='/Armure.+ (\d+)/';
$lang['tooltip_preg_durability']='/Durabilité(|:) (\d+) \/ (\d+)/';
$lang['tooltip_preg_madeby']='/\<Artisan.+ (.+)\>/';  // this is the text that shows who crafted the item.
$lang['tooltip_preg_bags']='/Conteneur (\d+) emplacements/';  // text for bags, ie '16 slot bag'
$lang['tooltip_preg_socketbonus']='/Bonus de sertissage : (.+)/';
$lang['tooltip_preg_classes']='/^(Classes.:.)(.+)$/'; // text for class restricted items
$lang['tooltip_preg_races']='/^(Races.:.)(.+)$/'; // text for race restricted items
$lang['tooltip_preg_charges']='/(\d+) Charges/i'; // text for items with charges
$lang['tooltip_preg_block']='/(Bloquer).+?(\d+)/i';  // text for shield blocking values
$lang['tooltip_preg_emptysocket']='/(?:Châsse )?(rouge(?!\()|jaune(?!\()|Prismatic(?!\()|bleue(?!\()|Méta(?=-))(?:-châsse)?/'; // text shown if the item has empty sockets.
$lang['tooltip_preg_reinforcedarmor']='/(Renforcé \(\+\d Armure\))/';
$lang['tooltip_preg_tempenchants']='/(.+\s\(\d+\s(min|sec)\))\n/';
$lang['tooltip_preg_meta_requires']='/Nécessite.*?gemme(?:s|)/i';
$lang['tooltip_preg_meta_requires_min']='/Nécessite au moins (\d) gemme(?:s|) (\S+)\(s\)/i';
$lang['tooltip_preg_meta_requires_more']='/Nécessite plus de gemmes (jaune|rouge|blue\S+) que de (\S+)/i';
$lang['tooltip_preg_item_level']='/Item Level (\d+)/';
$lang['tooltip_feral_ap']='Increases attack power by';
$lang['tooltip_source']='Source';
$lang['tooltip_boss']='Boss';
$lang['tooltip_droprate']='Fréquence de butin';
$lang['tooltip_reforged']='Reforged';

$lang['tooltip_chance_hit']='Chances quand vous touchez...'; // needs to find 'chance on|to hit:'
$lang['tooltip_reg_requires']='Niveau|requis|Requiert'; // À une main
$lang['tooltip_reg_onlyworksinside']='Ne fonctionne qu\'à l\'intérieur du Donjon de la Tempête';
$lang['tooltip_reg_conjureditems']='Objet invoqué';
$lang['tooltip_reg_weaponorbulletdps']='^\(|^Ajoute ';

$lang['tooltip_armor_types']='Tissu|Cuir|Mailles|Plaques';  // the types of armor
$lang['tooltip_weapon_types']='Hache|Arc|Arbaléte|Dague|Canne à pêche|Arme de pugilat|Arme à feu|À une main|Masse|Main droite|Arme d\'hast|Bâton|Epée|Armes de jet|Baguette|Tenu\(e\) en main gauche|Flèche|Balle';
$lang['tooltip_bind_types']='Lié|Lié quand équipé|Objet de quête|Lié quand utilisé|Cet objet permet de lancer une quête|Lier au compte|Account Bound';
$lang['tooltip_misc_types']='Doigt|Cou|Dos|Chemise|Bijou|Tabard|Tête|Torse|Jambes|Pieds';
$lang['tooltip_garbage']='Maj clic-droit pour sertir|<Right Click to Read>|Duration|Temps de recharge|<Right Click to Open>';  // these are texts that we really do not need to show in WoWRoster's tooltip so we'll strip them out

//CP v2.1.1+ Gems info
//uses preg_match() to find the type and color of the gem
$lang['gem_preg_singlecolor'] = '/Correspond à une châsse (.+)\./';
$lang['gem_preg_multicolor'] = '/Correspond à une châsse (.+) ou (.+)\./';
$lang['gem_preg_meta'] = '/Ne peut être serti que dans une châsse de méta-gemme\./';
$lang['gem_preg_prismatic'] = '/Correspond à une châsse rouge, jaune ou bleue\./';

//Gem color Array
$lang['gem_colors'] = array(
	'red' => 'rouge',
	'blue' => 'bleue',
	'yellow' => 'jaune',
	'green' => 'verte',
	'orange' => 'orange',
	'purple' => 'pourpre',
	'prismatic' => 'prismatique',
	'meta' => 'méta'
	);

$lang['gem_colors_to_en'] = array(
	'red' => 'rouge',
	'blue' => 'bleu',
	'yellow' => 'jaune',
	'green' => 'verte',
	'orange' => 'orange',
	'purple' => 'violette',
	'prismatic' => 'prismatic',
	'meta' => 'meta'
	);

$lang['socket_colors_to_en'] = array(
	'rouge' => 'red',
	'bleue' => 'blue',
	'jaune' => 'yellow',
	'méta' => 'meta',
	'prismatic' => 'prismatic',
	);
// -- end tooltip parsing



// Warlock pet names for icon displaying
$lang['Imp']='Diablotin';
$lang['Voidwalker']='Marcheur du Vide';
$lang['Succubus']='Succube';
$lang['Felhunter']='Chasseur corrompu';
$lang['Infernal']='Infernal';
$lang['Felguard']='Gangregarde';

// Max experiance for exp bar on char page
$lang['max_exp']='XP maximum';

// Error messages
$lang['CPver_err']='La version du WoWRoster-Profiler utilisée pour récupérer les données pour ce personnage est plus ancienne que la version minimum autorisée.<br />Veuillez vous assurez que vous fonctionnez avec au moins la version v%1$s, que vous vous êtes connecté sur ce personnage et avez sauvé les données en utilisant cette version.';
$lang['GPver_err']='La version du WoWRoster-GuildProfiler utilisée pour capturer les données pour ce personnage est plus ancienne que la version minimum autorisée pour le téléchargement.<br />SVP assurez vous que vous fonctionnez avec la v%1$s';

// Menu titles
$lang['menu_upprofile']='Mise à jour du profil|Mettez à jour votre profil sur ce site';
$lang['menu_search']='Recherche|Rechercher des objets et des recettes dans la base de donnée';
$lang['menu_roster_cp']='Configuration Roster|Panneau de configuration du Roster';
$lang['menupanel_util'] = 'Utilitaires';
$lang['menupanel_user'] = 'User CP';
$lang['menupanel_realm'] = 'Royaume';
$lang['menupanel_guild'] = 'Guilde';
$lang['menupanel_char']  = 'Personnage';

$lang['menuconf_sectionselect']='Zone de sélection';
$lang['menuconf_section']='Section';
$lang['menuconf_changes_saved']='Changements sur %1$s enregistré';
$lang['menuconf_no_changes_saved']='Aucun changement enregistré';
$lang['menuconf_add_button']='Ajouter un bouton';
$lang['menuconf_drag_delete']='Déplacer ici pour supprimer';
$lang['menuconf_addon_inactive']='Greffon inactif';
$lang['menuconf_unused_buttons']='Boutons non utilisés';

$lang['default_page_set']='The default page has been set to [%1$s]';

$lang['installer_install_0'] = 'L\'installation de [%1$s] a réussi';
$lang['installer_install_1'] = 'L\'installation de [%1$s] a échoué mais le retour à l\'état précédent a réussi';
$lang['installer_install_2'] = 'L\'installation de [%1$s] a échoué et il n\'a pas été possible de revenir à l\'état précédent la tentative d\'installation';
$lang['installer_uninstall_0'] = 'La désinstallation de [%1$s] a réussi';
$lang['installer_uninstall_1'] = 'La désinstallation de [%1$s] a échoué mais le retour à l\'état précédent a réussi';
$lang['installer_uninstall_2'] = 'La désinstallation de [%1$s] a échoué et il n\'a pas été possible de revenir à l\'état précédent la tentative de désinstallation';
$lang['installer_upgrade_0'] = 'La mise à jour de [%1$s] a réussi';
$lang['installer_upgrade_1'] = 'La mise à jour de [%1$s] a échoué mais le retour à l\'état précédent a réussi';
$lang['installer_upgrade_2'] = 'La mise à jour de [%1$s] a échoué et il n\'a pas été possible de revenir à l\'état précédent la tentative de mise à jour';
$lang['installer_purge_0'] = 'Nettoyage de [%1$s] réussi';

$lang['installer_icon'] = 'Icône';
$lang['installer_addoninfo'] = 'Informations du greffon';
$lang['installer_status'] = 'Status';
$lang['installer_installation'] = 'Installation';
$lang['installer_author'] = 'Auteur';
$lang['installer_log'] = 'Journal du gestionnaire de greffon';
$lang['installer_activate_0'] = 'Addon %1$s désactivé';
$lang['installer_activate_1'] = 'Addon %1$s activé';
$lang['installer_deactivated'] = 'Désactivé';
$lang['installer_activated'] = 'Activé';
$lang['installer_installed'] = 'Installé';
$lang['installer_upgrade_avail'] = 'Une mise à jour est disponible';
$lang['installer_not_installed'] = 'Non installé';
$lang['installer_install'] = 'Install';
$lang['installer_uninstall'] = 'Uninstall';
$lang['installer_activate'] = 'Activate';
$lang['installer_deactivate'] = 'Deactivate';
$lang['installer_upgrade'] = 'Upgrade';
$lang['installer_purge'] = 'Purge';

$lang['installer_turn_off'] = 'Cliquez pour désactiver';
$lang['installer_turn_on'] = 'Cliquez pour activer';
$lang['installer_click_uninstall'] = 'Cliquez pour désintaller';
$lang['installer_click_upgrade'] = 'Cliquez pour mettre à jour %1$s de %2$s';
$lang['installer_click_install'] = 'Cliquez pour installer';
$lang['installer_overwrite'] = 'Écrasement de l\'ancienne version';
$lang['installer_replace_files'] = 'Vous avez écrasé la version actuelle du greffon avec une version plus ancienne.<br />Mettez à jour les fichiers avec la version la plus récente.<br /><br />Ou cliquer pour purger le greffon';

$lang['installer_error'] = 'Erreurs relatives au programme d\'installation';
$lang['installer_invalid_type'] = 'Type d\'installation invalide';
$lang['installer_no_success_sql'] = 'Les requêtes n\'ont pas été ajoutées avec succès au programme d\'installation';
$lang['installer_no_class'] = 'Le fichier contenant les définitions du programme d\'installation pour [%1$s] ne contient pas de classes d\'installation correctes';
$lang['installer_no_installdef'] = 'inc/install.def.php pour [%1$s] n\'est pas trouvable';

$lang['installer_no_empty'] = 'Installation impossible avec un nom de greffon vide';
$lang['installer_fetch_failed'] = 'Échec de récupération des données du greffon [%1$s]';
$lang['installer_addon_exist'] = '%1$s contient déjà %2$s. Vous pouvez revenir en arrère et d\'abord supprimer ce greffon, ou le mettre à jour, ou installer ce greffon sous un nom différent.';
$lang['installer_no_upgrade'] = '[%1$s] ne contient pas de données à mettre à jour';
$lang['installer_not_upgradable'] = '[%1$s] ne peut pas mettre à jour [%2$s] car son nom [%3$s] n\'est pas dans la liste des greffons pouvant être mis à jour.';
$lang['installer_no_uninstall'] = '[%1$s] ne contient pas de greffon pouvant être désinstallé.';
$lang['installer_not_uninstallable'] = '[%1$s] contient le greffon [%2$s] qui doit être supprimé avec ce programme de désinstallation de greffon.';

// After Install guide
$lang['install'] = 'Installation';
$lang['setup_guide'] = 'Guide de post-installation';
$lang['skip_setup'] = 'Skip Setup';
$lang['default_data'] = 'Données par défaut';
$lang['default_data_help'] = 'Indiquez ici votre guilde autorisé par défaut.<br />Une guilde par défaut est nécessaire pour que plusieurs greffons fonctionnent correctement.<br />Vous pouvez ajouter plusieurs guildes autorisés dans RosterCP-&gt;Règles de mise à jour.<br /><br />Si cette installation de Roster n\'est pas liée à une guilde :<br />indiquez Guildless-F comme nom de guilde en remplaçant F par l\'initiale de votre faction (A-Alliance ou H-Horde), <br />indiquez votre royaume et région par défaut.<br />Indiquez les règles de mise à jour dans RosterCP-&gt;Règles de mise à jour';
$lang['guide_complete'] = 'Le processus de post-installation est complet.';
$lang['guide_next'] = 'Remember To';
$lang['guide_next_text'] = '<ul><li><a href="%1$s" target="_blank">Install Roster AddOns</a></li><li><a href="%2$s" target="_blank">Set Upload Rules</a></li><li><a href="%3$s" target="_blank">Update Data from the Armory</a></li></ul>';
$lang['guide_already_complete'] = 'Le processus de post-installation a déjà été effectué.<br />Vous ne pouvez pas l\'éxecuter une nouvelle fois.';

// Armory Data
$lang['adata_update_talents'] = 'Talents';
$lang['adata_update_class'] = 'Class %1$s updated';
$lang['adata_update_row'] = '%1$s rows added to database.';

// Password Stuff
$lang['password'] = 'Mot de passe';
$lang['changeadminpass'] = 'Changer le mot de passe administrateur';
$lang['changeofficerpass'] = 'Changer le mot de passe de mise à jour';
$lang['changeguildpass'] = 'Changer le mot de passe de guilde';
$lang['old_pass'] = 'Ancien mot de passe';
$lang['new_pass'] = 'Nouveau mot de passe';
$lang['new_pass_confirm'] = 'Confirmation du nouveau mot de passe';
$lang['pass_old_error'] = 'Mot de passe erroné. Merci de fournir le bon mot de passe d\'origine.';
$lang['pass_submit_error'] = 'Erreur d\'envoi. L\'ancien, le nouveau et la confirmation du nouveau mot de passe doivent être fournis.';
$lang['pass_mismatch'] = 'Erreur de mot de passe de confirmation. Merci de saisir le même mot de passe dans les champs nouveau mot de passe et confirmation du nouveau mot de passe';
$lang['pass_blank'] = 'Pas de mot de passe vide. Merci de saisir un mot de passe dans les deux champs. Les mots de passe vides ne sont pas autorisés';
$lang['pass_isold'] = 'Le mot de passe n\'a pas été modifié. Le nouveau mot de passe et l\'ancien sont exactement les mêmes.';
$lang['pass_changed'] = '&quot;%1$s&quot; le mot de passe a été modifié. Votre nouveau mot de passe est [ %2$s ].<br /> Ne l\'oubliez pas, il n\'est pas stocké de façon lisible.';
$lang['auth_req'] = 'Autorisation requise';

// Upload Rules
$lang['enforce_rules'] = 'Enforce Upload Rules';
$lang['enforce_rules_never'] = 'Never';
$lang['enforce_rules_all'] = 'All LUA Updates';
$lang['enforce_rules_cp'] = 'CP Updates Only';
$lang['enforce_rules_gp'] = 'Guild Updates Only';
$lang['upload_rules_error'] = 'Vous ne pouvez pas laisser un des champs vide quand vous ajoutez une règle.';
$lang['upload_rules_help'] = 'Les règles sont séparées en deux blocs.<ul><li>Pour chaque guilde/personnage envoyé, le premier bloc est pris en compte en premier.<br />Si le couple nom@serveur correspond à l\'une des règles de rejet, celui-ci sera rejeté.</li><li>Ensuite le second bloc est vérifié.<br />Si le couple nom@serveur correspond à l\'une des règles d\'acceptation, celui-ci sera accepté.</li><li>Si aucune règle n\'est vérifiée, celui-ci est alors rejeté.</li></ul>You can use % for a wildcard.<br /><br />Remember to set a default guild here as well.';

// Data Manager
$lang['clean'] = 'Clean up entries based on upload rules';
$lang['clean_help'] = 'This will run an update and enforce the rules as set by the \'Enforce Upload Rules\' setting';
$lang['select_guild'] = 'Sélectionner une guilde';
$lang['delete_checked'] = 'Supprimé les validés';
$lang['delete_guild'] = 'Supprimer la guilde';
$lang['delete_guild_confirm'] = 'Ceci supprimera entièrement la guilde et tous ses membres seront spécifier sans guilde.\\nÊtes-vous sûr de vouloir continuer ?\\n\\nNOTE : il est impossible de revenir en arrière !';

// Config Reset
$lang['config_is_reset'] = 'La configuration a été remise à zéro. Merci de ne pas oublier de tout re-configurer avant de renvoyer vos données.';
$lang['config_reset_confirm'] = 'Cette action est irréversible. Êtes-vous sûr de vouloir continuer ?';
$lang['config_reset_help'] = 'Ceci va complètement remettre à zéro la configuration du roster.<br />
Toutes les données relatives à la configuration du roster vont être détruites et les valeurs par défaut vont être remises.<br />
Les données relative aux guildes, aux personnages, à la configuration des greffons, aux greffons, aux boutons des menus, et aux règles de mise à jour seront conservées.<br />
Les mots de passe de guilde, d\'officiers et d\'administrateur seront aussi conservés.<br />
<br />
Afin de continuer, saisissez votre mot de passe administrateur et cliquez sur \'Valider\'.';

/******************************
 * Roster Admin Strings
 ******************************/

$lang['pagebar_function'] = 'Fonctions';
$lang['pagebar_rosterconf'] = 'Configuration principale';
$lang['pagebar_uploadrules'] = 'Règles de mise à jour';
$lang['pagebar_dataman'] = 'Gestion des données';
$lang['pagebar_userman'] = 'User Manager';
$lang['pagebar_armory_data'] = 'Armory Data';
$lang['pagebar_changepass'] = 'Changer le mot de passe';
$lang['pagebar_addoninst'] = 'Gestion des greffons';
$lang['pagebar_plugin'] = 'Plugin Management';
$lang['pagebar_update'] = 'Mise à jour';
$lang['pagebar_rosterdiag'] = 'Diagnostic';
$lang['pagebar_menuconf'] = 'Configuration des menus';
$lang['pagebar_configreset'] = 'Remise à zéro de la configuration';

$lang['pagebar_addonconf'] = 'Greffons';

$lang['roster_config_menu'] = 'Menu de configuration';
$lang['menu_config_help'] = 'Add Menu Button Help';
$lang['menu_config_help_text'] = 'Use this to add a new menu button. Adding a new menu button here will add it to the current section.<ul><li>Title - The name of the new button.</li><li>URL - The button\'s link. This can be a WoWRoster path or a full URL (add http:// in the link)</li><li>Icon - The button\'s image. This must be an image from the Interface Image Pack without the path or extension (ex. inv_misc_gear_01)</ul>';

// Submit/Reset confirm questions
$lang['config_submit_button'] = 'Sauvegarder les modifications';
$lang['config_reset_button'] = 'Remise à zéro du formulaire';
$lang['confirm_config_submit'] = 'Ceci va sauver vos modifications dans la base de données. Êtes-vous sûr ?';
$lang['confirm_config_reset'] = 'Ceci va remettre le formulaire dans l\'état où il était avant vos modifications. Êtes-vous sûr ?';

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
$lang['admin']['main_conf'] = 'Fondamentaux|Paramètres principaux du roster.<br />Ceci comprend l\'adresse du roster, l\'emplacement des images de l\'interface et d\'autres paramètres fondamentaux';
$lang['admin']['defaults_conf'] = 'Divers|Définissez divers options du roster';
$lang['admin']['index_conf'] = 'Accueil|Options pour ce qu\'affiche la page d\'accueil';
$lang['admin']['menu_conf'] = 'Menu|Contrôlez ce qu\'affiche le menu principal du roster';
$lang['admin']['display_conf'] = 'Affichage|Différents paramètres de configuration: css, javascript, motd, etc...';
$lang['admin']['realmstatus_conf'] = 'États des royaumes|Paramètres pour l\'état des royaumes';
$lang['admin']['data_links'] = 'Liens annexes|Liens externes';
$lang['admin']['update_access'] = 'Accréditations|Spécifiez les niveau d\'accès aux différents composants du panneau de contrôle du roster';

$lang['admin']['documentation'] = 'Documentation|Documentation de WoWRoster via wiki de WoWRoster.net';

// main_conf
$lang['admin']['roster_dbver'] = 'Version de la base de données Roster|La version de la base de données';
$lang['admin']['version'] = 'Version du Roster|Version actuelle du Roster';
$lang['admin']['debug_mode'] = 'Mode debug|off - aucun message d\'erreur ou de debug<br />on - message d\'erreur et de debug simples<br />étendu - mode debug complet et backtrace dans les messages d\'erreurs';
$lang['admin']['sql_window'] = 'Fenêtre SQL|off - pas de fenêtre des requêtes<br />on - fenêtre des requêtes en pied de page<br />étendu - inclure DESCRIBE statements';
$lang['admin']['minCPver'] = 'Version minimum de CP|Version minimale de WoWRoster-Profiler autorisée';
$lang['admin']['minGPver'] = 'Version minimum de GP|Version minimale de WoWRoster-GuildProfiler autorisée';
$lang['admin']['locale'] = "Langue principale|Langue principale utilisée par sur le roster";
$lang['admin']['default_page'] = 'Page d\'accueil|Page à afficher si aucune n\'est spécifiée dans l\'adresse';
$lang['admin']['external_auth'] = 'Authentification|Ici vous pouvez choisir le fichier que Roster utilisera pour l\'authentification.<br />&quot;Roster&quot; est la valeur par défaut, système d\'authentification intégré.';
$lang['admin']['website_address'] = "Adresse du site Web|Utilisé pour le lien sur le logo et le lien sur le menu principal<br />Certains addon pour le roster peuvent également l'utiliser";
$lang['admin']['interface_url'] = "Répertoire des images de l'interface|Répertoire où les images de l'interface sont situés<br />La valeur par défaut est &quot;img/&quot;<br /><br />Vous pouvez utiliser un chemin relatif ou une URL absolue";
$lang['admin']['img_suffix'] = "Extension des images de l'interface|Le type des images de l'interface";
$lang['admin']['alt_img_suffix'] = "Extension alternative des images de l'interface|Le type alternatif d'images pour les images de l'interface";
$lang['admin']['img_url'] = "Répertoire des images du roster|Répertoire où les images du roster sont situés<br />La valeur par défaut est &quot;img/&quot;<br /><br />Vous pouvez utiliser un chemin relatif ou une URL absolue";
$lang['admin']['timezone'] = "Fuseau horaire|Affiché après les dates et heures afin de savoir à quel fuseau horaire l'heure fait référence";
$lang['admin']['localtimeoffset'] = "Décalage horaire|Le décalage horaire par rapport à l'heure UTC/GMT<br />Les heures sur le roster seront affichées avec ce décalage";
$lang['admin']['use_update_triggers'] = 'Déclenchement de mise à jour de greffon|Déclencher automatiquement la mise à jour de greffon est nécessaire pour ceux qui ont besoin de fonctionner lors d\'une mise à jour d\'un profil.<br />Quelques greffons ont besoin de ce paramètre activé pour fonctionner correctement.';
$lang['admin']['check_updates'] = "Vérifier les mises à jour|Permettre au site de vérifier si une nouvelle version du roster (ou des greffons possédant cette fonctionalité) est disponible et si vous avez la dernière version d\\'installée";
$lang['admin']['seo_url'] = "Friendly URLs|Enable SEO like URL links in Roster<br /><br />on - /some/page/here/param=value.html<br />off - index.php?p=some-page-here&amp;param=value";
$lang['admin']['local_cache']= "Système de cache de fichiers|Utiliser un système de cache de fichiers sur le serveur pour améliorer les performances.";
$lang['admin']['use_temp_tables'] = "Utiliser des tables temporaires|Désactiver ce paramètre si votre hôte ne permet pas de créer des tables de base de données temporaires (le privilège CREATE TEMPORARY TABLE).<br/>Laisser activé ce paramètre est recommandé pour les performances.";
$lang['admin']['preprocess_js'] = "Aggregate JavaScript files|Certain JavaScript files will be optimized automatically, which can reduce both the size and number of requests made to your website.<br />Leaving this on is recommended for performance.";
$lang['admin']['preprocess_css'] = "Aggregate and compress CSS files|Certain CSS files will be optimized automatically, which can reduce both the size and number of requests made to your website.<br />Leaving this on is recommended for performance.";
$lang['admin']['enforce_rules'] = "Enforce Upload Rules|This setting will enforce the upload rules on every lua update<ul class='ul-no-m'><li>Never: Never enforce rules</li><li>All LUA Updates: Enforce rules on all lua updates</li><li>CP Updates: Enforce rules on any CP.lua update</li><li>Guild Updates: Enforce rules only on guild updates</li></ul>You can also toggle this setting on the &quot;Upload Rules&quot; page.";

// defaults_conf
$lang['admin']['default_name'] = "Nom du roster|Saisissez un nom qui sera affiché quand vous ne serez pas sur une page de guilde ou de personnage";
$lang['admin']['default_desc'] = "Description|Saisissez une courte description du site qui sera affichée quand vous ne serez pas sur une page de guilde ou de personnage";
$lang['admin']['alt_type'] = "Identification des rerolls|Textes identifiant les rerolls pour le décompte dans le menu principal<br /><br /><span class=\"red\">The Memebers List AddOn DOES NOT use this for alt grouping</span>";
$lang['admin']['alt_location'] = "Identification des rerolls (champ)|Où faut-il rechercher l'identification des rerolls<br /><br /><span class=\"red\">The Memebers List AddOn DOES NOT use this for alt grouping</span>";

// display_conf
$lang['admin']['theme'] = "Thème du roster|Sélectionner l'apparence du roster<br /><span class=\"red\">NOTE :</span> la matrice du roster n'a pas encore été complètement achevée<br />et l'utilisation de thèmes autres que celui par défaut peut donc avoir des conséquences sur l'affichage de celui-ci.";
$lang['admin']['logo'] = "URL pour le logo de l'entête|L'URL complète de l'image<br />Ou en laissant \"img/\" devant le nom, celà cherchera dans le répertoire img/ du roster";
$lang['admin']['roster_bg'] = "URL pour l'image de fond|L'URL absolue de l'image pour le fond principal<br />Ou en laissant &quot;img/&quot; devant le nom, celà cherchera dans le répertoire img/ du roster";
$lang['admin']['motd_display_mode'] = "Mode d'affichage du message du jour|Comment le message du jour sera affiché<br /><br />&quot;Text&quot; - Montre le message de du jour en rouge<br />&quot;Image&quot; - Montre le message du jour sous forme d'une image (nécesite GD!)";
$lang['admin']['header_locale'] = "Sélection de la langue|Contrôle l'affichage de la zone de sélection de la langue du panneau supérieur du menu principal du roster";
$lang['admin']['header_login'] = "Login in header|Control the display of the login box in the header";
$lang['admin']['header_search'] = "Search in header|Control the display of the search box in the header";
$lang['admin']['signaturebackground'] = "Image de fond pour img.php|Support de l'ancien générateur de signature";
$lang['admin']['processtime'] = "Temps de génération de la page|Displays render time and query count in the footer<br />&quot;<i>x.xx | xx</i>&quot;";

// data_links
$lang['admin']['profiler'] = "Lien de téléchargement du WoWRoster-Profiler|URL de téléchargement de WoWRoster-Profiler";
$lang['admin']['uploadapp'] = "Lien de téléchargement d'UniUploader|URL de téléchargement d'UniUploader";

// realmstatus_conf
$lang['admin']['rs_display'] = "Mode d'affichage|Comment l'état du royaume sera affiché<ul class='ul-no-m'><li>off: Do not show realm status</li><li>Text: L'état du royaume sera affiché dans une balise DIV avec du texte et des images</li><li>Image: Le statut du royaume sera affiché comme une image (NECESSITE GD !)</li></ul>";
$lang['admin']['rs_timer'] = "Rafraîchissement|Temps que met le roster avant de récupérer à nouveau les données sur l'état de royaume";
$lang['admin']['rs_left'] = "Affichage|";
$lang['admin']['rs_middle'] = "Type de royaume|";
$lang['admin']['rs_right'] = "Population du royaume|";
$lang['admin']['rs_font_server'] = "Police du nom|Police de caractère pour l'affichage du nom du royaume<br />(en mode image uniquement !)";
$lang['admin']['rs_size_server'] = "Taille de police du nom|Taille de la police de caractère pour l'affichage du nom du royaume<br />(en mode image uniquement !)";
$lang['admin']['rs_color_server'] = "Couleur du nom|Couleur du nom du royaume";
$lang['admin']['rs_color_shadow'] = "Couleur de l'ombre|Couleur pour l'effet d'ombre du texte<br />(en mode image uniquement !)";
$lang['admin']['rs_font_type'] = "Police du type|Police pour le type de royaume<br />(en mode image uniquement !)";
$lang['admin']['rs_size_type'] = "Taille de police|Taille de police pour le type de royaume<br />(en mode image uniquement !)";
$lang['admin']['rs_color_rppvp'] = "Couleur JdR-JCJ|Couleur pour un serveur de type JdR-JCJ";
$lang['admin']['rs_color_pve'] = "Couleur Normal|Couleur pour un serveur de type Normal";
$lang['admin']['rs_color_pvp'] = "Couleur JCJ|Couleur pour un serveur de type JCJ";
$lang['admin']['rs_color_rp'] = "Couleur JdR|Couleur pour un serveur de type JdR";
$lang['admin']['rs_color_unknown'] = "Couleur inconnu|Couleur pour un serveur de type inconnu";
$lang['admin']['rs_font_pop'] = "Police de population|Police de caractère pour le niveau de peuplement du serveur<br />(en mode image uniquement !)";
$lang['admin']['rs_size_pop'] = "Taille de police|Taille de la police de caractère pour le niveau de peuplement du serveur<br />(en mode image uniquement !)";
$lang['admin']['rs_color_low'] = "Couleur Faible|Couleur pour un niveau de peuplement faible";
$lang['admin']['rs_color_medium'] = "Couleur Moyen|Couleur pour un niveau de peuplement moyen";
$lang['admin']['rs_color_high'] = "Couleur Haute|Couleur pour un niveau de peuplement élevé";
$lang['admin']['rs_color_max'] = "Couleur Max|Couleur pour un niveau de peuplement maximum";
$lang['admin']['rs_color_error'] = "Couleur Erreur|Couleur d'un serveur avec une erreur";
$lang['admin']['rs_color_offline'] = "Couleur Hors-ligne|Couleur dans le cas d'un royaume hors-ligne";
$lang['admin']['rs_color_full'] = "Couleur Complet|Couleur pour un serveur complet (EU serveurs seulement)";
$lang['admin']['rs_color_recommended'] = "Couleur Recommandé|Couleur pour un serveur recommandé (EU serveurs seulement)";

// update_access
$lang['admin']['authenticated_user'] = "Accès à Update.php|Contrôle l'accès à update.php<br /><br />Passer ce paramètre à off désactive l'accès à TOUT LE MONDE";
$lang['admin']['api_key_private'] = "Blizzard API Private key|This is the Private key given to you by Blizzard<br />This enables WoWRoster to make more then 3000 requests per day to the Armory";
$lang['admin']['api_key_public'] = "Blizzard API Public key|This is the Public key given to you by Blizzard<br />This enables WoWRoster to make more then 3000 requests per day to the Armory";
$lang['admin']['api_url_region'] = "Blizzard API Region|this is the armory region that you are accessing";
$lang['admin']['api_url_locale'] = "Blizzard API Locale|These locales are REGION dependent and will display<br>info in the desired language";
$lang['admin']['update_inst'] = 'Instructions de mise à jour|Controls the display of the Update Instructions on the update page';
$lang['admin']['gp_user_level'] = "Niveau d'accès aux données de guilde|Niveau requis pour mettre à jour les données fournies par WoWRoster-GuildProfiler";
$lang['admin']['cp_user_level'] = "Niveau d'accès aux données des personnages|Niveau requis pour mettre à jour les données fournies par WoWRoster-Profiler";
$lang['admin']['lua_user_level'] = "Niveau d'accès aux données des autres LUA|Niveau requis pour mettre à jour les données fournies par d'autres sources de données (LUA).<br />Ceci est valable pour TOUTES SOURCES AUTRES pouvant être envoyées au roster.";
//session
$lang['admin']['sess_time']		= 'Session Time|Edit the length of time before a session is ended.';
$lang['admin']['save_login']  	= 'Save Login|Use a cookie to remember the client login?';
$lang['admin']['acc_session']	= 'Session Config|Configure the settings for accounts sessions.';

// Character Display Settings
$lang['admin']['per_character_display'] = 'Affichage par personnage';

//Overlib for Allow/Disallow rules
$lang['guildname'] = 'Nom de la guilde';
$lang['realmname']  = 'Nom du royaume';
$lang['regionname'] = 'Région (i.e. EU)';
$lang['charname'] = 'Nom du personnage';

//register locals
$lang['register'] 	= 'Register';
$lang['menu_register'] 	= 'Register|Register with roster to have access to your guilds pages';
$lang['cname_tt'] 	='Your Username should be your Main charactor in the guild Alts can be added later';
$lang['cname'] 		='Character name';
$lang['cclass_tt'] 	='Your characters class';
$lang['cclass'] 	='Character Class';
$lang['clevel_tt'] 	='Your characters level';
$lang['clevel']		='Character level';
$lang['cgrank_tt'] 	='This is your guild rank in the guild';
$lang['cgrank'] 	='Guild Rank';
$lang['cemail_tt'] 	='Your Email address DO NOT USE your battle.net address';
$lang['cemail'] 	='Email address';
