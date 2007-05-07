<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster locale class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: functions.lib.php 876 2007-05-05 05:19:20Z Zanix $
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

class roster_locale
{
	var $wordings = array();
	var $creditspage = '';

	function roster_locale()
	{
		global $roster;

		include(ROSTER_LOCALE_DIR.'languages.php');

		$this->creditspage = $creditspage;

		foreach( $roster->multilanguages as $language )
		{
			$this->add_locale_file(ROSTER_LOCALE_DIR.$language.'.php',$language);
		}

		$this->act = &$this->wordings[$roster->config['locale']];
	}

	/**
	 * Adds locale strings to global $wordings array
	 *
	 * @param string $localefile | Full path to locale file
	 * @param string $locale | Locale to add to (IE: enUS)
	 */
	function add_locale_file( $localefile , $locale )
	{
		if( file_exists($localefile) )
		{
			include($localefile);
		}
		else
		{
			$enUSfile = str_replace($locale . '.php','enUS.php',$localefile);
			if( file_exists($enUSfile) )
			{
				include($enUSfile);
			}
			else
			{
				die_quietly('Could not include locale file [' . $localefile . ']','Locale Inclusion Error',__FILE__,__LINE__);
			}
		}

		if( isset($lang) )
		{
			if( isset($this->wordings[$locale]) )
			{
				if( isset($lang['admin']) && isset($this->wordings[$locale]['admin']) )
				{
					$admin = array_merge($lang['admin'], $this->wordings[$locale]['admin']);
					$this->wordings[$locale] = array_merge($lang, $this->wordings[$locale]);
					$this->wordings[$locale]['admin'] = $admin;
				}
				else
				{
					$this->wordings[$locale] = array_merge($lang, $this->wordings[$locale]);
				}
			}
			else
			{
				$this->wordings[$locale] = $lang;
			}

			unset($lang);
		}
	}
}
