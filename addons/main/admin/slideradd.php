<?php

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if (isset($_POST['op']) && $_POST['op'] == 'upload')
{
	$target_path = $addon['dir'] .'images'. DIR_SEP . basename( $_FILES['b_image']['name']);
	if(move_uploaded_file($_FILES['b_image']['tmp_name'], $target_path))
	{

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
