<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Overall header for Roster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

define('ROSTER_HEADER_INC',true);

/**
 * Detect and set headers
 */
if( $roster->output['http_header'] && !headers_sent() )
{
	$now = gmdate('D, d M Y H:i:s', time()) . ' GMT';

	@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	@header('Last-Modified: ' . $now);
	@header('Cache-Control: no-store, no-cache, must-revalidate');
	@header('Cache-Control: post-check=0, pre-check=0', false);
	@header('Pragma: no-cache');
	@header('Content-type: text/html; charset=utf-8');
}

if( isset($roster->data['guild_name']) )
{
	$roster_title = ' [ ' . $roster->data['guild_name'] . ' @ ' . $roster->data['server_region'] . '-' . $roster->data['server_name'] . ' ] '
				  . (isset($roster->output['title']) ? $roster->output['title'] : '');
}
elseif( isset($roster->data['server_name']) )
{
	$roster_title = ' [ ' . $roster->data['server_region'] . '-' . $roster->data['server_name'] . ' ] '
				  . (isset($roster->output['title']) ? $roster->output['title'] : '');
}
elseif( !empty($roster->config['default_name']) )
{
	$roster_title = ' [ ' . $roster->config['default_name'] . ' ] '
				  . (isset($roster->output['title']) ? $roster->output['title'] : '');
}
else
{
	$roster_title = (isset($roster->output['title']) ? $roster->output['title'] : '');
}

/**
 * Assign template vars
 */
$roster->tpl->assign_vars(array(
	'S_SEO_URL'       => $roster->config['seo_url'],
	'S_HEADER_LOGO'   => ( !empty($roster->config['logo']) ? true : false ),

	'U_MAKELINK'      => makelink(),
	'ROSTER_URL'      => ROSTER_URL,
	'ROSTER_PATH'     => ROSTER_PATH,
	'THEME_PATH'      => ROSTER_PATH . $roster->config['theme'],
	'WEBSITE_ADDRESS' => $roster->config['website_address'],
	'HEADER_LOGO'     => $roster->config['logo'],
	'IMG_URL'         => $roster->config['img_url'],
	'INTERFACE_URL'   => $roster->config['interface_url'],
	'ROSTER_VERSION'  => $roster->config['version'],
	'ROSTER_CREDITS'  => sprintf($roster->locale->act['roster_credits'], makelink('credits')),
	'XML_LANG'        => substr($roster->config['locale'],0,2),

	'T_BORDER_WHITE'  => border('swhite','start'),
	'T_BORDER_GRAY'   => border('sgray','start'),
	'T_BORDER_GOLD'   => border('sgold','start'),
	'T_BORDER_RED'    => border('sred','start'),
	'T_BORDER_ORANGE' => border('sorange','start'),
	'T_BORDER_YELLOW' => border('syellow','start'),
	'T_BORDER_GREEN'  => border('sgreen','start'),
	'T_BORDER_PURPLE' => border('spurple','start'),
	'T_BORDER_END'    => border('sgray','end'),

	'PAGE_TITLE'      => $roster_title,
	'ROSTER_HEAD'     => $roster->output['html_head'],
	'ROSTER_BODY'     => (!empty($roster->config['roster_bg']) ? ' style="background-image:url(' . $roster->config['roster_bg'] . ');"' : '')
		. (!empty($roster->output['body_attr']) ? ' ' . $roster->output['body_attr'] : '')
		. (!empty($roster->output['body_onload']) ? ' onload="' . $roster->output['body_onload'] . '"' : ''),
	'ROSTER_MENU_BEFORE' => $roster->output['before_menu'],
	)
);


$roster->tpl->set_filenames(array('roster_header' => 'header.html'));
$roster->tpl->display('roster_header');

if( file_exists(ROSTER_BASE . 'valid.inc') )
{
	include(ROSTER_BASE . 'valid.inc');
}
