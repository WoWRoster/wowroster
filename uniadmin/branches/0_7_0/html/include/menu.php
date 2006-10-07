<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

$ua_menu = '
	<a href="'.UA_INDEXPAGE.'=help">'.$user->lang['title_help'].'</a> |
	<a href="'.UA_INDEXPAGE.'=addons">'.$user->lang['title_addons'].'</a> |
	<a href="'.UA_INDEXPAGE.'=logo">'.$user->lang['title_logo'].'</a> |
	<a href="'.UA_INDEXPAGE.'=settings">'.$user->lang['title_settings'].'</a> |
	<a href="'.UA_INDEXPAGE.'=stats">'.$user->lang['title_stats'].'</a> |
	<a href="'.UA_INDEXPAGE.'=users">'.$user->lang['title_users'].'</a>
';

if( isset($user->data['level']) && $user->data['level'] == UA_ID_ADMIN )
{
	$ua_menu .= '	| <a href="'.UA_INDEXPAGE.'=pref">'.$user->lang['title_config'].'</a>';
}

?>