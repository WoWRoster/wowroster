<?php

include("config.php");
if (!isset ($_POST['op'])) {
	$op = $_REQUEST['op'];
} else {
	$op = $_POST['op'];
}

if (!isset ($_POST['id'])) {
	$id = $id;
} else {
	$id = $_POST['id'];
}

if (!isset ($_POST)) {
	$post = $post;
} else {
	$post = $_POST;
}

if (!isset ($_REQUEST)) {
	$request = $request;
} else {
	$request = $_REQUEST;
}


function Main($message = ""){
	global $dblink, $config;

	$username = GetUsername();
	$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `name` LIKE '$username'";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$row = mysql_fetch_assoc($result);
	$currentUserL = $row['level'];


	$addform = "
	<form name='modifyuser' method='post' ENCTYPE='multipart/form-data' action='users.php'>
	<input type='hidden' value='new' name='op'>
	<table class='uuTABLE'>
	<tr><th>Add a user:</th></tr>
	<tr><td>Username: <input type=textbox name='name' value='' size='15' maxlength='15'></td></tr>
	<tr><td>Password: &nbsp;<input type=textbox name='password' value='' size='15' maxlength='15'></td><td>";
	if ($currentUserL > 2){
		$addform .= "<tr><td>UserLevel: <input type=textbox name='level' value='1' size='3' maxlength='3'></td></tr>";
	}else{
		$addform .= "<tr><td>UserLevel: 1</td></tr>";
	}
	$addform .= "
	<tr><td colspan=2><input type='submit' value='Add User'></td></tr>

	</table>
	</form>";


	if ($currentUserL > 1){
		EchoPage("$message<br><center>".CreateUserTable()."<br>$addform</center>");
	}else{
		EchoPage("$message<br><center>".CreateUserTable()."</center>");
	}

}

function CreateUserTable(){
	global $dblink, $config;
	$username = GetUsername();
	$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `name` LIKE '$username'";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$row = mysql_fetch_assoc($result);
	if ($row['level'] > 1)$canAddEdit = true; else $canAddEdit = false;
	$currentUserL = $row['level'];

	$sql = "SELECT * FROM `".$config['db_tables_users']."` ORDER BY `name` ASC";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);


	$table = "<table class='uuTABLE' id=\"user_table\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">
	<tr>
		<th class=\"tableHeader\">User Name</th>
		<th class=\"tableHeader\">User Level</th>
		<th class=\"tableHeader\">Modify</th>
		<th class=\"tableHeader\">Delete</th>
	</tr>
	";
	while ($user = mysql_fetch_assoc($result)) {
		if($i % 2){
			$tdClass = 'data2';
		}else{
			$tdClass = 'data1';
		}

		$userN = $user['name'];
		$userL = $user['level'];
		$userI = $user['id'];
		$table .= "<tr>";
		if (strtoupper($userN) == strtoupper($username) || $canAddEdit){


			$table .= "
			<td class=\"$tdClass\"  valign=\"top\">$userN</td>
			<td class=\"$tdClass\"  valign=\"top\">$userL</td>
			";

			if (strtoupper($userN) == strtoupper($username) || $currentUserL > 2):
			$table .= "
			<td class=\"$tdClass\"  valign=\"top\"><a href='users.php?op=edit&amp;uid=$userI'>Modify</a></td>
			<td class=\"$tdClass\"  valign=\"top\"><a href='users.php?op=delete&amp;uid=$userI'>Delete</a></td>";
			elseif ($currentUserL == "2" && $userL == "1"):
			$table .= "
			<td class=\"$tdClass\"  valign=\"top\"><a href='users.php?op=edit&amp;uid=$userI'>Modify</a></td>
			<td class=\"$tdClass\"  valign=\"top\"><a href='users.php?op=delete&amp;uid=$userI'>Delete</a></td>";

			endif;



		}else{
			$table .= "
			<td class=\"$tdClass\"  valign=\"top\">$userN</td>
			<td class=\"$tdClass\"  valign=\"top\">$userL</td>
			<td class=\"$tdClass\"  valign=\"top\"></td>
			<td class=\"$tdClass\"  valign=\"top\"></td>";
		}
		$table .= "</tr>";
		$i++;
	}
	$table .= "</table>";

	return $table;

}

