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

error_reporting(E_ALL);

$header_title = $wordings[$roster_conf['roster_lang']]['guildbank'];

require ($addon['dir'].'conf.php');
include($addon['dir'].'searcharrays.php');
require ($addon['dir'].'gbank.php');

echo $content;
?>