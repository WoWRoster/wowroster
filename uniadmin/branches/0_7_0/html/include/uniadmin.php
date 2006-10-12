<?php
/******************************
 * WoWRoster.net  UniAdmin
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if( !defined('IN_UNIADMIN') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Available to all pages as $uniadmin
 */

class UniAdmin
{
	// General vars
	var $config     = array();                  // Config values            @var config
	var $row_class  = 'data1';                  // Alternating row class    @var row_class
	var $menu       = '';                       // Main UA Menu             @var menu
	var $messages   = array();                  // Messages array           @var messages
	var $debug      = array();                  // Debug messages array     @var debug
	var $languages  = array();                  // Available Languages      @var languages
	var $reject_ini = array();                  // ini variable to not scan @var reject_ini

	// Output vars
	var $root_path         = './';              // Path to UniAdmin's root  @var root_path
	var $url_path          = '';                // URL Path                 @var url_path

	// Timer vars
	var $timer_start = 0;                       // Page timer start         @var timer_start
	var $timer_end   = 0;                       // Page timer end           @var timer_end

	/**
	 * UniAdmin constructor
	 *
	 * @param string $url
	 */
	function uniadmin( $url )
	{
		// Start a script timer if we're debugging
		if( UA_DEBUG )
		{
			$mc_split = split(' ', microtime());
			$this->timer_start = $mc_split[0] + $mc_split[1];
			unset($mc_split);
		}

		$this->root_path = UA_BASEDIR;
		$this->url_path = $url;

		$this->config();
	}

	/**
	 * Get and store config options for UniAdmin
	 *
	 * @return bool
	 */
	function config( )
	{
		global $db;

		if( !is_object($db) )
		{
			die('Database object not initialized');
		}

		$sql = 'SELECT `config_name`, `config_value`
				FROM `' . UA_TABLE_CONFIG . '`;';

		if( !($result = $db->query($sql)) )
		{
			die('Could not obtain configuration information');
		}
		while( $row = $db->fetch_record($result) )
		{
			if( !is_numeric($row['config_name']) )
			{
				$this->config[$row['config_name']] = $row['config_value'];
			}
		}

		// Fix interface url
		$this->config['interface_url'] = str_replace('%url%',$this->url_path,$this->config['interface_url']);

		if( $handle = opendir(UA_LANGDIR) )
		{
			while( false !== ($file = readdir($handle)) )
			{
				if( $file != '.' && $file != '..' && !is_dir(UA_LANGDIR.$file) )
				{
					$this->languages[] = substr($file,0,-4);
				}
			}
		}
		else
		{
			print('Cannot read the directory ['.UA_LANGDIR.']');
			die();
		}

		$this->reject_ini = explode(',',UA_REJECT_INI);

		return true;
	}

	/**
	 * Set a config option
	 *
	 * @param string $config_name
	 * @param string $config_value
	 * @return bool
	 */
	function config_set( $config_name , $config_value='' )
	{
		global $db;

		if( is_object($db) )
		{
			if( is_array($config_name) )
			{
				foreach ( $config_name as $d_name => $d_value )
				{
					$this->config_set($d_name, $d_value);
				}
			}
			else
			{
				$sql = 'UPDATE `' . UA_TABLE_CONFIG . "`
						SET `config_value` = '".strip_tags(htmlspecialchars($config_value))."'
						WHERE `config_name` = '".$config_name."';";
				$db->query($sql);

				return true;
			}
		}
		return false;
	}

	/**
	 * Switches the class for row coloring
	 *
	 * @param bool $set_new
	 * @return int
	 */
	function switch_row_class( $set_new = true )
	{
		$row_class = ( $this->row_class == '1' ) ? '2' : '1';

		if( $set_new )
		{
			$this->row_class = $row_class;
		}

		return $row_class;
	}

	/**
	 * Adds a debug message for diaply
	 *
	 * @param string $debug_string
	 */
	function debug( $debug_string )
	{
		global $uniadmin;

		$uniadmin->debug[] = $debug_string;
	}

	/**
	 * Adds a message for display
	 *
	 * @param string $message_string
	 */
	function message( $message_string )
	{
		global $uniadmin;

		$uniadmin->messages[] = $message_string;
	}

