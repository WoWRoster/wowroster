<?php
/**
 * WoWRoster.net WoWRoster
 *
 * german localisaton
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: deDE.php 370 2008-02-17 15:42:02Z poetter $
 * @link       http://www.wowroster.net
 * @package    ArmorySync
*/

// -[ deDE Localization ]-

// Button names
$lang['async_button1']			= 'ArmorySync Charakter|Synchronisiere deinen Charaktere mit Blizzards Armory';
$lang['async_button2']			= 'ArmorySync Charakters|Synchronisiere die Charaktere deiner Gilde mit Blizzards Armory';
$lang['async_button3']			= 'ArmorySync Charakters|Synchronisiere die Charaktere deines Realms mit Blizzards Armory';
$lang['async_button4']			= 'ArmorySync Memberlist|Synchronisiere deine Mitgliederliste mit Blizzards Armory';
$lang['async_button5']			= 'ArmorySync Memberlist für eine neue Gilde|Füge eine neue Gilde ein und synchronisiere<br />die Mitgliederliste mit Blizzards Armory';
// Config strings
$lang['admin']['armorysync_conf']			= 'Allgemein|Konfiguriere die Grundeinstellungen für ArmorySync';
$lang['admin']['armorysync_ranks']			= 'Ränge|Konfiguriere die Gildenränge für ArmorySync';
$lang['admin']['armorysync_images']			= 'Bilder|Konfiguriere die Anzeige von Bildern für ArmorySync';
$lang['admin']['armorysync_access']			= 'Zugriffsrechte|Konfiguriere die Zugriffsrechte für ArmorySync';
$lang['admin']['armorysync_debug']			= 'Debugging|Konfiguriere das Debugging für ArmorySync';

$lang['admin']['armorysync_host']			= 'Host|Host mit dem synchronisiert werden soll.';
$lang['admin']['armorysync_minlevel']		= 'Minimum Level|Minimum Level der Charaktere die synchronisiert werden.';
$lang['admin']['armorysync_synchcutofftime']	= 'Sync cutoff time|Zeit in Tagen.<br />Alle Charaktere die nicht in den letzten (x) Tagen aktualisiert wurden werden synchronisiert.';
$lang['admin']['armorysync_use_ajax']	= 'Benutze AJAX|Benutze AJAX für Status Updates oder nicht.';
$lang['admin']['armorysync_reloadwaittime']	= 'Reload wait time|Zeit in Sekunden.<br />Zeit in Sekunden bevor die nächste Synchronisierung angestossen wird.';
$lang['admin']['armorysync_fetch_timeout'] = 'Armory Fetch timeout|Zeit in Sekunden bis das Herunterladen<br />einer einzelnen XML Datei abgebrochen wird.';
$lang['admin']['armorysync_fetch_retrys'] = 'Armory Fetch Retrys|How many retrys on failed fetched.';
$lang['admin']['armorysync_fetch_method'] = 'Armory Fetch method|Per char will do a reload per Character.<br />Per page will do a reload after every page that is fetched from the armory.';
$lang['admin']['armorysync_update_incomplete'] = 'Update incomplete data|This option determines if characters with incomplete data will be updated';
$lang['admin']['armorysync_skip_start'] = 'Überspringe die Startseite|Überspringe die Startseite bei <b>ArmorySync</b> updates.';
$lang['admin']['armorysync_status_hide'] = 'Verstecke das Statusfenster initial|Versteckt das Status Fenster von <b>ArmorySync</b> beim ersten Laden.';
$lang['admin']['armorysync_protectedtitle']	= "Geschützer Gildentitel|Charaktere mit diesen Gildentiteln sind davor geschützt,<br />durch einen Abgleich der Mitgliederliste gegen die Armory gelöscht zu werden.<br />Dieses Problem besteht häufig mit Bank Charakteren.<br />Mehrfachnennung durch trennen mit \",\" möglich. Z.B. Banker,Lager";

