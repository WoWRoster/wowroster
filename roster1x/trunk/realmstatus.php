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

// WOW Server Status
// Version 3.2
// Copyright 2005 Nick Schaffner
// http://53x11.com

// EDITED BY: http://wowroster.net for use in wowroster
// XML parsing by Swipe
// Most other changes by Zanix

require_once( 'settings.php' );
require_once( ROSTER_LIB.'xmlparse.php' );


//==========[ GET FROM CONF.PHP ]====================================================

if( $roster_conf['realmstatus'] == '' )
{
	$realmstatus = utf8_decode($roster_conf['server_name']);
}
else
{
	$realmstatus = utf8_decode($roster_conf['realmstatus']);
}

$server = trim($realmstatus);

if($roster_conf['rs_mode'])
{
	$generate_image = true;
}
elseif(!$roster_conf['rs_mode'])
{
	$generate_image = false;
}
else
{
	$generate_image = true;
}


//==========[ OTHER SETTINGS ]=========================================================

// Minutes between status update refresh 0-60
	$timer = 10;

// Path to image folder
	$image_path = ROSTER_BASE.'img'.DIR_SEP.'realmstatus'.DIR_SEP;

// Path to font folder
	$font_path = ROSTER_BASE.'fonts'.DIR_SEP;

// URL for status page
	$url = $roster_conf['realmstatus_url'];

#--[ MYSQL CONNECT AND STORE ]=========================================================

// Read info from Database
$querystr = "SELECT * FROM `".ROSTER_REALMSTATUSTABLE."` WHERE `server_name` = '".$wowdb->escape($server)."'";
$sql = $wowdb->query($querystr);
if($sql)
{
	$realmData = $wowdb->fetch_array($sql);
}
else
{
	die("<!-- $querystr -->\nCould not query: ".$wowdb->error());
}


//==========[ STATUS GENERATION CODE ]=================================================

// Check timestamp, update when ready
	$timestamp = date('i')*1;


