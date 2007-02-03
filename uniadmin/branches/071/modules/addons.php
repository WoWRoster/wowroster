<?php
/******************************
 * WoWRoster.net  UniAdmin
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

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

// Get Operation
$op = ( isset($_POST[UA_URI_OP]) ? $_POST[UA_URI_OP] : '' );

$id = ( isset($_POST[UA_URI_ID]) ? $_POST[UA_URI_ID] : '' );

$detail = ( isset($_POST[UA_URI_DETAIL]) ? $_POST[UA_URI_DETAIL] : ( isset($_GET[UA_URI_DETAIL]) ? $_GET[UA_URI_DETAIL] : '' ) );

// Decide What To Do
switch($op)
{
	case UA_URI_PROCESS:
		if( $user->data['level'] >= UA_ID_ADMIN )
			process_addon();
		break;

	case UA_URI_DELETE:
		if( $user->data['level'] >= UA_ID_ADMIN )
			delete_addon($id);
		break;

	case UA_URI_REQ:
		if( $user->data['level'] >= UA_ID_POWER )
			require_addon($id);
		break;

	case UA_URI_OPT:
		if( $user->data['level'] >= UA_ID_POWER )
			optional_addon($id);
		break;

	case UA_URI_DISABLE:
		if( $user->data['level'] >= UA_ID_POWER )
			disable_addon($id);
		break;

	case UA_URI_ENABLE:
		if( $user->data['level'] >= UA_ID_POWER )
			enable_addon($id);
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

	if( isset($_REQUEST['s']) || isset($_REQUEST['d']) )
	{
		$orderby = $_REQUEST['s'];
		$direction = $_REQUEST['d'];
	}
	else
	{
		$orderby = 'name';
		$direction = 'ASC';
	}

	$sql = 'SELECT `a`.*, COUNT(`f`.`addon_id`) AS num_files FROM `'.UA_TABLE_ADDONS.'` AS a LEFT JOIN `'.UA_TABLE_FILES.'` AS f ON `a`.`id` = `f`.`addon_id` GROUP BY `a`.`id` ORDER BY '.$orderby.' '.$direction.';';

	$direction = ( $direction == 'ASC' ) ? 'DESC' : 'ASC';

	$tpl->assign_vars(array(
		'L_ADDON_MANAGE'   => $user->lang['addon_management'],
		'L_NAME'           => $user->lang['name'],
		'L_TOC'            => $user->lang['toc'],
		'L_REQUIRED'       => $user->lang['required'],
		'L_VERSION'        => $user->lang['version'],
		'L_UPLOADED'       => $user->lang['uploaded'],
		'L_ENABLED'        => $user->lang['enabled'],
		'L_FILES'          => $user->lang['files'],
		'L_DELETE'         => $user->lang['delete'],
		'L_DISABLE_ENABLE' => $user->lang['disable_enable'],
		'L_SELECT_FILE'    => $user->lang['select_file'],
		'L_DOWNLOAD'       => $user->lang['download'],
		'L_ADD_UPDATE'     => $user->lang['add_update_addon'],
		'L_REQUIRED_ADDON' => $user->lang['required_addon'],
		'L_SELECT_FILE'    => $user->lang['select_file'],
		'L_HOMEPAGE'       => $user->lang['homepage'],
		'L_GO'             => $user->lang['go'],
		'L_FULLPATH'       => $user->lang['fullpath_addon'],
		'L_ADDON_DETAILS'  => $user->lang['addon_details'],
		'L_MANAGE'         => $user->lang['manage'],
		'L_YES'            => $user->lang['yes'],
		'L_NO'             => $user->lang['no'],
		'L_NOTES'          => $user->lang['notes'],

		'L_NO_ADDONS'      => $user->lang['error_no_addon_in_db'],

		'L_REQUIRED_TIP'   => $user->lang['addon_required_tip'],
		'L_FULLPATH_TIP'   => $user->lang['addon_fullpath_tip'],
		'L_SELECTFILE_TIP' => $user->lang['addon_selectfile_tip'],

		'S_ADDONS'         => true,
		'S_ADDON_ADD_DEL'  => false,
		'U_DIRECTION'      => $direction,
		'U_DETAIL'         => UA_URI_DETAIL,
		)
	);

	if( $user->data['level'] == UA_ID_ADMIN )
	{
		$tpl->assign_var('S_ADDON_ADD_DEL',true);
		$tpl->assign_var('ONLOAD'," onload=\"initARC('ua_updateaddon','radioOn', 'radioOff','checkboxOn', 'checkboxOff');\"");
	}

	if( $user->data['level'] == UA_ID_ANON )
	{
		$tpl->assign_var('L_ADDON_MANAGE',$user->lang['view_addons']);
	}

	$result = $db->query($sql);

	if( $db->num_rows($result) > 0 )
	{
		while( $row = $db->fetch_record($result) )
		{
			$addon_name = $row['name'];
			$homepage = $row['homepage'];
			$version = $row['version'];
			$num_files = $row['num_files'];
			$time = date($user->lang['time_format'],$row['time_uploaded']);
			$url = $uniadmin->url_path.$uniadmin->config['addon_folder'].'/'.$row['file_name'];
			$addon_id = $row['id'];
			$filesize = $uniadmin->filesize_readable($row['filesize']);
			$notes = $row['notes'];

			if( $row['enabled'] == '1' )
			{
				$enabled = '<form name="ua_disableaddon_'.$addon_id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_DISABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addon_id.'" />
	<input class="icon" src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/enabled_mini.png" type="image" value="'.$user->lang['enabled'].'" alt="" onmouseover="return overlib(\''.$user->lang['enabled'].'\');" onmouseout="return nd();" />
</form>';
				if( $user->data['level'] <= UA_ID_USER )
				{
					$enabled = '<img src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/enabled_mini.png" class="icon" alt="" onmouseover="return overlib(\''.$user->lang['enabled'].'\');" onmouseout="return nd();" />';
				}
			}
			else
			{
				$enabled = '<form name="ua_enableaddon_'.$addon_id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_ENABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addon_id.'" />
	<input class="icon" src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/disabled_mini.png" type="image" value="'.$user->lang['disabled'].'" alt="" onmouseover="return overlib(\''.$user->lang['disabled'].'\');" onmouseout="return nd();" />
</form>';
				if( $user->data['level'] <= UA_ID_USER )
				{
					$enabled = '<img src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/disabled_mini.png" class="icon" alt="" onmouseover="return overlib(\''.$user->lang['disabled'].'\');" onmouseout="return nd();" />';
				}
			}

			if( $row['homepage'] == '' )
			{
				$homepage = './';
			}

			if( $row['required'] == 1 )
			{
				$required = '<form name="ua_optionaladdon_'.$addon_id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_OPT.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addon_id.'" />
	<input class="icon" src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/required_mini.png" type="image" value="'.$user->lang['required'].'" alt="" onmouseover="return overlib(\''.$user->lang['required'].'\');" onmouseout="return nd();" />
</form>';
				if( $user->data['level'] <= UA_ID_USER )
				{
					$required = '<img src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/required_mini.png" class="icon" alt="" onmouseover="return overlib(\''.$user->lang['required'].'\');" onmouseout="return nd();" />';
				}
			}
			else
			{
				$required = '<form name="ua_requireaddon_'.$addon_id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_REQ.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addon_id.'" />
	<input class="icon" src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/optional_mini.png" type="image" value="'.$user->lang['optional'].'" alt="" onmouseover="return overlib(\''.$user->lang['optional'].'\');" onmouseout="return nd();" />
</form>';
				if( $user->data['level'] <= UA_ID_USER )
				{
					$required = '<img src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/optional_mini.png" class="icon" alt="" onmouseover="return overlib(\''.$user->lang['optional'].'\');" onmouseout="return nd();" />';
				}
			}

			$toc = $row['toc'];

			$tpl->assign_block_vars('addons_row', array(
				'ROW_CLASS'   => $uniadmin->switch_row_class(),
				'ID'          => $addon_id,
				'HOMEPAGE'    => $homepage,
				'ADDONNAME'   => $addon_name,
				'TOC'         => $toc,
				'REQUIRED'    => $required,
				'VERSION'     => $version,
				'TIME'        => $time,
				'ENABLED'     => $enabled,
				'NUMFILES'    => $num_files,
				'DOWNLOAD'    => $url,
				'FILESIZE'    => $filesize,
				'NOTES'       => addslashes(htmlentities($notes))
				)
			);
		}
	}
	else
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

function addon_detail( $id )
{
	global $db, $uniadmin, $user, $tpl;

	$tpl->assign_vars(array(
		'L_ADDON_DETAILS'  => $user->lang['addon_details'],
		'L_NAME'           => $user->lang['name'],
		'L_TOC'            => $user->lang['toc'],
		'L_REQUIRED'       => $user->lang['required'],
		'L_VERSION'        => $user->lang['version'],
		'L_UPLOADED'       => $user->lang['uploaded'],
		'L_ENABLED'        => $user->lang['enabled'],
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

		'S_ADDONS'         => true,
		'S_ADDON_ADD_DEL'  => false,
		'S_FILES'          => false,
		)
	);

	if( $user->data['level'] == UA_ID_ADMIN )
	{
		$tpl->assign_var('S_ADDON_ADD_DEL',true);
	}

	if( $user->data['level'] == UA_ID_ANON )
	{
		$tpl->assign_var('L_ADDON_MANAGE',$user->lang['view_addons']);
	}

	$sql = 'SELECT * FROM `'.UA_TABLE_ADDONS."` WHERE `id` = '$id';";

	$result = $db->query($sql);

	$row = $db->fetch_record($result);

	if( $db->num_rows($result) > 0 )
	{
		$addon_name = $row['name'];
		$homepage = $row['homepage'];
		$version = $row['version'];
		$time = date($user->lang['time_format'],$row['time_uploaded']);
		$url = $uniadmin->url_path.$uniadmin->config['addon_folder'].'/'.$row['file_name'];
		$addon_id = $row['id'];
		$filesize = $uniadmin->filesize_readable($row['filesize']);
		$notes = $row['notes'];

		$sql = 'SELECT * FROM `'.UA_TABLE_FILES."` `addon_id` WHERE `addon_id` = '$id';";
		$result2 = $db->query($sql);

		$num_files = $db->num_rows($result2);

		if( $db->num_rows($result2) > 0 )
		{
			$tpl->assign_var('S_FILES', true);

			while( $row2 = $db->fetch_record($result2) )
			{
				$tpl->assign_block_vars('files_row',array(
					'ROW_CLASS' => $uniadmin->switch_row_class(),
					'FILENAME'  => $row2['filename'],
					'MD5'       => $row2['md5sum']
					)
				);
			}
		}

		if( $row['enabled'] == '1' )
		{
			$enabled = '<form name="ua_disableaddon" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_DETAIL.'" value="'.$addon_id.'" />
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_DISABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addon_id.'" />
	<input class="icon" src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/enabled.png" type="image" value="'.$user->lang['enabled'].'" alt="" onmouseover="return overlib(\''.$user->lang['enabled'].'\');" onmouseout="return nd();" />
</form>';
			if( $user->data['level'] <= UA_ID_USER )
			{
				$enabled = '<img src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/enabled.png" class="icon" alt="" onmouseover="return overlib(\''.$user->lang['enabled'].'\');" onmouseout="return nd();" />';
			}
		}
		else
		{
			$enabled = '<form name="ua_enableaddon" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_DETAIL.'" value="'.$addon_id.'" />
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_ENABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addon_id.'" />
	<input class="icon" src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/disabled.png" type="image" value="'.$user->lang['disabled'].'" alt="" onmouseover="return overlib(\''.$user->lang['disabled'].'\');" onmouseout="return nd();" />
</form>';
			if( $user->data['level'] <= UA_ID_USER )
			{
				$enabled = '<img src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/disabled.png" class="icon" alt="" onmouseover="return overlib(\''.$user->lang['disabled'].'\');" onmouseout="return nd();" />';
			}
		}

		if( $row['homepage'] == '' )
		{
			$homepage = './';
		}

		if( $row['required'] == 1 )
		{
			$required = '<form name="ua_optionaladdon" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_DETAIL.'" value="'.$addon_id.'" />
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_OPT.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addon_id.'" />
	<input class="icon" src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/required.png" type="image" value="'.$user->lang['required'].'" alt="" onmouseover="return overlib(\''.$user->lang['required'].'\');" onmouseout="return nd();" />
</form>';
			if( $user->data['level'] <= UA_ID_USER )
			{
				$required = '<img src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/required.png" class="icon" alt="" onmouseover="return overlib(\''.$user->lang['required'].'\');" onmouseout="return nd();" />';
			}
		}
		else
		{
			$required = '<form name="ua_requireaddon" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_DETAIL.'" value="'.$addon_id.'" />
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_REQ.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addon_id.'" />
	<input class="icon" src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/optional.png" type="image" value="'.$user->lang['optional'].'" alt="" onmouseover="return overlib(\''.$user->lang['optional'].'\');" onmouseout="return nd();" />
</form>';
			if( $user->data['level'] <= UA_ID_USER )
			{
				$required = '<img src="'.$uniadmin->url_path . 'styles/' . $user->style.'/images/optional.png" class="icon" alt="" onmouseover="return overlib(\''.$user->lang['optional'].'\');" onmouseout="return nd();" />';
			}
		}

		$toc = $row['toc'];

		$tpl->assign_vars(array(
			'ID'          => $addon_id,
			'HOMEPAGE'    => $homepage,
			'ADDONNAME'   => $addon_name,
			'TOC'         => $toc,
			'REQUIRED'    => $required,
			'VERSION'     => $version,
			'TIME'        => $time,
			'ENABLED'     => $enabled,
			'NUMFILES'    => $num_files,
			'DOWNLOAD'    => $url,
			'FILESIZE'    => $filesize,
			'NOTES'       => $notes
			)
		);
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
 * Disables an addon
 *
 * @param int $id
 */
