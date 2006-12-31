<?php

/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/class/cpmain.php
 *
 * This file is our main class, it's primary duty is object instantiation and to
 * allow public access ANYWHERE and everywhere to these objects. It uses what I
 * would like to consider a singleton pattern; although it may be arguable that
 * any valid pattern can be followed in php ; ) this is definately to the tee if
 * you ask me.
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
 *
 * One Ring to rule them all, One Ring to find them
 *
 * @package cpFramework
 */
final class cpMain
{

	/**
	 * Our static public instance array contains our storage for object instantiation.
	 * @var array
	 */
	static public $instance = Array();

	/**
	 * Our static public system array contains our storage for global system values
	 * @var array
	 */
	static public $system = Array();

	/**
	 * Keep track of what files we include, since php, doesn't.. publicly.
	 * granite, we do have php's "include_once" function, but I would prefer
	 * to keep tabs on it myself as well...
	 */
	static private $_includes = Array();

	/**
	 * Cannot instantiate this class, private cosntruct
	 */
	private function __construct()
	{
		/**
		 * Singleton
		 */
	}

	/**
	 * Load a class it will include the file as well as instantiate the object
	 * if a valid class is found and assign it to our instance container. We
	 * have the option to specify up to 10 parameters to pass to our classes
	 * construct. A example of such use is:
	 *
	 * loadClass('somefolder_classfile', 'className', 'Arg1', 'Arg2', 'Arg3');
	 *
	 * ALTERNATE
	 *
	 * $obj = loadClass('somefolder_classfile', 'className', 'Arg1', 'Arg2');
	 *
	 * @param string name of the file
	 * @param string name of class to instantiate
	 * @param mixed [UP TO 10] arguments to be passed to cosntruct
	 *
	 * @return object
	 */
	static public function loadClass()
	{

		$arguments = func_get_args();
		$arguments_count = func_num_args();

		/**
		 * we need at least two arguments to work with
		 */
		if($arguments_count < 2)
		{
			throw new cpException("Not enough arguments.");
		}

		/**
		 * we only allow accpetable characters in our filenames
		 */
		if(preg_match('/[^a-zA-Z0-9_.\-]/', $arguments[0]))
		{
			throw new cpException("Invalid characters in filename, match [^a-zA-Z0-9_.\-] when naming files.");
		}

		/**
		 * Only variables should be passed by reference, but i think its fine ;p
		 * hence our @ sign.. deal with it! silly redundant temporary variables
		 * are a bigger waste then this comment and a error surpressor... !
		 */
		$c_name = @end(explode("_", $arguments[0]));

		/**
		 * Valid paths are.. class.etc.php => class.etc.php
		 * bob_lib_class.etc.php => bob/lib/class.etc.php
		 * class.description.php is the required standard
		 */
		if(is_file($path = PATH_LOCAL . "library".DIR_SEP."class".DIR_SEP . str_replace('_', DIR_SEP, $arguments[0]) . ".php"))
		{
			if(!array_key_exists($arguments[1], self::$instance))
			{
				if(!isset(self::$_includes[$arguments[0]]))
				{
					include_once($path);
					self::$_includes[$arguments[0]] = true;
				}
				if(!class_exists($c_name, false))
				{
					throw new cpException("File " . $path . " was loaded but class " . $c_name . " was not found within.");
				}
				if($arguments_count > 2)
				{

					$arguments_list = Array();

					foreach($arguments as $key => $value)
					{
						if($key > 1)
						{
							$arguments_list[] = $value;
						}
					}
					$arguments_list_count = count($arguments_list);

					/**
					 * some might say this practice is silly.. and their may be a better, more dynamic
					 * implimentation. However, I could not find a way to use like call_user_func or something
					 * that could get done what were doing here, typicaly no constructor should have or needs
					 * a construct larger then 10.. so this should work out okay, while still bench marking
					 * well.. i hope :P
					 */
					if($arguments_list_count > 1)
					{
						if($arguments_list_count > 2)
						{
							if($arguments_list_count > 3)
							{
								if($arguments_list_count > 4)
								{
									if($arguments_list_count > 5)
									{
										if($arguments_list_count > 6)
										{
											if($arguments_list_count > 7)
											{
												if($arguments_list_count > 8)
												{
													if($arguments_list_count > 9)
													{
														if($arguments_list_count > 10)
														{
															throw new cpException("A class constructor may not contain more then 10 arguments in the systems current architecture.");
														}
														else
														{
															self::$instance[$arguments[1]] = new $c_name($arguments_list[0], $arguments_list[1], $arguments_list[2], $arguments_list[3], $arguments_list[4] ,$arguments_list[5] ,$arguments_list[6], $arguments_list[7], $arguments_list[8], $arguments_list[9]);
														}
													}
													else
													{
													self::$instance[$arguments[1]] = new $c_name($arguments_list[0], $arguments_list[1], $arguments_list[2], $arguments_list[3], $arguments_list[4] ,$arguments_list[5] ,$arguments_list[6], $arguments_list[7], $arguments_list[8]);
													}
												}
												else
												{
												self::$instance[$arguments[1]] = new $c_name($arguments_list[0], $arguments_list[1], $arguments_list[2], $arguments_list[3], $arguments_list[4] ,$arguments_list[5] ,$arguments_list[6], $arguments_list[7]);
												}
											}
											else
											{
											self::$instance[$arguments[1]] = new $c_name($arguments_list[0], $arguments_list[1], $arguments_list[2], $arguments_list[3], $arguments_list[4] ,$arguments_list[5] ,$arguments_list[6]);
											}
										}
										else
										{
										self::$instance[$arguments[1]] = new $c_name($arguments_list[0], $arguments_list[1], $arguments_list[2], $arguments_list[3], $arguments_list[4], $arguments_list[5]);
										}
									}
									else
									{
									self::$instance[$arguments[1]] = new $c_name($arguments_list[0], $arguments_list[1], $arguments_list[2], $arguments_list[3], $arguments_list[4]);
									}
								}
								else
								{
								self::$instance[$arguments[1]] = new $c_name($arguments_list[0], $arguments_list[1], $arguments_list[2], $arguments_list[3]);
								}
							}
							else
							{
							self::$instance[$arguments[1]] = new $c_name($arguments_list[0], $arguments_list[1], $arguments_list[2]);
							}
						}
						else
						{
						self::$instance[$arguments[1]] = new $c_name($arguments_list[0], $arguments_list[1]);
						}
					}
					else
					{
					self::$instance[$arguments[1]] = new $c_name($arguments_list[0]);
					}
				}
				else
				{
				self::$instance[$arguments[1]] = new $c_name;
				}

				/**
				* we return our instantiated object in case we want to perhaps assign or reference
				*/
				return self::$instance[$arguments[1]];

			}
			else
			{
			throw new cpException("Gave up when attempting to access the class ". $arguments[1] . " within " . $arguments[0] . " because it has been included already.");
			}
		}
		else
		{
		throw new cpException("File " . $arguments[0] . " was not found, or permission was denied when attempting to read it.");
		}
	}

