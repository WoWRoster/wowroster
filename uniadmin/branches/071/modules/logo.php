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
switch( $op )
{
	case UA_URI_PROCESS:
		if( $user->data['level'] > UA_ID_USER )
			process_logo();
		break;

	case UA_URI_DISABLE:
	case UA_URI_ENABLE:
		if( $user->data['level'] > UA_ID_USER )
			toggle_logo($op,$id);
		break;

	default:
		break;
}
main();








/**
 * Logo Page Functions
 */


/**
 * Main Display
 */
function main( )
{
	global $db, $uniadmin, $user, $tpl;

	$num_logos = 2;

	$tpl->assign_vars(array(
		'L_UPDATE_FILE'    => $user->lang['update_file'],
		'L_UPLOADED'       => $user->lang['uploaded'],
		'L_ENABLED'        => $user->lang['enabled'],
		'L_DISABLED'       => $user->lang['disabled'],
		'L_SELECT_FILE'    => $user->lang['select_file'],
		'L_UPDATED'        => $user->lang['updated'],
		'L_STATUS'         => $user->lang['status'],
		'S_LOGO'           => false
		)
	);

	if( $user->data['level'] > UA_ID_USER )
	{
		$tpl->assign_var('S_LOGO',true);
	}

	$logo_dir = $uniadmin->config['logo_folder'];

	for( $logo_num=1; $logo_num<=$num_logos; $logo_num++ )
	{
		$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `logo_num` = '$logo_num';";
		$result = $db->query($sql);
		$row = $db->fetch_record($result);

		$logo_updated = '-';

		$logo_image = ( empty($row['filename']) ? 'images/logo'.$logo_num.'_03.gif' : $logo_dir.'/'.$row['filename'] );
		$logo_updated = ( empty($row['updated']) ? '-' : date($user->lang['time_format'],$row['updated']) );

		// I hate kludges but this is how it has to be...for now
		switch( $logo_num )
		{
			case '1':
				$logo_table = '<table class="logo_table" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="3"><img src="'.$uniadmin->url_path.'images/logo1_01.gif" style="width:500px;height:62px;" alt="" /></td>
	</tr>
	<tr>
		<td><img src="'.$uniadmin->url_path.'images/logo1_02.gif" style="width:271px;height:144px;" alt="" /></td>
		<td bgcolor="#f4f4f4"><img src="'.$uniadmin->url_path.$logo_image.'" style="width:215px;height:144px;" alt="" /></td>
		<td><img src="'.$uniadmin->url_path.'images/logo1_04.gif" style="width:14px;height:144px;" alt="" /></td>
	</tr>
	<tr>
		<td colspan="3"><img src="'.$uniadmin->url_path.'images/logo1_05.gif" style="width:500px;height:95px;" alt="" /></td>
	</tr>
</table>';
				break;

			case '2':
				$logo_table = '<table class="logo_table" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="3"><img src="'.$uniadmin->url_path.'images/logo2_01.gif" style="width:500px;height:70px;" alt="" /></td>
	</tr>
	<tr>
		<td><img src="'.$uniadmin->url_path.'images/logo2_02.gif" style="width:151px;height:175px;" alt="" /></td>
		<td bgcolor="#f4f4f4"><img src="'.$uniadmin->url_path.$logo_image.'" style="width:319px;height:175px;" alt="" /></td>
		<td><img src="'.$uniadmin->url_path.'images/logo2_04.gif" style="width:30px;height:175px;" alt="" /></td>
	</tr>
	<tr>
		<td colspan="3"><img src="'.$uniadmin->url_path.'images/logo2_05.gif" style="width:500px;height:56px;" alt="" /></td>
	</tr>
</table>';
				break;

			default:
				break;
		}

		$tpl->assign_block_vars('logo_row', array(
			'NUM'           => $logo_num,
			'L_LOGO_NUM'    => sprintf($user->lang['logo_table'],$logo_num),
			'ID'            => $row['id'],
			'UPDATED'       => $logo_updated,
			'ACTIVE'        => $row['active'],
			'L_UPDATE_LOGO' => sprintf($user->lang['update_logo'],$logo_num),
			'IMAGE'         => $logo_table,
			'IMAGESET'      => ($db->num_rows($result) > 0) ? true : false
			)
		);
	}

	$uniadmin->set_vars(array(
		'page_title'    => $user->lang['title_logo'],
		'template_file' => 'logo.html',
		'display'       => true)
	);
}

