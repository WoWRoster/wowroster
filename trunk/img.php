<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Signature Generator
 * (Use The AddOn SigGen, it's much better)
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
*/

define('IN_ROSTER',true);

$roster_root_path = dirname(__FILE__) . DIRECTORY_SEPARATOR;

require_once($roster_root_path . 'settings.php');     ##  Uses the same settings.php as WoWProfiler
$char = addslashes(urldecode($_SERVER['QUERY_STRING']));

if( is_numeric($char) )
{
	$where = ' `member_id` = "' . $char . '"';
}
elseif( strpos($char, '@') !== false )
{
	list($name, $realm) = explode('@',$char);
	if( strpos($realm,'-') !== false )
	{
		list($region, $realm) = explode('-',$realm);
		$where = ' `name` = "' . $name . '" AND `server` = "' . $realm . '" AND `region` = "' . strtoupper($region) . '"';
	}
	else
	{
		$where = ' `name` = "' . $name . '" AND `server` = "' . $realm . '"';
	}
}
else
{
	$name = $char;
	$where = ' `name` = "' . $name . '"';
}

$sitename = $roster->config['website_address'];  ## Change this to your web address or a guild motto or whatever


$result = $roster->db->query("SELECT * FROM `" . $roster->db->table('players') . "` WHERE $where LIMIT 0 , 1;");

if( !$result )  ##  Checks to see if the character name is in the database, if it's not there then it ends
{
	die('Could not query:' . $roster->db->error());
}

##  since we are using the same databses as WoWProfiler, the die command was only needed for the
##  initial check.  You can add it to the rest if you're more comforatable with it there
$getdata = $roster->db->fetch($result);


##  Could have just pulled what's needed from the database but left open in case I want to add later
$result2 = $roster->db->query("SELECT * FROM `" . $roster->db->table('guild') . "` LIMIT 0 , 1;");
$getdata2 = $roster->db->fetch($result2);


$result3 = $roster->db->query("SELECT * FROM `" . $roster->db->table('members') . "` WHERE $where LIMIT 0 , 1;");
$getdata3 = $roster->db->fetch($result3);

##  Gets the character id number set in the database
$nameid = $getdata['member_id'];

##  This sets up the image that is going to be used as the background
##  This sets up my font color, this is Black
$im = imagecreatefrompng($roster->config['signaturebackground']);
$color = ImageColorAllocate($im, 0,0,0);

##   Making it so the servername aligns from the right instead of the left of the image field
$stxtsize = imagettfbbox(6,0,'fonts/VERANDA.TTF',$getdata['server']);	##  gets the points of the image coordinates
$stxt = $stxtsize[2];	##  pulls the variable for the x point right bottom
$stxtloc = 390-$stxt;	##  Sets the x coordinate where to print the server name

##   Making it so the Guild Name is centered in the title box
$gtxtsize = imagettfbbox(14,0,'fonts/OLDENGL.TTF', $getdata2['guild_name']);	##  gets the points of the image coordinates
$gtxt = $gtxtsize[2]/2;	##  pulls the variable for the x point right bottom, getting the length in pixels of the guild name
$gtxtloc = 237-$gtxt;	##  Sets the x coordinate where to print the guild name

##   Making it so the Site Name/motto line is centered in its box
$mtxtsize = imagettfbbox(6,0,'fonts/VERANDA.TTF', $sitename);	##  gets the points of the image coordinates
$mtxt = $mtxtsize[2]/2;	##  pulls the variable for the x point right bottom, getting the length in pixels of the text
$mtxtloc = 182-$mtxt;	##  Sets the x coordinate where to print the bottombar's text name


##   For those who don't know, this is how ImageTTFText() is set up
##   ImageTTFText($im, fontsize, fontangle, horizontal point, vertical point, font color, font name/path, Text);
ImageTTFText($im, 6, 0, 85, 20, $color, 'fonts/VERANDA.TTF', $getdata3['guild_title']);
ImageTTFText($im, 14, 0, $gtxtloc, 20, $color, 'fonts/OLDENGL.TTF', $getdata2['guild_name']);
ImageTTFText($im, 6, 0, $stxtloc, 20, $color, 'fonts/VERANDA.TTF', $getdata['server']);
ImageTTFText($im, 24, 0, 85, 51, $color, 'fonts/OLDENGL.TTF', $getdata['name']);
ImageTTFText($im, 7, 0, 85, 65, $color, 'fonts/VERANDA.TTF', 'Level ' . $getdata['level'] . ' ' . $getdata['race'] . ' ' . $getdata['class']);
ImageTTFText($im, 6, 0, $mtxtloc, 77, $color, 'fonts/VERANDA.TTF', $sitename);


##  Time for the professions and secondary skills to be shown
##  Scrolls through database and finds the 2 main professions then prints them and their skill levels on the signature

$result4 = $roster->db->query("SELECT * FROM `" . $roster->db->table('skills') . "` WHERE `member_id` = '$nameid' LIMIT 0 , 30;");

$pos=35; # <-- used as the variable for moving the text to the next line.  without this it would print the professions right on top of each other
while( $r = $roster->db->fetch($result4) )
{
	extract($r);

	if( $skill_type==$roster->locale->act['professions'] )
	{
		ImageTTFText($im, 6, 0, 290, $pos, $color, 'fonts/VERANDA.TTF', $skill_name);
		ImageTTFText($im, 6, 0, 355, $pos, $color, 'fonts/VERANDA.TTF', $skill_level);
		$pos += 8;
	}
}

$pos += 3; # <-- just to put a small space between the primary and secondary professions lists

##  Scrolls through database and finds all the secondary skills then prints them and their skill levels on the signature

$result5 = $roster->db->query("SELECT * FROM `" . $roster->db->table('skills') . "` WHERE `member_id` LIKE '$nameid' LIMIT 0 , 30;");
while( $r = $roster->db->fetch($result5) )
{
	extract($r);

	if( $skill_type==$roster->locale->act['secondary'] )
	{
		ImageTTFText($im, 6, 0, 290, $pos, $color, 'fonts/VERANDA.TTF', $skill_name);
		ImageTTFText($im, 6, 0, 355, $pos, $color, 'fonts/VERANDA.TTF', $skill_level);
		$pos += 8;
	}
}

##  Now to finalize and close


$trans = imagecolorresolve($im, 255, 255, 254);
imagecolortransparent($im, $trans);

## set as png since there are some issues with some using image/gif
header('Content-type: image/png');

ImagePng($im);
ImageDestroy($im);

##  The End
