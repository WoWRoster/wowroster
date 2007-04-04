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

// deDE translation by sphinx



//Instructions how to upload, as seen on the mainpage
$lang['update_link']='Hier klicken um zur Aktualisierungsanleitung zu gelangen';
$lang['update_instructions']='Anleitung zur Aktualisierung';

$lang['lualocation']='W&auml;hle die Datei "CharacterProfiler.lua" aus';

$lang['filelocation']='finden unter<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$lang['noGuild']='Gilde nicht in der Datenbank gefunden. Bitte lade zun&auml;chst die Mitgliederliste hoch.';
$lang['nodata']="Konnte Gilde <b>'".$roster_conf['guild_name']."'</b> auf dem Server <b>'".$roster_conf['server_name']."'</b> nicht finden<br />Du musst erst einmal die <a href=\"".makelink('update')."\">Gildendaten hochladen</a> oder die <a href=\"".makelink('rostercp')."\">Konfiguration beenden</a><br /><br /><a href=\"http://www.wowroster.net/wiki/index.php/Roster:Install\" target=\"_blank\">Klicke hier um zur Installationsanleitung zu gelangen</a>";
$lang['nodata_title']='No Guild Data';

$lang['update_page']='Gildenmitglied aktualisieren';

$lang['guild_nameNotFound']='&quot;%s&quot; nicht gefunden. Stimmt er mit dem konfigurierten Namen &uuml;berein?';
$lang['guild_addonNotFound']='Keine Gilde gefunden. Ist das Addon GuildProfiler korrekt installiert?';

$lang['ignored']='Ignoriert';
$lang['update_disabled']='Update.php Zugriff deaktiviert.';

$lang['nofileUploaded']='UniUploader hat keine oder die falschen Dateien hochgeladen.';
$lang['roster_upd_pwLabel']='Roster Update Passwort';
$lang['roster_upd_pw_help']='(Wird nur ben&ouml;tigt, wenn man die Gilde aktualisiert)';


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
$lang['addon_no_config'] = '<b>The addon [%1$s] does not have a config</b>';

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
$lang['index_text_uniloader'] = '(Du kannst dieses Programm von der WoW-Roster-Webseite herunterladen, schaue nach dem "UniUploader Installer" f&uuml;r die aktuellste Version)';

$lang['update_instruct']='
<strong>Empfehlung zur automatischen Aktualisierung:</strong>
<ul>
<li>Benutze den <a href="'.$roster_conf['uploadapp'].'" target="_blank">UniUploader</a><br />
'.$lang['index_text_uniloader'].'</li>
</ul>
<strong>Anleitung:</strong>
<ol>
<li>Lade den <a href="'.$roster_conf['profiler'].'" target="_blank">Character Profiler</a> herunter</li>
<li>Extrahiere die Zip-Datei in ein eigenes Verzeichnis unter C:\Program Files\World of Warcraft\Interface\Addons\CharacterProfiler\</li>
<li>Starte WoW</li>
<li>&Ouml;ffne einmal dein Bankschliessfach, deine Rucks&auml;cke, deine Berufsseiten und deine Charakter-&Uuml;bersicht</li>
<li>Logge aus oder beende WoW (Siehe oben, falls das der UniUploader automatisch erledigen soll.)</li>
<li>Gehe zur <a href="'.makelink('update').'"> Update-Seite</a></li>
<li>'.$lang['lualocation'].'</li>
</ol>';

$lang['update_instructpvp']='
<strong>Optionale PvP Stats:</strong>
<ol>
<li>Lade <a href="'.$roster_conf['pvplogger'].'" target="_blank">PvPLog</a> herunter</li>
<li>Auch in ein eigenes Addon-Verzeichnis entpacken</li>
<li>Mache ein paar Duelle oder PvP-Kills</li>
<li>Lade "PvPLog.lua" &uuml;ber die Update-Seite hoch</li>
</ol>';

$lang['roster_credits']='Dank an <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, und <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> f&uuml;r den originalen Code der Seite. <br />
WoWRoster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="'.makelink('credits').'">Additional Credits</a>';


//Charset
$lang['charset']="charset=utf-8";

$lang['timeformat'] = '%d.%m. %k:%i'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$lang['phptimeformat'] = 'd.m. G:i';  // PHP date() Time format (example - 'M D jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


/**
 * Realmstatus Localizations
 */
$lang['rs'] = array(
	'ERROR' => 'Error',
	'NOSTATUS' => 'No Status',
	'UNKNOWN' => 'Unknown',
	'RPPVP' => 'RP-PvP',
	'PVE' => 'Normal',
	'PVP' => 'PvP',
	'RP' => 'RP',
	'OFFLINE' => 'Offline',
	'LOW' => 'Low',
	'MEDIUM' => 'Medium',
	'HIGH' => 'High',
	'MAX' => 'Max',
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
		'SG' => 'Schlüssel zur Sengenden Schlucht|4826',
			'Das Horn der Bestie|',
			'Besitznachweis|',
			'Endlich!|'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Werkstattschlüssel|2288'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'Der scharlachrote Schlüssel|4445'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Schlaghammer von Zul\\\'Farrak|5695',
			'Hochheiliger Schlaghammer|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Szepter von Celebras|19710',
			'Celebriangriff|19549',
			'Celebriandiamant|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Gefängniszellenschlüssel|15545'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Schlüssel zur Schattenschmiede|2966',
			'Ironfel|9673'
		),
	'DM' => array( 'Key-Only',
		'DM' => 'Mondsichelschlüssel|35607'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Skelettschlüssel|16854',
			'Scholomance|',
			'Skelettfragmente|',
			'Sold reimt sich auf...|',
			'Feuerfeder geschmiedet|',
			'Arajs Skarabäus',
			'Der Schlüssel zur Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Schlüssel zur Stadt|13146'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Siegel des Aufstiegs|17057',
			'Unverziertes Siegel des Aufstiegs|5370',
			'Edelstein der Felsspitzoger|5379',
			'Edelstein der Gluthauer|16095',
			'Edelstein der Blutäxte|21777',
			'Ungeschmiedetes Siegel des Aufstiegs|24554||MS',
			'Geschmiedetes Siegel des Aufstiegs|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Drachenfeueramulett|4829',
			'Drachkin-Bedrohung|',
			'Die wahren Meister|',
			'Marshal Windsor|',
			'Verlorene Hoffnung|',
			'Eine zusammengeknüllte Notiz|',
			'Ein Funken Hoffnung|',
			'Gefängnisausbruch!|',
			'Treffen in Stormwind|',
			'Die große Maskerade|',
			'Das Großdrachenauge|',
			'Drachenfeueramulett|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Ewige Quintessenz|22754'
		),
);


