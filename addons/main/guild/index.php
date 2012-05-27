<?php

roster_add_js($addon['dir'] . '/js/slideshow.js');
roster_add_css($addon['dir'] . 'styles.css');

include_once($addon['inc_dir'].'functions.lib.php');
$func = New mainFunctions;


// Add news if any was POSTed
if( isset($_POST['process']) && $_POST['process'] == 'process' )
{
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
			$func->newsUPDATE($_POST,$html);
		}
		else
		{
			$func->newsADD($_POST,$html);
		}
	}
	else
	{
		$roster->set_message($roster->locale->act['news_error_process'], '', 'error');
	}
}


$roster->tpl->assign_vars(array(
	'FACTION' => isset($roster->data['factionEn']) ? strtolower($roster->data['factionEn']) : false,
	'JSDIE'		=> $addon['dir'].'js'
));

$r = $func->makeUSERmenu($roster->output['show_menu']);
$usrmenu = '<ul style="line-height: 24px;">';
foreach($r['user'] as $i => $usr)
{
	$usrmenu .= '<li><div class="icon">
	<img src="'.$usr['ICON'].'" alt="" />
	<div class="mask"></div>
</div>
<a href="'.$usr['U_LINK'].'">'.$usr['NAME'].'</a></li>';
}
$usrmenu .= '</ul>';

$roster->tpl->assign_block_vars('right', array(
	'BLOCKNAME'  => 'User menu',
	'ICON'       => 'inv_misc_bag_26_spellfire',
	'BLOCK_DATA' => $roster->auth->getMenuLoginForm() . $usrmenu
));

// begin the session user detection
$bots = array(
	array('agent' => 'AdsBot-Google', 'name' =>'AdsBot [Google]'),
	array('agent' => 'ia_archiver', 'name' =>'Alexa [Bot]'),
	array('agent' => 'Scooter/', 'name' =>'Alta Vista [Bot]'),
	array('agent' => 'Ask Jeeves', 'name' =>'Ask Jeeves [Bot]'),
	array('agent' => 'Baiduspider+(', 'name' =>'Baidu [Spider]'),
	array('agent' => 'Exabot/', 'name' =>'Exabot [Bot]'),
	array('agent' => 'FAST Enterprise Crawler', 'name' =>'FAST Enterprise [Crawler]'),
	array('agent' => 'FAST-WebCrawler/', 'name' =>'FAST WebCrawler [Crawler]'),
	array('agent' => 'http://www.neomo.de/', 'name' =>'Francis [Bot]'),
	array('agent' => 'Gigabot/', 'name' =>'Gigabot [Bot]'),
	array('agent' => 'Mediapartners-Google', 'name' =>'Google Adsense [Bot]'),
	array('agent' => 'Google Desktop', 'name' =>'Google Desktop'),
	array('agent' => 'Feedfetcher-Google', 'name' =>'Google Feedfetcher'),
	array('agent' => 'Googlebot', 'name' =>'Google [Bot]'),
	array('agent' => 'heise-IT-Markt-Crawler Heise', 'name' =>'IT-Markt [Crawler]'),
	array('agent' => 'heritrix/1.', 'name' =>'Heritrix [Crawler]'),
	array('agent' => 'ibm.com/cs/crawler', 'name' =>'IBM Research [Bot]'),
	array('agent' => 'ICCrawler - ICjobs', 'name' =>'ICCrawler - ICjobs'),
	array('agent' => 'ichiro/', 'name' =>'ichiro [Crawler]'),
	array('agent' => 'MJ12bot/', 'name' =>'Majestic-12 [Bot]'),
	array('agent' => 'MetagerBot/', 'name' =>'Metager [Bot]'),
	array('agent' => 'msnbot-NewsBlogs/', 'name' =>'MSN NewsBlogs'),
	array('agent' => 'msnbot/', 'name' =>'MSN [Bot]'),
	array('agent' => 'msnbot-media/', 'name' =>'MSNbot Media'),
	array('agent' => 'NG-Search/', 'name' =>'NG-Search [Bot]'),
	array('agent' => 'http://lucene.apache.org/nutch/', 'name' =>'Nutch [Bot]'),
	array('agent' => 'NutchCVS/', 'name' =>'Nutch/CVS [Bot]'),
	array('agent' => 'OmniExplorer_Bot/', 'name' =>'OmniExplorer [Bot]'),
	array('agent' => 'online link validator', 'name' =>'Online link [Validator]'),
	array('agent' => 'psbot/0', 'name' =>'psbot [Picsearch]'),
	array('agent' => 'Seekbot/', 'name' =>'Seekport [Bot]'),
	array('agent' => 'Sensis Web Crawler', 'name' =>'Sensis [Crawler]'),
	array('agent' => 'SEO search Crawler/', 'name' =>'SEO Crawler'),
	array('agent' => 'Seoma [SEO Crawler]', 'name' =>'Seoma [Crawler]'),
	array('agent' => 'SEOsearch/', 'name' =>'SEOSearch [Crawler]'),
	array('agent' => 'Snappy/1.1 ( http://www.urltrends.com/ )', 'name' =>'Snappy [Bot]'),
	array('agent' => 'http://www.tkl.iis.u-tokyo.ac.jp/~crawler/', 'name' =>'Steeler [Crawler]'),
	array('agent' => 'SynooBot/', 'name' =>'Synoo [Bot]'),
	array('agent' => 'crawleradmin.t-info@telekom.de', 'name' =>'Telekom [Bot]'),
	array('agent' => 'TurnitinBot/', 'name' =>'TurnitinBot [Bot]'),
	array('agent' => 'voyager/1.0', 'name' =>'Voyager [Bot]'),
	array('agent' => 'W3 SiteSearch', 'name' =>'Crawler W3 [Sitesearch]'),
	array('agent' => 'W3C-checklink/', 'name' =>'W3C [Linkcheck]'),
	array('agent' => 'W3C_*Validator', 'name' =>'W3C [Validator]'),
	array('agent' => 'http://www.WISEnutbot.com', 'name' =>'WiseNut [Bot]'),
	array('agent' => 'yacybot', 'name' =>'YaCy [Bot]'),
	array('agent' => 'Yahoo-MMCrawler/', 'name' =>'Yahoo MMCrawler [Bot]'),
	array('agent' => 'Yahoo! DE Slurp', 'name' =>'Yahoo Slurp [Bot]'),
	array('agent' => 'Yahoo! Slurp', 'name' =>'Yahoo [Bot]'),
	array('agent' => 'YahooSeeker/', 'name' =>'YahooSeeker [Bot]'),
	array('agent' => 'bingbot/', 'name' =>'Bing [Bot]'),
);


