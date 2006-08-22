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

// frFR translation by wowodo, lesablier, Exerladan, and Ansgar

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}


//Instructions how to upload, as seen on the mainpage
$wordings['frFR']['update_link']='Cliquer ici pour les instructions de mise à jour.';
$wordings['frFR']['update_instructions']='Updating Instructions';

$wordings['frFR']['lualocation']='Cliquer parcourir (browse) et télécharger les fichiers *.lua<br />';

$wordings['frFR']['filelocation']='se trouve sous <br />*WoWDirectory*\\\\WTF\\\\Account\\\\*Accountname*\\\\SavedVariables';

$wordings['frFR']['noGuild']='Impossible de trouver la guilde dans la base de données. Mettre à jour la liste des membres.';
$wordings['frFR']['nodata']="Impossible de trouver la guilde: <b>'".$roster_conf['guild_name']."'</b> du serveur <b>'".$roster_conf['server_name']."'</b><br />Vous devez préalablement<a href=\"".$roster_conf['roster_dir']."/update.php\">charger votre guilde</a> et <a href=\"".$roster_conf['roster_dir']."/admin.php\">finaliser la configuration</a><br /><br /><a href=\"".$roster_conf['roster_dir']."/docs/\" target=\"_blank\">Les instructions d'installation sont disponibles</a>";

$wordings['frFR']['update_page']='Mise à jour du profil';
// NOT USED $wordings['frFR']['updCharInfo']='Update Character Info';
$wordings['frFR']['guild_nameNotFound']='Impossible de mettre à jour la guilde "*GUILDNAME*". Vérifier la configuration!';
$wordings['frFR']['guild_addonNotFound']='Impossible de trouver la Guilde. L\'Addon GuildProfiler est-il installé correctement?';

$wordings['frFR']['ignored']='Ignoré';
$wordings['frFR']['update_disabled']='Update.php access has been disabled';

// NOT USED $wordings['frFR']['updGuildMembers']='Update Guild Members';
$wordings['frFR']['nofileUploaded']='Votre UniUploader n\'a pas téléchargé de fichier(s), ou des fichiers erronés.';
$wordings['frFR']['roster_upd_pwLabel']='Mot de passe du Roster';
$wordings['frFR']['roster_upd_pw_help']='(Requis lors d\'une mise à jour de la Guilde)';

// Updating Instructions

$index_text_uniloader = "(You can download the program from the WoWRoster website, look for the UniUploader Installer for the latest version)";

