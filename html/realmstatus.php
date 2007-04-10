<?php

// WOW Server Status
// Version 3.2
// Copyright 2005 Nick Schaffner
// http://53x11.com

// EDITED BY: http://wowprofilers.com for use in the wowprofilers_roster
// XML parsing by Swipe
include 'conf.php';
require_once 'lib/xmlparse.php';

//==========[ GET FROM CONF.PHP ]====================================================

	$server = $server_name;
	$roster_language = $roster_lang;
	if($rs_image) $generate_image = true;
	elseif(!$rs_image) $generate_image = false;
	else $generate_image = true;


//==========[ OTHER SETTINGS ]=========================================================

	// Minutes between status update refresh 0-60
		$timer	= 10;

	// Path to wowss folder
		$folder_path = './wowss/';

	// URL for status page
		$url = $blizstatuspage;

	// Text output configuration
	function text_output($server,$servertype,$serverstatus,$serverpop,$folder_path) {

		// Error control
		if ($serverstatus == 'Down' || $serverstatus == 'Maitenence') {
			$serverstatus = 'down';
			$serverpop = 'offline';
		}

		$outtext = '<table width="88" border="0" cellpadding="0" cellspacing="0">
  <tr><td>
    <img src="'.$folder_path.strtolower($serverstatus).'.png" />
  </td></tr>
  <tr background="'.$folder_path.strtolower($serverstatus).'2.png"><td valign="center" align="center" height="54" width="88">
    <span style="color:black; font-size: 10px; font-weight: bold;">'.$server.'</span><br>
    <img src="'.$folder_path.strtolower($serverpop).'.png" /><br>
    <span style="color:#333333; font-size: 9px; font-weight: bold;">'.$servertype.'</span><br>
  </td></tr>
</table>';
		return $outtext;
	}


//==========[ STATUS GENERATION CODE ]=================================================


if(substr($url,-4) == '.xml')
	$xml = true;
else
	$html = file_get_contents($url);


//////// PHP Magic Below, Don't Edit
$magic_array = array(min,server,servertype,serverstatus,serverpop,err);


// Check timestamp, update when ready
if (substr($folder_path, -1, 1) != '/') $folder_path = "$folder_path/";

$timestamp = $folder_path.'wowss_data.txt';
$min = date(i)*1;
$info = explode(',', file_get_contents($timestamp));

if (filesize($timestamp) == 0) $pass = true;

