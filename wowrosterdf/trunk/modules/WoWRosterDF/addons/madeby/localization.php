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
define('MADEBY_VERSION', 'MadeBy v1.7.2.6');
$wordings['addoncredits'][MADEBY_VERSION] = array(
array(
"name"=>	"Cybrey",
"info"=>	"Original Addon Developer -- Mods by Thorus"),
array(
"name"=>	"ds",
"info"=>	"Current Addon Developer"),
);

$wordings['enUS']['MadeBy_install_header']	= 'MadeBy Installer';
$wordings['enUS']['MadeBy_install_msg']     = 'The MadeBy tables haven\'t been installed yet. Click Install to start the installation';
$wordings['enUS']['MadeBy_upgrade_msg']     = 'The MadeBy tables are not up to date. Click Updade to update the database or click Install to drop and recreate the MadeBy tables.';
$wordings['enUS']['MadeBy_no_upgrade_msg']  = 'The MadeBy tables are already up to date. Click Reinstall below to reinstall the tables.';
$wordings['enUS']['MadeBy_installed_msg']   = 'Congratulations, MadeBy has been successfully installed. Click the link below to configure it.';
$wordings['enUS']['MadeBy_NoAction_msg']	= 'Nothing can be done with the selected action!';
$wordings['enUS']['MadeBy_Configure_txt']	= 'Goto MadeBy Configuration';

$wordings['enUS']['MadeBy'] 				= 'Made By';
$wordings['enUS']['professionfilter'] 		= 'Profession Filter:';
$wordings['enUS']['itemdescription'] 		= 'Item Description:';
$wordings['enUS']['whocanmakeit'] 			= 'Who can make it';
$wordings['enUS']['dnotpopulatelist'] 		= 'No Recipes found!';
$wordings['enUS']['applybutton']			= 'Apply';

// -- [ Configuation Page ]
// Page names
$wordings['enUS']['admin']['display']		= 'Display Configuration';
$wordings['enUS']['admin']['recipe']		= 'Recipe Configuration';
$wordings['enUS']['admin']['recipe_maint']	= 'Recipe Table Maintaince';
//$wordings['enUS']['admin']['documentation'] = 'Documentation';

// display config
$wordings['enUS']['admin']['display_recipe_icon'] = 'Recipe Icon|Do you wish to display Recipe Icons?';
$wordings['enUS']['admin']['display_recipe_name'] = 'Recipe Names|Do you wish to display the Recipe Names?';
$wordings['enUS']['admin']['display_recipe_level'] = 'Recipe Level|Do you wish to display the Recipe Level?';
$wordings['enUS']['admin']['display_recipe_tooltip'] = 'Recipe Tooltip|Do you wish to display the Recipe Tooltip?';
$wordings['enUS']['admin']['display_recipe_type'] = 'Recipe Type|Do you wish to display the Recipe Type?';
$wordings['enUS']['admin']['display_recipe_reagents'] = 'Recipe Reagents|Do you wish to display the Recipe Reagents?';
$wordings['enUS']['admin']['display_recipe_makers'] = 'Recipe Makers|Do you wish to display the Recipe Makers?';
$wordings['enUS']['admin']['display_recipe_makers_count'] = 'Makers per Line|How many recipe makers per line?';
$wordings['enUS']['admin']['display_prof_bar']	=	'Text Profession Bar|Do you wish to display a text profession bar?';

// recipe config
$wordings['enUS']['admin']['Blacksmithing'] = 'BlackSmithing|Do you wish to allow BlackSmithing to be shown?';
$wordings['enUS']['admin']['Mining'] = 'Mining|Do you wish to allow Mining to be shown?';
$wordings['enUS']['admin']['Alchemy'] = 'Alchemy|Do you wish to allow Alchemy to be shown?';
$wordings['enUS']['admin']['Leatherworking'] = 'Leatherworking|Do you wish to allow Leatherworking to be shown?';
$wordings['enUS']['admin']['Tailoring'] = 'Tailoring|Do you wish to allow Tailoring to be shown?';
$wordings['enUS']['admin']['Enchanting'] = 'Enchanting|Do you wish to allow Enchanting to be shown?';
$wordings['enUS']['admin']['Engineering'] = 'Engineering|Do you wish to allow Engineering to be shown?';
$wordings['enUS']['admin']['Cooking'] = 'Cooking|Do you wish to allow Cooking to be shown?';
$wordings['enUS']['admin']['First Aid'] = 'First Aid|Do you wish to allow First Aid to be shown?';
$wordings['enUS']['admin']['Poisons'] = 'Poisons|Do you wish to allow Poisons to be shown?';
// 1.7.2.6 -- jewelcrafting support
$wordings['enUS']['admin']['Jewelcrafting'] = 'JewelCrafting|Do you wish to allow JewelCrafting to be shown?';
$wordings['enUS']['Jewelcrafting'] = 'Jewelcrafting';

