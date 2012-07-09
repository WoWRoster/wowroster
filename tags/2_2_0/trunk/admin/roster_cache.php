<?php

if( !defined('IN_ROSTER') || !defined('IN_ROSTER_ADMIN') )
{
	exit('Detected invalid access to this file!');
}

$roster->output['title'] .= 'Purge Cache';
include_once (ROSTER_LIB . 'cache.php');
		$cache = new RosterCache();
		$cache->cleanCache();
$body = 'Cache Purged';