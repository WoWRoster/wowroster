<?php
$versions['versionDate']['siggen'] = '$Date: 2006/01/31 02:24:22 $';
$versions['versionRev']['siggen'] = '$Revision: 1.13 $';
$versions['versionAuthor']['siggen'] = '$Author: zanix $';


require_once( '../../conf.php' );				// Use the same "conf.php" as WoWProfilers.com roster
require_once( '../../lib/wowdb.php' );	// Use the same "lib/wowdb.php" as WoWProfilers.com roster

require_once( 'conf.php' );				// Require the siggen config

require_once( 'inc/translate.inc' );		// Translation file


// Define the sig_config table if not defined
if( !defined('ROSTER_SIGCONFIGTABLE') )
{
	define('ROSTER_SIGCONFIGTABLE',$db_prefix.'addon_siggen');
}


// Text to output when name is not found in the member list
$sig_no_data = 'SigGen Works';


// Get name from browser request
// url_decode the name, then utf-8_encode it
if( isset($_GET['name']) )
{
	$char_name = utf8_encode(urldecode($_GET['name']));
} // Try pulling from a "psth" request
else
{
	$char_name = utf8_encode(urldecode(substr( $_SERVER['PATH_INFO'],1,-4 )));
}


// Get image mode ( signature | avatar | etc )
if( isset($_GET['mode']) )
{
	$config_name = $_GET['mode'];
}
elseif( isset($config_name) )
{
	$config_name = $config_name;
}
else
{
	if( eregi('siggen.php',$_SERVER['PHP_SELF']) )
	{
		exit("You cannot access this file directly without a 'mode' option");
	}
}


// Web selectable "save only" mode
if( isset($_GET['saveonly']) )
{
	if( $_GET['saveonly'] == '1' )
	{
		$sig_saveonly = 1;
	}
	elseif( $_GET['saveonly'] == '0' )
	{
		$sig_saveonly = 0;
	}
}


// Web selectable etag mode
if( isset($_GET['etag']) )
{
	if( $_GET['etag'] == 1 )
	{
		$sig_etag_mode = 1;
	}
	elseif( $_GET['etag'] == 0 )
	{
		$sig_etag_mode = 0;
	}
}


#--[ MYSQL CONNECT AND STORE ]---------------------------------------------

	$wowdb->connect($db_host,$db_user,$db_passwd,$db_name);

	// Read SigGen Config data from Database
		$config_str = 'SELECT * FROM `'.ROSTER_SIGCONFIGTABLE."` WHERE `config_id` LIKE '$config_name';";
		$config_sql = $wowdb->query($config_str);
		if( $config_sql && mysql_num_rows($config_sql) != 0 )
		{
			$configData = mysql_fetch_array($config_sql);
		}
		else
		{
			exit("Could not find config_id[$config_name] in table[".ROSTER_SIGCONFIGTABLE."]<br />\nQuery-> $config_str<br />\n".mysql_error());
		}

		if( $sc_db_ver != $configData['db_ver'] )
		{
			exit('The database has been changed/upgraded<br />You need to run SigGen Config before using the signatures');
		}

	// Read guild data from Database
		$guild_str = 'SELECT * FROM `'.ROSTER_GUILDTABLE."` WHERE `guild_name` = '".$wowdb->escape($guild_name)."' AND `server` = '".$wowdb->escape($server_name)."';";
		$guild_sql = $wowdb->query($guild_str);
		if( $guild_sql && mysql_num_rows($guild_sql) != 0 )
		{
			$guildData = mysql_fetch_array($guild_sql);
			$guildid = $guildData['guild_id'];
		}
		else
		{
			exit("Could not Query-><br />\n$guild_str<br />\n".mysql_error());
		}


	// Read member list from Database
		$members_str = 'SELECT * FROM `'.ROSTER_MEMBERSTABLE."` WHERE `name` LIKE '$char_name' AND `guild_id` = $guildid;";
		$members_sql = $wowdb->query($members_str);
		if( $members_sql )
		{
			$membersData = mysql_fetch_array($members_sql);
			$member_id = $membersData['member_id'];		// Gets the character ID number from the database
		}
		else
		{
			exit("Could not Query-><br />\n$members_str<br />\n".mysql_error());
		}

	// If the member is not found, write message to name
		if( mysql_num_rows($members_sql) == 0 )
		{
			$membersData['name'] = $sig_no_data;
		}


	// Read character data from Database
		$players_str = 'SELECT * FROM `'.ROSTER_PLAYERSTABLE."` WHERE `member_id` = '$member_id';";
		$players_sql = $wowdb->query($players_str);
		if( $players_sql )
		{
			$playersData = mysql_fetch_array($players_sql);
		}
		else
		{
			exit("Could not Query-><br />\n$players_str<br />\n".mysql_error());
		}


	// Read skills_table from Database
		if( $member_id )
		{
			$skill_str = 'SELECT * FROM `'.ROSTER_SKILLSTABLE."` WHERE `member_id` = $member_id ORDER BY `skill_order` ASC;";
			$SQL_skill = $wowdb->query($skill_str);

			$skill_rows = mysql_num_rows($SQL_skill);

			if( $skill_rows != 0 )
			{
				for( $n=0; $n<$skill_rows; $n++ )
				{
					$tempData = mysql_fetch_assoc($SQL_skill);

					list($lvl,$maxlvl) = explode( ':', $tempData['skill_level'] );

					$skillsData[$n] = array('type' => $tempData['skill_type'],
																	'name' => $tempData['skill_name'],
																	'level' => $lvl,
																	'max' => $maxlvl);
				}
			}
		}