/**
 * Toggle Logo enable/disable
 *
 * @param string $op
 * @param string $id
 */
function toggle_logo( $op , $id )
{
	global $db, $user, $uniadmin;

	if( !empty($op) && !empty($id) )
	{
		switch( $op )
		{
			case UA_URI_DISABLE:
				$sql = "UPDATE `".UA_TABLE_LOGOS."` SET `active` = '0' WHERE `id` = '$id';";
				break;

			case UA_URI_ENABLE:
				$sql = "UPDATE `".UA_TABLE_LOGOS."` SET `active` = '1' WHERE `id` = '$id';";
				break;

			default:
			break;
		}
		$db->query($sql);
		if( !$db->affected_rows() )
		{
			$uniadmin->debug(sprintf($user->lang['sql_error_logo_toggle'],$op));
		}
	}
}

/**
 * Process Uploaded Logo
 */
function process_logo( )
{
	global $db, $uniadmin, $user;

	$logo_folder = UA_BASEDIR.$uniadmin->config['logo_folder'];
	if( isset($_FILES['logo1']) && $_FILES['logo1']['name'] != '' )
	{
		$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `logo_num` = '1';";
		$result = $db->query($sql);

		$row = $db->fetch_record($result);

		$logo_id = $row['id'];
		$logo_num = '1';
		$file_field = 'logo1';
	}
	elseif( isset($_FILES['logo2']) && $_FILES['logo2']['name'] != '' )
	{
		$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `logo_num` = '2';";
		$result = $db->query($sql);

		$row = $db->fetch_record($result);

		$logo_id = $row['id'];
		$logo_num = '2';
		$file_field = 'logo2';
	}
	else
	{
		$uniadmin->message($user->lang['error_no_uploaded_logo']);
		return;
	}

	if( $uniadmin->get_file_ext($_FILES[$file_field]['name']) == 'gif' )
	{
		$logo_location = $logo_folder.DIR_SEP.stripslashes('logo'.$logo_num.'.gif');
		@unlink($logo_folder.DIR_SEP.'logo'.$logo_num.'.gif');

		$try_move = @move_uploaded_file($_FILES[$file_field]['tmp_name'],$logo_location);
		if( !$try_move )
		{
			$uniadmin->debug(sprintf($user->lang['error_move_uploaded_file'],$_FILES[$file_field]['tmp_name'],$logo_location));
			return;
		}

		$md5 = md5_file($logo_location);

		if( !is_writeable($logo_location) )
		{
			$try_chmod = @chmod($logo_location,0777);
			if( !$try_chmod )
			{
				$uniadmin->debug(sprintf($user->lang['error_chmod'],$logo_location));
				return;
			}
		}

		$sql = "DELETE FROM `".UA_TABLE_LOGOS."` WHERE `id` = '$logo_id'";
		$result = $db->query($sql);


		$sql = "INSERT INTO `".UA_TABLE_LOGOS."` ( `filename` , `updated` , `logo_num` , `active` , `download_url` , `md5` ) VALUES ( 'logo$logo_num.gif', '".time()."', '$logo_num', '1', '".$uniadmin->url_path.$uniadmin->config['logo_folder']."/logo$logo_num.gif', '$md5' );";
		$result = $db->query($sql);
		if( !$db->affected_rows() )
		{
			$uniadmin->debug(sprintf($user->lang['sql_error_logo_insert'],$logo_num));
		}

		$uniadmin->message(sprintf($user->lang['logo_uploaded'],$logo_num));
	}
	else
	{
		$uniadmin->message($user->lang['error_logo_format']);
		return;
	}
}
