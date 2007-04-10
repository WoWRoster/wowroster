<?php
include 'conf.php';			// Uses the same conf.php as WoWProfiler

if(isset($_GET['name']))
	$name = $_GET['name'];
else
	$name = substr($_SERVER['PATH_INFO'],1,-4);

Header('Content-type: image/png');	// Set as png since there are some issues with some using image/gif


#--[ CONFIG ]--------------------------------------------------------------

	// These variables are set from "conf.php"
		$sitename = $website_address;		// Change this to your web address or a guild motto or whatever
		$imDir = $sig_dir;							// Change this to the dir where the signature images are stored
		$imUserDir = 'members/';				// Change this to the dir where the custom user signatures are stored
		$imDefault = $sig_default;			// Change this to the default image name, no extention

	//(In some servers, you might need the full path)
	// You might also need to add a leading './' and/or the extention '.TTF' to these
		$standardFont = './fonts/VERANDA.TTF';	// Standard font
		$fancyFont = './fonts/OLDENGL.TTF';			// Fancy font


#--[ MYSQL CONNECT AND STORE ]---------------------------------------------

	if (!mysql_connect($db_host, $db_user, $db_passwd)) die('Could not connect: '.mysql_error());
	if (!mysql_select_db($db_name)) die ("Can't use $dbname : ".mysql_error());

	// Checks to see if the character name is in the database, if it's not there then it ends
		$SQL_players = mysql_query("SELECT * FROM `".ROSTER_PLAYERSTABLE."` WHERE `name` LIKE '$name'");
		if ($SQL_players) {
			$playersData = mysql_fetch_array($SQL_players);
			$nameid = $playersData['member_id'];		// Gets the character ID number from the database
		} else die('Could not query:'.mysql_error());

	// Get guild_table data
		$SQL_guild = mysql_query("SELECT * FROM `".ROSTER_GUILDTABLE."` WHERE `guild_name` LIKE '$guild_name'");
		$guildData = mysql_fetch_array($SQL_guild);

	// Get members_table data
		$SQL_members = mysql_query("SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE `name` LIKE '$name'");
		$membersData = mysql_fetch_array($SQL_members);

	// Get skills_table data
		$SQL_prof = mysql_query("SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` LIKE '$nameid'");
		$SQL_skills = mysql_query("SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` LIKE '$nameid'");
	

#--[ eXPBar & HexColor Converter ]-----------------------------------------

	function printXP($expval) {
		list($current, $max) =
		explode( ':', $expval );
		if ($current > 0) {
			$forCurr = number_format($current);
			$forMax = number_format($max);
			return '[ ' . $forCurr .' of ' . $forMax. ' XP ]';
		}
	}
	function retPerc($expval) {
		list($current, $max) =
		explode( ':', $expval );
		if ($current > 0) {
		// $perc = round( (($current / $max)* barlength) + barstart, 0)
			$perc = round( (($current / $max)* 201) + 82, 0);
			return $perc;
		}
	}
	function setColor($fgt,$imagev) {
		$red = 100;
		$green = 100;
		$blue = 100;
		if(eregi("[#]?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})", $fgt, $ret)) {
			$red = hexdec($ret[1]);
			$green = hexdec($ret[2]);
			$blue = hexdec($ret[3]);
	  }
		$gotit = ImageColorAllocate($imagev, $red, $green, $blue);
		return $gotit;
	}
	// Variables to get and hold eXP bar data
		$outexp = printXP($playersData['exp']);
		$outperc = retPerc($playersData['exp']);


#--[ BACKGROUND IMAGES SELECTOR ]------------------------------------------

// First, set the default image
	$im = @imagecreatefrompng($imDir.$imDefault.'.png')
		or die('Error line '.__LINE__.': Cannot find default background image ['.$imDir.$imDefault.'.png]'."\n".'Check the config file and your background images directory');

