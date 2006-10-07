<?php

if( !defined('IN_UNIADMIN') )
{
	exit('Detected invalid access to this file!');
}

switch ( $config['dbtype'] )
{
	case 'mysql':
		include_once(UA_INCLUDEDIR . 'dbal' . DIR_SEP . 'mysql.php');
		break;

	default:
		include_once(UA_INCLUDEDIR . 'dbal' . DIR_SEP . 'mysql.php');
		break;
}

$db = new SQL_DB($config['host'], $config['database'], $config['username'], $config['password'], false);
if ( !$db->link_id )
{
	print("Cannot connect to the database<br />\n");
	print('MySQL Said: ' . mysql_error() );
	die();
}
?>