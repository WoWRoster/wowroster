<?php
/**
 * WoWRoster.net WoWRoster
 *
 * GD2 Bargraph Image Generator
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
*/

define('IN_ROSTER',true);
include('../../settings.php');

// Parameter fetching and checking
$barnames = isset($_GET['barnames'])?$_GET['barnames']:false;
$barsizes = isset($_GET['barsizes'])?$_GET['barsizes']:false;
$bar2sizes = isset($_GET['bar2sizes'])?$_GET['bar2sizes']:false;
$type = isset($_GET['type'])?$_GET['type']:false;
$side = isset($_GET['side'])?$_GET['side']:'menu_left';

if( !$barnames )
{
	debugMode(__LINE__,'Barnames not passed');
}

if( !$barsizes )
{
	debugMode(__LINE__,'Barsizes not passed');
}

if( !is_array($barnames) )
{
	debugMode(__LINE__,'Barnames is not an array');
}

if( !is_array($barsizes) )
{
	debugMode(__LINE__,'Barsizes is not an array');
}

if( count($barnames) != count($barsizes) )
{
	debugMode(__LINE__,'Barnames/barsizes array length doesn\'t match');
}

if( is_array($bar2sizes) && (count($bar2sizes) != count($barnames)) )
{
	debugMode(__LINE__,'some secondary bar sizes were passed but the count doesn\'t match');
}



$barnames = array_reverse($barnames);
$barsizes = array_reverse($barsizes);
$bar2sizes = array_reverse ($bar2sizes);


// Hardcoded options
$w = 850;
$h = 179;
$font = ROSTER_BASE . 'fonts' . DIR_SEP . $roster->config[$side . '_text'];

// calculate extra attributes
$count = count($barnames);
$colh = $h/$count;
$offset = '58';//($type == 'class') ? $colh : 0;
$factor = ($h-$offset)/max($barsizes);
$textheight = .6 * $colh;
$textbase = .7 * $colh;
$textoffset = $offset + .2 * $colh;

//echo max($barsizes).'<br>';
// ulms vars

$tm=0;

#get total members...
for($i=0; $i<$count; $i++)
{
	$tm = ($tm+($barsizes[$i]+$bar2size[$i]));
}



#end total members


$x1 = '58';
$x2 = '77';
$x3 = max($barsizes);
$x4 = '41';
$x5 = '36';
$x6 = 58;
$x7 = 78;
// end ulms vars...

// Initialize image
$image = ImageCreateFromJPEG(ROSTER_BASE . 'img' . DIR_SEP . 'graphs' . DIR_SEP . 'class-bg.jpg');//imagecreatetruecolor($w,$h);
//imagealphablending($image,false);
//$bgcolor = imagecolorallocatealpha($image,0,0,0,127);
//imagefilledrectangle($image,0,0,$w,$h,$bgcolor);
//imagealphablending($image,true);

// Initialize colors
$barcolor = setColor($image,$roster->config[$side.'_barcolor']);
$bar2color = setColor($image,$roster->config[$side.'_bar2color']);
$textcolor = setColor($image,$roster->config[$side.'_textcolor']);



