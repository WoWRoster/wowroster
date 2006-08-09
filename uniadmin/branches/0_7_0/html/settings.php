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

	//$sql = "SELECT * FROM `".$config['db_tables_settings']."` ORDER BY `enabled` DESC";
	$sql = "SELECT * FROM `".$config['db_tables_settings']."` ORDER BY `id` DESC";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);

	$form = "
<form method='post' enctype='multipart/form-data' action='".UA_FORMACTION."settings'>
<table class='uuTABLE'>
	<tr>
		<td class='tableHeader'>Setting Name</td>
		<td class='tableHeader'>Description</td>
		<td class='tableHeader'>Value</td>
		<td class='tableHeader'>Enabled</td>
	</tr>";

	$i=0;
	while ($row = mysql_fetch_assoc($result))
	{
		$description = stringChop($row['description'],25,"...");
		$setname = stringChop($row['set_name'],20,"...");

		if($i % 2)
		{
			$tdClass = 'data2';
		}
		else
		{
			$tdClass = 'data1';
		}

		$form .= "
		<tr>
			<td class='$tdClass' title='".$row['set_name']."'>$setname</td>
			<td class='$tdClass' onmouseover=\"return overlib('<img src=images/".$row['set_name'].".jpg>',CAPTION,'".$row['description']."',VAUTO);\" onmouseout=\"return nd();\">$description</td>
			<td class='$tdClass'><input class='input' type='textbox' size='50' name='".$row['set_name']."' value='".$row['set_value']."'></td>";

		if ($row['enabled'] == "1")
		{
			$form .= "<td class='$tdClass' align='center'><input type='checkbox' name='".$row['set_name']."enabled' checked='checked'></td>";
		}
		else
		{
			$form .= "<td class='$tdClass' align='center'><input type='checkbox' name='".$row['set_name']."enabled'></td>";
		}
		$form .= "</tr>";
		$i++;
	}

	$form .= "<tr>
		<td class='dataHeader' colspan='4' align='center'><input type='hidden' name='op' value='PROCESSUPDATE'>
			<input class='submit' type='submit' value='Update Settings'></td>
	</tr>
	</table>
	</form>";
	$sql = "SELECT * FROM `".$config['db_tables_svlist']."` ORDER BY `id` DESC";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);

	$svTable = "
	<table class='uuTABLE'>
		<tr>
			<td class='tableHeader'>Saved Variable Files</td>
			<td class='tableHeader'>Remove</td>
		</tr>
	";

	while ($row = mysql_fetch_assoc($result))
	{

		if($i % 2)
		{
			$tdClass = 'data2';
		}
		else
		{
			$tdClass = 'data1';
		}

		$svTable .= "
		<tr>
			<td class='$tdClass'>".$row['sv_name']." <b>.lua</b></td>
			<td class='$tdClass'>
				<form style='display:inline;' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."settings'>
					<input class='submit' type='submit' value='Remove'>
					<input type='hidden' value='".$row['id']."' name='svid'>
					<input type='hidden' value='removesv' name='op'>
				</form>
			</td>
		</tr>

		";

		$i++;
	}

	if($i % 2)
	{
		$tdClass = 'data2';
	}
	else
	{
		$tdClass = 'data1';
	}

	$svTable .= "
	</table>

	<br />

	<form method='post' enctype='multipart/form-data' action='".UA_FORMACTION."settings'>
	<input type='hidden' value='addsv' name='op'>
	<table class='uuTABLE'>
		<tr>
			<td class='tableHeader'>Add Saved Variable File</td>
			<td class='tableHeader'>Add</td>
		</tr>
		<tr>
			<td class='$tdClass'><input class='input' type='text' name='svname'> <b>.lua</b></td>
			<td class='$tdClass'><input class='submit' type='submit' value='Add'></td>
		</tr>
	</table>
	</form>



	";

	EchoPage($svTable."<br />".$form,'UU Settings');
}

function stringChop($string, $desiredLength, $suffix)
{
	if (strlen($string) > $desiredLength)
	{
		$string = substr($string,0,$desiredLength).$suffix;
		return $string;
	}
	return $string;
}

function ProcessUpdate()
{
	global $dblink, $config;
	foreach ($_POST as $settingName => $settingValue)
	{
		if(substr_count(strtoupper($settingName),"enabled") > 1)
			break;
		if ( isset($_POST["$settingName"."enabled"]) && $_POST["$settingName"."enabled"] == "on")
		{
			$enabled = "1";
		}
		else
		{
			$enabled = "0";
		}
		$sql = "UPDATE `".$config['db_tables_settings']."` SET `enabled` = '$enabled', `set_value` = '$settingValue' WHERE `set_name` = '$settingName' LIMIT 1 ;";
		mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
	}
}

function addSv()
{
	global $dblink, $config;

	$sql = "INSERT INTO `".$config['db_tables_svlist']."` ( `id` , `sv_name` ) VALUES ( '', '".$_POST['svname']."' );";
	mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);

}

function removeSv()
{
	global $dblink, $config;

	$sql = "DELETE FROM `".$config['db_tables_svlist']."` WHERE `id` = ".$_POST['svid']." LIMIT 1;";
	mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);

}

switch ($op)
{

	case "PROCESSUPDATE":
	ProcessUpdate();
	Main();
	break;

	case "addsv":
	addSv();
	Main();
	break;

	case "removesv":
	removeSv();
	Main();
	break;

	default:
	Main();
	break;
}






?>