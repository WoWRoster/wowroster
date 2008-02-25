<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    News
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster->auth->setAction('&amp;id=' . $_GET['id']);

if( ! $roster->auth->getAuthorized( $addon['config']['news_edit'] ) )
{
	print $roster->auth->getLoginForm($addon['config']['news_edit']);

	return; //To the addon framework
}

// Display news
$query = "SELECT * "
		. "FROM `" . $roster->db->table('news','news') . "` news "
		. "WHERE `news_id` = '" . $_GET['id'] . "';";

$result = $roster->db->query($query);

if( $roster->db->num_rows($result) == 0 )
{
	echo messagebox($roster->locale->act['no_news']);
}

$roster->output['body_onload'] .= 'initARC(\'editnews\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

$news = $roster->db->fetch($result);

// Assign template vars
$roster->tpl->assign_vars(array(
	'L_EDIT_NEWS'    => $roster->locale->act['edit_news'],
	'L_NAME'         => $roster->locale->act['name'],
	'L_TITLE'        => $roster->locale->act['title'],
	'L_ENABLE_HTML'  => $roster->locale->act['enable_html'],
	'L_DISABLE_HTML' => $roster->locale->act['disable_html'],

	'S_HTML_ENABLE'    => false,
	'S_COMMENT_HTML'   => (bool)$news['html'],

	'U_EDIT_FORMACTION'  => makelink('util-news'),
	'U_NEWS_ID'          => $news['news_id'],

	'CONTENT'       => $news['content'],
	'AUTHOR'        => $news['author'],
	'TITLE'         => $news['title'],
	)
);

if($addon['config']['news_html'] >= 0)
{
	$roster->tpl->assign_var('S_HTML_ENABLE',true);

	if($addon['config']['news_nicedit'] > 0)
	{
		$roster->output['html_head'] .= '<script type="text/javascript" src="' . ROSTER_PATH . 'js/nicEdit.js"></script>
<script type="text/javascript">
     bkLib.onDomLoaded(nicEditors.allTextAreas);
</script>';
	}
}


$roster->tpl->set_filenames(array('head' => $addon['basename'] . '/news_head.html'));
$roster->tpl->display('head');

$roster->tpl->set_filenames(array('body' => $addon['basename'] . '/edit.html'));
$roster->tpl->display('body');
