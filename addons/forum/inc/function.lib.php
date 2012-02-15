<?php
/*
*
*	this is my first attempt at a forum system bare with me...
*
*/
class forum
{
	function getForums()
	{
		global $roster, $addon;
		
		$forums = array();
		
		$queryb = "SELECT `forums`.*, `topics`.`author`, `topics`.`title` as t_title,"
		. "COUNT(`topics`.`topic_id`) as topic_count "
		. "FROM `" . $roster->db->table('forums',$addon['basename']) . "` forums "
		. "LEFT JOIN `" . $roster->db->table('topics',$addon['basename']) . "` topics USING (`forum_id`) "
		. "GROUP BY `forums`.`forum_id` "
		. "ORDER BY `forums`.`order_id` ASC; ";
		
		$resultsb = $roster->db->query($queryb);
		$num=1;
		$total = $roster->db->num_rows($resultsb);
		//$row = $roster->db->fetch($resultsb);
		//echo '<pre>';
		//print_r($row);
		//echo '</pre>';
		
		while( $rowb = $roster->db->fetch($resultsb) )
		{
			$querya = "SELECT * FROM `" . $roster->db->table('topics',$addon['basename']) . "` WHERE `forum_id` = '".$rowb['forum_id']."'"
			. "ORDER BY `date_update` DESC; ";
			$resulta = $roster->db->query($querya);
			$rowa = $roster->db->fetch($resulta);
			
			$forums[] = array(
				'title'		=> $rowb['title'],
				'posts'		=> $rowb['topic_count'],
				'forumid'	=> $rowb['forum_id'],
				't_poster'	=> $rowa['author'],
				't_title'	=> $rowa['title'],
				't_id'		=> $rowa['topic_id'],
				'access'	=> $rowb['access'],
				'order'		=> $rowb['order_id'],
				'active'	=> $rowb['active'],
				'desc'		=> $rowb['desc']);
		}
		
		return $forums;
		
	}
	
	function getTopics($forum)
	{
		global $roster, $addon;
		
		$forums = array();
		
		$queryb = "SELECT `topics`.*, `posts`.`poster_id`, `posts`.`post_subject` as t_title,"
		. "COUNT(`posts`.`post_id`) as topic_count "
		. "FROM `" . $roster->db->table('topics',$addon['basename']) . "` topics "
		. "LEFT JOIN `" . $roster->db->table('posts',$addon['basename']) . "` posts USING (`topic_id`) "
		. "WHERE `topics`.`forum_id` = '".$forum."' "
		. "GROUP BY `topics`.`topic_id` "
		. "ORDER BY `topics`.`topic_id` DESC; ";
		
		$resultsb = $roster->db->query($queryb);
		$num=1;
		$total = $roster->db->num_rows($resultsb);
		//$row = $roster->db->fetch($resultsb);
		//echo '<pre>';
		//print_r($row);
		//echo '</pre>';
		
		while( $rowb = $roster->db->fetch($resultsb) )
		{
			$querya = "SELECT * FROM `" . $roster->db->table('posts',$addon['basename']) . "` WHERE `topic_id` = '".$rowb['topic_id']."'"
			. "ORDER BY `post_time` DESC; ";
			$resulta = $roster->db->query($querya);
			$rowa = $roster->db->fetch($resulta);
			
			$forums[] = array(
				'title'		=> $rowb['title'],
				'posts'		=> $rowb['topic_count'],
				'topicid'	=> $rowb['topic_id'],
				'poster'	=> $rowb['author'],
				'access'	=> $rowb['access'],
				't_poster'	=> $rowa['post_username'],
				't_title'	=> $rowa['post_subject'],
				'desc'		=> $rowb['mics']);
		}
		
		return $forums;
		
	}
	
	function getPosts($forum)
	{
		global $roster, $addon;
		
		$forums = array();
		
		$queryb = "SELECT * FROM `" . $roster->db->table('posts',$addon['basename']) . "` WHERE `topic_id` = '".$forum."' ORDER BY `post_id` ASC; ";
		
		$resultsb = $roster->db->query($queryb);
		$num=1;
		$total = $roster->db->num_rows($resultsb);
		//$row = $roster->db->fetch($resultsb);
		//echo '<pre>';
		//print_r($row);
		//echo '</pre>';
		//  post_id  topic_id  forum_id  poster_id  post_time  post_username  enable_html  post_edit_time  post_edit_count  post_subject  post_text  
		while( $rowb = $roster->db->fetch($resultsb) )
		{
			$forums[] = array(
				'post_subject'		=> $rowb['post_subject'],
				'post_time'			=> $rowb['post_time'],
				'post_username'		=> $rowb['post_username'],
				'post_text'			=> $rowb['post_text'],
				'post_id'			=> $rowb['post_id'],
				'topic_id'			=> $rowb['topic_id'],
				'forum_id'			=> $rowb['forum_id']
			);
		}
		
		return $forums;
		
	}
	
	function getCrumbsb($topic)
	{
		global $roster, $addon;
		
		$queryb = "SELECT 
		`topics`.`title` as topic,
		`topics`.`topic_id` as topic_id,
		`forums`.`title` as forum, 
		`forums`.`forum_id` as forum_id 
		FROM `" . $roster->db->table('forums',$addon['basename']) . "` forums "
		. "LEFT JOIN `" . $roster->db->table('topics',$addon['basename']) . "` topics USING (`forum_id`) "
		. "WHERE `topics`.`topic_id` = '".$topic."' AND `forums`.`forum_id` = `topics`.`forum_id`; ";
		
		$resultsb = $roster->db->query($queryb);		
		$row = $roster->db->fetch($resultsb);

		$loc = '<a href="'.makelink('guild-'.$addon['basename'].'').'">Forums</a> > <a href="'.makelink('guild-'.$addon['basename'].'-forum&amp;id=' . $row['forum_id']).'">'.$row['forum'].'</a> > <a href="'.makelink('guild-'.$addon['basename'].'-topic&amp;tid=' . $row['topic_id']).'">'.$row['topic'].'</a>';
		return $loc;
	}
	
	function getCrumbsa($topic)
	{
		global $roster, $addon;
		
		$queryb = "SELECT 
		`forums`.`title` as forum, 
		`forums`.`forum_id` as forum_id 
		FROM `" . $roster->db->table('forums',$addon['basename']) . "` forums "
		. "WHERE `forums`.`forum_id` = '".$topic."'; ";
		
		$resultsb = $roster->db->query($queryb);		
		$row = $roster->db->fetch($resultsb);

		$loc = '<a href="'.makelink('guild-'.$addon['basename'].'').'">Forums</a> > <a href="'.makelink('guild-'.$addon['basename'].'-forum&amp;id=' . $row['forum_id']).'">'.$row['forum'].'</a>';
		return $loc;
	}
	
}
?>