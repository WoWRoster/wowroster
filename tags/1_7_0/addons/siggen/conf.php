<?php
/******************************
 * $Id$
 ******************************/

if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}


//------[ Show the SQL Queries Window? ]------------
	// This controls the display of the SQL Queries window in the SigGen config page
	$sc_show_sql_win = 1;



// ----[ Name Not Found Text ]------------------------------
// Text to output when name is not found in the member list
$sig_no_data = 'SigGen Works';




// ----[ SigGen directory ]---------------------------------
// This should be the path to the siggen addon directory
// Starting from where siggen config is accessed
$sigconfig_dir = ROSTER_BASE.'addons/siggen/';




// ----[ Define the sig_config table ]----------------------
if( !isset($db_prefix))
{
	global $db_prefix;
}

if( !defined('ROSTER_SIGCONFIGTABLE') )
{
	define('ROSTER_SIGCONFIGTABLE',$db_prefix.'addon_siggen');
}


//------[ END OF CONFIG ]-------------------------










// ----[ Database version DO NOT CHANGE!! ]-----------------
$sc_db_ver = '1.1';




define('SIGCONFIG_CONF',true);


?>