$lang['admin']['armorysync_rank_set_order']	= "Reihenfolge zum setzen der Gildenränge|Bestimmt in welcher Reihenfolge die Gildentitel gesetzt werden.";
$lang['admin']['armorysync_rank_0']	= "Titel des Gilden Meisters|Dieser Titel wird gesetzt wenn im Roster für die Gilde kein Titel existiert.";
$lang['admin']['armorysync_rank_1']	= "Titel Rang 1|Dieser Titel wird gesetzt wenn im Roster für die Gilde kein Titel existiert.";
$lang['admin']['armorysync_rank_2']	= "Titel Rang 2|Dieser Titel wird gesetzt wenn im Roster für die Gilde kein Titel existiert.";
$lang['admin']['armorysync_rank_3']	= "Titel Rang 3|Dieser Titel wird gesetzt wenn im Roster für die Gilde kein Titel existiert.";
$lang['admin']['armorysync_rank_4']	= "Titel Rang 4|Dieser Titel wird gesetzt wenn im Roster für die Gilde kein Titel existiert.";
$lang['admin']['armorysync_rank_5']	= "Titel Rang 5|Dieser Titel wird gesetzt wenn im Roster für die Gilde kein Titel existiert.";
$lang['admin']['armorysync_rank_6']	= "Titel Rang 6|Dieser Titel wird gesetzt wenn im Roster für die Gilde kein Titel existiert.";
$lang['admin']['armorysync_rank_7']	= "Titel Rang 7|Dieser Titel wird gesetzt wenn im Roster für die Gilde kein Titel existiert.";
$lang['admin']['armorysync_rank_8']	= "Titel Rang 8|Dieser Titel wird gesetzt wenn im Roster für die Gilde kein Titel existiert.";
$lang['admin']['armorysync_rank_9']	= "Titel Rang 9|Dieser Titel wird gesetzt wenn im Roster für die Gilde kein Titel existiert.";

$lang['admin']['armorysync_char_update_access'] = 'Char Update Access Level|Wer ist in der Lage Charakter zu aktualisieren';
$lang['admin']['armorysync_guild_update_access'] = 'Guild Update Access Level|Wer ist in der Lage Gilden zu aktualisieren';
$lang['admin']['armorysync_guild_memberlist_update_access'] = 'Guild Memberlist Update Access Level|Wer ist in der Lage Gilden Mitgliederlisten zu aktualisieren';
$lang['admin']['armorysync_realm_update_access'] = 'Realm Update Access Level|Wer ist in der Lage Realms zu aktualisieren';
$lang['admin']['armorysync_guild_add_access'] = 'Guild Add Access Level|Wer ist in der Lage neue Gilden einzufügen';

$lang['admin']['armorysync_logo'] = 'ArmorySync Logo|';
$lang['admin']['armorysync_pic1'] = 'ArmorySync Bild 1|';
$lang['admin']['armorysync_pic2'] = 'ArmorySync Bild 2|';
$lang['admin']['armorysync_pic3'] = 'ArmorySync Bild 3|';
$lang['admin']['armorysync_effects'] = 'ArmorySync Bild Effekte|';
$lang['admin']['armorysync_logo_show'] = 'Logo anzeigen|';
$lang['admin']['armorysync_pic1_show'] = $lang['admin']['armorysync_pic2_show'] = $lang['admin']['armorysync_pic3_show'] = 'Bild anzeigen|';
$lang['admin']['armorysync_pic_effects'] = 'Aktiviert|Benutze JavaScript Effekte für Bilder.';
$lang['admin']['armorysync_logo_pos_left'] = $lang['admin']['armorysync_pic1_pos_left'] = $lang['admin']['armorysync_pic2_pos_left'] = $lang['admin']['armorysync_pic3_pos_left'] = 'Bild Position horizontal|';
$lang['admin']['armorysync_logo_pos_top'] = $lang['admin']['armorysync_pic1_pos_top'] = $lang['admin']['armorysync_pic2_pos_top'] = $lang['admin']['armorysync_pic3_pos_top'] = 'Bild Position vertikal|';
$lang['admin']['armorysync_logo_size'] = $lang['admin']['armorysync_pic1_size'] = $lang['admin']['armorysync_pic2_size'] = $lang['admin']['armorysync_pic3_size'] = 'Bild Größe|';
$lang['admin']['armorysync_pic1_min_rows'] = $lang['admin']['armorysync_pic2_min_rows'] = $lang['admin']['armorysync_pic3_min_rows'] = 'Minimun Zeilen|Minimum Anzahl an Zeilen in der Statusanzeige<br />bei der das Bild angezeigt wird.';

