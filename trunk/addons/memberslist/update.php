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

define('IN_SORTMEMBER',true);

require($addon['dir'].'inc'.DIR_SEP.'login.php');

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
include_once($addon['dir'].'update_hook.php');

$SortMember = new SortMember($addon);

// Loop over all members
foreach($guild['Members'] as $member_name => $char)
{
	$member_id = $char['member_id'];
	$SortMember->messages .= $member_name;
	$SortMember->guild($char, $member_id);
}

// Guild post hook. Deletes old entries.
$SortMember->guild_post($guild);

$messages = $SortMember->messages;
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
}

// Print the update messages
print
	border('syellow','start','Update Log').
	'<div style="font-size:10px;background-color:#1F1E1D;text-align:left;height:300px;width:550px;overflow:auto;">'.
	$messages.
	'</div>'.
	border('syellow','end');