function disable_addon( $id )
{
	global $db, $user, $uniadmin;

	$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `enabled` = '0' WHERE `id` = '$id' LIMIT 1 ;";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
	    $uniadmin->debug($user->lang['error_disable_addon']);
		$uniadmin->debug(sprintf($user->lang['sql_error_addons_disable'],$id));
	}
}

/**
 * Enables an addon
 *
 * @param int $id
 */
function enable_addon( $id )
{
	global $db, $user, $uniadmin;

	$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `enabled` = '1' WHERE `id` = '$id' LIMIT 1 ;";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
	    $uniadmin->debug($user->lang['error_enable_addon']);
		$uniadmin->debug(sprintf($user->lang['sql_error_addons_enable'],$id));
	}
}

/**
 * Sets an addon to required
 *
 * @param int $id
 */
function require_addon( $id )
{
	global $db, $user, $uniadmin;

	$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `required` = '1' WHERE `id` = '$id' LIMIT 1 ;";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
	    $uniadmin->debug($user->lang['error_require_addon']);
		$uniadmin->debug(sprintf($user->lang['sql_error_addons_require'],$id));
	}
}

/**
 * Sets an addon to optional
 *
 * @param int $id
 */
function optional_addon( $id )
{
	global $db, $user, $uniadmin;

	$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `required` = '0' WHERE `id` = '$id' LIMIT 1 ;";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
	    $uniadmin->debug($user->lang['error_optional_addon']);
		$uniadmin->debug(sprintf($user->lang['sql_error_addons_optional'],$id));
	}
}

