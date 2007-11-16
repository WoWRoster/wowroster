<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * frFR Locale
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
 * @subpackage Locale
*/

$lang['char_info'] = 'Informations des personnages';
$lang['char_info_desc'] = 'Affiche les informations relatives aux personnages qui ont été téléchargées sur le roster';

// Menu Buttons
$lang['cb_character'] = 'Personnage|Affichage des statistiques, équipements, réputations, talents, informations JcJ du personnage';
$lang['cb_talents'] = 'Talents|Affichage de l\'arbre de talents actuel';
$lang['cb_spellbook'] = 'Livre de sorts|Affichage des sorts, actions et capacités passives disponibles';
$lang['cb_mailbox'] = 'Boîte aux lettres|Affichage du contenu de la boîte aux lettres';
$lang['cb_bags'] = 'Sacs|Affichage du contenu des sacs du personnage';
$lang['cb_bank'] = 'Banque|Affichage du contenu de la banque du personnage';
$lang['cb_quests'] = 'Quêtes|Affichage de la liste des quêtes en cours du personnage';
$lang['cb_recipes'] = 'Recettes|Affichages des objets que le personnage sait confectionner';

$lang['char_stats'] = 'Character Stats for: %1$s';
$lang['char_level_race_class'] = 'Level %1$s %2$s %3$s';
$lang['char_guildline'] = '%1$s of %2$s';
$lang['talents'] = 'Talents';

// Spellbook
$lang['spellbook'] = 'Grimoire';
$lang['no_spellbook'] = 'Pas de grimoire pour %1$s';

// Mailbox
$lang['mailbox'] = 'Boîte aux lettres';
$lang['maildateutc'] = 'Messages Capturés';
$lang['mail_item'] = 'Objet';
$lang['mail_sender'] = 'Expéditeur';
$lang['mail_subject'] = 'Sujet';
$lang['mail_expires'] = 'Messages expirés';
$lang['mail_money'] = 'Argent Inclus';
$lang['no_mail'] = 'Pas de courrier pour %1$s';

//skills
$lang['skilltypes'] = array(
	1 => 'Compétences de Classe',
	2 => 'Métiers',
	3 => 'Compétences secondaires',
	4 => 'Compétences d’armes',
	5 => 'Armures portables',
	6 => 'Langues'
);

// item slots, for missing items on characters
$lang['Head']          = 'Tête';
$lang['Neck']          = 'Cou';
$lang['Shoulder']      = 'Épaules';
$lang['Back']          = 'Dos';
$lang['Chest']         = 'Torse';
$lang['Shirt']         = 'Chemise';
$lang['Tabard']        = 'Tabard';
$lang['Wrist']         = 'Poignets';
$lang['MainHand']      = 'Main driote';
$lang['SecondaryHand'] = 'Main gauche';
$lang['Ranged']        = 'À distance';
$lang['Ammo']          = 'Munitions';
$lang['Hands']         = 'Mains';
$lang['Waist']         = 'Taille';
$lang['Legs']          = 'Jambes';
$lang['Feet']          = 'Pieds';
$lang['Finger0']       = 'Doigt 0';
$lang['Finger1']       = 'Doigt 1';
$lang['Trinket0']      = 'Bijou 0';
$lang['Trinket1']      = 'Bijou 1';

//tabs
$lang['tab1']='Personnage';
$lang['tab2']='Familier';
$lang['tab3']='Réputation';
$lang['tab4']='Compétences';
$lang['tab5']='JcJ';

$lang['strength_tooltip']='Augmente la puissance d\'attaque avec arme de mêlée.<br />Augmente le nombre de points de dégâts bloqués par le bouclier.';
$lang['agility_tooltip']= 'Augmente votre puissance d\'attaque avec arme de jet.<br />Améliore vos change de réaliser une attaque critique avec toutes les armes.<br />Augmente votre armure et votre change d\'esquiver les attaques.';
$lang['stamina_tooltip']= 'Augmente vos points de vie.';
$lang['intellect_tooltip']= 'Augmente vos points de mana et vos chances de réaliser une attaque critique aux moyens de sorts.<br />Augmente la vitesse d\'apprentissage des compétences en arme.';
$lang['spirit_tooltip']= 'Augmente la vitesse de régénération de vos points de vie et de mana.';
$lang['armor_tooltip']= 'Reduces physical damage taken by %1$s%%';

$lang['mainhand']='Main Hand';
$lang['offhand']='Off Hand';
$lang['ranged']='Ranged';
$lang['melee']='Melee';
$lang['spell']='Spell';

