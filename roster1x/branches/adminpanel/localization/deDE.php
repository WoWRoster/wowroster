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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}


//Instructions how to upload, as seen on the mainpage
$wordings['deDE']['update_link']='Hier klicken um zur Aktualisierungsanleitung zu gelangen';
$wordings['deDE']['update_instructions']='Updating Instructions';

$wordings['deDE']['lualocation']='W&auml;hle die Datei "CharacterProfiler.lua" aus';

$wordings['deDE']['filelocation']='finden unter<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$wordings['deDE']['noGuild']='Gilde nicht in der Datenbank gefunden. Bitte lade zun&auml;achst die Mitgliederliste hoch.';
$wordings['deDE']['nodata']="Konnte Gilde <b>'".$roster_conf['guild_name']."'</b> auf dem Server <b>'".$roster_conf['server_name']."'</b> nicht finden<br />Du musst erst einmal die <a href=\"".$roster_conf['roster_dir']."/update.php\">Gildendaten hochladen</a> oder die <a href=\"".$roster_conf['roster_dir']."/admin.php\">Konfiguration beenden</a><br /><br /><a href=\"".$roster_conf['roster_dir']."/install.txt\" target=\"_blank\">Klicke hier um zur Installationsanleitung zu gelangen</a>";

$wordings['deDE']['update_page']='Gildenmitglied aktualisieren';
// NOT USED $wordings['deDE']['updCharInfo']='Charakterinformationen aktualisieren';
$wordings['deDE']['guild_nameNotFound']='&quot;*GUILDNAME*&quot; nicht gefunden. Stimmt er mit dem konfigurierten Namen &uuml;berein?';
$wordings['deDE']['guild_addonNotFound']='Keine Gilde gefunden. Ist das Addon GuildProfiler korrekt installiert?';

$wordings['deDE']['ignored']='Ignored';

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
<li>Gehe zur <a href="'.$roster_conf['roster_dir'].'/update.php"> Update-Seite</a></li>
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

$wordings['deDE']['roster_credits']='Dank an <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, und <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a> f&uuml;r den originalen Code der Seite. <br />
WoW Roster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="'.$roster_conf['roster_dir'].'/credits.php">Additional Credits</a>';


//Charset
$wordings['deDE']['charset']="charset=utf-8";

//$timeformat['deDE']="%b %d %l%p"; // Time format example - Jul 23 2PM
$timeformat['deDE']= '%d.%m. %k:%i'; //Time format example - 23.07. 14:00
$phptimeformat['deDE']='d.m. g:i'; // Time format example - 23.Jul. 14:00. This is PHP syntax for date() function


/*
Instance Keys
=============
A part that is marked with 'MS' (milestone) will be designated as an overall status. So if
you have this one part it will mark all other parts lower than this one as complete.
*/
$inst_keys['deDE']['A'] = array(
		'SG' => array('Quests','SG' => 'Schlüssel zur Sengenden Schlucht|4826','Das Horn der Bestie|','Besitznachweis|','Endlich!|'),
		'Gnome' => array('Key-Only','Gnome' => 'Werkstattschlüssel|2288'),
		'SM' => array('Key-Only','SM' => 'Der scharlachrote Schlüssel|4445'),
		'ZF' => array('Parts','ZF' => 'Schlaghammer von Zul\\\'Farrak|5695','Hochheiliger Schlaghammer|8250'),
		'Mauro' => array('Parts','Mauro' => 'Szepter von Celebras|19710','Celebrian-Griff|19549','Celebrian-Diamant|19545'),
		'BRDp' => array('Key-Only','BRDp' => 'Gefängniszellenschlüssel|15545'),
		'BRDs' => array('Parts','BRDs' => 'Schlüssel zur Schattenschmiede|2966','Ironfel|9673'),
		'DM' => array('Key-Only','DM' => 'Mondsichelschlüssel|35607'),
		'Scholo' => array('Quests','Scholo' => 'Skelettschlüssel|16854','Scholomance|','Skelettfragmente|','Sold reimt sich auf...|','Feuerfeder geschmiedet|',' Arajs Skarabäus','Der Schlüssel zur Scholomance|'),
		'Strath' => array('Key-Only','Strath' => 'Schlüssel zur Stadt|13146'),
		'UBRS' => array('Parts','UBRS' => 'Siegel des Aufstiegs|17057','Unverziertes Siegel des Aufstiegs|5370','Edelstein von Spirestone|5379','Edelstein von Smolderthorn|16095','Edelstein von Bloodaxe|21777','Ungeschmiedetes Siegel des Aufstiegs|24554||MS','Geschmiedetes Siegel des Aufstiegs|19463||MS'),
		'Onyxia' => array('Quests','Onyxia' => 'Drachenfeueramulett|4829','Drachkin-Bedrohung|','Die wahren Meister|','Marshal Windsor|','Verlorene Hoffnung|','Eine zusammengeknüllte Notiz|','Ein Funken Hoffnung|','Gefängnisausbruch!|','Treffen in Stormwind|','Die große Maskerade|','Das Großdrachenauge|','Drachenfeuer-Amulett|'),
		'MC' => array('Key-Only','MC' => 'Ewige Quintessenz|22754'),
	);

