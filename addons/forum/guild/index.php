<?php

//$addon = getaddon('forum');
 
include( $addon['dir'] . 'inc/function.lib.php' );
$functions = new forum;

//roster_add_css(ROSTER_BASE . 'pages/forum/style.css','module');

	//$view->extend('forum/forum_template', 'content', array('title' => $view->lang->get('forum')->get('forums'))); 
	//
	$forums = $functions->getForums();

	/*
	echo '<pre>';
	print_r($forums);
	echo '</pre>';
	*/

	foreach($forums as $parent_id => $forum)
	{
		if( $roster->auth->getAuthorized( $forum['access'] ) )
		{
			$roster->tpl->assign_block_vars('forums', array(
					'FORUM_ID' 	=> $forum['forumid'],
					'LOCKED' 	=> $forum['locked'],
					//'FORUM_URL'	=> makelink('guild-'.$addon['basename'].'-forum&amp;id=' . $forum['forumid']),
					'TITLE'		=> $forum['title']
					//'P_URL'		=> makelink('guild-'.$addon['basename'].'-topic&amp;tid=' . $forum['t_id'])
				));
		}
		foreach($forum['forums'] as $forum_id => $data)
		{
			if( $roster->auth->getAuthorized( $data['access'] ) )
			{
				$is_read = false;//$functions->get_topic_tracking($forum['forum_id'], $forum['topicid'], $forum['r_date']);
				$icon = '';
				if(!empty($data['icon']))
				{
					if (file_exists($roster->config['img_url'] . 'class/' . $data['icon'] . '.png'))
					{
						$icon = $roster->config['img_url'] . 'class/' . $data['icon'] . '.png';
					}
					else if (remoteFileExists($roster->config['interface_url'] . 'Interface/Icons/' . $data['icon'] . '.png'))
					{
						$icon = $roster->config['interface_url'] . 'Interface/Icons/' . $data['icon'] . '.png';
					}
					else
					{
						$icon = $roster->config['img_url'] . '40X40_Blank_Icon_black.jpg';
					}
				}
				else
				{
					$icon = $roster->config['img_url'] . '40X40_Blank_Icon_black.jpg';
				}
				$roster->tpl->assign_block_vars('forums.subforum', array(
						'FORUM_ID' 		=> $data['forumid'],
						'LOCKED' 		=> $data['locked'],
						'FORUM_URL'		=> makelink('guild-'.$addon['basename'].'-forum&amp;id=' . $data['forumid']),
						'TITLE'			=> $data['title'],
						'POSTS'			=> $data['posts'],
						'TOPICS'		=> $data['topics'],
						'L_POSTER'		=> (isset($data['t_poster']) ? $data['t_poster'] : ''),
						'L_POST_TIME'	=> (isset($data['t_time']) ? date("F j, Y, g:i a", $data['t_time']) : ''),
						'ICON'			=> $icon,
						'READ'			=> $is_read,
						'LOCKED'		=> ($forum['locked'] == 1 ? true : false),
						'IMAGEL'    	=> ($forum['locked'] == 1 ? '_locked' : ''),
						'DESC'			=> $data['desc']
					));
			}
		}
	}		
		function remoteFileExists($url) 
	{
		$curl = curl_init($url);

		//don't fetch the actual page, you only want to check the connection is ok
		curl_setopt($curl, CURLOPT_NOBODY, true);

		//do request
		$result = curl_exec($curl);

		$ret = false;

		//if request did not fail
		if ($result !== false) {
			//if request was ok, check response code
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  

			if ($statusCode == 200) {
				$ret = true;   
			}
		}

		curl_close($curl);

		return $ret;
	}
	//$roster->tpl->set_filenames(array('forum_main' => 'forum_index.html'));
	$roster->tpl->set_handle('forum_main', $addon['basename'] . '/forum_index.html');
$roster->tpl->display('forum_main');


?>