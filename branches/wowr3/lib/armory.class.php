<?php
/**
 * WoWRoster.net WoWRoster
 *
 * WoWRoster Armory Class
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version	SVN: $Id$
 * @link	   http://www.wowroster.net
 * @since	  File available since Release 1.9.9
 * @package	WoWRoster
 * @subpackage Armory
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

	// SETTINGS FOR SQL STATEMENT GENERATION
	$config['sql_database'] = 'wotf_character';									// SQL Database Name
	$config['sql_table'] = 'guild_characters_test';									// SQL Table Name

	// XML PARSING SETTINGS
//	$config['min_char_level'] = 75;													 // Limits display parsing of Characters beyond a certain level. NOTE: Use '0' to process all characters. NOTE: Requires "$config['chars_to_process'] = -1;"
//	$config['min_char_rank'] = 1;													// Limits display to Guild Rank NOTE: Requires "$config['chars_to_process'] = -1;"
	$config['equipable_items_number'] = 18;											// How many equipable items on a character. !! DO NOT EDIT THIS !!

	// DEBUG SETTINGS
	$config['chars_to_process'] = -1;												// How many characters to pull xml data for. NOTE: Use '-1' to process all characters.
	$config['use_char_selction_list'] = false;										// Select this option to only process characters names found in the "$config['char_selction_list']" array.
	$config['char_selction_list'] = array( "" );			// A list of characters to process, excluding all others. NOTE: Requires "$config['chars_to_process'] = -1;"

// ---------------- EDIT THE ABOVE SETTINGS ----------------





/**
 * WoWRoster Armory Class
 *
 * Allows easy access to the WoW Armory database
 * Returns pre-parsed XML as an Array or
 * returns unparsed XML page as a string
 *
 * @package	WoWRoster
 * @subpackage Armory
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

	var $debugmessages = array();
	var $errormessages = array();
	var $logmessages = array();
	var $live_system = true;
	var $query;
	var $server;
	var $guild;
	var $guildie;
	var $page;

	var $base_filename           = 'roster.test.php';			// Base script file name
	var $base_url                =  ROSTER_URL;//'';						   // Base URL
	var $url_prefix_armory       = 'http://www.wowarmory.com/';   // URL for the AMERICAN armory
	var $url_prefix_char         = 'character-sheet.xml?';	   // Use for Char links
	var $url_prefix_itemtooltip  = 'item-tooltip.xml?i=';		// Use for Char links
	var $url_prefix_talents      = 'character-talents.xml?';	 // used for talent links
	var $url_prefix_rep          = 'character-reputation.xml?';  // used for talent links

	// NOTE: THE BELOW DIRECTORY NEEDS TO HAVE WRITE ACCESS IN ORDER TO CACHE THE XML
	var $DIR_cache = ROSTER_CACHEDIR;
	var $HTML_cache = ROSTER_CACHEDIR;				   // Directory where the XML cache files are stored
	// NOTE: THE ABOVE DIRECTORY NEEDS TO HAVE WRITE ACCESS IN ORDER TO CACHE THE XML

	var $days_to_cache = 0;								 // How many days to keep cached files for
	var $DIR_sql = ROSTER_CACHEDIR;						// Directory where the SQL files are stored

	// LOADING BAR
	var $loading_bar = 50;								 // How many characters in the loading bar
	var $loading_bar_mask = "=";							// The loading bar symbol

	// OUTPUT DISPLAY SETTING
	// WARNING: TURNING ALL OF THESE SETTINGS ON WILL TAKE A LOOONG TIME TO PROCESS AND WILL CONSUME A FAIR AMOUNT OF SERVER RESOURCES
	// WARNING: I ONLY HAVE OVER 150 MEMBERS IN MY GUILD, WITH ALL THESE SETTINGS ON IT CAN TAKE UPTO AN HOUR TO PROCESS AND LOAD.
	var $show_xml_source = true;							// 'TRUE' = Show XML Source; 'FALSE' = Hide XML Source
	var $show_sql_import_structure = true;				  // 'TRUE' = Show SQL Import Structure; 'FALSE' = Hide SQL Import Structure
	var $show_sql_import_data = true;					   // 'TRUE' = Show SQL Import Data; 'FALSE' = Hide SQL Import Data
	var $show_html_data_table = true;					   // 'TRUE' = Show HTML Data Table; 'FALSE' = Hide HTML Data Table
	var $show_html_data_sort = false;					   // 'TRUE' = Show HTML Data Table with sort functionality and only limited fields; 'FALSE' = Show HTML Data Table with every field

	// SETTINGS FOR SQL STATEMENT GENERATION
	var $sql_database = 'wotf_character';				   // SQL Database Name
	var $sql_table = 'guild_characters_test';			   // SQL Table Name

	// XML PARSING SETTINGS
//	var $min_char_level = 75;							   // Limits display parsing of Characters beyond a certain level. NOTE: Use '0' to process all characters. NOTE: Requires "$config['chars_to_process'] = -1;"
//	var $min_char_rank = 1;								 // Limits display to Guild Rank NOTE: Requires "$config['chars_to_process'] = -1;"
	var $equipable_items_number = 18;					   // How many equipable items on a character. !! DO NOT EDIT THIS !!

	// DEBUG SETTINGS
	var $chars_to_process = -1;							  // How many characters to pull xml data for. NOTE: Use '-1' to process all characters.
	var $use_char_selction_list = false;					// Select this option to only process characters names found in the "$config['char_selction_list']" array.
	var $char_selction_list = array( "" );


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


	function setMessage($message)
	{
		$this->messages[] = $message;
	}


	/**
	 * Returns all messages
	 *
	 * @return string
	 */
	function getMessages()
	{
		return implode("\n",$this->messages) . "\n";
	}
	/**
	 * Constructor
	 *
	 * @return RosterArmory
	 */
	function RosterArmory( $region=false )
	{
		global $roster;

		$this->base_url				   	= ROSTER_URL;//see if this works..$roster->config['website_address'].'/'; // gotta have a trailing slash here..... allways....
		$this->url_prefix_armory		= $this->url_prefix_armory;//isset($roster->data['armoryurl']) ? $roster->data['armoryurl'] : $this->url_prefix_armory;
		$this->url_prefix_char			= $this->url_prefix_armory . $this->url_prefix_char;
		$this->url_prefix_itemtooltip	= $this->url_prefix_armory . $this->url_prefix_itemtooltip;
		$this->url_prefix_talents		= $this->url_prefix_armory . $this->url_prefix_talents;
		$this->url_prefix_rep			= $this->url_prefix_armory . $this->url_prefix_rep;

		$this->region = ( $region !== false ? strtoupper($region) : 'US' );
	}

	// DEFINE THE ARMORY VARIABLES
	const BROWSER="Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.2) Gecko/20070319 Firefox/2.0.0.3";



