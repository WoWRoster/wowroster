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

// esES translation by maqjav, nekromant, BarryZGZ



//Instructions how to upload, as seen on the mainpage
$lang['update_link']='Pulsa aquí para las instrucciones de actualización';
$lang['update_instructions']='Instrucciones de Actualización';

$lang['lualocation']='Pulsa Examinar y selecciona tus ficheros *.lua para el envío';

$lang['filelocation']='se encuentra en<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*NOMBRE_DE_CUENTA*</i>\\\\SavedVariables';

$lang['noGuild']='No puedo encontrar la hermandad en la base de datos. Por favor, actualiza primero los miembros.';
$lang['nodata']="No puedo encontrar la hermandad: <b>'".$roster_conf['guild_name']."'</b> del servidor <b>'".$roster_conf['server_name']."'</b><br />Necesitas <a href=\"".makelink('update')."\">incluir tu hermandad</a> y asegurarte de que has <a href=\"".makelink('rostercp')."\">terminado la configuración</a><br /><br /><a href=\"http://www.wowroster.net/wiki/index.php/Roster:Install\" target=\"_blank\">Pulsa aquí para las instrucciones de instalación</a>";
$lang['nodata_title']='No Guild Data';

$lang['update_page']='Actualizar Perfil';

$lang['guild_nameNotFound']='No puedo actualizar &quot;%s&quot;. ¿Quizás no ha sido configurado?';
$lang['guild_addonNotFound']='No puedo encontrar la hermandad. ¿Has instalado correctamente Guild Profiler?';

$lang['ignored']='Ignorado';
$lang['update_disabled']='Ha sido desactivado el acceso a Update.php';

$lang['nofileUploaded']='UniUploader no ha enviado ningún archivo, o ha enviado el archivo incorrecto.';
$lang['roster_upd_pwLabel']='Clave de Actualización';
$lang['roster_upd_pw_help']='(Es necesaria para actualizar los datos de la hermandad)';


$lang['roster_error'] = 'Roster Error';
$lang['sql_queries'] = 'SQL Queries';
$lang['invalid_char_module'] = 'Invalid characters in module name';
$lang['invalid_char_addon'] = 'Invalid characters in addon name';
$lang['module_not_exist'] = 'The page [%1$s] does not exist';

$lang['addon_error'] = 'Addon Error';
$lang['specify_addon'] = 'You must specify an addon name!';
$lang['addon_not_exist'] = '<b>The addon [%1$s] does not exist!</b>';
$lang['addon_disabled'] = '<b>The addon [%1$s] has been disabled</b>';
$lang['addon_not_installed'] = '<b>The addon [%1$s] has not been installed yet</b>';

$lang['char_error'] = 'Character Error';
$lang['specify_char'] = 'Character was not specified';
$lang['no_char_id'] = 'Sorry no character data for member_id [ %1$s ]';
$lang['no_char_name'] = 'Sorry no character data for <strong>%1$s</strong> of <strong>%2$s</strong>';
$lang['char_stats'] = 'Character Stats for: %1$s @ %2$s';
$lang['char_links'] = 'Character Links';

$lang['gbank_list'] = 'Full Listing';
$lang['gbank_inv'] = 'Inventory';
$lang['gbank_not_loaded'] = '<strong>%1$s</strong> has not uploaded an inventory yet';

$lang['roster_cp'] = 'Roster Control Panel';
$lang['roster_cp_not_exist'] = 'Page [%1$s] does not exist';
$lang['roster_cp_invalid'] = 'Invalid page specified or insufficient credentials to access this page';

$lang['parsing_files'] = 'Parsing files';
$lang['parsed_time'] = 'Parsed %1$s in %2$s seconds';
$lang['error_parsed_time'] = 'Error while parsing %1$s after %2$s seconds';
$lang['upload_not_accept'] = 'Did not accept %1$s';
$lang['not_updating'] = 'NOT Updating %1$s for [%2$s] - %3$s';
$lang['not_update_guild'] = 'NOT Updating Guild List for %1$s';
$lang['no_members'] = 'Data does not contain any guild members';
$lang['upload_data'] = 'Updating %1$s Data for [<span class="orange">%2$s</span>]';
$lang['realm_ignored'] = 'Realm: %1$s Not Scanned';
$lang['guild_realm_ignored'] = 'Guild: %1$s @ Realm: %2$s  Not Scanned';
$lang['update_members'] = 'Updating Members';
$lang['gp_user_only'] = 'GuildProfiler User Only';
$lang['update_errors'] = 'Update Errors';
$lang['update_log'] = 'Update Log';
$lang['save_error_log'] = 'Save Error Log';
$lang['save_update_log'] = 'Save Update Log';


// Updating Instructions
$lang['index_text_uniloader'] = "(Puedes descargar este programa desde la web de WoWRoster, busca el instalador de UniUploader para obtener la última versión)";

$lang['update_instruct']='
<strong>Actualizadores Automáticos Recomendados:</strong>
<ul>
<li>Utiliza <a href="'.$roster_conf['uploadapp'].'" target="_blank">UniUploader</a><br />
'.$lang['index_text_uniloader'].'</li>
</ul>
<strong>Instrucciones de Actualización:</strong>
<ol>
<li>Descarga <a href="'.$roster_conf['profiler'].'" target="_blank">Character Profiler</a></li>
<li>Extrae el zip en su propia carpeta en C:\Archivos de Programa\World of Warcraft\Interface\Addons\</li>
<li>Inicia WoW</li>
<li>Abre tu ventana de banco, misiones, y profesiones que contengan recetas</li>
<li>Desconecta/Sal de WoW (Mira más arriba si deseas utilizar UniUploader para enviar los datos automáticamente.)</li>
<li>Vete a <a href="'.makelink('update').'">la página de actualización</a></li>
<li>'.$lang['lualocation'].'</li>
</ol>';

$lang['update_instructpvp']='
<strong>Estadísticas JcJ Opcionales:</strong>
<ol>
<li>Descarga <a href="'.$roster_conf['pvplogger'].'" target="_blank">PvPLog</a></li>
<li>Extrae PvPLog en la carpeta de Addons.</li>
<li>Haz duelos o combates JcJ</li>
<li>Envía PvPLog.lua</li>
</ol>';

