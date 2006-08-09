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

	while ($row = mysql_fetch_assoc($result))
	{
		switch ($row['logo_num'])
		{
			case '1':
				$logo1['filename'] = $row['filename'];
				$logo1['updated'] = date('M jS y H:i',$row['updated']);
				$logo1['id'] = $row['id'];
				$logo1['active'] = $row['active'];

				if ( $row['active']=='1')
					$logo1['active_link'] ='<span style="color:green;font-weight:bold;">Yes</span>';
				else
					$logo1['active_link'] ='<span style="color:red;font-weight:bold;">No</span>';

				break;

			case '2':
				$logo2['filename'] = $row['filename'];
				$logo2['updated'] = date('M jS y H:i',$row['updated']);
				$logo2['id'] = $row['id'];
				$logo2['active'] = $row['active'];

				if ( $row['active']=='1')
					$logo2['active_link'] ='<span style="color:green;font-weight:bold;">Yes</span>';
				else
					$logo2['active_link'] ='<span style="color:red;font-weight:bold;">No</span>';

				break;

			default:
				break;
		}

	}
	$logoDir = $config['logo_folder'];


	$table1 = "<table class='logo_table' border='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td colspan='3'>
			<img src='images/logo1_01.gif' style='width:500px;height:56px;' alt=''></td>
	</tr>
	<tr>
		<td rowspan='2'>
			<img src='images/logo1_02.gif' style='width:281px;height:256px;' alt=''></td>
		<td bgcolor='#e0dfe3'>
			<img src='$logoDir/".( isset($logo1['filename']) ? $logo1['filename'] : 'logo1_03.gif')."' style='width:201px;height:156px;' alt=''></td>
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
			<img src='$logoDir/".( isset($logo2['filename']) ? $logo2['filename'] : 'logo2_03.gif')."' style='width:316px;height:144px;' alt=''></td>
		<td rowspan=2>
			<img src='images/logo2_04.gif' style='width:31px;height:239px;' alt=''></td>
	</tr>
	<tr>
		<td>
			<img src='images/logo2_05.gif' style='width:316px;height:95px;' alt=''></td>
	</tr>
</table>";


	if( isset($logo1) )
	{
		if($logo1['active']=='1')
		{
			$logo1EnableLink = "<a href='".UA_FORMACTION."logo&amp;op=DISABLE&amp;id=".$logo1['id']."'>Disable</a>";
		}
		else
		{
			$logo1EnableLink = "<a href='".UA_FORMACTION."logo&amp;op=ENABLE&amp;id=".$logo1['id']."'>Enable</a>";
		}
		$logo1DeleteLink = "<a href='".UA_FORMACTION."logo&amp;op=DELETE&amp;id=".$logo1['id']."'>Delete</a>";
	}
	else
	{
		$logo1EnableLink = '-';
		$logo1DeleteLink = '-';
	}

	if( isset($logo2) )
	{
		if($logo2['active']=='1')
		{
			$logo2EnableLink = "<a href='".UA_FORMACTION."logo&amp;op=DISABLE&amp;id=".$logo2['id']."'>Disable</a>";
		}
		else
		{
			$logo2EnableLink = "<a href='".UA_FORMACTION."logo&amp;op=ENABLE&amp;id=".$logo2['id']."'>Enable</a>";
		}
		$logo2DeleteLink = "<a href='".UA_FORMACTION."logo&amp;op=DELETE&amp;id=".$logo2['id']."'>Delete</a>";
	}
	else
	{
		$logo2EnableLink = '-';
		$logo2DeleteLink = '-';
	}



	$Logo1InputForm ="
	<form method='post' enctype='multipart/form-data' action='".UA_FORMACTION."logo'>
		<table class='uuTABLE'>
			<tr>
				<th class='dataHeader'>Update File</th>
				<th class='dataHeader'>Updated</th>
				<th class='dataHeader'>Enabled</th>
				<th class='dataHeader'>Disable / Enable</th>
				<th class='dataHeader'>Delete!</th>
			</tr>
			<tr>
				<td class='data1' align='center'>Select file:
					<input class='file' type='file' name='logo1'>
					<input class='submit' type='submit' value='Update Logo 1'>
					<input type='hidden' value='PROCESSUPLOAD' name='op'></td>
				<td class='data1'>".( isset($logo1['updated']) ? $logo1['updated'] : '-' )."</td>
				<td class='data1'>".( isset($logo1['active_link']) ? $logo1['active_link'] : '-' )."</td>
				<td class='data1'>$logo1EnableLink</td>
				<td class='data1'>$logo1DeleteLink</td>
			</tr>
		</table>
	</form>
    	";

	$Logo2InputForm ="
	<form method='post' enctype='multipart/form-data' action='".UA_FORMACTION."logo'>
		<table class='uuTABLE'>
			<tr>
				<th class='dataHeader'>Update File</th>
				<th class='dataHeader'>Updated</th>
				<th class='dataHeader'>Enabled</th>
				<th class='dataHeader'>Disable / Enable</th>
				<th class='dataHeader'>Delete!</th>
			</tr>
			<tr>
				<td class='data1' align='center'>Select file:
					<input class='file' type='file' name='logo2'>
					<input class='submit' type='submit' value='Update Logo 2'>
					<input type='hidden' value='PROCESSUPLOAD' name='op'></td>
				<td class='data1'>".( isset($logo2['updated']) ? $logo2['updated'] : '-' )."</td>
				<td class='data1'>".( isset($logo2['active_link']) ? $logo2['active_link'] : '-' )."</td>
				<td class='data1'>$logo2EnableLink</td>
				<td class='data1'>$logo2DeleteLink</td>
			</tr>
		</table>
	</form>
";


	EchoPage("
<table class='uuTABLE'>
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
<table class='uuTABLE'>
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
	if (isset($_FILES['logo1']))
	{
		$sql = "SELECT * FROM `".$config['db_tables_logos']."` WHERE `logo_num` = '1'";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		$row = mysql_fetch_assoc($result);
		$RowNum = $row['id'];
		$logoNum = "1";
		$filefield = "logo1";
	}
	else
	{
		$sql = "SELECT * FROM `".$config['db_tables_logos']."` WHERE `logo_num` = '2'";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		$row = mysql_fetch_assoc($result);
		$RowNum = $row['id'];
		$logoNum = '2';
		$filefield = 'logo2';
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
		echo 'The Uploaded file MUST be a GIF IMAGE!';
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

	case 'DELETE':
		DeleteLogo();
		Main();
		break;

	default:
		Main();
		break;
}


?>