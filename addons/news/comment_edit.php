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

if( ! $roster->auth->getAuthorized( $addon['config']['comm_edit'] ) )
{
	print $roster->auth->getLoginForm($addon['config']['comm_edit']);

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

	'S_NICEDIT_ENABLE' => false,
	
	'S_HTML_ENABLE'    => false,
	'S_COMMENT_HTML'   => (bool)$comment['html'],

	'U_EDIT_FORMACTION'   => makelink('util-news-comment&amp;id=' . $comment['news_id']),
	'U_NEWS_ID'           => $comment['news_id'],
	'U_COMMENT_ID'        => $comment['comment_id'],

	'CONTENT'       => $comment['content'],
	'AUTHOR'        => $comment['author'],
	)
);

if($addon['config']['news_nicedit'] > 0)
{
	$roster->tpl->assign_var('S_NICEDIT_ENABLE', true);
	$roster->output['html_head'] .= "<script type=\"text/javascript\" src=\"http://js.nicedit.com/nicEdit.js\"></script>
<script type=\"text/javascript\">
    bkLib.onDomLoaded(nicEditors.allTextAreas);
</script>";
}

$roster->tpl->set_filenames(array('body' => $addon['basename'] . '/comment_edit.html'));
$roster->tpl->display('body');
