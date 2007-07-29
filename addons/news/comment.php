<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: enUS.php 1126 2007-07-27 05:14:27Z Zanix $
 * @link       http://www.wowroster.net
 * @package    News
*/

include( $addon['dir'] . 'template' . DIR_SEP . 'template.php' );

// Add the comment if one was POSTed
if( isset($_POST['process']) && $_POST['process'] == 'process' )
{
	if( isset($_POST['author']) && !empty($_POST['author'])
		&& isset($_POST['comment']) && !empty($_POST['comment'])
		&& isset($_GET['id']) && is_numeric($_GET['id']) )
	{
		if( isset($_POST['html']) && $_POST['html'] == 1 && $addon->config['comm_html'] >= 0)
		{
			$comment = nl2br($_POST['comment']);
		}
		else
		{
			$comment = nl2br(htmlentities($_POST['comment']));
		}
		
		$query = "INSERT INTO `" . $roster->db->table('comments','news') . "` SET "
				. "`news_id` = '" . $_GET['id'] . "', "
				. "`author` = '" . $_POST['author'] . "', "
				. "`content` = '" . $comment . "', "
				. "`date` = NOW();";
				
		if( $roster->db->query($query) )
		{
			echo messagebox("Comment added successfully");
		}
		else
		{
			echo messagebox("There was a DB error while adding your comment. MySQL said: " . $wowdb->db->error());
		}
	}
	else
	{
		echo messagebox("There was a problem processing your comment.");
	}
}

// Get the article to display at the head of the page
$query = "SELECT `news`.*, "
		. "DATE_FORMAT(  DATE_ADD(`news`.`date`, INTERVAL " . $roster->config['localtimeoffset'] . " HOUR ), '" . $roster->locale->act['timeformat'] . "' ) AS 'date_format' "
		. "FROM `" . $roster->db->table('news','news') . "` news "
		. "WHERE `news`.`news_id` = '" . $_GET['id'] . "';";

$result = $roster->db->query($query);

if( $roster->db->num_rows($result) == 0 )
{
	echo messagebox($roster->locale->act['bad_news_id']);
}

$news = $roster->db->fetch($result);
include_template( 'comment_head.tpl', $news);

// Get the comments
$query = "SELECT `comments`.*, "
		. "DATE_FORMAT(  DATE_ADD(`comments`.`date`, INTERVAL " . $roster->config['localtimeoffset'] . " HOUR ), '" . $roster->locale->act['timeformat'] . "' ) AS 'date_format' "
		. "FROM `" . $roster->db->table('comments','news') . "` comments "
		. "WHERE `comments`.`news_id` = '" . $_GET['id'] . "' "
		. "ORDER BY `comments`.`date` ASC;";

$result = $roster->db->query($query);

while( $comment = $roster->db->fetch($result) )
{
	include_template( 'comment.tpl', $comment );
}

include_template( 'comment_foot.tpl' );