$wordings['frFR']['update_instruct']='
<strong>Actualisation automatique recommandée:<strong>
<ul>
<li>Utiliser <a href="'.$roster_conf['uploadapp'].'" target="_blank">UniUploader</a><br />
'.$index_text_uniloader.'</li>
</ul>
<strong>Instructions pour actualiser le profil:<strong>
<ol>
<li>Download <a href="'.$roster_conf['profiler'].'" target="_blank">Character Profiler</a></li>
<li>Décompresser le fichier zip dans son propre répertoire dans le répertoire *WoW Directory*\Interface\Addons\.</li>
<li>Démarrer WoW</li>
<li>Ouvrir votre compte en banque, la fenêtre des quêtes, et la fenêtre des professions qui contient les recettes</li>
<li>Se déconnecter ou quitter WoW.<br />(Voir ci-dessus si vous disposez d\'UniUploader pour automatiser l\'envois des informations.)</li>
<li>Aller sur la page <a href="'.$roster_conf['roster_dir'].'/update.php">d\'actualisation</a></li>
<li>'.$wordings['frFR']['lualocation'].'</li>
</ol>';

$wordings['frFR']['update_instructpvp']='
<strong>Statistique PvP Optionnel:</strong>
<ol>
<li>Télécharger <a href="'.$roster_conf['pvplogger'].'" target="_blank">PvPLog</a></li>
<li>Décompresser le fichier zip dans son propre directory sous *WoW Directory*\Interface\Addons\ (PvPLog\) répertoire.</li>
<li>Duel ou PvP</li>
<li>Envoyer les informations PvPLog.lua (voir étape 7 de l\'actualisation du profil).</li>
</ol>';

$wordings['frFR']['roster_credits']='Props to <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, and <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a> for the original code used for this site.<br />
WoWRoster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="'.$roster_conf['roster_dir'].'/credits.php">Additional Credits</a>';


//Charset
$wordings['frFR']['charset']="charset=utf-8";

$timeformat['frFR'] = '%b %d %l:%i %p';  // MySQL Time format (example - Jul 23 2:19 PM) - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$phptimeformat['frFR'] = 'D jS M, g:ia'; // PHP date() Time format (example - Mon 23rd Jul, 2:19pm) - http://www.php.net/manual/en/function.date.php


/*
Instance Keys
=============
A part that is marked with 'MS' (milestone) will be designated as an overall status. So if
you have this one part it will mark all other parts lower than this one as complete.
*/
$inst_keys['frFR']['A'] = array(
		'SG' => array('Quests','SG' => 'Clé de la gorge des Vents brûlants|4826','The Horn of the Beast|','Proof of Deed|','At Last!|'),
		'Gnome' => array('Key-Only','Gnome' => 'Clé d\\\'atelier|2288'),
		'SM' => array('Key-Only','SM' => 'La Clé écarlate|4445'),
		'ZF' => array('Parts','ZF' => 'Marteau de Zul\\\'Farrak|5695','Sacred Mallet|8250'),
		'Mauro' => array('Parts', 'Mauro' => 'Sceptre de Celebras|19710','Bâtonnet de Celebras|19549','Diamant de Celebras|19545'),
		'BRDp' => array('Key-Only','BRDp' => 'Clé de la prison|15545'),
		'BRDs' => array('Parts','BRDs' => 'Clé de Shadowforge|2966','Ironfel|9673'),
		'HT' => array('Key-Only','HT' => 'Clé en croissant|35607'),
		'Scholo' => array('Quests','Scholo' => 'Clé squelette|16854','Scholomance|','Fragments de squelette|','Moisissure rime avec...|','Plume de feu forgée|','Le Scarabée d\\\'Araj|','La clé de la Scholomance|'),
		'Strath' => array('Key-Only','Strath' => 'Clé de la ville|13146'),
		'UBRS' => array('Parts','UBRS' => 'Sceau d\\\'ascension|17057','Sceau d\\\'ascension non décoré|5370','Gemme de Spirestone|5379','Gemme de Smolderthorn|16095','Gemme de Bloodaxe|21777','Sceau d\\\'ascension brut |24554||MS','Sceau d\\\'ascension forgé|19463||MS'),
		'Onyxia' => array('Quests','Onyxia' => 'Amulette Drakefeu|4829','La menace dragonkin|','Les véritables maîtres|','Maréchal Windsor|','Espoir abandonné|','Une Note chiffonnée|','Un espoir en lambeaux|','Evasion !|','Le rendez-vous à Stormwind|','La grande mascarade|','L\\\'Oeil de Dragon|','Amulette drakefeu|'),
		'MC' => array('Key-Only','MC' => 'Eternal Quintessence|22754'),
	);

$inst_keys['frFR']['H'] = array(
	    'SG' => array('Key-Only','SG' => 'Clé de la gorge des Vents brûlants|4826'),
		'Gnome' => array('Key-Only','Gnome' => 'Clé d\\\'atelier|2288'),
		'SM' => array('Key-Only','SM' => 'La Clé écarlate|4445'),
		'ZF' => array('Parts', 'ZF' => 'Marteau de Zul\\\'Farrak|5695','Sacred Mallet|8250'),
		'Mauro' => array('Parts', 'Mauro' => 'Sceptre de Celebras|19710','Bâtonnet de Celebras|19549','Diamant de Celebras|19545'),
		'BRDp' => array('Key-Only','BRDp' => 'Clé de la prison|15545'),
		'BRDs' => array('Parts', 'BRDs' => 'Clé de Shadowforge|2966','Ironfel|9673'),
		'HT' => array('Key-Only','HT' => 'Clé en croissantt|35607'),
		'Scholo' => array('Quests', 'Scholo' => 'Clé squelette|16854','Scholomance|','Skeletal Fragments|','Mold Rhymes With...|','Fire Plume Forged|','Araj\\\'s Scarab|','The Key to Scholomance|'),
		'Strath' => array('Key-Only','Strath' => 'Clé de la ville|13146'),
		'UBRS' => array('Parts', 'UBRS' => 'Sceau d\\\'ascension|17057','Sceau d\\\'ascension non décoré|5370','Gemme de Spirestone|5379','Gemme de Smolderthorn|16095','Gemme de Bloodaxe|21777', 'Unforged Seal of Ascension|24554||MS', 'Forged Seal of Ascension|19463||MS'),
		'Onyxia' => array('Quests', 'Onyxia' => 'Amulette Drakefire|4829','Warlord\\\'s Command|','Eitrigg\\\'s Wisdom|','For The Horde!|','What the Wind Carries|','The Champion of the Horde|','The Testament of Rexxar|','Oculus Illusions|','Emberstrife|','The Test of Skulls, Scryer|','The Test of Skulls, Somnus|','The Test of Skulls, Chronalis|','The Test of Skulls, Axtroz|','Ascension...|','Blood of the Black Dragon Champion|'),
		'MC' => array('Key-Only','MC' => 'Eternal Quintessence|22754'),
	);

//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$wordings['frFR']['upload']='Télécharger';
$wordings['frFR']['required']='Requis';
$wordings['frFR']['optional']='Optionnel';
$wordings['frFR']['attack']='Attaque';
$wordings['frFR']['defense']='Défense';
$wordings['frFR']['class']='Classe';
$wordings['frFR']['race']='Race';
$wordings['frFR']['level']='Niveau';
$wordings['frFR']['zone']='Dernière Zone';
$wordings['frFR']['note']='Note';
$wordings['frFR']['title']='Titre';
$wordings['frFR']['name']='Nom';
$wordings['frFR']['health']='Vie';
$wordings['frFR']['mana']='Mana';
$wordings['frFR']['gold']='Or';
$wordings['frFR']['armor']='Armure';
$wordings['frFR']['lastonline']='Dernière connexion';
$wordings['frFR']['lastupdate']='Dernière mise à jour';
$wordings['frFR']['currenthonor']='Rang d\'honneur actuel';
$wordings['frFR']['rank']='Rang';
$wordings['frFR']['sortby']='Trier par %';
$wordings['frFR']['total']='Total';
$wordings['frFR']['hearthed']='Pierre de Foyer';
$wordings['frFR']['recipes']='Recettes';
$wordings['frFR']['bags']='Sacs';
$wordings['frFR']['character']='Personnage';
$wordings['frFR']['bglog']='Journal BG';
$wordings['frFR']['pvplog']='Journal PvP';
$wordings['frFR']['duellog']='Journal Duel';
$wordings['frFR']['duelsummary']='Duel Summary';
$wordings['frFR']['money']='Argent';
$wordings['frFR']['bank']='Banque';
$wordings['frFR']['guildbank']='Banque de la Guilde';
$wordings['frFR']['guildbank_totalmoney']='Total des avoirs de la Guilde';
$wordings['frFR']['raid']='CT_Raid';
$wordings['frFR']['guildbankcontact']='Porter par (Contact)';
$wordings['frFR']['guildbankitem']='Nom de l\'objet et sa description';
$wordings['frFR']['quests']='Quêtes';
$wordings['frFR']['roster']='Roster';
$wordings['frFR']['alternate']='Alternate';
$wordings['frFR']['byclass']='Par Classe';
$wordings['frFR']['menustats']='Stats';
$wordings['frFR']['menuhonor']='Honneur';
$wordings['frFR']['keys']='Clefs';
$wordings['frFR']['team']='Trouver un groupe';
$wordings['frFR']['search']='Rechercher';
$wordings['frFR']['update']='Dernière mise à jour';
$wordings['frFR']['credit']='Crédits';
$wordings['frFR']['members']='Membres';
$wordings['frFR']['items']='Objets';
$wordings['frFR']['find']='Trouver les objets contenants';
$wordings['frFR']['upprofile']='Mise à jour du Profil';
$wordings['frFR']['backlink']='Retour au Roster';
$wordings['frFR']['gender']='Genre';
$wordings['frFR']['unusedtrainingpoints']='Points de formation non utilisés';
$wordings['frFR']['unusedtalentpoints']='Points de talent non utilisés';
$wordings['frFR']['questlog']='Journal des Quêtes';
$wordings['frFR']['recipelist']='Liste des recettes';
$wordings['frFR']['reagents']='Réactifs';
$wordings['frFR']['item']='Objet';
$wordings['frFR']['type']='Type';
$wordings['frFR']['date']='Date';
$wordings['frFR']['completedsteps'] = 'Etapes finies';
$wordings['frFR']['currentstep'] = 'Etapes actuelles';
$wordings['frFR']['uncompletedsteps'] = 'Etapes incomplètes';
$wordings['frFR']['key'] = 'Clef';
$wordings['frFR']['timeplayed'] = 'Temps joué';
$wordings['frFR']['timelevelplayed'] = 'Temps joué à ce niveau';
$wordings['frFR']['Addon'] = 'Addons:';
$wordings['frFR']['advancedstats'] = 'Statistiques avancées';
$wordings['frFR']['itembonuses'] = 'Bonus dûs à l\'équipement';
$wordings['frFR']['itembonuses2'] = 'Objets Bonus';
$wordings['frFR']['crit'] = 'Crit';
$wordings['frFR']['dodge'] = 'Esquive';
$wordings['frFR']['parry'] = 'Parade';
$wordings['frFR']['block'] = 'Bloquer';

// Memberlog
$wordings['frFR']['memberlog'] = 'Member Log';
$wordings['frFR']['removed'] = 'Removed';
$wordings['frFR']['added'] = 'Added';
$wordings['frFR']['no_memberlog'] = 'No Member Log Recorded';

$wordings['frFR']['rosterdiag'] = 'Diagnostic Roster';
$wordings['frFR']['Guild_Info'] = 'Info Guilde';
$wordings['frFR']['difficulty'] = 'Difficultée';
$wordings['frFR']['recipe_4'] = 'optimal';
$wordings['frFR']['recipe_3'] = 'moyen';
$wordings['frFR']['recipe_2'] = 'facile';
$wordings['frFR']['recipe_1'] = 'insignifiant';
$wordings['frFR']['roster_config'] = 'Configuration Roster';

// Spellbook
$wordings['frFR']['spellbook'] = 'Livre de sorts';
$wordings['frFR']['page'] = 'Page';
$wordings['frFR']['general'] = 'Général';
$wordings['frFR']['prev'] = 'Avant';
$wordings['frFR']['next'] = 'Après';

// Mailbox
$wordings['frFR']['mailbox'] = 'Boîte aux lettres';
$wordings['frFR']['maildateutc'] = 'Messages Capturés';
$wordings['frFR']['mail_item'] = 'Objet';
$wordings['frFR']['mail_sender'] = 'Expéditeur';
$wordings['frFR']['mail_subject'] = 'Sujet';
$wordings['frFR']['mail_expires'] = 'Messages expirés';
$wordings['frFR']['mail_money'] = 'Argent Inclus';


//this needs to be exact as it is the wording in the db
$wordings['frFR']['professions']='Métiers';
$wordings['frFR']['secondary']='Compétences secondaires';
$wordings['frFR']['Blacksmithing']='Forge';
$wordings['frFR']['Mining']='Minage';
$wordings['frFR']['Herbalism']='Herboristerie';
$wordings['frFR']['Alchemy']='Alchimie';
$wordings['frFR']['Leatherworking']='Travail du cuir';
$wordings['frFR']['Skinning']='Dépeçage';
$wordings['frFR']['Tailoring']='Couture';
$wordings['frFR']['Enchanting']='Enchantement';
$wordings['frFR']['Engineering']='Ingénierie';
$wordings['frFR']['Cooking']='Cuisine';
$wordings['frFR']['Fishing']='Pêche';
$wordings['frFR']['First Aid']='Secourisme';
$wordings['frFR']['backpack']='Backpack';
$wordings['frFR']['poisons']='Poisons';
$wordings['frFR']['PvPRankNone']='none';

//Tradeskill-Array
$tsArray['frFR'] = array (
		$wordings['frFR']['Alchemy'],
		$wordings['frFR']['Herbalism'],
		$wordings['frFR']['Blacksmithing'],
		$wordings['frFR']['Mining'],
		$wordings['frFR']['Leatherworking'],
		$wordings['frFR']['Skinning'],
		$wordings['frFR']['Tailoring'],
		$wordings['frFR']['Enchanting'],
		$wordings['frFR']['Engineering'],
		$wordings['frFR']['Cooking'],
		$wordings['frFR']['Fishing'],
		$wordings['frFR']['First Aid']
);

//Tradeskill Icons-Array
$wordings['frFR']['ts_iconArray'] = array (
		'Alchimie'=>'Trade_Alchemy',
		'Herboristerie'=>'Trade_Herbalism',
		'Forge'=>'Trade_BlackSmithing',
		'Minage'=>'Trade_Mining',
		'Travail du cuir'=>'Trade_Leatherworking',
		'Dépeçage'=>'INV_Misc_Pelt_Wolf_01',
		'Couture'=>'Trade_Tailoring',
		'Enchantement'=>'Trade_Engraving',
		'Ingénierie'=>'Trade_Engineering',
		'Cuisine'=>'INV_Misc_Food_15',
		'Pêche'=>'Trade_Fishing',
		'Secourisme'=>'Spell_Holy_SealOfSacrifice',
		'Monte de tigre'=>'Ability_Mount_WhiteTiger',
		'Equitation'=>'Ability_Mount_RidingHorse',
		'Monte de bélier'=>'Ability_Mount_MountainRam',
		'Pilotage de mécanotrotteur'=>'Ability_Mount_MechaStrider',
		'Undead Horsemanship'=>'Ability_Mount_Undeadhorse',
		'Raptor Riding'=>'Ability_Mount_Raptor',
		'Kodo Riding'=>'Ability_Mount_Kodo_03',
		'Wolf Riding'=>'Ability_Mount_BlackDireWolf',
);

// Class Icons-Array
$wordings['frFR']['class_iconArray'] = array (
		'Druide'=>'Ability_Druid_Maul',
		'Chasseur'=>'INV_Weapon_Bow_08',
		'Mage'=>'INV_Staff_13',
		'Paladin'=>'Spell_Fire_FlameTounge',
		'Prêtre'=>'Spell_Holy_LayOnHands',
		'Voleur'=>'INV_ThrowingKnife_04',
		'Chaman'=>'Spell_Nature_BloodLust',
		'Démoniste'=>'Spell_Shadow_Cripple',
		'Guerrier'=>'INV_Sword_25',
);

//skills
$skilltypes['frFR'] = array(
		1 => 'Compétences de Classe',
		2 => 'Métiers',
		3 => 'Compétences secondaires',
		4 => 'Compétences d’armes',
		5 => 'Armures portables',
		6 => 'Langues'
);

//tabs
$wordings['frFR']['tab1']='Stats';
$wordings['frFR']['tab2']='Pet';
$wordings['frFR']['tab3']='Rep';
$wordings['frFR']['tab4']='Comp';
$wordings['frFR']['tab5']='Talents';
$wordings['frFR']['tab6']='Honneur';

$wordings['frFR']['strength']='Force';
$wordings['frFR']['strength_tooltip']='Augmente la puissance d\'attaque avec arme de mêlée.<br />Augmente le nombre de points de dégâts bloqués par le bouclier.';
$wordings['frFR']['agility']='Agilité';
$wordings['frFR']['agility_tooltip']= 'Augmente votre puissance d\'attaque avec arme de jet.<br />Améliore vos change de réaliser une attaque critique avec toutes les armes.<br />Augmente votre armure et votre change d\'esquiver les attaques.';
$wordings['frFR']['stamina']='Endurance';
$wordings['frFR']['stamina_tooltip']= 'Augmente vos points de vie.';
$wordings['frFR']['intellect']='Intelligence';
$wordings['frFR']['intellect_tooltip']= 'Augmente vos points de mana et vos chances de réaliser une attaque critique aux moyens de sorts.<br />Augmente la vitesse d\'apprentissage des compétences en arme.';
$wordings['frFR']['spirit']='Esprit';
$wordings['frFR']['spirit_tooltip']= 'Augmente la vitesse de régénération de vos points de vie et de mana.';
$wordings['frFR']['armor_tooltip']= 'Diminue les dégâts resultant d\'attaque physique.<br />L\'importance de la diminution dépend du niveau de l\'attaquant.';

$wordings['frFR']['melee_att']='Att. de mêlée';
$wordings['frFR']['melee_att_power']='Puissance d\'attaque en mêlée';
$wordings['frFR']['range_att']='Att. à distance';
$wordings['frFR']['range_att_power']='Puissance d\'attaque à distance';
$wordings['frFR']['power']='Puissance';
$wordings['frFR']['damage']='Dégâts';
$wordings['frFR']['energy']='Energie';
$wordings['frFR']['rage']='Rage';

$wordings['frFR']['melee_rating']='Rang de l\'Attaque en Mêlée';
$wordings['frFR']['melee_rating_tooltip']='Votre rang d\'attaque influence vos change de toucher une cible<br />Et est basé sur votre habilité à utiliser l\'arme que vous portez..';
$wordings['frFR']['range_rating']='Rang de l\'Attaque à Distance';
$wordings['frFR']['range_rating_tooltip']='Votre rang d\'attaque influence vos change de toucher une cible<br />Et est basé sur votre habilité à utiliser l\'arme que vous manipulez..';

$wordings['frFR']['res_fire']='Résistance au feu';
$wordings['frFR']['res_fire_tooltip']='Augmente votre résistance aux dégâts de feu.<br />Plus haut est le nombre, meilleure est la résistance.';
$wordings['frFR']['res_nature']='Résistance à la nature';
$wordings['frFR']['res_nature_tooltip']='Augmente votre résistance aux dégâts de la nature.<br />Plus haut est le nombre, meilleure est la résistance.';
$wordings['frFR']['res_arcane']='Résistance des Arcanes';
$wordings['frFR']['res_arcane_tooltip']='Augmente votre résistance aux dégâts des Arcanes.<br />Plus haut est le nombre, meilleure est la résistance.';
$wordings['frFR']['res_frost']='Résistance au froid';
$wordings['frFR']['res_frost_tooltip']='Augmente votre résistance aux dégâts de froid.<br />Plus haut est le nombre, meilleure est la résistance.';
$wordings['frFR']['res_shadow']='Résistance à l\'ombre';
$wordings['frFR']['res_shadow_tooltip']='Augmente votre résistance aux dégâts d\'ombre.<br />Plus haut est le nombre, meilleure est la résistance.';

$wordings['frFR']['pointsspent']='Points Utilisés:';
$wordings['frFR']['none']='Rien';

$wordings['frFR']['pvplist']=' Stats JcJ/PvP';
$wordings['frFR']['pvplist1']='Guilde qui a le plus souffert de nos actions';
$wordings['frFR']['pvplist2']='Guilde qui nous a le plus fait souffrir';
$wordings['frFR']['pvplist3']='Joueur qui a le plus souffert de nos actions';
$wordings['frFR']['pvplist4']='Joueur qui nous a le plus tué';
$wordings['frFR']['pvplist5']='Membre de la guilde tuant le plus';
$wordings['frFR']['pvplist6']='Membre de la guilde tué le plus';
$wordings['frFR']['pvplist7']='Membre ayant la meilleure moyenne de mort';
$wordings['frFR']['pvplist8']='Membre ayant la meilleure moyenne de défaîte';

$wordings['frFR']['hslist']=' Stats du Système d\'Honneur';
$wordings['frFR']['hslist1']='Membre le mieux classé cette semaine';
$wordings['frFR']['hslist2']='Membre ayant la meilleur constance';
$wordings['frFR']['hslist3']='Membre ayant le plus de VH la semaine dernière';
$wordings['frFR']['hslist4']='Membre ayant le plus de VD la semaine dernière';
$wordings['frFR']['hslist5']='Membre ayant obtenu le plus d\'expérience la semaine dernière';
$wordings['frFR']['hslist6']='Membre le mieux classé';
$wordings['frFR']['hslist7']='Membre ayant le plus de VH';
$wordings['frFR']['hslist8']='Membre ayant le plus de VD';
$wordings['frFR']['hslist9']='Membre ayant le meilleur rapport VH/Exp la semaine dernière';

$wordings['frFR']['Druid']='Druide';
$wordings['frFR']['Hunter']='Chasseur';
$wordings['frFR']['Mage']='Mage';
$wordings['frFR']['Paladin']='Paladin';
$wordings['frFR']['Priest']='Prêtre';
$wordings['frFR']['Rogue']='Voleur';
$wordings['frFR']['Shaman']='Chaman';
$wordings['frFR']['Warlock']='Démoniste';
$wordings['frFR']['Warrior']='Guerrier';

$wordings['frFR']['today']='Aujourd\'hui';
$wordings['frFR']['yesterday']='Hier';
$wordings['frFR']['thisweek']='Cette semaine';
$wordings['frFR']['lastweek']='Semaine passée';
$wordings['frFR']['alltime']='Lifetime';
$wordings['frFR']['honorkills']='Vict. Honorables';
$wordings['frFR']['dishonorkills']='Vict. Déshonorantes';
$wordings['frFR']['honor']='Honneur';
$wordings['frFR']['standing']='Position';
$wordings['frFR']['highestrank']='Plus haut niveau';

$wordings['frFR']['totalwins']='Nombre de victoires:';
$wordings['frFR']['totallosses']='Nombre de défaites:';
$wordings['frFR']['totaloverall']='Total général:';
$wordings['frFR']['win_average']='Average Level Diff (Wins):';
$wordings['frFR']['loss_average']='Average Level Diff (Losses):';

// These need to be EXACTLY what PvPLog stores them as
$wordings['frFR']['alterac_valley']="Vallée d'Alterac";
$wordings['frFR']['arathi_basin']="Bassin d'Arathi";
$wordings['frFR']['warsong_gulch']='Goulet des Warsong';

$wordings['frFR']['world_pvp']='JcJ Mondial';
$wordings['frFR']['versus_guilds']='Contre Guilde';
$wordings['frFR']['versus_players']='Contre Joueurs';
$wordings['frFR']['bestsub']='Best Subzone';
$wordings['frFR']['worstsub']='Worst Subzone';
$wordings['frFR']['killedmost']='Killed Most';
$wordings['frFR']['killedmostby']='Killed Most By';
$wordings['frFR']['gkilledmost']='Guild Killed Most';
$wordings['frFR']['gkilledmostby']='Guild Killed Most By';

$wordings['frFR']['wins']='Wins';
$wordings['frFR']['losses']='Losses';
$wordings['frFR']['overall']='Overall';
$wordings['frFR']['best_zone']='Best Zone';
$wordings['frFR']['worst_zone']='Worst Zone';
$wordings['frFR']['most_killed']='Most Killed';
$wordings['frFR']['most_killed_by']='Most Killed By';

$wordings['frFR']['when']='Quand';
$wordings['frFR']['rank']='Rang';
$wordings['frFR']['guild']='Guilde';
$wordings['frFR']['leveldiff']='Différence de Niveau';
$wordings['frFR']['result']='Résultat';
$wordings['frFR']['zone2']='Zone';
$wordings['frFR']['subzone']='Subzone';
$wordings['frFR']['bg']='Champ de Bataille';
$wordings['frFR']['yes']='Oui';
$wordings['frFR']['no']='Non';
$wordings['frFR']['win']='Victoire';
$wordings['frFR']['loss']='Défaite';
$wordings['frFR']['kills']='Tués';
$wordings['frFR']['unknown']='Inconnu';

//strings for Rep-tab
$wordings['frFR']['exalted']='Exalté';
$wordings['frFR']['revered']='Révéré';
$wordings['frFR']['honored']='Honoré';
$wordings['frFR']['friendly']='Amical';
$wordings['frFR']['neutral']='Neutre';
$wordings['frFR']['unfriendly']='Inamical';
$wordings['frFR']['hostile']='Hostile';
$wordings['frFR']['hated']='Détesté';
$wordings['frFR']['atwar']='En guerre';
$wordings['frFR']['notatwar']='Pas en guerre';

// language definitions for the rogue instance keys 'fix'
$wordings['frFR']['thievestools']='Outils\\\' de Voleur';
$wordings['frFR']['lockpicking']='Crochetage';
// END

	// Quests page external links (on character quests page)
		// questlink_n_name=?		This is the name displayed on the quests page
		// questlink_n_url=?		This is the URL used for the quest lookup

		$questlinks[0]['frFR']['name']='Judgehype FR';
		$questlinks[0]['frFR']['url1']='http://worldofwarcraft.judgehype.com/index.php?page=squete&Ckey=';
		$questlinks[0]['frFR']['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$questlinks[0]['frFR']['url3']='&amp;maxl=';

		$questlinks[1]['frFR']['name']='WoWDBU FR';
		$questlinks[1]['frFR']['url1']='http://wowdbu.com/7.html?m=2&mode=qsearch&title=';
		$questlinks[1]['frFR']['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$questlinks[1]['frFR']['url3']='&amp;maxl=';

		$questlinks[2]['frFR']['name']='Allakhazam US';
		$questlinks[2]['frFR']['url1']='http://wow.allakhazam.com/db/qlookup.html?name=';
		$questlinks[2]['frFR']['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$questlinks[2]['frFR']['url3']='&amp;maxl=';

// Items external link
	$itemlink['frFR']='http://wowdbu.com/2-1.html?way=asc&order=name&showstats=&type_limit=0&lvlmin=&lvlmax=&name=';
	//$itemlink['frFR']='http://wow.allakhazam.com/search.html?q=';

// definitions for the questsearchpage
	$wordings['frFR']['search1']="Choisir la zone ou la quête dans la liste ci-dessous pour visualiser les joueurs concernés.<br />\n<small>Attention si les niveaux de quêtes ne sont pas les mêmes, il se peut qu'il s'agisse d'une autre partie d'une quête multiple.</small>";
	$wordings['frFR']['search2']='Recherche par Zone';
	$wordings['frFR']['search3']='Recherche par nom de quête';

// Definition for item tooltip coloring
	$wordings['frFR']['tooltip_use']='Utiliser';
	$wordings['frFR']['tooltip_requires']='Niveau';
	$wordings['frFR']['tooltip_reinforced']='Reinforced';
	$wordings['frFR']['tooltip_soulbound']='Lié';
	$wordings['frFR']['tooltip_equip']='Equipé';
	$wordings['frFR']['tooltip_equip_restores']='Equipé : Rend';
	$wordings['frFR']['tooltip_equip_when']='Equipé : Lorsque';
	$wordings['frFR']['tooltip_chance']='Chance';
	$wordings['frFR']['tooltip_enchant']='Enchantement';
	$wordings['frFR']['tooltip_set']='Set';
	$wordings['frFR']['tooltip_rank']='Rang';
	$wordings['frFR']['tooltip_next_rank']='Prochain rang';
	$wordings['frFR']['tooltip_spell_damage']='Spell Damage';
	$wordings['frFR']['tooltip_school_damage']='\\+.*Spell Damage';
	$wordings['frFR']['tooltip_healing_power']='Healing Power';
	$wordings['frFR']['tooltip_chance_hit']='Chances quand touché :';
	$wordings['frFR']['tooltip_reinforced_armor']='Reinforced Armor';
    $wordings['frFR']['tooltip_damage_reduction']='Damage Reduction';

// Warlock pet names for icon displaying
	$wordings['frFR']['Imp']='Diablotin';
	$wordings['frFR']['Voidwalker']='Marcheur du Vide';
	$wordings['frFR']['Succubus']='Succube';
	$wordings['frFR']['Felhunter']='Chasseur corrompu';
	$wordings['frFR']['Infernal']='Infernal';

// Max experiance for exp bar on char page
	$wordings['frFR']['max_exp']='Max XP';

// Error messages
	$wordings['frFR']['CPver_err']="The version of CharacterProfiler used to capture data for this character is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v".$roster_conf['minCPver']." and have logged onto this character and saved data using this version.";
	$wordings['frFR']['PvPLogver_err']="The version of PvPLog used to capture data for this character is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v$".$roster_conf['minPvPLogver'].", and if you have just updated your PvPLog, ensure you deleted your old PvPLog.lua Saved Variables file prior to updating.";
	$wordings['frFR']['GPver_err']="The version of GuildProfiler used to capture data for this guild is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v".$roster_conf['minGPver'];






/******************************
 * Roster Admin Strings
 ******************************/

// Submit/Reset confirm questions
$wordings['frFR']['confirm_config_submit'] = 'This will save the changes to the database. Are you sure?';
$wordings['frFR']['confirm_config_reset'] = 'This will reset the form to how it was when you loaded it. Are you sure?';

// Main Menu words
$wordings['frFR']['admin']['main_conf'] = 'Options principales';
$wordings['frFR']['admin']['guild_conf'] = 'Options Guilde';
$wordings['frFR']['admin']['index_conf'] = 'Option Affichage';
$wordings['frFR']['admin']['menu_conf'] = 'Option Menu';
$wordings['frFR']['admin']['display_conf'] = 'Option Style';
$wordings['frFR']['admin']['char_conf'] = 'Options Joueurs';
$wordings['frFR']['admin']['realmstatus_conf'] = 'Option Royaume';
$wordings['frFR']['admin']['guildbank_conf'] = 'Option Banque';
$wordings['frFR']['admin']['data_links'] = 'Item/Quest Data Links';
$wordings['frFR']['admin']['update_access'] = 'update.php Access';


// All strings here
// Each variable must be the same name as the config variable name
// Example:
//   Assign description text an tooltip for $roster_conf['sqldebug']
//   $wordings['locale']['admin']['sqldebug'] = "Desc|Tooltip";

// Each string is separated by a pipe ( | )
// The first part is the short description, the next part is the tooltip
// Use <br /> to make new lines!
// Example:
//   "Controls Flux-Capacitor|Turning this on may cause serious temporal distortions<br />Use with care"


// main_conf
$wordings['frFR']['admin']['roster_upd_pw'] = "Mot de passe du Roster|Il s'agit du mot de passe permettant la mise à jour de la liste des membres de la Guilde.<br />Certains addons peuvent aussi utilisé ce mot de passe.";
$wordings['frFR']['admin']['roster_dbver'] = "Version de la base de données Roster|La version de la base de données";
$wordings['frFR']['admin']['version'] = "Version du Roster|Version actuelle du Roster";
$wordings['frFR']['admin']['sqldebug'] = "SQL Debug Output|Afficher les informations de contrôles de MySQL en format HTML";
$wordings['frFR']['admin']['minCPver'] = "Version CP Minimum|Version minimale de CharacterProfiler autorisée";
$wordings['frFR']['admin']['minGPver'] = "Version GP Minimum|Version minimale de GuildProfiler autorisée";
$wordings['frFR']['admin']['minPvPLogver'] = "Version PvPLog Minimum|Version minimale de PvPLog autorisée";
$wordings['frFR']['admin']['roster_lang'] = "Langue du Roster|Le code langue principal du Roster";
$wordings['frFR']['admin']['website_address'] = "Adresse du site Web|Used for url link for logo, and guildname link in the main menu<br />Some roster addons may also use this";
$wordings['frFR']['admin']['roster_dir'] = "URL du Roster|The URL path to the Roster directory<br />It is critical that this is correct or errors may occur<br />(EX: http://www.site.com/roster )<br /><br />A full url is not required but a foreward slash before the directory is<br />(EX: /roster )";
$wordings['frFR']['admin']['server_name_comp'] = "char.php Compatibility Mode|If your character page does not work, try changing this";
$wordings['frFR']['admin']['interface_url'] = "Interface Directory URL|Directory that the Interface images are located<br />Default is &quot;img/&quot;<br /><br />You can use a relative path or a full URL";
$wordings['frFR']['admin']['img_suffix'] = "Interface Image Extension|The image type of the Interface images";
$wordings['frFR']['admin']['alt_img_suffix'] = "Alt Interface Image Extension|The alternate possible image type of the Interface images";
$wordings['frFR']['admin']['img_url'] = "Roster Images Directory URL|Directory that Roster's images are located<br />Default is &quot;img/&quot;<br /><br />You can use a relative path or a full URL";
$wordings['frFR']['admin']['timezone'] = "Timezone|Displayed after timestamps so people know what timezone the time references are in";
$wordings['frFR']['admin']['localtimeoffset'] = "Time Offest|The timezone offset from UTC/GMT<br />Times on roster will be displayed as a calculated value using this offset";
$wordings['frFR']['admin']['pvp_log_allow'] = "Allow upload of PvPLog Data|Changing this to &quot;no&quot; will disable the PvPLog upload field in &quot;update.php&quot;";
$wordings['frFR']['admin']['use_update_triggers'] = "Addon Update Triggers|Addon Update Triggers are for addons that need to run during a character or guild update<br />Some addons my require that this is turned on for them to function properly";

// guild_conf
$wordings['frFR']['admin']['guild_name'] = "Nom de la Guilde|Ce nom doit être orthographié exactement comme dans le jeu<br />ou vous <u>NE POURREZ PAS</u> charger les profils";
$wordings['frFR']['admin']['server_name'] = "Nom du Serveur|Ce nom doit être orthographié exactement comme dans le jeu<br />ou vous <u>NE POURREZ PAS</u> charger les profils";
$wordings['frFR']['admin']['guild_desc'] = "Description de la Guilde|Donner une description courte de la Guilde";
$wordings['frFR']['admin']['server_type'] = "Type de Serveur|Type de serveurs dans WoW";
$wordings['frFR']['admin']['alt_type'] = "Identification des rerolls|Textes identifiant les rerolls pour le décompte dans le menu principal";
$wordings['frFR']['admin']['alt_location'] = "Identification des rerolls (champ)|Où faut-il rechercher l'identification des rerolls";

// index_conf
$wordings['frFR']['admin']['index_pvplist'] = "Statistiques PvP|PvP-Logger stats on the index page<br />If you have disabled PvPlog uploading, there is no need to have this on";
$wordings['frFR']['admin']['index_hslist'] = "Statistiques Honneur|Honor System stats on the index page";
$wordings['frFR']['admin']['hspvp_list_disp'] = "PvP/Honor List Display|Controls how the PvP and Honor Lists display on page load<br />The lists can be collapsed and opened by clicking on the header<br /><br />&quot;show&quot; will fully display the lists when the page loads<br />&quot;hide&quot; will show the lists collapsed";
$wordings['frFR']['admin']['index_member_tooltip'] = "Member Info Tooltip|Displays some info about a character in a tooltip";
$wordings['frFR']['admin']['index_update_inst'] = "Update Instructions|Controls the display of the Update Instructions on the page";
$wordings['frFR']['admin']['index_sort'] = "Member List Sort|Controls the default sorting";
$wordings['frFR']['admin']['index_motd'] = "Guild MOTD|Show Guild Message of the Day on the top of the page<br /><br />This also controls the display on the &quot;Guild Info&quot; page as well";
$wordings['frFR']['admin']['index_level_bar'] = "Level Bar|Toggles the display of a visual level percentage bar on the main page";
$wordings['frFR']['admin']['index_iconsize'] = "Icon Size|Select the size of the icons on the main pages (PvP, tradeskills, class, etc..)";
$wordings['frFR']['admin']['index_tradeskill_icon'] = "Tradeskill Icons|Enables tradeskill icons on the main pages";
$wordings['frFR']['admin']['index_tradeskill_loc'] = "Tradeskill Column Display|Select what column to place tradeskill icons";
$wordings['frFR']['admin']['index_class_color'] = "Class Colorizing|Colorize the class names";
$wordings['frFR']['admin']['index_classicon'] = "Class Icons|Displays an icon for each class, for each character";
$wordings['frFR']['admin']['index_honoricon'] = "PvP Honor Icons|Displays a PvP rank icon next to the rank name";
$wordings['frFR']['admin']['index_prof'] = "Professions Column|This is a specific coulmn for the tradeskill icons<br />If you move them to another column, you might want to turn this off";
$wordings['frFR']['admin']['index_currenthonor'] = "Honor Column|Toggles the display of the honor column";
$wordings['frFR']['admin']['index_note'] = "Note Column|Toggles the display of the public note column";
$wordings['frFR']['admin']['index_title'] = "Guild Title Column|Toggles the display of the guild title column";
$wordings['frFR']['admin']['index_hearthed'] = "Hearthstone Loc. Column|Toggles the display of the hearthstone location column";
$wordings['frFR']['admin']['index_zone'] = "Last Zone Loc. Column|Toggles the display of the last zone column";
$wordings['frFR']['admin']['index_lastonline'] = "Last Seen Online Column|Toggles the display of the last seen online column";
$wordings['frFR']['admin']['index_lastupdate'] = "Last Updated Column|Display when the character last updated their info";

// menu_conf
$wordings['frFR']['admin']['menu_left_pane'] = "Left Pane (Member Quick List)|Controls display of the left pane of the main roster menu<br />This area holds the member quick list";
$wordings['frFR']['admin']['menu_right_pane'] = "Right Pane (Realmstatus)|Controls display of the right pane of the main roster menu<br />This area holds the realmstatus image";
$wordings['frFR']['admin']['menu_memberlog'] = "Member Log Link|Controls display of the Member Log Link";
$wordings['frFR']['admin']['menu_alt_page'] = "Alternate View Link|Controls display of the Alternate View Link";
$wordings['frFR']['admin']['menu_guild_info'] = "Guild-Info Link|Controls display of the Guild-Info Link";
$wordings['frFR']['admin']['menu_stats_page'] = "Basic Stats Link|Controls display of the Basic Stats Link";
$wordings['frFR']['admin']['menu_pvp_page'] = "PvPLog Stats Link|Controls display of the PvPLog Stats Link";
$wordings['frFR']['admin']['menu_honor_page'] = "Honor Page Link|Controls display of the Honor Page Link";
$wordings['frFR']['admin']['menu_guildbank'] = "Guildbank Link|Controls display of the Guildbank Link";
$wordings['frFR']['admin']['menu_keys_page'] = "Instance Keys Link|Controls display of the Instance Keys Link";
$wordings['frFR']['admin']['menu_tradeskills_page'] = "Professions Link|Controls display of the Professions Link";
$wordings['frFR']['admin']['menu_update_page'] = "Profile Update Link|Controls display of the Profile Update Link";
$wordings['frFR']['admin']['menu_quests_page'] = "Find Team/Quests Link|Controls display of the Find Team/Quests Link";
$wordings['frFR']['admin']['menu_search_page'] = "Search Page Link|Controls display of the Search Page Link";

// display_conf
$wordings['frFR']['admin']['stylesheet'] = "CSS Stylesheet|CSS stylesheet for roster";
$wordings['frFR']['admin']['roster_js'] = "Roster JS File|Main Roster JavaScript file location";
$wordings['frFR']['admin']['overlib'] = "Tooltip JS File|Tooltip JavaScript file location";
$wordings['frFR']['admin']['overlib_hide'] = "Overlib JS Fix|JavaScript file location of fix for Overlib in Internet Explorer";
$wordings['frFR']['admin']['logo'] = "URL for header logo|The full URL to the image<br />Or by apending &quot;img/&quot; to the name, it will look in the roster's img/ directory";
$wordings['frFR']['admin']['roster_bg'] = "URL for background image|The full URL to the image used for the main background<br />Or by apending &quot;img/&quot; to the name, it will look in the roster's img/ directory";
$wordings['frFR']['admin']['motd_display_mode'] = "MOTD Display Mode|How the MOTD will be displayed<br /><br />&quot;Text&quot; - Shows MOTD in red text<br />&quot;Image&quot; - Shows MOTD as an image (REQUIRES GD!)";
$wordings['frFR']['admin']['signaturebackground'] = "img.php Background|Support for legacy signature-creator";
$wordings['frFR']['admin']['processtime'] = "Page Gen. Time/DB Queries|Display &quot;This page was created in XXX seconds with XX queries executed&quot; in the footer of roster";

// data_links
$wordings['frFR']['admin']['questlink_1'] = "Quest Link #1|Item external links<br />Look in your localization-file(s) for link configuration";
$wordings['frFR']['admin']['questlink_2'] = "Quest Link #2|Item external links<br />Look in your localization-file(s) for link configuration";
$wordings['frFR']['admin']['questlink_3'] = "Quest Link #3|Item external links<br />Look in your localization-file(s) for link configuration";
$wordings['frFR']['admin']['profiler'] = "CharacterProfiler download link|URL to download CharacterProfiler";
$wordings['frFR']['admin']['pvplogger'] = "PvPLog download link|URL to download PvPLog";
$wordings['frFR']['admin']['uploadapp'] = "UniUploader download link|URL to download UniUploader";

// char_conf
$wordings['frFR']['admin']['char_bodyalign'] = "Character Page Alignment|Alignment of the data on the character page";
$wordings['frFR']['admin']['char_header_logo'] = "Header Logo|Show the roster header logo on character page";
$wordings['frFR']['admin']['show_talents'] = "Talents|Controls the display of Talents<br /><br />Setting is global and overrides per-user setting";
$wordings['frFR']['admin']['show_spellbook'] = "Spellbook|Controls the display of the Spellbook<br /><br />Setting is global and overrides per-user setting";
$wordings['frFR']['admin']['show_mail'] = "Mail|Controls the display of Mail<br /><br />Setting is global and overrides per-user setting";
$wordings['frFR']['admin']['show_inventory'] = "Bags|Controls the display of Bags<br /><br />Setting is global and overrides per-user setting";
$wordings['frFR']['admin']['show_money'] = "Money|Controls the display of Money<br /><br />Setting is global and overrides per-user setting";
$wordings['frFR']['admin']['show_bank'] = "Bank|Controls the display of Bank contents<br /><br />Setting is global and overrides per-user setting";
$wordings['frFR']['admin']['show_recipes'] = "Recipes|Controls the display of Recipes<br /><br />Setting is global and overrides per-user setting";
$wordings['frFR']['admin']['show_quests'] = "Quests|Controls the display of Quests<br /><br />Setting is global and overrides per-user setting";
$wordings['frFR']['admin']['show_bg'] = "Battleground PvPLog Data|Controls the display of Battleground PvPLog data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$wordings['frFR']['admin']['show_pvp'] = "PvPLog Data|Controls the display of PvPLog Data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$wordings['frFR']['admin']['show_duels'] = "Duel PvPLog Data|Controls the display of Duel PvPLog Data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$wordings['frFR']['admin']['show_item_bonuses'] = "Item Bonuses|Controls the display of Item Bonuses<br /><br />Setting is global and overrides per-user setting";
$wordings['frFR']['admin']['show_signature'] = "Display Signature|Controls the display of a Signature image<br /><span class=\"red\">Requires SigGen Roster Addon</span><br /><br />Setting is global";
$wordings['frFR']['admin']['show_avatar'] = "Display Avatar|Controls the display of an Avatar image<br /><span class=\"red\">Requires SigGen Roster Addon</span><br /><br />Setting is global";

// realmstatus_conf
$wordings['frFR']['admin']['realmstatus_url'] = "Realmstatus URL|URL to Blizzard's Realmstatus page";
$wordings['frFR']['admin']['rs_display'] = "Info Mode|&quot;full&quot; will show status and server name, population, and type<br />&quot;half&quot; will display just the status";
$wordings['frFR']['admin']['rs_mode'] = "Display Mode|How Realmstatus will be displayed<br /><br />&quot;DIV Container&quot; - Shows Realmstatus in a DIV container with text and standard images<br />&quot;Image&quot; - Shows Realmstatus as an image (REQUIRES GD!)";
$wordings['frFR']['admin']['realmstatus'] = "Alternate Servername|Some server names may cause realmstatus to not work correctly, even if uploading profiles works<br />The actual server name from the game may not match what is used on the server status data page<br />You can set this so serverstatus can use another servername<br /><br />Leave blank to use the name set in Guild Config";

// guildbank_conf
$wordings['frFR']['admin']['guildbank_ver'] = "Guildbank Display Type|Guild bank display type<br /><br />&quot;Table&quot; is a basic view showing all items available from every bank character in one list<br />&quot;Inventory&quot; shows a table of items for each bank character";
$wordings['frFR']['admin']['bank_money'] = "Money Display|Controls Money display in guildbanks";
$wordings['frFR']['admin']['banker_rankname'] = "Banker Search Text|Text used to designate banker characters";
$wordings['frFR']['admin']['banker_fieldname'] = "Banker Search Field|Banker Search location, what field to search for Banker Text";

// update_access
$wordings['frFR']['admin']['authenticated_user'] = "Access to Update.php|Controls access to update.php<br /><br />Turning this off disables access for EVERYONE";

// Character Display Settings
$wordings['frFR']['admin']['per_character_display'] = 'Per-Character Display';

?>