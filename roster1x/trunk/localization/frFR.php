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



//Instructions how to upload, as seen on the mainpage
$wordings['frFR']['update_link']='Cliquer ici pour les instructions de mise à jour.';
$wordings['frFR']['update_instructions']='Instructions de mise à jour.';

$wordings['frFR']['lualocation']='Cliquer parcourir (browse) et télécharger les fichiers *.lua<br />';

$wordings['frFR']['filelocation']='se trouve sous <br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$wordings['frFR']['noGuild']='Impossible de trouver la guilde dans la base de données. Mettre à jour la liste des membres.';
$wordings['frFR']['nodata']="Impossible de trouver la guilde: <b>'".$roster_conf['guild_name']."'</b> du serveur <b>'".$roster_conf['server_name']."'</b><br />Vous devez préalablement<a href=\"".$roster_conf['roster_dir']."/update.php\">charger votre guilde</a> et <a href=\"".$roster_conf['roster_dir']."/admin.php\">finaliser la configuration</a><br /><br /><a href=\"".$roster_conf['roster_dir']."/install.txt\" target=\"_blank\">Les instructions d'installation sont disponibles</a>";

$wordings['frFR']['update_page']='Mise à jour du profil';
// NOT USED $wordings['frFR']['updCharInfo']='Mettre à jour les informations du personnage';
$wordings['frFR']['guild_nameNotFound']='Impossible de mettre à jour la guilde &quot;%s&quot;. Vérifier la configuration!';
$wordings['frFR']['guild_addonNotFound']='Impossible de trouver la Guilde. L\'Addon GuildProfiler est-il installé correctement?';

$wordings['frFR']['ignored']='Ignoré';
$wordings['frFR']['update_disabled']='L\'accès à Update.php a été désactivé';

// NOT USED $wordings['frFR']['updGuildMembers']='Mettre à jour les membres de la guilde';
$wordings['frFR']['nofileUploaded']='Votre UniUploader n\'a pas téléchargé de fichier(s), ou des fichiers erronés.';
$wordings['frFR']['roster_upd_pwLabel']='Mot de passe du Roster';
$wordings['frFR']['roster_upd_pw_help']='(Requis lors d\'une mise à jour de la Guilde)';

// Updating Instructions

$index_text_uniloader = '<b><u>Prérequis à l\'utilisation d\'UniUploader:</b></u><a href="http://www.microsoft.com/downloads/details.aspx?FamilyID=0856EACB-4362-4B0D-8EDD-AAB15C5E04F5&displaylang=en">Microsoft .NET Framework</a> installé<br />Pour les utilisateurs d\'OS autres que Windows, utiliser JUniUploader qui vous permettra d\'effectuer les mêmes opérations que UniUploader mais en mode Java.';

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

$wordings['frFR']['roster_credits']='Props to <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, and <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> for the original code used for this site.<br />
WoWRoster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="'.$roster_conf['roster_dir'].'/credits.php">Additional Credits</a>';


//Charset
$wordings['frFR']['charset']="charset=utf-8";

$timeformat['frFR'] = '%d/%m/%Y %H:%i:%s'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$phptimeformat['frFR'] = 'd/m/Y H:i:s';    // PHP date() Time format (example - 'M D jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


/*
Instance Keys
=============
A part that is marked with 'MS' (milestone) will be designated as an overall status. So if
you have this one part it will mark all other parts lower than this one as complete.
*/

// ALLIANCE KEYS
$inst_keys['frFR']['A'] = array(
	'SG' => array( 'Quests', 'SG' =>
			'Clé de la gorge des Vents brûlants|4826',
			'La Corne de la B�te|',
			'Titre de propriété|',
			'Enfin !|'
		),
	'Gnome' => array( 'Key-Only', 'Gnome' =>
			'Clé d\\\'atelier|2288'
		),
	'SM' => array( 'Key-Only', 'SM' =>
			'La Clé écarlate|4445'
		),
	'ZF' => array( 'Parts', 'ZF' =>
			'Marteau de Zul\\\'Farrak|5695',
			'Maillet sacré|8250'
		),
	'Mauro' => array( 'Parts', 'Mauro' =>
			'Sceptre de Celebras|19710',
			'Bâtonnet de Celebras|19549',
			'Diamant de Celebras|19545'
		),
	'BRDp' => array( 'Key-Only', 'BRDp' =>
			'Clé de la prison|15545'
		),
	'BRDs' => array( 'Parts', 'BRDs' =>
			'Clé ombreforge|2966',
			'Souillefer|9673'
		),
	'HT' => array( 'Key-Only', 'HT' =>
			'Clé en croissant|35607'
		),
	'Scholo' => array( 'Quests', 'Scholo' =>
			'Clé squelette|16854',
			'Scholomance|',
			'Fragments de squelette|',
			'Moisissure rime avec...|',
			'Plume de feu forgée|',
			'Le Scarabée d\\\'Araj|',
			'La clé de la Scholomance|'
		),
	'Strath' => array( 'Key-Only', 'Strath' =>
			'Clé de la ville|13146'
		),
	'UBRS' => array( 'Parts', 'UBRS' =>
			'Sceau d\\\'ascension|17057',
			'Sceau d\\\'ascension non décoré|5370',
			'Gemme de Pierre-du-pic|5379',
			'Gemme de Brûleronce|16095',
			'Gemme de Hache-sanglante|21777',
			'Sceau d\\\'ascension brut |24554||MS',
			'Sceau d\\\'ascension forgé|19463||MS'
		),
	'Onyxia' => array( 'Quests', 'Onyxia' =>
			'Amulette Drakefeu|4829',
			'La menace dragonkin|',
			'Les véritables maîtres|',
			'Maréchal Windsor|',
			'Espoir abandonné|',
			'Une Note chiffonnée|',
			'Un espoir en lambeaux|',
			'Evasion !|',
			'Le rendez-vous à Stormwind|',
			'La grande mascarade|',
			'L\\\'Oeil de Dragon|',
			'Amulette drakefeu|'
		),
	'MC' => array( 'Key-Only', 'MC' =>
			'Quintessence éternelle|22754'
		),
);