#--[ FIX SOME STUFF ]------------------------------------------------------

	// Get system slash
		$dir_slash = DIRECTORY_SEPARATOR;

	// Acquire full directory to this file
		$paths_array = pathinfo( $_SERVER['SCRIPT_FILENAME'] );
		$path_dir = realpath( $paths_array['dirname'] ).$dir_slash;

	// Replace slashes in directories with system slashes
		$configData['image_dir'] = str_replace( '/',$dir_slash,$configData['image_dir'] );
		$configData['backg_dir'] = str_replace( '/',$dir_slash,$configData['backg_dir'] );
		$configData['user_dir'] = str_replace( '/',$dir_slash,$configData['user_dir'] );
		$configData['char_dir'] = str_replace( '/',$dir_slash,$configData['char_dir'] );
		$configData['class_dir'] = str_replace( '/',$dir_slash,$configData['class_dir'] );
		$configData['pvplogo_dir'] = str_replace( '/',$dir_slash,$configData['pvplogo_dir'] );
		$configData['font_dir'] = str_replace( '/',$dir_slash,'../../'.$configData['font_dir'] );
		$configData['save_images_dir'] = str_replace( '/',$dir_slash,$path_dir.$configData['save_images_dir'] );


	// Set a full font directory, if selected to do so
		if( $configData['font_fullpath'] )
		{
			$configData['font_dir'] = realpath($configData['font_dir']).$dir_slash;
		}


	// Variable references to DB for quick changing
		$sig_name  = $membersData['name'];
		$sig_exp   = $playersData['exp'];

		$sig_updated  = $playersData['dateupdatedutc'];


	// Get character specific lang if set, or get the roster_lang
		$sig_char_locale = ( empty($playersData['clientLocale']) ? $roster_lang : $playersData['clientLocale'] );

	// Get character class from players table first to avoid translation problems
		$sig_class = ( empty($playersData['class']) ? $membersData['class'] : $playersData['class'] );
		
		
		$sig_guild_title = $membersData['guild_title'];
		$sig_guild_name  = $guildData['guild_name'];
		$sig_server_name = $guildData['server'];

		$sig_race   = $siggen_translate['char'][$sig_char_locale][$playersData['race']];
		$sig_gender = $siggen_translate['char'][$sig_char_locale][$playersData['sex']];

		$sig_pvp_icon = $playersData['RankIcon'];

	// Translate Class Images
		$sig_class_img = $siggen_translate['class'][$sig_char_locale][$sig_class];


	// Check to remove 'http://'
		$sig_site_name = ( $configData['text_sitename_remove'] ? str_replace('http://','',$website_address) : $website_address );


	// Get player level
		$sig_level = $membersData['level'];


	// Check for PvP rank
	// Stored as none for some chars in database for no rank, so check for that and set to blank
		$sig_pvp_rank = ( $playersData['RankName'] == $wordings[$sig_char_locale]['PvPRankNone'] ? '' : $playersData['RankName'] );


