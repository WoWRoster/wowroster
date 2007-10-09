<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * esES Locale Translation by maqjav
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
 * @subpackage Locale
*/

$lang['char_info'] = 'Información de personaje';
$lang['char_info_desc'] = 'Muestra información sobre los personajes subidos al Roster';

// Menu Buttons
$lang['cb_character'] = 'Personaje|Muestra las estadísticas del personaje, equipamiento, reputación, habilidades e información de JcJ';
$lang['cb_talents'] = 'Talentos|Muestra la construcción actual de talentos';
$lang['cb_spellbook'] = 'Libro de hechizos|Muestra el libro de hechizos del personaje';
$lang['cb_mailbox'] = 'Buzón|Muestra el contenido del buzón';
$lang['cb_bags'] = 'Bolsas|Muestra el contenido de las bolsas del personaje';
$lang['cb_bank'] = 'Banco|Muestra el contenido del banco del personaje';
$lang['cb_quests'] = 'Misiones|Muestra el listado de misiones actuales del personaje';
$lang['cb_recipes'] = 'Recetas|Muestra los objetos que puede hacer el personaje';

$lang['char_stats'] = 'Estadísticas del personaje: %1$s';
$lang['char_level_race_class'] = 'Nivel %1$s %2$s %3$s';
$lang['char_guildline'] = '%1$s de %2$s';
$lang['talents'] = 'Talentos';

// Spellbook
$lang['spellbook'] = 'Libro de hechizos';
$lang['no_spellbook'] = 'No hay libro de hechizos para %1$s';

// Mailbox
$lang['mailbox'] = 'Buzón';
$lang['maildateutc'] = 'Fecha del correo';
$lang['mail_item'] = 'Objeto';
$lang['mail_sender'] = 'Remitente';
$lang['mail_subject'] = 'Asunto';
$lang['mail_expires'] = 'Caducidad';
$lang['mail_money'] = 'Dinero incluído';
$lang['no_mail'] = 'No hay correo para %1$s';

//skills
$lang['skilltypes'] = array(
	1 => 'Habilidades de clase',
	2 => 'Profesiones',
	3 => 'Habilidades secundarias',
	4 => 'Armas disponibles',
	5 => 'Armaduras disponibles',
	6 => 'Idiomas'
);

// item slots, for missing items on characters
$lang['Head']          = 'Cabeza';
$lang['Neck']          = 'Cuello';
$lang['Shoulder']      = 'Hombros';
$lang['Back']          = 'Espalda';
$lang['Chest']         = 'Pecho';
$lang['Shirt']         = 'Camisa';
$lang['Tabard']        = 'Tabardo';
$lang['Wrist']         = 'Muñeca';
$lang['MainHand']      = 'Mano derecha';
$lang['SecondaryHand'] = 'Mano izquierda';
$lang['Ranged']        = 'A distancia';
$lang['Ammo']          = 'Munición';
$lang['Hands']         = 'Manos';
$lang['Waist']         = 'Cintura';
$lang['Legs']          = 'Piernas';
$lang['Feet']          = 'Pies';
$lang['Finger0']       = 'Dedo 0';
$lang['Finger1']       = 'Dedo 1';
$lang['Trinket0']      = 'Abalorio 0';
$lang['Trinket1']      = 'Abalorio 1';

//tabs
$lang['tab1']='Personaje';
$lang['tab2']='Mascota';
$lang['tab3']='Reputación';
$lang['tab4']='Habilidades';
$lang['tab5']='JcJ';

$lang['strength_tooltip']='Aumenta tu poder de ataque con armas cuerpo a cuerpo.<br />Aumenta la cantidad de daño que puedes bloquear con un escudo.';
$lang['agility_tooltip']= 'Aumenta tu poder de ataque con armas a distancia.<br />Aumenta las probabilidades de asestar un impacto crítico con todas las armas.<br />Aumenta tu armadura y las probabilidades de esquivar los golpes.';
$lang['stamina_tooltip']= 'Aumenta tus puntos de salud.';
$lang['intellect_tooltip']= 'Aumenta tus puntos de maná y la probabilidad de asestar un impacto crítico con hechizos.<br />Aumenta la velocidad a la que mejora tu habilidad con las armas.';
$lang['spirit_tooltip']= 'Aumenta tu velocidad de regeneración de salud y maná.';
$lang['armor_tooltip']= 'Reduce daño físico hecho por %1$s%%';

$lang['mainhand']='Mano principal';
$lang['offhand']='Una mano';
$lang['ranged']='A distancia';
$lang['melee']='Cuerpo a cuerpo';
$lang['spell']='Hechizo';

