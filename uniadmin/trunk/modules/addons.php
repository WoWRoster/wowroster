<?php
/******************************
 * WoWRoster.net  UniAdmin
 * Copyright 2002-2007
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

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

include(UA_INCLUDEDIR.'addon_lib.php');

// Get Operation
$op = ( isset($_POST[UA_URI_OP]) ? $_POST[UA_URI_OP] : '' );

$id = ( isset($_POST[UA_URI_ID]) ? $_POST[UA_URI_ID] : '' );

$detail = ( isset($_POST[UA_URI_DETAIL]) ? $_POST[UA_URI_DETAIL] : ( isset($_GET[UA_URI_DETAIL]) ? $_GET[UA_URI_DETAIL] : '' ) );

// Decide What To Do
switch($op)
{
	case UA_URI_PROCESS:
		if( $user->data['level'] >= UA_ID_ADMIN )
			process_addon($_FILES['file']);
		break;

	case UA_URI_DELETE:
		if( $user->data['level'] >= UA_ID_ADMIN )
			delete_addon($id);
		break;

	case UA_URI_DELETE_ALL:
		if( $user->data['level'] >= UA_ID_ADMIN )
			delete_all_addons();
		break;

	case UA_URI_REQ:
	case UA_URI_OPT:
		if( $user->data['level'] >= UA_ID_POWER )
			toggle_addon($op,$id);
		break;

	case UA_URI_DISABLE:
	case UA_URI_ENABLE:
		if( $user->data['level'] >= UA_ID_POWER )
			toggle_addon($op,$id);
		break;

	case UA_URI_EDIT:
		if( $user->data['level'] >= UA_ID_POWER )
			edit_addon($id);
		break;

	default:
		break;
}

if( $detail != '' )
{
	addon_detail($detail);
}
else
{
	main();
}








/**
 * Addon Page Functions
 */


/**
 * Main Display
 */
