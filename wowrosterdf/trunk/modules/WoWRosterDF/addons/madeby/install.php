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
******************************/

if ( !defined('ROSTER_INSTALLED') )
{
	exit('Detected invalid access to this file!');
}

require($addonDir.'/sql.php');

// $dbversion contains the version in the altmonitor config database, or 0.0.0
// if the DB doesn't exist yet $fileversion contains the file version as specified
// in conf.php

// This loop iterates through the SQL blocks defined in sql.php. If the SQL block
// is newer than the current version the SQL is run on the database, then the
// version is set to the number of the SQL just executed.

foreach ($install_sql as $version => $sql)
{
	if (version_compare($dbversion,$version,"<"))
	{
		$install_queries = explode(';',$sql);

		foreach ($install_queries as $query)
		{
			if ( !empty($query))
			{
				if ( $roster_conf['sqldebug'] ) echo "<!--$query-->\n";

				$result = $wowdb->query( $query ) or die_quietly('Failed to install MadeBy. MySQL said: <br />'.$wowdb->error(),'MadeBy Installer',__FILE__,__LINE__,$query );

				$wowdb->free_result($result);
			}
		}

		$wowdb->reset_values();

		$wowdb->add_value( 'config_value', $version );

		$query = "UPDATE `".MADEBY_CONFIG_TABLE."` SET ".$wowdb->assignstr." WHERE `config_name` = 'version'";

		if ( $roster_conf['sqldebug'] ) echo "<!--$query-->\n";

		$result = $wowdb->query( $query ) or die_quietly('Failed to install MadeBy. MySQL said: <br />'.$wowdb->error(),'MadeBy Installer',__FILE__,__LINE__,$query );

		$wowdb->free_result($result);

	}
}

// Write the file version to the database, just in case the most recent update/fix didn't include a DB update.

$wowdb->reset_values();

$wowdb->add_value( 'config_value', $fileversion );

$query = "UPDATE `".MADEBY_CONFIG_TABLE."` SET ".$wowdb->assignstr." WHERE `config_name` = 'version'";

if ( $roster_conf['sqldebug'] ) echo "<!--$query-->\n";

$result = $wowdb->query( $query ) or die_quietly('Failed to install MadeBy. MySQL said: <br />'.$wowdb->error(),'MadeBy Installer',__FILE__,__LINE__,$query );

$wowdb->free_result($result);

echo border('sgreen','start',$wordings[$roster_conf['roster_lang']]['MadeBy_install_header']).
$wordings[$roster_conf['roster_lang']]['MadeBy_installed_msg'].'<br />'.
"<a href='".getlink($module_name."&amp;file=addon&amp;roster_addon_name=madeby&action=config")."'>".$wordings[$roster_conf['roster_lang']]['MadeBy_Configure_txt']."</a>".
border('sgreen','end');


?>
