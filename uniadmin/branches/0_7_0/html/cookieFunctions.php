<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

function GetUsername()
{
	if( isset($_COOKIE['UA']) )
	{
		$BigCookie = explode('|',$_COOKIE['UA']);
		return $BigCookie[0];
	}
	else
	{
		return '';
	}
}

function GetUserinfo()
{
	global $dblink, $config;

	$username = GetUsername();
	$sql = "SELECT * FROM `".$config['db_tables_users']."` WHERE `name` LIKE '$username'";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$row = mysql_fetch_assoc($result);

	return $row;
}


?>