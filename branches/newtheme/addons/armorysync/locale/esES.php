<?php
/**
 * WoWRoster.net WoWRoster
 *
 * spanish localisaton - thx to Subxero
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: esES.php 370 2008-02-17 15:42:02Z poetter $
 * @link       http://www.wowroster.net
 * @package    ArmorySync
*/

// -[ esES Localization ]-

// Button names
$lang['async_button1']			= 'ArmorySync Personaje|Sincroniza el personaje con la Armería de Blizzard';
$lang['async_button2']			= 'ArmorySync Personaje|Sincroniza el personaje con la Armería de Blizzard';
$lang['async_button3']			= 'ArmorySync Personaje|Sincroniza el personaje con la Armería de Blizzard';
$lang['async_button4']			= 'ArmorySync Miembros|Sincroniza el listado de miembros con la Armería de Blizzard';
$lang['async_button5']			= 'ArmorySync nueva Hermandad|Añade una nueva Hermandad y sincroniza<br />la lista de miembros con la Armería de Blizzard';

// Config strings
$lang['admin']['armorysync_conf']			= 'Configuración ArmorySync|Configure base settings for armorysync';
$lang['admin']['armorysync_ranks']			= 'Ranks|Configure guild ranks for ArmorySync';
$lang['admin']['armorysync_images']			= 'Images|Configure image displaying for armorysync';
$lang['admin']['armorysync_access']			= 'Access Rights|Configure access rights for armorysync';
$lang['admin']['armorysync_debug']			= 'Debugging|Configure debug settings for armorysync';

$lang['admin']['armorysync_host']			= 'Servidor|Servidor con el que sincronizar.';
$lang['admin']['armorysync_minlevel']		= 'Nivel Minimo|Nivel minimo de los personajes para sincronizar.';
$lang['admin']['armorysync_synchcutofftime']	= 'Tiempo de Sync minimo|Tiempo en dias.<br />Todos los personajes que no se hayan actualizado en los ultimos (x) dias seran sincronizados.';
$lang['admin']['armorysync_use_ajax']	= 'Use AJAX|Wether to use AJAX for status update or not.';
$lang['admin']['armorysync_reloadwaittime']	= 'Tiempo de espera recarga|Tiempo en segundos.<br />Tiempo que espera antes de empezar la siguiente sincronización.';
$lang['admin']['armorysync_fetch_timeout'] = 'Tiempo de espera agotado para la Armory|Tiempo en segundos que se espera para recibir un solo archivo XML antes de ser cancelado.';
$lang['admin']['armorysync_fetch_retrys'] = 'Armory Fetch Retrys|How many retrys on failed fetched.';
$lang['admin']['armorysync_fetch_method'] = 'Armory Fetch method|Per char will do a reload per Character.<br />Per page will do a reload after every page that is fetched from the armory.';
$lang['admin']['armorysync_update_incomplete'] = 'Update incomplete data|This option determines if characters with incomplete data will be updated';
$lang['admin']['armorysync_skip_start'] = 'Saltar pagina inicial|Saltar pagina inicial al actualizar con ArmorySync.';
$lang['admin']['armorysync_status_hide'] = 'Hide status windows initialy|Hide the status windows of ArmorySync on the first load.';
$lang['admin']['armorysync_protectedtitle']	= "Rango Hermandad protegidos|Los personajes con este rango estan protegidos<br />no podran ser borrados por una actualización del listado de miembros desde la armería.<br />Amenudo el problema suele ocurrir con los personajes banqueros.<br />Es posible añadir mas de uno separandolo con \",\". Ejem. Banco,Almacen";

$lang['admin']['armorysync_rank_set_order']	= "Guild Rank Set Order|Defines in which order the guild titles will be set.";
$lang['admin']['armorysync_rank_0']	= "Title Guild Leader|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_1']	= "Title Rank 1|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_2']	= "Title Rank 2|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_3']	= "Title Rank 3|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_4']	= "Title Rank 4|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_5']	= "Title Rank 5|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_6']	= "Title Rank 6|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_7']	= "Title Rank 7|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_8']	= "Title Rank 8|This title will be set if in WoWRoster for that guild non is defined.";
$lang['admin']['armorysync_rank_9']	= "Title Rank 9|This title will be set if in WoWRoster for that guild non is defined.";