$inst_keys['deDE']['H'] = array(
	    'SG' => array('Key-Only','SG' => 'Schlüssel zur Sengenden Schlucht|4826'),
		'Gnome' => array('Key-Only','Gnome' => 'Werkstattschlüssel|2288'),
		'SM' => array('Key-Only','SM' => 'Der scharlachrote Schlüssel|4445'),
		'ZF' => array('Parts','ZF' => 'Schlaghammer von Zul\\\'Farrak|5695','Hochheiliger Schlaghammer|8250'),
		'Mauro' => array('Parts','Mauro' => 'Szepter von Celebras|19710','Celebrian-Griff|19549','Celebrian-Diamant|19545'),
		'BRDp' => array('Key-Only','BRDp' => 'Gefängniszellenschlüssel|15545'),
		'BRDs' => array('Parts','BRDs' => 'Shadowforge-Schlüssel|2966','Ironfel|9673'),
		'DM' => array('Key-Only','DM' => 'Mondsichelschlüssel|35607'),
		'Scholo' => array('Quests','Scholo' => 'Skelettschlüssel|16854','Scholomance|','Skelettfragmente|','Sold reimt sich auf...|','Feuerfeder geschmiedet|',' Arajs Skarabäus','Der Schlüssel zur Scholomance|'),
		'Strath' => array('Key-Only','Strath' => 'Schlüssel zur Stadt|13146'),
		'UBRS' => array('Parts','UBRS' => 'Siegel des Aufstiegs|17057','Unverziertes Siegel des Aufstiegs|5370','Edelstein von Spirestone|5379','Edelstein von Smolderthorn|16095','Edelstein von Bloodaxe|21777','Ungeschmiedetes Siegel des Aufstiegs|24554||MS','Geschmiedetes Siegel des Aufstiegs|19463||MS'),
		'Onyxia' => array('Quests', 'Onyxia' => 'Drachenfeueramulett|4829','Befehl des Kriegsherrn|','Eitriggs Weisheit|','Für die Horde!|','Was der Wind erzählt|','Der Champion der Horde|','Nachricht von Rexxar|','Oculus-Illusionen|','Emberstrife|','Die Prüfung der Schädel, Scryer|','Die Prüfung der Schädel, Somnus|','Die Prüfung der Schädel, Chronalis|','Die Prüfung der Schädel, Axtroz|','Aufstieg...|','Blut des schwarzen Großdrachen-Helden|'),
		'MC' => array('Key-Only','MC' => 'Ewige Quintessenz|22754'),
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
$wordings['deDE']['bglog']='Bg &Uuml;bersicht';
$wordings['deDE']['pvplog']='PvP &Uuml;bersicht';
$wordings['deDE']['duellog']='Duell &Uuml;bersicht';
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
$wordings['deDE']['Skinning']='Kürschnerei';
$wordings['deDE']['Tailoring']='Schneiderei';
$wordings['deDE']['Enchanting']='Verzauberkunst';
$wordings['deDE']['Engineering']='Ingenieurskunst';
$wordings['deDE']['Cooking']='Kochkunst';
$wordings['deDE']['Fishing']='Angeln';
$wordings['deDE']['First Aid']='Erste Hilfe';
$wordings['deDE']['poisons']='Poisons';
$wordings['deDE']['backpack']='Rucksack';
$wordings['deDE']['PvPRankNone']='nichts';

//Tradeskill-Array
$tsArray['deDE'] = array (
		$wordings['deDE']['Alchemy'],
		$wordings['deDE']['Herbalism'],
		$wordings['deDE']['Blacksmithing'],
		$wordings['deDE']['Mining'],
		$wordings['deDE']['Leatherworking'],
		$wordings['deDE']['Skinning'],
		$wordings['deDE']['Tailoring'],
		$wordings['deDE']['Enchanting'],
		$wordings['deDE']['Engineering'],
		$wordings['deDE']['Cooking'],
		$wordings['deDE']['Fishing'],
		$wordings['deDE']['First Aid'],
);

//Tradeskill Icons-Array
$wordings['deDE']['ts_iconArray'] = array (
		'Alchimie'=>'Trade_Alchemy',
		'Kräuterkunde'=>'Trade_Herbalism',
		'Schmiedekunst'=>'Trade_BlackSmithing',
		'Bergbau'=>'Trade_Mining',
		'Lederverarbeitung'=>'Trade_LeatherWorking',
		'Kürschnerei'=>'INV_Misc_Pelt_Wolf_01',
		'Schneiderei'=>'Trade_Tailoring',
		'Verzauberkunst'=>'Trade_Engraving',
		'Ingenieurskunst'=>'Trade_Engineering',
		'Kochkunst'=>'INV_Misc_Food_15',
		'Angeln'=>'Trade_Fishing',
		'Erste Hilfe'=>'Spell_Holy_SealOfSacrifice',
		'Tigerreiten'=>'Ability_Mount_WhiteTiger',
		'Pferdreiten'=>'Ability_Mount_RidingHorse',
		'Widderreiten'=>'Ability_Mount_MountainRam',
		'Roboschreiter-Lenken'=>'Ability_Mount_MechaStrider',
		'Undead Horsemanship'=>'Ability_Mount_Undeadhorse',
		'Raptorreiten'=>'Ability_Mount_Raptor',
		'Kodoreiten'=>'Ability_Mount_Kodo_03',
		'Wolfreiten'=>'Ability_Mount_BlackDireWolf',
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
$wordings['deDE']['tab6']='Ehre';

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
$wordings['deDE']['hslist1']='H&ouml;chstrangigstes Mitglied diese Woche';
$wordings['deDE']['hslist2']='Beste Platzierung in der letzten Woche';
$wordings['deDE']['hslist3']='Meisten ES letzte Woche';
$wordings['deDE']['hslist4']='Meisten US letzte Woche';
$wordings['deDE']['hslist5']='Meisten EP letzte Woche';
$wordings['deDE']['hslist6']='H&ouml;chsten Lebenszeit Rang';
$wordings['deDE']['hslist7']='H&ouml;chsten Lebenszeit ES';
$wordings['deDE']['hslist8']='H&ouml;chsten Lebenszeit US';
$wordings['deDE']['hslist9']='Besten ES zu EP Durchschnitt';

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
$wordings['deDE']['alltime']='Gesamte Spielzeit';
$wordings['deDE']['honorkills']='Ehrenhafte Siege';
$wordings['deDE']['dishonorkills']='Ruchlose Morde';
$wordings['deDE']['honor']='Ehre';
$wordings['deDE']['standing']='Platzierung';
$wordings['deDE']['highestrank']='Höchster Rank';

$wordings['deDE']['totalwins']='Gewinne total';
$wordings['deDE']['totallosses']='Verluste total';
$wordings['deDE']['totaloverall']='Gesamt';
$wordings['deDE']['win_average']='Durchschnittliche Level Differenz (Gewinne)';
$wordings['deDE']['loss_average']='Durchschnittliche Level Differenz  (Verluste)';

// These need to be EXACTLY what PvPLog stores them as
$wordings['deDE']['alterac_valley']='Alteractal';
$wordings['deDE']['arathi_basin']='Arathibecken';
$wordings['deDE']['warsong_gulch']='Warsongschlucht';

$wordings['deDE']['world_pvp']='World PvP';
$wordings['deDE']['versus_guilds']='Versus Guilds';
$wordings['deDE']['versus_players']='Versus Players';
$wordings['deDE']['bestsub']='Best Subzone';
$wordings['deDE']['worstsub']='Worst Subzone';
$wordings['deDE']['killedmost']='Killed Most';
$wordings['deDE']['killedmostby']='Killed Most By';
$wordings['deDE']['gkilledmost']='Guild Killed Most';
$wordings['deDE']['gkilledmostby']='Guild Killed Most By';

$wordings['deDE']['wins']='Gewinne';
$wordings['deDE']['losses']='Verluste';
$wordings['deDE']['overall']='Gesamt';
$wordings['deDE']['best_zone']='Best Zone';
$wordings['deDE']['worst_zone']='Worst Zone';
$wordings['deDE']['most_killed']='Most Killed';
$wordings['deDE']['most_killed_by']='Most Killed By';

$wordings['deDE']['when']='Wann';
$wordings['deDE']['rank']='Rank';
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

		$questlinks[1]['deDE']['name']='Blasc DE';
		$questlinks[1]['deDE']['url1']='http://blasc.planet-multiplayer.de/?f=';
		//$questlinks[1]['deDE']['url2']='';
		//$questlinks[1]['deDE']['url3']='';

		$questlinks[2]['deDE']['name']='Thottbot';
		$questlinks[2]['deDE']['url1']='http://www.thottbot.com/?f=q&amp;title=';
		$questlinks[2]['deDE']['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$questlinks[2]['deDE']['url3']='&amp;maxl=';

// Items external link
	//$itemlink['deDE'] = 'http://www.wow-handwerk.de/search.php?quicksearch=';
	$itemlink['deDE'] = 'http://blasc.planet-multiplayer.de/?f=';

// definitions for the questsearchpage
	$wordings['deDE']['search1']="W&auml;hle eine Zone oder eine Quest um zu schauen, wer sie alles hat.<br />\n<small>Beachte: Stimmen die Questlevel bei verschiedenen Gildenleuten nicht &uuml;berein, handelt es sich um verschiedene Teile einer Questreihe.</small>";
	$wordings['deDE']['search2']='Suche nach Zone';
	$wordings['deDE']['search3']='Suche nach Questname';

// serverstatus strings
	$servertypes['deDE']= array( 'PvP', 'Normal', 'RP', 'RSP-PvP' );
	$serverpops['deDE']= array( 'Mittel', 'Niedrig', 'Hoch', 'Max)' );

// Definitions for item tooltip coloring
	$wordings['deDE']['tooltip_use']='Benutzen';
	$wordings['deDE']['tooltip_requires']='Benötigt';
	$wordings['deDE']['tooltip_reinforced']='Verstärkte';
	$wordings['deDE']['tooltip_soulbound']='Seelengebunden';
	$wordings['deDE']['tooltip_equip']='Verwenden';
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
	$wordings['deDE']['tooltip_damage_reduction']='Damage Reduction';

// Warlock pet names for icon displaying
	$wordings['deDE']['Imp']='Wichtel';
	$wordings['deDE']['Voidwalker']='Leerwandler';
	$wordings['deDE']['Succubus']='Sukkubus';
	$wordings['deDE']['Felhunter']='Teufelsjäger';
	$wordings['deDE']['Infernal']='Infernal';

// Max experiance for exp bar on char page
	$wordings['deDE']['max_exp']='Max XP';

// Error messages
	$wordings['deDE']['CPver_err']="Die verwendete Version des CharacterProfiler, zur Speicherung der Daten für diesen Charakter ist &auml;lter als die für den Upload minimal zugelassene Version.<br/> \nBitte stellen Sie sicher, daß Sie mindestens v".$roster_conf['minCPver']." verwenden, und daß Sie diese Version verwendet haben, um die Daten für diesen Charakter zu speichern.";
	$wordings['deDE']['PvPLogver_err']="Die verwendete Version von PvPLog, zur Speicherung der Daten für diesen Charakter ist &auml;lter als die für den Upload minimale zugelassene Version.<br/> \nBitte stellen Sie sicher, daß Sie mindestens v".$roster_conf['minPvPLogver']." verwenden. Falls Sie gerade Ihr PvPLog aktualisiert haben, stellen Sie sicher daß Sie Ihre alte PvPLog.lua Datei gel&ouml;scht haben, bevor Sie aktualisieren.";
	$wordings['deDE']['GPver_err']="Die verwendete Version von GuildProfiler, zur Speicherung der Daten für diese Gilde ist &auml;lter als die für den Upload minimal zugelassene Version.<br/> \nBitte stellen Sie sicher, daß Sie mindestens v".$roster_conf['minGPver']." verwenden.";

// Credit page
$creditspage['deDE']['top']='Dank an <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, und <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a> f&uuml;r den originalen Code der Seite
<br />
Besonderen Dank an <a href="mailto:calvin@rpgoutfitter.com">calvin</a> von <a href="http://www.rpgoutfitter.com" target="_blank">rpgoutfitter</a> f&uuml;r das Bereitstellen der <a href="http://www.rpgoutfitter.com/downloads/wowinterface.cfm" target="_blank">icons</a>
<br /><br />
Special Thanks to the DEVs of Roster for helping to build and maintain the package
<br /><br />';

// This is an array of the dev team
$creditspage['deDE']['devs'] = array(
		'active'=>array(
			array(	"name"=>	"AnthonyB",
					"info"=>	"Site Admin\nWoW Roster Coordinator"),
			array(	"name"=>	"Matt Miller",
					"info"=>	"Gimpy DEV\nAuthor of UniAdmin and UniUploader"),
			array(	"name"=>	"Calvin",
					"info"=>	"Gimpy DEV\nAuthor of CharacterProfiler and GuildProfiler"),
			array(	"name"=>	"Airor/Chris",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"mathos",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Nemm",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"nerk01",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Nostrademous",
					"info"=>	"WoWRoster Dev\nPvPLog Author"),
			array(	"name"=>	"peperone",
					"info"=>	"WoWRoster Dev\nGerman Translator"),
			array(	"name"=>	"RossiRat",
					"info"=>	"WoWRoster Dev\nGerman Translator"),
			array(	"name"=>	"seleleth",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"silencer-ch-au",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Sphinx",
					"info"=>	"WoWRoster Dev\nGerman Translator"),
			array(	"name"=>	"Swipe",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"vaccafoeda",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Vich",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"zanix",
					"info"=>	"WoWRoster Dev\nSigGen Roster-Addon Author"),
		),

		'inactive'=>array(
			array(	"name"=>	"dsrbo",
					"info"=>	"Retired DEV\nRetired PvPLog Author"),
			array(	"name"=>	"Guppy",
					"info"=>	"Retired DEV"),
			array(	"name"=>	"Mordon",
					"info"=>	"Retired Dev"),
		),

		'beta'=>array(
			array(	"name"=>	"Anaxent",
					"info"=>	"WoWRoster Beta Tester\nDragonflyCMS Port of Roster"),
			array(	"name"=>	"Kieeps",
					"info"=>	"WoWRoster Beta Tester"),
			array(	"name"=>	"Thorus",
					"info"=>	"WoWRoster Beta Tester"),
			array(	"name"=>	"Zeryl",
					"info"=>	"WoWRoster Beta Tester"),
		),
	);

$creditspage['deDE']['bottom'] = '
Thanks to Cybrey for the Orginal "Made By" addon and Thorus for his mod of this script.
<br />
Thanks to Cybrey for the Reputation addon.
<br />
Advanced Stats &amp; Bonuses, Thanks to Cybrey (original author) and dehoskins (for additional output formatting).
<br />
Thanks to all the coders who have contributed there codes in bug fixes and testing of the roster.
<br /><br />
WoW Roster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a>
<br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.';



/******************************
 * Roster Admin Strings
 ******************************/

// Main Menu words
$wordings['deDE']['admin']['main_conf'] = 'Haupteinstellungen';
$wordings['deDE']['admin']['guild_conf'] = 'Gildenkonfiguration';
$wordings['deDE']['admin']['index_conf'] = 'Indexseite';
$wordings['deDE']['admin']['menu_conf'] = 'Menü';
$wordings['deDE']['admin']['display_conf'] = 'Anzeigeneinstellungen';
$wordings['deDE']['admin']['misc_conf'] = 'Sonstige Einstellungen';
$wordings['deDE']['admin']['char_conf'] = 'Charakterseite';
$wordings['deDE']['admin']['realmstatus_conf'] = 'Serverstatus';
$wordings['deDE']['admin']['guildbank_conf'] = 'Gildenbank';
$wordings['deDE']['admin']['data_links'] = 'Item/Quest Data Links';
$wordings['deDE']['admin']['update_access'] = 'Update Zugriff';


// All strings here
// Each variable must be the same name as the config variable name
// Example:
//   Assign description text an tooltip for $roster_conf['sqldebug']
//   $wordings['locale']['admin']['sqldebug'] = "Desc|Tooltip";

// Each string is separated by a pipe ( | )
// The first part is the short description, the next part is the tooltip
// Use <br> to make new lines!
// Example:
//   "Controls Flux-Capacitor|Turning this on may cause serious temporal distortions<br>Use with care"


// main_conf
$wordings['deDE']['admin']['roster_upd_pw'] = "Roster Update Passwort|Dieses Passwort erlaubt die Aktualisierung der Gildenmitglieder<br>Eine Addons benötigen dieses PW auch";
$wordings['deDE']['admin']['roster_dbver'] = "Roster Databank Version|Die Version der Datenbank";
$wordings['deDE']['admin']['version'] = "Roster Version|Aktuelle Version des Rosters";
$wordings['deDE']['admin']['sqldebug'] = "SQL Debug Output|Gib MySQL Debug Ausgaben in HTML Kommentaren";
$wordings['deDE']['admin']['minCPver'] = "Min CP Version|Minimum CharacterProfiler Version zum Upload";
$wordings['deDE']['admin']['minGPver'] = "Min GP version|Minimum GuildProfiler Version zum Upload";
$wordings['deDE']['admin']['minPvPLogver'] = "Min PvPLog version|Minimum PvPLog Version zum Upload";
$wordings['deDE']['admin']['roster_lang'] = "Roster Hauptsprache|Sprache, in der das Roster anzeigen soll";
$wordings['deDE']['admin']['website_address'] = "Webseitenadresse|Wird benötigt für das Logo, den Gildennamenlink und das Hauptmenü<br>Einige Roster Addons benötigen diese auch";
$wordings['deDE']['admin']['roster_dir'] = "Roster URL|Der URL Pfad zum Rosterverzeichnis<br>Es ist wichtig, dass diese korrekt ist, da sonst Fehler auftreten können<br>(Beispiel: http://www.site.com/roster )<br><br>Eine vollständige URL wird nicht benötigt wenn vor dem Verzeichnis ein Slashzeichen ist<br>(Beispiel: /roster )";
$wordings['deDE']['admin']['server_name_comp']  = "char.php Compatibility Mode|Falls deine Charakterseite nicht funktionieren sollte, dann ändere diesen Wert";
$wordings['deDE']['admin']['interface_url'] = "Interface Directory URL|Verzeichnis zu den Interface Images<br>Das Standartverzeichnis ist &quot;img/&quot;<br><br>Du kannst auch eine andere URL verwenden.";
$wordings['deDE']['admin']['img_suffix'] = "Interface Image Extension|Der Dateityp deiner Interface Images";
$wordings['deDE']['admin']['alt_img_suffix'] = "Alt Interface Image Extension|Der Dateityp deiner Interface Images";
$wordings['deDE']['admin']['img_url'] = "Roster Images Directory URL|Verzeichnis zu den Roster's Images<br>Das Standartverzeichnis ist &quot;img/&quot;<br><br>Du kannst auch eine andere URL verwenden.";
$wordings['deDE']['admin']['timezone'] = "Timezone|Wird hinter der Zeit angezeigt, damit man weis in welcher Zeitzone sich der Zeithinweis befindet";
$wordings['deDE']['admin']['localtimeoffset'] = "Time Offest|Der Zeitzonenabstand zur UTC/GMT<br>Die Zeiten im Roster werden durch diesen Abstand zur UTC/GMT berechnet.";
$wordings['deDE']['admin']['pvp_log_allow'] = "Allow upload of PvPLog Data|Wenn man diesen Wert auf &quot;no&quot; stellt, wird das PVPLog Uploadfeld in der Datei &quot;update&quot; ausgeblendet.";
$wordings['deDE']['admin']['use_update_triggers'] = "Addon Update Triggers|Addon Update Triggers werden für einige AddOns während eines Character oder Gildenupdates benötigt.<br>Einige Addons benötigen wahrscheinlich, dass diese Funktion für sie angestellt ist.";

// guild_conf
$wordings['deDE']['admin']['guild_name'] = "Gildenname|Dieser muß exakt so wie im Spiel geschrieben sein,<br>oder du <u>WIRST</u> <u>NICHT</u> in der Lage sein, Daten hochzuladen";
$wordings['deDE']['admin']['server_name'] = "Servername|Dieser muß exakt so wie im Spiel geschrieben sein,<br>oder du <u>WIRST</u> <u>NICHT</u> in der Lage sein, Daten hochzuladen";
$wordings['deDE']['admin']['guild_desc'] = "Gildenbeschreibung|Gib eine kurze Beschreibung der Gilde ein";
$wordings['deDE']['admin']['server_type'] = "Servertyp|Gib an, um welche Art von Server es sich handelt";
$wordings['deDE']['admin']['alt_type'] = "2.-Char Suche (Twinks)|Text, der zur Anzeige der Anzahl der 2.-Charaktere auf der Hautpseite benutzt wird";
$wordings['deDE']['admin']['alt_location'] = "Twink Suchfeld|In welchem Feld soll der Twink-Text gesucht werden";

// index_conf
$wordings['deDE']['admin']['index_pvplist'] = "PvP-Logger Stats|PvP-Logger stats on the index page<br>If you have disabled PvPlog uploading, there is no need to have this on";
$wordings['deDE']['admin']['index_hslist'] = "Honor System Stats|Honor System stats on the index page";
$wordings['deDE']['admin']['hspvp_list_disp'] = "PvP/Honor List Display|Controls how the PvP and Honor Lists display on page load<br>The lists can be collapsed and opened by clicking on the header<br><br>&quot;show&quot; will fully display the lists when the page loads<br>&quot;hide&quot; will show the lists collapsed";
$wordings['deDE']['admin']['index_member_tooltip'] = "Member Info Tooltip|Displays some info about a character in a tooltip";
$wordings['deDE']['admin']['index_update_inst'] = "Update Instructions|Controls the display of the Update Instructions on the page";
$wordings['deDE']['admin']['index_sort'] = "Member List Sort|Controls the default sorting";
$wordings['deDE']['admin']['index_motd'] = "Guild MOTD|Show Guild Message of the Day on the top of the page<br><br>This also controls the display on the &quot;Guild Info&quot; page as well";
$wordings['deDE']['admin']['index_level_bar'] = "Level Bar|Toggles the display of a visual level percentage bar on the main page";
$wordings['deDE']['admin']['index_iconsize'] = "Icon Size|Select the size of the icons on the main pages (PvP, tradeskills, class, etc..)";
$wordings['deDE']['admin']['index_tradeskill_icon'] = "Tradeskill Icons|Enables tradeskill icons on the main pages";
$wordings['deDE']['admin']['index_tradeskill_loc'] = "Tradeskill Column Display|Select what column to place tradeskill icons";
$wordings['deDE']['admin']['index_class_color'] = "Class Colorizing|Colorize the class names";
$wordings['deDE']['admin']['index_classicon'] = "Class Icons|Displays an icon for each class, for each character";
$wordings['deDE']['admin']['index_honoricon'] = "PvP Honor Icons|Displays a PvP rank icon next to the rank name";
$wordings['deDE']['admin']['index_prof'] = "Professions Column|This is a specific coulmn for the tradeskill icons<br>If you move them to another column, you might want to turn this off";
$wordings['deDE']['admin']['index_currenthonor'] = "Honor Column|Toggles the display of the honor column";
$wordings['deDE']['admin']['index_note'] = "Note Column|Toggles the display of the public note column";
$wordings['deDE']['admin']['index_title'] = "Guild Title Column|Toggles the display of the guild title column";
$wordings['deDE']['admin']['index_hearthed'] = "Hearthstone Loc. Column|Toggles the display of the hearthstone location column";
$wordings['deDE']['admin']['index_zone'] = "Last Zone Loc. Column|Toggles the display of the last zone column";
$wordings['deDE']['admin']['index_lastonline'] = "Last Seen Online Column|Toggles the display of the last seen online column";
$wordings['deDE']['admin']['index_lastupdate'] = "Last Updated Column|Display when the character last updated their info";

// menu_conf
$wordings['deDE']['admin']['menu_left_pane'] = "Left Pane (Member Quick List)|Controls display of the left pane of the main roster menu<br>This area holds the member quick list";
$wordings['deDE']['admin']['menu_right_pane'] = "Right Pane (Realmstatus)|Controls display of the right pane of the main roster menu<br>This area holds the realmstatus image";
$wordings['deDE']['admin']['menu_byclass'] = "By Class Link|Controls display of the By Class Link";
$wordings['deDE']['admin']['menu_alt_page'] = "Alternate View Link|Controls display of the Alternate View Link";
$wordings['deDE']['admin']['menu_guild_info'] = "Guild-Info Link|Controls display of the Guild-Info Link";
$wordings['deDE']['admin']['menu_stats_page'] = "Basic Stats Link|Controls display of the Basic Stats Link";
$wordings['deDE']['admin']['menu_pvp_page'] = "PvPLog Stats Link|Controls display of the PvPLog Stats Link";
$wordings['deDE']['admin']['menu_honor_page'] = "Honor Page Link|Controls display of the Honor Page Link";
$wordings['deDE']['admin']['menu_guildbank'] = "Guildbank Link|Controls display of the Guildbank Link";
$wordings['deDE']['admin']['menu_keys_page'] = "Instance Keys Link|Controls display of the Instance Keys Link";
$wordings['deDE']['admin']['menu_tradeskills_page'] = "Professions Link|Controls display of the Professions Link";
$wordings['deDE']['admin']['menu_update_page'] = "Profile Update Link|Controls display of the Profile Update Link";
$wordings['deDE']['admin']['menu_quests_page'] = "Find Team/Quests Link|Controls display of the Find Team/Quests Link";
$wordings['deDE']['admin']['menu_search_page'] = "Search Page Link|Controls display of the Search Page Link";

// display_conf
$wordings['deDE']['admin']['stylesheet'] = "CSS Stylesheet|CSS stylesheet for roster";
$wordings['deDE']['admin']['roster_js'] = "Roster JS File|Main Roster JavaScript file location";
$wordings['deDE']['admin']['overlib'] = "Tooltip JS File|Tooltip JavaScript file location";
$wordings['deDE']['admin']['overlib_hide'] = "Overlib JS Fix|JavaScript file location of fix for Overlib in Internet Explorer";
$wordings['deDE']['admin']['logo'] = "URL für das Kopf-Logo|The full URL to the image<br>Or by apending &quot;img/&quot; to the name, it will look in the roster's img/ directory";
$wordings['deDE']['admin']['roster_bg'] = "URL for background image|The full URL to the image used for the main background<br>Or by apending &quot;img/&quot; to the name, it will look in the roster's img/ directory";
$wordings['deDE']['admin']['motd_display_mode'] = "MOTD Display Mode|How the MOTD will be displayed<br><br>&quot;Text&quot; - Shows MOTD in red text<br>&quot;Image&quot; - Shows MOTD as an image (REQUIRES GD!)";
$wordings['deDE']['admin']['signaturebackground'] = "img.php Background|Support for legacy signature-creator";

// data_links
$wordings['deDE']['admin']['questlink_1'] = "Quest Link #1|Item external links<br>Look in your localization-file(s) for link configuration";
$wordings['deDE']['admin']['questlink_2'] = "Quest Link #2|Item external links<br>Look in your localization-file(s) for link configuration";
$wordings['deDE']['admin']['questlink_3'] = "Quest Link #3|Item external links<br>Look in your localization-file(s) for link configuration";
$wordings['deDE']['admin']['profiler'] = "CharacterProfiler download link|URL to download CharacterProfiler";
$wordings['deDE']['admin']['pvplogger'] = "PvPLog download link|URL to download PvPLog";
$wordings['deDE']['admin']['uploadapp'] = "UniUploader download link|URL to download UniUploader";

// char_conf
$wordings['deDE']['admin']['char_bodyalign'] = "Character Page Alignment|Alignment of the data on the character page";
$wordings['deDE']['admin']['char_header_logo'] = "Header Logo|Show the roster header logo on character page";
$wordings['deDE']['admin']['show_talents'] = "Talents|Controls the display of Talents<br><br>Setting is global and overrides per-user setting";
$wordings['deDE']['admin']['show_spellbook'] = "Spellbook|Controls the display of the Spellbook<br><br>Setting is global and overrides per-user setting";
$wordings['deDE']['admin']['show_mail'] = "Mail|Controls the display of Mail<br><br>Setting is global and overrides per-user setting";
$wordings['deDE']['admin']['show_inventory'] = "Bags|Controls the display of Bags<br><br>Setting is global and overrides per-user setting";
$wordings['deDE']['admin']['show_money'] = "Money|Controls the display of Money<br><br>Setting is global and overrides per-user setting";
$wordings['deDE']['admin']['show_bank'] = "Bank|Controls the display of Bank contents<br><br>Setting is global and overrides per-user setting";
$wordings['deDE']['admin']['show_recipes'] = "Recipes|Controls the display of Recipes<br><br>Setting is global and overrides per-user setting";
$wordings['deDE']['admin']['show_quests'] = "Quests|Controls the display of Quests<br><br>Setting is global and overrides per-user setting";
$wordings['deDE']['admin']['show_bg'] = "Battleground PvPLog Data|Controls the display of Battleground PvPLog data<br>Requires upload of PvPLog addon data<br><br>Setting is global and overrides per-user setting";
$wordings['deDE']['admin']['show_pvp'] = "PvPLog Data|Controls the display of PvPLog Data<br>Requires upload of PvPLog addon data<br><br>Setting is global and overrides per-user setting";
$wordings['deDE']['admin']['show_duels'] = "Duel PvPLog Data|Controls the display of Duel PvPLog Data<br>Requires upload of PvPLog addon data<br><br>Setting is global and overrides per-user setting";
$wordings['deDE']['admin']['show_item_bonuses'] = "Item Bonuses|Controls the display of Item Bonuses<br><br>Setting is global and overrides per-user setting";
$wordings['deDE']['admin']['show_signature'] = "Display Signature|Controls the display of a Signature image<br><span class=\"red\">Requires SigGen Roster Addon</span><br><br>Setting is global";
$wordings['deDE']['admin']['show_avatar'] = "Display Avatar|Controls the display of an Avatar image<br><span class=\"red\">Requires SigGen Roster Addon</span><br><br>Setting is global";

// realmstatus_conf
$wordings['deDE']['admin']['realmstatus_url'] = "Realmstatus URL|URL to Blizzard's Realmstatus page";
$wordings['deDE']['admin']['rs_display'] = "Info Mode|&quot;full&quot; will show status and server name, population, and type<br>&quot;half&quot; will display just the status";
$wordings['deDE']['admin']['rs_mode'] = "Display Mode|How Realmstatus will be displayed<br><br>&quot;DIV Container&quot; - Shows Realmstatus in a DIV container with text and standard images<br>&quot;Image&quot; - Shows Realmstatus as an image (REQUIRES GD!)";
$wordings['deDE']['admin']['realmstatus'] = "Alternate Servername|Some server names may cause realmstatus to not work correctly, even if uploading profiles works<br>The actual server name from the game may not match what is used on the server status data page<br>You can set this so serverstatus can use another servername<br><br>Leave blank to use the name set in Guild Config";

// guildbank_conf
$wordings['deDE']['admin']['guildbank_ver'] = "Guildbank Display Type|Guild bank display type<br><br>&quot;Table&quot; is a basic view showing all items available from every bank character in one list<br>&quot;Inventory&quot; shows a table of items for each bank character";
$wordings['deDE']['admin']['bank_money'] = "Money Display|Controls Money display in guildbanks";
$wordings['deDE']['admin']['banker_rankname'] = "Banker Search Text|Text used to designate banker characters";
$wordings['deDE']['admin']['banker_fieldname'] = "Banker Search Field|Banker Search location, what field to search for Banker Text";

// update_access
$wordings['deDE']['admin']['authenticated_user'] = "Allow Access to Update|Controlls access to update<br><br>Turn this off when phpBB access control is configured";
$wordings['deDE']['admin']['phpbb_root_path'] = "Path to phpBB|Set this to where phpBB is located<br>The path <u>must</u> be realative to where roster is installed";
$wordings['deDE']['admin']['upload_group'] = "Usergroup Access to Update|Set the user group id's that have access to upload roster data separated by a comma<br>( EX: 3, 4, 44 )<br><br>You can get these id's from pbpBB's &quot;user_group&quot; table, in the column &quot; group_id&quot;";

// Character Display Settings
$wordings['deDE']['admin']['per_character_display'] = 'Per-Character Display';

?>