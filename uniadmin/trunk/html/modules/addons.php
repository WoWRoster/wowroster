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

	default:
		break;
}
main();








/**
 * Addon Page Functions
 */


/**
 * Main Display
 */
function main( )
{
	global $db, $uniadmin, $user, $tpl;

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
		'L_INTERFACE_FIX'  => $user->lang['interface_fix'],

		'L_NO_ADDONS'      => $user->lang['error_no_addon_in_db'],

		'S_ADDONS'         => true,
		'S_ADDON_ADD_DEL'  => false,
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

	$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` ORDER BY `name`;";
	$result = $db->query($sql);

	if( $db->num_rows($result) > 0 )
	{
		while( $row = $db->fetch_record($result) )
		{
			$sql = "SELECT * FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '".$row['id']."';";
			$result2 = $db->query($sql);
			$num_files = $db->num_rows($result2);
			$db->free_result($result2);

			$addon_name = $row['name'];
			$homepage = $row['homepage'];
			$version = $row['version'];
			$time = date($user->lang['time_format'],$row['time_uploaded']);
			$url = $row['dl_url'];
			$addon_id = $row['id'];

			if( $row['enabled'] == '1' )
			{
				$enabled = '<form name="ua_disableaddon_'.$addon_id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_DISABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addon_id.'" />
	<input class="submit" style="color:green;" type="submit" value="'.$user->lang['yes'].'" />
</form>';
				if( $user->data['level'] <= UA_ID_USER )
				{
					$enabled = '<span style="color:green;">'.$user->lang['yes'].'</span>';
				}
			}
			else
			{
				$enabled = '<form name="ua_enableaddon_'.$addon_id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_ENABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addon_id.'" />
	<input class="submit" style="color:red;" type="submit" value="'.$user->lang['no'].'" />
</form>';
				if( $user->data['level'] <= UA_ID_USER )
				{
					$enabled = '<span style="color:red;">'.$user->lang['no'].'</span>';
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
	<input class="submit" style="color:red;" type="submit" value="'.$user->lang['yes'].'" />
</form>';
				if( $user->data['level'] <= UA_ID_USER )
				{
					$required = '<span style="color:red;">'.$user->lang['yes'].'</span>';
				}
			}
			else
			{
				$required = '<form name="ua_requireaddon_'.$addon_id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_REQ.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addon_id.'" />
	<input class="submit" style="color:green;" type="submit" value="'.$user->lang['no'].'" />
</form>';
				if( $user->data['level'] <= UA_ID_USER )
				{
					$required = '<span style="color:green;">'.$user->lang['no'].'</span>';
				}
			}

			$toc = $row['toc'];

			$tpl->assign_block_vars('addons_row', array(
				'ROW_CLASS'  => $uniadmin->switch_row_class(),
				'ID'         => $addon_id,
				'HOMEPAGE'   => $homepage,
				'ADDONNAME'  => $addon_name,
				'TOC'        => $toc,
				'REQUIRED'   => $required,
				'VERSION'    => $version,
				'TIME'       => $time,
				'ENABLED'    => $enabled,
				'NUMFILES'   => $num_files,
				'DOWNLOAD'   => $url,
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
	    $uniadmin->debug($user->lang['error_requre_addon']);
		$uniadmin->debug(sprintf($user->lang['sql_error_addons_requre'],$id));
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
	$addon_url = $row['dl_url'];
	$k = explode('/',$addon_url);
	$addon_file_name = $k[count($k) - 1];

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

		$url = $uniadmin->url_path;
		$addon_file_name = str_replace(' ','_',$_FILES['file']['name']);

		$addon_zip_folder = UA_BASEDIR.$uniadmin->config['addon_folder'];
		$temp_folder = UA_BASEDIR.$uniadmin->config['temp_analyze_folder'];

		$version = $_POST['version'];
		$addon_name = substr($addon_file_name,0,count($addon_file_name) -5);
		$homepage = $_POST['homepage'];

		if( isset($_POST['required']) && $_POST['required'] == '1' )
		{
			$required = 1;
		}
		else
		{
			$required = 0;
		}

		// Set Download URL
		$download_url = $url.$uniadmin->config['addon_folder'].'/'.$addon_file_name;

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

		// Unzip the file
		$uniadmin->unzip($zip_file,$temp_folder.DIR_SEP);

		$files = $uniadmin->ls($temp_folder,array());


		// Get the TOC of the addon
		$toc_file_name = '';
		if( is_array($files) )
		{
			foreach( $files as $file )
			{
				if( $uniadmin->get_file_ext($file) == 'toc' )
				{
					$toc = get_toc($file);

					$k = explode(DIR_SEP,$file);
					$toc_file_name = $k[count($k) - 1];
					/* NOT USED ANYMORE
					$real_addon_name = substr($toc_file_name,0,count($toc_file_name) -5);
					*/
					break;
				}
			}
			/* REMOVED TOC CHECK
			 * This was causing multi-addon zip files to fail and not read the addon name correctly
			if( empty($toc_file_name) )
			{
				$try_unlink = @unlink($zip_file);
				if( !$try_unlink )
				{
					$uniadmin->debug(sprintf($user->lang['error_unlink'],$zip_file));
				}
				$uniadmin->cleardir($temp_folder);
				$uniadmin->debug($user->lang['error_no_toc_file']);
			}
			*/
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
		$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `name` = '".$db->escape($addon_name)."';";
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
			if( $version == '' )
			{
				$version = $row['version'];
			}

			$enabled = $row['enabled'];

			// Remove files from database since we'll be updating them all
			$sql = "DELETE FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '".$addon_id."';";
			$db->query($sql);

			// Update Main Addon data
			$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `time_uploaded` = '".time()."', `version` = '".$db->escape($version)."', `enabled` = '$enabled', `name` = '".$db->escape($addon_name)."', `dl_url` = '".$db->escape($download_url)."', `homepage` = '".$db->escape($homepage)."', `toc` = '$toc', `required` = '$required'
				WHERE `id` = '".$addon_id."';";
			$db->query($sql);
		}
		else
		{
			// Insert Main Addon data
			$sql = "INSERT INTO `".UA_TABLE_ADDONS."` ( `time_uploaded` , `version` , `enabled` , `name`, `dl_url`, `homepage`, `toc`, `required` )
				VALUES ( '".time()."', '".$db->escape($version)."', '1', '".$db->escape($addon_name)."', '".$db->escape($download_url)."', '".$db->escape($homepage)."', $toc, $required);";
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
			$addon_file_name = substr($file,$pos_t + 10);

			if( $addon_file_name != 'index.htm' && $addon_file_name != 'index.html' && $addon_file_name != '.svn' )
			{
				if( isset($_POST['interface_fix']) && $_POST['interface_fix'] == '1' )
				{
					$addon_file_name = '/Interface/AddOns'.$addon_file_name;
				}

				$sql = "INSERT INTO `".UA_TABLE_FILES."` ( `addon_id` , `filename` , `md5sum` )
					VALUES ( '".$addon_id."', '".$db->escape($addon_file_name)."', '".$db->escape($md5)."' );";
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

		$uniadmin->message(sprintf($user->lang['addon_uploaded'],$addon_name));
	}
	else // Nothing was uploaded
	{
		$uniadmin->message($user->lang['error_no_addon_uploaded']);
	}
}

/**
 * Gets the toc version number from the .toc file
 *
 * @param string $file
 * @return string
 */
function get_toc( $file )
{
	$lines = file($file);

	$toc = '00000';
	foreach( $lines as $line )
	{
		$int_pos = strpos(strtoupper($line),strtoupper('Interface:'));
		if( $int_pos !== false )
		{
			$toc = substr($line, $int_pos + 10);
		}
	}
	return $toc;
}


?>