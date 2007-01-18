<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/class/cplang.php
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
			 * We set our language to "found"
			 */
			$_found = TRUE;

			/**
			 * Set our language files path.
			 *
			 * No longer a silly complicated ternary condition
			 */
			$_lang = (cpMain::isClass('cpusers'))
				? cpMain::$instance['cpusers']->data['user_lang']
				: cpMain::$instance['cpconfig']->cpconf['def_lang'];
			
			// User language
			$_path = PATH_LOCAL . "library".DIR_SEP."language".DIR_SEP . $_lang . DIR_SEP . cpMain::$system['method_dir'] . DIR_SEP . "lang_" . cpMain::$system['method_mode'] . ".php";
			
			// Fallback: Default language
			if( !is_file($_path) )
			{
				$_path = PATH_LOCAL . "library".DIR_SEP."language".DIR_SEP . cpMain::$instance['cpconfig']->cpconf['def_lang'] . DIR_SEP . cpMain::$system['method_dir'] . DIR_SEP . "lang_" . cpMain::$system['method_mode'] . ".php";
			}
			
			// Fallback: English
			if( !is_file($_path) )
			{
				$_path = PATH_LOCAL . "library".DIR_SEP."language".DIR_SEP . "english" . DIR_SEP . cpMain::$system['method_dir'] . DIR_SEP . "lang_" . cpMain::$system['method_mode'] . ".php";
			}

			/**
			 * Didn't find a language file; that's not good.. We will not
			 * continue without one if it was requested, as the site may be visualy
			 * impaired or worst bad constructing of the method may result in security
			 * issues.
			 */
			if( !is_file($_path) )
			{
				cpMain::cpErrorFatal("Please consult the manual to see the proper directory hiearchy and system functionality. The path the system was looking for (or at least 1 of the paths we checked) is: " . $_path . "<br />", __LINE__, __FILE__);
			}

			/**
			 * Load the language into our lang container
			 */
			$this->lang = self::langLoad($_path);

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

		$_return = Array();

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
