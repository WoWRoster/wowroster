<?php
/**
 * WoWRoster.net WoWRoster
 *
 * esES Locale File
 *
 * esES translation by maqjav, nekromant, BarryZGZ
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.3
 * @package    WoWRoster
 * @subpackage Locale
*/

$lang['langname'] = 'Español';

//Instructions how to upload, as seen on the mainpage
$lang['update_link']='Pulsa aquí para las instrucciones de actualización';
$lang['update_instructions']='Instrucciones de Actualización';

$lang['lualocation']='Pulsa Examinar y selecciona tus ficheros *.lua para el envío';

$lang['filelocation']='se encuentra en<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*NOMBRE_DE_CUENTA*</i>\\\\SavedVariables';

$lang['nodata']='No se pudo encontrar la hermandad: <b>\'%1$s\'</b> del servidor <b>\'%2$s\'</b><br />Necesitas <a href="%3$s">incluir tu hermandad</a> y asegurarte de que has <a href="%4$s">terminado la configuración</a><br /><br /><a href="http://www.wowroster.net/MediaWiki/Roster:Install" target="_blank">Pulsa aquí para ver las instrucciones de instalación</a>';
$lang['no_default_guild']='No default guild has been set yet. Please set one here.';
$lang['nodefguild']='No se ha seleccionado ninguna hermandad como predeterminada. Por favor, asegurate de haber <a href="%1$s">completado la configuración</a><br /><br /><a href="http://www.wowroster.net/MediaWiki/Roster:Install" target="_blank">Haz click aquí para ver las instrucciones de instalación</a>';
$lang['nodata_title']='No hay información de la hermandad';

$lang['update_page']='Actualizar Perfil';

$lang['guild_addonNotFound']='No se pudo encontrar la hermandad. ¿Has instalado correctamente Guild Profiler?';

$lang['ignored']='Ignorado';
$lang['update_disabled']='Ha sido desactivado el acceso a Update.php';

$lang['nofileUploaded']='UniUploader no ha enviado ningún archivo, o ha enviado el archivo incorrecto.';
$lang['roster_upd_pwLabel']='Clave de Actualización';
$lang['roster_upd_pw_help']='(Algunos addons requieren de contraseña para poder subir los datos)';


$lang['roster_error'] = 'Roster Error';
$lang['sql_queries'] = 'SQL Queries';
$lang['invalid_char_module'] = 'Personajes inválidos en el módulo name';
$lang['module_not_exist'] = 'El módulo [%1$s] no existe';

$lang['addon_error'] = 'Addon Error';
$lang['specify_addon'] = '¡Debes especificar el nombre del addon!';
$lang['addon_not_exist'] = '<b>¡El addon [%1$s] no existe!</b>';
$lang['addon_disabled'] = '<b>El addon [%1$s] ha sido desactivado</b>';
$lang['addon_no_access'] = '<b>Insufficient credentials to access [%1$s]</b>';
$lang['addon_upgrade_notice'] = '<b>El addon [%1$s] se ha desabilitado porque necesita actualizarse</b>';
$lang['addon_not_installed'] = '<b>El addon [%1$s] no ha sido instalado todavía</b>';
$lang['addon_no_config'] = '<b>El addon [%1$s] no se ha configurado</b>';

$lang['char_error'] = 'Error de personaje';
$lang['specify_char'] = 'No se ha especificado ningun personaje';
$lang['no_char_id'] = 'Lo siento, no hay información de personaje para member_id [ %1$s ]';
$lang['no_char_name'] = 'Lo siento, no hay información de personaje para <strong>%1$s</strong> de <strong>%2$s</strong>';

$lang['roster_cp'] = 'Panel de Control Roster';
$lang['roster_cp_ab'] = 'Roster PC';
$lang['roster_cp_not_exist'] = 'La página [%1$s] no existe';
$lang['roster_cp_invalid'] = 'La página especificada es inválida o no tienes suficientes permisos para acceder a ella';
$lang['access_level'] = 'Access Level';

$lang['parsing_files'] = 'Analizando archivos';
$lang['parsed_time'] = 'Analizados %1$s en %2$s segundos';
$lang['error_parsed_time'] = 'Ha ocurrido un error mientras analizaba %1$s después de %2$s segundos';
$lang['upload_not_accept'] = '%1$s no se puede subir';

$lang['processing_files'] = 'Procesando Archivos';
$lang['error_addon'] = 'Ha ocurrido un error en el adddon %1$s en el método %2$s';
$lang['addon_messages'] = 'Mensajes de Addons:';

$lang['not_accepted'] = '%1$s %2$s @ %3$s-%4$s no aceptado';

$lang['not_updating'] = 'NO esta actualizando %1$s por [%2$s] - %3$s';
$lang['not_update_guild'] = 'NO esta actualizando la lista de la hermandad por %1$s@%3$s-%2$s';
$lang['not_update_guild_time'] = 'NO esta actualizando la lista de la hermandad por %1$s. Estas usando una versión de Guild profile demasiado antigua';
$lang['not_update_char_time'] = 'NO esta actualizando el personaje %1$s. El perfil es demasiado antiguo';
$lang['no_members'] = 'Los datos no contienen información sobre los miembros de la hermandad';
$lang['upload_data'] = 'Actualizando %1$s datos de [<span class="orange">%2$s@%4$s-%3$s</span>]';
$lang['realm_ignored'] = 'Reino: %1$s No escaneado';
$lang['guild_realm_ignored'] = 'Hermandad: %1$s @ Reino: %2$s No escaneado';
$lang['update_members'] = 'Actualizando miembros';
$lang['update_errors'] = 'Errores al actualizar';
$lang['update_log'] = 'Registro de actualización';
$lang['save_error_log'] = 'Registro de errores';
$lang['save_update_log'] = 'Registro de actualizaciones';

$lang['new_version_available'] = 'Existe una nueva versión de %1$s disponible <span class="green">v%2$s</span><br />Actual: %3$s<br />Descargalo <a href="%4$s" target="_blank">AQUI</a>';

$lang['remove_install_files'] = 'Borrar archivos de instalación';
$lang['remove_install_files_text'] = 'Por favor, elimina <span class="green">install.php</span> de este directorio';

$lang['upgrade_wowroster'] = 'Mejorar WoWRoster';
$lang['upgrade'] = 'Mejorar';
$lang['select_version'] = 'Selecciona versión';
$lang['upgrade_wowroster_text'] = "Has cargado una nueva versión del Roster<br /><br />\nTu versión: <span class=\"red\">%1\$s</span><br />\nNueva versión: <span class=\"green\">%2\$s</span><br /><br />\n<a href=\"upgrade.php\" style=\"border:1px outset white;padding:2px 6px 2px 6px;\">MEJORAR</a>";
$lang['no_upgrade'] = 'Acabas de mejorar tu Roster<br />O tienes una nueva versión mas nueva que esta mejora';
$lang['upgrade_complete'] = 'La instalación de WoWRoster se ha completado satisfactoriamente';

