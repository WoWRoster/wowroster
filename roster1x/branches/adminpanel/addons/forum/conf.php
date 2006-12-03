<?php
/******************************
 * WoWRoster.net  Roster
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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

if( !isset($roster_conf))
{
	global $roster_conf;
}

if( !isset($db_prefix))
{
	global $db_prefix;
}

if( !defined('ROSTER_FORUM_TABLE_CONFIG') )
{
	define('ROSTER_FORUM_TABLE_CONFIG',$db_prefix.'addons_forum_default_config');
}

if( !defined('ROSTER_FORUM_TABLE_FORUMS') )
{
	define('ROSTER_FORUM_TABLE_FORUMS',$db_prefix.'addons_forum_default_forums');
}

if( !defined('ROSTER_FORUM_TABLE_THREADS') )
{
	define('ROSTER_FORUM_TABLE_THREADS',$db_prefix.'addons_forum_default_threads');
}

if( !defined('ROSTER_FORUM_URL') )
{
	define('ROSTER_FORUM_URL',$roster_conf['roster_dir'].'/addons/forum');
}
if( !defined('ROSTER_FORUM_ACCESS_URL') )
{
	define('ROSTER_FORUM_ACCESS_URL',$roster_conf['roster_dir'].'/addon.php?dbname=forum');
}
if( !defined('ROSTER_FORUM_IMG_URL') )
{
	define('ROSTER_FORUM_IMG_URL',ROSTER_FORUM_URL.'/img');
}


?>