$lang['roster_credits']='Agradecimientos a <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, y <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> por el código original usado en este sitio.<br />
Página principal de WoWRoster - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft y Blizzard Entertainment son marcas registradas de Blizzard Entertainment, Inc. en los E.U.A. y/u otros países. El resto de marcas registradas pertenecen a sus respectivos propietarios.<br />
<a href="'.makelink('credits').'">Créditos Adicionales</a>';


//Charset
$lang['charset']="charset=utf-8";

$lang['timeformat'] = '%a %d %b, %H:%i'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$lang['phptimeformat'] = 'D d M, H:i';    // PHP date() Time format (example - 'D M jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


/**
 * Realmstatus Localizations
 */
$lang['rs'] = array(
	'ERROR' => 'Error',
	'NOSTATUS' => 'Sin Estado',
	'UNKNOWN' => 'Desconocido',
	'RPPVP' => 'JdR JcJ',
	'PVE' => 'Normal',
	'PVP' => 'JcJ',
	'RP' => 'JdR',
	'OFFLINE' => 'Caido',
	'LOW' => 'Bajo',
	'MEDIUM' => 'Medio',
	'HIGH' => 'Alto',
	'MAX' => 'Lleno',
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
		'SG' => 'Llave de la Garganta de Fuego|4826',
			'The Horn of the Beast|',
			'Proof of Deed|',
			'At Last!|'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Llave de taller|2288'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'La llave Escarlata|4445'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Marra de Zul\\\'Farrak|5695',
			'Marra sacra|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Cetro de Celebras|19710',
			'Vara de Celebras|19549',
			'Diamante de Celebras|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Llave de celda de prisión|15545'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Llave Sombratiniebla|2966',
			'Ferrovil|9673'
		),
	'DM' => array( 'Key-Only',
		'DM' => 'Llave creciente|35607'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Llave esqueleto|16854',
			'Scholomance|',
			'Skeletal Fragments|',
			'Mold Rhymes With...|',
			'Fire Plume Forged|',
			'Araj\\\'s Scarab|',
			'The Key to Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Llave de la ciudad|13146'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Lacre de ascensión|17057',
			'Sello de ascensión sin adornar|5370',
			'Gema de Cumbrerroca|5379',
			'Gema de Espina Ahumada|16095',
			'Gema de Hacha de Sangre|21777',
			'Sello de Ascensión sin forjar|24554||MS',
			'Sello de Ascensión forjado|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Amuleto de Pirodraco|4829',
			'Dragonkin Menace|',
			'The True Masters|',
			'Marshal Windsor|',
			'Abandoned Hope|',
			'A Crumpled Up Note|',
			'A Shred of Hope|',
			'Jail Break!|',
			'Stormwind Rendezvous|',
			'The Great Masquerade|',
			'The Dragon\\\'s Eye|',
			'Amuleto de Pirodraco|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Quintaesencia eterna|22754'
		),
);


// HORDE KEYS
$lang['inst_keys']['H'] = array(
	'SG' => array( 'Key-Only',
		'SG' => 'Llave de la Garganta de Fuego|4826'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Llave de taller|2288'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'La llave Escarlata|4445'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Marra de Zul\\\'Farrak|5695',
			'Marra sacra|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Cetro de Celebras|19710',
			'Vara de Celebras|19549',
			'Diamante de Celebras|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Llave de celda de prisión|15545'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Llave Sombratiniebla|2966',
			'Ferrovil|9673'
		),
	'DM' => array( 'Key-Only',
		'DM' => 'Llave creciente|35607'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Llave esqueleto|16854',
			'Scholomance|',
			'Skeletal Fragments|',
			'Mold Rhymes With...|',
			'Fire Plume Forged|',
			'Araj\\\'s Scarab|',
			'The Key to Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Llave de la ciudad|13146'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Lacre de ascensión|17057',
			'Sello de ascensión sin adornar|5370',
			'Gema de Cumbrerroca|5379',
			'Gema de Espina Ahumada|16095',
			'Gema de Hacha de Sangre|21777',
			'Sello de Ascensión sin forjar|24554||MS',
			'Sello de Ascensión forjado|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Amuleto de Pirodraco|4829',
			'Warlord\\\'s Command|',
			'Eitrigg\\\'s Wisdom|',
			'For The Horde!|',
			'What the Wind Carries|',
			'The Champion of the Horde|',
			'The Testament of Rexxar|',
			'Oculus Illusions|',
			'Emberstrife|',
			'The Test of Skulls, Scryer|',
			'The Test of Skulls, Somnus|',
			'The Test of Skulls, Chronalis|',
			'The Test of Skulls, Axtroz|',
			'Ascension...|',
			'Blood of the Black Dragon Champion|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Quintaesencia eterna|22754'
		),
);

