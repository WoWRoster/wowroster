<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
 * @subpackage Locale
*/

// -[ frFR Localization ]-

// Installer names
$lang['memberslist']            = 'Liste des membres';
$lang['memberslist_desc']       = 'Une liste des membres triable et filtrable';

// Button names
$lang['memberslist_Members']	= 'Membres|Affiche la liste des membres de la guilde avec divers informations comme les notes de personnages, la dernière fois où le joueur était en ligne, etc.';
$lang['memberslist_Stats']		= 'Statistiques|Affiche les caractéristiques de chaque membre de la guilde comme par exemple l\'intelligence, l\'endurance, etc.';
$lang['memberslist_Honor']		= 'Honneur|Affiche les informations liées au JcJ de chaque membre de la guilde.';
$lang['memberslist_Log']		= 'Journal des membres|Affiche les entrées et sorties des membres de la guilde.';
$lang['memberslist_Realm']		= 'Membres|Affiche la liste des membres des guildes du royaume.';
$lang['memberslist_RealmGuild']	= 'Guildes|Affiche la liste des guildes du royaume.';
$lang['memberslist_Skills']		= 'Skills|Displays each member\'s skills';

// Interface wordings
$lang['memberssortfilter']		= 'Ordre de tri et filtres';
$lang['memberssort']			= 'Ordre de tri';
$lang['memberscolshow']			= 'Monter/cacher les colonnes';
$lang['membersfilter']			= 'Données de filtrage';

$lang['openall']                = 'Tout ouvrir';
$lang['closeall']               = 'Tout fermer';
$lang['ungroupalts']            = 'Ungroup alts';
$lang['openalts']               = 'Group alts (open)';
$lang['closealts']              = 'Group alts (closed)';
$lang['clientsort']             = 'Tri client';
$lang['serversort']             = 'Tri serveur';

// Column headers
$lang['onote']                  = 'Note d\'officier';

$lang['honorpoints']            = 'Points d\'honneur';
$lang['arenapoints']            = 'Points d\'arène';

$lang['main_name']              = 'Main name';
$lang['alt_type']               = 'Alt type';

$lang['xp_to_go']               = '%1$s XP avant le niveau %2$s';

$lang['skill_level']			= 'Niveau de compétence';

// Last Online words
$lang['online_at_update']       = 'Connecté à la mise à jour';
$lang['second']                 = 'il y a %s seconde';
$lang['seconds']                = 'il y a %s secondes';
$lang['minute']                 = 'il y a %s minute';
$lang['minutes']                = 'il y a %s minutes';
$lang['hour']                   = 'il y a %s heure';
$lang['hours']                  = 'il y a %s heures';
$lang['day']                    = 'il y a %s jour';
$lang['days']                   = 'il y a %s jours';
$lang['week']                   = 'il y a %s semaine';
$lang['weeks']                  = 'il y a %s semaines';
$lang['month']                  = 'il y a %s mois';
$lang['months']                 = 'il y a %s mois';
$lang['year']                   = 'il y a %s année';
$lang['years']                  = 'il y a %s années';

$lang['armor_tooltip']			= 'Réduit les dégâts physiques subit de %1$s%%';

$lang['motd']                   = 'MOTD';
$lang['accounts']               = 'Comptes';

// Configuration
$lang['memberslist_config']		= 'Aller à la configuration de la liste des membres';
$lang['memberslist_config_page']= 'Configuration de la liste des membres';
$lang['documentation']			= 'Documentation';
$lang['uninstall']				= 'Désinstaller';

