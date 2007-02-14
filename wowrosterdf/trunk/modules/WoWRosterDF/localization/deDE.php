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
$wordings['deDE']['update_link']='Hier klicken um zur Aktualisierungsanleitung zu gelangen';
$wordings['deDE']['update_instructions']='Anleitung zur Aktualisierung';

$wordings['deDE']['lualocation']='W&auml;hle die Datei "CharacterProfiler.lua" aus';

$wordings['deDE']['filelocation']='finden unter<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$wordings['deDE']['noGuild']='Gilde nicht in der Datenbank gefunden. Bitte lade zun&auml;chst die Mitgliederliste hoch.';
$wordings['deDE']['nodata']="Konnte Gilde <b>'".$roster_conf['guild_name']."'</b> auf dem Server <b>'".$roster_conf['server_name']."'</b> nicht finden<br />Du musst erst einmal die <a href=\"".getlink($module_name.'&amp;file=update')."\">Gildendaten hochladen</a> oder die <a href=\"".adminlink($module_name)."\">Konfiguration beenden</a>";

$wordings['deDE']['update_page']='Gildenmitglied aktualisieren';
// NOT USED $wordings['deDE']['updCharInfo']='Charakterinformationen aktualisieren';
$wordings['deDE']['guild_nameNotFound']='&quot;%s&quot; nicht gefunden. Stimmt er mit dem konfigurierten Namen &uuml;berein?';
$wordings['deDE']['guild_addonNotFound']='Keine Gilde gefunden. Ist das Addon GuildProfiler korrekt installiert?';

$wordings['deDE']['ignored']='Ignoriert';
$wordings['deDE']['update_disabled']='Update.php Zugriff deaktiviert.';

// NOT USED $wordings['deDE']['updGuildMembers']='Mitgliederliste aktualisieren';
$wordings['deDE']['nofileUploaded']='UniUploader hat keine oder die falschen Dateien hochgeladen.';
$wordings['deDE']['roster_upd_pwLabel']='Roster Update Passwort';
$wordings['deDE']['roster_upd_pw_help']='(Wird nur ben&ouml;tigt, wenn man die Gilde aktualisiert)';

// Updating Instructions

$index_text_uniloader = '(Du kannst dieses Programm von der WoW-Roster-Webseite herunterladen, schaue nach dem "UniUploader Installer" f&uuml;r die aktuellste Version)';

$wordings['deDE']['update_instruct']='
<strong>Empfehlung zur automatischen Aktualisierung:</strong>
<ul>
<li>Benutze den <a href="'.$roster_conf['uploadapp'].'" target="_blank">UniUploader</a><br />
'.$index_text_uniloader.'</li>
</ul>
<strong>Anleitung:</strong>
<ol>
<li>Lade den <a href="'.$roster_conf['profiler'].'" target="_blank">Character Profiler</a> herunter</li>
<li>Extrahiere die Zip-Datei in ein eigenes Verzeichnis unter C:\Program Files\World of Warcraft\Interface\Addons\CharacterProfiler\</li>
<li>Starte WoW</li>
<li>&Ouml;ffne einmal dein Bankschliessfach, deine Rucks&auml;cke, deine Berufsseiten und deine Charakter-&Uuml;bersicht</li>
<li>Logge aus oder beende WoW (Siehe oben, falls das der UniUploader automatisch erledigen soll.)</li>
<li>Gehe zur <a href="'.getlink($module_name.'&amp;file=update').'"> Update-Seite</a></li>
<li>'.$wordings['deDE']['lualocation'].'</li>
</ol>';

$wordings['deDE']['update_instructpvp']='
<strong>Optionale PvP Stats:</strong>
<ol>
<li>Lade <a href="'.$roster_conf['pvplogger'].'" target="_blank">PvPLog</a> herunter</li>
<li>Auch in ein eigenes Addon-Verzeichnis entpacken</li>
<li>Mache ein paar Duelle oder PvP-Kills</li>
<li>Lade "PvPLog.lua" &uuml;ber die Update-Seite hoch</li>
</ol>';

$wordings['deDE']['roster_credits']='Dank an <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, und <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> f&uuml;r den originalen Code der Seite. <br />
WoWRoster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="'.getlink($module_name.'&amp;file=credits').'">Additional Credits</a>';


//Charset
$wordings['deDE']['charset']="charset=utf-8";

$timeformat['deDE'] = '%d.%m. %k:%i'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$phptimeformat['deDE'] = 'd.m. G:i';  // PHP date() Time format (example - 'M D jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


/**
 * Realmstatus Localizations
 */
