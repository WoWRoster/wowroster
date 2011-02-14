<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
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
$query = "SELECT `guild_id`, `server` AS `Server`, `guild_name` AS `Name` FROM `" . $roster->db->table('guild') . "` as guild";
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
	// Append the message to show Guild Name as well as Realm/Server Name (for multiple realm based rosters)
	$messages .= '<li>Updating relations for characters in "' . $guild['Name'] . '" @ "' . $guild['Server'] . '"' . "\n<ul>\n";
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
	print '
<div class="tier-2-a">
	<div class="tier-2-b">
		<div class="tier-2-title" style="cursor:pointer;" onclick="showHide(\'msgbox_data_error\',\'msgboximg_data_error\',\'' . $roster->config['theme_path'] . '/images/button_open.png\',\'' . $roster->config['theme_path'] . '/images/button_close.png\');">
			' . $roster->locale->act['update_errors'] . '
			<div class="toggle">
				<img id="msgboximg_data_error" src="' . $roster->config['theme_path'] . '/images/button_open.png" alt="" />
			</div>
		</div>
		<div class="border_color sredborder" style="background:black;height:300px;width:100%;overflow:auto;display:none;" id="msgbox_data_error">
			' . $errorstringout . '
		</div>
	</div>
</div>
';
}

// Print the update messages
print '
<div class="tier-2-a">
	<div class="tier-2-b">
		<div class="tier-2-title">
			' . $roster->locale->act['update_log'] . '
		</div>
		<div class="border_color syellowborder"  style="background:black;height:300px;width:100%;overflow:auto;text-align:left;font-size:10px;">
			' . $messages . '
		</div>

	</div>
</div>
';
