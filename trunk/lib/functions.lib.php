<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Starts or ends fancy bodering containers
 *
 * @param string $style What bordering style to use
 * @param string $mode ( start | end )
 * @param string $header_text Place text in a styled header
 * @return string
 */
function border($style,$mode,$header_text=null)
{
	$backg_css = $style.'border';
	if( substr($style,0,1) == 's' )
	{
		$style = 'simple';
	}
	$style .= 'border';

	if( $header_text != '' && $style != 'end' )
	{
		$header_text = '  <tr>
    <td class="'.$style.'_c_l '.$backg_css.'_c_l"></td>
    <th class="'.$style.'header '.$backg_css.'header" align="center" valign="top">'.$header_text.'</th>
    <td class="'.$style.'_c_r '.$backg_css.'_c_r"></td>
  </tr>';
	}
	else
	{
		$header_text = '';
	}

	// Dynamic Bordering
	$start = '
<!-- START [open-'.$style.'] container -->
<table cellspacing="0" cellpadding="0" border="0">
  <tr>
   <td class="'.$style.'_t_l '.$backg_css.'_t_l"></td>
   <td class="'.$style.'_t '.$backg_css.'_t"></td>
   <td class="'.$style.'_t_r '.$backg_css.'_t_r"></td>
  </tr>
'.$header_text.'
  <tr>
    <td class="'.$style.'_c_l '.$backg_css.'_c_l"></td>
    <td class="'.$style.'_c">
<!-- END [open-'.$style.'] container -->';

	$end = '
<!-- START [close-'.$style.'] container -->
    </td>
    <td class="'.$style.'_c_r '.$backg_css.'_c_r"></td>
  </tr>
  <tr>
   <td class="'.$style.'_b_l '.$backg_css.'_b_l"></td>
   <td class="'.$style.'_b '.$backg_css.'_b"></td>
   <td class="'.$style.'_b_r '.$backg_css.'_b_r"></td>
  </tr>
</table>
<!-- END [close-'.$style.'] container -->';

	switch ($mode)
	{
		case 'start':
			return $start;
			break;

		case 'end':
			return $end;
			break;
	}
}


$tooltips = array();
/**
 * Makes a tootip and places it into the tooltip array
 *
 * @param string $var
 * @param string $content
 */
function setTooltip( $var , $content )
{
	global $tooltips;

	$content = str_replace("\n",'',$content);
	$content = addslashes($content);
	$content = str_replace('</','<\\/',$content);
	$content = str_replace('/>','\\/>',$content);

	$tooltips += array($var=>$content);
}


/**
 * Gathers all tootips and places them into javascript variables
 *
 * @param array $tooltipArray
 * @return string Tooltips placed in javascript variables
 */
