<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * enUS Locale
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
 * @subpackage Locale
*/

$lang['char_info'] = 'Character Information';
$lang['char_info_desc'] = 'Displays info about characters uploaded to Roster';

// Menu Buttons
$lang['cb_character'] = 'Character|Shows character stats, equipment, reputation, skills, and pvp info';
$lang['cb_talents'] = 'Talents|Shows current talent build';
$lang['cb_spellbook'] = 'Spellbook|Shows available spells, actions, and passive abilities';
$lang['cb_mailbox'] = 'Mailbox|Shows the contents of the mailbox';
$lang['cb_bags'] = 'Bags|Shows the contents of this character\'s bags';
$lang['cb_bank'] = 'Bank|Shows the contents of this character\'s bank';
$lang['cb_quests'] = 'Quests|A list of quest this character is currently on';
$lang['cb_recipes'] = 'Recipes|Shows what items this character can make';

$lang['char_stats'] = 'Character Stats for: %1$s';
$lang['char_level_race_class'] = 'Level %1$s %2$s %3$s';
$lang['char_guildline'] = '%1$s of %2$s';
$lang['talents'] = 'Talents';

// Spellbook
$lang['spellbook'] = 'Spellbook';
$lang['no_spellbook'] = 'No Spellbook for %1$s';

// Mailbox
$lang['mailbox'] = 'Mailbox';
$lang['maildateutc'] = 'Mail Captured';
$lang['mail_item'] = 'Item';
$lang['mail_sender'] = 'Sender';
$lang['mail_subject'] = 'Subject';
$lang['mail_expires'] = 'Mail Expires';
$lang['mail_money'] = 'Money Included';
$lang['no_mail'] = 'No Mail for %1$s';

// Quests
$lang['no_quests'] = '%1$s has no Quests';

//skills
$lang['skilltypes'] = array(
	1 => 'Class Skills',
	2 => 'Professions',
	3 => 'Secondary Skills',
	4 => 'Weapon Skills',
	5 => 'Armor Proficiencies',
	6 => 'Languages'
);

// item slots, for missing items on characters
$lang['Head']          = 'Head';
$lang['Neck']          = 'Neck';
$lang['Shoulder']      = 'Shoulder';
$lang['Back']          = 'Back';
$lang['Chest']         = 'Chest';
$lang['Shirt']         = 'Shirt';
$lang['Tabard']        = 'Tabard';
$lang['Wrist']         = 'Wrist';
$lang['MainHand']      = 'Main Hand';
$lang['SecondaryHand'] = 'Secondary Hand';
$lang['Ranged']        = 'Ranged';
$lang['Ammo']          = 'Ammo';
$lang['Hands']         = 'Hands';
$lang['Waist']         = 'Waist';
$lang['Legs']          = 'Legs';
$lang['Feet']          = 'Feet';
$lang['Finger0']       = 'Finger 0';
$lang['Finger1']       = 'Finger 1';
$lang['Trinket0']      = 'Trinket 0';
$lang['Trinket1']      = 'Trinket 1';

//tabs
$lang['tab1']='Character';
$lang['tab2']='Pet';
$lang['tab3']='Reputation';
$lang['tab4']='Skills';
$lang['tab5']='PvP';

$lang['strength_tooltip']='Increases your attack power with melee Weapons.<br />Increases the amount of damage you can block with a shield.';
$lang['agility_tooltip']= 'Increases your attack power with ranged weapons.<br />Improves your chance to score a critcal hit with all weapons.<br />Increases your armor and your chance to dodge attacks.';
$lang['stamina_tooltip']= 'Increases your health points.';
$lang['intellect_tooltip']= 'Increases your mana points and your chance to score a critical hit with spells.<br />Increases the rate at which you improve weapon skills.';
$lang['spirit_tooltip']= 'Increases your health and mana regeneration rates.';
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

$lang['melee_att_power']='Melee Attack Power';
$lang['melee_att_power_tooltip']='Increases damage with melee weapons by %.1f damage per second.';
$lang['ranged_att_power']='Ranged Attack Power';
$lang['ranged_att_power_tooltip']='Increases damage with ranged weapons by %.1f damage per second.';

$lang['weapon_hit_rating']='Hit Rating';
$lang['weapon_hit_rating_tooltip']='Increases your chance to hit an enemy.';
$lang['weapon_expertise']='Expertise';
$lang['weapon_expertise_tooltip']='Reduces chance to be dodged or parried.';
$lang['weapon_crit_rating']='Crit Rating';
$lang['weapon_crit_rating_tooltip']='Critical strike chance %.2f%%.';

$lang['damage']='Damage';
$lang['energy']='Energy';
$lang['rage']='Rage';
$lang['power']='Power';

$lang['melee_rating']='Melee Attack Rating';
$lang['melee_rating_tooltip']='Your attack rating affects your chance to hit a target<br />And is based on the weapon skill of the weapon you are currently holding.';
$lang['range_rating']='Ranged Attack Rating';
$lang['range_rating_tooltip']='Your attack rating affects your chance to hit a target<br />And is based on the weapon skill of the weapon you are currently weilding.';

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

$lang['res_arcane']='Arcane Resistance';
$lang['res_arcane_tooltip']='Increases your ability to resist Arcane Resistance-based attacks, spells, and abilities.';
$lang['res_fire']='Fire Resistance';
$lang['res_fire_tooltip']='Increases your ability to resist Fire Resistance-based attacks, spells, and abilities.';
$lang['res_nature']='Nature Resistance';
$lang['res_nature_tooltip']='Increases your ability to resist Nature Resistance-based attacks, spells, and abilities.';
$lang['res_frost']='Frost Resistance';
$lang['res_frost_tooltip']='Increases your ability to resist Frost Resistance-based attacks, spells, and abilities.';
$lang['res_shadow']='Shadow Resistance';
$lang['res_shadow_tooltip']='Increases your ability to resist Shadow Resistance-based attacks, spells, and abilities.';

