<?php
/**
 * WoWRoster.net WoWRoster
 *
 * french localisaton
 * thx to tuigii@wowroster.net. visit his page at http://www.papy-team.fr
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: frFR.php 370 2008-02-17 15:42:02Z poetter $
 * @link       http://www.wowroster.net
 * @package    ArmorySync
*/

// -[ frFR Localization ]-
// Button names
$lang['async_button1']= 'ArmorySync Character|Synchronise vos personnages avec Blizzards Armory';
$lang['async_button2']= 'ArmorySync Characters|Synchronise vos personnages avec Blizzards Armory';
$lang['async_button3']= 'ArmorySync Characters|Synchronise vos personnages avec Blizzards Armory';
$lang['async_button4']= 'ArmorySync Memberlist|Synchronise vos liste des membres avec Blizzards Armory';
$lang['async_button5']= 'ArmorySync Memberlist Guilde|Ajoute un nouvelle Guilde et synchronise le avec la liste de ces membres chez Blizzards Armory';

// Config strings
$lang['admin']['armorysync_conf']			= 'General|Configure base settings for armorysync';
$lang['admin']['armorysync_ranks']			= 'Ranks|Configure guild ranks for ArmorySync';
$lang['admin']['armorysync_images']			= 'Images|Configure image displaying for armorysync';
$lang['admin']['armorysync_access']			= 'Access Rights|Configure access rights for armorysync';
$lang['admin']['armorysync_debug']			= 'Debugging|Configure debug settings for armorysync';

$lang['admin']['armorysync_host']= 'Host|Host to syncronise with.';
$lang['admin']['armorysync_minlevel']= 'Minimum Level|Minimum level pour la shynco des personnages.';
$lang['admin']['armorysync_synchcutofftime']= "Sync. temps de coupure|Le temps en jours.<br />Toutes personnages n étant pas mis à jour durant (x) jours seront synchronisé.";
$lang['admin']['armorysync_use_ajax']	= 'Use AJAX|Wether to use AJAX for status update or not.';
$lang['admin']['armorysync_reloadwaittime']= "Temps de rechargement |Le temps en secondes.<br />Le temps avant une prochaine synchronisation sera effectuée.";
$lang['admin']['armorysync_fetch_timeout'] = "Armory chargement arrêt|Temps en secondes jusqu à ce un fichier XML sera avorté.";
$lang['admin']['armorysync_fetch_retrys'] = 'Armory Fetch Retrys|How many retrys on failed fetched.';
$lang['admin']['armorysync_fetch_method'] = 'Armory Fetch method|Per char will do a reload per Character.<br />Per page will do a reload after every page that is fetched from the armory.';
$lang['admin']['armorysync_update_incomplete'] = 'Update incomplete data|This option determines if characters with incomplete data will be updated';
$lang['admin']['armorysync_skip_start'] = "Saute page de démarrage|Saute la page de démarrage des ArmorySync chargements.";
$lang['admin']['armorysync_status_hide'] = 'Hide status windows initialy|Hide the status windows of ArmorySync on the first load.';
$lang['admin']['armorysync_protectedtitle']	= "Guild titres protégé|Les personnages avec ce titre seront protégé<br />contre l'effacement de la Guilde pendant un synchronisation de la liste des membres auprès Armory de Blizzard.<br />Ce problème peut arriver avec des personnages 'banquiers'.<br />Plusieurs valeurs ont possible, avec la séparation \",\". i.e. Banquier,Stock";

$lang['admin']['armorysync_rank_set_order']	= "Guild Rank Set Order|Defines in which order the guild titles will be set.";
$lang['admin']['armorysync_rank_0']	= "Title Guild Leader|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_1']	= "Title Rank 1|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_2']	= "Title Rank 2|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_3']	= "Title Rank 3|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_4']	= "Title Rank 4|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_5']	= "Title Rank 5|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_6']	= "Title Rank 6|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_7']	= "Title Rank 7|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_8']	= "Title Rank 8|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_9']	= "Title Rank 9|This title will be set if in WoWRoster for that guild non is defined.";

$lang['admin']['armorysync_char_update_access'] = "Pers. niveau d'accès|Qui pourrait effectuer des chargements niveau personnage";
$lang['admin']['armorysync_guild_update_access'] = "Guild niveau d'accès|Qui pourrait effectuer des chargements niveau Guilde";
$lang['admin']['armorysync_guild_memberlist_update_access'] = "Guild Memberlist niveau d'accès|Qui pourrait effectuer des chargements niveau memberlist";
$lang['admin']['armorysync_realm_update_access'] ="Realm niveau d'accès|Qui pourrait effectuer des chargements niveau realm";
$lang['admin']['armorysync_guild_add_access'] = "Guild ajouter - niveau d'accès|Qui pourrait ajouter d'autres Guilds";

