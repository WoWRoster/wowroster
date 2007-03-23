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

// Basic wordings used by the Categorised GuildBank
$wordings['enUS']['filter'] = 'Filter';
$wordings['enUS']['gbank_charsNotFound'] = 'Could not find any '.$wordings[$roster_conf['roster_lang']]['guildbank'].' '.$wordings[$roster_conf['roster_lang']]['character'].'s.';
$wordings['enUS']['reqlevel'] = 'Requires Level';
$wordings['enUS']['lvlrange'] = 'Level Range';

$wordings['deDE']['filter'] = 'Filter';
$wordings['deDE']['gbank_charsNotFound'] = $wordings[$roster_conf['roster_lang']]['guildbank'].' '.$wordings[$roster_conf['roster_lang']]['character'].'(e) nicht gefunden.';
$wordings['deDE']['reqlevel'] = 'Benötigt Level';
$wordings['deDE']['lvlrange'] = 'Level Bereich';

$wordings['frFR']['filter'] = 'Filtre';
$wordings['frFR']['gbank_charsNotFound'] = 'Impossible de trouver des '.$wordings[$roster_conf['roster_lang']]['character'].'s-'.$wordings[$roster_conf['roster_lang']]['guildbank'];
$wordings['frFR']['reqlevel'] = 'Niveau requis';
$wordings['frFR']['lvlrange'] = 'Intervalle de Niveaux';

/*************************** Header (enUS) ********************************/
// The header name for each category that will appear
$wordings['enUS']['bankitem_1'] =  "Armor";
$wordings['enUS']['bankitem_2'] =  "Weapons";
$wordings['enUS']['bankitem_3'] =  "Leatherworking Patterns";
$wordings['enUS']['bankitem_4'] =  "Tailoring Patterns";
$wordings['enUS']['bankitem_5'] =  "Blacksmithing Plans";
$wordings['enUS']['bankitem_6'] =  "Alchemy Recipes";
$wordings['enUS']['bankitem_7'] =  "Enchanting Formulas";
$wordings['enUS']['bankitem_8'] =  "Engineering Schematics";
$wordings['enUS']['bankitem_9'] =  "First-Aid Items";
$wordings['enUS']['bankitem_10'] = "Tailoring Materials";
$wordings['enUS']['bankitem_11'] = "Herbs";
$wordings['enUS']['bankitem_12'] = "Alchemy Materials, Oils & Potions";
$wordings['enUS']['bankitem_13'] = "Darkmoon Cards";
$wordings['enUS']['bankitem_14'] = "Leathers";
$wordings['enUS']['bankitem_15'] = "Gems & Jewels";
$wordings['enUS']['bankitem_16'] = "Enchanting Supplies";
$wordings['enUS']['bankitem_17'] = "Mining Ores & Bars";
$wordings['enUS']['bankitem_18'] = "Valueable Items";
$wordings['enUS']['bankitem_19'] = "Written Works";
$wordings['enUS']['bankitem_20'] = "Zul'Gurub Loot"; // Zul'Gurub
$wordings['enUS']['bankitem_21'] = "Ahn'Quiraj Loot"; // Ahn'Quiraj
$wordings['enUS']['bankitem_22'] = "Molten Core Loot"; // Molten Core
$wordings['enUS']['bankitem_23'] = "Cooking Materials & Food";
$wordings['enUS']['bankitem_24'] = "Scales & Dragonscales";
$wordings['enUS']['bankitem_25'] = "Containers";
$wordings['enUS']['bankitem_26'] = "Elemental Loot";
$wordings['enUS']['bankitem_27'] = "Fishing & Supplies";
$wordings['enUS']['bankitem_28'] = "Quest Items";
$wordings['enUS']['bankitem_29'] = "Miscellaneous Items";
$wordings['enUS']['bankitem_30'] = "Scrolls";
$wordings['enUS']['bankitem_31'] = "Engineering Items";
$wordings['enUS']["bankitem_32"] = "Darkmoon Faire Items";


/************************ Array(enUS)**************************************/
$wordings['omit']['enUS'] =
array("Soulbound", "Plain Letter");

$wordings['armor']['enUS'] =
array("Head", "Neck", "Shoulder", "Back", "Chest", "Wrist", "Hands", "Waist",
      "Legs", "Feet", "Finger", "Shield");

$wordings['weapon']['enUS'] =
array("damage per second", "Off-hand", "Off Hand");

$wordings['firstaid']['enUS'] =
array("Small Venom Sac", "Large Venom Sac", "Huge Venom Sac", "Requires First Aid", "Powerful Anti-Venom", "Strong Anti-Venom",
"Anti-Venom");

$wordings['cloth']['enUS'] =
array("Linen Cloth", "Wool Cloth", "Silk Cloth", "Mageweave Cloth",
"Runecloth", "Felcloth", "Mooncloth", "Bolt of");

$wordings['leatherwork']['enUS'] =
array("Requires Leatherworking");

$wordings['tailor']['enUS'] =
array("Requires Tailoring");

$wordings['schematic']['enUS'] =
array("Schematic");

$wordings['plan']['enUS'] =
array("Plans");

$wordings['formula']['enUS'] =
array("Formula");

$wordings['recipe']['enUS'] =
array("Requires Alchemy");

$wordings['herbs']['enUS'] =
array("Heart of the Wild", "Peacebloom", "Silverleaf", "Mageroyal", "Sungrass","Firebloom",
"Earthroot", "Briarthorn", "Swiftthistle", "Stranglekelp", "Bruiseweed",
"Wild Steelbloom", "Kingsblood", "Fadeleaf", "Goldthorn", "Wintersbite",
"Khadgar's Whisker", "Purple Lotus", "Arthas' Tears", "Blindweed",
"Ghost Mushroom", "Gromsblood","Dreamfoil", "Plaguebloom", "Golden Sansam",
"Icecap", "Mountain Silversage", "Black Lotus", "Liferoot", "Wildvine",
"Grave Moss", "Bloodvine");