function Modify(){
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
	<form name='modifyuser' method='post' ENCTYPE='multipart/form-data' action='users.php'>
	<input type='hidden' value='edit2' name='op'>
	<input type='hidden' value='$uid' name='uid'>
	<table class='uuTABLE'>
	<tr>
	<th>Edit User</th>
	</tr>
	";
	if ($currentUserL > 1){
		$form .= "<tr><td>Username: <input type=textbox name='newname' value='$userN' size='15' maxlength='15'></td></tr>";
		if ($currentUserL > 2)$form .= "<tr><td>UserLevel: <input type=textbox name='level' value='$userL' size='3' maxlength='3'></td></tr>";
	}else{
		$form .= "<tr><td>Username: $userN</td></tr>";
		$form .= "<tr><td>UserLevel: $userL</td></tr>";
	}
	$form .= "
	<tr><td>Password: &nbsp;<input type=textbox name='newpassword' value='' size='15' maxlength='15'></td><td></td></tr>
	<tr><td colspan=2><input type='submit' value='Modify User'></td></tr>

	</table>
	</form>";

	EchoPage("<br><center>".$form."</center>");

}

function Modify2(){
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

	if ($userP == "")$userP = $oldPasswordHash; else $userP = md5($userP);

	if ($currentUserL > 1){

		if ($currentUserI != $userI)
		{
			if ($currentUserL < 3)$userL = 1;
			$sql = "UPDATE `".$config['db_tables_users']."` SET `name` = '$userN', `level` = '$userL', `password` = '$userP' WHERE `id` = '$userI' LIMIT 1 ;";
		}else{
			$sql = "UPDATE `".$config['db_tables_users']."` SET `name` = '$userN', `password` = '$userP' WHERE `id` = '$userI' LIMIT 1 ;";
		}
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
	}else{
		// user is level 1 and changing own password
		if ($currentUserI != $userI)die("die hacker!");
		$sql = "UPDATE `".$config['db_tables_users']."` SET `password` = '$userP' WHERE `id` = '$userI' LIMIT 1 ;";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		$userN = $currentUserN;
	}
	Main("<br><center>User '$userN' modified.</center>");
}

function NewUser(){
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

	if ($currentUserL > 1){
		if ($currentUserL > 2){
			$sql = "INSERT INTO `".$config['db_tables_users']."` ( `id` , `name` , `password` , `level` )VALUES ('', '$userN', '".md5($userP)."', '$userL');";
			$result = mysql_query($sql,$dblink);
			MySqlCheck($dblink,$sql);
			Main("<br><center>User '$userN' added.</center>");
		}else{
			$sql = "INSERT INTO `".$config['db_tables_users']."` ( `id` , `name` , `password` , `level` )VALUES ('', '$userN', '".md5($userP)."', '1');";
			$result = mysql_query($sql,$dblink);
			MySqlCheck($dblink,$sql);
			Main("<br><center>User '$userN' added.</center>");
		}


	}else{
		die("die hacker!");
	}

}

function DeleteUser(){
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


	if ($currentUserI == $userI || $currentUserL > 2):
	$sql = "DELETE FROM `".$config['db_tables_users']."` WHERE `id` = '$userI' LIMIT 1";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	Main("<br><center>User '$userN' deleted.</center>");

	elseif ($currentUserL == 2 && $userI == 1):
	$sql = "DELETE FROM `".$config['db_tables_users']."` WHERE `id` = '$userI' LIMIT 1";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	Main("<br><center>User '$userN' deleted.</center>");

	else:
	die("die hacker!");
	endif;


}



switch ($op){

	case "edit":
	Modify();
	break;

	case "edit2":
	Modify2();
	break;

	case "new":
	newUser();
	break;

	case "delete":
	deleteUser();
	break;

	default:
	Main();
	break;
}

?>