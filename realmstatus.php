<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Realmstatus Image generator
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    WoWRoster
 * @subpackage RealmStatus
*/

// WOW Server Status
// Version 3.2
// Copyright 2005 Nick Schaffner
// http://53x11.com

// EDITED BY: http://wowroster.net for use in wowroster
// Most other changes by Zanix

if( !defined('IN_ROSTER') )
{
	define('IN_ROSTER',true);
}

$roster_root_path = dirname(__FILE__) . DIRECTORY_SEPARATOR;

require_once($roster_root_path . 'settings.php');
require_once(ROSTER_LIB . 'simpleparser.class.php');


//==========[ GET FROM CONF.PHP ]====================================================

if( isset($_GET['r']) )
{
	list($region,$realmname) = explode('-',urldecode(trim(stripslashes($_GET['r']))),2);
	$region = strtoupper($region);
}
elseif( isset($realmname) )
{
	list($region,$realmname) = explode('-',trim(stripslashes($realmname)),2);
	$region = strtoupper($region);
}
else
{
	$realmname = '';
}

if( isset($_GET['d']) )
{
	$generate_image = ( $_GET['d'] == '0' ? false : true );
}
elseif( isset($roster->config['rs_mode']) )
{
	$generate_image = ( $roster->config['rs_mode'] ? true : false );
}
else
{
	$generate_image = true;
}

switch( $region )
{
	case 'US':
		$xmlsource = 'http://www.worldofwarcraft.com/realmstatus/status.xml';
		break;
	case 'EU':
		$xmlsource = 'http://www.wow-europe.com/en/serverstatus/index.xml';
		break;
	default:
		$xmlsource = '';
}

//==========[ OTHER SETTINGS ]=========================================================

// Path to image folder
$image_path = ROSTER_BASE . 'img' . DIR_SEP . 'realmstatus' . DIR_SEP;

// Path to font folder
$font_path = ROSTER_BASE . 'fonts' . DIR_SEP;

#--[ MYSQL CONNECT AND STORE ]=========================================================

// Read info from Database
$querystr = "SELECT * FROM `" . $roster->db->table('realmstatus') . "` WHERE `server_region` = '" . $roster->db->escape($region) . "' AND `server_name` = '" . $roster->db->escape($realmname) . "';";
$sql = $roster->db->query($querystr);
if( $sql && $roster->db->num_rows($sql) > 0 )
{
	$realmData = $roster->db->fetch($sql,SQL_ASSOC);
}
else
{
	$realmData['server_name'] = '';
	$realmData['server_region'] = '';
	$realmData['servertype'] = '';
	$realmData['serverstatus'] = '';
	$realmData['serverpop'] = '';
	$realmData['timestamp'] = '0';
}

//==========[ STATUS GENERATION CODE ]=================================================

// Check timestamp, update when ready
$current_time = date('i')*1;

