<?php 
/** 
 *
 */ 

if ( !defined('ROSTER_INSTALLED') ) 
{ 
    exit('Detected invalid access to this file!'); 
}

if( !isset($user) )
{
	include_once( $addon['inc_dir'] . 'users.lib.php' );
	$user = new user();
}


//global $roster, $addon, $user;

// --[ Get path info based on scope ]--
if( !isset($roster->pages[2]) )
{
	$roster->pages[2] = 'main';
}

if($roster->pages[2] == '')
{
	// Send a 404. Then the browser knows what's going on as well.
	header('HTTP/1.0 404 Not Found');
	roster_die(sprintf($roster->locale->act['page_not_exist'],ROSTER_PAGE_NAME),$roster->locale->act['roster_error']);
}

$page = $roster->pages[2];

$user->page->getPage($page);