$lang['admin']['armorysync_char_update_access'] = 'Nivel de acceso Personajes|Quien puede hacer actualizaciones de personajes';
$lang['admin']['armorysync_guild_update_access'] = 'Nivel de acceso Hermandad|Quien puede hacer actualizaciones de hermandades';
$lang['admin']['armorysync_guild_memberlist_update_access'] = 'Nivel de acceso Listado de miembros|Quien puede hacer actualizaciones del listado de miembros';
$lang['admin']['armorysync_realm_update_access'] = 'Nivel de acceso Reino|Quien puede hacer actualizaciones de reinos';
$lang['admin']['armorysync_guild_add_access'] = 'Nivel de acceso añadir Hermandad|Quien puede añadir nuevas hermandades';

$lang['admin']['armorysync_logo'] = 'ArmorySync Logo|';
$lang['admin']['armorysync_pic1'] = 'ArmorySync Image 1|';
$lang['admin']['armorysync_pic2'] = 'ArmorySync Image 2|';
$lang['admin']['armorysync_pic3'] = 'ArmorySync Image 3|';
$lang['admin']['armorysync_effects'] = 'ArmorySync Image Effects|';
$lang['admin']['armorysync_logo_show'] = 'Show Logo|';
$lang['admin']['armorysync_pic1_show'] = $lang['admin']['armorysync_pic2_show'] = $lang['admin']['armorysync_pic3_show'] = 'Show Image|';
$lang['admin']['armorysync_pic_effects'] = 'Activated|Use JavaScript effects for images.';
$lang['admin']['armorysync_logo_pos_left'] = $lang['admin']['armorysync_pic1_pos_left'] = $lang['admin']['armorysync_pic2_pos_left'] = $lang['admin']['armorysync_pic3_pos_left'] = 'Image position horizontal|';
$lang['admin']['armorysync_logo_pos_top'] = $lang['admin']['armorysync_pic1_pos_top'] = $lang['admin']['armorysync_pic2_pos_top'] = $lang['admin']['armorysync_pic3_pos_top'] = 'Image position vertikal|';
$lang['admin']['armorysync_logo_size'] = $lang['admin']['armorysync_pic1_size'] = $lang['admin']['armorysync_pic2_size'] = $lang['admin']['armorysync_pic3_size'] = 'Image size|';
$lang['admin']['armorysync_pic1_min_rows'] = $lang['admin']['armorysync_pic2_min_rows'] = $lang['admin']['armorysync_pic3_min_rows'] = 'Minimun Rows|Minimum number of rows in the status display<br />to display the image.';

$lang['admin']['armorysync_debuglevel']		= 'Debug Level|Adjust the debug level for ArmorySync.<br /><br />Quiete - No Messages<br />Base Infos - Base messages<br />Armory & Job Method Infos - All messages of Armory and Job methods<br />All Methods Info - Messages of all Methods  <b style="color:red;">(Be careful - very much data)</b>';
$lang['admin']['armorysync_debugdata']		= 'Debug Data|Raise debug output by methods arguments and returns<br /><b style="color:red;">(Be careful - much more infos on high debug level)</b>';
$lang['admin']['armorysync_javadebug']		= 'Debug Java|Enable JavaScript debugging.<br />Not implemented yet.';
$lang['admin']['armorysync_xdebug_php']		= 'XDebug Session PHP|Enable sending XDEBUG variable with POST.';
$lang['admin']['armorysync_xdebug_ajax']	= 'XDebug Session AJAX|Enable sending XDEBUG variable  with Ajax POST.';
$lang['admin']['armorysync_xdebug_idekey']	= 'XDebug Session IDEKEY|Define IDEKEY for Xdebug sessions.';
$lang['admin']['armorysync_sqldebug']		= 'SQL Debug|Enable SQL debugging for ArmorySync.<br />Not useful in combination with roster SQL debugging / duplicate data.';
$lang['admin']['armorysync_updateroster']	= "Update Roster|Enable roster updates.<br />Good for debugging<br />Not implemented yet.";


$lang['bindings']['bind_on_pickup'] = "Se liga al recogerlo";
$lang['bindings']['bind_on_equip'] = "Se liga al equiparlo";
$lang['bindings']['bind'] = "Ligado";

// Addon strings
$lang['RepStanding']['Exalted'] = 'Exaltado';
$lang['RepStanding']['Revered'] = 'Venerado';
$lang['RepStanding']['Honored'] = 'Honorable';
$lang['RepStanding']['Friendly'] = 'Amistoso';
$lang['RepStanding']['Neutral'] = 'Neutral';
$lang['RepStanding']['Unfriendly'] = 'Enemigo';
$lang['RepStanding']['Hostile'] = 'Hostil';
$lang['RepStanding']['Hated'] = 'Odiado';

