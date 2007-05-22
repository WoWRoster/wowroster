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


//Instructions how to upload, as seen on the mainpage
$lang['update_link']='Pulsa aquí para las instrucciones de actualización';
$lang['update_instructions']='Instrucciones de Actualización';

$lang['lualocation']='Pulsa Examinar y selecciona tus ficheros *.lua para el envío';

$lang['filelocation']='se encuentra en<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*NOMBRE_DE_CUENTA*</i>\\\\SavedVariables';

$lang['noGuild']='No puedo encontrar la hermandad en la base de datos. Por favor, actualiza primero los miembros.';
$lang['nodata']='No puedo encontrar la hermandad: <b>\'%1$s\'</b> del servidor <b>\'%2$s\'</b><br />Necesitas <a href="%3$s">incluir tu hermandad</a> y asegurarte de que has <a href="%4$s">terminado la configuración</a><br /><br /><a href="http://www.wowroster.net/wiki/Roster:Install" target="_blank">Pulsa aquí para las instrucciones de instalación</a>';
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
$lang['module_not_exist'] = 'The module [%1$s] does not exist';

$lang['addon_error'] = 'Addon Error';
$lang['specify_addon'] = 'You must specify an addon name!';
$lang['addon_not_exist'] = '<b>The addon [%1$s] does not exist!</b>';
$lang['addon_disabled'] = '<b>The addon [%1$s] has been disabled</b>';
$lang['addon_not_installed'] = '<b>The addon [%1$s] has not been installed yet</b>';
$lang['addon_no_config'] = '<b>The addon [%1$s] does not have a config</b>';

$lang['char_error'] = 'Character Error';
$lang['specify_char'] = 'Character was not specified';
$lang['no_char_id'] = 'Sorry no character data for member_id [ %1$s ]';
$lang['no_char_name'] = 'Sorry no character data for <strong>%1$s</strong> of <strong>%2$s</strong>';

$lang['roster_cp'] = 'Roster Control Panel';
$lang['roster_cp_ab'] = 'Roster CP';
$lang['roster_cp_not_exist'] = 'Page [%1$s] does not exist';
$lang['roster_cp_invalid'] = 'Invalid page specified or insufficient credentials to access this page';

$lang['parsing_files'] = 'Parsing files';
$lang['parsed_time'] = 'Parsed %1$s in %2$s seconds';
$lang['error_parsed_time'] = 'Error while parsing %1$s after %2$s seconds';
$lang['upload_not_accept'] = 'Did not accept %1$s';
$lang['not_updating'] = 'NOT Updating %1$s for [%2$s] - %3$s';
$lang['not_update_guild'] = 'NOT Updating Guild List for %1$s';
$lang['not_update_guild_time'] = 'NOT Updating Guild List for %1$s. Guild profile is too old';
$lang['no_members'] = 'Data does not contain any guild members';
$lang['upload_data'] = 'Updating %1$s Data for [<span class="orange">%2$s@4$s-%3$s</span>]';
$lang['realm_ignored'] = 'Realm: %1$s Not Scanned';
$lang['guild_realm_ignored'] = 'Guild: %1$s @ Realm: %2$s  Not Scanned';
$lang['update_members'] = 'Updating Members';
$lang['gp_user_only'] = 'GuildProfiler User Only';
$lang['update_errors'] = 'Update Errors';
$lang['update_log'] = 'Update Log';
$lang['save_error_log'] = 'Save Error Log';
$lang['save_update_log'] = 'Save Update Log';

$lang['new_version_available'] = 'There is a new version of %1$s available <span class="green">v%2$s</span><br />Get it <a href="%3$s" target="_blank">HERE</a>';


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
<li>Inicia WoW</li>
<li>Abre tu ventana de banco, misiones, y profesiones que contengan recetas</li>
<li>Desconecta/Sal de WoW (Mira más arriba si deseas utilizar UniUploader para enviar los datos automáticamente.)</li>
<li>Vete a <a href="%4$s">la página de actualización</a></li>
<li>%5$s</li>
</ol>';

