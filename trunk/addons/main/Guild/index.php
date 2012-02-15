<?php
$roster->output['show_header'] = true;
//$roster->output['show_menu'] = 'guild';
// Assign template vars

require (ROSTER_BASE . 'events/event.php');
$events = new events();
$roster->auth->GetMemberLogin();
$roster->tpl->assign_vars(array(
'FACTION' => isset($roster->data['factionEn']) ? strtolower($roster->data['factionEn']) : false,
'JSDIE'		=>$addon['dir'].'js',
'EVENTS' => $events->display()
));
roster_add_js('addons/' . $addon['basename'] . '/js/slideshow.js');
//roster_add_js('js/slideshow.js');
//roster_add_js$addon['dir']('js/swfobject.js');
roster_add_css($addon['dir'] . 'styles.css','module');



// Add news if any was POSTed
if( isset($_POST['process']) && $_POST['process'] == 'process' )
{
	if( !$roster->auth->getAuthorized( $addon['config']['news_add'] ) && !isset($_POST['id']) )
	{
		echo $roster->auth->getLoginForm($addon['config']['news_add']);
		return; //To the addon framework
	}
	if( !$roster->auth->getAuthorized( $addon['config']['news_edit'] ) && isset($_POST['id']) )
	{
		echo $roster->auth->getLoginForm($addon['config']['news_edit']);
		return; //To the addon framework
	}

	if( isset($_POST['author']) && !empty($_POST['author'])
		&& isset($_POST['title']) && !empty($_POST['title'])
		&& isset($_POST['news']) && !empty($_POST['news']) )
	{
		if( isset($_POST['html']) && $_POST['html'] == 1 && $addon['config']['news_html'] >= 0 )
		{
			$html = 1;
		}
		else
		{
			$html = 0;
		}

		if( isset($_POST['id']) )
		{
			$query = "UPDATE `" . $roster->db->table('news',$addon['basename']) . "` SET "
				. "`poster` = '" . $_POST['author'] . "', "
				. "`title` = '" . $_POST['title'] . "', "
				. "`text` = '" . $_POST['news'] . "', "
				. "`html` = '" . $html . "' "
				. "WHERE `news_id` = '" . $_POST['id'] . "';";

			if( $roster->db->query($query) )
			{
				$roster->set_message($roster->locale->act['news_edit_success']);
			}
			else
			{
				$roster->set_message('There was a DB error while editing the article.', '', 'error');
				$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
			}
		}
		else
		{
			$query = "INSERT INTO `" . $roster->db->table('news',$addon['basename']) . "` SET "
				. "`poster` = '" . $_POST['author'] . "', "
				. "`title` = '" . $_POST['title'] . "', "
				. "`text` = '" . $_POST['news'] . "', "
				. "`html` = '" . $html . "', "
				. "`date` = '". $roster->db->escape(gmdate('Y-m-d H:i:s')). "';";

			if( $roster->db->query($query) )
			{
				$roster->set_message($roster->locale->act['news_add_success']);
			}
			else
			{
				$roster->set_message('There was a DB error while adding the article.', '', 'error');
				$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
			}
		}
	}
	else
	{
		$roster->set_message($roster->locale->act['news_error_process'], '', 'error');
	}
}



$queryb = "SELECT * FROM `" . $roster->db->table('banners',$addon['basename']) . "` WHERE `b_active` = '1' ORDER BY `id` DESC;";
$resultsb = $roster->db->query($queryb);
$num=1;
$total = $roster->db->num_rows($resultsb);
	while( $rowb = $roster->db->fetch($resultsb) )
		{
			if ($total == $num)
			{ $e = true;}
			else
			{ $e = false;}
			if ($num ==1)
			{
				$x = $rowb['b_title'];
				$y = $rowb['b_desc'];
			}
			//echo $row['title'].'-'.$row['poster'].'-'.$row['text'].'<br>';
			$roster->tpl->assign_block_vars('banners', array(
					'B_DESC' 	=> $rowb['b_desc'],
					'B_URL'		=> $rowb['b_url'],
					'B_IMAGE'	=> $addon['url_path'].'images/'.$rowb['b_image'],
					'B_ID'		=> $rowb['b_id'],
					'B_TITLE'	=> $rowb['b_title'],
					'B_NUM'		=> $num,
					'B_NUMX'	=> $num-1,
					'B_END'		=> $e,
					'B_TOTAL'	=> $total
				));
			$num++;
		}

		
		$roster->tpl->assign_vars(array(
			'FIRST1' 		=> $x,
			'FIRST2' 		=> $y,
			//'S_EDIT_NEWS'	=> true,
			//'S_ADD_NEWS'    => true,
			'S_ADD_NEWS'  => $roster->auth->getAuthorized($addon['config']['news_add']),
			'S_EDIT_NEWS'  => $roster->auth->getAuthorized($addon['config']['news_edit']),
			'U_ADD_NEWS'	=> makelink('guild-'.$addon['basename'].'-add'),
		));

//$query = "SELECT * FROM `" . $roster->db->table('news',$addon['basename']) . "` ORDER BY `news_id` DESC;";

$query = "SELECT `news`.*, "
		. "DATE_FORMAT(  DATE_ADD(`news`.`date`, INTERVAL " . $roster->config['localtimeoffset'] . " HOUR ), '" . $roster->locale->act['timeformat'] . "' ) AS 'date_format', "
		. "COUNT(`comments`.`comment_id`) comm_count "
		. "FROM `" . $roster->db->table('news',$addon['basename']) . "` news "
		. "LEFT JOIN `" . $roster->db->table('comments',$addon['basename']) . "` comments USING (`news_id`) "
		. "GROUP BY `news`.`news_id`"
		. "ORDER BY `news`.`date` DESC;";
		
		
$results = $roster->db->query($query);
$numn=1;
$totaln = $roster->db->num_rows($results);
	while( $row = $roster->db->fetch($results) )
		{
			//echo $row['title'].'-'.$row['poster'].'-'.$row['text'].'<br>';
			$roster->tpl->assign_block_vars('news', array(
					'POSTER' 	=> $row['poster'],
					'NUM'		=> $numn,
					'TEXT'		=> $row['text'],
					'TITLE'		=> $row['title'],
					'DATE'		=> $row['date_format'],
					'U_EDIT'	=> makelink('guild-'.$addon['basename'].'-edit&amp;id=' . $row['news_id']),
					'U_COMMENT'  => makelink('guild-'.$addon['basename'].'-comment&amp;id=' . $row['news_id']),
					'U_EDIT'     => makelink('guild-'.$addon['basename'].'-edit&amp;id=' . $row['news_id']),
					'L_COMMENT' => ($row['comm_count'] != 1 ? sprintf($roster->locale->act['n_comments'],$row['comm_count']) : sprintf($roster->locale->act['n_comment'],$row['comm_count'])),
					'NEWS_TYPE'	=> $row['news_type']
				));
			$numn++;
		}

	
	//$roster->tpl->set_filenames( array ('main' => 'addons/main/templates/index.html',));
	$roster->tpl->set_filenames(array(
			'main' => $addon['basename'] . '/index_main.html'
			)
		);

	//$roster->tpl->set_handle('main', 'index_main.html');
	$roster->tpl->display('main');