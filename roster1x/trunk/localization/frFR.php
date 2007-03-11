<?php
/******************************
 * WoWRoster.net  Roster
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

// frFR translation by wowodo, lesablier, Exerladan, and Ansgar



//Instructions how to upload, as seen on the mainpage
$lang['update_link']='Cliquer ici pour les instructions de mise à jour.';
$lang['update_instructions']='Instructions de mise à jour.';

$lang['lualocation']='Cliquer parcourir (browse) et télécharger les fichiers *.lua<br />';

$lang['filelocation']='se trouve sous <br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$lang['noGuild']='Impossible de trouver la guilde dans la base de données. Mettre à jour la liste des membres.';
$lang['nodata']="Impossible de trouver la guilde: <b>'".$roster_conf['guild_name']."'</b> du serveur <b>'".$roster_conf['server_name']."'</b><br />Vous devez préalablement<a href=\"".makelink('update')."\">charger votre guilde</a> et <a href=\"".makelink('rostercp')."\">finaliser la configuration</a><br /><br /><a href=\"http://www.wowroster.net/wiki/index.php/Roster:Install\" target=\"_blank\">Les instructions d'installation sont disponibles</a>";
$wordings['nodata_title']='No Guild Data';

$lang['update_page']='Mise à jour du profil';

$lang['guild_nameNotFound']='Impossible de mettre à jour la guilde &quot;%s&quot;. Vérifier la configuration!';
$lang['guild_addonNotFound']='Impossible de trouver la Guilde. L\'Addon GuildProfiler est-il installé correctement?';

$lang['ignored']='Ignoré';
$lang['update_disabled']='L\'accès à Update.php a été désactivé';

$lang['nofileUploaded']='Votre UniUploader n\'a pas téléchargé de fichier(s), ou des fichiers erronés.';
$lang['roster_upd_pwLabel']='Mot de passe du Roster';
$lang['roster_upd_pw_help']='(Requis lors d\'une mise à jour de la Guilde)';

// Updating Instructions

$lang['index_text_uniloader'] = '<b><u>Prérequis à l\'utilisation d\'UniUploader:</b></u><a href="http://www.microsoft.com/downloads/details.aspx?FamilyID=0856EACB-4362-4B0D-8EDD-AAB15C5E04F5&displaylang=en">Microsoft .NET Framework</a> installé<br />Pour les utilisateurs d\'OS autres que Windows, utiliser JUniUploader qui vous permettra d\'effectuer les mêmes opérations que UniUploader mais en mode Java.';

$lang['update_instruct']='
<strong>Actualisation automatique recommandée:<strong>
<ul>
<li>Utiliser <a href="'.$roster_conf['uploadapp'].'" target="_blank">UniUploader</a><br />
'.$lang['index_text_uniloader'].'</li>
</ul>
<strong>Instructions pour actualiser le profil:<strong>
<ol>
<li>Download <a href="'.$roster_conf['profiler'].'" target="_blank">Character Profiler</a></li>
<li>Décompresser le fichier zip dans son propre répertoire dans le répertoire *WoW Directory*\Interface\Addons\.</li>
<li>Démarrer WoW</li>
<li>Ouvrir votre compte en banque, la fenêtre des quêtes, et la fenêtre des professions qui contient les recettes</li>
<li>Se déconnecter ou quitter WoW.<br />(Voir ci-dessus si vous disposez d\'UniUploader pour automatiser l\'envois des informations.)</li>
<li>Aller sur la page <a href="'.makelink('update').'">d\'actualisation</a></li>
<li>'.$lang['lualocation'].'</li>
</ol>';

$lang['update_instructpvp']='
<strong>Statistique PvP Optionnel:</strong>
<ol>
<li>Télécharger <a href="'.$roster_conf['pvplogger'].'" target="_blank">PvPLog</a></li>
<li>Décompresser le fichier zip dans son propre directory sous *WoW Directory*\Interface\Addons\ (PvPLog\) répertoire.</li>
<li>Duel ou PvP</li>
<li>Envoyer les informations PvPLog.lua (voir étape 7 de l\'actualisation du profil).</li>
</ol>';

$lang['roster_credits']='Props to <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, and <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> for the original code used for this site.<br />
WoWRoster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="'.makelink('credits').'">Additional Credits</a>';


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


/*
Instance Keys
=============
A part that is marked with 'MS' (milestone) will be designated as an overall status. So if
you have this one part it will mark all other parts lower than this one as complete.
*/

