<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

function GetUsername()
{
	global $_COOKIE;

	$BigCookie = explode('|',$_COOKIE['UA']);
	return $BigCookie[0];
}



?>