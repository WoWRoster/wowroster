<?php
$versions['versionDate']['realmstatus'] = '$Date: 2006/02/05 06:01:36 $'; 
$versions['versionRev']['realmstatus'] = '$Revision: 1.19 $'; 
$versions['versionAuthor']['realmstatus'] = '$Author: zanix $';

// WOW Server Status
// Version 3.2
// Copyright 2005 Nick Schaffner
// http://53x11.com

// EDITED BY: http://wowprofilers.com for use in the wowprofilers_roster
// XML parsing by Swipe
// Most other changes by Zanix
require_once 'conf.php';
require_once 'lib/xmlparse.php';
require_once 'lib/wowdb.php';


//==========[ GET FROM CONF.PHP ]====================================================

$server = trim($realmstatus);

if($rs_image) $generate_image = true;
elseif(!$rs_image) $generate_image = false;
else $generate_image = true;


//==========[ OTHER SETTINGS ]=========================================================

// Minutes between status update refresh 0-60
	$timer = 10;

// Path to image folder
	$image_path = './img/realmstatus/';

// Path to font folder
	$font_path = './fonts/';

// URL for status page
	$url = $blizstatuspage;

// Servertypes
	$types = $servertypes[$roster_lang];

// Server populations
	$pops = $serverpops[$roster_lang];


// Text output configuration
function text_output($server,$servertype,$serverstatus,$serverpop,$folder_path,$err)
{
	$outtext = '
<!-- Begin Realmstatus -->
<table width="88" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <img src="'.$folder_path.strtolower($serverstatus).'.png" alt="'.$serverstatus.'" /></td>
  </tr>
  <tr>
    <td valign="middle" align="center" height="54" width="88" style="background-image: url('.$folder_path.strtolower($serverstatus).'2.png);">
      <span style="color:black; font-size: 10px; font-weight: bold;">'.$server.'</span><br />
      <img src="'.$folder_path.strtolower($serverpop).'.png" alt="'.$serverpop.'" /><br />';
if( !$err )
	$outtext .= '      <span style="color:#333333; font-size: 9px; font-weight: bold;">'.$servertype.'</span>';

$outtext .= '</td>
  </tr>
</table>
<!-- End Realmstatus -->
';
	return $outtext;
}


#--[ MYSQL CONNECT AND STORE ]=========================================================

$wowdb->connect($db_host,$db_user,$db_passwd,$db_name);

// Read info from Database
$querystr = "SELECT * FROM `".ROSTER_REALMSTATUSTABLE."` WHERE `server_name` = '".$wowdb->escape($server)."'";
$sql = mysql_query($querystr);
if($sql)
{
	$realmData = mysql_fetch_array($sql);
}
else
{
	die("<!-- $querystr -->\nCould not query: ".mysql_error());
}


//==========[ STATUS GENERATION CODE ]=================================================

// Fix folder path
	if (substr($folder_path, -1, 1) != '/') $folder_path = "$folder_path/";

// Check timestamp, update when ready
	$timestamp = date(i)*1;


if( $timestamp >= ($realmData['timestamp']+$timer) or $timestamp < $realmData['timestamp'] )
{
	// Get and format HTML data

	// Check for xml/html
	if(substr($url,-4) == '.xml')
	{
		$xml = 1;
	}
	else
	{
		if (function_exists(curl_init))
		{
			$fp = curl_init( $url );
			curl_setopt($fp, CURLOPT_HEADER, 0);
			curl_setopt($fp, CURLOPT_RETURNTRANSFER, 1);
			$html = curl_exec($fp);
			if ( curl_errno($fp) )
				$ch_err = 1;
			else
				$ch_err = 0;
			curl_close($fp);
		}
		else
		{
			$html = file_get_contents($url);
		}
	}

//==========[ HTML PARSER ]============================================================

	if ($html)
	{
		$pos = strpos($html, str_replace('\\', '',str_replace("'", '&#039;', $server)));

		if (!$pos)
		{
			$error = $error.', check Realm name';
			$err = 1;
		}
	// Figure out Serverstatus
		$serverstatus = stristr(substr($html, ($pos - 165), 15), 'up');
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
		foreach($types as $t)
		{
		$servertype = stristr(substr($html, ($pos + 130), 30), $t);
			if($servertype)
			{
				$foundtype = 1;
				$servertype = $t;
				break;
			}
		}
		if(!$foundtype)
		{
			$err = 1;
		}

	// Figure out Server Pop.
		foreach($pops as $p)
		{
		$serverpop = stristr(substr($html, ($pos + 290), 35), $p);
			if($serverpop)
			{
				$foundpop = 1;
				$serverpop = $p;
				break;
			}
		}
		if(!$foundpop)
		{
			$err = 1;
		}

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
										break;
									case 1:
										$servertype = 'Normal';
										break;
									case 2:
										$servertype = '(PvP)';
										break;
									case 3:
										$servertype = '(RP)';
										break;
									default:
										$servertype = 'Unknown';
								}
								switch ( $xml_server['L'] )
								{
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
			}
		}
	}
	else
		$err = 1;


//==========[ WRITE INFO TO DATABASE ]=================================================

	if(!$err) // Don't write to DB if there has been an error
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
			mysql_query($querystr) or die(mysql_error());
			$querystr = "INSERT INTO `".ROSTER_REALMSTATUSTABLE."` SET ".$wowdb->assignstr;
		}
		// Give only debug infos with text-status enabled
		// Otherwise the debug-statement will destroy the png-generation
		if ($sqldebug AND !$generate_image)
		{
			print "<!-- $querystr -->\n";
		}

		mysql_query($querystr) or die(mysql_error());

		// Re-Read info from Database
		$querystr = "SELECT * FROM `".ROSTER_REALMSTATUSTABLE."` WHERE `server_name` = '".$wowdb->escape($server)."'";
		$sql = mysql_query($querystr);
		if($sql)
		{
			$realmData = mysql_fetch_array($sql);
		}
		else
		{
			die("<!-- $querystr -->\nCould not query: ".mysql_error());
		}
	}
}


//==========[ "NOW, WHAT TO DO NEXT?" ]================================================

// Error control
if( $realmData['serverstatus'] == 'Down' || $realmData['serverstatus'] == 'Maitenence' )
{
	$realmData['serverstatus'] = 'Down';
	$realmData['serverpop'] = 'Offline';
}

if( $err )
{
	$realmData['serverstatus'] = 'Unknown';
	$realmData['serverpop'] = 'Error';
}

if( $generate_image )
{
	img_output($realmData['server_name'],$realmData['servertype'],$realmData['serverstatus'],$realmData['serverpop'],$err,$image_path,$display,$font_path);
}
else
{
	echo text_output($realmData['server_name'],$realmData['servertype'],$realmData['serverstatus'],$realmData['serverpop'],$image_path,$err);
}


//==========[ IMAGE GENERATOR ]========================================================

function img_output ($server,$servertype,$serverstatus,$serverpop,$err,$image_path,$display,$font_path)
{
	$serverfont = $font_path . 'silkscreen.ttf';
	$typefont = $font_path . 'silkscreenb.ttf';
	header("Content-type: image/png");

	// Get and combine base images, set colors
	$back = imagecreatefrompng($image_path . strtolower($serverstatus) . '.png');

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
	imagepng($back);
	imagedestroy($back);
}
?>