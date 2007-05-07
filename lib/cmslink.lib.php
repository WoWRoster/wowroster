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

// Loaded from the $roster constructor, so reference it as $this
if( $roster->config['seo_url'] )
{
	// This is the url to access a page in Roster
	define('ROSTER_LINK', '%1$s.html?%2$s');

	define('ROSTER_LINK_NOARGS', '%1$s.html');
}
else
{
	define('ROSTER_LINK', '?'.ROSTER_PAGE.'=%1$s&amp;%2$s');

	define('ROSTER_LINK_NOARGS', '?'.ROSTER_PAGE.'=%1$s');
}

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
	global $roster;
	
	// Filter out anchor
	if( ($pos = strpos($url,'#')) !== false )
	{
		$anchor = substr($url,$pos);
		$url = substr($url,0,$pos);
	}
	else
	{
		$anchor = '';
	}

	// Prepend current page if needed
	if( empty($url) || $url[0] == '&' )
	{
		$page = ROSTER_PAGE_NAME;
		$url = substr($url, 5);
	}
	elseif( strpos($url, '&amp;') )
	{
		list($page, $url) = explode('&amp;',$url,2);
	}
	else
	{
		$page = $url;
		$url = '';
	}
	
	// Get target scope
	list($scope) = explode('-',$page);
	
	// Get the target GET vars
	parse_str(html_entity_decode($url), $get);
	
	// Add the member=/guild= param
	switch($scope)
	{
		case 'char':
			if( !isset($get['member']) && isset($roster->data['member_id']) )
			{
				$addget = 'member='.$roster->data['member_id'];
			}
			break;
		case 'guild':
			if( !isset($get['guild']) && isset($roster->data['guild_id']) )
			{
				$addget = 'guild='.$roster->data['guild_id'];
			}
			break;
		default:
			$addget = '';
			break;
	}
	
	// Put the url back together again
	if( empty($addget) || empty($url) )
	{
		$url = $addget . $url;
	}
	else
	{
		$url = $addget . '&amp;' . $url;
	}

	// Pass through the SEO encoder
	if( empty($url) )
	{
		if( $roster->config['seo_url'] )
		{
			$page = str_replace('-','/',$page);
		}

		$url = sprintf(ROSTER_LINK_NOARGS,$page);
	}
	else
	{
		if( $roster->config['seo_url'] )
		{
			$page = str_replace('-','/',$page);
		}

		$url = sprintf(ROSTER_LINK,$page,$url);
	}

	// Prepend the front end of the url if requested
	if( $full )
	{
		$url = ROSTER_URL."$url";
	}

	// Re-add the anchor and return
	return $url.$anchor;
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
	$return = '<input type="hidden" name="'.ROSTER_PAGE.'" value="'.ROSTER_PAGE_NAME.'" />'."\n";

	if( $get_links !== false )
	{
		foreach( $get_links as $name => $value )
		{
			$return = '<input type="hidden" name="'.$name.'" value="'.$value.'" />'."\n";
		}
	}
	return $return;
}

