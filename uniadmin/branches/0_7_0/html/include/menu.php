<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

$ua_menu = '
	<a href="'.UA_INDEXPAGE.'?p=help">'.$user->lang['title_help'].'</a> |
	<a href="'.UA_INDEXPAGE.'?p=addons">'.$user->lang['title_addons'].'</a> |
	<a href="'.UA_INDEXPAGE.'?p=logo">'.$user->lang['title_logo'].'</a> |
	<a href="'.UA_INDEXPAGE.'?p=settings">'.$user->lang['title_settings'].'</a> |
	<a href="'.UA_INDEXPAGE.'?p=stats">'.$user->lang['title_stats'].'</a> |
	<a href="'.UA_INDEXPAGE.'?p=users">'.$user->lang['title_users'].'</a>
';

if( isset($user->data['level']) && $user->data['level'] == UA_ID_ADMIN )
{
	$ua_menu .= '	| <a href="'.UA_INDEXPAGE.'?p=config">'.$user->lang['title_config'].'</a>';
}

?>