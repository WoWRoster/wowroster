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

// --[ Write submitted menu configuration to DB if applicable ]--

// --[ Fetch menu configuration from DB ]--

// --[ Render configuration screen ]--
if (!isset($html_head))
{
	$html_head = '';
}
$html_head .= '    <script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/wz_dragdrop.js"></script>'."\n";
$html_head .= '    <script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/menuconf.js"></script>'."\n";

/* Insert select box for which menu to configure in $menu */
$menu .= messagebox('<div id="palet" style="width:'./*buttonwidth+margin*/';height='./*buttonheight*numbuttons+margin*/';"></div>','Unused buttons','sblue');
/* Insert all unused button divs in $menu */

$body .= messagebox('<div id="array" style="width:'./*buttonwidth*numcols+margin*/';height='./*buttonheight*longestcol+margin*/';"></div>',/*menuname*/,'sgreen');
/* Insert the array of buttons for this menu in $body here */

$body .=
'<script type="text/javascript">
<!--

SET_DHTML(CURSOR_MOVE, "palet"+NO_DRAG, "array"+NO_DRAG,';


$body .= ');

var dy		= 50;
var margTop	= 10;
var dx		= 150;
var margLef	= 10;

var arrayWidth = $arrayWidth;
var arrayHeight = $arrayHeight;
var paletHeight = $paletHeight;

// Array intended to reflect the order of the draggable items
var aElts = Array();
var palet = Array();';

/* Insert code that assigns the elements to the javascript array */

$body .= '
updatePositions();
//-->
</script>';

?>
