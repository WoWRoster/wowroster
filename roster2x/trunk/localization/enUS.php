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

$roster_wordings['enUS']['timeformat']='%b %d %l:%i %p'; // Jul 23 2:19 PM
$roster_wordings['enUS']['phptimeformat']='D jS M, g:ia'; // Mon 23rd Jul, 2:19pm

// ----[ End Time Formatting ]------------------------------



// ----[ Page titles ]--------------------------------------

$roster_wordings['enUS']['pagetitle_members']='Members List';
$roster_wordings['enUS']['pagetitle_wealthhealth']='Wealth &amp; Health';
$roster_wordings['enUS']['pagetitle_basicstats']='Basic Stats';
$roster_wordings['enUS']['pagetitle_honor']='Honor';
$roster_wordings['enUS']['pagetitle_professions']='Professions';
$roster_wordings['enUS']['pagetitle_keys']='Keys';
$roster_wordings['enUS']['pagetitle_guildbank']='GuildBank';
$roster_wordings['enUS']['pagetitle_findteam']='Find Team';
$roster_wordings['enUS']['pagetitle_itemsearch']='Item Search';
$roster_wordings['enUS']['pagetitle_charmanagement']='Character Management';
$roster_wordings['enUS']['pagetitle_addons']='Addons';
$roster_wordings['enUS']['pagetitle_guilds']='Guilds';
$roster_wordings['enUS']['pagetitle_update']='Update';

// ----[ End Page Titles ]----------------------------------



// ----[ Tooltip strings ]----------------------------------

// These are for coloring lines in tooltips
// They must be exacly what is in the database
$roster_wordings['enUS']['tooltip_use']='Use';
$roster_wordings['enUS']['tooltip_requires']='Requires';
$roster_wordings['enUS']['tooltip_reinforced']='Reinforced';
$roster_wordings['enUS']['tooltip_soulbound']='Soulbound';
$roster_wordings['enUS']['tooltip_equip']='Equip';
$roster_wordings['enUS']['tooltip_equip_restores']='Equip: Restores';
$roster_wordings['enUS']['tooltip_equip_when']='Equip: When';
$roster_wordings['enUS']['tooltip_chance']='Chance';
$roster_wordings['enUS']['tooltip_enchant']='Enchant';
$roster_wordings['enUS']['tooltip_set']='Set';
$roster_wordings['enUS']['tooltip_rank']='Rank';
$roster_wordings['enUS']['tooltip_next_rank']='Next rank';
$roster_wordings['enUS']['tooltip_spell_damage']='Spell Damage';
$roster_wordings['enUS']['tooltip_healing_power']='Healing Power';
$roster_wordings['enUS']['tooltip_chance_hit']='Chance on hit:';
$roster_wordings['enUS']['tooltip_reinforced_armor']='Reinforced Armor';

// ----[ End Tooltip strings ]----------------------------------




// ----[ Character Page strings ]-------------------------------

// Strings for menubar
$roster_wordings['enUS']['charpage_menu_character']='Character';
$roster_wordings['enUS']['charpage_menu_talents']='Talents';
$roster_wordings['enUS']['charpage_menu_spellbook']='Spellbook';
$roster_wordings['enUS']['charpage_menu_bags']='Bags';
$roster_wordings['enUS']['charpage_menu_bank']='Bank';
$roster_wordings['enUS']['charpage_menu_inventory']='Inventory';
$roster_wordings['enUS']['charpage_menu_quests']='Quest Log';
$roster_wordings['enUS']['charpage_menu_professions']='Professions';
$roster_wordings['enUS']['charpage_menu_pvplog']='PvP Log';
$roster_wordings['enUS']['charpage_menu_bglog']='Battleground Log';
$roster_wordings['enUS']['charpage_menu_duellog']='Duel Log';
// End Strings for menubar


// Other strings, these should be for display only
$roster_wordings['enUS']['charpage_pagetitle']='Character Info';
$roster_wordings['enUS']['charpage_level']='Level';
$roster_wordings['enUS']['charpage_health']='Health';
$roster_wordings['enUS']['charpage_mana']='Mana';
$roster_wordings['enUS']['charpage_dodge']='Dodge';
$roster_wordings['enUS']['charpage_parry']='Parry';
$roster_wordings['enUS']['charpage_block']='Block';
$roster_wordings['enUS']['charpage_damage_mitigation']='Damage Mitigation';
$roster_wordings['enUS']['charpage_crit_rate']='Crit Rate';
$roster_wordings['enUS']['charpage_experience']='Experience';
$roster_wordings['enUS']['charpage_max_xp']='Max XP';
$roster_wordings['enUS']['charpage_faction']='Faction';
$roster_wordings['enUS']['charpage_standing']='Standing';
$roster_wordings['enUS']['charpage_at_war']='At War';
$roster_wordings['enUS']['charpage_rank']='Rank';
$roster_wordings['enUS']['charpage_training_points']='Training Points';
$roster_wordings['enUS']['charpage_attack']='Attack';
$roster_wordings['enUS']['charpage_defense']='Defense';
// End Other strings


