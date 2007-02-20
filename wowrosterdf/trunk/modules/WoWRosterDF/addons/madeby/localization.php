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
$wordings['deDE']['MadeBy_upgrade_msg']     = 'Die Rezeptesuchetabellen sind nicht aktuell. Klicke Update um die Datenbank zu aktualisieren oder klicke Install um die Tabellen zu lÃ¶schen und neu zu installieren.';
$wordings['deDE']['MadeBy_no_upgrade_msg']  = 'Die Rezeptesuchetabellen sind nicht schon aktualisiert. Klicke Reinstall um die Tabellen neu zu installieren.';
$wordings['deDE']['MadeBy_installed_msg']   = 'Gratuliert, die Rezeptsuche wurde erfolgreich installiert. Klicke auf den untenstehenden Link um das Addon zu konfigurieren.';
$wordings['deDE']['MadeBy_NoAction_msg']   = 'Nichts kann mit der gewÃ¤hlten Aktion gemacht werden!';
$wordings['deDE']['MadeBy_Configure_txt']   = 'Gehe zur Rezeptesuchekonfiguration';

// -- [ Configuation Page ]
// Page names
$wordings['deDE']['admin']['display']      = 'Anzeige Konfiguration';
$wordings['deDE']['admin']['recipe']      = 'Rezept Konfiguration';
$wordings['deDE']['admin']['recipe_maint']   = 'Recept Tabellenwartung';

// display config
$wordings['deDE']['admin']['display_recipe_icon'] = 'Rezepticon|MÃ¶chtest du das Rezepticon anzeigen?';
$wordings['deDE']['admin']['display_recipe_name'] = 'Rezeptnamen|MÃ¶chtest du die Rezeptnamen anzeigen?';
$wordings['deDE']['admin']['display_recipe_level'] = 'Rezeptlevel|MÃ¶chtest du das Rezeptlevel anzeigen?';
$wordings['deDE']['admin']['display_recipe_tooltip'] = 'Rezept-Tooltip|MÃ¶chtest du den Rezept-Tooltip anzeigen?';
$wordings['deDE']['admin']['display_recipe_type'] = 'Rezeptype|MÃ¶chtest du den Rezeptype anzeigen?';
$wordings['deDE']['admin']['display_recipe_reagents'] = 'Rezeptreagens|MÃ¶chtest du die Rezeptreagenzien anzeigen?';
$wordings['deDE']['admin']['display_recipe_makers'] = 'Rezepthersteller|MÃ¶chtest du die Rezepthersteller anzeigen?';
$wordings['deDE']['admin']['display_recipe_makers_count'] = 'Hersteller pro Zeile|Wieviele Hersteller pro Zeile?';
$wordings['deDE']['admin']['display_prof_bar']   =   'Berufetextzeile|MÃ¶chtest du die Berufetextzeile anzeigen?';

// recipe config
$wordings['deDE']['admin']['Schmiedekunst'] = 'Schmiedekunst|MÃ¶chtest du es erlauben, dass Schmiedekunst angezeigt wird?';
$wordings['deDE']['admin']['Bergbau'] = 'Bergbau|MÃ¶chtest du es erlauben, dass Bergbau angezeigt wird?';
$wordings['deDE']['admin']['Alchimie'] = 'Alchimie|MÃ¶chtest du es erlauben, dass Alchimie angezeigt wird?';
$wordings['deDE']['admin']['Lederverarbeitung'] = 'Lederverarbeitung|MÃ¶chtest du es erlauben, dass Lederverarbeitung angezeigt wird?';
$wordings['deDE']['admin']['Schneiderei'] = 'Schneiderei|MÃ¶chtest du es erlauben, dass Schneiderei angezeigt wird?';
$wordings['deDE']['admin']['Verzauberkunst'] = 'Verzauberkunst|MÃ¶chtest du es erlauben, dass Verzauberkunst angezeigt wird?';
$wordings['deDE']['admin']['Ingenieurskunst'] = 'Ingenieurskunst|MÃ¶chtest du es erlauben, dass Ingenieurskunst angezeigt wird?';
$wordings['deDE']['admin']['Kochkunst'] = 'Kochkunst|MÃ¶chtest du es erlauben, dass Kochkunst angezeigt wird?';
$wordings['deDE']['admin']['Erste Hilfe'] = 'Erste Hilfe|MÃ¶chtest du es erlauben, dass Erste Hilfe angezeigt wird?';
$wordings['deDE']['admin']['Gifte'] = 'Gifte|MÃ¶chtest du es erlauben, dass Gifte angezeigt wird?';
// 1.7.2.6 -- jewelcrafting support
$wordings['deDE']['admin']['Juwelenschleifen'] = 'Juwelenschleifen|Do you wish to allow JewelCrafting to be shown?';
$wordings['deDE']['Jewelcrafting'] = 'Juwelenschleifen';

