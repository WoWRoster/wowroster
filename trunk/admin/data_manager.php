<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster upload rule config
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: change_pass.php 913 2007-05-08 03:51:55Z Zanix $
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

include( ROSTER_LIB . 'update.lib.php' );
$update = new update;

$roster->output['title'] .= $roster->locale->act['pagebar_uploadrules'];

$mode = (isset($roster->pages[2]) && $roster->pages[2] == 'char')?'char':'guild';

// Process a new line
if( isset($_POST['process']) && $_POST['process'] == 'process')
{
	if( substr($_POST['action'],0,4) == 'del_' )
	{
		$member_id = substr($_POST['action'],4);

		$update->deleteMembers( $member_id );
	}
	elseif( $_POST['action'] == 'clean' )
	{
		$update->enforceRules( time() );
	}

	$messages = $update->getMessages();
	$errors = $update->getErrors();

	// print the error messages
	if( !empty($errors) )
	{
		$body .= scrollboxtoggle($errors,'<span class="red">' . $roster->locale->act['update_errors'] . '</span>','sred',false);

		// Print the downloadable errors separately so we can generate a download
		$body .= "<br />\n";
		$body .= '<form method="post" action="' . makelink() . '" name="post">' . "\n";
		$body .= '<input type="hidden" name="data" value="' . htmlspecialchars(stripAllHtml($errors)) . '" />' . "\n";
		$body .= '<input type="hidden" name="send_file" value="error_log" />' . "\n";
		$body .= '<input type="submit" name="download" value="' . $roster->locale->act['save_error_log'] . '" />' . "\n";
		$body .= '</form>';
		$body .= "<br />\n";
	}

	// Print the update messages
	$body .= scrollbox('<div style="text-align:left;font-size:10px;">' . $messages . '</div>',$roster->locale->act['update_log'],'syellow');

	// Print the downloadable messages separately so we can generate a download
	$body .= "<br />\n";
	$body .= '<form method="post" action="' . makelink() . '" name="post">' . "\n";
	$body .= '<input type="hidden" name="data" value="' . htmlspecialchars(stripAllHtml($messages)) . '" />' . "\n";
	$body .= '<input type="hidden" name="send_file" value="update_log" />' . "\n";
	$body .= '<input type="submit" name="download" value="' . $roster->locale->act['save_update_log'] . '" />' . "\n";
	$body .= '</form>';
	$body .= "<br />\n";
}

// Fetch data
$menu_select = array();

// Get the scope select data
$query = "SELECT `guild_name`, CONCAT(`region`,'-',`server`), `guild_id` FROM `" . $roster->db->table('guild') . "`"
	   . " ORDER BY `region` ASC, `server` ASC, `guild_name` ASC;";

$result = $roster->db->query($query);

if( !$result )
{
    die_quietly($roster->db->error(),'Database error',__FILE__,__LINE__,$query);
}

while( $data = $roster->db->fetch($result,SQL_NUM) )
{
	$menu_select[$data[1]][$data[2]] = $data[0];
}

$options='';

if( $roster->db->num_rows($result) > 0 )
{
	foreach( $menu_select as $realm => $guild )
	{
		$options .= '		<optgroup label="' . $realm . '">'. "\n";
		foreach( $guild as $id => $name )
		{
			$options .= '			<option value="' . makelink("&amp;guild=$id") . '"' . ( ( isset($_GET['guild']) && $id == $_GET['guild']) ? ' selected="selected"' : '' ) . '>' . $name . '</option>' . "\n";
		}
		$options .= '		</optgroup>';
	}
}

$roster->db->free_result($result);

$body = 'Select A Guild
<form action="' . makelink() . '" name="realm_select" method="post">
	<select name="guild" onchange="window.location.href=this.options[this.selectedIndex].value;">
		<option value="' . makelink() . '">----------</option>
' . $options . '
	</select>
</form>';

$body = messagebox($body,'','sgreen');

$listing = $next = $prev = '';



$data = getCharData();


// OUTPUT
$roster->output['body_onload'] .= 'initARC(\'allow\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

$body .= $roster_login->getMessage() . '<br />
<form action="' . makelink() . '" method="post" id="clean">
<input type="hidden" name="action" value="clean" />
<input type="hidden" name="process" value="process" />
<button type="submit" class="input">' . $roster->locale->act['clean'] . '</button></form>'."\n";

$body .= "<br />\n";

