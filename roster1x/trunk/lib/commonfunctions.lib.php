<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
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
	global $roster_conf;

	$backg_css = $style.'border';
	if( substr($style,0,1) == 's' )
	{
		$style = 'simple';
	}
	$style .= 'border';

	if( $header_text != '' && $style != 'end' )
	{
		$header_text = '  <tr>
    <td class="'.$style.'centerleft '.$backg_css.'centerleft"></td>
    <th class="'.$style.'header '.$backg_css.'header" align="center" valign="top">'.$header_text.'</th>
    <td class="'.$style.'centerright '.$backg_css.'centerright"></td>
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
   <td class="'.$style.'topleft '.$backg_css.'topleft"></td>
   <td class="'.$style.'top '.$backg_css.'top"></td>
   <td class="'.$style.'topright '.$backg_css.'topright"></td>
  </tr>
'.$header_text.'
  <tr>
    <td class="'.$style.'centerleft '.$backg_css.'centerleft"></td>
    <td class="'.$style.'center">
<!-- END [open-'.$style.'] container -->';

	$end = '
<!-- START [close-'.$style.'] container -->
    </td>
    <td class="'.$style.'centerright '.$backg_css.'centerright"></td>
  </tr>
  <tr>
   <td class="'.$style.'botleft '.$backg_css.'botleft"></td>
   <td class="'.$style.'bot '.$backg_css.'bot"></td>
   <td class="'.$style.'botright '.$backg_css.'botright"></td>
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


/**
 * Makes a tootip and places it into the tooltip array
 *
 * @param string $var
 * @param string $content
 */
$tooltips = array();
function setTooltip( $var , $content )
{
	global $tooltips;

	$content = str_replace('</','<\\/',$content);

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
			$ret_string .= "\tvar $var = '$content';\n";
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
function die_quietly( $text='', $title='', $file='', $line='', $sql='' )
{
	global $wowdb, $roster_conf, $wordings;

	if( is_object($wowdb) )
	{
		$wowdb->closeDb();
	}

	if( !empty($title) )
	{
		$header_title = $title;
	}

	if( !defined('HEADER_INC') && is_array($roster_conf) )
	{
		include_once(ROSTER_BASE.'roster_header.tpl');
	}

	if( empty($title) )
	{
		$title = 'Message';
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

	print "</table>\n".border('sred','end');

	if( is_array($roster_conf) )
	{
		include_once(ROSTER_BASE.'roster_footer.tpl');
	}

	exit();
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
	if (($extension = pathinfo($imagefilename, PATHINFO_EXTENSION)) === FALSE)
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
			default: 			return false;
		}
	}
}

?>
