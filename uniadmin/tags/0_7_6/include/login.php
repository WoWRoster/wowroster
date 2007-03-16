<?php
/******************************
 * WoWRoster.net  UniAdmin
 * Copyright 2002-2007
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

$tpl->assign_vars(array(
	'L_USERNAME'     => $user->lang['username'],
	'L_PASSWORD'     => $user->lang['password'],
	'L_LOGIN'        => $user->lang['login'],
	'L_GUEST_ACCESS' => $user->lang['guest_access'],
	'S_LOGIN_MSG'    => false,
	'S_LOGIN'        => false,
	)
);

$wrong_pass = '<span style="font-size:10px;color:red;">'.$user->lang['error_invalid_login'].'</span><br />';

// Check if logging out
if( isset($_POST['ua_logout']) )
{
	setcookie('UA','',time()-86400);
	$tpl->assign_var('S_LOGIN',true);
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
				$logged_in = '<span style="font-size:10px;">'.sprintf($user->lang['logged_in_as'],$row['name']).'</span>: <form name="ua_logoutform" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'"><input class="submit" name="ua_logout" style="color:red;" type="submit" value="'.$user->lang['logout'].'" /></form><br />';
				setcookie('UA',$_POST['name'].'|'.md5($_POST['password']));
				$tpl->assign_vars(array(
					'S_LOGIN_MSG' => true,
					'U_LOGIN_MSG' => $logged_in,
					)
				);
				$user->create($row);
				unset($row);
			}
			else
			{
				$tpl->assign_vars(array(
					'S_LOGIN_MSG' => true,
					'S_LOGIN'     => true,
					'U_LOGIN_MSG' => $wrong_pass,
					)
				);
			}
		}
		else
		{
			$tpl->assign_var('S_LOGIN',true);
		}
	}
	else // Cookie is set
	{
		$BigCookie = explode('|',$_COOKIE['UA']);

		$row = get_user_info();

		if( $BigCookie[1] == $row['password'] )
		{
			$logged_in = '<span style="font-size:10px;">'.sprintf($user->lang['logged_in_as'],$row['name']).'</span>: <form name="ua_logoutform" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'"><input class="submit" name="ua_logout" style="color:red;" type="submit" value="'.$user->lang['logout'].'" /></form><br />';
			$tpl->assign_vars(array(
				'S_LOGIN_MSG' => true,
				'U_LOGIN_MSG' => $logged_in,
				)
			);
			$user->create($row);
			unset($row);
		}
		else
		{
			setcookie('UA','',time()-86400);
			$tpl->assign_var('S_LOGIN',true);
		}
	}
}
