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
// UU upload file
$script_filename = 'UU_update.php';

// Include libraries
require_once('Settings.php');
require_once(ROSTER_LIB.'update.lib.php');
$update = new update;

// Fetch addon data.
$messages = $update->fetchAddonData();

// Parse, process
$messages .= $update->parseFiles();
$messages .= $update->processFiles();

// And output
echo stripAllHtml($messages);

?>
