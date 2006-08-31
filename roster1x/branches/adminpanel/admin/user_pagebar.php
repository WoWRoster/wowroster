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
$pagebar .= '<li><a href="?page=password">'.$act_words['pagebar_userpass'].'</a></li>'."\n";
$pagebar .= '<li><a href="?page=update">'.$act_words['pagebar_update'].'</a></li>'."\n";
$pagebar .= '</ul>'."\n";
$pagebar .= border('sgray','end')."\n";

?>