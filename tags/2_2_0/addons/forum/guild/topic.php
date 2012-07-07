<?php

include( $addon['dir'] . 'inc/function.lib.php' );
$functions = new forum;

if( isset( $_POST['type'] ) && !empty($_POST['type']) )
{
$op = ( isset($_POST['op']) ? $_POST['op'] : '' );
$id = ( isset($_POST['tid']) ? $_POST['tid'] : $_GET['tid'] );

	switch( $_POST['type'] )
	{
		case 'newTopic':
			postReply();
			
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

		$query = "UPDATE `" . $roster->db->table('topics',$addon['basename']) . "` SET `locked` = '$mode' WHERE `topic_id` = '".$id."';";
		$result = $roster->db->query($query);
		if( !$result )
		{
			$roster->set_message('Database Error: ' . $roster->db->error() . '<br />SQL: ' . $query);
		}
		else
		{
			if ($mode == 1)
			{
				$roster->set_message($roster->locale->act['t_lock']);
			}
			else
			{
				$roster->set_message($roster->locale->act['t_unlock']);
			}
		}
	}

$info = $functions->getInfo('topic',$_GET['tid']);
$forums = $functions->getPosts($_GET['tid']);
$x = $functions->getCrumbsb($_GET['tid']);
$roster->tpl->assign_vars(array(
			'CRUMB'			=> $x,
			'M_REPLYPOST'	=> makelink('guild-'.$addon['basename'].'-topic_reply&amp;tid=' . $_GET['tid']),
			'LOCKED'		=> ($info['locked'] == 1 ? true : false),
			'IMAGE'    		=> '<div class="icon"><img src="'.$addon['url_path'] .'images/topic_unread_locked.gif"></a></div>',
			'CANLOCK'		=> $roster->auth->getAuthorized( $addon['config']['forum_lock'] ),
			'L_ACTIVEU' 	=> ( $info['locked'] == 1 ? 'locked' : 'unlocked'),
			'L_ACTIVET'		=> ( $info['locked'] == 1 ? $roster->locale->act['lock'] : $roster->locale->act['unlock']),
			'L_ACTIVEOP'	=> ( $info['locked'] == 1 ? 'unlock' : 'lock'),
			'TOPIC_ID'		=> $info['topic_id'],
			
		));
	foreach($forums as $id =>$forum)
	{
		$f=null;
		$d = getAV($forum['post_username']);
		if ($d == '')
		{
			$f = 0;
		}else {$f = 1;}
		
		$roster->tpl->assign_block_vars('forums', array(

			'POST_SUBJECT'		=> $forum['post_subject'],
			'POST_TIME'			=> date("M d Y H:i:s", $forum['post_time']),//$forum['post_time'],
			'POST_USERNAME'		=> $forum['post_username'],
			'POST_AVATAR'		=> getAV($forum['post_username']),
			'POST_ISAV'			=> $f,
			'POST_TEXT'			=> $forum['post_text'],
			'POST_ID'			=> $forum['post_id'],
			'TOPIC_ID'			=> $forum['topic_id'],
			'FORUM_ID'			=> $forum['forum_id']
			));
	}		
	
	$roster->tpl->set_filenames(array(
		'posts_main' => $addon['basename'] . '/posts.html',
		));

	$roster->tpl->display('posts_main');
	
	function getAV($user)
	{
		global $roster;
		$user_is_active = active_addon('user');
		$siggen_is_active = active_addon('siggen');
		$av= null;
		if ($user_is_active == 1 && $siggen_is_active ==1)
		{
		
			$query = 'SELECT * FROM `'.$roster->db->table('user_members').'` AS user '.
			'LEFT JOIN `'.$roster->db->table('profile', 'user').'` AS profile ON `user`.`id` = `profile`.`uid` '.
			'WHERE `user`.`usr` = "' . $user . '" ';
			$result = $roster->db->query($query);
			$rw = $roster->db->fetch($result);
			if (!empty($rw['avatar']))
			{
				$av = '<img src="'.urldecode($rw['avatar']).'" ></a>';
			}
		}
		
		return $av;
		
	}
	function postReply()
	{
		global $roster, $addon;

		if( isset($_POST['html']) && $_POST['html'] == 1 && $addon['config']['forum_html_posts'] >= 0 )
		{
			$html = 1;
		}
		else
		{
			$html = 0;
		}
		$querya = "SELECT * FROM `" . $roster->db->table('posts',$addon['basename']) . "` WHERE `topic_id` = '".$_GET['tid']."';";
		$resulta = $roster->db->query($querya);
		$rowa = $roster->db->fetch($resulta);
		$q = "INSERT INTO `" . $roster->db->table('posts',$addon['basename']) . "` 
		(`topic_id`, `forum_id`, `poster_id`, `post_time`, `post_username`, `enable_html`, `post_edit_time`, `post_edit_count`, `post_subject`, `post_text`)
		VALUES
		('".$rowa['topic_id']."', '".$rowa['forum_id']."', 1, '".time()."', '".$_POST['author']."', '".$html."', 0, 0, 'RE:".$rowa['post_subject']."', '".$_POST['text']."');";
		$r = $roster->db->query($q);

	
	}