$lang['weapon_skill']='Skill';
$lang['weapon_skill_tooltip']='<span style="float:right;color:#fff;">%1$d</span>Weapon Skill<br /><span style="float:right;color:#fff;">%2$d</span>Weapon Skill Rating';
$lang['damage']='Damage';
$lang['damage_tooltip']='<span style="float:right;color:#fff;">%.2f</span>Attack speed (seconds):<br /><span style="float:right;color:#fff;">%d-%d</span>Damage:<br /><span style="float:right;color:#fff;">%.1f</span>Damage per second:<br />';
$lang['speed']='Speed';
$lang['atk_speed']='Attack Speed';
$lang['haste_tooltip']='Haste Rating ';

$lang['melee_att_power']='Puissance d\'attaque en mêlée';
$lang['melee_att_power_tooltip']='Increases damage with melee weapons by %.1f damage per second.';
$lang['ranged_att_power']='Puissance d\'attaque à distance';
$lang['ranged_att_power_tooltip']='Increases damage with ranged weapons by %.1f damage per second.';

$lang['weapon_hit_rating']='Hit Rating';
$lang['weapon_hit_rating_tooltip']='Increases your chance to hit an enemy.';
$lang['weapon_crit_rating']='Crit Rating';
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
$lang['holy']='Holy';
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
$lang['mana_regen_tooltip']='%1$d mana regenerated every 5 seconds while not casting<br />%2$d mana regenerated every 5 seconds while casting';

$lang['defense_rating']='Defense Rating ';
$lang['def_tooltip']='Increases your chance to %s';
$lang['resilience']='Resilience';

$lang['res_arcane']='Résistance des Arcanes';
$lang['res_arcane_tooltip']='Increases your ability to resist Arcane Resistance-based attacks, spells, and abilities.';
$lang['res_fire']='Résistance au feu';
$lang['res_fire_tooltip']='Increases your ability to resist Fire Resistance-based attacks, spells, and abilities.';
$lang['res_nature']='Résistance à la nature';
$lang['res_nature_tooltip']='Increases your ability to resist Nature Resistance-based attacks, spells, and abilities.';
$lang['res_frost']='Résistance au froid';
$lang['res_frost_tooltip']='Increases your ability to resist Frost Resistance-based attacks, spells, and abilities.';
$lang['res_shadow']='Résistance à l\'ombre';
$lang['res_shadow_tooltip']='Increases your ability to resist Shadow Resistance-based attacks, spells, and abilities.';

$lang['empty_equip']='No item equipped';
$lang['pointsspent']='Points Utilisés dans %1$s Talents';
// item_bonus locales //
$lang['item_bonuses_full'] = 'Bonus dûs à l\'équipement';
$lang['item_bonuses'] = 'Objets Bonus';
$lang['item_bonuses_preg_linesplits']='/(et|\/|&)/i';
$lang['item_bonuses_preg_main']='/(?!\d*\s(sec|min))(-{0,1}\d*\.{0,1}\d+)/i';

//
// patterns to standardize bonus string
$lang['item_bonuses_preg_patterns'] =
	array('/increases the block value of your shield by xx\.?/i',	//1
		  '/(?:increases|improves) (?:your )?(.+) by xx\.?/i',	//2
		  '/increases (damage) and (Healing) done by magical spells and effects by up to xx\.?$/i',	//3
		  '/(?:restores|\+)?\s?xx (mana|health) (?:per|every|regen).*$/i',	//4
		  '/increases damage done by (.+) and.*$/i',	//5
		  '/^\+?xx (Healing)(?: Spells)?\.?$/',	//6
		  '/^scope \(\+xx damage\)$/i',	//7
		  '/^\+?xx (?:shield )?block$/i',	//8
		 );
$lang['item_bonuses_preg_replacements'] =
	array('+XX Shield Block',  //1
		  '+XX $1', //2
		  '+XX Spell $1:+XX $2 Spells', //3
		  '+XX $1 Per 5 Seconds', //4
		  '+XX $1 Damage', //5
		  '+XX $1 Spells', //6
		  '+XX Ranged Damage (Scope)', //7
		  '+XX Shield Block', //8
		 );

