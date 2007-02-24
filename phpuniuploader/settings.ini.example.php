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
 * $Id: $
 *
 ******************************/

// Copy this file to settings.inc.php and modify it to match your system

//// Account data
// Your WoW Directory
$WoWDir               = "C:/Program\ Files/World\ of\ Warcraft";	// You WoW Directory without trailing /
// Your WoW Account Name (Check the directory name for case sensitivity)
$AccountName          = "ACCOUNT";			// You WoW account name, usually uppercase!!
// The URL to your UniAdmin interface.php
$UniAdminURL          = "http://your.domain.com/uniadmin/interface.php";

$CheckLUAFilesDelay   = 5;                              // How often do we check the LUA files (in seconds)
$CheckSettingsDelay   = 21600;				// How often do we check the AddOns (in seconds)

$RosterUpdateUser     = "";				// Your Roster User.
$RosterUpdatePassword = "";				// Your Roster Password.

$SendUpdatePassword   = FALSE;				// Do you want to send the password? (TRUE or FALSE)
$LogFile              = $WoWDir."/phpUniUploader.log";		// LogFile.
$UploadResultLog      = $WoWDir."/phpUniUploader.LastUpload.log";
$UserAgentVersion     = "UniUploader 2.0";
$TempDir              = $WoWDir;			// Temporary Directory to gzip files.

?>