// Menu buttons
$lang['menu_header_01'] = 'Datos de la hermandad';
$lang['menu_header_02'] = 'Información del reino';
$lang['menu_header_03'] = 'Actualizar Perfil';
$lang['menu_header_04'] = 'Utilidades';
$lang['menu_header_scope_panel'] = 'Panel de %s';

$lang['menu_totals'] = 'Total: %1$s (+%2$s Alts)';
$lang['menu_totals_level'] = ' al menos L%1$s';

// Updating Instructions
$lang['index_text_uniloader'] = "(Puedes descargar este programa desde la web de WoWRoster, busca el instalador de UniUploader para obtener la última versión)";

$lang['update_instruct']='
<strong>Actualizadores Automáticos Recomendados:</strong>
<ul>
<li>Utiliza <a href="%1$s" target="_blank">UniUploader</a><br />
%2$s</li>
</ul>
<strong>Instrucciones de Actualización:</strong>
<ol>
<li>Descarga <a href="%3$s" target="_blank">Character Profiler</a></li>
<li>Extrae el zip en su propia carpeta en C:\Archivos de Programa\World of Warcraft\Interface\Addons\</li>
<li>Inicia WoW.</li>
<li>Abre tu ventana de banco, misiones, y profesiones que contengan recetas.</li>
<li>Desconecta/Sal del WoW (Mira más arriba si deseas utilizar UniUploader para enviar los datos automáticamente.)</li>
<li>Vete a <a href="%4$s">la página de actualización.</a></li>
<li>%5$s</li>
</ol>';

$lang['update_instructpvp']='
<strong>Estadísticas Opcionales de JcJ:</strong>
<ol>
<li>Descarga <a href="%1$s" target="_blank">PvPLog</a></li>
<li>Extrae PvPLog en la carpeta de Addons.</li>
<li>Haz duelos o combates JcJ.</li>
<li>Envía PvPLog.lua</li>
</ol>';

$lang['roster_credits']='Agradecimientos a <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, y <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> por el código original usado en este sitio.<br />
Página principal de WoWRoster - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft y Blizzard Entertainment son marcas registradas de Blizzard Entertainment, Inc. en los E.U.A. y/u otros países. El resto de marcas registradas pertenecen a sus respectivos propietarios.<br />
<a href="%1$s">Créditos Adicionales</a>';


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
	'RECOMMENDED' => 'Recomendado',
	'FULL' => 'Lleno'
);


//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$lang['guildless']='Guildless'; //Need to translate
$lang['util']='Utilidades';
$lang['char']='Personaje';
$lang['upload']='Enviar';
$lang['required']='Requerido';
$lang['optional']='Opcional';
$lang['attack']='Ataque';
$lang['defense']='Defensa';
$lang['class']='Clase';
$lang['race']='Raza';
$lang['level']='Nivel';
$lang['lastzone']='Última Zona';
$lang['note']='Nota';
$lang['officer_note']='Nota de oficial';
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
$lang['money']='Dinero';
$lang['bank']='Banco';
$lang['raid']='CT_Raid';
$lang['quests']='Misiones';
$lang['roster']='Roster';
$lang['alternate']='Suplente';
$lang['byclass']='Por Clase';
$lang['menustats']='Estadísticas';
$lang['menuhonor']='Honor';
$lang['basename']='Nombre base';
$lang['scope']='�?mbito';

//start search engine
$lang['search']='Búsqueda';
$lang['search_roster']='Buscar en Roster';
$lang['search_onlyin']='Buscar solo en estos addons';
$lang['search_advancedoptionsfor']='Opciones avanzadas para';
$lang['search_results']='Búsqueda de resultados para';
$lang['search_results_from']='Aquí están los resultados de tu búsqueda';
$lang['search_nomatches']='Lo siento, no se encuentran coincidencias';
$lang['search_didnotfind']='¿No has encontrado lo que estabas buscando? ¡Inténtalo aquí!';
$lang['search_for']='Buscar en Roster';
$lang['search_next_matches'] = 'Mas coincidencias de';
$lang['search_previous_matches'] = 'Coincidencias anteriores de';
$lang['search_results_count'] = 'Resultados';
$lang['submited_author'] = 'Publicado por';
$lang['submited_date'] = 'Fecha de publicación';
//end search engine
$lang['update']='Actualizar';
$lang['credit']='Créditos';
$lang['members']='Miembros';
$lang['items']='Objetos';
$lang['find']='Encontrar objeto que contenga';
$lang['upprofile']='Envío Datos';
$lang['backlink']='Volver al Inicio';
$lang['gender']='Género';
$lang['unusedtrainingpoints']='Puntos de entrenamiento sin usar';
$lang['unusedtalentpoints']='Puntos de talento sin usar';
$lang['talentexport']='Exporta tus talentos';
$lang['questlog']='Registro de misiones';
$lang['recipelist']='Lista de recetas';
$lang['reagents']='Ingredientes';
$lang['item']='Objeto';
$lang['type']='Tipo';
$lang['date']='Fecha';
$lang['complete'] = 'Completo';
$lang['failed'] = 'Fallado';
$lang['completedsteps'] = 'Partes completas';
$lang['currentstep'] = 'Parte actual';
$lang['uncompletedsteps'] = 'Partes incompletas';
$lang['key'] = 'Llave';
$lang['timeplayed'] = 'Tiempo jugado';
$lang['timelevelplayed'] = 'T. jugado este nivel';
$lang['Addon'] = 'Addons';
$lang['advancedstats'] = 'Estadísticas avanzadas';
$lang['crit'] = 'Crit';
$lang['dodge'] = 'Esquivar';
$lang['parry'] = 'Parar';
$lang['block'] = 'Bloquear';
$lang['realm'] = 'Reino';
$lang['region'] = 'Región';
$lang['server'] = 'Servidor';
$lang['faction'] = 'Facción';
$lang['page'] = 'Página';
$lang['general'] = 'General';
$lang['prev'] = 'Anterior';
$lang['next'] = 'Siguiente';
$lang['memberlog'] = 'Registro';
$lang['removed'] = 'Borrado';
$lang['added'] = 'Añadido';
$lang['add'] = 'Añadir';
$lang['delete'] = 'Borrar';
$lang['updated'] = 'Actualizado';
$lang['no_info'] = 'Sin información';
$lang['info'] = 'Info';
$lang['url'] = 'URL';
$lang['none']='Ninguno';
$lang['kills']='Asesinatos';
$lang['allow'] = 'Permitir';
$lang['disallow'] = 'No permitir';
$lang['locale'] = 'Local';
$lang['language'] = 'Lenguaje';
$lang['default'] = 'Predefinido';
$lang['proceed'] = 'Proceder';
$lang['submit'] = 'Públicar';
$lang['strength']='Fortaleza';
$lang['agility']='Agilidad';
$lang['stamina']='Aguante';
$lang['intellect']='Inteligencia';
$lang['spirit']='Espíritu';