// HORDE KEYS
$lang['inst_keys']['H'] = array(
	'SG' => array( 'Key-Only',
		'SG' => 'Schlüssel zur Sengenden Schlucht|4826'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Werkstattschlüssel|2288'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'Der scharlachrote Schlüssel|4445'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Schlaghammer von Zul\\\'Farrak|5695',
			'Hochheiliger Schlaghammer|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Szepter von Celebras|19710',
			'Celebriangriff|19549',
			'Celebriandiamant|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Gefängniszellenschlüssel|15545'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Schlüssel zur Schattenschmiede|2966',
			'Ironfel|9673'
		),
	'DM' => array( 'Key-Only',
		'DM' => 'Mondsichelschlüssel|35607'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Skelettschlüssel|16854',
			'Scholomance|',
			'Skelettfragmente|',
			'Sold reimt sich auf...|',
			'Feuerfeder geschmiedet|',
			'Arajs Skarabäus',
			'Der Schlüssel zur Scholomance|'
		),
	'Strath' => array( 'Key-Only', 'Strath' =>
			'Schlüssel zur Stadt|13146'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Siegel des Aufstiegs|17057',
			'Unverziertes Siegel des Aufstiegs|5370',
			'Edelstein der Felsspitzoger|5379',
			'Edelstein der Gluthauer|16095',
			'Edelstein der Blutäxte|21777',
			'Ungeschmiedetes Siegel des Aufstiegs|24554||MS',
			'Geschmiedetes Siegel des Aufstiegs|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Drachenfeueramulett|4829',
			'Befehl des Kriegsherrn|',
			'Eitriggs Weisheit|',
			'Für die Horde!|',
			'Was der Wind erzählt|',
			'Der Champion der Horde|',
			'Nachricht von Rexxar|',
			'Oculus-Illusionen|',
			'Emberstrife|',
			'Die Prüfung der Schädel, Scryer|',
			'Die Prüfung der Schädel, Somnus|',
			'Die Prüfung der Schädel, Chronalis|',
			'Die Prüfung der Schädel, Axtroz|',
			'Aufstieg...|',
			'Blut des schwarzen Großdrachen-Helden|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Ewige Quintessenz|22754'
		),
);

//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$lang['upload']='Upload';
$lang['required']='Benötigt';
$lang['optional']='Optional';
$lang['attack']='Attacke';
$lang['defense']='Verteidigung';
$lang['class']='Klasse';
$lang['race']='Rasse';
$lang['level']='Level';
$lang['zone']='Letztes Gebiet';
$lang['note']='Notiz';
$lang['title']='Rang';
$lang['name']='Name';
$lang['health']='Gesundheit';
$lang['mana']='Mana';
$lang['gold']='Gold';
$lang['armor']='Rüstung';
$lang['lastonline']='Zuletzt Online';
$lang['online']='Online';
$lang['lastupdate']='Zuletzt aktualisiert';
$lang['currenthonor']='Aktueller Ehrenrang';
$lang['rank']='Rank';
$lang['sortby']='Sortieren nach %';
$lang['total']='Gesamt';
$lang['hearthed']='Ruhestein';
$lang['recipes']='Rezepte';
$lang['bags']='Taschen';
$lang['character']='Charakter';
$lang['bglog']='BG &Uuml;bersicht';
$lang['pvplog']='PvP &Uuml;bersicht';
$lang['duellog']='Duell &Uuml;bersicht';
$lang['duelsummary']='Duell Summary';
$lang['money']='Money';
$lang['bank']='Bank';
$lang['guildbank']='Gildenbank';
$lang['guildbank_totalmoney']='Gesamt Ersparnisse';
$lang['raid']='CT_Raid';
$lang['guildbankcontact']='Im Besitz von (Kontakt)';
$lang['guildbankitem']='Gegenstand und Beschreibung';
$lang['quests']='Quests';
$lang['roster']='Mitglieder';
$lang['alternate']='Alternative Ansicht';
$lang['byclass']='Nach Klasse';
$lang['menustats']='Grundwerte';
$lang['menuhonor']='Ehre';
$lang['keys']='Schl&uuml;ssel';
$lang['team']='Questgruppe Suchen';
$lang['search']='Suche';
$lang['update']='Letzte Aktualisierung';
$lang['credit']='Credits';
$lang['members']='Mitglieder';
$lang['items']='Gegenst&auml;nde';
$lang['find']='Suche nach';
$lang['upprofile']='Profil Updaten';
$lang['backlink']='Zur&uuml;ck zur &Uuml;bersicht';
$lang['gender']='Geschlecht';
$lang['unusedtrainingpoints']='Unbenutzte Trainingspunkte';
$lang['unusedtalentpoints']='Unbenutzte Talentpunkte';
$lang['talentexport']='Export Talent Build';
$lang['questlog']='Questlog';
$lang['recipelist']='Rezepte Liste';
$lang['reagents']='Reagenzien';
$lang['item']='Gegenstand';
$lang['type']='Typ';
$lang['date']='Datum';
$lang['complete'] = 'Complete';
$lang['failed'] = 'Failed';
$lang['completedsteps'] = 'Abgeschlossen';
$lang['currentstep'] = 'Aktuell';
$lang['uncompletedsteps'] = 'Nicht Abgeschlossen';
$lang['key'] = 'Schl&uuml;ssel';
$lang['timeplayed'] = 'Gespielte Zeit';
$lang['timelevelplayed'] = 'Auf diesem Level';
$lang['Addon'] = 'Addons';
$lang['advancedstats'] = 'Erweiterte Eigenschaften';
$lang['itembonuses'] = 'Boni f&uuml;r angelegte Gegenst&auml;nde';
$lang['itembonuses2'] = 'Gegenstand Boni';
$lang['crit'] = 'Krit.';
$lang['dodge'] = 'Ausweichen';
$lang['parry'] = 'Parieren';
$lang['block'] = 'Blocken';
$lang['realm'] = 'Realm';
$lang['talents'] = 'Talente';
$lang['online_at_up'] = 'Online at Update';
$lang['faction'] = 'Faction';