$lang['empty_equip']='No item equipped';
$lang['pointsspent']='Points Spent in %1$s Talents';
$lang['export_url']='http://www.worldofwarcraft.com/info/classes/';
$lang['no_talents']='No Talents for %1$s';

// item_bonus locales //
$lang['item_bonuses_full'] = 'Bonuses For Equipped Items';
$lang['item_bonuses'] = 'Item Bonuses';
$lang['item_bonuses_preg_linesplits']='/(and|\/|&)/';
$lang['item_bonuses_preg_main']='/(?!\d*\s(sec|min))(-{0,1}\d*\.{0,1}\d+)/i';

//
// patterns to standardize bonus string
$lang['item_bonuses_preg_patterns'] =
	array('/increases the block value of your shield by XX\.?/i',	//1
		  '/(?:increases|improves) (?:your )?(.+) by XX\.?/i',	//2
		  '/increases (Damage) and (Healing) done by magical spells and effects by up to XX\.?$/i',	//3
		  '/Increases Healing Done By Spells And Effects By Up To XX\.?/i', //4
		  '/Increases Damage Done By Spells And Effects By Up To XX\.?/i', //5'
		  '/(?:restores|\+)?\s?XX (mana|health) (?:per|every|regen).*$/i',	//6
		  '/increases damage done by (.+) and.*$/i',	//7
		  '/^\+?XX (Healing)(?: Spells)?\.?$/',	//8
		  '/^\+XX Spell Damage and Healing/i', //8.5
		  '/^scope \(\+XX damage\)$/i',	//9
		  '/^\+?XX (?:shield )?block$/i',	//10
		  '/^\+XX All Stats/i', //11
		  '/^\+XX All Resistances/i', //12
		  '/^\+XX Spell Critical Rating/i', //13
		 );
$lang['item_bonuses_preg_replacements'] =
	array('+XX Shield Block',  //1
		  '+XX $1', //2
		  '+XX Spell $1:+XX $2 Spells', //3
		  '+XX Healing Spells',	// 4
		  '+XX Damage Spells', // 5
		  '+XX $1 Per 5 Seconds', //6
		  '+XX $1 Damage', //7
		  '+XX $1 Spells', //8
		  '+XX Spell Damage:+XX Healing Spells', //8.5
		  '+XX Ranged Damage (Scope)', //9
		  '+XX Shield Block', //10
		  '+XX Strength:+XX Agility:+XX Stamina:+XX Intellect:+XX Spirit', //11
		  '+XX Arcane Resistance:+XX Fire Resistance:+XX Nature Resistance:+XX Frost Resistance:+XX Shadow Resistance', //12
		  '+XX Spell Critical Strike Rating', //13
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
		'Enchantment' 		=> 'Enchants',
		'BaseStats' 		=> 'Base',
		'Gems' 				=> 'Gems',
		'Effects' 			=> 'Passive',
		'Set' 				=> 'Sets',
		'Use' 				=> 'Use',
		'ChanceToProc' 		=> 'Procs',
		'TempEnchantment'	=> 'Temp Effects'
		);

// item_bonus end //
$lang['inactive'] = 'Inactive';

$lang['admin']['char_conf'] = 'Character Page|Control what is displayed in the Character pages';
$lang['admin']['char_links'] = "Character Page Links|Display the character page quick links on each character page";
$lang['admin']['recipe_disp'] = "Recipe Display|Controls how the recipe lists display on page load<br />The lists can be collapsed and opened by clicking on the header<br /><br />&quot;show&quot; will fully display the lists when the page loads<br />&quot;collapse&quot; will show the lists collapsed";
$lang['admin']['mail_disp'] = "Mail Display|Controls how the mail is displayed<br /><br />&quot;Table&quot; Shows the mail in a table view<br />&quot;Bag&quot; Shows each mail as a bag of items<br />&quot;Both&quot; Shows both";
$lang['admin']['show_money'] = "Money|Controls the display of Money<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_played'] = "Time Played|Controls the display of Time Played and Time Level Played<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_tab2'] = "Pets|Controls the display of Pets<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_tab3'] = "Reputation|Controls the display of Reputation<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_tab4'] = "Skills|Controls the display of Skills<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_tab5'] = "PvP|Controls the display of PvP<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_talents'] = "Talents|Controls the display of Talents<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_spellbook'] = "Spellbook|Controls the display of the Spellbook<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_mail'] = "Mail|Controls the display of Mail<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_bags'] = "Bags|Controls the display of Bags<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_bank'] = "Bank|Controls the display of Bank contents<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_recipes'] = "Recipes|Controls the display of Recipes<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_quests'] = "Quests|Controls the display of Quests<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_bg'] = "Battleground PvPLog Data|Controls the display of Battleground PvPLog data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_pvp'] = "PvPLog Data|Controls the display of PvPLog Data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_duels'] = "Duel PvPLog Data|Controls the display of Duel PvPLog Data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_item_bonuses'] = "Item Bonuses|Controls the display of Item Bonuses<br /><br />Setting is global and overrides per-user setting";

$lang['admin']['char_pref'] = 'Display Preferences|Control what is displayed in the character pages per character';

$lang['admin']['no_data'] = 'No Data';
$lang['admin']['nothing_to_config'] = 'Nothing to configure<br />All Global settings are set to override per-character settings';
