<?php

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}
function image_resize($src, $dst, $width, $height, $crop=0)
{

	if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

	$type = strtolower(substr(strrchr($src,"."),1));
	if($type == 'jpeg') $type = 'jpg';
	switch($type)
	{
		case 'bmp': $img = imagecreatefromwbmp($src); break;
		case 'gif': $img = imagecreatefromgif($src); break;
		case 'jpg': $img = imagecreatefromjpeg($src); break;
		case 'png': $img = imagecreatefrompng($src); break;
		default : return "Unsupported picture type!";
	}

	// resize
	if($crop)
	{
		if($w < $width or $h < $height) return "Picture is too small!";
		$ratio = max($width/$w, $height/$h);
		$h = $height / $ratio;
		$x = ($w - $width / $ratio) / 2;
		$w = $width / $ratio;
	}
	else
	{
		if($w < $width and $h < $height) return "Picture is too small!";
		$ratio = min($width/$w, $height/$h);
		$width = $w * $ratio;
		$height = $h * $ratio;
		$x = 0;
	}

	$new = imagecreatetruecolor($width, $height);

	// preserve transparency
	if($type == "gif" or $type == "png")
	{
		imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
		imagealphablending($new, false);
		imagesavealpha($new, true);
	}

	imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

	switch($type)
	{
		case 'bmp': imagewbmp($new, $dst); break;
		case 'gif': imagegif($new, $dst); break;
		case 'jpg': imagejpeg($new, $dst); break;
		case 'png': imagepng($new, $dst); break;
	}
	return true;
}

if (isset($_POST['op']) && $_POST['op'] == 'upload')
{
	$target_path = $addon['dir'] .'images'. DIR_SEP . basename( $_FILES['b_image']['name']);
	$path = $addon['dir'] .'images'. DIR_SEP;
	if(move_uploaded_file($_FILES['b_image']['tmp_name'], $target_path))
	{
		//$extension = substr(basename( $_FILES['b_image']['name']), strrpos(basename( $_FILES['b_image']['name']), '.')+1);
		$filename = basename( $_FILES['b_image']['name']);
		image_resize($target_path, $path."thumb-".$filename, 100, 47, 1);
		

		$query = "INSERT INTO `" . $roster->db->table('slider', $addon['basename']) . "` SET "
			. "`b_title` = '" . $_POST['b_title'] . "', "
			. "`b_desc` = '" . $_POST['b_desc'] . "', "
			. "`b_url` = '" . $_POST['b_url'] . "', "
			. "`b_image` = '" . $_FILES['b_image']['name'] . "';";

		if( $roster->db->query($query) )
		{
			$roster->set_message(sprintf($roster->locale->act['slider_add_success'], basename( $_FILES['b_image']['name'])));
		}
		else
		{
			unlink($target_path);
			$roster->set_message($roster->locale->act['slider_error_db'], '', 'error');
			$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
		}
	}
	else
	{
		$roster->set_message(sprintf($roster->locale->act['slider_add_success'], $target_path), $roster->locale->act['b_add'], 'error');
	}
	//*/
}



$roster->tpl->set_handle('slider',$addon['basename'] . '/admin/slideradd.html');

$body .= $roster->tpl->fetch('slider');

/**
 * Make our menu from the config api
 */
// ----[ Set the tablename and create the config class ]----
include(ROSTER_LIB . 'config.lib.php');
$config = new roster_config( $roster->db->table('addon_config'), '`addon_id` = "' . $addon['addon_id'] . '"' );

// ----[ Get configuration data ]---------------------------
$config->getConfigData();

// ----[ Build the page items using lib functions ]---------
$menu .= $config->buildConfigMenu('rostercp-addon-' . $addon['basename']);