$wordings['potion']['enUS'] =
array("Potion", "Elixir", "Frost Oil", "Fire Oil", "Blackmouth Oil", "Stonescale Oil",
"Oil of", "Volatile Rum", "Flask of the Titans", "Flask of Petrification",
"Flask of Supreme Power", "Flask of Chromatic Resistance",
"Flask of Distilled Wisdom", "Wizard Oil", "Mana Oil", "Stonescale Eel",
"Firefin Snapper", "Oily Blackmouth", "Gift of Arthas", "Winterfall Firewater", "Jungle Remedy");

$wordings['container']['enUS'] =
array("Pack", "Bag", "Sack", "Backpack", "Pouch", "Knapsack", "Quiver");

$wordings['scale']['enUS'] =
array("Scale", "Dragonscale");

$wordings['elemental']['enUS'] =
array("Essence of", "Living Essence", "Elemental Earth", "Elemental Water",
"Elemental Fire", "Elemental Air", "Heart of Fire", "Globe of Water",
"Core of Earth", "Breath of Wind", "Ichor of Undeath");

$wordings['food']['enUS'] =
array("Raptor Flesh", "Mithril Head Trout", "Must remain seated","Requires Cooking",
"Meat", "Spices", "Egg", "Liver", "Small Spider Leg", "Gooey", "Wing", "Scorpid Stinger",
"Boar Intestines", "Crawler Claw", "Dig Rat", "Murloc Fin", "Thunder Lizard Tail",
"Flank", "Runn Tum", "Tenderloin", "Raptor Flesh");

$wordings['write']['enUS'] =
array("Holy Bologna", "Harnessing Shadows", "Libram", "Tome", "Volume", "Book", "Expert", "Codex",
"Greatest Race", "Frost Shock and", "Foror's Compendium of Dragon Slaying", "The Light and How",
"The Arcanist's Cookbook", "Garona: A Study on Stealth", "The Emerald Dream");

$wordings['leather']['enUS'] =
array("Raptor Hide", "Light Leather", "Medium Leather", "Heavy Leather", "Thick Leather",
"Rugged Leather", "Light Hide", "Thin Kodo Leather", "Cured Light Hide",
"Medium Hide", "Cured Medium Hide", "Heavy Hide", "Cured Heavy Hide",
"Thick Hide", "Shadowcat Hide", "Cured Thick Hide", "Rugged Hide",
"Cured Rugged Hide", "Chimera Leather", "Frostsaber Leather",
"Warbear Leather", "Devilsaur Leather", "Enchanted Leather", "Ruined Pelt",
"Ruined Leather", "Wolfhide");

$wordings['gems']['enUS'] =
array("Malachite", "Tigerseye", "Shadowgem", "Small Lustrous Pearl",
"Lesser Moonstone", "Iridescent Pearl", "Moss Agate", "Citrine", "Jade",
"Aquamarine", "Black Pearl", "Star Ruby","Blue Sapphire", "Red Power Crystal",
"Blue Power Crystal","Yellow Power Crystal", "Green Power Crystal",
"Huge Emerald", "Large Opal", "Azerothian Diamond", "Blood of the Mountain",
"Guardian Stone", "Souldarite", "Black Diamond", "Blue Pearl",
"Pristine Black Diamond", "Arcane Crystal", "Golden Pearl", "Black Vitriol");

$wordings['val']['enUS'] =
array("Righteous Orb","Incendosaur Scale","Pristine Hide of the Beast",
"Elemental Flux", "Dark Iron Residue", "Larval Acid", "Dark Rune");

$wordings['metal']['enUS'] =
array("Bar", "Ore", "Coal", "Rough Stone", "Coarse Stone", "Solid Stone",
"Dense Stone", "Grinding Stone", "Heavy Stone");

$wordings['enchant']['enUS'] =
array("Strange Dust", "Dream Dust", "Magic Essence", "Shard", "Astral Essence",
"Vision Dust", "Soul Dust", "Mystic Essence", "Nether Essence",
"Eternal Essence", "Illusion Dust", "Nexus Crystal", "Copper Rod",
"Truesilver Rod", "Arcanite Rod", "Silver Rod", "Golden Rod");

$wordings['cards']['enUS'] =
array("of Portals", "of Beasts", "of Elementals", "of Warlords", "Deck");

$wordings['zg']['enUS'] =
array("Hakkari Bijou", "Zulian Coin", "Witherbark Coin","Vilebranch Coin",
"Skullsplitter Coin", "Sandfury Coin", "Razzashi Coin", "Hakkari Coin",
"Gurubashi Coin", "Bloodscalp Coin", "Primal Hakkari", "Voodoo Doll",
"Primal Bat Leather", "Primal Tiger Leather", "Massive Mojo", "Powerful Mojo",
"Big Mojo", "Troll Mojo", "Zulian Mudskunk");

$wordings['aq']['enUS'] =
array("Idol of", "Scarab", "Idol", "Silithid");

$wordings['mc']['enUS'] =
array("Lava Core", "Fiery Core", "Core Leather", "Sulfuron Ingot");

$wordings['fish']['enUS'] =
array("Requires Fishing", "Fishing Pole", "Nat Pagle's", "Lucky Fishing Hat",
"Nightcrawlers", "Bright Baubles", "Shiny Bauble", "Fishing Line",
"Flesh Eating Worm");

