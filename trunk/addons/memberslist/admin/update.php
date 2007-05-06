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
 * $Id: update.php 869 2007-05-01 14:30:57Z pleegwat $
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

define('IN_SORTMEMBER',true);

// Recreate the CP.lua guild subtree. Or at least the relevant parts.
$guild = $roster->data;

$query = "SELECT `member_id`, `name` AS `Name`, `note` AS `Note`, `officer_note` AS `OfficerNote` FROM `".ROSTER_MEMBERSTABLE."` as members";
$result = $roster->db->query( $query ) or die_quietly( $roster->db->error() );

while( $row = $roster->db->fetch($result))
{
	$guild['Members'][$row['Name']] = $row;
}

$roster->db->free_result($result);


// Start the actual update process
include_once($addon['dir'] . 'update_hook.php');
include_once(ROSTER_LIB . 'wowdb.php');

$memberslist = new memberslist($addon);

// Loop over all members
foreach($guild['Members'] as $member_name => $char)
{
	$member_id = $char['member_id'];
	$memberslist->messages .= $member_name;
	$memberslist->guild($char, $member_id);
}

// Guild post hook. Deletes old entries.
$memberslist->guild_post($guild);

$messages = $memberslist->messages;
$errorstringout = $wowdb->getErrors();

// print the error messages
if( !empty($errorstringout) )
{
	print
	'<div id="errorCol" style="display:inline;">
		'.border('sred','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('errorCol','error')\"><img src=\"".$roster->config['img_url']."plus.gif\" style=\"float:right;\" /><span class=\"red\">Update Errors</span></div>").'
		'.border('sred','end').'
	</div>
	<div id="error" style="display:none">
	'.border('sred','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('errorCol','error')\"><img src=\"".$roster->config['img_url']."minus.gif\" style=\"float:right;\" /><span class=\"red\">Update Errors</span></div>").
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