$lang['admin']['armorysync_debuglevel']		= 'Debug Level|Hiermit stellst du den Debuglevel für ArmorySync ein.<br /><br />Quiete - Keine Meldungen<br />Base Infos - Grundlegende Meldungen<br />Armory & Job Method Infos - Alle Meldungen der Methoden der Armory und des Jobs<br />All Methods Info - Meldungen aller Methoden <b style="color:red;">(Vorsicht - sehr viele Infos)</b>';
$lang['admin']['armorysync_debugdata']		= 'Debug Data|Hiermit erhöhst die gewählte Debug Ausgabe um die Argumente und Rückgabewerte der Methoden<br /><b style="color:red;">(Vorsicht - sehr viele Infos bei hohem Debuglevel)</b>';
$lang['admin']['armorysync_javadebug']		= 'Debug Java|Hiermit stellst du ein dass JavaScript Debugmeldungen ausgibt.<br />Bis jetzt nicht implementiert.';
$lang['admin']['armorysync_xdebug_php']		= 'XDebug Session PHP|Hiermit wird bei Reloads im POST die XDEBUG Variable gesetzt.';
$lang['admin']['armorysync_xdebug_ajax']	= 'XDebug Session AJAX|Hiermit wird bei Reloads per AJAX im POST die XDEBUG Variable gesetzt.';
$lang['admin']['armorysync_xdebug_idekey']	= 'XDebug Session IDEKEY|Hiermit wird der IDEKEY für Xdebug Session festgelegt.';
$lang['admin']['armorysync_sqldebug']		= 'SQL Debug|Hiermit schaltest du das SQL Debugging für ArmorySync ein.';
$lang['admin']['armorysync_updateroster']	= "Update Roster|Das Roster aktualisieren oder nicht.<br />Sinnvoll fürs Debuggen.<br />Bis jetzt nicht implementiert.";


$lang['bindings']['bind_on_pickup'] = "Wird beim Aufheben gebunden";
$lang['bindings']['bind_on_equip'] = "Wird beim Anlegen gebunden";
$lang['bindings']['bind'] = "Seelengebunden";

// Addon strings
$lang['RepStanding']['Exalted'] = 'Ehrfürchtig';
$lang['RepStanding']['Revered'] = 'Respektvoll';
$lang['RepStanding']['Honored'] = 'Wohlwollend';
$lang['RepStanding']['Friendly'] = 'Freundlich';
$lang['RepStanding']['Neutral'] = 'Neutral';
$lang['RepStanding']['Unfriendly'] = 'Unfreundlich';
$lang['RepStanding']['Hostile'] = 'Verfeindet';
$lang['RepStanding']['Hated'] = 'Hasserfüllt';

$lang['Skills']['Class Skills'] = "Klassenfertigkeiten";
$lang['Skills']['Professions'] = "Berufe";
$lang['Skills']['Secondary Skills'] = "Sekundäre Fertigkeiten";
$lang['Skills']['Weapon Skills'] = "Waffenfertigkeiten";
$lang['Skills']['Armor Proficiencies'] = "Rüstungssachverstand";
$lang['Skills']['Languages'] = "Sprachen";


$lang['Classes']['Druid'] = 'Druide';
$lang['Classes']['Hunter'] = 'Jäger';
$lang['Classes']['Mage'] = 'Magier';
$lang['Classes']['Paladin'] = 'Paladin';
$lang['Classes']['Priest'] = 'Priester';
$lang['Classes']['Rogue'] = 'Schurke';
$lang['Classes']['Shaman'] = 'Schamane';
$lang['Classes']['Warlock'] = 'Hexenmeister';
$lang['Classes']['Warrior'] = 'Krieger';