$wordings['quest']['enUS'] =
array("Quest Item", "Morrowgrain", "Blood Shard", "Abyssal Signet",
"Burning Charm", "Thundering Charm", "Cresting Charm", "Green Hills",
"Encrypted Twilight Text", "Shredder Operating", "Outhouse Key",
"Troll Tribal Necklace", "Basilisk Brain", "Blasted Boar Lung",
"Snickerfang Jowl", "Vulture Gizzard", "Wastewander Water Pouch",
"Postbox Key", "Gorilla Fang", "Map Fragment", "Relic Coffer",
"Un'Goro Soil");

$wordings['scroll']['enUS'] =
array("Scroll");

$wordings['engineer']['enUS'] =
array("Requires Engineering", "Fused Wiring", "Thorium Widget","Gyrochronatom",
"Bronze Framework", "Iron Strut", "Truesilver Transformer", "Copper Bolts",
"Whirring Bronze Gizmo", "Gold Power Core", "Mithril Casing", "Tube",
"Blasting Powder", "Unstable Trigger", "Goblin Rocket Fuel","Mithril Cylinder",
"Scope");

$wordings['darkmoon']['enUS'] =
array("Small Furry Paw", "Torn Bear Pelt", "Soft Bushy Tail", "Vibrant Plume",
"Evil Bat Eye", "Glowing Scorpid Blood", "Beasts Deck", "Elementals Deck",
"Portals Deck", "Warlords Deck", "Ace of", "Two of", "Three of",
"Four of", "Five of", "Six of", "Seven of", "Eight of");


/********************** config (enUS) ******************************/
$wordings['enUS']['admin']['cgb_conf'] = 'Display Configuration|On this page you can configure how data is shown';
$wordings['enUS']['admin']['row_columns'] = 'Columns|Number of columns per category row';
$wordings['enUS']['admin']['color_border'] = 'Color border|Displays a border around the item icon designating its rarity';
$wordings['enUS']['admin']['show_empty'] = 'Show Empty|Hides categories with no items';
$wordings['enUS']['admin']['searchtype'] = 'Item Link|Site to serch for item when clicked';





/********************** Header (deDE) ******************************/
// The header name for each category that will appear
$wordings['deDE']['bankitem_1'] =  "Rüstungen";
$wordings['deDE']['bankitem_2'] =  "Waffen";
$wordings['deDE']['bankitem_3'] =  "Lederverarbeitungs Muster";
$wordings['deDE']['bankitem_4'] =  "Schneiderei Muster";
$wordings['deDE']['bankitem_5'] =  "Schmiedekunst Pläne";
$wordings['deDE']['bankitem_6'] =  "Alchemie Rezepte";
$wordings['deDE']['bankitem_7'] =  "Verzauberungsformeln";
$wordings['deDE']['bankitem_8'] =  "Ingenieur Baupläne";
$wordings['deDE']['bankitem_9'] =  "Erste Hilfe Gegenst&auml;nde";
$wordings['deDE']['bankitem_10'] = "Schneiderei Material";
$wordings['deDE']['bankitem_11'] = "Pflanzen";
$wordings['deDE']['bankitem_12'] = "Tränke & Elixiere";
$wordings['deDE']['bankitem_13'] = "Dunkelmond Karten und Sets";
$wordings['deDE']['bankitem_14'] = "Leder";
$wordings['deDE']['bankitem_15'] = "Edelsteine & Juwelen";
$wordings['deDE']['bankitem_16'] = "Verzauberungsmaterial";
$wordings['deDE']['bankitem_17'] = "Bergbau Erze & Barren";
$wordings['deDE']['bankitem_18'] = "Wertvolle Gegenstände";
$wordings['deDE']['bankitem_19'] = "Lernbare Bücher";
$wordings['deDE']['bankitem_20'] = "Zul'Gurub Loot"; // Zul'Gurub
$wordings['deDE']['bankitem_21'] = "Ahn'Quiraj Loot"; // Ahn'Quiraj
$wordings['deDE']['bankitem_22'] = "Molten Core Loot"; // Molten Core
$wordings['deDE']['bankitem_23'] = "Material zum Kochen & Fertigessen";
$wordings['deDE']['bankitem_24'] = "Schuppen und Drachenschuppen";
$wordings['deDE']['bankitem_25'] = "Behälter";
$wordings['deDE']['bankitem_26'] = "Essenzen, Sekrete und Elementare";
$wordings['deDE']['bankitem_27'] = "Fische & Zubehör";
$wordings['deDE']['bankitem_28'] = "Quest Gegenstände";
$wordings['deDE']['bankitem_29'] = "Verschiedene Gegenstände";
$wordings['deDE']['bankitem_30'] = "Schriftrollen";
$wordings['deDE']['bankitem_31'] = "Ingenieur Bauteile";
$wordings["deDE"]["bankitem_32"] = "Dunkelmond Basar Gegenst&auml;nde";

/************************ Header ende ******************************/

/************************ Array (deDE)******************************/
// �ö   Ԡ= Ö
// �   ڠ=  M
// ➽ ä    =
// ݠ= ß

$wordings['omit']['deDE'] =
array("Seelengebunden");

$wordings['armor']['deDE'] =
array("Kopf", "Hals", "Schulter", "Rücken", "Brust", "Handgelenke","Hände",
"Taille", "Beine", "Füße", "Finger", "Schild");

$wordings['weapon']['deDE'] =
array("Einhändig", "Zweihändig", "Schusswaffe", "Armbrust", "Zauberstab",
"Projektil", "Waffenhand", "Schildhand", "Nebenhand", "Crossbow");

$wordings['firstaid']['deDE'] =
array("Schwerer Leinenverband", "Leinenverband", "Schwerer Wollverband",
"Wollverband", "Schwerer Seidenverband", "Seidenverband",
"Schwerer Magiestoffverband", "Magiestoffverband",
"Schwerer Runenstoffverband", "Runenstoffverband", "Mächtiges Gegengift",
"Starkes Gegengift", "Gegengift");

