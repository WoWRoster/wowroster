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

// ----[ Include roster conf file ]-----------------------------
require_once( 'conf.php' );


// ----[ Include the Initialization file ]----------------------
require_once('set_env.php');


// This is a list of allowed actions that a person can make.
// For each action, there is a corresponding php file, i.e.
// members == members.php
// Any action that is attempted that isn't listed in this array will
// refer them to the main page as defined by first_page in the config.
$allowed_pages = array('members', 'addon', 'char', 'credits','guilds','update');



// ----[ Decide what to do next ]-------------------------------
if( isset($_GET['p']) )
{
	$page = $_GET['p'];
}
else
{
	$page = '';
}

if( in_array($page, $allowed_pages) )
{
	include_once($page.'.php');
}
else
{
	include_once($roster_conf['first_page'].'.php');
}



// ----[ Assign tooltip and sql strings ]-----------------------
$tpl->assign( 'tooltip_strings', getAllTooltips() );



// Assign the sql strings
$tpl->assign( 'sql_strings', getSqlQueries());



// ----[ Print screen elements ]--------------------------------
print $tpl->fetch( 'roster_header.tpl' );
print $display;
print $tpl->fetch( 'roster_footer.tpl' );



// ----[ Disconnect from the database ]-------------------------
db_close();

?>