// ALLIANCE KEYS
$lang['inst_keys']['A'] = array(
	'SG' => array( 'Quests',
		'SG' => 'Clé de la gorge des Vents brûlants|4826',
			'La Corne de la B�te|',
			'Titre de propriété|',
			'Enfin !|'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Clé d\\\'atelier|2288'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'La Clé écarlate|4445'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Marteau de Zul\\\'Farrak|5695',
			'Maillet sacré|8250'
		),
	'Marau' => array( 'Parts',
		'Marau' => 'Sceptre de Celebras|19710',
			'Bâtonnet de Celebras|19549',
			'Diamant de Celebras|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Clé de la prison|15545'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Clé ombreforge|2966',
			'Souillefer|9673'
		),
	'HT' => array( 'Key-Only',
		'HT' => 'Clé en croissant|35607'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Clé squelette|16854',
			'Scholomance|',
			'Fragments de squelette|',
			'Moisissure rime avec...|',
			'Plume de feu forgée|',
			'Le Scarabée d\\\'Araj|',
			'La clé de la Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Clé de la ville|13146'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Sceau d\\\'ascension|17057',
			'Sceau d\\\'ascension non décoré|5370',
			'Gemme de Pierre-du-pic|5379',
			'Gemme de Brûleronce|16095',
			'Gemme de Hache-sanglante|21777',
			'Sceau d\\\'ascension brut |24554||MS',
			'Sceau d\\\'ascension forgé|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Amulette Drakefeu|4829',
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
	'MC' => array( 'Key-Only',
		'MC' => 'Quintessence éternelle|22754'
		),
);


// HORDE KEYS
$inst_keys['H'] = array(
	'SG' => array( 'Key-Only',
		'SG' => 'Clé de la gorge des Vents brûlants|4826'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Clé d\\\'atelier|2288'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'La Clé écarlate|4445'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Marteau de Zul\\\'Farrak|5695',
			'Maillet sacré|8250'
		),
	'Marau' => array( 'Parts',
		'Marau' => 'Sceptre de Celebras|19710',
			'Bâtonnet de Celebras|19549',
			'Diamant de Celebras|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Clé de la prison|15545'
		),
	'BRDs' => array( 'Parts', 'BRDs' =>
			'Clé ombreforge|2966',
			'Souillefer|9673'
		),
	'HT' => array( 'Key-Only',
		'HT' => 'Clé en croissant|35607'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Clé squelette|16854',
			'Scholomance|',
			'Fragments de squelette|',
			'Moisissure rime avec...|',
			'Plume de feu forgée|',
			'Le Scarabée d\\\'Araj|',
			'La clé de la Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Clé de la ville|13146'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Sceau d\\\'ascension|17057',
			'Sceau d\\\'ascension non décoré|5370',
			'Gemme de Pierre-du-pic|5379',
			'Gemme de Brûleronce|16095',
			'Gemme de Hache-sanglante|21777',
			'Sceau d\\\'ascension brut |24554||MS',
			'Sceau d\\\'ascension forgé|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Amulette Drakefeu|4829',
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
	'MC' => array( 'Key-Only',
		'MC' => 'Quintessence éternelle|22754'
		),
);

//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$lang['upload']='Télécharger';
$lang['required']='Requis';
$lang['optional']='Optionnel';
$lang['attack']='Attaque';
$lang['defense']='Défense';
$lang['class']='Classe';
$lang['race']='Race';
$lang['level']='Niveau';
$lang['zone']='Dernière Zone';
$lang['note']='Note';
$lang['title']='Titre';
$lang['name']='Nom';
$lang['health']='Vie';
$lang['mana']='Mana';
$lang['gold']='Or';
$lang['armor']='Armure';
$lang['lastonline']='Dernière connexion';
$lang['lastupdate']='Dernière mise à jour';
$lang['currenthonor']='Rang d\'honneur actuel';
$lang['rank']='Rang';
$lang['sortby']='Trier par %';
$lang['total']='Total';
$lang['hearthed']='Pierre de Foyer';
$lang['recipes']='Recettes';
$lang['bags']='Sacs';
$lang['character']='Personnage';
$lang['bglog']='Journal BG';
$lang['pvplog']='Journal PvP';
$lang['duellog']='Journal Duel';
$lang['duelsummary']='Résumé Duel';
$lang['money']='Argent';
$lang['bank']='Banque';
$lang['guildbank']='Banque de la Guilde';
$lang['guildbank_totalmoney']='Total des avoirs de la Guilde';
$lang['raid']='CT_Raid';
$lang['guildbankcontact']='Porté par (Contact)';
$lang['guildbankitem']='Nom de l\'objet et sa description';
$lang['quests']='Quêtes';
$lang['roster']='Roster';
$lang['alternate']='Alternate';
$lang['byclass']='Par Classe';
$lang['menustats']='Stats';
$lang['menuhonor']='Honneur';
$lang['keys']='Clefs';
$lang['team']='Trouver un groupe';
$lang['search']='Rechercher';
$lang['update']='Dernière mise à jour';
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
$lang['completedsteps'] = 'Etapes finies';
$lang['currentstep'] = 'Etapes actuelles';
$lang['uncompletedsteps'] = 'Etapes incomplètes';
$lang['key'] = 'Clef';
$lang['timeplayed'] = 'Temps joué';
$lang['timelevelplayed'] = 'Temps joué à ce niveau';
$lang['Addon'] = 'Addons:';
$lang['advancedstats'] = 'Statistiques avancées';
$lang['itembonuses'] = 'Bonus dûs à l\'équipement';
$lang['itembonuses2'] = 'Objets Bonus';
$lang['crit'] = 'Crit';
$lang['dodge'] = 'Esquive';
$lang['parry'] = 'Parade';
$lang['block'] = 'Bloquer';
$lang['realm'] = 'Royaume';
$lang['talents'] = 'Talents';

// Memberlog
$lang['memberlog'] = 'Journal';
$lang['removed'] = 'Enlevé';
$lang['added'] = 'Ajouté';
$lang['no_memberlog'] = 'Aucun journal enregistré';

$lang['rosterdiag'] = 'Diagnostic du Roster';
$lang['Guild_Info'] = 'Info Guilde';
$lang['difficulty'] = 'Difficultée';
$lang['recipe_4'] = 'optimal';
$lang['recipe_3'] = 'moyen';
$lang['recipe_2'] = 'facile';
$lang['recipe_1'] = 'insignifiant';
$lang['roster_config'] = 'Configuration Roster';

// Spellbook
$lang['spellbook'] = 'Livre de sorts';
$lang['page'] = 'Page';
$lang['general'] = 'Général';
$lang['prev'] = 'Avant';
$lang['next'] = 'Après';

// Mailbox
$lang['mailbox'] = 'Boîte aux lettres';
$lang['maildateutc'] = 'Messages Capturés';
$lang['mail_item'] = 'Objet';
$lang['mail_sender'] = 'Expéditeur';
$lang['mail_subject'] = 'Sujet';
$lang['mail_expires'] = 'Messages expirés';
$lang['mail_money'] = 'Argent Inclus';


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
$lang['PvPRankNone']='none';

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
	$lang['Alchemy']=>'Trade_Alchemy',
	$lang['Herbalism']=>'Trade_Herbalism',
	$lang['Blacksmithing']=>'Trade_BlackSmithing',
	$lang['Mining']=>'Trade_Mining',
	$lang['Leatherworking']=>'Trade_LeatherWorking',
	$lang['Jewelcrafting']=>'INV_Misc_Gem_02',
	$lang['Skinning']=>'INV_Misc_Pelt_Wolf_01',
	$lang['Tailoring']=>'Trade_Tailoring',
	$lang['Enchanting']=>'Trade_Engraving',
	$lang['Engineering']=>'Trade_Engineering',
	$lang['Cooking']=>'INV_Misc_Food_15',
	$lang['Fishing']=>'Trade_Fishing',
	$lang['First Aid']=>'Spell_Holy_SealOfSacrifice',
	$lang['Poisons']=>'Ability_Poisons',
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
$lang['riding'] = 'Monte';
$lang['ts_ridingIcon'] = array(
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
$lang['class_iconArray'] = array (
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
$lang['skilltypes'] = array(
	1 => 'Compétences de Classe',
	2 => 'Métiers',
	3 => 'Compétences secondaires',
	4 => 'Compétences d’armes',
	5 => 'Armures portables',
	6 => 'Langues'
);

//tabs
$lang['tab1']='Stats';
$lang['tab2']='Pet';
$lang['tab3']='Rep';
$lang['tab4']='Comp';
$lang['tab5']='JcJ';

$lang['strength']='Force';
$lang['strength_tooltip']='Augmente la puissance d\'attaque avec arme de mêlée.<br />Augmente le nombre de points de dégâts bloqués par le bouclier.';
$lang['agility']='Agilité';
$lang['agility_tooltip']= 'Augmente votre puissance d\'attaque avec arme de jet.<br />Améliore vos change de réaliser une attaque critique avec toutes les armes.<br />Augmente votre armure et votre change d\'esquiver les attaques.';
$lang['stamina']='Endurance';
$lang['stamina_tooltip']= 'Augmente vos points de vie.';
$lang['intellect']='Intelligence';
$lang['intellect_tooltip']= 'Augmente vos points de mana et vos chances de réaliser une attaque critique aux moyens de sorts.<br />Augmente la vitesse d\'apprentissage des compétences en arme.';
$lang['spirit']='Esprit';
$lang['spirit_tooltip']= 'Augmente la vitesse de régénération de vos points de vie et de mana.';
$lang['armor_tooltip']= 'Diminue les dégâts resultant d\'attaque physique.<br />L\'importance de la diminution dépend du niveau de l\'attaquant.';

$lang['mainhand']='Main Hand';
$lang['offhand']='Off Hand';
$lang['ranged']='Ranged';
$lang['melee']='Melee';
$lang['spell']='Spell';

$lang['weapon_skill']='Skill';
$lang['weapon_skill_tooltip']='Weapon Skill %d<br />Weapon Skill Rating %d';
$lang['damage']='Damage';
$lang['damage_tooltip']='<table><tr><td>Attack speed (seconds):<td>%.2f<tr><td>Damage:<td>%d-%d<tr><td>Damage per second:<td>%.1f</table>';
$lang['speed']='Speed';
$lang['atk_speed']='Attack Speed';
$lang['haste_tooltip']='Haste rating ';

$lang['melee_att_power']='Puissance d\'attaque en mêlée';
$lang['melee_att_power_tooltip']='Increases damage with melee weapons by %.1f damage per second.';
$lang['range_att_power']='Puissance d\'attaque à distance';
$lang['ranged_att_power_tooltip']='Increases damage with ranged weapons by %.1f damage per second.';

$lang['weapon_hit_rating']='Hit Rating';
$lang['weapon_hit_rating_tooltip']='Increases your chance to hit an enemy.';
$lang['weapon_crit_rating']='Crit rating';
$lang['weapon_crit_rating_tooltip']='Critical strike chance %.2f%%.';

$lang['damage']='Dégâts';
$lang['energy']='Energie';
$lang['rage']='Rage';
$lang['power']='Puissance';

$lang['melee_rating']='Rang de l\'Attaque en Mêlée';
$lang['melee_rating_tooltip']='Votre rang d\'attaque influence vos change de toucher une cible<br />Et est basé sur votre habilité à utiliser l\'arme que vous portez..';
$lang['range_rating']='Rang de l\'Attaque à Distance';
$lang['range_rating_tooltip']='Votre rang d\'attaque influence vos change de toucher une cible<br />Et est basé sur votre habilité à utiliser l\'arme que vous manipulez..';

$lang['spell_damage']='+Damage';
$lang['fire']='Fire';
$lang['nature']='Nature';
$lang['frost']='Frost';
$lang['shadow']='Shadow';
$lang['arcane']='Arcane';

$lang['spell_healing']='+Healing';
$lang['spell_healing_tooltip']='Increases your healing by up to %d';
$lang['spell_hit_rating']='Hit Rating';
$lang['spell_hit_rating_tooltip']='Increases your chance to hit an enemy with your spells.';
$lang['spell_crit_rating']='Crit Rating';
$lang['spell_crit_chance']='Crit Chance';
$lang['spell_penetration']='Penetration';
$lang['spell_penetration_tooltip']='Reduces the target\'s resistance to your spells';
$lang['mana_regen']='Mana Regen';
$lang['mana_regen_tooltip']='%d mana regenerated every %d seconds while not casting';

$lang['defense_rating']='Defense Rating ';
$lang['def_tooltip']='Increases your chance to %s';
$lang['resilience']='Resilience';

$lang['res_fire']='Résistance au feu';
$lang['res_fire_tooltip']='Augmente votre résistance aux dégâts de feu.<br />Plus haut est le nombre, meilleure est la résistance.';
$lang['res_nature']='Résistance à la nature';
$lang['res_nature_tooltip']='Augmente votre résistance aux dégâts de la nature.<br />Plus haut est le nombre, meilleure est la résistance.';
$lang['res_arcane']='Résistance des Arcanes';
$lang['res_arcane_tooltip']='Augmente votre résistance aux dégâts des Arcanes.<br />Plus haut est le nombre, meilleure est la résistance.';
$lang['res_frost']='Résistance au froid';
$lang['res_frost_tooltip']='Augmente votre résistance aux dégâts de froid.<br />Plus haut est le nombre, meilleure est la résistance.';
$lang['res_shadow']='Résistance à l\'ombre';
$lang['res_shadow_tooltip']='Augmente votre résistance aux dégâts d\'ombre.<br />Plus haut est le nombre, meilleure est la résistance.';

$lang['empty_equip']='No item equipped';
$lang['pointsspent']='Points Utilisés dans';
$lang['none']='Rien';

$lang['pvplist']=' Stats JcJ/PvP';
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
$lang['yesterday']='Hier';
$lang['thisweek']='Cette semaine';
$lang['lastweek']='Semaine passée';
$lang['lifetime']='A vie';
$lang['honorkills']='Vict. Honorables';
$lang['dishonorkills']='Vict. Déshonorantes';
$lang['honor']='Honneur';
$lang['standing']='Position';
$lang['highestrank']='Plus haut niveau';
$lang['arena']='Arène';

$lang['totalwins']='Nombre de victoires :';
$lang['totallosses']='Nombre de défaites :';
$lang['totaloverall']='Total général :';
$lang['win_average']='Différence moyenne de niveaux (victoires) :';
$lang['loss_average']='Différence moyenne de niveaux (défaites) :';

// These need to be EXACTLY what PvPLog stores them as
$lang['alterac_valley']='Vallée d\'Alterac';
$lang['arathi_basin']='Bassin d\'Arathi';
$lang['warsong_gulch']='Goulet des Chanteguerres';

$lang['world_pvp']='JcJ Mondial';
$lang['versus_guilds']='Contre Guilde';
$lang['versus_players']='Contre Joueurs';
$lang['bestsub']='Meilleure sous-zone';
$lang['worstsub']='Pire sous-zone';
$lang['killedmost']='Le plus tué';
$lang['killedmostby']='Le plus tué par';
$lang['gkilledmost']='Le plus tué par la guilde';
$lang['gkilledmostby']='Guild Killed Most By';

$lang['wins']='Victoires';
$lang['losses']='Défaites';
$lang['overall']='A vie';
$lang['best_zone']='Meilleure zone';
$lang['worst_zone']='Pire zone';
$lang['most_killed']='Le plus tué';
$lang['most_killed_by']='Le plus tué par';

$lang['when']='Quand';
$lang['guild']='Guilde';
$lang['leveldiff']='Différence de Niveau';
$lang['result']='Résultat';
$lang['zone2']='Zone';
$lang['subzone']='Sous-zone';
$lang['bg']='Champ de Bataille';
$lang['yes']='Oui';
$lang['no']='Non';
$lang['win']='Victoire';
$lang['loss']='Défaite';
$lang['kills']='Tués';
$lang['unknown']='Inconnu';

// guildpvp strings
$lang['guildwins'] = 'Wins by Guild';
$lang['guildlosses'] = 'Losses by Guild';
$lang['enemywins'] = 'Wins by Enemy';
$lang['enemylosses'] = 'Losses by Enemy';
$lang['purgewins'] = 'Guild Member Kills';
$lang['purgelosses'] = 'Guild Member Deaths';
$lang['purgeavewins'] = 'Best Win/Level-Diff Average';
$lang['purgeavelosses'] = 'Best Loss/Level-Diff Average';
$lang['pvpratio'] = 'Solo Win/Loss Ratios';
$lang['playerinfo'] = 'Player Info';
$lang['guildinfo'] = 'Guild Info';

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

// language definitions for the rogue instance keys 'fix'
$lang['thievestools']='Outils de Voleur';
$lang['lockpicking']='Crochetage';
// END

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


// definitions for the questsearchpage
	$lang['search1']='Choisir la zone ou la quête dans la liste ci-dessous pour visualiser les joueurs concernés.<br />'."\n".'<small>Attention si les niveaux de quêtes ne sont pas les mêmes, il se peut qu\'il s\'agisse d\'une autre partie d\'une quête multiple.</small>';
	$lang['search2']='Recherche par Zone';
	$lang['search3']='Recherche par nom de quête';

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
	$lang['CPver_err']="La version du CharacterProfiler utilisé pour capturer les données pour ce personnage est plus ancienne que la version minimum autorisée pour le téléchargement.<br />\nSVP assurez vous que vous fonctionnez avec la v".$roster_conf['minCPver']." et que vous vous êtes connecté sur ce personnage et avez sauvé les données en utilisant cette version.";
	$lang['PvPLogver_err']="La version du PvPLog utilisé pour capturer les données pour ce personnage est plus ancienne que la version minimum autorisée pour le téléchargement.<br />\nSVP assurez vous que vous fonctionnez avec la v$".$roster_conf['minPvPLogver']." et, si vous venez de mettre �  jour PvPLog, assurez vous que vous avez supprimé cotre ancien fichier PvPLog.lua contenu dans les SavedVariables avant de le mettre �  jour.";
	$lang['GPver_err']="La version du GuildProfiler utilisé pour capturer les données pour ce personnage est plus ancienne que la version minimum autorisée pour le téléchargement.<br />\nSVP assurez vous que vous fonctionnez avec la v".$roster_conf['minGPver'];






/******************************
 * Roster Admin Strings
 ******************************/

$lang['pagebar_function'] = 'Function';
$lang['pagebar_rosterconf'] = 'Configure Main Roster';
$lang['pagebar_charpref'] = 'Character Preferences';
$lang['pagebar_changepass'] = 'Change Password';
$lang['pagebar_addoninst'] = 'Manage Addons';
$lang['pagebar_update'] = 'Upload Profile';
$lang['pagebar_rosterdiag'] = 'Roster Diag';
$lang['pagebar_menuconf'] = 'Menu configuration';

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
//   Assign description text and tooltip for $roster_conf['sqldebug']
//   $wordings['locale']['admin']['sqldebug'] = "Desc|Tooltip";

// Each string is separated by a pipe ( | )
// The first part is the short description, the next part is the tooltip
// Use <br /> to make new lines!
// Example:
//   "Controls Flux-Capacitor|Turning this on may cause serious temporal distortions<br />Use with care"


// Main Menu words
$lang['admin']['main_conf'] = 'Main Settings|Roster\'s main settings<br>Including roster URL, Interface Images URL, and other core options';
$lang['admin']['guild_conf'] = 'Guild Config|Set up your guild info<ul><li>Guild name</li><li>Realm name (server)</li><li>Short guild description</li><li>Server type</li><li>etc...</li></ul>';
$lang['admin']['index_conf'] = 'Index Page|Options for what shows on the Main Page';
$lang['admin']['menu_conf'] = 'Menu|Control what is displayed in the Roster Main Menu';
$lang['admin']['display_conf'] = 'Display Config|Misc display settings<br>css, javascript, motd, etc...';
$lang['admin']['char_conf'] = 'Character Page|Control what is displayed in the Character pages';
$lang['admin']['realmstatus_conf'] = 'Realmstatus|Options for Realmstatus<br><br>To turn this off, look in the Menu section';
$lang['admin']['guildbank_conf'] = 'Guildbank|Set up your guildbank display and characters';
$lang['admin']['data_links'] = 'Item/Quest Data Links|External links for item and quest data';
$lang['admin']['update_access'] = 'Update Access|Set access levels for rostercp components';

$lang['admin']['documentation'] = 'Documentation|WoWRoster Documentation via the wowroster.net wiki';

// main_conf
$lang['admin']['roster_upd_pw'] = "Mot de passe du Roster|Il s'agit du mot de passe permettant la mise à jour de la liste des membres de la Guilde.<br />Certains addons peuvent aussi utilisé ce mot de passe.";
$lang['admin']['roster_dbver'] = "Version de la base de données Roster|La version de la base de données";
$lang['admin']['version'] = "Version du Roster|Version actuelle du Roster";
$lang['admin']['sqldebug'] = "Affichage SQL de debug|Afficher les informations de contrôles de MySQL en format HTML";
$lang['admin']['debug_mode'] = "Debuggage|Debug complet en cas d'erreur";
$lang['admin']['sql_window'] = "Affichage SQL|Affiche les requêtes SQL dans le pied de page";
$lang['admin']['minCPver'] = "Version CP Minimum|Version minimale de CharacterProfiler autorisée";
$lang['admin']['minGPver'] = "Version GP Minimum|Version minimale de GuildProfiler autorisée";
$lang['admin']['minPvPLogver'] = "Version PvPLog Minimum|Version minimale de PvPLog autorisée";
$lang['admin']['roster_lang'] = "Langue du Roster|Le code langue principal du Roster";
$lang['admin']['default_page'] = "Default Page|Page to display if no page is specified in the url";
$lang['admin']['website_address'] = "Adresse du site Web|Utilisé pour le lien sur le logo et le lien sur le menu principal<br />Certains addon pour le roster peuvent également l'utiliser";
$lang['admin']['roster_dir'] = "URL du Roster|L'URL du répertoire du roster<br />Ce paramètre est critique et doit être correct sous peine d'erreurs<br />(EX: http://www.site.com/roster )<br /><br />Une URL absolue n'est pas obligatoire mais un chemin relatif depuis la racine du serveur l'est (l'URL doit au moins commencer par un slash)<br />(EX: /roster )";
$lang['admin']['interface_url'] = "URL du répertoire Interface|Répertoire où les images Interface images sont situés<br />La valeur par défaut est &quot;img/&quot;<br /><br />Vous pouvez utiliser un chemin relatif ou une URL absolue";
$lang['admin']['img_suffix'] = "Extension des images Interface|Le type des images Interface";
$lang['admin']['alt_img_suffix'] = "Extension alternative des images d'interface|Le type alternatif d'images pour les images de l'interface";
$lang['admin']['img_url'] = "URL du répertoire des images du roster|Répertoire où les images du roster sont situés<br />La valeur par défaut est &quot;img/&quot;<br /><br />Vous pouvez utiliser un chemin relatif ou une URL absolue";
$lang['admin']['timezone'] = "Fuseau horaire|Affiché après les dates et heures afin de savoir à quel fuseau horaire l'heure fait référence";
$lang['admin']['localtimeoffset'] = "Décalage horaire|Le décalage horaire par rapport à l'heure UTC/GMT<br />Les heures sur le roster seront affichées avec ce décalage";
$lang['admin']['pvp_log_allow'] = "Permettre le téléchargement des données PvPLog|Mettre la valeur à &quot;no&quot; désactivera le champ de téléchargement du PvPLog dans &quot;mise à jour&quot;";
$lang['admin']['use_update_triggers'] = "Permettre le déclenchement de mise à jour d'AddOn|Le déclenchement de mise à jour d'AddOn est nécessaire pour les AddOns qui ont besoin de fonctionner lors d'une mise à jour d'un profil<br />Quelques AddOns ont besoin de ce paramètre à on pour fonctionner correctement";

// guild_conf
$lang['admin']['guild_name'] = "Nom de la Guilde|Ce nom doit être orthographié exactement comme dans le jeu<br />ou vous <u>NE POURREZ PAS</u> charger les profils";
$lang['admin']['server_name'] = "Nom du Serveur|Ce nom doit être orthographié exactement comme dans le jeu<br />ou vous <u>NE POURREZ PAS</u> charger les profils";
$lang['admin']['guild_desc'] = "Description de la Guilde|Donner une description courte de la Guilde";
$lang['admin']['server_type'] = "Type de Serveur|Type de serveurs dans WoW";
$lang['admin']['alt_type'] = "Identification des rerolls|Textes identifiant les rerolls pour le décompte dans le menu principal";
$lang['admin']['alt_location'] = "Identification des rerolls (champ)|Où faut-il rechercher l'identification des rerolls";

// index_conf
$lang['admin']['index_pvplist'] = "Statistiques PvP|Statistiques du journal JcJ sur la page d'accueil<br />Si vous avez désactivé le téléchargement des données PvPLog, il n'y a pas besoin d'activer ceci";
$lang['admin']['index_hslist'] = "Statistiques Honneur|Statistiques du système d'honneur sur la page d'accueil";
$lang['admin']['hspvp_list_disp'] = "Affichage des listes JcJ et Honneur|Contrôle comment les listes JcJ et d'honneur d'affichent au chargement de la page<br />Les listes peuvent être masquées et ouvertes en cliquant sur leur titre<br /><br />&quot;show&quot; montrera les listes complètes quand la page se chargera<br />&quot;hide&quot; masquera les listes";
$lang['admin']['index_member_tooltip'] = "Infobulle sur les membres|Affiche quelques informations sur un personnage dans une infobulle";
$lang['admin']['index_update_inst'] = "Instructions de mise à jour|Contrôle l'affichage des instructions de mise à jour sur la page";
$lang['admin']['index_sort'] = "Tri de la liste des membres|Contrôle le tri par défaut";
$lang['admin']['index_motd'] = "Message du jour de la guilde|Montre le message du jour de la guilde en haut de la page<br /><br />Celà contrôle également l'affichage de la page &quot;Info Guilde&quot;";
$lang['admin']['index_level_bar'] = "Barre de niveau|Change l'affichage d'une barre de niveau en pourcentage sur la page principale";
$lang['admin']['index_iconsize'] = "Taille des icônes|Sélectionne la taille des icônes sur les pages principales (JcJ, compétences, classes, etc..)";
$lang['admin']['index_tradeskill_icon'] = "Icônes de compétences|Active les icônes de compétence sur les pages principales";
$lang['admin']['index_tradeskill_loc'] = "Affichage de la colonne des compétences|Sélectionne quelle dans colonne placer les icônes de compétence";
$lang['admin']['index_class_color'] = "Couleurs des classes|Mets en couleur les noms suivant les classes";
$lang['admin']['index_classicon'] = "Icônes des classes|Affiche une icône pour chaque classe et chaque personnage";
$lang['admin']['index_honoricon'] = "Icônes JcJ|Affiche une icône du rang JcJ à côté du nom du rang";
$lang['admin']['index_prof'] = "Colonne des professions|C'est une colonne sp�ciale pour les ic�nes de comp�tence<br />Si vous les placez dans une autre colonne, vous pouvez vouloir désactiver ceci";
$lang['admin']['index_currenthonor'] = "Colonne honneur|Change l'affichage de la colonne d'honneur";
$lang['admin']['index_note'] = "Colonne des notes|Change l'affichage de la colonne de la note publique";
$lang['admin']['index_title'] = "Colonne du titre au sein de la guilde|Change l'affichage de la colonne du titre au sein de la guilde";
$lang['admin']['index_hearthed'] = "Colonne de la pierre de foyer|Change l'affichage de la colonne de la pierre de foyer";
$lang['admin']['index_zone'] = "Colonne de la dernière zone|Change l'affichage de la colonne de la dernière zone";
$lang['admin']['index_lastonline'] = "Colonne de la dernière connexion|Change l'affichage de la colonne de la dernière connexion";
$lang['admin']['index_lastupdate'] = "Colonne de la dernière mise à jour|Affiche quand un personnage a été mis à jour pour la dernière fois";

// menu_conf
$lang['admin']['menu_left_pane'] = "Panneau de gauche (liste rapide des membres)|Contrôle l'affichage du panneau de gauche du menu principal du roster<br />Cette zone sert à la liste rapide des membres";
$lang['admin']['menu_right_pane'] = "Panneau de droite (statut du royaume)|Contrôle l'affichage du panneau de droite du menu principal du roster<br />Cette zone sert au statut du royaume";
$lang['admin']['menu_memberlog'] = "Lien Journal|Contrôle l'affichage du lien Journal";
$lang['admin']['menu_member_page'] = "MemberList Link|Controls display of the MemberList Link";
$lang['admin']['menu_guild_info'] = "Lien Info Guilde|Contrôle l'affichage du lien Info Guilde";
$lang['admin']['menu_stats_page'] = "Lien Statistiques|Contrôle l'affichage du lien Statistiques";
$lang['admin']['menu_pvp_page'] = "Lien Statistiques PvP / JcJ|Contrôle l'affichage du lien Statistiques PvP / JcJ";
$lang['admin']['menu_honor_page'] = "Lien Honneur|Contrôle l'affichage du lien Honneur";
$lang['admin']['menu_guildbank'] = "Lien Banque de guilde|Contrôle l'affichage du lien Banque de guilde";
$lang['admin']['menu_keys_page'] = "Lien Clefs des instances|Contrôle l'affichage du lien Clefs des instances";
$lang['admin']['menu_tradeskills_page'] = "Lien Métiers|Contrôle l'affichage du lien Métiers";
$lang['admin']['menu_update_page'] = "Lien Mise à jour du profil|Contrôle l'affichage du lien Mise à jour du profil";
$lang['admin']['menu_quests_page'] = "Lien Trouver un groupe|Contrôle l'affichage du lien Trouver un groupe";
$lang['admin']['menu_search_page'] = "Lien Rechercher|Contrôle l'affichage du lien Rechercher";

// display_conf
$lang['admin']['stylesheet'] = "Feuille de style CSS|Feuille de style CSS pour le roster";
$lang['admin']['roster_js'] = "Fichier JavaScript du roster|Principal fichier JavaScript pour le roster";
$lang['admin']['tabcontent'] = "Fichier JavaScript des menus à onglets|Fichier JavaScript pour les  menus à onglets";
$lang['admin']['overlib'] = "Fichier JavaScript des infobulles|Fichier JavaScript pour les infobulles";
$lang['admin']['overlib_hide'] = "Fichier JavaScript Overlib|Fichier JavaScript de correction pour la librairie Overlib dans Internet Explorer";
$lang['admin']['logo'] = "URL pour le logo de l'entête|L'URL complète de l'image<br />Ou en laissant \"img/\" devant le nom, celà cherchera dans le répertoire img/ du roster";
$lang['admin']['roster_bg'] = "URL pour l'image de fond|L'URL absolue de l'image pour le fond principal<br>Ou en laissant &quot;img/&quot; devant le nom, celà cherchera dans le répertoire img/ du roster";
$lang['admin']['motd_display_mode'] = "Mode d'affichage du message du jour|Comment le message du jour sera affiché<br /><br />&quot;Text&quot; - Montre le message de du jour en rouge<br />&quot;Image&quot; - Montre le message du jour sous forme d'une image (nécesite GD!)";
$lang['admin']['compress_note'] = "Mode d'affichage des notes du joueur|Comment les notes du joueur seront affichées<br /><br />&quot;Text&quot; - Montre les notes du joueur sous format texte<br />&quot;Icon&quot; - Montre image avec une infobulle";
$lang['admin']['signaturebackground'] = "Image de fond pour img.php|Support de l'ancien générateur de signature";
$lang['admin']['processtime'] = "Temps de génération de la page|Affiche &quot;<i>This page was created in XXX seconds with XX queries executed</i>&quot; en bas de page du roster";

// data_links
$lang['admin']['questlink_1'] = "Lien de quête n°1|Lien externe sur des base de données<br />Regardez dans votre (vos) fichier(s) de localisation pour la configuration de ces liens";
$lang['admin']['questlink_2'] = "Lien de quête n°2|Lien externe sur des base de données<br />Regardez dans votre (vos) fichier(s) de localisation pour la configuration de ces liens";
$lang['admin']['questlink_3'] = "Lien de quête n°3|Lien externe sur des base de données<br />Regardez dans votre (vos) fichier(s) de localisation pour la configuration de ces liens";
$lang['admin']['profiler'] = "Lien de téléchargement du CharacterProfiler|URL de téléchargement de CharacterProfiler";
$lang['admin']['pvplogger'] = "Lien de téléchargement du PvPLog|URL de téléchargement de PvPLog";
$lang['admin']['uploadapp'] = "Lien de téléchargement d'UniUploader|URL de téléchargement d'UniUploader";

// char_conf
$lang['admin']['char_bodyalign'] = "Alignement sur la page des personnages|Alignement des donnes sur la page des personnages";
$lang['admin']['show_talents'] = "Talents|Visualisation des talents<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_spellbook'] = "Livre des sorts|Visualisation du livres des sorts<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_mail'] = "Courrier|Visualisation du courrier<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_inventory'] = "Sacs|Visualisation des sacs<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_money'] = "Argent|Visualisation de l'argent<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_bank'] = "Banque|Visualisation du contenu de la banque<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_recipes'] = "Recettes|Visualisation des recettes<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_quests'] = "Quêtes|Visualisation des quêtes<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_bg'] = "Champs de bataille|Visualisation des données de champs de bataille<br />Nécessite le téléchargement des données PvPLog<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_pvp'] = "Joueur contre joueur|Visualisation des données joueur contre joueur<br />Nécessite le téléchargement des données PvPLog<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_duels'] = "Duel|Visualisation des données de duel<br />Nécessite le téléchargement des données PvPLog<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_item_bonuses'] = "Bonus d'équipement|Visualisation des bonus d'équipement<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_signature'] = "Signature|Visualisation de l'image de la signature<br /><span class=\"red\">Nécessite l'addon du roster SigGen</span><br /><br />Le paramêtre est global";
$lang['admin']['show_avatar'] = "Avatar|Visualisation de l'image de l'avatar<br /><span class=\"red\">Nécessite l'addon du roster SigGen</span><br /><br />Le paramêtre est global";