public function pull_xmln($guildie = false, $guild = false, $server = false, $query = false) {
	global $config,$roster;

	// change the first part of the $url to the armory link that you need
	if( $query === 'roster' )
	{
		$filename_type = 'guild-info';
		$url = $this->url_prefix_armory.'/'.$filename_type.'.xml?r=' . urlencode( $server ) . '&gn=' . urlencode( $guild );
	}
	elseif( $query === 'character' )
	{
		$filename_type = 'character-sheet';
		$url = $this->url_prefix_armory.'/'.$filename_type.'.xml?r=' . urlencode( $server ) . '&cn=' . $guildie;
	}
	elseif( $query === 'achievement' )
	{
		$filename_type = 'character-achievements';
		$url = $this->url_prefix_armory.'/'.$filename_type.'.xml?r=' . urlencode( $server ) . '&cn=' . urlencode( $guildie);
	}
	//$url_prefix_itemtooltip
	elseif( $query === 'itemtooltip' )
	{
		$filename_type = 'character-item';
		$url = $this->url_prefix_itemtooltip.''. $guild . '&cn=' . urlencode( $guildie) . '&r=' . urlencode( $server );
	}
	elseif( $query === 'itemtooltip2' )
	{
		$filename_type = 'item';
		$url = 'http://wow.allakhazam.com/cluster/item-xml.pl?witem=' . $guild . '&xml';
	}

	elseif( $query === 'talents' )
	{
		$filename_type = 'character-talents';
		$url = $this->url_prefix_talents.''. $guild . '&cn=' . urlencode( $guildie) . '&r=' . urlencode( $server );
	}
	elseif( $query === 'rep' )
	{
		$filename_type = 'character-rep';
		$url = $this->url_prefix_rep.''. $guild . '&cn=' . urlencode( $guildie) . '&r=' . urlencode( $server );
	}
	//alert($url);
	if ( $this->live_system ) {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt ($ch, CURLOPT_USERAGENT,  "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.2) Gecko/20070319 Firefox/2.0.0.3");
		$url_string = curl_exec($ch);
		curl_close($ch);
		$this->setMessage('-> <b>'.strtoupper($filename_type).'.XML LIVE FEED:</b> <a href="'.$url.'" target="_BLANK">'.$url.'</a>');
	} else {
		if( $this->query === 'roster' ){
		   $this->setMessage('<b>NOTE: USING XML CACHE ONLY!!!</b><P>');
		}
	}
	//alert($url_string);
	// change the first part of the $url to the armory link that you need
	if( $query === 'roster' )
	{

		$guild_cache_filename = $filename_type.'-'.$guild; // BUILD THE Guild XML CACHE FILENAME
		if ( $this->live_system ) {
			$this->cacheXMLfile($guild_cache_filename, $url_string);					// CACHE THE GUILD XML STREAM
		}
		$latestGuildXMLfile = $this->getXMLfile('guild-info');					// GET THE LATEST CACHE GUILD XML FILE
		$url = $this->DIR_cache.$latestGuildXMLfile['filename'];
		$url_filesize = $latestGuildXMLfile['filesize'];
		$this->setMessage("<P>RESULT -> ".$latestGuildXMLfile['filename']." - ".$latestGuildXMLfile['filesize']." - ".$latestGuildXMLfile['filetime']." ");
	}
	elseif( $query === 'character' )
	{
		$char_cache_filename = $filename_type.'-'.$guildie;		// BUILD THE CHRACTER XML CACHE FILENAME
	   // echo  $char_cache_filename.'<br>';
		if ( $this->live_system ) {
			$this->cacheXMLfile($char_cache_filename, $url_string);			// CACHE THE CHARACTER XML STREAM
		}
		$latestCharacterXMLfile = $this->getXMLfile($char_cache_filename);		// GET THE LATEST CACHE GUILD XML FILE
		$url = $this->DIR_cache.$latestCharacterXMLfile['filename'];
		$urla = $this->DIR_cache.$latestCharacterXMLfile['filename'];
		$url_filesize = $latestCharacterXMLfile['filesize'];
		//echo "<P>RESULT -> ".$latestCharacterXMLfile['filename']." - ".$latestCharacterXMLfile['filesize']." - ".$latestCharacterXMLfile['filetime']." <br /><br /><br /><br />";
	}
	elseif( $query === 'achievement' )
	{
		$char_cache_filename = $filename_type.'-'.urldecode($guildie);		// BUILD THE CHRACTER XML CACHE FILENAME
		if ( $this->live_system ) {
			$this->cacheXMLfile($char_cache_filename, $url_string);			// CACHE THE CHARACTER XML STREAM
		}
		$latestCharacterXMLfile = $this->getXMLfile($char_cache_filename);		// GET THE LATEST CACHE GUILD XML FILE
		$url = $this->DIR_cache.$latestCharacterXMLfile['filename'];
		$url_filesize = $latestCharacterXMLfile['filesize'];
		//echo "<P>RESULT -> ".$latestCharacterXMLfile['filename']." - ".$latestCharacterXMLfile['filesize']." - ".$latestCharacterXMLfile['filetime']." <br /><br /><br /><br />";
	}
	elseif( $query === 'itemtooltip' )
	{			 //$url_prefix_itemtooltip
		$char_cache_filename = $filename_type.'-'.$guild.'-'.$guildie;		// BUILD THE CHRACTER XML CACHE FILENAME
		if ( $this->live_system ) {
			$this->cacheXMLfile($char_cache_filename, $url_string);			// CACHE THE CHARACTER XML STREAM
		}
		$latestCharacteriXMLfile = $this->getXMLfile($char_cache_filename);		// GET THE LATEST CACHE GUILD XML FILE
		$url = $this->DIR_cache.$latestCharacteriXMLfile['filename'];
		$url_filesize = $latestCharacteriXMLfile['filesize'];
		//echo "<P>RESULT -> ".$latestCharacterXMLfile['filename']." - ".$latestCharacterXMLfile['filesize']." - ".$latestCharacterXMLfile['filetime']." <br /><br /><br /><br />";


	}
	elseif( $query === 'itemtooltip2' )
	{			 //$url_prefix_itemtooltip
		$char_cache_filename = $filename_type.'-'.$guild;		// BUILD THE CHRACTER XML CACHE FILENAME
		if ( $this->live_system ) {
			$this->cacheXMLfile($char_cache_filename, $url_string);			// CACHE THE CHARACTER XML STREAM
		}
		$latestCharacteriXMLfile = $this->getXMLfile($char_cache_filename);		// GET THE LATEST CACHE GUILD XML FILE
		$url = $this->DIR_cache.$latestCharacteriXMLfile['filename'];
		$url_filesize = $latestCharacteriXMLfile['filesize'];
		//echo "<P>RESULT -> ".$latestCharacterXMLfile['filename']." - ".$latestCharacterXMLfile['filesize']." - ".$latestCharacterXMLfile['filetime']." <br /><br /><br /><br />";


	}
	elseif( $query === 'talents' )
	{			 //$url_prefix_itemtooltip
		$char_cache_filename = $filename_type.'-'.$guildie;		// BUILD THE CHRACTER XML CACHE FILENAME
		if ( $this->live_system ) {
			$this->cacheXMLfile($char_cache_filename, $url_string);			// CACHE THE CHARACTER XML STREAM
		}
		$latestCharacteriXMLfile = $this->getXMLfile($char_cache_filename);		// GET THE LATEST CACHE GUILD XML FILE
		$url = $this->DIR_cache.$latestCharacteriXMLfile['filename'];
		$url_filesize = $latestCharacteriXMLfile['filesize'];
		//echo "<P>RESULT -> ".$latestCharacterXMLfile['filename']." - ".$latestCharacterXMLfile['filesize']." - ".$latestCharacterXMLfile['filetime']." <br /><br /><br /><br />";
	}
	elseif( $query === 'rep' )
	{			 //$url_prefix_itemtooltip
		$char_cache_filename = $filename_type.'-'.$guildie;		// BUILD THE CHRACTER XML CACHE FILENAME
		if ( $this->live_system ) {
			$this->cacheXMLfile($char_cache_filename, $url_string);			// CACHE THE CHARACTER XML STREAM
		}
		$latestCharacteriXMLfile = $this->getXMLfile($char_cache_filename);		// GET THE LATEST CACHE GUILD XML FILE
		$url = $this->DIR_cache.$latestCharacteriXMLfile['filename'];
		$url_filesize = $latestCharacteriXMLfile['filesize'];
		//echo "<P>RESULT -> ".$latestCharacterXMLfile['filename']." - ".$latestCharacterXMLfile['filesize']." - ".$latestCharacterXMLfile['filetime']." <br /><br /><br /><br />";
	}

	//
	if ( $url_filesize > 0 ) {
		$url_string = simplexml_load_file($url) or die ("ERROR: Unable to load XML file! URL: ".$url);
		if ($query === 'itemtooltip')
		{
	   // //aprint($latestCharacteriXMLfile);
		unlink($url); //alert($config['DIR_cache'].$file);

		}
		//echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -> <b>'.strtoupper($filename_type).'.XML CACHE:</b> <a href="'.$url.'" target="_BLANK">'.$url.'</a> - '.$this->fileSizeInfo($url_filesize).' read...<P>';
	} else {
		$url_string = "";
	}

	return $url_string;


 } // end of pull_xml()
 function printLoadTime() {
	global $config;
	$m_time = explode(" ",microtime());
	$m_time = $m_time[0] + $m_time[1];
	$endtime = $m_time;
	$totaltime = ($endtime - $config['starttime']);
	return (string)round($totaltime,$config['round']);
}