#--[ FUNCTIONS ]-----------------------------------------------------------

	// Debug function
	function debugMode( $line,$message,$file,$config,$message2 = '' )
	{
		global $im;
		// Destroy the image
		imageDestroy($im);

		$line -= 1;
		if( $file != '' )
		{
			$file = "[<span style=\"color:green\">$file</span>]";
		}
		$string = "<strong><span style=\"color:red\">Error</span></strong>";
		$string .= " - [<a href=\"./gd_info.php\">GD Info</a>]<br /><br />\n";
		$string .= "<span style=\"color:blue\">Line $line:</span> $message $file\n<br /><br />\n";
		if( $config )
		{
			$string .= "Check the config file\n<br />\n";
		}
		if( $message2 != '' )
		{
			$string .= "$message2\n";
		}
		exit($string);
	}

	// Get and format eXp
	function printXP( $expval )
	{
		list($current, $max) = explode( ':', $expval );
		if( $current > 0 )
		{
			$for_curr = number_format($current);
			$for_max = number_format($max);
			return $for_curr.' of '.$for_max;
		}
		else
		{
			return '';
		}
	}

	// Get eXp percentage for expbar
	function retPerc( $expval,$loc,$len )
	{
		list($current, $max) = explode( ':', $expval );
		if ( $current > 0 )
		{
			$perc = round( ( ($current / $max)* $len ) + $loc, 0);
			return $perc;
		}
		else
		{
			return '0';
		}
	}

	// Function to set color of text
	function setColor( $color,$image,$trans=0 )
	{
		$red = 100;
		$green = 100;
		$blue = 100;

		if( eregi("[#]?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})",$color,$ret) )
		{
			$red = hexdec($ret[1]);
			$green = hexdec($ret[2]);
			$blue = hexdec($ret[3]);
		}

		// Get a transparent color if trans > 0
		if( $trans )
		{
			$color_index = imageColorAllocateAlpha( $image,$red,$green,$blue,$trans );
		} // Get a regular color
		else
		{
			$color_index = imageColorAllocate( $image,$red,$green,$blue );
		}

		return $color_index;
	}

	// Get color function
	function getColor( $color )
	{
		global $configData;
		return $configData[$color];
	}

	// Align text Function
	function textAlignment( $font,$size,$text,$where,$align = 'left' )
	{
		$txtsize = imageTTFBBox( $size,0,$font,$text );		// Gets the points of the image coordinates

		switch ($align)
		{
			case 'right':
				$txt = $txtsize[2];
				break;

			case 'center':
				$txt = $txtsize[2]/2;
				break;

			default:
				$txt = 0;
				break;
		}
		$txtloc = $where-$txt;	// Sets the x coordinate where to print the server name
		return $txtloc;
	}

	// Shadow Text
	function shadowText( $im,$fontsize,$xpos,$ypos,$font,$text,$color )
	{
		$color = setColor( getColor($color),$im );

		$_x = array(-1,-1,-1, 0, 0, 1, 1, 1 );
		$_y = array(-1, 0, 1,-1, 1,-1, 0, 1 );

		for( $n=0; $n<=7; $n++ )
		{
			imageTTFText( $im,$fontsize,0,$xpos+$_x[$n],$ypos+$_y[$n],$color,$font,$text );
		}
	}

	// Function to convert strings to a compatable format
	// This function was copied from http://de3.php.net/manual/de/function.imagettftext.php
	// Under post made by limalopex.eisfux.de
	define('EMPTY_STRING', '');
	function utf8_to_nce( $utf = EMPTY_STRING )
	{
		if($utf == EMPTY_STRING)
		{
			return($utf);
		}

		$max_count = 5;		// flag-bits in $max_mark ( 1111 1000 == 5 times 1)
		$max_mark = 248;	// marker for a (theoretical ;-)) 5-byte-char and mask for a 4-byte-char;

		$html = EMPTY_STRING;
		for($str_pos = 0; $str_pos < strlen($utf); $str_pos++)
		{
			$old_val = ord( $utf{$str_pos} );
			$new_val = 0;

			$utf8_marker = 0;

			// skip non-utf-8-chars
			if( $old_val > 127 )
			{
				$mark = $max_mark;
				for($byte_ctr = $max_count; $byte_ctr > 2; $byte_ctr--)
				{
					// actual byte is utf-8-marker?
					if( ( $old_val & $mark  ) == ( ($mark << 1) & 255 ) )
					{
						$utf8_marker = $byte_ctr - 1;
						break;
					}
					$mark = ($mark << 1) & 255;
				}
			}

			// marker found: collect following bytes
			if($utf8_marker > 1 and isset( $utf{$str_pos + 1} ) )
			{
				$str_off = 0;
				$new_val = $old_val & (127 >> $utf8_marker);
				for($byte_ctr = $utf8_marker; $byte_ctr > 1; $byte_ctr--)
				{
					// check if following chars are UTF8 additional data blocks
					// UTF8 and ord() > 127
					if( (ord($utf{$str_pos + 1}) & 192) == 128 )
					{
						$new_val = $new_val << 6;
						$str_off++;
						// no need for Addition, bitwise OR is sufficient
						// 63: more UTF8-bytes; 0011 1111
						$new_val = $new_val | ( ord( $utf{$str_pos + $str_off} ) & 63 );
					}
					// no UTF8, but ord() > 127
					// nevertheless convert first char to NCE
					else
					{
						$new_val = $old_val;
					}
				}
				// build NCE-Code
				$html .= '&#'.$new_val.';';
				// Skip additional UTF-8-Bytes
				$str_pos = $str_pos + $str_off;
			}
			else
			{
				$html .= chr($old_val);
				$new_val = $old_val;
			}
		}
		return($html);
	}

	// Write Text
	function writeText( $im,$fontsize,$xpos,$ypos,$color,$font,$text,$align,$shadow_color )
	{
		// Get the font
		$font = getFont( $font );
		// Get the color
		$color = setColor( getColor($color),$im );
		// Convert text for display
		$text = utf8_to_nce($text);

		// Correct alignment
		if( $align != 'left' )
		{
			$xpos = textAlignment( $font,$fontsize,$text,$xpos,$align );
		}

		// Create the pseudo-shadow
		if( !empty($shadow_color) )
		{
			shadowText( $im,$fontsize,$xpos,$ypos,$font,$text,$shadow_color );
		}

		// Write the text
		imageTTFText( $im,$fontsize,0,$xpos,$ypos,$color,$font,$text );
	}

	// Get font and font path
	function getFont( $font )
	{
		global $configData;

		$font_file = $configData['font_dir'].$configData[$font];

		// Check to see if SigGen can see the font
		if( file_exists($font_file) )
		{
			return $font_file;
		}
		else
		{
			debugMode( (__LINE__),'Cannot find font',$font_file,0,'Try flipping the "Use Full Path For Fonts" setting in SigGen Config' );
		}
	}

	// GIF image creator
	function makeImageGif( $im,$w,$h,$dither,$save_image = '' )
	{
		// Check dither mode
		if( $dither )
		{
			$dither = TRUE;
		}
		else
		{
			$dither = FALSE;
		}

		// Create a new true color image because we don't want to ruin the original
		$im_gif = imagecreatetruecolor( $w,$h );

		// Copy the original image into the new one
		imagecopy( $im_gif,$im,0,0,0,0,$w,$h );

		// Convert the new image to palette mode
		imagetruecolortopalette( $im_gif,$dither,256 );

		// Check if this needs to be saved
		if( empty($save_image) )
		{
			imageGif( $im_gif );
		}
		else
		{
			imageGif( $im_gif,$save_image );
		}

		// Destroy palette image
		imageDestroy( $im_gif );
	}

	// Funtion to merge images with the main image
	function combineImage( $im,$filename,$debug,$x_loc,$y_loc )
	{
		// Create a new temp image from file
		$im_temp = @imagecreatefrompng( $filename )
			or debugMode( $debug,'Cannot find image',$filename,1,'Check the images directory' );

		// Turn on alpha blending
		imageAlphaBlending( $im_temp,true );

		// Get the image dimentions
		$im_temp_width = imageSX( $im_temp );
		$im_temp_height = imageSY( $im_temp );

		// Copy created image into main image
		imagecopy( $im,$im_temp,$x_loc,$y_loc,0,0,$im_temp_width,$im_temp_height );

		// Destroy the temp image
		if( isset($im_temp) ) imageDestroy( $im_temp );
	}


