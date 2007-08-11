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
 * @version    $Id: $
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

//REMOVE THIS PRIOR TO GO-LIVE
require_once("../settings.php");
//*

require_once( ROSTER_LIB . 'functions.lib.php' );
require_once( ROSTER_LIB . 'minixml.lib.php' );


//REMOVE THIS PRIOR TO GO-LIVE
if(isset($_GET['id']))
{
	$id = $_GET['id'];
} else {
	$id = '30190';
}

$webitemdb = new webitemdb();

$itemarray = $webitemdb->getItem( $id );
//*

class webitemdb 
{	
	var $item = array();

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
		else {
			return false;
		}
		
		if( $this->xml == false)
		{
			return false;
		}
		else {
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
		$this->getSubStat( 'agility', 'stats' );
				
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
	function getSubStat( $stat, $element )
	{	
		$this->element =& $this->rootEL->getElement( $element );
		$this->item[$stat] = $this->element->getElement( $stat )->getValue();
	}
}