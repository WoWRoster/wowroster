<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster URL and form linking functions and defines
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage CMSLink
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
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
	define('ROSTER_LINK', 'index.php?' . ROSTER_PAGE . '=%1$s');
}

/**
 * Get the full URL to roster's root directory
 * You can modify the defines 'ROSTER_URL' and 'ROSTER_PATH' to suit your needs
 * and bypass the url checks if needed
 */
$url = explode('/','http://' . $_SERVER['SERVER_NAME']  . (( $_SERVER['SERVER_PORT'] != 80 ) ? ':' . $_SERVER['SERVER_PORT'] : '' ) . $_SERVER['PHP_SELF']);
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
				parse_str($get, $get);
				if( !get_magic_quotes_gpc() )
				{
					$get = escape_array($get);
				}
				$_GET = array_overlay($get, $_GET);
			}
		}
		// Needed in case someone specified www.example.com/roster/index.php.
		// That format is the only one that works in IIS
		if( $pages == array('index') )
		{
			$pages = array();
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

	// Filter out html anchor
	if( ($pos = strpos($url,'#')) !== false )
	{
		$html_anchor = substr($url,$pos);
		$url = substr($url,0,$pos);
	}
	else
	{
		$html_anchor = '';
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

	// Add the anchor param if it isn't in yet
	switch($roster->atype)
	{
		case 'char':
			$anchor = ( isset($roster->data['member_id'])?'a=c:' . $roster->data['member_id']:'' );
			break;
		case 'guild': case 'default':
			$anchor = ( isset($roster->data['guild_id'])?'a=g:' . $roster->data['guild_id']:'' );
			break;
		case 'realm':
			$anchor = 'a=r:' . $roster->anchor;
		default:
			$anchor = '';
	}

	if( empty($url) || empty($anchor) )
	{
		$url = $anchor . $url;
	}
	elseif( substr($url,0,2) != 'a=' && FALSE == strpos( $url, '&amp;a=' ) )
	{
		$url = $anchor . '&amp;' . $url;
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

	return $url . $html_anchor;
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
	if( strpos($url,'?') !==false )
	{
		$url = substr($url,strpos($url,'?')+1);
	}

	$return = '';

	foreach( explode('&amp;',$url) as $param )
	{
		list($name, $value) = explode('=',$param,2);
		$return .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />' . "\n";
	}

	return $return;
}