$lang['Skills']['Class Skills'] = "Habilidades de clase";
$lang['Skills']['Professions'] = "Profesiones";
$lang['Skills']['Secondary Skills'] = "Habilidades secundarias";
$lang['Skills']['Weapon Skills'] = "Armas disponibles";
$lang['Skills']['Armor Proficiencies'] = "Armaduras disponibles";
$lang['Skills']['Languages'] = "Lenguas";


$lang['Classes']['Druid'] = 'Druida';
$lang['Classes']['Hunter'] = 'Cazador';
$lang['Classes']['Mage'] = 'Mago';
$lang['Classes']['Paladin'] = 'Paladín';
$lang['Classes']['Priest'] = 'Sacerdote';
$lang['Classes']['Rogue'] = 'Pícaro';
$lang['Classes']['Shaman'] = 'Shamán';
$lang['Classes']['Warlock'] = 'Brujo';
$lang['Classes']['Warrior'] = 'Guerrero';

$lang['Talenttrees']['Druid']['Balance'] = "Equilibrio";
$lang['Talenttrees']['Druid']['Feral Combat'] = "Combate feral";
$lang['Talenttrees']['Druid']['Restoration'] = "Restauración";
$lang['Talenttrees']['Hunter']['Beast Mastery'] = "Dominio de bestias";
$lang['Talenttrees']['Hunter']['Marksmanship'] = "Puntería";
$lang['Talenttrees']['Hunter']['Survival'] = "Supervivencia";
$lang['Talenttrees']['Mage']['Arcane'] = "Arcano";
$lang['Talenttrees']['Mage']['Fire'] = "Fuego";
$lang['Talenttrees']['Mage']['Frost'] = "Escarcha";
$lang['Talenttrees']['Paladin']['Holy'] = "Sagrado";
$lang['Talenttrees']['Paladin']['Protection'] = "Protección";
$lang['Talenttrees']['Paladin']['Retribution'] = "Represión";
$lang['Talenttrees']['Priest']['Discipline'] = "Disciplina";
$lang['Talenttrees']['Priest']['Holy'] = "Sagrado";
$lang['Talenttrees']['Priest']['Shadow'] = "Sombras";
$lang['Talenttrees']['Rogue']['Assassination'] = "Asesinato";
$lang['Talenttrees']['Rogue']['Combat'] = "Combate";
$lang['Talenttrees']['Rogue']['Subtlety'] = "Sutileza";
$lang['Talenttrees']['Shaman']['Elemental'] = "Elemental";
$lang['Talenttrees']['Shaman']['Enhancement'] = "Mejora";
$lang['Talenttrees']['Shaman']['Restoration'] = "Restauración";
$lang['Talenttrees']['Warlock']['Affliction'] = "Aflicción";
$lang['Talenttrees']['Warlock']['Demonology'] = "Demonología";
$lang['Talenttrees']['Warlock']['Destruction'] = "Destrucción";
$lang['Talenttrees']['Warrior']['Arms'] = "Armas";
$lang['Talenttrees']['Warrior']['Fury'] = "Furia";
$lang['Talenttrees']['Warrior']['Protection'] = "Protección";

$lang['misc']['Rank'] = "Rango";

$lang['guild_short'] = "Herman.";
$lang['character_short'] = "Pers.";
$lang['skill_short'] = "Habil.";
$lang['reputation_short'] = "Repu.";
$lang['equipment_short'] = "Equipo";
$lang['talents_short'] = "Talen.";

$lang['started'] = "empezo";
$lang['finished'] = "acabo";

$lang['armorySyncTitle_Char'] = "ArmorySync para Personajes";
$lang['armorySyncTitle_Guild'] = "ArmorySync para Hermandades";
$lang['armorySyncTitle_Guildmembers'] = "ArmorySync para listado de miembros de una Hermandad";
$lang['armorySyncTitle_Realm'] = "ArmorySync para Reinos";

$lang['next_to_update'] = "Siguiente Actualización: ";
$lang['nothing_to_do'] = "Nada para hacer por el momento";

$lang['error'] = "Error";
$lang['error_no_character'] = "Sin referencias del personaje.";
$lang['error_no_guild'] = "Sin referencias de la hermandad.";
$lang['error_no_realm'] = "Sin referencias del reino.";
$lang['error_use_menu'] = "Use el menu para sincronizar.";

