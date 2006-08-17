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


function Main($message = '')
{
	global $dblink, $config;

	$username = GetUsername();
	$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `name` LIKE '$username'";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$row = mysql_fetch_assoc($result);
	$currentUserL = $row['level'];


	$addform = "
	<form name='modifyuser' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."users'>
	<input type='hidden' value='new' name='op'>
	<table class='uuTABLE'>
		<tr>
			<th colspan='2' class='tableHeader'>Add a user</th>
		</tr>
		<tr>
			<td class='data1' align='right'>Username:</td>
			<td class='data1'><input class='input' type='textbox' name='name' value='' size='15' maxlength='15'></td>
		</tr>
		<tr>
			<td class='data2' align='right'>Password:</td>
			<td class='data2'><input class='input' type='textbox' name='password' value='' size='15' maxlength='15'></td>
		<tr>
			<td class='data1' align='right'>";
	if ($currentUserL > 2)
	{
		$addform .= "UserLevel:</td>
		<td class='data1'><select class='select' name='level'>
				<option value='1' selected='selected'>basic user (level 1)</option>
				<option value='2'>power user (level 2)</option>
				<option value='3'>administrator (level 3)</option>
			</select>";
	}
	else
	{
		$addform .= 'UserLevel: [1]';
	}
	$addform .= "</td>
		</tr>
		<tr>
			<td class='data2'></td>
			<td class='data2'><input class='submit' type='submit' value='Add User'></td>
		</tr>
	</table>
	</form>";


	if ($currentUserL > 1)
	{
		EchoPage($message.CreateUserTable()."<br />$addform",'Users');
	}
	else
	{
		EchoPage($message.CreateUserTable(),'Users');
	}

}

function CreateUserTable()
{
	global $dblink, $config;

	$username = GetUsername();
	$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `name` LIKE '$username'";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$row = mysql_fetch_assoc($result);
	if ($row['level'] > 1)
		$canAddEdit = true;
	else
		$canAddEdit = false;

	$currentUserL = $row['level'];

	$sql = "SELECT * FROM `".$config['db_tables_users']."` ORDER BY `name` ASC";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);


	$table = "<table class='uuTABLE' width='50%'>
	<tr>
		<th colspan='4' class='tableHeader'>Current Users</th>
	</tr>
	<tr>
		<td class='dataHeader'>User Name</td>
		<td class='dataHeader'>User Level</td>
		<td class='dataHeader'>Modify</td>
		<td class='dataHeader'>Delete</td>
	</tr>
	";

	$i=0;
	while ($user = mysql_fetch_assoc($result))
	{
		if($i % 2)
		{
			$tdClass = 'data2';
		}
		else
		{
			$tdClass = 'data1';
		}

		$userN = $user['name'];
		$userL = $user['level'];
		$userI = $user['id'];
		$table .= '<tr>';
		if (strtoupper($userN) == strtoupper($username) || $canAddEdit)
		{
			$table .= "
			<td class='$tdClass'  valign='top'>$userN</td>
			<td class='$tdClass'  valign='top'>$userL</td>
			";

			if (strtoupper($userN) == strtoupper($username) || $currentUserL > 2)
			{
				$table .= "
			<td class='$tdClass'  valign='top'><a href='".UA_FORMACTION."users&amp;op=edit&amp;uid=$userI'>Modify</a></td>
			<td class='$tdClass'  valign='top'><a href='".UA_FORMACTION."users&amp;op=delete&amp;uid=$userI'>Delete</a></td>";
			}
			elseif ($currentUserL == '2' && $userL == '1')
			{
				$table .= "
			<td class='$tdClass'  valign='top'><a href='".UA_FORMACTION."users&amp;op=edit&amp;uid=$userI'>Modify</a></td>
			<td class='$tdClass'  valign='top'><a href='".UA_FORMACTION."users&amp;op=delete&amp;uid=$userI'>Delete</a></td>";
			}



		}
		else
		{
			$table .= "
			<td class='$tdClass'  valign='top'>$userN</td>
			<td class='$tdClass'  valign='top'>$userL</td>
			<td class='$tdClass'  valign='top'></td>
			<td class='$tdClass'  valign='top'></td>";
		}
		$table .= '</tr>';
		$i++;
	}
	$table .= '</table>';

	return $table;

}