// Memberlog
$lang['memberlog'] = 'Mitglieder Log';
$lang['removed'] = 'Entfernt';
$lang['added'] = 'Zugefügt';
$lang['updated'] = 'Updated';
$lang['no_memberlog'] = 'Kein Mitglieder Log gespeichert';

$lang['rosterdiag'] = 'Roster Diagnose Seite';
$lang['Guild_Info'] = 'Gilden Info';
$lang['difficulty'] = 'Schwierigkeit';
$lang['recipe_4'] = 'optimal';
$lang['recipe_3'] = 'mittel';
$lang['recipe_2'] = 'leicht';
$lang['recipe_1'] = 'trivial';
$lang['roster_config'] = 'Roster Config';

// Character
$lang['char_level_race_class'] = 'Level %1$s %2$s %3$s';
$lang['char_guildline'] = '%1$s of %2$s';

// Spellbook
$lang['spellbook'] = 'Zauberspr&uuml;che';
$lang['page'] = 'Seite';
$lang['general'] = 'General';
$lang['prev'] = 'Zurück';
$lang['next'] = 'Vor';
$lang['no_spellbook'] = 'Keine Zaubersprüche für %1$s';

// Mailbox
$lang['mailbox'] = 'Postfach';
$lang['maildateutc'] = 'Briefdatum';
$lang['mail_item'] = 'Gegenstand';
$lang['mail_sender'] = 'Absender';
$lang['mail_subject'] = 'Betreff';
$lang['mail_expires'] = 'Gültig bis';
$lang['mail_money'] = 'Geldanhang';
$lang['no_mail'] = 'Keine Briefe für %1$s';
$lang['no_info'] = 'Keine Information';


//this needs to be exact as it is the wording in the db
$lang['professions']='Berufe';
$lang['secondary']='Sekundäre Fertigkeiten';
$lang['Blacksmithing']='Schmiedekunst';
$lang['Mining']='Bergbau';
$lang['Herbalism']='Kräuterkunde';
$lang['Alchemy']='Alchimie';
$lang['Leatherworking']='Lederverarbeitung';
$lang['Jewelcrafting']='Juwelenschleifen';
$lang['Skinning']='Kürschnerei';
$lang['Tailoring']='Schneiderei';
$lang['Enchanting']='Verzauberkunst';
$lang['Engineering']='Ingenieurskunst';
$lang['Cooking']='Kochkunst';
$lang['Fishing']='Angeln';
$lang['First Aid']='Erste Hilfe';
$lang['Poisons']='Gifte';
$lang['backpack']='Rucksack';
$lang['PvPRankNone']='none';

// Uses preg_match() to find required level in recipe tooltip
$lang['requires_level'] = '/Benötigte Stufe ([\d]+)/';

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
$lang['riding'] = 'Reiten';
$lang['ts_ridingIcon'] = array(
	'Nachtelf'=>'ability_mount_whitetiger',
	'Mensch'=>'ability_mount_ridinghorse',
	'Zwerg'=>'ability_mount_mountainram',
	'Gnom'=>'ability_mount_mechastrider',
	'Untoter'=>'ability_mount_undeadhorse',
	'Troll'=>'ability_mount_raptor',
	'Tauren'=>'ability_mount_kodo_03',
	'Orc'=>'ability_mount_blackdirewolf',
	'Blutelf' => 'ability_mount_cockatricemount',
	'Draenei' => 'ability_mount_ridingelekk',
	'Paladin'=>'ability_mount_dreadsteed',
	'Hexenmeister'=>'ability_mount_nightmarehorse'
);

// Class Icons-Array
$lang['class_iconArray'] = array (
	'Druide'=>'ability_druid_maul',
	'J�ger'=>'inv_weapon_bow_08',
	'Magier'=>'inv_staff_13',
	'Paladin'=>'spell_fire_flametounge',
	'Priester'=>'spell_holy_layonhands',
	'Schurke'=>'inv_throwingknife_04',
	'Schamane'=>'spell_nature_bloodlust',
	'Hexenmeister'=>'spell_shadow_cripple',
	'Krieger'=>'inv_sword_25',
);

//skills
$lang['skilltypes'] = array(
	1 => 'Klassenfertigkeiten',
	2 => 'Berufe',
	3 => 'Sekundäre Fertigkeiten',
	4 => 'Waffenfertigkeiten',
	5 => 'Rüstungssachverstand',
	6 => 'Sprachen',
);

//tabs
$lang['tab1']='Stats';
$lang['tab2']='Tier';
$lang['tab3']='Ruf';
$lang['tab4']='Fertigk.';
$lang['tab5']='PvP';

$lang['strength']='Stärke';
$lang['strength_tooltip']='Erhöht deine Angriffskraft mit Nahkampfwaffen.<br />Erhöht die Menge an Schaden, die mit einem Schild geblockt werden kann.';
$lang['agility']='Beweglichkeit';
$lang['agility_tooltip']= 'Erhöht deine Angriffskraft mit Fernkampfwaffen.<br />Verbessert deine Chance auf einen kritischen Treffer mit allen Waffen.<br />Erhöht deine Rüstung und deine Chance Angriffen auszuweichen.';
$lang['stamina']='Ausdauer';
$lang['stamina_tooltip']= 'Erhöht deine Lebenspunkte.';
$lang['intellect']='Intelligenz';
$lang['intellect_tooltip']= 'Erhöht deine Manapunkte und die die Chance auf einen kritischen Treffer mit Sprüchen.<br />Erhöht die Rate mit denen du deine Waffenfertigkeiten verbesserst.';
$lang['spirit']='Willenskraft';
$lang['spirit_tooltip']= 'Erhöht deine Mana- und Lebens- regenerationsrate.';
$lang['armor_tooltip']= 'Verringert die Menge an Schaden die du von physischen Angriffen erleidest.<br />Die Höhe der Reduzierung ist abhängig vom Level deines Angreifers.';

$lang['mainhand']='Waffenhand';
$lang['offhand']='Off Hand';
$lang['ranged']='Distanzangriff';
$lang['melee']='Nahkampf';
$lang['spell']='Zauber';

