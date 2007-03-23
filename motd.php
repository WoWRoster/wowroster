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

//==========[ SETTINGS ]========================================================

if( isset($_GET['motd']) )
{
	$guildMOTD = urldecode($_GET['motd']);
}
else
{
	$guildMOTD = 'No Message';
}

// Chomp $guildMOTD at 145 characters
$guildMOTD = substr($guildMOTD,0,145);


// Path to font folder
$image_path = './img/';
$font_path = './fonts/';


motd_img($guildMOTD,$image_path,$font_path);


//==========[ IMAGE GENERATOR ]=================================================

function motd_img( $guildMOTD,$image_path,$font_path )
{
	// Set ttf font
	$visitor = $font_path . 'visitor.ttf';

	// Get sizes of text
	$dimensions = imagettfbbox( 12, 0, $visitor, $guildMOTD );
	$text_length = $dimensions[2] - $dimensions[6];

	// Get how many times to print center
	$image_size = ceil($text_length/198);
	$final_size = 55 + ($image_size*198);
	$text_loc = ($final_size/2) - ($dimensions[2]/2);

	// Create new image
	$img = imagecreate($final_size,38);

	// Get and combine base images, set colors
	$img_file = imagecreatefrompng($image_path . 'gmotd.png');

	// Copy image file into new image
	// Copy Left part
	imagecopy ( $img, $img_file, 0, 0, 0, 0, 38, 38 );

	// Copy center part however times needed
	for ($i=0;$i<$image_size;$i++)
	{
		imagecopy ( $img, $img_file, ($i*198)+38, 0, 39, 0, 198, 38 );
	}
	// Copy Right part
	imagecopy ( $img, $img_file, ($image_size*198)+38, 0, 237, 0, 17, 38 );

	$textcolor = imagecolorallocate($img, 255, 255, 255);

	imagettftext($img, 12, 0, $text_loc, 22, $textcolor, $visitor, $guildMOTD);

	header('Content-type: image/png');
	imagepng($img);
	imagedestroy($img);
}
?>