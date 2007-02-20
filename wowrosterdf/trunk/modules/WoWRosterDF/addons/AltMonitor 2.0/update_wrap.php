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

// Recreate the CP.lua guild subtree. Or at least the relevant parts.
$guild = $GLOBALS['guild_info'];

$query = "SELECT `member_id`, `name` AS `Name`, `note` AS `Note`, `officer_note` AS `OfficerNote` FROM `".ROSTER_MEMBERSTABLE."` as members";
$result = $wowdb->query( $query ) or die_quietly( $wowdb->error() );

while( $row = $wowdb->fetch_array($result))
{
	$guild['Members'][$row['Name']] = $row;
}

$wowdb->free_result($result);


// Start the actual update process
include_once($addonDir.'update.php');

$AltMonitorUpdate = $GLOBALS['AltMonitorUpdate'];
$AltMonitorUpdate->messages = '';

// Guild pre hook. Unused, but just in case I add something there in the future and forget it here.
$retval = $AltMonitorUpdate->guild_pre($guild);
if (!empty($retval)) $AltMonitorUpdate->messages .= " - <span style='color:red;'>".$retval."</span><br/>\n";

// Loop over all members
foreach($guild['Members'] as $member_name => $char)
{
	$member_id = $char['member_id'];
	$AltMonitorUpdate->messages .= $member_name;
	$retval = $AltMonitorUpdate->guild($member_id, $member_name, $char);
	if (!empty($retval)) $AltMonitorUpdate->messages .= " - <span style='color:red;'>".$retval."</span><br/>\n";
	$AltMonitorUpdate->messages .= "<br />\n";
}

// Guild post hook. Deletes old entries.
$retval = $AltMonitorUpdate->guild_post($guild);
if (!empty($retval)) $AltMonitorUpdate->messages .= " - <span style='color:red;'>".$retval."</span><br/>\n";

$messages = $AltMonitorUpdate->messages;
$errorstringout = $wowdb->getErrors();

// print the error messages
if( !empty($errorstringout) )
{
	print
	'<div id="errorCol" style="display:inline;">
		'.border('sred','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('errorCol','error')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" /><span class=\"red\">Update Errors</span></div>").'
		'.border('sred','end').'
	</div>
	<div id="error" style="display:none">
	'.border('sred','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('errorCol','error')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" /><span class=\"red\">Update Errors</span></div>").
	$errorstringout.
	border('sred','end').
	'</div>';

	// Print the downloadable errors separately so we can generate a download
	print "<br />\n";
	print '<form method="post" action="update.php" name="post">'."\n";
	print '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($errorstringout)).'" />'."\n";
	print '<input type="hidden" name="send_file" value="error" />'."\n";
	print '<input type="submit" name="download" value="Save Error Log" />'."\n";
	print '</form>';
	print "<br />\n";
}

// Print the update messages
print
	border('syellow','start','Update Log').
	'<div style="font-size:10px;background-color:#1F1E1D;text-align:left;height:300px;width:550px;overflow:auto;">'.
	$messages.
	'</div>'.
	border('syellow','end');

// Print the downloadable messages separately so we can generate a download
print "<br />\n";
print '<form method="post" action="'.$roster_conf['roster_dir'].'/admin/update.php" name="post">'."\n";
print '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($messages)).'" />'."\n";
print '<input type="hidden" name="send_file" value="update" />'."\n";
print '<input type="submit" name="download" value="Save Update Log" />'."\n";
print '</form>';
print "<br />\n";
