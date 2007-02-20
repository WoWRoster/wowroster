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

#--[ CONFIG ]--------------------------------------------------------

define('MADEBY_CONFIG_TABLE', $db_prefix.'addon_madeby_config');

$fileversion='1.1.0';

$query = "SHOW TABLES LIKE '".MADEBY_CONFIG_TABLE."'";

$result = $wowdb->query( $query ) or die_quietly($wowdb->error(),'MadeBy',__FILE__,__LINE__, $query );

if ( $row = $wowdb->fetch_assoc($result) )
{
	$wowdb->free_result($result);

	// -[ Get config values and insert them into the array ]-
	$query = "SELECT `config_name`, `config_value` FROM `".MADEBY_CONFIG_TABLE."` ORDER BY `id` ASC;";

	$result = $wowdb->query( $query ) or die_quietly($wowdb->error(),'MadeBy',__FILE__,__LINE__, $query );

	while( $row = $wowdb->fetch_assoc($result) )
	{
		$addon_conf['MadeBy'][$row['config_name']] = stripslashes($row['config_value']);
	}

	$wowdb->free_result($result);

	$dbversion = $addon_conf['MadeBy']['version'];
}
else
{
	$dbversion = '0.0.0'; // we need to install
}


function print_r_string($arr,$first=true,$tab=0)
{
	$output = "";
	$tabsign = ($tab) ? str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$tab) : '';
	if ($first) $output .= "<pre><br>\n";
	foreach($arr as $key => $val)
	{
		switch (gettype($val))
		{
			case "array":
				$output .= $tabsign."[".htmlspecialchars($key)."] = array(".count($val).")<br>\n".$tabsign."(<br>\n";
				$tab++;
				$output .= print_r_string($val,false,$tab);
				$tab--;
				$output .= $tabsign.")<br>\n";
				break;
			case "boolean":
				$output .= $tabsign."[".htmlspecialchars($key)."] bool = '".($val?"true":"false")."'<br>\n";
				break;
			case "integer":
				$output .= $tabsign."[".htmlspecialchars($key)."] int = '".htmlspecialchars($val)."'<br>\n";
				break;
			case "double":
				$output .= $tabsign."[".htmlspecialchars($key)."] double = '".htmlspecialchars($val)."'<br>\n";
				break;
			case "string":
				$output .= $tabsign."[".htmlspecialchars($key)."] string = '".((stristr($key,'passw')) ? str_repeat('*', strlen($val)) : htmlspecialchars($val))."'<br>\n";
				break;
			case "object":
				$output .= $tabsign."[".htmlspecialchars($key)."] = objectof(".count($val).")<br>\n".$tabsign."(<br>\n";
				$tab++;
				$output .= print_r_string($val,false,$tab);
				$tab--;
				$output .= $tabsign.")<br>\n";
				break;
			default:
				$output .= $tabsign."[".htmlspecialchars($key)."] unknown = '".htmlspecialchars(gettype($val))."'<br>\n";
				break;
		}
	}
	if ($first) $output .= "</pre><br>\n";
	return $output;
}


?>