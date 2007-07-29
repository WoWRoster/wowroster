<?php

class news_search
{
	var $title;
	var $options;
	var $result = array();
	var $result_count = 0;
	var $link_next;
	var $link_prev;
		var $icon;
	// class constructor

	function news_search()
	{
		global $roster, $addon;

		$this->basename = 'News';
				
	}

	function search( $search , $url_search , $limit=10 , $page=0 )
	{
		global $roster, $addon;

		$first = $page*$limit;

		$this->icon = $roster->config['interface_url'] . 'Interface/Icons/ability_warrior_rallyingcry.jpg';
				
				 
			     
		$q = "SELECT `news`.`news_id`, `news`.`author`, `news`.`title`,`news`.`content`, "
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
				list($news_id, $author, $title, $content, $date, $comments) = $roster->db->fetch($result);

				$item['author'] = $author;
				$item['date'] = $date;
				$item['title'] = $title;
				$item['url'] = makelink('util-news');

				$array = explode(' ',$content,101);
				unset($array[100]);
				$item['short_text'] = '<span style="white-space:normal">'.implode(' ',$array).'</span>';

				$item['footer'] = ($comments == 0?'No':$comments) . ' comment' . ($comments == 1?'':'s');

				$this->add_result($item);
				unset($item);
				$x--;
			}
		}

		if( $page > 0 )
		{
			$this->link_prev = '<a href="' . makelink('search&amp;page=' . ($page-1) . '&amp;search=' . $url_search . '&amp;s_addon=' . $addon['addon']) . '"><strong>' . $roster->locale->act['search_previous_matches'] . $addon['basename'] . '</strong></a>';
		}
		if( $nrows > $limit )
		{
			$this->link_next = '<a href="' . makelink('search&amp;page=' . ($page+1) . '&amp;search=' . $url_search . '&amp;s_addon=' . $addon['addon']) . '"><strong> ' . $roster->locale->act['search_next_matches'] . $addon['basename'] . '</strong></a>';
		}
	}

	function add_result( $resultarray )
	{
		$this->result[$this->result_count++] = $resultarray;
	}
}