// HORDE KEYS
$inst_keys['frFR']['H'] = array(
	'SG' => array( 'Key-Only', 'SG' =>
			'Clé de la gorge des Vents brûlants|4826'
		),
	'Gnome' => array( 'Key-Only', 'Gnome' =>
			'Clé d\\\'atelier|2288'
		),
	'SM' => array( 'Key-Only', 'SM' =>
			'La Clé écarlate|4445'
		),
	'ZF' => array( 'Parts', 'ZF' =>
			'Marteau de Zul\\\'Farrak|5695',
			'Maillet sacré|8250'
		),
	'Mauro' => array( 'Parts', 'Mauro' =>
			'Sceptre de Celebras|19710',
			'Bâtonnet de Celebras|19549',
			'Diamant de Celebras|19545'
		),
	'BRDp' => array( 'Key-Only', 'BRDp' =>
			'Clé de la prison|15545'
		),
	'BRDs' => array( 'Parts', 'BRDs' =>
			'Clé ombreforge|2966',
			'Souillefer|9673'
		),
	'HT' => array( 'Key-Only', 'HT' =>
			'Clé en croissant|35607'
		),
	'Scholo' => array( 'Quests', 'Scholo' =>
			'Clé squelette|16854',
			'Scholomance|',
			'Fragments de squelette|',
			'Moisissure rime avec...|',
			'Plume de feu forgée|',
			'Le Scarabée d\\\'Araj|',
			'La clé de la Scholomance|'
		),
	'Strath' => array( 'Key-Only', 'Strath' =>
			'Clé de la ville|13146'
		),
	'UBRS' => array( 'Parts', 'UBRS' =>
			'Sceau d\\\'ascension|17057',
			'Sceau d\\\'ascension non décoré|5370',
			'Gemme de Pierre-du-pic|5379',
			'Gemme de Brûleronce|16095',
			'Gemme de Hache-sanglante|21777',
			'Sceau d\\\'ascension brut |24554||MS',
			'Sceau d\\\'ascension forgé|19463||MS'
		),
	'Onyxia' => array( 'Quests', 'Onyxia' =>
			'Amulette Drakefeu|4829',
			'Ordres du seigneur de guerre Goretooth|',
			'Ordre du chef de guerre|',
			'Pour la Horde !|',
			'Ce que le vent apporte|',
			'Le Champion de la Horde|',
			'Le testament de Rexxar|',
			'Illusions d\\\'Occulus|',
			'Querelleur ardent|',
			'L\\\'épreuve des crânes, Cristallomancier|',
			'L\\\'épreuve des crânes, Somnus|',
			'L\\\'épreuve des crânes, Chronalis|',
			'L\\\'épreuve des crânes, Axtroz|',
			'Ascension...|',
			'Sang du Champion des Dragons noirs|'
		),
	'MC' => array( 'Key-Only', 'MC' =>
			'Quintessence éternelle|22754'
		),
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
$wordings['frFR']['duelsummary']='Résumé Duel';
$wordings['frFR']['money']='Argent';
$wordings['frFR']['bank']='Banque';
$wordings['frFR']['guildbank']='Banque de la Guilde';
$wordings['frFR']['guildbank_totalmoney']='Total des avoirs de la Guilde';
$wordings['frFR']['raid']='CT_Raid';
$wordings['frFR']['guildbankcontact']='Porté par (Contact)';
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
$wordings['frFR']['realm'] = 'Royaume';

// Memberlog
$wordings['frFR']['memberlog'] = 'Journal';
$wordings['frFR']['removed'] = 'Enlevé';
$wordings['frFR']['added'] = 'Ajouté';
$wordings['frFR']['no_memberlog'] = 'Aucun journal enregistré';

$wordings['frFR']['rosterdiag'] = 'Diagnostic du Roster';
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
$wordings['frFR']['Jewelcrafting']='Joaillier';
$wordings['frFR']['Skinning']='Dépeçage';
$wordings['frFR']['Tailoring']='Couture';
$wordings['frFR']['Enchanting']='Enchantement';
$wordings['frFR']['Engineering']='Ingénierie';
$wordings['frFR']['Cooking']='Cuisine';
$wordings['frFR']['Fishing']='Pêche';
$wordings['frFR']['First Aid']='Secourisme';
$wordings['frFR']['Poisons']='Poisons';
$wordings['frFR']['backpack']='Backpack';
$wordings['frFR']['PvPRankNone']='none';

// Uses preg_match() to find required level in recipie tooltip
$wordings['frFR']['requires_level'] = '/Niveau ([\d]+) requis/';

//Tradeskill-Array
$tsArray['frFR'] = array (
	$wordings['frFR']['Alchemy'],
	$wordings['frFR']['Herbalism'],
	$wordings['frFR']['Blacksmithing'],
	$wordings['frFR']['Mining'],
	$wordings['frFR']['Leatherworking'],
	$wordings['frFR']['Jewelcrafting'],
	$wordings['frFR']['Skinning'],
	$wordings['frFR']['Tailoring'],
	$wordings['frFR']['Enchanting'],
	$wordings['frFR']['Engineering'],
	$wordings['frFR']['Cooking'],
	$wordings['frFR']['Fishing'],
	$wordings['frFR']['First Aid'],
	$wordings['frFR']['Poisons'],
);

//Tradeskill Icons-Array
$wordings['frFR']['ts_iconArray'] = array (
	$wordings['frFR']['Alchemy']=>'Trade_Alchemy',
	$wordings['frFR']['Herbalism']=>'Trade_Herbalism',
	$wordings['frFR']['Blacksmithing']=>'Trade_BlackSmithing',
	$wordings['frFR']['Mining']=>'Trade_Mining',
	$wordings['frFR']['Leatherworking']=>'Trade_LeatherWorking',
	$wordings['frFR']['Jewelcrafting']=>'INV_Misc_Gem_02',
	$wordings['frFR']['Skinning']=>'INV_Misc_Pelt_Wolf_01',
	$wordings['frFR']['Tailoring']=>'Trade_Tailoring',
	$wordings['frFR']['Enchanting']=>'Trade_Engraving',
	$wordings['frFR']['Engineering']=>'Trade_Engineering',
	$wordings['frFR']['Cooking']=>'INV_Misc_Food_15',
	$wordings['frFR']['Fishing']=>'Trade_Fishing',
	$wordings['frFR']['First Aid']=>'Spell_Holy_SealOfSacrifice',
	$wordings['frFR']['Poisons']=>'Ability_Poisons',
	'Monte de tigre'=>'Ability_Mount_WhiteTiger',
	'Equitation'=>'Ability_Mount_RidingHorse',
	'Monte de bélier'=>'Ability_Mount_MountainRam',
	'Pilotage de mécanotrotteur'=>'Ability_Mount_MechaStrider',
	'Monte de cheval squelette'=>'Ability_Mount_Undeadhorse',
	'Monte de raptor'=>'Ability_Mount_Raptor',
	'Monte de kodo'=>'Ability_Mount_Kodo_03',
	'Monte de loup'=>'Ability_Mount_BlackDireWolf',
);

// Riding Skill Icons-Array
$wordings['frFR']['riding'] = 'Monte';
$wordings['frFR']['ts_ridingIcon'] = array(
	'Elfe de la nuit'=>'Ability_Mount_WhiteTiger',
	'Humain'=>'Ability_Mount_RidingHorse',
	'Nain'=>'Ability_Mount_MountainRam',
	'Gnome'=>'Ability_Mount_MechaStrider',
	'Mort-vivant'=>'Ability_Mount_Undeadhorse',
	'Troll'=>'Ability_Mount_Raptor',
	'Tauren'=>'Ability_Mount_Kodo_03',
	'Orc'=>'Ability_Mount_BlackDireWolf',
	'Elfe de Sang' => 'Ability_Mount_CockatriceMount',
	'Draenei' => 'Ability_Mount_RidingElekk',
	'Paladin'=>'Ability_Mount_Dreadsteed',
	'Démoniste'=>'Ability_Mount_NightmareHorse'
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
$wordings['frFR']['tab6']='JcJ';

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

$wordings['frFR']['empty_equip']='No item equipped';
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
$wordings['frFR']['hslist1']='Membre le mieux classé';
$wordings['frFR']['hslist2']='Membre ayant le plus de VH';
$wordings['frFR']['hslist3']='Le plus de Points d\'Honneur';
$wordings['frFR']['hslist4']='Le plus de Points d\'Arène';

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
$wordings['frFR']['lifetime']='A vie';
$wordings['frFR']['honorkills']='Vict. Honorables';
$wordings['frFR']['dishonorkills']='Vict. Déshonorantes';
$wordings['frFR']['honor']='Honneur';
$wordings['frFR']['standing']='Position';
$wordings['frFR']['highestrank']='Plus haut niveau';
$wordings['frFR']['arena']='Arène';

$wordings['frFR']['totalwins']='Nombre de victoires :';
$wordings['frFR']['totallosses']='Nombre de défaites :';
$wordings['frFR']['totaloverall']='Total général :';
$wordings['frFR']['win_average']='Différence moyenne de niveaux (victoires) :';
$wordings['frFR']['loss_average']='Différence moyenne de niveaux (défaites) :';

// These need to be EXACTLY what PvPLog stores them as
$wordings['frFR']['alterac_valley']='Vallée d\'Alterac';
$wordings['frFR']['arathi_basin']='Bassin d\'Arathi';
$wordings['frFR']['warsong_gulch']='Goulet des Chanteguerres';

$wordings['frFR']['world_pvp']='JcJ Mondial';
$wordings['frFR']['versus_guilds']='Contre Guilde';
$wordings['frFR']['versus_players']='Contre Joueurs';
$wordings['frFR']['bestsub']='Meilleure sous-zone';
$wordings['frFR']['worstsub']='Pire sous-zone';
$wordings['frFR']['killedmost']='Le plus tué';
$wordings['frFR']['killedmostby']='Le plus tué par';
$wordings['frFR']['gkilledmost']='Le plus tué par la guilde';
$wordings['frFR']['gkilledmostby']='Guild Killed Most By';

$wordings['frFR']['wins']='Victoires';
$wordings['frFR']['losses']='Défaites';
$wordings['frFR']['overall']='A vie';
$wordings['frFR']['best_zone']='Meilleure zone';
$wordings['frFR']['worst_zone']='Pire zone';
$wordings['frFR']['most_killed']='Le plus tué';
$wordings['frFR']['most_killed_by']='Le plus tué par';

$wordings['frFR']['when']='Quand';
$wordings['frFR']['guild']='Guilde';
$wordings['frFR']['leveldiff']='Différence de Niveau';
$wordings['frFR']['result']='Résultat';
$wordings['frFR']['zone2']='Zone';
$wordings['frFR']['subzone']='Sous-zone';
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
$wordings['frFR']['thievestools']='Outils de Voleur';
$wordings['frFR']['lockpicking']='Crochetage';
// END

	// Quests page external links (on character quests page)
		// questlink_n_name=?		This is the name displayed on the quests page
		// questlink_n_url=?		This is the URL used for the quest lookup

		$questlinks[0]['frFR']['name']='Judgehype FR';
		$questlinks[0]['frFR']['url1']='http://worldofwarcraft.judgehype.com/index.php?page=squete&amp;Ckey=';
		$questlinks[0]['frFR']['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$questlinks[0]['frFR']['url3']='&amp;maxl=';

		$questlinks[1]['frFR']['name']='WoWDBU FR';
		$questlinks[1]['frFR']['url1']='http://wowdbu.com/7.html?m=2&amp;mode=qsearch&amp;title=';
		$questlinks[1]['frFR']['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$questlinks[1]['frFR']['url3']='&amp;maxl=';

		$questlinks[2]['frFR']['name']='Allakhazam US';
		$questlinks[2]['frFR']['url1']='http://wow.allakhazam.com/db/qlookup.html?name=';
		$questlinks[2]['frFR']['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$questlinks[2]['frFR']['url3']='&amp;maxl=';

// Items external link
	$itemlink['frFR']='http://wowdbu.com/2-1.html?way=asc&amp;order=name&amp;showstats=&amp;type_limit=0&amp;lvlmin=&amp;lvlmax=&amp;name=';
	//$itemlink['frFR']='http://wow.allakhazam.com/search.html?q=';

// definitions for the questsearchpage
	$wordings['frFR']['search1']='Choisir la zone ou la quête dans la liste ci-dessous pour visualiser les joueurs concernés.<br />'."\n".'<small>Attention si les niveaux de quêtes ne sont pas les mêmes, il se peut qu\'il s\'agisse d\'une autre partie d\'une quête multiple.</small>';
	$wordings['frFR']['search2']='Recherche par Zone';
	$wordings['frFR']['search3']='Recherche par nom de quête';

// Definition for item tooltip coloring
	$wordings['frFR']['tooltip_use']='Utiliser';
	$wordings['frFR']['tooltip_requires']='Niveau';
	$wordings['frFR']['tooltip_reinforced']='renforcée';
	$wordings['frFR']['tooltip_soulbound']='Lié';
	$wordings['frFR']['tooltip_boe']='Lié quand équipé';
	$wordings['frFR']['tooltip_equip']='Equipé';
	$wordings['frFR']['tooltip_equip_restores']='Equipé : Rend';
	$wordings['frFR']['tooltip_equip_when']='Equipé : Lorsque';
	$wordings['frFR']['tooltip_chance']='Chance';
	$wordings['frFR']['tooltip_enchant']='Enchantement';
	$wordings['frFR']['tooltip_set']='Set';
	$wordings['frFR']['tooltip_rank']='Rang';
	$wordings['frFR']['tooltip_next_rank']='Prochain rang';
	$wordings['frFR']['tooltip_spell_damage']='les dégâts et les soins produits par les sorts et effets magiques';
	$wordings['frFR']['tooltip_school_damage']='les dégâts infligés par les sorts et effets';
	$wordings['frFR']['tooltip_healing_power']='les soins prodigués par les sorts et effets';
	$wordings['frFR']['tooltip_chance_hit']='Chances quand touché :';
	$wordings['frFR']['tooltip_reinforced_armor']='Armure renforcée';
	$wordings['frFR']['tooltip_damage_reduction']='Réduit les points de dégâts';

// Warlock pet names for icon displaying
	$wordings['frFR']['Imp']='Diablotin';
	$wordings['frFR']['Voidwalker']='Marcheur du Vide';
	$wordings['frFR']['Succubus']='Succube';
	$wordings['frFR']['Felhunter']='Chasseur corrompu';
	$wordings['frFR']['Infernal']='Infernal';
	$wordings['frFR']['Felguard']='Gangregarde';

// Max experiance for exp bar on char page
	$wordings['frFR']['max_exp']='Max XP';

// Error messages
	$wordings['frFR']['CPver_err']="La version du CharacterProfiler utilisé pour capturer les données pour ce personnage est plus ancienne que la version minimum autorisée pour le téléchargement.<br />\nSVP assurez vous que vous fonctionnez avec la v".$roster_conf['minCPver']." et que vous vous êtes connecté sur ce personnage et avez sauvé les données en utilisant cette version.";
	$wordings['frFR']['PvPLogver_err']="La version du PvPLog utilisé pour capturer les données pour ce personnage est plus ancienne que la version minimum autorisée pour le téléchargement.<br />\nSVP assurez vous que vous fonctionnez avec la v$".$roster_conf['minPvPLogver']." et, si vous venez de mettre �  jour PvPLog, assurez vous que vous avez supprimé cotre ancien fichier PvPLog.lua contenu dans les SavedVariables avant de le mettre �  jour.";
	$wordings['frFR']['GPver_err']="La version du GuildProfiler utilisé pour capturer les données pour ce personnage est plus ancienne que la version minimum autorisée pour le téléchargement.<br />\nSVP assurez vous que vous fonctionnez avec la v".$roster_conf['minGPver'];






/******************************
 * Roster Admin Strings
 ******************************/

// Submit/Reset confirm questions
$wordings['frFR']['confirm_config_submit'] = 'Ceci enregistrera les changements dans la base de données. Etes-vous sûr ?';
$wordings['frFR']['confirm_config_reset'] = 'Celà réinitialisera le formulaire aux valeurs précédentes. Etes-vous sûr ?';

// Main Menu words
$wordings['frFR']['admin']['main_conf'] = 'Options principales';
$wordings['frFR']['admin']['guild_conf'] = 'Options Guilde';
$wordings['frFR']['admin']['index_conf'] = 'Option Affichage';
$wordings['frFR']['admin']['menu_conf'] = 'Option Menu';
$wordings['frFR']['admin']['display_conf'] = 'Option Style';
$wordings['frFR']['admin']['char_conf'] = 'Options Joueurs';
$wordings['frFR']['admin']['realmstatus_conf'] = 'Option Royaume';
$wordings['frFR']['admin']['guildbank_conf'] = 'Option Banque';
$wordings['frFR']['admin']['data_links'] = 'Liens vers données objets/quêtes';
$wordings['frFR']['admin']['update_access'] = 'Accès à la mise à jour';


// All strings here
// Each variable must be the same name as the config variable name
// Example:
//   Assign description text and tooltip for $roster_conf['sqldebug']
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
$wordings['frFR']['admin']['sqldebug'] = "Affichage SQL de debug|Afficher les informations de contrôles de MySQL en format HTML";
$wordings['frFR']['admin']['debug_mode'] = "Debuggage|Debug complet en cas d'erreur";
$wordings['frFR']['admin']['sql_window'] = "Affichage SQL|Affiche les requêtes SQL dans le pied de page";
$wordings['frFR']['admin']['minCPver'] = "Version CP Minimum|Version minimale de CharacterProfiler autorisée";
$wordings['frFR']['admin']['minGPver'] = "Version GP Minimum|Version minimale de GuildProfiler autorisée";
$wordings['frFR']['admin']['minPvPLogver'] = "Version PvPLog Minimum|Version minimale de PvPLog autorisée";
$wordings['frFR']['admin']['roster_lang'] = "Langue du Roster|Le code langue principal du Roster";
$wordings['frFR']['admin']['website_address'] = "Adresse du site Web|Utilisé pour le lien sur le logo et le lien sur le menu principal<br />Certains addon pour le roster peuvent également l'utiliser";
$wordings['frFR']['admin']['roster_dir'] = "URL du Roster|L'URL du répertoire du roster<br />Ce paramètre est critique et doit être correct sous peine d'erreurs<br />(EX: http://www.site.com/roster )<br /><br />Une URL absolue n'est pas obligatoire mais un chemin relatif depuis la racine du serveur l'est (l'URL doit au moins commencer par un slash)<br />(EX: /roster )";
$wordings['frFR']['admin']['server_name_comp'] = "Mode de compatibilité char.php|Si la page des personnages ne fonctionne pas, essayez de changer ce paramètre";
$wordings['frFR']['admin']['interface_url'] = "URL du répertoire Interface|Répertoire où les images Interface images sont situés<br />La valeur par défaut est &quot;img/&quot;<br /><br />Vous pouvez utiliser un chemin relatif ou une URL absolue";
$wordings['frFR']['admin']['img_suffix'] = "Extension des images Interface|Le type des images Interface";
$wordings['frFR']['admin']['alt_img_suffix'] = "Extension alternative des images d'interface|Le type alternatif d'images pour les images de l'interface";
$wordings['frFR']['admin']['img_url'] = "URL du répertoire des images du roster|Répertoire où les images du roster sont situés<br />La valeur par défaut est &quot;img/&quot;<br /><br />Vous pouvez utiliser un chemin relatif ou une URL absolue";
$wordings['frFR']['admin']['timezone'] = "Fuseau horaire|Affiché après les dates et heures afin de savoir à quel fuseau horaire l'heure fait référence";
$wordings['frFR']['admin']['localtimeoffset'] = "Décalage horaire|Le décalage horaire par rapport à l'heure UTC/GMT<br />Les heures sur le roster seront affichées avec ce décalage";
$wordings['frFR']['admin']['pvp_log_allow'] = "Permettre le téléchargement des données PvPLog|Mettre la valeur à &quot;no&quot; désactivera le champ de téléchargement du PvPLog dans &quot;mise à jour&quot;";
$wordings['frFR']['admin']['use_update_triggers'] = "Permettre le déclenchement de mise à jour d'AddOn|Le déclenchement de mise à jour d'AddOn est nécessaire pour les AddOns qui ont besoin de fonctionner lors d'une mise à jour d'un profil<br />Quelques AddOns ont besoin de ce paramètre à on pour fonctionner correctement";

// guild_conf
$wordings['frFR']['admin']['guild_name'] = "Nom de la Guilde|Ce nom doit être orthographié exactement comme dans le jeu<br />ou vous <u>NE POURREZ PAS</u> charger les profils";
$wordings['frFR']['admin']['server_name'] = "Nom du Serveur|Ce nom doit être orthographié exactement comme dans le jeu<br />ou vous <u>NE POURREZ PAS</u> charger les profils";
$wordings['frFR']['admin']['guild_desc'] = "Description de la Guilde|Donner une description courte de la Guilde";
$wordings['frFR']['admin']['server_type'] = "Type de Serveur|Type de serveurs dans WoW";
$wordings['frFR']['admin']['alt_type'] = "Identification des rerolls|Textes identifiant les rerolls pour le décompte dans le menu principal";
$wordings['frFR']['admin']['alt_location'] = "Identification des rerolls (champ)|Où faut-il rechercher l'identification des rerolls";

// index_conf
$wordings['frFR']['admin']['index_pvplist'] = "Statistiques PvP|Statistiques du journal JcJ sur la page d'accueil<br />Si vous avez désactivé le téléchargement des données PvPLog, il n'y a pas besoin d'activer ceci";
$wordings['frFR']['admin']['index_hslist'] = "Statistiques Honneur|Statistiques du système d'honneur sur la page d'accueil";
$wordings['frFR']['admin']['hspvp_list_disp'] = "Affichage des listes JcJ et Honneur|Contrôle comment les listes JcJ et d'honneur d'affichent au chargement de la page<br />Les listes peuvent être masquées et ouvertes en cliquant sur leur titre<br /><br />&quot;show&quot; montrera les listes complètes quand la page se chargera<br />&quot;hide&quot; masquera les listes";
$wordings['frFR']['admin']['index_member_tooltip'] = "Infobulle sur les membres|Affiche quelques informations sur un personnage dans une infobulle";
$wordings['frFR']['admin']['index_update_inst'] = "Instructions de mise à jour|Contrôle l'affichage des instructions de mise à jour sur la page";
$wordings['frFR']['admin']['index_sort'] = "Tri de la liste des membres|Contrôle le tri par défaut";
$wordings['frFR']['admin']['index_motd'] = "Message du jour de la guilde|Montre le message du jour de la guilde en haut de la page<br /><br />Celà contrôle également l'affichage de la page &quot;Info Guilde&quot;";
$wordings['frFR']['admin']['index_level_bar'] = "Barre de niveau|Change l'affichage d'une barre de niveau en pourcentage sur la page principale";
$wordings['frFR']['admin']['index_iconsize'] = "Taille des icônes|Sélectionne la taille des icônes sur les pages principales (JcJ, compétences, classes, etc..)";
$wordings['frFR']['admin']['index_tradeskill_icon'] = "Icônes de compétences|Active les icônes de compétence sur les pages principales";
$wordings['frFR']['admin']['index_tradeskill_loc'] = "Affichage de la colonne des compétences|Sélectionne quelle dans colonne placer les icônes de compétence";
$wordings['frFR']['admin']['index_class_color'] = "Couleurs des classes|Mets en couleur les noms suivant les classes";
$wordings['frFR']['admin']['index_classicon'] = "Icônes des classes|Affiche une icône pour chaque classe et chaque personnage";
$wordings['frFR']['admin']['index_honoricon'] = "Icônes JcJ|Affiche une icône du rang JcJ à côté du nom du rang";
$wordings['frFR']['admin']['index_prof'] = "Colonne des professions|C'est une colonne sp�ciale pour les ic�nes de comp�tence<br />Si vous les placez dans une autre colonne, vous pouvez vouloir désactiver ceci";
$wordings['frFR']['admin']['index_currenthonor'] = "Colonne honneur|Change l'affichage de la colonne d'honneur";
$wordings['frFR']['admin']['index_note'] = "Colonne des notes|Change l'affichage de la colonne de la note publique";
$wordings['frFR']['admin']['index_title'] = "Colonne du titre au sein de la guilde|Change l'affichage de la colonne du titre au sein de la guilde";
$wordings['frFR']['admin']['index_hearthed'] = "Colonne de la pierre de foyer|Change l'affichage de la colonne de la pierre de foyer";
$wordings['frFR']['admin']['index_zone'] = "Colonne de la dernière zone|Change l'affichage de la colonne de la dernière zone";
$wordings['frFR']['admin']['index_lastonline'] = "Colonne de la dernière connexion|Change l'affichage de la colonne de la dernière connexion";
$wordings['frFR']['admin']['index_lastupdate'] = "Colonne de la dernière mise à jour|Affiche quand un personnage a été mis à jour pour la dernière fois";

// menu_conf
$wordings['frFR']['admin']['menu_left_pane'] = "Panneau de gauche (liste rapide des membres)|Contrôle l'affichage du panneau de gauche du menu principal du roster<br />Cette zone sert à la liste rapide des membres";
$wordings['frFR']['admin']['menu_right_pane'] = "Panneau de droite (statut du royaume)|Contrôle l'affichage du panneau de droite du menu principal du roster<br />Cette zone sert au statut du royaume";
$wordings['frFR']['admin']['menu_memberlog'] = "Lien Journal|Contrôle l'affichage du lien Journal";
$wordings['frFR']['admin']['menu_guild_info'] = "Lien Info Guilde|Contrôle l'affichage du lien Info Guilde";
$wordings['frFR']['admin']['menu_stats_page'] = "Lien Statistiques|Contrôle l'affichage du lien Statistiques";
$wordings['frFR']['admin']['menu_pvp_page'] = "Lien Statistiques PvP / JcJ|Contrôle l'affichage du lien Statistiques PvP / JcJ";
$wordings['frFR']['admin']['menu_honor_page'] = "Lien Honneur|Contrôle l'affichage du lien Honneur";
$wordings['frFR']['admin']['menu_guildbank'] = "Lien Banque de guilde|Contrôle l'affichage du lien Banque de guilde";
$wordings['frFR']['admin']['menu_keys_page'] = "Lien Clefs des instances|Contrôle l'affichage du lien Clefs des instances";
$wordings['frFR']['admin']['menu_tradeskills_page'] = "Lien Métiers|Contrôle l'affichage du lien Métiers";
$wordings['frFR']['admin']['menu_update_page'] = "Lien Mise à jour du profil|Contrôle l'affichage du lien Mise à jour du profil";
$wordings['frFR']['admin']['menu_quests_page'] = "Lien Trouver un groupe|Contrôle l'affichage du lien Trouver un groupe";
$wordings['frFR']['admin']['menu_search_page'] = "Lien Rechercher|Contrôle l'affichage du lien Rechercher";

// display_conf
$wordings['frFR']['admin']['stylesheet'] = "Feuille de style CSS|Feuille de style CSS pour le roster";
$wordings['frFR']['admin']['roster_js'] = "Fichier JavaScript du roster|Principal fichier JavaScript pour le roster";
$wordings['frFR']['admin']['tabcontent'] = "Fichier JavaScript des menus à onglets|Fichier JavaScript pour les  menus à onglets";
$wordings['frFR']['admin']['overlib'] = "Fichier JavaScript des infobulles|Fichier JavaScript pour les infobulles";
$wordings['frFR']['admin']['overlib_hide'] = "Fichier JavaScript Overlib|Fichier JavaScript de correction pour la librairie Overlib dans Internet Explorer";
$wordings['frFR']['admin']['logo'] = "URL pour le logo de l'entête|L'URL complète de l'image<br />Ou en laissant \"img/\" devant le nom, celà cherchera dans le répertoire img/ du roster";
$wordings['frFR']['admin']['roster_bg'] = "URL pour l'image de fond|L'URL absolue de l'image pour le fond principal<br>Ou en laissant &quot;img/&quot; devant le nom, celà cherchera dans le répertoire img/ du roster";
$wordings['frFR']['admin']['motd_display_mode'] = "Mode d'affichage du message du jour|Comment le message du jour sera affiché<br /><br />&quot;Text&quot; - Montre le message de du jour en rouge<br />&quot;Image&quot; - Montre le message du jour sous forme d'une image (nécesite GD!)";
$wordings['frFR']['admin']['compress_note'] = "Mode d'affichage des notes du joueur|Comment les notes du joueur seront affichées<br /><br />&quot;Text&quot; - Montre les notes du joueur sous format texte<br />&quot;Icon&quot; - Montre image avec une infobulle";
$wordings['frFR']['admin']['signaturebackground'] = "Image de fond pour img.php|Support de l'ancien générateur de signature";
$wordings['frFR']['admin']['processtime'] = "Temps de génération de la page|Affiche &quot;<i>This page was created in XXX seconds with XX queries executed</i>&quot; en bas de page du roster";

// data_links
$wordings['frFR']['admin']['questlink_1'] = "Lien de quête n°1|Lien externe sur des base de données<br />Regardez dans votre (vos) fichier(s) de localisation pour la configuration de ces liens";
$wordings['frFR']['admin']['questlink_2'] = "Lien de quête n°2|Lien externe sur des base de données<br />Regardez dans votre (vos) fichier(s) de localisation pour la configuration de ces liens";
$wordings['frFR']['admin']['questlink_3'] = "Lien de quête n°3|Lien externe sur des base de données<br />Regardez dans votre (vos) fichier(s) de localisation pour la configuration de ces liens";
$wordings['frFR']['admin']['profiler'] = "Lien de téléchargement du CharacterProfiler|URL de téléchargement de CharacterProfiler";
$wordings['frFR']['admin']['pvplogger'] = "Lien de téléchargement du PvPLog|URL de téléchargement de PvPLog";
$wordings['frFR']['admin']['uploadapp'] = "Lien de téléchargement d'UniUploader|URL de téléchargement d'UniUploader";

// char_conf
$wordings['frFR']['admin']['char_bodyalign'] = "Alignement sur la page des personnages|Alignement des donnes sur la page des personnages";
$wordings['frFR']['admin']['show_talents'] = "Talents|Visualisation des talents<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$wordings['frFR']['admin']['show_spellbook'] = "Livre des sorts|Visualisation du livres des sorts<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$wordings['frFR']['admin']['show_mail'] = "Courrier|Visualisation du courrier<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$wordings['frFR']['admin']['show_inventory'] = "Sacs|Visualisation des sacs<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$wordings['frFR']['admin']['show_money'] = "Argent|Visualisation de l'argent<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$wordings['frFR']['admin']['show_bank'] = "Banque|Visualisation du contenu de la banque<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$wordings['frFR']['admin']['show_recipes'] = "Recettes|Visualisation des recettes<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$wordings['frFR']['admin']['show_quests'] = "Quêtes|Visualisation des quêtes<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$wordings['frFR']['admin']['show_bg'] = "Champs de bataille|Visualisation des données de champs de bataille<br />Nécessite le téléchargement des données PvPLog<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$wordings['frFR']['admin']['show_pvp'] = "Joueur contre joueur|Visualisation des données joueur contre joueur<br />Nécessite le téléchargement des données PvPLog<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$wordings['frFR']['admin']['show_duels'] = "Duel|Visualisation des données de duel<br />Nécessite le téléchargement des données PvPLog<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$wordings['frFR']['admin']['show_item_bonuses'] = "Bonus d'équipement|Visualisation des bonus d'équipement<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$wordings['frFR']['admin']['show_signature'] = "Signature|Visualisation de l'image de la signature<br /><span class=\"red\">Nécessite l'addon du roster SigGen</span><br /><br />Le paramêtre est global";
$wordings['frFR']['admin']['show_avatar'] = "Avatar|Visualisation de l'image de l'avatar<br /><span class=\"red\">Nécessite l'addon du roster SigGen</span><br /><br />Le paramêtre est global";

// realmstatus_conf
$wordings['frFR']['admin']['realmstatus_url'] = "URL de statut des royaumes|URL vers la page de statut des royaumes de Blizzard";
$wordings['frFR']['admin']['rs_display'] = "Mode d'information|&quot;full&quot; montrera le statut et le nom du serveur, la population, and le type<br />&quot;half&quot; ne montrera que le statut";
$wordings['frFR']['admin']['rs_mode'] = "Mode d'affichage|Comment le statut du royaume sera affiché<br /><br />&quot;DIV Container&quot; - Le statut du royaume sera affiché dans une balise DIV avec du texte et des images<br />&quot;Image&quot; - Le statut du royaume sera affiché comme une image (NECESSITE GD !)";
$wordings['frFR']['admin']['realmstatus'] = "Nom de serveur alternatif|Quelques noms de serveur peuvent faire que le statut du royaume ne fonctionne pas même si le téléchargement de fichier marche<br />Le nom actuel du serveur provenant du jeu peut ne pas correspondre avec celui qui est utilisé sur la page de statut des royaumes<br />Vous pouvez donc régler le statut du royaume sur un autre nom de serveur<br /><br />Laissez vide pour prendre le nom utilisé dans la configuration de la guilde";

// guildbank_conf
$wordings['frFR']['admin']['guildbank_ver'] = "Type d'affichage de la banque de guilde|Type d'affichage de la banque de guilde<br /><br />&quot;Table&quot; est une vue simple montrant tous les objets de chaque personnage-banque dans une seule liste<br />&quot;Inventory&quot; montre une table d'objets par personnage-banque";
$wordings['frFR']['admin']['bank_money'] = "Affichage des avoirs de la guilde|Contrôle l'affichage des avoirs de la guilde";
$wordings['frFR']['admin']['banker_rankname'] = "Texte de recherche des personnages-banques|Texte utilisé pour désigner les personnages-banques";
$wordings['frFR']['admin']['banker_fieldname'] = "Champ de recherche des personnages-banques|Champ utilisé pour désigner les personnages-banques";

// update_access
$wordings['frFR']['admin']['authenticated_user'] = "Accès à Update.php|Contrôle l'accès à update.php<br /><br />Passer ce paramètre à off désactive l'accès à TOUT LE MONDE";

// Character Display Settings
$wordings['frFR']['admin']['per_character_display'] = 'Affichage par personnage';
