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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Addon Update class
 * This MUST be the same name as the addon basename
 */
class recipe
{
	var $messages = '';		// Update messages
	var $data = array();	// Addon config data automatically pulled from the addon_config table
	var $files = array();


	/**
	 * Class instantiation
	 * The name of this function MUST be the same name as the class name
	 *
	 * @param array $data	| Addon data
	 * @return recipe
	 */
	function recipe($data)
	{
		$this->data = $data;
	}

	/**
	 * Resets addon messages
	 */
	function reset_messages()
	{
		$this->messages = '';
	}


	function update()
	{
		global $roster;

		$this->messages .= "<span class=\"green\">This is a non CP hook</span><br />\n";

		return true;
	}

	function guild_pre( $data )
	{
		global $roster;

		$this->messages .= "<span class=\"green\">This is a guild_pre hook</span><br />\n";

		return true;
	}

	function guild( $data , $memberid )
	{
		global $roster;

		$this->messages .= "<span class=\"yellow\">This is a guild hook</span><br />\n";

		return true;
	}

	function guild_post( $data )
	{
		global $roster;

		$this->messages .= "<span class=\"red\">This is a guild_post hook</span><br />\n";

		return true;
	}

	function char_pre( $data )
	{
		global $roster;

		$this->messages .= "<span class=\"green\">This is a char_pre hook</span><br />\n";

		return true;

	}

	function char( $data , $memberid )
	{
		global $roster;

		$this->messages .= "<span class=\"yellow\">This is a char hook</span><br />\n";

		return true;
	}

	function char_post( $data )
	{
		global $roster;

		$this->messages .= "<span class=\"red\">This is a char_post hook</span><br />\n";

		return true;
	}
}
