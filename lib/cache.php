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
//
//if( !defined('ROSTER_INSTALLED') )
//{
//	exit('Detected invalid access to this file!');
//}

class cache
{
	var $cache_suffix;
	var $object_ttl;
	var $sql_ttl;
	var $cache_dir;
	var $sql_link_id;
	var $sql_query;
	var $cache_data;
	var $cache_index=0;

	/**
	 * Constructor
	 *
	 * @return cache
	 */
	function cache()
	{
		$this->cache_suffix = '.txt'; 
		$this->object_ttl = '600';
		$this->sql_ttl = '600';
		$this->cache_dir = ROSTER_CACHEDIR;
	}
	
	/**
	 * Verifies that the cache directory is readable/writeable.  Will return an error code on fault.
	 *
	 * @return int | 	1 on success else: 
	 * 					-1 : cannot open cache file
	 * 					-2 : cannot set file lock
	 * 					-3 : cannot write to opened file
	 * 					-4 : unable to read test cache file
	 */
	function verifyCacheDirectory()
	{
		$cache_file = $this->cache_dir . md5('cache_test') . $this->cache_suffix;
		
		if( !$file=fopen($cache_file, 'w') )
		{
			trigger_error('Could not open cache file');
			return -1;
		}
		
		if( !flock($file, LOCK_EX) )
		{
			trigger_error('Unable to set file lock');
			return -2;
		}
		
		$test_var = 'test of cache<br />';
		
		if( !fwrite($file, serialize($test_var)) )
		{
			trigger_error('Unable to write cache file');
			flock( $file, LOCK_UN );
			return -3;
		}
		
		flock($file, LOCK_UN);
		fclose($file);
		
		if( $this->readCache(md5('cache_test')) )
		{
			return 1;
		}
		else
		{
			return -4;
		}
	}
	
	/**
	 * writes serialized $data to $cache_file
	 *
	 * @param mixed $data
	 * @param string $cache_file
	 * @return bool
	 */
	function writeCache( $data, $cache_file='main_cache' )
	{
		$cache_file = $this->cache_dir . $cache_file . $this->cache_suffix;
		
		if( !$file=fopen($cache_file, 'w') )
		{
			trigger_error('Could not open cache file');
			return false;
		}
		
		if( !flock($file, LOCK_EX) )
		{
			trigger_error('Unable to set file lock');
			return false;
		}
		
		if( !fwrite($file, serialize($data)) )
		{
			trigger_error('Unable to write cache file');
			flock($file, LOCK_UN);
			fclose($file);
			return false;
		}
		
		flock($file, LOCK_UN);
		fclose($file);
		return true;
	}
	
	/**
	 * return the unserialized $cache_file
	 *
	 * @param unknown_type $cache_file
	 * @return unknown
	 */
	function readCache( $cache_file )
	{
		$cache_file = $this->cache_dir . md5($cache_file) . $this->cache_suffix;
		
		if( !file_exists($cache_file) )
		{
			echo $cache_file;
			trigger_error('Unable to read cache file');
			return 1;
		}
		return unserialize(file_get_contents($cache_file));
	}
	
//	function checkCache( $cache_data )
//	{
//		$cache_file = $this->cache_dir . md5($cache_data) . $this->cache_suffix;
//		
//		if( !file_exists($cache_file) )
//		{
//			// not cached.. cache and display.
//			$this->writeCache( $cache_data, $cache_file );
//			return $this->readCache( $cache_file );
//		}
//		else 
//		{
//			return $this->readCache( $cache_file );
//		}
//	}
	
	function fetchCache( $result_type='SQL_BOTH' )
	{
		if( !empty($this->cache_data) )
		{
			return array_shift($this->cache_index);
		}
		
		$cache_file = $this->cache_dir . $query_id . $this->cache_suffix;
		// reads in the cache file if found else makes the cache and reads in
		if( file_exists($cache_file) )
		{
			$this->cache_data = $this->readCache($cache_file);
			return array_shift($this->cache_data);
		}
		else
		{
			//SQL not cached. populate the cache_data property
			if( $this->sql_link_id )
			{
				$result = array();
				while( $this->record_set[$query_id] = @mysql_fetch_array($this->sql_link_id, $result_type) )
				{
					$result[] = $this->record_set[$query_id];
				}
				return $result;
			}
			else
			{
				return false;
			}
		}
	}

	function getSqlId( $sql, $link_id )
	{
		$this->sql_link_id = $link_id;
		$this->sql_query = $sql;
		
		return md5($sql);
	}
	
//	function fetchCache( $sql )

} // end of cache class
