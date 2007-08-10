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

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$roster_login = new RosterLogin('&amp;id=' . $_GET['id']);

if( $roster_login->getAuthorized() < $addon['config']['news_edit'] )
{
	print $roster_login->getMessage().
	$roster_login->getLoginForm($addon['config']['news_edit']);

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
	'L_EDIT_COMMENT' => $roster->locale->act['edit_comment'],
	'L_ENABLE_HTML'  => $roster->locale->act['enable_html'],
	'L_DISABLE_HTML' => $roster->locale->act['disable_html'],

	'S_ADD_NEWS'       => false,
	'S_ADD_COMMENT'    => false,
	'S_HTML_ENABLE'    => false,
	'S_COMMENT_HTML'   => (bool)$news['html'],

	'U_NEWS_EDIT_B_S'  => border('sgreen','start',$roster->locale->act['edit_news']),
	'U_NEWS_EDIT_B_E'  => border('sgreen','end'),
	'U_EDIT_FORMACTION'  => makelink('util-news'),
	'U_NEWS_ID'          => $news['news_id'],

	'CONTENT'       => $news['content'],
	'AUTHOR'        => $news['author'],
	'TITLE'         => $news['title'],
	'DATE'          => $news['date_format'],
	)
);

if($addon['config']['news_html'] >= 0)
{
	$roster->tpl->assign_var('S_HTML_ENABLE',true);
}

$roster->set_vars(array(
	'template_file' => 'edit.html',
	'display'       => true
	)
);