if( $timestamp >= ($realmData['timestamp']+$timer) || $timestamp < $realmData['timestamp'] )
{
	// Get and format HTML data

	// Check for xml/html
	if(substr($url,-4) == '.xml')
	{
		$xml = 1;
	}
	else
	{
		if (function_exists('curl_init'))
		{
			$fp = curl_init( $url );
			curl_setopt($fp, CURLOPT_HEADER, 0);
			curl_setopt($fp, CURLOPT_RETURNTRANSFER, 1);
			$html = curl_exec($fp);
			if ( curl_errno($fp) )
			{
				$ch_err = 1;
			}
			else
			{
				$ch_err = 0;
			}
			curl_close($fp);
		}
		else
		{
			$html = @file_get_contents($url);
		}
	}

//==========[ HTML PARSER ]============================================================

	if (isset($html) && $html)
	{
		if (!preg_match('/\<tr(.(?!\<tr))*('.str_replace("'",'&#039;',$server).')(.(?!\<\/tr))*/si',$html,$matches))
		{
			$err = 1;
		}
		elseif (preg_match_all('/^.*color: #([0-9a-fA-F]+);\">([^<]*)/m',$matches[0],$row) != 3)
		{
			$err = 1;
		}
		
		print_r($row);

	// Figure out Serverstatus
		$serverstatus = strpos($matches[0], 'uparrow');
		if (!$serverstatus)
		{
			$serverstatus = 'Down';
			$serverpop = 'Offline';
		}
		else
		{
			$serverstatus = 'Up';
		}

	// Figure out Servertype
		$servertype = $row[2][1];
		$servertypecolor = $row[1][1];
	// Figure out Server Pop.
		$serverpop = $row[2][2];
		$serverpopcolor = $row[2][1];
	}
	elseif ($xml)
	{

//==========[ XML PARSER ]=============================================================

		$xml_parse =& new xmlParser();
		$err = 1;
		if( $xml_parse->parse($url) )
		{
			if ( count( $xml_parse->output ) )
			{
				if( is_array($xml_parse->output[0]['child']) )
				{
					foreach ( $xml_parse->output[0]['child'] as $xml_array )
					{
						foreach ( $xml_array as $xml_server )
						{
							if ( $xml_server['N'] == $server )
							{
								$err = 0;
								switch ( $xml_server['S'] )
								{
									case 0:
										$serverstatus = 'Down';
										break;
									case 1:
										$serverstatus = 'Up';
										break;
									case 2:
										$serverstatus = 'Maitenence';
										break;
									default:
										$serverstatus = 'Unknown';
								}
								switch ( $xml_server['T'] )
								{
									case 0:
										$servertype = '(RP-PvP)';
										$servertypecolor = '535600';
										break;
									case 1:
										$servertype = 'Normal';
										$servertypecolor = '234303';
										break;
									case 2:
										$servertype = '(PvP)';
										$servertypecolor = '660D02';
										break;
									case 3:
										$servertype = '(RP)';
										$servertypecolor = '535600';
										break;
									default:
										$servertype = 'Unknown';
										$servertypecolor = '860D02';
								}
								switch ( $xml_server['L'] )
								{
									case 1:
										$serverpop = 'Low';
										$serverpopcolor = '234303';
										break;
									case 2:
										$serverpop = 'Medium';
										$serverpopcolor = '535600';
										break;
									case 3:
										$serverpop = 'High';
										$serverpopcolor = '660D02';
										break;
									case 4:
										$serverpop = 'Max';
										$serverpopcolor = '860D02';
										break;
									default:
										$serverpop = 'Error';
										$serverpopcolor = '860D02';
								}
							}
						}
					}
				}
			}
		}
	}
	else
	{
		$err = 1;
	}


//==========[ WRITE INFO TO DATABASE ]=================================================

	if( !$err ) // Don't write to DB if there has been an error
	{
		$wowdb->reset_values();
		if($server != $realmData['server_name'] )
		{
			$wowdb->add_value('server_name', $server);
		}
		$wowdb->add_value('servertype', $servertype);
		$wowdb->add_value('serverstatus', $serverstatus);
		$wowdb->add_value('serverpop', $serverpop);
		$wowdb->add_value('timestamp', $timestamp);

		if ($server == $realmData['server_name'])
		{
			$querystr = "UPDATE `".ROSTER_REALMSTATUSTABLE."` SET ".$wowdb->assignstr." WHERE `server_name` = '".$wowdb->escape($server)."'";
		}
		else
		{
			$querystr = "TRUNCATE `".ROSTER_REALMSTATUSTABLE."`";
			$wowdb->query($querystr) or die($wowdb->error());
			$querystr = "INSERT INTO `".ROSTER_REALMSTATUSTABLE."` SET ".$wowdb->assignstr;
		}
		// Give only debug infos with text-status enabled
		// Otherwise the debug-statement will destroy the png-generation
		if ($roster_conf['sqldebug'] AND !$generate_image)
		{
			print "<!-- $querystr -->\n";
		}

		$wowdb->query($querystr) or die($wowdb->error());

		$realmData['server_name'] = $server;
		$realmData['servertype'] = $servertype;
		$realmData['serverstatus'] = $serverstatus;
		$realmData['serverpop'] = $serverpop;
		$realmData['timestamp'] = $timestamp;
	}
}


//==========[ "NOW, WHAT TO DO NEXT?" ]================================================

// Error control
if( $realmData['serverstatus'] == 'Down' || $realmData['serverstatus'] == 'Maitenence' )
{
	$realmData['serverstatus'] = 'Down';
	$realmData['serverpop'] = 'Offline';
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
	$realmData['serverstatus'] = 'Unknown';
	$realmData['serverpop'] = 'Error';
}

// Generate image or text?
if( $generate_image )
{
	img_output($realmData['server_name'],$realmData['servertype'],$realmData['serverstatus'],$realmData['serverpop'],$err,$image_path,$roster_conf['rs_display'],$font_path);
}
else
{
	echo text_output($realmData['server_name'],$realmData['servertype'],$realmData['serverstatus'],$realmData['serverpop'],$err,$roster_conf['rs_display']);
}


