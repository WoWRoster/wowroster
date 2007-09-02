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
	var $xml_timeout = 8;  // seconds to pass for timeout
	var $user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1';
	

	/**
	 * xmlParsing object
	 *
	 * @var XmlParser
	 */
	var $xmlParser;

	/**
	 * Constructor
	 *
	 * @return RosterArmory
	 */
	function RosterArmory( $locale=false, $realm=false, $guild=false )
	{
		$this->locale = ( isset($locale) ? $locale : '' );		
		$this->guild = ( isset($guild) ? $guild : '' );
		$this->realm = ( isset($realm) ? $realm : '' );
		
	}

	/**
	 * requests item-tooltip.xml from armory for $item_id
	 *
	 * @param string $item_id
	 */
	function fetchItemTooltip( $item_id, $locale, $character=false, $realm=false, $guild=false, $is_XML=false )
	{
		global $roster;
		$locale = substr($locale, 0, 2);
		
		if( $roster->cache->check($item_id.$locale.$character.$is_XML) )
		{
			return $roster->cache->get($item_id.$locale.$character.$is_XML);
		}
		else
		{
			$url = $this->_makeUrl( 0, $locale, $item_id, $character, $realm, $guild );
			if( $this->_requestXml($url) )
			{
				// if true return unparsed xml page
				if( $is_XML )
				{
					$roster->cache->put($this->xml, $item_id.$locale.$character.$is_XML);
					return $this->xml;
				}
				// otherwise parse and return array
				$this->xmlParser->Parse($this->xml);
				$item = $this->xmlParser->getParsedData();
				$roster->cache->put($item, $item_id.$locale.$character.$is_XML);
				return $item;
			}
			else 
			{
				trigger_error('RosterArmory Class: Failed to fetch' . $url);
				return false;
			}
		}
	}
	
	function fetchItemTooltipXML( $item_id, $locale, $character=false, $realm=false, $guild=false )
	{
		return $this->fetchItemTooltip( $item_id, $locale, $character, $realm, $guild, true);
	}

	function fetchItemInfo( $item_id, $locale, $is_XML=false )
	{
		global $roster;
		
		$locale = substr($locale, 0, 2);
		
		if( $roster->cache->check($item_id.$locale.$is_XML) )
		{
			return $roster->cache->get($item_id.$locale.$is_XML);
		}
		else
		{
			$url = $this->_makeUrl( 1, $locale, $item_id );
			if( $this->_requestXml($url) )
			{
				if( $is_XML )
				{
					$roster->cache->put($this->xml, $item_id.$locale.$is_XML);
					return $this->xml;
				}
				$this->xmlParser->Parse($this->xml);
				$item = $this->xmlParser->getParsedData();
				$roster->cache->put($item, $id.$locale.$is_XML);
				return $item;
			}
		}
	}
	
	function fetchItemInfoXML( $item_id, $locale )
	{
		return $this->fetchItemInfo($item_id, $locale, true);
	}

	
	function fetchCharacter( $character, $locale, $realm, $is_XML=false )
	{
		global $roster;
		
		$locale = substr($locale, 0, 2);
		$cache_tag = $character.$locale.$realm.$is_XML;
		
		if( $roster->cache->check($cache_tag) )
		{
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 2, $locale, false, $character, $realm );
			if( $this->_requestXml($url) )
			{
				if( $is_XML )
				{
					$roster->cache->put($cache_tag);
					return $this->xml;
				}
				$this->xmlParser->Parse($this->xml);
				$char = $this->xmlParser->getParsedData();
				$roster->cache->put($char, $cache_tag);
				return $char;
			}
		}
	}
	
	function fetchCharacterXML( $character, $locale, $realm )
	{
		return $this->fetchCharacter($character, $locale, $realm, true);
	}


	function fetchGuild( $guild, $locale, $realm, $is_XML=false )
	{
		global $roster;
		
		$locale = substr($locale, 0, 2);
		$cache_tag = $guild.$locale.$realm.$is_XML;
		
		if( $roster->cache->check($cache_tag) )
		{
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 3, $locale, false, false, $realm, $guild );
			if( $this->_requestXml($url) )
			{
				if( $is_XML )
				{
					$roster->cache->put($cache_tag);
					return $this->xml;
				}
				$this->xmlParser->Parse($this->xml);
				$guild = $this->xmlParser->getParsedData();
				$roster->cache->put($guild, $cache_tag);
				return $guild;
			}
		}
	}
	
	function fetchCharacterXML( $character, $locale, $realm )
	{
		return $this->fetchCharacter($character, $locale, $realm, true);
	}

	function fetchCharacterTalents( $character, $locale, $realm, $is_XML=false )
	{
		global $roster;
		
		$locale = substr($locale, 0, 2);
		$cache_tag = $character.$locale.$realm.$is_XML;
		
		if( $roster->cache->check($cache_tag) )
		{
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 4, $locale, false, $character, $realm );
			if( $this->_requestXml($url) )
			{
				if( $is_XML )
				{
					$roster->cache->put($cache_tag);
					return $this->xml;
				}
				$this->xmlParser->Parse($this->xml);
				$talents = $this->xmlParser->getParsedData();
				$roster->cache->put($talents, $cache_tag);
				return $talents;
			}
		}
	}
	
	function fetchCharacterTalentsXML( $character, $locale, $realm )
	{
		return $this->fetchCharacterTalents($character, $locale, $realm, true);
	}
	
	/**
	 * Private funtion to make the URL urlgrabber tries to retrive
	 * Based on locale uses eu or us wow armory
	 * Based on mode requests proper xml
	 * if passed will append user, realm and guild information to the mode request
	 * locale will always be used, enUS if non passed
	 *
	 * @param string $mode	| string or int of data to request
	 * @param string $locale
	 * @param string $id
	 * @param string $char
	 * @param string $realm
	 * @param string $guild
	 */
	function _makeUrl($mode, $locale, $id=false, $char=false, $realm=false, $guild=false )
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
				break;
			case 1:
			case 'item-info':
				$mode = '?item-info.xml';
				break;
			case 2:
			case 'character-sheet':
				$mode = '?character-sheet.xml';
				break;
			case 3:
			case 'guild-info':
				$mode = '?guild-info.xml';
				break;
			case 4:
			case 'character-talents':
				$mode = '?character-talents.xml';
				break;
		}

		$url = $base_url . $mode . '&locale=' . $locale;

		// if char is passed always expect to pass realm name
		if( $char )
		{
			$url .= '&n=' . $char;
		}
		if( $realm )
		{
			$url .= '&r=' . str_replace(' ', '+', $realm);
		}
		if( $guild )
		{
			$url .= '&g=' . str_replace(' ', '+', $guild);
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
		$this->_initXmlParser();
		
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
			require_once(ROSTER_LIB . 'xmlparse.class.php');
			$this->xmlParser = new XmlParser();
		}
	}

}