$lang['rosterdiag'] = 'RosterDiag';
$lang['difficulty'] = 'Dificultad';
$lang['recipe_4'] = 'Óptima';
$lang['recipe_3'] = 'Media';
$lang['recipe_2'] = 'Fácil';
$lang['recipe_1'] = 'Trivial';
$lang['roster_config'] = 'Config. Roster';

$lang['search_names'] = 'Buscar nombres';
$lang['search_items'] = 'Buscar objetos';
$lang['search_tooltips'] = 'Buscar notas';

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
$lang['PvPRankNone']='Ninguno';

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
	'Paladín'=>'ability_mount_dreadsteed',
	'Brujo'=>'ability_mount_nightmarehorse'
);

// Class Icons-Array
$lang['class_iconArray'] = array (
	'Druida'=>'druid_icon',
	'Cazador'=>'hunter_icon',
	'Mago'=>'mage_icon',
	'Paladín'=>'paladin_icon',
	'Sacerdote'=>'priest_icon',
	'Pícaro'=>'rogue_icon',
	'Chamán'=>'shaman_icon',
	'Brujo'=>'warlock_icon',
	'Guerrero'=>'warrior_icon',
// Female variation
//	'Druida'=>'druid_icon',
//	'Cazador'=>'hunter_icon',
//	'Mago'=>'mage_icon',
//	'Paladín'=>'paladin_icon',
//	'Sacerdote'=>'priest_icon',
//	'Pícaro'=>'rogue_icon',
//	'Chamán'=>'shaman_icon',
//	'Brujo'=>'warlock_icon',
//	'Guerrero'=>'warrior_icon',
);

// Class Color-Array
$lang['class_colorArray'] = array(
	'Druida' => 'FF7C0A',
	'Cazador' => 'AAD372',
	'Mago' => '68CCEF',
	'Paladín' => 'F48CBA',
	'Sacerdote' => 'ffffff',
	'Pícaro' => 'FFF468',
	'Chamán' => '00DBBA',
	'Brujo' => '9382C9',
	'Guerrero' => 'C69B6D',
// Female variation
//	'Druida' => 'FF7C0A',
//	'Cazador' => 'AAD372',
//	'Mago' => '68CCEF',
//	'Paladín' => 'F48CBA',
//	'Sacerdote' => 'ffffff',
//	'Pícaro' => 'FFF468',
//	'Chamán' => '00DBBA',
//	'Brujo' => '9382C9',
//	'Guerrero' => 'C69B6D',
);

// Class To English Translation
$lang['class_to_en'] = array(
	'Druida' => 'Druid',
	'Cazador' => 'Hunter',
	'Mago' => 'Mage',
	'Paladín' => 'Paladin',
	'Sacerdote' => 'Priest',
	'Pícaro' => 'Rogue',
	'Chamán' => 'Shaman',
	'Brujo' => 'Warlock',
	'Guerrero' => 'Warrior',
// Female variation
//	'Druida' => 'Druid',
//	'Cazador' => 'Hunter',
//	'Mago' => 'Mage',
//	'Paladín' => 'Paladin',
//	'Sacerdote' => 'Priest',
//	'Pícaro' => 'Rogue',
//	'Chamán' => 'Shaman',
//	'Brujo' => 'Warlock',
//	'Guerrero' => 'Warrior'
);

// Class to game-internal ID
$lang['class_to_id'] = array(
	'Warrior' => 1,

	'Paladin' => 2,

	'Hunter' => 3,

	'Rogue' => 4,

	'Priest' => 5,

	'Shaman' => 7,

	'Mage' => 8,

	'Warlock' => 9,

	'Druid' => 11

);

// Game-internal ID to class
$lang['id_to_class'] = array(
	1 => 'Warrior',
	2 => 'Paladin',
	3 => 'Hunter',
	4 => 'Rogue',
	5 => 'Priest',
	7 => 'Shaman',
	8 => 'Mage',
	9 => 'Warlock',
	11 => 'Druid'
);

// Race to English Translation
$lang['race_to_en'] = array(
	'Elfo de sangre' => 'Blood Elf',
	'Draenei'   => 'Draenei',
	'Elfo de la noche' => 'Night Elf',
	'Enano'     => 'Dwarf',
	'Gnomo'     => 'Gnome',
	'Humano'    => 'Human',
	'Orco'      => 'Orc',
	'No-muerto' => 'Undead',
	'Trol'      => 'Troll',
	'Tauren'    => 'Tauren'
);

$lang['hslist']=' Estadísticas de honor';
$lang['hslist1']='Personaje con mayor rango';
$lang['hslist2']='Mayor número de muertes con honor';
$lang['hslist3']='Mayor número de puntos de honor';
$lang['hslist4']='Mayor puntuación de arenas';

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
$lang['todayhk']='Hoy MH';
$lang['todaycp']='Hoy MP';
$lang['yesterday']='Ayer';
$lang['yesthk']='Ayer MH';
$lang['yestcp']='Ayer MP';
$lang['thisweek']='Esta semana';
$lang['lastweek']='Semana pasada';
$lang['lifetime']='Vida';
$lang['lifehk']='Semana MH';
$lang['honorkills']='Muertes con honor';
$lang['dishonorkills']='Muertes sin honor';
$lang['honor']='Honor';
$lang['standing']='Prestigio';
$lang['highestrank']='Máximo rango';
$lang['arena']='Arena';

$lang['when']='Fecha';
$lang['guild']='Hermandad';
$lang['result']='Resultado';
$lang['zone']='Zona';
$lang['subzone']='Subzona';
$lang['yes']='Sí';
$lang['no']='No';
$lang['win']='Gana';
$lang['loss']='Pierde';
$lang['unknown']='Desconocido';

//strings for Rep-tab
$lang['exalted']='Exaltado';
$lang['revered']='Reverenciado';
$lang['honored']='Honorable';
$lang['friendly']='Amistoso';
$lang['neutral']='Neutral';
$lang['unfriendly']='Enemigo';
$lang['hostile']='Hostil';
$lang['hated']='Odiado';
$lang['atwar']='En Guerra';
$lang['notatwar']='En Paz';


// Quests page external links (on character quests page)
// $lang['questlinks'][][] = array(
// 		'name'=> 'Name',  //This is the name displayed on the quests page
// 		'url#'=> 'url',  //This is the URL used for the quest lookup

$lang['questlinks'][] = array(
	'name'=>'Thottbot',
	'url1'=>'http://www.thottbot.com/?f=q&amp;title=',
	'url2'=>'&amp;obj=&amp;desc=&amp;minl=',
	'url3'=>'&amp;maxl='
);

$lang['questlinks'][] = array(
	'name'=>'Allakhazam',
	'url1'=>'http://wow.allakhazam.com/db/qlookup.html?name=',
	'url2'=>'&amp;obj=&amp;desc=&amp;minl=',
	'url3'=>'&amp;maxl='
);

