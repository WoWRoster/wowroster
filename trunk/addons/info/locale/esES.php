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
$lang['cb_character'] = 'Personaje|Shows character stats, equipment, reputation, skills, and pvp info';
$lang['cb_talents'] = 'Talentos|Shows current talent build';
$lang['cb_spellbook'] = 'Libro Hechizos|Shows available spells, actions, and passive abilities';
$lang['cb_mailbox'] = 'Buzón|Shows the contents of the mailbox';
$lang['cb_bags'] = 'Bolsas|Shows the contents of this character\'s bags';
$lang['cb_bank'] = 'Banco|Shows the contents of this character\'s bank';
$lang['cb_quests'] = 'Misiones|A list of quest this character is currenly on';
$lang['cb_recipes'] = 'Recetas|Shows what items this character';

$lang['char_stats'] = 'Character Stats for: %1$s';
$lang['char_level_race_class'] = 'Level %1$s %2$s %3$s';
$lang['char_guildline'] = '%1$s of %2$s';
$lang['talents'] = 'Talentos';

// Spellbook
$lang['spellbook'] = 'Libro Hechizos';
$lang['no_spellbook'] = 'No Spellbook for %1$s';

// Mailbox
$lang['mailbox'] = 'Buzón';
$lang['maildateutc'] = 'Fecha Correo';
$lang['mail_item'] = 'Objeto';
$lang['mail_sender'] = 'Remitente';
$lang['mail_subject'] = 'Asunto';
$lang['mail_expires'] = 'Correo Caduca';
$lang['mail_money'] = 'Dinero Incluído';
$lang['no_mail'] = 'No Mail for %1$s';

//skills
$lang['skilltypes'] = array(
	1 => 'Habilidades de clase',
	2 => 'Profesiones',
	3 => 'Habilidades secundarias',
	4 => 'Armas disponibles',
	5 => 'Armaduras disponibles',
	6 => 'Lenguas'
);

//tabs
$lang['tab1']='Persj';
$lang['tab2']='Mascota';
$lang['tab3']='Reputation';
$lang['tab4']='Habilid';
$lang['tab5']='JcJ';

$lang['strength']='Fortaleza';
$lang['strength_tooltip']='Aumenta tu poder de ataque con armas cuerpo a cuerpo.<br />Aumenta la cantidad de daño que puedes bloquear con un escudo.';
$lang['agility']='Agilidad';
$lang['agility_tooltip']= 'Aumenta tu poder de ataque con armas a distancia.<br />Aumenta las probabilidades de asestar un impacto crítico con todas las armas.<br />Aumenta tu armadura y las probabilidades de esquivar los golpes.';
$lang['stamina']='Aguante';
$lang['stamina_tooltip']= 'Aumenta tus puntos de salud.';
$lang['intellect']='Inteligencia';
$lang['intellect_tooltip']= 'Aumenta tus puntos de maná y la probabilidad de asestar un impacto crítico con hechizos.<br />Aumenta la velocidad a la que mejora tu habilidad con las armas.';
$lang['spirit']='Espíritu';
$lang['spirit_tooltip']= 'Aumenta tu velocidad de regeneración de salud y maná.';
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

$lang['melee_att_power']='Poder de ataque cuerpo a cuerpo';
$lang['melee_att_power_tooltip']='Increases damage with melee weapons by %.1f damage per second.';
$lang['ranged_att_power']='Poder de ataque a distancia';
$lang['ranged_att_power_tooltip']='Increases damage with ranged weapons by %.1f damage per second.';

$lang['weapon_hit_rating']='Hit Rating';
$lang['weapon_hit_rating_tooltip']='Increases your chance to hit an enemy.';
$lang['weapon_crit_rating']='Crit Rating';
$lang['weapon_crit_rating_tooltip']='Critical strike chance %.2f%%.';

$lang['damage']='Daño';
$lang['energy']='Energía';
$lang['rage']='Ira';
$lang['power']='Poder';

