<?php

include( $addon['dir'] . 'inc/function.lib.php' );
$functions = new forum;

	//$view->extend('forum/forum_template', 'content', array('title' => $view->lang->get('forum')->get('forums'))); 
	//
	$forums = $functions->getForums();

	foreach($forums as $id =>$forum)
	{
		if( $roster->auth->getAuthorized( $forum['access'] ) )
		{
			$roster->tpl->assign_block_vars('forums', array(
					'FORUM_ID' 	=> $forum['forumid'],
					'LOCKED' 	=> $forum['locked'],
					'FORUM_URL'	=> makelink('guild-'.$addon['basename'].'-forum&amp;id=' . $forum['forumid']),
					'TITLE'		=> $forum['title'],
					'POSTS'		=> $forum['posts'],
					'POSTER'	=> $forum['t_poster'],
					'P_TITLE'	=> $forum['t_title'],
					'P_URL'		=> makelink('guild-'.$addon['basename'].'-topic&amp;tid=' . $forum['t_id']),
					'DESC'		=> $forum['desc']
				));
		}
	}		
	
	$roster->tpl->set_filenames(array(
		'forum_main' => $addon['basename'] . '/index.html',
		));

	$roster->tpl->display('forum_main');

?>