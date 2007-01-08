<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/class/cpconfig.php
 *
 * This is a class to load and handle config settings
 *
 * -http://en.wikipedia.org/wiki/Modular
 *
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Legal Information:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 *
 * Full License:
 *  license.txt (Included within this library)
 *
 * You should have recieved a FULL copy of this license in license.txt
 * along with this library, if you did not and you are unable to find
 * and agree to the license you may not use this library.
 *
 * For questions, comments, information and documentation please visit
 * the official website at cpframework.org
 *
 * @link http://cpframework.org
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author WoWRoster.net
 * @version 1.5.0
 * @copyright 2000-2006 WoWRoster.net
 * @package cpFramework
 * @filesource
 *
 * Roster versioning tag
 * $Id$
 */

/**
 * Our security measure, present in any file which does not contain
 * a direct access to our config itself. This is a security measure.
 */
if(!defined('SECURITY'))
{
	die("You may not access this file directly.");
}

/**
 * Our configuration management class. The config data is in data/config
 * @package cpFramework
 */
class cpConfig
{
	/**
	 * Stores configuration data after loading it
	 */
	private $config = array();

	/**
	 * Config directory
	 */
	private $cfgdir;

	/**
	 * Constructor. Set config var here.
	 */
	public function __construct()
	{
		$this->cfgdir = PATH_LOCAL.'data'.DIR_SEP.'config'.DIR_SEP;
	}

	/**
	 * Load a config file into the class
	 *
	 * @param $name 	name of the config file
	 * @param $user 	user name if we want to fetch the config user-specifically.
	 */
	public function loadConfig($name, $user = '')
	{
		/**
		 * Check for invalid config filenames
		 */
		if(preg_match('/[^a-zA-Z0-9_\-]/', $name))
		{
			throw new cpException("Invalid characters in config filename, match [a-zA-Z0-9_\-]+ when naming files.");
		}

		/**
		 * Check for invalid user names
		 */
		if(preg_match('/[^a-zA-Z0-9_\-]/', $user))
		{
			throw new cpException("Invalid characters in user name, match [a-zA-Z0-9_\-]+ when naming users.");
		}

		// Set up the spots where the file may be
		$places = array();

		if( $user != '')
		{
			$userdir = $user.DIRECTORY_SEPARATOR;
			$places[] = $this->cfgdir.$userdir.$name.'.php';
			$places[] = $this->cfgdir.$userdir.$name.'.bak.php';
		}
		$places[] = $this->cfgdir.$name.'.php';
		$places[] = $this->cfgdir.$name.'.bak.php';
		$places[] = $this->cfgdir.$name.'.def.php';

		// Check the spots in order for the file location
		$config_loaded = false;

		while( !$config_loaded && count($places) )
		{
			$place = array_shift($places);
			if( file_exists($place) )
			{
				require($place);
				$config_loaded = true;
			}
		}

		if( !$config_loaded )
		{
			throw new cpException("Tried to load config file with name ".$name." for user ".$user." but the file could not be found.");
		}

		$this->config[$name] = $config;
	}

	/**
	 * Get a config page
	 *
	 * @param $name 	the variable name requested on this class; also the config page to load
	 */
	public function __get($name)
	{
		return (isset($this->config[$name]))?$this->config[$name]:NULL;
	}