// Check for custom background (for future use or manually uploaded backg's)
	if( file_exists($imDir.$imUserDir.$membersData['name'].'.png'))
		// Set image based on name in DB
		$im = @imagecreatefrompng( $imDir.$imUserDir.$membersData['name'].'.png')
			or die('Error line '.__LINE__.': Cannot find user background ['.$imDir.$imUserDir.$membersData['name'].'.png]'."\n".'Check the config file and your member images directory');

	else {

		if($playersData['race']) { // Check for 'race' in DB

			if($playersData['sex']) // Then check for 'gender' in DB

				// Show race-gender image
				$im = @imagecreatefrompng($imDir.$wordings[$roster_lang][$playersData['race']].'-'.$wordings[$roster_lang][$playersData['sex']].'.png')
					or die('Error line '.__LINE__.': Cannot find race-gender background ['.$imDir.$wordings[$roster_lang][$playersData['race']].'-'.$wordings[$roster_lang][$playersData['sex']].'.png]'."\n".'Check the config file and your images directory');

			else // Show race image
				$im = @imagecreatefrompng($imDir.$wordings[$roster_lang][$playersData['race']].'.png')
					or die('Error line '.__LINE__.': Cannot find race background ['.$imDir.$wordings[$roster_lang][$playersData['race']].'.png]'."\n".'Check the config file and your images directory');

		}// Keep default image
	}


#--[ COLOR CONFIG ]--------------------------------------------------------

	$color = setColor('#000000',$im);					// This sets up my font color, this is Black
	$colorLvL = setColor('#99FF33',$im);			// Color for the text in the LvL bubble
	$colorExpIns = setColor('#808080',$im);		// Color for the eXP bar (inside box)
	$colorExpBar = setColor('#99FF33',$im);		// Color for the eXP bar (progress bar)
	$colorExpBdr = setColor('#517FD3',$im);		// Color for the eXP bar (outside border)


#--[ TEXT JUSTIFICATION ]--------------------------------------------------

// Making it so the Servername aligned right
	$stxtsize = imagettfbbox(6,0,$standardFont,$guildData['server']);		// Gets the points of the image coordinates
	$stxt = $stxtsize[2];				// Pulls the variable for the x point right bottom
	$stxtloc = 390-$stxt;				// Sets the x coordinate where to print the server name

// Making it so the Guild Name is centered
	$gtxtsize = imagettfbbox(14,0,$fancyFont, $guildData['guild_name']);		// Gets the points of the image coordinates
	$gtxt = $gtxtsize[2]/2;					// pulls the variable for the x point right bottom, getting the length in pixels of the text
	$gtxtloc = 207-$gtxt;						// Sets the x coordinate where to print the guild name

// Making it so the Site Name/motto line is aligned right
	$sntxtsize = imagettfbbox(6,0,$standardFont, $sitename);		// gets the points of the image coordinates
	$sntxt = $sntxtsize[2];					// pulls the variable for the x point right bottom, getting the length in pixels of the text
	$sntxtloc = 390-$sntxt;					// Sets the x coordinate where to print the sitename
	
// Making it so the Level text is centered in the LvL bubble
	if($playersData['level'] > $membersData['level'])
		$ltxtsize = imagettfbbox(9,0,$standardFont, $playersData['level']);
	else 
		$ltxtsize = imagettfbbox(9,0,$standardFont, $membersData['level']);

	$ltxt = $ltxtsize[2]/2;					// pulls the variable for the x point right bottom, getting the length in pixels of the text
	$ltxtloc = 70-$ltxt;						// Sets the x coordinate where to print the LvL
	
// Making it so the Class/race line is aligned right
	$rctxtsize = imagettfbbox(7,0,$standardFont, $playersData['race'].' '.$membersData['class']);		// gets the points of the image coordinates
	$rctxt = $rctxtsize[2];					// pulls the variable for the x point right bottom, getting the length in pixels of the text
	$rctxtloc = 280-$rctxt;					// Sets the x coordinate where to print Race/Class info


