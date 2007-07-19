<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
 * @subpackage Locale
*/

// Menu Buttons
$lang['cb_character'] = 'Character|Shows character stats, equipment, reputation, skills, and pvp info';
$lang['cb_talents'] = 'Talents|Shows current talent build';
$lang['cb_spellbook'] = 'Spellbook|Shows available spells, actions, and passive abilities';
$lang['cb_mailbox'] = 'Mailbox|Shows the contents of the mailbox';
$lang['cb_bags'] = 'Bags|Shows the contents of this character\'s bags';
$lang['cb_bank'] = 'Bank|Shows the contents of this character\'s bank';
$lang['cb_quests'] = 'Quests|A list of quest this character is currenly on';
$lang['cb_recipes'] = 'Recipes|Shows what items this character';

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

//skills
$lang['skilltypes'] = array(
	1 => 'Class Skills',
	2 => 'Professions',
	3 => 'Secondary Skills',
	4 => 'Weapon Skills',
	5 => 'Armor Proficiencies',
	6 => 'Languages'
);

//tabs
$lang['tab1']='Character';
$lang['tab2']='Pet';
$lang['tab3']='Reputation';
$lang['tab4']='Skills';
$lang['tab5']='PvP';

$lang['strength']='Strength';
$lang['strength_tooltip']='Increases your attack power with melee Weapons.<br />Increases the amount of damage you can block with a shield.';
$lang['agility']='Agility';
$lang['agility_tooltip']= 'Increases your attack power with ranged weapons.<br />Improves your chance to score a critcal hit with all weapons.<br />Increases your armor and your chance to dodge attacks.';
$lang['stamina']='Stamina';
$lang['stamina_tooltip']= 'Increases your health points.';
$lang['intellect']='Intellect';
$lang['intellect_tooltip']= 'Increases your mana points and your chance to score a critical hit with spells.<br />Increases the rate at which you improve weapon skills.';
$lang['spirit']='Spirit';
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
$lang['pointsspent']='Points Spent in';

$lang['item_bonuses_full'] = 'Bonuses For Equipped Items';
$lang['item_bonuses'] = 'Item Bonuses';

$lang['inactive'] = 'Inactive';

$lang['admin']['char_conf'] = 'Character Page|Control what is displayed in the Character pages';
$lang['admin']['char_links'] = "Character Page Links|Display the character page quick links on each character page";
$lang['admin']['recipe_disp'] = "Recipe Display|Controls how the recipe lists display on page load<br />The lists can be collapsed and opened by clicking on the header<br /><br />&quot;show&quot; will fully display the lists when the page loads<br />&quot;hide&quot; will show the lists collapsed";
$lang['admin']['show_tab2'] = "Pets|Controls the display of Pets<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_tab3'] = "Reputation|Controls the display of Reputation<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_tab4'] = "Skills|Controls the display of Skills<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_tab5'] = "PvP|Controls the display of PvP<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_talents'] = "Talents|Controls the display of Talents<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_spellbook'] = "Spellbook|Controls the display of the Spellbook<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_mail'] = "Mail|Controls the display of Mail<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_bags'] = "Bags|Controls the display of Bags<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_money'] = "Money|Controls the display of Money<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_bank'] = "Bank|Controls the display of Bank contents<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_recipes'] = "Recipes|Controls the display of Recipes<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_quests'] = "Quests|Controls the display of Quests<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_bg'] = "Battleground PvPLog Data|Controls the display of Battleground PvPLog data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_pvp'] = "PvPLog Data|Controls the display of PvPLog Data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_duels'] = "Duel PvPLog Data|Controls the display of Duel PvPLog Data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_item_bonuses'] = "Item Bonuses|Controls the display of Item Bonuses<br /><br />Setting is global and overrides per-user setting";

$lang['admin']['char_pref'] = 'Display Preferences|Control what is displayed in the character pages per character';