if( $current_time >= ($realmData['timestamp']+$roster->config['rs_timer']) || $current_time < $realmData['timestamp'] )
{
	$xmlsource = urlgrabber($xmlsource);

	$simpleParser = new SimpleParser();
	$simpleParser->parse($xmlsource);

	$err = 1;
	if( $xmlsource != false )
	{
		if( $region == 'US' )
		{
			foreach( $simpleParser->data->r as $value )
			{
				if( str_replace(' ','',$value->n) == str_replace(' ','',$realmname) )
				{
					$err = 0;
					switch( strtoupper($value->s) )
					{
						case '0':
							$realmData['serverstatus'] = 'DOWN';
							break;

						case '1':
							$realmData['serverstatus'] = 'UP';
							break;

						case '2':
							$realmData['serverstatus'] = 'MAINTENANCE';
							break;

						default:
							$realmData['serverstatus'] = 'UNKNOWN';
					}
					switch( strtoupper($value->t) )
					{
						case '0':
							$realmData['servertype'] = 'RPPVP';
							break;

						case '1':
							$realmData['servertype'] = 'PVE';
							break;

						case '2':
							$realmData['servertype'] = 'PVP';
							break;

						case '3':
							$realmData['servertype'] = 'RP';
							break;

						default:
							$realmData['servertype'] = 'UNKNOWN';
					}
					switch( strtoupper($value->l) )
					{
						case '0':
							$realmData['serverpop'] = 'OFFLINE';
							break;

						case '1':
							$realmData['serverpop'] = 'LOW';
							break;

						case '2':
							$realmData['serverpop'] = 'MEDIUM';
							break;

						case '3':
							$realmData['serverpop'] = 'HIGH';
							break;

						case '4':
							$realmData['serverpop'] = 'MAX';
							break;

						default:
							$realmData['serverpop'] = 'ERROR';
					}
				}
			}
		}
		elseif( $region == 'EU' )
		{
			foreach( $simpleParser->data->channel->item as $value )
			{
				if( str_replace(' ','',$value->title->_CDATA) == str_replace(' ','',$realmname) )
				{
					$err = 0;
					switch( strtoupper($value->category[0]->_CDATA) )
					{
						case 'REALM DOWN':
							$realmData['serverstatus'] = 'DOWN';
							break;

						case 'REALM UP':
							$realmData['serverstatus'] = 'UP';
							break;

						default:
							$realmData['serverstatus'] = 'UNKNOWN';
					}
					switch( strtoupper($value->category[2]->_CDATA) )
					{
						case 'RPPVP':
							$realmData['servertype'] = 'RPPVP';
							break;

						case 'PVE':
							$realmData['servertype'] = 'PVE';
							break;

						case 'PVP':
							$realmData['servertype'] = 'PVP';
							break;

						case 'RP':
							$realmData['servertype'] = 'RP';
							break;

						default:
							$realmData['servertype'] = 'UNKNOWN';
					}
					switch( strtoupper($value->category[3]->_CDATA) )
					{
						case 'FULL':
							$realmData['serverpop'] = 'FULL';
							break;

						case 'RECOMMENDED':
							$realmData['serverpop'] = 'RECOMMENDED';
							break;

						case 'FALSE':
							$realmData['serverpop'] = 'RECOMMENDED';
							break;

						default:
							$realmData['serverpop'] = ' ';
					}
				}
			}
		}
	}


	//==========[ WRITE INFO TO DATABASE ]=================================================

	if( !$err ) // Don't write to DB if there has been an error
	{
		$values['servertype'] = $realmData['servertype'];
		$values['serverstatus'] = $realmData['serverstatus'];
		$values['serverpop'] = $realmData['serverpop'];
		$values['timestamp'] = $current_time;

		if( $realmname == $realmData['server_name'] )
		{
			$querystr = "UPDATE `" . $roster->db->table('realmstatus') . "` SET " . $roster->db->build_query('UPDATE',$values) . " WHERE `server_name` = '" . $roster->db->escape($realmname) . "';";
		}
		else
		{
			$values['server_name'] = $realmname;
			$values['server_region'] = $region;
			$querystr = "INSERT INTO `" . $roster->db->table('realmstatus') . "` SET " . $roster->db->build_query('UPDATE',$values) . ";";
			$realmData['server_name'] = $realmname;
		}

		$roster->db->query($querystr);
	}
}


//==========[ "NOW, WHAT TO DO NEXT?" ]================================================

// Error control
if( $realmData['serverstatus'] == 'DOWN' || $realmData['serverstatus'] == 'MAINTENANCE' )
{
	$realmData['serverstatus'] = 'DOWN';
	$realmData['serverpop'] = 'OFFLINE';
	$realmData['serverpopcolor'] = $roster->config['rs_color_error'];
}

// Check to see if data from the DB is non-existant
if( empty($realmData['serverstatus']) || empty($realmData['serverpop']) )
{
	$err = 1;
}
else
{
	$err = 0;
}