/**
 * Deletes an addon from the server and the database
 *
 * @param int $id
 */
function delete_addon( $id )
{
	global $db, $user, $uniadmin;

	$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `id` = '$id'";
	$result = $db->query($sql);
	$row = $db->fetch_record($result);

	$id = $row['id'];
	$addon_file_name = $row['file_name'];

	$local_path = UA_BASEDIR.$uniadmin->config['addon_folder'].DIR_SEP.$addon_file_name;
	$try_unlink = @unlink($local_path);
	if( !$try_unlink )
	{
		$uniadmin->debug($user->lang['error_delete_addon']);
		$uniadmin->debug(sprintf($user->lang['error_unlink'],$local_path));
	}

	$sql = "DELETE FROM `".UA_TABLE_ADDONS."` WHERE `id` = '$id'";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
	    $uniadmin->debug(sprintf($user->lang['sql_error_addons_delete'],$id));
	}

	$sql = "DELETE FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '$id';";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
	    $uniadmin->debug(sprintf($user->lang['sql_error_addons_delete'],$id));
	}
}

/**
 * Processess an uploaded addon for insertion into the database
 */
function process_addon()
{
	global $db, $user, $uniadmin;

	$temp_file_name = $_FILES['file']['tmp_name'];

	if( !empty($temp_file_name) )
	{
		if( $uniadmin->get_file_ext($_FILES['file']['name']) != 'zip' )
		{
			$uniadmin->message($user->lang['error_zip_file']);
			return;
		}

		$addon_file_name = str_replace(' ','_',$_FILES['file']['name']);

		$addon_zip_folder = UA_BASEDIR.$uniadmin->config['addon_folder'];
		$temp_folder = UA_BASEDIR.$uniadmin->config['temp_analyze_folder'];

		if( isset($_POST['required']) && $_POST['required'] == '1' )
		{
			$required = 1;
		}
		else
		{
			$required = 0;
		}

		// Name and location of the zip file
		$zip_file = $addon_zip_folder.DIR_SEP.$addon_file_name;

		// Delete Addon if it exists
		@unlink($zip_file);

		// Try to move to the addon_temp directory
		$try_move = move_uploaded_file($temp_file_name,$zip_file);
		if( !$try_move )
		{
			$uniadmin->debug(sprintf($user->lang['error_move_uploaded_file'],$temp_file_name,$zip_file));
			return;
		}

		// Try to set write access on the uploaded file
		if( !is_writeable($zip_file) )
		{
			$try_chmod = @chmod($zip_file,0777);
			if( !$try_chmod )
			{
				$uniadmin->debug(sprintf($user->lang['error_chmod'],$zip_file));
				return;
			}
		}

		// Get the file size
		$file_size = @filesize($zip_file);

		// Unzip the file
		$uniadmin->unzip($zip_file,$temp_folder.DIR_SEP);

		$files = $uniadmin->ls($temp_folder,array());

		// Get the TOC of the addon
		$toc_file_name = '';
		$toc_files = array();
		$revision_files = array();

		if( is_array($files) )
		{
			foreach( $files as $file )
			{
				if( $uniadmin->get_file_ext($file) == 'toc' )
				{
					$toc_files[] = $file;
					continue;
				}
				elseif( strpos($file, 'changelog-r') && $uniadmin->get_file_ext($file) == 'txt' )
				{
					$revision_files[] = $file;
					continue;
				}
			}

			if( count($toc_files) > 0 )
			{
				foreach( $toc_files as $file )
				{
					$toc_number = get_toc_val($file, 'Interface', '00000');

					$k = explode(DIR_SEP,$file);
					$toc_file_name = $k[count($k) - 1];
					$toc_file_name = substr($toc_file_name,0,count($toc_file_name) -5);

					$real_addon_name = get_toc_val($file, 'Title', $toc_file_name);

					if( strpos($real_addon_name, 'Ace') && strpos($real_addon_name, '|r'))
					{
						$real_addon_name = trim(substr($real_addon_name, 0, strpos(substr($real_addon_name, 0, strpos($real_addon_name, '|r')), '|')));
					}

					// Get version
					$revision = '';
					$version = get_toc_val($file, 'Version', '');
					$rev_matches = null;

					if( count($revision_files) > 0 )
					{
						asort($revision_files);

						foreach( $revision_files as $revision_file_name )
						{
							preg_match('|changelog\-r(.+?).txt|',$revision_file_name,$rev_matches);

							$revision = $rev_matches[1];
						}

						if( !empty($version) && !empty($revision) && ($version != $revision) )
						{
							$version .= " | r$revision";
						}
						elseif( empty($version) && !empty($revision) )
						{
							$version = "r$revision";
						}
					}

					// Set notes and homepage
					$homepage = get_toc_val($file, 'X-Website', get_toc_val($file, 'URL', ''));
					$notes = get_toc_val($file, 'Notes', '');

					if( strpos(strtolower($addon_file_name), strtolower(str_replace(' ','_',$toc_file_name))) !== false )
					{
						break;
					}
					else
					{
						unset($k, $toc_file_name);
					}
				}
			}
			else
			{
				$try_unlink = @unlink($zip_file);
				if( !$try_unlink )
				{
					$uniadmin->debug(sprintf($user->lang['error_unlink'],$zip_file));
				}
				$uniadmin->cleardir($temp_folder);
				$uniadmin->debug($user->lang['error_no_toc_file']);
				return;
			}
		}
		else
		{
			$try_unlink = @unlink($zip_file);
			if( !$try_unlink )
			{
				$uniadmin->debug(sprintf($user->lang['error_unlink'],$zip_file));
			}
			$uniadmin->cleardir($temp_folder);
			$uniadmin->message($user->lang['error_no_files_addon']);
			return;
		}

		// See if AddOn exists in the database and do stuff to it
		$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `name` = '".$db->escape($real_addon_name)."';";
		$result = $db->query($sql);

		if( $db->num_rows($result) > 0 )
		{
			$row = $db->fetch_record($result);
			$db->free_result($result);

			$addon_id = $row['id'];

			if( $homepage == '' )
			{
				$homepage = $row['homepage'];
			}
			if( $notes == '' )
			{
				$notes = $row['notes'];
			}
			if( $version == '' )
			{
				$version = $row['version'];
			}

			$enabled = $row['enabled'];

			// Remove files from database since we'll be updating them all
			$sql = "DELETE FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '".$addon_id."';";
			$db->query($sql);

			// Update Main Addon data
			$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `time_uploaded` = '".time()."', `version` = '".$db->escape($version)."', `enabled` = '$enabled', `name` = '".$db->escape($real_addon_name)."', `file_name` = '".$db->escape($addon_file_name)."', `homepage` = '".$db->escape($homepage)."', `notes` = '".$db->escape($notes)."', `toc` = '$toc_number', `required` = '$required', `filesize` = '$file_size'
				WHERE `id` = '".$addon_id."';";
			$db->query($sql);
		}
		else
		{
			// Insert Main Addon data
			$sql = "INSERT INTO `".UA_TABLE_ADDONS."` ( `time_uploaded` , `version` , `enabled` , `name`, `file_name`, `homepage`, `notes`, `toc`, `required`, `filesize` )
				VALUES ( '".time()."', '".$db->escape($version)."', '1', '".$db->escape($real_addon_name)."', '".$db->escape($addon_file_name)."', '".$db->escape($homepage)."', '".$db->escape($notes)."', '$toc_number', '$required', '$file_size' );";
			$db->query($sql);

			// Get the insert id of the addon just inserted
			$addon_id = $db->insert_id();
		}

		if( !$db->affected_rows() )
		{
		    $uniadmin->debug($user->lang['sql_error_addons_insert']);
		    $uniadmin->cleardir($temp_folder);
		    return;
		}

		// Insert Addon Files' Data
		foreach( $files as $file )
		{
			$md5 = md5_file($file);
			$k = explode(DIR_SEP,$file);
			$pos_t = strpos($file,'addon_temp');
			$file_name = str_replace('/','\\',substr($file,$pos_t + 10));

			if( $file_name != 'index.htm' && $file_name != 'index.html' && $file_name != '.svn' )
			{
				if( isset($_POST['fullpath_addon']) && $_POST['fullpath_addon'] == '0' )
				{
					$file_name = '\Interface\AddOns'.$file_name;
				}

				$sql = "INSERT INTO `".UA_TABLE_FILES."` ( `addon_id` , `filename` , `md5sum` )
					VALUES ( '".$addon_id."', '".$db->escape($file_name)."', '".$db->escape($md5)."' );";
				$db->query($sql);
				if( !$db->affected_rows() )
				{
				    $uniadmin->debug($user->lang['sql_error_addons_files_insert']);
				    $uniadmin->cleardir($temp_folder);
				    return;
				}

				// We have obtained the md5 and inserted the row into the database, now delete the temp file
				$try_unlink = @unlink($file);
				if( !$try_unlink )
				{
					$uniadmin->debug(sprintf($user->lang['error_unlink'],$file));
				}
			}
		}

		// Now clear the temp folder
		$uniadmin->cleardir($temp_folder);

		$uniadmin->message(sprintf($user->lang['addon_uploaded'],$real_addon_name));
	}
	else // Nothing was uploaded
	{
		$uniadmin->message($user->lang['error_no_addon_uploaded']);
	}
}

