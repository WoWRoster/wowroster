<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
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

// Set the relative URL here. Use slashes, not backslashes. Use slash in front
// and omit the one at the end. If your windows server does not accept slashes
// in pathnames set the include statement seperately.
$roster_rel = '';

// Environment
include('.'.$roster_rel.'/settings.php');

// Get the char from the query string. To keep the link as short as
// possible, we don't use member= or anything like that
$char = addslashes(urldecode($_SERVER['QUERY_STRING']));

// Check if there's a character with this name
$query = "SELECT `member_id` FROM `".ROSTER_MEMBERSTABLE."` WHERE `name` = '$char'";

$result = $wowdb->query($query);

if( !$result )
{
	die_quietly($wowdb->error(),'Roster Autopointer',basename(__FILE__),__LINE__,$query);
}

if( $row = $wowdb->fetch_assoc($result) )
{
	header("Location: ".ROSTER_URL.$roster_rel."/".sprintf(ROSTER_LINK,'char&member='.$row['member_id']));
	exit();
}

// There's no char with that name? Redirect to guild page.

header("Location: ".ROSTER_URL.$roster_rel);

?>