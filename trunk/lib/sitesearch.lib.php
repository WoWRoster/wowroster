<?php
/**
 * WoWRoster.net WoWRoster
 *
 * WoW information site search engine list
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

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

function sitesearch($site)
{
	global $roster;

	$inputs = array();
	$title = $img = $url = $text = $url = $color = $method = '';
	switch( $site )
	{
		case 'alla':
			$title = 'Allakhazam';
			$img = 'http://wow.allakhazam.com/images/wowex.png';
			$text = 'q';
			$inputs = array();
			$method = 'get';
			$url = 'http://wow.allakhazam.com/search.html';
			$color = 'sorange';
			$link = 'http://wow.allakhazam.com/';
			break;

		case 'thott':
			$title = 'Thottbot';
			$img = 'http://i.thottbot.com/Thottbot.jpg';
			$text = 's';
			$inputs = array();
			$method = 'post';
			$url = 'http://www.thottbot.com';
			$color = 'sblue';
			$link = 'http://www.thottbot.com';
			break;

		case 'wowhead':
			$title = 'WoWHead';
			$img = 'http://www.wowhead.com/images/logo.gif';
			$text = 'search';
			$inputs = array();
			$method = 'get';
			$url = 'http://www.wowhead.com';
			$color = 'sred';
			$link = 'http://www.wowhead.com';
			break;

		case 'wwndata':
			$title = 'WWN Data';
			$img = 'http://wwndata.worldofwar.net/images/logo.jpg';
			$text = 'search';
			$inputs = array();
			$method = 'get';
			$url = 'http://wwndata.worldofwar.net/search.php';
			$color = 'sgray';
			$link = 'http://wwndata.worldofwar.net/';
			break;
	}

	if( !empty($title) )
	{
		$output = '
<table cellspacing="0" class="bodyline">
	<tr>
		<td valign="middle" class="membersRowRight1"><div align="center">
			<a href="'.$link.'"target="_blank"><img src="'.$img.'" alt="'.$title.'" width="158" height="51" /></a><br />
			<br />
			<form method="'.$method.'" action="'.$url.'">
				'.$roster->locale->act['search'].':
				<input type="text" name="'.$text.'" class="wowinput128" />&nbsp;&nbsp;
				<input type="submit" value="Go" onclick="win=window.open(\'\',\'myWin\',\'\'); this.form.target=\'myWin\'" />
			</form></div></td>
	</tr>
</table>';

		return messagebox($output,$title,$color);
	}
	else
	{
		return;
	}
}