$lang['weapon_skill']='Waffe';
$lang['weapon_skill_tooltip']='Waffe %d<br />Waffenfertigkeitswertung %d';
$lang['damage']='Schaden';
$lang['damage_tooltip']='<table><tr><td>Angriffstempo (Sekunden):<td>%.2f<tr><td>Schaden:<td>%d-%d<tr><td>Schaden pro Sekunde:<td>%.1f</table>';
$lang['speed']='Tempo';
$lang['atk_speed']='Angriffstempo';
$lang['haste_tooltip']='Tempowertung ';

$lang['melee_att_power']='Nahkampfangriffskraft';
$lang['melee_att_power_tooltip']='Erhöht die Angriffskraft mit Nahkampfwaffen um %.1f pro Sekunde.';
$lang['ranged_att_power']='Distanzangriffskraft';
$lang['ranged_att_power_tooltip']='Erhöht die Angriffskraft mit Distanzwaffen um %.1f pro Sekunde.';

$lang['weapon_hit_rating']='Trefferwert.';
$lang['weapon_hit_rating_tooltip']='Erhöht die Trefferchance im Nahkampf gegen ein Ziel.';
$lang['weapon_crit_rating']='Kritisch';
$lang['weapon_crit_rating_tooltip']='Kritische Trefferchance %.2f%%.';

$lang['damage']='Schaden';
$lang['energy']='Energie';
$lang['rage']='Wut';
$lang['power']='Kraft';

$lang['melee_rating']='Nahkampf Angriffsrate';
$lang['melee_rating_tooltip']='Deine Angriffsrate beinflusst deine Chance ein Ziel zu treffen und basiert auf deiner Waffenfähigkeit der Waffe die du grade trägst.';
$lang['range_rating']='Fernkampf Angriffsrate';
$lang['range_rating_tooltip']='Deine Angriffsrate beinflusst deine Chance ein Ziel zu treffen und basiert auf deiner Waffenfähigkeit der Waffe die du grade trägst.';

$lang['spell_damage']='Schadensboni';
$lang['fire']='Feuer';
$lang['nature']='Natur';
$lang['frost']='Frost';
$lang['shadow']='Schatten';
$lang['arcane']='Arkan';

$lang['spell_healing']='Heilungsboni';
$lang['spell_healing_tooltip']='Erhöht die Heilung um bis zu %d';
$lang['spell_hit_rating']='Trefferwertung';
$lang['spell_hit_rating_tooltip']='Erhöht die Trefferchance mit Zaubern gegen ein Ziel.';
$lang['spell_crit_rating']='Kritisch';
$lang['spell_crit_chance']='Kritische Chance';
$lang['spell_penetration']='Durchschlag';
$lang['spell_penetration_tooltip']='Verringert den Widerstand des Ziels gegen Eure Zauber.';
$lang['mana_regen']='Regeneration';
$lang['mana_regen_tooltip']='Regeneriert alle %d Sekunden %d Mana, wenn kein Zauber gewirkt wird.';

$lang['defense_rating']='Verteidigungswertung ';
$lang['def_tooltip']='Erhöht die Chance auf %s';
$lang['resilience']='Abhärtung';

$lang['res_arcane']='Arkan Widerstand';
$lang['res_arcane_tooltip']='Increases your ability to resist Arcane Resistance-based attacks, spells, and abilities.';
$lang['res_fire']='Feuer Widerstand';
$lang['res_fire_tooltip']='Increases your ability to resist Fire Resistance-based attacks, spells, and abilities.';
$lang['res_nature']='Natur Widerstand';
$lang['res_nature_tooltip']='Increases your ability to resist Nature Resistance-based attacks, spells, and abilities.';
$lang['res_frost']='Frost Widerstand';
$lang['res_frost_tooltip']='Increases your ability to resist Frost Resistance-based attacks, spells, and abilities.';
$lang['res_shadow']='Schatten Widerstand';
$lang['res_shadow_tooltip']='Increases your ability to resist Shadow Resistance-based attacks, spells, and abilities.';

$lang['empty_equip']='Kein Gegenstand angelegt';
$lang['pointsspent']='Punkte verteilt in';
$lang['none']='Keine';

$lang['pvplist']=' PvP Statistiken';
$lang['pvplist1']='Gilde, die am meisten unter uns zu leiden hat';
$lang['pvplist2']='Gilde, die uns am meisten zu Schaffen macht';
$lang['pvplist3']='Spieler, der am meisten unter uns zu leiden hat';
$lang['pvplist4']='Spieler, der uns am meisten zu Schaffen macht';
$lang['pvplist5']='Mitglied mit den meisten Kills';
$lang['pvplist6']='Mitglied, der am h&auml;ufigsten gestorben ist';
$lang['pvplist7']='Besten Kills-Level-Durchschnitt';
$lang['pvplist8']='Besten Tod-Level-Durchschnitt';

$lang['hslist']=' Ehren Statistiken';
$lang['hslist1']='H&ouml;chsten Lebenszeit Rang';
$lang['hslist2']='H&ouml;chsten Lebenszeit ES';
$lang['hslist3']='Die meisten Ehrenpunkte';
$lang['hslist4']='Die meisten Arenapunkte';

$lang['Druid']='Druide';
$lang['Hunter']='Jäger';
$lang['Mage']='Magier';
$lang['Paladin']='Paladin';
$lang['Priest']='Priester';
$lang['Rogue']='Schurke';
$lang['Shaman']='Schamane';
$lang['Warlock']='Hexenmeister';
$lang['Warrior']='Krieger';

$lang['today']='Heute';
$lang['todayhk']='Heute HK';
$lang['todaycp']='Heute CP';
$lang['yesterday']='Gestern';
$lang['yesthk']='Gestern HK';
$lang['yestcp']='Gestern CP';
$lang['thisweek']='Diese Woche';
$lang['lastweek']='Letzte Woche';
$lang['lifetime']='Gesamte Spielzeit';
$lang['lifehk']='Gesamte HK';
$lang['honorkills']='Ehrenhafte Siege';
$lang['dishonorkills']='Ruchlose Morde';
$lang['honor']='Ehre';
$lang['standing']='Platzierung';
$lang['highestrank']='Höchster Rank';
$lang['arena']='Arena';

