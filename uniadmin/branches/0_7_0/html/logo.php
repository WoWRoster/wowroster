<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}


if( isset($_REQUEST['op']) )
{
	$op = $_REQUEST['op'];
}
elseif( isset($_POST['op']) )
{
	$op = $_POST['op'];
}
else
{
	$op = '';
}

if( isset($_REQUEST['id']) )
{
	$id = $_REQUEST['id'];
}
elseif( isset($_POST['id']) )
{
	$id = $_POST['id'];
}
else
{
	$id = '';
}



function Main()
{
	global $dblink, $config, $_POST;

	$sql = "SELECT * FROM `".$config['db_tables_logos']."`";
	$result = mysql_query($sql,$dblink);

	$logoDir = $config['logo_folder'];

	$logo1['logo'] = $logoDir.'/logo1_03.gif';
	$logo1['updated'] = '-';
	$logo1['active_link'] = '-';
	$logo2['logo'] = $logoDir.'/logo2_03.gif';
	$logo2['updated'] = '-';
	$logo2['active_link'] = '-';

	while ($row = mysql_fetch_assoc($result))
	{
		switch ($row['logo_num'])
		{
			case '1':
				$logo1['logo'] = ( empty($row['filename']) ? $logo1['logo'] : $logoDir.'/'.$row['filename'] );
				$logo1['updated'] = ( empty($row['updated']) ? '-' : date($config['date_format'],$row['updated']) );

				if ( $row['active']=='1')
					$logo1['active_link'] = "<form name='ua_disablelogo1' style='display:inline;' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."'><input type='hidden' name='op' value='DISABLE' /><input type='hidden' name='id' value='".$row['id']."' /><input class='submit' style='color:green;' type='submit' value='Yes'></form>";
				else
					$logo1['active_link'] = "<form name='ua_enablelogo1' style='display:inline;' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."'><input type='hidden' name='op' value='ENABLE' /><input type='hidden' name='id' value='".$row['id']."' /><input class='submit' style='color:red;' type='submit' value='No'></form>";

				break;

			case '2':
				$logo2['logo'] = ( empty($row['filename']) ? $logo2['logo'] : $logoDir.'/'.$row['filename'] );
				$logo2['updated'] = ( empty($row['updated']) ? '-' : date($config['date_format'],$row['updated']) );

				if ( $row['active']=='1')
					$logo2['active_link'] = "<form name='ua_disablelogo2' style='display:inline;' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."'><input type='hidden' name='op' value='DISABLE' /><input type='hidden' name='id' value='".$row['id']."' /><input class='submit' style='color:green;' type='submit' value='Yes'></form>";
				else
					$logo2['active_link'] = "<form name='ua_enablelogo2' style='display:inline;' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."'><input type='hidden' name='op' value='ENABLE' /><input type='hidden' name='id' value='".$row['id']."' /><input class='submit' style='color:red;' type='submit' value='No'></form>";

				break;

			default:
				break;
		}

	}


	$table1 = "<table class='logo_table' border='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td colspan='3'>
			<img src='images/logo1_01.gif' style='width:500px;height:56px;' alt=''></td>
	</tr>
	<tr>
		<td rowspan='2'>
			<img src='images/logo1_02.gif' style='width:281px;height:256px;' alt=''></td>
		<td bgcolor='#e0dfe3'>
			<img src='".$logo1['logo']."' style='width:201px;height:156px;' alt=''></td>
		<td rowspan='2'>
			<img src='images/logo1_04.gif' style='width:18px;height:256px;' alt=''></td>
	</tr>
	<tr>
		<td>
			<img src='images/logo1_05.gif' style='width:201px;height:100px;' alt=''></td>
	</tr>
</table>";


	$table2 = "<table class='logo_table' border='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td colspan=3>
			<img src='images/logo2_01.gif' style='width:500px;height:73px;' alt=''></td>
	</tr>
	<tr>
		<td rowspan=2>
			<img src='images/logo2_02.gif' style='width:153px;height:239px;' alt=''></td>
		<td bgcolor=#e0dfe3>
			<img src='".$logo2['logo']."' style='width:316px;height:144px;' alt=''></td>
		<td rowspan=2>
			<img src='images/logo2_04.gif' style='width:31px;height:239px;' alt=''></td>
	</tr>
	<tr>
		<td>
			<img src='images/logo2_05.gif' style='width:316px;height:95px;' alt=''></td>
	</tr>
</table>";


	$Logo1InputForm ="

		<table class='uuTABLE'>
			<tr>
				<th class='dataHeader'>Update File</th>
				<th class='dataHeader'>Updated</th>
				<th class='dataHeader'>Enabled?</th>
			</tr>
			<tr>
				<td class='data1' align='center'><form name='ua_uploadlogo1' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."'>
					Select file:
					<input class='file' type='file' name='logo1'>
					<input class='submit' type='submit' value='Update Logo 1'>
					<input type='hidden' value='PROCESSUPLOAD' name='op'>
					</form></td>
				<td class='data1'>".$logo1['updated']."</td>
				<td class='data1'>".$logo1['active_link']."</td>
			</tr>
		</table>
    	";

	$Logo2InputForm ="
		<table class='uuTABLE'>
			<tr>
				<th class='dataHeader'>Update File</th>
				<th class='dataHeader'>Updated</th>
				<th class='dataHeader'>Enabled?</th>
			</tr>
			<tr>
				<td class='data1' align='center'><form name='ua_uploadlogo2' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."'>
					Select file:
					<input class='file' type='file' name='logo2'>
					<input class='submit' type='submit' value='Update Logo 2'>
					<input type='hidden' value='PROCESSUPLOAD' name='op'>
					</form></td>
				<td class='data1'>".$logo2['updated']."</td>
				<td class='data1'>".$logo2['active_link']."</td>
			</tr>
		</table>
";


	EchoPage("
<table class='uuTABLE' width='60%' align='center'>
	<tr>
		<th class='tableHeader'>Logo 1</th>
	</tr>
	<tr>
		<td align='center'>$table1</td>
	</tr>
	<tr>
		<td align='center'>$Logo1InputForm</td>
	</tr>
</table>
<br />
<table class='uuTABLE' width='60%' align='center'>
	<tr>
		<th class='tableHeader'>Logo 2</th>
	</tr>
	<tr>
		<td align='center'>$table2</td>
	</tr>
	<tr>
		<td align='center'>$Logo2InputForm</td>
	</tr>
</table>",'Logos');
}

function ToggleLogo()
{
	global $dblink, $config, $op, $id;

	switch ($op)
	{
		case 'DISABLE':
			$sql = "UPDATE `".$config['db_tables_logos']."` SET `active` = '0' WHERE `id` = '$id';";
			break;

		case 'ENABLE':
			$sql = "UPDATE `".$config['db_tables_logos']."` SET `active` = '1' WHERE `id` = '$id';";
			break;
		default:
		break;
	}
	mysql_query($sql, $dblink);
	MySqlCheck($dblink,$sql);
}

function ProcessUploadedLogo()
{
	global $dblink, $config, $op;

	$sep = DIRECTORY_SEPARATOR;

	$logoFolder = UA_BASEDIR.$config['logo_folder'];
	if( isset($_FILES['logo1']) && $_FILES['logo1']['name'] != '' )
	{
		$sql = "SELECT * FROM `".$config['db_tables_logos']."` WHERE `logo_num` = '1'";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		$row = mysql_fetch_assoc($result);
		$RowNum = $row['id'];
		$logoNum = "1";
		$filefield = "logo1";
	}
	elseif( isset($_FILES['logo2']) && $_FILES['logo2']['name'] != '' )
	{
		$sql = "SELECT * FROM `".$config['db_tables_logos']."` WHERE `logo_num` = '2'";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		$row = mysql_fetch_assoc($result);
		$RowNum = $row['id'];
		$logoNum = '2';
		$filefield = 'logo2';
	}
	else
	{
		message('No Logo Uploaded');
		return;
	}

	if (substr_count(strtoupper($_FILES[$filefield]['name']),'GIF') > 0)
	{
		$LocalLocation = $logoFolder.$sep.stripslashes('logo'.$logoNum.'.gif');
		@unlink($logoFolder.$sep.'logo'.$logoNum.'.gif');
		move_uploaded_file($_FILES[$filefield]['tmp_name'],$LocalLocation);
		$md5 = md5_file($LocalLocation);
		chmod($LocalLocation,0777);
		$sql = "DELETE FROM `".$config['db_tables_logos']."` WHERE `id` = '$RowNum'";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		$sql = "INSERT INTO `".$config['db_tables_logos']."` ( `filename` , `updated` , `logo_num` , `active` , `download_url` , `md5` ) VALUES ( 'logo$logoNum.gif', '".time()."', '$logoNum', '1', '".$config['URL'].$config['logo_folder']."/logo$logoNum.gif', '$md5' );";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
	}
	else
	{
		message('The Uploaded file MUST be a GIF IMAGE!');
		return;
	}
}
switch ($op)
{

	case 'PROCESSUPLOAD':
		ProcessUploadedLogo();
		Main();
		break;

	case 'DISABLE':
		ToggleLogo();
		Main();
		break;

	case 'ENABLE':
		ToggleLogo();
		Main();
		break;

	default:
		Main();
		break;
}


?>