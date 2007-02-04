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

// esES translation by maqjav, nekromant, BarryZGZ



//Instructions how to upload, as seen on the mainpage
$wordings['esES']['update_link']='Pulsa aquí para las instrucciones de actualización';
$wordings['esES']['update_instructions']='Instrucciones de Actualización';

$wordings['esES']['lualocation']='Pulsa Examinar y selecciona tus ficheros *.lua para el envío';

$wordings['esES']['filelocation']='se encuentra en<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*NOMBRE_DE_CUENTA*</i>\\\\SavedVariables';

$wordings['esES']['noGuild']='No puedo encontrar la hermandad en la base de datos. Por favor, actualiza primero los miembros.';
$wordings['esES']['nodata']="No puedo encontrar la hermandad: <b>'".$roster_conf['guild_name']."'</b> del servidor <b>'".$roster_conf['server_name']."'</b><br />Necesitas <a href=\"".getlink($module_name.'&amp;file=update')."\">incluir tu hermandad</a> y asegurarte de que has <a href=\"".adminlink($module_name)."\">terminado la configuración</a>";

$wordings['esES']['update_page']='Actualizar Perfil';
// NOT USED $wordings['esES']['updCharInfo']='Update Character Info';
$wordings['esES']['guild_nameNotFound']='No puedo actualizar &quot;%s&quot;. ¿Quizás no ha sido configurado?';
$wordings['esES']['guild_addonNotFound']='No puedo encontrar la hermandad. ¿Has instalado correctamente Guild Profiler?';

$wordings['esES']['ignored']='Ignorado';
$wordings['esES']['update_disabled']='Ha sido desactivado el acceso a Update.php';

// NOT USED $wordings['esES']['updGuildMembers']='Update Guild Members';
$wordings['esES']['nofileUploaded']='UniUploader no ha enviado ningún archivo, o ha enviado el archivo incorrecto.';
$wordings['esES']['roster_upd_pwLabel']='Clave de Actualización';
$wordings['esES']['roster_upd_pw_help']='(Es necesaria para actualizar los datos de la hermandad)';

// Updating Instructions

$index_text_uniloader = "(Puedes descargar este programa desde la web de WoWRoster, busca el instalador de UniUploader para obtener la última versión)";

$wordings['esES']['update_instruct']='
<strong>Actualizadores Automáticos Recomendados:</strong>
<ul>
<li>Utiliza <a href="'.$roster_conf['uploadapp'].'" target="_blank">UniUploader</a><br />
'.$index_text_uniloader.'</li>
</ul>
<strong>Instrucciones de Actualización:</strong>
<ol>
<li>Descarga <a href="'.$roster_conf['profiler'].'" target="_blank">Character Profiler</a></li>
<li>Extrae el zip en su propia carpeta en C:\Archivos de Programa\World of Warcraft\Interface\Addons\</li>
<li>Inicia WoW</li>
<li>Abre tu ventana de banco, misiones, y profesiones que contengan recetas</li>
<li>Desconecta/Sal de WoW (Mira más arriba si deseas utilizar UniUploader para enviar los datos automáticamente.)</li>
<li>Vete a <a href="'.getlink($module_name.'&amp;file=update').'">la página de actualización</a></li>
<li>'.$wordings['esES']['lualocation'].'</li>
</ol>';

$wordings['esES']['update_instructpvp']='
<strong>Estadísticas JcJ Opcionales:</strong>
<ol>
<li>Descarga <a href="'.$roster_conf['pvplogger'].'" target="_blank">PvPLog</a></li>
<li>Extrae PvPLog en la carpeta de Addons.</li>
<li>Haz duelos o combates JcJ</li>
<li>Envía PvPLog.lua</li>
</ol>';

$wordings['esES']['roster_credits']='Agradecimientos a <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, y <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> por el código original usado en este sitio.<br />
Página principal de WoWRoster - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft y Blizzard Entertainment son marcas registradas de Blizzard Entertainment, Inc. en los E.U.A. y/u otros países. El resto de marcas registradas pertenecen a sus respectivos propietarios.<br />
<a href="'.getlink($module_name.'&amp;file=credits').'">Créditos Adicionales</a>';


//Charset
$wordings['esES']['charset']="charset=utf-8";

$timeformat['esES'] = '%a %d %b, %H:%i'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$phptimeformat['esES'] = 'D d M, H:i';    // PHP date() Time format (example - 'D M jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


/**
 * Realmstatus Localizations
 */