// Draw bars
for($i=0; $i<$count; $i++)
{
	$xxx=0;
	$xxxx=0;
	// Get icon
	if( $type == 'class' )
	{
		$icon = imagecreatefromjpeg('../class/' . $roster->locale->act['class_iconArray'][$barnames[$i]].'.jpg');

		$thisbarcolor = $barcolor;
		$thisbar2color = $bar2color;
	}
	else
	{
		$icon = false;
		$thisbarcolor = $barcolor;
		$thisbar2color = $bar2color;
	}
	if( $icon )
	{
		imagecopyresampled($image, $icon, $x6+1, 98, 0, 0, 40, 40, 64, 64);
		imagedestroy($icon);
	}

	// If there was an error $icon will be false, otherwise add the icon
		// Draw the bar
	if( $barsizes[$i] > 0 )
	{
		//echo $offset+$barsizes[$i]*$factor.'<br>';
		//imagefilledrectangle($image, $offset, $colh * $i, $offset+$barsizes[$i]*$factor, 74, $thisbarcolor);
		$xxx = FLOOR($barsizes[$i] / $x3 * 100 / 2);
		//echo '{'.$xxx.'} - ';
		if ($type=='class'){ $thisbarcolor= setColor($image, $roster->locale->act['class_colorArray'][$barnames[$i]]);}
		imagefilledrectangle($image, $x6, 74-$xxx, $x6 + $x4, 74, $thisbarcolor);
		
	}
	if( isset($bar2sizes[$i]) && $bar2sizes[$i] > 0 )
	{
		//echo $offset+$barsizes[$i]*$factor.'<br>';
		
		$xxxx = FLOOR($bar2sizes[$i] / $x3 * 100 /2);
		//echo '('.$xxxx . ') - ';
		imagefilledrectangle($image, $x6, 74-$xxxx, $x6 + $x4/2, 74, $thisbar2color);
		
		
		//imagefilledrectangle($image, $offset, $colh * $i, $offset+$bar2sizes[$i]*$factor, 74, $thisbar2color);
	}
	
	$x6 = ($x6+77);
}
// Draw the labels
// This is separate so the text is on top of the bars
for($i=0; $i<$count; $i++)
{
	if( $type == 'class' )
	{
		$thistextcolor = setColor($image, $roster->locale->act['class_colorArray'][$barnames[$i]]);
	}
	else
	{
		$thistextcolor = $textcolor;
	}

	if( isset($font) )
	{
		$xyy = textAlignment( $font,$textheight,$barnames[$i],$x7,'center' );
		$xxyy = textAlignment( $font,$textheight,$barsizes[$i],$x7,'center' );
		
		if( $roster->config[$side.'_outlinecolor'] != '' )
		{
			writeOutline($image,$textheight,$xyy,95,$roster->config[$side.'_outlinecolor'],$font,$barnames[$i]);
			writeOutline($image,$textheight,$xxyy,59,$roster->config[$side.'_outlinecolor'],$font,$barsizes[$i]);
		}
		imagettftext($image, $textheight, 0, $xyy, 95, $thistextcolor, $font, $barnames[$i]);
		imagettftext($image, $textheight, 0, $xxyy, 59, $thistextcolor, $font, $barsizes[$i]);
	}
	$x7 = ($x7+77);
}



function textAlignment( $font,$size,$text,$where,$align = 'left' )
	{
		$txtsize = @imageTTFBBox( $size,0,$font,$text )
			or debugMode((__LINE__),$php_errormsg);		// Gets the points of the image coordinates

		switch ($align)
		{
			case 'right':
				$txt = $txtsize[2];
				break;

			case 'center':
				$txt = $txtsize[2]/2;
				break;

			default:
				$txt = 0;
				break;
		}
		$txtloc = $where-$txt;	// Sets the x coordinate where to print the server name
		return $txtloc;
	}
	
	
// Output image
header('Content-type: image/png');
imagealphablending( $image, false );
imagesavealpha( $image, true );
imagepng($image);
imagedestroy($image);


// Debug function
function debugMode( $line,$message,$file=null,$config=null,$message2=null )
{
	global $im, $configData;

	// Destroy the image
	if( isset($im) )
		imageDestroy($im);

	if( is_numeric($line) )
		$line -= 1;

	$error_text = 'Error!';
	$line_text  = 'Line: '.$line;
	$file  = ( !empty($file) ? 'File: '.$file : '' );
	$config = ( $config ? 'Check the config file' : '' );
	$message2 = ( !empty($message2) ? $message2 : '' );

	$lines = array();
	$lines[] = array( 's' => $error_text, 'f' => 5, 'c' => 'red' );
	$lines[] = array( 's' => $line_text,  'f' => 3, 'c' => 'blue' );
	$lines[] = array( 's' => $file,       'f' => 2, 'c' => 'green' );
	$lines[] = array( 's' => $message,    'f' => 2, 'c' => 'black' );
	$lines[] = array( 's' => $config,     'f' => 2, 'c' => 'black' );
	$lines[] = array( 's' => $message2,   'f' => 2, 'c' => 'black' );

	$height = $width = 0;
	foreach( $lines as $line )
	{
		if( strlen($line['s']) > 0 )
		{
			$line_width = ImageFontWidth($line['f']) * strlen($line['s']);
			$width = ( ($width < $line_width) ? $line_width : $width );
			$height += ImageFontHeight($line['f']);
		}
	}

	$im = @imagecreate($width+1,$height);
	if( $im )
	{
		$white = imagecolorallocate( $im, 255, 255, 255 );
		$red = imagecolorallocate( $im, 255, 0, 0 );
		$green = imagecolorallocate( $im, 0, 255, 0 );
		$blue = imagecolorallocate( $im, 0, 0, 255 );
		$black = imagecolorallocate( $im, 0, 0, 0 );

		$linestep = 0;
		foreach( $lines as $line )
		{
			if( strlen($line['s']) > 0 )
			{
				imagestring( $im, $line['f'], 1, $linestep, utf8_to_nce($line['s']), $$line['c'] );
				$linestep += ImageFontHeight($line['f']);
			}
		}

		switch ( $configData['image_type'] )
		{
			case 'jpeg':
			case 'jpg':
				header( 'Content-type: image/jpg' );
				@imageJpeg( $im );
				break;

			case 'png':
				header( 'Content-type: image/png' );
				@imagePng( $im );
				break;

			case 'gif':
			default:
				header( 'Content-type: image/gif' );
				@imagegif( $im );
				break;
		}
		imageDestroy( $im );
	}
	else
	{
		if( !empty($file) )
		{
			$file = "[<span style=\"color:green\">$file</span>]";
		}
		$string = "<strong><span style=\"color:red\">Error!</span></strong>";
		$string .= "<span style=\"color:blue\">Line $line:</span> $message $file\n<br /><br />\n";
		if( $config )
		{
			$string .= "$config\n<br />\n";
		}
		if( !empty($message2) )
		{
			$string .= "$message2\n";
		}
		echo $string;
	}

	exit();
}