$lang['error_guild_insert'] = "Error creando la hermandad.";
$lang['error_uploadrule_insert'] = "Error creando las reglas de subida.";
$lang['error_guild_notexist'] = "La hermandad facilitada no existe en la Armería.";
$lang['error_char_insert'] = "Error creating character.";
$lang['error_char_notexist'] = "The character given does not exist in the Armory.";
$lang['error_missing_params'] = "Faltan parametros. Por favor vuelve a intentarlo";
$lang['error_wrong_region'] = "Region incorrecta. Solo EU y US son regiones validas.";
$lang['armorysync_guildadd'] = "Añadiendo Hermandad y sincronizando<br />listado de miembros con la Armería.";
$lang['armorysync_charadd'] = "Adding Charakter and synchronize<br />with the Armory.";
$lang['armorysync_add_help'] = "Información";
$lang['armorysync_add_helpText'] = "Escribe la hermandad y el servidor exactamente como se muestran en la Armería de Blizzard.<br />Region es EU pra los europeos y US para las hermandades americanas. En primer lugar<br />se comprobara la existencia de la hermandad. Acto seguido se empezara con la<br />sincronización del listado de miembros.";

$lang['guildleader'] = "Maestro de la hermandad";

$lang['rage'] = "Ira";
$lang['energy'] = "Energía";
$lang['focus'] = "Focus";

$lang['armorysync_credits'] = 'Gracias a <a target="_blank" href="http://www.papy-team.fr">tuigii</a>, <a target="_blank" href="http://xent.homeip.net">zanix</a>, <a target="_blank" href="http://www.wowroster.net/Your_Account/profile=1126.html">ds</a> y <a target="_blank" href="http://www.wowroster.net/Your_Account/profile=711.html">Subxero</a> por probarlo, traducirlo y ayudar.<br />Especial gracias a <a target="_blank" href="http://www.wowroster.net/Your_Account/profile=13101.html">kristoff22</a> por el codigo original del ArmorySync y a <a target="_blank" href="http://www.iceguild.org.uk/forum">Pugro</a> por sus cambios en el.';

$lang['start'] = "Empezar";
$lang['start_message'] = "Estas apunto de empezar a usar ArmorySync con %s %s.<br /><br />Con esto toda la información de %s sera sobre escrita<br />con los detalles de la Armería de Blizzard. Solo se podra deshacer<br />subiendo de nuevo un CharacterProfiler.lua nuevo.<br /><br />Estas seguro de querer empezar el proceso";

$lang['start_message_the_char'] = "el personaje";
$lang['start_message_this_char'] = "este personaje";
$lang['start_message_the_guild'] = "la hermandad";
$lang['start_message_this_guild'] = "todos los personajes de esta hermandad";
$lang['start_message_the_realm'] = "el reino";
$lang['start_message_this_realm'] = "todos los personajes de este reino";

$lang['month_to_en'] = array(
    "enero" => "January",
    "febrero" => "February",
    "marzo" => "March",
    "abril" => "April",
    "mayo" => "May",
    "junio" => "June",
    "julio" => "July",
    "agosto" => "August",
    "septiembre" => "September",
    "octubre" => "October",
    "noviembre" => "November",
    "diciembre" => "December"
);

$lang['roster_deprecated'] = "WoWRoster deprecated";
$lang['roster_deprecated_message'] = "<br />You are using <b>WoWRoster</b><br /><br />Version: <strong style=\"color:red;\" >%s</strong><br /><br />To use <b>ArmorySync</b> Version <strong style=\"color:yellow;\" >%s</strong><br />you will at least need <b>WoWRoster</b><br /><br />Version <strong style=\"color:green;\" >%s</strong><br /><br />Please update your <b>WoWRoster</b><br />&nbsp;";

$lang['armorysync_not_upgraded'] = "ArmorySync not upgraded";
$lang['armorysync_not_upgraded_message'] = "<br />You have installed <b>ArmorySync</b><br /><br />Version: <strong style=\"color:green;\" >%s</strong><br /><br />Right now there is <b>ArmorySync</b><br /><br />Version <strong style=\"color:red;\" >%s</strong><br /><br />registered with <b>WoWRoster</b>.<br /><br />Please go to <b>WoWRoster</b> configuration<br />and upgrade your <b>ArmorySync</b><br />&nbsp;";

$lang['cache_not_writable'] = "ArmorySync cache dir is not writeable";
$lang['cache_not_writable_message'] = "Your <b>ArmorySync</b> cache dir is not writeable.<br />Be sure to write enable it!";

