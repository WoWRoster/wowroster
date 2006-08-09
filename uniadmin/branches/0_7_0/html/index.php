<?php

include('set_env.php');


// This is a list of allowed actions that a person can make.
// For each action, there is a corresponding php file, i.e.
// members == members.php
// Any action that is attempted that isn't listed in this array will
// refer them to the main page as defined by first_page in the config.
$allowed_pages = array(
	'addons',
	'settings',
	'logo',
	'help',
	'stats',
	'users',
	);



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
	include_once('help.php');
}

?>