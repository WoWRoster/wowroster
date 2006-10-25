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
	var $row_class  = 1;                        // Alternating row class    @var row_class
	var $menu       = '';                       // Main UA Menu             @var menu
	var $messages   = array();                  // Messages array           @var messages
	var $debug      = array();                  // Debug messages array     @var debug
	var $languages  = array();                  // Available Languages      @var languages
	var $styles  = array();                     // Available Styles         @var styles
	var $reject_ini = array();                  // ini variable to not scan @var reject_ini

	// Output vars
	var $root_path         = './';              // Path to UniAdmin's root  @var root_path
	var $url_path          = '';                // URL Path                 @var url_path
    var $gen_simple_header = false;             // Use a simple header?     @var gen_simple_header
    var $page_title        = '';                // Page title               @var page_title
    var $template_file     = '';                // Template file to parse   @var template_file
    var $template_path     = '';                // Path to template_file    @var template_path

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

		// Get languages
		if( $handle = opendir(UA_LANGDIR) )
		{
			while( false !== ($file = readdir($handle)) )
			{
				if( $file != '.' && $file != '..' && $file != '.svn' && !is_dir(UA_LANGDIR.$file) )
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

		// Get styles
		if( $handle = opendir(UA_THEMEDIR) )
		{
			while( false !== ($file = readdir($handle)) )
			{
				if( $file != '.' && $file != '..' && $file != '.svn' && is_dir(UA_THEMEDIR.$file) )
				{
					$this->styles[] = $file;
				}
			}
		}
		else
		{
			print('Cannot read the directory ['.UA_THEMEDIR.']');
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
		$row_class = ( $this->row_class == 1 ) ? 2 : 1;

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

	/**
	 * Set object variables
	 * NOTE: If the last var is 'display' and the val is TRUE, EQdkp::display() is called
	 *   automatically
	 *
	 * @var $var Var to set
	 * @var $val Value for Var
	 * @return bool
	 */
	function set_vars($var, $val = '', $append = false)
	{
		if ( is_array($var) )
		{
			foreach ( $var as $d_var => $d_val )
			{
				$this->set_vars($d_var, $d_val);
			}
		}
		else
		{
			if ( empty($val) )
			{
				return false;
			}
			if ( ($var == 'display') && ($val === true) )
			{
				$this->display();
			}
			else
			{
				if ( $append )
				{
					if ( is_array($this->$var) )
					{
						$this->{$var}[] = $val;
					}
					elseif ( is_string($this->$var) )
					{
						$this->$var .= $val;
					}
					else
					{
						$this->$var = $val;
					}
				}
				else
				{
					$this->$var = $val;
				}
			}
		}

		return true;
	}

	function display()
	{
		$this->page_header();
		$this->page_tail();
	}

	function page_header()
	{
		global $uniadmin, $db, $user, $tpl;

		// Define a variable so we know the header's been included
		define('HEADER_INC', true);

		// Use gzip if available
		if ( $this->config['enable_gzip'] == '1' )
		{
			if ( (extension_loaded('zlib')) && (!headers_sent()) )
			{
				@ob_start('ob_gzhandler');
			}
		}

		// Send the HTTP headers
		$now = gmdate('D, d M Y H:i:s', time()) . ' GMT';

		@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		@header('Last-Modified: ' . $now);
		@header('Cache-Control: no-store, no-cache, must-revalidate');
		@header('Cache-Control: post-check=0, pre-check=0', false);
		@header('Pragma: no-cache');
		@header('Content-Type: text/html; charset=iso-8859-1');

		// Assign global template variables
		$tpl->assign_vars(array(
			'SUB_TITLE'       => $this->page_title,
			'URL_PATH'        => $this->url_path,
			'TEMPLATE_PATH'   => $this->url_path . 'styles/' . $user->style,
			'UA_VER'          => UA_VER,
			'UA_FORMACTION'   => UA_FORMACTION,
			'UA_INDEXPAGE'    => UA_INDEXPAGE,
			'UA_INDEX'        => UA_INDEX,
			'U_INTERFACE_URL' => $uniadmin->config['interface_url'],

			'A_URI_PAGE'       => UA_URI_PAGE,
			'A_OPERATION'      => UA_URI_OP,
			'A_DELETE'         => UA_URI_DELETE,
			'A_ID'             => UA_URI_ID,
			'A_PROCESS'        => UA_URI_PROCESS,
			'A_ADD'            => UA_URI_ADD,
			'A_SVNAME'         => UA_URI_SVNAME,
			'A_UPINI'          => UA_URI_UPINI,
			'A_GETINI'         => UA_URI_GETINI,
			)
		);

		$tpl->assign_vars(array(
			'L_SYNCRO_URL' => $user->lang['syncro_url'],
			'L_VER_SYNCRO_URL' => $user->lang['verify_syncro_url'],
			'L_MESSAGES' => $user->lang['messages'],
			'L_DEBUG' => $user->lang['debug'],
			)
		);

		$tpl->assign_vars(array(
			'S_NORMAL_HEADER' => false,
			'S_MESSAGE'       => false,
			'S_DEBUG'         => false,
			)
		);


		//
		// Messages
		//
		if( !empty($this->messages) && is_array($this->messages) )
		{
			$tpl->assign_var('S_MESSAGE',true);
			foreach( $this->messages as $message )
			{
				$tpl->assign_block_vars('messages_row',
					array('TEXT'    => $message,
						'ROW_CLASS' => $uniadmin->switch_row_class(),
					)
				);
			}
		}

		//
		// Debug
		//
		if( !empty($this->debug) && is_array($this->debug) && UA_DEBUG )
		{
			$tpl->assign_var('S_DEBUG',true);
			foreach( $this->debug as $message )
			{
				$tpl->assign_block_vars('debug_row',
					array('TEXT'    => $message,
						'ROW_CLASS' => $uniadmin->switch_row_class(),
					)
				);
			}
		}

		//
		// Menus
		//
		$menus = $this->gen_menus();
		$main_menu = '';

		foreach ( $menus as $menu )
		{
			// Don't display the link if they don't have permission to view it
			if ( (empty($menu['check'])) || (isset($user->data['level']) && $user->data['level'] >= $menu['check']) )
			{
				$main_menu .= '<a href="' . UA_INDEXPAGE . '=' . $menu['link'] . '">' . $menu['text'] . '</a> | ';
			}
		}

		// Remove the trailing ' | ' from menus
		$main_menu = preg_replace('# \| $#', '', $main_menu);

		if ( !$this->gen_simple_header )
		{
			$tpl->assign_vars(array(
				'LOGO' => $user->style['logo_path'],

				'S_NORMAL_HEADER' => true,
				'S_LOGGED_IN' => ( isset($user->data['level']) ? ( ( $user->data['level'] != UA_ID_ANON ) ? true : false) : false),

				// Menu
				'MAIN_MENU' => $main_menu)
			);
		}
	}

	function gen_menus()
	{
		global $user;

		$main_menu = array(
			array('link' => 'help',     'text' => $user->lang['title_help'],     'check' => ''),
			array('link' => 'addons',   'text' => $user->lang['title_addons'],   'check' => ''),
			array('link' => 'logo',     'text' => $user->lang['title_logo'],     'check' => ''),
			array('link' => 'settings', 'text' => $user->lang['title_settings'], 'check' => ''),
			array('link' => 'stats',    'text' => $user->lang['title_stats'],    'check' => '1'),
			array('link' => 'users',    'text' => $user->lang['title_users'],    'check' => '1'),
			array('link' => 'pref',     'text' => $user->lang['title_config'],   'check' => '3'),
		);

		return $main_menu;
	}

	function page_tail()
	{
		global $db, $user, $tpl;

		if ( !empty($this->template_path) )
		{
			$tpl->set_template($user->style['template_path'], $this->template_path);
		}

		if ( empty($this->template_file) )
		{
			trigger_error('Template file is empty.', E_USER_ERROR);
			return false;
		}

		$tpl->set_filenames(array(
			'body' => $this->template_file
			)
		);

		// Hiding the copyright/debug info if gen_simple_header is set
		if ( !$this->gen_simple_header )
		{
			$tpl->assign_vars(array(
				'S_NORMAL_FOOTER' => true
				)
			);

			if ( UA_DEBUG )
			{
				$mc_split = split(' ', microtime());
				$this->timer_end = $mc_split[0] + $mc_split[1];
				unset($mc_split);

				$s_show_queries = ( UA_DEBUG == 2 ) ? true : false;

				$tpl->assign_vars(array(
					'L_QUERIES'      => $user->lang['queries'],
					'S_SHOW_DEBUG'   => true,
					'S_SHOW_QUERIES' => $s_show_queries,
					'U_RENDERTIME'   => substr($this->timer_end - $this->timer_start, 0, 5),
					'U_QUERYCOUNT'   => $db->query_count)
				);

				if ( $s_show_queries )
				{
					foreach ( $db->queries as $query )
					{
						$tpl->assign_block_vars('query_row', array(
						'ROW_CLASS' => $this->switch_row_class(),
						'QUERY' => $query)
						);
					}
				}
			}
			else
			{
				$tpl->assign_vars(array(
					'S_SHOW_DEBUG' => false,
					'S_SHOW_QUERIES' => false)
				);
			}
		}
		else
		{
			$tpl->assign_vars(array(
				'S_NORMAL_FOOTER' => false)
			);
		}

		// Close our DB connection.
		$db->close_db();

		// Get rid of our template data
		$tpl->display('body');
		$tpl->destroy();

		exit;
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
 * Function to generate the styles select box
 *
 * @param string $select_option
 * @return string
 */
function style_select( $select_option='' )
{
	global $uniadmin;

	if( empty($select_option) )
	{
		$select_option = $uniadmin->config['default_style'];
	}

	$retval = '<select class="select" name="style">';

	foreach( $uniadmin->styles as $style )
	{
		$selected = ( $style == $select_option ? ' selected="selected"' : '' );
		$retval .= "\n\t".'<option value="'.$style.'"'.$selected.'>'.$style.'</option>';
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