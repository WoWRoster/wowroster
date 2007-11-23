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
 * @version    SVN: $Id$
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

$sel_guild = ( $roster->atype == 'guild' ? $roster->anchor : false);

$start = (isset($_GET['start']) ? $_GET['start'] : 0);

$roster->output['title'] .= $roster->locale->act['pagebar_uploadrules'];

$mode = (isset($roster->pages[2]) && $roster->pages[2] == 'char')?'char':'guild';

// Process a new line
if( isset($_POST['process']) && $_POST['process'] == 'process')
{
	if( substr($_POST['action'],0,9) == 'delguild_' )
	{
		$update->deleteGuild( $sel_guild, time(), true );
	}
	elseif( isset($_POST['massdel']) )
	{
		$member_ids = array();
		foreach( $_POST['massdel'] as $member_id => $checked )
		{
			$member_ids[] = $member_id;
		}
		$member_ids = implode(',', $member_ids);

		$update->setMessage('<li>Deleting members "' . $member_ids . '".</li>');
		$update->deleteMembers( $member_ids );
	}
	elseif( substr($_POST['action'],0,4) == 'del_' )
	{
		$member_id = substr($_POST['action'],4);

		$update->setMessage('<li>Deleting member "' . $member_id . '".</li>');
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
			$options .= '			<option value="' . makelink("&amp;a=g:$id") . '"' . ( ( isset($sel_guild) && $id == $sel_guild) ? ' selected="selected"' : '' ) . '>' . $name . '</option>' . "\n";
		}
		$options .= '		</optgroup>';
	}
}

$roster->db->free_result($result);

$select_guild = '<form action="' . makelink('&amp;a=g:' . $sel_guild . '&amp;start=' . $start) . '" name="realm_select" method="post">
	<select name="guild" onchange="window.location.href=this.options[this.selectedIndex].value;">
		<option value="' . makelink() . '">----------</option>
' . $options . '
	</select>
</form>';

$body .= messagebox($select_guild,$roster->locale->act['select_guild'],'sgreen') . "<br />\n";

$listing = $next = $prev = '';



$data = getCharData();


// OUTPUT
$roster->output['body_onload'] .= 'initARC(\'delete\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

$body .= '<form action="' . makelink('&amp;a=g:' . $sel_guild . '&amp;start=' . $start) . '" method="post" id="clean">
	<input type="hidden" name="action" value="clean" />
	<input type="hidden" name="process" value="process" />
	<button type="submit" class="input">' . $roster->locale->act['clean'] . '</button>
</form>'."\n";

$body .= "<br />\n";

if( is_array($data) && count($data) > 0 )
{
	$body .= '<form action="' . makelink('&amp;a=g:' . $sel_guild . '&amp;start=' . $start) . '" method="post" id="delete">
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
			<td class="membersRowRight' . (($i%2)+1) . '"><button type="submit" class="input" onclick="setvalue(\'deletehide\',\'del_' . $row['member_id'] . '\');">' . $roster->locale->act['delete'] . '</button>
				<label for="massdel[' . $row['member_id'] . ']">&nbsp;</label><input type="checkbox" name="massdel[' . $row['member_id'] . ']" id="massdel[' . $row['member_id'] . ']" value="1" /></td>
		</tr>' . "\n";
		$i++;
	}
	$body .= '
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6" class="membersRowRight' . (($i%2)+1) . '">
				<button type="submit" class="input" style="float: right;">' . $roster->locale->act['delete_checked'] . '</button>
				<button type="submit" class="input" onclick="return confirm(\'' . $roster->locale->act['delete_guild_confirm'] . '\') &amp;&amp; setvalue(\'deletehide\',\'delguild_' . $sel_guild . '\');">' . $roster->locale->act['delete_guild'] . '</button></td>
		</tr>
	</tfoot>
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
	global $roster, $start, $listing, $next, $prev, $sel_guild;

	if( !$sel_guild )
	{
		return;
	}
	$sql = "SELECT "
		 . " `member_id`"
		 . " FROM `" . $roster->db->table('members') . "`"
		 . " WHERE `guild_id` = " . $sel_guild . ";";

	// Get the number of rows
	$results = $roster->db->query($sql);

	$max = $roster->db->num_rows();

	if ($start > 0)
	{
		$prev = '<a href="' . makelink('&amp;a=g:' . $sel_guild . '&amp;start=0') . '">|&lt;&lt;</a>&nbsp;&nbsp;<a href="' . makelink('&amp;a=g:' . $sel_guild . '&amp;start=' . ($start-30)) . '">&lt;</a> ';
	}
	else
	{
		$prev = '';
	}

	if (($start+30) < $max)
	{
		$listing = ' <small>[' . $start . ' - ' . ($start+30) . '] of ' . $max . '</small>';
		$next = ' <a href="' . makelink('&amp;a=g:' . $sel_guild . '&amp;start=' . ($start+30)) . '">&gt;</a>&nbsp;&nbsp;<a href="' . makelink('&amp;a=g:' . $sel_guild . '&amp;start=' . ($max-30)) . '">&gt;&gt;|</a>';
	}
	else
	{
		$listing = ' <small>[' . $start . ' - ' . ($max) . '] of ' . $max . '</small>';
		$next = '';
	}

	$sql = "SELECT `member_id`, `name`, `server`, `region`, `class`, `level`"
		 . " FROM `" . $roster->db->table('members') . "`"
		 . " WHERE `guild_id` = " . $sel_guild
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
