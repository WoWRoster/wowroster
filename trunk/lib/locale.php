<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster locale class
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
 * @subpackage Locale
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Roster Locale Class
 *
 * @package    WoWRoster
 * @subpackage Locale
 */
class roster_locale
{
	/**
	 * Array of all localized strings
	 * $roster->locale->wordings[LANG][STRING]
	 * $roster->locale->wordings['enUS']['menu_text']
	 *
	 * @var array
	 */
	var $wordings = array();
	var $creditspage;
	var $langlabel;
	var $curlocale;
	/**
	 * Array of locale strings for current language
	 * Example:
	 * $roster->locale->act['menu_text']
	 *
	 * @var array
	 */
	var $act;

	function roster_locale()
	{
		global $roster;

		include(ROSTER_LOCALE_DIR . 'languages.php');

		$this->creditspage = $creditspage;

		foreach( $roster->multilanguages as $language )
		{
			$this->add_locale_file(ROSTER_LOCALE_DIR . $language . '.php',$language);
		}

		if( isset($_SESSION['locale']) && $_SESSION['locale'] != '' )
		{
			$roster->config['locale'] = $_SESSION['locale'];
		}

		$this->curlocale = $roster->config['locale'];

		$this->act =& $this->wordings[$this->curlocale];
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
				// Do nothing for now. Nothing wrong with an addon not having any of its own localization
				//die_quietly('Could not include locale file [' . $localefile . ']','Locale Inclusion Error',__FILE__,__LINE__);
			}
		}

		if( isset($lang) )
		{
			if( isset($this->wordings[$locale]) )
			{
				$this->wordings[$locale] = array_overlay($lang, $this->wordings[$locale]);
			}
			else
			{
				$this->wordings[$locale] = $lang;
			}

			unset($lang);
		}
	}

	/**
	 * Method to get a locale string using the passed key/addonname/locale
	 * Pass a key to return the desired locale string
	 * If addonname is not passed, it will return $roster->locale->wordings[$locale][$key]
	 * If locale is not passed, it will default to the current locale setting
	 *
	 * @param string $key | Locale string to return
	 * @param string $addon | Addon to search key
	 * @param string $locale | Locale to search key
	 * @return string
	 */
	function get_string( $key , $addon='' , $locale='' )
	{
		if( $locale == '' )
		{
			$locale = $this->curlocale;
		}

		$lang = array();
		if( $addon != '' )
		{
			$localefile = ROSTER_ADDONS . $addon . DIR_SEP . 'locale' . DIR_SEP . $locale . '.php';

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
			}
		}

		if( is_array($key) )
		{
			$array_key = key($key);
			$sub_key = array_pop($key);
			if( isset($lang[$array_key][$sub_key]))
			{
				return $lang[$array_key][$sub_key];
			}
			else
			{
				return $sub_key;
			}
		}
		elseif( isset($lang[$key]) )
		{
			return $lang[$key];
		}
		elseif( isset($this->wordings[$locale][$key]) )
		{
			return $this->wordings[$locale][$key];
		}
		elseif( isset($this->act[$key]) )
		{
			return $this->act[$key];
		}
		else
		{
			return $key;
		}
	}
}