function main( )
{
	global $db, $uniadmin, $user, $tpl;

	// Assign template vars
	$tpl->assign_vars(array(
		'L_ADDON_MANAGE'   => $user->lang['addon_management'],
		'L_NAME'           => $user->lang['name'],
		'L_TOC'            => $user->lang['toc'],
		'L_REQUIRED'       => $user->lang['required'],
		'L_OPTIONAL'       => $user->lang['optional'],
		'L_VERSION'        => $user->lang['version'],
		'L_UPLOADED'       => $user->lang['uploaded'],
		'L_ENABLED'        => $user->lang['enabled'],
		'L_DISABLED'       => $user->lang['disabled'],
		'L_FILES'          => $user->lang['files'],
		'L_DELETE'         => $user->lang['delete'],
		'L_DELETE_ALL'     => $user->lang['delete_all_addons'],
		'L_DISABLE_ENABLE' => $user->lang['disable_enable'],
		'L_SELECT_FILE'    => $user->lang['select_file'],
		'L_DOWNLOAD'       => $user->lang['download'],
		'L_ADD_UPDATE'     => $user->lang['add_update_addon'],
		'L_REQUIRED_ADDON' => $user->lang['required_addon'],
		'L_SELECT_FILE'    => $user->lang['select_file'],
		'L_HOMEPAGE'       => $user->lang['homepage'],
		'L_GO'             => $user->lang['go'],
		'L_FULLPATH'       => $user->lang['fullpath_addon'],
		'L_AUTOMATIC'      => $user->lang['automatic'],
		'L_ADDON_DETAILS'  => $user->lang['addon_details'],
		'L_MANAGE'         => $user->lang['manage'],
		'L_YES'            => $user->lang['yes'],
		'L_NO'             => $user->lang['no'],
		'L_NOTES'          => $user->lang['notes'],

		'L_NO_ADDONS'      => $user->lang['error_no_addon_in_db'],
		'L_CONFIRM_DELETE' => $user->lang['confirm_addons_delete'],

		'L_REQUIRED_TIP'   => $user->lang['addon_required_tip'],
		'L_FULLPATH_TIP'   => $user->lang['addon_fullpath_tip'],
		'L_SELECTFILE_TIP' => $user->lang['addon_selectfile_tip'],

		'S_ADDONS'         => true,
		'S_ADDON_ADD_DEL'  => false
		)
	);

	// Check admin
	if( $user->data['level'] == UA_ID_ADMIN )
	{
		$tpl->assign_var('S_ADDON_ADD_DEL',true);
		$tpl->assign_var('ONLOAD'," onload=\"initARC('ua_updateaddon','radioOn', 'radioOff','checkboxOn', 'checkboxOff');\"");
	}

	// Set string to "View Addons" if user is anonymous
	if( $user->data['level'] == UA_ID_ANON )
	{
		$tpl->assign_var('L_ADDON_MANAGE',$user->lang['view_addons']);
	}

	$sql = 'SELECT `a`.*, COUNT(`f`.`addon_id`) AS num_files
		FROM `'.UA_TABLE_ADDONS.'` AS a
		LEFT JOIN `'.UA_TABLE_FILES.'` AS f
		ON `a`.`id` = `f`.`addon_id`
		GROUP BY `a`.`id`
		ORDER BY name ASC;';

	$result = $db->query($sql);

	// Loop for every addon in database
	if( $db->num_rows($result) > 0 )
	{
		if( $db->num_rows($result) > 15 )
		{
			$tpl->assign_var('S_BELOW_15', true);
		}
		else
		{
			$tpl->assign_var('S_BELOW_15', false);
		}

		/* NOT USED YET $addon_in_db = array(); */
		while( $row = $db->fetch_record($result) )
		{
			if( substr($row['file_name'], 0, 7) == 'http://' )
			{
				$download = $row['file_name'];
			}
			else
			{
				$download = $uniadmin->url_path.$uniadmin->config['addon_folder'].'/'.$row['file_name'];
				/* NOT USED YET $addon_in_db[] = $row['file_name']; */
			}
			// Assign template vars
			$tpl->assign_block_vars('addons_row', array(
				'ROW_CLASS'   => $uniadmin->switch_row_class(),
				'ID'          => $row['id'],
				'HOMEPAGE'    => $row['homepage'],
				'ADDONNAME'   => $row['name'],
				'TOC'         => $row['toc'],
				'REQUIRED'    => $row['required'],
				'VERSION'     => $row['version'],
				'TIME'        => date($user->lang['time_format'],$row['time_uploaded']),
				'ENABLED'     => $row['enabled'],
				'NUMFILES'    => $row['num_files'],
				'DOWNLOAD'    => $download,
				'FILESIZE'    => $uniadmin->filesize_readable($row['filesize']),
				'NOTE'        => addslashes(htmlentities($row['notes']))
				)
			);
		}

/* NOT USED YET
		// Get a list of currently uploaded addons
		$uploaded_addons = $uniadmin->ls(UA_BASEDIR.$uniadmin->config['addon_folder'],array(),false);

		$addon_not_db = array();
		if( is_array($uploaded_addons) && count($uploaded_addons) > 0 )
		{
			foreach( $uploaded_addons as $addon )
			{
				$addon = basename($addon);
				if ( !in_array($addon,$addon_in_db) )
				{
					$addon_not_db[] = $addon;
				}
			}
		}
		if( count($addon_not_db) > 0 )
		{
			$uniadmin->error('<pre>'.print_r($addon_not_db,true).'</pre>');
		}
*/

	}
	else // Set var to display "No Addons"
	{
		$tpl->assign_var('S_ADDONS',false);
	}

	$db->free_result($result);

	$uniadmin->set_vars(array(
		'page_title'    => $user->lang['title_addons'],
		'template_file' => 'addons.html',
		'display'       => true
		)
	);
}