// Tabs
$roster_wordings['enUS']['charpage_tab1']='Character';
$roster_wordings['enUS']['charpage_tab2']='Pet';
$roster_wordings['enUS']['charpage_tab3']='Reputation';
$roster_wordings['enUS']['charpage_tab4']='Skills';
$roster_wordings['enUS']['charpage_tab5']='Honor';
// End Tabs


// Honor Tab Strings
$roster_wordings['enUS']['charpage_honor_today']='Today';
$roster_wordings['enUS']['charpage_honor_yesterday']='Yesterday';
$roster_wordings['enUS']['charpage_honor_this_week']='This Week';
$roster_wordings['enUS']['charpage_honor_last_week']='Last Week';
$roster_wordings['enUS']['charpage_honor_lifetime']='Lifetime';
$roster_wordings['enUS']['charpage_honor_honor_kills']='Honorable Kills';
$roster_wordings['enUS']['charpage_honor_dishonor_kills']='Dishonorable Kills';
$roster_wordings['enUS']['charpage_honor_honor']='Honor';
$roster_wordings['enUS']['charpage_honor_standing']='Standing';
$roster_wordings['enUS']['charpage_honor_highest_rank']='Highest Rank';
// End Honor Tab Strings


// Stats
$roster_wordings['enUS']['charpage_strength']='Strength';
$roster_wordings['enUS']['charpage_strength_tooltip']='Increases your attack power with melee Weapons.<br />Increases the amount of damage you can block with a shield.';
$roster_wordings['enUS']['charpage_agility']='Agility';
$roster_wordings['enUS']['charpage_agility_tooltip']= 'Increases your attack power with ranged weapons.<br />Improves your chance to score a critical hit with all weapons.<br />Increases your armor and your chance to dodge attacks.';
$roster_wordings['enUS']['charpage_stamina']='Stamina';
$roster_wordings['enUS']['charpage_stamina_tooltip']= 'Increases your health points.';
$roster_wordings['enUS']['charpage_intellect']='Intellect';
$roster_wordings['enUS']['charpage_intellect_tooltip']= 'Increases your mana points and your chance to score a critical hit with spells.<br />Increases the rate at which you improve weapon skills.';
$roster_wordings['enUS']['charpage_spirit']='Spirit';
$roster_wordings['enUS']['charpage_spirit_tooltip']= 'Increases your health and mana regeneration rates.';
$roster_wordings['enUS']['charpage_armor']='Armor';
$roster_wordings['enUS']['charpage_defense']='Defense';
$roster_wordings['enUS']['charpage_defense_tooltip']= '';
// NOTE: In "charpage_armor_tooltip", *LEVEL* and *MITIGATION* are replaced with their real values
$roster_wordings['enUS']['charpage_armor_tooltip']= 'Decreases the amount of damage you take from physical attacks.<br />The amount of reduction is influenced by the level of your attacker.<br />Damage reduction against a level *LEVEL* attacker: *MITIGATION*%.';
// NOTE: This one doesn't have the additional *LEVEL* and *MITIGATION* info
$roster_wordings['enUS']['charpage_armor_tooltip_less']= 'Decreases the amount of damage you take from physical attacks.<br />The amount of reduction is influenced by the level of your attacker.';
// End Stats


// Warlock pet names for icon displaying
$roster_wordings['enUS']['Imp']='Imp';
$roster_wordings['enUS']['Voidwalker']='Voidwalker';
$roster_wordings['enUS']['Succubus']='Succubus';
$roster_wordings['enUS']['Felhunter']='Felhunter';
$roster_wordings['enUS']['Infernal']='Infernal';
// Warlock pet names for icon displaying

// Attack strings
$roster_wordings['enUS']['charpage_power']='Power';
$roster_wordings['enUS']['charpage_damage']='Damage';
$roster_wordings['enUS']['charpage_melee_att']='Melee Attack';
$roster_wordings['enUS']['charpage_melee_att_power']='Melee Attack Power';
$roster_wordings['enUS']['charpage_range_att']='Ranged Attack';
$roster_wordings['enUS']['charpage_range_att_power']='Ranged Attack Power';
$roster_wordings['enUS']['charpage_melee_rating']='Melee Attack Rating';
$roster_wordings['enUS']['charpage_melee_rating_tooltip']='Your attack rating affects your chance to hit a target and is based on the weapon skill of the weapon you are currently weilding.';
$roster_wordings['enUS']['charpage_range_rating']='Ranged Attack Rating';
$roster_wordings['enUS']['charpage_range_rating_tooltip']='Your attack rating affects your chance to hit a target and is based on the weapon skill of the weapon you are currently weilding.';
// End Attack Strings


