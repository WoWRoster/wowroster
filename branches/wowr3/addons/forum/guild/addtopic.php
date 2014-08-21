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
//$addon = getaddon('forum');

if( !$roster->auth->getAuthorized( $roster->config['forum_start_topic'] ) )
{
	echo $roster->auth->getLoginForm($roster->config['forum_start_topic']);
	return; //To the addon framework
}
 
include( $addon['dir'] . 'inc/function.lib.php' );
$functions = new forum;
$x = $functions->getCrumbsa($_GET['id']);
// Assign template vars
$bg = false;
/*
if ($roster->auth->_ingroup( '1', $roster->auth->user['groups'] ))
		{
			$bg = true;
		}
		else if ($roster->auth->_ingroup( '2', $roster->auth->user['groups'] ))
		{
			$bg = true;
		}
*/
$roster->tpl->assign_vars(array(
	'S_ADD_TOPIC'		=> false,
	'CRUMB'				=> $x,
	'IS_MOD'			=> $bg,
	'U_BACK'			=> makelink('forum'),
	'S_HTML_ENABLE'		=> false,
	'S_TOPIC_HTML'		=> $roster->config['forum_html_posts'],
	'S_POSTER'			=> $roster->auth->user['usr'],
	'S_POSTER_ID'		=> $roster->auth->user['id'],
	'S_TOPIC_ACCESS'	=> $roster->auth->rosterAccess(array('name' => 'access', 'value' => '0')),
	'U_FORMACTION'		=> makelink('guild-'.$addon['basename'].'-forum&amp;id=' .$_GET['id']),
	'U_FORM_NAME'	=>'topicadd',
	'U_TEXT_NAME'	=>'text',
	)
);

if($roster->config['forum_html_posts'] >= 0)
{
	$roster->tpl->assign_var('S_HTML_ENABLE', true);
}


$roster->tpl->set_filenames(array(
	'topic' => $addon['basename'] . '/forum_topic_new.html'
	)
);
$roster->tpl->display('topic');
