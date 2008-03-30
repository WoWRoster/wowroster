<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Functions file for template
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    WoWRoster
 * @subpackage Theme
 */

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

define('R_TPL_VERSION', '2.0.0.0');


/**
 * Starts or ends fancy bodering containers
 *
 * @param string $style What bordering style to use
 * @param string $mode ( start | end )
 * @param string $header_text Place text in a styled header
 * @param string $hwidth Set a fixed width for the box
 * @return string
 */
function border( $style , $mode , $header_text='' , $width='' )
{
	$backg_css = $style . 'border';
	if( substr($style,0,1) == 's' )
	{
		$style = 'simple';
	}
	$style .= 'border';

	if( $header_text != '' && $style != 'end' )
	{
		$header_text = "\n" . '<div class="header_text ' . $backg_css . '">' . $header_text . "</div>\n";
	}
	else
	{
		$header_text = '';
	}

	// Dynamic Bordering
	$start = '<table class="border_frame" cellpadding="0" cellspacing="1"' . ( $width!=''?' style="width:' . $width . ';"':'' ) . '><tr><td class="border_color ' . $backg_css . '">' . $header_text;

	$end = "\n</td></tr></table>\n";

	switch( $mode )
	{
		case 'start':
			return $start;
			break;

		case 'end':
			return $end;
			break;
	}
}

/**
 * Draw a message box with the specified border color.
 *
 * @param string $message | The message to display inside the box
 * @param string $title | The box title
 * @param string $style | The border style
 * @param string $width | Set a fixed width
 * @return string $html | The HTML for the messagebox
 */
function messagebox( $message , $title='Message' , $style='sgray' , $width='' )
{
	return
		border($style, 'start', $title, $width)
			. $message
			. border($style, 'end');
}

/**
 * Draw a 300x550px scrolling messagebox with the specified border color.
 *
 * @param string $message | The message to display inside the box
 * @param string $title | The box title
 * @param string $style | The border style
 * @param string $width | Initial width with unit
 * @param string $height | Initial height with unit
 * @return string $html | The HTML for the messagebox
 */
function scrollbox( $message , $title='Message' , $style='sgray' , $width='550px' , $height='300px' )
{
	return
		border($style,'start',$title, $width)
			. '<div style="height:' . $height . ';width:100%;overflow:auto;">'
			. $message
			. '</div>'
			. border($style,'end');
}

/**
 * Draw a message box with the specified border color.
 *
 * @param string $message | The message to display inside the box
 * @param string $title | The box title
 * @param string $style | The border style
 * @param boolean $open | True if initially open
 * @param string $width | Initial width with unit
 * @return string $html | The HTML for the messagebox
 */
function messageboxtoggle( $message , $title='Message' , $style='sgray' , $open=false , $width='550px' )
{
	global $toggleboxes, $roster;

	$toggleboxes++;

	$title = "<div style=\"cursor:pointer;width:100%;\" onclick=\"showHide('msgbox_" . $toggleboxes . "','msgboximg_" . $toggleboxes . "','" . ROSTER_PATH . "templates/default/images/minus.gif','" . ROSTER_PATH . "templates/default/images/plus.gif');\">"
		   . "<img src=\"" . ROSTER_PATH . 'templates/default/images/' . (($open)?'minus':'plus') . ".gif\" style=\"float:right;\" alt=\"\" id=\"msgboximg_" . $toggleboxes . "\" />" . $title . "</div>";

	return
		border($style, 'start', $title, $width)
			. '<div style="display:' . (($open)?'inline':'none') . ';" id="msgbox_' . $toggleboxes . '">'
			. $message
			. '</div>'
			. border($style, 'end');
}

/**
 * Draw a 300x550px scrolling messagebox with the specified border color.
 *
 * @param string $messages | The message to display inside the box
 * @param string $title | The box title
 * @param string $style | The border style
 * @param string $width | Initial width with unit
 * @param string $height | Initial height with unit
 * @return string $html | The HTML for the messagebox
 */
function scrollboxtoggle( $message , $title='Message' , $style='sgray' , $open=false , $width='550px' , $height='300px' )
{
	global $toggleboxes, $roster;

	$toggleboxes++;

	$title = "<div style=\"cursor:pointer;width:100%;\" onclick=\"showHide('msgbox_" . $toggleboxes . "','msgboximg_" . $toggleboxes . "','" . ROSTER_PATH . "templates/default/images/minus.gif','" . ROSTER_PATH . "templates/default/images/plus.gif');\">"
		   . "<img src=\"" . ROSTER_PATH . 'templates/default/images/' . (($open)?'minus':'plus') . ".gif\" style=\"float:right;\" alt=\"\" id=\"msgboximg_" . $toggleboxes . "\" />" . $title . "</div>";

	return
		border($style,'start',$title, $width)
			. '<div style="height:' . $height . ';width:100%;overflow:auto;display:'.(($open)?'inline':'none').';" id="msgbox_'.$toggleboxes.'">'
			. $message
			. '</div>'
			. border($style,'end');
}