#--[ IMAGE CREATION ]------------------------------------------------------

	// Create a new, truecolor image
	$im = @imagecreatetruecolor( $configData['main_image_size_w'], $configData['main_image_size_h'] )
		or debugMode( (__LINE__),'Cannot Initialize new GD image stream.','',0,'Make sure you have the latest version of GD2 installed' );

	// Pre-Allocate the colors, hopefully helps with
	// the yellow font errors on servers using TTF libs
	for( $n=1;$n<=10;$n++ )
	{
		setColor( $configData['color'.$n],$im );
	}


	// Choose the character image
	if( $configData['charlogo_disp'] )
	{
		// Check for custom/uploaded image
		$custom_user_img = $configData['image_dir'].$configData['user_dir'].$sig_name.'.png';

		// Set custom character image, based on name in DB
		if( file_exists($custom_user_img) )
		{
			$im_user_file = $custom_user_img;
		}
		// If custom image is not found, check for race and gender
		elseif( !empty($sig_race) )
		{
			// Set race-gender based image
			if( !empty($sig_gender) )
			{
				$im_user_file = $configData['image_dir'].$configData['char_dir'].$sig_race.'-'.$sig_gender.'.png';
			}
			// Set race only image
			else
			{
				$im_user_file = $configData['image_dir'].$configData['char_dir'].$sig_race.'.png';
			}

		}	// Set default character image
		else
		{
			$im_user_file = $configData['image_dir'].$configData['charlogo_default_image'].'.png';
		}
	}


	// Choose the background
	// ====Background Filenames====
	switch ( $configData['backg_data_table'] )
	{
		case 'members':
			$backg['getdatafrom'] = $membersData[$configData['backg_data']];
			break;

		case 'players':
			$backg['getdatafrom'] = $playersData[$configData['backg_data']];
			break;

		default:
			$backg['getdatafrom'] = $playersData['race'];
			break;
	}

	for( $i=1; $i<=12; $i++ )
	{
		if( $configData['backg_translate'] )
		{
			$backg[$siggen_translate['back'][$sig_char_locale][$configData['backg_search_'.$i]]] = $configData['backg_file_'.$i];
		}
		else
		{
			$backg[$configData['backg_search_'.$i]] = $configData['backg_file_'.$i];
		}
	}


	if( $configData['backg_disp'] )
	{
		// Set the default background image first
		$im_back_file = $configData['image_dir'].$configData['backg_dir'].$configData['backg_default_image'].'.png';

		// Check if the default background is forced
		if( !$configData['backg_force_default'] )
		{
			// Check for custom/uploaded image
			$custom_back_img = $configData['image_dir'].$configData['user_dir'].'bk-'.$sig_name.'.png';
			if( file_exists($custom_back_img) )
			{
				$im_back_file = $custom_back_img;
			}
			// Try setting background from config
			elseif( !empty($backg['getdatafrom']) )
			{
				$selected_back_img = $configData['image_dir'].$configData['backg_dir'].$backg[$backg['getdatafrom']].'.png';
				if( file_exists($selected_back_img) )
				{
					$im_back_file = $selected_back_img;
				}
			}
		}
	}


	// Colorfill the background?
	if( $configData['backg_fill'] )
	{
		imagefill($im,0,0,setColor( getColor($configData['backg_fill_color']),$im ) );
	}


	// Get the image layer order
	$layer_order = explode(':',$configData['image_order']);

	// Place images based on layer order
	foreach( $layer_order as $o )
	{
		// Place the background
		if( $o == 'back' && $configData['backg_disp'] && file_exists($im_back_file) )
		{
			combineImage( $im,$im_back_file,(__LINE__),0,0 );
		}


		// Place the character image
		if( $o == 'char' && $configData['charlogo_disp'] && file_exists($im_user_file) )
		{
			combineImage( $im,$im_user_file,(__LINE__),$configData['charlogo_loc_x'],$configData['charlogo_loc_y'] );
		}

		// Place the colored frames
		if( $o == 'frames' && !empty($configData['frames_image']) )
		{
			$im_frame_file = $configData['image_dir'].$configData['frames_image'].'.png';
			if( file_exists($im_frame_file) )
			{
				combineImage( $im,$im_frame_file,(__LINE__),0,0 );
			}
		}

		// Place the outside border
		if( $o == 'border' && !empty($configData['outside_border_image']) )
		{
			$im_bdr_file = $configData['image_dir'].$configData['outside_border_image'].'.png';
			if( file_exists($im_bdr_file) )
			{
				combineImage( $im,$im_bdr_file,(__LINE__),0,0 );
			}
		}

		// Place HonorRank logo
		if( $o == 'pvp' && $configData['pvplogo_disp'] && !empty($sig_pvp_icon) )
		{
			$im_pvp_file = $configData['image_dir'].$configData['pvplogo_dir'].substr($sig_pvp_icon,24,10).'.png';
			if( file_exists($im_pvp_file) )
			{
				combineImage( $im,$im_pvp_file,(__LINE__),$configData['pvplogo_loc_x'],$configData['pvplogo_loc_y'] );
			}
		}

		// Place Class image
		if( $o == 'class' && $configData['class_img_disp'] && !empty($sig_class_img) )
		{
			$im_class_file = $configData['image_dir'].$configData['class_dir'].$sig_class_img.'.png';
			if( file_exists($im_class_file) )
			{
				combineImage( $im,$im_class_file,(__LINE__),$configData['class_img_loc_x'],$configData['class_img_loc_y'] );
			}
		}

		// Place the level bubble
		if( $o == 'lvl' && $configData['lvl_disp'] && !empty($configData['lvl_image']) )
		{
			$im_lvl_file = $configData['image_dir'].$configData['lvl_image'].'.png';
			if( file_exists($im_lvl_file) )
			{
				combineImage( $im,$im_lvl_file,(__LINE__),$configData['lvl_loc_x'],$configData['lvl_loc_y'] );
			}

			// Get the text locations based on the image location
			$lvl_text_loc_x = $configData['lvl_text_loc_x']+$configData['lvl_loc_x'];
			$lvl_text_loc_y = $configData['lvl_text_loc_y']+$configData['lvl_loc_y'];

			// Print the level as text
			writeText( $im,$configData['lvl_font_size'],$lvl_text_loc_x,$lvl_text_loc_y,$configData['lvl_font_color'],$configData['lvl_font_name'],$sig_level,'center',$configData['lvl_text_shadow'] );
		}
	}


