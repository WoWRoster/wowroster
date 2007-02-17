<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/cplang.php
 *
 * This is a class to load and handle language requests. It loads
 * language files into a public container called lang. It's functions
 * are very simple and need not much explaining.
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
 * @author Chris Stockton
 * @version 1.5.0
 * @copyright 2000-2006 Chris Stockton
 * @package cpFramework
 * @subpackage cpLang
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
 * Our language loading class, very simply populats oure lang array
 * @package cpFramework
 */
class cplang
{

	/**
	 * Our objects container holds references to our class instances
	 *
	 * @var array
	 *
	 * @access public
	 */
	public $lang = Array();

	/**
	 * We populate our array on construct
	 *
	 * @return boolean
	 *
	 * @access public
	 */
	public function __construct($_autorun = TRUE)
	{
		if($_autorun === TRUE)
		{
			/**
			 * Build a list of languages to try
			 */
			$langs = array();

			if( cpMain::isClass('cpusers') )
			{
				$langs[] = cpMain::$instance['cpusers']->data['user_lang'];
			}
			$langs[] = cpMain::$instance['cpconfig']->cpconf['def_lang'];
			$langs[] = 'english';

			/**
			 * Try each of them. First one that exists for this module, load and return.
			 */
			foreach( $langs as $lang )
			{
				if( is_file(PATH_LOCAL . 'language' . DIR_SEP . $lang . DIR_SEP . 'modules' . DIR_SEP . 'lang_' . cpMain::$system['method_name'] . '.php') )
				{
					$this->lang = array_merge(
						$this->langLoad(R2_PATH_LANG . $lang . DIR_SEP . 'lang_global.php'),
						$this->langLoad(R2_PATH_LANG . $lang . DIR_SEP . 'modules' . DIR_SEP . 'lang_' . cpMain::$system['method_name'] . '.php'),
						$this->langLoad(R2_PATH_LANG . $lang . DIR_SEP . 'modules' . DIR_SEP . cpMain::$system['method_name'] . DIR_SEP . 'lang_' . cpMain::$system['method_mode'] . '.php')
					);
					return;
				}
			}

			/**
			 * Didn't find a language file; that's not good.. We will not
			 * continue without one if it was requested, as the site may be visualy
			 * impaired or worst bad constructing of the method may result in security
			 * issues.
			 */
			cpMain::cpErrorFatal('cpLang: Failed to load a language. The following languages were tried: '.implode(', ',$langs).'<br />', __LINE__, __FILE__);
		}
	}

	/**
	 * Returns a array of the language definitions. The key being the
	 * name of the variable, the value being the definition. This is
	 * available publicly so you may bypass population of our language
	 * variable and control and manipulate the data within your method
	 * to allow better scalability.
	 *
	 * @param _path name of the file
	 *
	 * @return array Array containing all of the language variables
	 *               Found within the language file.
	 */
	public function langLoad($_path)
	{
		/**
		 * Fail silently if the file doesn't exist
		 */
		if( !is_file($_path) )
		{
			return array();
		}

		$_return = array();

		/**
		* Load the language to our template class
		*/
		foreach(file($_path) as $value)
		{

			/**
			 * We match..
			 * LANG_ANYTHING_HERE_AND_MORE_AND_MORE
			 * LANG_ANYTHING
			 *
			 * NOT:
			 * LANG_ANYTHING_ <== trailing underscore; illegal
			 * ANYTHING_HERE_LANG
			 */
			if(preg_match('/^(LANG_\w+?(?:[A-Z]))\s+(.+)$/', $value, $match))
			{
				$_return[$match[1]] = $match[2];
			}
		}
		return $_return;
	}
}
