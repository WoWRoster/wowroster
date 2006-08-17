<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

$loginForm = "
<br />
<form class='ua_loginbox' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."help'>
	<fieldset>
	<legend>Please Log-In</legend>
		<p><label for='username'>Username</label> <input class='input' type='text' id='name' name='name' maxlength='30' /></p>
		<p><label for='password'>Password</label> <input class='input' type='password' id='password' name='password' maxlength='30' /></p>
		<p><input class='submit' type='submit' value='Login'></p>
	</fieldset>
</form>
";

if( isset($_POST['ua_logout']) && $_POST['ua_logout'] == '1' )
{

	setcookie('UA','',time()-86400);
	EchoPage('','Log-In');
	die('');

}
else
{
	if (!isset($_COOKIE['UA']))
	{

		if (isset($_POST['name']))
		{
			$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `name` LIKE '".$_POST['name']."'";
			$result = mysql_query($sql, $dblink);
			MySqlCheck($dblink,$sql);
			$row = mysql_fetch_assoc($result);

			//echo md5($_POST['password']);

			if (md5($_POST['password']) == $row['password'])
			{
				setcookie('UA',$_POST['name'].'|'.md5($_POST['password']));
				$loginForm = 'Logged in as: '.$_POST['name']." <form name='ua_logoutform' style='display:inline;' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."'><input type='hidden' name='ua_logout' value='1' />[<a href='javascript:document.ua_logoutform.submit();'>Logout</a>]</form><br />";
			}
			else
			{
				$loginForm = "<span style='color:red;'>Wrong username and/or password</span><br />".$loginForm;
				EchoPage('','Log-In');
				die('');

			}
		}
		else
		{
			EchoPage('','Log-In');
			die('');
		}
	}
	else
	{
		$BigCookie = explode('|',$_COOKIE['UA']);
		$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `name` LIKE '".$BigCookie[0]."'";
		$result = mysql_query($sql, $dblink);
		MySqlCheck($dblink,$sql);
		$row = mysql_fetch_assoc($result);

		if ($BigCookie[1] == $row['password'])
		{
			$loginForm = 'Logged in as: '.$BigCookie[0]." <form name='ua_logoutform' style='display:inline;' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."'><input type='hidden' name='ua_logout' value='1' />[<a href='javascript:document.ua_logoutform.submit();'>Logout</a>]</form><br />";
		}
		else
		{
			setcookie('UA','',time()-86400);
			EchoPage('','Log-In');
			die('');
		}
	}
}

?>