//------------------------------------------------------------------
// Convert seconds to '0h 0m 0s'
 function sec2hms ($sec, $padHours = false) {
	$hms = "";
	$hours = intval(intval($sec) / 3600);
	$hms .= ($padHours)
		  ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
		  : $hours. ':';
	$minutes = intval(($sec / 60) % 60);
	$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';
	$seconds = intval($sec % 60);
	$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
	return $hms;
 }



//------------------------------------------------------------------
// Convert bytes to '0TB 0GB 0MB 0MB 0kb'
function fileSizeInfo($size){
	$i=0;
	$iec = array("b", "kb", "mb", "gb", "tb", "pb", "eb", "zb", "yb");
	while (($size/1024)>1) {
		$size=$size/1024;
		$i++;
	}
	return substr($size,0,strpos($size,'.')+4).$iec[$i];
}


//$this->setMessage(
//------------------------------------------------------------------
// Get the latest cached XML file from the cache directory
function getXMLfile( $XMLtype ) {
	global $config;
	$caschmsg = '';
	// GET LATEST XML FILE
	$new_file_size = 0;
	$new_file_time = 0;
	$new_file_name = "";
	$old_file_date = time() - ($this->days_to_cache * 24 * 60 * 60);
	$dir = opendir ($this->DIR_cache);

	$caschmsg .= "<br /><b>-> CHECKING XML CACHE [";
	$filecount = count(glob($this->DIR_cache."*.xml"));
	$filecount_current=0;
	$loading_bar_list = array();
	for( $i=1; $i<$this->loading_bar; $i++ ) { $loading_bar_list[] = round($filecount/$i); }
	while (false !== ($file = readdir($dir))) {
		if (strpos($file, $XMLtype ) === false ) {
		} else {
			$file_size = filesize($this->DIR_cache.$file);
			$file_time = filemtime($this->DIR_cache.$file);
			if ( $file_size > 614 ) { // 614 BYTES 503 ERROR DOCUMENT // 651 BYTES CHAR ERROR PAGE
				$caschmsg .= $this->loading_bar_mask;
				if ( $file_time >= $new_file_time ) { // CHECK IF ITS A NEWER FILE
					//$caschmsg .= " [newer file]";
					$new_file_size = $file_size;
					$new_file_time = $file_time;
					$new_file_name = $file;
				}
				//$caschmsg .= "<br />";
			}
			// REMOVE FILES OLDER THAN THE SPECIFIED TIME
			if( $file_time < $old_file_date ) {
				//echo '---'.$this->DIR_cache.$file.'---<br>';
				unlink($this->DIR_cache.$file); //alert($config['DIR_cache'].$file);
			}
		}
		if (in_array($filecount_current,$loading_bar_list))	//echo $this->loading_bar_mask;
		$filecount_current++;
	}
	$caschmsg .= "] - DONE</b><P>";
	$this->setMessage(''.$caschmsg.'');
	return array( "filename" => $new_file_name, "filesize" => $new_file_size, "filetime" => $new_file_time );
}