$lang['max_execution_time_low'] = "max_execution_time is to low";
$lang['max_execution_time_low_message'] = "Your php max_execution_time of %s secs is to low.<br /><br />If you want to use Character update method with a fetch timout of %s secs and %s retrys<br />we will need at least a max_execution_time of %s secs.<br /><br />Please adjust max_execution_time or use smart update or per page update!";

$lang['gems'] = array(
	"inv_enchant_prismaticsphere" => "Esfera centelleante",
	"inv_enchant_voidsphere" => "Esfera de vacío",
	"inv_jewelcrafting_crimsonspinel_02" => array("Rosa de Kailee", "Sol carmesí", "Corazón de Don Julio", "Rubí de fuego", "Espinela carmesí",),
	"inv_jewelcrafting_dawnstone_03" => "Piedra del alba",
	"inv_jewelcrafting_empyreansapphire_02" => array("Estrella fugaz", "Zafiro empíreo",),
	"inv_jewelcrafting_lionseye_02" => array("Sangre de ámbar", "Bladestone", "Faceta de eternidad", "Piedra tajadera", "Ojo de león",),
	"inv_jewelcrafting_livingruby_03" => "Rubí vivo",
	"inv_jewelcrafting_nightseye_03" => array ("Ojo de noche", "Tanzanita", "Amatista",),
	"inv_jewelcrafting_nobletopaz_02" => "Topacio",
	"inv_jewelcrafting_nobletopaz_03" => array("Topacio noble", "Fire Opal",),
	"inv_jewelcrafting_pyrestone_02" => "Piropiedra",
	"inv_jewelcrafting_seasprayemerald_02" => "Esmeralda de espuma marina",
	"inv_jewelcrafting_shadowsongamethyst_01" => "Amatista",
	"inv_jewelcrafting_shadowsongamethyst_02" => "Amatista Cantosombrío",
	"inv_jewelcrafting_starofelune_03" => "Estrella de Elune",
	"inv_jewelcrafting_talasite_01" => "Talasita",
	"inv_jewelcrafting_talasite_03" => array("Talasita", "Crisoprasa"), //, "Spencerite"
	"inv_misc_gem_azuredraenite_02" => "Piedra lunar azur",
	"inv_misc_gem_bloodgem_02" => "Granate de sangre",
	"inv_misc_gem_bloodstone_01" => "Rubí rúnico ornamentado",
	"inv_misc_gem_bloodstone_02" => "Granate de sangre",
	"inv_misc_gem_crystal_03" => "Circón",
	"inv_misc_gem_deepperidot_01" => "Peridoto intenso",
	"inv_misc_gem_deepperidot_02" => "Peridoto intenso",
	"inv_misc_gem_deepperidot_03" => "Peridoto",
	"inv_misc_gem_diamond_05" => "Diamante de fuego celeste",
	"inv_misc_gem_diamond_06" => array("Diamante de tormenta de tierra", "Unstable Diamond",),
	"inv_misc_gem_diamond_07" => array("Diamante de fuego celeste", "Diamante fuego estelar", "Diamante quemaviento", "Unstable Diamond",),
	"inv_misc_gem_ebondraenite_02" => "Draenita de Sombras",
	"inv_misc_gem_flamespessarite_02" => "Espesartita de llamas",
	"inv_misc_gem_goldendraenite_02" => "Draenita dorada",
	"inv_misc_gem_opal_01" => array("Citrino", "Topacio ornamentado",),
	"inv_misc_gem_opal_02" => "Topacio ornamentado",
	"inv_misc_gem_pearl_07" => array("Charmed Amani Jewel", "Perla oscura",),
	"inv_misc_gem_pearl_08" => "Perla jaggal",
	"inv_misc_gem_ruby_01" => array("Corazón de don Amancio", "Corazón de don Rodrigo",),
	"inv_misc_gem_ruby_02" => "Rubí ornamentado llamativo",
	"inv_misc_gem_ruby_03" => "Turmalina",
	"inv_misc_gem_sapphire_02" => "Zafiro",
	"inv_misc_gem_topaz_01" => "Piedra del alba ornamentada lisa",
	"inv_misc_gem_topaz_02" => "Piedra del alba ornamentada reluciente",
	"inv_misc_gem_topaz_03" => "Ámbar",
);

$lang['faction_to_en'] = array(
	"Alliance" => "Alliance",
	"Horde" => "Horde",
);
