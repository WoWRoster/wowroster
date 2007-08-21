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
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterClass
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

require_once(ROSTER_LIB . 'xmlparse.class.php');  // needed?

/**
 * Armory Query Class
 * 
 * This will connect to the correct WoW Roster Site depending on locale information
 * and return requested info in XML or Array depending on the request
 * WIP
 *
 */
class lib_armory
{
	var $locale;
	var $guild;
	var $item_id;
	var $xml;
	
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
	function lib_armory($item_id=false, $locale='enUS')
	{
		$this->initXmlParser();
		$this->item_id = $item_id;
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
		$u_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1';
		
		if( $roster->cache->check($id.$locale) )
		{
			return $roster->cache->get($id.$locale);
		}
		else 
		{
		$this->xml = urlgrabber('http://armory.worldofwarcraft.com/item-tooltip.xml?i=' . $id . '&locale=' . $locale, 5, $u_agent );
		$this->xmlParser->Parse($this->xml);
		$item = $this->xmlParser->getParsedData();
		$roster->cache->put($item, $id.$locale);
		return $item;
		}
	}
	
	function initXmlParser()
	{
		if( !is_object($this->xmlParser) )
		{
			$this->xmlParser = new XmlParser();
		}
	}
}