<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster ajax function for Roster menu
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$roster_login = new RosterLogin();

switch ($method)
{
	case 'menu_button_add':
		if( !$roster_login->getAuthorized() )
		{
			$status = 103;
			$errmsg = 'Not authorized';
			return;
		}

		if( isset($_POST['title']) )
		{
			$title = $_POST['title'];
		}
		else
		{
			$status = 104;
			$errmsg = 'Failed to insert button: Not enough data (no title given)';
			return;
		}

		if( isset($_POST['url']) )
		{
			$url = $_POST['url'];
		}
		else
		{
			$status = 104;
			$errmsg = 'Failed to insert button: Not enough data (no url given)';
			return;
		}

		$query = "INSERT INTO `" . $wowdb->table('menu_button') . "` VALUES (NULL,0,'" . $wowdb->escape($_POST['title']) . "','" . $wowdb->escape($_POST['url']) . "')";

		$DBres = $wowdb->query($query);

		if (!$DBres)
		{
			$status = 101;
			$errmsg = 'Failed to insert button. MySQL said: ' . $wowdb->error();
			return;
		}

		$status=0;
		$result  = '<id>b' . $wowdb->insert_id() . "</id>\n";
		$result .= '<title>' . $_POST['title'] . '</title>';

		break;

	case 'menu_button_del':
		if( !$roster_login->getAuthorized() )
		{
			$status = 103;
			$errmsg = 'Not authorized';
			return;
		}

		$button = $_POST['button'];
		$button_id = (int)substr($button,1);

		$query = "SELECT * FROM `" . $wowdb->table('menu_button') . "` WHERE `button_id` = '" . $button_id . "';";
		$DBres = $wowdb->query($query);

		if( !$DBres )
		{
			$status = 101;
			$errmsg = 'Failed to fetch button properties. MySQL said: ' . "\n" . $wowdb->error() . "\n" . $query;
			return;
		}

		if( $wowdb->num_rows($DBres) == 0 )
		{
			$status = 102;
			$errmsg = 'The specified button does not exist: ' . $button;
			return;
		}

		$row = $wowdb->fetch_assoc($DBres);
		$wowdb->free_result($DBres);

		if( $row['addon_id'] == '0' )
		{
			$status = 105;
			$errmsg = 'You cannot delete WoWRoster made buttons: ' . $button;
			return;
		}

		$query = "DELETE FROM `".$wowdb->table('menu_button')."` WHERE `button_id` = '" . $button_id . "';";

		$DBres = $wowdb->query($query);

		if (!$DBres)
		{
			$status = 101;
			$errmsg = 'Failed to delete button. MySQL said: ' . "\n" . $wowdb->error() . "\n" . $query;
			return;
		}

		$status = 0;
		$result = $button;
		break;
}
