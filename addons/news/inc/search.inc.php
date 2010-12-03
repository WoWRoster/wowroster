<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    News
 * @subpackage Search
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * News Search
 *
 * @package    News
 * @subpackage Search
 */
class newsSearch
{
	var $options;
	var $result = array();
	var $result_count = 0;
	var $start_search;
	var $stop_search;
	var $time_search;
	var $open_table;
	var $close_table;
	var $search_url;
	var $data = array();    // Addon data

	function search( $search , $limit=10 , $page=0 )
	{
		global $roster;

		$first = $page*$limit;

		$sql = "SELECT `news`.`news_id`, `news`.`author`, `news`.`title`, `news`.`content`, `news`.`html`, "
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
			 . ( $limit > 0 ? " LIMIT $first," . $limit : '' ) . ';';

		//calculating the search time
		$this->start_search = format_microtime();

		$result = $roster->db->query($sql);

		$this->stop_search = format_microtime();
		$this->time_search = $this->stop_search - $this->start_search;

		$nrows = $roster->db->num_rows($result);

		$x = ($limit > $nrows) ? $nrows : ($limit > 0 ? $limit : $nrows);
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

				$item['footer'] = ($comments == 0 ? 'No' : $comments) . ' comment' . ($comments == 1 ? '' : 's');

				$this->add_result($item);
				unset($item);
				$x--;
			}
		}
		$roster->db->free_result($result);
	}

	function add_result( $resultarray )
	{
		$this->result[$this->result_count++] = $resultarray;
	}
}