$lang['Talenttrees']['Druid']['Balance'] = "Gleichgewicht";
$lang['Talenttrees']['Druid']['Feral Combat'] = "Wilder Kampf";
$lang['Talenttrees']['Druid']['Restoration'] = "Wiederherstellung";
$lang['Talenttrees']['Hunter']['Beast Mastery'] = "Tierherrschaft";
$lang['Talenttrees']['Hunter']['Marksmanship'] = "Treffsicherheit";
$lang['Talenttrees']['Hunter']['Survival'] = "Überleben";
$lang['Talenttrees']['Mage']['Arcane'] = "Arkan";
$lang['Talenttrees']['Mage']['Fire'] = "Feuer";
$lang['Talenttrees']['Mage']['Frost'] = "Frost";
$lang['Talenttrees']['Paladin']['Holy'] = "Heilig";
$lang['Talenttrees']['Paladin']['Protection'] = "Schutz";
$lang['Talenttrees']['Paladin']['Retribution'] = "Vergeltung";
$lang['Talenttrees']['Priest']['Discipline'] = "Disziplin";
$lang['Talenttrees']['Priest']['Holy'] = "Heilig";
$lang['Talenttrees']['Priest']['Shadow'] = "Schatten";
$lang['Talenttrees']['Rogue']['Assassination'] = "Meucheln";
$lang['Talenttrees']['Rogue']['Combat'] = "Kampf";
$lang['Talenttrees']['Rogue']['Subtlety'] = "Täuschung";
$lang['Talenttrees']['Shaman']['Elemental'] = "Elementar";
$lang['Talenttrees']['Shaman']['Enhancement'] = "Verstärkung";
$lang['Talenttrees']['Shaman']['Restoration'] = "Wiederherstellung";
$lang['Talenttrees']['Warlock']['Affliction'] = "Gebrechen";
$lang['Talenttrees']['Warlock']['Demonology'] = "Dämonologie";
$lang['Talenttrees']['Warlock']['Destruction'] = "Zerstörung";
$lang['Talenttrees']['Warrior']['Arms'] = "Waffen";
$lang['Talenttrees']['Warrior']['Fury'] = "Furor";
$lang['Talenttrees']['Warrior']['Protection'] = "Schutz";

$lang['misc']['Rank'] = "Rang";

$lang['guild_short'] = "Gilde";
$lang['character_short'] = "Char.";
$lang['skill_short'] = "Fertigk.";
$lang['reputation_short'] = "Ruf";
$lang['equipment_short'] = "Ausrü.";
$lang['talents_short'] = "Talente";

$lang['started'] = "begonnen";
$lang['finished'] = "beendet";

$lang['armorySyncTitle_Char'] = "ArmorySync für Charaktere";
$lang['armorySyncTitle_Guild'] = "ArmorySync für Gilden";
$lang['armorySyncTitle_Guildmembers'] = "ArmorySync für Mitgliederlisten";
$lang['armorySyncTitle_Realm'] = "ArmorySync für Realms";

$lang['next_to_update'] = "Nächstes Update: ";
$lang['nothing_to_do'] = "Im Moment ist nichts zu tun";

$lang['error'] = "Fehler";
$lang['error_no_character'] = "Es wurde kein Charakter übergeben.";
$lang['error_no_guild'] = "Es wurde keine Gilde übergeben.";
$lang['error_no_realm'] = "Es wurde kein Realm übergeben.";
$lang['error_use_menu'] = "Benutze das Menü zum synchronisieren.";

$lang['error_guild_insert'] = "Fehler beim Anlegen der Gilde.";
$lang['error_uploadrule_insert'] = "Fehler beim Anlegen der Upload Rule.";
$lang['error_guild_notexist'] = "Die angegebene Gilde existiert nicht in der Armory.";
$lang['error_char_insert'] = "Error creating character.";
$lang['error_char_notexist'] = "The character given does not exist in the Armory.";
$lang['error_missing_params'] = "Fehlende Angaben. Bitte versuch es erneut.";
$lang['error_wrong_region'] = "Ungültige Region. Nur EU und US sind gültige Regionen.";
$lang['armorysync_guildadd'] = "Gilde hinzufügen und Mitgliederliste<br />mit der Armory synchronisieren.";
$lang['armorysync_charadd'] = "Charakter hinzufügen und<br />mit der Armory synchronisieren.";
$lang['armorysync_add_help'] = "Hinweis";
$lang['armorysync_add_helpText'] = "Schreibe die Gilde / den Charakter und den Server exakt so wie sie, bzw. er,<br /> bei Blizzard geführt werden. Die Region ist EU für europäische Gilden<br />und US für amerikanische. Es wird zuerst überprüft ob der Charakter oder die Gilde<br />existiert. Anschließend wird eine Synchronisierung angestoßen.";