$userlist_ary = $userlist_visible = array();
$logged_visible_online = $logged_hidden_online = $guests_online = $prev_user_id = 0;
$prev_session_ip ='';

$sqlg = "SELECT COUNT(DISTINCT session_ip) as num_guests , session_browser
			FROM " . $roster->db->table('sessions') . " WHERE `session_user_id` = '0'
			AND `session_time` >= '" . (time() - (60 * 10)) ."';";

$resultg = $roster->db->query($sqlg);
$guest = $roster->db->fetch($resultg);
$guests_online = $guest['num_guests'];
// lets get the bots..
$sx = "SELECT * FROM " . $roster->db->table('sessions') . " WHERE `session_user_id` = '0'
			AND `session_time` >= '" . (time() - (60 * 10)) ."';";

$d = $roster->db->query($sx);
$bot = array();
while ($r = $roster->db->fetch($d))
{
	foreach ($bots as $rx)
	{
		if ($rx['agent'] && preg_match('#' . str_replace('\*', '.*?', preg_quote($rx['agent'], '#')) . '#i', $r['session_browser']))
		{
			$bot[] = $rx['name'];
		}
	}
}
unset($sqlg);
$roster->db->free_result($resultg);

$sql = 'SELECT u.usr, u.id, s.*
	FROM ' . $roster->db->table('user_members') . ' u, ' . $roster->db->table('sessions') . ' s
	WHERE s.session_time >= ' . (time() - (60 * 15)) . ' AND u.id = s.session_user_id AND s.session_user_id != 0
	ORDER BY u.usr ASC, s.session_ip ASC';