$lang['update_instructpvp']='
<strong>Estadísticas JcJ Opcionales:</strong>
<ol>
<li>Descarga <a href="%1$s" target="_blank">PvPLog</a></li>
<li>Extrae PvPLog en la carpeta de Addons.</li>
<li>Haz duelos o combates JcJ</li>
<li>Envía PvPLog.lua</li>
</ol>';

$lang['roster_credits']='Agradecimientos a <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, y <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> por el código original usado en este sitio.<br />
Página principal de WoWRoster - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft y Blizzard Entertainment son marcas registradas de Blizzard Entertainment, Inc. en los E.U.A. y/u otros países. El resto de marcas registradas pertenecen a sus respectivos propietarios.<br />
<a href="%1$s">Créditos Adicionales</a>';


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


//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
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
$lang['officer_note']='Officer Note';
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
$lang['search']='Búsqueda';
$lang['update']='Update';
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
$lang['crit'] = 'Crit';
$lang['dodge'] = 'Esquivar';
$lang['parry'] = 'Parar';
$lang['block'] = 'Bloquear';
$lang['realm'] = 'Reino';
$lang['region'] = 'Region';
$lang['server'] = 'Server';
$lang['faction'] = 'Faction';
$lang['page'] = 'Página';
$lang['general'] = 'General';
$lang['prev'] = 'Anterior';
$lang['next'] = 'Siguiente';
$lang['memberlog'] = 'Registro';
$lang['removed'] = 'Borrado';
$lang['added'] = 'Añadido';
$lang['add'] = 'Add';
$lang['delete'] = 'Delete';
$lang['updated'] = 'Updated';
$lang['no_info'] = 'No Information';
$lang['none']='Ninguno';
$lang['kills']='Asesinatos';
$lang['allow'] = 'Allow';
$lang['disallow'] = 'Disallow';
$lang['locale'] = 'Locale';
$lang['default'] = 'Default';

$lang['rosterdiag'] = 'Roster Diag.';
$lang['difficulty'] = 'Dificultad';
$lang['recipe_4'] = 'Óptima';
$lang['recipe_3'] = 'Media';
$lang['recipe_2'] = 'Fácil';
$lang['recipe_1'] = 'Trivial';
$lang['roster_config'] = 'Config. Roster';

$lang['search_names'] = 'Search Names';
$lang['search_items'] = 'Search Items';
$lang['search_tooltips'] = 'Search Tooltips';

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

// Class Color-Array
$lang['class_colorArray'] = array(
	'Druida' => 'FF7C0A',
	'Cazador' => 'AAD372',
	'Mago' => '68CCEF',
	'Palad�n' => 'F48CBA',
	'Sacerdote' => 'ffffff',
	'P�caro' => 'FFF468',
	'Cham�n' => '00DBBA',
	'Brujo' => '9382C9',
	'Guerrero' => 'C69B6D'
);

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
$lang['honored']='Honrado';
$lang['friendly']='Amistoso';
$lang['neutral']='Neutral';
$lang['unfriendly']='Antipático';
$lang['hostile']='Hostil';
$lang['hated']='Odiado';
$lang['atwar']='En Guerra';
$lang['notatwar']='En Paz';


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
$lang['data_search'] = 'WoW Data Site Search';
$lang['itemlinks']['Thottbot'] = 'http://www.thottbot.com/index.cgi?i=';
$lang['itemlinks']['Allakhazam'] = 'http://wow.allakhazam.com/search.html?q=';
$lang['itemlinks']['WoW-Lista'] = 'http://www.wow-lista.com/buscador.php?abuscar=';
//$lang['itemlinks']['WoWHead'] = 'http://www.wowhead.com/?items&amp;filter=na=';

