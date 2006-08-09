<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

function MySqlCheck($dblink,$sql)
{

	if (mysql_error($dblink) != "")
	{
		debug("<font color='red'>ERROR:</font> ".mysql_error($dblink)."<BR>".$sql);
	}
}

?>