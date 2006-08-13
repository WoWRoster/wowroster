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

// Include libraries
require_once('Settings.php');

// Check auth
$roster_login = new RosterLogin($script_filename);
$roster_auth_level = (( $roster_login->getAuthorized() )?3:1);


require_once(ROSTER_LIB.'update.lib.php');
$update = new update;

// Fetch addon data.
$messages = $update->fetchAddonData();

// Parse, process
$messages .= $update->parseFiles();
$messages .= $update->processFiles();


echo stripAllHtml($auth_message.$messages);

?>