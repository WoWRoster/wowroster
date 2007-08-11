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

$roster_login = new RosterLogin('&amp;id=' . $_GET['id']);

if( $roster_login->getAuthorized() < $addon['config']['comm_edit'] )
{
	print $roster_login->getMessage().
	$roster_login->getLoginForm($addon['config']['comm_edit']);

	return; //To the addon framework
}

// Display comment
$query = "SELECT * "
		. "FROM `" . $roster->db->table('comments','news') . "` news "
		. "WHERE `comment_id` = '" . $_GET['id'] . "';";

$result = $roster->db->query($query);

$comment = $roster->db->fetch($result);

$roster->output['body_onload'] .= 'initARC(\'editcomment\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

// Assign template vars
$roster->tpl->assign_vars(array(
	'L_EDIT'         => $roster->locale->act['edit'],
	'L_NAME'         => $roster->locale->act['name'],
	'L_EDIT_COMMENT' => $roster->locale->act['edit_comment'],
	'L_ENABLE_HTML'  => $roster->locale->act['enable_html'],
	'L_DISABLE_HTML' => $roster->locale->act['disable_html'],

	'S_ADD_NEWS'       => false,
	'S_ADD_COMMENT'    => false,
	'S_HTML_ENABLE'    => false,
	'S_COMMENT_HTML'   => (bool)$comment['html'],

	'U_COMMENT_EDIT_B_S'  => border('sblue','start',$roster->locale->act['edit_comment']),
	'U_COMMENT_EDIT_B_E'  => border('sblue','end'),
	'U_EDIT_FORMACTION'   => makelink('util-news-comment&amp;id=' . $comment['news_id']),
	'U_NEWS_ID'           => $comment['news_id'],
	'U_COMMENT_ID'        => $comment['comment_id'],

	'CONTENT'       => $comment['content'],
	'AUTHOR'        => $comment['author'],
	)
);

$roster->set_vars(array(
	'template_file' => 'comment_edit.html',
	'display'       => true
	)
);
