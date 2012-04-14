<?php 
/** 
 * 
 */ 
 
if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if( !isset($addon))
{
	$addon = getaddon('user');
}

if( !isset($user))
{
	include_once( $addon['inc_dir'] . 'users.lib.php' );
	$user = new user;
/*
	if( !isset($user->form))
	{
		include_once( $addon['inc_dir'] . 'form.lib.php');
		$user->form = new userForm;
	}
/*
	if( !isset($user->admin))
	{
		include_once( $addon['inc_dir'] . 'admin.lib.php');
		$user->admin = new userAdmin;
	}

	if( !isset($user->page))
	{
		include_once( $addon['inc_dir'] . 'page.lib.php');
		$user->page = new userPage;
	}

	if( !isset($user->user))
	{
		include_once( $addon['inc_dir'] . 'user.lib.php');
		$user->user = new userUser;
	}

	if( !isset($user->profile))
	{
		include_once( $addon['inc_dir'] . 'profile.lib.php');
		$user->profile = new usersProfile;
	}

	if( !isset($user->messaging))
	{
		include_once( $addon['inc_dir'] . 'messaging.lib.php');
		$user->messaging = new userMessaging;
	}
*/
}
?>