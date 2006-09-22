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
//	"access"	The access level to be met before this button is visible.
//			Should refer to a $roster_conf value.
//	"file"		The file to include if this page is called. Missing means
//			invalid page.
//	"special"	Ignored unless it's one of the following:
//			'divider'	Prints a horizontal line and no button.
//			'hidden'	Hides the link, but allows access to the page




$pages['roster'] = array(
	"href"=>	"?page=roster",
	"title"=>	"pagebar_rosterconf",
	"access"=>	$roster_conf['auth_roster_config'],
	"file"=>	"roster_conf.php",
	);
$pages['character'] = array(
	"href"=>	"?page=character",
	"title"=>	"pagebar_charpref",
	"access"=>	$roster_conf['auth_character_config'],
	"file"=>	"character_conf.php",
	);
$pages['install'] = array(
	"href"=>	"?page=install",
	"title"=>	"pagebar_addoninst",
	"access"=>	$roster_conf['auth_install_addon'],
	"file"=>	"addon_install.php",
	);
$pages['password'] = array(
	"href"=>	"?page=password",
	"title"=>	"pagebar_changepass",
	"access"=>	$roster_conf['auth_change_pass'],
	"file"=>	"change_pass.php",
	);
$pages['update'] = array(
	"href"=>	"?page=update",
	"title"=>	"pagebar_update",
	"access"=>	$roster_conf['auth_update'],
	"file"=>	"update.php",
	);
$pages['create'] = array(
	"href"=>	"?page=create",
	"title"=>	"pagebar_usercreate",
	"file"=>	"user_create.php",
	);
$pages['hr'] = array(
	"special"=>	"divider",
	"access"=>	$roster_conf['auth_diag_button'],
	);
$pages['rosterdiag'] = array(
	"href"=>	"rosterdiag.php",
	"title"=>	"pagebar_rosterdiag",
	"access"=>	$roster_conf['auth_diag_button'],
	);

$pages['addon'] = array(
	"special"=>	"hidden",
	"file"=>	"addon_conf.php",
	);
$pages['menu'] = array(
	"special"=>	"hidden",
	"file"=>	"menu_conf.php",
	);
