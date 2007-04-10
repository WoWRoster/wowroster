<?php
$versions['versionDate']['deDE'] = '$Date: 2006/02/05 03:43:06 $'; 
$versions['versionRev']['deDE'] = '$Revision: 1.44 $'; 
$versions['versionAuthor']['deDE'] = '$Author: anthonyb $';


//Instructions how to upload, as seen on the mainpage
$update_link['deDE']='Hier klicken um zur Aktualisierungsanleitung zu gelangen.';

$index_text_uniloader = '(Du kannst dieses Programm von der WoW-Profilers-Webseite herunterladen, schaue nach dem "UniUploader Installer" f&uuml;r die aktuellste Version)';

$lualocation['deDE']='W&auml;hle die Datei "CharacterProfiler.lua" aus<br>
(Zu finden unter C:\Program Files\World of Warcraft\WTF\Account\*Accountname*\SavedVariables\*.lua)<br>';

$noGuild['deDE']='Gilde nicht in der Datenbank gefunden. Bitte lade zun&auml;achst die Mitgliederliste hoch.';
$nodata['deDE']="<br><br>Konnte Gilde <b>'$guild_name'</b> auf dem Server <b>'$server_name'</b> nicht finden. Du musst erst einmal die Gildendaten hochladen oder die Konfiguration beenden.<br><a href=\"docs/\" target=\"_new\">Klicke hier um zur Installationsanleitung zu gelangen.</a>";
$return['deDE']='Zur&uuml;ck zur &Uuml;bersicht';
$updMember['deDE']='Gildenmitglied aktualisieren (update.php)';
$updCharInfo['deDE']='Charakterinformationen aktualisieren';
$guild_nameNotFound['deDE']='Gildenname nicht gefunden. Stimmt er mit dem konfigurierten Namen &uuml;berein?';
$guild_addonNotFound['deDE']='Keine Gilde gefunden. Ist das Addon GuildProfiler korrekt installiert?';
$updGuildMembers['deDE']='Mitgliederliste aktualisieren';
$nofileUploaded['deDE']='UniUploader hat keine oder die falschen Dateien hochgeladen.';
$roster_upd_pwLabel['deDE']='Roster Update Passwort';
$roster_upd_pw_help['deDE']='(Dies ist ben�tigt wenn man ein Gild aktualisiert)';

// Updating Instructions
$update_instruct['deDE']='
Empfehlung zur automatischen Aktualisierung:<br>
Benutze den <a href="'.$uploadapp.'">UniUploader</a>&nbsp; '.$index_text_uniloader.'<br>
<br>
Anleitung:<br>
Schritt 1: Lade den <a href="'.$profiler.'">Character Profiler</a> herunter<br>
Schritt 2: Extrahiere die Zip-Datei in ein eigenes Verzeichnis unter C:\Program Files\World of Warcraft\Interface\Addons\ (CharacterProfiler\)<br>
Schritt 3: Starte WoW<br>
Schritt 4: &Ouml;ffne einmal dein Bankschliessfach, deine Rucks&auml;cke, deine Berufsseiten und deine Charakter-&Uuml;bersicht<br>
Schritt 5: Logge aus oder beende WoW (Siehe oben, falls das der UniUploader automatisch erledigen soll.)<br>
Schritt 6: Gehe zur <a href="'.$roster_dir.'/admin/update.php"> Update-Seite</a><br>
Schritt 7: '.$lualocation['deDE'].'
<br>';

$update_instructpvp['deDE']='
Optionale PvP Stats:<br>
Schritt 1: Lade <a href="'.$pvplogger.'">PvPLog</a> herunter<br>
Schritt 2: Auch in ein eigenes Addon-Verzeichnis entpacken<br>
Schritt 3: Mache ein paar Duelle oder PvP-Kills<br>
Schritt 4: Lade "PvPLog.lua" &uuml;ber die Update-Seite hoch<br>
';

