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

	var $itemTooltip = 0;
	var $itemInfo = 1;
	var $characterInfo = 2;
	var $guildInfo = 3;
	var $characterTalents = 4;
	var $characterSkills = 5;
	var $characterReputation = 6;
	var $characterArenaTeams = 7;
	var $strings = 8;
	var $search = 9;

	/**
	 * xmlParsing object
	 *
	 * @var XmlParser
	 */
	var $xmlParser;

	/**
	 * simpleParsing object
	 *
	 * @var XmlParser
	 */
	var $simpleParser;

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
 	 * General armory fetch class
	 * Returns XML, HTML or an array of the parsed XML page
	 *
	 * @param int $type
	 * @param string $character
	 * @param string $guild
	 * @param string $realm
	 * @param int $item_id
	 * @param string $fetch_type
	 * @return array
	 */
	function fetchArmory( $type = false, $character = false, $guild = false, $realm = false, $item_id = false,$fetch_type = 'array' )
	{
		global $roster;

		$url = $this->_makeUrl( $type, false, $item_id, $character, $realm, $guild );
		if ( $fetch_type == 'html')
		{
			$this->setUserAgent('Opera/9.22 (X11; Linux i686; U; en)');
		}
		if( $this->_requestXml($url) )
		{
			if( $fetch_type == 'array' )
			{
				// parse and return array
				$this->_initXmlParser();
				$this->xmlParser->Parse($this->xml);
				$data = $this->xmlParser->getParsedData();
			}
			elseif( $fetch_type == 'simpleClass' )
			{
				// parse and return SimpleClass object
				$this->_initSimpleParser();
				$data = $this->simpleParser->parse($this->xml);
			}
			else
			{
				// unparsed fetches
				return $this->xml;
			}
			return $data;
		}
		else
		{
			trigger_error('RosterArmory:: Failed to fetch ' . $url);
			return false;
		}
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
		return $this->fetchArmory( 0, $character, false, $realm, $item_id, $fetch_type );
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
		return $this->fetchArmory( 1, false, false, false, $item_id, $fetch_type );
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
		return $this->fetchArmory( 2, $character, $guild, $realm, false, $fetch_type );
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
		return $this->fetchArmory( 3, false, $guild, $realm, false, $fetch_type );
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
	function fetchGuildSimpleClass( $guild, $locale, $realm )
	{
		return $this->fetchGuild($guild, $locale, $realm, 'simpleClass');
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
		return $this->fetchArmory( 4, $character, $guild, $realm, false, $fetch_type );
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
		return $this->fetchArmory( 5, $character, $guild, $realm, false, $fetch_type );
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
		return $this->fetchArmory( 6, $character, $guild, $realm, false, $fetch_type );
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
		return $this->fetchArmory( 7, $character, $guild, $realm, false, $fetch_type );
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
		return $this->fetchArmory( 8, false, false, false, false, $fetch_type );
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
		if( $this->region == 'US' )
		{
			//$base_url = 'http://localhost:18080/?url=http://www.wowarmory.com/';
			$base_url = 'http://www.wowarmory.com/';
		}
		else
		{
			//$base_url = 'http://localhost:18080/?url=http://eu.wowarmory.com/';
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
				switch( substr($this->locale, 0, 2) )
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
			case 9:
			case 'search':
				$mode = 'search.xml?searchQuery=' . urlencode($id) . '&searchType=items';
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

	/**
	 * Private function that includes simpleparser class if needed and then creates
	 * a new SimpleParser() object if needed
	 *
	 * @return void
	 */
	function _initSimpleParser()
	{
		if( !is_object($this->simpleParser) )
		{
			require_once(ROSTER_LIB . 'simpleparser.class.php');
			$this->simpleParser = new simpleParser();
		}
	}
}
