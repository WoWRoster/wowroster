<?php

class questlist_search
{
    var $title;
    var $options;
    var $result = array();
    var $result_count = 0;
    var $link_next;
    var $link_prev;
	
    // class constructor
	
    function questlist_search() {
        global $roster, $addon;
        
		$quests[0] = 'All';
		$quest_list = $roster->db->query("SELECT zone, quest_name FROM `" . $roster->db->table('quests') . "` ORDER BY quest_name");
		while (list($zoneid, $questid) = $roster->db->fetch($quest_list)) {
			$quests[$zoneid] = $zoneid;
			$quests[$questid] = $questid;
		}
		$roster->db->free_result($quest_list);

        $this->basename = 'Quests List';
	
		      
		
    }

    function search($search, $url_search, $limit=10, $page=0) {
        global $roster, $addon;
        $first = $page*$limit;

        $zone = isset($_POST['zoneid']) ? intval($_POST['zoneid']) : 0;
		$questid = isset($_POST['questid']) ? intval($_POST['questid']) : 0;
		
        $search_id = ($questid == 0) ? '' : "quest_name='$questid' AND";
        $ret = '';
	    
        $result = $roster->db->query("SELECT `quest_name`
		 FROM `" . $roster->db->table('quests') . "`
		 WHERE `quest_name` LIKE '%$search%' LIMIT $first,".($limit+1));
        $nrows = $roster->db->num_rows($result);

      
            $x = ($limit > $nrows) ? $nrows : $limit;
		if ($nrows > 0) {
			while($x > 0) {
                (list($quest_name) = $roster->db->fetch($result));
                
                
                $item['title'] = $quest_name;
                $item['image'] = $roster->config['interface_url'].'Interface/Icons/inv_misc_note_02.jpg';
                
                $item['url'] = makelink('realm-questlist&realm=US-Alexstrasza&amp;questid='.urlencode($quest_name));
				
                
                //$item['footer'] = 'this is a custom footer section great place for credits';

                $this->add_result($item);
                unset($item);
				$x--;

             }
        }

        if ($page > 0) {
           $this->link_prev = '<a href="'.makelink('search&amp;page='.($page-1).'&amp;search='. $url_search .'&amp;s_addon='. $addon['addon']).'"><strong>'. $roster->locale->act['search_previous_matches'] . $addon['basename'] .'</strong></a>';
        }
        if ($nrows > $limit) {
            $this->link_next = '<a href="'.makelink('search&amp;page='.($page+1).'&amp;search='. $url_search .'&amp;s_addon='. $addon['addon']).'"><strong> '. $roster->locale->act['search_next_matches'] . $addon['basename'] .'</strong></a>';
        }
    }

    function add_result($resultarray) {
        $this->result[$this->result_count++] = $resultarray;
    }

}