$wordings['deDE']['rs'] = array(
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
$inst_keys['deDE']['A'] = array(
	'SG' => array( 'Quests', 'SG' =>
			'Schlüssel zur Sengenden Schlucht|4826',
			'Das Horn der Bestie|',
			'Besitznachweis|',
			'Endlich!|'
		),
	'Gnome' => array( 'Key-Only', 'Gnome' =>
			'Werkstattschlüssel|2288'
		),
	'SM' => array( 'Key-Only', 'SM' =>
			'Der scharlachrote Schlüssel|4445'
		),
	'ZF' => array( 'Parts', 'ZF' =>
			'Schlaghammer von Zul\\\'Farrak|5695',
			'Hochheiliger Schlaghammer|8250'
		),
	'Mauro' => array( 'Parts', 'Mauro' =>
			'Szepter von Celebras|19710',
			'Celebriangriff|19549',
			'Celebriandiamant|19545'
		),
	'BRDp' => array( 'Key-Only', 'BRDp' =>
			'Gefängniszellenschlüssel|15545'
		),
	'BRDs' => array( 'Parts', 'BRDs' =>
			'Schlüssel zur Schattenschmiede|2966',
			'Ironfel|9673'
		),
	'DM' => array( 'Key-Only', 'DM' =>
			'Mondsichelschlüssel|35607'
		),
	'Scholo' => array( 'Quests', 'Scholo' =>
			'Skelettschlüssel|16854',
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
	'UBRS' => array( 'Parts', 'UBRS' =>
			'Siegel des Aufstiegs|17057',
			'Unverziertes Siegel des Aufstiegs|5370',
			'Edelstein der Felsspitzoger|5379',
			'Edelstein der Gluthauer|16095',
			'Edelstein der Blutäxte|21777',
			'Ungeschmiedetes Siegel des Aufstiegs|24554||MS',
			'Geschmiedetes Siegel des Aufstiegs|19463||MS'
		),
	'Onyxia' => array( 'Quests', 'Onyxia' =>
			'Drachenfeueramulett|4829',
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
	'MC' => array( 'Key-Only', 'MC' =>
			'Ewige Quintessenz|22754'
		),
);


// HORDE KEYS
$inst_keys['deDE']['H'] = array(
	'SG' => array( 'Key-Only', 'SG' =>
			'Schlüssel zur Sengenden Schlucht|4826'
		),
	'Gnome' => array( 'Key-Only', 'Gnome' =>
			'Werkstattschlüssel|2288'
		),
	'SM' => array( 'Key-Only', 'SM' =>
			'Der scharlachrote Schlüssel|4445'
		),
	'ZF' => array( 'Parts', 'ZF' =>
			'Schlaghammer von Zul\\\'Farrak|5695',
			'Hochheiliger Schlaghammer|8250'
		),
	'Mauro' => array( 'Parts', 'Mauro' =>
			'Szepter von Celebras|19710',
			'Celebriangriff|19549',
			'Celebriandiamant|19545'
		),
	'BRDp' => array( 'Key-Only', 'BRDp' =>
			'Gefängniszellenschlüssel|15545'
		),
	'BRDs' => array( 'Parts', 'BRDs' =>
			'Schlüssel zur Schattenschmiede|2966',
			'Ironfel|9673'
		),
	'DM' => array( 'Key-Only', 'DM' =>
			'Mondsichelschlüssel|35607'
		),
	'Scholo' => array( 'Quests', 'Scholo' =>
			'Skelettschlüssel|16854',
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
	'UBRS' => array( 'Parts', 'UBRS' =>
			'Siegel des Aufstiegs|17057',
			'Unverziertes Siegel des Aufstiegs|5370',
			'Edelstein der Felsspitzoger|5379',
			'Edelstein der Gluthauer|16095',
			'Edelstein der Blutäxte|21777',
			'Ungeschmiedetes Siegel des Aufstiegs|24554||MS',
			'Geschmiedetes Siegel des Aufstiegs|19463||MS'
		),
	'Onyxia' => array( 'Quests', 'Onyxia' =>
			'Drachenfeueramulett|4829',
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
	'MC' => array( 'Key-Only', 'MC' =>
			'Ewige Quintessenz|22754'
		),
);

//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$wordings['deDE']['upload']='Upload';
$wordings['deDE']['required']='Benötigt';
$wordings['deDE']['optional']='Optional';
$wordings['deDE']['attack']='Attacke';
$wordings['deDE']['defense']='Verteidigung';
$wordings['deDE']['class']='Klasse';
$wordings['deDE']['race']='Rasse';
$wordings['deDE']['level']='Level';
$wordings['deDE']['zone']='Letztes Gebiet';
$wordings['deDE']['note']='Notiz';
$wordings['deDE']['title']='Rang';
$wordings['deDE']['name']='Name';
$wordings['deDE']['health']='Gesundheit';
$wordings['deDE']['mana']='Mana';
$wordings['deDE']['gold']='Gold';
$wordings['deDE']['armor']='Rüstung';
$wordings['deDE']['lastonline']='Zuletzt Online';
$wordings['deDE']['lastupdate']='Zuletzt aktualisiert';
$wordings['deDE']['currenthonor']='Aktueller Ehrenrang';
$wordings['deDE']['rank']='Rank';
$wordings['deDE']['sortby']='Sortieren nach %';
$wordings['deDE']['total']='Gesamt';
$wordings['deDE']['hearthed']='Ruhestein';
$wordings['deDE']['recipes']='Rezepte';
$wordings['deDE']['bags']='Taschen';
$wordings['deDE']['character']='Charakter';
$wordings['deDE']['bglog']='BG &Uuml;bersicht';
$wordings['deDE']['pvplog']='PvP &Uuml;bersicht';
$wordings['deDE']['duellog']='Duell &Uuml;bersicht';
$wordings['deDE']['duelsummary']='Duell Summary';
$wordings['deDE']['money']='Money';
$wordings['deDE']['bank']='Bank';
$wordings['deDE']['guildbank']='Gildenbank';
$wordings['deDE']['guildbank_totalmoney']='Gesamt Ersparnisse';
$wordings['deDE']['raid']='CT_Raid';
$wordings['deDE']['guildbankcontact']='Im Besitz von (Kontakt)';
$wordings['deDE']['guildbankitem']='Gegenstand und Beschreibung';
$wordings['deDE']['quests']='Quests';
$wordings['deDE']['roster']='Mitglieder';
$wordings['deDE']['alternate']='Alternative Ansicht';
$wordings['deDE']['byclass']='Nach Klasse';
$wordings['deDE']['menustats']='Stats';
$wordings['deDE']['menuhonor']='Ehre';
$wordings['deDE']['keys']='Schl&uuml;ssel';
$wordings['deDE']['team']='Questgruppe Suchen';
$wordings['deDE']['search']='Suche';
$wordings['deDE']['update']='Letzte Aktualisierung';
$wordings['deDE']['credit']='Credits';
$wordings['deDE']['members']='Mitglieder';
$wordings['deDE']['items']='Gegenst&auml;nde';
$wordings['deDE']['find']='Suche nach';
$wordings['deDE']['upprofile']='Profil Updaten';
$wordings['deDE']['backlink']='Zur&uuml;ck zur &Uuml;bersicht';
$wordings['deDE']['gender']='Geschlecht';
$wordings['deDE']['unusedtrainingpoints']='Unbenutzte Trainingspunkte';
$wordings['deDE']['unusedtalentpoints']='Unbenutzte Talentpunkte';
$wordings['deDE']['questlog']='Questlog';
$wordings['deDE']['recipelist']='Rezepte Liste';
$wordings['deDE']['reagents']='Reagenzien';
$wordings['deDE']['item']='Gegenstand';
$wordings['deDE']['type']='Typ';
$wordings['deDE']['date']='Datum';
$wordings['deDE']['completedsteps'] = 'Abgeschlossen';
$wordings['deDE']['currentstep'] = 'Aktuell';
$wordings['deDE']['uncompletedsteps'] = 'Nicht Abgeschlossen';
$wordings['deDE']['key'] = 'Schl&uuml;ssel';
$wordings['deDE']['timeplayed'] = 'Gespielte Zeit';
$wordings['deDE']['timelevelplayed'] = 'Auf diesem Level';
$wordings['deDE']['Addon'] = 'Addons';
$wordings['deDE']['advancedstats'] = 'Erweiterte Eigenschaften';
$wordings['deDE']['itembonuses'] = 'Boni f&uuml;r angelegte Gegenst&auml;nde';
$wordings['deDE']['itembonuses2'] = 'Gegenstand Boni';
$wordings['deDE']['crit'] = 'Krit.';
$wordings['deDE']['dodge'] = 'Ausweichen';
$wordings['deDE']['parry'] = 'Parrieren';
$wordings['deDE']['block'] = 'Blocken';
$wordings['deDE']['realm'] = 'Realm';

// Memberlog
$wordings['deDE']['memberlog'] = 'Mitglieder Log';
$wordings['deDE']['removed'] = 'Entfernt';
$wordings['deDE']['added'] = 'Zugefügt';
$wordings['deDE']['no_memberlog'] = 'Kein Mitglieder Log gespeichert';

$wordings['deDE']['rosterdiag'] = 'Roster Diagnose Seite';
$wordings['deDE']['Guild_Info'] = 'Gilden Info';
$wordings['deDE']['difficulty'] = 'Schwierigkeit';
$wordings['deDE']['recipe_4'] = 'optimal';
$wordings['deDE']['recipe_3'] = 'mittel';
$wordings['deDE']['recipe_2'] = 'leicht';
$wordings['deDE']['recipe_1'] = 'trivial';
$wordings['deDE']['roster_config'] = 'Roster Config';

// Spellbook
$wordings['deDE']['spellbook'] = 'Zauberspr&uuml;che';
$wordings['deDE']['page'] = 'Seite';
$wordings['deDE']['general'] = 'General';
$wordings['deDE']['prev'] = 'Zurück';
$wordings['deDE']['next'] = 'Vor';

// Mailbox
$wordings['deDE']['mailbox'] = 'Postfach';
$wordings['deDE']['maildateutc'] = 'Briefdatum';
$wordings['deDE']['mail_item'] = 'Gegenstand';
$wordings['deDE']['mail_sender'] = 'Absender';
$wordings['deDE']['mail_subject'] = 'Betreff';
$wordings['deDE']['mail_expires'] = 'Gültig bis';
$wordings['deDE']['mail_money'] = 'Geldanhang';


//this needs to be exact as it is the wording in the db
$wordings['deDE']['professions']='Berufe';
$wordings['deDE']['secondary']='Sekundäre Fertigkeiten';
$wordings['deDE']['Blacksmithing']='Schmiedekunst';
$wordings['deDE']['Mining']='Bergbau';
$wordings['deDE']['Herbalism']='Kräuterkunde';
$wordings['deDE']['Alchemy']='Alchimie';
$wordings['deDE']['Leatherworking']='Lederverarbeitung';
$wordings['deDE']['Jewelcrafting']='Juwelenschleifen';
$wordings['deDE']['Skinning']='Kürschnerei';
$wordings['deDE']['Tailoring']='Schneiderei';
$wordings['deDE']['Enchanting']='Verzauberkunst';
$wordings['deDE']['Engineering']='Ingenieurskunst';
$wordings['deDE']['Cooking']='Kochkunst';
$wordings['deDE']['Fishing']='Angeln';
$wordings['deDE']['First Aid']='Erste Hilfe';
$wordings['deDE']['Poisons']='Gifte';
$wordings['deDE']['backpack']='Rucksack';
$wordings['deDE']['PvPRankNone']='none';

// Uses preg_match() to find required level in recipe tooltip
$wordings['deDE']['requires_level'] = '/Benötigte Stufe ([\d]+)/';

//Tradeskill-Array
$tsArray['deDE'] = array (
	$wordings['deDE']['Alchemy'],
	$wordings['deDE']['Herbalism'],
	$wordings['deDE']['Blacksmithing'],
	$wordings['deDE']['Mining'],
	$wordings['deDE']['Leatherworking'],
	$wordings['deDE']['Jewelcrafting'],
	$wordings['deDE']['Skinning'],
	$wordings['deDE']['Tailoring'],
	$wordings['deDE']['Enchanting'],
	$wordings['deDE']['Engineering'],
	$wordings['deDE']['Cooking'],
	$wordings['deDE']['Fishing'],
	$wordings['deDE']['First Aid'],
	$wordings['deDE']['Poisons'],
);

//Tradeskill Icons-Array
$wordings['deDE']['ts_iconArray'] = array (
	$wordings['deDE']['Alchemy']=>'Trade_Alchemy',
	$wordings['deDE']['Herbalism']=>'Trade_Herbalism',
	$wordings['deDE']['Blacksmithing']=>'Trade_BlackSmithing',
	$wordings['deDE']['Mining']=>'Trade_Mining',
	$wordings['deDE']['Leatherworking']=>'Trade_LeatherWorking',
	$wordings['deDE']['Jewelcrafting']=>'INV_Misc_Gem_02',
	$wordings['deDE']['Skinning']=>'INV_Misc_Pelt_Wolf_01',
	$wordings['deDE']['Tailoring']=>'Trade_Tailoring',
	$wordings['deDE']['Enchanting']=>'Trade_Engraving',
	$wordings['deDE']['Engineering']=>'Trade_Engineering',
	$wordings['deDE']['Cooking']=>'INV_Misc_Food_15',
	$wordings['deDE']['Fishing']=>'Trade_Fishing',
	$wordings['deDE']['First Aid']=>'Spell_Holy_SealOfSacrifice',
	$wordings['deDE']['Poisons']=>'Ability_Poisons',
	'Tigerreiten'=>'Ability_Mount_WhiteTiger',
	'Pferdreiten'=>'Ability_Mount_RidingHorse',
	'Widderreiten'=>'Ability_Mount_MountainRam',
	'Roboschreiter-Lenken'=>'Ability_Mount_MechaStrider',
	'Untoten-Reitkunst'=>'Ability_Mount_Undeadhorse',
	'Raptorreiten'=>'Ability_Mount_Raptor',
	'Kodoreiten'=>'Ability_Mount_Kodo_03',
	'Wolfreiten'=>'Ability_Mount_BlackDireWolf',
);

// Riding Skill Icons-Array
$wordings['deDE']['riding'] = 'Reiten';
$wordings['deDE']['ts_ridingIcon'] = array(
	'Nachtelf'=>'Ability_Mount_WhiteTiger',
	'Mensch'=>'Ability_Mount_RidingHorse',
	'Zwerg'=>'Ability_Mount_MountainRam',
	'Gnom'=>'Ability_Mount_MechaStrider',
	'Untoter'=>'Ability_Mount_Undeadhorse',
	'Troll'=>'Ability_Mount_Raptor',
	'Tauren'=>'Ability_Mount_Kodo_03',
	'Orc'=>'Ability_Mount_BlackDireWolf',
	'Blutelf' => 'Ability_Mount_CockatriceMount',
	'Draenei' => 'Ability_Mount_RidingElekk',
	'Paladin'=>'Ability_Mount_Dreadsteed',
	'Hexenmeister'=>'Ability_Mount_NightmareHorse'
);

// Class Icons-Array
$wordings['deDE']['class_iconArray'] = array (
	'Druide'=>'Ability_Druid_Maul',
	'Jäger'=>'INV_Weapon_Bow_08',
	'Magier'=>'INV_Staff_13',
	'Paladin'=>'Spell_Fire_FlameTounge',
	'Priester'=>'Spell_Holy_LayOnHands',
	'Schurke'=>'INV_ThrowingKnife_04',
	'Schamane'=>'Spell_Nature_BloodLust',
	'Hexenmeister'=>'Spell_Shadow_Cripple',
	'Krieger'=>'INV_Sword_25',
);

//skills
$skilltypes['deDE'] = array(
	1 => 'Klassenfertigkeiten',
	2 => 'Berufe',
	3 => 'Sekundäre Fertigkeiten',
	4 => 'Waffenfertigkeiten',
	5 => 'Rüstungssachverstand',
	6 => 'Sprachen',
);

//tabs
$wordings['deDE']['tab1']='Stats';
$wordings['deDE']['tab2']='Tier';
$wordings['deDE']['tab3']='Ruf';
$wordings['deDE']['tab4']='Fertigk.';
$wordings['deDE']['tab5']='Talente';
$wordings['deDE']['tab6']='PvP';

$wordings['deDE']['strength']='Stärke';
$wordings['deDE']['strength_tooltip']='Erhöht deine Angriffskraft mit Nahkampfwaffen.<br />Erhöht die Menge an Schaden, die mit einem Schild geblockt werden kann.';
$wordings['deDE']['agility']='Beweglichkeit';
$wordings['deDE']['agility_tooltip']= 'Erhöht deine Angriffskraft mit Fernkampfwaffen.<br />Verbessert deine Chance auf einen kritischen Treffer mit allen Waffen.<br />Erhöht deine Rüstung und deine Chance Angriffen auszuweichen.';
$wordings['deDE']['stamina']='Ausdauer';
$wordings['deDE']['stamina_tooltip']= 'Erhöht deine Lebenspunkte.';
$wordings['deDE']['intellect']='Intelligenz';
$wordings['deDE']['intellect_tooltip']= 'Erhöht deine Manapunkte und die die Chance auf einen kritischen Treffer mit Sprüchen.<br />Erhöht die Rate mit denen du deine Waffenfertigkeiten verbesserst.';
$wordings['deDE']['spirit']='Willenskraft';
$wordings['deDE']['spirit_tooltip']= 'Erhöht deine Mana- und Lebens- regenerationsrate.';
$wordings['deDE']['armor_tooltip']= 'Verringert die Menge an Schaden die du von physischen Angriffen erleidest.<br />Die Höhe der Reduzierung ist abhängig vom Level deines Angreifers.';

$wordings['deDE']['melee_att']='Nahkampf';
$wordings['deDE']['melee_att_power']='Nahkampf Kraft';
$wordings['deDE']['range_att']='Fernkampf';
$wordings['deDE']['range_att_power']='Fernkampf Kraft';
$wordings['deDE']['power']='Kraft';
$wordings['deDE']['damage']='Schaden';
$wordings['deDE']['energy']='Energie';
$wordings['deDE']['rage']='Wut';

$wordings['deDE']['melee_rating']='Nahkampf Angriffsrate';
$wordings['deDE']['melee_rating_tooltip']='Deine Angriffsrate beinflusst deine Chance ein Ziel zu treffen und basiert auf deiner Waffenfähigkeit der Waffe die du grade trägst.';
$wordings['deDE']['range_rating']='Fernkampf Angriffsrate';
$wordings['deDE']['range_rating_tooltip']='Deine Angriffsrate beinflusst deine Chance ein Ziel zu treffen und basiert auf deiner Waffenfähigkeit der Waffe die du grade trägst.';

$wordings['deDE']['res_fire']='Feuer Widerstand';
$wordings['deDE']['res_fire_tooltip']='Erh&ouml;ht deinen Widerstand gegen Feuerschaden.<br />Je h&ouml;her der Wert, desto h&ouml;her der Widerstand.';
$wordings['deDE']['res_nature']='Natur Widerstand';
$wordings['deDE']['res_nature_tooltip']='Erh&ouml;ht deinen Widerstand gegen Naturschaden.<br />Je h&ouml;her der Wert, desto h&ouml;her der Widerstand.';
$wordings['deDE']['res_arcane']='Arkan Widerstand';
$wordings['deDE']['res_arcane_tooltip']='Erh&ouml;ht deinen Widerstand gegen Arkanschaden.<br />Je h&ouml;her der Wert, desto h&ouml;her der Widerstand.';
$wordings['deDE']['res_frost']='Frost Widerstand';
$wordings['deDE']['res_frost_tooltip']='Erh&ouml;ht deinen Widerstand gegen Frostschaden.<br />Je h&ouml;her der Wert, desto h&ouml;her der Widerstand.';
$wordings['deDE']['res_shadow']='Schatten Widerstand';
$wordings['deDE']['res_shadow_tooltip']='Erh&ouml;ht deinen Widerstand gegen Schattenschaden.<br />Je h&ouml;her der Wert, desto h&ouml;her der Widerstand.';

$wordings['deDE']['empty_equip']='Kein Gegenstand angelegt';
$wordings['deDE']['pointsspent']='Punkte verteilt:';
$wordings['deDE']['none']='Keine';

$wordings['deDE']['pvplist']=' PvP Statistiken';
$wordings['deDE']['pvplist1']='Gilde, die am meisten unter uns zu leiden hat';
$wordings['deDE']['pvplist2']='Gilde, die uns am meisten zu Schaffen macht';
$wordings['deDE']['pvplist3']='Spieler, der am meisten unter uns zu leiden hat';
$wordings['deDE']['pvplist4']='Spieler, der uns am meisten zu Schaffen macht';
$wordings['deDE']['pvplist5']='Mitglied mit den meisten Kills';
$wordings['deDE']['pvplist6']='Mitglied, der am h&auml;ufigsten gestorben ist';
$wordings['deDE']['pvplist7']='Besten Kills-Level-Durchschnitt';
$wordings['deDE']['pvplist8']='Besten Tod-Level-Durchschnitt';

$wordings['deDE']['hslist']=' Ehren Statistiken';
$wordings['deDE']['hslist1']='H&ouml;chsten Lebenszeit Rang';
$wordings['deDE']['hslist2']='H&ouml;chsten Lebenszeit ES';
$wordings['deDE']['hslist3']='Die meisten Ehrenpunkte';
$wordings['deDE']['hslist4']='Die meisten Arenapunkte';

$wordings['deDE']['Druid']='Druide';
$wordings['deDE']['Hunter']='Jäger';
$wordings['deDE']['Mage']='Magier';
$wordings['deDE']['Paladin']='Paladin';
$wordings['deDE']['Priest']='Priester';
$wordings['deDE']['Rogue']='Schurke';
$wordings['deDE']['Shaman']='Schamane';
$wordings['deDE']['Warlock']='Hexenmeister';
$wordings['deDE']['Warrior']='Krieger';

$wordings['deDE']['today']='Heute';
$wordings['deDE']['yesterday']='Gestern';
$wordings['deDE']['thisweek']='Diese Woche';
$wordings['deDE']['lastweek']='Letzte Woche';
$wordings['deDE']['lifetime']='Gesamte Spielzeit';
$wordings['deDE']['honorkills']='Ehrenhafte Siege';
$wordings['deDE']['dishonorkills']='Ruchlose Morde';
$wordings['deDE']['honor']='Ehre';
$wordings['deDE']['standing']='Platzierung';
$wordings['deDE']['highestrank']='Höchster Rank';
$wordings['deDE']['arena']='Arena';

$wordings['deDE']['totalwins']='Gewinne total';
$wordings['deDE']['totallosses']='Verluste total';
$wordings['deDE']['totaloverall']='Gesamt';
$wordings['deDE']['win_average']='Durchschnittliche Level Differenz (Gewinne)';
$wordings['deDE']['loss_average']='Durchschnittliche Level Differenz  (Verluste)';

// These need to be EXACTLY what PvPLog stores them as
$wordings['deDE']['alterac_valley']='Alteractal';
$wordings['deDE']['arathi_basin']='Arathibecken';
$wordings['deDE']['warsong_gulch']='Warsongschlucht';

$wordings['deDE']['world_pvp']='Welt-PvP';
$wordings['deDE']['versus_guilds']='Gegengilden';
$wordings['deDE']['versus_players']='Gegenspieler';
$wordings['deDE']['bestsub']='Beste Subzone';
$wordings['deDE']['worstsub']='Schlechteste Subzone';
$wordings['deDE']['killedmost']='Am meisten get&ouml;tet';
$wordings['deDE']['killedmostby']='Am meisten get&ouml;tet durch';
$wordings['deDE']['gkilledmost']='Am meisten get&ouml;tete Spieler der Gilde';
$wordings['deDE']['gkilledmostby']='Am meister get&ouml;tet durch Spieler der Gilde';

$wordings['deDE']['wins']='Gewinne';
$wordings['deDE']['losses']='Verluste';
$wordings['deDE']['overall']='Gesamt';
$wordings['deDE']['best_zone']='Beste Zone';
$wordings['deDE']['worst_zone']='Schlechteste Zone';
$wordings['deDE']['most_killed']='Meisten get&ouml;tet';
$wordings['deDE']['most_killed_by']='Meisten get&ouml;tet durch';

$wordings['deDE']['when']='Wann';
$wordings['deDE']['guild']='Gilde';
$wordings['deDE']['leveldiff']='LevelDiff';
$wordings['deDE']['result']='Ergebnis';
$wordings['deDE']['zone2']='Zone';
$wordings['deDE']['subzone']='Subzone';
$wordings['deDE']['bg']='Schlachtfeld';
$wordings['deDE']['yes']='Ja';
$wordings['deDE']['no']='Nein';
$wordings['deDE']['win']='Sieg';
$wordings['deDE']['loss']='Niederlage';
$wordings['deDE']['kills']='Kills';
$wordings['deDE']['unknown']='Unknown';

//strings for Rep-tab
$wordings['deDE']['exalted']='Ehrfürchtig';
$wordings['deDE']['revered']='Respektvoll';
$wordings['deDE']['honored']='Wohlwollend';
$wordings['deDE']['friendly']='Freundlich';
$wordings['deDE']['neutral']='Neutral';
$wordings['deDE']['unfriendly']='Unfreundlich';
$wordings['deDE']['hostile']='Feindselig';
$wordings['deDE']['hated']='Hasserfüllt';
$wordings['deDE']['atwar']='Im Krieg';
$wordings['deDE']['notatwar']='Nicht im Krieg';

// language definitions for the rogue instance keys 'fix'
$wordings['deDE']['thievestools']='Diebeswerkzeug';
$wordings['deDE']['lockpicking']='Schlossknacken';
// END

	// Quests page external links (on character quests page)
		// questlinks[#]['lang']['name']  This is the name displayed on the quests page
		// questlinks[#]['lang']['url#']   This is the URL used for the quest lookup

		$questlinks[0]['deDE']['name']='WoW-Handwerk';
		$questlinks[0]['deDE']['url1']='http://www.wow-handwerk.de/search.php?quicksearch=';
		//$questlinks[0]['deDE']['url2']='';
		//$questlinks[0]['deDE']['url3']='&amp;maxl='';

		$questlinks[1]['deDE']['name']='Buffed DE';
		$questlinks[1]['deDE']['url1']='http://www.buffed.de/?f=';
		//$questlinks[1]['deDE']['url2']='';
		//$questlinks[1]['deDE']['url3']='';

		$questlinks[2]['deDE']['name']='Thottbot';
		$questlinks[2]['deDE']['url1']='http://www.thottbot.com/?f=q&amp;title=';
		$questlinks[2]['deDE']['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$questlinks[2]['deDE']['url3']='&amp;maxl=';

// Items external link
	//$itemlink['deDE'] = 'http://www.wow-handwerk.de/search.php?quicksearch=';
	$itemlink['deDE'] = 'http://www.buffed.de/?f=';

// definitions for the questsearchpage
	$wordings['deDE']['search1']="W&auml;hle eine Zone oder eine Quest um zu schauen, wer sie alles hat.<br />\n<small>Beachte: Stimmen die Questlevel bei verschiedenen Gildenleuten nicht &uuml;berein, handelt es sich um verschiedene Teile einer Questreihe.</small>";
	$wordings['deDE']['search2']='Suche nach Zone';
	$wordings['deDE']['search3']='Suche nach Questname';

// Definitions for item tooltip coloring
	$wordings['deDE']['tooltip_use']='Benutzen';
	$wordings['deDE']['tooltip_requires']='Benötigt';
	$wordings['deDE']['tooltip_reinforced']='Verstärkte';
	$wordings['deDE']['tooltip_soulbound']='Seelengebunden';
	$wordings['deDE']['tooltip_boe']='Wird beim Anlegen gebunden';
	$wordings['deDE']['tooltip_equip']='Anlegen';
	$wordings['deDE']['tooltip_equip_restores']='Anlegen: Stellt';
	$wordings['deDE']['tooltip_equip_when']='Anlegen: Erhöht';
	$wordings['deDE']['tooltip_chance']='Gewährt';
	$wordings['deDE']['tooltip_enchant']='Enchant';
	$wordings['deDE']['tooltip_set']='Set';
	$wordings['deDE']['tooltip_rank']='Rang';
	$wordings['deDE']['tooltip_next_rank']='Nächster Rang';
	$wordings['deDE']['tooltip_spell_damage']='Schaden';
	$wordings['deDE']['tooltip_school_damage']='\\+.*Schaden';
	$wordings['deDE']['tooltip_healing_power']='Heilung';
	$wordings['deDE']['tooltip_chance_hit']='Trefferchance';
	$wordings['deDE']['tooltip_reinforced_armor']='Verstärkte Rüstung';
	$wordings['deDE']['tooltip_damage_reduction']='Schadensreduzierung';

// Warlock pet names for icon displaying
	$wordings['deDE']['Imp']='Wichtel';
	$wordings['deDE']['Voidwalker']='Leerwandler';
	$wordings['deDE']['Succubus']='Sukkubus';
	$wordings['deDE']['Felhunter']='Teufelsjäger';
	$wordings['deDE']['Infernal']='Infernal';
	$wordings['deDE']['Felguard']='Teufelswache';

// Max experiance for exp bar on char page
	$wordings['deDE']['max_exp']='Max XP';

// Error messages
	$wordings['deDE']['CPver_err']="Die verwendete Version des CharacterProfiler, zur Speicherung der Daten für diesen Charakter ist &auml;lter als die für den Upload minimal zugelassene Version.<br/> \nBitte stellen Sie sicher, daß Sie mindestens v".$roster_conf['minCPver']." verwenden, und daß Sie diese Version verwendet haben, um die Daten für diesen Charakter zu speichern.";
	$wordings['deDE']['PvPLogver_err']="Die verwendete Version von PvPLog, zur Speicherung der Daten für diesen Charakter ist &auml;lter als die für den Upload minimale zugelassene Version.<br/> \nBitte stellen Sie sicher, daß Sie mindestens v".$roster_conf['minPvPLogver']." verwenden. Falls Sie gerade Ihr PvPLog aktualisiert haben, stellen Sie sicher daß Sie Ihre alte PvPLog.lua Datei gel&ouml;scht haben, bevor Sie aktualisieren.";
	$wordings['deDE']['GPver_err']="Die verwendete Version von GuildProfiler, zur Speicherung der Daten für diese Gilde ist &auml;lter als die für den Upload minimal zugelassene Version.<br/> \nBitte stellen Sie sicher, daß Sie mindestens v".$roster_conf['minGPver']." verwenden.";






/******************************
 * Roster Admin Strings
 ******************************/

// Submit/Reset confirm questions
$wordings['deDE']['confirm_config_submit'] = 'Dies wird die Änderungen in die Datenbank schreiben. Sind sie sicher?';
$wordings['deDE']['confirm_config_reset'] = 'Dies wird das Formular in den Zustand zurücksetzen in dem es am Anfang war. Sind sie sicher?';

// Main Menu words
$wordings['deDE']['admin']['main_conf'] = 'Haupteinstellungen';
$wordings['deDE']['admin']['guild_conf'] = 'Gildenkonfiguration';
$wordings['deDE']['admin']['index_conf'] = 'Indexseite';
$wordings['deDE']['admin']['menu_conf'] = 'Menüeinstellungen';
$wordings['deDE']['admin']['display_conf'] = 'Anzeigeneinstellungen';
$wordings['deDE']['admin']['char_conf'] = 'Charakterseite';
$wordings['deDE']['admin']['realmstatus_conf'] = 'Serverstatus';
$wordings['deDE']['admin']['guildbank_conf'] = 'Gildenbank';
$wordings['deDE']['admin']['data_links'] = 'Item/Quest Data Links';
$wordings['deDE']['admin']['update_access'] = 'Update Zugriff';


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
$wordings['deDE']['admin']['roster_upd_pw'] = "Roster Update Passwort|Dieses Passwort erlaubt die Aktualisierung der Gildenmitglieder<br />Eine Addons benötigen dieses PW auch";
$wordings['deDE']['admin']['roster_dbver'] = "Roster Datenbank Version|Die Version der Datenbank";
$wordings['deDE']['admin']['version'] = "Roster Version|Aktuelle Version des Rosters";
$wordings['deDE']['admin']['sqldebug'] = "SQL Debug Output|Gib MySQL Debug Ausgaben in HTML Kommentaren";
$wordings['deDE']['admin']['debug_mode'] = "Debug Modus|Zeigt die komplette Debugprotokollierung im Fehlerfalle an";
$wordings['deDE']['admin']['sql_window'] = "SQL Fenster|Zeigt die SQL Fehler in einem Fenster in der Fu&szlig;zeile an";
$wordings['deDE']['admin']['minCPver'] = "Min CP Version|Minimum CharacterProfiler Version zum Upload";
$wordings['deDE']['admin']['minGPver'] = "Min GP version|Minimum GuildProfiler Version zum Upload";
$wordings['deDE']['admin']['minPvPLogver'] = "Min PvPLog version|Minimum PvPLog Version zum Upload";
$wordings['deDE']['admin']['roster_lang'] = "Roster Hauptsprache|Sprache, in der das Roster anzeigen soll";
$wordings['deDE']['admin']['website_address'] = "Webseitenadresse|Wird benötigt für das Logo, den Gildennamenlink und das Hauptmenü<br />Einige Roster Addons benötigen diese auch";
$wordings['deDE']['admin']['roster_dir'] = "Roster URL|Der URL Pfad zum Rosterverzeichnis<br />Es ist wichtig, dass diese korrekt ist, da sonst Fehler auftreten können<br />(Beispiel: http://www.site.com/roster )<br /><br />Eine vollständige URL wird nicht benötigt wenn vor dem Verzeichnis ein Slashzeichen ist<br />(Beispiel: /roster )";
$wordings['deDE']['admin']['server_name_comp']  = "char.php Compatibility Mode|Falls deine Charakterseite nicht funktionieren sollte, dann ändere diesen Wert";
$wordings['deDE']['admin']['interface_url'] = "Interface Directory URL|Verzeichnis zu den Interface Images<br />Das Standartverzeichnis ist &quot;img/&quot;<br /><br />Du kannst auch eine andere URL verwenden.";
$wordings['deDE']['admin']['img_suffix'] = "Bilder Dateierweiterung|Der Dateityp deiner Interface Images";
$wordings['deDE']['admin']['alt_img_suffix'] = "Alternative Bilder Dateierweiterung|Der Dateityp deiner Interface Images";
$wordings['deDE']['admin']['img_url'] = "Roster Bilder Verzeichnis URL|Verzeichnis zu den Roster's Images<br />Das Standartverzeichnis ist &quot;img/&quot;<br /><br />Du kannst auch eine andere URL verwenden.";
$wordings['deDE']['admin']['timezone'] = "Zeitzone|Wird hinter der Zeit angezeigt, damit man weis in welcher Zeitzone sich der Zeithinweis befindet";
$wordings['deDE']['admin']['localtimeoffset'] = "Zeitzonenabstand|Der Zeitzonenabstand zur UTC/GMT<br />Die Zeiten im Roster werden durch diesen Abstand zur UTC/GMT berechnet.";
$wordings['deDE']['admin']['pvp_log_allow'] = "Erlaube Upload von PvPLog-Daten|Wenn man diesen Wert auf &quot;no&quot; stellt, wird das PVPLog Uploadfeld in der Datei &quot;update&quot; ausgeblendet.";
$wordings['deDE']['admin']['use_update_triggers'] = "Addon Update Triggers|Addon Update Triggers werden für einige AddOns während eines Character oder Gildenupdates benötigt.<br />Einige Addons benötigen wahrscheinlich, dass diese Funktion für sie angestellt ist.";

// guild_conf
$wordings['deDE']['admin']['guild_name'] = "Gildenname|Dieser muß exakt so wie im Spiel geschrieben sein,<br />oder du <u>WIRST</u> <u>NICHT</u> in der Lage sein, Daten hochzuladen";
$wordings['deDE']['admin']['server_name'] = "Servername|Dieser muß exakt so wie im Spiel geschrieben sein,<br />oder du <u>WIRST</u> <u>NICHT</u> in der Lage sein, Daten hochzuladen";
$wordings['deDE']['admin']['guild_desc'] = "Gildenbeschreibung|Gib eine kurze Beschreibung der Gilde ein";
$wordings['deDE']['admin']['server_type'] = "Servertyp|Gib an, um welche Art von Server es sich handelt";
$wordings['deDE']['admin']['alt_type'] = "2.-Char Suche (Twinks)|Text, der zur Anzeige der Anzahl der 2.-Charaktere auf der Hautpseite benutzt wird";
$wordings['deDE']['admin']['alt_location'] = "Twink Suchfeld|In welchem Feld soll der Twink-Text gesucht werden";

// index_conf
$wordings['deDE']['admin']['index_pvplist'] = "PvP-Logger Statistiken|PvP-Logger Statistiken auf der Index-Seite<br />Wenn due PvPLog-Upload deaktiviert hast, brauchst du das nicht aktivieren";
$wordings['deDE']['admin']['index_hslist'] = "Ehrensystem Statistiken|Ehrensystem Statistiken auf der Index-Seite";
$wordings['deDE']['admin']['hspvp_list_disp'] = "PvP/Ehren-Listen Anzeige|Wie sollen die PvP- und Ehren Listen initial angezeigt werden:<br />Die Listen können auf- und zugeklappt werden, indem man auf den Kopf klickt<br /><br />&quot;show&quot; zeigt die Listen aufgeklappt beim Seitenaufruf<br />&quot;hide&quot; zeigt die Listen zugeklappt";
$wordings['deDE']['admin']['index_member_tooltip'] = "Mitglied Info Tooltip|Zeigt einige Infos über das Mitglied im Tooltip an";
$wordings['deDE']['admin']['index_update_inst'] = "Aktualisierungsanleitung|Zeigt die Anleitung zum Aktualisieren auf der Indexseite";
$wordings['deDE']['admin']['index_sort'] = "Mitgliedsliste Sortierung|Stellt die Standardsortierung ein";
$wordings['deDE']['admin']['index_motd'] = "Gilden MOTD|Zeige Gilden MOTD auf der Indexseite<br /><br />Regelt auch die Anzeige auf der &quot;Gilden Info&quot; Seite";
$wordings['deDE']['admin']['index_level_bar'] = "Level Balken|Zeigt einen prozentualen Levelbalken auf der Indexseite";
$wordings['deDE']['admin']['index_iconsize'] = "Icon Größe|Wähle die Größe der Icons auf der Indexseite (PvP, Berufe, Klassen, etc..)";
$wordings['deDE']['admin']['index_tradeskill_icon'] = "Beruf Icons|Ermöglich die Anzeige von Berufsicons auf der Indexseite";
$wordings['deDE']['admin']['index_tradeskill_loc'] = "Beruf Spalte Anzeige|In welcher Spalte sollen die Berufsicons angezeigt werden";
$wordings['deDE']['admin']['index_class_color'] = "Klassenfarben|Färbt die Klassennamen ein";
$wordings['deDE']['admin']['index_classicon'] = "Klassen Icons|Zeigt ein Icon für jeden Charakter jeder Klasse an";
$wordings['deDE']['admin']['index_honoricon'] = "PvP Ehrenrang Icons|Zeigt ein Icon des Ehrenrangs neben dem Namen an";
$wordings['deDE']['admin']['index_prof'] = "Berufs Spalte|Dies ist eine eigene Spalte für die Berufsicons<br />Wenn du sie in einer anderen Spalte anzeigst, kannst du diese deaktivieren.";
$wordings['deDE']['admin']['index_currenthonor'] = "Ehren Spalte|Zeigt eine Spalte mit dem aktuellen Ehrenrang an";
$wordings['deDE']['admin']['index_note'] = "Notiz Spalte|Zeigt eine Spalte mit der Spielernotiz an";
$wordings['deDE']['admin']['index_title'] = "Gildentitel Spalte|Zeigt eine Spalte mit dem Gildentitel an";
$wordings['deDE']['admin']['index_hearthed'] = "Ruhestein Ort Spalte|Zeigt eine Spalte mit dem Ort des Ruhesteins an";
$wordings['deDE']['admin']['index_zone'] = "Letztes Gebiet Spalte|Zeigt eine Spalte mit dem letzten Aufenthaltsort an";
$wordings['deDE']['admin']['index_lastonline'] = "Zuletzt Online Spalte|Zeigt eine Spalte, wann der Spieler zuletzt online war";
$wordings['deDE']['admin']['index_lastupdate'] = "Zuletzt aktualisiert Spalte|Zeigt eine Spalte, wann ein Spieler zuletzt sein Profil aktualisiert hat";

// menu_conf
$wordings['deDE']['admin']['menu_left_pane'] = "Linker Bereich (Kleine Mitgliederübersicht)|Anzeige des linken Bereichs des Menüs<br />Hier wird eine kurze Mitgliederübersicht gezeigt";
$wordings['deDE']['admin']['menu_right_pane'] = "Rechter Bereich (Realmstatus)|Anzeige des rechten Bereichs des Menüs<br />Hier wir der Realmstatus angezeigt";
$wordings['deDE']['admin']['menu_memberlog'] = "Memberlog|Link zur Memberlog";
$wordings['deDE']['admin']['menu_guild_info'] = "Gilden Info Link|Link zum Gilden Info";
$wordings['deDE']['admin']['menu_stats_page'] = "Stats Link|Link zu den einfachen Stats";
$wordings['deDE']['admin']['menu_pvp_page'] = "PvP Statistiken Link|Link zu den PvP-Statistiken";
$wordings['deDE']['admin']['menu_honor_page'] = "Ehre Link|Link zur Ehrenseite";
$wordings['deDE']['admin']['menu_guildbank'] = "Gildenbank Link|Link zur Gildenbank";
$wordings['deDE']['admin']['menu_keys_page'] = "Schlüssel Link|Link zu den Instanzschlüsseln";
$wordings['deDE']['admin']['menu_tradeskills_page'] = "Berufe Link|Link zu den Berufen";
$wordings['deDE']['admin']['menu_update_page'] = "Profil Updaten Link|Link zur Update-Seite";
$wordings['deDE']['admin']['menu_quests_page'] = "Questgruppe Suchen Link|Link zur Questgruppen-Seite";
$wordings['deDE']['admin']['menu_search_page'] = "Suche Link|Link zur Suchseite";

// display_conf
$wordings['deDE']['admin']['stylesheet'] = "CSS Stylesheet|CSS stylesheet für das Roster";
$wordings['deDE']['admin']['roster_js'] = "Roster JS File|Main Roster JavaScript Dateiort";
$wordings['deDE']['admin']['tabcontent'] = "Dynamic Tab JS File|JavaScript file location for dynamic tab menus";
$wordings['deDE']['admin']['overlib'] = "Tooltip JS File|Tooltip JavaScript Dateiort";
$wordings['deDE']['admin']['overlib_hide'] = "Overlib JS Fix|JavaScript Dateiort für den Fix für Overlib im Internet Explorer";
$wordings['deDE']['admin']['logo'] = "URL für das Kopf-Logo|Die volle URL für das Logo<br />Oder &quot;img/&quot; vor den Namen setzen, um im /img-Verzeichnis des Rosters nachzugucken";
$wordings['deDE']['admin']['roster_bg'] = "URL für das Hintergrundbild|Die volle URL für den Haupthintergrund<br />Oder &quot;img/&quot; vor den Namen setzen, um im /img-Verzeichnis des Rosters nachzugucken";
$wordings['deDE']['admin']['motd_display_mode'] = "MOTD Anzeige Modus|Wie die MOTD (Message of the day) angezeigt werden soll:<br /><br />&quot;Text&quot; - Zeigt MOTD in rotem Text<br />&quot;Image&quot; - Zeigt MOTD als Bild (Benötigt GD!)";
$wordings['deDE']['admin']['compress_note'] = "Notiz Anzeige Modus|Wie die Notiz angezeigt werden soll:<br /><br />&quot;Text&quot; - Zeigt die Notiz als Text<br />&quot;Icon&quot; - Zeigt ein Notizicon mit dem Text in einem Tooltip";
$wordings['deDE']['admin']['signaturebackground'] = "img.php Hintergrund|Support für die (alten) Standard Signaturen";
$wordings['deDE']['admin']['processtime'] = "Seiten Gen. Zeit/DB Abfragen|Zeigt &quot;<i>Diese Seite wurde erzeugt in XXX Sekunden mit XX Datenbankabfragen</i>&quot; im Footer des Rosters an";
$wordings['deDE']['admin']['item_stats'] = "Item Stats Mod|If you have item_stats installed, turn this on";

// data_links
$wordings['deDE']['admin']['questlink_1'] = "Quest Link #1|Externe Verlinkung der Gegenstände<br />Schau in deine Lokalisations-Datei(en) für weitere Einstellungen";
$wordings['deDE']['admin']['questlink_2'] = "Quest Link #2|Externe Verlinkung der Gegenstände<br />Schau in deine Lokalisations-Datei(en) für weitere Einstellungen";
$wordings['deDE']['admin']['questlink_3'] = "Quest Link #3|Externe Verlinkung der Gegenstände<br />Schau in deine Lokalisations-Datei(en) für weitere Einstellungen";
$wordings['deDE']['admin']['profiler'] = "CharacterProfiler Downloadlink|URL um das CharacterProfiler-Addon herunterzuladen";
$wordings['deDE']['admin']['pvplogger'] = "PvPLog Downloadlink|URL um das PvPLog-Addon herunterzuladen";
$wordings['deDE']['admin']['uploadapp'] = "UniUploader Downloadlink|URL um den UniUploader herunterzuladen";

// char_conf
$wordings['deDE']['admin']['char_bodyalign'] = "Charakterseiten Ausrichtung|Ausrichtung der Daten auf der Charakterseite";
$wordings['deDE']['admin']['show_talents'] = "Talente|Anzeige der Talente<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_spellbook'] = "Zaubersprüche|Anzeige des Zauberbuchs<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_mail'] = "Postfach|Anzeige des Postfaches<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_inventory'] = "Taschen|Anzeige der Taschen<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_money'] = "Gold|Anzeige des Goldes im Rucksack<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_bank'] = "Bank|Anzeige der Bankfächer<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_recipes'] = "Rezepte|Anzeige der Rezepte<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_quests'] = "Quests|Anzeige der Quests<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_bg'] = "Schlachtfeld PvPLog Daten|Anzeige der Schlachtfeld-Statistiken<br />Benötigt das Hochladen der PvP-Daten mittels des PvPLog-Addons<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_pvp'] = "PvPLog Daten|Anzeige der PvPLog Daten<br />Benötigt das Hochladen der PvP-Daten mittels des PvPLog-Addons<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_duels'] = "Duell PvPLog Daten|Anzeige der Duell PvPLog Data<br />Benötigt das Hochladen der PvP-Daten mittels des PvPLog-Addons<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_item_bonuses'] = "Gegenstands Boni|Anzeige der Boni durch angelegte Gegenstände<br /><br />Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_signature'] = "Signatur anzeigen|Anzeige der Signatur<br /><span class=\"red\">Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";
$wordings['deDE']['admin']['show_avatar'] = "Avatar anzeigen|Anzeige des Avatars<br /><span class=\"red\">Einstellung ist global und überschreibt Charakterspezifische Anzeige-Einstellungen!";

// realmstatus_conf
$wordings['deDE']['admin']['realmstatus_url'] = "Realmstatus URL|URL zu Blizzard's Realmstatus Seite";
$wordings['deDE']['admin']['rs_display'] = "Info Mode|&quot;full&quot; zeigt Status, Name, Population, und Servertyp<br />&quot;half&quot; zeigt nur den Status an";
$wordings['deDE']['admin']['rs_mode'] = "Display Mode|Wie der Status angezeigt werden soll:<br /><br />&quot;DIV Container&quot; - Zeigt den Realmstatus in einem DIV Container mit Text und Standardbildern<br />&quot;Image&quot; - Zeigt Realmstatus als ein Bild (BENÖTIGT GD!)";
$wordings['deDE']['admin']['realmstatus'] = "Alternativer Servername|Manche Servernamen funktionieren hier nicht richtig, auch wenn der Upload von Profilen geht<br />Der tatsächliche Servername stimmt dann mit dem Namen auf der Statusseite von Blizzard nicht überein.<br />Dann kannst du hier einen anderen Servernamen setzen<br /><br />Leer lassen, um den Namen in der Gildenkonfiguration einzustellen";

// guildbank_conf
$wordings['deDE']['admin']['guildbank_ver'] = "Gildenbank Anzeigeeinstellung|Gildenbank Anzeigeeinstellung:<br /><br />&quot;Table&quot; ist eine einfache Ansicht die eine Liste aller Sachen der Banker anzeigt<br />&quot;Inventory&quot; zeigt eine eigene Tabelle für jeden Banker";
$wordings['deDE']['admin']['bank_money'] = "Goldanzeige|Steuert die Anzeige der Goldmenge in der Gildenbank";
$wordings['deDE']['admin']['banker_rankname'] = "Banker Suchtext|Text um den Banker zu finden";
$wordings['deDE']['admin']['banker_fieldname'] = "Banker Suchfeld|In diesem Tabellenfeld wird nach dem Banker Suchtext gesucht";

// update_access
$wordings['deDE']['admin']['authenticated_user'] = "Zugriff auf Update.php|Kontrolliert den Zugriff auf update.php<br /><br />OFF deaktiviert den Zugriff für JEDEN";

// Character Display Settings
$wordings['deDE']['admin']['per_character_display'] = 'Charakterspezifische Anzeige-Einstellungen';
