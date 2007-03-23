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

if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}

$header_title = $wordings[$roster_conf['roster_lang']]['MadeBy'];

require_once ($addonDir.'RecipeList.php');

echo $content;
?>