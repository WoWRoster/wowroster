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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$pagebar  = border('sgray','start','Function')."\n";
$pagebar .= '<ul class="tab_menu">'."\n";
$pagebar .= '<li><a href="?page=roster">Roster Configuration</a></li>'."\n";
$pagebar .= '<li><a href="?page=character">Per-Character Preferences</a></li>'."\n";
$pagebar .= '<li><a href="?page=install">Addon Installation Screen</a></li>'."\n";
$pagebar .= '</ul>'."\n";
$pagebar .= border('sgray','end')."\n";
$pagebar .= "<br />\n";

$query = 'SELECT `basename`,`dbname`,`hasconfig` FROM `'.$wowdb->table('addon').'` WHERE `hasconfig` != ""';
$result = $wowdb->query($query);
if( !$result )
{
	die_quietly('Could not fetch addon records for pagebar','Roster Admin Panel',__LINE__,basename(__FILE__),$query);
}

if ($wowdb->num_rows($result))
{
	$pagebar .= border('sgray','start','Per-Addon config')."\n";
	$pagebar .= '<ul class="tab_menu">'."\n";
	while($row = $wowdb->fetch_assoc($result))
	{
		include(ROSTER_ADDONS.$row['basename'].DIR_SEP.'localization.php');
		$pagebar .= '<li><a href="?page=addon&amp;addon='.$row['dbname'].'&amp;profile='.$row['hasconfig'].'">'.$row['basename'].' - '.$row['dbname'].'</a></li>'."\n";
	}
	$pagebar .= '</ul>'."\n";
	$pagebar .= border('sgray','end')."\n";
}

$wowdb->free_result($result);

?>