<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Template Parser
 * Modified from the EQDkp Project
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: $
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage Template
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

class Template
{
	// variable that holds all the data we'll be substituting into
	// the compiled templates. Takes form:
	// --> $this->_tpldata[block.][iteration#][child.][iteration#][child2.][iteration#][variablename] == value
	// if it's a root-level variable, it'll be like this:
	// --> $this->_tpldata[.][0][varname] == value
	var $_tpldata = array();

	// Root dir and hash of filenames for each template handle.
	var $tpl = '';
	var $root = '';
	var $files = array();

	// this will hash handle names to the compiled/uncompiled code for that handle.
	var $compiled_code = array();

	function Template()
	{
		global $roster;

		if( !is_dir(ROSTER_TPLDIR . 'default') )
		{
			trigger_error("'default' theme does not exist", E_USER_ERROR);
		}

		if( is_dir(ROSTER_TPLDIR . $roster->config['theme']) )
		{
			$this->tpl = $roster->config['theme'];
		}
		else
		{
			$this->tpl = 'default';
		}
		$this->_tpldata['.'][0]['REQUEST_URI'] = str_replace('&', '&amp;', substr(request_uri(),strlen(ROSTER_PATH)));
		$this->root = 'templates/' . $this->tpl;
	}

	// Sets the template filenames for handles. $filename_array
	// should be a hash of handle => filename pairs.
	function set_filenames( $filename_array )
	{
		if( !is_array($filename_array) )
		{
			return false;
		}
		foreach( $filename_array as $handle => $filename )
		{
			$this->set_handle($handle, $filename);
		}
		return true;
	}

	function set_handle( $handle , $filename )
	{
		if( empty($handle) )
		{
			trigger_error('template error - No handlename specified', E_USER_ERROR);
		}
		if( empty($filename) )
		{
			trigger_error("template error - Empty filename specified for $handle", E_USER_ERROR);
		}
		$this->filename[$handle] = $filename;
		$this->files[$handle] = $this->root . '/' . $filename;
	}

	// Destroy template data set
	function destroy( )
	{
		$this->_tpldata = array();
	}

	// Methods for loading and evaluating the templates
	function display( $handle , $include_once = true )
	{
		if ($filename = $this->_tpl_load($handle))
		{
			($include_once) ? include_once($filename) : include($filename);
		}
		else
		{
			eval(' ?>'.$this->compiled_code[$handle].'<?php ');
		}
		return true;
	}

	function assign_var_from_handle( $varname , $handle , $include_once = true )
	{
		ob_start();
		$valid = $this->display($handle, $include_once);
		$varval = ob_get_contents();
		ob_end_clean();
		if( $valid )
		{
			$this->assign_var($varname, $varval);
		}
		return $valid;
	}

	// Load a compiled template if possible, if not, recompile it
	function _tpl_load( &$handle )
	{
		// If we don't have a file assigned to this handle, die.
		if (!isset($this->files[$handle]))
		{
			trigger_error("template->_tpl_load(): No file specified for handle $handle", E_USER_ERROR);
		}

		if (!file_exists(ROSTER_BASE.$this->files[$handle]))
		{
			trigger_error('template->_tpl_load(): '.($this->files[$handle]).' does not exist', E_USER_NOTICE);
			$this->files[$handle] = 'templates/default/'.$this->filename[$handle];
			$this->_tpldata['.'][0]['THEME_PATH'] = 'templates/default';
			$this->cachepath = 'cache/tpl_default_';
			if( !file_exists(ROSTER_BASE.$this->files[$handle]) && $pos = strpos($this->filename[$handle], '/') && is_dir(ROSTER_BASE.'addons/'.substr($this->filename[$handle],0,$pos).'/templates') )
			{
				$this->files[$handle] = 'addons/'.substr($this->filename[$handle],0,$pos).'/templates/'.substr($this->filename[$handle],$pos+1);
				$this->_tpldata['.'][0]['THEME_PATH'] = 'addons/'.substr($this->filename[$handle],0,$pos);
			}
		}
		else
		{
			$this->_tpldata['.'][0]['THEME_PATH'] = 'templates/'.$this->tpl;
			$this->cachepath = 'cache/tpl_'.$this->tpl.'_';
		}

		$filename = ereg_replace('/', '#', $this->filename[$handle]);
		$filename = $this->cachepath.$filename.'.inc';

		// Don't recompile page if the original template is older then the compiled cache
		if( file_exists($filename) && filemtime($filename) > filemtime($this->files[$handle]) )
		{
			return $filename;
		}

		$this->_tpl_load_file($handle);
		return false;
	}

	// Load template source from file
	function _tpl_load_file( $handle )
	{
		// Try and open template for read
		if( !($fp = fopen(ROSTER_BASE.$this->files[$handle], 'r')) )
		{
			trigger_error("template->_tpl_load_file(): File ".$this->files[$handle]." does not exist or is empty", E_USER_ERROR);
		}
		require_once(ROSTER_LIB.'template_enc.php');
		$this->compiled_code[$handle] = tpl_encode::compile(trim(fread($fp, filesize($this->files[$handle]))));
		fclose($fp);
		// Actually compile the code now.
		tpl_encode::compile_write($handle, $this->compiled_code[$handle]);
	}

	// Assign key variable pairs from an array
	function assign_vars( $vararray )
	{
		foreach( $vararray as $key => $val )
		{
			$this->_tpldata['.'][0][$key] = $val;
		}
		return true;
	}

	// Assign a single variable to a single key
	function assign_var( $varname , $varval )
	{
		$this->_tpldata['.'][0][$varname] = $varval;
		return true;
	}

	// Assign key variable pairs from an array to a specified block
	function assign_block_vars( $blockname , $vararray )
	{
		if( strstr($blockname, '.') )
		{
			// Nested block.
			$blocks = explode('.', $blockname);
			$blockcount = count($blocks) - 1;
			$str = &$this->_tpldata;
			for( $i = 0; $i < $blockcount; $i++ )
			{
				$str = &$str[$blocks[$i]];
				$str = &$str[count($str) - 1];
			}
			// Now we add the block that we're actually assigning to.
			// We're adding a new iteration to this block with the given
			// variable assignments.
			$str[$blocks[$blockcount]][] = &$vararray;
		}
		else
		{
			// Top-level block.
			// Add a new iteration to this block with the variable assignments
			// we were given.
			$this->_tpldata[$blockname][] = &$vararray;
		}
		return true;
	}

	function unset_block( $blockname )
	{
		if( strstr($blockname, '.') )
		{
			trigger_error('It\'s only allowed to unset toplevel blocks', E_USER_ERROR);
		}
		if( isset($this->_tpldata[$blockname]) )
		{
			unset($this->_tpldata[$blockname]);
		}
		return true;
	}

	// Include a seperate template
	function _tpl_include( $filename , $include = true )
	{
		$handle = $filename;
		$this->filename[$handle] = $filename;
		$this->files[$handle] = $this->root . '/' . $filename;
		$filename = $this->_tpl_load($handle);
		if( $include )
		{
			if( $filename )
			{
				include_once($filename);
				return;
			}
			eval(' ?>' . $this->compiled_code[$handle] . '<?php ');
		}
	}
}