// Patterns RegEx
$wordings['enUS']['REGEX_WAND_ROD_OILS'] 	= '/\b(rod|wand|oil)\b/i';
$wordings['enUS']['REGEX_ENCHANTMENTS'] 	= '/\benchant\b\s([2a-z ]+)\s-\s.+/i';


//
// -- German Translation provided by SethDeBlade
//

$wordings['deDE']['MadeBy'] = 'Rezeptesuche';
$wordings['deDE']['professionfilter'] = 'Fertigkeit:';
$wordings['deDE']['itemdescription'] = 'Beschreibung';
$wordings['deDE']['whocanmakeit'] = 'Wird hergestellt von';
$wordings['deDE']['dnotpopulatelist'] = 'Leider nichts gefunden';
$wordings['deDE']['applybutton']= 'Start';

// -- installer
$wordings['deDE']['MadeBy_install_header']   = 'Rezeptesuche Installer';
$wordings['deDE']['MadeBy_install_msg']     = 'Die Rezeptesuchetabellen sind noch nicht installiert. Klicke auf Install im die Installation zu starten';
$wordings['deDE']['MadeBy_upgrade_msg']     = 'Die Rezeptesuchetabellen sind nicht aktuell. Klicke Update um die Datenbank zu aktualisieren oder klicke Install um die Tabellen zu löschen und neu zu installieren.';
$wordings['deDE']['MadeBy_no_upgrade_msg']  = 'Die Rezeptesuchetabellen sind nicht schon aktualisiert. Klicke Reinstall um die Tabellen neu zu installieren.';
$wordings['deDE']['MadeBy_installed_msg']   = 'Gratuliert, die Rezeptsuche wurde erfolgreich installiert. Klicke auf den untenstehenden Link um das Addon zu konfigurieren.';
$wordings['deDE']['MadeBy_NoAction_msg']   = 'Nichts kann mit der gewählten Aktion gemacht werden!';
$wordings['deDE']['MadeBy_Configure_txt']   = 'Gehe zur Rezeptesuchekonfiguration';

// -- [ Configuation Page ]
// Page names
$wordings['deDE']['admin']['display']      = 'Anzeige Konfiguration';
$wordings['deDE']['admin']['recipe']      = 'Rezept Konfiguration';
$wordings['deDE']['admin']['recipe_maint']   = 'Recept Tabellenwartung';

// display config
$wordings['deDE']['admin']['display_recipe_icon'] = 'Rezepticon|Möchtest du das Rezepticon anzeigen?';
$wordings['deDE']['admin']['display_recipe_name'] = 'Rezeptnamen|Möchtest du die Rezeptnamen anzeigen?';
$wordings['deDE']['admin']['display_recipe_level'] = 'Rezeptlevel|Möchtest du das Rezeptlevel anzeigen?';
$wordings['deDE']['admin']['display_recipe_tooltip'] = 'Rezept-Tooltip|Möchtest du den Rezept-Tooltip anzeigen?';
$wordings['deDE']['admin']['display_recipe_type'] = 'Rezeptype|Möchtest du den Rezeptype anzeigen?';
$wordings['deDE']['admin']['display_recipe_reagents'] = 'Rezeptreagens|Möchtest du die Rezeptreagenzien anzeigen?';
$wordings['deDE']['admin']['display_recipe_makers'] = 'Rezepthersteller|Möchtest du die Rezepthersteller anzeigen?';
$wordings['deDE']['admin']['display_recipe_makers_count'] = 'Hersteller pro Zeile|Wieviele Hersteller pro Zeile?';
$wordings['deDE']['admin']['display_prof_bar']   =   'Berufetextzeile|Möchtest du die Berufetextzeile anzeigen?';