if ($min >= ($info[0]+$timer) or $min < $info[0] or $realm or $pass) {
	// Get and format HTML data
	error_reporting(0);

//==========[ HTML PARSER ]============================================================

	if ($html) {
		$server = str_replace('\\', '',str_replace("'", '&#039;', $server));
		$length = strlen($server);
		$pos = strpos($html, $server);

		if (!$pos) {
			$error = $error.', check Realm name';
			$err = true;
		}

		// Figure out Serverstatus
		$serverstatus = stristr(substr(substr($html, -(strlen($html) - $pos + 164)), 0 ,20), 'up');
		if (!$serverstatus) {
			$serverstatus = 'down';
			$serverpop = 'low';
		} else { $serverstatus = 'up'; }

		// Figure out Servertype
		$servertype = strtolower(substr(substr($html, -(strlen($html) - ($pos + 100))), 0, 150));
		if ($roster_language == 'french') { $types = array('jcj','normal','jdr');
		} else { $types = array('pvp','normal','rp'); }

		foreach ($types as $i) {
			if (strpos($servertype,$i)) {
				$servertype = translate($i,$roster_language);
				break;
			}
		}

		if ($servertype == 'rp') {
			$length++;
			$servertype = 'Roleplay';
		} elseif ($servertype == 'normal')
			$servertype = 'PVE';
		elseif ($servertype == 'pvp')
			$servertype= 'PVP'; 
		else
			$err = true;
		
		// Figure out Server Pop.
		if (!$serverpop) {
			$serverpop = strtolower(substr(substr($html, -(strlen($html) - ($pos + 240))),0,150));
			if ($roster_language == 'deDE') $levels = array('warteschlange','hoch','mittel','niedrig');
			if ($roster_language == 'french') $levels = array("file d'attente",'lev','moyenne','faible');
			if ($roster_language == 'enUS') $levels = array('max','high','medium','low');
			foreach ($levels as $i) {
				if (strpos($serverpop,$i)) {
					$serverpop = translate($i,$roster_language);
					break;
				}
			}
		}
	} elseif ($xml) {

//==========[ XML PARSER ]=============================================================

		$xml_parse =& new xmlParser();
		$xml_parse->parse($url);
		if ( count( $xml_parse->output ) ) {
			foreach ( $xml_parse->output[0]['child'] as $xml_array ) {
				foreach ( $xml_array as $xml_server ) {
					if ( $xml_server['N'] == $server ) {
						switch ( $xml_server['S'] ) {
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
						switch ( $xml_server['T'] ) {
							case 1:
								$servertype = 'Normal';
								break;
							case 2:
								$servertype = '(PvP)';
								break;
							case 3:
								$servertype = '(RP)';
								break;
							case 4:
								$servertype = '(RP-PvP)';
								break;
							default:
								$servertype = 'Unknown';
						}
						switch ( $xml_server['L'] ) {
							case 1:
								$serverpop = 'Low';
								break;
							case 2:
								$serverpop = 'Medium';
								break;
							case 3:
								$serverpop = 'High';
								break;
							case 4:
								$serverpop = 'Max';
								break;
							default:
								$serverpop = 'Error';
						}
					}
				}
			}
		}
	} else $err = true;


//==========[ WRITE INFO TO FILE ]=====================================================

	if (!$realm) {
		foreach($magic_array as $value) {
			$data = $data.$$value.',';
		}
		$handle = fopen($timestamp,'w');
		flock($handle, LOCK_EX);
		fwrite($handle, $data);
		flock($handle, LOCK_UN);
		fclose($handle);
	}
} else {		// If timer isn't up yet, fetch old data and output
	$i=0;
	foreach ($info as $value) {
		$$magic_array[$i] = $value;
		$i++;
	}
}


//==========[ "NOW, WHAT TO DO NEXT?" ]================================================

	if ($generate_image) {
		img_output($server,$servertype,$serverstatus,$serverpop,$err,$folder_path,$display);
	} else {
		if (!$err) {
			echo text_output($server,$servertype,$serverstatus,$serverpop,$folder_path);
		} else {
			echo $error;
		}
	}


//==========[ IMAGE GENERATOR ]========================================================

function img_output ($server,$servertype,$serverstatus,$serverpop,$err,$folder_path,$display) {
	$serverfont = $folder_path . 'silkscreen.ttf';
	$typefont = $folder_path . 'silkscreenb.ttf';
	header("Content-type: image/png");

	// Error control
		if ($serverstatus == 'Down' || $serverstatus == 'Maitenence') {
			$serverstatus = 'Down';
			$serverpop = 'Offline';
		}
		if ($err) {
			$serverstatus = 'Unknown';
			$serverpop = 'Error';
		}

	// Get and combine base images, set colors
	$back = imagecreatefrompng($folder_path . strtolower($serverstatus) . '.png');

	if ($display == 'full') {
		$backwidth = imagesx($back);
		$bottom = imagecreatefrompng($folder_path . strtolower($serverstatus) . '2.png');
		$serverpop = imagecreatefrompng($folder_path . strtolower($serverpop) . '.png');
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

		if ($w > $maxw) {
			$i = $w;
			$t = strlen($server);
			while ($i > $maxw) {
				$t--;
				$box = imagettfbbox (6, 0,$serverfont,substr($server,0,$t));
		  	$i = abs($box[0]) + abs($box[2]);
			}
			$t = strrpos(substr($server, 0, $t), ' ');
			$output[0] = substr($server, 0, $t);
			$output[1] = ltrim(substr($server, $t));
			$vadj = -6;
		} else { $output[0] = $server; }
		$i = 0;

		foreach($output as $value) {
			$box = imagettfbbox(6,0,$serverfont,$value);
			$w = abs($box[0]) + abs($box[2]);
			imagettftext($back, 6, 0, round(($backwidth-$w)/2)+1, 58+($i*8)+$vadj, $shadow, $serverfont, $value);
			imagettftext($back, 6, 0, round(($backwidth-$w)/2), 57+($i*8)+$vadj, -$textcolor, $serverfont, $value);
			$i++;
		}

		// Ouput centered $servertype
		if ($servertype and !$err) {
			$box = imagettfbbox(6,0,$typefont,$servertype);
			$w = abs($box[0]) + abs($box[2]);
			imagettftext($back, 6, 0, round(($backwidth-$w)/2)+1, 85, $shadow, $typefont, $servertype);
			imagettftext($back, 6, 0, round(($backwidth-$w)/2), 84, -$textcolor, $typefont, $servertype);
		}
	}
	imagepng($back);
	imagedestroy($back);
}

function translate($word, $roster_language) {
	if ($roster_language == 'deDE') {
		if ($word == 'warteschlange') $word = 'max';
		if ($word == 'hoch') $word = 'high';
		if ($word == 'mittel') $word = 'medium';
		if ($word == 'niedrig') $word = 'low';
	}
	if ($roster_language == 'french') {
		if ($word == "file d'attente") $word = 'max';
		if ($word == 'lev') $word = 'high';
		if ($word == 'moyenne') $word = 'medium';
		if ($word == 'faible') $word = 'low';
		if ($word == 'jcj') $word = 'pvp';
		if ($word == 'jdr') $word = 'rp';
	}
	return $word;
}
?>
