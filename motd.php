<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Message of the Day image generator
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.6.0
*/

define('IN_ROSTER',true);

//==========[ SETTINGS ]========================================================

$roster_root_path = dirname(__FILE__) . DIRECTORY_SEPARATOR;

if( isset($_GET['motd']) )
{
	$guildMOTD = substr(stripslashes(urldecode($_GET['motd'])),0,145);
}
elseif( isset($_GET['id']) )
{
	include( $roster_root_path . 'settings.php' );

	$guild_escape = $_GET['id'];

	$query = "SELECT `guild_motd` "
		   . "FROM `" . $roster->db->table('guild') . "` "
		   . "WHERE `guild_id` = '" . $guild_escape . "';";

	$guild_motd = $roster->db->query_first($query);

	if( !$guild_motd )
	{
		$guild_motd = 'Failed to fetch guild MOTD';
	}
	$guildMOTD = substr(htmlspecialchars($guild_motd),0,145);
}
else
{
	include( $roster_root_path . 'settings.php' );

	$guildMOTD = 'Invalid Access';
}


// Path to font folder
$image_path = $roster_root_path . 'img' . DIRECTORY_SEPARATOR;
$font_path = $roster_root_path . 'fonts' . DIRECTORY_SEPARATOR;


motd_img($guildMOTD,$image_path,$font_path);
die();


//==========[ IMAGE GENERATOR ]=================================================

function motd_img( $guildMOTD,$image_path,$font_path )
{
	$guildMOTD = html_entity_decode($guildMOTD);

	$maxw = 550;
	$vadj = 0;

	// Set ttf font
	$visitor = $font_path . 'VERANDA.TTF';

	// Get sizes of text
	$box = imagettfbbox( 11, 0, $visitor, $guildMOTD );
	$text_length = $box[2] - $box[6];

	// Get how many times to print center
	$image_size = ( $text_length < $maxw ? ceil($text_length/198) : ceil($maxw/198) );
	$final_size = 54 + ($image_size*198);

	$text_loc = ($final_size/2) - ($box[2]/2);

	// Create new image
	$img = imagecreatetruecolor( $final_size,38 );

	// Get and combine base images, set colors
	$img_file = imagecreatefrompng($image_path . 'gmotd.png');

	// Copy image file into new image
	// Copy Left part
	imagecopy( $img, $img_file, 0, 0, 0, 0, 38, 38 );

	// Copy center part however times needed
	for( $i=0;$i<$image_size;$i++ )
	{
		imagecopy( $img, $img_file, ($i*198)+38, 0, 39, 0, 198, 38 );
	}
	// Copy Right part
	imagecopy( $img, $img_file, ($image_size*198)+38, 0, 237, 0, 17, 38 );

	$textcolor = imagecolorallocate( $img, 255, 255, 255 );

	if( $text_length > $maxw )
	{
		$i = $text_length;
		$t = strlen($guildMOTD);
		while( $i > $maxw )
		{
			$t--;
			$box = imagettfbbox(11, 0,$visitor,substr($guildMOTD,0,$t));
			$i = abs($box[0]) + abs($box[2]);
		}
		$t = strrpos(substr($guildMOTD, 0, $t), ' ');
		$output[0] = substr($guildMOTD, 0, $t);
		$output[1] = ltrim(substr($guildMOTD, $t));
		$vadj = -6;
	}
	else
	{
		$output[0] = $guildMOTD;
	}

	$i = 0;
	foreach( $output as $value )
	{
		$box = imagettfbbox(11,0,$visitor,$value);
		$text_length = abs($box[0]) + abs($box[2]);
		imagettftext( $img, 11, 0, round(($final_size-$text_length)/2), 23+($i*14)+$vadj, $textcolor, $visitor, $value );
		$i++;
	}


	//imagettftext( $img, 11, 0, $text_loc, 23, $textcolor, $visitor, $guildMOTD );

	header('Content-type: image/png');
	imagepng($img);
	imagedestroy($img);
}
