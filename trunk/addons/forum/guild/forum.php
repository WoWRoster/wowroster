<?php

include( $addon['dir'] . 'inc/function.lib.php' );
$functions = new forum;

if( isset( $_POST['type'] ) && !empty($_POST['type']) )
{
$op = ( isset($_POST['op']) ? $_POST['op'] : '' );
$id = ( isset($_POST['id']) ? $_POST['id'] : $_GET['id'] );

	switch( $_POST['type'] )
	{
		case 'newTopic':
			createTopic();
			
		break;

		default:
			break;
	}
}

$forums = $functions->getTopics($_GET['id']);
$x = $functions->getCrumbsa($_GET['id']);
$roster->tpl->assign_vars(array(
			'CRUMB'			=> $x,
			'M_STARTTOPIC'	=> makelink('guild-'.$addon['basename'].'-addtopic&amp;id=' . $_GET['id']),
		));

	foreach($forums as $id =>$forum)
	{
		$roster->tpl->assign_block_vars('forums', array(
					'FORUM_ID' 	=> $forum['topicid'],
					'FORUM_URL'	=> makelink('guild-'.$addon['basename'].'-topic&amp;tid=' . $forum['topicid']),
					'TITLE'		=> $forum['title'],
					'POSTS'		=> $forum['posts'],
					'POSTER'	=> $forum['poster'],
					'T_POSTER'	=> $forum['t_poster'],
					'T_TITLE'	=> $forum['t_title'],
					'T_ACCESS'	=> $roster->auth->getAuthorized( $forum['access'] ),
					'DESC'		=> $forum['desc']
				));
	}		
	
	$roster->tpl->set_filenames(array(
		'topic_main' => $addon['basename'] . '/topics.html',
		));

	$roster->tpl->display('topic_main');
	
	function createTopic()
	{
		global $roster, $addon;
		$value = $_POST['access'];
		if (is_array($value))
		{
			$access = implode(":",$value);
		}
		else
		{
			$access = $value;
		}
		if( isset($_POST['html']) && $_POST['html'] == 1 && $addon['config']['forum_html_posts'] >= 0 )
		{
			$html = 1;
		}
		else
		{
			$html = 0;
		}
		
		$query = "INSERT INTO `" . $roster->db->table('topics',$addon['basename']) . "` 
			(`forum_id`, `title`, `access`, `date_update`, `date_create`, `author`, `last_poster`, `mics`, `active`) VALUES
			(".$_GET['id'].",'".$_POST['title']."','".$access."','". $roster->db->escape(gmdate('Y-m-d H:i:s')). "',	'". $roster->db->escape(gmdate('Y-m-d H:i:s')). "',	'".$_POST['author']."',	'',	'',	'1');";
		$result = $roster->db->query($query);
		$t_id = $roster->db->insert_id();
		
		$q = "INSERT INTO `" . $roster->db->table('posts',$addon['basename']) . "` 
		(`topic_id`, `forum_id`, `poster_id`, `post_time`, `post_username`, `enable_html`, `post_edit_time`, `post_edit_count`, `post_subject`, `post_text`)
		VALUES
		('".$t_id."', '".$_GET['id']."', 1, '".time()."', '".$_POST['author']."', '".$html."', 0, 0, '".$_POST['title']."', '".$_POST['text']."');";
		$r = $roster->db->query($q);
		
		if ($result && $r)
		{
			$roster->set_message('Topic was created.', '', 'notice');
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	