$lang['questlinks'][] = array(
	'name'=>'WoW-Lista',
	'url1'=>'http://www.wow-lista.com/buscadormision.php?titulo=',
	'url2'=>'&amp;descripcion=&amp;nivelde=',
	'url3'=>'&amp;nivelhasta='
);

/*$lang['questlinks'][] = array(
	'name'=>'WoWHead',
	'url1'=>'http://www.wowhead.com/?quests&amp;filter=na=',
	'url2'=>';minle=',
	'url3'=>';maxle='
);*/

// Items external link
// Add as manu item links as you need
// Just make sure their names are unique
// uses the 'item_id' for data
$lang['itemlink'] = 'Enlaces de objetos';
$lang['itemlinks']['Thottbot'] = 'http://www.thottbot.com/i';
$lang['itemlinks']['Allakhazam'] = 'http://wow.allakhazam.com/search.html?q=';
$lang['itemlinks']['WoW-Lista'] = 'http://www.wow-lista.com/buscador.php?abuscar=';
//$lang['itemlinks']['WoWHead'] = 'http://www.wowhead.com/?items&amp;filter=na=';

// WoW Data Site Search
// Add as many item links as you need
// Just make sure their names are unique
// use these locales for data searches
$lang['data_search'] = 'Bases de datos de WoW';
$lang['data_links']['Thottbot'] = 'http://www.thottbot.com/index.cgi?s=';
$lang['data_links']['Allakhazam'] = 'http://wow.allakhazam.com/search.html?q=';
$lang['data_links']['WoW-Lista'] = 'http://www.wow-lista.com/buscador.php?abuscar=';
//$lang['data_links']['WoWHead'] = 'http://www.wowhead.com/?search=';

// Google Search
// Add as many item links as you need
// Just make sure their names are unique
// use these locales for data searches
$lang['google_search'] = 'Google';
$lang['google_links']['Google'] = 'http://www.google.com/search?q=';
$lang['google_links']['Google Groups'] = 'http://groups.google.com/groups?q=';
$lang['google_links']['Google Images'] = 'http://images.google.com/images?q=';
$lang['google_links']['Google News'] = 'http://news.google.com/news?q=';

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
$lang['tooltip_random_enchant']='Encantamiento aleatorio';
$lang['tooltip_set']='Conjunto|Bonif.';
$lang['tooltip_rank']='Rango';
$lang['tooltip_next_rank']='Siguiente Rango';
$lang['tooltip_spell_damage']='Daño por Hechizos';
$lang['tooltip_school_damage']='\\+.*Daño por Hechizos';
$lang['tooltip_healing_power']='Poder de Curación';
$lang['tooltip_reinforced_armor']='Armadura Reforzada';
$lang['tooltip_damage_reduction']='Reducción de daño';
//--Tooltip Parsing -- Translated by Subxero
$lang['tooltip_durability']='Durabilidad';
$lang['tooltip_unique']='Único';
$lang['tooltip_speed']='Veloc.';
$lang['tooltip_poisoneffect']='^Cada golpe tiene';  // this is found in poison tooltips  I need the common text that decribes the poison.

$lang['tooltip_preg_armor']='/^(\d+) armadura/';
$lang['tooltip_preg_durability']='/Durabilidad(|:) (\d+) \/ (\d+)/';
$lang['tooltip_preg_madeby']='/\<Hecho por (.+)\>/';  // this is the text that shows who crafted the item.  don't worry about the pattern just post me the text I will make the pattern.
$lang['tooltip_preg_bags']='/de (\d+) casillas/';  // text for bags, ie '16 slot bag'
$lang['tooltip_preg_socketbonus']='/Bonus ranura: (.+)\n/';
$lang['tooltip_preg_classes']='/^(Clases:) (.+)/'; // text for class restricted items
$lang['tooltip_preg_races']='/^(Razas:) (.+)/'; // test for race restricted items
$lang['tooltip_preg_charges']='/(\d+) cargas/'; // text for items with charges
$lang['tooltip_preg_block']='/(\d+) (bloqueo)/';  // text for shield blocking values
$lang['tooltip_preg_emptysocket']='/Ranura (roja|amarilla|azul|meta)/'; // text shown if the item has empty sockets.
$lang['tooltip_preg_reinforcedarmor']='/(Reforzado\s\(\+\d+\sarmadura\))/i';
$lang['tooltip_preg_tempenchants']='/(.+\s\(\d+\s(min|seg)\))\n/';
$lang['tooltip_preg_meta_requires']='/Requiere.*?gemas/';
$lang['tooltip_preg_meta_requires_min']='/Requiere al menos (\d) gema (\S+)(s)/';
$lang['tooltip_preg_meta_requires_more']='/Requiere mas gemas (\S+) que gemas (\S+)/';
$lang['tooltip_source']='Fuente';
$lang['tooltip_boss']='Jefe';
$lang['tooltip_droprate']='Pos\. de aparici';

$lang['tooltip_chance_hit']='Probabilidad al acertar:'; // needs to find 'chance on|to hit:'
$lang['tooltip_reg_requires']='(Requiere|Necesitas)'; //really a preg call but w/o delims etc
$lang['tooltip_reg_onlyworksinside']='Solo funciona dentro';
$lang['tooltip_reg_conjureditems']='Los objetos creados mágicamente desaparecen';
$lang['tooltip_reg_weaponorbulletdps']='^\(|^Añade ';

$lang['tooltip_armor_types']='Tela|Cuero|Malla|Placas';  // the types of armor
$lang['tooltip_weapon_types']='Flecha|Hacha|Arco|Bala|Ballesta|Daga|Caña de pescar|Arma de puño|Arma de fuego|�?dolo|Maza|Mano derecha|Mano izquierda|Arma de asta|Bastón|Espada|Arma arrojadiza|Varita|Sostener con la mano izquierda|Mano principal|Reliquia'; // the types of weapons as shown in the tooltip
$lang['tooltip_bind_types']='Ligado|Se liga al equiparlo|Objeto de misión|Se liga al recogerlo|Este objeto inicia una misión';
$lang['tooltip_misc_types']='Dedo|Cuello|Atrás|Camisa|Alhaja|Tabardo|Cabeza|Pecho|Espalda|Pies|Abalorio';
$lang['tooltip_garbage']='<Mayús clic derecho para insertar>|<Clic derecho para leer>|Duración|<Clic derecho para abrir>';  // these are texts that we really do not need to show in WoWRoster's tooltip so we'll strip them out

//CP v2.1.1+ Gems info
//uses preg_match() to find the type and color of the gem
$lang['gem_preg_singlecolor'] = '/Encaja en una ranura de color (.+)\./i';
$lang['gem_preg_multicolor'] = '/Encaja en una ranura de color (.+) o (.+)\./i';
$lang['gem_preg_meta'] = '/Solo encaja en una ranura de gema meta\./i';
$lang['gem_preg_prismatic'] = '/Encaja en una ranura roja, amarilla o azul\./i';

