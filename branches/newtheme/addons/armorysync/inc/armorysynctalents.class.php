<?php
/**
 * WoWRoster.net WoWRoster
 *
 * ArmorySyncTalents Library
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: armorysynctalents.class.php 344 2007-12-24 02:29:40Z poetter $
 * @link       http://www.wowroster.net
 * @package    ArmorySync
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

require_once ($addon['dir'] . 'inc/armorysyncbase.class.php');

class ArmorySyncTalents extends ArmorySyncBase {

    var $host;
    var $url;

    var $debugmessages = array();
    var $errormessages = array();

    function getTalents( $class ) {
        global $roster;

        $locale = substr($roster->config['locale'], 0, 2);
        $cache_tag = $class.$locale;

        $ret = '';
		if( $roster->cache->check($cache_tag) )
        {
            $ret = $roster->cache->get($cache_tag);
        }
        else
        {
            $this->_makeUrl( $class );
            $content = $this->_fetchData();
            $content = $this->_prepareData( $content );
            $data = $this->_getData( $content );
            $roster->cache->put($data, $cache_tag);
            $ret = $data;
        }
		$this->_debug( $ret ? 2 : 0, $ret, 'Fetch talent tree icons from blizzard', $ret ? 'OK' : 'Failed' );
		return $ret;
    }

    function _getData( $content = false ) {

        $check = @eval( $content );

        $ret = array(
                        'tree' => $tree,
                        'talent' => $talent,
                        'rank' => $rank,
                        'treeStartStop' => $treeStartStop,
                    );
		$this->_debug( is_null($check)  ? 3 : 0, array($check, $ret), 'Eval converted java code', is_null($check) ? 'OK' : 'Failed' );
		return $ret;
    }

    function _prepareData( $content = false ) {

        $content = str_replace('i =', '$i =', $content );
        $content = str_replace('i++ ', 'i++;', $content );
        $content = str_replace('i++', '$i++', $content );
        $content = str_replace('[i]', '[$i]', $content );
        $content = str_replace('= i', '= $i', $content );

        $content = str_replace('t =', '$t =', $content );
        $content = str_replace('t++', '$t++', $content );
        $content = str_replace('[t]', '[$t]', $content );

        $content = str_replace('className', '$className', $content );
        $content = str_replace('talentPath', '$talentPath', $content );

        $content = str_replace('tree[', '$tree[', $content );
        $content = str_replace('talent[', '$talent[', $content );
        $content = str_replace('rank[', '$rank[', $content );

        $content = preg_replace('/, \[.*?\]/', '', $content, -1);

        $content = str_replace('var ', '', $content );
        $content = str_replace('treeStartStop', '$treeStartStop', $content );
        $content = str_replace('jsLoaded', '//jsLoaded', $content );

        $content = str_replace('= [', '= array(', $content );
        $content = str_replace('=[', '= array(', $content );
        $content = str_replace('];', ');', $content );

		$this->_debug( $content ? 3 : 0, $content, 'Convert java code to php', $content ? 'OK' : 'Failed' );
        return $content;
    }

    function _fetchData() {
        global $roster;
	$useragent = "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/2.0.0.2";
        $cookie = "cookieLangId=".strtolower(substr($roster->config['locale'], 0, 2)) . "_" . strtolower(substr($roster->config['locale'], 2, 2)) .";";
        $content = '';

		if ($fp = fsockopen($this->host, 80, $errno, $errstr, 30)) {
			stream_set_timeout($fp, 30);
			$buffer = '';

			$headers =
				"GET ". $this->url. " HTTP/1.0\r\n" .
				"Host: ". $this->host. "\r\n".
				"Connection: close\r\n".
				"Cookie: $cookie\r\n".
				"\r\n";

			fwrite($fp, $headers);
			while (!feof($fp)) {
				$content .= fread($fp, 8192);
			}
			fclose($fp);
		} else {
			//Throw Error
			print "CRITICAL ERROR - socket not connected";
		}

		//If not using CURL we may get the header returned before the HTML table..
		$pos = $this->_striposB ($content, 'var i =');
		if ($pos != 0){
			//Need to trim header off..
			$content = substr ($content, $pos);
		}
		$this->_debug( $content ? 3 : 0, $content, 'Fetch java code from blizzard', $content ? 'OK' : 'Failed' );
		return $content;
    }


    function _makeURL ( $class = false ) {
        global $roster;

        $host = '';
        $url = '';

        $locale = substr($roster->config['locale'], 0, 2);

        if( $locale == 'en' )
        {
                $this->host = 'www.worldofwarcraft.com';
                $this->url = '/shared/global/talents/'. $class. '/data.js';
        }
        else
        {
                $this->host = 'www.wow-europe.com';
                $this->url = '/shared/global/talents/'. $class. '/'. $locale. '/data.js';
        }
		$this->_debug( 3, null, 'Choose server and url', 'OK');
    }

    function _striposB($haystack, $needle){
        $ret = strpos($haystack, stristr( $haystack, $needle ));
		$this->_debug( $ret ? 3 : 0, $ret, 'Find needle in haystack', $ret ? 'OK' : 'Failed' );
		return $ret;
    }
}