//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$lang['upload']='Enviar';
$lang['required']='Requerido';
$lang['optional']='Opcional';
$lang['attack']='Ataque';
$lang['defense']='Defensa';
$lang['class']='Clase';
$lang['race']='Raza';
$lang['level']='Nivel';
$lang['zone']='Última Zona';
$lang['note']='Nota';
$lang['title']='Título';
$lang['name']='Nombre';
$lang['health']='Salud';
$lang['mana']='Maná';
$lang['gold']='Oro';
$lang['armor']='Armadura';
$lang['lastonline']='Última Conexión';
$lang['online']='Conexión';
$lang['lastupdate']='Última Actualización';
$lang['currenthonor']='Rango de Honor Actual';
$lang['rank']='Rango';
$lang['sortby']='Ordenar por %';
$lang['total']='Total';
$lang['hearthed']='Posada';
$lang['recipes']='Recetas';
$lang['bags']='Bolsas';
$lang['character']='Personaje';
$lang['bglog']='Registro Batallas';
$lang['pvplog']='Registro JcJ';
$lang['duellog']='Registro Duelos';
$lang['duelsummary']='Resumen Duelos';
$lang['money']='Dinero';
$lang['bank']='Banco';
$lang['guildbank']='Banco Clan';
$lang['guildbank_totalmoney']='Fondos Totales Banco';
$lang['raid']='CT_Raid';
$lang['guildbankcontact']='Mantenido por (Contacto)';
$lang['guildbankitem']='Nombre de Objeto y Descripción';
$lang['quests']='Misiones';
$lang['roster']='Roster';
$lang['alternate']='Suplente';
$lang['byclass']='Por Clase';
$lang['menustats']='Estadísticas';
$lang['menuhonor']='Honor';
$lang['keys']='Llaves';
$lang['team']='Buscar Equipo';
$lang['search']='Búsqueda';
$lang['update']='Última Actualización';
$lang['credit']='Créditos';
$lang['members']='Miembros';
$lang['items']='Objetos';
$lang['find']='Encontrar objeto que contenga';
$lang['upprofile']='Envío Datos';
$lang['backlink']='Volver al Inicio';
$lang['gender']='Género';
$lang['unusedtrainingpoints']='Puntos Entrenamiento No Usados';
$lang['unusedtalentpoints']='Puntos Talento No Usados';
$lang['talentexport']='Export Talent Build';
$lang['questlog']='Registro Misiones';
$lang['recipelist']='Lista Recetas';
$lang['reagents']='Ingredientes';
$lang['item']='Objeto';
$lang['type']='Tipo';
$lang['date']='Fecha';
$lang['complete'] = 'Complete';
$lang['failed'] = 'Failed';
$lang['completedsteps'] = 'Partes Completas';
$lang['currentstep'] = 'Parte Actual';
$lang['uncompletedsteps'] = 'Partes Incompletas';
$lang['key'] = 'Llave';
$lang['timeplayed'] = 'Tiempo Jugado';
$lang['timelevelplayed'] = 'Tiempo Jugado Nivel Actual';
$lang['Addon'] = 'Addons';
$lang['advancedstats'] = 'Estadísticas Avanzadas';
$lang['itembonuses'] = 'Bonificaciones para objetos equipados';
$lang['itembonuses2'] = 'Bonificaciones de objetos';
$lang['crit'] = 'Crit';
$lang['dodge'] = 'Esquivar';
$lang['parry'] = 'Parar';
$lang['block'] = 'Bloquear';
$lang['realm'] = 'Reino';
$lang['talents'] = 'Talentos';
$lang['online_at_up'] = 'Online at Update';
$lang['faction'] = 'Faction';

// Memberlog
$lang['memberlog'] = 'Registro';
$lang['removed'] = 'Borrado';
$lang['added'] = 'Añadido';
$lang['updated'] = 'Updated';
$lang['no_memberlog'] = 'No existe registro de miembros';

$lang['rosterdiag'] = 'Roster Diag.';
$lang['Guild_Info'] = 'Info Clan';
$lang['difficulty'] = 'Dificultad';
$lang['recipe_4'] = 'Óptima';
$lang['recipe_3'] = 'Media';
$lang['recipe_2'] = 'Fácil';
$lang['recipe_1'] = 'Trivial';
$lang['roster_config'] = 'Config. Roster';

// Character
$lang['char_level_race_class'] = 'Level %1$s %2$s %3$s';
$lang['char_guildline'] = '%1$s of %2$s';

// Spellbook
$lang['spellbook'] = 'Libro Hechizos';
$lang['page'] = 'Página';
$lang['general'] = 'General';
$lang['prev'] = 'Anterior';
$lang['next'] = 'Siguiente';
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
$lang['no_info'] = 'No Information';


//this needs to be exact as it is the wording in the db
$lang['professions']='Profesiones';
$lang['secondary']='Habilidades secundarias';
$lang['Blacksmithing']='Herrería';
$lang['Mining']='Minería';
$lang['Herbalism']='Botánica';
$lang['Alchemy']='Alquimia';
$lang['Leatherworking']='Peletería';
$lang['Jewelcrafting']='Joyería';
$lang['Skinning']='Desollar';
$lang['Tailoring']='Sastrería';
$lang['Enchanting']='Encantamiento';
$lang['Engineering']='Ingeniería';
$lang['Cooking']='Cocina';
$lang['Fishing']='Pesca';
$lang['First Aid']='Primeros auxilios';
$lang['Poisons']='Venenos';
$lang['backpack']='Mochila';
$lang['PvPRankNone']='ninguno';

// Uses preg_match() to find required level in recipe tooltip
$lang['requires_level'] = '/Necesitas ser de nivel ([\d]+)/';

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
	$lang['Alchemy']=>'trade_alchemy',
	$lang['Herbalism']=>'trade_herbalism',
	$lang['Blacksmithing']=>'trade_blacksmithing',
	$lang['Mining']=>'trade_mining',
	$lang['Leatherworking']=>'trade_leatherworking',
	$lang['Jewelcrafting']=>'inv_misc_gem_02',
	$lang['Skinning']=>'inv_misc_pelt_wolf_01',
	$lang['Tailoring']=>'trade_tailoring',
	$lang['Enchanting']=>'trade_engraving',
	$lang['Engineering']=>'trade_engineering',
	$lang['Cooking']=>'inv_misc_food_15',
	$lang['Fishing']=>'trade_fishing',
	$lang['First Aid']=>'spell_holy_sealofsacrifice',
	$lang['Poisons']=>'ability_poisons'
);

// Riding Skill Icons-Array
$lang['riding'] = 'Equitación';
$lang['ts_ridingIcon'] = array(
	'Elfo de la noche'=>'ability_mount_whitetiger',
	'Humano'=>'ability_mount_ridinghorse',
	'Enano'=>'ability_mount_mountainram',
	'Gnomo'=>'ability_mount_mechastrider',
	'No-muerto'=>'ability_mount_undeadhorse',
	'Trol'=>'ability_mount_raptor',
	'Tauren'=>'ability_mount_kodo_03',
	'Orco'=>'ability_mount_blackdirewolf',
	'Elfo de sangre' => 'ability_mount_cockatricemount',
	'Draenei' => 'ability_mount_ridingelekk',
	'Palad�n'=>'ability_mount_dreadsteed',
	'Brujo'=>'ability_mount_nightmarehorse'
);

// Class Icons-Array
$lang['class_iconArray'] = array (
	'Druida'=>'ability_druid_maul',
	'Cazador'=>'inv_weapon_bow_08',
	'Mago'=>'inv_staff_13',
	'Palad�n'=>'spell_fire_flametounge',
	'Sacerdote'=>'spell_holy_layonhands',
	'P�caro'=>'inv_throwingknife_04',
	'Cham�n'=>'spell_nature_bloodlust',
	'Brujo'=>'spell_shadow_cripple',
	'Guerrero'=>'inv_sword_25',
);

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
$lang['armor_tooltip']= 'Disminuye la cantidad de daño recibido por ataques físicos.<br />La reducción se determina por el nivel del que te ataca.';

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

