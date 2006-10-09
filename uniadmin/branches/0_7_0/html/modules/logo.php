<?php

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
		process_logo();
		break;

	case UA_URI_DISABLE:
	case UA_URI_ENABLE:
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
	global $db, $uniadmin, $user;

	$sql = "SELECT * FROM `".UA_TABLE_LOGOS."`;";
	$result = $db->query($sql);

	$logo_dir = $uniadmin->config['logo_folder'];

	$logo1['logo'] = 'images/logo1_03.gif';
	$logo1['updated'] = '-';
	$logo1['active_link'] = '-';
	$logo2['logo'] = 'images/logo2_03.gif';
	$logo2['updated'] = '-';
	$logo2['active_link'] = '-';

	while( $row = $db->fetch_record($result) )
	{
		switch( $row['logo_num'] )
		{
			case '1':
				$logo1['logo'] = ( empty($row['filename']) ? $logo1['logo'] : $logo_dir.'/'.$row['filename'] );
				$logo1['updated'] = ( empty($row['updated']) ? '-' : date($user->lang['time_format'],$row['updated']) );

				if( $row['active']=='1' )
				{
					$logo1['active_link'] = '<form name="ua_disablelogo1" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_DISABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$row['id'].'" />
	<input class="submit" style="color:green;" type="submit" value="'.$user->lang['yes'].'" />
</form>';
				}
				else
				{
					$logo1['active_link'] = '<form name="ua_enablelogo1" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_ENABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$row['id'].'" />
	<input class="submit" style="color:red;" type="submit" value="'.$user->lang['no'].'" />
</form>';
				}
				break;

			case '2':
				$logo2['logo'] = ( empty($row['filename']) ? $logo2['logo'] : $logo_dir.'/'.$row['filename'] );
				$logo2['updated'] = ( empty($row['updated']) ? '-' : date($user->lang['time_format'],$row['updated']) );

				if( $row['active']=='1' )
				{
					$logo2['active_link'] = '<form name="ua_disablelogo2" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_DISABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$row['id'].'" />
	<input class="submit" style="color:green;" type="submit" value="'.$user->lang['yes'].'" />
</form>';
				}
				else
				{
					$logo2['active_link'] = '<form name="ua_enablelogo2" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_ENABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$row['id'].'" />
	<input class="submit" style="color:red;" type="submit" value="'.$user->lang['no'].'" />
</form>';
				}
				break;

			default:
				break;
		}
	}


	$table1 = '<table class="logo_table" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="3"><img src="'.$uniadmin->url_path.'images/logo1_01.gif" style="width:500px;height:62px;" alt="" /></td>
	</tr>
	<tr>
		<td><img src="'.$uniadmin->url_path.'images/logo1_02.gif" style="width:271px;height:144px;" alt="" /></td>
		<td bgcolor="#e0dfe3"><img src="'.$uniadmin->url_path.$logo1['logo'].'" style="width:215px;height:144px;" alt="" /></td>
		<td><img src="'.$uniadmin->url_path.'images/logo1_04.gif" style="width:14px;height:144px;" alt="" /></td>
	</tr>
	<tr>
		<td colspan="3"><img src="'.$uniadmin->url_path.'images/logo1_05.gif" style="width:500px;height:95px;" alt="" /></td>
	</tr>
</table>';


	$table2 = '<table class="logo_table" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="3"><img src="'.$uniadmin->url_path.'images/logo2_01.gif" style="width:500px;height:70px;" alt="" /></td>
	</tr>
	<tr>
		<td><img src="'.$uniadmin->url_path.'images/logo2_02.gif" style="width:151px;height:175px;" alt="" /></td>
		<td bgcolor="#e0dfe3"><img src="'.$uniadmin->url_path.$logo2['logo'].'" style="width:319px;height:175px;" alt="" /></td>
		<td><img src="'.$uniadmin->url_path.'images/logo2_04.gif" style="width:30px;height:175px;" alt="" /></td>
	</tr>
	<tr>
		<td colspan="3"><img src="'.$uniadmin->url_path.'images/logo2_05.gif" style="width:500px;height:56px;" alt="" /></td>
	</tr>
</table>';


	$logo_input_form_1 ='
		<table class="ua_table">
			<tr>
				<th class="data_header">'.$user->lang['update_file'].'</th>
				<th class="data_header">'.$user->lang['uploaded'].'</th>
				<th class="data_header">'.$user->lang['enabled'].'</th>
			</tr>
			<tr>
				<td class="data1" align="center">'.$user->lang['select_file'].':
					<form name="ua_uploadlogo1" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
						<input class="file" type="file" name="logo1" />
						<input class="submit" type="submit" value="'.sprintf($user->lang['update_logo'],1).'" />
						<input type="hidden" value="'.UA_URI_PROCESS.'" name="'.UA_URI_OP.'" />
					</form>
				</td>
				<td class="data1">'.$logo1['updated'].'</td>
				<td class="data1">'.$logo1['active_link'].'</td>
			</tr>
		</table>
';

	$logo_input_form_2 = '
		<table class="ua_table">
			<tr>
				<th class="data_header">'.$user->lang['update_file'].'</th>
				<th class="data_header">'.$user->lang['uploaded'].'</th>
				<th class="data_header">'.$user->lang['enabled'].'</th>
			</tr>
			<tr>
				<td class="data1" align="center">'.$user->lang['select_file'].':
					<form name="ua_uploadlogo2" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
						<input class="file" type="file" name="logo2" />
						<input class="submit" type="submit" value="'.sprintf($user->lang['update_logo'],2).'" />
						<input type="hidden" value="'.UA_URI_PROCESS.'" name="'.UA_URI_OP.'" />
					</form>
				</td>
				<td class="data1">'.$logo2['updated'].'</td>
				<td class="data1">'.$logo2['active_link'].'</td>
			</tr>
		</table>
';


	display_page('
<table class="ua_table" width="60%" align="center">
	<tr>
		<th class="table_header">'.sprintf($user->lang['logo_table'],1).'</th>
	</tr>
	<tr>
		<td align="center">'.$table1.'</td>
	</tr>
	<tr>
		<td align="center">'.$logo_input_form_1.'</td>
	</tr>
</table>
<br />
<table class="ua_table" width="60%" align="center">
	<tr>
		<th class="table_header">'.sprintf($user->lang['logo_table'],2).'</th>
	</tr>
	<tr>
		<td align="center">'.$table2.'</td>
	</tr>
	<tr>
		<td align="center">'.$logo_input_form_2.'</td>
	</tr>
</table>',$user->lang['title_logo']);
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
		$try_chmod = @chmod($logo_location,0777);
		if( !$try_chmod )
		{
			$uniadmin->debug(sprintf($user->lang['error_chmod'],$logo_location));
			return;
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

?>