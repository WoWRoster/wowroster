<?php
/**
 * WoWCoin.net WoWCoin
 *
 * @copyright  2002-2011 WoWCoin.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    News
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

if( !$roster->auth->getAuthorized( $roster->config['forum_reply_post'] ) )
{
	echo $roster->auth->getLoginForm($roster->config['forum_reply_post']);
	return; //To the addon framework
}
 
include( $addon['dir'] . 'inc/function.lib.php' );
$functions = new forum;

$x = $functions->getCrumbsb($_GET['tid']);

// Assign template vars
$roster->tpl->assign_vars(array(
	'S_ADD_TOPIC'	=> false,
	'CRUMB'			=> $x,
	'U_BACK'		=> makelink('guild-'.$addon['basename'].'-topic_reply&amp;tid=' . $_GET['tid']),
	'S_HTML_ENABLE'	=> false,
	'S_TOPIC_HTML'	=> $roster->config['forum_html_posts'],
	'S_POSTER'		=> $roster->auth->user['usr'],
	'S_POSTER_ID'	=> $roster->auth->user['id'],
	'U_FORMACTION'	=> makelink('guild-'.$addon['basename'].'-topic&amp;tid=' .$_GET['tid']),
	'U_FORM_NAME'	=>'topicreply',
	'U_TEXT_NAME'	=>'text',
	)
);

if($roster->config['forum_html_posts'] >= 0)
{
	$roster->tpl->assign_var('S_HTML_ENABLE', true);
}

$roster->tpl->set_filenames(array(
	'topic' => $addon['basename'] . '/forum_post_reply.html'
	)
);
$roster->tpl->display('topic');