// WoW Data Site Search
// Add as many item links as you need
// Just make sure their names are unique
$lang['data_search'] = 'WoW Data Site Search';
$lang['data_links']['Thottbot'] = 'http://www.thottbot.com/index.cgi?s=';
$lang['data_links']['Allakhazam'] = 'http://wow.allakhazam.com/search.html?q=';
$lang['data_links']['WoW-Lista'] = 'http://www.wow-lista.com/buscador.php?abuscar=';
//$lang['data_links']['WoWHead'] = 'http://www.wowhead.com/?search=';


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
$lang['CPver_err']='La versión de CharacterProfiler utilizada para capturar los datos de este personaje es inferior a la versión mínima admitida para el envío.<br />\nPor favor, asegúrate de que estás utilizando al menos la versión %1$s y has iniciado sesión y grabado los datos utilizando la misma.';
$lang['PvPLogver_err']='La versión de PvPLog uutilizada para capturar los datos de este personaje es inferior a la versión mínima admitida para el envío.<br />\nPor favor, asegúrate de que estás utilizando al menos la versión %1$s, y si has actualizado recientemente PvPLog, asegúrate de que has borrado el antiguo fichero PvPLog.lua en la carpeta SavedVariables antes de enviar los datos.';
$lang['GPver_err']='La versión de GuildProfiler uutilizada para capturar los datos de este personaje es inferior a la versión mínima admitida para el envío.<br />\nPor favor, asegúrate de que estás utilizando al menos la versión %1$s';

// Menu titles
$lang['menu_upprofile']='Update Profile|Update your profile on this site';
$lang['menu_search']='Search|Search items and recipes in the database';
$lang['menu_roster_cp']='Roster CP|Roster Configuration Panel';
$lang['menu_credits']='Credits|Who made WoW Roster';

$lang['menuconf_sectionselect']='Select Section';

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

// Password Stuff
$lang['password'] = 'Password';
$lang['changeadminpass'] = 'Change Admin Password';
$lang['changeupdatepass'] = 'Change Update Password';
$lang['changeguildpass'] = 'Change Guild Password';
$lang['old_pass'] = 'Old Password';
$lang['new_pass'] = 'New Password';
$lang['new_pass_confirm'] = 'New Password [ confirm ]';
$lang['pass_old_error'] = 'Wrong password. Please enter the correct old password';
$lang['pass_submit_error'] = 'Submit error. The old password, the new password, and the confirmed new password need to be submitted';
$lang['pass_mismatch'] = 'Passwords do not match. Please type the exact same password in both new password fields';
$lang['pass_blank'] = 'No blank passwords. Please enter a password in both fields. Blank passwords are not allowed';
$lang['pass_isold'] = 'Password not changed. The new password was the same as the old one';
$lang['pass_changed'] = 'Password changed. Your new password is [ %1$s ].<br /> Do not forget this password, it is stored encrypted only';
$lang['auth_req'] = 'Authorization Required';

// Upload Rules
$lang['upload_rules_help'] = 'The rules are divided into two blocks.<br />For each uploaded guild/char, first the top block is checked.<br />If the name@server matches one of these \'deny\' rules, it is rejected.<br />After that the second block is checked.<br />If the name@server matches one of these \'accept\' rules, it is accepted.<br />If it does not match any rule, it is rejected.';

/******************************
 * Roster Admin Strings
 ******************************/

$lang['pagebar_function'] = 'Function';
$lang['pagebar_rosterconf'] = 'Configure Main Roster';
$lang['pagebar_uploadrules'] = 'Upload Rules';
$lang['pagebar_changepass'] = 'Change Password';
$lang['pagebar_addoninst'] = 'Manage Addons';
$lang['pagebar_update'] = 'Upload Profile';
$lang['pagebar_rosterdiag'] = 'Roster Diag';
$lang['pagebar_menuconf'] = 'Menu Configuration';
$lang['pagebar_configreset'] = 'Config Reset';

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
//   Assign description text and tooltip for $roster->config['sqldebug']
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
$lang['admin']['realmstatus_conf'] = 'Realmstatus|Options for Realmstatus<br><br>To turn this off, look in the Menu section';
$lang['admin']['data_links'] = 'Item/Quest Data Links|External links for item and quest data';
$lang['admin']['update_access'] = 'Update Access|Set access levels for rostercp components';