//------------------------------------------------------------------
// Save XML Stream to the cache directory
function cacheXMLfile($filename, $XMLstream) {
	global $config;

	$XML_filename = $this->DIR_cache.$filename.'-'.date('Y.m.d-H.i.s').'.xml';
	$handle = fopen($XML_filename, "wb");
	$numbytes = fwrite($handle, $XMLstream);
	fclose($handle);
	$url_filesize_obj = $this->fileSizeInfo($numbytes);
   // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -> <b>LIVE XML FEED CACHED TO XML FILE:</b> ./'.$XML_filename.' - '.$this->fileSizeInfo($numbytes).' written...<P>';
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

		$url = $this->_makeUrl( $type, $roster->config['locale'], $item_id, $character, $realm, $guild );
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
	// extended function to acomadate achivements
	function fetchArmorya( $type, $character, $guild = false, $realm, $item_id,$fetch_type = 'array' )
	{
		global $roster;

		$url = $this->_makeUrl( $type, false, $item_id, $character, $realm, $guild );
		//echo $url.'<br>';
		if ( $fetch_type == 'html')
		{
			$this->setUserAgent('Opera/9.22 (X11; Linux i686; U; en)');
		}
		if( $this->_requestXml($url) )
		{
			$f = "";

								 $ch = curl_init();
					curl_setopt ($ch, CURLOPT_URL, $url);
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 15);
					curl_setopt ($ch, CURLOPT_USERAGENT,  self::BROWSER);
					$f = curl_exec($ch);
					curl_close($ch);
				//$f = curl_exec($ch);
				//curl_close($ch);
			$xml = simplexml_load_string($f, 'SimpleXMLElement', LIBXML_NOCDATA);

			return $xml;
		}
		else
		{
			trigger_error('RosterArmory:: Failed to fetch ' . $url);
			return false;
		}
	}

	function fetchArmoryachive( $url, $character = false, $guild = false, $realm = false, $item_id = false,$fetch_type = 'array' )
	{
		global $roster;
/*
		//$this->setUserAgent('Opera/9.22 (X11; Linux i686; U; en)');
		$this->xml = urlgrabber($url, '10', 'Opera/9.22 (X11; Linux i686; U; en)');
		//echo $this->xml;

		// parse and return array
		$this->_initXmlParser();
		$this->xmlParser->Parse($this->xml);
		$data = $this->xmlParser->getParsedData();

		return $data;
		*/

		return $this->fetchArmorya( $url, $character, false, $realm, $item_id, $fetch_type );
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

	/**
	 * Fetches $item_id Tooltip from the Armory
	 * Accepts optional $character if used $realm is also required
	 * Returns HTML string
	 *
	 * @param string $item_id
	 * @return string
	 */
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
	function fetchCharacter( $character, $guild, $locale, $realm, $fetch_type='array' )
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
	function _makeUrl( $mode, $locale, $id=false, $char=false, $realm=false, $guild=false )
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
				$mode = 'guild-info.xml?r=' . urlencode($realm) . '&gn=' . urlencode($guild) . '&p=1';
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
			case 10:
			case 'talents':
				$mode = 'talent-tree.xml?cid=' . urlencode($id) . '&loc=' . $locale . '';
				break;

			// these next 2 modes are for achivements because there is no page identifer for the summary page but one for the other pages
			// case 11 is for the summary page
			case 11:
			case 'achivements':
				$mode = 'character-achievements.xml?cn=' . urlencode($char) . '&r=' . urlencode($realm) . '';
				break;
			// case 12 is for sup menu items ie quests and world events for a example...
			// id is used for the page number
			case 12:
			case 'achivements':
				$mode = 'character-achievements.xml?cn=' . urlencode($char) . '&r=' . urlencode($realm) . '&c=' . urlencode($id) . '';
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
	function _initXmlParser( )
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
	function _initSimpleParser( )
	{
		if( !is_object($this->simpleParser) )
		{
			require_once(ROSTER_LIB . 'simpleparser.class.php');
			$this->simpleParser = new simpleParser();
		}
	}

	function _parseData ( $array = array() ) {
		$this->datas = array();
		$this->_makeSimpleClass( $array );
		//$this->_debug( 3, true, 'Parsed XML data', 'OK' );
		return $this->datas[0];
	   // $this->_debug( 3, '', 'Parsed XML data', 'OK' );
	}

	function _makeSimpleClass ( $array = array() ) {

		$tags = array_keys( $array );
		foreach ( $array as $tag => $content ) {
			foreach ( $content as $leave ) {
				$this->_initClass( $tag, $leave['attribs'] );
				$this->datas[count($this->datas)-1]->setProp("_CDATA", $leave['data']);
				if ( array_keys($leave['child']) ) {
					$this->_makeSimpleClass( $leave['child'] );
				}
				$this->_finalClass( $tag );
			}
		}
	   // $this->_debug( 3, '', 'Made simple class', 'OK' );
	}

	/**
	 * helper function initialise a simpleClass Object
	 *
	 * @param string $class
	 * @param string $tree
	 * @return string
	 */
	function _initClass( $tag, $attribs = array() ) {
		$node = new SimpleClass();
		$node->setArray($attribs);
		$node->setProp("_TAGNAME", $tag);
		$this->datas[] = $node;
		//$this->_debug( 3, '', 'Initialized simple class', 'OK' );
	}


	/**
	 * helper function finalize a simpleClass Object
	 *
	 * @param string $class
	 * @param string $tree
	 * @return string
	 */
	function _finalClass( $tag, $val = array() ) {
		if (count($this->datas) > 1) {
			$child = array_pop($this->datas);

			if (count($this->datas) > 0) {
				$parent = &$this->datas[count($this->datas)-1];
				$tag = $child->_TAGNAME;

				if ($parent->hasProp($tag)) {
					if (is_array($parent->$tag)) {
						//Add to children array
						$array = &$parent->$tag;
						$array[] = $child;
					} else {
						//Convert node to an array
						$children = array();
						$children[] = $parent->$tag;
						$children[] = $child;
						$parent->$tag = $children;
					}
				} else {
					$parent->setProp($tag, $child);
				}
			}
		}
		//$this->_debug( 3, '', 'Finalized simple class', 'OK' );
	}

	function setProp($propName, $propValue) {
		$propName = $propValue;
		if (!in_array($propName, $properties)) {
			$properties[] = $propName;
		}
  }

	function setArray($array) {
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				$this->setProp($key, $value);
			}
		}
	}

	function hasProp($propName) {
		return in_array($propName, $this->properties);
	}

  function _debug( $level = 0, $ret = false, $info = false, $status = false ) {
		global $roster, $addon;

		if ( $level > $addon['config']['armorysync_debuglevel'] ) {
			return;
		}
		$timestamp = round((format_microtime() - ARMORYSYNC_STARTTIME), 4);
		if( version_compare(phpversion(), '4.3.0','>=') ) {
			$tmp = debug_backtrace();
			$trace = $tmp[1];
		}
		$array = array(
			'time' => $timestamp,
			'file' => isset($trace['file']) ? str_replace($addon['dir'], '', $trace['file']) : 'armorysync.class.php',
			'line' => isset($trace['line']) ? $trace['line'] : '',
			'function' => isset($trace['function']) ? $trace['function'] : '',
			'class' => isset($trace['class']) ? $trace['class'] : '',
			//'object' => isset($trace['object']) ? $trace['object'] : '',
			//'type' => isset($trace['type']) ? $trace['class'] : '',
			'args' => ( $addon['config']['armorysync_debugdata'] != 0 && isset($trace['args']) && !is_object($trace['args']) ) ? $trace['args'] : '',
			'ret' => ( $addon['config']['armorysync_debugdata'] != 0 && isset($ret) && !is_object($ret)) ? $ret : '',
			'info' => isset($info) ? $info : '',
			'status' => isset($status) ? $status : '',
										);
		if ( !($level > $addon['config']['armorysync_debuglevel']) ) {
			$this->debugmessages[] = $array;
		}
		if ( $level == 0 ) {
			$this->errormessages[] = $array;
		}
	}
	function _showFooter() {
		global $roster, $addon;

		////aprint($this->debugmessages[0]['ret']);

		$roster->tpl->assign_vars( array (
			'IMAGE_PATH' => $addon['image_path'],
			'ARMORYSYNC_VERSION' => $addon['version']. ' by Ulminia',
			'ARMORYSYNC_CREDITS' => $roster->locale->act['armorysync_credits'],
			'ERROR' => count( $this->errormessages ) > 0,
			'DEBUG' => $addon['config']['armorysync_debuglevel'],
			'DEBUG_DATA' => $addon['config']['armorysync_debugdata'],
			'D_START_BORDER' => border( 'sblue', 'start', 'ArmorySync Debugging '. ( $addon['config']['armorysync_debugdata'] ? 'Infos & Data' : 'Infos'), '100%' ),
			'E_START_BORDER' => border( 'sred', 'start', 'ArmorySync Error '. ( $addon['config']['armorysync_debugdata'] ? 'Infos & Data' : 'Infos'), '100%' ),
			'RUNTIME' => round((format_microtime() - ARMORYSYNC_STARTTIME), 4),
			'S_SQL_WIN' => $addon['config']['armorysync_sqldebug'],
			));

		$this->_debug( 3, null, 'Printed footer', 'OK');

		if ($roster->switch_row_class(false) != 1 ) {
			$roster->switch_row_class();
		}

		foreach ( $this->errormessages as $message ) {
			$roster->tpl->assign_block_vars('e_row', array(
				'FILE' => $message['file'],
				'LINE' => $message['line'],
				'TIME' => $message['time'],
				'CLASS' => $message['class'],
				'FUNC' => $message['function'],
				'INFO' => $message['info'],
				'STATUS' => $message['status'],
				'ARGS' => aprint($message['args'], '', 1),
				'RET'  => aprint($message['ret'], '' , 1),
				'ROW_CLASS1' => $addon['config']['armorysync_debugdata'] ? 1 : $roster->switch_row_class(),
				'ROW_CLASS2' => 1,
				'ROW_CLASS3' => 1,
				));
		}

		$roster->tpl->assign_var( 'E_STOP_BORDER', border( 'sred', 'end', '', '' ) );

		if ($roster->switch_row_class(false) != 1 ) {
			$roster->switch_row_class();
		}

		foreach ( $this->debugmessages as $message ) {
			$roster->tpl->assign_block_vars('d_row', array(
				'FILE' => $message['file'],
				'LINE' => $message['line'],
				'TIME' => $message['time'],
				'CLASS' => $message['class'],
				'FUNC' => $message['function'],
				'INFO' => $message['info'],
				'STATUS' => $message['status'],
				'ARGS' => aprint($message['args'], '', 1),
				'RET'  => aprint($message['ret'], '' , 1),
				'ROW_CLASS1' => $addon['config']['armorysync_debugdata'] ? 1 : $roster->switch_row_class(),
				'ROW_CLASS2' => 1,
				'ROW_CLASS3' => 1,
				));
		}

		$roster->tpl->assign_var( 'D_STOP_BORDER', border( 'sblue', 'end', '', '' ) );

		if( $addon['config']['armorysync_sqldebug'] )
		{
			if( count($roster->db->queries) > 0 )
			{
				foreach( $roster->db->queries as $file => $queries )
				{
					if (!preg_match('#[\\\/]{1}addons[\\\/]{1}armorysync[\\\/]{1}inc[\\\/]{1}[a-z_.]+.php$#', $file)) {
						continue;
					}
					$roster->tpl->assign_block_vars('sql_debug', array(
						'FILE' => substr($file, strlen(ROSTER_BASE)),
						)
					);
					foreach( $queries as $query )
					{
						$roster->tpl->assign_block_vars('sql_debug.row', array(
							'ROW_CLASS' => $roster->switch_row_class(),
							'LINE'	  => $query['line'],
							'TIME'	  => $query['time'],
							'QUERY'	 => nl2br(htmlentities($query['query'])),
							)
						);
					}
				}

				$roster->tpl->assign_vars(array(
					'SQL_DEBUG_B_S' => border('sgreen','start',$roster->locale->act['sql_queries']),
					'SQL_DEBUG_B_E' => border('sgreen','end'),
					)
				);
			}
		}

		$roster->tpl->set_filenames( array (
				'footer' => $addon['basename'] . '/footer.html',
				));
		$roster->tpl->display('footer');
	}




}
