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
 * @subpackage Search
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

class news_search
{
	var $options;
	var $result = array();
	var $result_count = 0;
	var $link_next;
	var $link_prev;
	var $data = array();	// Addon data


	function search( $search , $url_search , $limit=10 , $page=0 )
	{
		global $roster;

		$first = $page*$limit;

		$q = "SELECT `news`.`news_id`, `news`.`author`, `news`.`title`, `news`.`content`, `news`.`html`, "
			. "DATE_FORMAT(  DATE_ADD(`news`.`date`, INTERVAL " . $roster->config['localtimeoffset'] . " HOUR ), '" . $roster->locale->act['timeformat'] . "' ) AS 'date_format', "
			. "COUNT(`comments`.`comment_id`) comm_count "
			. "FROM `" . $roster->db->table('news','news') . "` news "
			. "LEFT JOIN `" . $roster->db->table('comments','news') . "` comments USING (`news_id`) "
			. "WHERE `news`.`news_id` LIKE '%$search%' "
			. "OR `news`.`author` LIKE '%$search%' "
			. "OR `news`.`date` LIKE '%$search%' "
			. "OR `news`.`title` LIKE '%$search%' "
			. "OR `news`.`content` LIKE '%$search%' "
			. "GROUP BY `news`.`news_id` "
			. "LIMIT $first," . ($limit+1) .";";

		$result = $roster->db->query($q);
		$nrows = $roster->db->num_rows($result);
		//$result = $roster->db->query($query);

		$x = ($limit > $nrows) ? $nrows : $limit;
		if( $nrows > 0 )
		{
			while( $x > 0 )
			{
				list($news_id, $author, $title, $content, $html, $date, $comments) = $roster->db->fetch($result);

				$item['author'] = $author;
				$item['date'] = $date;
				$item['title'] = $title;
				$item['url'] = makelink('util-news-comment&amp;id=' . $news_id);

				if( $html == '1' && $this->data['config']['news_html'] >= 0 )
				{
					$content = $content;
				}
				else
				{
					$content = htmlentities($content);
				}
				$content = nl2br($content);

				$array = explode(' ',$content,101);
				if( isset($array[100]) )
				{
					unset($array[100]);
					$item['more_text'] = true;
				}
				else
				{
					$item['more_text'] = false;
				}
				$item['short_text'] = implode(' ',$array);

				$item['footer'] = ($comments == 0?'No':$comments) . ' comment' . ($comments == 1?'':'s');

				$this->add_result($item);
				unset($item);
				$x--;
			}
		}

		if( $page > 0 )
		{
			$this->link_prev = '<a href="' . makelink('search&amp;page=' . ($page-1) . '&amp;search=' . $url_search . '&amp;s_addon=' . $this->data['basename']) . '"><strong>' . $roster->locale->act['search_previous_matches'] . $this->data['fullname'] . '</strong></a>';
		}
		if( $nrows > $limit )
		{
			$this->link_next = '<a href="' . makelink('search&amp;page=' . ($page+1) . '&amp;search=' . $url_search . '&amp;s_addon=' . $this->data['basename']) . '"><strong> ' . $roster->locale->act['search_next_matches'] . $this->data['fullname'] . '</strong></a>';
		}
	}

	function add_result( $resultarray )
	{
		$this->result[$this->result_count++] = $resultarray;
	}
}