$lang['admin']['armorysync_logo'] = 'ArmorySync Logo|';
$lang['admin']['armorysync_pic1'] = 'ArmorySync Image 1|';
$lang['admin']['armorysync_pic2'] = 'ArmorySync Image 2|';
$lang['admin']['armorysync_pic3'] = 'ArmorySync Image 3|';
$lang['admin']['armorysync_effects'] = 'ArmorySync Image Effects|';
$lang['admin']['armorysync_logo_show'] = 'Show Logo|';
$lang['admin']['armorysync_pic1_show'] = $lang['admin']['armorysync_pic2_show'] = $lang['admin']['armorysync_pic3_show'] = 'Show Image|';
$lang['admin']['armorysync_pic_effects'] = 'Activated|Use JavaScript effects for images.';
$lang['admin']['armorysync_logo_pos_left'] = $lang['admin']['armorysync_pic1_pos_left'] = $lang['admin']['armorysync_pic2_pos_left'] = $lang['admin']['armorysync_pic3_pos_left'] = 'Image position horizontal|';
$lang['admin']['armorysync_logo_pos_top'] = $lang['admin']['armorysync_pic1_pos_top'] = $lang['admin']['armorysync_pic2_pos_top'] = $lang['admin']['armorysync_pic3_pos_top'] = 'Image position vertikal|';
$lang['admin']['armorysync_logo_size'] = $lang['admin']['armorysync_pic1_size'] = $lang['admin']['armorysync_pic2_size'] = $lang['admin']['armorysync_pic3_size'] = 'Image size|';
$lang['admin']['armorysync_pic1_min_rows'] = $lang['admin']['armorysync_pic2_min_rows'] = $lang['admin']['armorysync_pic3_min_rows'] = 'Minimun Rows|Minimum number of rows in the status display<br />to display the image.';

$lang['admin']['armorysync_debuglevel']		= 'Debug Level|Adjust the debug level for ArmorySync.<br /><br />Quiete - No Messages<br />Base Infos - Base messages<br />Armory & Job Method Infos - All messages of Armory and Job methods<br />All Methods Info - Messages of all Methods  <b style="color:red;">(Be careful - very much data)</b>';
$lang['admin']['armorysync_debugdata']		= 'Debug Data|Raise debug output by methods arguments and returns<br /><b style="color:red;">(Be careful - much more infos on high debug level)</b>';
$lang['admin']['armorysync_javadebug']		= 'Debug Java|Enable JavaScript debugging.<br />Not implemented yet.';
$lang['admin']['armorysync_xdebug_php']		= 'XDebug Session PHP|Enable sending XDEBUG variable with POST.';
$lang['admin']['armorysync_xdebug_ajax']	= 'XDebug Session AJAX|Enable sending XDEBUG variable  with Ajax POST.';
$lang['admin']['armorysync_xdebug_idekey']	= 'XDebug Session IDEKEY|Define IDEKEY for Xdebug sessions.';
$lang['admin']['armorysync_sqldebug']		= 'SQL Debug|Enable SQL debugging for ArmorySync.<br />Not useful in combination with roster SQL debugging / duplicate data.';
$lang['admin']['armorysync_updateroster']	= "Update Roster|Enable roster updates.<br />Good for debugging<br />Not implemented yet.";

$lang['bindings']['bind_on_pickup'] = "Lié quand ramassé"; //"Binds when picked up";
$lang['bindings']['bind_on_equip'] = "Lié quand équipé"; //"Binds when equiped";
$lang['bindings']['bind'] = "Lié"; // "Soulbound";

// Addon strings [done]
$lang['RepStanding']['Exalted'] = 'Exalté';
$lang['RepStanding']['Revered'] = 'Révéré';
$lang['RepStanding']['Honored'] = 'Honoré';
$lang['RepStanding']['Friendly'] = 'Amical';
$lang['RepStanding']['Neutral'] = 'Neutre';
$lang['RepStanding']['Unfriendly'] = 'Inamical';
$lang['RepStanding']['Hostile'] = 'Hostile';
$lang['RepStanding']['Hated'] = 'Détesté';

