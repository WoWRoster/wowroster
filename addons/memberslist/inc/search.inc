<?php

class memberslist_search
{
	var $title;
	var $options;
	var $result = array();
	var $result_count = 0;
	var $link_next;
	var $link_prev;

	// class constructor
	function memberslist_search() {
		global $roster, $addon;
		$members[0] = 'All Members';
		$memberslist = $roster->db->query("SELECT member_id, name FROM `" . $roster->db->table('members') . "` ORDER BY name");
		while (list($member_id, $name) = $roster->db->fetch($memberslist)) {
			$members[$name] = $name;
		}
		$roster->db->free_result($memberslist);

		$this->basename = 'Members List';
		$this->icon = $roster->config['interface_url'] . 'Interface/Icons/inv_letter_06.jpg';

		
	}

	function search($search, $url_search, $limit=10, $page=0) {
		global $roster, $addon;
		$first = $page*$limit;
		
		$q = "SELECT `member_id`, `name`, `server`, `region`, `guild_id`, `class`, `level`, `note`, `guild_rank`
		, `guild_title`, `zone`, `last_online`
		 FROM `" . $roster->db->table('members') . "`
		 WHERE (`member_id` LIKE '%$search%' or `name` LIKE '%$search%' or `server` LIKE '%$search%' or `region` LIKE '%$search%' or
		`guild_id` LIKE '%$search%' or	`class` LIKE '%$search%' or `level` LIKE '%$search%' or `note` LIKE '%$search%' or
		`guild_rank` LIKE '%$search%' or `guild_title` LIKE '%$search%' or `zone` LIKE '%$search%') GROUP BY member_id LIMIT $first,".($limit+1);
        
				
		$result = $roster->db->query($q);
		$nrows	= $roster->db->num_rows($result);
		$crows  = 0;

		$x = ($limit > $nrows) ? $nrows : $limit;
		if ($nrows > 0) {
			while($x > 0) {
				list($member_id, $name, $server, $region, $guild_id, $class, $level, $note, $guild_rank, $guild_title, $zone
				, $last_online) = $roster->db->fetch($result);
				
				$item['title'] = $name;
				
				$item['date'] = $last_online;
				$item['url'] = 	 makelink("char-info&amp;member=$member_id");
				

				$this->add_result($item);
				unset($item);

				$x--;
			}
		}

		$roster->db->fetch($result);
		
		// this would be much nicer if we'd join the comments with the articles and include
		// the comments results as a subset of the article results. Consider fetching comments
		// inside the above loop.
		

		if ($page>0) {
			$this->link_prev = '<a href="'.makelink('search&amp;page='.($page-1).'&amp;search='. $url_search .'&amp;s_addon='. $addon['addon']).'"><strong>'. $roster->locale->act['search_previous_matches'] . $addon['basename'] .'</strong></a>';
		}
		if (($nrows > $limit) || ($crows > $limit)) {
			$this->link_next = '<a href="'.makelink('search&amp;page='.($page+1).'&amp;search='. $url_search .'&amp;s_addon='. $addon['addon']).'"><strong>' . $roster->locale->act['search_next_matches'] . $addon['basename'] .'</strong></a>';
		}

	}

	function add_result($resultarray) {
		$this->result[$this->result_count++] = $resultarray;
	}
}
