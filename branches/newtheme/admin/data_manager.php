<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster upload rule config
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: data_manager.php 1791 2008-06-15 16:59:24Z Zanix $
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
*/

if( !defined('IN_ROSTER') || !defined('IN_ROSTER_ADMIN') )
{
    exit('Detected invalid access to this file!');
}

include( ROSTER_LIB . 'update.lib.php' );
$update = new update;

$start = (isset($_GET['start']) ? $_GET['start'] : 0);

$roster->output['title'] .= $roster->locale->act['pagebar_uploadrules'];
$roster->output['body_onload'] .= "initARC('delete','radioOn','radioOff','checkboxOn','checkboxOff');";

// Change scope to guild, and rerun detection to load default
$roster->scope = 'guild';
$roster->get_scope_data();


$roster->tpl->assign_vars(array(
	'U_ACTION'   => makelink('&amp;start=' . $start),
	'U_GUILD_ID' => $roster->data['guild_id'],

	'S_DATA'           => false,
	'S_RESPONSE'       => false,
	'S_RESPONSE_ERROR' => false,

	'L_CLEAN'          => $roster->locale->act['clean'],
	'L_SAVE_ERROR_LOG' => $roster->locale->act['save_error_log'],
	'L_SAVE_LOG'       => $roster->locale->act['save_update_log'],

	'L_NAME'           => $roster->locale->act['name'],
	'L_SERVER'         => $roster->locale->act['server'],
	'L_REGION'         => $roster->locale->act['region'],
	'L_CLASS'          => $roster->locale->act['class'],
	'L_LEVEL'          => $roster->locale->act['level'],
	'L_UPDATE_ERRORS'  => $roster->locale->act['update_errors'],
	'L_UPDATE_LOG'     => $roster->locale->act['update_log'],
	'L_DELETE'         => $roster->locale->act['delete'],
	'L_DELETE_CHECKED' => $roster->locale->act['delete_checked'],
	'L_DELETE_GUILD'   => $roster->locale->act['delete_guild'],
	'L_DELETE_GUILD_CONFIRM' => $roster->locale->act['delete_guild_confirm'],
	)
);


/**
 * Process a new line
 */
if( isset($_POST['process']) && $_POST['process'] == 'process' )
{
	// We have a response
	$roster->tpl->assign_var('S_RESPONSE',true);

	if( substr($_POST['action'],0,9) == 'delguild_' )
	{
		$sel_guild = substr($_POST['action'],9);
		$update->deleteGuild( $sel_guild, time() );

		$roster->scope = 'none';
		$roster->anchor = '';
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
		// We have errors
		$roster->tpl->assign_vars(array(
			'S_RESPONSE_ERROR'   => true,
			'RESPONSE_ERROR'     => $errors,
			'RESPONSE_ERROR_LOG' => htmlspecialchars(stripAllHtml($errors)),
			)
		);
	}

	$roster->tpl->assign_vars(array(
		'RESPONSE'      => $messages,
		'RESPONSE_POST' => htmlspecialchars(stripAllHtml($messages)),
		)
	);
}


/**
 * Actual list
 */
$query = "SELECT "
	. " COUNT( `member_id` )"
	. " FROM `" . $roster->db->table('members') . "`"
	. " WHERE `guild_id` = " . ( isset($roster->data['guild_id']) ? $roster->data['guild_id'] : 0 ) . ";";

$num_members = $roster->db->query_first($query);

if( $num_members > 0 )
{
	$roster->tpl->assign_var('S_DATA',true);

	// Draw the header line
	if ($start > 0)
	{
		$prev = '<a href="' . makelink('&amp;start=0') . '">|&lt;&lt;</a>&nbsp;&nbsp;<a href="' . makelink('&amp;start=' . ($start - 30)) . '">&lt;</a> ';
	}
	else
	{
		$prev = '';
	}

	if (($start+30) < $num_members)
	{
		$listing = ' <small>[' . $start . ' - ' . ($start+30) . '] of ' . $num_members . '</small>';
		$next = ' <a href="' . makelink('&amp;start=' . ($start+30)) . '">&gt;</a>&nbsp;&nbsp;<a href="' . makelink('&amp;start=' . ( floor( $num_members / 30) * 30 )) . '">&gt;&gt;|</a>';
	}
	else
	{
		$listing = ' <small>[' . $start . ' - ' . ($num_members) . '] of ' . $num_members . '</small>';
		$next = '';
	}

	$roster->tpl->assign_vars(array(
		'PREV'    => $prev,
		'NEXT'    => $next,
		'LISTING' => $listing
		)
	);

	$i=0;

	$query = "SELECT `member_id`, `name`, `server`, `region`, `class`, `level`"
		. " FROM `" . $roster->db->table('members') . "`"
		. " WHERE `guild_id` = " . $roster->data['guild_id']
		. " ORDER BY `name` ASC"
		. " LIMIT " . ($start > 0 ? $start : 0) . ", 30;";

	$result = $roster->db->query($query);

	while( $row = $roster->db->fetch($result) )
	{
		$roster->tpl->assign_block_vars('data_list', array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'ID'        => $row['member_id'],
			'NAME'      => $row['name'],
			'SERVER'    => $row['server'],
			'REGION'    => $row['region'],
			'CLASS'     => $row['class'],
			'LEVEL'     => $row['level'],
			)
		);

		$i++;
	}

	$roster->db->free_result($result);
}

$roster->tpl->set_filenames(array('body' => 'admin/data_manager.html'));
$body = $roster->tpl->fetch('body');