$lang['Skills']['Class Skills'] = "Compétences de classe";
$lang['Skills']['Professions'] = "Métiers";
$lang['Skills']['Secondary Skills'] = "Compétences secondaires";
$lang['Skills']['Weapon Skills'] = "Compétences d'armes";
$lang['Skills']['Armor Proficiencies'] = "Armures utilisables";
$lang['Skills']['Languages'] = "Langues";

$lang['Classes']['Druid'] = 'Druide';
$lang['Classes']['Hunter'] = 'Chasseur';
$lang['Classes']['Mage'] = 'Mage';
$lang['Classes']['Paladin'] = 'Paladin';
$lang['Classes']['Priest'] = 'Prêtre';
$lang['Classes']['Rogue'] = 'Voleur';
$lang['Classes']['Shaman'] = 'Chaman';
$lang['Classes']['Warlock'] = 'Démoniste';
$lang['Classes']['Warrior'] = 'Guerrier';

$lang['Talenttrees']['Druid']['Balance'] = "Equilibre";
$lang['Talenttrees']['Druid']['Feral Combat'] = "Combat farouche";
$lang['Talenttrees']['Druid']['Restoration'] = "Restauration";
$lang['Talenttrees']['Hunter']['Beast Mastery'] = "Maîtrise des bêtes";
$lang['Talenttrees']['Hunter']['Marksmanship'] = "Précision";
$lang['Talenttrees']['Hunter']['Survival'] = "Survie";
$lang['Talenttrees']['Mage']['Arcane'] = "Arcanes";
$lang['Talenttrees']['Mage']['Fire'] = "Feu";
$lang['Talenttrees']['Mage']['Frost'] = "Givre";
$lang['Talenttrees']['Paladin']['Holy'] = "Sacré";
$lang['Talenttrees']['Paladin']['Protection'] = "Protection";
$lang['Talenttrees']['Paladin']['Retribution'] = "Vindicte";
$lang['Talenttrees']['Priest']['Discipline'] = "Discipline";
$lang['Talenttrees']['Priest']['Holy'] = "Sacré";
$lang['Talenttrees']['Priest']['Shadow'] = "Ombre";
$lang['Talenttrees']['Rogue']['Assassination'] = "Assassinat";
$lang['Talenttrees']['Rogue']['Combat'] = "Combat";
$lang['Talenttrees']['Rogue']['Subtlety'] = "Finesse";
$lang['Talenttrees']['Shaman']['Elemental'] = "Élémentaire";
$lang['Talenttrees']['Shaman']['Enhancement'] = "Amélioration";
$lang['Talenttrees']['Shaman']['Restoration'] = "Restauration";
$lang['Talenttrees']['Warlock']['Affliction'] = "Affliction";
$lang['Talenttrees']['Warlock']['Demonology'] = "Démonologie";
$lang['Talenttrees']['Warlock']['Destruction'] = "Destruction";
$lang['Talenttrees']['Warrior']['Arms'] = "Armes";
$lang['Talenttrees']['Warrior']['Fury'] = "Fureur";
$lang['Talenttrees']['Warrior']['Protection'] = "Protection";

$lang['misc']['Rank'] = "Rang";

$lang['guild_short'] = "Guild";
$lang['character_short'] = "Char.";
$lang['skill_short'] = "Prof";
$lang['reputation_short'] = "Repu.";
$lang['equipment_short'] = "Equip";
$lang['talents_short'] = "Talent";

$lang['started'] = "Démarré";
$lang['finished'] = "Fini";

$lang['armorySyncTitle_Char'] = "ArmorySync pour Characters";
$lang['armorySyncTitle_Guild'] = "ArmorySync pour Guilds";
$lang['armorySyncTitle_Guildmembers'] = "ArmorySync pour Guildmemberlists";
$lang['armorySyncTitle_Realm'] = "ArmorySync pour Realms";

$lang['next_to_update'] = "Prochaine mise à jour : ";
$lang['nothing_to_do'] = "Rien à faire en ce moment";

$lang['error'] = "Erreur";
$lang['error_no_character'] = "Aucun personnage référé.";
$lang['error_no_guild'] = "Aucune Guild référé.";
$lang['error_no_realm'] = "Aucun Realm référé.";
$lang['error_use_menu'] = "Utilise Menu pour la synchronisation.";