$lang['totalwins']='Gewinne total';
$lang['totallosses']='Verluste total';
$lang['totaloverall']='Gesamt';
$lang['win_average']='Durchschnittliche Level Differenz (Gewinne)';
$lang['loss_average']='Durchschnittliche Level Differenz  (Verluste)';

// These need to be EXACTLY what PvPLog stores them as
$lang['alterac_valley']='Alteractal';
$lang['arathi_basin']='Arathibecken';
$lang['warsong_gulch']='Warsongschlucht';

$lang['world_pvp']='Welt-PvP';
$lang['versus_guilds']='Gegengilden';
$lang['versus_players']='Gegenspieler';
$lang['bestsub']='Beste Subzone';
$lang['worstsub']='Schlechteste Subzone';
$lang['killedmost']='Am meisten get&ouml;tet';
$lang['killedmostby']='Am meisten get&ouml;tet durch';
$lang['gkilledmost']='Am meisten get&ouml;tete Spieler der Gilde';
$lang['gkilledmostby']='Am meister get&ouml;tet durch Spieler der Gilde';

$lang['wins']='Gewinne';
$lang['losses']='Verluste';
$lang['overall']='Gesamt';
$lang['best_zone']='Beste Zone';
$lang['worst_zone']='Schlechteste Zone';
$lang['most_killed']='Meisten get&ouml;tet';
$lang['most_killed_by']='Meisten get&ouml;tet durch';

$lang['when']='Wann';
$lang['guild']='Gilde';
$lang['leveldiff']='LevelDiff';
$lang['result']='Ergebnis';
$lang['zone2']='Zone';
$lang['subzone']='Subzone';
$lang['bg']='Schlachtfeld';
$lang['yes']='Ja';
$lang['no']='Nein';
$lang['win']='Sieg';
$lang['loss']='Niederlage';
$lang['kills']='Kills';
$lang['unknown']='Unknown';

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
$lang['exalted']='Ehrfürchtig';
$lang['revered']='Respektvoll';
$lang['honored']='Wohlwollend';
$lang['friendly']='Freundlich';
$lang['neutral']='Neutral';
$lang['unfriendly']='Unfreundlich';
$lang['hostile']='Feindselig';
$lang['hated']='Hasserfüllt';
$lang['atwar']='Im Krieg';
$lang['notatwar']='Nicht im Krieg';