$lang['melee_att_power']='Poder de ataque cuerpo a cuerpo';
$lang['melee_att_power_tooltip']='Increases damage with melee weapons by %.1f damage per second.';
$lang['ranged_att_power']='Poder de ataque a distancia';
$lang['ranged_att_power_tooltip']='Increases damage with ranged weapons by %.1f damage per second.';

$lang['weapon_hit_rating']='Hit Rating';
$lang['weapon_hit_rating_tooltip']='Increases your chance to hit an enemy.';
$lang['weapon_crit_rating']='Crit rating';
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
$lang['none']='Ninguno';

$lang['pvplist'] ='Estadísticas JcJ/PvP';
$lang['pvplist1']='Hermandad a la que más hemos hecho sufrir';
$lang['pvplist2']='Hermandad que más nos ha hecho sufrir';
$lang['pvplist3']='Jugador al que más hemos matado';
$lang['pvplist4']='Jugador que más nos ha matado';
$lang['pvplist5']='Miembro con más muertes';
$lang['pvplist6']='Miembro que más ha muerto';
$lang['pvplist7']='Miembro con la mejor media de muertes';
$lang['pvplist8']='Miembro con la mejor media de derrotas';

$lang['hslist']=' Estadísticas Sistema Honor';
$lang['hslist1']='Miembro con Mayor Rango';
$lang['hslist2']='Máximo Rango';
$lang['hslist3']='Mayores Muertes con Honor';
$lang['hslist4']='Mejor Clasificado';

$lang['Druid']='Druida';
$lang['Hunter']='Cazador';
$lang['Mage']='Mago';
$lang['Paladin']='Paladín';
$lang['Priest']='Sacerdote';
$lang['Rogue']='Pícaro';
$lang['Shaman']='Chamán';
$lang['Warlock']='Brujo';
$lang['Warrior']='Guerrero';

$lang['today']='Hoy';
$lang['todayhk']='Hoy HK';
$lang['todaycp']='Hoy CP';
$lang['yesterday']='Ayer';
$lang['yesthk']='Ayer HK';
$lang['yestcp']='Ayer CP';
$lang['thisweek']='Esta Semana';
$lang['lastweek']='Semana Pasada';
$lang['lifehk']='Semana HK';
$lang['alltime']='Vida';
$lang['honorkills']='Muertes con Honor';
$lang['dishonorkills']='Muertes sin Honor';
$lang['honor']='Honor';
$lang['standing']='Prestigio';
$lang['highestrank']='Máximo Rango';
$lang['arena']='Arena';

$lang['totalwins']='Victorias Totales';
$lang['totallosses']='Derrotas Totales';
$lang['totaloverall']='Total';
$lang['win_average']='Promedio de diferencias de nivel (Victorias)';
$lang['loss_average']='Promedio de diferencias de nivel (Derrotas)';

// These need to be EXACTLY what PvPLog stores them as
$lang['alterac_valley']='Valle de Alterac';
$lang['arathi_basin']='Cuenca de Arathi';
$lang['warsong_gulch']='Garganta Grito de Guerra';

$lang['world_pvp']='JcJ Mundial';
$lang['versus_guilds']='Contra Hermandades';
$lang['versus_players']='Contra Jugadores';
$lang['bestsub']='Mejor Subzona';
$lang['worstsub']='Peor Subzona';
$lang['killedmost']='El más asesinado';
$lang['killedmostby']='El que más nos ha asesinado';
$lang['gkilledmost']='La hermandad más asesinada';
$lang['gkilledmostby']='El que más ha asesinado a nuestra hermandad';

$lang['wins']='Victorias';
$lang['losses']='Derrotas';
$lang['overall']='Total';
$lang['best_zone']='Mejor Zona';
$lang['worst_zone']='Peor Zona';
$lang['most_killed']='El más asesinado';
$lang['most_killed_by']='El que más nos ha asesinado';

$lang['when']='Fecha';
$lang['guild']='Hermandad';
$lang['leveldiff']='Dif Nivel';
$lang['result']='Resultado';
$lang['zone2']='Zona';
$lang['subzone']='Subzona';
$lang['bg']='Campo de Batalla';
$lang['yes']='Sí';
$lang['no']='No';
$lang['win']='Gana';
$lang['loss']='Pierde';
$lang['kills']='Asesinatos';
$lang['unknown']='Desconocido';

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
$lang['kill_lost_hist']='Kill/Loss history for %1$s (%2$s %3$s) of %4$s';
$lang['kill_lost_hist_guild'] = 'Kill/Loss history for Guild &quot;%1$s&quot;';
$lang['solo_win_loss'] = 'Solo Win/Loss Ratios (Level differences -7 to +7 counted)';

//strings for Rep-tab
$lang['exalted']='Exaltado';
$lang['revered']='Reverenciado';
$lang['honored']='Honrado';
$lang['friendly']='Amistoso';
$lang['neutral']='Neutral';
$lang['unfriendly']='Antipático';
$lang['hostile']='Hostil';
$lang['hated']='Odiado';
$lang['atwar']='En Guerra';
$lang['notatwar']='En Paz';