	/**
	 * Load a factory it will include the file as well as run the factory function
	 * if a valid class is found and assign it to our instance container. We
	 * have the option to specify parameters to pass to the factory. Example:
	 *
	 * loadFactory('somefolder_classfile', 'className', 'Arg1', 'Arg2', 'Arg3');
	 *
	 * ALTERNATE
	 *
	 * $obj = loadFactory('somefolder_classfile', 'className', 'Arg1', 'Arg2');
	 *
	 * @param string name of the file
	 * @param string name of class to instantiate
	 * @param mixed arguments to be passed to cosntruct
	 *
	 * @return object
	 */
	static public function loadFactory()
	{

		$arguments = func_get_args();
		$arguments_count = func_num_args();

		/**
		 * we need at least two arguments to work with
		 */
		if($arguments_count < 2)
		{
			throw new cpException("Not enough arguments.");
		}

		/**
		 * we only allow accpetable characters in our filenames
		 */
		if(preg_match('/[^a-zA-Z0-9_.\-]/', $arguments[0]))
		{
			throw new cpException("Invalid characters in filename, match [^a-zA-Z0-9_.\-] when naming files.");
		}

		/**
		 * Only variables should be passed by reference, but i think its fine ;p
		 * hence our @ sign.. deal with it! silly redundant temporary variables
		 * are a bigger waste then this comment and a error surpressor... !
		 */
		$c_name = @end(explode("_", $arguments[0]));

		/**
		 * Valid paths are.. class.etc.php => class.etc.php
		 * bob_lib_class.etc.php => bob/lib/class.etc.php
		 * class.description.php is the required standard
		 */
		if(is_file($path = PATH_LOCAL . "library".DIR_SEP."class".DIR_SEP . str_replace('_', DIR_SEP, $arguments[0]) . ".php"))
		{
			if(!array_key_exists($arguments[1], self::$instance))
			{
				if(!isset(self::$_includes[$arguments[0]]))
				{
					include_once($path);
					self::$_includes[$arguments[0]] = true;
				}
				if(!class_exists($c_name, false))
				{
					throw new cpException("File " . $path . " was loaded but class " . $c_name . " was not found within.");
				}
				if($arguments_count > 2)
				{

					$arguments_list = Array();

					foreach($arguments as $key => $value)
					{
						if($key > 1)
						{
							$arguments_list[] = $value;
						}
					}

					self::$instance[$arguments[1]] = call_user_func_array(array($c_name, 'factory'), $arguments_list);
				}
				else
				{
					self::$instance[$arguments[1]] = call_user_func(array($c_name, 'factory'));
				}

				/**
				* we return our instantiated object in case we want to perhaps assign or reference
				*/
				return self::$instance[$arguments[1]];

			}
			else
			{
				throw new cpException("Gave up when attempting to access the class ". $arguments[1] . " within " . $arguments[0] . " because it has been included already.");
			}
		}
		else
		{
			throw new cpException("File " . $arguments[0] . " was not found, or permission was denied when attempting to read it.");
		}
	}