#--[ EXP BAR PLACEMENT ]---------------------------------------------------

	if( $configData['expbar_disp'] && !empty($sig_exp) )
	{
		// Get the end locations for the eXp bar
		$x_end = $configData['expbar_loc_x']+$configData['expbar_length'];
		$y_end = $configData['expbar_loc_y']+$configData['expbar_height'];

		// Draw a full eXP bar
		if( $sig_level == $configData['expbar_max_level'] && $configData['expbar_max_disp'] && !$configData['expbar_max_hidden'] )
		{
			// Fix eXp bar text alignment
			if( $configData['expbar_align_max'] == 'center' )
			{
				$exp_text_loc = $x_end-($configData['expbar_length']/2);
			}
			elseif( $configData['expbar_align_max'] == 'right' )
			{
				$exp_text_loc = $x_end-3;
			}
			else
			{
				$exp_text_loc = $configData['expbar_loc_x']+3;
			}

			// The eXP bar (outside border)
			if( $configData['expbar_disp_bdr'] )
			{
				imageRectangle( $im,$configData['expbar_loc_x']-1,$configData['expbar_loc_y']-1,$x_end+1,$y_end+1,setColor( getColor($configData['expbar_color_border']),$im,$configData['expbar_trans_border'] ) );
			}

			// The eXP bar (inside box)
			if( $configData['expbar_disp_inside'] )
			{
				imageFilledRectangle( $im,$configData['expbar_loc_x'],$configData['expbar_loc_y'],$x_end,$y_end,setColor( getColor($configData['expbar_color_inside']),$im,$configData['expbar_trans_inside'] ) );
			}

			// The progress bar
			imageFilledRectangle( $im,$configData['expbar_loc_x'],$configData['expbar_loc_y'],$x_end,$y_end,setColor( getColor($configData['expbar_color_maxbar']),$im,$configData['expbar_trans_maxbar'] ) );

			// eXpbar text
			if($configData['expbar_disp_text'])
			{
				writeText( $im,$configData['expbar_font_size'],$exp_text_loc,$y_end-1,$configData['expbar_font_color'],$configData['expbar_font_name'],$configData['expbar_max_string'],$configData['expbar_align_max'],$configData['expbar_text_shadow'] );
			}

		}	// Draw the standard eXP bar
		elseif( $sig_level < $configData['expbar_max_level'] || !$configData['expbar_max_hidden'] )
		{
			// Variables to get and hold eXP bar data
			$outexp = printXP($sig_exp);
			$outperc = retPerc($sig_exp,$configData['expbar_loc_x'],$configData['expbar_length']);

			// Fix eXp bar text alignment
			if( $configData['expbar_align'] == 'center' )
			{
				$exp_text_loc = $x_end-($configData['expbar_length']/2);
			}
			elseif( $configData['expbar_align'] == 'right' )
			{
				$exp_text_loc = $x_end-3;
			}
			else
			{
				$exp_text_loc = $configData['expbar_loc_x']+3;
			}

			// The eXP bar (outside border)
			if( $configData['expbar_disp_bdr'] )
			{
				imageRectangle( $im,$configData['expbar_loc_x']-1,$configData['expbar_loc_y']-1,$x_end+1,$y_end+1,setColor( getColor($configData['expbar_color_border']),$im,$configData['expbar_trans_border'] ) );
			}

			// The eXP bar (inside box)
			if( $configData['expbar_disp_inside'] )
			{
				imageFilledRectangle( $im,$configData['expbar_loc_x'],$configData['expbar_loc_y'],$x_end,$y_end,setColor( getColor($configData['expbar_color_inside']),$im,$configData['expbar_trans_inside'] ) );
			}

			// The progress bar
			imageFilledRectangle( $im,$configData['expbar_loc_x'],$configData['expbar_loc_y'],$outperc,$y_end,setColor( getColor($configData['expbar_color_bar']),$im,$configData['expbar_trans_bar'] ) );

			// eXpbar text
			if( $configData['expbar_disp_text'] )
			{
				writeText( $im,$configData['expbar_font_size'],$exp_text_loc,$y_end-1,$configData['expbar_font_color'],$configData['expbar_font_name'],$configData['expbar_string_before'].$outexp.$configData['expbar_string_after'],$configData['expbar_align'],$configData['expbar_text_shadow'] );
			}
		}
	}