$lang['melee_rating']='Tasa de ataque';
$lang['melee_rating_tooltip']='Tu velocidad de ataque afecta a las probabilidades de acertar a un enemigo<br /> y se mide por la habilidad con el arma que equipes en cada momento.';
$lang['range_rating']='Tasa de ataque a distancia';
$lang['range_rating_tooltip']='Tu velocidad de ataque afecta a las probabilidades de acertar a un enemigo<br /> y se mide por la habilidad con el arma que equipes en cada momento.';

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

$lang['res_arcane']='Resistencia a lo Arcano';
$lang['res_arcane_tooltip']='Increases your ability to resist Arcane Resistance-based attacks, spells, and abilities.';
$lang['res_fire']='Resistencia al Fuego';
$lang['res_fire_tooltip']='Increases your ability to resist Fire Resistance-based attacks, spells, and abilities.';
$lang['res_nature']='Resistencia a la Naturaleza';
$lang['res_nature_tooltip']='Increases your ability to resist Nature Resistance-based attacks, spells, and abilities.';
$lang['res_frost']='Resistencia a la Escarcha';
$lang['res_frost_tooltip']='Increases your ability to resist Frost Resistance-based attacks, spells, and abilities.';
$lang['res_shadow']='Resistencia a las Sombras';
$lang['res_shadow_tooltip']='Increases your ability to resist Shadow Resistance-based attacks, spells, and abilities.';

$lang['empty_equip']='No hay objeto equipado';
$lang['pointsspent']='Puntos Gastados en';

$lang['item_bonuses_full'] = 'Bonificaciones para objetos equipados';
$lang['item_bonuses'] = 'Bonificaciones de objetos';

$lang['admin']['char_conf'] = 'Character Page|Control what is displayed in the Character pages';
$lang['admin']['char_links'] = "Character Page Links|Display the character page quick links on each character page";
$lang['admin']['recipe_disp'] = "Recipe Display|Controls how the recipe lists display on page load<br />The lists can be collapsed and opened by clicking on the header<br /><br />&quot;show&quot; will fully display the lists when the page loads<br />&quot;hide&quot; will show the lists collapsed";
$lang['admin']['show_tab2'] = "Pets|Controls the display of Pets<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_tab3'] = "Reputation|Controls the display of Reputation<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_tab4'] = "Skills|Controls the display of Skills<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_tab5'] = "PvP|Controls the display of PvP<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_talents'] = "Talentos|Controla el modo de mostrar los talentos<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_spellbook'] = "Libro de hechizos|Controla el modo de mostrar el libro de hechizos<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_mail'] = "Correo|Controla el modo de mostrar el correo<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_bags'] = "Bolsas|Controla el modo de mostrar las bolsas <br><br>opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_money'] = "Dinero|Controla el modo de mostrar el dinero<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_bank'] = "Banco|Controla el modo de mostrar el banco<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_recipes'] = "Recetas|Controla el modo de mostrar las recetas<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_quests'] = "Quests|Controla el modo de mostrar las quests<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_bg'] = "Informaci&oacuten del CampoBatalla del PVPlog|Controla el modo de mostrar la informaci&oacuten del Campo de Batalla del PVPlog<br>Requiere subir la informaci&oacuten del addon PvPLog<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_pvp'] = "Informaci&oacuten del PVPlog|Controla el modo de mostrar la informaci&oacuten del PVPlog<br>Requiere subir la informaci&oacuten del addon PvPLog<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_duels'] = "Informaci&oacuten de duelos del PVPlog|Controla el modo de mostrar la informaci&oacuten de los duelos del PVPlog<br>Requiere subir la informaci&oacuten del addon PvPLog<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_item_bonuses'] = "Bonus de objetos|Controla el modo de mostrar los Bonus de objetos<br><br>Las opciones son globales y afectan a todos los usuarios";

$lang['admin']['char_pref'] = 'Preferences|Control what is displayed in the character pages per character';