	/**
	 * Read config metadata by parsing the config file
	 *
	 * @param $name 	name of the config file
	 * @param $user 	user name if we want to fetch for a specific user
	 */
	public function loadConfigMeta($name, $user='')
	{
		/**
		 * Check for invalid config filenames
		 */
		if(preg_match('/[^a-zA-Z0-9_\-]/', $name))
		{
			throw new cpException("Invalid characters in config filename, match [a-zA-Z0-9_\-]+ when naming files.");
		}

		/**
		 * Check for invalid user names
		 */
		if(preg_match('/[^a-zA-Z0-9_\-]/', $user))
		{
			throw new cpException("Invalid characters in user name, match [a-zA-Z0-9_\-]+ when naming users.");
		}

		// Now that that's done, let's make sure the active config for this page is loaded
		$this->loadConfig($name, $user);

		// We'll load the metadata from the defaults file
		if( !file_exists($this->cfgdir.$name.'.def.php') )
		{
			throw new cpException("Config defaults file for ".$name." doesn't exist.");
		}
		$file = file_get_contents($this->cfgdir.$name.'.def.php');

		// We've got the whole file in $file now. So we'll split it into the individual
		// settings, and throw away the header.
		$settings = explode("\n#'",$file);
		array_shift($settings);


		foreach( $settings as $setting )
		{
			// Get rid of any spaces at the beginning or the end
			$setting = trim($setting);
			// Get the name
			$option = substr($setting, 0, strpos($setting,' '));
			// Get the metadata
			$config[$option]['metaraw'] = trim(substr($setting, 0, ($em = strpos($setting,"\n"))));
			// Explode the metadata
			$meta = explode(' ',$config[$option]['metaraw']);
			array_shift($meta);
			foreach( $meta as $metavar )
			{
				list($prop, $var) = explode('=',$metavar);
				if( substr($prop,-2,2) == '[]' )
				{
					$config[$option]['meta'][substr($prop,0,-2)][] = trim($var);
				}
				else
				{
					$config[$option]['meta'][$prop] = trim($var);
				}
			}
			// Get comment lines
			$config[$option]['comment'] = '';
			while( substr($setting, $em+1, 2) == '//' )
			{
				$config[$option]['comment'] .= substr($setting, $em+3, -($em+3) + ($em = strpos($setting,"\n",$em+3)))."\n";
			}
			$config[$option]['comment'] = trim($config[$option]['comment']);
			// Calculate the start position for the value
			$vs = $em + strlen($option) + 15;
			// Store the value
			$valuestr = substr($setting, $vs, -1);
			$config[$option]['default'] = eval('return '.$valuestr.';');
			// Fetch the current setting
			if( isset($this->config[$name][$option]) )
			{
				$config[$option]['value'] = $this->config[$name][$option];
			}
		}

		return $config;
	}

	/**
	 * Write config data
	 *
	 * @param $name 	name of the config file
	 * @param $config 	configuration data, name=>value
	 *					Can be any data type, even object!
	 * @param $user 	user name if we want to write a user-specific config file
	 */
	public function writeConfig($name, $config, $user='')
	{
		$meta = $this->loadConfigMeta($name, $user);

		$file = <<<ENDHEADER
<?php
/**
 * Roster config file
 */

/**
 * Our security measure, present in any file which does not contain
 * a direct access to our config itself. This is a security measure.
 */
if(!defined('SECURITY'))
{
	die("You may not access this file directly.");
}

ENDHEADER;

		foreach( $meta as $option => $info )
		{
			if( !isset($config[$option] ) )
			{
				$config[$option] = isset($info['value'])?$info['value']:$info['default'];
			}
			$metaline = "#' ".$info['metaraw']."\n";
			$comments =	empty($meta[$option]['comment'])?'':'//'.str_replace("\n","\n//",$meta[$option]['comment'])."\n";
			$code = '$config[\''.$option.'\'] = '.var_export($config[$option],true).';'."\n";

			$file .= $metaline.$comments.$code;
		}

		if( !empty($user) )
		{
			$userdir = $user.DIR_SEP;
			if( !is_dir($this->cfgdir.$user) && !mkdir($this->cfgdir.$user) )
			{
				throw new cpException("Config directory for ".$user." does not exist and I could not create one");
			}
		}
		else
		{
			$userdir = '';
		}
		$filedest = $this->cfgdir.$userdir.$name.'.php';
		$filebak = $this->cfgdir.$userdir.$name.'.bak.php';

		if( file_exists($filedest) && !copy($filedest, $filebak) )
		{
			throw new cpException("Failed to copy existing config file for ".$name);
		}

		$fp = fopen($filedest,'w');
		fwrite($fp, $file);
		fclose($fp);
	}
}
