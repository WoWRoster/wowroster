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

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster_login = new RosterLogin('&amp;id=' . $_GET['id']);

// Add the comment if one was POSTed
if( isset($_POST['process']) && $_POST['process'] == 'process' )
{
	if( $roster_login->getAuthorized() < $addon['config']['comm_add'] && !isset($_POST['comment_id']) )
	{
		print $roster_login->getMessage().
		$roster_login->getLoginForm($addon['config']['comm_add']);

		return; //To the addon framework
	}
	if( $roster_login->getAuthorized() < $addon['config']['comm_edit'] && isset($_POST['comment_id']) )
	{
		print $roster_login->getMessage().
		$roster_login->getLoginForm($addon['config']['comm_edit']);

		return; //To the addon framework
	}
	if( isset($_POST['author']) && !empty($_POST['author'])
		&& isset($_POST['comment']) && !empty($_POST['comment'])
		&& isset($_GET['id']) && is_numeric($_GET['id']) )
	{
		if( isset($_POST['html']) && $_POST['html'] == 1 && $addon['config']['comm_html'] >= 0)
		{
			$html = 1;
		}
		else
		{
			$html = 0;
		}

		if( isset($_POST['comment_id']) )
		{
			$query = "UPDATE `" . $roster->db->table('comments','news') . "` SET "
					. "`author` = '" . $_POST['author'] . "', "
					. "`content` = '" . $_POST['comment'] . "', "
					. "`html` = '" . $html . "' "
					. "WHERE `comment_id` = '" . $_POST['comment_id'] . "';";

			if( $roster->db->query($query) )
			{
				echo messagebox($roster->locale->act['comment_edit_success']);
			}
			else
			{
				echo messagebox("There was a DB error while editing your comment. MySQL said: " . $wowdb->db->error());
			}
		}
		else
		{
			$query = "INSERT INTO `" . $roster->db->table('comments','news') . "` SET "
					. "`news_id` = '" . $_GET['id'] . "', "
					. "`author` = '" . $_POST['author'] . "', "
					. "`content` = '" . $_POST['comment'] . "', "
					. "`html` = '" . $html . "', "
					. "`date` = NOW();";

			if( $roster->db->query($query) )
			{
				echo messagebox($roster->locale->act['comment_add_success']);
			}
			else
			{
				echo messagebox("There was a DB error while adding your comment. MySQL said: " . $wowdb->db->error());
			}
		}
	}
	else
	{
		echo messagebox($roster->locale->act['comment_error_process']);
	}
}

// Get the article to display at the head of the page
$query = "SELECT `news`.*, "
		. "DATE_FORMAT(  DATE_ADD(`news`.`date`, INTERVAL " . $roster->config['localtimeoffset'] . " HOUR ), '" . $roster->locale->act['timeformat'] . "' ) AS 'date_format', "
		. "COUNT(`comments`.`comment_id`) comm_count "
		. "FROM `" . $roster->db->table('news','news') . "` news "
		. "LEFT JOIN `" . $roster->db->table('comments','news') . "` comments USING (`news_id`) "
		. "WHERE `news`.`news_id` = '" . $_GET['id'] . "' "
		. "GROUP BY `news`.`news_id`";

$result = $roster->db->query($query);

if( $roster->db->num_rows($result) == 0 )
{
	echo messagebox($roster->locale->act['bad_news_id']);
}

$news = $roster->db->fetch($result);
if( isset($news['html']) && $news['html'] == 1 && $addon['config']['news_html'] >= 0 )
{
	$news['content'] = nl2br($news['content']);
}
else
{
	$news['content'] = nl2br(htmlentities($news['content']));
}

// Assign template vars
$roster->tpl->assign_vars(array(
	'L_POSTEDBY'     => $roster->locale->act['posted_by'],
	'L_EDIT'         => $roster->locale->act['edit'],
	'L_NAME'         => $roster->locale->act['name'],
	'L_ADD_COMMENT'  => $roster->locale->act['add_comment'],
	'L_ENABLE_HTML'  => $roster->locale->act['enable_html'],
	'L_DISABLE_HTML' => $roster->locale->act['disable_html'],

	'S_HTML_ENABLE'    => false,
	'S_COMMENT_HTML'   => $addon['config']['comm_html'],

	'U_COMMENT_BORDER_S' => border('swhite','start',$roster->locale->act['comments'],'60%'),
	'U_COMMENT_ADD_B_S'  => border('sblue','start',$roster->locale->act['add_comment']),
	'U_ADD_FORMACTION'   => makelink('util-news-comment&amp;id=' . $_GET['id']),
	'U_NEWS_ID'          => $news['news_id'],
	)
);

if($addon['config']['comm_html'] >= 0)
{
	$roster->tpl->assign_var('S_HTML_ENABLE',true);
}

$roster->tpl->assign_block_vars('news_row', array(
	'TITLE'         => $news['title'],
	'ID'            => $news['news_id'],
	'CONTENT'       => $news['content'],
	'COMMENT_COUNT' => $news['comm_count'],
	'AUTHOR'        => $news['author'],
	'DATE'          => $news['date_format'],

	'U_BORDER_S' => border('sorange','start',$news['title'],'60%'),
	'U_COMMENT'  => makelink('util-news-comment&amp;id=' . $news['news_id']),
	'U_EDIT'     => makelink('util-news-edit&amp;id=' . $news['news_id']),

	'L_COMMENT' => ($news['comm_count'] != 1 ? sprintf($roster->locale->act['n_comments'],$news['comm_count']) : sprintf($roster->locale->act['n_comment'],$news['comm_count'])),
	)
);


// Get the comments
$query = "SELECT `comments`.*, "
		. "DATE_FORMAT(  DATE_ADD(`comments`.`date`, INTERVAL " . $roster->config['localtimeoffset'] . " HOUR ), '" . $roster->locale->act['timeformat'] . "' ) AS 'date_format' "
		. "FROM `" . $roster->db->table('comments','news') . "` comments "
		. "WHERE `comments`.`news_id` = '" . $_GET['id'] . "' "
		. "ORDER BY `comments`.`date` ASC;";

$result = $roster->db->query($query);

if( $roster->db->num_rows() > 0 )
{
	while( $comment = $roster->db->fetch($result) )
	{
		if( isset($news['html']) && $news['html'] == 1 && $addon['config']['news_html'] >= 0 )
		{
			$comment['content'] = nl2br($comment['content']);
		}
		else
		{
			$comment['content'] = nl2br(htmlentities($comment['content']));
		}
		$roster->tpl->assign_block_vars('comment_row', array(
			'CONTENT'       => $comment['content'],
			'AUTHOR'        => $comment['author'],
			'DATE'          => $comment['date_format'],
			'U_COMMENT_ID'  => $comment['comment_id'],

			'U_EDIT'     => makelink('util-news-comment_edit&amp;id=' . $comment['comment_id']),
			)
		);
	}
}


$roster->tpl->set_filenames(array('head' => 'news/news.html'));
$roster->tpl->display('head');

$roster->tpl->set_filenames(array('body' => 'news/comment.html'));
$roster->tpl->display('body');

if( $roster_login->getAuthorized() < $addon['config']['comm_add'] )
{
	print $roster_login->getMessage().
	$roster_login->getLoginForm($addon['config']['comm_add']);
}
else
{
	$roster->output['body_onload'] .= 'initARC(\'addcomment\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';
	$roster->tpl->set_filenames(array('foot' => 'news/comment_add.html'));
	$roster->tpl->display('foot');
}