$wordings['cloth']['deDE'] =
array("Leinenstoff", "Wollstoff", "Seidenstoff", "Magiestoff", "Runenstoff",
"Teufelsstoff", "Mondstoff", "Leinenstoffballen", "Wollstoffballen",
"Seidenstoffballen", "Magiestoffballen", "Runenstoffballen",
"Teufelsstoffballen", "Mondstoffballen", "Schattenseide", "Spinnenseide",
"Waldweberspinnenseide", "Makellose Spinnenseide", "Eisenweberseide",
"Dicke Spinnenseide");

$wordings['leatherwork']['deDE'] =
array("Benötigt Lederverarbeitung");

$wordings['tailor']['deDE'] =
array("Benötigt Schneiderei");

$wordings['schematic']['deDE'] =
array("Benötigt Ingenieurskunst");

$wordings['plan']['deDE'] =
array("Pläne","Benötigt Schmiedekunst", "Hochentwickelte");

$wordings['formula']['deDE'] =
array("Benötigt Verzauberkunst");

$wordings['recipe']['deDE'] =
array("Benötigt Alchemy", "Steinschuppenaal", "Feuerflossenschnapper","Öliges Schwarzmaul");

$wordings['herbs']['deDE'] =
array("Friedensblume", "Silberblatt", "Maguskönigskraut", "Sonnengras",
"Feuerblüte", "Erdwurzel", "Wilddornrose", "Flitzdistel", "Würgetang",
"Beulengras", "Wildstahlblume", "Königsblut", "Blassblatt", "Golddorn",
"Winterbiss", "Khadgars Schnurrbart", "Lila Lotus", "Arthas' Tränen",
"Blindkraut", "Geisterpilz", "Gromsblut", "Traumblatt", "Pestblüte",
"Goldener Sansam", "Eiskappe", "Bergsilberweisling", "Schwarzer Lotus",
"Lebenswurz", "Wildranke", "Grabmoos", "Blutrebe");

$wordings['potion']['deDE'] =
array("Manatrank", "Heiltrank", "Elixier", "Feueröl", "Schwarzmaulöl", "Arthas' Gabe");

$wordings['container']['deDE'] =
array("Gesellenrucksack", "Rucksack", "Deviathautpack", "Seidenpack",
"Reiserucksack", "Reisetasche", "Sack", "Jagdmunitionssack", "Rupfensack",
"Wandererknappsack", "Gnollbalgsack", "Panterbalgsack", "Forscherknappsack",
"Taupelzsack", "Dämonenbalgsack", "Knapsack", "Ledermunitionsbeutel",
"Geschossbeutel", "Seelenbeutel", "Munitionsbeutel");

$wordings['scale']['deDE'] =
array("Grünwelpenschuppe", "Pterrordaxschuppe", "Dimetrodonschuppe",
"Schildkrötenschuppe", "Deviatschuppe", "Schwarzwelpenschuppe",
"Rotwelpenschuppe", "Raptorschuppe", "Basiliskenschuppe", "Kodoschuppe",
"Prismabasiliskenschuppe", "Säbelflossenschuppe", "Welpenbauchschuppe",
"Vilefinschuppe", "Frenzyschuppe", "Fischschuppe", "Traumschuppe",
"Krokiliskenschuppe", "Siechdrachenschuppe", "Drachenschuppe",
"Großdrachenschuppe", "Murlocschuppe", "Schuppe", "Skorpidschuppe",
"Nagaschuppe");

$wordings['elemental']['deDE'] =
array("Essenz der reinen Flamme", "Essenz des Schmerzes", "Essenz von Hakkar",
"Essenz der Elemente", "Nightlash-Essenz", "Essenz des Feuerfürsten",
"Essenz der Verbannung", "Essenz von Xandivious", "Essenz des Feuerfürsten",
"Essenz der Erde", "Essenz des Untodes", "Essenz des Wassers",
"Essenz des Feuers", "Essenz des Eranikus", "Essenz der Luft",
"Essenz der Pein", "Essenz des Lebens", "Elementarerde", "Elementarwasser",
"Elementarfeuer", "Elementarluft", "Herz des Feuers", "Kugel des Wassers",
"Erdenkern", "Odem des Windes", "Sekret des Untodes", "Herz der Wildnis");

$wordings['write']['deDE'] =
array("Buchband", "Foliant", "Band", "Buch", "Leitfaden", "Expert", "Kodex", "Rolle");

$wordings['write']['deDE'] =
array("Buchband", "Foliant", "Buch", "Leitfaden", "Expert", "Kodex", "Rolle");

$wordings['leather']['deDE'] =
array("Leichtes Leder", "Mittleres Leder", "Schweres Leder", "Dickes Leder",
"Unverwüstliches Leder", "Leichter Balg",	"Dünnes Kodoleder",
"Geschmeidiger leichter Balg", "Mittlerer Balg","Geschmeidiger mittlerer Balg",
"Schwerer Balg", "Geschmeidiger schwerer Balg", "Dicker Balg",
"Schattenkatzenbalg", "Geschmeidiger dicker Balg", "Unverwüstlicher Balg",
"Geschmeidiger unverwüstlicher Balg", "Schimärenleder", "Frostsäblerleder",
"Kriegsbärenleder", "Teufelssaurierleder", "Tiefsteinsalz",
"Verzaubertes Leder", "Verdorbener Pelz", "Verdorbene Lederfetzen");

$wordings['gems']['deDE'] =
array("Malachit", "Tigerauge", "Schattenedelstein", "Perle",
"Geringer Mondstein", "Moosachat", "Citrin", "Jade", "Aquamarin", "Sternrubin",
"Blauer Saphir", "Machtkristall", "Gewaltiger Smaragd", "Großer Opal",
"Diamant", "Blut des Berges", "Wächterstein", "Soldarit", "Arkankristall",
"Schwarzes Vitriol");

