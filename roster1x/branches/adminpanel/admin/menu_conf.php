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

// --[ Translate POST data ]--
if (isset($_POST['section']))
{
	$section = $_POST['section'];
}
else
{
	$section = 'main';
}
if (isset($_POST['doglobal']) && $_POST['doglobal'] && $roster_login->getAuthorized($roster_conf['auth_editglobalmenu']))
{
	$account = 0;
}
else
{
	$account = $roster_login->getAccount;
	if ($account < 0)
	{
		messagebox('This config section is not relevant for guests. Please log in.','Roster','sred');
		return;
	}
}


// --[ Write submitted menu configuration to DB if applicable ]--
if (isset($_POST['process']) && $_POST['process'] == 'process')
{

}

// --[ Fetch menu configuration from DB ]--
$query = "SELECT * FROM ".$wowdb->table('menu')." WHERE `account_id` = '".$account."' AND `section` = '".$section."'";

$result = $wowdb->query($query);

if (!$result)
{
	die_quietly('Could not fetch menu configuration from database. MySQL said: <br />'.$wowdb->error(),'Roster');
}

$row = $wowdb->fetch_assoc($result);

$wowdb->free_result($result);

$arrayHeight = 0;
foreach(explode('|',$row['config']) AS $id=>$column)
{
	$config[$id] = explode(':',$column);
	$arrayHeight = max($arrayHeight, count($config[$id]));
}
$arrayWidth = count($config);

// --[ Fetch button list from DB ]--
$query = "SELECT * FROM ".$wowdb->table('menu_button')

$result = $wowdb->query($query);

if (!$result)
{
	die_quietly('Could not fetch buttons from database. MySQL said: <br />'.$wowdb->error(),'Roster');
}

while ($row = $wowdb->fetch_assoc($result))
{
	$buttons['b'.$row['button_id']] = $row;
}

$wowdb->free_result($result);

// --[ Render configuration screen ]--
if (!isset($html_head))
{
	$html_head = '';
}
$html_head .= '    <script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/wz_dragdrop.js"></script>'."\n";
$html_head .= '    <script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/menuconf.js"></script>'."\n";

/* Insert select box for which menu to configure in $menu. Remember a checkbox for global/personal if current user can edit global */
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

var arrayWidth = '.$arrayWidth.';
var arrayHeight = '.$arrayHeight.';
var paletHeight = '.$paletHeight.';

// Array intended to reflect the order of the draggable items
var aElts = Array();
var palet = Array();';

/* Insert code that assigns the elements to the javascript array */

$body .= '
updatePositions();
//-->
</script>';

?>