function getAllTooltips()
{
	global $tooltips;

	if( is_array($tooltips) )
	{
		$ret_string = "<script type=\"text/javascript\">\n<!--\n";
		foreach ($tooltips as $var => $content)
		{
			$ret_string .= "\tvar overlib_$var = \"".str_replace('--','-"+"-',$content)."\";\n";
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
	global $db_prefix;

	// Make table names bold
	$sql = preg_replace('/' . $db_prefix .'(\S+?)([\s\.,]|$)/', '<span class="blue">' . $db_prefix . "\\1\\2</span>", $sql);

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
function die_quietly( $text='', $title='Message', $file='', $line='', $sql='' )
{

	global $wowdb, $roster_conf, $wordings, $act_words;

	// die_quitely died quietly
	if(defined('ROSTER_DIED') )
	{
		print '<pre>The quiet die function suffered a fatal error. Die information below'."\n";
		print 'First die data:'."\n";
		print_r($GLOBALS['die_data']);
		print "\n".'Second die data'."\n";
		print_r(func_get_args());
		exit();
	}

	define( 'ROSTER_DIED', true );

	$GLOBALS['die_data'] = func_get_args();

	$header_title = $title;

	if( !defined('ROSTER_HEADER_INC') && is_array($roster_conf) )
	{
		include_once(ROSTER_BASE.'roster_header.tpl');
	}

	if( !defined('ROSTER_MENU_INC') && is_array($roster_conf) )
	{
		$roster_menu = new RosterMenu;
		print $roster_menu->makeMenu('main');
	}

	if( is_object($wowdb) )
	{
		$wowdb->closeDb();
	}

	print border('sred','start',$title).'<table class="bodyline" cellspacing="0" cellpadding="0">'."\n";

	if( !empty($text) )
	{
		print "<tr>\n<td class=\"membersRowRight1\" style=\"white-space:normal;\"><div align=\"center\">$text</div></td>\n</tr>\n";
	}
	if( !empty($sql) )
	{
		print "<tr>\n<td class=\"membersRowRight1\" style=\"white-space:normal;\">SQL:<br />".sql_highlight($sql)."</td>\n</tr>\n";
	}
	if( !empty($file) )
	{
		print "<tr>\n<td class=\"membersRowRight1\">File: $file</td>\n</tr>\n";
	}
	if( !empty($line) )
	{
		print "<tr>\n<td class=\"membersRowRight1\">Line: $line</td>\n</tr>\n";
	}

	if( $roster_conf['debug_mode'] )
	{
		print "<tr>\n<td class=\"membersRowRight1\">";
		print  backtrace();
		print "</td>\n</tr>\n";
	}

	print "</table>\n".border('sred','end');

	if( !defined('ROSTER_FOOTER_INC') && is_array($roster_conf) )
	{
		include_once(ROSTER_BASE.'roster_footer.tpl');
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
function roster_die($message, $title = 'Message', $style = 'sred')
{
	global $wowdb, $roster_conf, $wordings, $act_words;

	if( !defined('ROSTER_HEADER_INC') && is_array($roster_conf) )
	{
		include_once(ROSTER_BASE.'roster_header.tpl');
	}

	if( !defined('ROSTER_MENU_INC') && is_array($roster_conf) )
	{
		$roster_menu = new RosterMenu;
		print $roster_menu->makeMenu('main');
	}

	if( is_object($wowdb) )
	{
		$wowdb->closeDb();
	}

	print messagebox('<div align="center">'.$message.'</div>',$title,$style);

	if( is_array($roster_conf) )
	{
		include_once(ROSTER_BASE.'roster_footer.tpl');
	}

	exit();
}

/**
 * Print a debug backtrace. This works in PHP4.3.x+, there is an integrated
 * function for this starting PHP5 but I prefer always having the same layout.
 */
function backtrace()
{
	if( version_compare( phpversion(), '4.3', '<' ) )
	{
		return "Unable to print backtrace: PHP version too low";
	}
	$bt = debug_backtrace();

	$output = "Backtrace (most recent call last):<ul>\n";
	for($i = 0; $i <= count($bt) - 1; $i++)
	{
		if(!isset($bt[$i]["file"]))
		{
			$output .= "<li>[PHP core called function]<ul>\n";
		}
		else
		{
			$output .= "<li>File: ".$bt[$i]["file"]."<ul>\n";
		}

		if(isset($bt[$i]["line"]))
		{
			$output .= "<li>line ".$bt[$i]["line"]."</li>\n";
		}
		$output .= "<li>function called: ".$bt[$i]["function"]."</li>\n";

		if($bt[$i]["args"])
		{
			$output .= "<li>args: ";
			for($j = 0; $j <= count($bt[$i]["args"]) - 1; $j++)
			{
				if( is_array($bt[$i]["args"][$j]) )
				{
					$output .= print_r($bt[$i]["args"][$j],true);
				}
				else
				{
					$output .= $bt[$i]["args"][$j] ;
				}

				if($j != count($bt[$i]["args"]) - 1)
				{
					$output .= ", ";
				}
			}
			$output .= "</li>\n";
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
function stripAllHtml($string)
{
	$search = array ('@<script[^>]*?>.*?</script>@si', // Strip out javascript
					'@<[\/\!]*?[^<>]*?>@si',           // Strip out HTML tags
					'@([\r\n])[\s]+@',               // Strip out white space
					'@&(quot|#34);@i',                 // Replace HTML entities
					'@&(amp|#38);@i',
					'@&(lt|#60);@i',
					'@&(gt|#62);@i',
					'@&(nbsp|#160);@i',
					'@&(iexcl|#161);@i',
					'@&(cent|#162);@i',
					'@&(pound|#163);@i',
					'@&(copy|#169);@i',
					'@&#(\d+);@e');                     // evaluate as php

	$replace = array ('','',"\n",'"','&','<','>',' ',chr(161),chr(162),chr(163),chr(169),'chr(\1)');

	$string = preg_replace($search, $replace, $string);

	return $string;
}

/**
 * This will check if the given Filename is an image
 *
 * @param imagefile $file
 * Returns the extentsion if the filetype is an image
 */
function check_if_image($imagefilename)
{
	if( ($extension = pathinfo($imagefilename, PATHINFO_EXTENSION)) === FALSE )
	{
		return false;
	}
	else
	{
	  	switch($extension)
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
 * Default is $roster_conf['roster_lang']
 * @param bool $inline_caption | (optional)
 * Default is true
 * @return string | Formatted tooltip
 */
function colorTooltip( $tooltip , $caption_color='' , $locale='' , $inline_caption=1 )
{
	global $wordings, $roster_conf, $act_words;

	// Use main locale if one is not specified
	if( $locale == '' )
		$locale = $roster_conf['roster_lang'];

	// Detect caption mode and display accordingly
	if( $inline_caption )
		$first_line = true;
	else
		$first_line = false;


	// Initialize tooltip_out
	$tooltip_out = '';

	// Color parsing time!
	$tooltip = str_replace('<br>',"\n",$tooltip);
	$tooltip = str_replace('<br />',"\n",$tooltip);
	foreach (explode("\n", $tooltip) as $line )
	{
		$color = '';

		if( !empty($line) )
		{
			$line = preg_replace('|\\>|','&#8250;', $line );
			$line = preg_replace('|\\<|','&#8249;', $line );
			$line = preg_replace('/\|c[a-f0-9]{2}([a-f0-9]{6})(.+?)\|r/i','<span style="color:#$1;">$2</span>',$line);

			// Do this on the first line
			// This is performed when $caption_color is set
			if( $first_line )
			{
				if( $caption_color == '' )
					$caption_color = 'ffffff';

				if( strlen($caption_color) > 6 )
					$color = substr( $caption_color, 2, 6 ) . ';font-size:11px;font-weight:bold';
				else
					$color = $caption_color . ';font-size:11px;font-weight:bold';

				$first_line = false;
			}
			else
			{
				if ( ereg('^'.$wordings[$locale]['tooltip_use'],$line) )
					$color = '00ff00';
				elseif ( ereg('^'.$wordings[$locale]['tooltip_requires'],$line) )
					$color = 'ff0000';
				elseif ( ereg('^'.$wordings[$locale]['tooltip_reinforced'],$line) )
					$color = '00ff00';
				elseif ( ereg('^'.$wordings[$locale]['tooltip_equip'],$line) )
					$color = '00ff00';
				elseif ( ereg('^'.$wordings[$locale]['tooltip_chance'],$line) )
					$color = '00ff00';
				elseif ( ereg('^'.$wordings[$locale]['tooltip_enchant'],$line) )
					$color = '00ff00';
				elseif ( ereg('^'.$wordings[$locale]['tooltip_soulbound'],$line) )
					$color = '00bbff';
				elseif ( ereg('^'.$wordings[$locale]['tooltip_set'],$line) )
					$color = '00ff00';
				elseif (ereg('^'.$wordings[$locale]['tooltip_rank'],$line) )
					$color = '00ff00;font-weight:bold';
				elseif (ereg('^'.$wordings[$locale]['tooltip_next_rank'],$line) )
					$color = 'ffffff;font-weight:bold';
				elseif ( preg_match('|\([a-f0-9]\).'.$wordings[$locale]['tooltip_set'].'|',$line) )
					$color = '666666';
				elseif ( ereg('^"',$line) )
					$color = 'ffd517';
			}

			// Convert tabs to a formated table
			if( strpos($line,"\t") )
			{
				$line = explode("\t",$line);
				$line = '<div style="width:100%;"><span style="float:right;">'.$line[0].'</span>'.$line[1].'</div>';
				$tooltip_out .= $line;
			}
			elseif( !empty($color) )
			{
				$tooltip_out .= '<span style="color:#'.$color.';">'.$line.'</span><br />';
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
		$first_line = true;
	else
		$first_line = false;


	// Initialize tooltip_out
	$tooltip_out = '';

	// Parsing time!
	$tooltip = str_replace('<br>',"\n",$tooltip);
	$tooltip = str_replace('<br />',"\n",$tooltip);
	foreach (explode("\n", $tooltip) as $line )
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
					$caption_color = 'ffffff';

				if( strlen($caption_color) > 6 )
					$color = substr( $caption_color, 2, 6 ) . ';font-size:11px;font-weight:bold';
				else
					$color = $caption_color . ';font-size:11px;font-weight:bold';

				$first_line = false;
			}

			// Convert tabs to a formated table
			if( strpos($line,"\t") )
			{
				$line = explode("\t",$line);
				$line = '<div style="width:100%;"><span style="float:right;">'.$line[0].'</span>'.$line[1].'</div>';
				$tooltip_out .= $line;
			}
			elseif( !empty($color) )
			{
				$tooltip_out .= '<span style="color:#'.$color.';">'.$line.'</span><br />';
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
 * Default is $roster_conf['roster_lang']
 * @param string $extra_parameters | (optional) Extra OverLib parameters you wish to pass
 * @return unknown
 */
function makeOverlib( $tooltip , $caption='' , $caption_color='' , $mode=0 , $locale='' , $extra_parameters='' )
{
	global $wordings, $roster_conf, $tooltips, $act_words;

	$tooltip = stripslashes($tooltip);

	// Use main locale if one is not specified
	if( $locale == '' )
		$locale = $roster_conf['roster_lang'];

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
			$caption = '<span style="color:#'.$caption_color.';">'.$caption.'</span>';
		}

		$caption = ",CAPTION,'".addslashes($caption)."'";

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

	return 'onmouseover="return overlib(overlib_'.$num_of_tips.$caption.$extra_parameters.');" onmouseout="return nd();"';
}

/**
 * Draw a message box with the specified border color.
 *
 * @param string $message | The message to display inside the box
 * @param string $title | The box title
 * @param string $style | The border style
 * @return string $html | The HTML for the messagebox
 */
function messagebox($message, $title = 'Message', $style = 'sgray')
{
	return
		border($style, 'start', $title).
		'<div align="center" style="background-color:#1F1E1D;">'.
		$message.
		'</div>'.
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
function scrollbox($message, $title = 'Message', $style = 'sgray', $width = '550px', $height = '300px')
{
	return
		border($style,'start',$title).
		'<div style="font-size:10px;background-color:#1F1E1D;text-align:left;height:'.$height.';width:'.$width.';overflow:auto;">'.
			$message.
		'</div>'.
		border($style,'end');
}

// Index to generate unique toggle IDs
$toggleboxes = 0;

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
function messageboxtoggle($message, $title = 'Message', $style = 'sgray', $open = false, $width = '550px')
{
	global $toggleboxes, $roster_conf;

	$toggleboxes++;

	$title = "<div style=\"cursor:pointer;width:".$width.";\" onclick=\"showHide('msgbox_".$toggleboxes."','msgboximg_".$toggleboxes."','".$roster_conf['img_url']."minus.gif','".$roster_conf['img_url']."plus.gif');\"><img src=\"".$roster_conf['img_url'].(($open)?'minus':'plus').".gif\" style=\"float:right;\" alt=\"\" id=\"msgboximg_".$toggleboxes."\" />".$title."</div>";

	return
		border($style, 'start', $title).
		'<div style="display:'.(($open)?'inline':'none').';background-color:#1F1E1D;" id="msgbox_'.$toggleboxes.'">'.
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
function scrollboxtoggle($message, $title = 'Message', $style = 'sgray', $open = false, $width = '550px', $height = '300px')
{
	global $toggleboxes, $roster_conf;

	$toggleboxes++;

	$title = "<div style=\"cursor:pointer;width:".$width.";\" onclick=\"showHide('msgbox_".$toggleboxes."','msgboximg_".$toggleboxes."','".$roster_conf['img_url']."minus.gif','".$roster_conf['img_url']."plus.gif');\"><img src=\"".$roster_conf['img_url'].(($open)?'minus':'plus').".gif\" style=\"float:right;\" alt=\"\" id=\"msgboximg_".$toggleboxes."\" />".$title."</div>";

	return
		border($style,'start',$title).
		'<div style="font-size:10px;background-color:#1F1E1D;text-align:left;height:'.$height.';width:'.$width.';overflow:auto;display:'.(($open)?'inline':'none').';" id="msgbox_'.$toggleboxes.'">'.
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
function escape_array($array)
{
	global $wowdb;
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
 * Calculates the last updated value
 *
 * @param string $updateTimeUTC dateupdatedutc
 * @return string formatted date string
 */
function DateDataUpdated($updateTimeUTC)
{
	global $roster_conf, $act_words;

	list($year,$month,$day,$hour,$minute,$second) = sscanf($updateTimeUTC,"%d-%d-%d %d:%d:%d");
	$localtime = mktime($hour+$roster_conf['localtimeoffset'] ,$minute, $second, $month, $day, $year, -1);

	return date($act_words['phptimeformat'], $localtime);
}

function get_file_ext( $filename )
{
	return strtolower(ltrim(strrchr($filename,'.'),'.'));
}

function seconds_to_time($seconds)
{
	while( $seconds >= 60 )
	{
		if( $seconds >= 86400 )
		{
		// there is more than 1 day
			$days = floor($seconds / 86400);
			$seconds = $seconds - ($days * 86400);
		}
		elseif( $seconds >= 3600 )
		{
			$hours = floor($seconds / 3600);
			$seconds = $seconds - ($hours * 3600);
		}
		elseif( $seconds >= 60 )
		{
			$minutes = floor($seconds / 60);
			$seconds = $seconds - ($minutes * 60);
		}
	}

	// convert variables into sentence structure components
	if( !isset($days) )
	{
		$days = '';
	}
	else
	{
		if( $days == 1 )
		{
			$days = $days.'d, ';
		}
		else
		{
			$days = $days.'d, ';
		}
	}
	if( !isset($hours) )
	{
		$hours = '';
	}
	else
	{
		$hours = $hours.'h, ';
	}
	if( !isset($minutes) )
	{
		$minutes = '';
	}
	else
	{
		$minutes = $minutes.'m, ';
	}
	if( !isset($seconds) )
	{
		$seconds = '';
	}
	else
	{
		$seconds = $seconds.'s';
	}

	return array('days' => $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $seconds);
}

/**
 * Includes an addon's files to create the addon framework for that addon.
 *
 * @param string $addonname | The name of the addon
 * @return array $addon  | The addon's database record
 *
 * @global array $addon_conf | The addon's config data is added to this global array.
 */
function getaddon( $addonname )
{
	global $wowdb, $act_words, $wordings;

	// Get addon registration entry
	$query = "SELECT * FROM `".$wowdb->table('addon')."` WHERE `basename` = '$addonname' LIMIT 1";

	$result = $wowdb->query( $query );

	if ( !$result )
	{
		die_quietly($wowdb->error(),$act_words['addon_error'],__FILE__,__LINE__, $query );
	}

	if ( $wowdb->num_rows($result) != 1 )
	{
		roster_die(sprintf($act_words['addon_not_installed'],$addonname),$act_words['addon_error']);
	}

	$addon = $wowdb->fetch_assoc($result);

	$wowdb->free_result($result);

	// Get the addon's location
	$addon['dir'] = ROSTER_ADDONS.$addon['basename'].DIR_SEP;

	// Get the addon's index file
	$addon['index_file'] = $addon['dir'].'index.php';

	// Get the addon's css style
	$addon['css_file'] = $addon['dir'].'style.css';

	if( file_exists($addon['css_file']) )
	{
		$addon['css_url'] = '/addons/'.$addon['basename'].'/style.css';
	}
	else
	{
		$addon['css_url'] = '';
	}

	// Get the addon's conf file
	$addon['conf_file'] = $addon['dir'].'conf.php';

	// Get the addon's locale file
	$addon['locale_dir'] = $addon['dir'].'locale'.DIR_SEP;

	// Get the addon's admin file
	$addon['admin_file'] = $addon['dir'].'admin.php';

	// Get the addon's ajax functions file
	$addon['ajax_file'] = $addon['dir'].'ajax.php';

	// Get config values for the default profile and insert them into the array
	$addon['config'] = '';

	$query = "SELECT `config_name`, `config_value` FROM `".$wowdb->table('addon_config')."` WHERE `addon_id` = ".$addon['addon_id']." ORDER BY `id` ASC;";

	$result = $wowdb->query( $query );

	if ( !$result )
	{
		die_quietly($wowdb->error(),$act_words['addon_error'],__FILE__,__LINE__, $query );
	}

	if( $wowdb->num_rows($result) > 0 )
	{
		while( $row = $wowdb->fetch_assoc($result) )
		{
			$addon['config'][$row['config_name']] = stripslashes($row['config_value']);
		}
		$wowdb->free_result($result);
	}


	return $addon;
}

/**
 * Adds locale strings to global $wordings array
 *
 * @param string $localefile | Full path to locale file
 * @param string $locale | Locale to add to (IE: enUS)
 * @param array $array | Array you would like to add the locale strings to
 */
function add_locale_file( $localefile , $locale , &$array )
{
	global $roster_conf;

	include($localefile);

	if( isset($array[$locale]) )
	{
		$array[$locale] = array_merge($array[$locale], $lang);
	}
	else
	{
		$array[$locale] = array();
		$array[$locale] = array_merge($array[$locale], $lang);
	}

	unset($lang);
}
