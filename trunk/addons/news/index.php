<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    News
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

// Add news if any was POSTed
if( isset($_POST['process']) && $_POST['process'] == 'process' )
{
	if( !$roster->auth->getAuthorized( $addon['config']['news_add'] ) && !isset($_POST['id']) )
	{
		echo $roster->auth->getLoginForm($addon['config']['news_add']);
		return; //To the addon framework
	}
	if( !$roster->auth->getAuthorized( $addon['config']['news_edit'] ) && isset($_POST['id']) )
	{
		echo $roster->auth->getLoginForm($addon['config']['news_edit']);
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
				$roster->set_message($roster->locale->act['news_edit_success']);
			}
			else
			{
				$roster->set_message('There was a DB error while editing the article.', '', 'error');
				$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
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
				$roster->set_message($roster->locale->act['news_add_success']);
			}
			else
			{
				$roster->set_message('There was a DB error while adding the article.', '', 'error');
				$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
			}
		}
	}
	else
	{
		$roster->set_message($roster->locale->act['news_error_process'], '', 'error');
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
	'U_ADD_NEWS'  => makelink('util-news-add'),

	'S_ADD_NEWS'  => $roster->auth->getAuthorized($addon['config']['news_add']),
	'S_EDIT_NEWS'  => $roster->auth->getAuthorized($addon['config']['news_edit'])
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

		'U_COMMENT'  => makelink('util-news-comment&amp;id=' . $news['news_id']),
		'U_EDIT'     => makelink('util-news-edit&amp;id=' . $news['news_id']),

		'L_COMMENT' => ($news['comm_count'] != 1 ? sprintf($roster->locale->act['n_comments'],$news['comm_count']) : sprintf($roster->locale->act['n_comment'],$news['comm_count'])),
		)
	);
}

$roster->tpl->set_filenames(array(
	'head' => $addon['basename'] . '/news_head.html',
	'body' => $addon['basename'] . '/news.html',
	'foot' => $addon['basename'] . '/news_foot.html'
	)
);
$roster->tpl->display('head');
$roster->tpl->display('body');
$roster->tpl->display('foot');
