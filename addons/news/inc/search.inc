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
				
				 
                             
               $q = "SELECT `news_id`, `author`, `date`, `title`, `content` FROM `" . $roster->db->table('news','news') . "` news "
				. "WHERE (`news_id` LIKE '%$search%' or `author` LIKE '%$search%' or `date` LIKE '%$search%' or `title` LIKE '%$search%'"
				. " or `content` LIKE '%$search%')"
                . "LIMIT $first," . ($limit+1) .";";
		$result = $roster->db->query($q);
		$nrows = $roster->db->num_rows($result);
		//$result = $roster->db->query($query);
				
                $x = ($limit > $nrows) ? $nrows : $limit;
                if( $nrows > 0 )
                {
                        while( $x > 0 )
                        {
                                list($news_id, $author, $date, $title, $content) = $roster->db->fetch($result);
								$item['author'] = $author;
								$item['date'] = $date;
                                $item['title'] = $title;
                                $item['url'] = makelink('util-news');
								$item['short_text'] = $content;

                                //$item['footer'] = 'this is a custom footer section great place for credits';

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
