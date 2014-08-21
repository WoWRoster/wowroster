<?php

//$addon = getaddon('forum');
 
include( $addon['dir'] . 'inc/function.lib.php' );
$functions = new forum;

//roster_add_css(ROSTER_BASE . 'pages/forum/style.css','module');

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

		$query = "UPDATE `" . $roster->db->table('topics') . "` SET `locked` = '$mode' WHERE `topic_id` = '".$id."';";
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

//require_once (ROSTER_LIB . 'bbcode.php' );
//$bbcode = new bbcode();

$info = $functions->getInfo('topic',$_GET['tid']);
$forums = $functions->getPosts($_GET['tid']);
$x = $functions->getCrumbsb($_GET['tid']);
$roster->tpl->assign_vars(array(
			'CRUMB'			=> $x,
			'M_REPLYPOST'	=> makelink('guild-'.$addon['basename'].'-topic_reply&amp;tid=' . $_GET['tid']),
			'LOCKED'		=> ($info['locked'] == 1 ? true : false),
			'IMAGE'    		=> '<div class="icon"><img src="'.$addon['url_path'] .'images/topic_unread_locked.gif"></a></div>',
			'CANLOCK'		=> $roster->auth->getAuthorized( $addon['config']['forum_lock'] ),
			'CANPOST'		=> $roster->auth->getAuthorized( $addon['config']['forum_start_topic'] ),
			'L_ACTIVEU' 	=> ( $info['locked'] == 1 ? 'locked' : 'unlocked'),
			'L_ACTIVET'		=> ( $info['locked'] == 1 ? $roster->locale->act['lock'] : $roster->locale->act['unlock']),
			'L_ACTIVEOP'	=> ( $info['locked'] == 1 ? 'unlock' : 'lock'),
			'TOPIC_ID'		=> $info['topic_id'],
			'TOPIC_TITLE'		=> $info['title'],
			
		));
	foreach($forums as $id =>$forum)
	{
		$f=null;
		$u=null;
		$message ='';
			
		$u = $roster->auth->GetUserInfo($forum['user_id']);

		$bg ='';
		if ($roster->auth->_ingroup( '1', $u['access'] ))
		{
			$bg = ' xadmin';
		}
		else if ($roster->auth->_ingroup( '2', $u['access'] ))
		{
			$bg = ' xmod';
		}
		$message = $forum['post_text'];
		//$message = $bbcode->bbcodeParser($message);
		//$message = bbcode_nl2br($message);
		/*
		postmod
		<li class="edit-icon"><a href="" title="Edit post"><span>Edit post</span></a></li>
						<li class="delete-icon"><a href="" title="Delete post"><span>Delete post</span></a></li>
						<li class="report-icon"><a href="" title="Report this post"><span>Report this post</span></a></li>
						<li class="info-icon"><a href="" title="Information"><span>Information</span></a></li>
						<li class="quote-icon"><a href="" title="Reply with quote"><span>Reply with quote</span></a></li>
						
		*/
		$roster->tpl->assign_block_vars('forums', array(

			'POST_SUBJECT'		=> $forum['post_subject'],
			'POST_TIME'			=> date("F j, Y, g:i a", $forum['post_time']),//$forum['post_time'],
			'POST_USERNAME'		=> $forum['post_username'],
			//'POST_AVATAR'		=> $d,// poster_id
			//'POST_SIG'			=> $u['signature'],// poster_id
			//'POST_ISAV'			=> $f,
			'POST_MOD'			=> '',
			'POST_BG'			=> $roster->switch_row_class(),
			'POST_ADMIN'		=> $bg,
			'POST_TEXT'			=> $message,
			'POST_ID'			=> $forum['post_id'],
			'TOPIC_ID'			=> $forum['topic_id'],
			'FORUM_ID'			=> $forum['forum_id']
			));
			$f_id = $forum['forum_id'];
	}		
	
	$functions->trackTopics('', $f_id, $_GET['tid'], $post_time = 0, $user_id = 0);		
	
	$roster->tpl->set_filenames(array(
		'posts_main' => $addon['basename'] . '/forum_posts.html',
		));

	$roster->tpl->display('posts_main');
	
	if(!$roster->auth->allow_login)
	{
		$roster->auth->message = 'Sorry you must be logged in to post a reply';
		echo $roster->auth->getLoginForm();
		$roster->auth->message ='';
	}
	else
	{
		$roster->tpl->assign_vars(array(
			'S_ADD_TOPIC'		=> false,
			'S_HTML_ENABLE'		=> false,
			'S_TOPIC_HTML'		=> $addon['config']['forum_html_posts'],
			'S_POSTER'			=> $roster->auth->user['usr'],
			'S_POSTER_ID'		=> $roster->auth->user['id'],
			'U_ADD_FORMACTION'	=> '',
			'U_FORM_NAME'		=>'add_comment',
			'U_TEXT_NAME'		=>'text',
			'U_TYPE_ID'			=> $id,
			)
		);

		$roster->tpl->set_filenames(array('comments_add' => $addon['basename'] . '/forum_reply.html'));
		$roster->tpl->display('comments_add');
	}
	
	
	

	function postReply()
	{
		global $roster, $addon;

		if( isset($_POST['html']) && $_POST['html'] == 1 && $roster->config['forum_html_posts'] >= 0 )
		{
			$html = 1;
		}
		else
		{
			$html = 0;
		}
		$querya = "SELECT * FROM `" . $roster->db->table('posts') . "` WHERE `topic_id` = '".$_GET['tid']."';";
		$resulta = $roster->db->query($querya);
		$rowa = $roster->db->fetch($resulta);
		$q = "INSERT INTO `" . $roster->db->table('posts') . "` 
		(`topic_id`, `forum_id`, `user_id`, `post_time`, `user`, `enable_html`, `post_edit_time`, `post_edit_count`, `post_subject`, `post_text`)
		VALUES
		('".$rowa['topic_id']."', '".$rowa['forum_id']."', '".$_POST['author_id']."', '".time()."', '".$_POST['author']."', '".$html."', 0, 0, 'RE:".$rowa['post_subject']."', '".$_POST['text']."');";
		$r = $roster->db->query($q);
		if ($r)
		{
			$query = "UPDATE `" . $roster->db->table('topics') . "` SET `last_user` = '".$_POST['author']."',`date_update`='".time()."' WHERE `topic_id` = '".$rowa['topic_id']."';";
			$result = $roster->db->query($query);
		}

	
	}