#--[ PLACE DYNAMIC TEXT IN THE IMAGE ]-------------------------------------

	// Place the Character's Name
	if( $configData['text_name_disp'] && !empty($sig_name) )
	{
		writeText( $im,$configData['text_name_font_size'],$configData['text_name_loc_x'],$configData['text_name_loc_y'],$configData['text_name_font_color'],$configData['text_name_font_name'],$sig_name,$configData['text_name_align'],$configData['text_name_shadow'] );
	}

	// Place the Character's Honor Rank
	if( $configData['text_honor_disp'] && !empty($sig_pvp_rank) )
	{
		writeText( $im,$configData['text_honor_font_size'],$configData['text_honor_loc_x'],$configData['text_honor_loc_y'],$configData['text_honor_font_color'],$configData['text_honor_font_name'],$sig_pvp_rank,$configData['text_honor_align'],$configData['text_honor_shadow'] );
	}

	// Place the Character's Class
	if( $configData['text_class_disp'] && !empty($sig_class) )
	{
		writeText( $im,$configData['text_class_font_size'],$configData['text_class_loc_x'],$configData['text_class_loc_y'],$configData['text_class_font_color'],$configData['text_class_font_name'],$sig_class,$configData['text_class_align'],$configData['text_class_shadow'] );
	}

	// Place the Character's Guild Title Name
	if( $configData['text_guildtitle_disp'] && !empty($sig_guild_title) )
	{
		writeText( $im,$configData['text_guildtitle_font_size'],$configData['text_guildtitle_loc_x'],$configData['text_guildtitle_loc_y'],$configData['text_guildtitle_font_color'],$configData['text_guildtitle_font_name'],$sig_guild_title,$configData['text_guildtitle_align'],$configData['text_guildtitle_shadow'] );
	}

	// Place the Guild Name
	if( $configData['text_guildname_disp'] && !empty($sig_guild_name) )
	{
		writeText( $im,$configData['text_guildname_font_size'],$configData['text_guildname_loc_x'],$configData['text_guildname_loc_y'],$configData['text_guildname_font_color'],$configData['text_guildname_font_name'],$sig_guild_name,$configData['text_guildname_align'],$configData['text_guildname_shadow'] );
	}

	// Place the Server Name
	if( $configData['text_servername_disp'] && !empty($sig_server_name) )
	{
		writeText( $im,$configData['text_servername_font_size'],$configData['text_servername_loc_x'],$configData['text_servername_loc_y'],$configData['text_servername_font_color'],$configData['text_servername_font_name'],$sig_server_name,$configData['text_servername_align'],$configData['text_servername_shadow'] );
	}

	// Place the Site Name
	if( $configData['text_sitename_disp'] && !empty($sig_site_name) )
	{
		writeText( $im,$configData['text_sitename_font_size'],$configData['text_sitename_loc_x'],$configData['text_sitename_loc_y'],$configData['text_sitename_font_color'],$configData['text_sitename_font_name'],$sig_site_name,$configData['text_sitename_align'],$configData['text_sitename_shadow'] );
	}

	// Place Custom Text
	if( $configData['text_custom_disp'] && !empty($configData['text_custom_text']) )
	{
		writeText( $im,$configData['text_custom_font_size'],$configData['text_custom_loc_x'],$configData['text_custom_loc_y'],$configData['text_custom_font_color'],$configData['text_custom_font_name'],$configData['text_custom_text'],$configData['text_custom_align'],$configData['text_custom_shadow'] );
	}