//Gems color Array
$lang['gem_colors'] = array(
	'red' => 'roja',
	'blue' => 'azul',
	'yellow' => 'amarilla',
	'green' => 'verde',
	'orange' => 'naranja',
	'purple' => 'morada',
	'prismatic' => 'centelleante',
	'meta' => 'meta'
	);

$lang['gem_colors_to_en'] = array(
	'roja' => 'red',
	'rojo' => 'red',
	'azul' => 'blue',
	'amarilla' => 'yellow',
	'amarillo' => 'yellow',
	'verde' => 'green',
	'naranja' => 'orange',
	'morada' => 'purple',
	'centelleante' => 'prismatic',
	'meta' => 'meta'
	);

$lang['socket_colors_to_en'] = array(
	'roja' => 'red',
	'azul' => 'blue',
	'amarilla' => 'yellow',
	'meta' => 'meta',
	);
// -- end tooltip parsing

// Warlock pet names for icon displaying
$lang['Imp']='Diablillo';
$lang['Voidwalker']='Abisario';
$lang['Succubus']='Súcubo';
$lang['Felhunter']='Manáfago';
$lang['Infernal']='Inferno';
$lang['Felguard']='Guardia Maldito';

// Max experiance for exp bar on char page
$lang['max_exp']='Max PE';

// Error messages
$lang['CPver_err']='La versión de CharacterProfiler utilizada para capturar los datos de este personaje es inferior a la versión mínima admitida para el envío.<br />Por favor, asegúrate de que estás utilizando al menos la versión %1$s y has iniciado sesión y grabado los datos utilizando la misma.';
$lang['GPver_err']='La versión de GuildProfiler utilizada para capturar los datos de este personaje es inferior a la versión mínima admitida para el envío.<br />Por favor, asegúrate de que estás utilizando al menos la versión %1$s';

// Menu titles
$lang['menu_upprofile']='Actualizar Perfil|Aquí puedes actualizar tu perfil';
$lang['menu_search']='Buscar|Busca objetos y recetas en la base de datos';
$lang['menu_roster_cp']='Panel de control|Abre el panel de configuración de Roster';
$lang['menu_credits']='Créditos|Creadores de WoWRoster';
$lang['menupanel_util']  = 'Utilidades';
$lang['menupanel_realm'] = 'Reino';
$lang['menupanel_guild'] = 'Hermandad';
$lang['menupanel_char']  = 'Personaje';

$lang['menuconf_sectionselect']='Selecciona panel';
$lang['menuconf_changes_saved']='Los cambios a %1$s se han guardado';
$lang['menuconf_no_changes_saved']='No se han guardado los cambios';
$lang['menuconf_add_button']='Añadir botón';
$lang['menuconf_drag_delete']='Arrastrar aquí para borrar';
$lang['menuconf_addon_inactive']='El addon está inactivo';
$lang['menuconf_unused_buttons']='Botones sin usar';

$lang['installer_install_0']='La instalación de %1$s se ha completado satisfactoriamente';
$lang['installer_install_1']='La instalación de %1$s falló, pero se ha recuperado la versión anterior satisfactoriamente';
$lang['installer_install_2']='La instalación de %1$s falló y no se ha conseguido recuperar la versión anterior';
$lang['installer_uninstall_0']='Se ha desinstalado %1$s satisfactoriamente';
$lang['installer_uninstall_1']='Fallo al desinstalar %1$s. Se ha dejado como estaba';
$lang['installer_uninstall_2']='Fallo al desinstalar %1$s. No se ha conseguido dejar como estaba';
$lang['installer_upgrade_0']='Mejora de %1$s completada satisfactoriamente';
$lang['installer_upgrade_1']='Ha fallado la mejora de %1$s. Se ha conseguido dejar la versión anterior';
$lang['installer_upgrade_2']='Ha fallado la mejora de %1$s. No se ha conseguido dejar la versión anterior';
$lang['installer_purge_0']='Purge of %1$s successful';

$lang['installer_icon'] = 'Icono';
$lang['installer_addoninfo'] = 'Información sobre el addon';
$lang['installer_status'] = 'Estado';
$lang['installer_installation'] = 'Instalar';
$lang['installer_author'] = 'Autor';
$lang['installer_log'] = 'Registro del Addon Manager';
$lang['installer_activated'] = 'Activado';
$lang['installer_deactivated'] = 'Desactivado';
$lang['installer_installed'] = 'Instalado';
$lang['installer_upgrade_avail'] = 'Mejora disponible';
$lang['installer_not_installed'] = 'No instalado';

$lang['installer_turn_off'] = 'Haz click para desactivar';
$lang['installer_turn_on'] = 'Haz click para activar';
$lang['installer_click_uninstall'] = 'Haz click para desinstalar';
$lang['installer_click_upgrade'] = 'Haz click para mejorar de %1$s a %2$s';
$lang['installer_click_install'] = 'Haz click para instalar';
$lang['installer_overwrite'] = 'Versión anterior sobreescrita';
$lang['installer_replace_files'] = 'Has sobreescribido tu instalación actual del addon con una versión anterior<br />Reemplaza los archivos con la última versión<br /><br />Or Click to Purge AddOn';

$lang['installer_error'] = 'Errores de instalación';
$lang['installer_invalid_type'] = 'Tipo de instalación invalido';
$lang['installer_no_success_sql'] = 'No se ha podido añadir algunas entradas al instalador';
$lang['installer_no_class'] = 'El archivo de definición para la instalación de %1$s no contenía información correcta';
$lang['installer_no_installdef'] = 'inc/install.def.php de %1$s no se ha encontrado';

$lang['installer_no_empty'] = 'No se puede instalar con el nombre del addon en blanco';
$lang['installer_fetch_failed'] = 'Error al traer información del addon de %1$s';
$lang['installer_addon_exist'] = '%1$s actualmente contiene %2$s. Puedes volver atrás y desinstalar el addon primero, mejorarlo, o instalar el addon con un nombre diferente';
$lang['installer_no_upgrade'] = '%1$s no contiene información de mejora';
$lang['installer_not_upgradable'] = '%1$s no se puede mejorar %2$s desde que su nombre base %3$s no esta en la lista de addons mejorables';
$lang['installer_no_uninstall'] = '%1$s no se puede desinstalar';
$lang['installer_not_uninstallable'] = '%1$s contiene un addon %2$s que debe de ser instalado con este desinstalador de addons';

// After Install guide
$lang['install'] = 'Install';
$lang['setup_guide'] = 'After Install Guide';
$lang['default_data'] = 'Default Data';
$lang['default_data_help'] = 'Set your default allowed guild here<br />A default guild is needed for many addons to function properly<br />You can add more allowed guilds in RosterCP-&gt;Upload Rules<br /><br />If this is a non-guilded Roster install:<br />Enter Guildless-F for guild name<br />Replace F with your Faction (A-Alliance, H-Horde)<br />Enter your realm and region<br />Set Upload Rules for characters in RosterCP-&gt;Upload Rules';
$lang['guide_complete'] = 'The after install setup is complete<br /><br />Remember to <a href="%1$s">install addons</a> for Roster';
$lang['guide_already_complete'] = 'The after install guide setup has already been completed<br />You cannot run it again';