$lang['weapon_skill']='Habilidad';
$lang['weapon_skill_tooltip']='<span style="float:right;color:#fff;">%1$d</span>Hab. arma<br /><span style="float:right;color:#fff;">%2$d</span>Indice de habilidad con arma';
$lang['damage']='Daño';
$lang['damage_tooltip']='<span style="float:right;color:#fff;">%.2f</span>Velocidad de ataque (segundos):<br /><span style="float:right;color:#fff;">%d-%d</span>Daño:<br /><span style="float:right;color:#fff;">%.1f</span>Daño por segundo:<br />';
$lang['speed']='Veloc.';
$lang['atk_speed']='Velocidad de ataque';
$lang['haste_tooltip']='Indice de celeridad ';

$lang['melee_att_power']='Poder de ataque cuerpo a cuerpo';
$lang['melee_att_power_tooltip']='Aumenta el daño que causas con armas cuerpo a cuerpo en %.1f puntos de daño por segundo.';
$lang['ranged_att_power']='Poder de ataque a distancia';
$lang['ranged_att_power_tooltip']='Aumenta el daño con armas de ataque a distancia en %.1f puntos de dañor por segundo.';

$lang['weapon_hit_rating']='Ind. golpe';
$lang['weapon_hit_rating_tooltip']='Aumenta tu probabilidad de acierto cuerpo a cuerpo cuando golpeas a un enemigo.';
$lang['weapon_crit_rating']='Pro. Crit.';
$lang['weapon_crit_rating_tooltip']='Indice de golpe crítico %.2f%%.';

$lang['damage']='Daño';
$lang['energy']='Energía';
$lang['rage']='Ira';
$lang['power']='Poder';

$lang['melee_rating']='Tasa de ataque';
$lang['melee_rating_tooltip']='Tu velocidad de ataque afecta a las probabilidades de acertar a un enemigo<br /> y se mide por la habilidad con el arma que equipes en cada momento.';
$lang['range_rating']='Tasa de ataque a distancia';
$lang['range_rating_tooltip']='Tu velocidad de ataque afecta a las probabilidades de acertar a un enemigo<br /> y se mide por la habilidad con el arma que equipes en cada momento.';

$lang['spell_damage']='+Daño';
$lang['holy']='Sagrado';
$lang['fire']='Fuego';
$lang['nature']='Naturaleza';
$lang['frost']='Hielo';
$lang['shadow']='Sombras';
$lang['arcane']='Arcano';

$lang['spell_healing']='+Salud';
$lang['spell_healing_tooltip']='Incrementa tus hechizos de sanazión en %d';
$lang['spell_hit_rating']='Ind. golpe';
$lang['spell_hit_rating_tooltip']='Aumenta tu probabilidad de golpear con hechizos a tu objetivo.';
$lang['spell_crit_rating']='Ind. Crit.';
$lang['spell_crit_chance']='Prob. Crit.';
$lang['spell_penetration']='Penetración';
$lang['spell_penetration_tooltip']='Reduce la resistencia del objetivo a tus hechizos';
$lang['mana_regen']='Reg. Maná';
$lang['mana_regen_tooltip']='%1$d maná que regeneras cada 5 segundos cuando no lanzas hechizos<br />%2$d maná que regeneras cada 5 segundos cuando lanzas hechizos';

$lang['defense_rating']='Defensa ';
$lang['def_tooltip']='Incrementa tu indice de defensa en %s';
$lang['resilience']='Resistencia';

$lang['res_arcane']='Resistencia a lo arcano';
$lang['res_arcane_tooltip']='Aumenta tu facultad para resistir ataques, hechizos y facultades de arcano.';
$lang['res_fire']='Resistencia al fuego';
$lang['res_fire_tooltip']='Aumenta tu facultad para resistir ataques, hechizos y facultades de fuego.';
$lang['res_nature']='Resistencia a la naturaleza';
$lang['res_nature_tooltip']='Aumenta tu facultad para resistir ataques, hechizos y facultades de naturaleza.';
$lang['res_frost']='Resistencia a la escarcha';
$lang['res_frost_tooltip']='Aumenta tu facultad para resistir ataques, hechizos y facultades de escarcha.';
$lang['res_shadow']='Resistencia a las sombras';
$lang['res_shadow_tooltip']='Aumenta tu facultad para resistir ataques, hechizos y facultades de sombras.';

$lang['empty_equip']='No hay objetos equipados';
$lang['pointsspent']='Puntos de talento gastados en %1$s';
// item_bonus locales //
$lang['item_bonuses_full'] = 'Bonificaciones de objetos equipados';
$lang['item_bonuses'] = 'Bonificaciones de objetos';
$lang['item_bonuses_preg_linesplits']='/(and|\/|&)/';
$lang['item_bonuses_preg_main']='/(?!\d*\s(sec|min))(-{0,1}\d*\.{0,1}\d+)/i';

