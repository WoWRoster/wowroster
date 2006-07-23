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

// ----[ Prevent Direct Access to this file ]-------------------
if( !defined('ROSTER_INCLUDED') )
{
	exit("You can't access this file directly!");
}


// ----[ Time Formatting ]----------------------------------

//  "%b %d %l%p"  Time format example - Jul 23 2PM

$roster_wordings['deDE']['timeformat']= '%d.%m. %k:%i'; // 23.07. 14:00
$roster_wordings['deDE']['phptimeformat']='d.m. g:i'; // 23.Jul. 14:00

// ----[ End Time Formatting ]------------------------------



// ----[ Page titles ]--------------------------------------

$roster_wordings['deDE']['pagetitle_members']='Members List';
$roster_wordings['deDE']['pagetitle_wealthhealth']='Wealth &amp; Health';
$roster_wordings['deDE']['pagetitle_basicstats']='Basic Stats';
$roster_wordings['deDE']['pagetitle_honor']='Honor';
$roster_wordings['deDE']['pagetitle_professions']='Professions';
$roster_wordings['deDE']['pagetitle_keys']='Keys';
$roster_wordings['deDE']['pagetitle_guildbank']='GuildBank';
$roster_wordings['deDE']['pagetitle_findteam']='Find Team';
$roster_wordings['deDE']['pagetitle_itemsearch']='Item Search';
$roster_wordings['deDE']['pagetitle_charmanagement']='Character Management';
$roster_wordings['deDE']['pagetitle_addons']='Addons';
$roster_wordings['deDE']['pagetitle_guilds']='Guilds';
$roster_wordings['deDE']['pagetitle_update']='Update';

// ----[ End Page Titles ]----------------------------------



// ----[ Tooltip strings ]----------------------------------

// These are for coloring lines in tooltips
// They must be exacly what is in the database
$roster_wordings['deDE']['tooltip_use']='Use';
$roster_wordings['deDE']['tooltip_requires']='Requires';
$roster_wordings['deDE']['tooltip_reinforced']='Reinforced';
$roster_wordings['deDE']['tooltip_soulbound']='Soulbound';
$roster_wordings['deDE']['tooltip_equip']='Equip';
$roster_wordings['deDE']['tooltip_equip_restores']='Equip: Restores';
$roster_wordings['deDE']['tooltip_equip_when']='Equip: When';
$roster_wordings['deDE']['tooltip_chance']='Chance';
$roster_wordings['deDE']['tooltip_enchant']='Enchant';
$roster_wordings['deDE']['tooltip_set']='Set';
$roster_wordings['deDE']['tooltip_rank']='Rank';
$roster_wordings['deDE']['tooltip_next_rank']='Next rank';
$roster_wordings['deDE']['tooltip_spell_damage']='Spell Damage';
$roster_wordings['deDE']['tooltip_healing_power']='Healing Power';
$roster_wordings['deDE']['tooltip_chance_hit']='Chance on hit:';
$roster_wordings['deDE']['tooltip_reinforced_armor']='Reinforced Armor';

// ----[ End Tooltip strings ]----------------------------------




// ----[ Character Page strings ]-------------------------------

// Strings for menubar
$roster_wordings['deDE']['charpage_menu_character']='Character';
$roster_wordings['deDE']['charpage_menu_talents']='Talents';
$roster_wordings['deDE']['charpage_menu_spellbook']='Spellbook';
$roster_wordings['deDE']['charpage_menu_bags']='Bags';
$roster_wordings['deDE']['charpage_menu_bank']='Bank';
$roster_wordings['deDE']['charpage_menu_inventory']='Inventory';
$roster_wordings['deDE']['charpage_menu_quests']='Quest Log';
$roster_wordings['deDE']['charpage_menu_professions']='Professions';
$roster_wordings['deDE']['charpage_menu_pvplog']='PvP Log';
$roster_wordings['deDE']['charpage_menu_bglog']='Battleground Log';
$roster_wordings['deDE']['charpage_menu_duellog']='Duel Log';
// End Strings for menubar


