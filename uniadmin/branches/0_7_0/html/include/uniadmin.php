<?php

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
	var $languages  = array();                  // Availabel Languages      @var languages

	// Output vars
	var $root_path         = './';              // Path to UniAdmin's root  @var root_path
	var $url_path          = '';                // URL Path                 @var url_path

	// Timer vars
	var $timer_start = 0;                       // Page timer start         @var timer_start
	var $timer_end   = 0;                       // Page timer end           @var timer_end

	function uniadmin($url)
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

	function config()
	{
		global $db;

		if ( !is_object($db) )
		{
			ua_die('Database object not instantiated', E_USER_ERROR);
		}

		$sql = 'SELECT `config_name`, `config_value`
				FROM `' . UA_TABLE_CONFIG . '`;';

		if( !($result = $db->query($sql)) )
		{
			ua_die('Could not obtain configuration information', E_USER_ERROR);
		}
		while( $row = $db->fetch_record($result) )
		{
			if( !is_numeric($row['config_name']) )
			{
				$this->config[$row['config_name']] = $row['config_value'];
			}
		}

		$this->config['interface_url'] = str_replace('%url%',$this->url_path,$this->config['interface_url']);

		if ($handle = opendir(UA_LANGDIR))
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
			debug('Cannot read the directory ['.UA_LANGDIR.']');
			die_ua();
		}

		return true;
	}

	function config_set($config_name, $config_value='')
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

	function switch_row_class($set_new = true)
	{
		$row_class = ( $this->row_class == '1' ) ? '2' : '1';

		if ( $set_new )
		{
			$this->row_class = $row_class;
		}

		return $row_class;
	}
}


function debug($debugString)
{
	global $uniadmin;

	$uniadmin->debug[] = $debugString;
}

function message($messageString)
{
	global $uniadmin;

	$uniadmin->messages[] = $messageString;
}

/**
 * Chops a string to the specified length
 *
 * @param string $string
 * @param int $desiredLength
 * @param string $suffix
 * @return string
 */
function stringChop($string, $desiredLength, $suffix)
{
	if (strlen($string) > $desiredLength)
	{
		$string = substr($string,0,$desiredLength).$suffix;
		return $string;
	}
	return $string;
}


?>