<?php
	
if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if (isset($_POST) && $_POST['op'] == 'upload')
{
	$target_path = ROSTER_BASE.$addon['image_path'].basename( $_FILES['b_image']['name']);
	if(move_uploaded_file($_FILES['b_image']['tmp_name'], $target_path))
	{
		$roster->set_message("The file ".basename( $_FILES['b_image']['name'])." has been uploaded <br> To the banners Directory");
		
		$query = "INSERT INTO `" . $roster->db->table('banners',$addon['basename']) . "` SET "
			. "`b_title` = '" . $_POST['b_title'] . "', "
			. "`b_desc` = '" . $_POST['b_desc'] . "', "
			. "`b_url` = '" . $_POST['b_url'] . "', "
			. "`b_image` = '" . $_FILES['b_image']['name'] . "';";

		if( $roster->db->query($query) )
		{
			$roster->set_message($roster->locale->act['banner_add_success']);
		}
		else
		{
			$roster->set_message('There was a DB error while adding the article.', '', 'error');
			$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
		}
	}
	else
	{
		$roster->set_message( ' your upload was Not completed dir "'.$target_path.'" not found', 'Add Banner', 'error' );
	}
	//*/
}



$roster->tpl->set_handle('banners',$addon['basename'] . '/banneradd.html');

$body .= $roster->tpl->fetch('banners');

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