// language definitions for the rogue instance keys 'fix'
$lang['thievestools']='Thieves\\\' Tools';
$lang['lockpicking']='Lockpicking';
// END

	// Quests page external links (on character quests page)
		// $lang['questlinks'][#]['name']  This is the name displayed on the quests page
		// $lang['questlinks'][#]['url#']  This is the URL used for the quest lookup

		$lang['questlinks'][0]['name']='Thottbot';
		$lang['questlinks'][0]['url1']='http://www.thottbot.com/?f=q&amp;title=';
		$lang['questlinks'][0]['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$lang['questlinks'][0]['url3']='&amp;maxl=';

		$lang['questlinks'][1]['name']='Allakhazam';
		$lang['questlinks'][1]['url1']='http://wow.allakhazam.com/db/qlookup.html?name=';
		$lang['questlinks'][1]['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$lang['questlinks'][1]['url3']='&amp;maxl=';

		$lang['questlinks'][2]['name']='WoW-Lista';
		$lang['questlinks'][2]['url1']='http://www.wow-lista.com/buscadormision.php?titulo=';
		$lang['questlinks'][2]['url2']='&amp;descripcion=&amp;nivelde=';
		$lang['questlinks'][2]['url3']='&amp;nivelhasta=';

		//$lang['questlinks'][3]['name']='WoWHead';
		//$lang['questlinks'][3]['url1']='http://www.wowhead.com/?quests&amp;filter=ti=';
		//$lang['questlinks'][3]['url2']=';minle=';
		//$lang['questlinks'][3]['url3']=';maxle=';

// Items external link
// Add as manu item links as you need
// Just make sure their names are unique
	$lang['itemlink'] = 'Item Links';
	$lang['itemlinks']['Thottbot'] = 'http://www.thottbot.com/index.cgi?i=';
	$lang['itemlinks']['Allakhazam'] = 'http://wow.allakhazam.com/search.html?q=';
	$lang['itemlinks']['WoW-Lista'] = 'http://www.wow-lista.com/buscador.php?abuscar=';
	//$lang['itemlinks']['WoWHead'] = 'http://www.wowhead.com/?items&amp;filter=na=';


// definitions for the questsearchpage
	$lang['search1']="De la siguiente lista escoge una zona o un nombre de misión para ver quien está trabajando en ello.<br />\n<small>Nótese que si el nivel de la misión no es el mismo para todos los miembros listados de la hermandad, pueden estar en otra parte de una misión de múltiples partes.</small>";
	$lang['search2']='Buscar por Zona';
	$lang['search3']='Buscar por Nombre de Misión';

// Definition for item tooltip coloring
	$lang['tooltip_use']='Uso:';
	$lang['tooltip_requires']='Requiere';
	$lang['tooltip_reinforced']='Reforzado';
	$lang['tooltip_soulbound']='Ligado';
	$lang['tooltip_boe']='Se liga al equiparlo';
	$lang['tooltip_equip']='Equipar:';
	$lang['tooltip_equip_restores']='Equipar: Restaura';
	$lang['tooltip_equip_when']='Equipar: Cuando';
	$lang['tooltip_chance']='Probabilidad';
	$lang['tooltip_enchant']='Encantar';
	$lang['tooltip_set']='Set';
	$lang['tooltip_rank']='Rango';
	$lang['tooltip_next_rank']='Siguiente Rango';
	$lang['tooltip_spell_damage']='Daño por Hechizos';
	$lang['tooltip_school_damage']='\\+.*Daño por Hechizos';
	$lang['tooltip_healing_power']='Poder de Curación';
	$lang['tooltip_chance_hit']='Probabilidad al acertar:';
	$lang['tooltip_reinforced_armor']='Armadura Reforzada';
	$lang['tooltip_damage_reduction']='Reducción de daño';

// Warlock pet names for icon displaying
	$lang['Imp']='Diablillo';
	$lang['Voidwalker']='Abisario';
	$lang['Succubus']='Súcubo';
	$lang['Felhunter']='Manáfago';
	$lang['Infernal']='Inferno';
	$lang['Felguard']='Guardia Maldito';

// Max experiance for exp bar on char page
	$lang['max_exp']='Max XP';

// Error messages
	$lang['CPver_err']="La versión de CharacterProfiler utilizada para capturar los datos de este personaje es inferior a la versión mínima admitida para el envío.<br />\nPor favor, asegúrate de que estás utilizando al menos la versión ".$roster_conf['minCPver']." y has iniciado sesión y grabado los datos utilizando la misma.";
	$lang['PvPLogver_err']="La versión de PvPLog uutilizada para capturar los datos de este personaje es inferior a la versión mínima admitida para el envío.<br />\nPor favor, asegúrate de que estás utilizando al menos la versión ".$roster_conf['minPvPLogver'].", y si has actualizado recientemente PvPLog, asegúrate de que has borrado el antiguo fichero PvPLog.lua en la carpeta SavedVariables antes de enviar los datos.";
	$lang['GPver_err']="La versión de GuildProfiler uutilizada para capturar los datos de este personaje es inferior a la versión mínima admitida para el envío.<br />\nPor favor, asegúrate de que estás utilizando al menos la versión ".$roster_conf['minGPver'];


$lang['installer_install_0']='Installation of %1$s successful';
$lang['installer_install_1']='Installation of %1$s failed, but rollback successful';
$lang['installer_install_2']='Installation of %1$s failed, and rollback also failed';
$lang['installer_uninstall_0']='Uninstallation of %1$s successful';
$lang['installer_uninstall_1']='Uninstallation of %1$s failed, but rollback successful';
$lang['installer_uninstall_2']='Uninstallation of %1$s failed, and rollback also failed';
$lang['installer_upgrade_0']='Upgrade of %1$s successful';
$lang['installer_upgrade_1']='Upgrade of %1$s failed, but rollback successful';
$lang['installer_upgrade_2']='Upgrade of %1$s failed, and rollback also failed';

$lang['installer_icon'] = 'Icon';
$lang['installer_addoninfo'] = 'Addon Info';
$lang['installer_status'] = 'Status';
$lang['installer_installation'] = 'Installation';
$lang['installer_author'] = 'Author';
$lang['installer_log'] = 'Addon Manager Log';
$lang['installer_activated'] = 'Activated';
$lang['installer_deactivated'] = 'Deactivated';
$lang['installer_installed'] = 'Installed';
$lang['installer_upgrade_avail'] = 'Upgrade Available';
$lang['installer_not_installed'] = 'Not Installed';

$lang['installer_turn_off'] = 'Click to Deactivate';
$lang['installer_turn_on'] = 'Click to Activate';
$lang['installer_click_uninstall'] = 'Click to Uninstall';
$lang['installer_click_upgrade'] = 'Click to Upgrade';
$lang['installer_click_install'] = 'Click to Install';
$lang['installer_overwrite'] = 'Old Version Overwrite';
$lang['installer_replace_files'] = 'Replace files with latest version';

$lang['installer_error'] = 'Install Errors';
$lang['installer_invalid_type'] = 'Invalid install type';
$lang['installer_no_success_sql'] = 'Queries were not successfully added to the installer';
$lang['installer_no_class'] = 'The install definition file for %1$s did not contain a correct installation class';
$lang['installer_no_installdef'] = 'install.def.php for %1$s was not found';

$lang['installer_no_empty'] = 'Cannot install with an empty addon name';
$lang['installer_fetch_failed'] = 'Failed to fetch addon data for %1$s';
$lang['installer_addon_exist'] = '%1$s already contains %2$s. You can go back and uninstall that addon first, or upgrade it, or install this addon with a different name';
$lang['installer_no_upgrade'] = '%1$s doesn\`t contain data to upgrade from';
$lang['installer_not_upgradable'] = '%1$s cannot upgrade %2$s since its basename %3$s isn\'t in the list of upgradable addons';
$lang['installer_no_uninstall'] = '%1$s doesn\'t contain an addon to uninstall';
$lang['installer_not_uninstallable'] = '%1$s contains an addon %2$s which must be uninstalled with that addons\' uninstaller';


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
//   $lang['admin']['sqldebug'] = "Desc|Tooltip";

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
$lang['admin']['roster_upd_pw'] = "Roster Actualizaci&oacuten Clave|Esta es la clave para permitir las actualizaciones de la hermandad en la p&aacutegina de actualizaciones<br>Algunos addons usar&aacuten esta clave";
$lang['admin']['roster_dbver'] = "Roster Base de datos Versi&oacuten|Lave versi&oacuten de la base de datos";
$lang['admin']['version'] = "Roster Versi&oacuten|Versi&oacuten actual del Roster";
$lang['admin']['sqldebug'] = "Mensajes de SQL|Muestra errores de MySQL en comentarios en HTML";
$lang['admin']['debug_mode'] = "Depurar Modo|Depurar errores mostrados en los comentarios ";
$lang['admin']['sql_window'] = "SQL Window|Displays SQL Queries in a window in the footer";
$lang['admin']['minCPver'] = "Min CP versi&oacuten|M&iacutenima versi&oacuten permitida para usar el CharacterProfiler";
$lang['admin']['minGPver'] = "Min GP versi&oacuten|M&iacutenima versi&oacuten permitida para usar el GuildProfiler";
$lang['admin']['minPvPLogver'] = "Min PvPLog versi&oacuten|M&iacutenima versi&oacuten permitida para usar el PvPLog";
$lang['admin']['roster_lang'] = "Roster Lenguaje principal|Elige el lenguaje del interfaz";
$lang['admin']['default_page'] = "Default Page|Page to display if no page is specified in the url";
$lang['admin']['website_address'] = "Website dirección|Usada para el URL del logo, y para el link del nombre de la hermandad en el menú principal<br>Algunos addons del roster usarán esto";
$lang['admin']['roster_dir'] = "Roster URL|El path del URL al directorio del Roster<br>Esto es muy importante que este bien, si no ocurrir&aacuten muchos errores<br>(EJ: http://www.site.com/roster )<br><br>El nombre entero del URL no es necesario, en su lugar puedes poner el relativo<br>(EJ: /roster )";
$lang['admin']['interface_url'] = "Directorio del Interfaz|Directorio donde se encuentran las im&aacutegenes del interfaz<br>El predeterminado es &quot;img/&quot;<br><br>Puedes usar un path relativo o el completo";
$lang['admin']['img_suffix'] = "Extensi&oacuten de las im&aacutegenes del interfaz|El tipo de im&aacutegenes que usa tu interfaz";
$lang['admin']['alt_img_suffix'] = "Extensi&oacuten de las im&aacutegenes del interfaz Alt|Posibilidad alternativa de los tipos de im&aacutegenes para el interfaz";
$lang['admin']['img_url'] = "Directorio de im&aacutegenes del Roster|Directorio donde estan localizadas las imagenes del Roster<br>El predeterminado es &quot;img/&quot;<br><br>Puedes usar un path relativo o el completo";
$lang['admin']['timezone'] = "HoraZona|Mostrar&aacute la hora de tu regi&oacuten geogr&aacutefica";
$lang['admin']['localtimeoffset'] = "Diferencia horaria|La diferencia horaria desde el UTC/GMT<br>La hora del roster ser&aacute calculada con esta diferencia";
$lang['admin']['pvp_log_allow'] = "Permitir subir informaci&oacuten del PvPLog|Cambiando esto a &quot;no&quot; desactivaras mostrar la parte del PvPlog en &quot;update.php&quot;";
$lang['admin']['use_update_triggers'] = "Actualizar Addon Triggers|Esto se utiliza con addons que necesitan ser ejecutados mientras actualizas un personaje o la hermandad<br>Algunos addons requieren de esto para funcionar correctamente";

// guild_conf
$lang['admin']['guild_name'] = "Nombre de la hermandad|Debe ser exactamente igual a como esta escrito en el juego<br>o <u>NO</u> <u>PODRAS</u> subir personajes a la web";
$lang['admin']['server_name'] = "Nombre del servidor|Debe ser exactamente igual al del juego o <u>NO</u> <u>PODRAS</u> subir personajes";
$lang['admin']['guild_desc'] = "Descripci&oacuten de la hermandad|Introduce una corta descripci&oacuten de tu hermandad";
$lang['admin']['server_type'] = "Tipo de servidor|Esto es para determinar tu tipo de servidor en el WoW";
$lang['admin']['alt_type'] = "Alt-Texto b&uacutesqueda|Asignamos un texto a cada uno de los alts de la gente, para su siguiente localizaci&oacuten";
$lang['admin']['alt_location'] = "Campo b&uacutesqueda de alts|Indica el campo en el que se tiene que buscar la etiqueta indicada en el campo anterior";

// index_conf
$lang['admin']['index_pvplist'] = "Estad&iacutesticas de PVPlog|Mostrar las estad&iacutesticas del PVPlog en la p&aacutegina principal<br>Si tienes desactivado el addon PVPlog entonces no tienes que hacer nada aqu&iacute";
$lang['admin']['index_hslist'] = "Estad&iacutesticas Honor|Muestra las estad&iacutesticas de Honor en la p&aacutegina principal";
$lang['admin']['hspvp_list_disp'] = "JcJ/Honor Mostrar lista|Mostrar o esconder las listas de JcJ y Honor en la p&aacutegina principal<br>Las listas pueden ser abiertas y cerradas haciendo click en la cabecera<br><br>&quot;show&quot; mostrar&aacute el listado completo cuando la p&aacutegina cargue<br>&quot;hide&quot; no mostrar&aacute el listado cuando la p&aacutegina cargue (aparecer&aacute minimizado)";
$lang['admin']['index_member_tooltip'] = "Info de miembros|Muestra informaci&oacuten sobre un miembro en una ventanita al poner el cursor sobre su nombre";
$lang['admin']['index_update_inst'] = "Instrucciones subir datos|Muestra o esconde el texto de la parte inferior de la web donde se explica como subir personajes";
$lang['admin']['index_sort'] = "Orden lista miembros|Elige el orden en el que se mostrar&aacute la lista";
$lang['admin']['index_motd'] = "MDD de la hermandad|Muestra el mensaje del d&iacutea de la hermandad en la parte de arriba de la p&aacutegina";
$lang['admin']['index_level_bar'] = "Barra de nivel|Muestra una barra visual con el porcentaje de nivel en la p&aacutegina principal";
$lang['admin']['index_iconsize'] = "Tama&ntildeo icono|Selecciona el tama&ntildeo de los iconos en la p&aacutegina principal (JcJ, habilidades, clases, etc..)";
$lang['admin']['index_tradeskill_icon'] = "Iconos de habilidades|Activa los iconos de las habilidades en la p&aacutegina principal";
$lang['admin']['index_tradeskill_loc'] = "Mostrar columna de habilidades|Selecciona en que columna mostrar los iconos de las habilidades";
$lang['admin']['index_class_color'] = "Colores clases|Colorea los nombres de las clases";
$lang['admin']['index_classicon'] = "Iconos Clases|Muestra el icono de la clase de personaje";
$lang['admin']['index_honoricon'] = "JcJ Honor iconos|Muestra un icono con el rango de JcJ al lado del nombre del rango de honor";
$lang['admin']['index_prof'] = "Columna profesiones|Esto es para mostrar los iconos de las habilidades en la columna de las habilidades<br>Si quieres cambiarlos a otra columna, entonces debes desactivar esta opci&oacuten";
$lang['admin']['index_currenthonor'] = "Columna honor|Muestra la columna del honor";
$lang['admin']['index_note'] = "Columna nota|Muestra la columna de las notas de los personajes (p&uacuteblicas)";
$lang['admin']['index_title'] = "Columna Titulo Hermandad|Muestra la columna del t&iacutetulo de la hermandad";
$lang['admin']['index_hearthed'] = "Columna Hearthstone Loc.|Muestra la localizaci&oacuten donde cada personaje tiene su posada";
$lang['admin']['index_zone'] = "Columna Ultima zona Loc.|Muestra la columna de la &uacuteltima zona donde estuvo el personaje";
$lang['admin']['index_lastonline'] = "Columna Ultima vez visto|Muestra la columna con la fecha de la &uacuteltima vez visto un personaje";
$lang['admin']['index_lastupdate'] = "Columna Ultima actualizaci&oacuten|Muestra la &uacuteltima actualizaci&oacuten de cada personaje (la &uacuteltima vez que ha subido informaci&oacuten)";

// menu_conf
$lang['admin']['menu_left_pane'] = "Panel izquierdo (Lista r&aacutepida de miembros)|Muestra el panel izquierdo del men&uacute principal del roster<br>Este area contiene la lista r&aacutepida de miembros";
$lang['admin']['menu_right_pane'] = "Panel derecho (EstadoReino)|Muestra el panel derecho del men&uacute principal del roster<br>Este area contiene la imagen del estado real del reino";
$lang['admin']['menu_memberlog'] = "Link Member Log|Muestra el bot&oacuten Member Log";
$lang['admin']['menu_member_page'] = "MemberList Link|Controls display of the MemberList Link";
$lang['admin']['menu_guild_info'] = "Link Guild-Info|Muestra el bot&oacuten Guild-Info";
$lang['admin']['menu_stats_page'] = "Link estad&iacutesticas|Muestra el bot&oacuten Estad&iacutesticas";
$lang['admin']['menu_pvp_page'] = "Link estad&iacutesticas JcJ|Muestra el bot&oacuten estad&iacutesticas JcJ";
$lang['admin']['menu_honor_page'] = "Link Honor|Muestra el bot&oacuten de Honor";
$lang['admin']['menu_guildbank'] = "Link Guildbank|Muestra el bot&oacuten de GuildBank";
$lang['admin']['menu_keys_page'] = "Link Llaves de dungeon|Muestra el bot&oacuten Llaves de dungeon";
$lang['admin']['menu_tradeskills_page'] = "Link Profesiones|Muestra el bot&oacuten Profesiones";
$lang['admin']['menu_update_page'] = "Link Actualizar ficha|Muestra el bot&oacuten Actualizar ficha";
$lang['admin']['menu_quests_page'] = "Link Encontrar Grupo/Quests|Muestra el bot&oacuten Encontrar Grupo/Quests";
$lang['admin']['menu_search_page'] = "Link Buscar objetos o recetas|Muestra el bot&oacuten Buscar objetos o recetas";

// display_conf
$lang['admin']['stylesheet'] = "CSS Stylesheet|Indica la direci&oacuten del archivo styles.css";
$lang['admin']['roster_js'] = "Roster JS Archivo|Localizaci&oacuten del archivo Roster JavaScript";
$lang['admin']['tabcontent'] = "Dynamic Tab JS Archivos|Localizaci&oacuten de los archivos JavaScript para los men&uacutes din&aacutemicos";
$lang['admin']['overlib'] = "Tooltip JS Archivo|Localizaci&oacuten del archivo de la ventana JavaScript";
$lang['admin']['overlib_hide'] = "Overlib JS Fix|Localizaci&oacuten del archivo de JavaScript para arreglar Overlib en Internet Explorer";
$lang['admin']['logo'] = "URL para el logo de la cabecera|Escribe el URL completo de la imagen o en su lugar &quot;img/&quot;nombre_logo. <br>Esta imagen ser&aacute mostrada en la cabecera de la p&aacutegina";
$lang['admin']['roster_bg'] = "URL para la imagen del fondo|Indica el URL completo de la imagen a mostrar en el fondo de la web<br />o el nombre relativo &quot;img/&quot;";
$lang['admin']['motd_display_mode'] = "Modo de mostrar MDD|Elige como aparecer&aacute el texto del mensaje del d&iacutea<br><br>&quot;Texto&quot; - Muestra el MDD en rojo<br>&quot;Imagen&quot; - Muestra el MDD en una imagen (REQUERIDO GD!)";
$lang['admin']['compress_note'] = "Modo de mostrar las notas|Indica como ser&aacuten mostradas las notas de los jugadores<br /><br />&quot;Text&quot; - Muestra el texto de la nota<br />&quot;Icon&quot; - Muestra un icono y el mensaje en una ventanita";
$lang['admin']['signaturebackground'] = "img.php Fondo|Soporte para elegir el fondo de pantalla";
$lang['admin']['processtime'] = "Pag Gen. Tiempo/DB Colas|Mostrar &quot;Esta p&aacutegina fue creada en XXX segundos con XX preguntas ejecutadas&quot; en el pie del roster";

// data_links
$lang['admin']['questlink_1'] = "Enlace para misiones #1|Enlace externo para buscar Objetos/Misiones<br>Mira en tu archivo localization para configurar los enlaces.";
$lang['admin']['questlink_2'] = "Enlace para misiones #2|Enlace externo para buscar Objetos/Misiones<br>Mira en tu archivo localization para configurar los enlaces.";
$lang['admin']['questlink_3'] = "Enlace para misiones #3|Enlace externo para buscar Objetos/Misiones<br>Mira en tu archivo localization para configurar los enlaces.";
$lang['admin']['profiler'] = "Enlace para descargar CharacterProfiler|URL para descargar CharacterProfiler";
$lang['admin']['pvplogger'] = "Enlace para descargar PvPLog|URL para descargar PvPLog";
$lang['admin']['uploadapp'] = "Enlace para descargar UniUploader|URL para descargar UniUploader";

// char_conf
$lang['admin']['char_bodyalign'] = "P&aacutegina del personaje Alineaci&oacuten|Alineaci&oacuten de la informaci&oacuten en la p&aacutegina del personaje";
$lang['admin']['recipe_disp'] = "Recipe Display|Controls how the recipe lists display on page load<br />The lists can be collapsed and opened by clicking on the header<br /><br />&quot;show&quot; will fully display the lists when the page loads<br />&quot;hide&quot; will show the lists collapsed";
$lang['admin']['show_talents'] = "Talentos|Controla el modo de mostrar los talentos<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_spellbook'] = "Libro de hechizos|Controla el modo de mostrar el libro de hechizos<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_mail'] = "Correo|Controla el modo de mostrar el correo<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_inventory'] = "Bolsas|Controla el modo de mostrar las bolsas <br><br>opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_money'] = "Dinero|Controla el modo de mostrar el dinero<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_bank'] = "Banco|Controla el modo de mostrar el banco<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_recipes'] = "Recetas|Controla el modo de mostrar las recetas<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_quests'] = "Quests|Controla el modo de mostrar las quests<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_bg'] = "Informaci&oacuten del CampoBatalla del PVPlog|Controla el modo de mostrar la informaci&oacuten del Campo de Batalla del PVPlog<br>Requiere subir la informaci&oacuten del addon PvPLog<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_pvp'] = "Informaci&oacuten del PVPlog|Controla el modo de mostrar la informaci&oacuten del PVPlog<br>Requiere subir la informaci&oacuten del addon PvPLog<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_duels'] = "Informaci&oacuten de duelos del PVPlog|Controla el modo de mostrar la informaci&oacuten de los duelos del PVPlog<br>Requiere subir la informaci&oacuten del addon PvPLog<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_item_bonuses'] = "Bonus de objetos|Controla el modo de mostrar los Bonus de objetos<br><br>Las opciones son globales y afectan a todos los usuarios";
$lang['admin']['show_signature'] = "Mostrar firmas|Controla el modo de mostrar las im&aacutegenes de las firmas<br><span class=\"red\">Requiere el addon SigGen Roster</span><br><br>Las opciones son globales";
$lang['admin']['show_avatar'] = "Mostrar avatar|Controla el modo de mostrar la imagen del avatar<br><span class=\"red\">Requiere el addon SigGen Roster</span><br><br>Las opciones son globales";