// recipe config
$wordings['deDE']['admin']['Schmiedekunst'] = 'Schmiedekunst|Möchtest du es erlauben, dass Schmiedekunst angezeigt wird?';
$wordings['deDE']['admin']['Bergbau'] = 'Bergbau|Möchtest du es erlauben, dass Bergbau angezeigt wird?';
$wordings['deDE']['admin']['Alchimie'] = 'Alchimie|Möchtest du es erlauben, dass Alchimie angezeigt wird?';
$wordings['deDE']['admin']['Lederverarbeitung'] = 'Lederverarbeitung|Möchtest du es erlauben, dass Lederverarbeitung angezeigt wird?';
$wordings['deDE']['admin']['Schneiderei'] = 'Schneiderei|Möchtest du es erlauben, dass Schneiderei angezeigt wird?';
$wordings['deDE']['admin']['Verzauberkunst'] = 'Verzauberkunst|Möchtest du es erlauben, dass Verzauberkunst angezeigt wird?';
$wordings['deDE']['admin']['Ingenieurskunst'] = 'Ingenieurskunst|Möchtest du es erlauben, dass Ingenieurskunst angezeigt wird?';
$wordings['deDE']['admin']['Kochkunst'] = 'Kochkunst|Möchtest du es erlauben, dass Kochkunst angezeigt wird?';
$wordings['deDE']['admin']['Erste Hilfe'] = 'Erste Hilfe|Möchtest du es erlauben, dass Erste Hilfe angezeigt wird?';
$wordings['deDE']['admin']['Gifte'] = 'Gifte|Möchtest du es erlauben, dass Gifte angezeigt wird?';
// 1.7.2.6 -- jewelcrafting support
$wordings['deDE']['admin']['Juwelenschleifen'] = 'Juwelenschleifen|Do you wish to allow JewelCrafting to be shown?';
$wordings['deDE']['Jewelcrafting'] = 'Juwelenschleifen';

// Patterns RegEx
$wordings['deDE']['REGEX_WAND_ROD_OILS'] 	= '/(rute|zauberstab|öl)/i';  // used to find Wands, Rods, or Oils
$wordings['deDE']['REGEX_ENCHANTMENTS'] 	= '/([2a-z ]+)-/i';    // used to parse all other enchantments

// -------------------------------------------------------------------------------------------- //

// translated to frFR from enUS by Harut/Yoshette
$wordings['frFR']['MadeBy'] = 'Objets d\'artisanat';
$wordings['frFR']['professionfilter'] = 'Filtre de profession:';
$wordings['frFR']['itemdescription'] = 'Description de l\'objet:';
$wordings['frFR']['whocanmakeit'] = 'Artisants';
$wordings['frFR']['dnotpopulatelist'] = 'Liste vide';
$wordings['frFR']['applybutton']= 'Appliquer';

// -- installer
$wordings['frFR']['MadeBy_install_header']   = 'Installateur Objets d\'artisanat';
$wordings['frFR']['MadeBy_install_msg']     = 'Les tables Objets d\'artisanat n\'ont pas encore �t� install�. Cliquez sur Install pour commencer l\'installation.';
$wordings['frFR']['MadeBy_upgrade_msg']     = 'Les tables Objets d\'artisanat ne sont pas � jour. Cliquez sur Updade pour mettre � la base de donn�es ou cliquez sur Install pour effacer et recr�er les tables Objets d\'artisanat.';
$wordings['frFR']['MadeBy_no_upgrade_msg']  = 'Les tables Objets d\'artisanat sont d�j� � jour. Cliquez sur Reinstall pour reinstaller les tables.';
$wordings['frFR']['MadeBy_installed_msg']   = 'F�licitations, Objets d\'artisanat a �t� install� avec succ�s. Cliquez sur le lien ci dessous pour le configurer.';
$wordings['frFR']['MadeBy_NoAction_msg']   = 'Rien ne peut �tre fait avec l\'action demand�e !';
$wordings['frFR']['MadeBy_Configure_txt']   = 'Allez � la Configuration Objets d\'artisanat';

// -- [ Configuation Page ]
// Page names
$wordings['frFR']['admin']['display']      = 'Configuration d\'Affichage';
$wordings['frFR']['admin']['recipe']      = 'Configuration des Recettes';
$wordings['frFR']['admin']['recipe_maint']   = 'Maintenance de la table Recette';

