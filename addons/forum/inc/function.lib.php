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
		
		$queryb = "SELECT `forums`.*, `topics`.`user`, `topics`.`title` as t_title, `topics`.`topic_id`,`topics`.`last_user`,`topics`.`date_update`,"
		. "COUNT(`topics`.`topic_id`) as topic_count "
		. "FROM `" . $roster->db->table('forums',$addon['basename']) . "` forums "
		. "LEFT JOIN `" . $roster->db->table('topics',$addon['basename']) . "` topics USING (`forum_id`) "
		. "GROUP BY `forums`.`forum_id` "
		. "ORDER BY `forums`.`parent_id` ASC, `forums`.`order_id` ASC,`topics`.`topic_id` DESC; ";
		
		$resultsb = $roster->db->query($queryb);
		$num=1;
		$total = $roster->db->num_rows($resultsb);
		
		while( $rowb = $roster->db->fetch($resultsb) )
		{
			
			if ($rowb['parent_id'] == 0)
			{
				$forums[$rowb['forum_id']] = array(
					'title'		=> $rowb['title'],
					'forumid'	=> $rowb['forum_id'],
					'locked'	=> $rowb['locked'],
					'access'	=> $rowb['access'],
					'access_post'	=> $rowb['access_post'],
					'forums'	=> array(),
				);
			}
			else
			{
			
				$queryp = "SELECT *, COUNT(`post_id`) as post_count FROM `" . $roster->db->table('posts',$addon['basename']) . "` ".
				"WHERE `forum_id` = '".$rowb['forum_id']."'";
				$resultp = $roster->db->query($queryp);
				$rowp = $roster->db->fetch($resultp);
			
				$forums[$rowb['parent_id']]['forums'][$rowb['forum_id']] = array(
					'title'		=> $rowb['title'],
					'posts'		=> $rowp['post_count'],
					'topics'	=> $rowb['topic_count'],
					'forumid'	=> $rowb['forum_id'],
					'parent_id'	=> $rowb['parent_id'],
					'locked'	=> $rowb['locked'],
					'icon'		=> $rowb['icon'],
					't_poster'	=> $rowb['last_user'],
					't_title'	=> $rowb['t_title'],
					't_time'	=> $rowb['date_update'],
					't_id'		=> $rowb['topic_id'],
					'access'	=> $rowb['access'],
					'order'		=> $rowb['order_id'],
					'active'	=> $rowb['active'],
					'access'	=> $rowb['access'],
					'access_post'	=> $rowb['access_post'],
					'desc'		=> $rowb['desc']
				);
			}
		}
		
		return $forums;
	}
	
	function getForumsa()
	{
		global $roster, $addon;
		
		$forums = array();
		
		$queryb = "SELECT `forums`.*, `topics`.`user`, `topics`.`title` as t_title,"
		. "COUNT(`topics`.`topic_id`) as topic_count "
		. "FROM `" . $roster->db->table('forums',$addon['basename']) . "` forums "
		. "LEFT JOIN `" . $roster->db->table('topics',$addon['basename']) . "` topics USING (`forum_id`) "
		. "GROUP BY `forums`.`forum_id` "
		. "ORDER BY `forums`.`order_id` ASC; ";
		
		$resultsb = $roster->db->query($queryb);
		$num=1;
		$total = $roster->db->num_rows($resultsb);
		
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
					'parent_id'	=> $rowb['parent_id'],
					'locked'	=> $rowb['locked'],
					't_poster'	=> $rowa['user'],
					'icon'		=> $rowb['icon'],
					't_title'	=> $rowa['title'],
					't_id'		=> $rowa['topic_id'],
					'access'	=> $rowb['access'],
					'order'		=> $rowb['order_id'],
					'active'	=> $rowb['active'],
					'access'	=> $rowb['access'],
					'access_post'	=> $rowb['access_post'],
					'desc'		=> $rowb['desc']
				);

		}
		
		return $forums;
	}
	
	function getInfo($table,$id)
	{
		global $roster, $addon;
		
		$querya = "SELECT * FROM `" . $roster->db->table($table.'s',$addon['basename']) . "` WHERE `".$table."_id` = '".$id."'";
		$resulta = $roster->db->query($querya);
		$rowa = $roster->db->fetch($resulta);
		
		return $rowa;
	}
	
	function getTopics($forum)
	{
		global $roster, $addon;
		
		$forums = array();
		
		$queryb = "SELECT `topics`.*, `posts`.`user_id`, `posts`.`post_subject` as t_title,"
		. "COUNT(`posts`.`post_id`) as topic_count "
		. "FROM `" . $roster->db->table('topics',$addon['basename']) . "` topics "
		. "LEFT JOIN `" . $roster->db->table('posts',$addon['basename']) . "` posts USING (`topic_id`) "
		. "WHERE `topics`.`forum_id` = '".$forum."' "
		. "GROUP BY `topics`.`topic_id` "
		. "ORDER BY `topics`.`topic_id` DESC; ";
		
		$resultsb = $roster->db->query($queryb);
		$num=1;
		$total = $roster->db->num_rows($resultsb);
		
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
				'forumid'	=> $rowb['forum_id'],
				'locked'	=> $rowb['locked'],
				'sticky'	=> $rowb['sticky'],
				'poster'	=> $rowb['user'],
				'l_poster'	=> $rowb['last_user'],
				'r_date'	=> $rowb['date_update'],
				'c_date'	=> $rowb['date_create'],
				'access'	=> $rowb['access'],
				't_poster'	=> $rowa['user'],
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
		
		while( $rowb = $roster->db->fetch($resultsb) )
		{
			$forums[] = array(
				'post_subject'		=> $rowb['post_subject'],
				'post_time'			=> $rowb['post_time'],
				'post_username'		=> $rowb['user'],
				'user_id'			=> $rowb['user_id'],
				'post_text'			=> $rowb['post_text'],
				'post_id'			=> $rowb['post_id'],
				'locked'			=> $rowb['locked'],
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
	
	/*
				Topic forum and post actions
	*/
	
	/*
		Forum actions
	*/
	
	/*
		Topic Actions
	*/
	function processLock( $id , $mode )
	{
		global $roster, $addon, $installer;

		$query = "UPDATE `" . $roster->db->table('topics',$addon['basename']) . "` SET `locked` = '$mode' WHERE `topic_id` = '".$id."';";
		$result = $roster->db->query($query);
		if( !$result )
		{
			$installer->seterrors('Database Error: ' . $roster->db->error() . '<br />SQL: ' . $query);
		}
		else
		{
			$installer->setmessages(sprintf($roster->locale->act['installer_activate_' . $mode] ));
		}
	}
	
	/*
		Post Actions
	*/
	
	/*
		Delete post
	*/
	function post_delete($id)
	{
		global $roster, $addon, $installer;
	}
	/*
		report post
	*/
	function post_report($id)
	{
		global $roster, $addon, $installer;
	}
	/*
		edit post
	*/
	function post_edit($id)
	{
		global $roster, $addon, $installer;
	}
	/*
		move post
	*/
	function post_move($id)
	{
		global $roster, $addon, $installer;
	}
	/*
		Reply post
	*/
	function post_reply($data,$topic_id,$forum_id)
	{
		global $roster, $addon;

		if( isset($data['html']) && $data['html'] == 1 && $roster->config['forum_html_posts'] >= 0 )
		{
			$html = 1;
		}
		else
		{
			$html = 0;
		}
		$querya = "SELECT * FROM `" . $roster->db->table('posts') . "` WHERE `topic_id` = '".$topic_id."';";
		$resulta = $roster->db->query($querya);
		$rowa = $roster->db->fetch($resulta);
		
		$inst = array(
			'topic_id'			=> $topic_id,
			'forum_id'			=> $forum_id,
			'user_id'			=> $data['author_id'],
			'post_time'			=> time(),
			'user'				=> $data['author'],
			'enable_html'		=> $html,
			'post_edit_time'	=> 0,
			'post_edit_count'	=> 0,
			'post_subject'		=> 'RE:'.$rowa['post_subject'].'',
			'post_text'			=> $data['text'],
		);
		$q = $roster->db->build_query( 'INSERT' , $inst );
		$r = $roster->db->query($q);
		
		if ($r)
		{
			$query = "UPDATE `" . $roster->db->table('topics') . "` SET `last_user` = '".$data['author']."',`date_update`='".time()."' WHERE `topic_id` = '".$topic_id."';";
			$result = $roster->db->query($query);
		}

	}
	/*
	############################################    End admin/mod actions
	*/
	
	function trackTopics($mode, $forum_id = false, $topic_id = false, $post_time = 0, $user_id = 0)
	{
		global $roster, $addon, $installer;
		
		$sql = 'UPDATE `' . $roster->db->table('topics_track',$addon['basename']) . "` SET `mark_time` = '" . (($post_time) ? $post_time : time()) . "' WHERE `user_id` = '".$roster->auth->user['id']."' AND `topic_id` = '".$topic_id."'";

			$result = $roster->db->query($sql);
			// insert row
			if (!$roster->db->affected_rows( ))
			{
				$sql_ary = array(
					'user_id'		=> (int) $roster->auth->user['id'],
					'topic_id'		=> (int) $topic_id,
					'forum_id'		=> (int) $forum_id,
					'mark_time'		=> ($post_time) ? (int) $post_time : time(),
				);
				$roster->db->query('INSERT INTO `' . $roster->db->table('topics_track',$addon['basename']) . "` " . $roster->db->build_query('INSERT', $sql_ary));
			}
	}
	
	function get_topic_tracking($forum_id, $topic_id, $forum_mark_time)
	{
		global $roster, $addon, $config, $user;

		$sql = 'SELECT mark_time FROM `' . $roster->db->table('topics_track',$addon['basename']) . "` WHERE `user_id` = '".$roster->auth->user['id']."' AND `topic_id` = '".$topic_id."'";
		
		$result = $roster->db->query($sql);
		$row = $roster->db->fetch($result);

		if ($row['mark_time'] < $forum_mark_time)
		{
			return false;
		}
		else
		{
			return true;
		}

		return false;
	}
	
	
}
?>