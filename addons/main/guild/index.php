<?php

roster_add_js('addons/' . $addon['basename'] . '/js/slideshow.js');
roster_add_css($addon['dir'] . 'styles.css','module');
$roster->cache->cleanCache();
	// Add news if any was POSTed
	if( isset($_POST['process']) && $_POST['process'] == 'process' )
	{
/*	
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
*/
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
///*
	
	$roster->tpl->assign_vars(array(
			'FACTION' => isset($roster->data['factionEn']) ? strtolower($roster->data['factionEn']) : false,
			'JSDIE'		=>$addon['dir'].'js'
		)
	);

	$r = makeUSERmenu($roster->output['show_menu']);
	$usrmenu = '<br><ul style="line-height: 24px;">';
	foreach($r['user'] as $i => $usr)
	{
		$usrmenu .= '<li><div class="icon">
							<img src="'.$usr['ICON'].'" alt="" />
							<div class="mask"></div>
						</div><a href="'.$usr['U_LINK'].'">'.$usr['NAME'].'</a></li>';
	}
	$usrmenu .= '</ul>';

	$roster->tpl->assign_block_vars('right', array(
			'BLOCKNAME' 	=> 'User menu',
			'ICON'			=> 'inv_misc_bag_26_spellfire',
			'BLOCK_DATA'	=> $roster->auth->getLoginForm().$usrmenu
		)
	);
//*/
// begin the session user detection

	$userlist_ary = $userlist_visible = array();
	$logged_visible_online = $logged_hidden_online = $guests_online = $prev_user_id = 0;
	$prev_session_ip ='';
	

	$sqlg = "SELECT COUNT(DISTINCT session_ip) as num_guests
				FROM " . $roster->db->table('sessions') . " WHERE `session_user_id` = '0'
					AND `session_time` >= '" . (time() - (60 * 10)) ."';";

	$resultg = $roster->db->query($sqlg);
	$guest = $roster->db->fetch($resultg);
	$guests_online = $guest['num_guests'];
	unset($sqlg);
	$roster->db->free_result($resultg);

	$sql = 'SELECT u.usr, u.id, s.*
		FROM ' . $roster->db->table('user_members') . ' u, ' . $roster->db->table('sessions') . ' s
		WHERE s.session_time >= ' . (time() - (60 * 15)) .
			' AND u.id = s.session_user_id AND s.session_user_id != 0
		ORDER BY u.usr ASC, s.session_ip ASC';
	$result = $roster->db->query($sql);
	$user_online_link = '';
	while ($row = $roster->db->fetch($result))
	{
		// User is logged in and therefore not a guest
		if ($row['id'] != 0)
		{
			// Skip multiple sessions for one user
			if ($row['id'] != $prev_user_id)
			{
					$user_online_link .= '<em>'.$row['usr'].'<em>,';
					$logged_visible_online++;
			}
			$prev_user_id = $row['id'];
		}
		else
		{
			// Skip multiple sessions for one user
			if ($row['session_ip'] != $prev_session_ip)
			{
				$guests_online++;
			}
		}
		$prev_session_ip = $row['session_ip'];
	}
	unset($sql);
	$roster->db->free_result($result);
	
	$online = '<span style="float:left;">Total:</span><span style="float:right;padding-right:10px;">'.($logged_visible_online+$guests_online).'</span><br /><hr width="90%" />
			<span style="float:left;">Registered:</span><span style="float:right;padding-right:10px;">'.$logged_visible_online.'</span><br />
			<span style="float:left;">Guest:</span><span style="float:right;padding-right:10px;">'.$guests_online.'</span><br />
			<small>
				'.$user_online_link.'
			</small>
			';
	
	//$online = '';
	$roster->tpl->assign_block_vars('right', array(
					'BLOCKNAME' 	=> 'Who is online',
					'ICON'			=> 'inv_misc_groupneedmore',
					'BLOCK_DATA'	=> $online
				));

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
	unset($queryb);
	$roster->db->free_result($resultsb);

	$roster->tpl->assign_vars(array(
		'FIRST1' 		=> $x,
		'FIRST2' 		=> $y,
		//'S_EDIT_NEWS'	=> true,
		//'S_ADD_NEWS'    => true,
		'S_ADD_NEWS'  => $roster->auth->getAuthorized($addon['config']['news_add']),
		'S_EDIT_NEWS'  => $roster->auth->getAuthorized($addon['config']['news_edit']),
		'U_ADD_NEWS'	=> makelink('guild-'.$addon['basename'].'-add')
	));

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
	unset($query);
	$roster->db->free_result($results);

	$roster->tpl->set_filenames(array(
			'main' => $addon['basename'] . '/index_main.html'
			)
		);
	$roster->tpl->display('main');

	function makeUSERmenu( $sections )
	{
		global $roster;

		// Save current locale array
		// Since we add all locales for button name localization, we save the current locale array
		// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
		$localetemp = $roster->locale->wordings;
		
		$menue = array();

		// Add all addon locale files
		foreach( $roster->addon_data as $addondata )
		{
			foreach( $roster->multilanguages as $lang )
			{
				$roster->locale->add_locale_file(ROSTER_ADDONS . $addondata['basename'] . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php',$lang);
			}
		}

		$section = "'" . implode("','",array_keys($sections)) . "'";

		// --[ Fetch button list from DB ]--
		$query = "SELECT `mb`.*, `a`.`basename` "
			   . "FROM `" . $roster->db->table('menu_button') . "` AS mb "
			   . "LEFT JOIN `" . $roster->db->table('addon') . "` AS a "
			   . "ON `mb`.`addon_id` = `a`.`addon_id` "
			   . "WHERE `a`.`addon_id` IS NULL "
			   . "OR `a`.`active` = 1;";

		$result = $roster->db->query($query);

		if (!$result)
		{
			die_quietly('Could not fetch buttons from database .  MySQL said: <br />' . $roster->db->error(),'Roster',__FILE__,__LINE__,$query);
		}

		while ($row = $roster->db->fetch($result,SQL_ASSOC))
		{
			$palet['b' . $row['button_id']] = $row;
		}

		$roster->db->free_result($result);

		// --[ Fetch menu configuration from DB ]--
		$query = "SELECT * FROM `" . $roster->db->table('menu') . "` WHERE `section` IN (" . $section . ") ORDER BY `config_id`;";

		$result = $roster->db->query($query);

		if (!$result)
		{
			die_quietly('Could not fetch menu configuration from database. MySQL said: <br />' . $roster->db->error(),'Roster',__FILE__,__LINE__,$query);
		}

		while($row = $roster->db->fetch($result,SQL_ASSOC))
		{
			$data[$row['section']] = $row;
		}

		$roster->db->free_result($result);

		$page = array();
		$arrayButtons = array();

		foreach( $sections as $name => $visible )
		{
			if( isset($data[$name]) )
			{
				$page[$name] = $data[$name];
			}
		}

		// --[ Parse DB data ]--
		foreach( $page as $name => $value )
		{
			$config[$name] = explode(':',$value['config']);
			foreach( $config[$name] as $pos=>$button )
			{
				if( isset($palet[$button]) )
				{
					$arrayButtons[$name][$pos] = $palet[$button];
				}
			}
		}

		foreach( $arrayButtons as $id => $page )
		{
			switch( $id )
			{
				case 'char':
					$panel_label = $roster->data['name'] . ' @ ' . $roster->data['region'] . '-' . $roster->data['server'];
					break;

				default:
					$panel_label = (isset($roster->locale->act['menupanel_' . $id]) ? sprintf($roster->locale->act['menu_header_scope_panel'], $roster->locale->act['menupanel_' . $id]) : '');
					break;
			}

			$menue[$panel_label][] = array(
				'ID' => $id,
				'OPEN' => !$sections[$id],
				'LABEL' => $panel_label
				);

			foreach( $page as $button )
			{
				if( !empty($button['icon']) )
				{
					if( strpos($button['icon'],'.') !== false )
					{
						$button['icon'] = ROSTER_PATH . 'addons/' . $button['basename'] . '/images/' . $button['icon'];
					}
					else
					{
						$button['icon'] = $roster->config['interface_url'] . 'Interface/Icons/' . $button['icon'] . '.' . $roster->config['img_suffix'];
					}
				}
				else
				{
					$button['icon'] = $roster->config['interface_url'] . 'Interface/Icons/inv_misc_questionmark.' . $roster->config['img_suffix'];
				}

				if( !in_array($button['scope'],array('util','user','realm','guild','char')) || $button['addon_id'] == 0 )
				{
					$button['url'] = makelink($button['url']);
				}
				elseif( substr($button['url'],0,7) != 'http://')
				{
					$button['url'] = makelink($button['scope'] . '-' . $button['basename'] . (empty($button['url']) ? '' : '-' . $button['url']));
				}

				$button['title'] = isset($roster->locale->act[$button['title']]) ? $roster->locale->act[$button['title']] : $button['title'];
				if( strpos($button['title'],'|') )
				{
					list($button['title'],$button['tooltip']) = explode('|',$button['title'],2);
					$button['tooltip'] = ' ' . makeOverlib($button['tooltip'],$button['title'],'',1,'',',WRAP');
				}
				else
				{
					$button['tooltip'] = ' ' . makeOverlib($button['title']);
				}

				$menue[''.$button['scope'].''][] = array(
					'TOOLTIP'  => $button['tooltip'],
					'ICON'     => $button['icon'],
					'NAME'     => $button['title'],
					'SCOPE'    => $button['scope'],
					'BASENAME' => $button['basename'],
					'U_LINK'   => $button['url']
					);
			}
		}

		// Restore our locale array
		unset($localetemp);
		return $menue;
	}