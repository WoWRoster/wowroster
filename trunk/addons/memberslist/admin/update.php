<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

// Recreate a data structure containing what out hooks need to update relations. This is a partial structure, and will need changed for use by any other addon.
// Fetch guilds
$query = "SELECT `guild_id`, `guild_name` AS `Name` FROM `" . $roster->db->table('guild') . "` as guild";
$result = $roster->db->query( $query );

while( $row = $roster->db->fetch($result, SQL_ASSOC))
{
	$data[$row['guild_id']] = $row;
	$data[$row['guild_id']]['ScanInfo']['HasOfficerNote'] = '1';
	$data[$row['guild_id']]['Members'] = array();
}

$roster->db->free_result($result);

// Fetch characters
$query = "SELECT `guild_id`, `member_id`, `name` AS `Name`, `note` AS `Note`, `officer_note` AS `OfficerNote` FROM `".$roster->db->table('members')."` as members";
$result = $roster->db->query( $query );

while( $row = $roster->db->fetch($result, SQL_ASSOC))
{
	$data[$row['guild_id']]['Members'][$row['Name']] = $row;
}

$roster->db->free_result($result);

// Start the actual update process
include_once($addon['trigger_file']);
include_once(ROSTER_LIB . 'update.lib.php');
$update = new update;

$memberslist = new memberslistUpdate($addon);
$memberslist->data['config']['update_type'] = 3;

$messages = "<ul>\n";
// Loop over guilds
foreach( $data as $guild )
{
	$messages .= '<li>Updating relations for characters in "' . $guild['Name'] . '"' . "\n<ul>\n";
	// We need to do the guild_pre to load the guild-specific rules
	$memberslist->guild_pre($guild);

	// Loop over all members
	foreach($guild['Members'] as $member_name => $char)
	{
		$member_id = $char['member_id'];
		$memberslist->messages .= '<li>' . $member_name;
		$memberslist->guild($char, $member_id);
		$memberslist->messages .= '</li>' . "\n";
	}

	// Guild post hook. Deletes old entries.
	$memberslist->guild_post($guild);
	$messages .= $memberslist->messages . "</ul></li>";
	$memberslist->messages = '';
}

$messages .= '</ul>' . "\n";

$errorstringout = $update->getErrors();

// print the error messages
if( !empty($errorstringout) )
{
	print
	'<div id="errorCol" style="display:inline;">
		'.border('sred','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('errorCol','error')\"><img src=\"".$roster->config['theme_path']."/images/plus.gif\" style=\"float:right;\" /><span class=\"red\">Update Errors</span></div>").'
		'.border('sred','end').'
	</div>
	<div id="error" style="display:none">
	'.border('sred','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('errorCol','error')\"><img src=\"".$roster->config['theme_path']."/images/minus.gif\" style=\"float:right;\" /><span class=\"red\">Update Errors</span></div>").
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
