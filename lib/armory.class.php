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
	var $region;
	var $debug_url = false;
	var $debug_cachehits = false;

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
	function RosterArmory( $region=false )
	{
		$this->region = ( $region !== false ? strtoupper($region) : 'US' );
	}

	/**
 	 * Fetches $item_id Tooltip from the Armory
	 * Accepts optional $character if used $realm is also required
	 * Returns Array of the parsed XML page
	 *
	 * @param int $item_id
	 * @param string $locale
	 * @param string $character
	 * @param string $realm
	 * @param string $fetch_type
	 * @return array
	 */
	function fetchItemTooltip( $item_id, $locale, $character=false, $realm=false, $fetch_type='array' )
	{
		global $roster;
		$locale = substr($locale, 0, 2);
		$cache_tag = $item_id.$locale.$character.$realm.$fetch_type;

		if( $roster->cache->check($cache_tag) )
		{
			if( $this->debug_cachehits )
			{
				echo __FUNCTION__ . " ::: Cache Hit: [ $cache_tag ]";
				trigger_error(__FUNCTION__ . " ::: Cache Hit: [ $cache_tag ]", E_NOTICE);
			}
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 0, $locale, $item_id, $character, $realm );
			if( $this->_requestXml($url) )
			{
				// unparsed fetches
				if( $fetch_type != 'array' )
				{
					$roster->cache->put($this->xml, $cache_tag);
					return $this->xml;
				}
				// otherwise parse and return array
				$this->xmlParser->Parse($this->xml);
				$item = $this->xmlParser->getParsedData();
				if( !isset($item['page'][0]['child']['errorhtml']) )
				{
					$roster->cache->put($item, $cache_tag);
				}
				else
				{
					trigger_error('RosterArmory:: Failed to fetch ' . $url. '. Armory is in maintenance mode');
				}
				return $item;
			}
			else
			{
				trigger_error('RosterArmory:: Failed to fetch ' . $url);
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
		return $this->fetchItemTooltip( $item_id, $locale, $character, $realm, 'xml' );
	}

	function fetchItemTooltipHTML( $item_id, $locale, $character=false, $realm=false )
	{
		$this->setUserAgent('Opera/9.22 (X11; Linux i686; U; en)');
		return $this->fetchItemTooltip( $item_id, $locale, $character, $realm, 'html' );
	}
	/**
	 * Fetches $item_id General Information from the Armory
	 * Returns Array of the parsed XML page
	 *
	 * @param string $item_id
	 * @param string $locale
	 * @param string $fetch_type
	 * @return string
	 */
	function fetchItemInfo( $item_id, $locale, $fetch_type='array' )
	{
		global $roster;
		$locale = substr($locale, 0, 2);
		$cache_tag = $item_id.$locale.$fetch_type;

		if( $roster->cache->check($cache_tag) )
		{
			if( $this->debug_cachehits )
			{
				trigger_error(__FUNCTION__ . " ::: Cache Hit: [ $cache_tag ]", E_NOTICE);
			}
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 1, $locale, $item_id );
			if( $this->_requestXml($url) )
			{
				//unparsed fetches
				if( $fetch_type != 'array' )
				{
					$roster->cache->put($this->xml, $cache_tag);
					return $this->xml;
				}
				// otherwise parse and return array
				$this->xmlParser->Parse($this->xml);
				$item = $this->xmlParser->getParsedData();
				if( !isset($item['page'][0]['child']['errorhtml']) )
				{
					$roster->cache->put($item, $cache_tag);
				}
				else
				{
					trigger_error('RosterArmory:: Failed to fetch ' . $url. '. Armory is in maintenance mode');
				}
				return $item;
			}
			else
			{
				trigger_error('RosterArmory:: Failed to fetch ' . $url);
				return false;
			}
		}
	}

	/**
	 * Fetches $item_id General Information from the Armory
	 * Returns XML string
	 *
	 * @param string $item_id
	 * @param string $locale
	 * @return string
	 */
	function fetchItemInfoXML( $item_id, $locale )
	{
		return $this->fetchItemInfo($item_id, $locale, 'xml');
	}

	/**
	 * Fetches $item_id General Information from the Armory
	 * Returns HTML string
	 *
	 * @param string $item_id
	 * @param string $locale
	 * @return string
	 */
	function fetchItemInfoHTML( $item_id, $locale )
	{
		$this->setUserAgent('Opera/9.22 (X11; Linux i686; U; en)');
		return $this->fetchItemInfo($item_id, $locale, 'html');
	}

	/**
	 * Fetch $character from the Armory
	 * $character is required
	 * $realm is required
	 * $locale is reqired
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @param string $fetch_type
	 * @return array
	 */
	function fetchCharacter( $character, $locale, $realm, $fetch_type='array' )
	{
		global $roster;

		$locale = substr($locale, 0, 2);
		$cache_tag = $character.$locale.$realm.$fetch_type;

		if( $roster->cache->check($cache_tag) )
		{
			if( $this->debug_cachehits )
			{
				trigger_error(__FUNCTION__ . " ::: Cache Hit: [ $cache_tag ]", E_NOTICE);
			}
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 2, $locale, false, $character, $realm );
			if( $this->_requestXml($url) )
			{
				// unparsed fetches
				if( $fetch_type != 'array' )
				{
					$roster->cache->put($cache_tag);
					return $this->xml;
				}
				// else parse and return array
				$this->xmlParser->Parse($this->xml);
				$char = $this->xmlParser->getParsedData();
				if( !isset($guild['page'][0]['child']['errorhtml']) )
				{
					$roster->cache->put($guild, $cache_tag);
				}
				else
				{
					trigger_error('RosterArmory:: Failed to fetch ' . $url. '. Armory is in maintenance mode');
				}
				return $char;
			}
			else
			{
				trigger_error('RosterArmory:: Failed to fetch ' . $url);
				return false;
			}
		}
	}

	/**
	 * Fetch $character from the Armory
	 * $character is required
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
		return $this->fetchCharacter($character, $locale, $realm, 'xml');
	}

	/**
	 * Fetch $character from the Armory
	 * $character is required
	 * $realm is required
	 * $locale is reqired
	 * Returns HTML string
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @return string | html string
	 */
	function fetchCharacterHTML( $character, $locale, $realm )
	{
		$this->setUserAgent('Opera/9.22 (X11; Linux i686; U; en)');
		return $this->fetchCharacter($character, $locale, $realm, 'html');
	}

	/**
	 * Fetch $guild from the Armory
	 * $guild should be passed as-is
	 * $realm is required
	 *
	 * @param string $guild
	 * @param string $locale
	 * @param string $realm
	 * @param string $fetch_type
	 * @return array
	 */
	function fetchGuild( $guild, $locale, $realm, $fetch_type='array' )
	{
		global $roster;

		$locale = substr($locale, 0, 2);
		$cache_tag = $guild.$locale.$realm.$fetch_type;

		if( $roster->cache->check($cache_tag) )
		{
			if( $this->debug_cachehits )
			{
				trigger_error(__FUNCTION__ . " ::: Cache Hit: [ $cache_tag ]", E_NOTICE);
			}
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 3, $locale, false, false, $realm, $guild );
			if( $this->_requestXml($url) )
			{
				//unparsed fetches
				if( $fetch_type != 'array' )
				{
					$roster->cache->put($cache_tag);
					return $this->xml;
				}
				//else parse and return array
				$this->xmlParser->Parse($this->xml);
				$guild = $this->xmlParser->getParsedData();
				$roster->cache->put($guild, $cache_tag);
				return $guild;
			}
			else
			{
				trigger_error('RosterArmory:: Failed to fetch ' . $url);
				return false;
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
		return $this->fetchGuild($guild, $locale, $realm, 'xml');
	}

	/**
	 * Fetch $guild from the Armory
	 * $guild should be passed as-is
	 * $realm is required
	 * Returns HTML string
	 *
	 * @param string $guild
	 * @param string $locale
	 * @param string $realm
	 * @return string | html string
	 */
	function fetchGuildHTML( $guild, $locale, $realm )
	{
		$this->setUserAgent('Opera/9.22 (X11; Linux i686; U; en)');
		return $this->fetchGuild($guild, $locale, $realm, 'html');
	}

	/**
	 * Fetch Character Talents for $character from the Armory
	 * $character, $locale, $realm are required to be passed
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @param bool $fetch_type
	 * @return array
	 */
	function fetchCharacterTalents( $character, $locale, $realm, $fetch_type='array' )
	{
		global $roster;

		$locale = substr($locale, 0, 2);
		$cache_tag = $character.$locale.$realm.$fetch_type.'talents';

		if( $roster->cache->check($cache_tag) )
		{
			if( $this->debug_cachehits )
			{
				trigger_error(__FUNCTION__ . " ::: Cache Hit: [ $cache_tag ]", E_NOTICE);
			}
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 4, $locale, false, $character, $realm );
			if( $this->_requestXml($url) )
			{
				//unparsed fetches
				if( $fetch_type != 'array' )
				{
					$roster->cache->put($cache_tag);
					return $this->xml;
				}
				//else parse and return array
				$this->xmlParser->Parse($this->xml);
				$talents = $this->xmlParser->getParsedData();
				if ( !isset($char['page'][0]['child']['errorhtml']) )
				{
					$roster->cache->put($talents, $cache_tag);
				}
				else
				{
					trigger_error('RosterArmory:: Failed to fetch ' . $url. '. Armory is in maintenance mode');
				}
				return $talents;
			}
			else
			{
				trigger_error('RosterArmory:: Failed to fetch ' . $url);
				return false;
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
		return $this->fetchCharacterTalents($character, $locale, $realm, 'xml');
	}

	/**
	 * Fetch Character Talents for $character from the Armory
	 * $character, $locale, $realm are required to be passed
	 * Returns HTML string
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @return string | html string
	 */
	function fetchCharacterTalentsHTML( $character, $locale, $realm )
	{
		$this->setUserAgent('Opera/9.22 (X11; Linux i686; U; en)');
		return $this->fetchCharacterTalents($character, $locale, $realm, 'html');
	}

	/**
	 * Fetch skills of $character from the Armory
	 * $realm is required
	 * $locale is reqired
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @param string $fetch_type
	 * @return array
	 */
	function fetchCharacterSkills( $character, $locale, $realm, $fetch_type='array' )
	{
		global $roster;

		$locale = substr($locale, 0, 2);
		$cache_tag = $character.$locale.$realm.$fetch_type.'skills';

		if( $roster->cache->check($cache_tag) )
		{
			if( $this->debug_cachehits )
			{
				trigger_error(__FUNCTION__ . " ::: Cache Hit: [ $cache_tag ]", E_NOTICE);
			}
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 5, $locale, false, $character, $realm );
			if( $this->_requestXml($url) )
			{
				//unparsed fetches
				if( $fetch_type != 'array' )
				{
					$roster->cache->put($cache_tag);
					return $this->xml;
				}
				// else parse and return array
				$this->xmlParser->Parse($this->xml);
				$char = $this->xmlParser->getParsedData();
				if( !isset($char['page'][0]['child']['errorhtml']) )
				{
					$roster->cache->put($char, $cache_tag);
				}
				else
				{
					trigger_error('RosterArmory:: Failed to fetch ' . $url. '. Armory is in maintenance mode');
				}
				return $char;
			}
			else
			{
				trigger_error('RosterArmory:: Failed to fetch ' . $url);
				return false;
			}
		}
	}

	/**
	 * Fetch skills of $character from the Armory
	 * $realm is required
	 * $locale is reqired
	 * Returns XML string
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @return string | xml string
	 */
	function fetchCharacterSkillsXML( $character, $locale, $realm )
	{
		return $this->fetchCharacterSkills($character, $locale, $realm, 'xml');
	}

	/**
	 * Fetch skills of $character from the Armory
	 * $realm is required
	 * $locale is reqired
	 * Returns HTML string
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @return string | html string
	 */
	function fetchCharacterSkillsHTML( $character, $locale, $realm )
	{
		$this->setUserAgent('Opera/9.22 (X11; Linux i686; U; en)');
		return $this->fetchCharacterSkills($character, $locale, $realm, 'html');
	}

	/**
	 * Fetch reputation of $character from the Armory
	 * $realm is required
	 * $locale is reqired
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @param bool $fetch_type
	 * @return array
	 */
	function fetchCharacterReputation( $character, $locale, $realm, $fetch_type='array' )
	{
		global $roster;

		$locale = substr($locale, 0, 2);
		$cache_tag = $character.$locale.$realm.$fetch_type.'reputation';

		if( $roster->cache->check($cache_tag) )
		{
			if( $this->debug_cachehits )
			{
				trigger_error(__FUNCTION__ . " ::: Cache Hit: [ $cache_tag ]", E_NOTICE);
			}
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 6, $locale, false, $character, $realm );
			if( $this->_requestXml($url) )
			{
				// unparsed fetches
				if( $fetch_type != 'array' )
				{
					$roster->cache->put($cache_tag);
					return $this->xml;
				}
				// else parse and return array
				$this->xmlParser->Parse($this->xml);
				$char = $this->xmlParser->getParsedData();
				if ( ! isset($char['page'][0]['child']['errorhtml']) )
				{
					$roster->cache->put($char, $cache_tag);
				}
				else
				{
					trigger_error('RosterArmory:: Failed to fetch ' . $url. '. Armory is in maintenance mode');
				}
				return $char;
			}
			else
			{
				trigger_error('RosterArmory:: Failed to fetch ' . $url);
				return false;
			}
		}
	}

	/**
	 * Fetch reputation of $character from the Armory
	 * $realm is required
	 * $locale is reqired
	 * Returns XML string
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @return string | xml string
	 */
	function fetchCharacterReputationXML( $character, $locale, $realm )
	{
		return $this->fetchCharacterReputation($character, $locale, $realm, 'xml');
	}

	/**
	 * Fetch reputation of $character from the Armory
	 * $realm is required
	 * $locale is reqired
	 * Returns HTML string
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @return string | html string
	 */
	function fetchCharacterReputationHTML( $character, $locale, $realm )
	{
		$this->setUserAgent('Opera/9.22 (X11; Linux i686; U; en)');
		return $this->fetchCharacterReputation($character, $locale, $realm, 'html');
	}

	/**
	 * Fetch arena teams of $character from the Armory
	 * $realm is required
	 * $locale is reqired
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @param bool $fetch_type
	 * @return array
	 */
	function fetchCharacterArenaTeams( $character, $locale, $realm, $fetch_type='array' )
	{
		global $roster;

		$locale = substr($locale, 0, 2);
		$cache_tag = $character.$locale.$realm.$fetch_type.'arenateams';

		if( $roster->cache->check($cache_tag) )
		{
			if( $this->debug_cachehits )
			{
				trigger_error(__FUNCTION__ . " ::: Cache Hit: [ $cache_tag ]", E_NOTICE);
			}
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 7, $locale, false, $character, $realm );
			if( $this->_requestXml($url) )
			{
				// unparsed fetches
				if( $fetch_type != 'array' )
				{
					$roster->cache->put($cache_tag);
					return $this->xml;
				}
				// else parse and return array
				$this->xmlParser->Parse($this->xml);
				$char = $this->xmlParser->getParsedData();
				if( !isset($char['page'][0]['child']['errorhtml']) )
				{
					$roster->cache->put($char, $cache_tag);
				}
				else
				{
					trigger_error('RosterArmory:: Failed to fetch ' . $url. '. Armory is in maintenance mode');
				}
				return $char;
			}
			else
			{
				trigger_error('RosterArmory:: Failed to fetch ' . $url);
				return false;
			}
		}
	}

	/**
	 * Fetch arena teams of $character from the Armory
	 * $realm is required
	 * $locale is reqired
	 * Returns XML string
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @return string | xml string
	 */
	function fetchCharacterArenaTeamsXML( $character, $locale, $realm )
	{
		return $this->fetchCharacterArenaTeams($character, $locale, $realm, 'xml');
	}

	/**
	 * Fetch arena teams of $character from the Armory
	 * $realm is required
	 * $locale is reqired
	 * Returns HTML string
	 *
	 * @param string $character
	 * @param string $locale
	 * @param string $realm
	 * @return string | html string
	 */
	function fetchCharacterArenaTeamsHTML( $character, $locale, $realm )
	{
		$this->setUserAgent('Opera/9.22 (X11; Linux i686; U; en)');
		return $this->fetchCharacterArenaTeams($character, $locale, $realm, 'html');
	}

	function fetchStrings( $locale, $fetch_type='array' )
	{
		global $roster;

		$locale = substr($locale, 0, 2);
		$cache_tag = 'stings'.$locale;

		if( $roster->cache->check($cache_tag) )
		{
			if( $this->debug_cachehits )
			{
				trigger_error(__FUNCTION__ . " ::: Cache Hit: [ $cache_tag ]", E_NOTICE);
			}
			return $roster->cache->get($cache_tag);
		}
		else
		{
			$url = $this->_makeUrl( 8, $locale );
			if( $this->_requestXml($url) )
			{
				// unparsed fetches
				if( $fetch_type != 'array' )
				{
					$roster->cache->put($cache_tag);
					return $this->xml;
				}
				// else parse and return array
				$this->xmlParser->Parse($this->xml);
				$strings = $this->xmlParser->getParsedData();
				if( !isset($strings['page'][0]['child']['errorhtml']) )
				{
					$roster->cache->put($strings, $cache_tag);
				}
				else
				{
					trigger_error('RosterArmory:: Failed to fetch ' . $url. '. Armory is in maintenance mode');
				}
				return $strings;
			}
			else
			{
				trigger_error('RosterArmory:: Failed to fetch ' . $url);
				return false;
			}
		}
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
	function setTimeOut( $timeout )
	{
		$this->xml_timeout = $timeout;
	}

	/**
	 * Enables Echoing of $url created.
	 *
	 */
	function setDebugUrl( )
	{
		$this->debug_url = true;
	}

	/**
	 * Enables Cache Hit Notices
	 *
	 */
	function setDebugCache( )
	{
		$this->debug_cachehits = true;
	}

	/**
	 * Enables all debugging
	 *
	 */
	function setDebugAll( )
	{
		$this->debug_url = true;
		$this->debug_cachehits = true;
	}

	/**
	 * Sets Region
	 * US = www.wowarmory.com
	 * EU = eu.wowarmory.com
	 * @param string $region
	 */
	function setRegion( $region )
	{
		$this->region = strtoupper($region);
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

		if( $this->region == 'US' )
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
					$mode .= '&n=' . urlencode($char) . '&r=' . urlencode($realm);
				}
				break;
			case 1:
			case 'item-info':
				$mode = 'item-info.xml?i=' . $id;
				if( $char )
				{
					$mode .= '&n=' . urlencode($char) . '&r=' . urlencode($realm);
				}
				break;
			case 2:
			case 'character-sheet':
				$mode = 'character-sheet.xml?n=' . urlencode($char) . '&r=' . urlencode($realm);
				break;
			case 3:
			case 'guild-info':
				$mode = 'guild-info.xml?r=' . urlencode($realm) . '&n=' . urlencode($guild). '&p=1';
				break;
			case 4:
			case 'character-talents':
				$mode = 'character-talents.xml?n=' . urlencode($char) . '&r=' . urlencode($realm);
				break;
			case 5:
			case 'character-skills':
				$mode = 'character-skills.xml?n=' . urlencode($char) . '&r=' . urlencode($realm);
				break;
			case 6:
			case 'character-reputation':
				$mode = 'character-reputation.xml?n=' . urlencode($char) . '&r=' . urlencode($realm);
				break;
			case 7:
			case 'character-arenateams':
				$mode = 'character-arenateams.xml?n=' . urlencode($char) . '&r=' . urlencode($realm);
				break;
			case 8:
			case 'strings':
				switch( $locale )
				{
					case 'en':
						$val = 'en_us';
						break;
					case 'fr':
						$val = 'fr_fr';
						break;
					case 'de':
						$val = 'de_de';
						break;
					case 'es':
						$val = 'es_es';
						break;
				}
				$mode = 'strings/' . $val . '/strings.xml?';
				break;
		}

		$url = $base_url . $mode;

		if( $this->debug_url )
		{
			echo $url;
			trigger_error('Debug: Sending [' . $url . '] to urlgrabber()', E_NOTICE);
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