// Other strings, these should be for display only
$roster_wordings['deDE']['charpage_pagetitle']='Character Info';
$roster_wordings['deDE']['charpage_level']='Level';
$roster_wordings['deDE']['charpage_health']='Health';
$roster_wordings['deDE']['charpage_mana']='Mana';
$roster_wordings['deDE']['charpage_dodge']='Dodge';
$roster_wordings['deDE']['charpage_parry']='Parry';
$roster_wordings['deDE']['charpage_block']='Block';
$roster_wordings['deDE']['charpage_damage_mitigation']='Damage Mitigation';
$roster_wordings['deDE']['charpage_crit_rate']='Crit Rate';
$roster_wordings['deDE']['charpage_experience']='Experience';
$roster_wordings['deDE']['charpage_max_xp']='Max XP';
$roster_wordings['deDE']['charpage_faction']='Faction';
$roster_wordings['deDE']['charpage_standing']='Standing';
$roster_wordings['deDE']['charpage_at_war']='At War';
$roster_wordings['deDE']['charpage_rank']='Rank';
$roster_wordings['deDE']['charpage_training_points']='Training Points';
$roster_wordings['deDE']['charpage_attack']='Attack';
$roster_wordings['deDE']['charpage_defense']='Defense';
// End Other strings


// Tabs
$roster_wordings['deDE']['charpage_tab1']='Character';
$roster_wordings['deDE']['charpage_tab2']='Pet';
$roster_wordings['deDE']['charpage_tab3']='Reputation';
$roster_wordings['deDE']['charpage_tab4']='Skills';
$roster_wordings['deDE']['charpage_tab5']='Honor';
// End Tabs


// Honor Tab Strings
$roster_wordings['deDE']['charpage_honor_today']='Today';
$roster_wordings['deDE']['charpage_honor_yesterday']='Yesterday';
$roster_wordings['deDE']['charpage_honor_this_week']='This Week';
$roster_wordings['deDE']['charpage_honor_last_week']='Last Week';
$roster_wordings['deDE']['charpage_honor_lifetime']='Lifetime';
$roster_wordings['deDE']['charpage_honor_honor_kills']='Honorable Kills';
$roster_wordings['deDE']['charpage_honor_dishonor_kills']='Dishonorable Kills';
$roster_wordings['deDE']['charpage_honor_honor']='Honor';
$roster_wordings['deDE']['charpage_honor_standing']='Standing';
$roster_wordings['deDE']['charpage_honor_highest_rank']='Highest Rank';
// End Honor Tab Strings


// Stats
$roster_wordings['deDE']['charpage_strength']='Strength';
$roster_wordings['deDE']['charpage_strength_tooltip']='Increases your attack power with melee Weapons.<br />Increases the amount of damage you can block with a shield.';
$roster_wordings['deDE']['charpage_agility']='Agility';
$roster_wordings['deDE']['charpage_agility_tooltip']= 'Increases your attack power with ranged weapons.<br />Improves your chance to score a critical hit with all weapons.<br />Increases your armor and your chance to dodge attacks.';
$roster_wordings['deDE']['charpage_stamina']='Stamina';
$roster_wordings['deDE']['charpage_stamina_tooltip']= 'Increases your health points.';
$roster_wordings['deDE']['charpage_intellect']='Intellect';
$roster_wordings['deDE']['charpage_intellect_tooltip']= 'Increases your mana points and your chance to score a critical hit with spells.<br />Increases the rate at which you improve weapon skills.';
$roster_wordings['deDE']['charpage_spirit']='Spirit';
$roster_wordings['deDE']['charpage_spirit_tooltip']= 'Increases your health and mana regeneration rates.';
$roster_wordings['deDE']['charpage_armor']='Armor';
$roster_wordings['deDE']['charpage_defense']='Defense';
$roster_wordings['deDE']['charpage_defense_tooltip']= '';
// NOTE: In "charpage_armor_tooltip", *LEVEL* and *MITIGATION* are replaced with their real values
$roster_wordings['deDE']['charpage_armor_tooltip']= 'Decreases the amount of damage you take from physical attacks.<br />The amount of reduction is influenced by the level of your attacker.<br />Damage reduction against a level *LEVEL* attacker: *MITIGATION*%.';
// NOTE: This one doesn't have the additional *LEVEL* and *MITIGATION* info
$roster_wordings['deDE']['charpage_armor_tooltip_less']= 'Decreases the amount of damage you take from physical attacks.<br />The amount of reduction is influenced by the level of your attacker.';
// End Stats


