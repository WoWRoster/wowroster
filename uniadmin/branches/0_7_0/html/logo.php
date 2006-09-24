<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

// Get Operation
$op = ( isset($_POST[UA_URI_OP]) ? $_POST[UA_URI_OP] : '' );

$id = ( isset($_POST[UA_URI_ID]) ? $_POST[UA_URI_ID] : '' );

// Decide What To Do
switch ($op)
{
	case UA_URI_PROCESS:
		processUploadedLogo();
		break;

	case UA_URI_DISABLE:
		toggleLogo($op,$id);
		break;

	case UA_URI_ENABLE:
		toggleLogo($op,$id);
		break;

	default:
		break;
}
main();

$db->close_db();






/**
 * Logo Page Functions
 */


/**
 * Main Display
 */
function main()
{
	global $db, $uniadmin, $user;

	$sql = "SELECT * FROM `".UA_TABLE_LOGOS."`;";
	$result = $db->query($sql);

	$logoDir = $uniadmin->config['logo_folder'];

	$logo1['logo'] = $logoDir.'/logo1_03.gif';
	$logo1['updated'] = '-';
	$logo1['active_link'] = '-';
	$logo2['logo'] = $logoDir.'/logo2_03.gif';
	$logo2['updated'] = '-';
	$logo2['active_link'] = '-';

	while ($row = $db->fetch_record($result))
	{
		switch ($row['logo_num'])
		{
			case '1':
				$logo1['logo'] = ( empty($row['filename']) ? $logo1['logo'] : $logoDir.'/'.$row['filename'] );
				$logo1['updated'] = ( empty($row['updated']) ? '-' : date($user->lang['time_format'],$row['updated']) );

				if ( $row['active']=='1')
					$logo1['active_link'] = '<form name="ua_disablelogo1" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_DISABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$row['id'].'" />
	<input class="submit" style="color:green;" type="submit" value="'.$user->lang['yes'].'">
</form>';
				else
					$logo1['active_link'] = '<form name="ua_enablelogo1" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_ENABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$row['id'].'" />
	<input class="submit" style="color:red;" type="submit" value="'.$user->lang['no'].'">
</form>';
				break;

			case '2':
				$logo2['logo'] = ( empty($row['filename']) ? $logo2['logo'] : $logoDir.'/'.$row['filename'] );
				$logo2['updated'] = ( empty($row['updated']) ? '-' : date($user->lang['time_format'],$row['updated']) );

				if ( $row['active']=='1')
					$logo2['active_link'] = '<form name="ua_disablelogo2" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_DISABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$row['id'].'" />
	<input class="submit" style="color:green;" type="submit" value="'.$user->lang['yes'].'">
</form>';
				else
					$logo2['active_link'] = '<form name="ua_enablelogo2" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_ENABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$row['id'].'" />
	<input class="submit" style="color:red;" type="submit" value="'.$user->lang['no'].'">
</form>';
				break;

			default:
				break;
		}

	}


	$table1 = '<table class="logo_table" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="3">
			<img src="'.$uniadmin->url_path.'images/logo1_01.gif" style="width:500px;height:56px;" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="'.$uniadmin->url_path.'images/logo1_02.gif" style="width:281px;height:256px;" alt=""></td>
		<td bgcolor="#e0dfe3">
			<img src="'.$uniadmin->url_path.$logo1['logo'].'" style="width:201px;height:156px;" alt=""></td>
		<td rowspan="2">
			<img src="'.$uniadmin->url_path.'images/logo1_04.gif" style="width:18px;height:256px;" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="'.$uniadmin->url_path.'images/logo1_05.gif" style="width:201px;height:100px;" alt=""></td>
	</tr>
</table>';


	$table2 = '<table class="logo_table" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan=3>
			<img src="'.$uniadmin->url_path.'images/logo2_01.gif" style="width:500px;height:73px;" alt=""></td>
	</tr>
	<tr>
		<td rowspan=2>
			<img src="'.$uniadmin->url_path.'images/logo2_02.gif" style="width:153px;height:239px;" alt=""></td>
		<td bgcolor="#e0dfe3">
			<img src="'.$uniadmin->url_path.$logo2['logo'].'" style="width:316px;height:144px;" alt=""></td>
		<td rowspan=2>
			<img src="'.$uniadmin->url_path.'images/logo2_04.gif" style="width:31px;height:239px;" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="'.$uniadmin->url_path.'images/logo2_05.gif" style="width:316px;height:95px;" alt=""></td>
	</tr>
</table>';


	$Logo1InputForm ='
		<table class="uuTABLE">
			<tr>
				<th class="dataHeader">'.$user->lang['update_file'].'</th>
				<th class="dataHeader">'.$user->lang['uploaded'].'</th>
				<th class="dataHeader">'.$user->lang['enabled'].'</th>
			</tr>
			<tr>
				<td class="data1" align="center"><form name="ua_uploadlogo1" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
					'.$user->lang['select_file'].':
					<input class="file" type="file" name="logo1">
					<input class="submit" type="submit" value="'.sprintf($user->lang['update_logo'],1).'">
					<input type="hidden" value="'.UA_URI_PROCESS.'" name="'.UA_URI_OP.'">
					</form></td>
				<td class="data1">'.$logo1['updated'].'</td>
				<td class="data1">'.$logo1['active_link'].'</td>
			</tr>
		</table>
';

	$Logo2InputForm = '
		<table class="uuTABLE">
			<tr>
				<th class="dataHeader">'.$user->lang['update_file'].'</th>
				<th class="dataHeader">'.$user->lang['uploaded'].'</th>
				<th class="dataHeader">'.$user->lang['enabled'].'</th>
			</tr>
			<tr>
				<td class="data1" align="center"><form name="ua_uploadlogo2" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
					'.$user->lang['select_file'].':
					<input class="file" type="file" name="logo2">
					<input class="submit" type="submit" value="'.sprintf($user->lang['update_logo'],2).'">
					<input type="hidden" value="'.UA_URI_PROCESS.'" name="'.UA_URI_OP.'">
					</form></td>
				<td class="data1">'.$logo2['updated'].'</td>
				<td class="data1">'.$logo2['active_link'].'</td>
			</tr>
		</table>
';


	EchoPage('
<table class="uuTABLE" width="60%" align="center">
	<tr>
		<th class="tableHeader">'.sprintf($user->lang['logo_table'],1).'</th>
	</tr>
	<tr>
		<td align="center">'.$table1.'</td>
	</tr>
	<tr>
		<td align="center">'.$Logo1InputForm.'</td>
	</tr>
</table>
<br />
<table class="uuTABLE" width="60%" align="center">
	<tr>
		<th class="tableHeader">'.sprintf($user->lang['logo_table'],1).'</th>
	</tr>
	<tr>
		<td align="center">'.$table2.'</td>
	</tr>
	<tr>
		<td align="center">'.$Logo2InputForm.'</td>
	</tr>
</table>',$user->lang['title_logo']);
}

/**
 * Toggle Logo enable/disable
 *
 * @param string $op
 * @param string $id
 */
function toggleLogo($op,$id)
{
	global $db;

	if( !empty($op) && !empty($id) )
	{
		switch ($_POST[UA_URI_OP])
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
		$result = $db->query($sql);
		if( !$db->affected_rows() )
		{
			debug(sprintf($user->lang['sql_error_logo_toggle'],$op));
		}
	}
}

/**
 * Process Uploaded Logo
 */
function processUploadedLogo()
{
	global $db, $uniadmin, $user;

	$logoFolder = UA_BASEDIR.$uniadmin->config['logo_folder'];
	if( isset($_FILES['logo1']) && $_FILES['logo1']['name'] != '' )
	{
		$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `logo_num` = '1';";
		$result = $db->query($sql);

		$row = $db->fetch_record($result);

		$RowNum = $row['id'];
		$logoNum = '1';
		$filefield = 'logo1';
	}
	elseif( isset($_FILES['logo2']) && $_FILES['logo2']['name'] != '' )
	{
		$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `logo_num` = '2';";
		$result = $db->query($sql);

		$row = $db->fetch_record($result);

		$RowNum = $row['id'];
		$logoNum = '2';
		$filefield = 'logo2';
	}
	else
	{
		message($user->lang['error_no_uploaded_logo']);
		return;
	}

	if (substr_count(strtoupper($_FILES[$filefield]['name']),'GIF') > 0)
	{
		$LocalLocation = $logoFolder.DIR_SEP.stripslashes('logo'.$logoNum.'.gif');
		@unlink($logoFolder.DIR_SEP.'logo'.$logoNum.'.gif');
		$try_move = @move_uploaded_file($_FILES[$filefield]['tmp_name'],$LocalLocation);
		if( !$try_move )
		{
			debug(sprintf($user->lang['error_move_uploaded_file'],$_FILES[$filefield]['tmp_name'],$LocalLocation));
			return;
		}

		$md5 = md5_file($LocalLocation);
		$try_chmod = @chmod($LocalLocation,0777);
		if( !$try_chmod )
		{
			debug(sprintf($user->lang['error_chmod'],$LocalLocation));
			return;
		}

		$sql = "DELETE FROM `".UA_TABLE_LOGOS."` WHERE `id` = '$RowNum'";
		$result = $db->query($sql);


		$sql = "INSERT INTO `".UA_TABLE_LOGOS."` ( `filename` , `updated` , `logo_num` , `active` , `download_url` , `md5` ) VALUES ( 'logo$logoNum.gif', '".time()."', '$logoNum', '1', '".$uniadmin->url_path.$uniadmin->config['logo_folder']."/logo$logoNum.gif', '$md5' );";
		$result = $db->query($sql);
		if( !$db->affected_rows() )
		{
			debug(sprintf($user->lang['sql_error_logo_insert'],$logoNum));
		}
	}
	else
	{
		message($user->lang['error_logo_format']);
		return;
	}
}


?>