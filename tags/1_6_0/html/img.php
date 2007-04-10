<?php
$versions['versionDate']['img'] = '$Date: 2005/12/30 20:40:52 $'; 
$versions['versionRev']['img'] = '$Revision: 1.5 $'; 
$versions['versionAuthor']['img'] = '$Author: mordon $';

  include "conf.php";     ##  Uses the same conf.php as WoWProfiler
  $name = $_GET['name'];  ##  Here because some hosts need it

  $sitename=$website_address;  ## Change this to your web address or a guild motto or whatever

  if (!mysql_connect($db_host, $db_user, $db_passwd)) {	die('Could not connect: ' . mysql_error()); }
  if (!mysql_select_db($db_name)) {	die ('Can\'t use $dbname : ' . mysql_error()); }

  Header("Content-type: image/png");							 ## set as png since there are some issues with some using image/gif

  ###########################  Depending on which version of the sql tables you are using, you may have to change it  ##############################################
  ###########################  If you are using the OLD databse structure, aka tablename is char instead of players   ##############################################
  ###########################       then uncomment line 17 and comment out the "SELECT * FROM `players`" line 18      ##############################################  
  ###################  Also check and change lines 62, 81, for database query and 70, 71, 72, 87, 88, and 89 for table names  ######################################  
	
  ##$result = mysql_query("SELECT * FROM `char` WHERE `name` LIKE '$name' LIMIT 0 , 30");       ##  OLD DATABASE TABLE STRUCTURE
  $result = mysql_query("SELECT * FROM `".ROSTER_PLAYERSTABLE."` WHERE `name` LIKE '$name' LIMIT 0 , 30");  ##  NEW DATABASE TABLE STRUCTURE
	if (!$result) {	die('Could not query:' . mysql_error());  }  ##  Checks to see if the character name is in the database, if it's not there then it ends
  $getdata = mysql_fetch_array($result);						 ##  since we are using the same databses as WoWProfiler, the die command was only needed for the
																 ##  initial check.  You can add it to the rest if you're more comforatable with it there

  $result2 = mysql_query("SELECT * FROM `".ROSTER_GUILDTABLE."` LIMIT 0 , 30");  ##  Could have just pulled what's needed from the database but left open in case I want to add later
  $getdata2 = mysql_fetch_array($result2);

  $result3 = mysql_query("SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE `name` LIKE '$name' LIMIT 0 , 30");
  $getdata3 = mysql_fetch_array($result3);

  $nameid = $getdata["member_id"];  							 ##  Gets the character id number set in the database

  $im = imagecreatefrompng($signaturebackground);        ##  This sets up the image that is going to be used as the background
  $color=ImageColorAllocate($im, 0,0,0);  						 ##  This sets up my font color, this is Black

  ##   Making it so the servername aligns from the right instead of the left of the image field
  $stxtsize = imagettfbbox(6,0,"/fonts/VERANDA.TTF",$getdata["server"]);  ##  gets the points of the image coordinates
  $stxt = $stxtsize[2];  										 ##  pulls the variable for the x point right bottom
  $stxtloc = 390-$stxt;  										 ##  Sets the x coordinate where to print the server name

  ##   Making it so the Guild Name is centered in the title box
  $gtxtsize = imagettfbbox(14,0,"/fonts/OLDENGL.TTF", $getdata2["guild_name"]);  ##  gets the points of the image coordinates
  $gtxt = $gtxtsize[2]/2; 										 ##  pulls the variable for the x point right bottom, getting the length in pixels of the guild name
  $gtxtloc = 237-$gtxt;  										 ##  Sets the x coordinate where to print the guild name
  
  ##   Making it so the Site Name/motto line is centered in its box
  $mtxtsize = imagettfbbox(6,0,"/fonts/VERANDA.TTF", $sitename); ##  gets the points of the image coordinates
  $mtxt = $mtxtsize[2]/2;  										 ##  pulls the variable for the x point right bottom, getting the length in pixels of the text
  $mtxtloc = 182-$mtxt;											 ##  Sets the x coordinate where to print the bottombar's text name
  
  ##   For those who don't know, this is how ImageTTFText() is set up
  ##   ImageTTFText($im, fontsize, fontangle, horizontal point, vertical point, font color, font name/path, Text);
  
  ImageTTFText($im, 6, 0, 85, 20, $color, "/fonts/VERANDA.TTF", $getdata3["guild_title"]);
  ImageTTFText($im, 14, 0, $gtxtloc, 20, $color, "/fonts/OLDENGL.TTF", $getdata2["guild_name"]);
  ImageTTFText($im, 6, 0, $stxtloc, 20, $color, "/fonts/VERANDA.TTF", $getdata["server"]);
  ImageTTFText($im, 24, 0, 85, 51, $color, "/fonts/OLDENGL.TTF", $getdata["name"]);
  ImageTTFText($im, 7, 0, 85, 65, $color, "/fonts/VERANDA.TTF", 'Level '.$getdata["level"].' '.$getdata["race"].' '.$getdata["class"]);
  ImageTTFText($im, 6, 0, $mtxtloc, 77, $color, "/fonts/VERANDA.TTF", $sitename);
  
  ##  Time for the professions and secondary skills to be shown
  ##  Scrolls through database and finds the 2 main professions then prints them and their skill levels on the signature

  ##  $result4 = mysql_query("SELECT * FROM `skills` WHERE `name` LIKE '$char' LIMIT 0 , 30");  ## OLD DATABASE STRUCTURE
  $result4 = mysql_query("SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` LIKE '$nameid' LIMIT 0 , 30");  ## NEW DATABASE STRUCTURE

  $pos=35; # <-- used as the variable for moving the text to the next line.  without this it would print the professions right on top of each other
  while ($r = mysql_fetch_array($result4))
		{
			extract($r);
   ######## IF using old TABLE STRUCTURE change $skill_type to $type, $skill_name to $name, and $skill_level to $level ########
			if ($skill_type==$wordings[$roster_lang]['professions']){
			ImageTTFText($im, 6, 0, 290, $pos, $color, "/fonts/VERANDA.TTF", $skill_name);
			ImageTTFText($im, 6, 0, 355, $pos, $color, "/fonts/VERANDA.TTF", $skill_level);
			$pos=$pos+8;
			}
		}
  
  $pos=$pos+3; # <-- just to put a small space between the primary and secondary professions lists
  
  ##  Scrolls through database and finds all the secondary skills then prints them and their skill levels on the signature

  ##  $result5 = mysql_query("SELECT * FROM `skills` WHERE `name` LIKE '$name' LIMIT 0 , 30");  ## OLD DATABASE STRUCTURE
  $result5 = mysql_query("SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` LIKE '$nameid' LIMIT 0 , 30");  ## NEW DATABASE STRUCTURE
  while ($r = mysql_fetch_array($result5))
		{
			extract($r);
   ######## IF using old TABLE STRUCTURE change $skill_type to $type, $skill_name to $name, and $skill_level to $level ########
			if ($skill_type==$wordings[$roster_lang]['secondary']){
			ImageTTFText($im, 6, 0, 290, $pos, $color, "/fonts/VERANDA.TTF", $skill_name); 
			ImageTTFText($im, 6, 0, 355, $pos, $color, "/fonts/VERANDA.TTF", $skill_level);
			$pos=$pos+8;
			}
		}
  
  ##  Now to finalize and close
  
  $trans = imagecolorresolve($im, 255, 255, 254); 
  imagecolortransparent($im, $trans);
  ImagePng($im);
  ImageDestroy($im);
  
  ##  The End
?>