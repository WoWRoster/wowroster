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
//			'disabled'	Makes the page unusable
//			'divider'	Prints a horizontal line and no button.




$pages['roster'] = array(
	"href"=>	"?page=roster",
	"title"=>	"pagebar_rosterconf",
	"access"=>	0,
	"file"=>	"roster_conf.php",
	);
$pages['character'] = array(
	"href"=>	"?page=character",
	"title"=>	"pagebar_charpref",
	"access"=>	0,
	"file"=>	"character_conf.php",
	);
$pages['install'] = array(
	"href"=>	"?page=install",
	"title"=>	"pagebar_addoninst",
	"access"=>	0,
	"file"=>	"addon_install.php",
	);
$pages['password'] = array(
	"href"=>	"?page=password",
	"title"=>	"pagebar_changepass",
	"access"=>	0,
	"file"=>	"change_pass.php",
	);
$pages['update'] = array(
	"href"=>	"?page=update",
	"title"=>	"pagebar_update",
	"access"=>	10,
	"file"=>	"update.php",
	);
$pages['create'] = array(
	"href"=>	"?page=create",
	"title"=>	"pagebar_usercreate",
	"file"=>	"user_create.php",
	);
$pages['hr'] = array(
	"special"=>	"divider",
	"access"=>	10,
	);
$pages['rosterdiag'] = array(
	"href"=>	"rosterdiag.php",
	"title"=>	"pagebar_rosterdiag",
	"access"=>	0,	// But actually for everyone since it's not an admincp page
	);