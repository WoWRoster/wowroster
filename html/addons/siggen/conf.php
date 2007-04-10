<?php
/*
* $Date: 2006/01/15 22:48:46 $
* $Revision: 1.10 $
* $Author: zanix $
*/

if( eregi('conf.php',$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}


//------[ SigConfig password ]--------------------
	// Here is when you set the password to access Sigconfig
	// The default password is pulled form roster's guild update password
	// If you need/want a different password, change "$sc_pass = 'NewPassword';"
		$sc_pass = $roster_upd_pw;



//------[ Show the SQL Debug Window? ]------------
	// This controls the display of the SQL Debug window in the SigGen config page
		$sc_show_sql_win = 1;






//------[ END OF CONFIG ]-------------------------


// ----[ Database version DO NOT CHANGE!! ]-----------------
$sc_db_ver = '1.1';





//------[ Update Triggers ]-----------------------
// THERE IS NO NEED TO EDIT BELOW
if( $get_trigger )
{

	if( !defined('ROSTER_SIGCONFIGTABLE') )
	{
		define('ROSTER_SIGCONFIGTABLE',$db_prefix.'addon_siggen');
	}
	
	
	$sql_str = "SHOW TABLES LIKE '".ROSTER_SIGCONFIGTABLE."';";
	$result = $wowdb->query($sql_str);
	$r = mysql_fetch_assoc($result);
	
	if( !empty($r) )
	{
		// Read SigGen Config data from Database
		$config_str = "SELECT `config_id`,`trigger`,`guild_trigger` FROM `".ROSTER_SIGCONFIGTABLE."`;";
		$config_sql = $wowdb->query($config_str);
		if( $config_sql && mysql_num_rows($config_sql) != 0 )
		{
			$configAva = mysql_fetch_assoc($config_sql);
			$configSig = mysql_fetch_assoc($config_sql);
	
			$sc_trigger_sig = $configSig['trigger'];
			$sc_trigger_ava = $configAva['trigger'];
			$sc_guild_trigger_sig = $configSig['guild_trigger'];
			$sc_guild_trigger_ava = $configAva['guild_trigger'];
		}
	}

}


?>