// Set to Unknown values upon error
if( $err )
{
	$realmData['serverstatus'] = 'UNKNOWN';
	$realmData['serverpop'] = $roster->locale->act['rs']['NOSTATUS'];
	$realmData['serverpopcolor'] = $roster->config['rs_color_error'];
	$realmData['servertypecolor'] = $roster->config['rs_color_error'];
}
else
{
	if( $realmData['serverpop'] == ' ' )
	{
		$realmData['serverpopcolor'] = $roster->config['rs_color_low'];
	}
	else
	{
		$realmData['serverpopcolor'] = $roster->config['rs_color_' . strtolower($realmData['serverpop'])];
	}
	$realmData['servertypecolor'] = $roster->config['rs_color_' . strtolower($realmData['servertype'])];
	$realmData['serverpop'] = $roster->locale->act['rs'][$realmData['serverpop']];
	$realmData['servertype'] = $roster->locale->act['rs'][$realmData['servertype']];
}


// Generate image or text?
if( $generate_image )
{
	img_output($realmData,$err,$image_path,$font_path);
}
else
{
	echo text_output($realmData);
}

return;

//==========[ TEXT OUTPUT MODE ]=======================================================
function text_output( $realmData )
{
	global $roster;

	$outtext = '
<!-- Begin Realmstatus -->
<div style="width:88px;font-family:arial;font-weight:bold;">
	<div style="width:88px;height:41px;background-image:url(' . ROSTER_URL . $roster->config['img_url'] . 'realmstatus/' . strtolower($realmData['serverstatus']) . '.png);"></div>';

	if ($roster->config['rs_display'] == 'full')
	{
		$outtext .= '
	<div style="vertical-align:middle;text-align:center;width:88px;height:54px;background-image:url(' . ROSTER_URL . $roster->config['img_url'] . 'realmstatus/' . strtolower($realmData['serverstatus']) . '2.png);">
		<div style="padding-top:7px;color:' . $roster->config['rs_color_server'] . ';font-size:10px;">' . $realmData['server_name'] . '</div>
		<div style="color:' . $realmData['serverpopcolor'] . ';font-size:12px;">' . $realmData['serverpop'] . '</div>
		<div style="color:' . $realmData['servertypecolor'] . ';font-size:9px;">' . $realmData['servertype'] . '</div>
	</div>';
	}
	$outtext .= '
</div>
<!-- End Realmstatus -->
';
	return $outtext;
}


//==========[ IMAGE GENERATOR ]========================================================