	/**
	 * Load a file, a semi-wrapper for include, as it parses a special directory
	 * structure based on underscores. It also populates our private $_includes
	 * with a key of the file, this allows us to keep track of the included files
	 * manualy instead of using include_once or require_once, I believe this is
	 * a better practice but it's still under question in my mind. Until I find
	 * a good reason to rewrite this structure.
	 *
	 * USAGE:
	 * loadFile('somefolder_classfile');
	 *
	 * @param string $file name of the file
	 *
	 * @return bool true|exception on failure
	 */
	static public function loadFile($file)
	{

		/**
		 * we only allow accpetable characters in our filenames
		 */
		if(preg_match('/[^a-zA-Z0-9_.\-]/', $file))
		{
			throw new cpException("Invalid characters in filename, match [^a-zA-Z0-9_.\-] when naming files.");
		}

		/**
		 * Valid paths are.. class.etc.php => class.etc.php
		 * bob_lib_class.etc.php => bob/lib/class.etc.php
		 * class.description.php is the required standard
		 */
		if(is_file($path = PATH_LOCAL . "library".DIR_SEP."class".DIR_SEP . str_replace('_', DIR_SEP, $file) . ".php"))
		{
			if(!isset(self::$_includes[$file]))
			{
				include_once($path);
				self::$_includes[$file] = true;
				return true;
			}
		}
		else
		{
			throw new cpException("File " . $file . " was not found, or permission was denied when attempting to read it.");
		}
	}

	/**
	 * Destroys a class instance
	 *
	 * @param string $class name of the class reference
	 *
	 * @return bool true
	 */
	static public function destroyClass($class)
	{
		unset(self::$instance[$class]);
		return true;
	}

	/**
	 * Returns if a class has been instantiated yet. Note that
	 * this checks to see if the reference exists, not to see
	 * if the object was created.
	 *
	 * @param string $class name of the class reference
	 *
	 * @return bool true
	 */
	static public function isClass($class)
	{
		return isset(self::$instance[$class]);
	}

	/**
	 * Returns if a file has been included yet quite simply.
	 *
	 * @param string $file name of the file to check
	 *
	 * @return bool true
	 */
	static public function isIncluded($file)
	{
		return isset(self::$_includes[$file]);
	}

	/**
	 * Displays a error, but does not halt the script!
	 *
	 * @param string $_error a description of the error
	 * @param string $_line line number of the error
	 * @param string $_file the name of the file the error occured
	 * @param string $_friendly display a friendly or full error
	 *
	 * @return void
	 */
	static public function cpError($_error, $_line, $_file, $_friendly = FALSE)
	{
		echo "<b>cpError [recoverable error]: </b> " . $_error  . (($_friendly == FALSE) ? " on line <b>". $_line ."</b> in file <b>" . $_file . "</b>." : "."). "<br>\n";
	}

	/**
	 * Displays a error, and halts scripts execution.
	 *
	 * @param string $_error a description of the error
	 * @param string $_line line number of the error
	 * @param string $_file the name of the file the error occured
	 * @param string $_friendly display a friendly or full error
	 *
	 * @return void
	 */
	static public function cpErrorFatal($_error, $_line, $_file, $_friendly = FALSE)
	{
		die("<b>cpErrorFatal [script halt]: </b> " . $_error  . (($_friendly == FALSE) ? " on line <b>". $_line ."</b> in file <b>" . $_file . "</b>." : "."). "<br>\n");
	}
}
