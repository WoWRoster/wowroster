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

// Account data
// Your WoW Directory
$WoWDir               = "/home/mathos/WoW/WoW-Dir";	// You WoW Directory without trailing /
$AccountName          = "MATHOS";			// You WoW account name, usually uppercase!!
$UniAdminURL          = "http://elune.mysticwoods.nl/roster/admin/interface.php";
$CheckLUAFilesDelay   = 5;                              // How often do we check the LUA files (in seconds)
$CheckSettingsDelay   = 21600;				// How often do we check the AddOns (in seconds)
$RosterUpdateUser     = "";				// Your Roster User.
$RosterUpdatePassword = "";				// Your Roster Password.
$SendUpdatePassword   = TRUE;
$LogFile              = "";	// LogFile.
//$LogFile              = "~/phpUniUploader.log";	// LogFile.
//$UploadResultLog      = "~/phpUniUploader.LastUpload.log";
$UploadResultLog      = "";
$TempDir              = "/tmp";				// Temporary Directory to gzip files.

?>