// Function to convert strings to a compatable format
// This function was copied from http://de3.php.net/manual/de/function.imagettftext.php
// Under post made by limalopex.eisfux.de
function utf8_to_nce( $utf = '' )
{
	if($utf == '')
	{
		return($utf);
	}

	$max_count = 5;		// flag-bits in $max_mark ( 1111 1000 == 5 times 1)
	$max_mark = 248;	// marker for a (theoretical ;-)) 5-byte-char and mask for a 4-byte-char;

	$html = '';
	for($str_pos = 0; $str_pos < strlen($utf); $str_pos++)
	{
		$old_val = ord( $utf{$str_pos} );
		$new_val = 0;

		$utf8_marker = 0;

		// skip non-utf-8-chars
		if( $old_val > 127 )
		{
			$mark = $max_mark;
			for($byte_ctr = $max_count; $byte_ctr > 2; $byte_ctr--)
			{
				// actual byte is utf-8-marker?
				if( ( $old_val & $mark  ) == ( ($mark << 1) & 255 ) )
				{
					$utf8_marker = $byte_ctr - 1;
					break;
				}
				$mark = ($mark << 1) & 255;
			}
		}

		// marker found: collect following bytes
		if($utf8_marker > 1 and isset( $utf{$str_pos + 1} ) )
		{
			$str_off = 0;
			$new_val = $old_val & (127 >> $utf8_marker);
			for($byte_ctr = $utf8_marker; $byte_ctr > 1; $byte_ctr--)
			{
				// check if following chars are UTF8 additional data blocks
				// UTF8 and ord() > 127
				if( (ord($utf{$str_pos + 1}) & 192) == 128 )
				{
					$new_val = $new_val << 6;
					$str_off++;
					// no need for Addition, bitwise OR is sufficient
					// 63: more UTF8-bytes; 0011 1111
					$new_val = $new_val | ( ord( $utf{$str_pos + $str_off} ) & 63 );
				}
				// no UTF8, but ord() > 127
				// nevertheless convert first char to NCE
				else
				{
					$new_val = $old_val;
				}
			}
			// build NCE-Code
			$html .= '&#'.$new_val.';';
			// Skip additional UTF-8-Bytes
			$str_pos = $str_pos + $str_off;
		}
		else
		{
			$html .= chr($old_val);
			$new_val = $old_val;
		}
	}
	return($html);
}

// Function to set color of text
function setColor( $image,$color,$trans=0 )
{
	$red = 100;
	$green = 100;
	$blue = 100;

	$ret = array();
	if( preg_match("/[#]?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})/i",$color,$ret) )
	{
		$red = hexdec($ret[1]);
		$green = hexdec($ret[2]);
		$blue = hexdec($ret[3]);
	}

	// Get a transparent color if trans > 0
	if( $trans > 0 )
	{
		$color_index = @imageColorAllocateAlpha( $image,$red,$green,$blue,$trans )
			or debugMode((__LINE__),$php_errormsg);
	}
	else // Get a regular color
	{
		// Damn, we cannot supress this function...
		$color_index = imageColorAllocate( $image,$red,$green,$blue );
	}

	return $color_index;
}

function writeOutline( $image , $size , $xpos , $ypos , $color , $font , $text , $width=1 )
{
	$color = setColor($image,$color);

	// For every X pixel to the left and the right
	for( $xc=$xpos-abs($width);$xc<=$xpos+abs($width);$xc++ )
	{
		// For every Y pixel to the top and the bottom
		for( $yc=$ypos-abs($width);$yc<=$ypos+abs($width);$yc++ )
		{
			// Draw the text in the outline color
			imagettftext($image,$size,0,$xc,$yc,$color,$font,$text);
		}
	}
}
