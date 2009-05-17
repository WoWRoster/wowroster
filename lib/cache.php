<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Cache Class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCache
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Cache Class
 *
 * @package    WoWRoster
 * @subpackage RosterCache
 */
class RosterCache
{
	var $cache_suffix;
	var $object_ttl;
	var $sql_ttl;
	var $cache_dir;
	var $cache_data = array();

	//	var $sql_link_id;
	//	var $sql_query;
	//	var $sql_cache_data;
	//	var $sql_cache_rows=-1;


	/**
	 * Constructor
	 *
	 * @return cache
	 */
	function RosterCache( )
	{
		$this->cache_suffix = '.inc';
		$this->object_ttl = '10800'; //3 hours
		$this->sql_ttl = '60';
		$this->cache_dir = ROSTER_CACHEDIR;
	}

	/**
	 * Maint function to clean cache directory
	 * optional prefix arg
	 *
	 * @param string $prefix
	 */
	function cleanCache( $prefix = null )
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

	function mcheck( $cache_name )
	{
		$cache_name = md5($cache_name);

		if( isset($this->cache_data[$cache_name]) )
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
			return $this->_readCache($cache_file);
		}
		else
		{
			return false;
		}
	}

	function mget( $cache_name )
	{
		$cache_name = md5($cache_name);

		return $this->cache_data[$cache_name];
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
	function put( $data , $cache_file = false )
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

	function mput( $data , $cache_name )
	{
		$cache_name = md5($cache_name);
		$this->cache_data[$cache_name] = $data;
		return;
	}

	function sqlFetch( )
	{
		if( !empty($this->sql_cache_data) )
		{
			$this->sql_cache_rows--;
			return array_shift($this->sql_cache_data);
		}
		else
		{
			return false;
		}
	}

	/**
	 * preps the cache object to handle requests on cached object
	 *
	 * @param unknown_type $sql
	 * @param unknown_type $link_id
	 * @return unknown
	 */
	function sqlCache( $sql , $link_id )
	{
		global $roster;

		$cache_file = $this->cache_dir . 'sql_' . md5($sql) . $this->cache_suffix;

		$this->sql_link_id = $link_id;
		$this->sql_query = $sql;
		$this->sql_cache_rows = -1;

		if( file_exists($cache_file) && filemtime($cache_file) > (time() - $this->sql_ttl) )
		{
			echo "Cache Hit!<br>";
			$this->sql_cache_data = $this->_readCache($cache_file);
			$this->sql_cache_rows = count($this->sql_cache_data);
		}
		else
		{
			echo "Cache Created!<br>";
			$result = $roster->db->query($sql, $link_id);

			$data = array();
			while( $data = $roster->db->fetch($result) )
			{
				$this->sql_cache_data[] = $data;
			}
			$this->_writeCache($this->sql_cache_data, $cache_file);
			$this->sql_cache_rows = count($this->sql_cache_data);
			unset($data);
		}
		return md5($sql);
	}

	/**
	 * writes serialized $data to $cache_file
	 *
	 * @param mixed $data
	 * @param string $cache_file
	 * @return bool
	 */
	function _writeCache( $data , $cache_file )
	{
		if( !$file = fopen($cache_file, 'w') )
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
	function verifyCacheDirectory( )
	{
		$cache_file = $this->cache_dir . md5('cache_test') . $this->cache_suffix;

		if( !$file = fopen($cache_file, 'w') )
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
			flock($file, LOCK_UN);
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
