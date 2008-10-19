<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
 * @subpackage Locale
*/

// -[ esES Localization: maqjav ]-

// Installer names
$lang['memberslist']            = 'Listado de miembros';
$lang['memberslist_desc']       = 'Muestra el listado de miembros de la hermandad';

// Button names
$lang['memberslist_Members']	= 'Miembros|Muestra el listado de miembros de la hermandad con las notas de los jugadores, última conexión, etc...';
$lang['memberslist_Stats']		= 'Estadísticas|Muestra las estadísticas de los miembros de la hermandad, como la inteligencia, fortaleza, etc...';
$lang['memberslist_Honor']		= 'Honor|Muestra información sobre JcJ de los miembros de la hermandad';
$lang['memberslist_Log']		= 'Registro de miembros|Muestra el registro de actualizaciones de miembros';
$lang['memberslist_Realm']		= 'Miembros|Muestra el listado de miembros de todas las hermandades del reino';
$lang['memberslist_RealmGuild']	= 'Hermandades|Muestra un listado de todas las hermandades del reino';
$lang['memberslist_Skills']		= 'Skills|Displays each member\'s skills';

// Interface wordings
$lang['memberssortfilter']		= 'Filtrado y ordenado';
$lang['memberssort']			= 'Ordenar';
$lang['memberscolshow']			= 'Mostrar/esconder columnas';
$lang['membersfilter']			= 'Filtrar filas';

$lang['openall']                = 'Abrir todo';
$lang['closeall']               = 'Cerrar todo';
$lang['ungroupalts']            = 'Desagrupar alts';
$lang['openalts']               = 'Agrupar alts (Abrir)';
$lang['closealts']              = 'Agrupar alts (Cerrar)';
$lang['clientsort']             = 'Orden del cliente';
$lang['serversort']             = 'Orden del servidor';

// Column headers
$lang['onote']                  = 'Nota de oficial';

$lang['honorpoints']            = 'Puntos de honor';
$lang['arenapoints']            = 'Puntos de arena';

$lang['main_name']              = 'Personaje principal';
$lang['alt_type']               = 'Personaje Alt';

$lang['xp_to_go']               = '%1$s PE hasta nivel %2$s';

$lang['skill_level']		= 'Nivel de habilidad';

// Last Online words
$lang['online_at_update']       = 'Conectado en la actualización';
$lang['second']                 = 'hace %s segundo';
$lang['seconds']                = 'hace %s segundos';
$lang['minute']                 = 'hace %s minuto';
$lang['minutes']                = 'hace %s minutos';
$lang['hour']                   = 'hace %s hora';
$lang['hours']                  = 'hace %s horas';
$lang['day']                    = 'hace %s día';
$lang['days']                   = 'hace %s días';
$lang['week']                   = 'hace %s semana';
$lang['weeks']                  = 'hace %s semanas';
$lang['month']                  = 'hace %s mes';
$lang['months']                 = 'hace %s meses';
$lang['year']                   = 'hace %s año';
$lang['years']                  = 'hace %s años';

$lang['armor_tooltip']			= 'Reduce el daño físico hecho por %1$s%%';

$lang['motd']                   = 'MDD';
$lang['accounts']               = 'Cuentas';

// Configuration
$lang['memberslist_config']		= 'Ves a la configuración del listado de miembros';
$lang['memberslist_config_page']= 'Configuración del listado de miembros';
$lang['documentation']			= 'Documentación';
$lang['uninstall']				= 'Desinstalar';