function edit_addon( $id )
{
	global $db, $user, $uniadmin;

	$addon_name = stripslashes($_POST['name']);
	$addon_toc = stripslashes($_POST['toc']);
	$addon_url = stripslashes($_POST['homepage']);
	$addon_version = stripslashes($_POST['version']);
	$addon_notes = stripslashes($_POST['notes']);

	// Insert Main Addon data
	$sql = "UPDATE `".UA_TABLE_ADDONS."` SET
		`version` = '".$db->escape($addon_version)."',
		`name` = '".$db->escape($addon_name)."',
		`homepage` = '".$db->escape($addon_url)."',
		`notes` = '".$db->escape($addon_notes)."',
		`toc` = '".$db->escape($addon_toc)."'
		WHERE `id` = '$id';";

	$db->query($sql);

	$uniadmin->message(sprintf($user->lang['addon_edited'],$addon_name));
}

/**
 * Gets the value from the .toc file
 *
 * @param string $file		File to parse
 * @param string $var		Variable to search for
 * @param string $def_val	Default Value
 * @return string
 */
function get_toc_val( $file, $var, $def_val )
{
	$lines = file($file);

	$matches = $str_matches = null;

	$val = $def_val;
	foreach( $lines as $line )
	{
		$found = preg_match('/## \\b'.$var.'\\b: (.+)/',$line,$matches);

		if( $found )
		{
			$val = $matches[1];

			switch ( $var )
			{
				case 'Version':
					$str_found = preg_match('/\$Revision:(.+?)\$/',$val,$str_matches);
					if( $str_found )
					{
						$val = $str_matches[1];
					}
					break;
			}

			$val = preg_replace('/(.+?)\\|c[a-f0-9]{8}(.+?)\\|r/i','\\1\\2',$val);
			$val = preg_replace('/\\|c[a-f0-9]{8}/i','',$val);
			$val = str_replace('|r','',$val);
		}
	}
	return trim($val);
}
