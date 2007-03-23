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

// Disable generation of headers from settings.php
$no_roster_headers = true;

require_once( 'settings.php' );     ##  Uses the same settings.php as WoWProfiler
$name = $_GET['name'];  ##  Here because some hosts need it

$sitename=$roster_conf['website_address'];  ## Change this to your web address or a guild motto or whatever


$result = $wowdb->query("SELECT * FROM `".ROSTER_PLAYERSTABLE."` WHERE `name` LIKE '$name' LIMIT 0 , 1");

if (!$result)  ##  Checks to see if the character name is in the database, if it's not there then it ends
{
	die('Could not query:' . $wowdb->error());
}

##  since we are using the same databses as WoWProfiler, the die command was only needed for the
##  initial check.  You can add it to the rest if you're more comforatable with it there
$getdata = $wowdb->fetch_array($result);


##  Could have just pulled what's needed from the database but left open in case I want to add later
$result2 = $wowdb->query("SELECT * FROM `".ROSTER_GUILDTABLE."` LIMIT 0 , 1");
$getdata2 = $wowdb->fetch_array($result2);


$result3 = $wowdb->query("SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE `name` LIKE '$name' LIMIT 0 , 1");
$getdata3 = $wowdb->fetch_array($result3);

##  Gets the character id number set in the database
$nameid = $getdata["member_id"];

##  This sets up the image that is going to be used as the background
##  This sets up my font color, this is Black
$im = imagecreatefrompng($roster_conf['signaturebackground']);
$color=ImageColorAllocate($im, 0,0,0);

##   Making it so the servername aligns from the right instead of the left of the image field
$stxtsize = imagettfbbox(6,0,"fonts/VERANDA.TTF",$getdata['server']);	##  gets the points of the image coordinates
$stxt = $stxtsize[2];	##  pulls the variable for the x point right bottom
$stxtloc = 390-$stxt;	##  Sets the x coordinate where to print the server name

##   Making it so the Guild Name is centered in the title box
$gtxtsize = imagettfbbox(14,0,"fonts/OLDENGL.TTF", $getdata2['guild_name']);	##  gets the points of the image coordinates
$gtxt = $gtxtsize[2]/2;	##  pulls the variable for the x point right bottom, getting the length in pixels of the guild name
$gtxtloc = 237-$gtxt;	##  Sets the x coordinate where to print the guild name

##   Making it so the Site Name/motto line is centered in its box
$mtxtsize = imagettfbbox(6,0,"fonts/VERANDA.TTF", $sitename);	##  gets the points of the image coordinates
$mtxt = $mtxtsize[2]/2;	##  pulls the variable for the x point right bottom, getting the length in pixels of the text
$mtxtloc = 182-$mtxt;	##  Sets the x coordinate where to print the bottombar's text name


##   For those who don't know, this is how ImageTTFText() is set up
##   ImageTTFText($im, fontsize, fontangle, horizontal point, vertical point, font color, font name/path, Text);
ImageTTFText($im, 6, 0, 85, 20, $color, "fonts/VERANDA.TTF", $getdata3["guild_title"]);
ImageTTFText($im, 14, 0, $gtxtloc, 20, $color, "fonts/OLDENGL.TTF", $getdata2["guild_name"]);
ImageTTFText($im, 6, 0, $stxtloc, 20, $color, "fonts/VERANDA.TTF", $getdata["server"]);
ImageTTFText($im, 24, 0, 85, 51, $color, "fonts/OLDENGL.TTF", $getdata["name"]);
ImageTTFText($im, 7, 0, 85, 65, $color, "fonts/VERANDA.TTF", 'Level '.$getdata["level"].' '.$getdata["race"].' '.$getdata["class"]);
ImageTTFText($im, 6, 0, $mtxtloc, 77, $color, "fonts/VERANDA.TTF", $sitename);


##  Time for the professions and secondary skills to be shown
##  Scrolls through database and finds the 2 main professions then prints them and their skill levels on the signature

$result4 = $wowdb->query("SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` LIKE '$nameid' LIMIT 0 , 30");

$pos=35; # <-- used as the variable for moving the text to the next line.  without this it would print the professions right on top of each other
while ($r = $wowdb->fetch_array($result4))
{
	extract($r);

	if ($skill_type==$wordings[$roster_conf['roster_lang']]['professions'])
	{
		ImageTTFText($im, 6, 0, 290, $pos, $color, "fonts/VERANDA.TTF", $skill_name);
		ImageTTFText($im, 6, 0, 355, $pos, $color, "fonts/VERANDA.TTF", $skill_level);
		$pos=$pos+8;
	}
}

$pos=$pos+3; # <-- just to put a small space between the primary and secondary professions lists

##  Scrolls through database and finds all the secondary skills then prints them and their skill levels on the signature

$result5 = $wowdb->query("SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` LIKE '$nameid' LIMIT 0 , 30");
while ($r = $wowdb->fetch_array($result5))
{
	extract($r);

	if ($skill_type==$wordings[$roster_conf['roster_lang']]['secondary'])
	{
		ImageTTFText($im, 6, 0, 290, $pos, $color, "fonts/VERANDA.TTF", $skill_name);
		ImageTTFText($im, 6, 0, 355, $pos, $color, "fonts/VERANDA.TTF", $skill_level);
		$pos=$pos+8;
	}
}

##  Now to finalize and close


$trans = imagecolorresolve($im, 255, 255, 254);
imagecolortransparent($im, $trans);

## set as png since there are some issues with some using image/gif
Header("Content-type: image/png");

ImagePng($im);
ImageDestroy($im);

##  The End
?>