$credits['deDE']='Dank an <a href="http://www.poseidonguild.com/char.php?name=Celandro&amp;server=Cenarius">Celandro</a>, <a href="http://www.movieobsession.com/wow/parser/char.php?name=Grieve&amp;server=Bleeding%20Hollow">Paleblackness</a>, Pytte, und <a href="http://www.witchhunters.net/wowinfonew/char.php?name=Rubsi&amp;server=Deathwing">Rubricsinger</a> f&uuml;r den originalen Code der Seite. <br>
WoW Roster home - <a href="http://wowroster.net">http://wowroster.net</a>.<br>
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br>
<a href="credits.php">Additional Credits</a>';

//Charset
$charset['deDE']="charset=utf-8";

//$timeformat['enUS']="%b %d %l%p"; // Time format example - Jul 23 2PM
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
		'Mauro' => array('Parts', 'Mauro' => 'Szepter von Celebras|19710','Celebrian-Griff|19549','Celebrian-Diamant|19545'),
		'BRDp' => array('Key-Only','BRDp' => 'Gefängniszellenschlüssel|15545'),
		'BRDs' => array('Parts','BRDs' => 'Shadowforge-Schlüssel|2966','Ironfel|9673'),
		'DM' => array('Key-Only','DM' => 'Mondsichelschlüssel|35607'),
		'Scholo' => array('Quests','Scholo' => 'Skelettschlüssel|16854','Scholomance|','Skelettfragmente|','Sold reimt sich auf...|','Feuerfeder geschmiedet|',' Arajs Skarabäus','Der Schlüssel zur Scholomance|'),
		'Strath' => array('Key-Only','Strath' => 'Schlüssel zur Stadt|13146'),
		'UBRS' => array('Parts','UBRS' => 'Siegel des Aufstiegs|17057','Unverziertes Siegel des Aufstiegs|5370','Edelstein von Spirestone|5379','Edelstein von Smolderthorn|16095','Edelstein von Bloodaxe|21777','Ungeschmiedetes Siegel des Aufstiegs|24554||MS','Geschmiedetes Siegel des Aufstiegs|19463||MS'),
		'Onyxia' => array('Quests','Onyxia' => 'Drachenfeueramulett|4829','Drachkin-Bedrohung|','Die wahren Meister|','Marshal Windsor|','Verlorene Hoffnung|','Eine zusammengeknüllte Notiz|','Ein Funken Hoffnung|','Gefängnisausbruch!|','Treffen in Stormwind|','Die große Maskerade|','Das Großdrachenauge|','Drachenfeuer-Amulett|')
	);
	
$inst_keys['deDE']['H'] = array(
	    'SG' => array('Key-Only','SG' => 'Schlüssel zur Sengenden Schlucht|4826'),
		'Gnome' => array('Key-Only','Gnome' => 'Werkstattschlüssel|2288'),
		'SM' => array('Key-Only','SM' => 'Der scharlachrote Schlüssel|4445'),
		'ZF' => array('Parts','ZF' => 'Schlaghammer von Zul\\\'Farrak|5695','Hochheiliger Schlaghammer|8250'),
		'Mauro' => array('Parts', 'Mauro' => 'Szepter von Celebras|19710','Celebrian-Griff|19549','Celebrian-Diamant|19545'),
		'BRDp' => array('Key-Only','BRDp' => 'Gefängniszellenschlüssel|15545'),
		'BRDs' => array('Parts','BRDs' => 'Shadowforge-Schlüssel|2966','Ironfel|9673'),
		'DM' => array('Key-Only','DM' => 'Mondsichelschlüssel|35607'),
		'Scholo' => array('Quests','Scholo' => 'Skelettschlüssel|16854','Scholomance|','Skelettfragmente|','Sold reimt sich auf...|','Feuerfeder geschmiedet|',' Arajs Skarabäus','Der Schlüssel zur Scholomance|'),
		'Strath' => array('Key-Only','Strath' => 'Schlüssel zur Stadt|13146'),
		'UBRS' => array('Parts','UBRS' => 'Siegel des Aufstiegs|17057','Unverziertes Siegel des Aufstiegs|5370','Edelstein von Spirestone|5379','Edelstein von Smolderthorn|16095','Edelstein von Bloodaxe|21777','Ungeschmiedetes Siegel des Aufstiegs|24554||MS','Geschmiedetes Siegel des Aufstiegs|19463||MS'),
		'Onyxia' => array('Quests', 'Onyxia' => 'Drachenfeueramulett|4829','Befehl des Kriegsherrn|','Eitriggs Weisheit|','Für die Horde!|','Was der Wind erzählt|','Der Champion der Horde|','Nachricht von Rexxar|','Oculus-Illusionen|','Emberstrife|','Die Prüfung der Schädel, Scryer|','Die Prüfung der Schädel, Somnus|','Die Prüfung der Schädel, Chronalis|','Die Prüfung der Schädel, Axtroz|','Aufstieg...|','Blut des schwarzen Großdrachen-Helden|')
	);