$wordings['val']['deDE'] =
array("Rechtschaffene Kugel", "Incendosaurierschuppe",
"Makelloser Balg der Bestie", "Elementarfluxus");

$wordings['cards']['deDE'] =
array("Portal", "Bestien", "Elementar", "Kriegsfürst", "Portalkartenset",
"Elementarkartenset", "Bestienkartenset", "Kriegsfürstenkartenset");

$wordings['metal']['deDE'] =
array("Kupferbarren", "Kupfererz", "Zinnbarren", "Zinnerz", "Bronzebarren",
"Dunkeleisenbarren", "Dunkeleisenerz", "Stahlbarren", "Mithrilbarren", "
Mithrilerz", "Echtsilberbarren", "Echtsilbererz", "Silberbarren", "Silbererz",
"Goldbarren", "Golderz", "Verzauberter Thoriumbarren", "Thoriumerz",
"Elementium-Barren", "Elementium-Erz", "Arkanitbarren", "Thoriumbarren",
"Eisenbarren", "Eisenerz", "Kohle", "Rauer Stein", "Grober Stein",
"Robuster Stein", "Verdichteter Stein", "Schleifstein", "Schwerer Stein");

$wordings['enchant']['deDE'] =
array("Seltsamer Staub", "Traumstaub", "Magie-Essenz", "Splitter",
"Astralessenz", "Visionenstaub", "Seelenstaub", "Mystikeressenz",
"Netheressenz", "ewige Essenz", "Illusionsstaub", "Nexuskristall",
"Kupferrute", "Echtsilberrute", "Arkanitrute", "Silberrute", "Goldrute");

$wordings['quest']['deDE'] =
array("Quest", "Questgegenstand", "Morgenkorn", "Blutsplitter");

$wordings['zg']['deDE'] =
array("Schmuckstück der Hakkari", "Zulianische Münze",
"Münze der Witherbark", "Münze der Vilebranch", "Münze der Skullsplitter",
"Münze der Sandfury", "Münze der Razzashi", "Münze der Hakkari",
"Münze der Gurubashi", "Münze der Bloodscalp",
"Urzeitliche Hakkaribindungen", "Voodoo-Puppe", "Urzeitliches Fledermausleder",
"Urzeitliches Tigerleder", "Urzeitlicher Hakkariwappenrock",
"Urzeitliche Hakkarischärpe", "Urzeitliche Hakkariarmsplinte",
"Urzeitliche Hakkaristütze", "Urzeitliche Aegis der Hakkari",
"Urzeitlicher Hakkarikosak", "Urzeitlicher Hakkarischal",
"Urzeitlicher Hakkarigurt");

$wordings['aq']['deDE'] =
array("Götze des", "Kristallskarabäus", "Goldskarabäus", "Silberskarabäus",
"Elfenbeinskarabäus", "Knochenskarabäus", "Bronzeskarabäus",
"Tonskarabäus", "Steinskarabäus", "Skarabäustasche", "Skarabäuskasten",
"Skarabäusschale", "Skarabäus", "Skarabäusplattenhelm");

$wordings['mc']['deDE'] =
array("Lavakern", "Feuerkern", "Kernleder", "Sulfuron-Block");

$wordings['fish']['deDE'] =
array("Goblin-Angelrute","Arkanitangel","Große Eisenangel","Angel",
"Goblin-Angelrute","Zwergische Angelrute", "Sonnenschuppenlachs",
"Weißschuppenlachs","Lachs", "Panzerfisch", "Wels", "Stoppelfühlerwels",
"Sägezahnschnapperklaue","Matschschnapper","Superschnapper FX", "Zitteraal",
"Rotkiemen", "Roher", "Machtfisch", "Dunkelklauenhummer", "Hummer",
"Feralas Ahi", "Nebelschilf-Mahi-Mahi", "Blauwimpel", "Dunkelküstenbarsch",
"Zackenbarsch", "Sar'theris-Barsch", "Mithrilkopfforelle", "Forelle",
"Matschstinker", "Leckerfisch", "Weisenfisch", "Steinschuppenkabeljau",
"Regenbogenflossenthunfisch", "Kleinfisch", "Lochfrenzy", "Tüpfelgelbschwanz",
"Streifengelbschwanz", "Winterkalmar", "Felsnischenstarkfisch",
"Dezianischer Königinnenfisch", "Flitzerfisch", "Glitschhautmakrele",
"Muscheln","Muschelfleisch","Muschel","RiesenmuschelfleischMuscheln",
"Großmaulmuschel", "Stahlschuppenknautscher", "Nat Pagles",
"Glücksangelhut", "Nachtkriecher", "Helle Schmuckstücke",
"Glänzendes Schmuckstück", "angelschnur", "Fleischfressender Wurm",
"Matschstinkerköder");

$wordings['food']['deDE'] =
array("Aaswurmfleisch","Spinnenfleisch","Tigerfleisch","Fleisch",
"Schildkrötenfleisch", "Bärenfleisch","Kriecherfleisch","Eberfleisch",
"Wolfsfleisch","Kondorfleisch","Geierfleisch",	"Fleischpastete",
"Schreiterfleisch","Kodofleisch","Früchtepastete","Kojotenfleisch",
"Dreschadonfleisch", "Hirschfleisch","Löwenfleisch","Sandwurmfleisch",
"Fledermausflügel","Krebsfleisch","Beinfleisch", "Splitterzahnfleisch",
"Frostsäblerfleisch","Ebenenschreiter-Fleisch","Trockenfleisch",
"Muschelfleisch", "Fleischschenkel","Krokiliskenfleisch",
"Großbärenfleisch","Riesenei","Hühner-Ei","Raptorei","Eierflip", "Ei",
"Winterkalmar","Skorpidstachel","Ebergedärme","Kriecherklaue",
"Eiskalte Milch","Grubenratte", "Murlocflosse", "Donnerechsenschwanz",
"Kleines Spinnenbein","Roher","Eberrippchen","Apfel","Goldrindenapfel",
"Kaktusapfel", "Geiferzahnleber","Groddoc-Leber","Eisenfellleber","Kodoleber",
"Klebriger Spinnenkuchen", "Klebriges Spinnenbein","Wolfflanke","Bärenflanke",
"Sägezahnflanke","Silbermähnenpirscherflanke", "Kampfeberflanke",
"Runn Tum Knolle","Chimaeroklenden", "Gewürze", "sitzen bleiben");

