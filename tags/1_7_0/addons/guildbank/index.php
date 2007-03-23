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
error_reporting(E_ALL);

if (eregi("index.php",$_SERVER['PHP_SELF'])) {
    die("You can't access this file directly!");
}

$header_title = $wordings[$roster_conf['roster_lang']]['guildbank'];

require ($addonDir.'gbank.php');

echo $content;
?>