$lang['admin']['documentation'] = 'Documentation|WoWRoster Documentation via the wowroster.net wiki';

// main_conf
$lang['admin']['roster_dbver'] = "Roster Base de datos Versi&oacute;n|Lave versi&oacute;n de la base de datos";
$lang['admin']['version'] = "Roster Versi&oacute;n|Versi&oacute;n actual del Roster";
$lang['admin']['sqldebug'] = "Mensajes de SQL|Muestra errores de MySQL en comentarios en HTML";
$lang['admin']['debug_mode'] = "Depurar Modo|Depurar errores mostrados en los comentarios ";
$lang['admin']['sql_window'] = "SQL Window|Displays SQL Queries in a window in the footer";
$lang['admin']['minCPver'] = "Min CP versi&oacute;n|M&iacutenima versi&oacute;n permitida para usar el CharacterProfiler";
$lang['admin']['minGPver'] = "Min GP versi&oacute;n|M&iacutenima versi&oacute;n permitida para usar el GuildProfiler";
$lang['admin']['locale'] = "Roster Lenguaje principal|Elige el lenguaje del interfaz";
$lang['admin']['default_page'] = "Default Page|Page to display if no page is specified in the url";
$lang['admin']['website_address'] = "Website dirección|Usada para el URL del logo, y para el link del nombre de la hermandad en el menú principal<br>Algunos addons del roster usarán esto";
$lang['admin']['interface_url'] = "Directorio del Interfaz|Directorio donde se encuentran las im&aacute;genes del interfaz<br>El predeterminado es &quot;img/&quot;<br><br>Puedes usar un path relativo o el completo";
$lang['admin']['img_suffix'] = "Extensi&oacute;n de las im&aacute;genes del interfaz|El tipo de im&aacute;genes que usa tu interfaz";
$lang['admin']['alt_img_suffix'] = "Extensi&oacute;n de las im&aacute;genes del interfaz Alt|Posibilidad alternativa de los tipos de im&aacute;genes para el interfaz";
$lang['admin']['img_url'] = "Directorio de im&aacute;genes del Roster|Directorio donde estan localizadas las imagenes del Roster<br>El predeterminado es &quot;img/&quot;<br><br>Puedes usar un path relativo o el completo";
$lang['admin']['timezone'] = "HoraZona|Mostrar&aacute; la hora de tu regi&oacute;n geogr&aacute;fica";
$lang['admin']['localtimeoffset'] = "Diferencia horaria|La diferencia horaria desde el UTC/GMT<br>La hora del roster ser&aacute; calculada con esta diferencia";
$lang['admin']['use_update_triggers'] = "Actualizar Addon Triggers|Esto se utiliza con addons que necesitan ser ejecutados mientras actualizas un personaje o la hermandad<br>Algunos addons requieren de esto para funcionar correctamente";
$lang['admin']['check_updates'] = "Check for Updates|This allows your copy of WoWRoster (and addons that use this feature) to check if you have the newest version of the software";
$lang['admin']['seo_url'] = "Alternative urls|Use /some/page/here.html?param=value instead of /?p=some-page-here&param=value";

// guild_conf
$lang['admin']['default_name'] = "WowRoster Name|Enter a name to be displayed when not in the guild or char scope";
$lang['admin']['default_desc'] = "Description|Enter a short description to be displayed when not in the guild or char scope";
$lang['admin']['alt_type'] = "Alt-Texto b&uacute;squeda|Asignamos un texto a cada uno de los alts de la gente, para su siguiente localizaci&oacute;n";
$lang['admin']['alt_location'] = "Campo b&uacute;squeda de alts|Indica el campo en el que se tiene que buscar la etiqueta indicada en el campo anterior";

// menu_conf
$lang['admin']['menu_conf_left'] = "Left pane|";
$lang['admin']['menu_conf_right'] = "Right pane|";