// realmstatus_conf
$lang['admin']['realmstatus_url'] = "URL de statut des royaumes|URL vers la page de statut des royaumes de Blizzard";
$lang['admin']['rs_display'] = "Mode d'information|&quot;full&quot; montrera le statut et le nom du serveur, la population, and le type<br />&quot;half&quot; ne montrera que le statut";
$lang['admin']['rs_mode'] = "Mode d'affichage|Comment le statut du royaume sera affiché<br /><br />&quot;DIV Container&quot; - Le statut du royaume sera affiché dans une balise DIV avec du texte et des images<br />&quot;Image&quot; - Le statut du royaume sera affiché comme une image (NECESSITE GD !)";
$lang['admin']['realmstatus'] = "Nom de serveur alternatif|Quelques noms de serveur peuvent faire que le statut du royaume ne fonctionne pas même si le téléchargement de fichier marche<br />Le nom actuel du serveur provenant du jeu peut ne pas correspondre avec celui qui est utilisé sur la page de statut des royaumes<br />Vous pouvez donc régler le statut du royaume sur un autre nom de serveur<br /><br />Laissez vide pour prendre le nom utilisé dans la configuration de la guilde";

// guildbank_conf
$lang['admin']['guildbank_ver'] = "Type d'affichage de la banque de guilde|Type d'affichage de la banque de guilde<br /><br />&quot;Table&quot; est une vue simple montrant tous les objets de chaque personnage-banque dans une seule liste<br />&quot;Inventory&quot; montre une table d'objets par personnage-banque";
$lang['admin']['bank_money'] = "Affichage des avoirs de la guilde|Contrôle l'affichage des avoirs de la guilde";
$lang['admin']['banker_rankname'] = "Texte de recherche des personnages-banques|Texte utilisé pour désigner les personnages-banques";
$lang['admin']['banker_fieldname'] = "Champ de recherche des personnages-banques|Champ utilisé pour désigner les personnages-banques";

// update_access
$lang['admin']['authenticated_user'] = "Accès à Update.php|Contrôle l'accès à update.php<br /><br />Passer ce paramètre à off désactive l'accès à TOUT LE MONDE";

// Character Display Settings
$lang['admin']['per_character_display'] = 'Affichage par personnage';