#--[ PLACE DYNAMIC INFO IN THE IMAGE ]-------------------------------------

	// For those who don't know, this is how ImageTTFText() and imagefilledrectangle() are set-up
	// ImageTTFText($im, fontsize, fontangle, horizontal point, vertical point, font color, font name/path, Text);
	// imagefilledrectangle(img, x start, y start, x end, y end, color)

	// Check for LvL 60 and draw a full exp bar and write MAX EXP in it
	if($playersData['exp']) {
		if($playersData['level'] == 60) {
			// imagefilledrectangle($im, 82, 70, 283, 79, $colorExpBdr);			// The eXP bar (outside border)			
			// imagefilledrectangle($im, 83, 71, 282, 78, $colorExpIns);			// The eXP bar (inside box)
			imagefilledrectangle($im, 83, 71, 282, 78, $colorExpBar);					// A Full eXP bar (progress bar)	
			ImageTTFText($im, 6, 0, 88, 77, $color, $standardFont, 'Max Experience');
		} else {
			// imagefilledrectangle($im, 82, 70, 283, 79, $colorExpBdr);			// The eXP bar (outside border)			
			// imagefilledrectangle($im, 83, 71, 282, 78, $colorExpIns);			// The eXP bar (inside box)
			imagefilledrectangle($im, 83, 71, $outperc, 78, $colorExpBar);		// The eXP bar (progress bar)	
			ImageTTFText($im, 6, 0, 88, 77, $color, $standardFont, 'Exp: ' .$outexp);
		}
	}

	// Check for PvP rank
	// Stored as none for some chars in database for no rank, so check for that and set to blank
	if( $playersData['RankName'] == 'None' )
		$pvpRank = '';
	else
		$pvpRank = $playersData['RankName'];

	ImageTTFText($im, 7, 0, 85, 21, $color, $standardFont, $membersData['guild_title']);
	ImageTTFText($im, 14, 0, $gtxtloc, 21, $color, $fancyFont, $guildData['guild_name']);
	ImageTTFText($im, 6, 0, $sntxtloc, 21, $color, $standardFont, $sitename);
	ImageTTFText($im, 6, 0, $stxtloc, 11, $color, $standardFont, $guildData['server']);
	ImageTTFText($im, 11, 0, 85, 39, $color, $fancyFont, $pvpRank);
	ImageTTFText($im, 24, 0, 95, 66, $color, $fancyFont, $membersData['name']);
	ImageTTFText($im, 7, 0, $rctxtloc, 34, $color, $standardFont, $playersData['race'].' '.$membersData['class']);
	if($playersData['level'] > $membersData['level'])
		ImageTTFText($im, 9, 0, $ltxtloc, 75, $colorLvL, $standardFont, $playersData['level']);
	else
		ImageTTFText($im, 9, 0, $ltxtloc, 75, $colorLvL, $standardFont, $membersData['level']);


#--[ PROFESSIONS AND SECONDARY SKILLS ]------------------------------------

	// Variable for moving the text to the next line.  without this it would print the professions right on top of each other
	$pos=35;

	// Scrolls through database and finds all the professions then prints them and their skill levels on the signature
	while ($r = mysql_fetch_array($SQL_prof)) {
		extract($r);
		if ($skill_type==$wordings[$roster_lang]['professions']) {
			ImageTTFText($im, 6, 0, 290, $pos, $color, $standardFont, $skill_name);
			ImageTTFText($im, 6, 0, 355, $pos, $color, $standardFont, $skill_level);
			$pos=$pos+8;
		}
	}

	// Put a small space between the primary and secondary professions lists
	$pos=$pos+3;

	// Scrolls through database and finds all the secondary skills then prints them and their skill levels on the signature
	while ($r = mysql_fetch_array($SQL_skills)) {
		extract($r);
		if ($skill_type==$wordings[$roster_lang]['secondary']) {
			ImageTTFText($im, 6, 0, 290, $pos, $color, $standardFont, $skill_name); 
			ImageTTFText($im, 6, 0, 355, $pos, $color, $standardFont, $skill_level);
			$pos=$pos+8;
		}
	}


#--[ FINALIZE AND CLOSE ]--------------------------------------------------

ImagePng($im);
ImageDestroy($im);
?>