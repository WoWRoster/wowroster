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

define('HEADER_INC',true);

$pagetitle .= $module_title.' '._BC_DELIM.' '.$header_title;



$modheader = '<link rel="stylesheet" type="text/css" href="'.$roster_conf['roster_dir'].'/'.$roster_conf['stylesheet'].'" />
<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/'.$roster_conf['roster_js'].'"></script>'."\n";

if( !isset($roster_conf['item_stats']) || $roster_conf['item_stats'] )
{
	$modheader .= '<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/'.$roster_conf['overlib'].'"></script>
<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/'.$roster_conf['overlib_hide'].'"></script>'."\n";
}

$modheader .= (isset($more_css) ? $more_css : '');

include (BASEDIR.'header.php');
opentable();
?>
<div class="wowroster">

<div align="center" style="margin:10px;">
<!-- End Roster Header -->