$lang['error_guild_insert'] = "Erreur creation de la guilde.";
$lang['error_uploadrule_insert'] = "Error creating upload rule.";
$lang['error_guild_notexist'] = "La guilde donnée n'existe pas dans Armory.";
$lang['error_char_insert'] = "Error creating character.";
$lang['error_char_notexist'] = "The character given does not exist in the Armory.";
$lang['error_missing_params'] = "Paramètre(s) absent(s). Essaie encore une fois.";
$lang['error_wrong_region'] = "Region invalide. Seulement EU ou US sont des regions valable.";
$lang['armorysync_guildadd'] = "Ajoute la Guild and synchronise<br />la liste des membres avec Armory.";
$lang['armorysync_charadd'] = "Adding Charakter and synchronize<br />with the Armory.";
$lang['armorysync_add_help'] = "Information";
$lang['armorysync_add_helpText'] = "<br />Orthographier <b>la guilde</b> et <b>le serveur</b> <b>exactement</b> <br />comme ils sont connues chez Blizzards Armory. <br />Region est <b>EU</b> pour l'Europe et <b>US</b> pour des guildes Américaines.<br /><br /> En premier lieu la guilde sera examinée<br /> pour assurer l'existence.<br /><br /> Après une synchronisation de la liste des membres commencera.<br /><br />";

$lang['guildleader'] = "Maître de Guild";

$lang['rage'] = "Rage";
$lang['energy'] = "Énergie";
$lang['focus'] = "Focus";

$lang['armorysync_credits'] = 'Thanks to <a target="_blank" href="http://www.papy-team.fr">tuigii</a>, <a target="_blank" href="http://xent.homeip.net">zanix</a>, <a target="_blank" href="http://www.wowroster.net/Your_Account/profile=1126.html">ds</a> and <a target="_blank" href="http://www.wowroster.net/Your_Account/profile=711.html">Subxero</a> for testing, translating and supporting.<br />Spezial thanks to <a target="_blank" href="http://www.wowroster.net/Your_Account/profile=13101.html">kristoff22</a> for the original code of ArmorySync and <a target="_blank" href="http://www.iceguild.org.uk/forum">Pugro</a> for his changes to it.';

$lang['start'] = "Démarrage";
$lang['start_message'] = "Vous êtes prêt pour démarrer <b>ArmorySync</b> pour la Guilde %s %s.<br /><br />Avec cette démarche, toutes les données de %s <br />seront remplacé(s) avec les détails de Blizzards Armory.<br />  Ceci pourrait être annulé après par le téléchargement d'un CharacterProfiler.lua <b>récent</b> de chaque membre.  <br />Voulez-vous démarrer la synchronisation ";

$lang['start_message_the_char'] = "le personnage";
$lang['start_message_this_char'] = "ce personnage";
$lang['start_message_the_guild'] = "la guilde";
$lang['start_message_this_guild'] = "toutes les personnages de cette guilde";
$lang['start_message_the_realm'] = "le realm";
$lang['start_message_this_realm'] = "toutes les personnages de ce realm";

$lang['month_to_en'] = array(
    "janvier" => "January",
    "février" => "February",
    "mars" => "March",
    "avril" => "April",
    "mai" => "May",
    "juin" => "June",
    "juillet" => "July",
    "août" => "August",
    "septembre" => "September",
    "octobre" => "October",
    "novembre" => "November",
    "décembre" => "December"
);

$lang['roster_deprecated'] = "WoWRoster obsolète";
$lang['roster_deprecated_message'] = "<br />Vous utilisez <b>WoWRoster</b><br /><br />Version: <strong style=\"color:red;\" >%s</strong><br /><br />Pour utiliser <b>ArmorySync</b> Version <strong style=\"color:yellow;\" >%s</strong><br />vous avez besoin au moins <b>WoWRoster</b><br /><br />Version <strong style=\"color:green;\" >%s</strong><br /><br />Veuillez mettre à jour <b>WoWRoster</b><br /> ";

$lang['armorysync_not_upgraded'] = "ArmorySync n'est pas été mise à jour";
$lang['armorysync_not_upgraded_message'] = "<br />Vous avez actuèllement <b>ArmorySync</b><br /><br />Version: <strong style=\"color:green;\" >%s</strong><br /><br />Maintenant on est à <b>ArmorySync</b><br /><br />Version <strong style=\"color:red;\" >%s</strong><br /><br />enregistré avec <b>WoWRoster</b>.<br /><br />Veuillez aller à la <b>WoWRoster</b> configuration<br />et mettez à jour votre <b>ArmorySync</b><br /> ";

