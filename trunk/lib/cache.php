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
		$this->cache_suffix = '.inc'; 
		$this->object_ttl = '10800'; //3 hours
		$this->sql_ttl = '600';
		$this->cache_dir = ROSTER_CACHEDIR;
	}
	
	/**
	 * Maint function to clean cache directory
	 * optional prefix arg
	 *
	 * @param string $prefix
	 */
	function cleanCache( $prefix=null )
	{
		foreach( glob($this->cache_dir . $prefix . '*') as $file ) 
		{
			@unlink($file);
		}
	}

	/**
	 * check the object cache for $cache_file
	 * returns true if cache file exists and not expired
	 *
	 * @param string $cache_file
	 * @return bool
	 */
	function check( $cache_file )
	{
		global $roster;
		
		if( !$roster->config['local_cache'] )
		{
			return false;
		}		
		
		$cache_file = $this->cache_dir . 'obj_' . md5($cache_file) . $this->cache_suffix;

		if( file_exists($cache_file) && filemtime($cache_file) > (time() - $this->object_ttl) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * returns contains of $cache_file if cache file exists
	 *
	 * @param string $cache_file
	 * @return bool
	 */
	function get( $cache_file )
	{
		$cache_file = $this->cache_dir . 'obj_' . md5($cache_file) . $this->cache_suffix;

		if( file_exists($cache_file) )
		{
			return $this->_readCache( $cache_file );
		}
		else
		{
			return false;
		}		
	}
	
	/**
	 * writes object cache for $data
	 * accepts optional $cache_file otherwise uses $data as the $cache_file name
	 * does not check for expired ttl use check()q
	 *
	 * @param mixed $data
	 * @param string $cache_file
	 * @return bool
	 */
	function put( $data, $cache_file=false )
	{
		global $roster;
		
		if( !$roster->config['local_cache'] )
		{
			return false;
		}
		
		if( $cache_file )
		{
			$cache_file = $this->cache_dir . 'obj_' . md5($cache_file) . $this->cache_suffix;
		}
		else 
		{
			$cache_file = $this->cache_dir . 'obj_' . md5($data) . $this->cache_suffix;
		}

		if( !empty($data) )
		{		
			return $this->_writeCache($data, $cache_file);
		}
		else 
		{
			return false;
		}
		
	}
		
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
	
	/**
	 * writes serialized $data to $cache_file
	 *
	 * @param mixed $data
	 * @param string $cache_file
	 * @return bool
	 */
	function _writeCache( $data, $cache_file )
	{
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
	 * @param string $cache_file
	 * @return mixed
	 */
	function _readCache( $cache_file )
	{
		return unserialize(file_get_contents($cache_file));
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

} // end of cache class
