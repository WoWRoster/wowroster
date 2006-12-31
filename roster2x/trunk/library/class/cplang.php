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
			 * Note, I understand this is kind of a big silly ternary
			 * condition, ill probably make a not silly if else statement
			 * in the future but i've added carriage returns to make it
			 * more obvious what it's doing.
			 */
			$_path =
			(
				(
					(is_file
						($var = PATH_LOCAL . "library".DIRECTORY_SEPERATOR."language".DIRECTORY_SEPERATOR .
							(is_object
								(
									(isset(cpMain::$instance['cpusers'])
										? cpMain::$instance['cpusers']
										: NULL
									)
								)
							  ? cpMain::$instance['cpusers']->data['user_lang']
							  : NULL
							) .
							(
								(cpMain::$system['method_type'] == "plugins")
								? DIRECTORY_SEPERATOR."plugins" : DIRECTORY_SEPERATOR."modules".DIRECTORY_SEPERATOR . cpMain::$system['method_name']
							) . DIRECTORY_SEPERATOR."lang_".cpMain::$system['method_mode'].".php"
						)
					)
					? $var
					:
					(is_file
						($var =  PATH_LOCAL . "library".DIRECTORY_SEPERATOR."language".DIRECTORY_SEPERATOR . SYSTEM_DEFAULT_LANG . ((cpMain::$system['method_type'] == "plugins")
							? DIRECTORY_SEPERATOR."plugins"
							: DIRECTORY_SEPERATOR."modules".DIRECTORY_SEPERATOR . cpMain::$system['method_name']) . DIRECTORY_SEPERATOR."lang_".cpMain::$system['method_mode'].".php"
						)
					)
					? $var
					: $_found = FALSE
				)
            );

			/**
			 * Didn't find a language file; that's not good.. We will not
			 * continue without one if it was requested, as the site may be visualy
			 * impaired or worst bad constructing of the method may result in security
			 * issues.
			 */
			if($_found == false)
			{
				cpMain::cpErrorFatal("Please consult the manual to see the proper directory hiearchy and system functionality. The path the system was looking for (or at least 1 of the paths we checked) is: " . $var . "<br>", __LINE__, __FILE__);
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