//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct

$wordings['deDE']['upload']='Upload';
$wordings['deDE']['required']='Required';
$wordings['deDE']['optional']='Optional';
$wordings['deDE']['unusedtrainingpoints']='Unbenutzte Trainingspunkte';
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
$wordings['deDE']['bank']='Bank';
$wordings['deDE']['guildbank']='Gildenbank';
$wordings['deDE']['guildbank_totalmoney']='Total bank holdings';
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
$wordings['deDE']['Addon'] = 'Addons:';
$wordings['deDE']['advancedstats'] = 'Erweiterte Eigenschaften';
$wordings['deDE']['itembonuses'] = 'Boni f&uuml;r angelegte Gegenst&auml;nde';
$wordings[$roster_lang]['crit'] = 'Krit.';
$wordings[$roster_lang]['dodge'] = 'Ausweichen';
$wordings[$roster_lang]['parry'] = 'Parrieren';
$wordings[$roster_lang]['block'] = 'Blocken';

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
$tsArray['deDE'] = array ($wordings['deDE']['Alchemy'],$wordings['deDE']['Herbalism'],$wordings['deDE']['Blacksmithing'],$wordings['deDE']['Mining'],$wordings['deDE']['Leatherworking'],$wordings['deDE']['Skinning'],$wordings['deDE']['Tailoring'],$wordings['deDE']['Enchanting'],$wordings['deDE']['Engineering'],$wordings['deDE']['Cooking'],$wordings['deDE']['Fishing'],$wordings['deDE']['First Aid']);

//skills
$skilltypes['deDE'] = array( 1 => 'Klassenfertigkeiten',
         2 => 'Berufe',
         3 => 'Sekundäre Fertigkeiten',
         4 => 'Waffenfertigkeiten',
         5 => 'Rüstungssachverstand',
         6 => 'Sprachen' );

//tabs
$wordings['deDE']['tab1']='Stats';
$wordings['deDE']['tab2']='Tier';
$wordings['deDE']['tab3']='Ruf';
$wordings['deDE']['tab4']='Fertigk.';
$wordings['deDE']['tab5']='Talente';
$wordings['deDE']['tab6']='Ehre';

