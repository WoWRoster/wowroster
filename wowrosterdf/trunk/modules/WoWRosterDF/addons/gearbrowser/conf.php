<?php
/******************************
 * Gear Browser
 * By Rihlsul
 * www.ironcladgathering.com
 * v 1.0  (9/2/2006 2:15 PM)
 * Compatible with Roster 1.70
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$gbVersion = '1.0';

// For Guild Bank
// ---------------
include($addonDir.'searcharrays.php');
// Number of columns per category row
$row_columns = 8;
// Show color border
$color_border = 1; // 0 = No, 1 = Yes
// Do you want categories with no items to appear?
$show_empty = 1; // 0 = No, 1 = Yes
////// ItemLink Site
// 1=Thottbot,
// 2=Allakhazam for 'enUS' and blasc.de for 'deDE'.
$searchtype = 1;
// The order the tables will display in the guildbank page
// You can exclude items you don't want to appear
// This is 1,2 by default. That translates to 'armor','weapons' types.
$display_order = array(1,2);  


// For Made By
// -----------
$display_recipe_icon = 1;			//Recipe Icon column display ( 1 on | 0 off)
$display_recipe_name = 1;			//Recipe Name column display ( 1 on | 0 off)
$display_recipe_level = 1;			//Recipe Level column display ( 1 on | 0 off)
$display_recipe_tooltip = 0;		//Recipe tooltip column display ( 1 on | 0 off )
$display_recipe_type = 0;			//Recipe Type column display ( 1 on | 0 off)
$display_recipe_reagents = 1;		//Recipe Reagents column display ( 1 on | 0 off)
$display_recipe_makers = 0;			//Recipe Who can make what column display ( 1 on | 0 off)
$display_recipe_makers_count = 3;	//Number of makers per row in makers column (3 is default)

?>