// Resistances
$roster_wordings['enUS']['charpage_res_fire']='Fire Resistance';
$roster_wordings['enUS']['charpage_res_fire_tooltip']='Increases your ability to resist Fire Resistance-based attacks, spells, and abilities.';
$roster_wordings['enUS']['charpage_res_nature']='Nature Resistance';
$roster_wordings['enUS']['charpage_res_nature_tooltip']='Increases your ability to resist Nature Resistance-based attacks, spells, and abilities.';
$roster_wordings['enUS']['charpage_res_arcane']='Arcane Resistance';
$roster_wordings['enUS']['charpage_res_arcane_tooltip']='Increases your ability to resist Arcane Resistance-based attacks, spells, and abilities.';
$roster_wordings['enUS']['charpage_res_frost']='Frost Resistance';
$roster_wordings['enUS']['charpage_res_frost_tooltip']='Increases your ability to resist Frost Resistance-based attacks, spells, and abilities.';
$roster_wordings['enUS']['charpage_res_shadow']='Shadow Resistance';
$roster_wordings['enUS']['charpage_res_shadow_tooltip']='Increases your ability to resist Shadow Resistance-based attacks, spells, and abilities.';
// End Resistances


// Reputation Tab
$roster_wordings['enUS']['charpage_exalted']='Exalted';
$roster_wordings['enUS']['charpage_revered']='Revered';
$roster_wordings['enUS']['charpage_honored']='Honored';
$roster_wordings['enUS']['charpage_friendly']='Friendly';
$roster_wordings['enUS']['charpage_neutral']='Neutral';
$roster_wordings['enUS']['charpage_unfriendly']='Unfriendly';
$roster_wordings['enUS']['charpage_hostile']='Hostile';
$roster_wordings['enUS']['charpage_hated']='Hated';
// End Reputation Tab

// ----[ End Charater Page strings ]----------------------------




// ----[ Upload Page localization ]-------------------------

$roster_wordings['enUS']['uploadpage']['automationenabled']='Automation detected. Reducing HTML output.';

// ----[ End Upload Page localization ]-------------------------




// ----[ Credit Page localization ]-------------------------

// Link in the footer to the credits page
$roster_wordings['enUS']['credit_page_link'] = 'Additional Credits';


// This displays at the top of the credits page
// "\n" (lines) should be converted to "\n<br />" in the display
$roster_wordings['enUS']['credit_page_top'] = 'Props to <a href="http://www.poseidonguild.com/char.php?name=Celandro&amp;server=Cenarius">Celandro</a>, <a href="http://www.movieobsession.com/wow/parser/char.php?name=Grieve&amp;server=Bleeding Hollow">Paleblackness</a>, Pytte, and <a href="http://www.witchhunters.net/wowinfonew/char.php?name=Rubsi&amp;server=Deathwing">Rubricsinger</a> for the original code used for this site.
Special Thanks to calvin from <a href="http://www.rpgoutfitter.com">rpgoutfitter</a> for sharing his <a href="http://www.rpgoutfitter.com/downloads/wowinterface.cfm">icons</a>
Thanks to all the coders who have contributed there codes in bug fixes and testing of the roster.

Special Thanks to the DEVs of Roster for helping to build and maintain the package';


// This is an array of the dev team
$roster_wordings['enUS']['credit_page_devs'] = array(

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
$roster_wordings['enUS']['credit_page_bottom'] = 'World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries.
All other trademarks are the property of their respective owners
<a href="?p=credits">'.$roster_wordings['enUS']['credit_page_link'].'</a>

<a href="http://www.wowroster.net" target="_new"><img style="border:0;width:80px;height:15px" src="'.$roster_conf['imagepath'].'/wowroster_small.gif" alt="WoWRoster Home" /></a>';

// ----[ End Credit Page localization ]---------------------



// ----[ Translation Tables ]-------------------------------
// $roster_translate['locale']['english word']='localizedword';

$roster_translate['enUS']['Night Elf']='Night Elf';
$roster_translate['enUS']['Dwarf']='Dwarf';
$roster_translate['enUS']['Gnome']='Gnome';
$roster_translate['enUS']['Human']='Human';
$roster_translate['enUS']['Orc']='Orc';
$roster_translate['enUS']['Undead']='Undead';
$roster_translate['enUS']['Troll']='Troll';
$roster_translate['enUS']['Tauren']='Tauren';
$roster_translate['enUS']['Male']='Male';
$roster_translate['enUS']['Female']='Female';

$roster_translate['enUS']['Druid']='Druid';
$roster_translate['enUS']['Hunter']='Hunter';
$roster_translate['enUS']['Mage']='Mage';
$roster_translate['enUS']['Paladin']='Paladin';
$roster_translate['enUS']['Priest']='Priest';
$roster_translate['enUS']['Rogue']='Rogue';
$roster_translate['enUS']['Shaman']='Shaman';
$roster_translate['enUS']['Warlock']='Warlock';
$roster_translate['enUS']['Warrior']='Warrior';

$roster_translate['enUS']['Alliance']='Alliance';
$roster_translate['enUS']['Horde']='Horde';

// ----[ End Translation Tables ]---------------------------



?>