// Page names
$lang['admin']['main']			= 'Main|Back to the global part of the configuration.';
$lang['admin']['display']       = 'Affichage|Configurer les options d\'affichage relatif à la liste des membres.';
$lang['admin']['members']       = 'Membres|Configurer la lisibilité des colonnes de la liste des membres.';
$lang['admin']['stats']         = 'Statistiques|Configurer la lisibilité des colonnes de la liste des statistiques.';
$lang['admin']['honor']         = 'Honneur|Configurer la lisibilité des colonnes de la liste d\'honneur.';
$lang['admin']['log']           = 'Journal des membres|Configurer la lisibilité des colonnes du journal des membres.';
$lang['admin']['build']         = 'Relations principaux/secondaires|Configure la façon dont les personnages principaux/secondaires sont détectés.';
$lang['admin']['gbuild']        = 'Main/Alt per guild|Configure guild-specific Main/Alt detection rules.';
$lang['admin']['ml_wiki']       = 'Documentation|Documentation de la liste des membres de WoWroster sur le Wiki officiel.';
$lang['admin']['updMainAlt']    = 'Mise à jour des relations|Mettre à jour les relations entre principaux et secondaires selon les données déjà présentes dans la base.';
$lang['admin']['page_size']		= 'Taille de la page|Configurer le nombre d\'objets par page, ou 0 pour aucune pagination.';

// Settings names on display page
$lang['admin']['openfilter']	= 'Ouverture boîte de filtre|Spécifier si vous voulez que la boîte de filtre soit ouverte ou fermée par défaut.';
$lang['admin']['nojs']          = 'Type de liste|Spécifier si vous voulez utiliser un tri côté serveur ou un tri+filtre côté client.';
$lang['admin']['def_sort']		= 'Tri par défaut|Spécifier la méthode de tir par défaut.';
$lang['admin']['member_tooltip']= 'Member Tooltip|Turn the info tooltips on the member names on or off.';
$lang['admin']['group_alts']    = 'Group Alts|Goup alts under their main, rather than sorting them separately.';
$lang['admin']['icon_size']     = 'Taille des icônes|Spécifier la taille des icônes de classe, d\'honneur et de profession.';
$lang['admin']['spec_icon']		= 'Icône de talent|Affiche ou non l\'icône de spécialisation de talent.';
$lang['admin']['class_icon']    = 'Icône de classe|Contrôler l\'affichage des icônes de classe et de spécialisation de talent.<br />Full - affiche les icônes de classe et de talent<br />On - affiche uniquement l\'icône de classe<br />Off- cache les icônes';
$lang['admin']['class_text']    = 'Texte de classe|Contrôler l\'affichage du texte de la classe.<br />Color - texte de classe en couleur<br />On - affiche le texte de classe<br />Off - cache le texte de classe';
$lang['admin']['talent_text']   = 'Texte de talent|Affiche la spécialisation du talent à la place du texte de classe.';
$lang['admin']['level_bar']     = 'Barre de niveau|Afficher des barres de niveau à la place de nombres.';
$lang['admin']['honor_icon']    = 'Icône d\'honneur|Affiche l\'îcone du rang d\'honneur.';
$lang['admin']['compress_note'] = 'Notes compressées|Afficher les notes de guilde dans un tooltip à la place d\'une colonne.';

// Settings on Members page
$lang['admin']['member_update_inst'] = 'Instructions de mise à jour|Contrôle l\'affichage des instructions de mise à jour sur la page des membres';
$lang['admin']['member_motd']	= 'MOTD de guilde|Monte le message du jour de la guide en haut de la page des membres';
$lang['admin']['member_hslist']	= 'Statistiques d\'honneur|Contrôle l\'affichage des statistiques d\'honneur sur la page des membres';
$lang['admin']['member_pvplist']= 'PvP-Logger Stats|Controls the display of the PvP-Logger stats on the members page<br />If you have disabled PvPlog uploading, there is no need to have this on';
$lang['admin']['member_class']  = 'Classe|Spécifie la présence de la colonne de classe sur la page des membres';
$lang['admin']['member_level']  = 'Niveau|Spécifie la présence de la colonne de niveau sur la page des membres';
$lang['admin']['member_gtitle'] = 'Titre de guilde|Spécifie la présence de la colonne de titre de guilde sur la page des membres';
$lang['admin']['member_hrank']  = 'Rang d\'honneur|Spécifie la présence de la colonne du dernier rang d\'honneur sur la page des membres';
$lang['admin']['member_prof']   = 'Profession|Spécifie la présence de la colonne des professions sur la page des membres';
$lang['admin']['member_hearth'] = 'Pierre de foyer|Spécifie la présence de la colonne de l\'endroit de liaison de la pierre de foyer sur la page des membres';
$lang['admin']['member_zone']   = 'Zone|Spécifie la présence de la colonne de la dernière zone sur la page des membres';
$lang['admin']['member_online'] = 'Last Online|Set visibility of the last online column on the members page';
$lang['admin']['member_update'] = 'Dernière mise à jour|Spécifie la présence de la colonne de date de denière mise à jour sur la page des membres';
$lang['admin']['member_note']   = 'Note|Spécifie la présence de la colonne des notes sur la page des membres';
$lang['admin']['member_onote']  = 'Note d\'officer|Spécifie la présence de la colonne des notes d\'officier sur la page des membres';