$lang['admin']['menu_top_pane'] = "Top Pane|Controls display of the top pane of the main roster menu<br />This area holds the guild name, server, last update, etc...";
$lang['admin']['menu_top_faction'] = "Faction Icon|Controls display of the faction icon in the top pane of the main roster menu";
$lang['admin']['menu_top_locale'] = "Locale Selection|Controls display of the locale selection in the top pane of the main roster menu";
$lang['admin']['menu_top_list'] = "List Selection|Controls display of the realm/guild/character list in the top pane of the main roster menu";

$lang['admin']['menu_left_type'] = "Display type|Decide whether to show a level overview, a class overview, the realm status, or nothing at all";
$lang['admin']['menu_left_level'] = "Minimum level|Minimum level for characters to be included in the level/class overview";
$lang['admin']['menu_left_style'] = "Display style|Show as a list, a linear bargraph, or a logarithmic bargraph";
$lang['admin']['menu_left_barcolor'] = "Bar color|The color for the bar showing the number of characters of this level group/class";
$lang['admin']['menu_left_bar2color'] = "Bar 2 color|The color for the bar showing the number of alts of this level group/class";
$lang['admin']['menu_left_textcolor'] = "Text color|The color for the level/class group labels (class graph uses class colors for labels)";
$lang['admin']['menu_left_outlinecolor'] = "Text Outline color|The outline color for the level/class group labels<br />Clear this box to turn the outline off";
$lang['admin']['menu_left_text'] = "Text Font|The font for the level/class group labels";

$lang['admin']['menu_right_type'] = "Display type|Decide whether to show a level overview, a class overview, the realm status, or nothing at all";
$lang['admin']['menu_right_level'] = "Minimum level|Minimum level for characters to be included in the level/class overview";
$lang['admin']['menu_right_style'] = "Display style|Show as a list, a linear bargraph, or a logarithmic bargraph";
$lang['admin']['menu_right_barcolor'] = "Bar color|The color for the bar showing the number of characters of this level group/class";
$lang['admin']['menu_right_bar2color'] = "Bar 2 color|The color for the bar showing the number of alts of this level group/class";
$lang['admin']['menu_right_textcolor'] = "Text color|The color for the level/class group labels (class graph uses class colors for labels)";
$lang['admin']['menu_right_outlinecolor'] = "Text Outline color|The outline color for the level/class group labels<br />Clear this box to turn the outline off";
$lang['admin']['menu_right_text'] = "Text font|The font for the level/class group labels";

$lang['admin']['menu_bottom_pane'] = "Bottom Pane|Controls display of the bottom pane of the main roster menu<br />This area holds the search box";

// display_conf
$lang['admin']['logo'] = "URL para el logo de la cabecera|Escribe el URL completo de la imagen o en su lugar &quot;img/&quot;nombre_logo. <br>Esta imagen ser&aacute; mostrada en la cabecera de la p&aacute;gina";
$lang['admin']['roster_bg'] = "URL para la imagen del fondo|Indica el URL completo de la imagen a mostrar en el fondo de la web<br />o el nombre relativo &quot;img/&quot;";
$lang['admin']['motd_display_mode'] = "Modo de mostrar MDD|Elige como aparecer&aacute; el texto del mensaje del d&iacutea<br><br>&quot;Texto&quot; - Muestra el MDD en rojo<br>&quot;Imagen&quot; - Muestra el MDD en una imagen (REQUERIDO GD!)";
$lang['admin']['signaturebackground'] = "img.php Fondo|Soporte para elegir el fondo de pantalla";
$lang['admin']['processtime'] = "Pag Gen. Tiempo/DB Colas|Mostrar &quot;Esta p&aacute;gina fue creada en XXX segundos con XX preguntas ejecutadas&quot; en el pie del roster";

