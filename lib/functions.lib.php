<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Common functions for Roster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

// Global variables this file uses

// Index to generate unique toggle IDs
$toggleboxes = 0;
// Array of Tooltips
$tooltips = array();

/**
 * Starts or ends fancy bodering containers
 *
 * @param string $style What bordering style to use
 * @param string $mode ( start | end )
 * @param string $header_text Place text in a styled header
 * @param string $hwidth Set a fixed width for the box
 * @return string
 */
function border( $style , $mode , $header_text=null , $width='' )
{
	$backg_css = $style . 'border';
	if( substr($style,0,1) == 's' )
	{
		$style = 'simple';
	}
	$style .= 'border';

	if( $header_text != '' && $style != 'end' )
	{
		$header_text = '  <div class="header_text '. $backg_css .'">' . $header_text . '</div>';
	}
	else
	{
		$header_text = '';
	}

	// Dynamic Bordering
	$start = '
<!-- START [open-' . $style . '] container -->
<table class="border_frame" cellpadding="0" cellspacing="1"' . ( $width!=''?' style="width:' . $width . ';"':'' ) . '><tr><td class="border_color ' . $backg_css . '">
'.$header_text.'
<!-- END [open-' . $style . '] container -->';

	$end = '
<!-- START [close-' . $style . '] container -->
    </td></tr></table>
<!-- END [close-' . $style . '] container -->';

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
 * Makes a tootip and places it into the tooltip array
 *
 * @param string $var
 * @param string $content
 */
function setTooltip( $var , $content )
{
	global $tooltips;

	if( !isset($tooltips[$var]) )
	{
		$content = str_replace("\n",'',$content);
		$content = addslashes($content);
		$content = str_replace('</','<\\/',$content);
		$content = str_replace('/>','\\/>',$content);

		$tooltips += array($var=>$content);
	}
}

/**
 * Gathers all tootips and places them into javascript variables
 *
 * @param array $tooltipArray
 * @return string Tooltips placed in javascript variables
 */
function getAllTooltips( )
{
	global $tooltips;

	if( is_array($tooltips) )
	{
		$ret_string = "<script type=\"text/javascript\">\n<!--\n";
		foreach ($tooltips as $var => $content)
		{
			$ret_string .= "\tvar overlib_$var = \"" . str_replace('--','-"+"-',$content) . "\";\n";
		}
		$ret_string .= "//-->\n</script>";

		return $ret_string;
	}
	else
	{
		return '';
	}
}

/**
* Highlight certain keywords in a SQL query
*
* @param string $sql Query string
* @return string Highlighted string
*/
function sql_highlight( $sql )
{
	global $roster;

	// Make table names bold
	$sql = preg_replace('/' . $roster->db->prefix . '(\S+?)([\s\.,]|$)/', '<span class="blue">' . $roster->db->prefix . "\\1\\2</span>", $sql);

	// Non-passive keywords
	$red_keywords = array('/(INSERT INTO)/','/(UPDATE\s+)/','/(DELETE FROM\s+)/','/(CREATE TABLE)/','/(IF (NOT)? EXISTS)/',
						  '/(ALTER TABLE)/', '/(CHANGE)/','/(SET)/','/(REPLACE INTO)/');

	$red_replace = array_fill(0, sizeof($red_keywords), '<span class="red">\\1</span>');
	$sql = preg_replace( $red_keywords, $red_replace, $sql );


	// Passive keywords
	$green_keywords = array('/(SELECT)/','/(FROM)/','/(WHERE)/','/(LIMIT)/','/(ORDER BY)/','/(GROUP BY)/',
							'/(\s+AND\s+)/','/(\s+OR\s+)/','/(\s+ON\s+)/','/(BETWEEN)/','/(DESC)/','/(LEFT JOIN)/','/(SHOW TABLES)/',
							'/(LIKE)/','/(PRIMARY KEY)/','/(VALUES)/','/(TYPE)/','/(ENGINE)/','/(MyISAM)/','/(SHOW COLUMNS)/');

	$green_replace = array_fill(0, sizeof($green_keywords), '<span class="green">\\1</span>');
	$sql = preg_replace( $green_keywords, $green_replace, $sql );

	return $sql;
}

/**
 * Clean replacement for die(), outputs a message with debugging info if needed and ends output
 *
 * @param string $text Text to display on error page
 * @param string $title Title to place on web page
 * @param string $file Filename to display
 * @param string $line Line in file to display
 * @param string $sql Any SQL text to display
 */
function die_quietly( $text='' , $title='Message' , $file='' , $line='' , $sql='' )
{
	global $roster;

	if( $roster->pages[0] == 'ajax' )
	{
		ajax_die($text, $title, $file, $line, $sql);
	}
	// die_quitely died quietly
	if(defined('ROSTER_DIED') )
	{
		print "<pre>The quiet die function suffered a fatal error. Die information below\n";
		print "First die data:\n";
		print_r($GLOBALS['die_data']);
		print "\nSecond die data:\n";
		print_r(func_get_args());
		if( !empty($roster->error->report) )
		{
			print "\nPHP Notices/Warnings:\n";
			print_r( $roster->error->report );
		}
		exit();
	}

	define( 'ROSTER_DIED', true );

	$GLOBALS['die_data'] = func_get_args();

	$roster->output['title'] = $title;

	if( !defined('ROSTER_HEADER_INC') && is_array($roster->config) )
	{
		include_once(ROSTER_BASE . 'header.php');
	}

	if( !defined('ROSTER_MENU_INC') && is_array($roster->config) )
	{
		$roster_menu = new RosterMenu;
		$roster_menu->makeMenu($roster->output['show_menu']);
	}

	if( is_object($roster->db) )
	{
		$roster->db->close_db();
	}

	print border('sred','start',$title) . '<table class="bodyline" cellspacing="0" cellpadding="0">'."\n";

	if( !empty($text) )
	{
		print "<tr>\n<td class=\"membersRowRight1\" style=\"white-space:normal;\"><div align=\"center\">$text</div></td>\n</tr>\n";
	}
	if( !empty($sql) )
	{
		print "<tr>\n<td class=\"membersRowRight1\" style=\"white-space:normal;\">SQL:<br />" . sql_highlight($sql) . "</td>\n</tr>\n";
	}
	if( !empty($file) )
	{
		$file = str_replace(ROSTER_BASE,'',$file);

		print "<tr>\n<td class=\"membersRowRight1\">File: $file</td>\n</tr>\n";
	}
	if( !empty($line) )
	{
		print "<tr>\n<td class=\"membersRowRight1\">Line: $line</td>\n</tr>\n";
	}

	if( $roster->config['debug_mode'] )
	{
		print "<tr>\n<td class=\"membersRowRight1\" style=\"white-space:normal;\">";
		print  backtrace();
		print "</td>\n</tr>\n";
	}

	print "</table>\n" . border('sred','end');

	if( !defined('ROSTER_FOOTER_INC') && is_array($roster->config) )
	{
		include_once(ROSTER_BASE . 'footer.php');
	}

	exit();
}

/**
 * Draw a message box with the specified border color, then die cleanly
 *
 * @param string $message | The message to display inside the box
 * @param string $title | The box title (default = 'Message')
 * @param string $style | The border style (default = sred)
 */
function roster_die( $message , $title = 'Message' , $style = 'sred' )
{
	global $roster;

	if( $roster->pages[0] == 'ajax' )
	{
		ajax_die($message, $title, null, null, null );
	}

	if( !defined('ROSTER_HEADER_INC') && is_array($roster->config) )
	{
		include_once(ROSTER_BASE . 'header.php');
	}

	if( !defined('ROSTER_MENU_INC') && is_array($roster->config) )
	{
		$roster_menu = new RosterMenu;
		$roster_menu->makeMenu($roster->output['show_menu']);
	}

	if( is_object($roster->db) )
	{
		$roster->db->close_db();
	}

	print messagebox('<div align="center">' . $message . '</div>',$title,$style);

	if( !defined('ROSTER_FOOTER_INC') && is_array($roster->config) )
	{
		include_once(ROSTER_BASE . 'footer.php');
	}

	exit();
}

/**
 * Print a roster-ajax XML error message
 */
function ajax_die($text, $title, $file, $line, $sql)
{
	if( $file )
	{
		$text .= "\n" . 'FILE: ' . $file;
	}
	if( $line )
	{
		$text .= "\n" . 'LINE: ' . $line;
	}
	if( $sql )
	{
		$text .= "\n" . 'SQL: ' . $sql;
	}
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'."\n".
		'<response>'."\n".
		'  <method/>'."\n".
		'  <cont/>'."\n".
		'  <result/>'."\n".
		'  <status>255</status>'."\n".
		'  <errmsg>'.$text.'</errmsg>'."\n".
		'</response>'."\n";
}


/**
 * Print a debug backtrace. This works in PHP4.3.x+, there is an integrated
 * function for this starting PHP5 but I prefer always having the same layout.
 */
function backtrace()
{
	if( version_compare( phpversion(), '4.3', '<' ) )
	{
		return 'Unable to print backtrace: PHP version too low';
	}
	$bt = debug_backtrace();

	$output = "Backtrace (most recent call last):<ul>\n";
	for( $i = 0; $i <= count($bt) - 1; $i++ )
	{
		if( !isset($bt[$i]['file']) )
		{
			$output .= "<li>[PHP core called function]<ul>\n";
		}
		else
		{
			$output .= '<li>' . str_replace(ROSTER_BASE,'',$bt[$i]['file']) . "<ul>\n";
		}

		if( isset($bt[$i]['line']) )
		{
			$output .= '<li>Line: ' . $bt[$i]['line'] . "</li>\n";
		}
		$output .= '<li>Function Called: ' . $bt[$i]['function'] . "</li>\n";

		if( $bt[$i]['args'] )
		{
			$output .= '<li>Arguments:<ul>';
			for( $j = 0; $j <= count($bt[$i]['args']) - 1; $j++ )
			{
				if( is_array($bt[$i]['args'][$j]) )
				{
					$output .= '<li>' . print_r($bt[$i]['args'][$j],true) . "</li>\n";
				}
				elseif( is_object($bt[$i]['args'][$j]) )
				{
					$output .= '<li>' . 'object' . "</li>\n";
				}
				elseif( is_null($bt[$i]['args'][$j]) )
				{
					$output .= "<li><i>NULL</i></li>\n";
				}
				elseif( empty($bt[$i]['args'][$j]) )
				{
					$output .= "<li>&nbsp;</li>\n";
				}
				else
				{
					$output .= '<li>' . $bt[$i]['args'][$j] . "</li>\n";
				}
			}
			$output .= "</ul></li>\n";
		}
		$output .= "</ul></li>\n";
	}
	$output .= "</ul>\n";

	return $output;
}

/**
 * This will remove HTML tags, javascript sections and white space
 * It will also convert some common HTML entities to their text equivalent
 *
 * @param string $file
 */
function stripAllHtml( $string )
{
	$search = array ('@<script[^>]*?>.*?</script>@si', // Strip out javascript
					'@<[\/\!]*?[^<>]*?>@si',           // Strip out HTML tags
					'@([\r\n])[\s]+@',                 // Strip out white space
					'@&(quot|#34);@i',                 // Replace HTML entities
					'@&(amp|#38);@i',
					'@&(lt|#60);@i',
					'@&(gt|#62);@i',
					'@&(nbsp|#160);@i',
					'@&(iexcl|#161);@i',
					'@&(cent|#162);@i',
					'@&(pound|#163);@i',
					'@&(copy|#169);@i',
					'@&#(\d+);@e');                    // evaluate as php

	$replace = array ('','',"\n",'"','&','<','>',' ',chr(161),chr(162),chr(163),chr(169),'chr(\1)');

	$string = preg_replace($search, $replace, $string);

	return $string;
}

/**
 * This will check if the given Filename is an image
 *
 * @param imagefile $file
 * @return mixed The extentsion if the filetype is an image, false if it is not
 */
function check_if_image( $imagefilename )
{
	if( ($extension = pathinfo($imagefilename, PATHINFO_EXTENSION)) === FALSE )
	{
		return false;
	}
	else
	{
	  	switch( $extension )
	  	{
		  	case 'bmp': 	return $extension;
		  	case 'cod': 	return $extension;
		  	case 'gif': 	return $extension;
		  	case 'ief': 	return $extension;
		  	case 'jpg': 	return $extension;
		  	case 'jpeg': 	return $extension;
		  	case 'jfif': 	return $extension;
		  	case 'tif': 	return $extension;
		  	case 'ras': 	return $extension;
		  	case 'ico': 	return $extension;
		  	case 'pnm': 	return $extension;
		  	case 'pbm': 	return $extension;
		  	case 'pgm': 	return $extension;
		  	case 'ppm': 	return $extension;
		  	case 'rgb': 	return $extension;
		  	case 'xwd': 	return $extension;
		  	case 'png': 	return $extension;
		  	case 'jps': 	return $extension;
		  	case 'fh': 		return $extension;

			default: 		return false;
		}
	}
}

/**
 * Tooltip colorizer function with string cleaning
 * Use only with makeOverlib
 *
 * @param string $tooltip | Tooltip as a string (delimited by "\n" character)
 * @param string $caption_color | (optional) Color for the caption
 * Default is 'ffffff' - white
 * @param string $locale | (optional) Locale so color parser can work correctly
 * Default is $roster->config['locale']
 * @param bool $inline_caption | (optional)
 * Default is true
 * @return string | Formatted tooltip
 */
function colorTooltip( $tooltip, $caption_color='', $locale='', $inline_caption=1 )
{
	global $roster;

	// Use main locale if one is not specified
	if( $locale == '' )
	{
		$locale = $roster->config['locale'];
	}

	// Detect caption mode and display accordingly
	if( $inline_caption )
	{
		$first_line = true;
	}
	else
	{
		$first_line = false;
	}

	// Initialize tooltip_out
	$tooltip_out = '';

	// Color parsing time!
	$tooltip = str_replace("\n\n", "\n", $tooltip);
	$tooltip = str_replace('<br>',"\n",$tooltip);
	$tooltip = str_replace('<br />',"\n",$tooltip);
	foreach (explode("\n", $tooltip) as $line )
	{
		$color = '';

		if( !empty($line) )
		{
			$line = preg_replace('/\|c[a-f0-9]{2}([a-f0-9]{6})(.+?)\|r/i','<span style="color:#$1;">$2</span>',$line);

			// Do this on the first line
			// This is performed when $caption_color is set
			if( $first_line )
			{
				if( $caption_color == '' )
				{
					$caption_color = 'ffffff';
				}

				if( strlen($caption_color) > 6 )
				{
					$color = substr( $caption_color, 2, 6 ) . ';font-size:12px;font-weight:bold';
				}
				else
				{
					$color = $caption_color . ';font-size:12px;font-weight:bold';
				}

				$first_line = false;
			}
			else
			{
				if( ereg('^' . $roster->locale->wordings[$locale]['tooltip_use'], $line) )
				{
					$color = '00ff00';
				}
				elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_requires'], $line) )
				{
					$color = 'ff0000';
				}
				elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_reinforced'], $line) )
				{
					$color = '00ff00';
				}
				elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_equip'], $line) )
				{
					$color = '00ff00';
				}
				elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_chance'], $line) )
				{
					$color = '00ff00';
				}
				elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_enchant'], $line) )
				{
					$color = '00ff00';
				}
				elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_soulbound'], $line) )
				{
					$color = '00bbff';
				}
				elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_set'], $line) )
				{
					$color = '00ff00';
				}
				elseif(ereg('^' . $roster->locale->wordings[$locale]['tooltip_rank'], $line) )
				{
					$color = '00ff00;font-weight:bold';
				}
				elseif(ereg('^' . $roster->locale->wordings[$locale]['tooltip_next_rank'], $line) )
				{
					$color = 'ffffff;font-weight:bold';
				}
				elseif( preg_match('/\([a-f0-9]\).' . $roster->locale->wordings[$locale]['tooltip_set'].'/i',$line) )
				{
					$color = '666666';
				}
				elseif( ereg('^"',$line) )
				{
					$color = 'ffd517';
				}
				elseif( ereg($roster->locale->wordings[$locale]['tooltip_garbage'], $line) )
				{
					$line = '';
				}
				elseif( preg_match($roster->locale->wordings[$locale]['tooltip_preg_emptysocket'], $line, $matches) )
				{
					$line = '<img src="' . $roster->config['interface_url'] . 'Interface/ItemSocketingFrame/ui-emptysocket-'
						  . socketColorEn($matches[1], $locale) . '.' . $roster->config['img_suffix'] . '">&nbsp;&nbsp;' . $matches[0];
				}
				elseif( preg_match($roster->locale->wordings[$locale]['tooltip_preg_classes'], $line, $matches) )
				{
					$classes = explode(', ', $matches[2]);
					$count = count($classes);
					$class_text = $matches[1];

					$line = $class_text . '&nbsp;';
					$i = 0;
					foreach( $classes as $class )
					{
						$i++;
						$line .= '<span style="color:#'. $roster->locale->wordings[$locale]['class_colorArray'][$class] . ';">' . $class . '</span>';
						if( $count > $i )
						{
							$line .= ', ';
						}
					}
				}
			}

			// Convert tabs to a formated table
			if( strpos($line,"\t") )
			{
				$line = explode("\t",$line);
				if( !empty($color) )
				{
					$line = '<div style="width:100%;color:#' . $color . ';"><span style="float:right;">' . $line[1] . '</span>' . $line[0] . '</div>';
				}
				else
				{
					$line = '<div style="width:100%;"><span style="float:right;">' . $line[1] . '</span>' . $line[0] . '</div>';
				}
				$tooltip_out .= $line;
			}
			elseif( !empty($color) )
			{
				$tooltip_out .= '<span style="color:#' . $color . ';">' . $line . '</span><br />';
			}
			else
			{
				$tooltip_out .= "$line<br />";
			}
		}
		else
		{
			$tooltip_out .= '<br />';
		}
	}
	return $tooltip_out;
}

/**
 * Cleans up the tooltip and parses an inline_caption if needed
 * Use only with makeOverlib
 *
 * @param string $tooltip | Tooltip as a string (delimited by "\n" character)
 * @param string $caption_color | (optional) Color for the caption
 * Default is 'ffffff' - white
 * @param bool $inline_caption | (optional)
 * Default is true
 * @return string | Formatted tooltip
 */
function cleanTooltip( $tooltip , $caption_color='' , $inline_caption=1 )
{
	// Detect caption mode and display accordingly
	if( $inline_caption )
	{
		$first_line = true;
	}
	else
	{
		$first_line = false;
	}


	// Initialize tooltip_out
	$tooltip_out = '';

	// Parsing time!
	$tooltip = str_replace('<br>',"\n",$tooltip);
	$tooltip = str_replace('<br />',"\n",$tooltip);
	foreach( explode("\n", $tooltip) as $line )
	{
		$color = '';

		if( !empty($line) )
		{
			$line = preg_replace('|\\>|','&#8250;', $line );
			$line = preg_replace('|\\<|','&#8249;', $line );
			$line = preg_replace('|\|c[a-f0-9]{2}([a-f0-9]{6})(.+?)\|r|','<span style="color:#$1;">$2</span>',$line);

			// Do this on the first line
			// This is performed when $caption_color is set
			if( $first_line )
			{
				if( $caption_color == '' )
				{
					$caption_color = 'ffffff';
				}

				if( strlen($caption_color) > 6 )
				{
					$color = substr( $caption_color, 2, 6 ) . ';font-size:11px;font-weight:bold';
				}
				else
				{
					$color = $caption_color . ';font-size:11px;font-weight:bold';
				}

				$first_line = false;
			}

			// Convert tabs to a formated table
			if( strpos($line,"\t") )
			{
				$line = explode("\t",$line);
				if( !empty($color) )
				{
					$line = '<div style="width:100%;color:#' . $color . ';"><span style="float:right;">' . $line[1] . '</span>' . $line[0] . '</div>';
				}
				else
				{
					$line = '<div style="width:100%;"><span style="float:right;">' . $line[1] . '</span>' . $line[0] . '</div>';
				}
				$tooltip_out .= $line;
			}
			elseif( !empty($color) )
			{
				$tooltip_out .= '<span style="color:#' . $color . ';">' . $line . '</span><br />';
			}
			else
			{
				$tooltip_out .= "$line<br />";
			}
		}
		else
		{
			$tooltip_out .= '<br />';
		}
	}

	return $tooltip_out;
}

/**
 * Easy all in one function to make overlib tooltips
 * Creates a string for insertion into any html tag that has "onmouseover" and "onmouseout" events
 *
 * @param string $tooltip | Tooltip as a string (delimited by "\n" character)
 * @param string $caption | (optional) Text to set as a true OverLib caption
 * @param string $caption_color | (optional) Color for the caption
 * Default is 'ffffff' - white
 * @param bool $mode| (optional) Options 0=colorize,1=clean,2=pass through
 * Default 0 (colorize)
 * @param string $locale | Locale so color parser can work correctly
 * Only needed when $colorize is true
 * Default is $roster->config['locale']
 * @param string $extra_parameters | (optional) Extra OverLib parameters you wish to pass
 * @param string $item_id
 * @return unknown
 */
function makeOverlib( $tooltip , $caption='' , $caption_color='' , $mode=0 , $locale='' , $extra_parameters='' )
{
	global $roster, $tooltips;

	$tooltip = stripslashes($tooltip);

	// Use main locale if one is not specified
	if( $locale == '' )
	{
		$locale = $roster->config['locale'];
	}

	// Detect caption text and display accordingly
	$caption_mode = 1;
	if( $caption_color != '' )
	{
		if( strlen($caption_color) > 6 )
		{
			$caption_color = substr( $caption_color, 2 );
		}
	}

	if( $caption != '' )
	{
		if( $caption_color != '' )
		{
			$caption = '<span style="color:#' . $caption_color . ';">' . $caption . '</span>';
		}

		$caption = ",CAPTION,'" . addslashes($caption) . "'";

		$caption_mode = 0;
	}

	switch ($mode)
	{
		case 0:
			$tooltip = colorTooltip($tooltip,$caption_color,$locale,$caption_mode);
			break;

		case 1:
			$tooltip = cleanTooltip($tooltip,$caption_color,$caption_mode);
			break;

		case 2:
			break;

		default:
			$tooltip = colorTooltip($tooltip,$caption_color,$locale,$caption_mode);
			break;
	}

	$num_of_tips = (count($tooltips)+1);

	setTooltip($num_of_tips,$tooltip);

	return 'onmouseover="return overlib(overlib_' . $num_of_tips . $caption . $extra_parameters . ');" onmouseout="return nd();"';
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
		border($style, 'start', $title, $width).
			$message.
		border($style, 'end');
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
		border($style,'start',$title, $width).
		'<div style="height:' . $height . ';width:100%;overflow:auto;">'.
			$message.
		'</div>'.
		border($style,'end');
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

	$title = "<div style=\"cursor:pointer;width:100%;\" onclick=\"showHide('msgbox_" . $toggleboxes . "','msgboximg_" . $toggleboxes . "','" . $roster->config['img_url'] . "minus.gif','" . $roster->config['img_url'] . "plus.gif');\">"
		   . "<img src=\"" . $roster->config['img_url'] . (($open)?'minus':'plus') . ".gif\" style=\"float:right;\" alt=\"\" id=\"msgboximg_" . $toggleboxes . "\" />" . $title . "</div>";

	return
		border($style, 'start', $title, $width).
		'<div style="display:' . (($open)?'inline':'none') . ';" id="msgbox_' . $toggleboxes . '">'.
			$message.
		'</div>'.
		border($style, 'end');
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

	$title = "<div style=\"cursor:pointer;width:100%;\" onclick=\"showHide('msgbox_" . $toggleboxes . "','msgboximg_" . $toggleboxes . "','" . $roster->config['img_url'] . "minus.gif','" . $roster->config['img_url'] . "plus.gif');\">"
		   . "<img src=\"" . $roster->config['img_url'] . (($open)?'minus':'plus') . ".gif\" style=\"float:right;\" alt=\"\" id=\"msgboximg_" . $toggleboxes . "\" />" . $title . "</div>";

	return
		border($style,'start',$title, $width).
		'<div style="height:' . $height . ';width:100%;overflow:auto;display:'.(($open)?'inline':'none').';" id="msgbox_'.$toggleboxes.'">'.
			$message.
		'</div>'.
		border($style,'end');
}

/**
 * Recursively escape $array
 *
 * @param array $array
 *	The array to escape
 * @return array
 *	The same array, escaped
 */
function escape_array( $array )
{
	foreach ($array as $key=>$value)
	{
		if( is_array($value) )
		{
			$array[$key] = escape_array($value);
		}
		else
		{
			$array[$key] = addslashes($value);
		}
	}

	return $array;
}

/**
 * Recursively stripslash $array
 *
 * @param array $array
 *	The array to escape
 * @return array
 *	The same array, escaped
 */
function stripslash_array( $array )
{
	foreach ($array as $key=>$value)
	{
		if( is_array($value) )
		{
			$array[$key] = stripslash_array($value);
		}
		else
		{
			$array[$key] = stripslashes($value);
		}
	}

	return $array;
}

/**
 * Converts a datetime field into a readable date
 *
 * @param string $datetime datetime field data in DB
 * @param string $offset Offset in hours to calcuate time returned
 * @return string formatted date string
 */
function readbleDate( $datetime , $offset=null )
{
	global $roster;

	$offset = ( is_null($offset) ? $roster->config['localtimeoffset'] : $offset );

	list($year,$month,$day,$hour,$minute,$second) = sscanf($datetime,"%d-%d-%d %d:%d:%d");
	$localtime = mktime($hour+$offset ,$minute, $second, $month, $day, $year, -1);

	return date($roster->locale->act['phptimeformat'], $localtime);
}

/**
 * Gets a file's extention passed as a string
 *
 * @param string $filename
 * @return string
 */
function get_file_ext( $filename )
{
	return strtolower(ltrim(strrchr($filename,'.'),'.'));
}

/**
 * Converts seconds to a string delimited by time values
 * Will show w,d,h,m,s
 *
 * @param string $seconds
 * @return string
 */
function seconds_to_time( $seconds )
{
	while( $seconds >= 60 )
	{
		if( $seconds >= 86400 )
		{
			$days = floor($seconds / 86400);
			$seconds -= ($days * 86400);
		}
		elseif( $seconds >= 3600 )
		{
			$hours = floor($seconds / 3600);
			$seconds -= ($hours * 3600);
		}
		elseif( $seconds >= 60 )
		{
			$minutes = floor($seconds / 60);
			$seconds -= ($minutes * 60);
		}
	}

	// convert variables into sentence structure components
	$days = ( isset($days) ? $days . 'd, ' : '' );
	$hours = ( isset($hours) ? $hours . 'h, ' : '' );
	$minutes = ( isset($minutes) ? $minutes . 'm, ' : '' );
	$seconds = ( isset($seconds) ? $seconds . 's' : '' );

	return array('days' => $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $seconds);
}

/**
 * Sets up addon data for use in the addon framework
 *
 * @param string $addonname | The name of the addon
 * @return array $addon  | The addon's database record
 *
 * @global array $addon_conf | The addon's config data is added to this global array.
 */
function getaddon( $addonname )
{
	global $roster;

	if ( !isset($roster->addon_data[$addonname]) )
	{
		roster_die(sprintf($roster->locale->act['addon_not_installed'],$addonname),$roster->locale->act['addon_error']);
	}

	$addon = $roster->addon_data[$addonname];

	// Get the addon's location
	$addon['dir'] = ROSTER_ADDONS . $addon['basename'] . DIR_SEP;

	// Get the addons url
	$addon['url'] = 'addons/' . $addon['basename'] . '/';
	$addon['url_full'] = ROSTER_URL . $addon['url'];
	$addon['url_path'] = ROSTER_PATH . $addon['url'];

	// Get addons url to images directory
	$addon['image_url'] = ROSTER_URL . $addon['url'] . 'images/';
	$addon['image_path'] = ROSTER_PATH . $addon['url'] . 'images/';

	// Get the addon's css style
	$addon['css_file'] = $addon['dir'] . 'style.css';

	if( file_exists($addon['css_file']) )
	{
		$addon['css_url'] = $addon['url_path'] . 'style.css';
	}
	else
	{
		$addon['css_url'] = '';
	}

	// Get the addon's inc dir
	$addon['inc_dir'] = $addon['dir'] . 'inc' . DIR_SEP;

	// Get the addon's conf file
	$addon['conf_file'] = $addon['inc_dir'] . DIR_SEP . 'conf.php';

	// Get the addon's search file
	$addon['search_file'] = $addon['inc_dir'] . DIR_SEP . 'search.inc.php';

	// Get the addon's locale dir
	$addon['locale_dir'] = $addon['dir'] . 'locale' . DIR_SEP;

	// Get the addon's admin dir
	$addon['admin_dir'] = $addon['dir'] . 'admin' . DIR_SEP;

	// Get the addon's trigger file
	$addon['trigger_file'] = $addon['inc_dir'] . 'update_hook.php';

	// Get the addon's ajax functions file
	$addon['ajax_file'] = $addon['inc_dir'] . 'ajax.php';

	// Get config values for the default profile and insert them into the array
	$addon['config'] = '';

	$query = "SELECT `config_name`, `config_value` FROM `" . $roster->db->table('addon_config') . "` WHERE `addon_id` = '" . $addon['addon_id'] . "' ORDER BY `id` ASC;";

	$result = $roster->db->query($query);

	if ( !$result )
	{
		die_quietly($roster->db->error(),$roster->locale->act['addon_error'],__FILE__,__LINE__, $query );
	}

	if( $roster->db->num_rows($result) > 0 )
	{
		while( $row = $roster->db->fetch($result,SQL_ASSOC) )
		{
			$addon['config'][$row['config_name']] = $row['config_value'];
		}
		$roster->db->free_result($result);
	}

	return $addon;
}

/**
 * Check to see if an addon is active or not
 *
 * @param string $name | Addon basename
 * @return bool
 */
function active_addon( $name )
{
	global $roster;

	if( !isset($roster->addon_data[$name]) )
	{
		return false;
	}
	else
	{
		return (bool)$roster->addon_data[$name]['active'];
	}
}

/**
 * Handles retrieving the contents of a URL trying multiple methods
 * Current methods are curl, file_get_contents, fsockopen and will try each in that order
 *
 * @param string $url	| URL to retrieve
 * @param int $timeout	| Timeout for curl, socket connection timeout for fsock
 * @param  string $user_agent	| Useragent to use for connection
 * @return mixed		| False on error, contents on success
 */
function urlgrabber( $url , $timeout = 5 , $user_agent=false, $loopcount = 0 )
{
	global $roster;

	$cache_tag = 'JSESSIONID';

	$jSessionId = '';
	if( $roster->cache->check($cache_tag) && ! preg_match('/jsessionid/', $url) )
	{
		$jSessionId = $roster->cache->get($cache_tag);
		list ($mainUrl, $getVars) = split ("\?", $url);
		$url = $mainUrl. ';jsessionid=' . $jSessionId . '?' . $getVars;
	}

	$loopcount++;
	$contents = '';

	if( $loopcount > 2 )
	{
		trigger_error("UrlGrabber Error: No many loops. Unable to grab URL ($url)", E_USER_WARNING);
		return $contents;
	}

	if( function_exists('curl_init') )
	{
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		if( $user_agent )
		{
			curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		}
		$contents = curl_exec($ch);

		// If there were errors
		if( curl_errno($ch) )
		{
			trigger_error('UrlGrabber Error [CURL]: ' . curl_error($ch), E_USER_WARNING);
			return false;
		}
		$lastUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$tmp;
		if( preg_match('/(?:jsessionid=)(.+)?\?/', $lastUrl, $tmp) )
		{
			$roster->cache->put($tmp[1], $cache_tag);
		}


		curl_close($ch);

		return $contents;
	}
	elseif( preg_match('/\bhttps?:\/\/([-A-Z0-9.]+):?(\d+)?(\/[-A-Z0-9+&@#\/%=~_|!:,.;]*)?(\?[-A-Z0-9+&@#\/%=~_|!:,.;]*)?/i', $url, $matches) )
	{
		// 0 = $url, 1 = host, 2 = port or null, 3 = page requested, 4 = pararms
		$host = $matches[1];
		$port = (($matches[2] == '') ? 80 : $matches[2]);
		$page = $matches[3];
		$page_params = ( isset($matches[4]) ? $matches[4] : '' );

		$file = fsockopen($host, $port, $errno, $errstr, $timeout);
		if( !$file )
		{
			trigger_error("UrlGrabber Error [fsock]: $errstr ($errno)", E_USER_WARNING);
			return false;
		}
		else
		{
			$header = "GET $page$page_params HTTP/1.0\r\n"
					. "Host: $host\r\n"
					. "User-Agent: $user_agent\r\n"
					. "Connection: Close\r\n\r\n";
			fwrite($file, $header);
			stream_set_timeout($file, $timeout);
			$inHeader = true;
			$redirect = false;
			$tmp = '';
			while( !feof($file) )
			{
				$chunk = fgets($file, 256);
				//print htmlspecialchars($chunk). "<br />";
				if( $inHeader )
				{
					$pos = strpos($chunk, '<');
					if( $pos !== false )
					{
						$contents .= substr( $chunk, $pos, strlen($chunk) );
						$inHeader = false;
					}
					if( preg_match('/^(?:Set-Cookie: JSESSIONID=)(.+)?\;/', $chunk, $tmp) )
					{
						$roster->cache->put($tmp[1], $cache_tag);
					}
					if( preg_match('/^(?:Location:\s)(.+)/', $chunk, $tmp) )
					{
						$redirect = $tmp[1];
					}
					continue;
				}
				$contents .= $chunk;
			}
			fclose($file);
			if( $redirect != false )
			{
				return urlgrabber( $redirect, $timeout, $user_agent, $loopcount );
			}
			else
			{
				return $contents;
			}
		}
	}
	elseif( $contents = file_get_contents($url) )
	{
		return $contents;
	}
	else
	{
		trigger_error("UrlGrabber Error: Unable to grab URL ($url)", E_USER_WARNING);
		return false;
	}
} //-END function urlgrabber()

/**
 * Stupid function to create an REQUEST_URI for IIS 5 servers
 *
 * @return string
 */
function request_uri( )
{
	if( ereg('IIS', $_SERVER['SERVER_SOFTWARE']) && isset($_SERVER['SCRIPT_NAME']) )
	{
		$REQUEST_URI = $_SERVER['SCRIPT_NAME'];
		if( isset($_SERVER['QUERY_STRING']) )
		{
			$REQUEST_URI .= '?' . $_SERVER['QUERY_STRING'];
		}
	}
	else
	{
		$REQUEST_URI = $_SERVER['REQUEST_URI'];
	}
	# firefox encodes url by default but others don't
	$REQUEST_URI = urldecode($REQUEST_URI);
	# encode the url " %22 and <> %3C%3E
	$REQUEST_URI = str_replace('"', '%22', $REQUEST_URI);
	$REQUEST_URI = preg_replace('#([\x3C\x3E])#e', '"%".bin2hex(\'\\1\')', $REQUEST_URI);
	$REQUEST_URI = substr($REQUEST_URI, 0, strlen($REQUEST_URI)-strlen(stristr($REQUEST_URI, '&CMSSESSID')));

	return $REQUEST_URI;
}


/**
 * Attempts to write a file to the file system
 *
 * @param string $filename
 * @param string $content
 * @param string $mode
 * @return bool
 */
function file_writer( $filename , &$content , $mode='wb' )
{
	if(!$fp = fopen($filename, $mode))
	{
		trigger_error("Cannot open file ($filename)", E_USER_WARNING);
		return false;
	}
	flock($fp, LOCK_EX);
	$bytes_written = fwrite($fp, $content);
	flock($fp, LOCK_UN);
	fclose($fp);
	if($bytes_written === FALSE)
	{
		trigger_error("Couldn't write to file ($filename)", E_USER_WARNING);
		return false;
	}
	if( !defined('PHP_AS_NOBODY') )
	{
		php_as_nobody($filename);
	}
	chmod($filename, (PHP_AS_NOBODY ? 0666 : 0644));
	return true;
}

function php_as_nobody( $file )
{
	if( !defined('PHP_AS_NOBODY') )
	{
		define('PHP_AS_NOBODY', (ROSTER_PROCESS_OWNER == 'nobody' || getmyuid() != fileowner($file)));
	}
}

/**
 * Debugging function dumps arrays/object formatted
 * Do Not call this, call aprint()
 *
 * @param array $arr
 * @param int $tab
 * @return string
 */
function _aprint( $arr , $tab=1 )
{
	$obj = false;
	if( is_object($arr) )
	{
		$obj = get_class($arr);
		$arr = get_object_vars($arr);
	}
	if( !is_array($arr) )
	{
		return " <span style=\"color:#3366FF\">" . ucfirst(gettype($arr)) . "</span> " . $arr;
	}

	$space = str_repeat("\t", $tab);
	$out = " <span style=\"color:#3366FF\">" . ($obj ? $obj . ' object' : 'Array') . "(</span>\n";
	end($arr); $end = key($arr);

	if( count($arr) == 0 )
	{
		return "<span style=\"color:#3366FF\">" . ($obj ? $obj . ' object' : 'Array') . "()</span>";
	}

	foreach( $arr as $key=>$val )
	{
		if( $key == $end )
		{
			$colon = '';
		}
		else
		{
			$colon = ',';
		}

		if( !is_numeric($key) )
		{
			$key = "<span style=\"color:#FFFFFF\">'" . htmlspecialchars($key) . "'</span>";
		}

		if( is_object($val) || is_array($val) )
		{
			$val = _aprint($val, ($tab+1));
		}
		elseif( is_bool($val) )
		{
			if( $val )
			{
				$val = "<span style=\"color:#3366FF\">True</span>";
			}
			else
			{
				$val = "<span style=\"color:#FF6633\">False</span>";
			}
		}
		elseif( is_null($val) )
		{
			$val = "<span style=\"color:#3366FF\">Null</span>";
		}
		elseif( is_resource($val) )
		{
			$val = "<span style=\"color:#3366FF\">" . get_resource_type($val) . " resource</span>";
		}
		else
		{
			if( !is_numeric($val) )
			{
				$val = "<span style=\"color:#FFFFFF\">'" . htmlspecialchars($val) . "'</span>";
			}
		}
		$out .= "$space$key => $val$colon\n";
	}

	if( $tab == 1 )
	{
		return "$out$space<span style=\"color:#3366ff\">)</span>;";
	}
	else
	{
		return "$out$space<span style=\"color:#3366ff\">)</span>";
	}
}

/**
 * Debugging function dumps arrays/object formatted
 *
 * @param array $arr
 * @param string $prefix
 * @return string
 */
function aprint( $arr , $prefix='' , $return=false )
{
	if( ltrim($prefix) != '' )
	{
		$prefix = '<span style="color:#3366ff">' . $prefix . '</span> =';
	}
	if( $return )
	{
		return "\n\n<pre style=\"text-align:left;color:#000000;margin:1px;background:#555555;border:1px solid #D8DDE6;\">$prefix" . _aprint($arr) . "</pre>\n\n";
	}
	else
	{
		echo "\n\n<pre style=\"text-align:left;color:#000000;margin:1px;background:#555555;border:1px solid #D8DDE6;\">$prefix" . _aprint($arr) . "</pre>\n\n";
	}
}

/**
 * Helper function that returns the localized gem color in english
 *
 * @param string $socket_color
 * @return string $color
 */
function socketColorEn( $socket_color, $locale )
{
	global $roster;

	if( $locale == 'enUS' )
	{
		return strtolower($socket_color);
	}
	elseif( $locale == 'deDE' )
	{
		switch( trim($socket_color) )
		{
			case 'Roter':
				return 'red';
			case 'Blauer':
				return 'blue';
			case 'Gelber':
				return 'yellow';
			default:
				break;
		}
	}

	$colorArr = array_flip($roster->locale->wordings[$locale]['gem_colors']);
	return (string)strtolower($colorArr[$socket_color]);
}

function format_microtime( )
{
	list($usec, $sec) = explode(' ', microtime());
	return ($usec + $sec);
}

/**
 * A better array_merge()
 *
 * @param array $skel
 * @param array $arr
 * @return array
 */
function array_overlay( $skel , $arr )
{
	foreach ($skel as $key => $val)
	{
		if( !isset($arr[$key]) )
		{
			$arr[$key] = $val;
		}
		elseif( is_array($val) )
		{
			$arr[$key] = array_overlay($val, $arr[$key]);
		}
		else
		{
			// UnComment if you want to know if you are overwritting a locale variable
			//trigger_error('Key already set: ' . $key . '->' . $arr[$key] . '<br />&nbsp;&nbsp;New value tried: ' . $skel[$key]);
		}
	}

	return $arr;
}

/**
 * Checks an addon download id on the wowroster.net rss feed
 * And informs if there is an update
 *
 * @param string $name | name of the download
 * @param string $url | url
 */
function updateCheck( $addon )
{
	global $roster;

	$ver_latest = $ver_info = $ver_link = $ver_date = $return = '';

	if( isset($addon['wrnet_id']) && !empty($addon['wrnet_id']) && isset($roster->config['versioncache']) )
	{
		$cache = unserialize($roster->config['versioncache']);
		if( !isset($cache[$addon['basename']]) )
		{
			$cache[$addon['basename']] = time();
			$roster->db->query ( "UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '" . serialize($cache) . "' WHERE `id` = '6' LIMIT 1;");
		}

		if( ($cache[$addon['basename']] + (60 * 60 * $roster->config['check_updates'])) <= time() )
		{
			$cache[$addon['basename']] = time();
			$roster->db->query ( "UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '" . serialize($cache) . "' WHERE `id` = '6' LIMIT 1;");

			$content = urlgrabber(sprintf(ROSTER_ADDONUPDATEURL,$addon['wrnet_id']));

			if( preg_match('#<version>(.+)</version>#i',$content,$info) )
			{
				$ver_latest = $info[1];
			}

			if( preg_match('#<info>(.+)</info>#i',$content,$info) )
			{
				$ver_info = '<br />' . $info[1];
			}

			if( preg_match_all('#<link>(.+)</link>#i',$content,$info) )
			{
				$ver_link = $info[1][2];
			}

			if( preg_match('#<updated>(.+)</updated>#i',$content,$info) )
			{
				$ver_date = date($roster->locale->act['phptimeformat'], $info[1]);
			}

			if( version_compare($ver_latest,$addon['version'],'>') )
			{
				// Save current locale array
				// Since we add all locales for localization, we save the current locale array
				// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
				$localetemp = $roster->locale->wordings;

				foreach( $roster->multilanguages as $lang )
				{
					$roster->locale->add_locale_file(ROSTER_ADDONS . $addon['basename'] . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php',$lang);
				}

				$name = ( isset($roster->locale->act[$addon['fullname']]) ? $roster->locale->act[$addon['fullname']] : $addon['fullname'] );

				// Restore our locale array
				$roster->locale->wordings = $localetemp;
				unset($localetemp);

				$return = messagebox(sprintf($roster->locale->act['new_version_available'],$name,$ver_latest,$ver_date,$ver_link) . $ver_info,$roster->locale->act['update']);
			}
		}
	}
	return $return;
}

/**
 * Dummy function. For when you need a callback that doesn't do anything.
 */
function dummy(){}