$lang['cache_not_writable'] = "ArmorySync cache dir is not writeable";
$lang['cache_not_writable_message'] = "Your <b>ArmorySync</b> cache dir is not writeable.<br />Be sure to write enable it!";

$lang['max_execution_time_low'] = "max_execution_time is to low";
$lang['max_execution_time_low_message'] = "Your php max_execution_time of %s secs is to low.<br /><br />If you want to use Character update method with a fetch timout of %s secs and %s retrys<br />we will need at least a max_execution_time of %s secs.<br /><br />Please adjust max_execution_time or use smart update or per page update!";

$lang['gems'] = array(
	"inv_enchant_prismaticsphere" => "Sphère prismatique",
	"inv_enchant_voidsphere" => "Sphère de vide",
	"inv_jewelcrafting_crimsonspinel_02" => array(" Rose de Kailee", "Crimson Sun", "Don Julio's Heart", "Rubis de feu", "Spinelle cramoisi",),
	"inv_jewelcrafting_dawnstone_03" => "Pierre d'aube",
	"inv_jewelcrafting_empyreansapphire_02" => array("Etoile filante", "Saphir empyréen",),
	"inv_jewelcrafting_lionseye_02" => array("Sang d'ambre", "Bladestone", "Facet of Eternity", "Stone of Blades", "Oeil de lion",),
	"inv_jewelcrafting_livingruby_03" => "Rubis vivant",
	"inv_jewelcrafting_nightseye_03" => array ("Oeil de nuit", "Tanzanite", "Améthyste",),
	"inv_jewelcrafting_nobletopaz_02" => "Topaze",
	"inv_jewelcrafting_nobletopaz_03" => array("Topaze noble", "Fire Opal",),
	"inv_jewelcrafting_pyrestone_02" => "Pyrolithe",
	"inv_jewelcrafting_seasprayemerald_02" => "Seaspray Emerald",
	"inv_jewelcrafting_shadowsongamethyst_01" => "Améthyste",
	"inv_jewelcrafting_shadowsongamethyst_02" => "Améthyste chantelombre",
	"inv_jewelcrafting_starofelune_03" => "Etoile d'Elune",
	"inv_jewelcrafting_talasite_01" => "Talasite",
	"inv_jewelcrafting_talasite_03" => array("Talasite", " Chrysoprase"), //, "Spencerite"
	"inv_misc_gem_azuredraenite_02" => "Pierre de lune azur",
	"inv_misc_gem_bloodgem_02" => "Grenat sanguin",
	"inv_misc_gem_bloodstone_01" => "Rubis orné runique",
	"inv_misc_gem_bloodstone_02" => "Grenat sanguin",
	"inv_misc_gem_crystal_03" => "Ziron",
	"inv_misc_gem_deepperidot_01" => "Olivine",
	"inv_misc_gem_deepperidot_02" => "Olivine",
	"inv_misc_gem_deepperidot_03" => "Olivine",
	"inv_misc_gem_diamond_05" => "Diamant brûleciel",
	"inv_misc_gem_diamond_06" => array("Diamant tonneterre", "Unstable Diamond",),
	"inv_misc_gem_diamond_07" => array("Diamant brûleciel", "Diamant brûlétoile", "Diamant brûlevent", "Unstable Diamond",),
	"inv_misc_gem_ebondraenite_02" => "Draénite ombreuse",
	"inv_misc_gem_flamespessarite_02" => "Spessarite de flamme",
	"inv_misc_gem_goldendraenite_02" => "Draénite dorée",
	"inv_misc_gem_opal_01" => array("Citrine", "Topaze ornée",),
	"inv_misc_gem_opal_02" => "Topaze ornée",
	"inv_misc_gem_pearl_07" => array("Joyau d'amani charmé", "Perle d'ombre",),
	"inv_misc_gem_pearl_08" => " Perle jaggale",
	"inv_misc_gem_ruby_01" => array("Coeur de don Amancio", "Coeur de don Rodrigue",),
	"inv_misc_gem_ruby_02" => "Rubis orné soutenu",
	"inv_misc_gem_ruby_03" => "Tourmaline",
	"inv_misc_gem_sapphire_02" => "Saphir",
	"inv_misc_gem_topaz_01" => "Pierre d'aube ornée lisse",
	"inv_misc_gem_topaz_02" => "Pierre d'aube ornée resplendissante",
	"inv_misc_gem_topaz_03" => "Ambre",
);

$lang['faction_to_en'] = array(
	"Alliance" => "Alliance",
	"Horde" => "Horde",
);