/*
$lang['item_bonuses_remap']=
	array( // key must be lowercase!											// standardized bonus
		'+xx healing'                   												=> '+XX to Healing Spells',
		'+xx healing spells'															=> '+XX to Healing Spells',
		'increases healing done by spells and effects by up to xx.'						=> '+XX to Healing Spells',
		'restores xx health per 5 sec.'													=> '+XX Health per 5 Seconds',
		'+xx mana every 5 sec.'         												=> '+XX Mana per 5 Seconds',
		'+xx mana every 5 sec'															=> '+XX Mana per 5 Seconds',
		'+xx mana every 5 seconds'														=> '+XX Mana per 5 Seconds',
		'xx mana per 5 sec.'															=> '+XX Mana per 5 Seconds',
		'+xx mana regen'																=> '+XX Mana per 5 Seconds',
		'restores xx mana per 5 sec.'													=> '+XX Mana per 5 Seconds',
		'restores xx mana per 5 sec'													=> '+XX Mana per 5 Seconds',
		'+xx spell critical rating'														=> '+XX Spell Critical Strike Rating',
		'improves spell critical strike rating by xx.'									=> '+XX Spell Critical Strike Rating',
		'+xx spell damage'																=> '+XX Spell Damage and Heal',
		'+xx spell power'																=> '+XX Spell Damage and Heal',
		'increases damage and healing done by magical spells and effects by up to xx.'	=> '+XX Spell Damage and Heal',
		'improves spell hit rating by xx.'												=> '+XX Spell Hit Rating',
		'increases your spell hit rating by xx.'										=> '+XX Spell Hit Rating',
		'increases your dodge rating by xx.'											=> '+XX Dodge Rating',
		'increases defense rating by xx.'												=> '+XX Defense Rating',
		'increases your parry rating by xx.'											=> '+XX Parry Rating',
//		'xx block'																		=> '+XX Shield Block',
		'increases the block value of your shield by xx.'								=> '+XX Shield Block Rating',
		'increases your shield block rating by xx.'										=> '+XX Shield Block Rating',
		'improves hit rating by xx.'													=> '+XX Hit Rating',
		'improves your resilience rating by xx.'										=> '+XX Resilience Rating',
		'increases damage done by fire spells and effects by up to xx.'					=> '+XX Fire Spell Damage',
		'increases damage done by frost spells and effects by up to xx.'				=> '+XX Frost Spell Damage',
		'increases damage done by shadow spells and effects by up to xx.'				=> '+XX Shadow Spell Damage',
//
		'increases attack power by xx.'													=> '+XX Attack Power',
		'improves critical strike rating by xx.'										=> '+XX Critical Strike Rating',
		'increases your hit rating by xx.'												=> '+XX Hit Rating',
		'scope (+xx damage)'															=> '+XX Ranged Damage (Scope)'
		);
*/

$lang['item_bonuses_tabs'] = array(
		//key				//translate this
		'Totals' 			=> 'Totals',
		'Enchantment' 		=> 'Enchantments',
		'BaseStats' 		=> 'Base Stats',
		'Gems' 				=> 'Gems',
		'Effects' 			=> 'Passive',
		'Set' 				=> 'Sets',
		'Use' 				=> 'Use',
		'ChanceToProc' 		=> 'Procs',
		'TempEnchantment'	=> 'Temp Effects'
		);

// item_bonus end //
$lang['inactive'] = 'Inactive';

$lang['admin']['char_conf'] = 'Page de Personnage|Définir ce qui est affiché sur la page de personnage';
$lang['admin']['char_links'] = "Liens de la page de personnage|Afficher le lien rapide vers la page de personnage sur chaque page de personnage";
$lang['admin']['recipe_disp'] = "Affichage des recettes|Contrôle la façon dont la page des recettes est affichée à son chargement<br />Les listes peuvent être repliées ou ouvertes en cliquant sur l'en-tête du tableau<br /><br />&quot;show&quot; laissera apparaître les listes au chargement<br />&quot;collapse&quot; laissera les listes repliées";
$lang['admin']['show_tab2'] = "Familiers|Affichage des familiers<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_tab3'] = "Réputation|Affichage des réputations<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_tab4'] = "Compétences|Affichage des compétences<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_tab5'] = "JcJ|Affichage du JcJ<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_talents'] = "Talents|Affichage des talents<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_spellbook'] = "Grimoire|Affichage du grimoire<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_mail'] = "Courrier|Affichage du courrier<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_bags'] = "Sacs|Affichage des sacs<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_money'] = "Argent|Affichage de l'argent<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_bank'] = "Banque|Affichage du contenu de la banque<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_recipes'] = "Recettes|Affichage des recettes<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_quests'] = "Quêtes|Affichage des quêtes<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_bg'] = "Champs de bataille|Affichage des données de champs de bataille<br />Nécessite le téléchargement des données PvPLog<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_pvp'] = "Joueur contre joueur|Affichage des données joueur contre joueur<br />Nécessite le téléchargement des données PvPLog<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_duels'] = "Duel|Affichage des données de duel<br />Nécessite le téléchargement des données PvPLog<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";
$lang['admin']['show_item_bonuses'] = "Bonus d'équipement|Affichage des bonus d'équipement<br /><br />Le paramêtre est global et écrase le paramêtre par personnage";

$lang['admin']['char_pref'] = 'Préférences d\'affichage|Définir ce qui est affiché sur la page de personnage pour chacun d\'eux';