// Password Stuff
$lang['password'] = 'Contraseña';
$lang['changeadminpass'] = 'Cambiar contraseña de Admin';
$lang['changeofficerpass'] = 'Cambiar contraseña de Oficial';
$lang['changeguildpass'] = 'Cambiar contraseña de la Hermandad';
$lang['old_pass'] = 'Contraseña antigua';
$lang['new_pass'] = 'Contraseña nueva';
$lang['new_pass_confirm'] = 'Nueva contraseña [confirmar]';
$lang['pass_old_error'] = 'Contraseña incorrecta. Por favor, introduce la contraseña correcta';
$lang['pass_submit_error'] = 'Error de publicación. La contraseña vieja, la nueva y la confirmada necesitan ser publicadas';
$lang['pass_mismatch'] = 'Las contraseñas no coinciden. Por favor, escribe exactamente la misma contraseña en ambas casillas';
$lang['pass_blank'] = 'No se permite contraseñas en blanco. Por favor, escribe una contraseña en ambas casillas.';
$lang['pass_isold'] = 'No se ha cambiado la contraseña. La nueva contraseña es la misma que la anterior';
$lang['pass_changed'] = '&quot;%1$s&quot; contraseña cambiada. Tu nueva contraseña es [ %2$s ].<br /> No olvides la contraseña, se guarda encriptada únicamente';
$lang['auth_req'] = 'Introduce contraseña';

// Upload Rules
$lang['upload_rules_error'] = 'No puedes dejar ningún campo vacío cuando añades una hermandad/personaje';
$lang['upload_rules_help'] = 'En este apartado puedes configurar las hermandades/personajes excluidos y permitidos.<br />Cada vez que se sube una hermandad/personaje, se comprueba el bloque de arriba.<br />Si el nombre@servidor concuerda con los \'no permitidos\', directamente se rechaza.<br />Después se comprueba el segundo bloque.<br />Si el nombre@servidor concuerda con los \'permitidos\', entonces los datos se graban en la base de datos.<br />Si no concuerda con ningún bloque, los datos se descartan.';

// Data Manager
$lang['clean'] = 'Limpia todas las entradas basándose en las normas';
$lang['select_guild'] = 'Selecciona hermandad';
$lang['delete_checked'] = 'Eliminar marcado';
$lang['delete_guild'] = 'Eliminar hermandad';
$lang['delete_guild_confirm'] = 'Con esto borraras esta hermandad entera y todos los miembros marcados como miembros de la hermandad.\\n ¿Estas seguro de continuar?\\n\\nNOTA: ¡No podrás volver atrás!';

// Config Reset
$lang['config_is_reset'] = 'La configuración ha sido restaurada. Por favor, recuerda re-configurar TODAS tus opciones antes de pasar a subir datos';
$lang['config_reset_confirm'] = 'Esto es irreversible. ¿Estas seguro de continuar?';
$lang['config_reset_help'] = 'En esta ventana puedes restaurar por completo la configuración de Roster.<br />
Todos los datos de configuración de Roster se borrarán permanentemente, y los valores predefinidos serán<br /> restaurados. Los datos de la hermandad, datos de personajes, configuración de addons, datos<br /> de addons, botones del menú y las exclusiones serán guardadas.<br />
Las contraseñas de la hermandad, oficiales y administrador también serán guardadas.<br />
<br />
Para continuar, introduce tu contraseña de administrador debajo y haz click en \'Proceder\'.';


/******************************
 * Roster Admin Strings
 ******************************/

$lang['pagebar_function'] = 'Funciones';
$lang['pagebar_rosterconf'] = 'Configuración de Roster';
$lang['pagebar_uploadrules'] = 'Exclusiones';
$lang['pagebar_dataman'] = 'Control de datos';
$lang['pagebar_changepass'] = 'Cambiar contraseña';
$lang['pagebar_addoninst'] = 'Instalar addons';
$lang['pagebar_update'] = 'Subir perfil';
$lang['pagebar_rosterdiag'] = 'Roster Diag.';
$lang['pagebar_menuconf'] = 'Configuración de paneles';
$lang['pagebar_configreset'] = 'Restaurar configuración';

$lang['pagebar_addonconf'] = 'Configurar addon';

$lang['roster_config_menu'] = 'Menú de configuración';

// Submit/Reset confirm questions
$lang['config_submit_button'] = 'Guardar opciones';
$lang['config_reset_button'] = 'Restaurar';
$lang['confirm_config_submit'] = 'Esto guardará los cambios en la base de datos. ¿Estas seguro?';
$lang['confirm_config_reset'] = 'Esto restaurara el formulario a como estaba cuando lo cargastes. ¿Estas seguro?';

// All strings here
// Each variable must be the same name as the config variable name
// Example:
//   Assign description text and tooltip for $roster->config['sqldebug']
//   $lang['admin']['sqldebug'] = "Desc|Tooltip";

// Each string is separated by a pipe ( | )
// The first part is the short description, the next part is the tooltip
// Use <br /> to make new lines!
// Example:
//   "Controls Flux-Capacitor|Turning this on may cause serious temporal distortions<br />Use with care"


// Main Menu words
$lang['admin']['main_conf'] = 'Opciones principales|Opciones principales del Roster<br />Incluye la URL de Roster, el URL de las Imágenes del Interfaz, y otras opciones del núcleo';
$lang['admin']['defaults_conf'] = 'Datos de la hermandad|Configura aspectos generales sobre la hermandad';
$lang['admin']['index_conf'] = 'Página principal|Opciones sobre que mostrar en la Página Principal';
$lang['admin']['menu_conf'] = 'Aspecto del menú|Cambia el aspecto del menú principal';
$lang['admin']['display_conf'] = 'Configuración general|Muestra opciones misceláneas<br />css, javascript, motd, etc...';
$lang['admin']['realmstatus_conf'] = 'Estado del reino|Opciones sobre la imagen del estado del reino<br /><br />Para quitarlo, mira en la sección Aspecto del menú';
$lang['admin']['data_links'] = 'Descargas|Enlaces externos de addons indispensables';
$lang['admin']['update_access'] = 'Permisos para actualizar|Configura los niveles de acceso al panel de control de control de Roster';

$lang['admin']['documentation'] = 'Documentación|Documentación de WoWRoster vía wowroster.net wiki';

