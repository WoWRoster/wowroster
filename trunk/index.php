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

require_once( dirname(__FILE__).DIRECTORY_SEPARATOR.'settings.php' );


//---[ Text File Downloader ]-----------------------------
if( isset($_POST['send_file']) && !empty($_POST['send_file']) && !empty($_POST['data']) )
{
	$file = $_POST['data'];

	header('Content-Type: text/x-delimtext; name="'.$_POST['send_file'].'.txt"');
	header('Content-disposition: attachment; filename="'.$_POST['send_file'].'.txt"');

	// We need to stripslashes no matter what the setting of magic_quotes_gpc is
	echo stripslashes($file);

	exit;
}


// Determine the module request
if( isset($_GET[ROSTER_PAGE]) && !empty($_GET[ROSTER_PAGE]) )
{
	$page = $_GET[ROSTER_PAGE];
}
elseif( !strpos($roster_conf['default_page'], '&amp;') )
{
	$page = $roster_conf['default_page'];
}
else
{
	list($page, $gets) = explode('&amp;',$roster_conf['default_page'],2);
	foreach( explode('&amp;',$gets) as $get )
	{
		list($key, $value) = explode('=',$get,2);
		$_GET[$key] = $value;
	}
}

define('ROSTER_PAGE_NAME', $page);

$roster_pages = explode('-', $page);
$page = $roster_pages[0];

if( preg_match('/[^a-zA-Z0-9_-]/', ROSTER_PAGE_NAME) )
{
	roster_die($act_words['invalid_char_module'],$act_words['roster_error']);
}

//---[ Check for Guild Info ]------------
if( empty($guild_info) && !in_array($page,array('rosterdiag','rostercp','update','credits','license')) )
{
	roster_die( sprintf($act_words['nodata'], $roster_conf['guild_name'], $roster_conf['server_name'], makelink('update'), makelink('rostercp') ), $act_words['nodata_title'] );
}

// Include the module
if( is_file( $var = ROSTER_PAGES . $page . '.php' ) )
{
	require($var);
}
else
{
	roster_die(sprintf($act_words['module_not_exist'],$page),$act_words['roster_error']);
}

unset($page,$var);

$wowdb->closeDb();
