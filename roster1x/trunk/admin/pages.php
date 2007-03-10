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

// The key in the $pages array is the pagename for the admincp file.
// The value is an array whose keys have these menaings:
//	"href"		The link this should refer to.
//	"title"		The localization key for the button title.
//	"file"		The file to include if this page is called. Missing means
//			invalid page.
//	"special"	Ignored unless it's one of the following:
//			'divider'	Prints a horizontal line and no button.
//			'hidden'	Hides the link, but allows access to the page




$pages['roster'] = array(
	"href"=>	$pages[0]."-roster",
	"title"=>	"pagebar_rosterconf",
	"file"=>	"roster_conf.php",
	);
$pages['character'] = array(
	"href"=>	$pages[0]."-character",
	"title"=>	"pagebar_charpref",
	"file"=>	"character_conf.php",
	);
$pages['menu'] = array(
	"href"=>	$pages[0]."-menu",
	"title"=>	"pagebar_menuconf",
	"file"=>	"menu_conf.php",
	);
$pages['install'] = array(
	"href"=>	$pages[0]."-install",
	"title"=>	"pagebar_addoninst",
	"file"=>	"addon_install.php",
	);
$pages['password'] = array(
	"href"=>	$pages[0]."-password",
	"title"=>	"pagebar_changepass",
	"file"=>	"change_pass.php",
	);
$pages['hr'] = array(
	"special"=>	"divider",
	);
$pages['rosterdiag'] = array(
	"href"=>	"rosterdiag.php",
	"title"=>	"pagebar_rosterdiag",
	);

$pages['addon'] = array(
	"special"=>	"hidden",
	"file"=>	"addon_conf.php",
	);