$wordings['engineer']['deDE'] =
array("Benötigt Ingenieurskunst", "Gyrochronatom", "Bronzegerüst", "Echtsilberumwandler",
"Verschmorte Verkabelung", "Thoriumapparat", "Eisenstrebe", "Eine Hand voll Kupferbolzen",
"Surrendes bronzenes Dingsda", "Goldkraftkern", "Mithrilgehäuse", "rohr", "Sprengpulver",
"Instabiler Auslöser", "Goblin-Raketentreibstoff", "Veredelter Mithrilzylinder",
"Zielfernrohr", "röhre");

$wordings['scroll']['deDE'] =
array("Schriftrolle");

$wordings['darkmoon']['deDE'] =
array("Small Furry Paw", "Torn Bear Pelt", "Soft Bushy Tail", "Vibrant Plume",
"Evil Bat Eye", "Glowing Scorpid Blood", "Beasts Deck", "Elementals Deck",
"Portals Deck", "Warlords Deck", "Ace of", "Two of", "Three of",
"Four of", "Five of", "Six of", "Seven of", "Eight of");


/********************** config (deDE) ******************************/
$wordings['deDE']['admin']['cgb_conf'] = 'Display Configuration|On this page you can configure how data is shown';
$wordings['deDE']['admin']['row_columns'] = 'Columns|Number of columns per category row';
$wordings['deDE']['admin']['color_border'] = 'Color border|Displays a border around the item icon designating its rarity';
$wordings['deDE']['admin']['show_empty'] = 'Show Empty|Hides categories with no items';
$wordings['deDE']['admin']['searchtype'] = 'Item Link|Site to serch for item when clicked';


/*************************** Header (frFR) ********************************/
// The header name for each category that will appear
$wordings['frFR']['bankitem_1'] =  "Armure";
$wordings['frFR']['bankitem_2'] =  "Armes";
$wordings['frFR']['bankitem_3'] =  "Patron de Travail du Cuir";
$wordings['frFR']['bankitem_4'] =  "Patron de couture";
$wordings['frFR']['bankitem_5'] =  "Plan de forge";
$wordings['frFR']['bankitem_6'] =  "Recette d'Alchimie";
$wordings['frFR']['bankitem_7'] =  "Formule d'Enchantement";
$wordings['frFR']['bankitem_8'] =  "Schéma d'ingénieur";
$wordings['frFR']['bankitem_9'] =  "Objets de secourisme";
$wordings['frFR']['bankitem_10'] = "Matériel de couture";
$wordings['frFR']['bankitem_11'] = "Herbes";
$wordings['frFR']['bankitem_12'] = "Composants d'Alchimie, Huiles &amp; Potions";
$wordings['frFR']['bankitem_13'] = "Cartes de Sombrelune";
$wordings['frFR']['bankitem_14'] = "Cuirs";
$wordings['frFR']['bankitem_15'] = "Gemmes &amp; pierres précieuses";
$wordings['frFR']['bankitem_16'] = "Matériel d'enchantement";
$wordings['frFR']['bankitem_17'] = "Minerais et Barres de métaux";
$wordings['frFR']['bankitem_18'] = "Objets Précieux";
$wordings['frFR']['bankitem_19'] = "Ouvrages";
$wordings['frFR']['bankitem_20'] = "Loot Zul'Gurub"; // Zul'Gurub
$wordings['frFR']['bankitem_21'] = "Loot Ahn'Quiraj"; // Ahn'Quiraj
$wordings['frFR']['bankitem_22'] = "Loot Molten Core"; // Molten Core
$wordings['frFR']['bankitem_23'] = "Matériel de Cuisine &amp; Nourriture";
$wordings['frFR']['bankitem_24'] = "Ecailles";
$wordings['frFR']['bankitem_25'] = "Conteneurs";
$wordings['frFR']['bankitem_26'] = "Loot Elementaire";
$wordings['frFR']['bankitem_27'] = "Pêche & Accessoires";
$wordings['frFR']['bankitem_28'] = "Objets de quêtes";
$wordings['frFR']['bankitem_29'] = "Objets Divers";
$wordings['frFR']['bankitem_30'] = "Parchemins";
$wordings['frFR']['bankitem_31'] = "Objets d'Ingénierie";
$wordings['frFR']["bankitem_32"] = "Objets de la Foire de Sombrelune";


/************************ Array(frFR)**************************************/
$wordings['omit']['frFR'] =
array("Pierre de foyer", "Lettre simple");

$wordings['armor']['frFR'] =
array("Tête", "Cou", "Epaule", "Dos", "Torse", "Poignets", "Taille",
"Jambes", "Pieds", "Doigt", "Bouclier",
// More frFR translations
"Mains    Plaques", "Mains    Mailles", "Mains    Cuir", "Gantelets", "Gants");

$wordings['weapon']['frFR'] =
array( "dégâts par seconde", "À une main", "Main gauche",
// More frFR translations
"Arc", "Arme à feu", "Masse", "Epée", "Hache", "Tenu(e) en main gauche", "Arme d'hast",
"Marteau", "Dague", "Sceptre", "Orbe de Mistmantle");