$lang['guildleader'] = "Gildenmeister";

$lang['rage'] = "Wut";
$lang['energy'] = "Energie";
$lang['focus'] = "Focus";

$lang['armorysync_credits'] = 'Danke an <a target="_blank" href="http://www.papy-team.fr">tuigii</a>, <a target="_blank" href="http://xent.homeip.net">zanix</a>, <a target="_blank" href="http://www.wowroster.net/Your_Account/profile=1126.html">ds</a> und <a target="_blank" href="http://www.wowroster.net/Your_Account/profile=711.html">Subxero</a> fürs Testen, Übersetzen und Unterstützen.<br />Besonderen Dank an <a target="_blank" href="http://www.wowroster.net/Your_Account/profile=13101.html">kristoff22</a> für den originalen Code von <b>ArmorySync</b> und <a target="_blank" href="http://www.iceguild.org.uk/forum">Pugro</a> für seine Änderungen daran.';

$lang['start'] = "Start";
$lang['start_message'] = "Du bist dabei <b>ArmorySync</b> für %s <b style=\"color:yellow\"; >%s</b> auszuführen.<br /><br />Hierdurch werden die Daten für %s mit den Angaben<br />aus Blizzards Armory überschrieben. Dieser Vorgang kann nur rückgängig<br />gemacht werden, in dem eine <b style=\"color:red\"; >aktuelle</b> CharacterProfiler.lua<br />hochgeladen wird.<br /><br />Willst du diesen Vorgang jetzt starten";

$lang['start_message_the_char'] = "den Charakter";
$lang['start_message_this_char'] = "diesen Charakter";
$lang['start_message_the_guild'] = "die Gilde";
$lang['start_message_this_guild'] = "alle Charaktere dieser Gilde";
$lang['start_message_the_realm'] = "den Realm";
$lang['start_message_this_realm'] = "alle Charaktere dieses Realms";

$lang['month_to_en'] = array(
    "Januar" => "January",
    "Februar" => "February",
    "März" => "March",
    "April" => "April",
    "Mai" => "May",
    "Juni" => "June",
    "Juli" => "July",
    "August" => "August",
    "September" => "September",
    "Oktober" => "October",
    "November" => "November",
    "Dezember" => "December"
);

$lang['roster_deprecated'] = "WoWRoster veraltet";
$lang['roster_deprecated_message'] = "<br />Du benutzt <b>WoWRoster</b><br /><br />Version: <strong style=\"color:red;\" >%s</strong><br /><br />Um <b>ArmorySync</b> Version <strong style=\"color:yellow;\" >%s</strong><br />benutzen zu können brauchst du mindestens <b>WoWRoster</b><br /><br />Version <strong style=\"color:green;\" >%s</strong><br /><br />Bitte aktualisiere dein <b>WoWRoster</b><br />&nbsp;";

$lang['armorysync_not_upgraded'] = "<b>ArmorySync</b> nicht aktualisiert";
$lang['armorysync_not_upgraded_message'] = "<br />Du hast <b>ArmorySync</b><br /><br />Version: <strong style=\"color:green;\" >%s</strong><br /><br />installiert. Zur Zeit ist noch <b>ArmorySync</b><br /><br />Version <strong style=\"color:red;\" >%s</strong><br /><br />im <b>WoWRoster</b> registriert.<br /><br />Bitte gehe in die <b>WoWRoster</b> Konfiguration<br />und aktualisiere dein <b>ArmorySync</b><br />&nbsp;";

$lang['cache_not_writable'] = "ArmorySync cache dir is not writeable";
$lang['cache_not_writable_message'] = "Your <b>ArmorySync</b> cache dir is not writeable.<br />Be sure to write enable it!";

$lang['max_execution_time_low'] = "max_execution_time is to low";
$lang['max_execution_time_low_message'] = "Your php max_execution_time of %s secs is to low.<br /><br />If you want to use Character update method with a fetch timout of %s secs and %s retrys<br />we will need at least a max_execution_time of %s secs.<br /><br />Please adjust max_execution_time or use smart update or per page update!";