// data_links
$lang['admin']['questlink_1'] = "Enlace para misiones #1|Enlace externo para buscar Objetos/Misiones<br>Mira en tu archivo localization para configurar los enlaces.";
$lang['admin']['questlink_2'] = "Enlace para misiones #2|Enlace externo para buscar Objetos/Misiones<br>Mira en tu archivo localization para configurar los enlaces.";
$lang['admin']['questlink_3'] = "Enlace para misiones #3|Enlace externo para buscar Objetos/Misiones<br>Mira en tu archivo localization para configurar los enlaces.";
$lang['admin']['profiler'] = "Enlace para descargar CharacterProfiler|URL para descargar CharacterProfiler";
$lang['admin']['uploadapp'] = "Enlace para descargar UniUploader|URL para descargar UniUploader";

// realmstatus_conf
$lang['admin']['realmstatus_url'] = "Estado real del reino URL|URL a la p&aacute;gina de Blizzard's Realmstatus ";
$lang['admin']['rs_display'] = "Mostrar Informaci&oacute;n|&quot;lleno&quot; mostrar&aacute; el estado y el nombre del servidor, poblaci&oacute;n y tipo<br>&quot;medio&quot; mostrar&aacute; el estado del reino";
$lang['admin']['rs_mode'] = "Modo de mostrar|Como aparecer&aacute; el EstadoReino<br><br>&quot;DIV Container&quot; - Muestra el reino en una imagen con un texto<br>&quot;Imagen&quot; - Muestra el ReinoEstado como una imagen (REQUERIDO GD!)";
$lang['admin']['realmstatus'] = "Nombre alternativo del reino|Algunos nombres de los servidores no permiten al ReinoEstado funcionar correctamente<br>A veces el nombre del servidor no es encontrado en la base de datos de la p&aacute;gina de EstadoReino<br>Puedes activar esta opci&oacute;n y as&iacute utilizar otro nombre para tu servidor<br><br>D&eacutejalo en blanco para utilizar el nombre elegido en la configuraci&oacute;n de la hermandad";
$lang['admin']['rs_timer'] = "Refresh Timer|Set the timeout period for fetching new realmstatus data";
$lang['admin']['rs_left'] = "Display|";
$lang['admin']['rs_middle'] = "Type Display Settings|";
$lang['admin']['rs_right'] = "Population Display Settings|";
$lang['admin']['rs_font_server'] = "Realm Font|Font for the realm name<br />(Image mode only!)";
$lang['admin']['rs_size_server'] = "Realm Font Size|Font size for the realm name<br />(Image mode only!)";
$lang['admin']['rs_color_server'] = "Realm Color|Color of realm name";
$lang['admin']['rs_color_shadow'] = "Shadow Color|Color for text drop shadows<br />(Image mode only!)";
$lang['admin']['rs_font_type'] = "Type Font|Font for the realm type<br />(Image mode only!)";
$lang['admin']['rs_size_type'] = "Type Font Size|Font size for the realm type<br />(Image mode only!)";
$lang['admin']['rs_color_rppvp'] = "RP-PvP Color|Color for RP-PvP";
$lang['admin']['rs_color_pve'] = "Normal Color|Color for Normal";
$lang['admin']['rs_color_pvp'] = "PvP Color|Color for PvP";
$lang['admin']['rs_color_rp'] = "RP Color|Color for RP";
$lang['admin']['rs_color_unknown'] = "Unknown Color|Color for unknown";
$lang['admin']['rs_font_pop'] = "Pop Font|Font for the realm population<br />(Image mode only!)";
$lang['admin']['rs_size_pop'] = "Pop Font Size|Font size for the realm population<br />(Image mode only!)";
$lang['admin']['rs_color_low'] = "Low Color|Color for low population";
$lang['admin']['rs_color_medium'] = "Medium Color|Color for medium population";
$lang['admin']['rs_color_high'] = "High Color|Color for high population";
$lang['admin']['rs_color_max'] = "Max Color|Color for max population";
$lang['admin']['rs_color_error'] = "Offline Color|Color for realm offline";

// update_access
$lang['admin']['authenticated_user'] = "Acceso a Update.php|Controla el acceso a update.php<br /><br />Poniendo esta opcion en off desactivas el acceso para todo el mundo.";

// Character Display Settings
$lang['admin']['per_character_display'] = 'Pantalla Per-Character';
