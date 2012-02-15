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

if( !$roster->auth->getAuthorized( $addon['config']['forum_start_topic'] ) )
{
	echo $roster->auth->getLoginForm($addon['config']['forum_start_topic']);
	return; //To the addon framework
}

// Assign template vars
$roster->tpl->assign_vars(array(
	'S_ADD_TOPIC'     => false,
	'U_BACK'   => makelink('guild-forum'),
	'S_HTML_ENABLE' => false,
	'S_TOPIC_HTML'   => $addon['config']['forum_html_posts'],
	'S_POSTER'				=> $_COOKIE['roster_user'],
	'S_TOPIC_ACCESS'	=> $roster->auth->makeAccess(array('name' => 'access', 'value' => '0')),
	'U_FORMACTION'  => makelink('guild-forum-forum&amp;id=' .$_GET['id']),
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
	'topic' => $addon['basename'] . '/topic_new.html'
	)
);
$roster->tpl->display('topic');
