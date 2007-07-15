<?php
/**
 * WoWRoster.net WoWRoster
 *
 * frFR Locale File
 *
 * frFR translation by wowodo, lesablier, Exerladan, and Ansgar
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.1
 * @package    WoWRoster
 * @subpackage Locale
*/

$lang['langname'] = 'French';

//Instructions how to upload, as seen on the mainpage
$lang['update_link']='Cliquer ici pour les instructions de mise à jour.';
$lang['update_instructions']='Instructions de mise à jour.';

$lang['lualocation']='Cliquer parcourir (browse) et télécharger les fichiers *.lua<br />';

$lang['filelocation']='se trouve sous <br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$lang['noGuild']='Impossible de trouver la guilde dans la base de données. Mettre à jour la liste des membres.';
$lang['nodata']='Impossible de trouver la guilde: <b>\'%1$s\'</b> du serveur <b>\'%2$s\'</b><br />Vous devez préalablement<a href="%3$s">charger votre guilde</a> et <a href="%4$s">finaliser la configuration</a><br /><br /><a href="http://www.wowroster.net/wiki/Roster:Install" target="_blank">Les instructions d\'installation sont disponibles</a>';
$lang['nodata_title']='No Guild Data';

$lang['update_page']='Mise à jour du profil';

$lang['guild_nameNotFound']='Impossible de mettre à jour la guilde &quot;%s&quot;. Vérifier la configuration!';
$lang['guild_addonNotFound']='Impossible de trouver la Guilde. L\'Addon GuildProfiler est-il installé correctement?';

$lang['ignored']='Ignoré';
$lang['update_disabled']='L\'accès à Update.php a été désactivé';

$lang['nofileUploaded']='Votre UniUploader n\'a pas téléchargé de fichier(s), ou des fichiers erronés.';
$lang['roster_upd_pwLabel']='Mot de passe du Roster';
$lang['roster_upd_pw_help']='(Requis lors d\'une mise à jour de la Guilde)';


$lang['roster_error'] = 'Roster Error';
$lang['sql_queries'] = 'SQL Queries';
$lang['invalid_char_module'] = 'Invalid characters in module name';
$lang['module_not_exist'] = 'The module [%1$s] does not exist';

$lang['addon_error'] = 'Addon Error';
$lang['specify_addon'] = 'You must specify an addon name!';
$lang['addon_not_exist'] = '<b>The addon [%1$s] does not exist!</b>';
$lang['addon_disabled'] = '<b>The addon [%1$s] has been disabled</b>';
$lang['addon_not_installed'] = '<b>The addon [%1$s] has not been installed yet</b>';
$lang['addon_no_config'] = '<b>The addon [%1$s] does not have a config</b>';

$lang['char_error'] = 'Character Error';
$lang['specify_char'] = 'Character was not specified';
$lang['no_char_id'] = 'Sorry no character data for member_id [ %1$s ]';
$lang['no_char_name'] = 'Sorry no character data for <strong>%1$s</strong> of <strong>%2$s</strong>';

$lang['roster_cp'] = 'Roster Control Panel';
$lang['roster_cp_ab'] = 'Roster CP';
$lang['roster_cp_not_exist'] = 'Page [%1$s] does not exist';
$lang['roster_cp_invalid'] = 'Invalid page specified or insufficient credentials to access this page';

$lang['parsing_files'] = 'Parsing files';
$lang['parsed_time'] = 'Parsed %1$s in %2$s seconds';
$lang['error_parsed_time'] = 'Error while parsing %1$s after %2$s seconds';
$lang['upload_not_accept'] = 'Did not accept %1$s';
$lang['not_updating'] = 'NOT Updating %1$s for [%2$s] - %3$s';
$lang['not_update_guild'] = 'NOT Updating Guild List for %1$s';
$lang['not_update_guild_time'] = 'NOT Updating Guild List for %1$s. Guild profile is too old';
$lang['no_members'] = 'Data does not contain any guild members';
$lang['upload_data'] = 'Updating %1$s Data for [<span class="orange">%2$s@%4$s-%3$s</span>]';
$lang['realm_ignored'] = 'Realm: %1$s Not Scanned';
$lang['guild_realm_ignored'] = 'Guild: %1$s @ Realm: %2$s Not Scanned';
$lang['update_members'] = 'Updating Members';
$lang['gp_user_only'] = 'GuildProfiler User Only';
$lang['update_errors'] = 'Update Errors';
$lang['update_log'] = 'Update Log';
$lang['save_error_log'] = 'Save Error Log';
$lang['save_update_log'] = 'Save Update Log';

$lang['new_version_available'] = 'There is a new version of %1$s available <span class="green">v%2$s</span><br />Get it <a href="%3$s" target="_blank">HERE</a>';

$lang['upgrade_wowroster'] = 'Upgrade WoWRoster';
$lang['upgrade_wowroster_text'] = "Looks like you've loaded a new version of Roster<br /><br />\nYour Version: <span class=\"red\">%1\$s</span><br />\nNew Version: <span class=\"green\">%2\$s</span><br /><br />\n<a href=\"upgrade.php\" style=\"border:1px outset white;padding:2px 6px 2px 6px;\">UPGRADE</a>";
$lang['remove_install_files'] = 'Remove Install Files';
$lang['remove_install_files_text'] = 'Please remove the <span class="green">install/</span> folder and the files <span class="green">install.php</span> and <span class="green">upgrade.php</span> in this directory';

