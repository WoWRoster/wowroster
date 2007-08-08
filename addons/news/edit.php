<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    News
*/

include( $addon['dir'] . 'template' . DIR_SEP . 'template.php' );

$roster_login = new RosterLogin('&amp;id=' . $_GET['id']);

if( $roster_login->getAuthorized() < $addon['config']['news_edit'] )
{
	print $roster_login->getMessage().
	$roster_login->getLoginForm($addon['config']['news_edit']);

	return; //To the addon framework
}

// Display news
$query = "SELECT * "
		. "FROM `" . $roster->db->table('news','news') . "` news "
		. "WHERE `news_id` = '" . $_GET['id'] . "';";

$result = $roster->db->query($query);

if( $roster->db->num_rows($result) == 0 )
{
	echo messagebox($roster->locale->act['no_news']);
}

include_template( 'news_head.tpl' );

$news = $roster->db->fetch($result);

include_template( 'edit.tpl', $news );
