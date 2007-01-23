<?php
/******************************
 * WoWRoster.net  UniAdmin
 * Copyright 2002-2006
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

// Create the main menu
$ua_menu = '
	<a href="'.UA_INDEXPAGE.'=help">'.$user->lang['title_help'].'</a> |
	<a href="'.UA_INDEXPAGE.'=addons">'.$user->lang['title_addons'].'</a> |
	<a href="'.UA_INDEXPAGE.'=logo">'.$user->lang['title_logo'].'</a> |
	<a href="'.UA_INDEXPAGE.'=settings">'.$user->lang['title_settings'].'</a> |
	<a href="'.UA_INDEXPAGE.'=stats">'.$user->lang['title_stats'].'</a> |
	<a href="'.UA_INDEXPAGE.'=users">'.$user->lang['title_users'].'</a>
';

// Check if user is an admin and give UA config option
if( isset($user->data['level']) && $user->data['level'] == UA_ID_ADMIN )
{
	$ua_menu .= '	| <a href="'.UA_INDEXPAGE.'=pref">'.$user->lang['title_config'].'</a>';
}
