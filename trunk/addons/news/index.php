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

$roster_login = new RosterLogin();

// Add news if any was POSTed
if( isset($_POST['process']) && $_POST['process'] == 'process' )
{
	if( $roster_login->getAuthorized() < $addon['config']['news_add'] && !isset($_POST['id']) )
	{
		print $roster_login->getMessage().
		$roster_login->getLoginForm($addon['config']['news_add']);

		return; //To the addon framework
	}
	if( $roster_login->getAuthorized() < $addon['config']['news_edit'] && isset($_POST['id']) )
	{
		print $roster_login->getMessage().
		$roster_login->getLoginForm($addon['config']['news_edit']);

		return; //To the addon framework
	}
	if( isset($_POST['author']) && !empty($_POST['author'])
		&& isset($_POST['title']) && !empty($_POST['title'])
		&& isset($_POST['news']) && !empty($_POST['news']) )
	{
		if( isset($_POST['html']) && $_POST['html'] == 1 && $addon['config']['news_html'] >= 0 )
		{
			$html = 1;
		}
		else
		{
			$html = 0;
		}

		if( isset($_POST['id']) )
		{
			$query = "UPDATE `" . $roster->db->table('news','news') . "` SET "
					. "`author` = '" . $_POST['author'] . "', "
					. "`title` = '" . $_POST['title'] . "', "
					. "`content` = '" . $_POST['news'] . "', "
					. "`html` = '" . $html . "' "
					. "WHERE `news_id` = '" . $_POST['id'] . "';";

			if( $roster->db->query($query) )
			{
				echo messagebox($roster->locale->act['news_edit_success']);
			}
			else
			{
				echo messagebox("There was a DB error while editing the article. MySQL said: " . $wowdb->db->error());
			}
		}
		else
		{
			$query = "INSERT INTO `" . $roster->db->table('news','news') . "` SET "
					. "`author` = '" . $_POST['author'] . "', "
					. "`title` = '" . $_POST['title'] . "', "
					. "`content` = '" . $_POST['news'] . "', "
					. "`html` = '" . $html . "', "
					. "`date` = NOW();";

			if( $roster->db->query($query) )
			{
				echo messagebox($roster->locale->act['news_add_success']);
			}
			else
			{
				echo messagebox("There was a DB error while adding the article. MySQL said: " . $wowdb->db->error());
			}
		}
	}
	else
	{
		echo messagebox($roster->locale->act['news_error_process']);
	}
}

// Display news
$query = "SELECT `news`.*, "
		. "DATE_FORMAT(  DATE_ADD(`news`.`date`, INTERVAL " . $roster->config['localtimeoffset'] . " HOUR ), '" . $roster->locale->act['timeformat'] . "' ) AS 'date_format', "
		. "COUNT(`comments`.`comment_id`) comm_count "
		. "FROM `" . $roster->db->table('news','news') . "` news "
		. "LEFT JOIN `" . $roster->db->table('comments','news') . "` comments USING (`news_id`) "
		. "GROUP BY `news`.`news_id`"
		. "ORDER BY `news`.`date` DESC;";

$result = $roster->db->query($query);

if( $roster->db->num_rows($result) == 0 )
{
	echo messagebox($roster->locale->act['no_news']);
}


// Assign template vars
$roster->tpl->assign_vars(array(
	'L_POSTEDBY' => $roster->locale->act['posted_by'],
	'L_EDIT'     => $roster->locale->act['edit'],
	'L_ADD_NEWS' => $roster->locale->act['add_news'],

	'U_ADD_NEWS'  => makelink('util-news-add'),
	)
);

while( $news = $roster->db->fetch($result) )
{
	if( isset($news['html']) && $news['html'] == 1 && $addon['config']['news_html'] >= 0 )
	{
		$news['content'] = nl2br($news['content']);
	}
	else
	{
		$news['content'] = nl2br(htmlentities($news['content']));
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
}

$roster->tpl->set_filenames(array('head' => 'news/news_head.html'));
$roster->tpl->display('head');

$roster->tpl->set_filenames(array('body' => 'news/news.html'));
$roster->tpl->display('body');

$roster->tpl->set_filenames(array('foot' => 'news/news_foot.html'));
$roster->tpl->display('foot');
