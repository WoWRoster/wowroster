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

include('settings.php');

// Set height/width here
$w = 150;
$h = 160;
$max = 0;

$type = isset($_GET['type'])?$_GET['type']:'class';

// Collect data
if ($type == 'class')
{
	$query = "SELECT `class`, count(`member_id`) AS `count` ".
			"FROM ".ROSTER_CHARACTERSTABLE." AS `characters` ".
			"GROUP BY `class`";
	$result = $wowdb->query($query);

	if (!$result)
	{
		die($wowdb->error());
	}

	while( $row = $wowdb->fetch_assoc($result) )
	{
		$dat[] = array( 'label' => $row['class'],
						'value' => $row['count'],
						'image' => $wordings[$roster_conf['roster_lang']]['class_iconArray'][$row['class']]
				);
		$max = ($max>$row['count'])?$max:$row['count'];
	}

	$wowdb->free_result($result);

	// Calculate proportions
	$count = count($dat);
	$colh = $h / $count;
	$offset = $colh;
	$factor = ($w-$offset)/$max;

	$font = ROSTER_BASE.'fonts/VERANDA.TTF';
	$textheight = .6*$colh;
	$textbase = .8*$colh;
	$textoffset = $offset + .2*$colh;
}
elseif ($type == 'level')
{
	$query = "SELECT FLOOR(`level`/10) AS `level`, count(`member_id`) AS `count` ".
			"FROM ".ROSTER_CHARACTERSTABLE." AS `characters` ".
			"GROUP BY FLOOR(`level`/10)";
	$result = $wowdb->query($query);

	if (!$result)
	{
		die($wowdb->error());
	}

	// Set amount for all level groups to 0.
	$dat = array_fill(0,8,0);

	while( $row = $wowdb->fetch_assoc($result) )
	{
		$dat[$row['level']] = array( 'label' => ($row['level']*10).' - '.($row['level']*10+9),
						'value' => $row['count']
				);
		$max = ($max>$row['count'])?$max:$row['count'];
	}

	$wowdb->free_result($result);

	// Calculate proportions
	$count = count($dat);
	$colh = $h / $count;
	$offset = 0;
	$factor = ($w-$offset)/$max;

	$font = ROSTER_BASE.'fonts/VERANDA.TTF';
	$textheight = .8*$colh;
	$textbase = .9*$colh;
	$textoffset = $offset + .2*$colh;
}

// Initialize image
$image = imagecreatetruecolor($w,$h);
imagealphablending($image,false);
$bgcolor = imagecolorallocatealpha($image,0,0,0,127);
imagefilledrectangle($image,0,0,$w,$h,$bgcolor);
imagealphablending($image,true);

// Set drawing color
$barcolor = imagecolorallocate($image,255,0,0);
$textcolor = imagecolorallocate($image,0,0,0);

// Draw bars
foreach($dat as $i => $bar)
{
	// Get icon
	if( isset($bar['image']) ) switch ($roster_conf['img_suffix'])
	{
		case 'jpg':
			$icon = @imagecreatefromjpeg($roster_conf['interface_url'].'Interface/Icons/'.$bar['image'].'.jpg');
			break;
		case 'png':
			$icon = @imagecreatefrompng($roster_conf['interface_url'].'Interface/Icons/'.$bar['image'].'.png');
			break;
		case 'gif':
			$icon = @imagecreatefromgif($roster_conf['interface_url'].'Interface/Icons/'.$bar['image'].'.gif');
			break;
		default:
			$icon = false;
			break;
	}

	// If there was an error $icon will be false, otherwise add the icon
	if( $icon )
	{
		imagecopyresampled($image, $icon, 0, $colh * $i, 0, 0, $colh, $offset, 40, 40);
		imagedestroy($icon);
	}

	// Draw the bar
	imagefilledrectangle($image, $offset, $colh * $i, $offset+$bar['value']*$factor, $colh * ($i+1), $barcolor);

	// Draw the label
	if( isset($font) )
	{
		imagettftext($image, $textheight, 0, $textoffset, $colh*$i+$textbase, $textcolor, $font, $bar['label']);
	}
}

// Output image
header('Content-type: image/png');
imagealphablending( $image, false );
imagesavealpha( $image, true );
imagepng($image);
imagedestroy($image);

?>
