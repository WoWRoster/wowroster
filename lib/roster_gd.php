<?php
/**
 * WoWRoster.net WoWRoster
 *
 * GD library
 *
 * @copyright  2002-2011 WoWRoster.net
 * @author     zanix
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    WoWRoster
 * @subpackage RosterGD
 * @filesource
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}


class RosterGD
{
	var $im;					// GD image
	var $colorindex = array();	// Index of all colors used
	var $width = 100;		// width of the image
	var $height = 100;	// height of the image
	var $rgb = array();

	/**
	 * Image based error handler
	 *
	 * @var GDError
	 */
	var $error;					// Image based error handler

	function RosterGD( )
	{
		// Initialize the image based error handler
		require(ROSTER_LIB . 'roster_gderror.php');
		$this->error = new GDError();
	}

	function make_image( $width=100 , $height=100 )
	{
		$this->width = $width;
		$this->height = $height;

		// Create a transparent image
		$this->imagecreatetruecolortrans();
	}

	/**
	* function to make hex colors to R G B array
	*
	*/
	function hex_rgb($Hex)
	{
		if (substr($Hex,0,1) == "#")
		$Hex = substr($Hex,1);

		$R = substr($Hex,0,2);
		$G = substr($Hex,2,2);
		$B = substr($Hex,4,2);

		$R = hexdec($R);
		$G = hexdec($G);
		$B = hexdec($B);

		$RGB['R'] = $R;
		$RGB['G'] = $G;
		$RGB['B'] = $B;

		$this->rgb = $RGB;
	
	}
	
	function colorize($color,$opc = '35')
	{
		$this->hex_rgb($color);	
		imagefilter($this->im, IMG_FILTER_COLORIZE, $this->rgb['R'], $this->rgb['G'], $this->rgb['B'],$opc);
	}
	//*/
	/**
	 * Function to set color of text
	 * We store the color as a class variable
	 * If the same color is requested, we just return the stored value
	 * Hopefully this will reduce processing time some, every little bit helps
	 *
	 * @param string $color | Color represented in hex
	 * @param int $alpha | Alpha value
	 * @return int | Color Index
	 */
	function set_color( $color , $alpha=0 )
	{
		// See if our color index exists already, if not, do some processing
		if( !isset($this->colorindex[$color . $alpha]) )
		{
			// Initial values for r/g/b, in case our color string is messed up
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
			if( $alpha > 0 )
			{
				$this->colorindex[$color . $alpha] = imagecolorallocatealpha($this->im,$red,$green,$blue,$alpha);
			}
			else // Get a non-transparent color
			{
				$this->colorindex[$color . $alpha] = imagecolorallocate($this->im,$red,$green,$blue);
			}
		}

		// Return stored color index
		return $this->colorindex[$color . $alpha];
	}

	/**
	 * Text Alignment Function
	 *
	 * @param int $size | The font size in pixels
	 * @param int $angle | Angle in degrees in which $text will be measured
	 * @param string $font | The name of the TrueType font file
	 * @param string $text | The string to be measured
	 * @param int $align | Alignment type
	 * 		0 = left : 1 = center : 2 = right
	 * @return string | Calculated text offset
	 */
	function text_align( $size , $angle , $font , $text , $align=0 )
	{
		// Gets the points of the image coordinates
		$txtsize = imagettfbbox($size,$angle,$font,$text);

		switch( $align )
		{
			case 2: // Right align
			case 'right':
				$offset = $txtsize[2];
				break;

			case 1: // Center align
			case 'center':
				$offset = $txtsize[2]/2;
				break;

			default: // Left align (default)
				$offset = 0;
				break;
		}
		return $offset;
	}

	/**
	 * Function to convert strings to a GD True Type compatable format
	 * This function was copied from http://www.php.net/manual/en/function.imagettftext.php
	 * Under post made by limalopex.eisfux.de
	 * Thanks
	 *
	 * @param string $text | String to convert
	 * @return string | Converted string
	 */
	function utf8_to_nce( $text='' )
	{
		if( $text == '' )
		{
			return($text);
		}

		$max_count = 5;		// flag-bits in $max_mark ( 1111 1000 == 5 times 1)
		$max_mark = 248;	// marker for a (theoretical ;-)) 5-byte-char and mask for a 4-byte-char;

		$html = '';
		for( $str_pos = 0; $str_pos < strlen($text); $str_pos++ )
		{
			$old_val = ord($text{$str_pos});
			$new_val = 0;

			$utf8_marker = 0;

			// skip non-utf-8-chars
			if( $old_val > 127 )
			{
				$mark = $max_mark;
				for( $byte_ctr = $max_count; $byte_ctr > 2; $byte_ctr-- )
				{
					// actual byte is utf-8-marker?
					if( ($old_val & $mark ) == ( ($mark << 1) & 255 ) )
					{
						$utf8_marker = $byte_ctr - 1;
						break;
					}
					$mark = ($mark << 1) & 255;
				}
			}

			// marker found: collect following bytes
			if( $utf8_marker > 1 && isset($text{$str_pos + 1}) )
			{
				$str_off = 0;
				$new_val = $old_val & (127 >> $utf8_marker);
				for( $byte_ctr = $utf8_marker; $byte_ctr > 1; $byte_ctr-- )
				{
					// check if following chars are UTF8 additional data blocks
					// UTF8 and ord() > 127
					if( (ord($text{$str_pos + 1}) & 192) == 128 )
					{
						$new_val = $new_val << 6;
						$str_off++;
						// no need for Addition, bitwise OR is sufficient
						// 63: more UTF8-bytes; 0011 1111
						$new_val = $new_val | ( ord( $text{$str_pos + $str_off} ) & 63 );
					}
					// no UTF8, but ord() > 127
					// nevertheless convert first char to NCE
					else
					{
						$new_val = $old_val;
					}
				}
				// build NCE-Code
				$html .= '&#' . $new_val . ';';
				// Skip additional UTF-8-Bytes
				$str_pos += $str_off;
			}
			else
			{
				$html .= chr($old_val);
				$new_val = $old_val;
			}
		}
		return $html;
	}

	/**
	 * Convert an accented character to an English compatible format
	 *
	 * @param string $string | String to convert
	 * @return string | Converted string
	 */
	function convert_accents( $string )
	{
		return utf8_encode(strtr(utf8_decode($string),
			"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
			'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn'
		));
	}

	/**
	 * Write Text to GD
	 *
	 * @param float $size | The font size in pixels
	 * @param float $angle | The angle in degrees, with 0 degrees being left-to-right reading text
	 * @param int $xpos | The coordinates given by x and y will define the basepoint of the first character
	 * @param int $ypos | The y-ordinate. This sets the position of the fonts baseline, not the very bottom of the character
	 * @param string $color | The color
	 * @param int $alpha | Transparentcy level
	 * @param string $font | The path to the TrueType font you wish to use
	 * @param string $text | The text string
	 * @param int $align | Alignment type
	 * 		0 = left : 1 = center : 2 = right
	 * @param array $outline | Outline data
	 * @param array $shadow | Drop Shadow data
	 * @return array | Bounding box coordinates of written text. Extra points are returned for text baselines
	 */
	function write_text( $size , $angle , $xpos , $ypos , $color , $alpha , $font , $text , $align , $outline=array() , $shadow=array() )
	{
		// Get the font
		$font = $this->get_font($font);

		// Get the color
		$color = $this->set_color($color,$alpha);

		// Convert utf8 text for display
		$text = $this->utf8_to_nce($text);

		// Calculate alignment offest
		$xpos -= $this->text_align($size,$angle,$font,$text,$align);

		// Create drop shadow
		if( isset($shadow['color']) && $shadow['color'] != '' )
		{
			$this->shadow_text($size,$angle,$xpos,$ypos,$shadow['color'],$font,$text,$shadow['distance'],$shadow['direction'],$shadow['spread']);
		}

		// Create outline / border
		if( isset($outline['color']) && $outline['color'] != '' )
		{
			$this->outline_text($size,$angle,$xpos,$ypos,$outline['color'],$font,$text,$outline['width'],$outline['color']);
		}

		// Write the text
		$coords = imagettftext($this->im,$size,$angle,$xpos,$ypos,$color,$font,$text);

		// The true y axis baseline
		$coords[8] = imagettfbbox($size,$angle,$font,$text);
		$coords[8] = ($coords[3]-$coords[8][1]);

		return $coords;
	}

	/**
	 * Creates a drop shadow
	 *
	 * @param float $size | The font size in pixels
	 * @param float $angle | The angle in degrees, with 0 degrees being left-to-right reading text
	 * @param int $xpos | The coordinates given by x and y will define the basepoint of the first character
	 * @param int $ypos | The y-ordinate. This sets the position of the fonts baseline, not the very bottom of the character
	 * @param string $color | The color
	 * @param string $font | The path to the TrueType font you wish to use
	 * @param string $text | The text string
	 * @param int $distance | How far the shadow should drop
	 * @param int $degrees | Direction of the shadow
	 * @param int $spread | The spread of the shadow
	 */
	function shadow_text( $size , $angle , $xpos , $ypos , $color , $font , $text , $distance , $degrees , $spread=0 )
	{
		$rad = deg2rad($degrees);
		$xoff = $distance*cos($rad);
		$yoff = $distance*sin($rad);

		$xpos += $xoff;
		$ypos += $yoff;

		if( $spread != 0 )
		{
			$this->outline_text($size,$angle,$xpos,$ypos,$color,$font,$text,$spread);
		}
		$color = $this->set_color($color);
		imagettftext($this->im,$size,$angle,$xpos,$ypos,$color,$font,$text);
	}

	/**
	 * Create an outline/border around text
	 *
	 * @param float $size | The font size in pixels
	 * @param float $angle | The angle in degrees, with 0 degrees being left-to-right reading text
	 * @param int $xpos | The coordinates given by x and y will define the basepoint of the first character
	 * @param int $ypos | The y-ordinate. This sets the position of the font baseline, not the very bottom of the character
	 * @param string $color | The color
	 * @param string $font | The path to the TrueType font you wish to use
	 * @param string $text | The text string
	 * @param int $width | Set how thick the outline will be
	 * @param int $alpha | Transparency level
	 */
	function outline_text( $size , $angle , $xpos , $ypos , $color , $font , $text , $width , $alpha=0 )
	{
		$color = $this->set_color($color, $alpha);

		// For every X pixel to the left and the right
		for( $xc=$xpos-abs($width); $xc<=$xpos+abs($width); $xc++ )
		{
			// For every Y pixel to the top and the bottom
			for( $yc=$ypos-abs($width); $yc<=$ypos+abs($width); $yc++ )
			{
				// Draw the text in the outline color
				imagettftext($this->im,$size,$angle,$xc,$yc,$color,$font,$text);
			}
		}
	}

	/**
	 * Get the full font path
	 *
	 * @param string $font | Font filename
	 * @return string | Full path to the font file
	 */
	function get_font( $font='' )
	{
		// Set our secondary font backup
		// If it works on Star Trek, it can work here!
		$_2ndary_backup = ROSTER_BASE . 'fonts'. DIR_SEP . 'VERANDA.TTF';

		// We should never have a blank font, but oh well
		if( $font != '' )
		{
			// We look in Roster's font dir
			$default_font = ROSTER_BASE . 'fonts'. DIR_SEP . $font;

			// Check to see if SigGen can see the font
			if( file_exists($default_font) )
			{
				return $default_font;
			}
			else
			{
				return $_2ndary_backup;
			}
		}
		else
		{
			return $_2ndary_backup;
		}
	}

	/**
	 * Funtion to merge images with the main image
	 *
	 * @param string $filename | Full path to image
	 * @param int $dst_x | x-coordinate of destination point
	 * @param int $dst_y | y-coordinate of destination point
	 * @param int $src_x | x-coordinate of source point
	 * @param int $src_y | y-coordinate of source point
	 * @param mixed $width | width to resize image to
	 *  Leave blank to preserve aspect ratio
	 *  (not implemented) Append a % for percent resize
	 * @param int $height | height to resize image to
	 *  Leave blank to preserve aspect ratio
	 *  (not implemented) Append a % for percent resize
	 */
	function combine_image( $filename , $dst_x , $dst_y , $src_x=0 , $src_y=0 , $width=0 , $height=0 )
	{
		$info = getimagesize($filename);

		switch( $info['mime'] )
		{
			case 'image/jpeg':
			case 'image/jpg':
				$im_temp = imagecreatefromjpeg($filename);
				break;

			case 'image/png':
				$im_temp = imagecreatefrompng($filename);
				break;

			case 'image/gif':
				$im_temp = imagecreatefromgif($filename);
				break;

			default:
				trigger_error('Unhandled image type [' . $info['mime'] . ']',E_USER_ERROR);
		}
		// Get the image dimentions
		$im_temp_width = imagesx($im_temp);
		$im_temp_height = imagesy($im_temp);

		if( $width != 0 && $height != 0 )
		{
			$new_width = $width;
			$new_height = $height;
		}
		elseif( $width != 0 )
		{
			$new_width = $width;
			$new_height = ((int)($new_width * $im_temp_height) / $im_temp_width);
		}
		elseif( $height != 0 )
		{
			$new_height = $height;
			$new_width = ((int)($new_height * $im_temp_width) / $im_temp_height);
		}
		else
		{
			// No resizing, use fastest copy method
			imagecopy($this->im,$im_temp,$dst_x,$dst_y,$src_x,$src_y,$im_temp_width,$im_temp_height);

			// Destroy the temp image
			imagedestroy($im_temp);

			return;
		}

		imagecopyresampled($this->im,$im_temp,$dst_x,$dst_y,$src_x,$src_y,$new_width,$new_height,$im_temp_width,$im_temp_height);

		// Destroy the temp image
		imagedestroy($im_temp);
	}

	/**
	 * imagecreateformsting equivalent to a blank png image
	 *
	 * @return string
	 */
	function blankpng( )
	{
		return base64_decode("iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29m".
			"dHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADqSURBVHjaYvz//z/DYAYAAcTEMMgBQAANegcCBNCg".
			"dyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAAN".
			"egcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQ".
			"oHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAA".
			"DXoHAgTQoHcgQAANegcCBNCgdyBAgAEAMpcDTTQWJVEAAAAASUVORK5CYII=");
	}

	/**
	 * Combined with SigGen::blankpng, this creates a blank transparent image
	 *
	 * @param bool $return | Return the image resource?
	 * @return unknown
	 */
	function imagecreatetruecolortrans( $return=false )
	{
		$im = imagecreatetruecolor($this->width,$this->height);

		$b = imagecreatefromstring($this->blankpng());

		imagealphablending($im,false);
		imagesavealpha($im,true);
		imagecopyresized($im,$b,0,0,0,0,$this->width,$this->height,imagesx($b),imagesy($b));
		imagealphablending($im,true);

		if( $return )
		{
			return $im;
		}
		else
		{
			$this->im = $im;
		}
	}

	/**
	 * Browser cache detection, setting, etc...
	 *
	 * @param int $time | Unix timestamp
	 * @param mixed $etag | Identifier for the etag
	 */
	function etag( $time , $etag )
	{
		// Get browser last modified since
		$condDTS = ( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : 0 );

		if( isset($_SERVER['HTTP_IF_NONE_MATCH']) && ereg(md5($etag) , $_SERVER['HTTP_IF_NONE_MATCH']) )
		{
			header('HTTP/1.1 304 Not Modified');
			exit;
		}
		elseif( $condDTS && ($_SERVER['REQUEST_METHOD'] == 'GET') && (strtotime($condDTS) >= $time) )
		{
			header('HTTP/1.1 304 Not Modified');
			exit;
		}
		else
		{
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', $time));
			header('ETag: "{ ' . md5($etag) . ' }"');
		}
    }

	/**
	 * Saves the image with the specified name to the save folder
	 *
	 * @param string $save_file
	 * @param string $format
	 * @param string $jpg_quality
	 */
	function store_image( $save_file, $format='jpg', $jpg_quality=85 )
	{
		if( file_exists(dirname($save_file)) )
		{
			if( is_writable(dirname($save_file)) )
			{
				switch( $format )
				{
					case 'gif':
						$this->makeimagegif($save_file);
						break;

					case 'jpg':
						imagejpeg($this->im, $save_file, $jpg_quality);
						break;

					case 'png':
						imagepng($this->im, $save_file);
						break;
				}
			}
			else
			{
				trigger_error('Cannot save image to the server. [' . dirname($save_file) . '] was not writable',E_USER_ERROR);
			}
		}
		else
		{
			trigger_error('Cannot save image to the server. [' . dirname($save_file) . '] was not found',E_USER_ERROR);
		}
	}

	/**
	 * Output generated image
	 * Dies and exits upon completion
	 *
	 * @param string $format
	 * @param string $jpg_quality
	 */
	function get_image( $format='jpg', $jpg_quality=85 )
	{
		header('Content-type: image/' . $format);

		switch( $format )
		{
			case 'gif':
				$this->makeimagegif();
				break;

			case 'jpg':
				imagejpeg($this->im, '', $jpg_quality);
				break;

			case 'png':
				imagepng($this->im);
				break;
		}
	}

	function finish()
	{
		$this->error->stop();
		imagedestroy($this->im);
		exit;
	}


	/**
	 * GD Rectangle Function
	 *
	 * @param int x
	 * @param int y
	 * @param int x2
	 * @param int y2
	 * @param bool filled
	 * @param int radius
	 * @param string color
	 * @param int alpha
	 *
	 * @return bool
	 */
	function gd_rectangle( $x, $y, $x2, $y2, $filled=1, $radius=0, $color='000000', $alpha=0 )
	{
		// Get our color index
		$color = $this->set_color($color, $alpha);

		// Make sure radius is a positive number
		$radius = abs($radius);

		// Only do this "massive" drawing if we have rounded corners
		if( $radius > 0 )
		{
			if( $filled == 1 )
			{
				imagefilledrectangle($this->im, $x+$radius, $y, $x2-$radius, $y2, $color);
				imagefilledrectangle($this->im, $x, $y+$radius, $x+$radius-1, $y2-$radius, $color);
				imagefilledrectangle($this->im, $x2-$radius+1, $y+$radius, $x2, $y2-$radius, $color);

				imagefilledarc($this->im,$x+$radius, $y+$radius, $radius*2, $radius*2, 180, 270, $color, IMG_ARC_PIE);
				imagefilledarc($this->im,$x2-$radius, $y+$radius, $radius*2, $radius*2, 270, 360, $color, IMG_ARC_PIE);
				imagefilledarc($this->im,$x+$radius, $y2-$radius, $radius*2, $radius*2, 90, 180, $color, IMG_ARC_PIE);
				imagefilledarc($this->im,$x2-$radius, $y2-$radius, $radius*2, $radius*2, 360, 90, $color, IMG_ARC_PIE);

			}
			else
			{
				imageline($this->im, $x+$radius, $y, $x2-$radius, $y, $color);
				imageline($this->im, $x+$radius, $y2, $x2-$radius, $y2, $color);
				imageline($this->im, $x, $y+$radius, $x, $y2-$radius, $color);
				imageline($this->im, $x2, $y+$radius, $x2, $y2-$radius, $color);

				imagearc($this->im,$x+$radius, $y+$radius, $radius*2, $radius*2, 180, 270, $color);
				imagearc($this->im,$x2-$radius, $y+$radius, $radius*2, $radius*2, 270, 360, $color);
				imagearc($this->im,$x+$radius, $y2-$radius, $radius*2, $radius*2, 90, 180, $color);
				imagearc($this->im,$x2-$radius, $y2-$radius, $radius*2, $radius*2, 360, 90, $color);
			}
		}
		else
		{
			if( $filled == 1 )
			{
				imagefilledrectangle($this->im, $x, $y, $x2, $y2, $color);
			}
			else
			{
				imagerectangle($this->im, $x, $y, $x2, $y2, $color);
			}
		}

		return true;
	}
}