$wordings['deDE']['strength']='Stärke';
$wordings['deDE']['strength_tooltip']='Erhöht deine Angriffskraft mit Nahkampfwaffen.<br>Erhöht die Menge an Schaden, die mit einem Schild geblockt werden kann.'; 
$wordings['deDE']['agility']='Beweglichkeit';
$wordings['deDE']['agility_tooltip']= 'Erhöht deine Angriffskraft mit Fernkampfwaffen.<br>Verbessert deine Chance auf einen kritischen Treffer mit allen Waffen.<br>Erhöht deine Rüstung und deine Chance Angriffen auszuweichen.';   
$wordings['deDE']['stamina']='Ausdauer';   
$wordings['deDE']['stamina_tooltip']= 'Erhöht deine Lebenspunkte.';
$wordings['deDE']['intellect']='Intelligenz';
$wordings['deDE']['intellect_tooltip']= 'Erhöht deine Manapunkte und die die Chance auf einen kritischen Treffer mit Sprüchen.<br>Erhöht die Rate mit denen du deine Waffenfertigkeiten verbesserst.';
$wordings['deDE']['spirit']='Willenskraft';
$wordings['deDE']['spirit_tooltip']= 'Erhöht deine Mana- und Lebens- regenerationsrate.';  
$wordings['deDE']['armor_tooltip']= 'Verringert die Menge an Schaden die du von physischen Angriffen erleidest.<br>Die Höhe der Reduzierung ist abhängig vom Level deines Angreifers.';

$wordings['deDE']['melee_att']='Nahkampf';
$wordings['deDE']['melee_att_power']='Nahkampf Kraft';
$wordings['deDE']['range_att']='Fernkampf';
$wordings['deDE']['range_att_power']='Fernkampf Kraft';
$wordings['deDE']['power']='Kraft';
$wordings['deDE']['damage']='Schaden'; 

$wordings['deDE']['melee_rating']='Nahkampf Angriffsrate';
$wordings['deDE']['melee_rating_tooltip']='Deine Angriffsrate beinflusst deine Chance ein Ziel zu treffen und basiert auf deiner Waffenfähigkeit der Waffe die du grade trägst.';
$wordings['deDE']['range_rating']='Fernkampf Angriffsrate';
$wordings['deDE']['range_rating_tooltip']='Deine Angriffsrate beinflusst deine Chance ein Ziel zu treffen und basiert auf deiner Waffenfähigkeit der Waffe die du grade trägst.';

$wordings['deDE']['res_fire']='Feuer Widerstand';
$wordings['deDE']['res_fire_tooltip']='Erh&&ouml;ht deinen Widerstand gegen Feuerschaden.<br>Je h&ouml;her der Wert, desto h&ouml;her der Widerstand.';
$wordings['deDE']['res_nature']='Natur Widerstand';
$wordings['deDE']['res_nature_tooltip']='Erh&&ouml;ht deinen Widerstand gegen Naturschaden.<br>Je h&ouml;her der Wert, desto h&ouml;her der Widerstand.';
$wordings['deDE']['res_arcane']='Arkan Widerstand';
$wordings['deDE']['res_arcane_tooltip']='Erh&&ouml;ht deinen Widerstand gegen Arkanschaden.<br>Je h&ouml;her der Wert, desto h&ouml;her der Widerstand.';
$wordings['deDE']['res_frost']='Frost Widerstand';
$wordings['deDE']['res_frost_tooltip']='Erh&&ouml;ht deinen Widerstand gegen Frostschaden.<br>Je h&ouml;her der Wert, desto h&ouml;her der Widerstand.';
$wordings['deDE']['res_shadow']='Schatten Widerstand';
$wordings['deDE']['res_shadow_tooltip']='Erh&&ouml;ht deinen Widerstand gegen Schattenschaden.<br>Je h&ouml;her der Wert, desto h&ouml;her der Widerstand.';

$wordings['deDE']['pointsspent']='Punkte verteilt:';  
$wordings['deDE']['none']='Keine';  

$wordings['deDE']['pvplist']=' PvP Statistiken';
$wordings['deDE']['pvplist1']='Gilde, die am meisten unter uns zu leiden hat';
$wordings['deDE']['pvplist2']='Gilde, die uns am meisten zu Schaffen macht';
$wordings['deDE']['pvplist3']='Spieler, der am meisten unter uns zu leiden hat';
$wordings['deDE']['pvplist4']='Spieler, der uns am meisten zu Schaffen macht';
$wordings['deDE']['pvplist5']=' Mitglied mit den meisten Kills';
$wordings['deDE']['pvplist6']=' Mitglied, der am h&auml;ufigsten gestorben ist';
$wordings['deDE']['pvplist7']=' Mitglied mit dem besten Kills-Level-Durchschnitt';
$wordings['deDE']['pvplist8']=' Mitglied mit dem besten Tod-Level-Durchschnitt';