// Warlock pet names for icon displaying
$roster_wordings['deDE']['Imp']='Imp';
$roster_wordings['deDE']['Voidwalker']='Voidwalker';
$roster_wordings['deDE']['Succubus']='Succubus';
$roster_wordings['deDE']['Felhunter']='Felhunter';
$roster_wordings['deDE']['Infernal']='Infernal';
// Warlock pet names for icon displaying

// Attack strings
$roster_wordings['deDE']['charpage_power']='Power';
$roster_wordings['deDE']['charpage_damage']='Damage';
$roster_wordings['deDE']['charpage_melee_att']='Melee Attack';
$roster_wordings['deDE']['charpage_melee_att_power']='Melee Attack Power';
$roster_wordings['deDE']['charpage_range_att']='Ranged Attack';
$roster_wordings['deDE']['charpage_range_att_power']='Ranged Attack Power';
$roster_wordings['deDE']['charpage_melee_rating']='Melee Attack Rating';
$roster_wordings['deDE']['charpage_melee_rating_tooltip']='Your attack rating affects your chance to hit a target and is based on the weapon skill of the weapon you are currently weilding.';
$roster_wordings['deDE']['charpage_range_rating']='Ranged Attack Rating';
$roster_wordings['deDE']['charpage_range_rating_tooltip']='Your attack rating affects your chance to hit a target and is based on the weapon skill of the weapon you are currently weilding.';
// End Attack Strings


// Resistances
$roster_wordings['deDE']['charpage_res_fire']='Fire Resistance';
$roster_wordings['deDE']['charpage_res_fire_tooltip']='Increases your ability to resist Fire Resistance-based attacks, spells, and abilities.';
$roster_wordings['deDE']['charpage_res_nature']='Nature Resistance';
$roster_wordings['deDE']['charpage_res_nature_tooltip']='Increases your ability to resist Nature Resistance-based attacks, spells, and abilities.';
$roster_wordings['deDE']['charpage_res_arcane']='Arcane Resistance';
$roster_wordings['deDE']['charpage_res_arcane_tooltip']='Increases your ability to resist Arcane Resistance-based attacks, spells, and abilities.';
$roster_wordings['deDE']['charpage_res_frost']='Frost Resistance';
$roster_wordings['deDE']['charpage_res_frost_tooltip']='Increases your ability to resist Frost Resistance-based attacks, spells, and abilities.';
$roster_wordings['deDE']['charpage_res_shadow']='Shadow Resistance';
$roster_wordings['deDE']['charpage_res_shadow_tooltip']='Increases your ability to resist Shadow Resistance-based attacks, spells, and abilities.';
// End Resistances


// Reputation Tab
$roster_wordings['deDE']['charpage_exalted']='Ehrfürchtig';
$roster_wordings['deDE']['charpage_revered']='Respektvoll';
$roster_wordings['deDE']['charpage_honored']='Wohlwollend';
$roster_wordings['deDE']['charpage_friendly']='Freundlich';
$roster_wordings['deDE']['charpage_neutral']='Neutral';
$roster_wordings['deDE']['charpage_unfriendly']='Unfreundlich';
$roster_wordings['deDE']['charpage_hostile']='Feindselig';
$roster_wordings['deDE']['charpage_hated']='Hasserfüllt';
// End Reputation Tab

// ----[ End Charater Page strings ]----------------------------




// ----[ Upload Page localization ]-------------------------

$roster_wordings['deDE']['uploadpage']['automationenabled']='Automation detected. Reducing HTML output.';

// ----[ End Upload Page localization ]-------------------------




// ----[ Credit Page localization ]-------------------------

// Link in the footer to the credits page
$roster_wordings['deDE']['credit_page_link'] = 'Additional Credits';


