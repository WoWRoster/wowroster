<?php

$loginForm = "
<center><form name='login' method='post' ENCTYPE='multipart/form-data' action='index.php'>
<table>
<tr><td colspan=2><font color=red>Please log in:</font></td></tr>
<tr>
<td>UserName:</td><td><input type=textbox name='name' value='' size='10' maxlength='30'></td>
</tr>
<tr>
<td>Password:</td><td><input type=password name='password' value='' size='10' maxlength='30'></td>
</tr>
<tr>
<td></td><td><input type='submit' value='Login'></td>
</tr>
</table>
</form></center>
";

if (isset($_REQUEST['logout'])){

	setcookie("UA","",time()-86400);
	EchoPage($loginForm);
	die("");

}else{
	if (!isset($_COOKIE['UA'])){

		if (isset($_POST['name'])){
			$sql = "select * from `".$config['db_tables_users']."` where `name` like '".$_POST['name']."'";
			$result = mysql_query($sql, $dblink);
			MySqlCheck($dblink,$sql);
			$row = mysql_fetch_assoc($result);

			//echo md5($_POST['password']);

			if (md5($_POST['password']) == $row['password']){
				setcookie("UA",$_POST['name']."|".md5($_POST['password']));
				$loginForm = "<center>Logged in as: ".$_POST['name']." [<a href='index.php?logout=logout'>Logout</a>]</center><br>";
			}else {
				$loginForm = "<center><font color=red>Wrong userName and/or password</font></center><br>".$loginForm;
				EchoPage($loginForm);
				die("");

			}
		}else{
			EchoPage($loginForm);
			die("");
		}

	}
	else {
		$BigCookie = explode("|",$_COOKIE['UA']);
		$sql = "select * from `".$config['db_tables_users']."` where `name` like '".$BigCookie[0]."'";
		$result = mysql_query($sql, $dblink);
		MySqlCheck($dblink,$sql);
		$row = mysql_fetch_assoc($result);

		if ($BigCookie[1] == $row['password']){
			$loginForm = "<center>Logged in as: ".$BigCookie[0]." [<a href='index.php?logout=logout'>Logout</a>]</center><br>";
		}
		else{
			setcookie("UA","",time()-86400);
			EchoPage($loginForm);
			die("");
		}
	}
}

?>