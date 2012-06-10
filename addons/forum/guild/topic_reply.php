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

if( !$roster->auth->getAuthorized( $addon['config']['forum_reply_post'] ) )
{
	echo $roster->auth->getLoginForm($addon['config']['forum_reply_post']);
	return; //To the addon framework
}
include( $addon['dir'] . 'inc/function.lib.php' );
$functions = new forum;
$x = $functions->getCrumbsb($_GET['tid']);

// Assign template vars
$roster->tpl->assign_vars(array(
	'S_ADD_TOPIC'     => false,
	'CRUMB'			=> $x,
	'U_BACK'   => makelink('guild-'.$addon['basename'].'-topic_reply&amp;tid=' . $_GET['tid']),
	'S_HTML_ENABLE' => false,
	'S_TOPIC_HTML'   => $addon['config']['forum_html_posts'],
	'S_POSTER'				=> $_COOKIE['roster_user'],
	'U_FORMACTION'  => makelink('guild-'.$addon['basename'].'-topic&amp;tid=' .$_GET['tid']),
	)
);

if($addon['config']['forum_html_posts'] >= 0)
{
	$roster->tpl->assign_var('S_HTML_ENABLE', true);

	if($addon['config']['forum_nicedit'] > 0)
	{
		roster_add_js('js/nicEdit.js');
		roster_add_js('bkLib.onDomLoaded(function() { nicEditors.allTextAreas({xhtml : true, fullPanel : true, iconsPath : \'' . $roster->config['img_url'] . 'nicEditorIcons.gif\'}) });', 'inline');
	}
}

$roster->tpl->set_filenames(array(
	'topic' => $addon['basename'] . '/post_reply.html'
	)
);
$roster->tpl->display('topic');