$wordings['deDE']['hslist']=' Ehren Statistiken';
$wordings['deDE']['hslist1']='H&ouml;chstrangigstes Mitglied diese Woche';
$wordings['deDE']['hslist2']='Beste Platzierung in der letzten Woche';
$wordings['deDE']['hslist3']='Mitglied mit den meisten ES letzte Woche';
$wordings['deDE']['hslist4']='Mitglied mit den meisten US letzte Woche';
$wordings['deDE']['hslist5']='Mitglied mit den meisten EP letzte Woche';
$wordings['deDE']['hslist6']='Mitglied mit dem h&ouml;chsten Lebenszeit Rang';
$wordings['deDE']['hslist7']='Mitglied mit dem h&ouml;chsten Lebenszeit ES';
$wordings['deDE']['hslist8']='Mitglied mit dem h&ouml;chsten Lebenszeit US';
$wordings['deDE']['hslist9']='Mitglied mit dem besten ES zu EP Durchschnitt';

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

$wordings['deDE']['totalwins']='Gewinne total:';
$wordings['deDE']['totallosses']='Verluste total:';
$wordings['deDE']['totaloverall']='Gesamt:';
$wordings['deDE']['win_average']='Durchschnittliche Level Differenz (Gewinne):';
$wordings['deDE']['loss_average']='Durchschnittliche Level Differenz  (Verluste):';

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

//strings for Rep-tab
//no translation for now, because CP stores english values
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
		// questlink_n_name=?		This is the name displayed on the quests page
		// questlink_n_url=?		This is the URL used for the quest lookup

		$questlink_1_name['deDE']='WoW-Handwerk';
		$questlink_1_url1['deDE']='http://www.wow-handwerk.de/search.php?quicksearch='; 
		//$questlink_1_url2['deDE']='';
		//$questlink_1_url3['deDE']='&maxl=''';

		$questlink_2_name['deDE']='Blasc DE'; 
		$questlink_2_url1['deDE']='http://blasc.planet-multiplayer.de/?f='; 
		//$questlink_2_url2['deDE']='';
		//$questlink_2_url3['deDE']='';

		$questlink_3_name['deDE']='Thottbot';
		$questlink_3_url1['deDE']='http://www.thottbot.com/?f=q&title=';
		$questlink_3_url2['deDE']='&obj=&desc=&minl=';
		$questlink_3_url3['deDE']='&maxl=';

// Items external link
	//$itemlink['deDE'] = 'http://www.wow-handwerk.de/search.php?quicksearch=';
	$itemlink['deDE'] = 'http://blasc.planet-multiplayer.de/?f=';
	
// definitions for the questsearchpage
	$wordings['deDE']['search1']="W&auml;hle eine Zone oder eine Quest um zu schauen, wer sie alles hat.<br>\n<small>Beachte: Stimmen die Questlevel bei verschiedenen Gildenleuten nicht &uuml;berein, handelt es sich um verschiedene Teile einer Questreihe.</small>";
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
	$wordings['deDE']['tooltip_healing_power']='Heilung';
	$wordings['deDE']['tooltip_chance_hit']='Trefferchance';
	$wordings['deDE']['tooltip_reinforced_armor']='Verstärkte Rüstung';

// Warlock pet names for icon displaying
	$wordings['deDE']['Imp']='Wichtel';
	$wordings['deDE']['Voidwalker']='Leerwandler';
	$wordings['deDE']['Succubus']='Sukkubus';
	$wordings['deDE']['Felhunter']='Teufelsjäger';
	$wordings['deDE']['Infernal']='Infernal';