$lang['gems'] = array(
	"inv_enchant_prismaticsphere" => "Prismasphäre",
	"inv_enchant_voidsphere" => "Sphäre der Leere",
	"inv_jewelcrafting_crimsonspinel_02" => array("Kailees Rose", "Blutrote Sonne", "Don Julios Herz", "Feuerrubin", "Purpurspinell",),
	"inv_jewelcrafting_dawnstone_03" => "Dämmerstein",
	"inv_jewelcrafting_empyreansapphire_02" => array("Funkelnde Sternschnuppe", "Engelssaphir",),
	"inv_jewelcrafting_lionseye_02" => array("Bernsteinblut", "Klingenstein", "Facette der Ewigkeit", "Stein der Klingen", "Löwenauge",),
	"inv_jewelcrafting_livingruby_03" => "Lebendiger Rubin",
	"inv_jewelcrafting_nightseye_03" => array ("Nachtauge", "Tansanit", "Amethyst",),
	"inv_jewelcrafting_nobletopaz_02" => "Topas",
	"inv_jewelcrafting_nobletopaz_03" => array("Edeltopas", "Feueropal",),
	"inv_jewelcrafting_pyrestone_02" => "Pyrostein",
	"inv_jewelcrafting_seasprayemerald_02" => "Gischtsmaragd",
	"inv_jewelcrafting_shadowsongamethyst_01" => "Amethyst",
	"inv_jewelcrafting_shadowsongamethyst_02" => "Schattensangamethyst",
	"inv_jewelcrafting_starofelune_03" => "Stern der Elune",
	"inv_jewelcrafting_talasite_01" => "Talasit",
	"inv_jewelcrafting_talasite_03" => array("Talasit", "Chrysopras"), //, "Strahlender Spencerit"
	"inv_misc_gem_azuredraenite_02" => "Azurmondstein",
	"inv_misc_gem_bloodgem_02" => "Blutgranat",
	"inv_misc_gem_bloodstone_01" => "Runenverzierter Schmuckrubin",
	"inv_misc_gem_bloodstone_02" => "Blutgranat",
	"inv_misc_gem_crystal_03" => "Zirkon",
	"inv_misc_gem_deepperidot_01" => "Tiefenperidot",
	"inv_misc_gem_deepperidot_02" => "Tiefenperidot",
	"inv_misc_gem_deepperidot_03" => "Peridot",
	"inv_misc_gem_diamond_05" => "Himmelsfeuerdiamant",
	"inv_misc_gem_diamond_06" => array("Erdsturmdiamant", "instabiler Diamant",),
	"inv_misc_gem_diamond_07" => array("Himmelsfeuerdiamant", "Sternenfeuerdiamant", "Windfeuerdiamant", "instabiler Diamant",),
	"inv_misc_gem_ebondraenite_02" => "Schattendraenit",
	"inv_misc_gem_flamespessarite_02" => "Flammenspessarit",
	"inv_misc_gem_goldendraenite_02" => "Golddraenit",
	"inv_misc_gem_opal_01" => array("Citrin", "Schmucktopas",),
	"inv_misc_gem_opal_02" => "Schmucktopas",
	"inv_misc_gem_pearl_07" => array("Bezaubertes Juwel der Amani", "Schattenperle",),
	"inv_misc_gem_pearl_08" => "Jaggalperle",
	"inv_misc_gem_ruby_01" => array("Don Amancios Herz", "Don Rodrigos Herz",),
	"inv_misc_gem_ruby_02" => "Klobiger Schmuckrubin",
	"inv_misc_gem_ruby_03" => "Turmalin",
	"inv_misc_gem_sapphire_02" => "Saphir",
	"inv_misc_gem_topaz_01" => "Glatter Schmuckdämmerstein",
	"inv_misc_gem_topaz_02" => "Schimmernder Schmuckdämmerstein",
	"inv_misc_gem_topaz_03" => "Bernstein",
);

$lang['faction_to_en'] = array(
	"Allianz" => "Alliance",
	"Horde" => "Horde",
);
