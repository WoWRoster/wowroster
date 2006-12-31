<?php

/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/class/cpusers.php
 *
 * This handles our user authentication and permissions management.
 * We use this class to inject a container into our global scope for
 * usage throughout the site, we store the password in a serialized
 * string, in the near future I may switch to session based authentication
 * but for now I am content with the cookie based system.
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
 * Our users class, user authentication and permissions management.
 * @package cpFramework
 */
class cpusers
{
	/**
	 * Array container for user private-+ public info
	 *
	 * @var array
	 *
	 * @access private
	 */
	public $data = Array();

	/**
	 * Constructor of the users class, it constructs our users class
	 * and assigns our user to the proper level.
	 *
	 * @return void
	 *
	 * @access public
	 */
	public function __construct($user = NULL, $pass = NULL, $fetch = NULL, $cookie = NULL)
	{
		if(isset($user) && isset($pass))
		{
			self::checkLogin($user, $pass, $fetch, $cookie);
		}
		else
		{
			self::_checkLoginCookie();
		}
	}

	/**
	 * For inactive users
	 *
	 * @return void
	 *
	 * @access private
	 */
	private function _inactive()
	{
		cpMain::cpErrorFatal(cpMain::$instance['cpsql']->record['users']['user_inactive'], __LINE__, __FILE__, TRUE);
	}

	/**
	 * Set our few neccassary values for the guests that visit.
	 *
	 * @return void
	 *
	 * @access private
	 */
	private function _guest()
	{
		$this->data['user_id'] = -1;
		$this->data['user_name'] = "Guest";
		$this->data['user_level'] = "0";
		$this->data['user_theme'] = SYSTEM_DEFAULT_THEME;
		$this->data['user_lang'] = SYSTEM_DEFAULT_LANG;
	}

	/**
	 * Set our few neccassary values for invalid crudentials.
	 *
	 * @return void
	 *
	 * @access private
	 */
	private function _error()
	{
		$this->data['user_id'] = -1;
		$this->data['user_name'] = "Guest";
		$this->data['user_level'] = "0";
		$this->data['user_theme'] = SYSTEM_DEFAULT_THEME;
		$this->data['user_lang'] = SYSTEM_DEFAULT_LANG;
	}

	/**
	 * Lets assign all of the values for ALL fields within the table automaticaly
	 * this will allow easy scalability with ease in relational type setups.
	 *
	 * @return void
	 *
	 * @access private
	 */
	private function _member()
	{
		for($collumn = 0; $collumn < cpMain::$instance['cpsql']->fields_num("users"); $collumn++)
		{
			$field_name = cpMain::$instance['cpsql']->fields_name("users", $collumn);
			$this->data[$field_name] = cpMain::$instance['cpsql']->record['users'][$field_name];
		}
	}

	/**
	 * Checks the login for the user, this is a private function and is based
	 * off of a cookie.
	 *
	 * @return string     result type
	 *
	 * @access public
	 */
	private function _checkLoginCookie()
	{
		if(isset($_COOKIE[COOKIE_USER]))
		{
			// this practice is questioned.. a user serialized array could potentialy become a threat
			// if not handled properly within a module, however the only danger is the coder, not the code
			$login_info = unserialize(preg_replace("'\\\'", "", $_COOKIE[COOKIE_USER]));

			cpMain::$instance['cpsql']->query
			(
				cpMain::$instance['cpsql']->query_prepare
				(
					"SELECT *
					FROM
						".DB_PREFIX."users
					WHERE
						user_name = '?' AND user_password = '?'",
					$login_info['username'],
					$login_info['password']
				),
				"users"
			);

			cpMain::$instance['cpsql']->fetch("users");

			if(cpMain::$instance['cpsql']->num_rows("users") > 0)
			{
				if(trim(cpMain::$instance['cpsql']->record['users']['user_active']) !== "")
				{
					self::_inactive();
				}
				self::_member();
			}
			else
			{
				self::_error();
			}
		}
		else
		{
		self::_guest();
		}
	}

	/**
	 * Checks the login for the user when logging in from a form or
	 * any method not initiated via our common call (check_login) this
	 * function is available publicly.
	 *
	 * @return bool
	 *
	 * @access public
	 */
	public function checkLogin($username, $password, $fetch = false, $cookie = false)
	{
		if((empty($username)) || (empty($password)))
		{
			return false;
		}
		else
		{
			cpMain::$instance['cpsql']->query
			(
				cpMain::$instance['cpsql']->query_prepare
				(
					"SELECT *
					FROM
						".DB_PREFIX."users
					WHERE
						user_name = '?' AND user_password = '?'",
					$username,
					md5($password)
				),
				"users"
			);
			if(cpMain::$instance['cpsql']->num_rows("users") == 1)
			{
				cpMain::$instance['cpsql']->fetch("users");
				if($cookie === true)
				{
					setcookie
					(
						COOKIE_USER,
						serialize
						(
							array
							(
								"username" => $username,
								"password" => cpMain::$instance['cpsql']->record['users']['user_password']
							)
						),
						(time()+(60*60*24*360))
					);
				}
				if($fetch === true)
				{
					self::_member();
				}
				return true;
			}
			else
			{
				if($fetch === true)
				{
					self::_error();
				}
				return false;
			}
		}
	}
}
