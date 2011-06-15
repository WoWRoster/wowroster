<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    News
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

$roster->auth->setAction('&amp;id=' . $_GET['id']);

if( !$roster->auth->getAuthorized( $addon['config']['comm_edit'] ) )
{
	echo $roster->auth->getLoginForm($addon['config']['comm_edit']);
	return; //To the addon framework
}

// Display comment
$query = "SELECT * "
		. "FROM `" . $roster->db->table('comments','news') . "` news "
		. "WHERE `comment_id` = '" . $_GET['id'] . "';";

$result = $roster->db->query($query);

$comment = $roster->db->fetch($result);

// Assign template vars
$roster->tpl->assign_vars(array(
	'S_HTML_ENABLE'    => false,
	'S_COMMENT_HTML'   => (bool)$comment['html'],

	'U_EDIT_FORMACTION'   => makelink('util-news-comment&amp;id=' . $comment['news_id']),
	'U_NEWS_ID'           => $comment['news_id'],
	'U_COMMENT_ID'        => $comment['comment_id'],

	'CONTENT'       => $comment['content'],
	'AUTHOR'        => $comment['author'],
	)
);

if( $addon['config']['comm_html'] >= 0 && $addon['config']['news_nicedit'] > 0 )
{
	$roster->output['html_head'] .= '<script type="text/javascript" src="' . ROSTER_PATH . 'js/nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas({xhtml : true, fullPanel : true, iconsPath : \'' . $roster->config['img_url'] . 'nicEditorIcons.gif\'}) });
</script>';
}

$roster->tpl->set_filenames(array(
	'head' => $addon['basename'] . '/news_head.html',
	'body' => $addon['basename'] . '/comment_edit.html',
	'foot' => $addon['basename'] . '/news_foot.html'
	)
);
$roster->tpl->display('head');
$roster->tpl->display('body');
$roster->tpl->display('foot');