// Page names
$lang['admin']['main']			= 'Principal|Vuelve a la parte de configuración global.';
$lang['admin']['display']       = 'Mostrar|Configura opciones específicas de como mostrar el listado de miembros.';
$lang['admin']['members']       = 'Listado de miembros|Configura el modo de mostrar las columnas del listado de miembros.';
$lang['admin']['stats']         = 'Lista de estadísticas|Configura el modo de mostrar las columnas del listado de estadísticas.';
$lang['admin']['honor']         = 'Lista de honor|Configura el modo de mostrar las columnas del listado de honor.';
$lang['admin']['log']           = 'Registro de miembros|Configura el modo de mostrar las columnas del registro de miembros.';
$lang['admin']['build']         = 'Relaciones principal/alt|Configura como detectar las relaciones entre personajes principales y alts.';
$lang['admin']['gbuild']        = 'Principal/Alt por hermandad|Configura la detección de los personajes alternativos por hermandad.';
$lang['admin']['ml_wiki']       = 'Documentación|Documentación sobre el listado de miembros en WoWRoster wiki.';
$lang['admin']['updMainAlt']    = 'Actualizar relaciones|Actualiza relaciones entre personajes principales y alts usando los datos de la BD.';
$lang['admin']['page_size']		= 'Tamaño de página|Configura el número de objetos por página o elige 0 par ano mostrar paginación';

// Settings names on display page
$lang['admin']['openfilter']	= 'Abrir caja de filtros|Elige si quieres abrir la caja de filtros o cerrarla predeterminadamente.';
$lang['admin']['nojs']          = 'Tipo de lista|Elige si quieres ordenar el listado según el modo de organizar los datos en el servidor o en el cliente orden+filtro.';
$lang['admin']['def_sort']		= 'Orden predeterminado|Elige el modo predeterminado de mostrar los datos.';
$lang['admin']['member_tooltip']= 'Utilidades de personaje|Activa o desactiva mostrar las utilidades del personaje en el nombre del miembro.';
$lang['admin']['group_alts']    = 'Grupo de alts|Agrupa a los personajes alts debajo del principal, ordenandolos por separado.';
$lang['admin']['icon_size']     = 'Tamaño de icono|Elige el tamaño del icono de clases/honor/profesiones.';
$lang['admin']['spec_icon']		= 'Icono de talento espec.|Activa o desactiva mostrar el icono de la especialización en talentos.';
$lang['admin']['class_icon']    = 'Icono de clase|Elige si mostrar el icono de clase/talento.<br />Completo - Muestra todos los iconos<br />On - Muestra solo el icono de clase<br />Off - Esconde los iconos';
$lang['admin']['class_text']    = 'Texto de clase|Elige si mostrar el texto de la clase.<br />Color - Colorea el nombre de la clase<br />On - Muestra el texto de la clase<br />Off - Esconde el texto de la clase';
$lang['admin']['talent_text']   = 'Texto de talento|Muestra la especialización de talentos en lugar del nombre de clase.';
$lang['admin']['level_bar']     = 'Barras de nivel|Muestra barras de nivel en lugar de números.';
$lang['admin']['honor_icon']    = 'Icono de honor|Muestra el icono con el rango de honor.';
$lang['admin']['compress_note'] = 'Comprimir nota|Muestra la nota de la hermandad en una ventana en lugar de en una columna.';

// Settings on Members page
$lang['admin']['member_update_inst'] = 'Instrucciones de actualización|Elige si mostrar o no las instrucciones de actualización en la página de miembros.';
$lang['admin']['member_motd']	= 'MDD de la hermandad|Muestra el mensaje del día de la hermandad encima de la lista de miembros.';
$lang['admin']['member_hslist']	= 'Estadísticas de honor|Configura el modo de mostrar el listado de las estadísticas de honor, en la página de miembros.';
$lang['admin']['member_pvplist']= 'Estadísticas de JcJ|Elige si mostrar o no el registro de estadísticas JcJ en la página de miembros.<br />Si tienes desactivada la subida de PvPlog, no necesitas activar esta opción';
$lang['admin']['member_class']  = 'Clases|Elige si mostrar la columna de clases en la página de miembros';
$lang['admin']['member_level']  = 'Niveles|Elige si mostrar la columna de niveles en la página de miembros';
$lang['admin']['member_gtitle'] = 'Título de la hermandad|Elige si mostrar el título de la hermandad en la página de miembros';
$lang['admin']['member_hrank']  = 'Rango de honor|Elige si mostrar la columna del último rango de honor en la página de miembros';
$lang['admin']['member_prof']   = 'Profesiones|Elige si mostrar la columna de profesiones en la página de miembros';
$lang['admin']['member_hearth'] = 'Piedra|Elige si mostrar la columna de la piedra de hogar en la página de miembros';
$lang['admin']['member_zone']   = 'Zona|Elige si mostrar la columna de la última zona visitada en la página de miembros';
$lang['admin']['member_online'] = 'Última conexión|Elige si mostrar la columna de última conexión en la página de miembros';
$lang['admin']['member_update'] = 'Última actualización|Elige si mostrar la columna de última actualización en la página de miembros';
$lang['admin']['member_note']   = 'Notas|Elige si mostrar la columna de notas de jugador en la página de miembros';
$lang['admin']['member_onote']  = 'Notas de oficial|Elige si mostrar la columna de notas de oficial en la página de miembros';

