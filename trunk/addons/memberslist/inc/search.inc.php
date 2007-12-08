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
 * @package    MembersList
 * @subpackage Search
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

class memberslist_search
{
        var $options;
        var $result = array();
        var $result_count = 0;
        var $start_search;
        var $stop_search;
        var $time_search;
        var $link_next;
        var $link_prev;
		var $open_table = '<table><th class="membersHeader ts_string">Lv</th><th class="membersHeader ts_string">Class</th><th class="membersHeader ts_string">Name</th><th class="membersHeader ts_string">Guild Rank</th>';
		var $close_table = '<table>';
        var $data = array();    // Addon data

        // class constructor
        function memberslist_search()
        {
                global $roster;

                $members[0] = 'All Members';
                $memberslist = $roster->db->query("SELECT `member_id`, `name` FROM `" . $roster->db->table('members') . "` ORDER BY `name`;");
                while (list($member_id, $name) = $roster->db->fetch($memberslist))
                {
                        $members[$name] = $name;
                }
                $roster->db->free_result($memberslist);
        }

        function search( $search , $url_search , $limit=10 , $page=0 )
        {
                global $roster;

                $first = $page*$limit;
                $q = "SELECT `member_id`, `name`, `server`, `region`, `guild_id`, `class`, `level`, `note`, `guild_rank`
                , `guild_title`, `zone`, `last_online`
                 FROM `" . $roster->db->table('members') . "`
                 WHERE (`member_id` LIKE '%$search%' or `name` LIKE '%$search%' or `server` LIKE '%$search%' or `region` LIKE '%$search%' or
                `guild_id` LIKE '%$search%' or  `class` LIKE '%$search%' or `level` LIKE '%$search%' or `note` LIKE '%$search%' or
                `guild_rank` LIKE '%$search%' or `guild_title` LIKE '%$search%' or `zone` LIKE '%$search%') GROUP BY member_id LIMIT $first,".($limit+1);

                $result = $roster->db->query($q);
                $nrows  = $roster->db->num_rows($result);
                $crows  = 0;

                $x = ($limit > $nrows) ? $nrows : $limit;
                if ($nrows > 0)
                {
                        while($x > 0)
                        {
                                list($member_id, $name, $server, $region, $guild_id, $class, $level, $note, $guild_rank, $guild_title, $zone, $last_online) = $roster->db->fetch($result);

                                $item['title'] = $name;
                                $item['date'] = $last_online;
                                //$item['url'] =   makelink("char-info&amp;a=c:$member_id");
				$item['html'] = $level.'</td><td>'.$class.'</td><td width="100%"><a href="' . makelink("char-info&amp;a=c:$member_id") . '"><strong>' . $name . '</strong></a></td><td>'.$guild_title;
                                $this->add_result($item);
                                unset($item);

                                $x--;
                        }
                }
                $this->start_search  = getmicrotime();
                $roster->db->fetch($result);
                $this->stop_search  = getmicrotime();
                //calculating the search time
                $this->time_search = ($this->stop_search - $this->start_search);
                // this would be much nicer if we'd join the comments with the articles and include
                // the comments results as a subset of the article results. Consider fetching comments
                // inside the above loop.


                if ($page>0)
                {
                        $this->link_prev = '<a href="'.makelink('search&amp;page='.($page-1).'&amp;search='. $url_search .'&amp;s_addon='. $this->data['basename']).'"><strong>'. $roster->locale->act['search_previous_matches'] . $this->data['fullname'] .'</strong></a>';
                }
                if (($nrows > $limit) || ($crows > $limit))
                {
                        $this->link_next = '<a href="'.makelink('search&amp;page='.($page+1).'&amp;search='. $url_search .'&amp;s_addon='. $this->data['basename']).'"><strong>' . $roster->locale->act['search_next_matches'] . $this->data['fullname'] .'</strong></a>';
                }
        }

        function add_result($resultarray)
        {
                $this->result[$this->result_count++] = $resultarray;
        }
}
