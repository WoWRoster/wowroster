<?php
/**
 * WoWRoster.net WoWRoster
 *
 * WoWRoster Armory Class
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
 * WoWRoster Armory Class
 *
 * Allows easy access to the WoW Armory database
 * Returns pre-parsed XML as an Array or
 * returns unparsed XML page as a string
 *
 */
class RosterArmory
{
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
	function RosterArmory( )
	{
		// notta
	}

	/**
	 * Fetches $item_id Tooltip from the Armory
	 * Accepts optional $character if used $realm is also required
	 * Returns Array of the parsed XML page
	 * 
	 * @param string $item_id
	 * @return array 
	 */
	function fetchItemTooltip( $item_id, $locale, $character=false, $realm=false, $is_XML=false )
	{
		global $roster;
		$locale = substr($locale, 0, 2);
		$cache_tag = $item_id.$locale.$character.$is_XML;
		
		if( $roster->cache->check($cache_tag) )
		{
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 0, $locale, $item_id, $character, $realm );
			if( $this->_requestXml($url) )
			{
				// if true return unparsed xml page
				if( $is_XML )
				{
					$roster->cache->put($this->xml, $cache_tag);
					return $this->xml;
				}
				// otherwise parse and return array
				$this->xmlParser->Parse($this->xml);
				$item = $this->xmlParser->getParsedData();
				$roster->cache->put($item, $cache_tag);
				return $item;
			}
			else 
			{
				trigger_error('RosterArmory Class: Failed to fetch ' . $url);
				return false;
			}
		}
	}
	
	/**
	 * Fetches $item_id Tooltip from the Armory
	 * Accepts optional $character if used $realm is also required
	 * Returns XML string
	 * 
	 * @param string $item_id
	 * @return string 
	 */
	function fetchItemTooltipXML( $item_id, $locale, $character=false, $realm=false )
	{
		return $this->fetchItemTooltip( $item_id, $locale, $character, $realm, true);
	}
	
	/**
	 * Fetches $item_id General Information from the Armory
	 * Accepts optional $character if used $realm is also required
	 * Returns Array of the parsed XML page
	 * 
	 * @param string $item_id
	 * @return string 
	 */
	function fetchItemInfo( $item_id, $locale, $is_XML=false )
	{
		global $roster;
		$locale = substr($locale, 0, 2);
		$cache_tag = $item_id.$locale.$is_XML;
		
		if( $roster->cache->check($cache_tag) )
		{
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 1, $locale, $item_id );
			echo $url;
			if( $this->_requestXml($url) )
			{
				if( $is_XML )
				{
					$roster->cache->put($this->xml, $cache_tag);
					return $this->xml;
				}
				$this->xmlParser->Parse($this->xml);
				$item = $this->xmlParser->getParsedData();
				$roster->cache->put($item, $cache_tag);
				return $item;
			}
		}
	}

	/**
	 * Fetches $item_id General Information from the Armory
	 * Accepts optional $character if used $realm is also required
	 * Returns XML string
	 * 
	 * @param string $item_id
	 * @return string 
	 */
	function fetchItemInfoXML( $item_id, $locale )
	{
		return $this->fetchItemInfo($item_id, $locale, true);
	}

	/**
	 * Fetch $character from the Armory
	 * $realm is required
	 * $locale is reqired
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @param bool $is_XML
	 * @return array
	 */
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
	
	/**
	 * Fetch $character from the Armory
	 * $realm is required
	 * $locale is reqired
	 * Returns XML string
	 * 
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @return string | xml string
	 */	
	function fetchCharacterXML( $character, $locale, $realm )
	{
		return $this->fetchCharacter($character, $locale, $realm, true);
	}

	/**
	 * Fetch $guild from the Armory
	 * $guild should be passed as-is
	 * $realm is required
	 *
	 * @param string $guild
	 * @param string $locale
	 * @param string $realm
	 * @param bool $is_XML
	 * @return array
	 */
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
	
	/**
	 * Fetch $guild from the Armory
	 * $guild should be passed as-is
	 * $realm is required
	 * Returns XML string
	 * 
	 * @param string $guild
	 * @param string $locale
	 * @param string $realm
	 * @return string | xml string
	 */	
	function fetchGuildXML( $guild, $locale, $realm )
	{
		return $this->fetchGuild($guild, $locale, $realm, true);
	}

	/**
	 * Fetch Character Talents for $character from the Armory
	 * $character, $locale, $realm are required to be passed
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @param bool $is_XML
	 * @return array
	 */
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

	/**
	 * Fetch Character Talents for $character from the Armory
	 * $character, $locale, $realm are required to be passed
	 * Returns XML string
	 * 
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @return string | xml string
	 */
	function fetchCharacterTalentsXML( $character, $locale, $realm )
	{
		return $this->fetchCharacterTalents($character, $locale, $realm, true);
	}
	
	/**
	 * Sets $user_agent 
	 * Use to override default useragent
	 * 
	 * @param string $user_agent
	 */
	function setUserAgent( $user_agent )
	{
		$this->user_agent = $user_agent;
	}
	
	/**
	 * Sets socket connection time out
	 * Use to override default of 8 seconds
	 *
	 * @param int $time_out | timeout in seconds
	 * @return void
	 */
	function setTimeOut( $time_out )
	{
		$this->xml_timeout = $time_out;
	}
	/**
	 * Private function to build the armory URL
	  *
	 * @param string $mode	| string or int of data to request
	 * @param string $locale | required
	 * @param string $id | 
	 * @param string $char | 
	 * @param string $realm |
	 * @param string $guild |
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
				$mode = 'item-tooltip.xml?i=' . $id;
				if( $char )
				{
					$mode .= '&n=' . $char . '&r=' . urlencode($realm);
				}
				break;
			case 1:
			case 'item-info':
				$mode = 'item-info.xml?i=' . $id;
				if( $char )
				{
					$mode .= '&n=' . $char . '&r=' . urlencode($realm);
				}
				break;
			case 2:
			case 'character-sheet':
				$mode = 'character-sheet.xml?n=' . $char . '&r=' . urlencode($realm);
				break;
			case 3:
			case 'guild-info':
				$mode = 'guild-info.xml?r=' . urlencode($realm) . '&n=' . urlencode($guild);
				break;
			case 4:
			case 'character-talents':
				$mode = 'character-talents.xml?n=' . $char . '&r=' . urlencode($realm);
				break;
		}

		$url = $base_url . $mode . '&locale=' . $locale;
//		echo $url;
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

	/**
	 * Private function that includes xmlparser class if needed and then creates
	 * a new XmlParser() object if needed
	 * 
	 * @return void
	 */
	function _initXmlParser()
	{
		if( !is_object($this->xmlParser) )
		{
			require_once(ROSTER_LIB . 'xmlparse.class.php');
			$this->xmlParser = new XmlParser();
		}
	}

}
