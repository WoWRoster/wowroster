<?php
/**
 * WoWRoster.net WoWRoster
 *
 * GD2 Bargraph Image Generator
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    GuildInfo
*/

define('IN_ROSTER', true);
require('../../../settings.php');
require(ROSTER_LIB . 'roster_gd.php');
$roster_gd =& new RosterGD();

// Parameter fetching and checking
$data = isset($_GET['data']) ? $_GET['data'] : false;

if( !$data || $data == '' )
{
	trigger_error('No data was recieved', E_USER_ERROR);
}

// Decode and convert data into an array
$data = urldecode($data);
$data = stripslashes($data);
$data = json_decode($data, true);
/*
print '<pre>' . print_r($data, true) . '</pre>';
die;
*/
$type = isset($data['type']) ? $data['type'] : false;
$text = isset($data['text']) ? $data['text'] : false;
$footer = isset($data['footer']) ? $data['footer'] : false;
$bar = isset($data['bar']) ? $data['bar'] : false;
$bar2 = isset($data['bar2']) ? $data['bar2'] : false;

if( !$type )
{
	trigger_error('Type not passed',E_USER_ERROR);
}

if( !is_array($text) )
{
	trigger_error('Text data not passed',E_USER_ERROR);
}

if( !is_array($bar) )
{
	trigger_error('Bar data not passed',E_USER_ERROR);
}

if( !is_array($bar2) )
{
	trigger_error('Bar2 data not passed',E_USER_ERROR);
}

if( !$bar['names'] )
{
	trigger_error('No bar[names] data passed',E_USER_ERROR);
}

if( !$bar['sizes'] )
{
	trigger_error('No bar[sizes] data passed',E_USER_ERROR);
}

if( !$bar2['sizes'] )
{
	trigger_error('No bar2[sizes] data passed',E_USER_ERROR);
}

if( count($bar['names']) != count($bar['sizes']) )
{
	trigger_error('Barnames/barsizes array length doesn\'t match',E_USER_ERROR);
}

if( is_array($bar2['sizes']) && (count($bar2['sizes']) != count($bar['names'])) )
{
	trigger_error('Some secondary bar sizes were passed but the count doesn\'t match',E_USER_ERROR);
}


$barnames = array_reverse($bar['names']);
$barsizes = array_reverse($bar['sizes']);
$bar2sizes = array_reverse($bar2['sizes']);


// Initialize image
$bkg_img = ROSTER_BASE . 'img' . DIR_SEP . 'graphs' . DIR_SEP . $type . '-bg.jpg';
$fg_img = ROSTER_BASE . 'img' . DIR_SEP . 'graphs' . DIR_SEP . $type . '-bg.png';

$bkg_img_info = getimagesize($bkg_img);
$roster_gd->make_image($bkg_img_info[0], $bkg_img_info[1]);
$roster_gd->combine_image($bkg_img, 0, 0);

$roster_gd->gd_rectangle(0, 0, $bkg_img_info[0]-1, $bkg_img_info[1]-1, 0, 0, '#B6803D');
$roster_gd->gd_rectangle(1, 1, $bkg_img_info[0]-2, $bkg_img_info[1]-2, 0, 0, '#FFFFD6');

$w = $bkg_img_info[0];
$h = $bkg_img_info[1];

// calculate extra attributes
$count = count($barnames);
$offset = 58;
$factor = ($h-$offset)/max($barsizes);


$max_barsizes = max($barsizes);
$bar_max_width = 41;
$bar_max_height = 50;
$bar_x_offset = 53;
$bar_y_offset = 74;
$bar_gap = 77;
$text_offset = 73;



// Draw bars
for($i=0; $i<$count; $i++)
{
	// Get icon
	if( $type == 'class' )
	{
		$icon = ROSTER_BASE . 'img' . DIR_SEP . 'class' . DIR_SEP . $roster->locale->act['class_iconArray'][$barnames[$i]] . '.png';
		$roster_gd->combine_image($icon, $bar_x_offset + 1, 98, 0, 0, 40, 40);
	}

	// Draw the bar
	if( $barsizes[$i] > 0 )
	{
		$bar_height = $bar_y_offset - ceil(($barsizes[$i] / $max_barsizes) * $bar_max_height);
		$bar_width = $bar_x_offset + $bar_max_width;

		if( $type == 'class' && $bar['color'] == '' )
		{
			$thiscolor = $roster->locale->act['class_colorArray'][$barnames[$i]];
		}
		else
		{
			$thiscolor = $bar['color'];
		}
		$roster_gd->gd_rectangle($bar_x_offset, $bar_height, $bar_width, $bar_y_offset, 1, 0, $thiscolor, 40);
		$roster_gd->gd_rectangle($bar_x_offset, $bar_height, $bar_width, $bar_y_offset, 0, 0, $thiscolor);

	}
	if( isset($bar2sizes[$i]) && $bar2sizes[$i] > 0 )
	{
		$bar_height = $bar_y_offset - ceil(($bar2sizes[$i] / $max_barsizes) * $bar_max_height);
		$bar_width = $bar_x_offset + ($bar_max_width / 2);

		$roster_gd->gd_rectangle($bar_x_offset + 5, $bar_height, $bar_width, $bar_y_offset, 1, 0, $bar2['color'], 40);
		$roster_gd->gd_rectangle($bar_x_offset + 5, $bar_height, $bar_width, $bar_y_offset, 0, 0, $bar2['color']);
	}

	$bar_x_offset += $bar_gap;
}

// Overlay the foreground image
$roster_gd->combine_image($fg_img, 0, 0);


// Draw the labels
// This is separate so the text is on top of all the bars
$shadow = array('color' => $text['outline'], 'distance' => 1, 'direction' => 90, 'spread' => 0);

for($i=0; $i<$count; $i++)
{
	if( $type == 'class' && $text['color'] == '' )
	{
		$thiscolor = $roster->locale->act['class_colorArray'][$barnames[$i]];
	}
	else
	{
		$thiscolor = $text['color'];
	}

	$roster_gd->write_text($text['size'], 0, $text_offset, 95, $thiscolor, 0, $text['font'], $barnames[$i], 'center', array(), $shadow);
//	$roster_gd->write_text($text['size']*.7, 0, $text_offset+18, $bar_y_offset-1, $thiscolor, 0, $text['font'], (string)$barsizes[$i], 'right', array(), $shadow);

	$text_offset += $bar_gap;
}

if( $footer['text'] != '' )
{
	$shadow = array('color' => $footer['outline'], 'distance' => 1, 'direction' => 90, 'spread' => 0);
	$roster_gd->write_text($footer['size'], 0, 5, $h-5, $footer['color'], 0, $footer['font'], $footer['text'], 'left', array(), $shadow);
}

$roster_gd->get_image('png');
$roster_gd->finish();