#--[ PROFESSIONS AND SECONDARY SKILLS ]------------------------------------

	if( $skill_rows != 0 )
	{
		$pos['desc']  = $configData['skills_desc_loc_y'];
		$pos['level'] = $configData['skills_level_loc_y'];

		// Display Primary skills
		if( $configData['skills_disp_primary'] )
		{
			foreach( $skillsData as $skill )
			{
				// Print only professions where the max level does not equal 1
				if( $skill['type'] == $wordings[$sig_char_locale]['professions'] && $skill['max'] != '1' )
				{
					// Print Skill description
					if( $configData['skills_disp_desc'] )
					{
						$desc = $skill['name'];
						// Shorten long strings based on max length in config
						if( strlen($desc) > $configData['skills_desc_length'] )
						{
							$desc = trim( substr($desc,0,$configData['skills_desc_length']) ).'.';
						}
						writeText( $im,$configData['skills_font_size'],$configData['skills_desc_loc_x'],$pos['desc'],$configData['skills_font_color'],$configData['skills_font_name'],$desc,$configData['skills_align_desc'],$configData['skills_shadow'] );
					}

					// Print Skill level
					if( $configData['skills_disp_level'] )
					{
						// Print max level if turned on in config
						if( $configData['skills_disp_levelmax'] )
						{
							$level = $skill['level'].':'.$skill['max'];
						}
						else
						{
							$level = $skill['level'];
						}
						writeText( $im,$configData['skills_font_size'],$configData['skills_level_loc_x'],$pos['level'],$configData['skills_font_color'],$configData['skills_font_name'],$level,$configData['skills_align_level'],$configData['skills_shadow'] );
					}

					// Move the line position
					$pos['desc']  += $configData['skills_desc_linespace'];
					$pos['level'] += $configData['skills_level_linespace'];
				}
			}

			// Place a gap based on config
			$pos['desc']  += $configData['skills_gap'];
			$pos['level'] += $configData['skills_gap'];
		}

		// Display Secondary skills
		if( $configData['skills_disp_secondary'] )
		{
			foreach( $skillsData as $skill )
			{
				// Print only secondary skills where the max level does not equal 1
				if( $skill['type'] == $wordings[$sig_char_locale]['secondary'] && $skill['max'] != '1' )
				{
					// Print Skill description
					if( $configData['skills_disp_desc'] )
					{
						$desc = $skill['name'];
						// Shorten long strings based on max length in config
						if( strlen($desc) > $configData['skills_desc_length'] )
						{
							$desc = trim( substr($desc,0,$configData['skills_desc_length']) ).'.';
						}
						writeText( $im,$configData['skills_font_size'],$configData['skills_desc_loc_x'],$pos['desc'],$configData['skills_font_color'],$configData['skills_font_name'],$desc,$configData['skills_align_desc'],$configData['skills_shadow'] );
					}

					// Print Skill level
					if( $configData['skills_disp_level'] )
					{
						// Print max level if turned on in config
						if( $configData['skills_disp_levelmax'] )
						{
							$level = $skill['level'].':'.$skill['max'];
						}
						else
						{
							$level = $skill['level'];
						}
						writeText( $im,$configData['skills_font_size'],$configData['skills_level_loc_x'],$pos['level'],$configData['skills_font_color'],$configData['skills_font_name'],$level,$configData['skills_align_level'],$configData['skills_shadow'] );
					}

					// Move the line position
					$pos['desc']  += $configData['skills_desc_linespace'];
					$pos['level'] += $configData['skills_level_linespace'];
				}
			}

			// Place a gap based on config
			$pos['desc']  += $configData['skills_gap'];
			$pos['level'] += $configData['skills_gap'];
		}

		// Display Mount Info
		if( $configData['skills_disp_mount'] )
		{
			foreach( $skillsData as $skill )
			{
				// Print only secondary skills where the max level equalls 1
				if( $skill['type'] == $wordings[$sig_char_locale]['secondary'] && $skill['max'] == '1' )
				{
					$desc = $skill['name'];
					// Shorten long strings based on max length in config
					if( strlen($desc) > $configData['skills_desc_length_mount'] )
					{
						$desc = trim( substr($desc,0,$configData['skills_desc_length_mount']) ).'.';
					}
					writeText( $im,$configData['skills_font_size'],$configData['skills_desc_loc_x'],$pos['desc'],$configData['skills_font_color'],$configData['skills_font_name'],$desc,$configData['skills_align_desc'],$configData['skills_shadow'] );

					// Move the line position
					$pos['desc']  += $configData['skills_desc_linespace'];
					$pos['level'] += $configData['skills_level_linespace'];
				}
			}
		}
	}