// Settings on Stats page
$lang['admin']['stats_update_inst'] = 'Instructions de mise à jour|Contrôle l\'affiche des instructions de mise à jour sur la page des statistiques';
$lang['admin']['stats_motd']	= 'MOTD de guilde|Monte le message du jour de la guide en haut de la page des statistiques';
$lang['admin']['stats_hslist']  = 'Statistiques d\'honneur|Contrôle l\'affichage des statistiques d\'honneur sur la page des statistiques';
$lang['admin']['stats_pvplist']	= 'PvP-Logger Stats|Controls the display of the PvP-Logger stats on the stats page<br />If you have disabled PvPlog uploading, there is no need to have this on';
$lang['admin']['stats_class']   = 'Classe|Spécifie la présence de la colonne de classe sur la page des statistiques';
$lang['admin']['stats_level']   = 'Niveau|Spécifie la présence de la colonne de niveau sur la page des statistiques';
$lang['admin']['stats_str']     = 'Force|Spécifie la présence de la colonne force sur la page des statistiques';
$lang['admin']['stats_agi']     = 'Agilité|Spécifie la présence de la colonne agilité sur la page des statistiques';
$lang['admin']['stats_sta']     = 'Endurance|Spécifie la présence de la colonne endurance sur la page des statistiques';
$lang['admin']['stats_int']     = 'Intelligence|Spécifie la présence de la colonne intelligence sur la page des statistiques';
$lang['admin']['stats_spi']     = 'Esprit|Spécifie la présence de la colonne esprit sur la page des statistiques';
$lang['admin']['stats_sum']     = 'Total|Spécifie la présence de la colonne somme des caracrtéristiques sur la page des statistiques';
$lang['admin']['stats_health']  = 'Vie|Spécifie la présence de la colonne vie sur la page des statistiques';
$lang['admin']['stats_mana']    = 'Mana|Spécifie la présence de la colonne mana sur la page des statistiques';
$lang['admin']['stats_armor']   = 'Armure|Spécifie la présence de la colonne armure dans al page des statistiques';
$lang['admin']['stats_dodge']   = 'Esquive|Spécifie la présence de la colonne esquive sur la page des statistiques';
$lang['admin']['stats_parry']   = 'Parade|Spécifie la présence de la colonne parade sur la page des statistiques';
$lang['admin']['stats_block']   = 'Blocage|Spécifie la présence de la colonne blocage sur la page des statistiques';
$lang['admin']['stats_crit']    = 'Critiques|Spécifie la présence de la colonne critiques sur la page des statistiques';