// language definitions for the rogue instance keys 'fix'
$lang['thievestools']='Diebeswerkzeug';
$lang['lockpicking']='Schlossknacken';
// END

	// Quests page external links (on character quests page)
		// $lang['questlinks'][#]['name']  This is the name displayed on the quests page
		// $lang['questlinks'][#]['url#']  This is the URL used for the quest lookup

		$lang['questlinks'][0]['name']='WoW-Handwerk';
		$lang['questlinks'][0]['url1']='http://www.wow-handwerk.de/search.php?quicksearch=';
		//$lang['questlinks'][0]['url2']='';
		//$lang['questlinks'][0]['url3']='&amp;maxl='';

		$lang['questlinks'][1]['name']='Buffed DE';
		$lang['questlinks'][1]['url1']='http://www.buffed.de/?f=';
		//$lang['questlinks'][1]['url2']='';
		//$lang['questlinks'][1]['url3']='';

		$lang['questlinks'][2]['name']='Thottbot';
		$lang['questlinks'][2]['url1']='http://www.thottbot.com/?f=q&amp;title=';
		$lang['questlinks'][2]['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$lang['questlinks'][2]['url3']='&amp;maxl=';

		//$lang['questlinks'][3]['name']='WoWHead';
		//$lang['questlinks'][3]['url1']='http://www.wowhead.com/?quests&amp;filter=ti=';
		//$lang['questlinks'][3]['url2']=';minle=';
		//$lang['questlinks'][3]['url3']=';maxle=';

// Items external link
// Add as many item links as you need
// Just make sure their names are unique
	$lang['itemlink'] = 'Item Links';
	$lang['itemlinks']['WoW-Handwerk'] = 'http://www.wow-handwerk.de/search.php?quicksearch=';
	$lang['itemlinks']['buffed.de'] = 'http://www.buffed.de/?f=';
	$lang['itemlinks']['Thottbot'] = 'http://www.thottbot.com/index.cgi?i=';
	//$lang['itemlinks']['WoWHead'] = 'http://www.wowhead.com/?items&amp;filter=na=';


// definitions for the questsearchpage
	$lang['search1']="W&auml;hle eine Zone oder eine Quest um zu schauen, wer sie alles hat.<br />\n<small>Beachte: Stimmen die Questlevel bei verschiedenen Gildenleuten nicht &uuml;berein, handelt es sich um verschiedene Teile einer Questreihe.</small>";
	$lang['search2']='Suche nach Zone';
	$lang['search3']='Suche nach Questname';

// Definitions for item tooltip coloring
	$lang['tooltip_use']='Benutzen';
	$lang['tooltip_requires']='Benötigt';
	$lang['tooltip_reinforced']='Verstärkte';
	$lang['tooltip_soulbound']='Seelengebunden';
	$lang['tooltip_boe']='Wird beim Anlegen gebunden';
	$lang['tooltip_equip']='Anlegen';
	$lang['tooltip_equip_restores']='Anlegen: Stellt';
	$lang['tooltip_equip_when']='Anlegen: Erhöht';
	$lang['tooltip_chance']='Gewährt';
	$lang['tooltip_enchant']='Enchant';
	$lang['tooltip_set']='Set';
	$lang['tooltip_rank']='Rang';
	$lang['tooltip_next_rank']='Nächster Rang';
	$lang['tooltip_spell_damage']='Schaden';
	$lang['tooltip_school_damage']='\\+.*Schaden';
	$lang['tooltip_healing_power']='Heilung';
	$lang['tooltip_chance_hit']='Trefferchance';
	$lang['tooltip_reinforced_armor']='Verstärkte Rüstung';
	$lang['tooltip_damage_reduction']='Schadensreduzierung';

// Warlock pet names for icon displaying
	$lang['Imp']='Wichtel';
	$lang['Voidwalker']='Leerwandler';
	$lang['Succubus']='Sukkubus';
	$lang['Felhunter']='Teufelsjäger';
	$lang['Infernal']='Infernal';
	$lang['Felguard']='Teufelswache';

// Max experiance for exp bar on char page
	$lang['max_exp']='Max XP';

// Error messages
	$lang['CPver_err']="Die verwendete Version des CharacterProfiler, zur Speicherung der Daten für diesen Charakter ist &auml;lter als die für den Upload minimal zugelassene Version.<br/> \nBitte stellen Sie sicher, daß Sie mindestens v".$roster_conf['minCPver']." verwenden, und daß Sie diese Version verwendet haben, um die Daten für diesen Charakter zu speichern.";
	$lang['PvPLogver_err']="Die verwendete Version von PvPLog, zur Speicherung der Daten für diesen Charakter ist &auml;lter als die für den Upload minimale zugelassene Version.<br/> \nBitte stellen Sie sicher, daß Sie mindestens v".$roster_conf['minPvPLogver']." verwenden. Falls Sie gerade Ihr PvPLog aktualisiert haben, stellen Sie sicher daß Sie Ihre alte PvPLog.lua Datei gel&ouml;scht haben, bevor Sie aktualisieren.";
	$lang['GPver_err']="Die verwendete Version von GuildProfiler, zur Speicherung der Daten für diese Gilde ist &auml;lter als die für den Upload minimal zugelassene Version.<br/> \nBitte stellen Sie sicher, daß Sie mindestens v".$roster_conf['minGPver']." verwenden.";

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


/******************************
 * Roster Admin Strings
 ******************************/

$lang['pagebar_function'] = 'Aufgaben';
$lang['pagebar_rosterconf'] = 'Konfiguriere Roster';
$lang['pagebar_charpref'] = 'Character Preferenzen';
$lang['pagebar_changepass'] = 'Passwort Änderung';
$lang['pagebar_addoninst'] = 'Verwalte Addons';
$lang['pagebar_update'] = 'Upload Profile';
$lang['pagebar_rosterdiag'] = 'Roster Diagnose Seite';
$lang['pagebar_menuconf'] = 'Menu Konfiguration';

$lang['pagebar_addonconf'] = 'Addon Konfiguration';

$lang['roster_config_menu'] = 'Config Menu';

// Submit/Reset confirm questions
$lang['config_submit_button'] = 'Speichere Einstellungen';
$lang['config_reset_button'] = 'Zurücksetzen';
$lang['confirm_config_submit'] = 'Dies wird die Änderungen in die Datenbank schreiben. Sind sie sicher?';
$lang['confirm_config_reset'] = 'Dies wird das Formular in den Zustand zurücksetzen in dem es am Anfang war. Sind sie sicher?';

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
$lang['admin']['main_conf'] = 'Haupteinstellungen|Roster\'s wichtigste Einstellungen<br>Enthält Roster URL, Bilder URL und andere grundlegende Einstellungen...';
$lang['admin']['guild_conf'] = 'Gildenkonfiguration|Gib deine Gildeninfos ein<ul><li>Gildenname</li><li>Realmname (Server)</li><li>Eine kurze Beschreibung</li><li>Servertyp</li><li>etc...</li></ul>';
$lang['admin']['index_conf'] = 'Indexseite|Einstellen was auf der Hauptseite angezeigt werden soll';
$lang['admin']['menu_conf'] = 'Menüeinstellungen|Einstellen welche Elemente im Menü gezeigt werden sollen';
$lang['admin']['display_conf'] = 'Anzeigeneinstellungen|Verschiedene Anzeigeeinstellungen<br>css, javascript, motd, etc...';
$lang['admin']['char_conf'] = 'Charakterseite|Einstellen was auf den Charakterseite angezeigt werden soll';
$lang['admin']['realmstatus_conf'] = 'Serverstatus|Optionen für die Serverstatus<br><br>Um es auszustellen, bitte bei Menüeinstellungen gucken';
$lang['admin']['guildbank_conf'] = 'Gildenbank|Konfiguriere deine Gildenbank';
$lang['admin']['data_links'] = 'Item/Quest Data Links|Externe Links für Gegenstände und Quests';
$lang['admin']['update_access'] = 'Update Zugriff|Optionale phpBB Authorisierung für update';

$lang['admin']['documentation'] = 'Dokumentation|WoWRoster Dokumentation über das wowroster.net-Wiki';

// main_conf
$lang['admin']['roster_dbver'] = "Roster Datenbank Version|Die Version der Datenbank";
$lang['admin']['version'] = "Roster Version|Aktuelle Version des Rosters";
$lang['admin']['sqldebug'] = "SQL Debug Output|Gib MySQL Debug Ausgaben in HTML Kommentaren";
$lang['admin']['debug_mode'] = "Debug Modus|Zeigt die komplette Debugprotokollierung im Fehlerfalle an";
$lang['admin']['sql_window'] = "SQL Fenster|Zeigt die SQL Fehler in einem Fenster in der Fu&szlig;zeile an";
$lang['admin']['minCPver'] = "Min CP Version|Minimum CharacterProfiler Version zum Upload";
$lang['admin']['minGPver'] = "Min GP version|Minimum GuildProfiler Version zum Upload";
$lang['admin']['minPvPLogver'] = "Min PvPLog version|Minimum PvPLog Version zum Upload";
$lang['admin']['roster_lang'] = "Roster Hauptsprache|Sprache, in der das Roster anzeigen soll";
$lang['admin']['default_page'] = "Default Page|Page to display if no page is specified in the url";
$lang['admin']['website_address'] = "Webseitenadresse|Wird benötigt für das Logo, den Gildennamenlink und das Hauptmenü<br />Einige Roster Addons benötigen diese auch";
$lang['admin']['interface_url'] = "Interface Directory URL|Verzeichnis zu den Interface Images<br />Das Standartverzeichnis ist &quot;img/&quot;<br /><br />Du kannst auch eine andere URL verwenden.";
$lang['admin']['img_suffix'] = "Bilder Dateierweiterung|Der Dateityp deiner Interface Images";
$lang['admin']['alt_img_suffix'] = "Alternative Bilder Dateierweiterung|Der Dateityp deiner Interface Images";
$lang['admin']['img_url'] = "Roster Bilder Verzeichnis URL|Verzeichnis zu den Roster's Images<br />Das Standartverzeichnis ist &quot;img/&quot;<br /><br />Du kannst auch eine andere URL verwenden.";
$lang['admin']['timezone'] = "Zeitzone|Wird hinter der Zeit angezeigt, damit man weis in welcher Zeitzone sich der Zeithinweis befindet";
$lang['admin']['localtimeoffset'] = "Zeitzonenabstand|Der Zeitzonenabstand zur UTC/GMT<br />Die Zeiten im Roster werden durch diesen Abstand zur UTC/GMT berechnet.";
$lang['admin']['pvp_log_allow'] = "Erlaube Upload von PvPLog-Daten|Wenn man diesen Wert auf &quot;no&quot; stellt, wird das PVPLog Uploadfeld in der Datei &quot;update&quot; ausgeblendet.";
$lang['admin']['use_update_triggers'] = "Addon Update Triggers|Addon Update Triggers werden für einige AddOns während eines Character oder Gildenupdates benötigt.<br />Einige Addons benötigen wahrscheinlich, dass diese Funktion für sie angestellt ist.";

// guild_conf
$lang['admin']['guild_name'] = "Gildenname|Dieser muß exakt so wie im Spiel geschrieben sein,<br />oder du <u>WIRST</u> <u>NICHT</u> in der Lage sein, Daten hochzuladen";
$lang['admin']['server_name'] = "Servername|Dieser muß exakt so wie im Spiel geschrieben sein,<br />oder du <u>WIRST</u> <u>NICHT</u> in der Lage sein, Daten hochzuladen";
$lang['admin']['guild_desc'] = "Gildenbeschreibung|Gib eine kurze Beschreibung der Gilde ein";
$lang['admin']['server_type'] = "Servertyp|Gib an, um welche Art von Server es sich handelt";
$lang['admin']['alt_type'] = "2.-Char Suche (Twinks)|Text, der zur Anzeige der Anzahl der 2.-Charaktere auf der Hautpseite benutzt wird";
$lang['admin']['alt_location'] = "Twink Suchfeld|In welchem Feld soll der Twink-Text gesucht werden";

// index_conf
$lang['admin']['index_pvplist'] = "PvP-Logger Statistiken|PvP-Logger Statistiken auf der Index-Seite<br />Wenn due PvPLog-Upload deaktiviert hast, brauchst du das nicht aktivieren";
$lang['admin']['index_hslist'] = "Ehrensystem Statistiken|Ehrensystem Statistiken auf der Index-Seite";
$lang['admin']['hspvp_list_disp'] = "PvP/Ehren-Listen Anzeige|Wie sollen die PvP- und Ehren Listen initial angezeigt werden:<br />Die Listen können auf- und zugeklappt werden, indem man auf den Kopf klickt<br /><br />&quot;show&quot; zeigt die Listen aufgeklappt beim Seitenaufruf<br />&quot;hide&quot; zeigt die Listen zugeklappt";
$lang['admin']['index_member_tooltip'] = "Mitglied Info Tooltip|Zeigt einige Infos über das Mitglied im Tooltip an";
$lang['admin']['index_update_inst'] = "Aktualisierungsanleitung|Zeigt die Anleitung zum Aktualisieren auf der Indexseite";
$lang['admin']['index_sort'] = "Mitgliedsliste Sortierung|Stellt die Standardsortierung ein";
$lang['admin']['index_motd'] = "Gilden MOTD|Zeige Gilden MOTD auf der Indexseite<br /><br />Regelt auch die Anzeige auf der &quot;Gilden Info&quot; Seite";
$lang['admin']['index_level_bar'] = "Level Balken|Zeigt einen prozentualen Levelbalken auf der Indexseite";
$lang['admin']['index_iconsize'] = "Icon Größe|Wähle die Größe der Icons auf der Indexseite (PvP, Berufe, Klassen, etc..)";
$lang['admin']['index_tradeskill_icon'] = "Beruf Icons|Ermöglich die Anzeige von Berufsicons auf der Indexseite";
$lang['admin']['index_tradeskill_loc'] = "Beruf Spalte Anzeige|In welcher Spalte sollen die Berufsicons angezeigt werden";
$lang['admin']['index_class_color'] = "Klassenfarben|Färbt die Klassennamen ein";
$lang['admin']['index_classicon'] = "Klassen Icons|Zeigt ein Icon für jeden Charakter jeder Klasse an";
$lang['admin']['index_honoricon'] = "PvP Ehrenrang Icons|Zeigt ein Icon des Ehrenrangs neben dem Namen an";
$lang['admin']['index_prof'] = "Berufs Spalte|Dies ist eine eigene Spalte für die Berufsicons<br />Wenn du sie in einer anderen Spalte anzeigst, kannst du diese deaktivieren.";
$lang['admin']['index_currenthonor'] = "Ehren Spalte|Zeigt eine Spalte mit dem aktuellen Ehrenrang an";
$lang['admin']['index_note'] = "Notiz Spalte|Zeigt eine Spalte mit der Spielernotiz an";
$lang['admin']['index_title'] = "Gildentitel Spalte|Zeigt eine Spalte mit dem Gildentitel an";
$lang['admin']['index_hearthed'] = "Ruhestein Ort Spalte|Zeigt eine Spalte mit dem Ort des Ruhesteins an";
$lang['admin']['index_zone'] = "Letztes Gebiet Spalte|Zeigt eine Spalte mit dem letzten Aufenthaltsort an";
$lang['admin']['index_lastonline'] = "Zuletzt Online Spalte|Zeigt eine Spalte, wann der Spieler zuletzt online war";
$lang['admin']['index_lastupdate'] = "Zuletzt aktualisiert Spalte|Zeigt eine Spalte, wann ein Spieler zuletzt sein Profil aktualisiert hat";

// menu_conf
$lang['admin']['menu_left_pane'] = "Left Pane|Controls display of the left pane of the main roster menu<br />By default this area holds the member quick list";
$lang['admin']['menu_right_pane'] = "Right Pane|Controls display of the right pane of the main roster menu<br />By default this area holds the realmstatus image";
$lang['admin']['menu_top_pane'] = "Top Pane|Controls display of the top pane of the main roster menu<br />This area holds the guild name, server, and last update";

// display_conf
$lang['admin']['logo'] = "URL für das Kopf-Logo|Die volle URL für das Logo<br />Oder &quot;img/&quot; vor den Namen setzen, um im /img-Verzeichnis des Rosters nachzugucken";
$lang['admin']['roster_bg'] = "URL für das Hintergrundbild|Die volle URL für den Haupthintergrund<br />Oder &quot;img/&quot; vor den Namen setzen, um im /img-Verzeichnis des Rosters nachzugucken";
$lang['admin']['motd_display_mode'] = "MOTD Anzeige Modus|Wie die MOTD (Message of the day) angezeigt werden soll:<br /><br />&quot;Text&quot; - Zeigt MOTD in rotem Text<br />&quot;Image&quot; - Zeigt MOTD als Bild (Benötigt GD!)";
$lang['admin']['compress_note'] = "Notiz Anzeige Modus|Wie die Notiz angezeigt werden soll:<br /><br />&quot;Text&quot; - Zeigt die Notiz als Text<br />&quot;Icon&quot; - Zeigt ein Notizicon mit dem Text in einem Tooltip";
$lang['admin']['signaturebackground'] = "img.php Hintergrund|Support für die (alten) Standard Signaturen";
$lang['admin']['processtime'] = "Seiten Gen. Zeit/DB Abfragen|Zeigt &quot;<i>Diese Seite wurde erzeugt in XXX Sekunden mit XX Datenbankabfragen</i>&quot; im Footer des Rosters an";

// data_links
$lang['admin']['questlink_1'] = "Quest Link #1|Externe Verlinkung der Gegenstände<br />Schau in deine Lokalisations-Datei(en) für weitere Einstellungen";
$lang['admin']['questlink_2'] = "Quest Link #2|Externe Verlinkung der Gegenstände<br />Schau in deine Lokalisations-Datei(en) für weitere Einstellungen";
$lang['admin']['questlink_3'] = "Quest Link #3|Externe Verlinkung der Gegenstände<br />Schau in deine Lokalisations-Datei(en) für weitere Einstellungen";
$lang['admin']['profiler'] = "CharacterProfiler Downloadlink|URL um das CharacterProfiler-Addon herunterzuladen";
$lang['admin']['pvplogger'] = "PvPLog Downloadlink|URL um das PvPLog-Addon herunterzuladen";
$lang['admin']['uploadapp'] = "UniUploader Downloadlink|URL um den UniUploader herunterzuladen";

// char_conf
$lang['admin']['char_bodyalign'] = "Charakterseiten Ausrichtung|Ausrichtung der Daten auf der Charakterseite";
$lang['admin']['recipe_disp'] = "Recipe Display|Controls how the recipe lists display on page load<br />The lists can be collapsed and opened by clicking on the header<br /><br />&quot;show&quot; will fully display the lists when the page loads<br />&quot;hide&quot; will show the lists collapsed";
$lang['admin']['show_talents'] = "Talente|Anzeige der Talente<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_spellbook'] = "Zaubersprüche|Anzeige des Zauberbuchs<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_mail'] = "Postfach|Anzeige des Postfaches<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_inventory'] = "Taschen|Anzeige der Taschen<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_money'] = "Gold|Anzeige des Goldes im Rucksack<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_bank'] = "Bank|Anzeige der Bankfächer<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_recipes'] = "Rezepte|Anzeige der Rezepte<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_quests'] = "Quests|Anzeige der Quests<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_bg'] = "Schlachtfeld PvPLog Daten|Anzeige der Schlachtfeld-Statistiken<br />Benötigt das Hochladen der PvP-Daten mittels des PvPLog-Addons<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_pvp'] = "PvPLog Daten|Anzeige der PvPLog Daten<br />Benötigt das Hochladen der PvP-Daten mittels des PvPLog-Addons<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_duels'] = "Duell PvPLog Daten|Anzeige der Duell PvPLog Data<br />Benötigt das Hochladen der PvP-Daten mittels des PvPLog-Addons<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_item_bonuses'] = "Gegenstands Boni|Anzeige der Boni durch angelegte Gegenstände<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_signature'] = "Signatur anzeigen|Anzeige der Signatur<br /><span class=\"red\">Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$lang['admin']['show_avatar'] = "Avatar anzeigen|Anzeige des Avatars<br /><span class=\"red\">Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";

