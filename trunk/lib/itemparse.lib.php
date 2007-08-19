<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Item Statistic Parser
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

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

class webitemdb 
{	
	var $item = array();
	var $url;
	var $xml;
	var $element;
	/**
	 * Object of MiniXMLDoc
	 *
	 * @var MiniXMLDoc
	 */
	var $parsedDoc;
	/**
	 * Root Element object of $parsedDoc
	 *
	 * @var MiniXMLDoc
	 */
	var $rootEL;
	
	
	/**
	 * Retrieves item XML from Allakhazam
	 *
	 * @param var $itemid
	 * @return array
	 */
	function getItem( $itemid )
	{
		if( is_numeric( $itemid ) )
		{
			$this->url = 'http://wow.allakhazam.com/cluster/item-xml.pl?witem=' . urlencode($itemid);
			$this->xml = urlgrabber( $this->url );
		}
		else
		{
			return false;
		}
		
		if( $this->xml == false)
		{
			return false;
		}
		else 
		{
			return $this->parseItem( $this->xml );
		}
	}
	
	/**
	 * Parses out item information using MiniXML
	 *
	 * @param var $xml
	 */
	function parseItem( $xml )
	{
		$this->parsedDoc = new MiniXMLDoc();
		$this->parsedDoc->fromString( $xml );
		$this->rootEL = $this->parsedDoc->getRoot( $this->parsedDoc );
		
		$this->getStat( 'name1' );
		$this->getStat( 'armor' );
		$this->getStat( 'socket_1' );
		$this->getStat( 'socket_2' );
		$this->getStat( 'socket_3' );
		$this->getSubStat( 'agility' );
		$this->getSubStat( 's_blue' );
		$this->getSubStat( 's_meta' );
		$this->getSubStat( 's_red' );
		$this->getSubStat( 's_yellow' );
				
		echo("<pre>");
		print_r($this->item);
		
	}
	
	/**
	 * Gets a first-level element from the XML
	 *
	 * @param var $stat
	 */
	function getStat( $stat )
	{	
		$this->item[$stat] = $this->rootEL->getElement( $stat )->getValue();
	}
	
	/**
	 * Gets a second-level element from the XML
	 *
	 * @param var $stat
	 * @param var $element
	 */
	function getSubStat( $stat, $element = 'stats' )
	{	
		$this->element =& $this->rootEL->getElement( $element );
		$this->item[$stat] = $this->element->getElement( $stat )->getValue();
	}
}