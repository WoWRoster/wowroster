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

$pagebar  = border('sgray','start',$act_words['pagebar_function'])."\n";
$pagebar .= '<ul class="tab_menu">'."\n";
$pagebar .= '<li><a href="?page=roster">'.$act_words['pagebar_rosterconf'].'</a></li>'."\n";
$pagebar .= '<li><a href="?page=character">'.$act_words['pagebar_charpref'].'</a></li>'."\n";
$pagebar .= '<li><a href="?page=install">'.$act_words['pagebar_addoninst'].'</a></li>'."\n";
$pagebar .= '<li><a href="?page=password">'.$act_words['pagebar_adminpass'].'</a></li>'."\n";
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
	$pagebar .= border('sgray','start',$act_words['pagebar_addonconf'])."\n";
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