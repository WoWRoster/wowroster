<?php

roster_add_js($addon['url'] . 'js/jquery.easing.1.3.js');
roster_add_js($addon['url'] . 'js/camera.js');

roster_add_css($addon['tpl_url'] . 'camera.css');

include_once($addon['inc_dir'] . 'functions.lib.php');
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

$queryb = "SELECT * FROM `" . $roster->db->table('slider', $addon['basename']) . "` WHERE `b_active` = '1' ORDER BY `id` DESC;";
$resultsb = $roster->db->query($queryb);
$num = 1;
$total = $roster->db->num_rows($resultsb);

$slider_js = array();

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
	$target_path = $addon['dir'] .'images/'. $rowb['b_image'];
	if (!file_exists($addon['dir'] .'images/slider-'. $rowb['b_image']) )
	{
		$func->image_resize($target_path, $addon['dir'] .'images/slider-'. $rowb['b_image'], 600, 300);
	}
	if (!file_exists($addon['dir'] .'images/thumb-'. $rowb['b_image']) )
	{
		$func->image_resize($target_path, $addon['dir'] .'images/thumb-'. $rowb['b_image'], 100, 47);
	}

	$roster->tpl->assign_block_vars('slider', array(
		'DESC'     => $rowb['b_desc'],
		'URL'      => $rowb['b_url'],
		'IMAGE'    => $addon['url_path'] .'images/slider-'. $rowb['b_image'],
		'TIMAGE'   => $addon['url_path'] .'images/thumb-'. $rowb['b_image'],
		'ID'       => $rowb['b_id'],
		'TITLE'    => $rowb['b_title'],
		'IS_VIDEO' => $rowb['b_video'],
		'NUM'      => $num,
		'NUMX'     => $num - 1,
		'END'      => $e,
		'TOTAL'    => $total
	));
	$num++;
}

$camera_js_config = array();
$camera_js_config2 = array();
foreach ($addon['config'] as $key => $value) {
	if (strpos($key, 'slider_') !== 0 || $key == 'slider_skin') {
		continue;
	}

	$key = str_replace('slider_',	'', $key);


	if (!is_numeric($value) && $value != 'true' && $value != 'false') {
		$value = "'$value'";
	}

	$camera_js_config[] = "$key:$value";
}
$camera_js_config[] = "imagePath:'". $addon['tpl_image_path'] ."'";

$camera_js = '$(function() {
$(\'#camera_wrap_1\').camera({'. implode(',', $camera_js_config) .'});
});';

roster_add_js($camera_js, 'inline', 'footer', false, false);

unset($queryb);
$roster->db->free_result($resultsb);

$roster->tpl->assign_vars(array(
  'SLIDER_SKIN' => $addon['config']['slider_skin'],
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