// main_conf
$lang['admin']['roster_dbver'] = "Versión de la base de datos de Roster|Versión de la base de datos";
$lang['admin']['version'] = "Versión de Roster|Versión actual del Roster";
$lang['admin']['debug_mode'] = "Debug Mode|off - No debug or error messages<br />on - Display error messages and simple debug<br />extended - Full debug mode and backtrace in error messages";
$lang['admin']['sql_window'] = "SQL Window|off - Do not show query window<br />on - Display query window in the footer<br />extended - Include DESCRIBE statements";
$lang['admin']['minCPver'] = "Versión min de CP|Mínima versión permitida del CharacterProfiler";
$lang['admin']['minGPver'] = "Versión min de GP|Mínima versión permitida del GuildProfiler";
$lang['admin']['locale'] = "Lenguaje principal del Roster|Elige el lenguaje del interfaz";
$lang['admin']['default_page'] = "Página predeterminada|Elige la página a mostrar si el URL introducido no existe";
$lang['admin']['external_auth'] = "Roster Auth|Aquí puedes elegir que archivo auth se utilizará<br />&quot;Roster&quot; es el predeterminado, creado por el sistema";
$lang['admin']['website_address'] = "Dirección del sitio|Se utiliza para localizar el URL del logo, y para el enlace del nombre de la hermandad que aparece en el menú principal.<br />Algunos addons del roster requieren de ella.";
$lang['admin']['interface_url'] = "Directorio del interfaz|Directorio donde se encuentran las imágenes del interfaz.<br />El predeterminado es &quot;img/&quot;<br /><br />Puedes usar una ruta relativa o la completa";
$lang['admin']['img_suffix'] = "Extensión de las imágenes del interfaz|El tipo de imágenes que usa tu interfaz";
$lang['admin']['alt_img_suffix'] = "Extensión de las imágenes del interfaz Alt|Otro tipo de imágenes que usa tu interfaz";
$lang['admin']['img_url'] = "Directorio de imágenes del Roster|Directorio donde se encuentran las imágenes del Roster<br />El predeterminado es &quot;img/&quot;.<br /><br />Puedes usar una ruta relativa o la completa";
$lang['admin']['timezone'] = "Hora de la zona|Muestra la hora de tu región geográfica";
$lang['admin']['localtimeoffset'] = "Diferencia horaria|La diferencia horaria desde el UTC/GMT<br />La hora del Roster se calcula con esta diferencia";
$lang['admin']['use_update_triggers'] = "Actualizar addon triggers|Esto se utiliza con addons que necesitan ser ejecutados mientras actualizas un personaje o la hermandad.<br />Algunos addons requieren de esto para funcionar correctamente";
$lang['admin']['check_updates'] = "Buscar actualizaciones|Permite a WoWRoster (y a los addons que usan esta opción)<br />comprobar si existe alguna versión nueva del software";
$lang['admin']['seo_url'] = "Friendly URLs|Enable SEO like URL links in Roster<br /><br />on - /some/page/here/param=value.html<br />off - index.php?p=some-page-here&amp;param=value";
$lang['admin']['local_cache']= "Sistema de archivos en caché|Permite al servidor local guardar algunos archivos para mejorar el rendimiento.";
$lang['admin']['use_temp_tables'] = "Usar tablas temporales|Desactiva esta opción si tu servidor no permite crear tablas temporales en la base de datos (CREATE TEMPORARY TABLE privilege).<br/>Se recomienda activarlo para mejorar el rendimiento.";
$lang['admin']['enforce_rules'] = "Forzar exclusiones|Esta opción forzará las mismas normas de subida para todos los ficheros lua que se suban<br />- Never: Nunca forzará las normas<br />- All LUA Updates: Forzará las normas a todas las actualizaciones de ficheros lua<br />- CP Updates: Forzará las normas solo a los ficheros CP.lua<br />- Guild Updates: Forzará las normas solo con actualizaciones de la hermandad<br /><br />También puedes activar esta opción en el panel &quot;Exclusiones&quot;.";

// defaults_conf
$lang['admin']['default_name'] = "Nombre de WoWRoster|Título que aparece en la parte superior del menú principal";
$lang['admin']['default_desc'] = "Descripción|Introduce una breve descripción a mostrar debajo del nombre de WoWRoster";
$lang['admin']['alt_type'] = "Alt-Texto búsqueda|Asigna el código para reconocer a los personajes alts<br /><br /><span class=\"red\">The Memebers List AddOn DOES NOT use this for alt grouping</span>";
$lang['admin']['alt_location'] = "Campo búsqueda de alts|Indica el campo en el que se tiene que buscar la etiqueta establecida en el campo anterior<br /><br /><span class=\"red\">The Memebers List AddOn DOES NOT use this for alt grouping</span>";

// menu_conf
$lang['admin']['menu_conf_left'] = "Panel Izquierdo|";
$lang['admin']['menu_conf_right'] = "Panel Derecho|";

$lang['admin']['menu_top_pane'] = "Panel Superior|Muestra el panel superior encima del menú principal de Roster<br />Este area tiene el nombre de la hermandad, servidor, última actualización, etc...";
$lang['admin']['menu_top_faction'] = "Icono de facción|Muestra un icono con la facción de la hermandad en el menú principal de Roster";
$lang['admin']['menu_top_locale'] = "Caja de idioma|Muestra la caja de selección de idioma en el menú principal de Roster";

$lang['admin']['menu_left_type'] = "Mostrar tipo|Decide si mostrar una descripción por nivel, por clase, por el estado de reino o nada";
$lang['admin']['menu_left_level'] = "Nivel mínimo|Nivel mínimo de los personajes para ser mostrados en la descripción de nivel/clase";
$lang['admin']['menu_left_style'] = "Muestra estilo|Como mostrar la lista, como un gráfico linear o como uno logarítmico";
$lang['admin']['menu_left_barcolor'] = "Color de la barra|Decide el color de la barra que muestra el numero de personajes de un nivel, grupo/clase";
$lang['admin']['menu_left_bar2color'] = "Color 2 de la barra|Decide el color de la barra que muestra el numero de alts de un nivel, grupo/clase";
$lang['admin']['menu_left_textcolor'] = "Color del texto|Elige el color para las etiquetas del nivel/clase grupo (El gráfico de las clases usa el color de las etiquetas de clase)";
$lang['admin']['menu_left_outlinecolor'] = "Color de texto fuera de línea|Elige el color del texto fuera de línea para las etiquetas del nivel/clase y grupo.<br />Vacia esta casilla para desactivar el color de fuera de linea";
$lang['admin']['menu_left_text'] = "Fuente de texto|El tipo de fuente para las etiquetas de nivel/clase y grupo";

