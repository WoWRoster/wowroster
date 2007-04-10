<?php

//Instructions how to upload, as seen on the mainpage
$update_link['deDE']='Hier klicken f&uuml;r Anleitung zum Aktualisieren.';

$index_text_uniloader = '(Du kannst dieses Programm von der WoW-Profilers-Webseite herunterladen, schaue nach dem "UniUploader Installer" f&uuml;r aktuellste Version)';

$lualocation['deDE']='W&auml;hle die Datei "CharacterProfiler.lua" aus<br>
(Zu finden unter C:\Program Files\World of Warcraft\WTF\Account\*Accountname*\SavedVariables\CharacterProfiler.lua)<br>';

$noGuild['deDE']='Gilde nicht in der Datenbank gefunden. Bitte lade zun&auml;achst die Mitgliederliste hoch.';
$return['deDE']='Zur&uuml;ck zur &Uuml;bersicht';
$updMember['deDE']='Gildenmitglied aktualisieren (update.php)';
$updCharInfo['deDE']='Charakterinformationen aktualisieren';
$guild_nameNotFound['deDE']='Gildenname nicht gefunden. Stimmt er mit dem konfigurierten Namen &uuml;berein?';
$guild_addonNotFound['deDE']='Keine Gilde gefunden. Ist das Addon GuildProfiler korrekt installiert?';
$updGuildMembers['deDE']='Mitgliederliste aktualisieren';
$nofileUploaded['deDE']='Ihr UniUploader hochlud kein file(s) oder Antriebskraft das falsche file(s).';

// Updating Instructions
$update_instruct['deDE']='
Empfehlung zur automatischen Aktualisierung:<br>
Benutze den <a href="'.$uploadapp.'">UniUploader</a>&nbsp; '.$index_text_uniloader.'<br>
<br>
Anleitung:<br>
Schritt 1: Lade den <a href="'.$profiler.'">Character Profiler</a> herunter<br>
Schritt 2: Extrahiere zip in ein eigenes Verzeichnis unter C:\Program Files\World of Warcraft\Interface\Addons\ (CharacterProfiler\)<br>
Schritt 3: Starte WoW<br>
Schritt 4: &Ouml;ffne einmal dein Bankschliessfach, deine Rucks&auml;cke, deine Berufsseiten und deine Charakter-&Uuml;bersicht<br>
Schritt 5: Logge aus oder beende WoW (Siehe oben, falls das der UniUploader automatisch erledigen soll.)<br>
Schritt 6: Gehe zur <a href="'.$roster_dir.'/admin/update.php"> Update-Seite</a><br>
Schritt 7: '.$lualocation['deDE'].'
<br>';

$update_instructpvp['deDE']='
Optionale PvP Stats:<br>
Schritt 1: Lade <a href="'.$pvplogger.'">PVPLogger</a> herunter<br>
Schritt 2: Auch in ein eigenes Addon-Verzeichnis entpacken<br>
Schritt 3: Mache ein paar Duelle oder PvP-Kills<br>
Schritt 4: Lade "PvPLog.lua" &uuml;ber die Update-Seite hoch<br>
';

$credits['deDE']='Props to <a href="http://www.poseidonguild.com/char.php?name=Celandro&server=Cenarius">Celandro</a>, <a href="http://www.movieobsession.com/wow/parser/char.php?name=Grieve&server=Bleeding%20Hollow">Paleblackness</a>, Pytte, and <a href="http://www.witchhunters.net/wowinfonew/char.php?name=Rubsi&server=Deathwing">Rubricsinger</a> for the original code used for this site.<br>
Special thanks to <a href="mailto:calvin@rpgoutfitter.com">calvin</a> from <a href="http://www.rpgoutfitter.com">rpgoutfitter</a> for sharing his <a href="http://www.rpgoutfitter.com/downloads/wowinterface.cfm">icons</a>.<br>
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br>';

$questsearchtext['deDE']='W&auml;hle aus der unten stehenden Liste ein Gebiet, um zu sehen, welche Mitglieder dort Quests zu erledigen haben. Falls sich das Level eines Quests bei gleichem Namen unterscheidet, ist es wahrscheinlich Teil einer Questserie.';
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
$wordings['deDE']['sortby']='Sortieren nach %';
$wordings['deDE']['total']='Gesamt';
$wordings['deDE']['hearthed']='Ruhestein';
$wordings['deDE']['recipes']='Rezepte';
$wordings['deDE']['bags']='Taschen';
$wordings['deDE']['character']='Charakter';
$wordings['deDE']['pvplog']='PvP &Uuml;bersicht';
$wordings['deDE']['duellog']='Duell &Uuml;bersicht';
$wordings['deDE']['bank']='Bank';
$wordings['deDE']['guildbank']='Gildenbank';
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
$wordings['deDE']['backpack']='Rucksack';

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
$wordings['deDE']['guild']='Gilde';
$wordings['deDE']['theirlevel']='Ihr Level';
$wordings['deDE']['yourlevel']='Dein Level';
$wordings['deDE']['diff']='Diff';
$wordings['deDE']['result']='Ergebniss';
$wordings['deDE']['zone2']='Zone';
$wordings['deDE']['subzone']='Subzone';
$wordings['deDE']['group']='Gruppe';

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

// Language definitions for Race selection in sig.php
// Format: $wordings['language']['Localized Name']='Race in Filename';
	// Example: $wordings['deDE']['Nachtelf']='nightelf';
	// Example: $wordings['enUS']['Night Elf']='nightelf';
// 'Localized Name' MUST be the same as what is stored in the database
// 'Race in Filename' MUST be the same as the race part in the image filenames

$wordings['deDE']['Nachtelf']='nightelf';
$wordings['deDE']['Zwerg']='dwarf';
$wordings['deDE']['Gnom']='gnome';
$wordings['deDE']['Mensch']='human';
$wordings['deDE']['Orc']='orc';
$wordings['deDE']['Untoter']='undead';
$wordings['deDE']['Troll']='troll';
$wordings['deDE']['Tauren']='tauren';
$wordings['deDE']['Männlich']='male';
$wordings['deDE']['Weiblich']='female';
// END Language definitions for Race selection in sig.php

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

// definitions for the questsearchpage
	$wordings['deDE']['search1']='W&auml;hle eine Zone oder eine Quest um zu schauen, wer sie alles hat. Beachte: Stimmen die Questlevel bei verschiedenen Gildenleuten nicht &uuml;berein, handelt es sich um verschiedene Teile einer Questreihe.';
	$wordings['deDE']['search2']='Suche nach Zone';
	$wordings['deDE']['search3']='oder nach Questname';
?>