$wordings['firstaid']['frFR'] =
array("Enorme glande à venin", "Grande glande à venin", "Petite glande à venin", "Anti-venin",
"Anti-venin puissant", "Secourisme");

$wordings['cloth']['frFR'] =
array("Etoffe de lin", "Etoffe de laine", "Etoffe de soie", "Etoffe de tisse-mage",
"Etoffe runique", "Etoffe lunaire", "Gangrétoffe",
"Rouleau d'étoffe runique", "Rouleau d'étoffe de soie", "Rouleau de tisse-mage",
"Rouleau d'étoffe de laine", "Rouleau d'étoffe en lin",
// More frFR translations
"Soie d'", "Soie des", "Teinture"
);

$wordings['leatherwork']['frFR'] =
array("Travail du cuir");

$wordings['tailor']['frFR'] =
array("Couture", "Patron");

$wordings['schematic']['frFR'] =
array("Ingénierie");

$wordings['plan']['frFR'] =
array("Plans");

$wordings['formula']['frFR'] =
array("Formule : Enchantement");

$wordings['recipe']['frFR'] =
array("Alchimie");

$wordings['herbs']['frFR'] =
array("Aciérite sauvage", "Terrestrine","Soleillette","Aveuglette", "Chardonnier",
"Feuillargent", "Eglantine", "Etouffante", "Mage royal", "Hivernale", "Doulourante",
"Coeur de fauve", "Pacifique", "Moustache de Khadgar", "Lotus Pourpre",  "Vietérule",
"Pâlerette", "Sauge-argent des montagnes", "Vignesang", "Lotus Noir", "Larmes d'Arthas",
"Champignon fantôme", "Dorépine", "Gromsang", "Sansam doré", "Calot de glace",
"Fleur de peste", "Feuillerêve", "Fibre d'aurore", "Fleur de feu", "Sang-royal", "Tombeline",
"Sauvageonne");

$wordings['potion']['frFR'] =
array("Potion", "Elixir", "Huile de froid", "Huile de feu", "Huile de bouche-noire", "Huile de pierre-écaille",
"Huile de", "Rhum explosif", "Flacon des Titans", "Flacon de pétrification",
"Flacon de pouvoir suprême", "Flacon de résistance chromatique",
"Flacon de sagesse distillée", "Huile de sorcier", "Huile de mana", "Anguille pierre-écaille",
"Lutjan de nagefeu", "Bouche-noire huileux", "Don d'Arthas", "Eau de feu des Tombe-hiver", "Remède tropical",
// More frFR translations
"Huile d'");

$wordings['container']['frFR'] =
array("Sac", "Conteneur", "Sac � dos", "Sacoche", "Giberne", "Carquois",
// More frFR translations
"Grand sac", "Sac d'âme");

$wordings['scale']['frFR'] =
array("Ecaille", "Ecailles", "Ecaille de dragon",
// More frFR translations
"Rêvécaille");

$wordings['elemental']['frFR'] =
array("Essence de", "Essence d'", "Air élémentaire", "Coeur de feu", "Globe d'eau",
"Noyau de terre", "Souffle de vent", "Ichor de non-mort",
"Essence de vie", "Terre élémentaire", "Eau élémentaire",
"Feu élémentaire");

$wordings['food']['frFR'] =
array("Chair de raptor", "Truite tête-mithril", "Vous devez rester assis", "Cuisine",
"Viande", "Epice", "Oeuf", "Petit oeuf", "Foie", "Petite patte d'araignée", "Aile", "Dard de scorpide",
"Tripes de sanglier", "Pince de clampant", "Aileron de murloc", "Queue de lézard-tonnerre",
"Côtes", "Filet",
// ???
"Dig Rat",
// More frFR translations
"Oeil de murloc", "Chair", "Chair de palourde", "Barracuda luisant", "Ichor d'araignée", "Chair de clampant",
"Chair de ver des sables", "Pomme rouge brillante", "Calmar", "Racine de Courante",
"Poisson déviant", "Saumon", "Furie du loch", "sagerelle", "Rouget", "Chair tendre de Furie",
"Goujon", "Chair croustillante d'araignée", "Jaune-queue tacheté", "Lutjan �  longue mâchoire",
"Maquereau ombré", "Morue rochécaille", "Perche estivale", "Poisson-chat moustachu",
"Filet de chimaerok", "Homard Pince-noire", "Lutjan nagenuit", "Grand barracuda", "Chardonnier",
"Patte d'araignée fondante");

$wordings['write']['frFR'] =
array("Sainte Bolognaise", "Contrôler les ombres", "Libram", "Tome", "Volume", "Livre", "Expert", "Codex",
"La plus grande race de chasseurs", "Le Horion de givre et vous", "Le guide Dagharn du tueur de dragons", "La lumière et comment l'altérer",
"Les recettes de l�arcaniste", "Garona : une étude en discrétion et en trahison", "Le Rêve d'Emeraude",
// More frFR translations
"Grimoire", "Guide", "Manuel", "Recueil", "Tablette"
);

$wordings['leather']['frFR'] =
array(
"Peau de raptor", "Cuir léger", "Cuir moyen", "Cuir lourd", "Cuir épais",
"Cuir robuste", "Peau légère", "Cuir fin de kodo", "Peau légère traitée",
"Peau moyenne", "Peau moyenne traitée", "Peau lourde", "Peau lourde traitée",
"Peau épaisse", "Peau épaisse traitée", "Peau de félin des ombres",
"Peau robuste", "Peau robuste traitée", "Cuir de chimère", "Cuir de sabre-de-givre",
"Cuir de diablosaure", "Cuir enchanté", "Peau de Loup épaisse", "Cuir d'ours de guerre",
// ???
"Ruined Pelt", "Ruined Leather",
// More frFR translations
"Peau de vieux kodo", "Plume noire", "Plumacier", "Flacon de grand mojo", "Flacon de mojo",
"Sel de Fonderoc", "Cuir du Magma", "Cuir de tigre primordial", "Cuir de chauve-souris primordiale",
"Carapace" );