$result = $roster->db->query($sql);
$user_online_link = array();
while ($row = $roster->db->fetch($result))
{
	// User is logged in and therefore not a guest
	if ($row['id'] != 0)
	{
		// Skip multiple sessions for one user
		if ($row['id'] != $prev_user_id)
		{
			$user_online_link[] = $row['usr'];
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

$online = '<span style="float:left;">Total:</span><span style="float:right;padding-right:10px;">'.($logged_visible_online+$guests_online).'</span>
<hr width="90%" />
<span style="float:left;">Registered:</span><span style="float:right;padding-right:10px;">'.$logged_visible_online.'</span><br />
<span style="float:left;">Guest:</span><span style="float:right;padding-right:10px;">'.$guests_online.'</span>
';
if (count($user_online_link) > 0) {
	$online .= '<br /><small><em>'. implode(', ', $user_online_link) .'</em></small>';
}
if (count($bot) > 0) {
	$online .= '<br /><small><em>Bots: '. implode(', ', $bot) .'</em></small>';
}

$roster->tpl->assign_block_vars('right', array(
	'BLOCKNAME'  => $roster->locale->act['whos_online'],
	'ICON'       => 'inv_misc_groupneedmore',
	'BLOCK_DATA' => $online
));

// init the plugin for this addon and display them

$func->_initPlugins();
foreach($func->block as $id => $info)
{
	$roster->tpl->assign_block_vars('right', array(
		'BLOCKNAME'  => $info['name'],
		'ICON'       => $info['icon'],
		'BLOCK_DATA' => $info['output']
	));
}
// end plugins

$queryb = "SELECT * FROM `" . $roster->db->table('banners', $addon['basename']) . "` WHERE `b_active` = '1' ORDER BY `id` DESC;";
$resultsb = $roster->db->query($queryb);
$num = 1;
$total = $roster->db->num_rows($resultsb);

$x = $y = '';
while( $rowb = $roster->db->fetch($resultsb) )
{
	if ($total == $num)
	{
		$e = true;
	}
	else
	{
		$e = false;
	}
	if ($num ==1)
	{
		$x = $rowb['b_title'];
		$y = $rowb['b_desc'];
	}

	//echo $row['title'].'-'.$row['poster'].'-'.$row['text'].'<br />';
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
	'FIRST1'      => $x,
	'FIRST2'      => $y,
	'S_ADD_NEWS'  => $roster->auth->getAuthorized($addon['config']['news_add']),
	'S_EDIT_NEWS' => $roster->auth->getAuthorized($addon['config']['news_edit']),
	'U_ADD_NEWS'  => makelink('guild-'.$addon['basename'].'-add')
));

$query = "SELECT `news`.*, "
	. "DATE_FORMAT(  DATE_ADD(`news`.`date`, INTERVAL " . $roster->config['localtimeoffset'] . " HOUR ), '" . $roster->locale->act['timeformat'] . "' ) AS 'date_format', "
	. "COUNT(`comments`.`comment_id`) comm_count "
	. "FROM `" . $roster->db->table('news',$addon['basename']) . "` news "
	. "LEFT JOIN `" . $roster->db->table('comments',$addon['basename']) . "` comments USING (`news_id`) "
	. "GROUP BY `news`.`news_id`"
	. "ORDER BY `news`.`date` DESC;";

$results = $roster->db->query($query);
$numn = 1;
$totaln = $roster->db->num_rows($results);

while( $row = $roster->db->fetch($results) )
{
	//echo $row['title'].'-'.$row['poster'].'-'.$row['text'].'<br />';
	$roster->tpl->assign_block_vars('news', array(
		'POSTER'    => $row['poster'],
		'NUM'       => $numn,
		'TEXT'      => $row['text'],
		'TITLE'     => $row['title'],
		'DATE'      => $row['date_format'],
		'U_EDIT'    => makelink('guild-'. $addon['basename'] .'-edit&amp;id='. $row['news_id']),
		'U_COMMENT' => makelink('guild-'. $addon['basename'] .'-comment&amp;id='. $row['news_id']),
		'U_EDIT'    => makelink('guild-'. $addon['basename'] .'-edit&amp;id='. $row['news_id']),
		'L_COMMENT' => ($row['comm_count'] != 1 ? sprintf($roster->locale->act['n_comments'], $row['comm_count']) : sprintf($roster->locale->act['n_comment'], $row['comm_count'])),
		'NEWS_TYPE' => $row['news_type']
	));
	$numn++;
}
unset($query);
$roster->db->free_result($results);

$roster->tpl->set_filenames(array(
	'main' => $addon['basename'] . '/index_main.html'
));
$roster->tpl->display('main');