// Max experiance for exp bar on char page
	$wordings['deDE']['max_exp']='Max. Erfahrung';

// Error messages
	$wordings['deDE']['CPver_err']="Die verwendete Version von CharacterProfiler, um Daten f�r diesen Charakter zu speichern ist �lter als die minimale zugelassene Version f�r upload.<br/> \nBitte stellen Sie sicher, da� Sie mindestens v$minCPver verwenden, und da� Sie diese Version verwendet haben, um die Daten fur diesen Charakter zu speichern."; 
	$wordings['deDE']['PvPLogver_err']="Die verwendete Version von PvPLog, um Daten f�r diesen Charakter zu speichern ist �lter als die minimale zugelassene Version f�r upload.<br/> \nBitte stellen Sie sicher, da� Sie mindestens v$minPvPLogver verwenden. Falls Sie gerade Ihr PvPLog aktualisiert haben, stellen Sie sicher da� Sie Ihre alte PvPLog.lua Saved Variables file l�schen, bevor Sie aktualisieren."; 
	$wordings['deDE']['GPver_err']="Die verwendete Version von GuildProfiler, um Daten f�r diese Gilde zu speichern ist �lter als die minimale zugelassene Version f�r upload.<br/> \nBitte stellen Sie sicher, da� Sie mindestens v$minGPver verwenden."; 

// Credit page
$creditspage['deDE']='Dank an <a href="http://www.poseidonguild.com/char.php?name=Celandro&amp;server=Cenarius">Celandro</a>, <a href="http://www.movieobsession.com/wow/parser/char.php?name=Grieve&amp;server=Bleeding Hollow">Paleblackness</a>, Pytte, und <a href="http://www.witchhunters.net/wowinfonew/char.php?name=Rubsi&amp;server=Deathwing">Rubricsinger</a> f&uuml;r den originalen Code der Seite.
<br /><br />Besonderen Dank an  <a href="mailto:calvin@rpgoutfitter.com">calvin</a> von <a href="http://www.rpgoutfitter.com">rpgoutfitter</a> f&uuml;r das Bereitstellen der <a href="http://www.rpgoutfitter.com/downloads/wowinterface.cfm">icons</a>.<br />
<br />
Special Thanks to the DEVs of Roster for helping to build and maintain the package, current DEVs are:<br /><br />
<TABLE BORDER CELLPADDING=5>
<TABLE style="border: none;">
<COL>
<COL ALIGN=LEFT>
<TR> <TD>AnthonyB:</TD> <TD>Site Admin, DEV Coordinator</TD></TR>
<TR> <TD>Matt Miller:</TD> <TD>Roster DEV, UniAdmin and UniUploader Author, Site Hoster</TD></TR>
<TR> <TD>Calvin:</TD> <TD>Roster DEV, CharacterProfiler and GuildProfiler Author</TD></TR>
<TR> <TD>Mordon:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Zanix:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Sphinx:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Swipe:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Nerk01:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>RossiRat:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Vaccafoeda:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Nostrademous:</TD> <TD>Roster DEV, PvPLog 0.4.8 Author</TD></TR>
<TR> <TD>Ulminia:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Guppy:</TD> <TD>Roster DEV (currently retired)</TD></TR>
</TABLE>
<br /><br />
Thanks to Cybrey for the Orginal "Made By" addon and Thorus for his mod of this script.
<br /><br />
Thanks to Cybrey for the Reputation addon.
<br /><br />
Advanced Stats & Bonuses, Thanks to cybrey (original author) and dehoskins (for additional output formatting).
<br /><br />
Thanks to the Roster 1.6 Beta Test Team - Kieeps, silencer-ch-au, Thorus, and Zeryl.
<br /><br />
Thanks to all the coders who have contributed there codes in bug fixes and testing of the roster.
<br /><br />
WoW Roster home - <a href="http://wowroster.net">http://wowroster.net</a>.<br /><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />';
?>
