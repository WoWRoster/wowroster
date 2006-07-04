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

require_once( 'settings.php' );

define('HEADER_INC',true);

$name = (isset($_GET['name']) ? $_GET['name'] : '');

$header_title = 'Character Stats for: '.$name;

include_once (ROSTER_BASE.'roster_header.tpl');

include_once (ROSTER_BASE.'memberdetails.php');

include_once (ROSTER_BASE.'roster_footer.tpl');

?>