$wordings['esES']['rs'] = array(
	'ERROR' => 'Error',
	'NOSTATUS' => 'Sin Estado',
	'UNKNOWN' => 'Desconocido',
	'RPPVP' => 'JdR JcJ',
	'PVE' => 'Normal',
	'PVP' => 'JcJ',
	'RP' => 'JdR',
	'OFFLINE' => 'Caido',
	'LOW' => 'Recomendado',
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
$inst_keys['esES']['A'] = array(
	'SG' => array( 'Quests', 'SG' =>
			'Llave de la Garganta de Fuego|4826',
			'The Horn of the Beast|',
			'Proof of Deed|',
			'At Last!|'
		),
	'Gnome' => array( 'Key-Only', 'Gnome' =>
			'Llave de taller|2288'
		),
	'SM' => array( 'Key-Only', 'SM' =>
			'La llave Escarlata|4445'
		),
	'ZF' => array( 'Parts', 'ZF' =>
			'Marra de Zul\\\'Farrak|5695',
			'Marra sacra|8250'
		),
	'Mauro' => array( 'Parts', 'Mauro' =>
			'Cetro de Celebras|19710',
			'Vara de Celebras|19549',
			'Diamante de Celebras|19545'
		),
	'BRDp' => array( 'Key-Only', 'BRDp' =>
			'Llave de celda de prisión|15545'
		),
	'BRDs' => array( 'Parts', 'BRDs' =>
			'Llave Sombratiniebla|2966',
			'Ferrovil|9673'
		),
	'DM' => array( 'Key-Only', 'DM' =>
			'Llave creciente|35607'
		),
	'Scholo' => array( 'Quests', 'Scholo' =>
			'Llave esqueleto|16854',
			'Scholomance|',
			'Skeletal Fragments|',
			'Mold Rhymes With...|',
			'Fire Plume Forged|',
			'Araj\\\'s Scarab|',
			'The Key to Scholomance|'
		),
	'Strath' => array( 'Key-Only', 'Strath' =>
			'Llave de la ciudad|13146'
		),
	'UBRS' => array( 'Parts', 'UBRS' =>
			'Lacre de ascensión|17057',
			'Sello de ascensión sin adornar|5370',
			'Gema de Cumbrerroca|5379',
			'Gema de Espina Ahumada|16095',
			'Gema de Hacha de Sangre|21777',
			'Sello de Ascensión sin forjar|24554||MS',
			'Sello de Ascensión forjado|19463||MS'
		),
	'Onyxia' => array( 'Quests', 'Onyxia' =>
			'Amuleto de Pirodraco|4829',
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
	'MC' => array( 'Key-Only', 'MC' =>
			'Quintaesencia eterna|22754'
		),
);


// HORDE KEYS
$inst_keys['esES']['H'] = array(
	'SG' => array( 'Key-Only', 'SG' =>
			'Llave de la Garganta de Fuego|4826'
		),
	'Gnome' => array( 'Key-Only', 'Gnome' =>
			'Llave de taller|2288'
		),
	'SM' => array( 'Key-Only', 'SM' =>
			'La llave Escarlata|4445'
		),
	'ZF' => array( 'Parts', 'ZF' =>
			'Marra de Zul\\\'Farrak|5695',
			'Marra sacra|8250'
		),
	'Mauro' => array( 'Parts', 'Mauro' =>
			'Cetro de Celebras|19710',
			'Vara de Celebras|19549',
			'Diamante de Celebras|19545'
		),
	'BRDp' => array( 'Key-Only', 'BRDp' =>
			'Llave de celda de prisión|15545'
		),
	'BRDs' => array( 'Parts', 'BRDs' =>
			'Llave Sombratiniebla|2966',
			'Ferrovil|9673'
		),
	'DM' => array( 'Key-Only', 'DM' =>
			'Llave creciente|35607'
		),
	'Scholo' => array( 'Quests', 'Scholo' =>
			'Llave esqueleto|16854',
			'Scholomance|',
			'Skeletal Fragments|',
			'Mold Rhymes With...|',
			'Fire Plume Forged|',
			'Araj\\\'s Scarab|',
			'The Key to Scholomance|'
		),
	'Strath' => array( 'Key-Only', 'Strath' =>
			'Llave de la ciudad|13146'
		),
	'UBRS' => array( 'Parts', 'UBRS' =>
			'Lacre de ascensión|17057',
			'Sello de ascensión sin adornar|5370',
			'Gema de Cumbrerroca|5379',
			'Gema de Espina Ahumada|16095',
			'Gema de Hacha de Sangre|21777',
			'Sello de Ascensión sin forjar|24554||MS',
			'Sello de Ascensión forjado|19463||MS'
		),
	'Onyxia' => array( 'Quests', 'Onyxia' =>
			'Amuleto de Pirodraco|4829',
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
	'MC' => array( 'Key-Only', 'MC' =>
			'Quintaesencia eterna|22754'
		),
);

//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$wordings['esES']['upload']='Enviar';
$wordings['esES']['required']='Requerido';
$wordings['esES']['optional']='Opcional';
$wordings['esES']['attack']='Ataque';
$wordings['esES']['defense']='Defensa';
$wordings['esES']['class']='Clase';
$wordings['esES']['race']='Raza';
$wordings['esES']['level']='Nivel';
$wordings['esES']['zone']='Última Zona';
$wordings['esES']['note']='Nota';
$wordings['esES']['title']='Título';
$wordings['esES']['name']='Nombre';
$wordings['esES']['health']='Salud';
$wordings['esES']['mana']='Maná';
$wordings['esES']['gold']='Oro';
$wordings['esES']['armor']='Armadura';
$wordings['esES']['lastonline']='Última Conexión';
$wordings['esES']['lastupdate']='Última Actualización';
$wordings['esES']['currenthonor']='Rango de Honor Actual';
$wordings['esES']['rank']='Rango';
$wordings['esES']['sortby']='Ordenar por %';
$wordings['esES']['total']='Total';
$wordings['esES']['hearthed']='Posada';
$wordings['esES']['recipes']='Recetas';
$wordings['esES']['bags']='Bolsas';
$wordings['esES']['character']='Personaje';
$wordings['esES']['bglog']='Registro Batallas';
$wordings['esES']['pvplog']='Registro JcJ';
$wordings['esES']['duellog']='Registro Duelos';
$wordings['esES']['duelsummary']='Resumen Duelos';
$wordings['esES']['money']='Dinero';
$wordings['esES']['bank']='Banco';
$wordings['esES']['guildbank']='Banco Clan';
$wordings['esES']['guildbank_totalmoney']='Fondos Totales Banco';
$wordings['esES']['raid']='CT_Raid';
$wordings['esES']['guildbankcontact']='Mantenido por (Contacto)';
$wordings['esES']['guildbankitem']='Nombre de Objeto y Descripción';
$wordings['esES']['quests']='Misiones';
$wordings['esES']['roster']='Roster';
$wordings['esES']['alternate']='Suplente';
$wordings['esES']['byclass']='Por Clase';
$wordings['esES']['menustats']='Estadísticas';
$wordings['esES']['menuhonor']='Honor';
$wordings['esES']['keys']='Llaves';
$wordings['esES']['team']='Buscar Equipo';
$wordings['esES']['search']='Búsqueda';
$wordings['esES']['update']='Última Actualización';
$wordings['esES']['credit']='Créditos';
$wordings['esES']['members']='Miembros';
$wordings['esES']['items']='Objetos';
$wordings['esES']['find']='Encontrar objeto que contenga';
$wordings['esES']['upprofile']='Envío Datos';
$wordings['esES']['backlink']='Volver al Inicio';
$wordings['esES']['gender']='Género';
$wordings['esES']['unusedtrainingpoints']='Puntos Entrenamiento No Usados';
$wordings['esES']['unusedtalentpoints']='Puntos Talento No Usados';
$wordings['esES']['questlog']='Registro Misiones';
$wordings['esES']['recipelist']='Lista Recetas';
$wordings['esES']['reagents']='Ingredientes';
$wordings['esES']['item']='Objeto';
$wordings['esES']['type']='Tipo';
$wordings['esES']['date']='Fecha';
$wordings['esES']['completedsteps'] = 'Partes Completas';
$wordings['esES']['currentstep'] = 'Parte Actual';
$wordings['esES']['uncompletedsteps'] = 'Partes Incompletas';
$wordings['esES']['key'] = 'Llave';
$wordings['esES']['timeplayed'] = 'Tiempo Jugado';
$wordings['esES']['timelevelplayed'] = 'Tiempo Jugado Nivel Actual';
$wordings['esES']['Addon'] = 'Addons';
$wordings['esES']['advancedstats'] = 'Estadísticas Avanzadas';
$wordings['esES']['itembonuses'] = 'Bonificaciones para objetos equipados';
$wordings['esES']['itembonuses2'] = 'Bonificaciones de objetos';
$wordings['esES']['crit'] = 'Crit';
$wordings['esES']['dodge'] = 'Esquivar';
$wordings['esES']['parry'] = 'Parar';
$wordings['esES']['block'] = 'Bloquear';
$wordings['esES']['realm'] = 'Reino';

// Memberlog
$wordings['esES']['memberlog'] = 'Registro';
$wordings['esES']['removed'] = 'Borrado';
$wordings['esES']['added'] = 'Añadido';
$wordings['esES']['no_memberlog'] = 'No existe registro de miembros';

$wordings['esES']['rosterdiag'] = 'Roster Diag.';
$wordings['esES']['Guild_Info'] = 'Info Clan';
$wordings['esES']['difficulty'] = 'Dificultad';
$wordings['esES']['recipe_4'] = 'Óptima';
$wordings['esES']['recipe_3'] = 'Media';
$wordings['esES']['recipe_2'] = 'Fácil';
$wordings['esES']['recipe_1'] = 'Trivial';
$wordings['esES']['roster_config'] = 'Config. Roster';

// Spellbook
$wordings['esES']['spellbook'] = 'Libro Hechizos';
$wordings['esES']['page'] = 'Página';
$wordings['esES']['general'] = 'General';
$wordings['esES']['prev'] = 'Anterior';
$wordings['esES']['next'] = 'Siguiente';

// Mailbox
$wordings['esES']['mailbox'] = 'Buzón';
$wordings['esES']['maildateutc'] = 'Fecha Correo';
$wordings['esES']['mail_item'] = 'Objeto';
$wordings['esES']['mail_sender'] = 'Remitente';
$wordings['esES']['mail_subject'] = 'Asunto';
$wordings['esES']['mail_expires'] = 'Correo Caduca';
$wordings['esES']['mail_money'] = 'Dinero Incluído';


//this needs to be exact as it is the wording in the db
$wordings['esES']['professions']='Profesiones';
$wordings['esES']['secondary']='Habilidades secundarias';
$wordings['esES']['Blacksmithing']='Herrería';
$wordings['esES']['Mining']='Minería';
$wordings['esES']['Herbalism']='Botánica';
$wordings['esES']['Alchemy']='Alquimia';
$wordings['esES']['Leatherworking']='Peletería';
$wordings['esES']['Jewelcrafting']='Joyería';
$wordings['esES']['Skinning']='Desollar';
$wordings['esES']['Tailoring']='Sastrería';
$wordings['esES']['Enchanting']='Encantamiento';
$wordings['esES']['Engineering']='Ingeniería';
$wordings['esES']['Cooking']='Cocina';
$wordings['esES']['Fishing']='Pesca';
$wordings['esES']['First Aid']='Primeros Auxilios';
$wordings['esES']['Poisons']='Venenos';
$wordings['esES']['backpack']='Mochila';
$wordings['esES']['PvPRankNone']='ninguno';

// Uses preg_match() to find required level in recipe tooltip
$wordings['esES']['requires_level'] = '/Necesitas ser de nivel ([\d]+)/';

//Tradeskill-Array
$tsArray['esES'] = array (
	$wordings['esES']['Alchemy'],
	$wordings['esES']['Herbalism'],
	$wordings['esES']['Blacksmithing'],
	$wordings['esES']['Mining'],
	$wordings['esES']['Leatherworking'],
	$wordings['esES']['Jewelcrafting'],
	$wordings['esES']['Skinning'],
	$wordings['esES']['Tailoring'],
	$wordings['esES']['Enchanting'],
	$wordings['esES']['Engineering'],
	$wordings['esES']['Cooking'],
	$wordings['esES']['Fishing'],
	$wordings['esES']['First Aid'],
	$wordings['esES']['Poisons'],
);

//Tradeskill Icons-Array
$wordings['esES']['ts_iconArray'] = array (
	$wordings['esES']['Alchemy']=>'Trade_Alchemy',
	$wordings['esES']['Herbalism']=>'Trade_Herbalism',
	$wordings['esES']['Blacksmithing']=>'Trade_BlackSmithing',
	$wordings['esES']['Mining']=>'Trade_Mining',
	$wordings['esES']['Leatherworking']=>'Trade_LeatherWorking',
	$wordings['esES']['Jewelcrafting']=>'INV_Misc_Gem_02',
	$wordings['esES']['Skinning']=>'INV_Misc_Pelt_Wolf_01',
	$wordings['esES']['Tailoring']=>'Trade_Tailoring',
	$wordings['esES']['Enchanting']=>'Trade_Engraving',
	$wordings['esES']['Engineering']=>'Trade_Engineering',
	$wordings['esES']['Cooking']=>'INV_Misc_Food_15',
	$wordings['esES']['Fishing']=>'Trade_Fishing',
	$wordings['esES']['First Aid']=>'Spell_Holy_SealOfSacrifice',
	$wordings['esES']['Poisons']=>'Ability_Poisons',
	'Equitación'=>'Ability_Mount_WhiteTiger',
	'Horse Riding'=>'Ability_Mount_RidingHorse',
	'Ram Riding'=>'Ability_Mount_MountainRam',
	'Mecazancudo Piloting'=>'Ability_Mount_MechaStrider',
	'Undead Horsemanship'=>'Ability_Mount_Undeadhorse',
	'Raptor Riding'=>'Ability_Mount_Raptor',
	'Kodo Riding'=>'Ability_Mount_Kodo_03',
	'Wolf Riding'=>'Ability_Mount_BlackDireWolf',
);

// Riding Skill Icons-Array
$wordings['esES']['riding'] = 'Equitación';
$wordings['esES']['ts_ridingIcon'] = array(
	'Elfo de la noche'=>'Ability_Mount_WhiteTiger',
	'Humano'=>'Ability_Mount_RidingHorse',
	'Enano'=>'Ability_Mount_MountainRam',
	'Gnomo'=>'Ability_Mount_MechaStrider',
	'No-muerto'=>'Ability_Mount_Undeadhorse',
	'Trol'=>'Ability_Mount_Raptor',
	'Tauren'=>'Ability_Mount_Kodo_03',
	'Orco'=>'Ability_Mount_BlackDireWolf',
	'Elfo de sangre' => 'Ability_Mount_CockatriceMount',
	'Draenei' => 'Ability_Mount_RidingElekk',
	'Paladín'=>'Ability_Mount_Dreadsteed',
	'Brujo'=>'Ability_Mount_NightmareHorse'
);

// Class Icons-Array
$wordings['esES']['class_iconArray'] = array (
	'Druida'=>'Ability_Druid_Maul',
	'Cazador'=>'INV_Weapon_Bow_08',
	'Mago'=>'INV_Staff_13',
	'Paladín'=>'Spell_Fire_FlameTounge',
	'Sacerdote'=>'Spell_Holy_LayOnHands',
	'Pícaro'=>'INV_ThrowingKnife_04',
	'Chamán'=>'Spell_Nature_BloodLust',
	'Brujo'=>'Spell_Shadow_Cripple',
	'Guerrero'=>'INV_Sword_25',
);

//skills
$skilltypes['esES'] = array(
	1 => 'Habilidades de clase',
	2 => 'Profesiones',
	3 => 'Habilidades secundarias',
	4 => 'Armas disponibles',
	5 => 'Armaduras disponibles',
	6 => 'Lenguas'
);

//tabs
$wordings['esES']['tab1']='Persj';
$wordings['esES']['tab2']='Mascota';
$wordings['esES']['tab3']='Rep';
$wordings['esES']['tab4']='Habilid';
$wordings['esES']['tab5']='Talentos';
$wordings['esES']['tab6']='JcJ';

$wordings['esES']['strength']='Fortaleza';
$wordings['esES']['strength_tooltip']='Aumenta tu poder de ataque con armas cuerpo a cuerpo.<br />Aumenta la cantidad de daño que puedes bloquear con un escudo.';
$wordings['esES']['agility']='Agilidad';
$wordings['esES']['agility_tooltip']= 'Aumenta tu poder de ataque con armas a distancia.<br />Aumenta las probabilidades de asestar un impacto crítico con todas las armas.<br />Aumenta tu armadura y las probabilidades de esquivar los golpes.';
$wordings['esES']['stamina']='Aguante';
$wordings['esES']['stamina_tooltip']= 'Aumenta tus puntos de salud.';
$wordings['esES']['intellect']='Inteligencia';
$wordings['esES']['intellect_tooltip']= 'Aumenta tus puntos de maná y la probabilidad de asestar un impacto crítico con hechizos.<br />Aumenta la velocidad a la que mejora tu habilidad con las armas.';
$wordings['esES']['spirit']='Espíritu';
$wordings['esES']['spirit_tooltip']= 'Aumenta tu velocidad de regeneración de salud y maná.';
$wordings['esES']['armor_tooltip']= 'Disminuye la cantidad de daño recibido por ataques físicos.<br />La reducción se determina por el nivel del que te ataca.';

$wordings['esES']['melee_att']='Cuerpo a cuerpo';
$wordings['esES']['melee_att_power']='Poder de ataque cuerpo a cuerpo';
$wordings['esES']['range_att']='A distancia';
$wordings['esES']['range_att_power']='Poder de ataque a distancia';
$wordings['esES']['power']='Poder';
$wordings['esES']['damage']='Daño';
$wordings['esES']['energy']='Energía';
$wordings['esES']['rage']='Ira';

$wordings['esES']['melee_rating']='Tasa de ataque';
$wordings['esES']['melee_rating_tooltip']='Tu velocidad de ataque afecta a las probabilidades de acertar a un enemigo<br /> y se mide por la habilidad con el arma que equipes en cada momento.';
$wordings['esES']['range_rating']='Tasa de ataque a distancia';
$wordings['esES']['range_rating_tooltip']='Tu velocidad de ataque afecta a las probabilidades de acertar a un enemigo<br /> y se mide por la habilidad con el arma que equipes en cada momento.';

$wordings['esES']['res_fire']='Resistencia al Fuego';
$wordings['esES']['res_fire_tooltip']='Aumenta tu resistencia al daño de Fuego.<br />Cuanto más alto sea el número, mayor será la resistencia.';
$wordings['esES']['res_nature']='Resistencia a la Naturaleza';
$wordings['esES']['res_nature_tooltip']='Aumenta tu resistencia al daño de Naturaleza.<br />Cuanto más alto sea el número, mayor será la resistencia.';
$wordings['esES']['res_arcane']='Resistencia a lo Arcano';
$wordings['esES']['res_arcane_tooltip']='Aumenta tu resistencia al daño Arcano.<br />Cuanto más alto sea el número, mayor será la resistencia.';
$wordings['esES']['res_frost']='Resistencia a la Escarcha';
$wordings['esES']['res_frost_tooltip']='Aumenta tu resistencia al daño de Escarcha.<br />Cuanto más alto sea el número, mayor será la resistencia.';
$wordings['esES']['res_shadow']='Resistencia a las Sombras';
$wordings['esES']['res_shadow_tooltip']='Aumenta tu resistencia al daño de Sombras.<br />Cuanto más alto sea el número, mayor será la resistencia.';

$wordings['esES']['empty_equip']='No hay objeto equipado';
$wordings['esES']['pointsspent']='Puntos Gastados:';
$wordings['esES']['none']='Ninguno';

$wordings['esES']['pvplist'] ='Estadísticas JcJ/PvP';
$wordings['esES']['pvplist1']='Hermandad a la que más hemos hecho sufrir';
$wordings['esES']['pvplist2']='Hermandad que más nos ha hecho sufrir';
$wordings['esES']['pvplist3']='Jugador al que más hemos matado';
$wordings['esES']['pvplist4']='Jugador que más nos ha matado';
$wordings['esES']['pvplist5']='Miembro con más muertes';
$wordings['esES']['pvplist6']='Miembro que más ha muerto';
$wordings['esES']['pvplist7']='Miembro con la mejor media de muertes';
$wordings['esES']['pvplist8']='Miembro con la mejor media de derrotas';

$wordings['esES']['hslist']=' Estadísticas Sistema Honor';
$wordings['esES']['hslist1']='Miembro con Mayor Rango';
$wordings['esES']['hslist2']='Máximo Rango';
$wordings['esES']['hslist3']='Mayores Muertes con Honor';
$wordings['esES']['hslist4']='Mejor Clasificado';

$wordings['esES']['Druid']='Druida';
$wordings['esES']['Hunter']='Cazador';
$wordings['esES']['Mage']='Mago';
$wordings['esES']['Paladin']='Paladín';
$wordings['esES']['Priest']='Sacerdote';
$wordings['esES']['Rogue']='Pícaro';
$wordings['esES']['Shaman']='Chamán';
$wordings['esES']['Warlock']='Brujo';
$wordings['esES']['Warrior']='Guerrero';

$wordings['esES']['today']='Hoy';
$wordings['esES']['yesterday']='Ayer';
$wordings['esES']['thisweek']='Esta Semana';
$wordings['esES']['lastweek']='Semana Pasada';
$wordings['esES']['alltime']='Vida';
$wordings['esES']['honorkills']='Muertes con Honor';
$wordings['esES']['dishonorkills']='Muertes sin Honor';
$wordings['esES']['honor']='Honor';
$wordings['esES']['standing']='Prestigio';
$wordings['esES']['highestrank']='Máximo Rango';
$wordings['esES']['arena']='Arena';

$wordings['esES']['totalwins']='Victorias Totales';
$wordings['esES']['totallosses']='Derrotas Totales';
$wordings['esES']['totaloverall']='Total';
$wordings['esES']['win_average']='Promedio de diferencias de nivel (Victorias)';
$wordings['esES']['loss_average']='Promedio de diferencias de nivel (Derrotas)';

// These need to be EXACTLY what PvPLog stores them as
$wordings['esES']['alterac_valley']='Valle de Alterac';
$wordings['esES']['arathi_basin']='Cuenca de Arathi';
$wordings['esES']['warsong_gulch']='Garganta Grito de Guerra';

$wordings['esES']['world_pvp']='JcJ Mundial';
$wordings['esES']['versus_guilds']='Contra Hermandades';
$wordings['esES']['versus_players']='Contra Jugadores';
$wordings['esES']['bestsub']='Mejor Subzona';
$wordings['esES']['worstsub']='Peor Subzona';
$wordings['esES']['killedmost']='El más asesinado';
$wordings['esES']['killedmostby']='El que más nos ha asesinado';
$wordings['esES']['gkilledmost']='La hermandad más asesinada';
$wordings['esES']['gkilledmostby']='El que más ha asesinado a nuestra hermandad';

$wordings['esES']['wins']='Victorias';
$wordings['esES']['losses']='Derrotas';
$wordings['esES']['overall']='Total';
$wordings['esES']['best_zone']='Mejor Zona';
$wordings['esES']['worst_zone']='Peor Zona';
$wordings['esES']['most_killed']='El más asesinado';
$wordings['esES']['most_killed_by']='El que más nos ha asesinado';

$wordings['esES']['when']='Fecha';
$wordings['esES']['guild']='Hermandad';
$wordings['esES']['leveldiff']='Dif Nivel';
$wordings['esES']['result']='Resultado';
$wordings['esES']['zone2']='Zona';
$wordings['esES']['subzone']='Subzona';
$wordings['esES']['bg']='Campo de Batalla';
$wordings['esES']['yes']='Sí';
$wordings['esES']['no']='No';
$wordings['esES']['win']='Gana';
$wordings['esES']['loss']='Pierde';
$wordings['esES']['kills']='Asesinatos';
$wordings['esES']['unknown']='Desconocido';

//strings for Rep-tab
$wordings['esES']['exalted']='Exaltado';
$wordings['esES']['revered']='Reverenciado';
$wordings['esES']['honored']='Honrado';
$wordings['esES']['friendly']='Amistoso';
$wordings['esES']['neutral']='Neutral';
$wordings['esES']['unfriendly']='Antipático';
$wordings['esES']['hostile']='Hostil';
$wordings['esES']['hated']='Odiado';
$wordings['esES']['atwar']='En Guerra';
$wordings['esES']['notatwar']='En Paz';

// language definitions for the rogue instance keys 'fix'
$wordings['esES']['thievestools']='Thieves\\\' Tools';
$wordings['esES']['lockpicking']='Lockpicking';
// END

	// Quests page external links (on character quests page)
		// questlinks[#]['lang']['name']  This is the name displayed on the quests page
		// questlinks[#]['lang']['url#']   This is the URL used for the quest lookup

		$questlinks[0]['esES']['name']='Thottbot';
		$questlinks[0]['esES']['url1']='http://www.thottbot.com/?f=q&amp;title=';
		$questlinks[0]['esES']['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$questlinks[0]['esES']['url3']='&amp;maxl=';

		$questlinks[1]['esES']['name']='Allakhazam';
		$questlinks[1]['esES']['url1']='http://wow.allakhazam.com/db/qlookup.html?name=';
		$questlinks[1]['esES']['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$questlinks[1]['esES']['url3']='&amp;maxl=';

		$questlinks[2]['esES']['name']='WoW-Lista';
		$questlinks[2]['esES']['url1']='http://www.wow-lista.com/buscadormision.php?titulo=';
		$questlinks[2]['esES']['url2']='&amp;descripcion=&amp;nivelde=';
		$questlinks[2]['esES']['url3']='&amp;nivelhasta=';

// Items external link
	$itemlink['esES']='http://www.thottbot.com/index.cgi?i=';
	//$itemlink['esES']='http://wow.allakhazam.com/search.html?q=';

// definitions for the questsearchpage
	$wordings['esES']['search1']="De la siguiente lista escoge una zona o un nombre de misión para ver quien está trabajando en ello.<br />\n<small>Nótese que si el nivel de la misión no es el mismo para todos los miembros listados de la hermandad, pueden estar en otra parte de una misión de múltiples partes.</small>";
	$wordings['esES']['search2']='Buscar por Zona';
	$wordings['esES']['search3']='Buscar por Nombre de Misión';

// Definition for item tooltip coloring
	$wordings['esES']['tooltip_use']='Uso:';
	$wordings['esES']['tooltip_requires']='Requiere';
	$wordings['esES']['tooltip_reinforced']='Reforzado';
	$wordings['esES']['tooltip_soulbound']='Ligado';
	$wordings['esES']['tooltip_boe']='Se liga al equiparlo';
	$wordings['esES']['tooltip_equip']='Equipar:';
	$wordings['esES']['tooltip_equip_restores']='Equipar: Restaura';
	$wordings['esES']['tooltip_equip_when']='Equipar: Cuando';
	$wordings['esES']['tooltip_chance']='Probabilidad';
	$wordings['esES']['tooltip_enchant']='Encantar';
	$wordings['esES']['tooltip_set']='Set';
	$wordings['esES']['tooltip_rank']='Rango';
	$wordings['esES']['tooltip_next_rank']='Siguiente Rango';
	$wordings['esES']['tooltip_spell_damage']='Daño por Hechizos';
	$wordings['esES']['tooltip_school_damage']='\\+.*Daño por Hechizos';
	$wordings['esES']['tooltip_healing_power']='Poder de Curación';
	$wordings['esES']['tooltip_chance_hit']='Probabilidad al acertar:';
	$wordings['esES']['tooltip_reinforced_armor']='Armadura Reforzada';
	$wordings['esES']['tooltip_damage_reduction']='Reducción de daño';

// Warlock pet names for icon displaying
	$wordings['esES']['Imp']='Diablillo';
	$wordings['esES']['Voidwalker']='Abisario';
	$wordings['esES']['Succubus']='Súcubo';
	$wordings['esES']['Felhunter']='Manáfago';
	$wordings['esES']['Infernal']='Inferno';
	$wordings['esES']['Felguard']='Guardia Maldito';

// Max experiance for exp bar on char page
	$wordings['esES']['max_exp']='Max XP';

// Error messages
	$wordings['esES']['CPver_err']="La versión de CharacterProfiler utilizada para capturar los datos de este personaje es inferior a la versión mínima admitida para el envío.<br />\nPor favor, asegúrate de que estás utilizando al menos la versión ".$roster_conf['minCPver']." y has iniciado sesión y grabado los datos utilizando la misma.";
	$wordings['esES']['PvPLogver_err']="La versión de PvPLog uutilizada para capturar los datos de este personaje es inferior a la versión mínima admitida para el envío.<br />\nPor favor, asegúrate de que estás utilizando al menos la versión ".$roster_conf['minPvPLogver'].", y si has actualizado recientemente PvPLog, asegúrate de que has borrado el antiguo fichero PvPLog.lua en la carpeta SavedVariables antes de enviar los datos.";
	$wordings['esES']['GPver_err']="La versión de GuildProfiler uutilizada para capturar los datos de este personaje es inferior a la versión mínima admitida para el envío.<br />\nPor favor, asegúrate de que estás utilizando al menos la versión ".$roster_conf['minGPver'];






/******************************
 * Roster Admin Strings
 ******************************/

// Submit/Reset confirm questions
$wordings['esES']['confirm_config_submit'] = 'Esto guardar&aacute los datos en la base de datos. �Estás seguro?';
$wordings['esES']['confirm_config_reset'] = 'Esto resetear&aacute a los valores antes de modificarlo. Est&aacutes seguro?';

// Main Menu words
$wordings['esES']['admin']['main_conf'] = 'Opciones Principales';
$wordings['esES']['admin']['guild_conf'] = 'Configuraci&oacuten de la hermandad';
$wordings['esES']['admin']['index_conf'] = 'P&aacutegina principal';
$wordings['esES']['admin']['menu_conf'] = 'Men&uacute';
$wordings['esES']['admin']['display_conf'] = 'Mostrar Configuraci&oacuten';
$wordings['esES']['admin']['char_conf'] = 'P&aacutegina de personaje';
$wordings['esES']['admin']['realmstatus_conf'] = 'Estado del reino';
$wordings['esES']['admin']['guildbank_conf'] = 'Guildbanco';
$wordings['esES']['admin']['data_links'] = 'Links de los Objetos/Quests';
$wordings['esES']['admin']['update_access'] = 'update.php Acceso';


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
$wordings['esES']['admin']['roster_upd_pw'] = "Roster Actualizaci&oacuten Clave|Esta es la clave para permitir las actualizaciones de la hermandad en la p&aacutegina de actualizaciones<br>Algunos addons usar&aacuten esta clave";
$wordings['esES']['admin']['roster_dbver'] = "Roster Base de datos Versi&oacuten|Lave versi&oacuten de la base de datos";
$wordings['esES']['admin']['version'] = "Roster Versi&oacuten|Versi&oacuten actual del Roster";
$wordings['esES']['admin']['sqldebug'] = "SQL Debug Salida|Imprime las estad&iacutesticas en comentarios html de MySQL Debug";
$wordings['esES']['admin']['debug_mode'] = "Debug Mode|Full debug trace in error messages";
$wordings['esES']['admin']['sql_window'] = "SQL Window|Displays SQL Queries in a window in the footer";
$wordings['esES']['admin']['minCPver'] = "Min CP versi&oacuten|M&iacutenima versi&oacuten permitida para usar el CharacterProfiler";
$wordings['esES']['admin']['minGPver'] = "Min GP versi&oacuten|M&iacutenima versi&oacuten permitida para usar el GuildProfiler";
$wordings['esES']['admin']['minPvPLogver'] = "Min PvPLog versi&oacuten|M&iacutenima versi&oacuten permitida para usar el PvPLog";
$wordings['esES']['admin']['roster_lang'] = "Roster Lenguaje principal|Elige el lenguaje del interfaz";
$wordings['esES']['admin']['website_address'] = "Website direci&oacuten|Usada para el URL del logo, y para el link del nombre de la hermandad en el men&uacute principal<br>Algunos addons del roster usar&aacuten esto";
$wordings['esES']['admin']['roster_dir'] = "Roster URL|El path del URL al directorio del Roster<br>Esto es muy importante que este bien, si no ocurrir&aacuten muchos errores<br>(EJ: http://www.site.com/roster )<br><br>El nombre entero del URL no es necesario, en su lugar puedes poner el relativo<br>(EJ: /roster )";
$wordings['esES']['admin']['server_name_comp'] = "char.php Compatibilidad|Si la p&aacutegina de tu personaje no funciona, prueba a activar esta opci&oacuten";
$wordings['esES']['admin']['interface_url'] = "Directorio del Interfaz|Directorio donde se encuentran las im&aacutegenes del interfaz<br>El predeterminado es &quot;img/&quot;<br><br>Puedes usar un path relativo o el completo";
$wordings['esES']['admin']['img_suffix'] = "Extensi&oacuten de las im&aacutegenes del interfaz|El tipo de im&aacutegenes que usa tu interfaz";
$wordings['esES']['admin']['alt_img_suffix'] = "Extensi&oacuten de las im&aacutegenes del interfaz Alt|Posibilidad alternativa de los tipos de im&aacutegenes para el interfaz";
$wordings['esES']['admin']['img_url'] = "Directorio de im&aacutegenes del Roster|Directorio donde estan localizadas las imagenes del Roster<br>El predeterminado es &quot;img/&quot;<br><br>Puedes usar un path relativo o el completo";
$wordings['esES']['admin']['timezone'] = "HoraZona|Mostrar&aacute la hora de tu regi&oacuten geogr&aacutefica";
$wordings['esES']['admin']['localtimeoffset'] = "Diferencia horaria|La diferencia horaria desde el UTC/GMT<br>La hora del roster ser&aacute calculada con esta diferencia";
$wordings['esES']['admin']['pvp_log_allow'] = "Permitir subir informaci&oacuten del PvPLog|Cambiando esto a &quot;no&quot; desactivaras mostrar la parte del PvPlog en &quot;update.php&quot;";
$wordings['esES']['admin']['use_update_triggers'] = "Actualizar Addon Triggers|Esto se utiliza con addons que necesitan ser ejecutados mientras actualizas un personaje o la hermandad<br>Algunos addons requieren de esto para funcionar correctamente";

// guild_conf
$wordings['esES']['admin']['guild_name'] = "Nombre de la hermandad|Debe ser exactamente igual a como esta escrito en el juego<br>o <u>NO</u> <u>PODRAS</u> subir personajes a la web";
$wordings['esES']['admin']['server_name'] = "Nombre del servidor|Debe ser exactamente igual al del juego o <u>NO</u> <u>PODRAS</u> subir personajes";
$wordings['esES']['admin']['guild_desc'] = "Descripci&oacuten de la hermandad|Introduce una corta descripci&oacuten de tu hermandad";
$wordings['esES']['admin']['server_type'] = "Tipo de servidor|Esto es para determinar tu tipo de servidor en el WoW";
$wordings['esES']['admin']['alt_type'] = "Alt-Texto b&uacutesqueda|Asignamos un texto a cada uno de los alts de la gente, para su siguiente localizaci&oacuten";
$wordings['esES']['admin']['alt_location'] = "Campo b&uacutesqueda de alts|Indica el campo en el que se tiene que buscar la etiqueta indicada en el campo anterior";

// index_conf
$wordings['esES']['admin']['index_pvplist'] = "Estad&iacutesticas de PVPlog|Mostrar las estad&iacutesticas del PVPlog en la p&aacutegina principal<br>Si tienes desactivado el addon PVPlog entonces no tienes que hacer nada aqu&iacute";
$wordings['esES']['admin']['index_hslist'] = "Estad&iacutesticas Honor|Muestra las estad&iacutesticas de Honor en la p&aacutegina principal";
$wordings['esES']['admin']['hspvp_list_disp'] = "JcJ/Honor Mostrar lista|Mostrar o esconder las listas de JcJ y Honor en la p&aacutegina principal<br>Las listas pueden ser abiertas y cerradas haciendo click en la cabecera<br><br>&quot;show&quot; mostrar&aacute el listado completo cuando la p&aacutegina cargue<br>&quot;hide&quot; no mostrar&aacute el listado cuando la p&aacutegina cargue (aparecer&aacute minimizado)";
$wordings['esES']['admin']['index_member_tooltip'] = "Info de miembros|Muestra informaci&oacuten sobre un miembro en una ventanita al poner el cursor sobre su nombre";
$wordings['esES']['admin']['index_update_inst'] = "Instrucciones subir datos|Muestra o esconde el texto de la parte inferior de la web donde se explica como subir personajes";
$wordings['esES']['admin']['index_sort'] = "Orden lista miembros|Elige el orden en el que se mostrar&aacute la lista";
$wordings['esES']['admin']['index_motd'] = "MDD de la hermandad|Muestra el mensaje del d&iacutea de la hermandad en la parte de arriba de la p&aacutegina";
$wordings['esES']['admin']['index_level_bar'] = "Barra de nivel|Muestra una barra visual con el porcentaje de nivel en la p&aacutegina principal";
$wordings['esES']['admin']['index_iconsize'] = "Tama&ntildeo icono|Selecciona el tama&ntildeo de los iconos en la p&aacutegina principal (JcJ, habilidades, clases, etc..)";
$wordings['esES']['admin']['index_tradeskill_icon'] = "Iconos de habilidades|Activa los iconos de las habilidades en la p&aacutegina principal";
$wordings['esES']['admin']['index_tradeskill_loc'] = "Mostrar columna de habilidades|Selecciona en que columna mostrar los iconos de las habilidades";
$wordings['esES']['admin']['index_class_color'] = "Colores clases|Colorea los nombres de las clases";
$wordings['esES']['admin']['index_classicon'] = "Iconos Clases|Muestra el icono de la clase de personaje";
$wordings['esES']['admin']['index_honoricon'] = "JcJ Honor iconos|Muestra un icono con el rango de JcJ al lado del nombre del rango de honor";
$wordings['esES']['admin']['index_prof'] = "Columna profesiones|Esto es para mostrar los iconos de las habilidades en la columna de las habilidades<br>Si quieres cambiarlos a otra columna, entonces debes desactivar esta opci&oacuten";
$wordings['esES']['admin']['index_currenthonor'] = "Columna honor|Muestra la columna del honor";
$wordings['esES']['admin']['index_note'] = "Columna nota|Muestra la columna de las notas de los personajes (p&uacuteblicas)";
$wordings['esES']['admin']['index_title'] = "Columna Titulo Hermandad|Muestra la columna del t&iacutetulo de la hermandad";
$wordings['esES']['admin']['index_hearthed'] = "Columna Hearthstone Loc.|Muestra la localizaci&oacuten donde cada personaje tiene su posada";
$wordings['esES']['admin']['index_zone'] = "Columna Ultima zona Loc.|Muestra la columna de la &uacuteltima zona donde estuvo el personaje";
$wordings['esES']['admin']['index_lastonline'] = "Columna Ultima vez visto|Muestra la columna con la fecha de la &uacuteltima vez visto un personaje";
$wordings['esES']['admin']['index_lastupdate'] = "Columna Ultima actualizaci&oacuten|Muestra la &uacuteltima actualizaci&oacuten de cada personaje (la &uacuteltima vez que ha subido informaci&oacuten)";

// menu_conf
$wordings['esES']['admin']['menu_left_pane'] = "Panel izquierdo (Lista r&aacutepida de miembros)|Muestra el panel izquierdo del men&uacute principal del roster<br>Este area contiene la lista r&aacutepida de miembros";
$wordings['esES']['admin']['menu_right_pane'] = "Panel derecho (EstadoReino)|Muestra el panel derecho del men&uacute principal del roster<br>Este area contiene la imagen del estado real del reino";
$wordings['esES']['admin']['menu_memberlog'] = "Link Member Log|Muestra el bot&oacuten Member Log";
$wordings['esES']['admin']['menu_guild_info'] = "Link Guild-Info|Muestra el bot&oacuten Guild-Info";
$wordings['esES']['admin']['menu_stats_page'] = "Link estad&iacutesticas|Muestra el bot&oacuten Estad&iacutesticas";
$wordings['esES']['admin']['menu_pvp_page'] = "Link estad&iacutesticas JcJ|Muestra el bot&oacuten estad&iacutesticas JcJ";
$wordings['esES']['admin']['menu_honor_page'] = "Link Honor|Muestra el bot&oacuten de Honor";
$wordings['esES']['admin']['menu_guildbank'] = "Link Guildbank|Muestra el bot&oacuten de GuildBank";
$wordings['esES']['admin']['menu_keys_page'] = "Link Llaves de dungeon|Muestra el bot&oacuten Llaves de dungeon";
$wordings['esES']['admin']['menu_tradeskills_page'] = "Link Profesiones|Muestra el bot&oacuten Profesiones";
$wordings['esES']['admin']['menu_update_page'] = "Link Actualizar ficha|Muestra el bot&oacuten Actualizar ficha";
$wordings['esES']['admin']['menu_quests_page'] = "Link Encontrar Grupo/Quests|Muestra el bot&oacuten Encontrar Grupo/Quests";
$wordings['esES']['admin']['menu_search_page'] = "Link Buscar objetos o recetas|Muestra el bot&oacuten Buscar objetos o recetas";

// display_conf
$wordings['esES']['admin']['stylesheet'] = "CSS Stylesheet|Indica la direci&oacuten del archivo styles.css";
$wordings['esES']['admin']['roster_js'] = "Roster JS Archivo|Localizaci&oacuten del archivo Roster JavaScript";
$wordings['esES']['admin']['tabcontent'] = "Dynamic Tab JS Archivos|Localizaci&oacuten de los archivos JavaScript para los men&uacutes din&aacutemicos";
$wordings['esES']['admin']['overlib'] = "Tooltip JS Archivo|Localizaci&oacuten del archivo de la ventana JavaScript";
$wordings['esES']['admin']['overlib_hide'] = "Overlib JS Fix|Localizaci&oacuten del archivo de JavaScript para arreglar Overlib en Internet Explorer";
$wordings['esES']['admin']['logo'] = "URL para el logo de la cabecera|Escribe el URL completo de la imagen o en su lugar &quot;img/&quot;nombre_logo. <br>Esta imagen ser&aacute mostrada en la cabecera de la p&aacutegina";
$wordings['esES']['admin']['roster_bg'] = "URL para la imagen del fondo|Indica el URL completo de la imagen a mostrar en el fondo de la web<br />o el nombre relativo &quot;img/&quot;";
$wordings['esES']['admin']['motd_display_mode'] = "Modo de mostrar MDD|Elige como aparecer&aacute el texto del mensaje del d&iacutea<br><br>&quot;Texto&quot; - Muestra el MDD en rojo<br>&quot;Imagen&quot; - Muestra el MDD en una imagen (REQUERIDO GD!)";
$wordings['esES']['admin']['compress_note'] = "Modo de mostrar las notas|Indica como ser&aacuten mostradas las notas de los jugadores<br /><br />&quot;Text&quot; - Muestra el texto de la nota<br />&quot;Icon&quot; - Muestra un icono y el mensaje en una ventanita";
$wordings['esES']['admin']['signaturebackground'] = "img.php Fondo|Soporte para elegir el fondo de pantalla";
$wordings['esES']['admin']['processtime'] = "Pag Gen. Tiempo/DB Colas|Mostrar &quot;Esta p&aacutegina fue creada en XXX segundos con XX preguntas ejecutadas&quot; en el pie del roster";
$wordings['esES']['admin']['item_stats'] = "Item Stats Mod|If you have item_stats installed, turn this on";

// data_links
$wordings['esES']['admin']['questlink_1'] = "Quest Link #1|Link externo para objetos/Quest<br>Mira en tu archivo localizationpara la configuracion de los link.";
$wordings['esES']['admin']['questlink_2'] = "Quest Link #2|Link externo para objetos/Quest<br>Mira en tu archivo localizationpara la configuracion de los link.";
$wordings['esES']['admin']['questlink_3'] = "Quest Link #3|Link externo para objetos/Quest<br>Mira en tu archivo localizationpara la configuracion de los link.";
$wordings['esES']['admin']['profiler'] = "Descargar CharacterProfiler link|URL para descargar CharacterProfiler";
$wordings['esES']['admin']['pvplogger'] = "Descargar PvPLog link|URL para descargar PvPLog";
$wordings['esES']['admin']['uploadapp'] = "Descargar UniUploader link|URL para descargar UniUploader";

// char_conf
$wordings['esES']['admin']['char_bodyalign'] = "P&aacutegina del personaje Alineaci&oacuten|Alineaci&oacuten de la informaci&oacuten en la p&aacutegina del personaje";
$wordings['esES']['admin']['show_talents'] = "Talentos|Controla el modo de mostrar los talentos<br><br>Las opciones son globales y afectan a todos los usuarios";
$wordings['esES']['admin']['show_spellbook'] = "Libro de hechizos|Controla el modo de mostrar el libro de hechizos<br><br>Las opciones son globales y afectan a todos los usuarios";
$wordings['esES']['admin']['show_mail'] = "Correo|Controla el modo de mostrar el correo<br><br>Las opciones son globales y afectan a todos los usuarios";
$wordings['esES']['admin']['show_inventory'] = "Bolsas|Controla el modo de mostrar las bolsas <br><br>opciones son globales y afectan a todos los usuarios";
$wordings['esES']['admin']['show_money'] = "Dinero|Controla el modo de mostrar el dinero<br><br>Las opciones son globales y afectan a todos los usuarios";
$wordings['esES']['admin']['show_bank'] = "Banco|Controla el modo de mostrar el banco<br><br>Las opciones son globales y afectan a todos los usuarios";
$wordings['esES']['admin']['show_recipes'] = "Recetas|Controla el modo de mostrar las recetas<br><br>Las opciones son globales y afectan a todos los usuarios";
$wordings['esES']['admin']['show_quests'] = "Quests|Controla el modo de mostrar las quests<br><br>Las opciones son globales y afectan a todos los usuarios";
$wordings['esES']['admin']['show_bg'] = "Informaci&oacuten del CampoBatalla del PVPlog|Controla el modo de mostrar la informaci&oacuten del Campo de Batalla del PVPlog<br>Requiere subir la informaci&oacuten del addon PvPLog<br><br>Las opciones son globales y afectan a todos los usuarios";
$wordings['esES']['admin']['show_pvp'] = "Informaci&oacuten del PVPlog|Controla el modo de mostrar la informaci&oacuten del PVPlog<br>Requiere subir la informaci&oacuten del addon PvPLog<br><br>Las opciones son globales y afectan a todos los usuarios";
$wordings['esES']['admin']['show_duels'] = "Informaci&oacuten de duelos del PVPlog|Controla el modo de mostrar la informaci&oacuten de los duelos del PVPlog<br>Requiere subir la informaci&oacuten del addon PvPLog<br><br>Las opciones son globales y afectan a todos los usuarios";
$wordings['esES']['admin']['show_item_bonuses'] = "Bonus de objetos|Controla el modo de mostrar los Bonus de objetos<br><br>Las opciones son globales y afectan a todos los usuarios";
$wordings['esES']['admin']['show_signature'] = "Mostrar firmas|Controla el modo de mostrar las im&aacutegenes de las firmas<br><span class=\"red\">Requiere el addon SigGen Roster</span><br><br>Las opciones son globales";
$wordings['esES']['admin']['show_avatar'] = "Mostrar avatar|Controla el modo de mostrar la imagen del avatar<br><span class=\"red\">Requiere el addon SigGen Roster</span><br><br>Las opciones son globales";

// realmstatus_conf
$wordings['esES']['admin']['realmstatus_url'] = "Estado real del reino URL|URL a la p&aacutegina de Blizzard's Realmstatus ";
$wordings['esES']['admin']['rs_display'] = "Mostrar Informaci&oacuten|&quot;lleno&quot; mostrar&aacute el estado y el nombre del servidor, poblaci&oacuten y tipo<br>&quot;medio&quot; mostrar&aacute el estado del reino";
$wordings['esES']['admin']['rs_mode'] = "Modo de mostrar|Como aparecer&aacute el EstadoReino<br><br>&quot;DIV Container&quot; - Muestra el reino en una imagen con un texto<br>&quot;Imagen&quot; - Muestra el ReinoEstado como una imagen (REQUERIDO GD!)";
$wordings['esES']['admin']['realmstatus'] = "Nombre alternativo del reino|Algunos nombres de los servidores no permiten al ReinoEstado funcionar correctamente<br>A veces el nombre del servidor no es encontrado en la base de datos de la p&aacutegina de EstadoReino<br>Puedes activar esta opci&oacuten y as&iacute utilizar otro nombre para tu servidor<br><br>D&eacutejalo en blanco para utilizar el nombre elegido en la configuraci&oacuten de la hermandad";

// guildbank_conf
$wordings['esES']['admin']['guildbank_ver'] = "GuildBanco|Banco de la hermandad<br><br>&quot;Tabla&quot; mostrar todos los objetos de todos los jugadores banco en una &uacutenica lista<br>&quot;Inventario&quot; muestra una tabla para cada uno de los jugadores banco";
$wordings['esES']['admin']['bank_money'] = "Mostrar dinero|Muestra el dinero en el addon GuildBanco";
$wordings['esES']['admin']['banker_rankname'] = "Texto para buscar un banco|Indica el texto con el que se localizar&aacute a un personaje banco";
$wordings['esES']['admin']['banker_fieldname'] = "Campo de b&uacutesqueda de banquero|Indica el campo en el que se localiza el texto que has puesto en el apartado anterior";

// update_access
$wordings['esES']['admin']['authenticated_user'] = "Acceso a Update.php|Controla el acceso a update.php<br /><br />Poniendo esta opcion en off desactivas el acceso para todo el mundo.";

// Character Display Settings
$wordings['esES']['admin']['per_character_display'] = 'Pantalla Per-Character';