// realmstatus_conf
$lang['admin']['realmstatus_url'] = "Estado real del reino URL|URL a la p&aacutegina de Blizzard's Realmstatus ";
$lang['admin']['rs_display'] = "Mostrar Informaci&oacuten|&quot;lleno&quot; mostrar&aacute el estado y el nombre del servidor, poblaci&oacuten y tipo<br>&quot;medio&quot; mostrar&aacute el estado del reino";
$lang['admin']['rs_mode'] = "Modo de mostrar|Como aparecer&aacute el EstadoReino<br><br>&quot;DIV Container&quot; - Muestra el reino en una imagen con un texto<br>&quot;Imagen&quot; - Muestra el ReinoEstado como una imagen (REQUERIDO GD!)";
$lang['admin']['realmstatus'] = "Nombre alternativo del reino|Algunos nombres de los servidores no permiten al ReinoEstado funcionar correctamente<br>A veces el nombre del servidor no es encontrado en la base de datos de la p&aacutegina de EstadoReino<br>Puedes activar esta opci&oacuten y as&iacute utilizar otro nombre para tu servidor<br><br>D&eacutejalo en blanco para utilizar el nombre elegido en la configuraci&oacuten de la hermandad";

// guildbank_conf
$lang['admin']['guildbank_ver'] = "GuildBanco|Banco de la hermandad<br><br>&quot;Tabla&quot; mostrar todos los objetos de todos los jugadores banco en una &uacutenica lista<br>&quot;Inventario&quot; muestra una tabla para cada uno de los jugadores banco";
$lang['admin']['bank_money'] = "Mostrar dinero|Muestra el dinero en el addon GuildBanco";
$lang['admin']['banker_rankname'] = "Texto para buscar un banco|Indica el texto con el que se localizar&aacute a un personaje banco";
$lang['admin']['banker_fieldname'] = "Campo de b&uacutesqueda de banquero|Indica el campo en el que se localiza el texto que has puesto en el apartado anterior";

// update_access
$lang['admin']['authenticated_user'] = "Acceso a Update.php|Controla el acceso a update.php<br /><br />Poniendo esta opcion en off desactivas el acceso para todo el mundo.";

// Character Display Settings
$lang['admin']['per_character_display'] = 'Pantalla Per-Character';