// This displays at the top of the credits page
// "\n" (lines) should be converted to "\n<br />" in the display
$roster_wordings['deDE']['credit_page_top'] = 'Props to <a href="http://www.poseidonguild.com/char.php?name=Celandro&amp;server=Cenarius">Celandro</a>, <a href="http://www.movieobsession.com/wow/parser/char.php?name=Grieve&amp;server=Bleeding Hollow">Paleblackness</a>, Pytte, and <a href="http://www.witchhunters.net/wowinfonew/char.php?name=Rubsi&amp;server=Deathwing">Rubricsinger</a> for the original code used for this site.
Special Thanks to calvin from <a href="http://www.rpgoutfitter.com">rpgoutfitter</a> for sharing his <a href="http://www.rpgoutfitter.com/downloads/wowinterface.cfm">icons</a>
Thanks to all the coders who have contributed there codes in bug fixes and testing of the roster.

Special Thanks to the DEVs of Roster for helping to build and maintain the package';


// This is an array of the dev team
$roster_wordings['deDE']['credit_page_devs'] = array(

		'active'=>array(
			array(	"name"=>	"AnthonyB",
					"info"=>	"Site Admin\nWoW Roster Coordinator"),
			array(	"name"=>	"Matt Miller",
					"info"=>	"Gimpy DEV\nAuthor of UniAdmin and Uniuploader"),
			array(	"name"=>	"Calvin",
					"info"=>	"Gimpy DEV\nAuthor of CharacterProfiler and GuildProfiler"),
			array(	"name"=>	"Mordon",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Nemm",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Nerk01",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Nostrademous",
					"info"=>	"WoWRoster Dev\nPvPLog DEV"),
			array(	"name"=>	"peperone",
					"info"=>	"WoWRoster Dev\nGerman Translator"),
			array(	"name"=>	"RossiRat",
					"info"=>	"WoWRoster Dev\nGerman Translator"),
			array(	"name"=>	"seleleth",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Sphinx",
					"info"=>	"WoWRoster Dev\nGerman Translator"),
			array(	"name"=>	"Swipe",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Vaccafoeda",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Vich",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Zanix",
					"info"=>	"WoWRoster Dev\nSigGen Addon Author"),
		),

		'inactive'=>array(
			array(	'name'=>'dsrbo',
					'info'=>'Retired DEV, Retired PvPLog DEV'),
			array(	'name'=>'Guppy',
					'info'=>'Retired DEV')
		)
	);


// This is displayed at the bottom of every page
// You MUST keep this in agreement with the WoWRoster Licence
$roster_wordings['deDE']['credit_page_bottom'] = 'World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries.
All other trademarks are the property of their respective owners
<a href="?p=credits">'.$roster_wordings['deDE']['credit_page_link'].'</a>

<a href="http://www.wowroster.net" target="_blank"><img style="border:0;width:80px;height:15px" src="'.$roster_conf['imagepath'].'/wowroster_small.gif" alt="WoWRoster Home" /></a>';

// ----[ End Credit Page localization ]---------------------



// ----[ Translation Tables ]-------------------------------
// $roster_translate['locale']['english word']='localizedword';

$roster_translate['deDE']['Night Elf']='Nachtelf';
$roster_translate['deDE']['Dwarf']='Zwerg';
$roster_translate['deDE']['Gnome']='Gnom';
$roster_translate['deDE']['Human']='Mensch';
$roster_translate['deDE']['Orc']='Orc';
$roster_translate['deDE']['Undead']='Untoter';
$roster_translate['deDE']['Troll']='Troll';
$roster_translate['deDE']['Tauren']='Taure';
$roster_translate['deDE']['Male']='Männlich';
$roster_translate['deDE']['Female']='Weiblich';

$roster_translate['deDE']['Druid']='Druide';
$roster_translate['deDE']['Hunter']='Jäger';
$roster_translate['deDE']['Mage']='Magier';
$roster_translate['deDE']['Paladin']='Paladin';
$roster_translate['deDE']['Priest']='Priester';
$roster_translate['deDE']['Rogue']='Schurke';
$roster_translate['deDE']['Shaman']='Schamane';
$roster_translate['deDE']['Warlock']='Hexenmeister';
$roster_translate['deDE']['Warrior']='Krieger';

$roster_translate['deDE']['Alliance']='Alliance';
$roster_translate['deDE']['Horde']='Horde';

// ----[ End Translation Tables ]---------------------------



?>