// Patterns RegEx
$wordings['deDE']['REGEX_WAND_ROD_OILS'] 	= '/(rute|zauberstab|Ã¶l)/i';  // used to find Wands, Rods, or Oils
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
$wordings['frFR']['MadeBy_install_msg']     = 'Les tables Objets d\'artisanat n\'ont pas encore été installé. Cliquez sur Install pour commencer l\'installation.';
$wordings['frFR']['MadeBy_upgrade_msg']     = 'Les tables Objets d\'artisanat ne sont pas à jour. Cliquez sur Updade pour mettre à la base de données ou cliquez sur Install pour effacer et recréer les tables Objets d\'artisanat.';
$wordings['frFR']['MadeBy_no_upgrade_msg']  = 'Les tables Objets d\'artisanat sont déjà à jour. Cliquez sur Reinstall pour reinstaller les tables.';
$wordings['frFR']['MadeBy_installed_msg']   = 'Félicitations, Objets d\'artisanat a été installé avec succès. Cliquez sur le lien ci dessous pour le configurer.';
$wordings['frFR']['MadeBy_NoAction_msg']   = 'Rien ne peut être fait avec l\'action demandée !';
$wordings['frFR']['MadeBy_Configure_txt']   = 'Allez à la Configuration Objets d\'artisanat';

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
$wordings['frFR']['admin']['display_recipe_reagents'] = 'Réactifs Recette|Voulez vous afficher les Réactifs des Recettes ?';
$wordings['frFR']['admin']['display_recipe_makers'] = 'Crafteurs Recette|Voulez vous afficher les Crafteurs des Recettes ?';
$wordings['frFR']['admin']['display_recipe_makers_count'] = 'Crafteurs par ligne|Combien de crafeurs voulez vous afficher par ligne ?';
$wordings['frFR']['admin']['display_prof_bar']   =   'Barre Textuelle de Progression|Voulez vous afficher la Barre Textuelles de Progression ?';

// recipe config
$wordings['frFR']['admin']['Forge'] = 'Forge|Voulez vous autoriser la Forge à être affichée ?';
$wordings['frFR']['admin']['Minage'] = 'Minage|Voulez vous autoriser le Minage à être affiché ?';
$wordings['frFR']['admin']['Alchimie'] = 'Alchimie|Voulez vous autoriser l\'Alchimie à être affichée ?';
$wordings['frFR']['admin']['Travail du cuir'] = 'Travail du cuir|Voulez vous autoriser le Travail du cuir à être affiché ?';
$wordings['frFR']['admin']['Couture'] = 'Couture|Voulez vous autoriser la Couture à être affichée ?';
$wordings['frFR']['admin']['Enchantement'] = 'Enchantement|Voulez vous autoriser l\'Enchantement à être affiché ?';
$wordings['frFR']['admin']['IngÃ©nierie'] = 'Ingénierie|Voulez vous autoriser l\'Ingénierie à être affichée ?';
$wordings['frFR']['admin']['Cuisine'] = 'Cuisine|Voulez vous autoriser la Cuisine à être affichée ?';
$wordings['frFR']['admin']['Secourisme'] = 'Secourisme|Voulez vous autoriser le Secourisme à être affiché ?';
$wordings['frFR']['admin']['Poisons'] = 'Poisons|Voulez vous autoriser les Poisons à être affichés ?';
// 1.7.2.6 -- jewelcrafting support
$wordings['frFR']['admin']['Joaillerie'] = 'Joaillerie|Voulez vous autoriser la Joaillerie à être affichée ?';
$wordings['frFR']['Jewelcrafting'] = 'Joaillerie';

// Patterns RegEx
$wordings['frFR']['REGEX_WAND_ROD_OILS']    = '/\b(baguette|bâtonnet runique|huile)\b/i';  // wands, rods or oils
$wordings['frFR']['REGEX_ENCHANTMENTS']    = '/ench\. (d\'arme 2M|d\'arme|.+) \(.+\)/i';  // am i even close? :)

// -- Spanish Localization by Nekromant!