#--[ FINALIZE AND CLOSE ]--------------------------------------------------

	// Set to output image by default
	$make_image = 1;


	// Save mode Web request over-ride
	if( isset($sig_saveonly) )
	{
		$configData['save_only_mode'] = $sig_saveonly;
		$configData['save_images'] = $sig_saveonly;
	}


	// Save the image to the server?
	if( $configData['save_images'] && $sig_no_data != $sig_name )
	{
		$save_dir = $configData['save_images_dir'];
		$saved_image = $save_dir.$configData['save_prefix'].$sig_name.$configData['save_suffix'].'.'.$configData['save_images_format'];

		if( file_exists($save_dir) )
		{
			if( is_writable($save_dir) )
			{
				switch ( $configData['save_images_format'] )
				{
					case 'gif':
						makeImageGif( $im,$configData['main_image_size_w'],$configData['main_image_size_h'],$configData['gif_dither'],$saved_image );
						break;

					case 'jpg':
						imageJpeg( $im,$saved_image,$configData['image_quality'] );
						break;

					case 'png':
						imagePng( $im,$saved_image );
						break;
				}

				if( $configData['save_only_mode'] )
				{
					echo "<!-- Image Saved: [$saved_image] -->\n";
					$make_image = 0;	// Don't output an image
				}
			}
			else
			{
				debugMode( (__LINE__),'Cannot save image to the server. "Saved Images Folder" was not writable',$save_dir,0,'Check SigGen Config settings. Also try manually setting write access' );
			}
		}
		else
		{
			debugMode( (__LINE__),'Saved Images Folder was not found',$save_dir,0,'Check SigGen Config settings' );
		}
	}


#--[ OUTPUT IMAGE ]--------------------------------------------------------

	if( $make_image )
	{
		// Set the header
		header( 'Content-type: image/'.$configData['image_type'] );

		// e-tag'ing
		// Web request over-ride
		if( isset($sig_etag_mode) )
		{
			$configData['etag_cache'] = $sig_etag_mode;
		}

		if( $configData['etag_cache'] )
		{
			$hash = md5( $member_id.$sig_updated );
		  if ( ereg($hash, $_SERVER['HTTP_IF_NONE_MATCH']) )
		  {
				header( 'HTTP/1.1 304 Not Modified' );
				exit(0);
			}
			else
				header( "ETag: \"{$hash}\"" );
		}

		switch ( $configData['image_type'] )
		{
			case 'gif':
				makeImageGif( $im,$configData['main_image_size_w'],$configData['main_image_size_h'],$configData['gif_dither'] );
				break;

			case 'jpg':
				imageJpeg( $im,'',$configData['image_quality'] );
				break;

			case 'png':
				imagePng( $im );
				break;
		}
	}


#--[ FREE MEMORY ]---------------------------------------------------------

	if( isset($im) ) imageDestroy( $im );
?>