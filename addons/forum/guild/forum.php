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
		
		case 'unlock':
			processLock($id,0);
			break;

		case 'lock':
			processLock($id,1);
			break;

		default:
			break;
	}
}

function processLock( $id , $mode )
	{
		global $roster, $addon, $installer;

		$query = "UPDATE `" . $roster->db->table('forums',$addon['basename']) . "` SET `locked` = '$mode' WHERE `forum_id` = '".$id."';";
		$result = $roster->db->query($query);
		if( !$result )
		{
			$roster->set_message('Database Error: ' . $roster->db->error() . '<br />SQL: ' . $query);
		}
		else
		{
			if ($mode == 1)
			{
				$roster->set_message($roster->locale->act['f_lock']);
			}
			else
			{
				$roster->set_message($roster->locale->act['f_unlock']);
			}
		}
	}
	
$info = $functions->getInfo('forum',$_GET['id']);
$forums = $functions->getTopics($_GET['id']);
$x = $functions->getCrumbsa($_GET['id']);
$roster->tpl->assign_vars(array(
			'CRUMB'			=> $x,
			'M_STARTTOPIC'	=> makelink('guild-'.$addon['basename'].'-addtopic&amp;id=' . $_GET['id']),
			'LOCKED'		=> ($info['locked'] == 1 ? true : false),
			'IMAGE'    		=> '<div class="icon"><img src="'.$addon['url_path'] .'images/topic_unread_locked.gif"></a></div>',
			'CANLOCK'		=> $roster->auth->getAuthorized( $addon['config']['forum_lock'] ),
			'L_ACTIVEU' 	=> ( $info['locked'] == 1 ? 'locked' : 'unlocked'),
			'L_ACTIVET'		=> ( $info['locked'] == 1 ? $roster->locale->act['lock'] : $roster->locale->act['unlock']),
			'L_ACTIVEOP'	=> ( $info['locked'] == 1 ? 'unlock' : 'lock'),
			'TOPIC_ID'		=> $info['forum_id'],
		));

	foreach($forums as $id =>$forum)
	{
		if( $roster->auth->getAuthorized( $forum['access'] ) )
		{
			$roster->tpl->assign_block_vars('forums', array(
					'FORUM_ID' 	=> $forum['topicid'],
					'FORUM_URL'	=> makelink('guild-'.$addon['basename'].'-topic&amp;tid=' . $forum['topicid']),
					'TITLE'		=> $forum['title'],
					'POSTS'		=> $forum['posts'],
					'POSTER'	=> $forum['poster'],
					'T_POSTER'	=> $forum['t_poster'],
					'T_TITLE'	=> $forum['t_title'],
					'LOCKED'	=> ($forum['locked'] == 1 ? true : false),
					'IMAGE'    	=> '<div class="icon"><img src="'.$addon['url_path'] .'images/topic_unread_locked.gif"></a></div>',
					'T_ACCESS'	=> $roster->auth->getAuthorized( $forum['access'] ),
					'DESC'		=> $forum['desc']
				));
		}
	}		
	
	$roster->tpl->set_filenames(array(
		'topic_main' => $addon['basename'] . '/topics.html',
		));

	$roster->tpl->display('topic_main');
	
	function createTopic()
	{
		global $roster, $addon;
		$value = $_POST['config_access'];
		if (is_array($value))
		{
			$access = implode(":",$value);
		}
		else
		{
			$access = $value;
		}
		if (empty($access))
		{
			$access = '0';
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	