function addon_detail( $addon_id )
{
	global $db, $uniadmin, $user, $tpl;

	// Assign template vars
	$tpl->assign_vars(array(
		'L_ADDON_DETAILS'  => $user->lang['addon_details'],
		'L_NAME'           => $user->lang['name'],
		'L_TOC'            => $user->lang['toc'],
		'L_REQUIRED'       => $user->lang['required'],
		'L_OPTIONAL'       => $user->lang['optional'],
		'L_VERSION'        => $user->lang['version'],
		'L_UPLOADED'       => $user->lang['uploaded'],
		'L_ENABLED'        => $user->lang['enabled'],
		'L_DISABLED'       => $user->lang['disabled'],
		'L_FILES'          => $user->lang['files'],
		'L_DELETE'         => $user->lang['delete'],
		'L_DISABLE_ENABLE' => $user->lang['disable_enable'],
		'L_SELECT_FILE'    => $user->lang['select_file'],
		'L_DOWNLOAD'       => $user->lang['download'],
		'L_ADD_UPDATE'     => $user->lang['add_update_addon'],
		'L_UPDATE'         => $user->lang['update_addon'],
		'L_REQUIRED_ADDON' => $user->lang['required_addon'],
		'L_SELECT_FILE'    => $user->lang['select_file'],
		'L_HOMEPAGE'       => $user->lang['homepage'],
		'L_GO'             => $user->lang['go'],
		'L_MANAGE'         => $user->lang['manage'],
		'L_YES'            => $user->lang['yes'],
		'L_NO'             => $user->lang['no'],
		'L_NOTES'          => $user->lang['notes'],
		'L_EDIT'           => $user->lang['edit'],
		'L_CANCEL'         => $user->lang['cancel'],

		'S_ADDON_ADD_DEL'  => false,
		'S_FILES'          => false,
		)
	);

	// Check admin
	if( $user->data['level'] == UA_ID_ADMIN )
	{
		$tpl->assign_var('S_ADDON_ADD_DEL',true);
	}

	// If anonymous, cahnge to "View Addons"
	if( $user->data['level'] == UA_ID_ANON )
	{
		$tpl->assign_var('L_ADDON_MANAGE',$user->lang['view_addons']);
	}

	$sql = 'SELECT * FROM `'.UA_TABLE_ADDONS."` WHERE `id` = '$addon_id';";

	$result = $db->query($sql);

	$row = $db->fetch_record($result);

	// Get all files
	if( $db->num_rows($result) > 0 )
	{
		$sql = 'SELECT * FROM `'.UA_TABLE_FILES."` WHERE `addon_id` = '$addon_id' ORDER BY `filename` ASC;";

		$result2 = $db->query($sql);

		$num_files = $db->num_rows($result2);

		// Loop and assign to tpl vars
		// Also generate a HTML list
		// Themes can decide whether to display file list, or a html <li> list
		if( $num_files > 0 )
		{
			$tpl->assign_var('S_FILES', true);

			$addonsArray = array();
			while( $row2 = $db->fetch_record($result2) )
			{
				$tpl->assign_block_vars('files_row',array(
					'ROW_CLASS' => $uniadmin->switch_row_class(),
					'FILE'      => $row2['filename'],
					'FILEPATH'  => dirname($row2['filename']),
					'FILENAME'  => basename($row2['filename']),
					'MD5'       => $row2['md5sum']
					)
				);

				// Add to list for dir tree parsing
				addToList($row2['filename'],$row2['md5sum'],$addonsArray);
			}

			//$uniadmin->error('<pre>'.print_r($addonsArray,true).'</pre>');

			// Parse the dir tree array into an html list
			$htmllist = '';
			arrayToLi($addonsArray,$htmllist);
			$tpl->assign_var('FILE_HTML_LIST',$htmllist);
		}

		if( substr($row['file_name'], 0, 7) == 'http://' )
		{
			$download = $row['file_name'];
		}
		else
		{
			$download = $uniadmin->url_path.$uniadmin->config['addon_folder'].'/'.$row['file_name'];
		}

		// Assign template vars
		$tpl->assign_vars(array(
			'ID'          => $row['id'],
			'HOMEPAGE'    => $row['homepage'],
			'ADDONNAME'   => $row['name'],
			'TOC'         => $row['toc'],
			'REQUIRED'    => $row['required'],
			'VERSION'     => $row['version'],
			'TIME'        => date($user->lang['time_format'],$row['time_uploaded']),
			'ENABLED'     => $row['enabled'],
			'NUMFILES'    => $num_files,
			'DOWNLOAD'    => $download,
			'FILESIZE'    => $uniadmin->filesize_readable($row['filesize']),
			'NOTES'       => htmlentities($row['notes'])
			)
		);
	}
	else
	{
		ua_die(sprintf($user->lang['error_addon_not_exist'],$addon_id));
	}
	$db->free_result($result);

	$uniadmin->set_vars(array(
		'page_title'    => $user->lang['title_addons'],
		'template_file' => 'addon_detail.html',
		'display'       => true
		)
	);
}

