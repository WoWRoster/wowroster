<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster URL and form linking functions and defines
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}

/**
 * Page linking constants
 */

// This is what GET var the page link should be
define('ROSTER_PAGE', 'p');

// This is the url to access a page in Roster
define('ROSTER_LINK', '?'.ROSTER_PAGE.'=%1$s');


/**
 * Get the full URL to roster's root directory
 * You can modify the defines 'ROSTER_URL' and 'ROSTER_PATH' to suit your needs
 * and bypass the url checks if needed
 */
$url = explode('/','http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']);
array_pop($url);
$url = implode('/',$url).'/';

define('ROSTER_URL',$url);
unset($url);


/**
 * Get the url path to roster's directory
 */
$urlpath = explode('/',$_SERVER['PHP_SELF']);
array_pop($urlpath);
$urlpath = implode('/',$urlpath).'/';

define('ROSTER_PATH',$urlpath);
unset($urlpath);


/**
 * Function to create links in Roster
 * ALL LINKS SHOULD PASS THROUGH THIS FUNCTION
 * Hopefully this function will be the magic that makes porting Roster easier
 *
 * (Ninja looted from DragonFly, thanks you guys!)
 *
 * @param string $url
 * @param bool $full
 * @return string
 */
function makelink( $url='' , $full=false )
{
	if( empty($url) || $url[0] == '&')
		$url = ROSTER_PAGE_NAME.$url;

	$url = sprintf(ROSTER_LINK,$url);

	if( $full )
		$url = ROSTER_URL."$url";

	return $url;
}

/**
 * Function to insert get variables in a <form> that uses GET to post
 * If this is a Roster port, insert any additional get vars needed to point to the right location
 *
 * @param array $get_links | Optional, Additional vars you need to pass
 *        array( 'key name of var' => 'value of var' )
 *        Makes <input type="hidden" name="{key name of var}" value="{value of var}" />
 */
function linkform( $get_links = false )
{
	$return = '<input type="hidden" name="'.ROSTER_PAGE.'" value="'.ROSTER_PAGE_NAME.'">'."\n";

	if( $get_links !== false )
	{
		foreach( $get_links as $name => $value )
		{
			$return = '<input type="hidden" name="'.$name.'" value="'.$value.'">'."\n";
		}
	}
	return $return;
}
