<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

$loginForm = '
<br />
<form class="ua_loginbox" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<fieldset>
	<legend>'.$user->lang['title_login'].'</legend>
		<p><label for="username">'.$user->lang['username'].':</label> <input class="input" type="text" id="name" name="name" maxlength="30" /></p>
		<p><label for="password">'.$user->lang['password'].':</label> <input class="input" type="password" id="password" name="password" maxlength="30" /></p>
		<p><input class="submit" type="submit" value="'.$user->lang['login'].'" /></p>
	</fieldset>
</form>
<br />
[<a href="'.UA_INDEXPAGE.'?p=view">'.$user->lang['guest_access'].'</a>]

';

if( isset($_POST['ua_logout']) )
{
	setcookie('UA','',time()-86400);
	EchoPage('',$user->lang['title_login']);
	die('');
}
else
{
	if (!isset($_COOKIE['UA']))
	{
		if (isset($_POST['name']))
		{
			$row = GetUserinfo($_POST['name']);

			if (md5($_POST['password']) == $row['password'])
			{
				setcookie('UA',$_POST['name'].'|'.md5($_POST['password']));
				$loginForm = '<span style="font-size:10px;">'.sprintf($user->lang['logged_in_as'],$row['name']).'</span>: <form name="ua_logoutform" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'"><input class="submit" name="ua_logout" style="color:red;" type="submit" value="'.$user->lang['logout'].'" /></form><br />';
				$user->create($row);
			}
			else
			{
				$loginForm = '<span style="font-size:10px;color:red;">'.$user->lang['error_invalid_login'].'</span><br />'.$loginForm;
				EchoPage('',$user->lang['title_login']);
				die('');
			}
		}
		else
		{
			EchoPage('',$user->lang['title_login']);
			die('');
		}
	}
	else
	{
		$BigCookie = explode('|',$_COOKIE['UA']);

		$row = GetUserinfo();

		if ($BigCookie[1] == $row['password'])
		{
			$loginForm = '<span style="font-size:10px;">'.sprintf($user->lang['logged_in_as'],$row['name']).'</span>: <form name="ua_logoutform" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'"><input class="submit" name="ua_logout" style="color:red;" type="submit" value="'.$user->lang['logout'].'" /></form><br />';
			$user->create($row);
		}
		else
		{
			setcookie('UA','',time()-86400);
			EchoPage('',$user->lang['title_login']);
			die('');
		}
	}
}

?>