//==========[ TEXT OUTPUT MODE ]=======================================================
function text_output($server,$servertype,$serverstatus,$serverpop,$err,$display)
{
	global $roster_conf;

	$outtext = '
<!-- Begin Realmstatus -->
<div style="width:88px;">
  <div style="width:88px;height:41px;background-image:url('.$roster_conf['img_url'].'realmstatus/'.strtolower($serverstatus).'.png);"></div>';

	if ($display == 'full')
	{
		$outtext .= '
  <div style="vertical-align: middle;text-align:center;width:88px;height:54px;background-image:url('.$roster_conf['img_url'].'realmstatus/'.strtolower($serverstatus).'2.png);">
    <div style="padding-top:7px;color:black;font:10px arial;font-weight:bold;">'.$server.'</div>
    <img style="padding-top:2px;" src="'.$roster_conf['img_url'].'realmstatus/'.strtolower($serverpop).'.png" alt="'.$serverpop.'" />
    <div style="color:#333333;font:9px arial;font-weight:bold;">'.$servertype.'</div>
  </div>';
	}
	$outtext .= '
</div>
<!-- End Realmstatus -->
';
	return $outtext;
}


//==========[ IMAGE GENERATOR ]========================================================

function img_output ($server,$servertype,$serverstatus,$serverpop,$err,$image_path,$display,$font_path)
{
	$serverfont = $font_path . 'silkscreen.ttf';
	$typefont = $font_path . 'silkscreenb.ttf';

	// Get and combine base images, set colors
	$back = @imagecreatefrompng($image_path . strtolower($serverstatus) . '.png');
	if( !$back )
		return('Realmstatus Image Creation Failed');

	if ($display == 'full')
	{
		$backwidth = imagesx($back);
		$bottom = imagecreatefrompng($image_path . strtolower($serverstatus) . '2.png');
		$serverpop = imagecreatefrompng($image_path . strtolower($serverpop) . '.png');
		$full = imagecreate($backwidth,(imagesy($back)+imagesy($bottom)));
		$bg = imagecolorallocate($full, 0, 255, 255);
		$red = imagecolorallocate($full,204,0,0); // HIGH Red color

		imagecolortransparent($full, $bg);
		imagecopy($full,$back,0,0,0,0,$backwidth,imagesy($back));
		imagecopy($full,$bottom,0,imagesy($back),0,0,imagesx($bottom),imagesy($bottom));
		$back = $full;
		$textcolor = imagecolorallocate($back, 51, 51, 51);
		$shadow = imagecolorclosest($back, 255, 204, 0);
		imagecopy($back,$serverpop,round(($backwidth-imagesx($serverpop))/2),62,0,0,imagesx($serverpop),imagesy($serverpop));

		// Ouput centered $server name
		$maxw = 62;
		$box = imagettfbbox(6,0,$serverfont,$server);
		$w = abs($box[0]) + abs($box[2]);

		if ($w > $maxw)
		{
			$i = $w;
			$t = strlen($server);
			while ($i > $maxw)
			{
				$t--;
				$box = imagettfbbox (6, 0,$serverfont,substr($server,0,$t));
		  	$i = abs($box[0]) + abs($box[2]);
			}
			$t = strrpos(substr($server, 0, $t), ' ');
			$output[0] = substr($server, 0, $t);
			$output[1] = ltrim(substr($server, $t));
			$vadj = -6;
		}
		else
			$output[0] = $server;

		$i = 0;

		foreach($output as $value)
		{
			$box = imagettfbbox(6,0,$serverfont,$value);
			$w = abs($box[0]) + abs($box[2]);
			imagettftext($back, 6, 0, round(($backwidth-$w)/2)+1, 58+($i*8)+$vadj, $shadow, $serverfont, $value);
			imagettftext($back, 6, 0, round(($backwidth-$w)/2), 57+($i*8)+$vadj, -$textcolor, $serverfont, $value);
			$i++;
		}

		// Ouput centered $servertype
		if ($servertype and !$err)
		{
			$box = imagettfbbox(6,0,$typefont,$servertype);
			$w = abs($box[0]) + abs($box[2]);
			imagettftext($back, 6, 0, round(($backwidth-$w)/2)+1, 85, $shadow, $typefont, $servertype);
			imagettftext($back, 6, 0, round(($backwidth-$w)/2), 84, -$textcolor, $typefont, $servertype);
		}
	}

	header("Content-type: image/png");

	imagepng($back);
	imagedestroy($back);
	exit();
}
?>