// display config
$wordings['frFR']['admin']['display_recipe_icon'] = 'Icone Recette|Voulez vous afficher les Icones des Recettes ?';
$wordings['frFR']['admin']['display_recipe_name'] = 'Noms Recette|Voulez vous afficher les Noms des Recettes ?';
$wordings['frFR']['admin']['display_recipe_level'] = 'Niveau Recette|Voulez vous afficher les Niveaux des Recettes ?';
$wordings['frFR']['admin']['display_recipe_tooltip'] = 'Tooltip Recette|Voulez vous afficher le Tooltip des Recettes ?';
$wordings['frFR']['admin']['display_recipe_type'] = 'Type Recette|Voulez vous afficher le Type des Recettes ?';
$wordings['frFR']['admin']['display_recipe_reagents'] = 'R�actifs Recette|Voulez vous afficher les R�actifs des Recettes ?';
$wordings['frFR']['admin']['display_recipe_makers'] = 'Crafteurs Recette|Voulez vous afficher les Crafteurs des Recettes ?';
$wordings['frFR']['admin']['display_recipe_makers_count'] = 'Crafteurs par ligne|Combien de crafeurs voulez vous afficher par ligne ?';
$wordings['frFR']['admin']['display_prof_bar']   =   'Barre Textuelle de Progression|Voulez vous afficher la Barre Textuelles de Progression ?';

// recipe config
$wordings['frFR']['admin']['Forge'] = 'Forge|Voulez vous autoriser la Forge � �tre affich�e ?';
$wordings['frFR']['admin']['Minage'] = 'Minage|Voulez vous autoriser le Minage � �tre affich� ?';
$wordings['frFR']['admin']['Alchimie'] = 'Alchimie|Voulez vous autoriser l\'Alchimie � �tre affich�e ?';
$wordings['frFR']['admin']['Travail du cuir'] = 'Travail du cuir|Voulez vous autoriser le Travail du cuir � �tre affich� ?';
$wordings['frFR']['admin']['Couture'] = 'Couture|Voulez vous autoriser la Couture � �tre affich�e ?';
$wordings['frFR']['admin']['Enchantement'] = 'Enchantement|Voulez vous autoriser l\'Enchantement � �tre affich� ?';
$wordings['frFR']['admin']['Ingénierie'] = 'Ing�nierie|Voulez vous autoriser l\'Ing�nierie � �tre affich�e ?';
$wordings['frFR']['admin']['Cuisine'] = 'Cuisine|Voulez vous autoriser la Cuisine � �tre affich�e ?';
$wordings['frFR']['admin']['Secourisme'] = 'Secourisme|Voulez vous autoriser le Secourisme � �tre affich� ?';
$wordings['frFR']['admin']['Poisons'] = 'Poisons|Voulez vous autoriser les Poisons � �tre affich�s ?';
// 1.7.2.6 -- jewelcrafting support
$wordings['frFR']['admin']['Joaillerie'] = 'Joaillerie|Voulez vous autoriser la Joaillerie � �tre affich�e ?';
$wordings['frFR']['Jewelcrafting'] = 'Joaillerie';

// Patterns RegEx
$wordings['frFR']['REGEX_WAND_ROD_OILS']    = '/\b(baguette|b�tonnet runique|huile)\b/i';  // wands, rods or oils
$wordings['frFR']['REGEX_ENCHANTMENTS']    = '/ench\. (d\'arme 2M|d\'arme|.+) \(.+\)/i';  // am i even close? :)

// -- Spanish Localization by Nekromant!

$wordings['esES']['MadeBy_install_header']   = 'Instalador MadeBy';
$wordings['esES']['MadeBy_install_msg']     = 'Las tablas de MadeBy a�n no han sido instaladas. Haz click en Install para comenzar la instalaci�n.';
$wordings['esES']['MadeBy_upgrade_msg']     = 'Las tablas de MadeBy no est�n actualizadas. Haz click en Updade para actualizar la base de datos o click en Install para borrar y volver a crear las tablas de MadeBy.';
$wordings['esES']['MadeBy_no_upgrade_msg']  = 'Las tablas de MadeBy est�n actualizadas. Haz click en Reinstall para reinstalarlas.';
$wordings['esES']['MadeBy_installed_msg']   = 'Enhorabuena, MadeBy ha sido instalado correctamente. Haz click en el enlace para configurarlo.';
$wordings['esES']['MadeBy_NoAction_msg']   = '�No se puede hacer nada con la acci�n seleccionada!';
$wordings['esES']['MadeBy_Configure_txt']   = 'Configuraci�n MadeBy';