// Settings on Stats page
$lang['admin']['stats_update_inst'] = 'Instrucciones de actualización|Elige si mostrar o no las instrucciones de actualización en la página de estadísticas';
$lang['admin']['stats_motd']	= 'MDD de la hermandad|Muestra el mensaje del día de la hermandad encima de la lista de estadísticas';
$lang['admin']['stats_hslist']  = 'Estadísticas de honor|Configura el modo de mostrar el listado de las estadísticas de honor, en la página estadísticas';
$lang['admin']['stats_pvplist']	= 'Estadísticas de JcJ|Elige si mostrar o no el registro de estadísticas JcJ en la página de estadísticas.<br />Si tienes desactivada la subida de PvPlog, no necesitas activar esta opción';
$lang['admin']['stats_class']   = 'Clases|Elige si mostrar la columna de clases en la página de estadísticas';
$lang['admin']['stats_level']   = 'Niveles|Elige si mostrar la columna de niveles en la página de estadísticas';
$lang['admin']['stats_str']     = 'Fortaleza|Elige si mostrar la columna de fortaleza en la página de estadísticas';
$lang['admin']['stats_agi']     = 'Agilidad|Elige si mostrar la columna de agilidad en la página de estadísticas';
$lang['admin']['stats_sta']     = 'Aguante|Elige si mostrar la columna de aguante en la página de estadísticas';
$lang['admin']['stats_int']     = 'Intelecto|Elige si mostrar la columna de intelecto en la página de estadísticas';
$lang['admin']['stats_spi']     = 'Espiritú|Elige si mostrar la columna de espiritú en la página de estadísticas';
$lang['admin']['stats_sum']     = 'Total|Elige si mostrar la columna suma en la página de estadísticas';
$lang['admin']['stats_health']  = 'Salud|Elige si mostrar la columna de salud en la página de estadísticas';
$lang['admin']['stats_mana']    = 'Maná|Elige si mostrar la columna de maná en la página de estadísticas';
$lang['admin']['stats_armor']   = 'Armadura|Elige si mostrar la columna de armadura en la página de estadísticas';
$lang['admin']['stats_dodge']   = 'Esquivar|Elige si mostrar la columna de probabilidad de esquivar en la página de estadísticas';
$lang['admin']['stats_parry']   = 'Parar|Elige si mostrar la columna de probabilidad de parar en la página de estadísticas';
$lang['admin']['stats_block']   = 'Bloquear|Elige si mostrar la columna de probabilidad de bloquear en la página de estadísticas';
$lang['admin']['stats_crit']    = 'Crítico|Elige si mostrar la columna de probabilidad de hacer un crítico en la página de estadísticas';

