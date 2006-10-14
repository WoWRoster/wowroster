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

switch ($method)
{
	case menu_button_add:
		$status = 1;
		$errmsg = 'This function is not yet implemented';
		break;

	case menu_button_del:
		if (!$roster_login->getAuthorized())
		{
			$status = 103;
			$errmsg = 'Not authorized';
			return;
		}

		$button = $_GET['button'];
		$button_id = (int)substr($button,1);

		$query = "SELECT * FROM `".$wowdb->table('menu_button')."` WHERE `button_id` = ".$button_id;
		$DBres = $wowdb->query($query);

		if (!$DBres)
		{
			$status = 101;
			$errmsg = 'Failed to fetch button properties. MySQL said: '."\n".$wowdb->error()."\n".$query;
			return;
		}

		if ($wowdb->num_rows($DBres) == 0)
		{
			$status = 102;
			$errmsg = 'The specified button does not exist: '.$button;
			return;
		}

		$row = $wowdb->fetch_assoc($DBres);
		$wowdb->free_result($DBres);

		$query = "DELETE FROM `".$wowdb->table('menu_button')."` WHERE `button_id` = ".$button_id;

		$DBres = $wowdb->query($query);

		if (!$DBres)
		{
			$status = 103;
			$errmsg = 'Failed to delete button. MySQL said: '."\n".$wowdb->error()."\n".$query;
		}
		else
		{
			$status = 0;
			$result = $button;
		}

		break;
}

?>
