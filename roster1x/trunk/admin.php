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


require_once('settings.php');

$file = isset($_POST['mode']) ? $_POST['mode'] : (isset($_GET['mode']) ? $_GET['mode'] : 'config');
if (!ereg('^([a-zA-Z0-9_\-]+)$', $file))
{
	die_quietly('This is not a valid entry point','Bad Entry',basename(__FILE__));
}
elseif( file_exists(ROSTER_BASE.'admin'.DIR_SEP.$file.'.php') )
{
	$script_filename = 'admin.php?mode='.$file;
	require(ROSTER_BASE.'admin'.DIR_SEP.$file.'.php');
}
else
{
	die_quietly('This is not a valid entry point','Bad Entry',basename(__FILE__));
}

?>