function img_output( $realmData , $err , $image_path , $font_path )
{
	global $roster;

	$vadj = 0;

	$serverfont = $font_path . $roster->config['rs_font_server'];
	$typefont = $font_path . $roster->config['rs_font_type'];
	$serverpopfont = $font_path . $roster->config['rs_font_pop'];

	// Get and combine base images, set colors
	$top = @imagecreatefrompng( $image_path . strtolower($realmData['serverstatus']) . '.png' );
	if( !$top )
	{
		return;
	}

	if( $roster->config['rs_display'] == 'full' )
	{
		// Get with of the top image
		$topwidth = imagesx($top);

		// Make the bottom part
		$bottom = imagecreatefrompng( $image_path . strtolower(($err ? 'down' : $realmData['serverstatus'])) . '2.png' );

		// Create a new image
		$full = imagecreatetruecolor( $topwidth, (imagesy($top)+imagesy($bottom)) );

		// Turn off apha so we can get a clean boder to set to transparent
		imagealphablending( $full, false );

		// Set the trans color
		$col = getColor('22ff22',$full);
		imagefilledrectangle( $full, 0, 0, $topwidth, (imagesy($top)+imagesy($bottom)), $col );

		imagecopy($full,$top,0,0,0,0,$topwidth,imagesy($top));
		imagecopy($full,$bottom,0,imagesy($top),0,0,imagesx($bottom),imagesy($bottom));
		imagealphablending( $full, true );

		$top = $full;


		// Ouput centered $server name
		$maxw = 62;

		$output = '';
		$box = imagettfbbox($roster->config['rs_size_server'],0,$serverfont,$realmData['server_name']);
		$w = abs($box[0]) + abs($box[2]);

		if( $w > $maxw )
		{
			$i = $w;
			$t = strlen($realmData['server_name']);
			while( $i > $maxw )
			{
				$t--;
				$box = imagettfbbox($roster->config['rs_size_server'], 0,$serverfont,substr($realmData['server_name'],0,$t));
				$i = abs($box[0]) + abs($box[2]);
			}
			$t = strrpos(substr($realmData['server_name'], 0, $t), ' ');
			$output[0] = substr($realmData['server_name'], 0, $t);
			$output[1] = ltrim(substr($realmData['server_name'], $t));
			$vadj = -6;
		}
		else
		$output[0] = $realmData['server_name'];

		$i = 0;
		foreach( $output as $value )
		{
			$box = imagettfbbox($roster->config['rs_size_server'],0,$serverfont,$value);
			$w = abs($box[0]) + abs($box[2]);
			writeText($top,$roster->config['rs_size_server'], round(($topwidth-$w)/2), 54+($i*8)+$vadj,$roster->config['rs_color_server'],$serverfont,$value,$roster->config['rs_color_shadow']);
			$i++;
		}

		unset($output);
		$vadj = 0;

		// Ouput centered $realmData['serverpop']
		if( $realmData['serverpop'] )
		{
			$box = imagettfbbox($roster->config['rs_size_pop'],0,$serverpopfont,$realmData['serverpop']);
			$w = abs($box[0]) + abs($box[2]);

			if( $w > $maxw )
			{
				$i = $w;
				$t = strlen($realmData['serverpop']);
				while( $i > $maxw )
				{
					$t--;
					$box = imagettfbbox($roster->config['rs_size_pop'], 0,$serverpopfont,substr($realmData['serverpop'],0,$t));
					$i = abs($box[0]) + abs($box[2]);
				}
				$t = strrpos(substr($realmData['serverpop'], 0, $t), ' ');
				$output[0] = substr($realmData['serverpop'], 0, $t);
				$output[1] = ltrim(substr($realmData['serverpop'], $t));
				$vadj = -4;
			}
			else
			{
				$output[0] = $realmData['serverpop'];
			}

			$i = 0;
			foreach( $output as $value )
			{
				$box = imagettfbbox($roster->config['rs_size_pop'],0,$serverpopfont,$value);
				$w = abs($box[0]) + abs($box[2]);
				writeText($top,$roster->config['rs_size_pop'], round(($topwidth-$w)/2), 73+($i*10)+$vadj,$realmData['serverpopcolor'],$serverpopfont,$value,$roster->config['rs_color_shadow']);
				$i++;
			}
		}

		// Ouput centered $realmData['servertype']
		if( $realmData['servertype'] && !$err )
		{
			$box = imagettfbbox($roster->config['rs_size_type'],0,$typefont,$realmData['servertype']);
			$w = abs($box[0]) + abs($box[2]);
			writeText($top,$roster->config['rs_size_type'], round(($topwidth-$w)/2), 88,$realmData['servertypecolor'],$typefont,$realmData['servertype'],$roster->config['rs_color_shadow']);
		}
	}

	header('Content-type: image/png');

	imagealphablending($top, false);
	imagesavealpha($top, true);
	imagepng($top);
	imagedestroy($top);
}

// Function to set color of text
function getColor( $color , $image )
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

	return imagecolorallocate($image,$red,$green,$blue);
}

// Write Text
function writeText( $image , $size , $xpos , $ypos , $color , $font , $text , $shadow )
{
	$color = getColor($color,$image);

	// Create a drop shadow
	if( $shadow != '' )
	{
		$shadow = getColor($shadow,$image);
		imagettftext($image,$size,0,$xpos+1,$ypos+1,$shadow,$font,$text);
	}

	// Write the text
	imagettftext($image,$size,0,$xpos,$ypos,$color,$font,$text);
}