// realmstatus_conf
$lang['admin']['realmstatus_url'] = "Realmstatus URL|URL zu Blizzard's Realmstatus Seite";
$lang['admin']['rs_display'] = "Info Mode|&quot;full&quot; zeigt Status, Name, Population, und Servertyp<br />&quot;half&quot; zeigt nur den Status an";
$lang['admin']['rs_mode'] = "Display Mode|Wie der Status angezeigt werden soll:<br /><br />&quot;DIV Container&quot; - Zeigt den Realmstatus in einem DIV Container mit Text und Standardbildern<br />&quot;Image&quot; - Zeigt Realmstatus als ein Bild (BENÖTIGT GD!)";
$lang['admin']['realmstatus'] = "Alternativer Servername|Manche Servernamen funktionieren hier nicht richtig, auch wenn der Upload von Profilen geht<br />Der tatsächliche Servername stimmt dann mit dem Namen auf der Statusseite von Blizzard nicht überein.<br />Dann kannst du hier einen anderen Servernamen setzen<br /><br />Leer lassen, um den Namen in der Gildenkonfiguration einzustellen";

// guildbank_conf
$lang['admin']['guildbank_ver'] = "Gildenbank Anzeigeeinstellung|Gildenbank Anzeigeeinstellung:<br /><br />&quot;Table&quot; ist eine einfache Ansicht die eine Liste aller Sachen der Banker anzeigt<br />&quot;Inventory&quot; zeigt eine eigene Tabelle für jeden Banker";
$lang['admin']['bank_money'] = "Goldanzeige|Steuert die Anzeige der Goldmenge in der Gildenbank";
$lang['admin']['banker_rankname'] = "Banker Suchtext|Text um den Banker zu finden";
$lang['admin']['banker_fieldname'] = "Banker Suchfeld|In diesem Tabellenfeld wird nach dem Banker Suchtext gesucht";

// update_access
$lang['admin']['authenticated_user'] = "Zugriff auf Update.php|Kontrolliert den Zugriff auf update.php<br /><br />OFF deaktiviert den Zugriff für JEDEN";

// Character Display Settings
$lang['admin']['per_character_display'] = 'Charakterspezifische Anzeige-Einstellungen';
