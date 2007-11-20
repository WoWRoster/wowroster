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
		$body .= scrollboxtoggle($errors,'<span class="red">'.$roster->locale->act['update_errors'].'</span>','sred',false);

		// Print the downloadable errors separately so we can generate a download
		$body .= "<br />\n";
		$body .= '<form method="post" action="'.makelink().'" name="post">'."\n";
		$body .= '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($errors)).'" />'."\n";
		$body .= '<input type="hidden" name="send_file" value="error_log" />'."\n";
		$body .= '<input type="submit" name="download" value="'.$roster->locale->act['save_error_log'].'" />'."\n";
		$body .= '</form>';
		$body .= "<br />\n";
	}

	// Print the update messages
	$body .= scrollbox('<div style="text-align:left;font-size:10px;">'.$messages.'</div>',$roster->locale->act['update_log'],'syellow');

	// Print the downloadable messages separately so we can generate a download
	$body .= "<br />\n";
	$body .= '<form method="post" action="'.makelink().'" name="post">'."\n";
	$body .= '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($messages)).'" />'."\n";
	$body .= '<input type="hidden" name="send_file" value="update_log" />'."\n";
	$body .= '<input type="submit" name="download" value="'.$roster->locale->act['save_update_log'].'" />'."\n";
	$body .= '</form>';
	$body .= "<br />\n";
}

// Fetch data

$query = "SELECT * FROM `" . $roster->db->table('members') . "`";
$result = $roster->db->query($query);

if( !$result )
{
	die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
}


$data = $roster->db->fetch_all($result);

$roster->db->free_result($result);

// OUTPUT
$roster->output['body_onload'] .= 'initARC(\'allow\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

$body .= '<form action="' . makelink() . '" method="post" id="clean">
<input type="hidden" name="action" value="clean" />
<input type="hidden" name="process" value="process" />
<button type="submit" class="input">' . $roster->locale->act['clean'] . '</button></form>'."\n";

$body .= "<br />\n";

$body .= '<form action="' . makelink() . '" method="post" id="delete">
<input type="hidden" id="deletehide" name="action" value="" />
<input type="hidden" name="process" value="process" />'."\n";

$body .= border('sgreen','start',$roster->locale->act['delete']) . '
<table class="bodyline" cellspacing="0">
	<thead>
		<tr>
';
$name = $roster->locale->act['charname'];

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

foreach( $data as $row )
{
	$body .= "\n\t\t<tr>\n" . '
			<td class="membersRow1">' . $row['name'] . '</td>
			<td class="membersRow1">' . $row['server'] . '</td>
			<td class="membersRow1">' . $row['region'] . '</td>
			<td class="membersRow1">' . $row['class'] . '</td>
			<td class="membersRow1">' . $row['level'] . '</td>
			<td class="membersRowRight1"><button type="submit" class="input" onclick="setvalue(\'deletehide\',\'del_' . $row['member_id'] . '\');">' . $roster->locale->act['delete'] . '</button></td>
		</tr>' . "\n";
}
$body .= '
	</tbody>
</table>
' . border('sgreen','end');

$body .= '</form>';
