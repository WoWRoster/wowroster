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

// -[ This file is nearly dummy now since the addon.php is switching. ]-
if( !isset($roster_pages[2]) )
{
	include($addon['dir'].'memberslist.php' );
}
else
{
	die_quietly($act_words['SortMember_NoAction'],'SortMember');
}