/**
 * Toggle Addon
 *
 * @param string $op
 * @param string $addon_id
 */
function toggle_addon( $op , $addon_id )
{
	global $db, $user, $uniadmin;

	if( !empty($op) && !empty($addon_id) )
	{
		switch( $op )
		{
			case UA_URI_DISABLE:
				$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `enabled` = '0' WHERE `id` = '$addon_id';";
				$error = 'disable';
				break;

			case UA_URI_ENABLE:
				$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `enabled` = '1' WHERE `id` = '$addon_id';";
				$error = 'enable';
				break;

			case UA_URI_OPT:
				$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `required` = '0' WHERE `id` = '$addon_id';";
				$error = 'optional';
				break;

			case UA_URI_REQ:
				$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `required` = '1' WHERE `id` = '$addon_id';";
				$error = 'require';
				break;

			default:
				break;
		}
		$db->query($sql);
		if( !$db->affected_rows() )
		{
		    $uniadmin->error($user->lang['error_'.$error.'_addon']);
			$uniadmin->error(sprintf($user->lang['sql_error_addons_'.$error],$addon_id));
		}
	}
}

/**
 * Deletes an addon from the addon_zip directory and the database
 *
 * @param int $addon_id
 */
function delete_addon( $addon_id )
{
	global $db, $user, $uniadmin;

	$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `id` = '$addon_id'";
	$result = $db->query($sql);

	if( $db->num_rows($result) > 0 )
	{
		$row = $db->fetch_record($result);

		if( substr($row['file_name'], 0, 7) != 'http://' )
		{
			$local_path = UA_BASEDIR.$uniadmin->config['addon_folder'].DIR_SEP.$row['file_name'];
			$try_unlink = @unlink($local_path);
			if( !$try_unlink )
			{
				$uniadmin->error($user->lang['error_delete_addon']);
				$uniadmin->error(sprintf($user->lang['error_unlink'],$local_path));
			}
		}

		$sql = "DELETE FROM `".UA_TABLE_ADDONS."` WHERE `id` = '$addon_id'";
		$db->query($sql);
		if( !$db->affected_rows() )
		{
		    $uniadmin->error(sprintf($user->lang['sql_error_addons_delete'],$addon_id));
		}

		$sql = "DELETE FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '$addon_id';";
		$db->query($sql);
		if( !$db->affected_rows() )
		{
		    $uniadmin->error(sprintf($user->lang['sql_error_addons_delete'],$addon_id));
		}
	}
}

/**
 * Deletes all addons from the addon_zip directory and the database
 */
function delete_all_addons( )
{
	global $db, $user, $uniadmin;

	$sql = "TRUNCATE TABLE `".UA_TABLE_ADDONS."`;";
	$result = $db->query($sql);

	$sql = "TRUNCATE TABLE `".UA_TABLE_FILES."`;";
	$result = $db->query($sql);

	$uniadmin->cleardir(UA_BASEDIR.$uniadmin->config['addon_folder']);

	$uniadmin->message($user->lang['all_addons_delete']);
}

function edit_addon( $addon_id )
{
	global $db, $user, $uniadmin;

	$addon_name = strip_tags(stripslashes($_POST['name']));
	$addon_toc = strip_tags(stripslashes($_POST['toc']));
	$addon_url = strip_tags(stripslashes($_POST['homepage']));
	$addon_version = strip_tags(stripslashes($_POST['version']));
	$addon_notes = str_replace(array("\r","\n"),array('',' '),strip_tags(stripslashes($_POST['notes'])));

	// Insert Main Addon data
	$sql = "UPDATE `".UA_TABLE_ADDONS."` SET
		`version` = '".$db->escape($addon_version)."',
		`name` = '".$db->escape($addon_name)."',
		`homepage` = '".$db->escape($addon_url)."',
		`notes` = '".$db->escape($addon_notes)."',
		`toc` = '".$db->escape($addon_toc)."'
		WHERE `id` = '$addon_id';";

	$db->query($sql);

	$uniadmin->message(sprintf($user->lang['addon_edited'],$addon_name));
}