$wordings['esES']['MadeBy_install_header']   = 'Instalador MadeBy';
$wordings['esES']['MadeBy_install_msg']     = 'Las tablas de MadeBy aún no han sido instaladas. Haz click en Install para comenzar la instalación.';
$wordings['esES']['MadeBy_upgrade_msg']     = 'Las tablas de MadeBy no están actualizadas. Haz click en Updade para actualizar la base de datos o click en Install para borrar y volver a crear las tablas de MadeBy.';
$wordings['esES']['MadeBy_no_upgrade_msg']  = 'Las tablas de MadeBy están actualizadas. Haz click en Reinstall para reinstalarlas.';
$wordings['esES']['MadeBy_installed_msg']   = 'Enhorabuena, MadeBy ha sido instalado correctamente. Haz click en el enlace para configurarlo.';
$wordings['esES']['MadeBy_NoAction_msg']   = '¡No se puede hacer nada con la acción seleccionada!';
$wordings['esES']['MadeBy_Configure_txt']   = 'Configuración MadeBy';

$wordings['esES']['MadeBy']             = 'Hecho Por';
$wordings['esES']['professionfilter']       = 'Filtro de Profesiones:';
$wordings['esES']['itemdescription']       = 'Descripción del Objeto:';
$wordings['esES']['whocanmakeit']          = 'Quien puede hacerlo';
$wordings['esES']['dnotpopulatelist']       = 'No se ha creado la lista';
$wordings['esES']['applybutton']         = 'Aplicar';

// -- [ Configuation Page ]
// Page names
$wordings['esES']['admin']['display']      = 'Configuración Apariencia';
$wordings['esES']['admin']['recipe']      = 'Configuración Recetas';
$wordings['esES']['admin']['recipe_maint']   = 'Mantenimiento Tabla Recetas';
//$wordings['esES']['admin']['documentation'] = 'Documentation';

// display config
$wordings['esES']['admin']['display_recipe_icon'] = 'Iconos Recetas|¿Deseas mostrar los iconos de las recetas?';
$wordings['esES']['admin']['display_recipe_name'] = 'Nombres Recetas|¿Deseas mostrar los nombres de las recetas?';
$wordings['esES']['admin']['display_recipe_level'] = 'Nivel Receta|¿Deseas mostrar el nivel de la receta?';
$wordings['esES']['admin']['display_recipe_tooltip'] = 'Descripción Receta|¿Deseas mostrar la descripción de la receta?';
$wordings['esES']['admin']['display_recipe_type'] = 'Tipo Receta|¿Deseas mostrar el tipo de receta?';
$wordings['esES']['admin']['display_recipe_reagents'] = 'Ingredientes Receta|¿Deseas mostrar los ingredientes?';
$wordings['esES']['admin']['display_recipe_makers'] = 'Creadores Receta|¿Deseas mostrar quien puede hacerlo?';
$wordings['esES']['admin']['display_recipe_makers_count'] = 'Creadores por línea|¿Cuantos creadores de la receta por línea?';
$wordings['esES']['admin']['display_prof_bar']   =   'Texto Barra Profesión|¿Deseas mostrar una barra con el texto de la profesión?';

// recipe config
$wordings['esES']['admin']['Herrería'] = 'Herrería|¿Deseas mostrar Herrería?';
$wordings['esES']['admin']['Minería'] = 'Minería|¿Deseas mostrar Minería?';
$wordings['esES']['admin']['Alquimia'] = 'Alquimia|¿Deseas mostrar Alquimia?';
$wordings['esES']['admin']['Peletería'] = 'Peletería|¿Deseas mostrar Peletería?';
$wordings['esES']['admin']['Sastrería'] = 'Sastrería|¿Deseas mostrar Sastrería?';
$wordings['esES']['admin']['Encantamiento'] = 'Encantamiento|¿Deseas mostrar Encantamiento?';
$wordings['esES']['admin']['Ingeniería'] = 'Ingeniería|¿Deseas mostrar Ingeniería?';
$wordings['esES']['admin']['Cocina'] = 'Cocina|¿Deseas mostrar Cocina?';
$wordings['esES']['admin']['Primeros Auxilios'] = 'Primeros Auxilios|¿Deseas mostrar Primeros Auxilios?';
$wordings['esES']['admin']['Venenos'] = 'Venenos|¿Deseas mostrar Venenos?';
// 1.7.2.6 -- jewelcrafting support
$wordings['esES']['admin']['Joyería'] = 'JewelCrafting|Do you wish to allow JewelCrafting to be shown?';
$wordings['esES']['Jewelcrafting'] = 'Joyería';

// Patterns RegEx
$wordings['esES']['REGEX_WAND_ROD_OILS']    = '/\b(Vara|Varita|Aceite)\b/i';
$wordings['esES']['REGEX_ENCHANTMENTS']    = '/\bEncantar\b\s([2a-z ]+)\s-\s.+/i';


?>