// Menu buttons
$lang['menu_header_01'] = 'Guild Information';
$lang['menu_header_02'] = 'Realm Information';
$lang['menu_header_03'] = 'Update Profile';
$lang['menu_header_04'] = 'Utilities';
$lang['menu_header_scope_panel'] = '%s Panel';

// Updating Instructions
$lang['index_text_uniloader'] = '<b><u>Prérequis à l\'utilisation d\'UniUploader:</b></u><a href="http://www.microsoft.com/downloads/details.aspx?FamilyID=0856EACB-4362-4B0D-8EDD-AAB15C5E04F5&displaylang=en">Microsoft .NET Framework</a> installé<br />Pour les utilisateurs d\'OS autres que Windows, utiliser JUniUploader qui vous permettra d\'effectuer les mêmes opérations que UniUploader mais en mode Java.';

$lang['update_instruct']='
<strong>Actualisation automatique recommandée:<strong>
<ul>
<li>Utiliser <a href="%1$s" target="_blank">UniUploader</a><br />
%2$s</li>
</ul>
<strong>Instructions pour actualiser le profil:<strong>
<ol>
<li>Download <a href="%3$s" target="_blank">Character Profiler</a></li>
<li>Décompresser le fichier zip dans son propre répertoire dans le répertoire *WoW Directory*\Interface\Addons\.</li>
<li>Démarrer WoW</li>
<li>Ouvrir votre compte en banque, la fenêtre des quêtes, et la fenêtre des professions qui contient les recettes</li>
<li>Se déconnecter ou quitter WoW.<br />(Voir ci-dessus si vous disposez d\'UniUploader pour automatiser l\'envois des informations.)</li>
<li>Aller sur la page <a href="%4$s">d\'actualisation</a></li>
<li>%5$s</li>
</ol>';

$lang['update_instructpvp']='
<strong>Statistique PvP Optionnel:</strong>
<ol>
<li>Télécharger <a href="%1$s" target="_blank">PvPLog</a></li>
<li>Décompresser le fichier zip dans son propre directory sous *WoW Directory*\Interface\Addons\ (PvPLog\) répertoire.</li>
<li>Duel ou PvP</li>
<li>Envoyer les informations PvPLog.lua (voir étape 7 de l\'actualisation du profil).</li>
</ol>';

$lang['roster_credits']='Props to <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, and <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> for the original code used for this site.<br />
WoWRoster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="%1$s">Additional Credits</a>';


//Charset
$lang['charset']="charset=utf-8";

$lang['timeformat'] = '%d/%m/%Y %H:%i:%s'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$lang['phptimeformat'] = 'd/m/Y H:i:s';    // PHP date() Time format (example - 'M D jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


/**
 * Realmstatus Localizations
 */
$lang['rs'] = array(
	'ERROR' => 'Error',
	'NOSTATUS' => 'No Status',
	'UNKNOWN' => 'Unknown',
	'RPPVP' => 'RP-PvP',
	'PVE' => 'Normal',
	'PVP' => 'PvP',
	'RP' => 'RP',
	'OFFLINE' => 'Offline',
	'LOW' => 'Low',
	'MEDIUM' => 'Medium',
	'HIGH' => 'High',
	'MAX' => 'Max',
);


//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$lang['guildless']='Guildless';
$lang['util']='Utilities';
$lang['char']='Character';
$lang['upload']='Télécharger';
$lang['required']='Requis';
$lang['optional']='Optionnel';
$lang['attack']='Attaque';
$lang['defense']='Défense';
$lang['class']='Classe';
$lang['race']='Race';
$lang['level']='Niveau';
$lang['lastzone']='Dernière Zone';
$lang['note']='Note';
$lang['officer_note']='Officer Note';
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
$lang['hearthed']='Pierre de Foyer';
$lang['recipes']='Recettes';
$lang['bags']='Sacs';
$lang['character']='Personnage';
$lang['money']='Argent';
$lang['bank']='Banque';
$lang['raid']='CT_Raid';
$lang['quests']='Quêtes';
$lang['roster']='Roster';
$lang['alternate']='Alternate';
$lang['byclass']='Par Classe';
$lang['menustats']='Stats';
$lang['menuhonor']='Honneur';
$lang['search']='Rechercher';
$lang['update']='Update';
$lang['credit']='Crédits';
$lang['members']='Membres';
$lang['items']='Objets';
$lang['find']='Trouver les objets contenants';
$lang['upprofile']='Mise à jour du Profil';
$lang['backlink']='Retour au Roster';
$lang['gender']='Genre';
$lang['unusedtrainingpoints']='Points de formation non utilisés';
$lang['unusedtalentpoints']='Points de talent non utilisés';
$lang['talentexport']='Export Talent Build';
$lang['questlog']='Journal des Quêtes';
$lang['recipelist']='Liste des recettes';
$lang['reagents']='Réactifs';
$lang['item']='Objet';
$lang['type']='Type';
$lang['date']='Date';
$lang['complete'] = 'Complete';
$lang['failed'] = 'Failed';
$lang['completedsteps'] = 'Etapes finies';
$lang['currentstep'] = 'Etapes actuelles';
$lang['uncompletedsteps'] = 'Etapes incomplètes';
$lang['key'] = 'Clef';
$lang['timeplayed'] = 'Temps joué';
$lang['timelevelplayed'] = 'Temps joué à ce niveau';
$lang['Addon'] = 'Addons:';
$lang['advancedstats'] = 'Statistiques avancées';
$lang['crit'] = 'Crit';
$lang['dodge'] = 'Esquive';
$lang['parry'] = 'Parade';
$lang['block'] = 'Bloquer';
$lang['realm'] = 'Royaume';
$lang['region'] = 'Region';
$lang['server'] = 'Server';
$lang['faction'] = 'Faction';
$lang['page'] = 'Page';
$lang['general'] = 'Général';
$lang['prev'] = 'Avant';
$lang['next'] = 'Après';
$lang['memberlog'] = 'Journal';
$lang['removed'] = 'Enlevé';
$lang['added'] = 'Ajouté';
$lang['add'] = 'Add';
$lang['delete'] = 'Delete';
$lang['updated'] = 'Updated';
$lang['no_info'] = 'No Information';
$lang['none']='Rien';
$lang['kills']='Tués';
$lang['allow'] = 'Allow';
$lang['disallow'] = 'Disallow';
$lang['locale'] = 'Locale';
$lang['language'] = 'Language';
$lang['default'] = 'Default';

$lang['rosterdiag'] = 'Diagnostic du Roster';
$lang['difficulty'] = 'Difficultée';
$lang['recipe_4'] = 'optimal';
$lang['recipe_3'] = 'moyen';
$lang['recipe_2'] = 'facile';
$lang['recipe_1'] = 'insignifiant';
$lang['roster_config'] = 'Configuration Roster';

$lang['search_names'] = 'Search Names';
$lang['search_items'] = 'Search Items';
$lang['search_tooltips'] = 'Search Tooltips';

//this needs to be exact as it is the wording in the db
$lang['professions']='Métiers';
$lang['secondary']='Compétences secondaires';
$lang['Blacksmithing']='Forge';
$lang['Mining']='Minage';
$lang['Herbalism']='Herboristerie';
$lang['Alchemy']='Alchimie';
$lang['Leatherworking']='Travail du cuir';
$lang['Jewelcrafting']='Joaillerie';
$lang['Skinning']='Dépeçage';
$lang['Tailoring']='Couture';
$lang['Enchanting']='Enchantement';
$lang['Engineering']='Ingénierie';
$lang['Cooking']='Cuisine';
$lang['Fishing']='Pêche';
$lang['First Aid']='Premiers soins';
$lang['Poisons']='Poisons';
$lang['backpack']='Sac �  dos';
$lang['PvPRankNone']='Rien';

// Uses preg_match() to find required level in recipie tooltip
$lang['requires_level'] = '/Niveau ([\d]+) requis/';

//Tradeskill-Array
$lang['tsArray'] = array (
	$lang['Alchemy'],
	$lang['Herbalism'],
	$lang['Blacksmithing'],
	$lang['Mining'],
	$lang['Leatherworking'],
	$lang['Jewelcrafting'],
	$lang['Skinning'],
	$lang['Tailoring'],
	$lang['Enchanting'],
	$lang['Engineering'],
	$lang['Cooking'],
	$lang['Fishing'],
	$lang['First Aid'],
	$lang['Poisons'],
);

//Tradeskill Icons-Array
$lang['ts_iconArray'] = array (
	$lang['Alchemy']=>'trade_alchemy',
	$lang['Herbalism']=>'trade_herbalism',
	$lang['Blacksmithing']=>'trade_blacksmithing',
	$lang['Mining']=>'trade_mining',
	$lang['Leatherworking']=>'trade_leatherworking',
	$lang['Jewelcrafting']=>'inv_misc_gem_02',
	$lang['Skinning']=>'inv_misc_pelt_wolf_01',
	$lang['Tailoring']=>'trade_tailoring',
	$lang['Enchanting']=>'trade_engraving',
	$lang['Engineering']=>'trade_engineering',
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
	'Draenei' => 'ability_mount_ridingelekk',
	'Paladin'=>'ability_mount_dreadsteed',
	'D�moniste'=>'ability_mount_nightmarehorse'
);

// Class Icons-Array
$lang['class_iconArray'] = array (
	'Druide'=>'ability_druid_maul',
	'Chasseur'=>'inv_weapon_bow_08',
	'Mage'=>'inv_staff_13',
	'Paladin'=>'spell_fire_flametounge',
	'Pr�tre'=>'spell_holy_layonhands',
	'Voleur'=>'inv_throwingknife_04',
	'Chaman'=>'spell_nature_bloodlust',
	'D�moniste'=>'spell_shadow_cripple',
	'Guerrier'=>'inv_sword_25',
);

// Class Color-Array
$lang['class_colorArray'] = array(
	'Druide' => 'FF7C0A',
	'Chasseur' => 'AAD372',
	'Mage' => '68CCEF',
	'Paladin' => 'F48CBA',
	'Pr�tre' => 'ffffff',
	'Voleur' => 'FFF468',
	'Chaman' => '00DBBA',
	'D�moniste' => '9382C9',
	'Guerrier' => 'C69B6D'
);

$lang['pvplist']='Stats JcJ/PvP';
$lang['pvplist1']='Guilde qui a le plus souffert de nos actions';
$lang['pvplist2']='Guilde qui nous a le plus fait souffrir';
$lang['pvplist3']='Joueur qui a le plus souffert de nos actions';
$lang['pvplist4']='Joueur qui nous a le plus tué';
$lang['pvplist5']='Membre de la guilde tuant le plus';
$lang['pvplist6']='Membre de la guilde tué le plus';
$lang['pvplist7']='Membre ayant la meilleure moyenne de mort';
$lang['pvplist8']='Membre ayant la meilleure moyenne de défaîte';

$lang['hslist']=' Stats du Système d\'Honneur';
$lang['hslist1']='Membre le mieux classé';
$lang['hslist2']='Membre ayant le plus de VH';
$lang['hslist3']='Le plus de Points d\'Honneur';
$lang['hslist4']='Le plus de Points d\'Arène';

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
$lang['todayhk']='Today HK';
$lang['todaycp']='Today CP';
$lang['yesterday']='Hier';
$lang['yesthk']='Hier HK';
$lang['yestcp']='Hier CP';
$lang['thisweek']='Cette semaine';
$lang['lastweek']='Semaine passée';
$lang['lifetime']='A vie';
$lang['lifehk']='A vie HK';
$lang['honorkills']='Vict. Honorables';
$lang['dishonorkills']='Vict. Déshonorantes';
$lang['honor']='Honneur';
$lang['standing']='Position';
$lang['highestrank']='Plus haut niveau';
$lang['arena']='Arène';

$lang['when']='Quand';
$lang['guild']='Guilde';
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
$lang['hated']='Détesté';
$lang['atwar']='En guerre';
$lang['notatwar']='Pas en guerre';


// Quests page external links (on character quests page)
// $lang['questlinks'][#]['name']  This is the name displayed on the quests page
// $lang['questlinks'][#]['url#']  This is the URL used for the quest lookup

$lang['questlinks'][0]['name']='Judgehype FR';
$lang['questlinks'][0]['url1']='http://worldofwarcraft.judgehype.com/index.php?page=squete&amp;Ckey=';
$lang['questlinks'][0]['url2']='&amp;obj=&amp;desc=&amp;minl=';
$lang['questlinks'][0]['url3']='&amp;maxl=';

$lang['questlinks'][1]['name']='WoWDBU FR';
$lang['questlinks'][1]['url1']='http://wowdbu.com/7.html?m=2&amp;mode=qsearch&amp;title=';
$lang['questlinks'][1]['url2']='&amp;obj=&amp;desc=&amp;minl=';
$lang['questlinks'][1]['url3']='&amp;maxl=';

$lang['questlinks'][2]['name']='Allakhazam US';
$lang['questlinks'][2]['url1']='http://wow.allakhazam.com/db/qlookup.html?name=';
$lang['questlinks'][2]['url2']='&amp;obj=&amp;desc=&amp;minl=';
$lang['questlinks'][2]['url3']='&amp;maxl=';

//$lang['questlinks'][3]['name']='WoWHead';
//$lang['questlinks'][3]['url1']='http://www.wowhead.com/?quests&amp;filter=ti=';
//$lang['questlinks'][3]['url2']=';minle=';
//$lang['questlinks'][3]['url3']=';maxle=';

// Items external link
// Add as manu item links as you need
// Just make sure their names are unique
$lang['itemlink'] = 'Item Links';
$lang['itemlinks']['WoWDBU FR'] ='http://wowdbu.com/2-1.html?way=asc&amp;order=name&amp;showstats=&amp;type_limit=0&amp;lvlmin=&amp;lvlmax=&amp;name=';
$lang['itemlinks']['Judgehype FR'] = 'http://worldofwarcraft.judgehype.com/index.php?page=sobj&amp;Ckey=';
$lang['itemlinks']['Allakhazam'] = 'http://wow.allakhazam.com/search.html?q=';
//$lang['itemlinks']['WoWHead'] = 'http://www.wowhead.com/?items&amp;filter=na=';

// WoW Data Site Search
// Add as many item links as you need
// Just make sure their names are unique
$lang['data_search'] = 'WoW Data Site Search';
$lang['data_links']['Thottbot'] = 'http://www.thottbot.com/index.cgi?s=';
$lang['data_links']['Allakhazam'] = 'http://wow.allakhazam.com/search.html?q=';
$lang['data_links']['WWN Data'] = 'http://wwndata.worldofwar.net/search.php?search=';
$lang['data_links']['WoWHead'] = 'http://www.wowhead.com/?search=';


// Definition for item tooltip coloring
$lang['tooltip_use']='Utiliser';
$lang['tooltip_requires']='Niveau';
$lang['tooltip_reinforced']='renforcée';
$lang['tooltip_soulbound']='Lié';
$lang['tooltip_boe']='Lié quand équipé';
$lang['tooltip_equip']='Équipé';
$lang['tooltip_equip_restores']='Équipé : Rend';
$lang['tooltip_equip_when']='Équipé : Lorsque';
$lang['tooltip_chance']='Chance';
$lang['tooltip_enchant']='Enchantement';
$lang['tooltip_set']='Set';
$lang['tooltip_rank']='Rang';
$lang['tooltip_next_rank']='Prochain rang';
$lang['tooltip_spell_damage']='les dégâts et les soins produits par les sorts et effets magiques';
$lang['tooltip_school_damage']='les dégâts infligés par les sorts et effets';
$lang['tooltip_healing_power']='les soins prodigués par les sorts et effets';
$lang['tooltip_chance_hit']='Chances quand touché :';
$lang['tooltip_reinforced_armor']='Armure renforcée';
$lang['tooltip_damage_reduction']='Réduit les points de dégâts';
//--new
$lang['tooltip_durability']='Durability';
$lang['tooltip_unique']='Unique';
$lang['tooltip_speed']='Speed';
$lang['tooltip_poisoneffect']='^Each strike has';

$lang['tooltip_preg_armour']='/^(\d+) Armor/';
$lang['tooltip_preg_durability']='/Durability (\d+) \/ (\d+)/';
$lang['tooltip_preg_madeby']='/\<Made By (\w+)\>/';
$lang['tooltip_preg_bags']='/^(\d+) Slot/';
$lang['tooltip_preg_socketbonus']='/Socket Bonus: (.+)\n/';
$lang['tooltip_preg_classes']='/^Classes: (.+)/';
$lang['tooltip_preg_races']='/^Races: (.+)/';
$lang['tooltip_preg_charges']='/(\d+) Charges/';
$lang['tooltip_preg_block']='/(\d+) (Block)/';
$lang['tooltip_preg_emptysocket']='/(Red|Yellow|Blue|Meta) Socket/';

$lang['tooltip_armour_types']='Cloth|Leather|Mail|Plate';
$lang['tooltip_weapon_types']='Axe|Bow|Crossbow|Dagger|Fishing Pole|Fist Weapon|Gun|Mace|Polearm|Staff|Sword|Thrown|Wand';
$lang['tooltip_bind_types']='Soulbound|Binds when equipped|Quest Item|Binds when used';
$lang['tooltip_misc_types']='Finger|Neck|Back|Shirt|Trinket|Tabard|Head|Chest';
$lang['tooltip_garbage']='<Shift Right Click to Socket>|<Right Click to Read>';

//CP v2.1.1+ Gems info
//uses preg_match() to find the type and color of the gem
$lang['gem_preg_singlecolor'] = '/Matches a (\w+) Socket/';
$lang['gem_preg_multicolor'] = '/Matches a (\w+) or (\w+) Socket/';
$lang['gem_preg_meta'] = '/Only fits in a meta gem slot/';
$lang['gem_preg_prismatic'] = '/Matches a Red, Yellow or Blue Socket/';

//Gems color Array
$lang['gem_colors'] = array(
	'red' => 'Red',
	'blue' => 'Blue',
	'yellow' => 'Yellow',
	'green' => 'Green',
	'orange' => 'Orange',
	'purple' => 'Purple',
	'prismatic' => 'Prismatic');

// Warlock pet names for icon displaying
$lang['Imp']='Diablotin';
$lang['Voidwalker']='Marcheur du Vide';
$lang['Succubus']='Succube';
$lang['Felhunter']='Chasseur corrompu';
$lang['Infernal']='Infernal';
$lang['Felguard']='Gangregarde';

// Max experiance for exp bar on char page
$lang['max_exp']='Max XP';

// Error messages
$lang['CPver_err']='La version du CharacterProfiler utilisé pour capturer les données pour ce personnage est plus ancienne que la version minimum autorisée pour le téléchargement.<br />SVP assurez vous que vous fonctionnez avec la v%1$s et que vous vous êtes connecté sur ce personnage et avez sauvé les données en utilisant cette version.';
$lang['GPver_err']='La version du GuildProfiler utilisé pour capturer les données pour ce personnage est plus ancienne que la version minimum autorisée pour le téléchargement.<br />SVP assurez vous que vous fonctionnez avec la v%1$s';

// Menu titles
$lang['menu_upprofile']='Update Profile|Update your profile on this site';
$lang['menu_search']='Search|Search items and recipes in the database';
$lang['menu_roster_cp']='Roster CP|Roster Configuration Panel';
$lang['menu_credits']='Credits|Who made WoW Roster';

$lang['menuconf_sectionselect']='Select Section';

$lang['installer_install_0']='Installation of %1$s successful';
$lang['installer_install_1']='Installation of %1$s failed, but rollback successful';
$lang['installer_install_2']='Installation of %1$s failed, and rollback also failed';
$lang['installer_uninstall_0']='Uninstallation of %1$s successful';
$lang['installer_uninstall_1']='Uninstallation of %1$s failed, but rollback successful';
$lang['installer_uninstall_2']='Uninstallation of %1$s failed, and rollback also failed';
$lang['installer_upgrade_0']='Upgrade of %1$s successful';
$lang['installer_upgrade_1']='Upgrade of %1$s failed, but rollback successful';
$lang['installer_upgrade_2']='Upgrade of %1$s failed, and rollback also failed';

$lang['installer_icon'] = 'Icon';
$lang['installer_addoninfo'] = 'Addon Info';
$lang['installer_status'] = 'Status';
$lang['installer_installation'] = 'Installation';
$lang['installer_author'] = 'Author';
$lang['installer_log'] = 'Addon Manager Log';
$lang['installer_activated'] = 'Activated';
$lang['installer_deactivated'] = 'Deactivated';
$lang['installer_installed'] = 'Installed';
$lang['installer_upgrade_avail'] = 'Upgrade Available';
$lang['installer_not_installed'] = 'Not Installed';

$lang['installer_turn_off'] = 'Click to Deactivate';
$lang['installer_turn_on'] = 'Click to Activate';
$lang['installer_click_uninstall'] = 'Click to Uninstall';
$lang['installer_click_upgrade'] = 'Click to Upgrade %1$s to %2$s';
$lang['installer_click_install'] = 'Click to Install';
$lang['installer_overwrite'] = 'Old Version Overwrite';
$lang['installer_replace_files'] = 'You have overwrote your current addon installation with an older version<br />Replace files with latest version';

$lang['installer_error'] = 'Install Errors';
$lang['installer_invalid_type'] = 'Invalid install type';
$lang['installer_no_success_sql'] = 'Queries were not successfully added to the installer';
$lang['installer_no_class'] = 'The install definition file for %1$s did not contain a correct installation class';
$lang['installer_no_installdef'] = 'inc/install.def.php for %1$s was not found';

$lang['installer_no_empty'] = 'Cannot install with an empty addon name';
$lang['installer_fetch_failed'] = 'Failed to fetch addon data for %1$s';
$lang['installer_addon_exist'] = '%1$s already contains %2$s. You can go back and uninstall that addon first, or upgrade it, or install this addon with a different name';
$lang['installer_no_upgrade'] = '%1$s doesn\`t contain data to upgrade from';
$lang['installer_not_upgradable'] = '%1$s cannot upgrade %2$s since its basename %3$s isn\'t in the list of upgradable addons';
$lang['installer_no_uninstall'] = '%1$s doesn\'t contain an addon to uninstall';
$lang['installer_not_uninstallable'] = '%1$s contains an addon %2$s which must be uninstalled with that addons\' uninstaller';

// Password Stuff
$lang['password'] = 'Password';
$lang['changeadminpass'] = 'Change Admin Password';
$lang['changeupdatepass'] = 'Change Update Password';
$lang['changeguildpass'] = 'Change Guild Password';
$lang['old_pass'] = 'Old Password';
$lang['new_pass'] = 'New Password';
$lang['new_pass_confirm'] = 'New Password [ confirm ]';
$lang['pass_old_error'] = 'Wrong password. Please enter the correct old password';
$lang['pass_submit_error'] = 'Submit error. The old password, the new password, and the confirmed new password need to be submitted';
$lang['pass_mismatch'] = 'Passwords do not match. Please type the exact same password in both new password fields';
$lang['pass_blank'] = 'No blank passwords. Please enter a password in both fields. Blank passwords are not allowed';
$lang['pass_isold'] = 'Password not changed. The new password was the same as the old one';
$lang['pass_changed'] = 'Password changed. Your new password is [ %1$s ].<br /> Do not forget this password, it is stored encrypted only';
$lang['auth_req'] = 'Authorization Required';

// Upload Rules
$lang['upload_rules_help'] = 'The rules are divided into two blocks.<br />For each uploaded guild/char, first the top block is checked.<br />If the name@server matches one of these \'deny\' rules, it is rejected.<br />After that the second block is checked.<br />If the name@server matches one of these \'accept\' rules, it is accepted.<br />If it does not match any rule, it is rejected.';

/******************************
 * Roster Admin Strings
 ******************************/

$lang['pagebar_function'] = 'Function';
$lang['pagebar_rosterconf'] = 'Configure Main Roster';
$lang['pagebar_uploadrules'] = 'Upload Rules';
$lang['pagebar_changepass'] = 'Change Password';
$lang['pagebar_addoninst'] = 'Manage Addons';
$lang['pagebar_update'] = 'Upload Profile';
$lang['pagebar_rosterdiag'] = 'Roster Diag';
$lang['pagebar_menuconf'] = 'Menu Configuration';
$lang['pagebar_configreset'] = 'Config Reset';

$lang['pagebar_addonconf'] = 'Addon Config';

$lang['roster_config_menu'] = 'Config Menu';

// Submit/Reset confirm questions
$lang['config_submit_button'] = 'Save Settings';
$lang['config_reset_button'] = 'Reset';
$lang['confirm_config_submit'] = 'This will save the changes to the database. Are you sure?';
$lang['confirm_config_reset'] = 'This will reset the form to how it was when you loaded it. Are you sure?';

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
$lang['admin']['main_conf'] = 'Main Settings|Roster\'s main settings<br />Including roster URL, Interface Images URL, and other core options';
$lang['admin']['guild_conf'] = 'Guild Config|Set up your guild info<ul><li>Guild name</li><li>Realm name (server)</li><li>Short guild description</li><li>Server type</li><li>etc...</li></ul>';
$lang['admin']['index_conf'] = 'Index Page|Options for what shows on the Main Page';
$lang['admin']['menu_conf'] = 'Menu|Control what is displayed in the Roster Main Menu';
$lang['admin']['display_conf'] = 'Display Config|Misc display settings<br />css, javascript, motd, etc...';
$lang['admin']['realmstatus_conf'] = 'Realmstatus|Options for Realmstatus<br /><br />To turn this off, look in the Menu section';
$lang['admin']['data_links'] = 'Item/Quest Data Links|External links for item and quest data';
$lang['admin']['update_access'] = 'Update Access|Set access levels for rostercp components';

$lang['admin']['documentation'] = 'Documentation|WoWRoster Documentation via the wowroster.net wiki';

// main_conf
$lang['admin']['roster_dbver'] = "Version de la base de données Roster|La version de la base de données";
$lang['admin']['version'] = "Version du Roster|Version actuelle du Roster";
$lang['admin']['sqldebug'] = "Affichage SQL de debug|Afficher les informations de contrôles de MySQL en format HTML";
$lang['admin']['debug_mode'] = "Debuggage|Debug complet en cas d'erreur";
$lang['admin']['sql_window'] = "Affichage SQL|Affiche les requêtes SQL dans le pied de page";
$lang['admin']['minCPver'] = "Version CP Minimum|Version minimale de CharacterProfiler autorisée";
$lang['admin']['minGPver'] = "Version GP Minimum|Version minimale de GuildProfiler autorisée";
$lang['admin']['locale'] = "Langue du Roster|Le code langue principal du Roster";
$lang['admin']['default_page'] = "Default Page|Page to display if no page is specified in the url";
$lang['admin']['website_address'] = "Adresse du site Web|Utilisé pour le lien sur le logo et le lien sur le menu principal<br />Certains addon pour le roster peuvent également l'utiliser";
$lang['admin']['interface_url'] = "URL du répertoire Interface|Répertoire où les images Interface images sont situés<br />La valeur par défaut est &quot;img/&quot;<br /><br />Vous pouvez utiliser un chemin relatif ou une URL absolue";
$lang['admin']['img_suffix'] = "Extension des images Interface|Le type des images Interface";
$lang['admin']['alt_img_suffix'] = "Extension alternative des images d'interface|Le type alternatif d'images pour les images de l'interface";
$lang['admin']['img_url'] = "URL du répertoire des images du roster|Répertoire où les images du roster sont situés<br />La valeur par défaut est &quot;img/&quot;<br /><br />Vous pouvez utiliser un chemin relatif ou une URL absolue";
$lang['admin']['timezone'] = "Fuseau horaire|Affiché après les dates et heures afin de savoir à quel fuseau horaire l'heure fait référence";
$lang['admin']['localtimeoffset'] = "Décalage horaire|Le décalage horaire par rapport à l'heure UTC/GMT<br />Les heures sur le roster seront affichées avec ce décalage";
$lang['admin']['use_update_triggers'] = "Permettre le déclenchement de mise à jour d'AddOn|Le déclenchement de mise à jour d'AddOn est nécessaire pour les AddOns qui ont besoin de fonctionner lors d'une mise à jour d'un profil<br />Quelques AddOns ont besoin de ce paramètre à on pour fonctionner correctement";
$lang['admin']['check_updates'] = "Check for Updates|This allows your copy of WoWRoster (and addons that use this feature) to check if you have the newest version of the software";
$lang['admin']['seo_url'] = "Alternative urls|Use /some/page/here.html?param=value instead of /?p=some-page-here&param=value";

// guild_conf
$lang['admin']['default_name'] = "WowRoster Name|Enter a name to be displayed when not in the guild or char scope";
$lang['admin']['default_desc'] = "Description|Enter a short description to be displayed when not in the guild or char scope";
$lang['admin']['alt_type'] = "Identification des rerolls|Textes identifiant les rerolls pour le décompte dans le menu principal";
$lang['admin']['alt_location'] = "Identification des rerolls (champ)|Où faut-il rechercher l'identification des rerolls";

// menu_conf
$lang['admin']['menu_conf_left'] = "Left pane|";
$lang['admin']['menu_conf_right'] = "Right pane|";

$lang['admin']['menu_top_pane'] = "Top Pane|Controls display of the top pane of the main roster menu<br />This area holds the guild name, server, last update, etc...";
$lang['admin']['menu_top_faction'] = "Faction Icon|Controls display of the faction icon in the top pane of the main roster menu";
$lang['admin']['menu_top_locale'] = "Locale Selection|Controls display of the locale selection in the top pane of the main roster menu";

$lang['admin']['menu_left_type'] = "Display type|Decide whether to show a level overview, a class overview, the realm status, or nothing at all";
$lang['admin']['menu_left_level'] = "Minimum level|Minimum level for characters to be included in the level/class overview";
$lang['admin']['menu_left_style'] = "Display style|Show as a list, a linear bargraph, or a logarithmic bargraph";
$lang['admin']['menu_left_barcolor'] = "Bar color|The color for the bar showing the number of characters of this level group/class";
$lang['admin']['menu_left_bar2color'] = "Bar 2 color|The color for the bar showing the number of alts of this level group/class";
$lang['admin']['menu_left_textcolor'] = "Text color|The color for the level/class group labels (class graph uses class colors for labels)";
$lang['admin']['menu_left_outlinecolor'] = "Text Outline color|The outline color for the level/class group labels<br />Clear this box to turn the outline off";
$lang['admin']['menu_left_text'] = "Text Font|The font for the level/class group labels";

$lang['admin']['menu_right_type'] = "Display type|Decide whether to show a level overview, a class overview, the realm status, or nothing at all";
$lang['admin']['menu_right_level'] = "Minimum level|Minimum level for characters to be included in the level/class overview";
$lang['admin']['menu_right_style'] = "Display style|Show as a list, a linear bargraph, or a logarithmic bargraph";
$lang['admin']['menu_right_barcolor'] = "Bar color|The color for the bar showing the number of characters of this level group/class";
$lang['admin']['menu_right_bar2color'] = "Bar 2 color|The color for the bar showing the number of alts of this level group/class";
$lang['admin']['menu_right_textcolor'] = "Text color|The color for the level/class group labels (class graph uses class colors for labels)";
$lang['admin']['menu_right_outlinecolor'] = "Text Outline color|The outline color for the level/class group labels<br />Clear this box to turn the outline off";
$lang['admin']['menu_right_text'] = "Text font|The font for the level/class group labels";

$lang['admin']['menu_bottom_pane'] = "Bottom Pane|Controls display of the bottom pane of the main roster menu<br />This area holds the search box";

// display_conf
$lang['admin']['logo'] = "URL pour le logo de l'entête|L'URL complète de l'image<br />Ou en laissant \"img/\" devant le nom, celà cherchera dans le répertoire img/ du roster";
$lang['admin']['roster_bg'] = "URL pour l'image de fond|L'URL absolue de l'image pour le fond principal<br />Ou en laissant &quot;img/&quot; devant le nom, celà cherchera dans le répertoire img/ du roster";
$lang['admin']['motd_display_mode'] = "Mode d'affichage du message du jour|Comment le message du jour sera affiché<br /><br />&quot;Text&quot; - Montre le message de du jour en rouge<br />&quot;Image&quot; - Montre le message du jour sous forme d'une image (nécesite GD!)";
$lang['admin']['signaturebackground'] = "Image de fond pour img.php|Support de l'ancien générateur de signature";
$lang['admin']['processtime'] = "Temps de génération de la page|Affiche &quot;<i>This page was created in XXX seconds with XX queries executed</i>&quot; en bas de page du roster";

// data_links
$lang['admin']['questlink_1'] = "Lien de quête n°1|Lien externe sur des base de données<br />Regardez dans votre (vos) fichier(s) de localisation pour la configuration de ces liens";
$lang['admin']['questlink_2'] = "Lien de quête n°2|Lien externe sur des base de données<br />Regardez dans votre (vos) fichier(s) de localisation pour la configuration de ces liens";
$lang['admin']['questlink_3'] = "Lien de quête n°3|Lien externe sur des base de données<br />Regardez dans votre (vos) fichier(s) de localisation pour la configuration de ces liens";
$lang['admin']['profiler'] = "Lien de téléchargement du CharacterProfiler|URL de téléchargement de CharacterProfiler";
$lang['admin']['uploadapp'] = "Lien de téléchargement d'UniUploader|URL de téléchargement d'UniUploader";

// realmstatus_conf
$lang['admin']['rs_display'] = "Mode d'information|&quot;full&quot; montrera le statut et le nom du serveur, la population, and le type<br />&quot;half&quot; ne montrera que le statut";
$lang['admin']['rs_mode'] = "Mode d'affichage|Comment le statut du royaume sera affiché<br /><br />&quot;DIV Container&quot; - Le statut du royaume sera affiché dans une balise DIV avec du texte et des images<br />&quot;Image&quot; - Le statut du royaume sera affiché comme une image (NECESSITE GD !)";
$lang['admin']['rs_timer'] = "Refresh Timer|Set the timeout period for fetching new realmstatus data";
$lang['admin']['rs_left'] = "Display|";
$lang['admin']['rs_middle'] = "Type Display Settings|";
$lang['admin']['rs_right'] = "Population Display Settings|";
$lang['admin']['rs_font_server'] = "Realm Font|Font for the realm name<br />(Image mode only!)";
$lang['admin']['rs_size_server'] = "Realm Font Size|Font size for the realm name<br />(Image mode only!)";
$lang['admin']['rs_color_server'] = "Realm Color|Color of realm name";
$lang['admin']['rs_color_shadow'] = "Shadow Color|Color for text drop shadows<br />(Image mode only!)";
$lang['admin']['rs_font_type'] = "Type Font|Font for the realm type<br />(Image mode only!)";
$lang['admin']['rs_size_type'] = "Type Font Size|Font size for the realm type<br />(Image mode only!)";
$lang['admin']['rs_color_rppvp'] = "RP-PvP Color|Color for RP-PvP";
$lang['admin']['rs_color_pve'] = "Normal Color|Color for Normal";
$lang['admin']['rs_color_pvp'] = "PvP Color|Color for PvP";
$lang['admin']['rs_color_rp'] = "RP Color|Color for RP";
$lang['admin']['rs_color_unknown'] = "Unknown Color|Color for unknown";
$lang['admin']['rs_font_pop'] = "Pop Font|Font for the realm population<br />(Image mode only!)";
$lang['admin']['rs_size_pop'] = "Pop Font Size|Font size for the realm population<br />(Image mode only!)";
$lang['admin']['rs_color_low'] = "Low Color|Color for low population";
$lang['admin']['rs_color_medium'] = "Medium Color|Color for medium population";
$lang['admin']['rs_color_high'] = "High Color|Color for high population";
$lang['admin']['rs_color_max'] = "Max Color|Color for max population";
$lang['admin']['rs_color_error'] = "Offline Color|Color for realm offline";

// update_access
$lang['admin']['authenticated_user'] = "Accès à Update.php|Contrôle l'accès à update.php<br /><br />Passer ce paramètre à off désactive l'accès à TOUT LE MONDE";
$lang['admin']['delete_removed_members'] = "Delete Removed Members|Controls the action taken during a guild update to members no longer in a guild<ul><li>Set Guildless - This will flag the member guildless and their data will be retained in the Roster<br />Useful for guild name changes</li><li>Delete - This will delete the member from the Roster</li></ul>";

// Character Display Settings
$lang['admin']['per_character_display'] = 'Affichage par personnage';

//Overlib for Allow/Disallow rules
$lang['guildname'] = 'Guild name';
$lang['realmname']  = 'Realm name';
$lang['regionname']     = 'Region (i.e. US)';
$lang['charname'] = 'Character name';