$wordings['esES']['MadeBy']             = 'Hecho Por';
$wordings['esES']['professionfilter']       = 'Filtro de Profesiones:';
$wordings['esES']['itemdescription']       = 'Descripci�n del Objeto:';
$wordings['esES']['whocanmakeit']          = 'Quien puede hacerlo';
$wordings['esES']['dnotpopulatelist']       = 'No se ha creado la lista';
$wordings['esES']['applybutton']         = 'Aplicar';

// -- [ Configuation Page ]
// Page names
$wordings['esES']['admin']['display']      = 'Configuraci�n Apariencia';
$wordings['esES']['admin']['recipe']      = 'Configuraci�n Recetas';
$wordings['esES']['admin']['recipe_maint']   = 'Mantenimiento Tabla Recetas';
//$wordings['esES']['admin']['documentation'] = 'Documentation';

// display config
$wordings['esES']['admin']['display_recipe_icon'] = 'Iconos Recetas|�Deseas mostrar los iconos de las recetas?';
$wordings['esES']['admin']['display_recipe_name'] = 'Nombres Recetas|�Deseas mostrar los nombres de las recetas?';
$wordings['esES']['admin']['display_recipe_level'] = 'Nivel Receta|�Deseas mostrar el nivel de la receta?';
$wordings['esES']['admin']['display_recipe_tooltip'] = 'Descripci�n Receta|�Deseas mostrar la descripci�n de la receta?';
$wordings['esES']['admin']['display_recipe_type'] = 'Tipo Receta|�Deseas mostrar el tipo de receta?';
$wordings['esES']['admin']['display_recipe_reagents'] = 'Ingredientes Receta|�Deseas mostrar los ingredientes?';
$wordings['esES']['admin']['display_recipe_makers'] = 'Creadores Receta|�Deseas mostrar quien puede hacerlo?';
$wordings['esES']['admin']['display_recipe_makers_count'] = 'Creadores por l�nea|�Cuantos creadores de la receta por l�nea?';
$wordings['esES']['admin']['display_prof_bar']   =   'Texto Barra Profesi�n|�Deseas mostrar una barra con el texto de la profesi�n?';

// recipe config
$wordings['esES']['admin']['Herrer�a'] = 'Herrer�a|�Deseas mostrar Herrer�a?';
$wordings['esES']['admin']['Miner�a'] = 'Miner�a|�Deseas mostrar Miner�a?';
$wordings['esES']['admin']['Alquimia'] = 'Alquimia|�Deseas mostrar Alquimia?';
$wordings['esES']['admin']['Peleter�a'] = 'Peleter�a|�Deseas mostrar Peleter�a?';
$wordings['esES']['admin']['Sastrer�a'] = 'Sastrer�a|�Deseas mostrar Sastrer�a?';
$wordings['esES']['admin']['Encantamiento'] = 'Encantamiento|�Deseas mostrar Encantamiento?';
$wordings['esES']['admin']['Ingenier�a'] = 'Ingenier�a|�Deseas mostrar Ingenier�a?';
$wordings['esES']['admin']['Cocina'] = 'Cocina|�Deseas mostrar Cocina?';
$wordings['esES']['admin']['Primeros Auxilios'] = 'Primeros Auxilios|�Deseas mostrar Primeros Auxilios?';
$wordings['esES']['admin']['Venenos'] = 'Venenos|�Deseas mostrar Venenos?';
// 1.7.2.6 -- jewelcrafting support
$wordings['esES']['admin']['Joyer�a'] = 'JewelCrafting|Do you wish to allow JewelCrafting to be shown?';
$wordings['esES']['Jewelcrafting'] = 'Joyer�a';

// Patterns RegEx
$wordings['esES']['REGEX_WAND_ROD_OILS']    = '/\b(Vara|Varita|Aceite)\b/i';
$wordings['esES']['REGEX_ENCHANTMENTS']    = '/\bEncantar\b\s([2a-z ]+)\s-\s.+/i';


?>