function Modify()
{
	global $dblink, $config;

	$uid = $_REQUEST['uid'];
	$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `id` = '$uid'";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$row = mysql_fetch_assoc($result);
	$userN = $row['name'];
	$userL = $row['level'];

	$username = GetUsername();
	$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `name` LIKE '$username'";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$row = mysql_fetch_assoc($result);
	$currentUserL = $row['level'];



	$form = "
	<form name='modifyuser' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."users'>
	<input type='hidden' value='edit2' name='op'>
	<input type='hidden' value='$uid' name='uid'>
	<table class='uuTABLE'>
		<tr>
			<th colspan='2' class='tableHeader'>Edit User</th>
		</tr>
	";
	if ($currentUserL > 1)
	{
		$form .= "		<tr>
			<td class='data1' align='right'>Change Username:</td>
			<td class='data1'><input class='input' type='textbox' name='newname' value='$userN' size='15' maxlength='15'></td>
		</tr>";
		if ($currentUserL > 2)$form .= "		<tr>
			<td class='data2' align='right'>Change UserLevel:</td>
			<td class='data2'><select class='select' name='level'>
					<option value='1'".($userL == '1' ? " selected='selected'" : '').">basic user (level 1)</option>
					<option value='2'".($userL == '2' ? " selected='selected'" : '').">power user (level 2)</option>
					<option value='3'".($userL == '3' ? " selected='selected'" : '').">administrator (level 3)</option>
				</select></td>
		</tr>";
	}
	else
	{
		$form .= "		<tr>
			<td class='data1' align='right'>Username:</td>
			<td class='data1'>[$userN]</td>
		</tr>
		<tr>
			<td class='data2' align='right'>UserLevel:</td>
			<td class='data2'>[$userL]</td>
		</tr>";
	}
	$form .= "
		<tr>
			<td class='data1' align='right'>Change Password:</td>
			<td class='data2'><input class='input' type='textbox' name='newpassword' value='' size='15' maxlength='15'></td>
		</tr>
		<tr>
			<td class='data2'>&nbsp;</td>
			<td class='data2'><input class='submit' type='submit' value='Modify User'></td>
		</tr>
	</table>
	</form>";

	EchoPage($form,'Users');

}

function Modify2()
{
	global $dblink, $config;

	$userN = $_POST['newname'];
	$userI = $_POST['uid'];
	$userP = $_POST['newpassword'];
	$userL = $_POST['level'];

	$username = GetUsername();
	$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `name` LIKE '$username'";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$row = mysql_fetch_assoc($result);
	$currentUserI = $row['id'];
	$currentUserL = $row['level'];
	$currentUserN = $row['name'];

	$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `name` LIKE '$userN'";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$row = mysql_fetch_assoc($result);
	$oldPasswordHash = $row['password'];

	if ($userP == '')
		$userP = $oldPasswordHash;
	else
		$userP = md5($userP);

	if ($currentUserL > 1)
	{
		if ($currentUserI != $userI)
		{
			if ($currentUserL < 3)
				$userL = 1;
			$sql = "UPDATE `".$config['db_tables_users']."` SET `name` = '$userN', `level` = '$userL', `password` = '$userP' WHERE `id` = '$userI' LIMIT 1 ;";
		}
		else
		{
			$sql = "UPDATE `".$config['db_tables_users']."` SET `name` = '$userN', `password` = '$userP' WHERE `id` = '$userI' LIMIT 1 ;";
		}
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
	}
	else
	{
		// user is level 1 and changing own password
		if ($currentUserI != $userI)
			die('die hacker!');

		$sql = "UPDATE `".$config['db_tables_users']."` SET `password` = '$userP' WHERE `id` = '$userI' LIMIT 1 ;";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		$userN = $currentUserN;
	}
	message("User '$userN' modified");
}

function NewUser()
{
	global $dblink, $config;

	$userN = $_POST['name'];
	$userP = $_POST['password'];
	$userL = $_POST['level'];

	$username = GetUsername();
	$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `name` LIKE '$username'";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$row = mysql_fetch_assoc($result);
	$currentUserL = $row['level'];

	if ($currentUserL > 1)
	{
		if ($currentUserL > 2)
		{
			$sql = "INSERT INTO `".$config['db_tables_users']."` ( `id` , `name` , `password` , `level` )VALUES ('', '$userN', '".md5($userP)."', '$userL');";
			$result = mysql_query($sql,$dblink);
			MySqlCheck($dblink,$sql);
			message("User '$userN' added");
		}
		else
		{
			$sql = "INSERT INTO `".$config['db_tables_users']."` ( `id` , `name` , `password` , `level` )VALUES ('', '$userN', '".md5($userP)."', '1');";
			$result = mysql_query($sql,$dblink);
			MySqlCheck($dblink,$sql);
			message("User '$userN' added");
		}
	}
	else
	{
		die('die hacker!');
	}

}

function DeleteUser()
{
	global $dblink, $config;

	$userI = $_REQUEST['uid'];

	$username = GetUsername();
	$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `name` LIKE '$username'";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$row = mysql_fetch_assoc($result);
	$currentUserI = $row['id'];
	$currentUserL = $row['level'];

	$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `id` = '$userI'";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$row = mysql_fetch_assoc($result);
	$userN = $row['name'];


	if ($currentUserI == $userI || $currentUserL > 2)
	{
		$sql = "DELETE FROM `".$config['db_tables_users']."` WHERE `id` = '$userI' LIMIT 1";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		message("User '$userN' deleted");
	}
	elseif ($currentUserL == 2 && $userI == 1)
	{
		$sql = "DELETE FROM `".$config['db_tables_users']."` WHERE `id` = '$userI' LIMIT 1";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		message("User '$userN' deleted");
	}
	else
	{
		die('die hacker!');
	}


}



switch ($op)
{

	case 'edit':
		Modify();
		break;

	case 'edit2':
		Modify2();
		Main();
		break;

	case 'new':
		newUser();
		Main();
		break;

	case 'delete':
		deleteUser();
		Main();
		break;

	default:
		Main();
		break;
}

?>