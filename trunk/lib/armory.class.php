<?php
/**
 * WoWRoster.net WoWRoster
 *
 * cache class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.9.9
 * @package    WoWRoster
 * @subpackage RosterClass
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

require_once(ROSTER_LIB . 'xmlparse.class.php');  // move to xmlInit method?

/**
 * Armory Query Class
 *
 * This will connect to the correct WoW Roster Site depending on locale information
 * and return requested info in XML or Array depending on the request
 * WIP
 *
 */
class RosterArmory
{
	var $character;
	var $guild;
	var $realm;
	var $locale;
	var $xml;
	var $user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1';
	var $xml_timeout = 5;  // seconds to pass for timeout

	/**
	 * xmlParsing object
	 *
	 * @var XmlParser
	 */
	var $xmlParser;

	/**
	 * Constructor
	 *
	 * @return lib_armory
	 */
	function RosterArmory( $character=false, $guild=false, $realm=false, $locale=false )
	{
		$this->_initXmlParser();  // remove from here and call when _getXml() gets called

		$this->character 	= ( isset($character) ? $character : '' );
		$this->guild 		= ( isset($guild) ? $guild : '' );
		$this->server		= ( isset($realm) ? $realm : '' );
		$this->locale		= ( isset($locale) ? $locale : '' );
	}

	/**
	 * requests item-tooltip.xml from armory for $item_id
	 *
	 * @param string $item_id
	 */
	function fetchItemTooltip( $item_id, $locale )
	{
		global $roster;
		$id = $item_id;
		$locale = substr($locale, 0, 2);
		$u_agent = '';

		if( $roster->cache->check($id.$locale) )
		{
			return $roster->cache->get($id.$locale);
		}
		else
		{
			$this->xml = urlgrabber('http://www.wowarmory.com/item-tooltip.xml?i=' . $id . '&locale=' . $locale, 5, $this->user_agent );
			$this->xmlParser->Parse($this->xml);
			$item = $this->xmlParser->getParsedData();
			$roster->cache->put($item, $id.$locale);
			return $item;
		}
	}

	function fetchItemInfo( $item_id, $locale )
	{
		global $roster;
		$id = $item_id;
		$locale = substr($locale, 0, 2);
		$u_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1';

		if( $roster->cache->check($id.$locale) )
		{
			return $roster->cache->get($id.$locale);
		}
		else
		{
			$this->xml = urlgrabber('http://www.wowarmory.com/item-info.xml?i=' . $id . '&locale=' . $locale, 5, $u_agent );
			$this->xmlParser->Parse($this->xml);
			$item = $this->xmlParser->getParsedData();
			$roster->cache->put($item, $id.$locale);
			return $item;
		}
	}

	/**
	 * Private funtion to generate the URL urlgrabber tries to retrive
	 * Based on locale uses eu or us wow armory
	 * Based on mode requests proper xml
	 * if passed will append user, server, guild information to the mode request
	 * locale will always be used, enUS if non passed
	 *
	 * @param mixed $mode	| string or int of data to request
	 * @param unknown_type $id
	 * @param unknown_type $char
	 * @param unknown_type $server
	 * @param unknown_type $locale
	 */
	function _generateUrl($mode, $id=false, $char=false, $guild=false, $realm=false, $locale=false)
	{
		//build base url
		if( $locale )
		{
			$locale = substr($locale, 0, 2);
		}
		else
		{
			$locale = substr($this->locale, 0, 2);
		}

		if( $locale == 'en' )
		{
			$base_url = 'http://www.wowarmory.com/';
		}
		else
		{
			$base_url = 'http://eu.wowarmory.com/';
		}

		// get request mode
		switch( $mode )
		{
			case 0:
			case 'item-tooltip':
				$mode = '?item-tooltip.xml';
			case 1:
			case 'item-info':
				$mode = '?item-info.xml';
		}

		if( !$char )
		{
			$char = $this->character;
		}

		if( $realm )
		{
			$realm = str_replace(' ', '+', $realm);
		}
		elseif( $this->realm )
		{
			$realm = str_replace(' ', '+', $this->realm);
		}

		if( $guild )
		{
			$guild = str_replace(' ', '+', $guild);
		}
		elseif( $this->guild )
		{
			$guild = str_replace(' ', '+', $this->guild);
		}

		$url = $base_url . $mode . '&locale=' . $locale;
		if( $char )
		{
			$url .= '&n=' . $char . '&r=' . $realm . '&g=' . $guild;
		}
		return $url;
	}

	/**
	 * Private call to populate the xml property.
	 * returns true on successful request
	 * false otherwise
	 *
	 * @param string $url
	 * @param int $timeout
	 * @param string $user_agent
	 * @return bool
	 */
	function _requestXml( $url, $timeout=false, $user_agent=false )
	{
		$this->xml = ''; // clear xml if any

		if( $timeout === false )
		{
			$timeout = $this->xml_timeout;
		}
		if( $user_agent === false )
		{
			$user_agent = $this->user_agent;
		}

		$this->xml = urlgrabber($url, $timeout, $user_agent);

		if( !empty($this->xml) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function _initXmlParser()
	{
		if( !is_object($this->xmlParser) )
		{
			$this->xmlParser = new XmlParser();
		}
	}
}