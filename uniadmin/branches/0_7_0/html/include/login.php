<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

// Make the login form
$login_form = '
<br />
<form class="ua_loginbox" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<fieldset>
	<legend>'.$user->lang['title_login'].'</legend>
		<p><label for="name">'.$user->lang['username'].':</label> <input class="input" type="text" id="name" name="name" maxlength="30" /></p>
		<p><label for="password">'.$user->lang['password'].':</label> <input class="input" type="password" id="password" name="password" maxlength="30" /></p>
		<p><input class="submit" type="submit" value="'.$user->lang['login'].'" /></p>
	</fieldset>
</form>
<br />
[<a href="'.UA_INDEXPAGE.'=view">'.$user->lang['guest_access'].'</a>]

';

// Check if logging out
if( isset($_POST['ua_logout']) )
{
	setcookie('UA','',time()-86400);
	display_page('',$user->lang['title_login']);
	die('');
}
else // Logging in
{
	// Check if logging in
	if( !isset($_COOKIE['UA']) )
	{
		if( isset($_POST['name']) )
		{
			$row = get_user_info($_POST['name']);

			if( md5($_POST['password']) == $row['password'] )
			{
				setcookie('UA',$_POST['name'].'|'.md5($_POST['password']));
				$login_form = '<span style="font-size:10px;">'.sprintf($user->lang['logged_in_as'],$row['name']).'</span>: <form name="ua_logoutform" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'"><input class="submit" name="ua_logout" style="color:red;" type="submit" value="'.$user->lang['logout'].'" /></form><br />';
				$user->create($row);
			}
			else
			{
				$login_form = '<span style="font-size:10px;color:red;">'.$user->lang['error_invalid_login'].'</span><br />'.$login_form;
				display_page('',$user->lang['title_login']);
				die('');
			}
		}
		else
		{
			display_page('',$user->lang['title_login']);
			die('');
		}
	}
	else // Cookie is set
	{
		$BigCookie = explode('|',$_COOKIE['UA']);

		$row = get_user_info();

		if( $BigCookie[1] == $row['password'] )
		{
			$login_form = '<span style="font-size:10px;">'.sprintf($user->lang['logged_in_as'],$row['name']).'</span>: <form name="ua_logoutform" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'"><input class="submit" name="ua_logout" style="color:red;" type="submit" value="'.$user->lang['logout'].'" /></form><br />';
			$user->create($row);
		}
		else
		{
			setcookie('UA','',time()-86400);
			display_page('',$user->lang['title_login']);
			die('');
		}
	}
}

?>