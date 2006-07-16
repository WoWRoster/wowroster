<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/


class RosterLogin
{
	var $allow_login;
	var $message;
	var $script_filename;

	/**
	 * Constructor for Roster Login class
	 * Accepts an action for the form
	 * And an array of additional fields
	 *
	 * @param string $script_filename
	 * @param array $fields
	 * @return RosterLogin
	 */
	function RosterLogin($script_filename)
	{
		$this->script_filename = $script_filename;
		$this->allow_login = false;

		$this->checkLogin();
	}

	function checkLogin()
	{
		$this->allow_login = is_admin();
	}

	function getAuthorized()
	{
		return $this->allow_login;
	}

	function getMessage()
	{
		return '';
	}

	function getLoginForm()
	{
		return '';
	}
}

?>