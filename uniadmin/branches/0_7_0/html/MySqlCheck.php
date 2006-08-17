<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

function MySqlCheck($dblink,$sql)
{
	if (mysql_error($dblink) != '')
	{
		debug("<span style='color:red;'>ERROR:</span> ".mysql_error($dblink));
		debug($sql);
	}
}

?>