$lang['admin']['menu_right_type'] = "Mostrar tipo|Decide si mostrar una descripción por nivel, por clase, por el estado de reino o nada";
$lang['admin']['menu_right_level'] = "Nivel mínimo|Nivel mínimo de los personajes para ser mostrados en la descripción de nivel/clase";
$lang['admin']['menu_right_style'] = "Muestra estilo|Como mostrar la lista, como un gráfico linear o como uno logarítmico";
$lang['admin']['menu_right_barcolor'] = "Color de la barra|Decide el color de la barra que muestra el numero de personajes de un nivel, grupo/clase";
$lang['admin']['menu_right_bar2color'] = "Color 2 de la barra|Decide el color de la barra que muestra el numero de alts de un nivel, grupo/clase";
$lang['admin']['menu_right_textcolor'] = "Color del texto|Elige el color para las etiquetas del nivel/clase grupo (El gráfico de las clases usa el color de las etiquetas de clase)";
$lang['admin']['menu_right_outlinecolor'] = "Color de texto fuera de línea|Elige el color del texto fuera de línea para las etiquetas del nivel/clase y grupo.<br />Vacia esta casilla para desactivar el color de fuera de linea";
$lang['admin']['menu_right_text'] = "Fuente de texto|El tipo de fuente para las etiquetas de nivel/clase y grupo";

$lang['admin']['menu_bottom_pane'] = "Panel inferior|Muestra el panel inferior debajo del menu principal de Roster.<br />Este area contiene el campo de búsqueda.";

// display_conf
$lang['admin']['theme'] = "Tema del Roster|Elige el aspecto general de Roster<br /><span style=\"color:red;\">NOTA:</span> No todo el Roster varia con el tema.<br />Usando otros temas puede que no obtengas los resultados esperados";
$lang['admin']['logo'] = "URL para el logo de la cabecera|Escribe el URL completo de la imagen o en su lugar &quot;img/&quot;nombre_logo. <br />Es la imagen que se muestra en la cabecera de la página";
$lang['admin']['roster_bg'] = "URL para la imagen del fondo|Indica el URL completo de la imagen a mostrar en el fondo de la web<br />o el nombre relativo &quot;img/&quot;";
$lang['admin']['motd_display_mode'] = "Modo de mostrar MDD|Elige como mostrar el texto del mensaje del día<br /><br />&quot;Texto&quot; - Muestra el MDD en rojo<br />&quot;Imagen&quot; - Muestra el MDD en una imagen (¡REQUERIDO GD!)";
$lang['admin']['signaturebackground'] = "img.php Fondo|Soporte para elegir el fondo de pantalla";
$lang['admin']['processtime'] = "Pag Gen. Tiempo/DB Colas|Muestra el tiempo de renderizado y el número de llamadas al pie de la página<br />&quot;<i>x.xx | xx</i>&quot;";

// data_links
$lang['admin']['profiler'] = "Enlace para descargar CharacterProfiler|URL para descargar CharacterProfiler";
$lang['admin']['uploadapp'] = "Enlace para descargar UniUploader|URL para descargar UniUploader";

// realmstatus_conf
$lang['admin']['rs_display'] = "Mostrar Información|&quot;lleno&quot; muestra el estado y el nombre del servidor, población y tipo<br />&quot;medio&quot; muestra el estado del reino";
$lang['admin']['rs_mode'] = "Modo de mostrar|Elige como mostrar el estado del reino<br /><br />&quot;DIV Container&quot; - Muestra el reino en una imagen con un texto<br />&quot;Imagen&quot; - Muestra el estado del reino con una imagen (¡REQUERIDO GD!)";
$lang['admin']['rs_timer'] = "Tiempo de refresco|Elige el periodo de tiempo para obtener nuevos datos sobre el estado del reino";
$lang['admin']['rs_left'] = "Mostrar|";
$lang['admin']['rs_middle'] = "Opciones de tipos de Mostrar|";
$lang['admin']['rs_right'] = "Opciones sobre como mostrar la Población|";
$lang['admin']['rs_font_server'] = "Fuente de teino|Tipo de fuente para el nombre del reino<br />(¡Solo en modo imagen!)";
$lang['admin']['rs_size_server'] = "Tamaño de fuente de reino|Elige el tamaño de la fuente del nombre de reino<br />(¡Solo en modo imagen!)";
$lang['admin']['rs_color_server'] = "Color de reino|Elige el color del nombre del Reino";
$lang['admin']['rs_color_shadow'] = "Color de la sombra|Elige el color para la sombra de los textos<br />(¡Solo en modo imagen!)";
$lang['admin']['rs_font_type'] = "Fuente de letra|Elige el tipo de letra para el reino<br />(¡Solo en modo imagen!)";
$lang['admin']['rs_size_type'] = "Tamaño de la fuente de letra|Elige el tamaño de la fuente de letra para el reino<br />(¡Solo en modo imagen!)";
$lang['admin']['rs_color_rppvp'] = "RJ-JcJ Color|Color para reinos RJ-JcJ";
$lang['admin']['rs_color_pve'] = "Normal Color|Color para reinos Normales";
$lang['admin']['rs_color_pvp'] = "JcJ Color|Color para reinos JcJ";
$lang['admin']['rs_color_rp'] = "RJ Color|Color para reinos RJ";
$lang['admin']['rs_color_unknown'] = "Color desconocido|Color para reinos desconocidos";
$lang['admin']['rs_font_pop'] = "Población Fuente|Tipo de letra para la población del reino<br />(¡Solo en modo imagen!)";
$lang['admin']['rs_size_pop'] = "Población Tamaño de fuente|Elige el tamaño de la fuente de letra para la población del reino<br />(¡Solo en modo imagen!)";
$lang['admin']['rs_color_low'] = "Bajo Color|Color para poblaciones bajas";
$lang['admin']['rs_color_medium'] = "Medio Color|Color para poblaciones medias";
$lang['admin']['rs_color_high'] = "Alto Color|Color para poblaciones altas";
$lang['admin']['rs_color_max'] = "Max Color|Color para poblaciones con el máximo";
$lang['admin']['rs_color_error'] = "Error Color|Color for realm error";
$lang['admin']['rs_color_offline'] = "Desconectado Color|Color para reinos desconectados";
$lang['admin']['rs_color_full'] = "Lleno Color|Color para reinos lleinos (solo en reinos europeos)";
$lang['admin']['rs_color_recommended'] = "Recomendado Color|Color para reinos recomendados (solo en reinos europeos)";

// update_access
$lang['admin']['authenticated_user'] = "Acceso a Update.php|Controla el acceso a update.php<br /><br />Poniendo esta opción en off desactivas el acceso para todo el mundo.";
$lang['admin']['gp_user_level'] = "Nivel de hermandad|Elige el nivel requerido para poder subir datos con GuildProfiler";
$lang['admin']['cp_user_level'] = "Nivel de personaje|Elige el nivel requerido para poder subir datos con CharacterProfiler";
$lang['admin']['lua_user_level'] = "Nivel de otros LUA|Elige el nivel requerido para poder subir datos con otros addons<br />Esto es para TODOS los tipos de archivos LUA que quieras subir al Roster.";

// Character Display Settings
$lang['admin']['per_character_display'] = 'Pantalla Per-Character';

//Overlib for Allow/Disallow rules
$lang['guildname'] = 'Nombre de Hermandad';
$lang['realmname']  = 'Nombre de Reino';
$lang['regionname'] = 'Región (i.e. ES)';
$lang['charname'] = 'Nombre de Personaje';