//
// patterns to standardize bonus string (NOTE: Not all the translations to spanish are exactly, we have to probe them)
$lang['item_bonuses_preg_patterns'] =
	array('/Aumenta el valor de bloqueo de tu escudo en xx\.?/i',	//1
		  '/(?:incrementa|decrementa) (?:tu )?(.+) en xx\.?/i',	//2
		  '/Aumenta el daño y la sanación de los hechizos mágicos y los efectos hasta en xx\.?$/i',	//3
		  '/(?:Restaura|\+)?\s?xx de (maná|salud) (?:cada|durante|regen).*$/i',	//4
		  '/Aumenta el daño hecho en (.+) y.*$/i',	//5
		  '/^\+?xx (sanación)(?: hechizos)?\.?$/',	//6
		  '/^alcance \(\+xx daño\)$/i',	//7
		  '/^\+?xx (?:escudo )?bloquear$/i',	//8
		 );
$lang['item_bonuses_preg_replacements'] =
	array('+XX Bloqueo de escudo',  //1
		  '+XX $1', //2
		  '+XX Hechizo $1:+XX $2 Hechizos', //3
		  '+XX $1 Cada 5 segundos', //4
		  '+XX $1 Daño', //5
		  '+XX $1 Hechizos', //6
		  '+XX Daño a distancia (Alcance)', //7
		  '+XX Bloqueo de escudo', //8
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
		'Totals' 			=> 'Totales',
		'Enchantment' 		=> 'Encantamientos',
		'BaseStats' 		=> 'Estadísticas base',
		'Gems' 				=> 'Gemas',
		'Effects' 			=> 'Pasivo',
		'Set' 				=> 'Equipos',
		'Use' 				=> 'Uso',
		'ChanceToProc' 		=> 'Procs',
		'TempEnchantment'	=> 'Efectos temporales'
		);

// item_bonus end //
$lang['inactive'] = 'Inactivo';

$lang['admin']['char_conf'] = 'Página de personaje|Elige que mostrar en la página de personaje';
$lang['admin']['char_links'] = "Enlaces de la página de personaje|Muestra enlaces rápidos en la página de cada personaje";
$lang['admin']['recipe_disp'] = "Mostrar recetas|Elige si mostrar el listado de recetas en la página.<br />Las listas pueden minimizarse y abrirse pinchando en la cabecera.<br /><br />&quot;show&quot; mostrará los listados abiertos en la página.<br />&quot;collapse&quot; mostrará las listas minimizadas.";
$lang['admin']['show_tab2'] = "Mascotas|Elige si mostrar las mascotas<br /><br />La configuración es global y afectará a todos los personajes";
$lang['admin']['show_tab3'] = "Reputación|Elige si mostrar las reputaciones.<br /><br />La configuración es global y afectará a todos los personajes";
$lang['admin']['show_tab4'] = "Habilidades|Elige si mostrar las habilidades.<br /><br />La configuración es global y afectará a todos los personajes";
$lang['admin']['show_tab5'] = "JcJ|Elige si mostrar los datos de JcJ.<br /><br />La configuración es global y afectará a todos los personajes";
$lang['admin']['show_talents'] = "Talentos|Elige si mostrar los talentos.<br><br>La configuración es global y afectará a todos los personajes";
$lang['admin']['show_spellbook'] = "Libro de hechizos|Elige si mostrar el libro de hechizos.<br><br>La configuración es global y afectará a todos los personajes";
$lang['admin']['show_mail'] = "Correo|Elige si mostrar el correo.<br><br>La configuración es global y afectará a todos los personajes";
$lang['admin']['show_bags'] = "Bolsas|Elige si mostrar las bolsas-<br><br>La configuración es global y afectará a todos los personajes";
$lang['admin']['show_money'] = "Dinero|Elige si mostrar el dinero.<br><br>La configuración es global y afectará a todos los personajes";
$lang['admin']['show_bank'] = "Banco|Elige si mostrar el banco.<br><br>La configuración es global y afectará a todos los personajes";
$lang['admin']['show_recipes'] = "Recetas|Elige si mostrar las recetas.<br><br>La configuración es global y afectará a todos los personajes";
$lang['admin']['show_quests'] = "Misiones|Elige si mostrar las misiones.<br><br>La configuración es global y afectará a todos los personajes";
$lang['admin']['show_bg'] = "Información de campos de batalla|Elige si mostrar la información de los campos de batalla.<br>Requiere subir la información del addon PvPLog.<br><br>La configuración es global y afectará a todos los personajes";
$lang['admin']['show_pvp'] = "Información de JcJ|Elige si mostrar la información de JcJ.<br>Requiere subir la información del addon PvPLog.<br><br>La configuración es global y afectará a todos los personajes";
$lang['admin']['show_duels'] = "Información de duelos|Elige si mostrar la información sobre duelos.<br>Requiere subir la información del addon PvPLog.<br><br>La configuración es global y afectará a todos los personajes";
$lang['admin']['show_item_bonuses'] = "Bonificaciones de objetos|Elige si mostrar las bonificaciones de los objetos.<br><br>La configuración es global y afectará a todos los personajes";

$lang['admin']['char_pref'] = 'Mostrar preferencias|Controla que mostrar en la página de personajes para cada personaje';
