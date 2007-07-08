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
 * @package    WoWRoster
 * @subpackage CMSLink
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
	define('ROSTER_LINK', '%1$s');
}
else
{
	define('ROSTER_LINK', '?' . ROSTER_PAGE . '=%1$s');
}

/**
 * Get the full URL to roster's root directory
 * You can modify the defines 'ROSTER_URL' and 'ROSTER_PATH' to suit your needs
 * and bypass the url checks if needed
 */
$url = explode('/','http://'.$_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']);
array_pop($url);
$url = implode('/',$url) . '/';

define('ROSTER_URL',$url);
unset($url);


/**
 * Get the url path to roster's directory
 */
$urlpath = explode('/',$_SERVER['PHP_SELF']);
array_pop($urlpath);
$urlpath = implode('/',$urlpath) . '/';

define('ROSTER_PATH',$urlpath);
unset($urlpath);

/**
 * Parse any get params that might be hidden in the URL
 */
function parse_params()
{
	// --[ mod_rewrite code ]--
	if( !isset($_GET[ROSTER_PAGE]) )
	{
		$uri = request_uri();
		$page = substr($uri,strlen(ROSTER_PATH));
		list($page) = explode('.',$page);

		// Build the Roster page var
		$pages = array();
		foreach( explode('/',$page) as $get )
		{
			if( strpos($get,'=') === false )
			{
				$pages[] = $get;
			}
			else
			{
				list($var,$val) = explode('=',$get);
				$_GET[$var] = $val;
			}
		}
		$_GET[ROSTER_PAGE] = implode('-',$pages);
	}
}

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

	// Split the page from the rest
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

	// Add the scope param if it isn't in yet
	$addget = '';
	switch( $scope )
	{
		case 'char':
			if( !isset($get['member']) && isset($roster->data['member_id']) )
			{
				$addget = 'member=' . $roster->data['member_id'];
			}
			break;

		case 'guild':
			if( !isset($get['guild']) && isset($roster->data['guild_id']) )
			{
				$addget = 'guild=' . $roster->data['guild_id'];
			}
			break;

		case 'guildless':
		case 'realm':
			if( !isset($get['realm']) && isset($roster->data['server']) )
			{
				$addget = 'realm=' . $roster->data['server'];
			}
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

	// SEO magic
	if( $roster->config['seo_url'] )
	{
		$url = str_replace('&amp;', '/', $url);
		$page = str_replace('-', '/', $page);

		if( empty($url) )
		{
			$url = $page . '.html';
		}
		else
		{
			$url = $page . '/' . $url . '.html';
		}
	}
	else
	{
		if( empty($url) )
		{
			$url = sprintf(ROSTER_LINK, $page);
		}
		else
		{
			$url = sprintf(ROSTER_LINK, $page . '&amp;' . $url);
		}
	}

	// Return full url if requested
	if( $full )
	{
		$url = ROSTER_URL . "$url";
	}

	return $url . $anchor;
}

/**
 * Wrapper function for GET form actions. Params like makelink.
 *
 * @param string $url
 * @param bool $full
 */

function getFormAction( $url='', $full=false )
{
	global $roster;

	if( $roster->config['seo_url'] )
	{
		return makelink($url, $full);
	}
	elseif( $full )
	{
		return ROSTER_URL;
	}
	else
	{
		return '';
	}
}

/**
 * Function to insert get variables in a <form> that uses GET to post.
 * Params like makelink.
 *
 * @param string $url
 */
function linkform( $url='' )
{
	global $roster;

	// If SEO mode is on, we don't need to pass anything here.
	if( $roster->config['seo_url'] )
	{
		return '';
	}

	// Run makelink for the extra params
	$url = makelink($url,false);

	// Cut off the ? at the start
	$url = substr($url,1);

	$return = '';

	foreach( explode('&amp;',$url) as $param )
	{
		list($name, $value) = explode('=',$param,2);
		$return .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />' . "\n";
	}

	return $return;
}