	/**
	 * Unzips a zip file
	 *
	 * @param string $file
	 * @param string $path
	 */
	function unzip( $file , $path )
	{
		global $user;

		require_once(UA_INCLUDEDIR.'pclzip.lib.php');

		$archive = new PclZip($file);
		$list = $archive->extract(PCLZIP_OPT_PATH, $path); //removed PCLZIP_OPT_REMOVE_ALL_PATH to preserve file structure
		if ($list == 0)
		{
			$try_unlink = @unlink($file);
			if( !$try_unlink )
			{
				$this->debug(sprintf($user->lang['error_unlink'],$file));
			}
			$this->debug(sprintf($user->lang['error_pclzip'],$archive->errorInfo(true)));
			die_ua();
		}
		unset($archive);
	}

	/**
	 * Figures out what the file's extention is
	 *
	 * @param string $filename
	 * @return string
	 */
	function get_file_ext( $filename )
	{
		return strtolower(ltrim(strrchr($filename,'.'),'.'));
	}

	/**
	 * Chops a string to the specified length
	 *
	 * @param string $string
	 * @param int $desiredLength
	 * @param string $suffix
	 * @return string
	 */
	function string_chop( $string , $desired_length , $suffix )
	{
		if( strlen($string) > $desired_length )
		{
			$string = substr($string,0,$desired_length).$suffix;
			return $string;
		}
		return $string;
	}

	/**
	 * Lists the contents of a directory
	 *
	 * @param string $dir
	 * @param array $array
	 * @return array
	 */
	function ls( $dir , $array )
	{
		$handle = opendir($dir);
		for(;(false !== ($readdir = readdir($handle)));)
		{
			if( $readdir != '.' && $readdir != '..' && $readdir != 'index.htm' && $readdir != 'index.html' && $readdir != '.svn' )
			{
				$path = $dir.DIR_SEP.$readdir;
				if( is_dir($path) )
				{
					$array = $this->ls($path, $array);
				}
				if( is_file($path) )
				{
					$array[count($array)] = $path;
				}
			}
		}
		closedir($handle);
		return $array;
	}

	/**
	 * Removes a file or directory
	 *
	 * @param string $dir
	 * @return bool
	 */
	function rmdirr( $dir )
	{
		if( is_dir($dir) && !is_link($dir) )
		{
			return ( $this->cleardir($dir) ? rmdir($dir) : false );
		}
		return unlink($dir);
	}

	/**
	 * Clears a directory of files
	 *
	 * @param string $dir
	 * @return bool
	 */
	function cleardir( $dir )
	{
		global $uniadmin;

		if( !($dir = dir($dir)) )
		{
			return false;
		}
		while( false !== $item = $dir->read() )
		{
			if( $item != '.' && $item != '..' && $item != '.svn' && $item != 'index.html' && $item != 'index.htm' && !$this->rmdirr($dir->path . DIR_SEP . $item) )
			{
				$dir->close();
				return false;
			}
		}
		$dir->close();
		return true;
	}
}

/**
 * Function to generate the languages select box
 *
 * @param string $select_option
 * @return string
 */
function lang_select( $select_option='' )
{
	global $uniadmin;

	if( empty($select_option) )
	{
		$select_option = $uniadmin->config['default_lang'];
	}

	$retval = '<select class="select" name="language">';

	foreach( $uniadmin->languages as $lang )
	{
		$selected = ( $lang == $select_option ? ' selected="selected"' : '' );
		$retval .= "\n\t".'<option value="'.$lang.'"'.$selected.'>'.$lang.'</option>';
	}
	$retval .= '
			</select>';
	return $retval;
}


/**
 * Function to generate the access level select box
 *
 * @param string $select_option
 * @return string
 */
function level_select( $select_option='' )
{
	global $uniadmin, $user;

	$selected = ' selected="selected"';

	$retval = '<select class="select" name="level">
	<option value="'.UA_ID_USER.'"'.( UA_ID_USER == $select_option ? $selected : '' ).'>'.$user->lang['basic_user_level_1'].'</option>
	<option value="'.UA_ID_POWER.'"'.( UA_ID_POWER == $select_option ? $selected : '' ).'>'.$user->lang['power_user_level_2'].'</option>
	<option value="'.UA_ID_ADMIN.'"'.( UA_ID_ADMIN == $select_option ? $selected : '' ).'>'.$user->lang['admin_level_3'].'</option>
</select>';

	return $retval;
}

?>