// Settings on Honor page
$lang['admin']['honor_update_inst'] = 'Instrucciones de actualización|Elige si mostrar o no las instrucciones de actualización en la página de honor';
$lang['admin']['honor_motd']	= 'MDD de la hermandad|Muestra el mensaje del día de la hermandad encima de la lista de honor';
$lang['admin']['honor_hslist']  = 'Estadísticas de honor|Configura el modo de mostrar el listado de las estadísticas de honor, en la página de honor';
$lang['admin']['honor_pvplist']	= 'Estadísticas de JcJ|Elige si mostrar o no el registro de estadísticas JcJ en la página de honor.<br />Si tienes desactivada la subida de PvPlog, no necesitas activar esta opción';
$lang['admin']['honor_class']   = 'Clase|Elige si mostrar la columna de clases en la página de honor';
$lang['admin']['honor_level']   = 'Nivel|Elige si mostrar la columna de niveles en la página de honor';
$lang['admin']['honor_thk']     = 'MH del día|Elige si mostrar la columna de muertes con honor del día en la página de honor';
$lang['admin']['honor_tcp']     = 'MP del día|Elige si mostrar la columna de muertes con penalización de honor en la página de honor';
$lang['admin']['honor_yhk']     = 'MH de ayer|Elige si mostrar la columna de muertes con honor del día anterior en la página de honor';
$lang['admin']['honor_ycp']     = 'MP de ayer|Elige si mostrar la columna de muertes con penalización de honor en la página de honor';
$lang['admin']['honor_lifehk']  = 'MH de vida|Elige si mostrar la columna de muertes con honor en toda la vida del personaje en la página de honor';
$lang['admin']['honor_hrank']   = 'Rango de honor|Elige si mostrar la columna de rango en la página de honor';
$lang['admin']['honor_hp']      = 'Puntos de honor|Elige si mostrar la columna de puntos de honor en la página de honor';
$lang['admin']['honor_ap']      = 'Puntos de arena|Elige si mostrar la columna de puntos de arena en la página de honor';

// Settings on Members page
$lang['admin']['log_update_inst'] = 'Instrucciones de actualización|Elige si mostrar o no las instrucciones de actualización en la página de registro de miembros';
$lang['admin']['log_motd']		= 'MDD de la hermandad|Muestra el mensaje del día de la hermandad encima de la lista de registro de miembros';
$lang['admin']['log_hslist']	= 'Estadísticas de honor|Configura el modo de mostrar el listado de las estadísticas de honor, en la página de registro de miembros';
$lang['admin']['log_pvplist']	= 'Estadísticas de JcJ|Elige si mostrar o no el registro de estadísticas JcJ en la página de registro de miembros.<br />Si tienes desactivada la subida de PvPlog, no necesitas activar esta opción';
$lang['admin']['log_class']		= 'Clases|Elige si mostrar la columna de clases en la página de registro de miembros';
$lang['admin']['log_level']		= 'Nivel|Elige si mostrar la columna de niveles en la página de registro de miembros';
$lang['admin']['log_gtitle']	= 'Título de la hermandad|Elige si mostrar el título de la hermandad en la página de registro de miembros';
$lang['admin']['log_type']		= 'Tipo de actualización|Elige si mostrar la columna del tipo de actualización en la página de registro de miembros';
$lang['admin']['log_date']		= 'Última actualización|Elige si mostrar la columna con la fecha de actualización en la página de registro de miembros';
$lang['admin']['log_note']		= 'Nota|Elige si mostrar la columna con la nota en la página de registro de miembros';
$lang['admin']['log_onote']		= 'Nota de oficial|Elige si mostrar la nota de oficial en la página de registro de miembros';

// Settings names on build page
$lang['admin']['use_global']    = 'Usar configuraciones globales|Utiliza una configuración global en lugar de una local para esta hermandad.';
$lang['admin']['getmain_regex'] = 'Expresiones|Este campo especifica las expresiones a usar. <br />Mira en el enlace de wiki para mas detalles.';
$lang['admin']['getmain_field'] = 'Aplicar en un campo|Este campo especifica sobre que campo de miembro se aplica la expresión. <br />Mira en el enlace de wiki para mas detalles.';
$lang['admin']['getmain_match'] = 'Coincidencias|Este campo especifica que valor devolver con la expresión. <br />Mira en el enlace de wiki para mas detalles.';
$lang['admin']['getmain_main']  = 'Personaje principal id|Si la expresión coincide con este valor, el personaje se asigna como principal.';
$lang['admin']['defmain']       = 'Sin coincidencias|Selecciona como quieres que se defina el personaje si la expresión no retorna nada.';
$lang['admin']['invmain']       = 'Resultado inválido|Selecciona como quieres que se defina el personaje<br /> si la expresión retornada no es un miembro de la hermandad o no es igual a un identificador de personaje principal.';
$lang['admin']['altofalt']      = 'Alt de Alt|Especifica que el personaje es principal de otro alt.';
$lang['admin']['update_type']   = 'Tipo de actualización|Especifica que relaciones existen entre personaje principal y alt.';