$wordings['gems']['frFR'] =
array("Malachite", "Oeil de tigre", "Oeil ténébreux", "Petite perle satinée",
"Pierre de lune inférieure", "Perle iridescente", "Agate", "Agate enchantée", "Citrine", "Jade",
"Aigue-Marine", "Perle noire", "Rubis étoilé", "Saphir bleu", "Cristal de puissance rouge",
"Cristal de puissance bleu", "Cristal de puissance jaune", "Cristal de puissance vert",
"Enorme émeraude", "Grande opale", "Diamant d'Azeroth", "Sang de la montagne",
"Pierre de gardien", "Âmarite", "Diamant noir", "Perle bleue",
"Diamant noir parfait", "Cristal des arcanes", "Perle dorée", "Vitriol noir");

$wordings['val']['frFR'] =
array("Orbe de piété","Ecaille d'Incendosaure","Peau parfaite de la Bête",
"Catalyseur élémentaire", "Résidu de sombrefer", "Acide de larve", "Rune ténébreuse");

$wordings['metal']['frFR'] =
array("Barre", "Minerai", "Charbon", "Pierre solide", "Pierre grossière", "Pierre lourde",
"Pierre dense", "Pierre �  aiguiser", "Pierre brute");

$wordings['enchant']['frFR'] =
array("Poussière étrange", "Poussière d'âme", "Poussière de rêve", "Poussière de vision",
"Poussière magique", "Essence mystique", "Poudre d'Illusion", "Cristal de nexus",
"Gros éclat", "Essence astrale", "Essence éternelle", "Essence de magie",
"Essence du néant", "Petit éclat", "Grand éclat brillant",
"Bâtonnet en or", "Bâtonnet runique en arcanite", "Bâtonnet runique en argent",
"Bâtonnet runique en cuivre", "Bâtonnet runique en or", "Bâtonnet runique en vrai-argent");

$wordings['cards']['frFR'] =
array("de Portails", "de Fauves", "d'Elémentaires", "de Seigneurs de Guerre", "Suite");

$wordings['zg']['frFR'] =
array("Bijou Hakkari", "Pièce zulienne", "Pièce Witherbark", "Pièce Vilebranch",
"Pièce Skullsplitter", "Pièce Sandfury", "Pièce Razzashi", "Pièce hakkari",
"Pièce Gurubashi", "Pièce Bloodscalp", "primordial hakkari", "primordiale hakkari",
"primordiales hakkari", "primordiaux hakkari", "Poupée vaudou",
"Cuir de chauve-souris primordiale", "Cuir de tigre primordial", "Mojo écrasant", "Mojo puissant",
"Grand Mojo", "Troll Mojo", "Puant de vase zulien");

$wordings['aq']['frFR'] =
array("Idole d", "Scarabée", "Idole", "Silithid");

$wordings['mc']['frFR'] =
array("Noyau de lave", "Noyau de feu", "Cuir du Magma", "Lingot de sulfuron");

$wordings['fish']['frFR'] =
array("Pêche", "Canne �  pêche", "Nat Pagle", "Chapeau du pêcheur chanceux",
"Asticots",
// ???
"Nightcrawlers", "Bright Baubles", "Shiny Bauble", "Fishing Line");

$wordings['quest']['frFR'] =
array("Objet de quête", "Fibre d'aurore", "Eclat de pierre de sang",
"Charme ardent", "Charme de tonnerre", "Charme armori�",
"Texte du crépuscule crypté",
"Collier tribal troll", "Cerveau de Basilic",
"Gésier de Vautour", "Outre de Bat-le-désert",
"Clé de la boîte", "Croc de Gorille", "de la carte", "Relic Coffer",
"Humus d'Un'Goro",
// ???
"Snickerfang Jowl", "Blasted Boar Lung", "Abyssal Signet", "Shredder Operating", "Outhouse Key",
"Green Hills", "Blood Shard");

$wordings['scroll']['frFR'] =
array("Parchemin");

$wordings['engineer']['frFR'] =
array("Ingénirie", "Câble avec fusible", "Rouage en thorium", "Gyrochronatome",
"Structure en bronze", "Contrefiche en fer", "Altérateur de vrai-argent", "Poignée de boulons en cuivre",
"Bidule bourdonnant en bronze", "Batterie en or", "Caisse en mithril", "Tube",
"Poudre d'explosion", "Déclencheur instable", "Carburant de fusée des gobelins", "Cylindre en alliage de mithril",
// More frFR translations
"Modulateur de cuivre", "Convertisseur d'arcanite délicat", "Cylindre damasquiné en mithril",
"Contact en argent", "Micro-ajusteur gyromatique");

$wordings['darkmoon']['frFR'] =
array("Queue fournie soyeuse", "Plume vibrante",
"Oeil de chauve-souris maléfique", "Sang de scorpide luminescent", "Suite", "Un d", "Deux d", "Trois d",
"Quatre d", "Cinq d", "Six d", "Sept d", "Huit d",
// ???
"Small Furry Paw", "Torn Bear Pelt");


/********************** config (frFR) ******************************/
$wordings['frFR']['admin']['cgb_conf'] = 'Display Configuration|On this page you can configure how data is shown';
$wordings['frFR']['admin']['row_columns'] = 'Columns|Number of columns per category row';
$wordings['frFR']['admin']['color_border'] = 'Color border|Displays a border around the item icon designating its rarity';
$wordings['frFR']['admin']['show_empty'] = 'Show Empty|Hides categories with no items';
$wordings['frFR']['admin']['searchtype'] = 'Item Link|Site to serch for item when clicked';

?>