if( is_array($data) && count($data) > 0 )
{
	$body .= '<form action="' . makelink() . '" method="post" id="delete">
<input type="hidden" id="deletehide" name="action" value="" />
<input type="hidden" name="process" value="process" />'."\n";

	$body .= border('sgreen','start',$prev . $roster->locale->act['delete'] . $listing . $next) . '
<table class="bodyline" cellspacing="0">
	<thead>
		<tr>
';

	$body .= '
			<th class="membersHeader"> ' . $roster->locale->act['name'] . '</th>
			<th class="membersHeader"> ' . $roster->locale->act['server'] . '</th>
			<th class="membersHeader"> ' . $roster->locale->act['region'] . '</th>
			<th class="membersHeader"> ' . $roster->locale->act['class'] . '</th>
			<th class="membersHeader"> ' . $roster->locale->act['level'] . '</th>
			<th class="membersHeaderRight">&nbsp;</th>
		</tr>
	</thead>
	<tbody>' . "\n";

	$i=0;
	foreach( $data as $row )
	{
		$body .= "\n\t\t<tr>\n" . '
			<td class="membersRow' . (($i%2)+1) . '">' . $row['name'] . '</td>
			<td class="membersRow' . (($i%2)+1) . '">' . $row['server'] . '</td>
			<td class="membersRow' . (($i%2)+1) . '">' . $row['region'] . '</td>
			<td class="membersRow' . (($i%2)+1) . '">' . $row['class'] . '</td>
			<td class="membersRow' . (($i%2)+1) . '">' . $row['level'] . '</td>
			<td class="membersRowRight' . (($i%2)+1) . '"><button type="submit" class="input" onclick="setvalue(\'deletehide\',\'del_' . $row['member_id'] . '\');">' . $roster->locale->act['delete'] . '</button></td>
			</tr>' . "\n";
		$i++;
	}
	$body .= '
	</tbody>
</table>
' . border('sgreen','end');

	$body .= '</form>' . $prev . $listing . $next;
}
else
{
	$body .= '<span class="title_text">No Data</span>';
}



/**
 * Get character config data
 *
 * @return array on success, error string on failure
 */
function getCharData()
{
	global $roster, $listing, $next, $prev;

	$start = (isset($_GET['start']) ? $_GET['start'] : 0);

	if( !isset($_GET['guild']) )
	{
		return;
	}
	$sql = "SELECT "
		 . " `member_id`"
		 . " FROM `" . $roster->db->table('members') . "`"
		 . " WHERE `guild_id` = " . $_GET['guild'] . ";";

	// Get the number of rows
	$results = $roster->db->query($sql);

	$max = $roster->db->num_rows();

	if ($start > 0)
	{
		$prev = '<a href="' . makelink('&amp;guild=' . $_GET['guild'] . '&amp;start=0') . '">|&lt;&lt;</a>&nbsp;&nbsp;<a href="' . makelink('&amp;guild=' . $_GET['guild'] . '&amp;start=' . ($start-30)) . '">&lt;</a> ';
	}
	else
	{
		$prev = '';
	}

	if (($start+30) < $max)
	{
		$listing = ' <small>[' . $start . ' - ' . ($start+30) . '] of ' . $max . '</small>';
		$next = ' <a href="' . makelink('&amp;guild=' . $_GET['guild'] . '&amp;start=' . ($start+30)) . '">&gt;</a>&nbsp;&nbsp;<a href="' . makelink('&amp;guild=' . $_GET['guild'] . '&amp;start=' . ($max-30)) . '">&gt;&gt;|</a>';
	}
	else
	{
		$listing = ' <small>[' . $start . ' - ' . ($max) . '] of ' . $max . '</small>';
		$next = '';
	}

	$sql = "SELECT `member_id`, `name`, `server`, `region`, `class`, `level`"
		 . " FROM `" . $roster->db->table('members') . "`"
		 . " WHERE `guild_id` = " . $_GET['guild']
		 . " ORDER BY `name` ASC";


	if( $start != -1 )
	{
		$sql .= ' LIMIT ' . $start . ', 30;';
	}
	else
	{
		$sql .= ' LIMIT 0, 30;';
	}

	// Get the current config values
	$results = $roster->db->query($sql);
	if( !$results )
	{
		die_quietly( $roster->db->error(), 'Database Error',__FILE__,__LINE__,$sql);
	}

	$db_values = false;

	while( $row = $roster->db->fetch($results,SQL_ASSOC) )
	{
		foreach( $row as $field => $value )
		{
			$db_values[$row['name']][$field] = $value;
		}
	}

	$roster->db->free_result($results);

	return $db_values;
}