// Settings on Honor page
$lang['admin']['honor_update_inst'] = 'Instructions de mise à jour|Contrôle l\'affichage des instructions de mise à jour sur la page d\'honneur';
$lang['admin']['honor_motd']	= 'MOTD de guilde|Monte le message du jour de la guide en haut de la page d\'honneur';
$lang['admin']['honor_hslist']  = 'Statistiques d\'honneur|Contrôle l\'affichage des statistiques d\'honneur sur la page d\'honneur';
$lang['admin']['honor_pvplist']	= 'PvP-Logger Stats|Controls the display of the PvP-Logger stats on the honor page<br />If you have disabled PvPlog uploading, there is no need to have this on';
$lang['admin']['honor_class']   = 'Classe|Spécifie la présence de la colonne de classe sur la page d\'honneur';
$lang['admin']['honor_level']   = 'Niveau|Spécifie la présence de la colonne de niveau sur la page d\'honneur';
$lang['admin']['honor_thk']     = 'VH du jour|Spécifier la présence de la colonne VH du jour sur la page d\'honneur';
$lang['admin']['honor_tcp']     = 'CP du jour|Spécifier la présence de la colonne CP du jour sur la page d\'honneur';
$lang['admin']['honor_yhk']     = 'VH d\'hier|Spécifier la présence de la colonne VH d\'hier sur la page d\'honneur';
$lang['admin']['honor_ycp']     = 'CP d\'hier|Spécifier la présence de la colonne CP d\'hier sur la page d\'honneur';
$lang['admin']['honor_lifehk']  = 'VH à vie|Spécifier la présence de la colonne VH à vie sur la page d\'honneur';
$lang['admin']['honor_hrank']   = 'Rang d\'honneur|Spécifie la présence de la colonne du rang d\'honneur sur la page d\'honneur';
$lang['admin']['honor_hp']      = 'Points d\'honneur|Spécifie la présence de la colonne des points d\'honneur sur la page d\'honneur';
$lang['admin']['honor_ap']      = 'Points d\'arène|Spécifie la présence de la colonne des points d\'arène sur la page d\'honneur';

// Settings on Members page
$lang['admin']['log_update_inst'] = 'Instructions de mise à jour|Contrôle l\'affichage des instructions de mise à jour sur la page du journal des membres';
$lang['admin']['log_motd']		= 'MOTD de guilde|Monte le message du jour de la guide en haut de la page du journal des membres';
$lang['admin']['log_hslist']	= 'Statistiques d\'honneur|Contrôle l\'affichage des statistiques d\'honneur sur la page du journal des membres';
$lang['admin']['log_pvplist']	= 'PvP-Logger Stats|Controls the display of the PvP-Logger stats on the member log page<br />If you have disabled PvPlog uploading, there is no need to have this on';
$lang['admin']['log_class']		= 'Classe|Spécifie la présence de la colonne de classe sur la page du journal des membres';
$lang['admin']['log_level']		= 'Niveau|Spécifie la présence de la colonne de niveau sur la page du journal des membres';
$lang['admin']['log_gtitle']	= 'Titre de guilde|Spécifie la présence de la colonne de titre de guilde sur la page du journal des membres';
$lang['admin']['log_type']		= 'Type de mise à jour|Spécifie la présence de la colonne de type de mise à jour sur la page du journal des membres';
$lang['admin']['log_date']		= 'Dernière mise à jour|Spécifie la présence de la colonne de date de denière mise à jour sur la page du journal des membres';
$lang['admin']['log_note']		= 'Note|Spécifie la présence de la colonne des notes sur la page du journal des membres';
$lang['admin']['log_onote']		= 'Note d\'officer|Spécifie la présence de la colonne des notes d\'officier sur la page du journal des membres';

// Settings names on build page
$lang['admin']['use_global']    = 'Use global settings|Use global settings rather than these local ones for this guild.';
$lang['admin']['getmain_regex'] = 'Regexp|This field specifies the regex to use. <br /> See the wiki link for details.';
$lang['admin']['getmain_field'] = 'Apply on field|This field specifies which member field the regex is applied on. <br /> See the wiki link for details.';
$lang['admin']['getmain_match'] = 'Use match no|This field specifies which return value of the regex is used. <br /> See the wiki link for details.';
$lang['admin']['getmain_main']  = 'Main identifier|If the regex resolves to this value the character is assumed to be a main.';
$lang['admin']['defmain']       = 'No result|Set what you want the character to be defined as if the regex doesn\'t return anything.';
$lang['admin']['invmain']       = 'Invalid result|Set what you want the character to be defined as <br /> if the regex returns a result that isn\'t a guild member or equal to the main identifier.';
$lang['admin']['altofalt']      = 'Alt of Alt|Specify what to do if the character is a mainless alt.';
$lang['admin']['update_type']   = 'Update type|Specify on which trigger types to update main/alt relations.';
